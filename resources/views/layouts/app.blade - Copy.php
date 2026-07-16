<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('fonts/stylesheet.css')}}" rel="stylesheet" > -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('dist/css/bootstrap-datepicker.css')}}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Athiti&amp;subset=thai" rel="stylesheet">

    <link href="{{ asset('css/mycustom.css')}}" rel ="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image --><img src="{{ asset('imgs/obec_icon2.png') }}">
                    <a class="dropdown navbar-brand" href="{{ url('/') }}">

                        {{ config('app.name', 'Laravel') }} '{{Session('year')}}
                    </a>

                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">

                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <!--<li><a href="{{ route('register') }}">Register</a></li>-->
                        @else
							
							<li class="dropdown">
                          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                              <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> งานธุรการ
                          </a>
                          <ul class="dropdown-menu text_">
                            <li>
                                <a href="{{ url('turakan')}}">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ทะเบียนเอกสาร
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('turakan/create')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ลงทะเบียนเอกสาร
                                </a>
                            </li>

                          </ul>
                      </li>
<!--
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                              <span class="glyphicon glyphicon-user" aria-hidden="true"></span> งานบุคลากร
                          </a>
                          <ul class="dropdown-menu text_">
                            <li>
                                <a href="{{ url('leave')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ใบลา
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('leave/create')}}">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> เขียนใบลา
                                </a>
                            </li>

                          </ul>
                      </li>
					  -->
					    @if(officerchk())
							
						
						   <li class="dropdown">
                          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                              <span class="glyphicon glyphicon-bitcoin" aria-hidden="true"></span> งานบริหารงบประมาณ
                          </a>
                          <ul class="dropdown-menu text_">
							<li>
                                <a href="{{ url('configyear')}}">
                                    <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> ตั้งค่าปีการศึกษา
                                </a>
                            </li>
							<li>
                                <a href="{{ url('configyear/create')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มปีการศึกษา
                                </a>
                            </li>
							<li>
                                <a href="{{ url('budgetofschool')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มประเภทงบประมาณ
                                </a>
                            </li> 
                            <li>
                                <a href="{{ url('project/create')}}">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มโครงการตามแผนปฏิบัติการ
                                </a>
                            </li>
							<hr>
							 <li>
                                <a href="{{ url('project')}}">
                                    <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> รายการโครงการตามแผนปฏิบัติการ
                                </a>
                            </li>
                           <!-- <li>
                                <a href="{{ url('leave/create')}}">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> เขียนใบลา
                                </a>
                            </li> -->

                          </ul>
                      </li>
					  
					  
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                  <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> งานพัสดุ
                              </a>
                              <ul class="dropdown-menu text_">
                              
                                <li>
                                    <a href="{{ url('purchase/create')}}">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มรายการจัดซื้อ/จัดจ้าง
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('buildinglib/create')}}">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> เพิ่มรายการข้อมูลครุภัณฑ์[ที่ไม่ผ่านการจัดซื้อจัดจ้างในระบบ]
                                    </a>
                                </li>

                                <hr>
                                
                                <li>
                                    <a href="{{ url('purchase/home/buy')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนการจัดซื้อ
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('purchase/home/hire')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนการจัดจ้าง
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('itemlibrary/lib1')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนทรัพย์สิน
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('itemlibrary/lib2')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนทรัพย์สิน(สิ่งก่อสร้าง) จากการสำรวจ
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('itemlibrary/lib3')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนทรัพย์สิน(ครุภัณฑ์) จากการสำรวจ
                                    </a>
                                </li>
								 <li>
                                    <a href="{{ url('itemlibrary/lib4')}}">
                                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ทะเบียนทรัพย์สิน(จำหน่ายแล้ว)
                                    </a>
                                </li>

                                  <hr>
								  <li>
                                    <a href="{{ url('myuser/create')}}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ลงทะเบียนกรรมการตรวจรับ
                                    </a>
                                  </li>
                                  <li>
                                    <a href="{{ url('partner')}}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ลงทะเบียนผู้ขาย/ผู้รับจ้าง
                                    </a>
                                  </li>
                                 <!-- <li>
                                    <a href="{{ url('itemtype')}}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ลงทะเบียนประเภทงาน/พัสดุ
                                    </a> 
                                  </li> -->
                                  <li>
                                    <a href="{{ url('item')}}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ลงทะเบียนงาน/วัสดุ/ครุภัณฑ์
                                    </a>
                                  </li>
                              </ul>
                          </li>
							@endif
                            @if(Auth::user()->position==10)
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                   <span class="glyphicon glyphicon-th" aria-hidden="true"></span> Menu
                                </a>
                                <ul class="dropdown-menu text_">
                                    <li>
                                        <a href="{{ url('myuser')}}">
                                            จัดการข้อมูลผู้ใช้
                                        </a>
                                        <a href="{{ url('department')}}">
                                            จัดการข้อมูลองค์กร
                                        </a>
                                    </li>



                                </ul>
                            </li>
                          @endif

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
    <script src="{{ asset('dist/js/bootstrap-datepicker-custom.js') }}"></script>
    <script src="{{ asset('dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>
    <script src="{{ asset('js/autoNumeric.js')}}"></script>
    <script src="{{ asset('js/myjs.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <script ="java script">
    $(document).ready(function () {

        $('.date').datepicker({
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




    </script>
    <div class="footer">
    <p align="center">-</p>
    </div>

</body>
</html>
