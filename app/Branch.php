<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function getuserbranch(){
        return $this->hasOne('App\User','branch','id');
    }
}
