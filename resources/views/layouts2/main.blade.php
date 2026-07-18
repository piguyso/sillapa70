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


function showCustomConfirm(title, message, onConfirm) {
    const modalHtml = `
        <div id="custom-confirm-modal" style="position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); transition: all 0.2s;">
            <div style="background-color: white; border-radius: 24px; padding: 32px; max-width: 448px; width: 100%; margin: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #f1f5f9; text-align: center; font-family: 'Sarabun', sans-serif;">
                <div style="width: 64px; height: 64px; background-color: #fef2f2; color: #ef4444; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 30px;">
                    <i class="ri-error-warning-line"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin-bottom: 8px; margin-top: 0;">${title}</h3>
                <p style="font-size: 14px; color: #64748b; font-weight: 500; margin-bottom: 32px; line-height: 1.625;">${message}</p>
                <div style="display: flex; gap: 16px;">
                    <button id="confirm-cancel" style="flex: 1; padding: 14px 0; background-color: #f1f5f9; color: #475569; border-radius: 16px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: all 0.2s;">
                        ยกเลิก
                    </button>
                    <button id="confirm-ok" style="flex: 1; padding: 14px 0; background-color: #dc2626; color: white; border-radius: 16px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: all 0.2s;">
                        ยืนยัน
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = document.getElementById('custom-confirm-modal');
    const cancelBtn = document.getElementById('confirm-cancel');
    const okBtn = document.getElementById('confirm-ok');
    const closeModal = () => { modal.remove(); };
    cancelBtn.addEventListener('click', closeModal);
    okBtn.addEventListener('click', () => {
        closeModal();
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
    });
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
}

function DelConfirm($x)
{
    showCustomConfirm('ยืนยันการลบข้อมูล', 'คุณต้องการลบข้อมูลนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้', function() {
        document.getElementById('delform'+$x).submit();
    });
}
function SaveConfirm($x)
{
    showCustomConfirm('ยืนยันการบันทึกข้อมูล', 'คุณต้องการยืนยันการบันทึกข้อมูลใช่หรือไม่?', function() {
        document.getElementById($x).submit();
    });
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
    @if(Session::has('massage') || Session::has('success') || Session::has('status'))
        <div id="toast-notification" style="position: fixed; bottom: 24px; right: 24px; z-index: 10000; display: flex; align-items: center; gap: 12px; background-color: #10b981; color: white; padding: 16px 24px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3), 0 4px 6px -4px rgba(16, 185, 129, 0.3); font-family: 'Sarabun', sans-serif; font-weight: bold; font-size: 14px; animation: slideInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; transition: all 0.3s ease;">
            <i class="ri-checkbox-circle-fill" style="font-size: 20px;"></i>
            <span>{{ Session::get('massage') ?? Session::get('success') ?? Session::get('status') }}</span>
        </div>
        <style>
            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    toast.style.transform = 'translateY(20px)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        </script>
    @endif
</body>
</html>