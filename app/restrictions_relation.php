<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;

class restrictions_relation extends Pivot
{
    protected $table = 'restrictions_relation';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'app_id', 'maximun_time', 'start_hour', 'finish_hour'];




    public function register(Request $request)
    {
        try {
            $restriction = new self();
            $users_inv = new users();

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
       
    }

}

