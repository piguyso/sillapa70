<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function partner()
    {
      return $this->hasOne('App\Partner','id','purchasePartner');
    }
    public function committee()
    {
      return $this->hasMany('App\Committee','purchaseId','purchaseId');
    }
}
