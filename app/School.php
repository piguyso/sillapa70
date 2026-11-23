<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    /** เครือข่ายหลัก (school_group) */
    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','school_group');
    }

    /** ทุกเครือข่ายที่โรงเรียนสังกัด (โรงเรียนขยายโอกาสสังกัดได้หลายเครือข่าย) */
    public function schoolgroups()
    {
        return $this->belongsToMany(Schoolgroup::class, 'school_schoolgroup', 'school_id', 'schoolgroup_id')->withTimestamps();
    }

    /** โรงเรียนที่สังกัดเครือข่ายนี้ (นับทั้งเครือข่ายหลักและเครือข่ายเพิ่มเติม) */
    public function scopeInNetwork(Builder $query, $networkId): Builder
    {
        return $query->whereIn($this->getTable().'.id',
            DB::table('school_schoolgroup')->where('schoolgroup_id', (int) $networkId)->select('school_id'));
    }

    /** บันทึกสังกัดทั้งหมด; $primaryId ต้องอยู่ในรายการเสมอ */
    public function syncNetworks(array $networkIds, int $primaryId): void
    {
        $ids = array_values(array_unique(array_map('intval', array_merge([$primaryId], $networkIds))));
        $this->schoolgroups()->sync($ids);
        if ((int) $this->school_group !== $primaryId) {
            $this->school_group = $primaryId;
            $this->save();
        }
    }
}
