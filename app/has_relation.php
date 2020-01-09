<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;

class has_relation extends Pivot
{
    protected $table = 'has_relation';
    protected $fillable = ['user_id', 'app_id'];


}

