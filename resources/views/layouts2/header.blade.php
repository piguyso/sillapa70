  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="" class="nav-link">ระบบรับสมัครนักเรียนโรงเรียนอนุบาลชุมพร</a>
      </li>
      
    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                               <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else

                                                        <!-- Notifications Dropdown Menu 
                                <li class="nav-item dropdown">
                                  <a class="nav-link" data-toggle="dropdown" href="#">
                                    กรรมการรับนักเรียน
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                    <span class="dropdown-item dropdown-header">Memu</span>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-envelope mr-2"></i> 4 new messages
                                      <span class="float-right text-muted text-sm">3 mins</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-users mr-2"></i> 8 friend requests
                                      <span class="float-right text-muted text-sm">12 hours</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-file mr-2"></i> 3 new reports
                                      <span class="float-right text-muted text-sm">2 days</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                                  </div>
                                </li>
                                                         Notifications Dropdown Menu 
                                                        <li class="nav-item dropdown">
                                  <a class="nav-link" data-toggle="dropdown" href="#">
                                    AdminMenu
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                    <span class="dropdown-item dropdown-header">Menu</span>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-envelope mr-2"></i> จัดการโครงการที่เปิดรับสมัคร
                                     
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-users mr-2"></i> 8 friend requests
                                      <span class="float-right text-muted text-sm">12 hours</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                      <i class="fas fa-file mr-2"></i> 3 new reports
                                      <span class="float-right text-muted text-sm">2 days</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                                  </div>
                                </li>

-->
                                <!-- logout -->
                                <li class="nav-item">
                                <a class="nav-link" href="">
                                    Hi {{Auth::user()->fname}} {{Auth::user()->lname}}
                                </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                </form>
                           
                        @endguest
    </ul>
  </nav>
  <!-- /.navbar -->