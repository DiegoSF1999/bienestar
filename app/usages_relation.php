<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use App\users;
use Illuminate\Support\Facades\DB;

class usages_relation extends Pivot
{
    protected $table = 'usages_relation';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'app_id', 'used_time', 'date'];


    public function register($user_id, $app_id, $used_time, $date)
    {
        try {
            $usage = new self();
            $usage->user_id = $user_id;
            $usage->app_id = $app_id;
            $usage->used_time = $used_time;
            $usage->date = $date;
            $usage->save();
            return 200;    
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "wrong data"
            ], 401);
       }
       
    }


    public function get_stadistics(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.app_id from usages_relation where user_id = ' . $user->id . ' group by usages_relation.app_id ASC, usages_relation.date DESC');

        $ids = array();

        for ($i=0; $i < count($data); $i++) { 
        
            array_push($ids, $data[$i]->app_id);
        }
        
        $single_ids = array();

        for ($i=0; $i < count($ids); $i++) { 
           
            if (in_array($ids[$i], $single_ids)) {
                
            } else {

                array_push($single_ids, $ids[$i]);
            }


        }

        $days = array();
        $numbers_day = 0;

        for ($i=0; $i < count($single_ids); $i++) { 
            
            for ($o=0; $o < count($ids); $o++) { 
                
                if ($ids[$o] == $single_ids[$i]) {
                    $numbers_day += 1;
                }


            }

            array_push($days, $numbers_day);

            $numbers_day = 0;

        }
        
        $both = array();

        array_push($both, $single_ids);
        array_push($both, $days);

        return response()->json($both, 401);
       
    }


}
