<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\apps;
use App\locations_relation;
use App\usages_relation;

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

            $apps_info = $this->readCSVinfo($request, $user->id);
    
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
          //  json_encode($this->getTokenFromUser($user));
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

    function readCSVinfo(Request $request, $user_id)
    {
        $CSVfile = $request->file;
        $longitudDeLinea = 100;
        $delimitador = ",";
        $gestor = fopen($CSVfile, "r");
        if (!$gestor) {
            exit("No se puede abrir el archivo $CSVfile");
        }

        $name[] = [];
        $time[] = [];
        $status[] = [];
        $latitude[] = [];
        $longitude[] = [];
    

        fgetcsv($gestor);
        while ($fila = fgetcsv($gestor, $longitudDeLinea, $delimitador)) {

           
            array_push($time, $fila[0]);
            array_push($name, $fila[1]);
            array_push($status, $fila[2]);
            array_push($latitude, $fila[3]);
            array_push($longitude, $fila[4]);

       
        }
        fclose($gestor);
        
        $apps = new apps();
        $locations = new locations_relation();
        $usages = new usages_relation();

        for ($i=1; $i < count($time); $i++) { 

            $apps->register($name[$i]);            

            $data = DB::select('select id from apps where apps.name = "'. $name[$i] . '"' );
            $app_id = $data[0]->id;

            $this->link_user_app($user_id, $app_id);

                
            $locations->register($user_id, $app_id, $latitude[$i], $longitude[$i], $status[$i]);

            
            if ($status[$i] != "opens") {

                $tiempo1 = strtotime($time[$i-1]);
                $tiempo2 = strtotime($time[$i]);

                $tiempodef = $tiempo2 - $tiempo1;

                $tiempodef *= 1000;

                $usages->register($user_id, $app_id, $tiempodef, $time[$i-1]);

               
            }       


        }



        return 200;
    }



    public function recover_password(Request $request)
    {
        try {
            $user = users::where('email', $request->email)->first();
            if ($user == null) {
                return 401;
            } else  {
                $new_password = str_random(8);
                $hashed_random_password = Hash::make($new_password);
                users::where('id', $user->id)->update(['password' => $hashed_random_password]);
                users::where('id', $user->id)->update(['changed' => ($user->changed + 1)]);

                $to      = $user->email;
                $subject = 'password reset bienestapp';
                $message = 'the new password is: ' . $new_password;
                $headers = 'From: alex_rodriguezrealnofake@cev.com' . "\r\n" .
                    'Reply-To: ' . $to . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);

                return 200;

            }

           
       } catch (\Throwable $th) {
            return 401;
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


    public function link_user_app($user_id, $app_id)
    {

       // try {

        $data = DB::select('select * from has_relation where has_relation.user_id = ' . $user_id . ' and has_relation.app_id = ' . $app_id);

        if ($data == null) {

            self::find($user_id)->has()->attach($app_id);

            return 200;
        } else {
            return response(203, 203);
        }       
       /* } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
        }*/
   

    }

    public function show_user_apps(Request $request)
    {
         
        $user = $this->get_logged_user($request);

        $data = DB::select('select apps.* from apps, has_relation where has_relation.user_id = ' . $user->id . ' and has_relation.app_id = apps.id');

        return $data;
 

    }



}
