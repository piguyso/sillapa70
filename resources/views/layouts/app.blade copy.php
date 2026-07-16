<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ session('app_name') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('fonts/stylesheet.css')}}" rel="stylesheet" > 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="{{ asset('dist/css/bootstrap-datepicker.css')}}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Athiti&amp;subset=thai" rel="stylesheet">

    <link href="{{ asset('css/mycustom.css')}}" rel ="stylesheet">

     <!-- DataTables 
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
--
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.6.0/css/autoFill.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"> -->
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css')}}">

</head>
<body>







    <div id="app">
        <nav class="navbar navbar-default navbar-static-top " style="background-color: #801515;">
            <div class="container">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <!--
                    <a class="dropdown navbar-brand" href="{{ url('/') }}"  style="color:#ffffff">
                    <img src="{{ asset('imgs/obec_icon2.png') }}">
                        {{ config('app.name', 'Laravel') }} '{{Session('year')}}
                    </a>
-->
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                     
                        <li class="dropdown">

                          <img src="{{ asset('imgs/obec_icon2.png') }}">

                      </li>
                      <li class="dropdown" >
                          <a href="{{url('/')}}" style="color:#ffffff;font-size:20px">
                          {{ session('app_name').' '.session('app_group').' ปีการศึกษา '.session('app_year') }}
                          </a>
                     
                      </li>
                    </ul>
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                          <a href="{{url('/')}}">
                              <span class="glyphicon glyphicon-home" aria-hidden="true" style="color:#ffffff"></span>
                          </a>
                     
                      </li>

                    <li class="dropdown">
                          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre >
                             | ข้อมูลสารสนเทศการแข่ง
                          </a>
                          
                          <ul class="dropdown-menu text_">
                          <!--  <li>
                                <a href="{{ url('showall1')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> รายชื่อโรงเรียนตัวแทนเครือข่าย
                                </a>
                            </li>-->
                            <li>
                                <a href="{{ url('showall_print')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print อันดับ 1-3
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print2')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อทัั้งหมด
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print3')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อโรงเรียน
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print6')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อกิจกรรมทั้งหมด
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print8')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อกิจกรรมที่มีทีมไม่เกิน 3 ทีม
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print4')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อกิจกรรมที่มีทีมมากกว่า 3 ทีม
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('showall_print7')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print รายชื่อกิจกรรมที่ไม่มีผู้ส่งนักเรียนเข้าร่วม
                                </a>
                            </li>
                        
                        
                            <li>
                                <a href="{{ url('showall_print5')}}">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> จำนวนเกียรติบัตร 3 อันดับแรก
                                </a>
                            </li>
                          </ul>
                      </li>
                      <li class="dropdown">
                          <a href="{{url('report')}}">
                              <span class="glyphicon glyphicon-print" aria-hidden="true" style="color:#ffffff"></span>
                         เกียรติบัตร
                            </a>
                     
                      </li>
  <!--
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                             | Print เกียรติบัตร
                          </a>
                        <ul class="dropdown-menu text_">
                            <li>
                                <a href="{{ url('report')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> นักเรียน/ครู
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('report')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> กรรมการตัดสิน
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('report')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> กรรมการดำเนินงาน
                                </a>
                            </li>
                            

                          </ul>-->
                      </li>

                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Login</a></li>
                            <!--<li><a href="{{ route('register') }}">Register</a></li>-->
                        @else
							
						

                       
					  


					  
