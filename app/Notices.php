<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    protected $fillable = ['notice_text', 'notice_detail', 'notice_file', 'notice_type', 'schoolgroup', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
     public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','schoolgroup');
    }

    public function getFilesAttribute()
    {
        $file = $this->notice_file;
        if (empty($file)) {
            return [];
        }
        if (strpos($file, '[') === 0) {
            $decoded = json_decode($file, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [$file];
    }
}
