<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Token;
use Illuminate\Support\Facades\Hash;

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





}
