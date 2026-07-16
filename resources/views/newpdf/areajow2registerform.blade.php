<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: "THSarabunNew";
            font-size:18px;
            margin-top: 4.5cm;
            margin-left: 0.5cm;
            margin-right: 1cm;
            margin-bottom: 2cm;
        }
        @page {
    /*    size: A4 landscape;*/
        margin: 0cm 0cm;
         }
            header {
                position: fixed;
                top: 1.5cm;
                left: 2cm;
                right: 2cm;
                height: 2.5cm;

                /** Extra personal styles **/
               /* background-color: #03a9f4;
                color: white; */
                text-align: center;
                line-height: 0.5cm;
                font-family: "THSarabunNew";
                font-size:20px;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
            thead {
                
                font-family: "THSarabunNew";
                font-size:18px;
                line-height: 0.5cm;
               
            }

    </style>
</head>
<body>
<header>
    <img src ="imgs\obec.jpg" width="50px" style="float: left";  
>
ใบลงทะเบียนกรรมการดำเนินงาน {{ session('app_name') }} ระดับเครือข่ายสถานศึกษาปีการศึกษา {{session('app_year') }} <br>
            เครือข่ายสถานศึกษา {{$school_group->schoolgroup_name}}
        
          <br> วันที่ _________ เดือน __________________ พ.ศ. _________  
            
        </header>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
  
          
                  <table class="table table-bordered">
     
<thead>
                     <tr>
                       <th>ที่</th>
                       <th>ชื่อ-สกุล</th>
                      
                     
                       <th>เวลามา</th>
                       <th>ลายมือชื่อ</th>
                       <th>เวลากลับ</th>
                       <th>ลายมือชื่อ</th>
                       <th>หมายเหตุ</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php 
                     $xc=0;
                     $x=0;
                     $temp_score =0;
                     ?>



                  
                    @forelse($jows as $l)
                     <tr>
                       <td>
                        <?php
                        $xc++;
                        echo $xc;
                        
                        ?>
                       </td>
           
                       <td>{{$l->getjowtitle->title}}{{$l->jow_name}} {{$l->jow_lastname}}</td>
                      
                      
                       <td>
                     
                       ..........................
                      

                       </td>
                       <td>
                       
                       ..........................
                   
                      
                       </td>
                       <td>
                      
                       ..........................
                   
                      
                       </td>
                       <td>
                      
                       ..........................
                      
                       </td>
                       <td>
                        <!--
                       <a href="#" class="btn btn-info btn-md"> <span class="glyphicon glyphicon-print"></span></a>
                    -->
                 
                      
                       </td>
                     </tr>
                  
               
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse

                   

                   </tbody>
               
                 </table>
         
    </div>
</div>
        

</body>
</html>