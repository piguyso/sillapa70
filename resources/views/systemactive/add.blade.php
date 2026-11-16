@extends('layouts.app')
@section('content')
@php
$closed = fn($level,$subject,$scope,$id=0) => (bool) optional($rules->first(fn($r) => $r->competition_level===$level && $r->subject_type===$subject && $r->scope_type===$scope && (int)$r->scope_id===(int)$id))->is_closed;
@endphp
<div class="max-w-7xl mx-auto px-4 py-8 font-sarabun text-slate-800">
 <div class="rounded-3xl p-8 mb-8 text-white shadow-xl" style="background:linear-gradient(135deg,var(--ui-secondary),var(--ui-primary))"><h1 class="text-3xl font-black">เปิด–ปิดการแก้ไขข้อมูล ครั้งที่ {{ config('app.event_number') }}</h1></div>
 <div class="flex flex-wrap gap-3 mb-6" role="tablist"><x-form.button type="button" data-tab="network">ระดับเครือข่าย</x-form.button><x-form.button type="button" data-tab="district" variant="secondary">ระดับเขตพื้นที่</x-form.button></div>
 <div id="panel-network" class="access-panel space-y-6">
  <section class="bg-white border rounded-3xl p-6 shadow-sm"><h2 class="text-xl font-black mb-3">ควบคุมระบบระดับเครือข่ายทั้งหมด</h2>@include('systemactive.partials.toggle-row',['label'=>'Master Freeze','level'=>'network','subject'=>'master','scope'=>'all','scopeId'=>0,'isClosed'=>$closed('network','master','all')])</section>
  <section class="bg-white border rounded-3xl p-6 shadow-sm"><h2 class="text-xl font-black mb-3">แอดมินเครือข่าย</h2>
   @include('systemactive.partials.toggle-row',['label'=>'ทุกเครือข่าย','level'=>'network','subject'=>'network','scope'=>'all','scopeId'=>0,'isClosed'=>$closed('network','network','all')])
   @foreach($schoolgroups as $group) @include('systemactive.partials.toggle-row',['label'=>$group->schoolgroup_name,'level'=>'network','subject'=>'network','scope'=>'network','scopeId'=>$group->id,'isClosed'=>$closed('network','network','network',$group->id)]) @endforeach
  </section>
  <section class="bg-white border rounded-3xl p-6 shadow-sm"><h2 class="text-xl font-black mb-3">โรงเรียน</h2>
   @include('systemactive.partials.toggle-row',['label'=>'ทุกโรงเรียน','level'=>'network','subject'=>'school','scope'=>'all','scopeId'=>0,'isClosed'=>$closed('network','school','all')])
   @foreach($schoolgroups as $group)
    <details class="border-t py-3"><summary class="cursor-pointer font-black text-slate-700 flex items-center justify-between">{{ $group->schoolgroup_name }} <span class="text-xs text-slate-400">กดดูโรงเรียน</span></summary><div class="mt-2 pl-3 border-l-4" style="border-color:var(--ui-primary)">
     @include('systemactive.partials.toggle-row',['label'=>'ทั้งเครือข่าย','level'=>'network','subject'=>'school','scope'=>'network','scopeId'=>$group->id,'isClosed'=>$closed('network','school','network',$group->id)])
     @foreach($schools->where('school_group',$group->id) as $school) @include('systemactive.partials.toggle-row',['label'=>$school->school_name,'level'=>'network','subject'=>'school','scope'=>'school','scopeId'=>$school->id,'isClosed'=>$closed('network','school','school',$school->id),'parentKey'=>'network|school|network|'.$group->id]) @endforeach
    </div></details>
   @endforeach
  </section>
 </div>
 <div id="panel-district" class="access-panel hidden space-y-6">
  <section class="bg-white border rounded-3xl p-6 shadow-sm"><h2 class="text-xl font-black mb-3">แอดมินเครือข่าย</h2>
   @include('systemactive.partials.toggle-row',['label'=>'ทุกเครือข่าย','level'=>'district','subject'=>'network','scope'=>'all','scopeId'=>0,'isClosed'=>$closed('district','network','all')])
   @foreach($schoolgroups as $group) @include('systemactive.partials.toggle-row',['label'=>$group->schoolgroup_name,'level'=>'district','subject'=>'network','scope'=>'network','scopeId'=>$group->id,'isClosed'=>$closed('district','network','network',$group->id)]) @endforeach
  </section>
  <section class="bg-white border rounded-3xl p-6 shadow-sm"><h2 class="text-xl font-black mb-3">แอดมินศูนย์การแข่งขัน</h2>
   @include('systemactive.partials.toggle-row',['label'=>'ทุกศูนย์การแข่งขัน','level'=>'district','subject'=>'center','scope'=>'all','scopeId'=>0,'isClosed'=>$closed('district','center','all')])
   @foreach($centers as $center) @include('systemactive.partials.toggle-row',['label'=>$center->branch_name,'level'=>'district','subject'=>'center','scope'=>'center','scopeId'=>$center->id,'isClosed'=>$closed('district','center','center',$center->id)]) @endforeach
  </section>
 </div>
