<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jows_areas extends Model
{
     public function getjowtitle(){
        return $this->hasOne('App\titles','title_id','jow_title');
    }
    public function getjowbranch(){
        return $this->hasOne('App\branch','id','jow_branch');
    }

}
