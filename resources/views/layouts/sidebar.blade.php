<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-success elevation-4">
    <!-- Brand Logo -->
    <a href="{{ config('app.url', '/') }}" class="brand-link">
 
		<i class="fas fa-laptop-code fa-1x" style="color:Pink;" ></i>
      <span class="brand-text font-weight-light" style="color:Pink;">ระบบรับสมัครนักเรียน</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
   
	@section('sidebar')
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
     
          <li class="nav-item">
          @guest
          <li class="nav-item">
            <a href="{{url('/')}}" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Home
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('schedule')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                ตารางการรับสมัคร
              </p>
            </a>
          </li>
         
            <li class="nav-item">
            <a href="{{url('startregister')}}" class="nav-link">
              <i class="nav-icon fas  fa-expand-arrows-alt"></i>
              <p>
                ยื่นใบสมัคร
              </p>
            </a>
          </li>
      
          <li class="nav-item">
            <a href="{{url('Studentcheck')}}" class="nav-link">
              <i class="nav-icon fas fa-search"></i>
              <p>
                ตรวจสอบรายชื่อผู้สมัคร
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('Studentedit')}}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                แก้ไขข้อมูล/พิมพ์ใบสมัคร
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('Studentedit')}}" class="nav-link">
              <i class="nav-icon fas fa-info"></i>
              <p>
                ตรวจสอบผล/รายงานตัว
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('Studentstatus')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                สถิติการรับสมัคร
              </p>
            </a>
          </li>
          @else
          <li class="nav-item">
            <a href="{{url('startregister')}}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                สมัครเรียน
              </p>
            </a>
          </li>
            @if(Auth::user()->user_level == 99)
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Admin Menu
              </p>
            </a>
              <li class="nav-item">
                <a href="{{url('Classtype')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการโครงการ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Changebanner')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>เปลี่ยนภาพBanner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Title')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการคำนำหน้า</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Myuser')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการชื่อผู้ใช้</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Year')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการปีการศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Notice')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>เพิ่มประกาศ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Databackup')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ลบข้อมูลใบสมัคร</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Studentcheck')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ลบใบสมัคร</p>
                </a>
              </li>
              @endif
              @if(Auth::user()->user_level == 99 || Auth::user()->user_level == 1)
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Stuff Menu
              </p>
            </a>

              <li class="nav-item">
                <a href="{{url('Studentcheck')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตรวจเอกสารนักเรียน</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Studentnamelist')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายชื่อนักเรียนที่ผ่านการตรวจเอกสาร</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Studentresult')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>บันทึกผลการคัดเลือก</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('Studentresultcompleted')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายชื่อนักเรียนที่ผ่านการคัดเลือก</p>
                </a>
              </li>

             
              @endif
             @endguest
            </ul>
          </li>
          <div style="text-align:center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin: 10px; font-family: sans-serif; font-size: 11px; font-weight: bold; color: #495057; border: 1px solid #dee2e6;">
              <div style="margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                  <span style="display: inline-block; width: 8px; height: 8px; background-color: #28a745; border-radius: 50%;"></span>
                  <span>ออนไลน์: {{ number_format(online_count()) }} คน</span>
              </div>
              <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                  <span style="color: #6c757d;">👁️</span>
                  <span>ผู้เข้าชม: {{ number_format(visitor_count()) }} ครั้ง</span>
              </div>
          </div>
      </nav>
	@show
		<!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>