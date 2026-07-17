<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ระบบรับสมัครนักเรียน โรงเรียนอนุบาลชุมพร</title>
    <!-- change font  -->
    <link href="https://fonts.googleapis.com/css?family=Athiti&amp;subset=thai" rel="stylesheet">


 
   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/mycss.css')}}">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css')}}">

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>

   <!-- daterange picker -->
 <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css')}}">

 <link href="{{ asset('dist/css/bootstrap-datepicker.css')}}" rel="stylesheet" />

  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css')}}">

<style>
    /* ยกระดับการออกแบบระบบปุ่มควบคุมหน้า (Pagination) ให้สวยงามและมองเห็นง่าย */
    
    /* 1. สำหรับ DataTables (Tailwind) */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px !important;
        margin-bottom: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 6px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; /* ขอบชัดเจน */
        background-color: #ffffff !important;
        color: #374151 !important;
        padding: 6px 14px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.2s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: #059669 !important; /* สีเขียวหลัก */
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active {
        background-color: #059669 !important; /* สีเขียวเด่นชัด */
        color: #ffffff !important;
        border-color: #059669 !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2), 0 2px 4px -1px rgba(5, 150, 105, 0.1) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background-color: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }

    /* 2. สำหรับ Laravel Pagination (ทั้ง Tailwind และ Bootstrap) */
    nav[role="navigation"], .pagination {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 6px !important;
        list-style: none !important;
        padding-left: 0 !important;
        width: 100% !important;
    }

    /* ตกแต่งลิงก์และปุ่มตัวเลข */
    nav[role="navigation"] a,
    nav[role="navigation"] span,
    .pagination .page-link {
        border: 1px solid #d1d5db !important;
        background-color: #ffffff !important;
        color: #374151 !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* โหมดโฮเวอร์ */
    nav[role="navigation"] a:hover,
    .pagination .page-link:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: #059669 !important;
    }

    /* โหมดกำลังใช้งาน (Active) */
    nav[role="navigation"] span[aria-current="page"],
    nav[role="navigation"] .bg-emerald-600,
    nav[role="navigation"] .bg-primary,
    .pagination .page-item.active .page-link {
        background-color: #059669 !important;
        color: #ffffff !important;
        border-color: #059669 !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2), 0 2px 4px -1px rgba(5, 150, 105, 0.1) !important;
    }

    /* ปรับแต่งปุ่ม Disabled */
    nav[role="navigation"] span[aria-disabled="true"],
    .pagination .page-item.disabled .page-link {
        background-color: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }
</style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    @include('layouts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><!--<a href="javascript:history.back()">Back</a>--></li>
              <li class="breadcrumb-item active"></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container -->
    </div>
    <!-- /.content-header -->
    @if(Session::has('massage'))
                <script>
              $( document ).ready(function() {
                var Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: '{{Session::get('massage')}}'
                  })
                });
            </script>
    @php
      Session::forget('massage');
    @endphp
     @endif
    <!-- Main content -->
    <div class="content">
      <div class="container">
            <!-- Your Page Content Here -->
            @yield('content')        
		  
      </div><!-- /.container -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

    <!-- Footer -->
  
	
</div>
<footer style="text-align:center;">
    http://www.abcp.ac.th
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->


<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
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
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes 
<script src="{{ asset('adminlte/dist/js/demo.js')}}"></script> -->

<!-- Toastr -->
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Page specific script -->

<script src="{{ asset('dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>
<!-- Summernote -->
<script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

$(function () {
    // Summernote
    $('#notice_detail').summernote()

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  })
  ///////////////
    $('#notice_detail').summernote({
    height: ($(window).height() - 300),
    callbacks: {
        onImageUpload: function(image) {
            uploadImage(image[0]);
        }
    }
});

function uploadImage(image) {
    var data = new FormData();
    data.append("image", image);
    data.append("_token", "{{ csrf_token() }}");
    
    $.ajax({
        url: '../noiteuploadimg',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function(url) {
            var image = $('<img width="100%">').attr('src', 'http://reg.abcp.ac.th:88/' + url);
            $('#notice_detail').summernote("insertNode", image[0]);
            console.log(data);
        },
        error: function(data) {
            console.log(data);
        }
    });
}
///////////////////

$(document).ready(function () {

window.setTimeout(function() {
    $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
        $(this).remove();
    });
}, 15000);

});


function DelConfirm($x)
{
var result = confirm("ยืนยันการลบข้อมูล?");
    if (result) {
        document.getElementById('delform'+$x).submit();
    }
}
function SaveConfirm($x)
{
var result = confirm("ยืนยันการบันทึกข้อมูล?");
    if (result) {
        document.getElementById($x).submit();
    }
}








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
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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
  
</script>

</body>
</html>