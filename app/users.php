<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class users extends Model
{
    protected $table = 'users';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'password', 'changed'];

    public function usages()
    {
        return $this->belongsToMany('App\apps', 'usages_relation', 'user_id', 'app_id')->using('App\usages_relation')->withPivot([ 'used_time', 'date', 'created_at', 'updated_at']);
    }

    public function restrictions()
    {
        return $this->belongsToMany('App\apps', 'restrictions_relation', 'user_id', 'app_id')->using('App\restrictions_relation')
        ->withPivot(['maximun_time', 'start_hour', 'finish_hour', 'created_at', 'updated_at']);
    }
    public function locations()
    {
        return $this->belongsToMany('App\apps', 'locations_relation', 'user_id', 'app_id')->using('App\locations_relation')
        ->withPivot([ 'latitude', 'altitude', 'open_close', 'created_at', 'updated_at']);
    }

    public function has()
    {
        return $this->belongsToMany('App\apps', 'has_relation', 'user_id', 'app_id');
    }

    public function register(Request $request)
    {
        try {
            $user = new self();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->changed = 0;
            $user->save();
    
            return $this->getTokenFromUser($user);        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "email already used"
            ], 401);
       }
    }
    public function login(Request $request)
    {
        try {
            $user = self::where('email', $request->email)->first();
           if (Hash::check($request->password, $user->password))
           {
            return $this->getTokenFromUser($user);
           } else {
            return response()->json([
                'message' => "wrong data"
            ], 401);
           }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
        }
        
    }

    public function recover_password(Request $request)
    {
        try {
            $user = users::where('email', $request->email)->first();
            if ($user == null) {
                return response()->json([
                    'message' => "email not found"
                ], 401);
            } else  {
                $new_password = str_random(8);
                $hashed_random_password = Hash::make($new_password);
                users::where('id', $user->id)->update(['password' => $hashed_random_password]);

                $to      = 'diego_sanchez-brunete_apps1ma1819@cev.com'; //$user->email;
                $subject = 'password reset bienestapp';
                $message = 'the new password is: ' . $new_password;
                $headers = 'From: diego_sanchez-brunete_apps1ma1819@cev.com' . "\r\n" .
                    'Reply-To: ' . $to . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);

                return $new_password;

            }

           
       } catch (\Throwable $th) {
            return response()->json([
                'message' => "email not found"
            ], 401);
        }
        


    }
    
    private function getTokenFromUser($user)
    {
        $token_inv = new Token();
        $token = $token_inv->encode_token($user->email, $user->changed);
        return response()->json([
           'token' => $token
        ], 200);
    }

    public function get_logged_user(Request $request)
    {
        $token_inv = new Token();
        $coded_token = $request->header('token');
        $decoded_token = $token_inv->decode_token($coded_token);
        $user = users::where('email', $decoded_token[0])->first();
        return $user;
    }


    public function link_user_app(Request $request)
    {

        try {
            
        $user = $this->get_logged_user($request);

        $user->has()->attach($request->app_id);

        return 200;
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
        }
   

    }

    public function show_user_apps(Request $request)
    {
         
        $user = $this->get_logged_user($request);

        $data = DB::select('select * from apps, has_relation where has_relation.user_id = ' . $user->id);

        return $data;
 

    }



}
