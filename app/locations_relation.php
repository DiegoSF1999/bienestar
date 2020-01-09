<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;

class locations_relation extends Pivot
{
    protected $table = 'locations_relation';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'app_id', 'latitude', 'altitude', 'open_close'];






    public function register(Request $request)
    {
        try {
            $location = new self();
            $users_inv = new users();

            $location->user_id = $users_inv->get_logged_user($request)->id;
            $location->app_id = $request->app_id;
            $location->latitude = $request->latitude;
            $location->altitude = $request->altitude;
            $location->open_close = $request->open_close;
            $location->save();
            return 200;    
            
            
       } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
       }
       
    }
}
