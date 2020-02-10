<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class apps extends Model
{
    protected $table = 'apps';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'icon'];
    public $timestamps = false;

    public function register($name)
    {
        try {
            $app = new self();
            $app->name = $name;
            $app->icon = $this->getIcon($name);
            $app->save();

    
            return 200;        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "app already registered"
            ], 401);
       }
    }

    

    
public function getIcon($name) {

$icon = "";

    switch ($name) {
        case "Whatsapp":
            $icon = "https://lh3.googleusercontent.com/bYtqbOcTYOlgc6gqZ2rwb8lptHuwlNE75zYJu6Bn076-hTmvd96HH-6v7S0YUAAJXoJN";
            break;
        case "Instagram":
            $icon = "https://lh3.googleusercontent.com/2sREY-8UpjmaLDCTztldQf6u2RGUtuyf6VT5iyX3z53JS4TdvfQlX-rNChXKgpBYMw";
            break;
        case "Reloj":
            $icon = "https://lh3.googleusercontent.com/k-K6mdmZJZrJiuMJCHILReDGjMl_2ljzFIz3QLULfKL1q0tWtTcAkc0RDsjg9QEuXYw";
            break;
        case "Gmail":
            $icon = "https://lh3.googleusercontent.com/qTG9HMCp-s_aubJGeQWkR6M_myn-aXDJnraWn9oePcY1dGbYqXibaeLQBAeMdmxSBus";
            break;
        case "Chrome":
            $icon = "https://lh3.googleusercontent.com/KwUBNPbMTk9jDXYS2AeX3illtVRTkrKVh5xR1Mg4WHd0CG2tV4mrh1z3kXi5z_warlk";
            break;
        case "Facebook":
            $icon = "https://lh3.googleusercontent.com/ccWDU4A7fX1R24v-vvT480ySh26AYp97g1VrIB_FIdjRcuQB2JP2WdY7h_wVVAeSpg";
            break;
        default:
            $icon = "https://cdn3.vectorstock.com/i/1000x1000/50/07/http-404-not-found-error-message-hypertext-vector-20025007.jpg";
            break;
    }

    return $icon;

}



}