</div>
<script>
document.querySelectorAll('[data-tab]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.access-panel').forEach(p=>p.classList.add('hidden'));document.getElementById('panel-'+b.dataset.tab).classList.remove('hidden')}));
const accessForms=()=>Array.from(document.querySelectorAll('.access-toggle-form'));
const formIsClosed=form=>form&&form.querySelector('[name="is_closed"]').value==='0';
const formByKey=key=>accessForms().find(form=>form.dataset.ruleKey===key);
function refreshAccessHierarchy(){
 const networkMasterClosed=formIsClosed(formByKey('network|master|all|0'));
 accessForms().forEach(form=>{
  const [level,subject,scope]=form.dataset.ruleKey.split('|'); let inherited=false;
  if(level==='network'&&subject!=='master')inherited=networkMasterClosed;
  if(!inherited&&scope!=='all')inherited=formIsClosed(formByKey([level,subject,'all','0'].join('|')));
  if(!inherited&&form.dataset.parentKey)inherited=formIsClosed(formByKey(form.dataset.parentKey));
  form.querySelector('.access-toggle-button').disabled=inherited;
 });
}
document.querySelectorAll('.access-toggle-form').forEach(form=>form.addEventListener('submit',async event=>{
 event.preventDefault();
 const statusInput=form.querySelector('[name="is_closed"]');
 const closing=statusInput.value==='1';
 const confirmed=await appConfirm(closing?'ยืนยันล๊อคไม่ให้ใช้งาน?':'ยืนยันให้ใช้งานได้?',{danger:closing});
 if(!confirmed)return;
 const button=form.querySelector('.access-toggle-button');
 const label=form.querySelector('.access-toggle-label');
 const dot=form.querySelector('.access-toggle-dot');
 button.disabled=true;
 try{
  const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
  const data=await response.json().catch(()=>({}));
  if(!response.ok)throw new Error(data.message||'ไม่สามารถบันทึกสถานะได้');
  (data.affected||[]).forEach(rule=>{
   const key=[rule.competition_level,rule.subject_type,rule.scope_type,rule.scope_id].join('|');
   const targetForm=Array.from(document.querySelectorAll('.access-toggle-form')).find(item=>item.dataset.ruleKey===key);
   if(!targetForm)return;
   const isClosed=Boolean(rule.is_closed), targetButton=targetForm.querySelector('.access-toggle-button'), targetDot=targetForm.querySelector('.access-toggle-dot');
   targetForm.querySelector('[name="is_closed"]').value=isClosed?'0':'1';
   targetForm.querySelector('.access-toggle-label').textContent=isClosed?'ล๊อคไม่ให้ใช้งาน':'ใช้งานได้';
   targetButton.style.backgroundColor=isClosed?'#dc2626':'#16a34a';
   targetDot.classList.toggle('bg-red-200',isClosed); targetDot.classList.toggle('bg-emerald-200',!isClosed);
  });
  refreshAccessHierarchy();
 }catch(error){
  await appAlert(error.message,{danger:true});
 }finally{button.disabled=false;}
}));
refreshAccessHierarchy();
</script>
@endsection
