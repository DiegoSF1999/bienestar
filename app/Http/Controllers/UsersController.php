<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_encode(users::all());
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
            $users = new users();
            return $users->register($request);
         
    }

    public function login(Request $request)
    {
            $users = new users();
            return $users->login($request);
         
    }


    public function recover_password(Request $request)
    {
            $users = new users();
            return $users->recover_password($request);
         
    }

    public function link_user_app(Request $request)
    {
        $users = new users();
            return $users->link_user_app($request);
    }

    public function show_user_apps(Request $request)
    {
        $users = new users();
        return $users->show_user_apps($request);  

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