<!-- กรณีเป็น แอดมิน -->
                            @if(Auth::user()->position==10 or Auth::user()->position==5 or Auth::user()->position==1 or Auth::user()->position >= 10) 
								
					
							
							@if(Auth::user()->position==1 or Auth::user()->position==10 )
							   <li class="dropdown">
                              <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                  <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> [ศูนย์การแข่งขัน]
                              </a>
                              <ul class="dropdown-menu text_">
                              
                                <li>
                                    <a href="{{ url('centersillapa')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> บันทึกผลการแข่งขัน
                                    </a>
                                    <a href="{{ url('jow')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> บันทึกรายชื่อกรรมการตัดสิน
                                    </a>
                                    <a href="{{ url('addjow2')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> บันทึกรายชื่อกรรมการดำเนินงาน
                                    </a>
                                    <hr><!--
                                    <a href="{{ url('centersillapa')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> พิมพ์ใบรายงานตัว
                                    </a>-->
                                    <a href="{{ url('printrandomz')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> พิมพ์ใบจับฉลาก
                                    </a>
                                    <a href="{{ url('printpointdoc')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> พิมพ์เอกสารที่เกี่ยวข้อง
                                    </a><!--
                                    <a href="{{ url('centersillapa')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> พิมพ์ใบลงเวลากรรมการตัดสิน
                                    </a>-->
                                    <a href="{{ url('addjow2')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> พิมพ์ใบลงเวลากรรมการดำเนินงาน
                                    </a>
                                    <hr>
                                    <a href="{{ url('shownotice')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มประกาศแจ้งผู้เข้าร่วมแข่งขัน
                                    </a>
                                </li>
                              
                               
                                
                               
                              </ul>
                          </li>
							@endif
						
                            @if(Auth::user()->position==5 or Auth::user()->position==10 or Auth::user()->position ==14 )
                          <li class="dropdown">
                              <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                  <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> [เครือข่ายสถานศึกษา]
                              </a>
                              <ul class="dropdown-menu text_">
                              
                                <li>
                                    <a href="{{ url('subjectgroup')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> บันทึกข้อมูลนักเรียน
                                    </a>
                                </li>
                     
                                
                               
                              </ul>
                          </li>
							@endif
							
                          @if(Auth::user()->position==10)
							
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                   <span class="glyphicon glyphicon-th" aria-hidden="true"></span> Admin
                                </a>
                                <ul class="dropdown-menu text_">
                                    <li>
                                    <a href="{{ url('title')}}">
													จัดการคำนำหน้า
                                        </a>
                                        <a href="{{ url('myuser')}}">
													จัดการข้อมูลผู้ใช้
                                        </a>
                                        <a href="{{ url('branch')}}">
													จัดการข้อมูลศูนย์แข่งขัน
                                        </a>
                                        <a href="{{ url('schoolgroup')}}">
													จัดการข้อมูลเครือข่ายสถานศึกษา
                                        </a>
                                        <a href="{{ url('school')}}">
													จัดการข้อมูลโรงเรียน
                                        </a>
                                        <a href="{{ url('Notice')}}">
													จัดการประกาศ
                                        </a>
                                        <a href="{{ url('config')}}">
													ตั้งค่าระบบ
                                        </a>
                                        <a href="{{ url('systemactive')}}">
													ปิด/เปิดระบบ
                                        </a>
                             
                                  <hr>
                                  <a href="{{ url('class')}}">
													จัดการช่วงชั้น
                                        </a>
                                        <a href="{{ url('subjectgroup')}}">
													จัดการรายวิชา
                                        </a>
                                        <a href="{{ url('activity')}}">
													จัดการรายการแข่งขัน
                                        </a>
                                        <a href="{{ url('dayofsillapa')}}">
													จัดการวันการแข่งขัน
                                        </a>
                                        <a href="{{ url('subjectgroup')}}">
													ยกเลิกผลการแข่งขัน
                                        </a>
                                    </li>



                                </ul>
                            </li>
                         @endif

                          @endif
<!-- end admin -->

<!-- if padsadu user -->

						




<!-- and pudsadu user -->

<!-- if kan ngen user -->
						

<!-- end if kan ngen user -->












                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                 <!-- <span class="glyphicon glyphicon-globe" aria-hidden="true"></span> ยินดีต้อนรับ :  {{ Auth::user()->name }} {{ Auth::user()->lastname}} [{{position(Auth::user()->position)}}]
									-->
									<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
								</a>

                                <ul class="dropdown-menu text_">
									<li><a href="#">
									ยินดีต้อนรับ :  {{ Auth::user()->name }} {{ Auth::user()->lastname}} [{{position(Auth::user()->position)}}]
									</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li>
                                       <a href="#">
                                        Change password
                                      </a>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

   
    @yield('content')
  
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>
    <script src="{{ asset('js/autoNumeric.js')}}"></script>
    <script src="{{ asset('js/myjs.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js')}}"></script>   
