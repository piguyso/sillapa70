<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function discipline()
    {
        return $this->belongsTo('App\Discipline');
    }
    public function userbranch(){
        return $this->hasOne('App\Branch','id','branch');
    }
    public function getbranch(){
        return $this->hasone('App\Branch','id','branch');
    }
    public function getschoolgroup(){
        return $this->hasone('App\Schoolgroup','id','schoolgroup');
    }

    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname','phonenumber','position','email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
