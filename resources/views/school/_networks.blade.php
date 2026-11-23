{{-- ฟิลด์สังกัดเครือข่าย: เครือข่ายหลัก + เครือข่ายเพิ่มเติม + ป้ายโรงเรียนขยายโอกาส --}}
@php
    $primaryId = (int) old('school_group', $school->school_group ?? ($schoolgroups->first()->id ?? 0));
    $selectedIds = collect(old('networks', isset($school) && $school->exists ? $school->schoolgroups->pluck('id')->all() : []))->map(fn ($v) => (int) $v)->all();
    $expansion = (bool) old('is_expansion', $school->is_expansion ?? false);
@endphp
<div class="space-y-2">
    <x-form.select label="เครือข่ายหลัก" name="school_group" required>
        @foreach($schoolgroups as $g)
            <option value="{{ $g->id }}" @selected($primaryId === (int) $g->id)>{{ $g->schoolgroup_name }} ({{ \App\Schoolgroup::TYPES[$g->group_type] ?? 'กลุ่มโรงเรียน' }})</option>
        @endforeach
    </x-form.select>
</div>

<div class="space-y-2 mt-4">
    <label class="ui-label">เครือข่ายเพิ่มเติม (โรงเรียนที่สังกัดหลายเครือข่ายแข่งขันได้ทั้งสองเครือข่าย)</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto p-3 border border-gray-200 rounded-2xl bg-gray-50">
        @foreach($schoolgroups as $g)
            <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                <input type="checkbox" name="networks[]" value="{{ $g->id }}" @checked(in_array((int) $g->id, $selectedIds, true))>
                {{ $g->schoolgroup_name }} <span class="text-xs text-gray-400">({{ \App\Schoolgroup::TYPES[$g->group_type] ?? 'กลุ่มโรงเรียน' }})</span>
            </label>
        @endforeach
    </div>
    <p class="ui-help">เครือข่ายหลักจะถูกนับรวมให้อัตโนมัติ</p>
</div>

<label class="flex items-center gap-2 mt-4 text-sm font-black text-gray-700">
    <input type="checkbox" name="is_expansion" value="1" @checked($expansion)>
    โรงเรียนขยายโอกาส (มีระดับมัธยม)
</label>
@error('networks')<p class="ui-error">{{ $message }}</p>@enderror
