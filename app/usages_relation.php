<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use App\users;

class usages_relation extends Pivot
{
    protected $table = 'usages_relation';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'app_id', 'used_time', 'date'];


    public function register(Request $request)
    {
        try {
            $usage = new self();
            $users_inv = new users();

            $usage->user_id = $users_inv->get_logged_user($request)->id;
            $usage->app_id = $request->app_id;
            $usage->used_time = $request->used_time;
            $usage->date = $request->date;
            $usage->save();
            return 200;    
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
       }
       
    }

}
