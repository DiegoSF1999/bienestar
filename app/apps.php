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

    public function register(Request $request)
    {
        try {
            $app = new self();
            $app->name = $request->name;
            $app->icon = $request->icon;
            $app->save();
    
            return 200;        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "app already registered"
            ], 401);
       }
    }


}
