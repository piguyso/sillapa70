<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolgroup extends Model
{
    public const TYPES = ['district' => 'อำเภอ', 'cluster' => 'กลุ่มโรงเรียน'];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_schoolgroup', 'schoolgroup_id', 'school_id');
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->group_type] ?? self::TYPES['cluster'];
    }
}
