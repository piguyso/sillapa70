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
            margin-top: 2.8cm;
            margin-left: 0.5cm;
            margin-right: 1cm;
            margin-bottom: 3.5cm;
        }
        @page {
       size: A4 landscape; 
     /*  size: A4 portrait;  */
       
        margin: 0cm 0cm;
         }
            header {
                position: fixed;
                top: 0.3cm;
                left: 1.5cm;
                right: 1.5cm;
                height: 2.3cm;

                /** Extra personal styles **/
               /* background-color: #03a9f4;
                color: white; */
                text-align: center;
                line-height: 0.4cm;
                font-family: "THSarabunNew";
                font-size:18px;
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
                line-height: 1cm;
            }
            thead {
                
                font-family: "THSarabunNew";
                font-size:16px;
                line-height: 0.3cm;
                
               
            }
       
            table{
        width:783px;
      height:auto;
    table-layout:fixed;
  }
  td{
    padding: 1px;
  }

  .mytd{
            text-align: center;  /* จัดข้อความให้อยู่กลางเซลล์ */
            padding: 10px;  /* ระยะห่างภายในเซลล์ */
        }
        .xtable th,.xtable tr,.xtable td,.xtable thead {
            border: 1px solid black; /* Set border color to black and thickness to 1px */

        }
    </style>
</head>
<body>
<header>
    <img src ="imgs\obec.jpg" width="50px" style="float: left";  
>
แบบบันทึกคะแนน ผลการแข่งขัน
    {{ session('app_name') }} {{ session('app_group').' ปีการศึกษา '.session('app_year') }} <br>
            กลุ่มสาระการเรียนรู้ {{$activity->getactivitysubject->subjectgroup_name}} กิจกรรม {{$activity->activity_name}} {{$activity->getclass->class_name}}
           <br>
           {{$activity->getactivitybranch->branch_name}}  {{ session('app_area') }} 
          <br> วันที่ _________ เดือน __________________ พ.ศ. _________  
          <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


          <table align="center">
        <tr class="mytd">
        
            <td>ลงชื่อ......................................................ประธานศูนย์การจัดการแข่งขัน</td>
           
        </tr>
        <tr class="mytd">
       
            <td>(......................................................)</td>
            
        </tr>
        <tr class="mytd">
       
            <td></td>
           
        </tr>
    </table>
    <br>
          <table align="center">
        <tr class="mytd">
        @foreach($jow as $j)
            <td></td>
            @endforeach
        </tr>
        <tr class="mytd">
        @foreach($jow as $l)
            <td>{{$l->getjowtitle->title}}{{$l->jow_name}} {{$l->jow_lastname}}</td>
            @endforeach
        </tr>
        <tr class="mytd">
        @foreach($jow as $l)
            <td>{{$l->jowclass}}</td>
            @endforeach
        </tr>
    </table>
    


          
        </header>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
  
         
          
                  <table class="table table-bordered" style="table-layout:fixed;">
     
<thead>
                     <tr>
                       <th style="width:4%;height:30px;">ที่</th>
                       <th style="width:25%;height:30px;">โรงเรียน</th>
                     
                     
                       <th style="width:10%;height:30px;">คะแนน</th>
                       <th style="width:10%;height:30px;">ลำดับที่</th>
                    
                       <th style="width:10%;height:30px;">หมายเหตุ</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php 
                     $xc=0;
                     $x=0;
                     $temp_score =0;
                     ?>



                  
                    @forelse($addschool as $l)
                     <tr>
                       <td>
                        <?php
                        $xc++;
                        echo $xc;
                        
                        ?>
                       </td>
           
                       <td class="wx">{{$l->getschoolname->school_name}}</td>
                      
                    
                       <td>
 

                       </td>
                       <td>
               
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
                 <div class="footer">
<br>
        
        

 
    </div>   
            
    </div>
</div>

</body>
</html>