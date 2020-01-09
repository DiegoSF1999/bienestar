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

        //SELECT usages_relation.id, usages_relation.user_id, usages_relation.app_id, usages_relation.used_time, usages_relation.date, SUM(usages_relation.used_time) AS "total_used_time" FROM usages_relation GROUP BY usages_relation.id, usages_relation.user_id, usages_relation.app_id, usages_relation.used_time, usages_relation.date

        $data = DB::select('SELECT usages_relation.id, user_id, app_id, used_time, usages_relation.date, SUM(used_time) AS "total_used_time" FROM usages_relation, users WHERE users.id = "' . $user->email . '" GROUP BY usages_relation.id, user_id, app_id, used_time, usages_relation.date');

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
