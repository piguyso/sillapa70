<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
     public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','schoolgroup');
    }
}