<!--
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/autofill/2.6.0/js/dataTables.autoFill.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
-->

    <script>



$(function () {

    $("#table1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "search":"ค้นหา :",
            "zeroRecords": "ไม่พบข้อมูลการค้นหา",
            "paginate": {
        "first":      "หน้าแรก",
        "last":       "หน้าสุดท้าย",
        "next":       "หน้าถัดไป",
        "previous":   "หน้าที่ผ่านมา"
    },
            "info": "แสดงหน้า _PAGE_ จากทั้งหมด _PAGES_ หน้า",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
     "buttons": ["csv", "excel", "print"]
    }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
    
    $("#table2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "search":"ค้นหา :",
            "zeroRecords": "ไม่พบข้อมูลการค้นหา",
            "paginate": {
        "first":      "หน้าแรก",
        "last":       "หน้าสุดท้าย",
        "next":       "หน้าถัดไป",
        "previous":   "หน้าที่ผ่านมา"
    },
            "info": "แสดงหน้า _PAGE_ จากทั้งหมด _PAGES_ หน้า",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
     "buttons": ["csv", "excel", "print"]
    }).buttons().container().appendTo('#table2_wrapper .col-md-6:eq(0)');


  });



    $(document).ready(function () {



   
        $('select[name="school_schoolgroup"]').on('change', function(){
     // alert('dfksld;fksl;kl');
        var schoolId = $(this).val();
        if(schoolId) {
            $.ajax({
                url: 'getschool/'+schoolId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                    $('select[name="school_name"]').empty();
                

                    $.each(data, function(key, value){

                        $('select[name="school_name"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            
     

        }

    });





  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "search":"ค้นหา :",
            "zeroRecords": "ไม่พบข้อมูลการค้นหา",
            "paginate": {
        "first":      "หน้าแรก",
        "last":       "หน้าสุดท้าย",
        "next":       "หน้าถัดไป",
        "previous":   "หน้าที่ผ่านมา"
    },
            "info": "แสดงหน้า _PAGE_ จากทั้งหมด _PAGES_ หน้า",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
     "buttons": ["csv", "excel", "print"]
    }).buttons().container().appendTo('#data_table .col-md-6:eq(0)');
   
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering":true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "orderby": [[1, 'asc']],
    });
  });






	
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
            //Set เป็นปี พ.ศ.
            orientation: "bottom auto",
            autoclose: true,
            todayHighlight: true,
           todayBtn: "linked",
          });  //กำหนดเป็นวันปัจุบัน


          $('.date2').datepicker({
            format: 'yyyy-mm-dd',
            language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
            //Set เป็นปี พ.ศ.
            orientation: "bottom auto",
            autoclose: true,
            todayHighlight: true,
           todayBtn: "linked",
          }).datepicker("setStartDate", $('.date').val());  //กำหนดเป็นวันปัจุบัน

          $('.leavedate').datepicker({
              format: 'yyyy-mm-dd',
              language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
              //Set เป็นปี พ.ศ.
              orientation: "bottom auto",
              autoclose: true,
              todayHighlight: true,
              todayBtn: "linked",
            });
            $('.date').on('changeDate', function(e) {

            //  alert(e.date);
              $('.leavedate').datepicker('setStartDate', e.date);
            });

    });


    $('#saveform').on('keydown', 'input', function (event) {
    if (event.which == 13) {
        event.preventDefault();
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        $('[data-index="' + (index + 1).toString() + '"]').focus();
    }
});

    </script>

   <!-- <footer style="text-align:center;background-color: #03adfc;">-->
   <footer style="position: relative; margin-top:50px; height: 100px; clear: both; text-align:center;background-image: url('{{ asset('imgs/buttom.png')}}');">
    
   
   <div style="text-align:center;">
 <!-- Histats.com  (div with counter) --><div id="histats_counter"></div>
<!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,4737145,4,438,112,75,00011111']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4737145&101" alt="" border="0"></a></noscript>
<!-- Histats.com  END  -->
</div>
   
   
   
   
   © COPYRIGHT 2023, ALL RIGHTS RESERVED<br>

    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
   
  

  </footer>

</body>
</html>
