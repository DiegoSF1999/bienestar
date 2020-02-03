<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\users;

class restrictions_relation extends Pivot
{
    protected $table = 'restrictions_relation';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'app_id', 'maximun_time', 'start_hour', 'finish_hour'];




    public function register(Request $request)
    {
        $restriction = new self();
        $users_inv = new users();
        

        $data = DB::select('select * from restrictions_relation where user_id = ' . $users_inv->get_logged_user($request)->id . ' and app_id = ' . $request->app_id);

        if ($data == null)
        {
            try {

                $restriction->user_id = $users_inv->get_logged_user($request)->id;
                $restriction->app_id = $request->app_id;
                $restriction->maximun_time = $request->maximun_time;
                $restriction->start_hour = $request->start_hour;
                $restriction->finish_hour = $request->finish_hour;
                $restriction->save();
                return 200;    
                
                
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => "wrong data"
                ], 401);
           }
        } else {
            return response()->json([
                'message' => "restriction already exists"
            ], 401);
        } 


        
       
    }

    public function remove(Request $request) {

        $users_inv = new users();

        try {

            DB::delete('delete from restrictions_relation where user_id = ' . $users_inv->get_logged_user($request)->id . ' and app_id = ' . $request->app_id);
            
            return 200;    
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
       }
    

    }


    public function change(Request $request) {

        $users_inv = new users();

       // try {

            DB::update('update restrictions_relation set maximun_time = ' . $request->maximun_time . ', start_hour = ' . $request->start_hour . ', finish_hour = ' . $request->finish_hour . ' where user_id = ' . $users_inv->get_logged_user($request)->id . ' and app_id = ' . $request->app_id);

            return 200;
            
            
        /*} catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
       }*/
    

    }

}

