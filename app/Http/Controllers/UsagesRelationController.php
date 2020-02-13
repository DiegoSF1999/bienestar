<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\usages_relation;
use App\users;
use Illuminate\Support\Facades\DB;

class UsagesRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.date, SUM(usages_relation.used_time) as used_time from usages_relation where user_id = ' . $user->id . ' and app_id = ' . $request->app_id . ' group by usages_relation.date DESC');

        return $data;
    }

    public function get_days_use(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.date, SUM(usages_relation.used_time) as used_time from usages_relation where user_id = ' . $user->id . ' and app_id = ' . $request->app_id . ' group by usages_relation.date DESC
        ');

        return $data;
    }

    public function get_total_use(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.app_id, SUM(usages_relation.used_time) as used_time from usages_relation where user_id = ' . $user->id . ' group by usages_relation.app_id');

        return $data;
    }

    public function get_average_use(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.app_id, AVG(usages_relation.used_time) as used_time from usages_relation where user_id = ' . $user->id . ' group by usages_relation.app_id');

        return $data;
    }

    public function get_stadistics(Request $request)
    {
        $usages_inv = new usages_relation();

        return $usages_inv->get_stadistics($request);
    }



    public function get_today_use(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);

        $data = DB::select('select usages_relation.app_id, SUM(usages_relation.used_time) as used_time from usages_relation, apps where user_id = ' 
        . $user->id . ' and usages_relation.date = "' . $request->date . '" and usages_relation.app_id = apps.id group by usages_relation.app_id
        ');

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usages_inv = new usages_relation();

        return $usages_inv->register($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
