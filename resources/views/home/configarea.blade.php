@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                Configuration & Systems
            </h1>
        </div>
        <i class="ri-settings-4-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if(Session::has('massage') || session('status'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ Session::get('massage') ?: session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm text-white">
                        <i class="ri-award-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase italic leading-none text-white">Certificate Setting</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white leading-none">ตั้งค่าพื้นหลังเกียรติบัตร</p>
                    </div>
                </div>

                <div class="p-8">
                    @if(isset($config[0]))
                    <form method="POST" enctype="multipart/form-data" action="{{ Route('config.update', $config[0]->id) }}" class="space-y-6">
                    @else
                    <form method="POST" enctype="multipart/form-data" action="#" class="space-y-6">
                    @endif
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        <div class="space-y-4">
                            <div class="relative group bg-slate-100 rounded-3xl border-2 border-dashed border-slate-300 p-2 overflow-hidden aspect-[1.414/1] flex items-center justify-center shadow-inner">
                                @if($config[0]->app_certificate != "")
                                    <img id="app_certificate_preview" src="{{ get_secure_file_url('upload', $config[0]->app_certificate, $config[0]->app_year) }}" class="w-full h-full object-cover rounded-2xl shadow-md transition-all duration-300">
                                    <div id="no_img_placeholder" class="hidden text-center text-slate-300">
                                        <i class="ri-image-add-line text-5xl"></i>
                                        <p class="text-[10px] font-black mt-2 uppercase italic text-slate-400">เลือกรูปพื้นหลัง</p>
                                    </div>
                                @else
                                    <img id="app_certificate_preview" src="" class="hidden w-full h-full object-cover rounded-2xl shadow-md transition-all duration-300">
                                    <div id="no_img_placeholder" class="text-center text-slate-300">
                                        <i class="ri-image-add-line text-5xl"></i>
                                        <p class="text-[10px] font-black mt-2 uppercase italic text-slate-400">ยังไม่ได้เลือกรูปภาพ</p>
                                    </div>
                                @endif
                            </div>

                            <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100">
                                <label class="block text-[11px] font-black text-orange-800 uppercase tracking-widest italic mb-2">อัปโหลดไฟล์ (JPG/PNG)</label>
                                <input type="file" 
                                       class="block w-full text-xs text-slate-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-[10px] file:font-black
                                              file:bg-orange-600 file:text-white
                                              hover:file:bg-orange-700 transition-all cursor-pointer shadow-sm" 
                                       id="cert_input" 
                                       name="app_certificate" 
                                       onchange="previewImage(event)">
                                <p class="mt-2 text-[10px] text-orange-600 font-bold italic leading-none">* ขนาดไฟล์ห้ามเกิน 2MB</p>
                            </div>
                        </div>

                        <div class="pt-4 space-y-3">
                            <a href="https://www.canva.com/design/DAGe9nyQhgg/bmcJSUUxPOwYJ1tyd72Fsw/edit" target="_blank" class="w-full inline-flex justify-center items-center gap-2 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs hover:bg-teal-700 transition-all shadow-lg shadow-teal-100 uppercase italic tracking-widest text-white">
                                <i class="ri-palette-line text-white"></i> Canva Template
                            </a>
                            <a href="{{url('/area')}}/printtest/{{Auth::user()->schoolgroup}}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 py-3 bg-slate-800 text-white rounded-2xl font-black text-xs hover:bg-slate-900 transition-all shadow-lg shadow-slate-100 uppercase italic tracking-widest text-white">
                                <i class="ri-eye-line text-white"></i> ดูตัวอย่างจริง
                            </a>
                            <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white">
                                <i class="ri-save-3-line mr-1 text-white"></i> บันทึกข้อมูลตั้งค่า
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none">สถานะการเข้าใช้งาน (System Access)</h2>
                </div>

                <div class="p-4 md:p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                    <th class="px-6 py-2 text-center w-12 text-slate-400">ID</th>
                                    <th class="px-6 py-2 text-slate-400">รายการระบบ</th>
                                    <th class="px-6 py-2 text-slate-400">รายละเอียด</th>
                                    <th class="px-6 py-2 text-center text-slate-400">สถานะ</th>
                                    <th class="px-6 py-2 text-right text-slate-400">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-bold italic">
                                @forelse($systemactive as $l)
                                <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                                    <td class="px-6 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 italic">
                                        {{$l->id}}
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-slate-800 font-black uppercase text-sm leading-none">{{$l->systemname}}</p>
                                    </td>
                                    <td class="px-6 py-5 text-slate-400 font-medium italic">
                                        {{$l->detail}}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($l->status == 1)
                                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-[9px] font-black uppercase tracking-tighter shadow-sm flex items-center justify-center gap-1 mx-auto w-max text-red-600">
                                                <i class="ri-lock-2-line"></i> ปิดระบบ
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-tighter shadow-sm flex items-center justify-center gap-1 mx-auto w-max text-emerald-600">
                                                <i class="ri-lock-unlock-line"></i> เปิดใช้งาน
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right rounded-r-2xl">
                                        @if(Auth::user()->position == 5)
                                            <a href="{{url('systemactive/'.$l->id.'/edit?action=edit')}}" class="w-9 h-9 inline-flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-settings-line"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-10 italic text-slate-300 font-bold">ไม่พบข้อมูลระบบการใช้งาน</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชัน Preview รูปภาพทันทีเมื่อเลือกไฟล์
    function previewImage(event) {
        const reader = new FileReader();
        const output = document.getElementById('app_certificate_preview');
        const placeholder = document.getElementById('no_img_placeholder');

        reader.onload = function() {
            if (reader.readyState === 2) {
                output.src = reader.result;
                output.classList.remove('hidden'); // แสดงรูปภาพ
                if (placeholder) {
                    placeholder.classList.add('hidden'); // ซ่อน Placeholder
                }
            }
        }
        
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection