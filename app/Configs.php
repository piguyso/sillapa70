<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    protected $guarded = [];

    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','schoolgroup');
    }
}
