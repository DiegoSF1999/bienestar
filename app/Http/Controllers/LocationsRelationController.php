<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\locations_relation;
use App\users;
use Illuminate\Support\Facades\DB;

class LocationsRelationController extends Controller
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

        $data = DB::select('select * from locations_relation where user_id = ' . $user->id . ' and open_close = 0');

        return $data;
    }

    public function get_last_location(Request $request)
    {
        $users_inv = new users();
       
        $user = $users_inv->get_logged_user($request);
       
        $data = DB::select('select * from locations_relation where locations_relation.user_id = "' . $user->id . '" ORDER BY locations_relation.id DESC  
        LIMIT 1');

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
        $locations_inv = new locations_relation();

        return $locations_inv->register($request);
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
