

$(document).ready(function () {

      window.setTimeout(function() {
          $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
              $(this).remove();
          });
      }, 2000);

     
    });


function DelConfirm($x)
{
      var result = confirm("ยืนยันการลบข้อมูล?");
          if (result) {
              document.getElementById('delform'+$x).submit();
          }
}
function Activeactivity($x)
{
    //  var activity_status = document.getElementById("activity_status");
      var result = confirm("ยืนยันการประกาศผลการแข่งขัน คุณจะไม่สามารถแก้ไขข้อมูลได้อีก?");
          if (result) {
      //      activity_status.value='1';
              document.getElementById($x).submit();
          }
}
function Saveactivity($x)
{
      var activity_status = document.getElementById("activity_status");
      var result = confirm("ยืนยันการบันทึกคะแนน?");
          if (result) {
        //      activity_status.value='0';
              document.getElementById($x).submit();
          }
}
function SaveConfirm($x)
{
      var result = confirm("ยืนยันการบันทึกข้อมูล?");
          if (result) {
              document.getElementById($x).submit();
          }
}
$(document).ready(function(){
//Add(); //--- เพิ่มช่องสำหรับกรอกข้อมูล 1 ช่องตอนเริ่มรันหน้า
//echo ('fksld;fksdl;');
 $(".expenses").on('keyup change', calculateSum);
});
$x=1;
function AddUser(){
  //--------------
    if($("#Name option:selected").val() =="")
    {
      alert("กรุณาเลือกกรรมการตรวจรับ !");
     return false;
    }
     if($("#position option:selected").val() =="")
     {
       alert("กรุณาเลือกตำแหน่งของกรรมการ !");
      return false;
     }
    // if($("#itemNameMenu option:selected").val() =="")
    // {
    //   alert("กรุณาเลือกข้อมูลให้ครบถ้วน !");
    //  return false;
    // }
  //-------------
	$("#tblUser tbody").append(
	"<tr>"+
  // "<td><select class='form-control' name='CommitteeId[]'>"+
  //       "<option value='"+$("#Name").val()+"'>"+$("#Name option:selected").text()+"</option>"+
  //       "</select></td>"+
  "<td><input type='hidden' id='CommitteeId[]' name='CommitteeId[]' value='"+$("#Name").val()+"'>"+$("#Name option:selected").text()+"</option>"+


  // "<td><select class='form-control' name='CommitteePosition[]'>"+
  //       "<option value='"+$("#position").val()+"'>"+$("#position option:selected").text()+"</option>"+
  //       "</select></td>"+
  "<td><input type='hidden' id='CommitteePosition[]' name='CommitteePosition[]' value='"+$("#position").val()+"'>"+$("#position option:selected").text()+"</option>"+


 "<td><button class='btn btn-info btnDelete btn-xs' type='button'><span class='glyphicon glyphicon-trash'></span></button></td>"+
		"</tr>");
		$(".btnDelete").bind("click", Delete);
		$x=$x+1;
		//CheckTextBox();
		$(".expenses").on('keyup change', calculateSum);

};


function Add(){
  //--------------
    if($("#itemTypeMenu option:selected").val() =="")
    {
      alert("กรุณาเลือกข้อมูลให้ครบถ้วน !");
     return false;
    }
    if($("#itemCategoryMenu option:selected").val() =="")
    {
      alert("กรุณาเลือกข้อมูลให้ครบถ้วน !");
     return false;
    }
    if($("#itemNameMenu option:selected").val() =="")
    {
      alert("กรุณาเลือกข้อมูลให้ครบถ้วน !");
     return false;
    }
  //-------------
	$("#tblData tbody").append(
	"<tr>"+



    // "<td><input type='hidden' id='itemId[]' name='itemId[]' value='"+$("#itemIdMemu").val()+"'><select class='form-control myselect' name='itemType[]' readonly>"+
    //     "<option value='"+$("#itemTypeMenu").val()+"'>"+$("#itemTypeMenu option:selected").text()+"</option>"+
    //     "</select></td>"+

    "<td><input type='hidden' id='itemId[]' name='itemId[]' value='"+$("#itemIdMemu").val()+"'><input type='hidden' id='itemType[]' name='itemType[]' value='"+$("#itemTypeMenu").val()+"'>"+$("#itemTypeMenu option:selected").text()+"</option>"+



        // "<td><select class='form-control myselect' name='itemCategory[]' readonly>"+
        // "<option value='"+$("#itemCategoryMenu").val()+"'>"+$("#itemCategoryMenu option:selected").text()+"</option>"+
        // "</select></td>"+

        "<td><input type='hidden' id='itemCategory[]' name='itemCategory[]' value='"+$("#itemCategoryMenu").val()+"'>"+$("#itemCategoryMenu option:selected").text()+"</option>"+






        // "<td><select class='form-control myselect' name='itemName[]' readonly>"+
        // "<option value='"+$("#itemNameMenu").val()+"'>"+$("#itemNameMenu option:selected").text()+"</option>"+
        // "</select></td>"+
        "<td><input type='hidden' id='itemName[]' name='itemName[]' value='"+$("#itemNameMenu").val()+"'>"+$("#itemNameMenu option:selected").text()+"</option>"+




    "<td><input type='text' class='form-control text-right' value='' placeholder='ItemDetail' id='ItemDetail[]' name='ItemDetail[]' size='30'/></td>"+

		"<td><input type='text' class='form-control expenses text-right' value='1' placeholder='Item Number' id='ItemNumber[]' name='ItemNumber[]' size='1' onchange='calculateSum()'/></td>"+
    "<td><span id=''>"+$("#itemUnitMemu").val()+"</span></td>"+
		"<td><input type='text' class='form-control expenses text-right' placeholder='Item Price' id='ItemPrice[]' name='ItemPrice[]' size='2' /></td>"+
    "<td><input type='text' class='form-control expenses_sum text-right' placeholder='Item Totel' id='ItemTotel[]' name='ItemTotel[]' size='2' readonly/></td>"+

		"<td><button class='btn btn-info btn-xs btnDelete' type='button'><span class='glyphicon glyphicon-trash'></span></button></td>"+
		"</tr>");
		$(".btnDelete").bind("click", Delete);
		$x=$x+1;
		//CheckTextBox();
		 $(".expenses").on('keyup change', calculateSum);

};
function Delete(){

		var par = $(this).parent().parent();
		par.remove();
		//CheckTextBox();
		calculateSum();
	};
  function Delete2(){
      alert();
  		$(this).closest('tr').remove();
  		//CheckTextBox();
  	//	calculateSum();
  	};

  $(document).on('click', 'button.btnDelete', function () { // ฟังก์ชั่นการลบข้อมูลหน้าแก้ไขกรรมการตรวจรับ
       //alert("aa");
       $(this).closest('tr').remove();
       return false;
   });

   $(document).on('click', 'button.btnDelete2', function () { // ฟังก์ชั่นการลบข้อมูลไอเทม
        //alert("aa");
        $(this).closest('tr').remove();
        calculateSum();
        return false;
    });

/// multiselect list item
  $(document).ready(function() {
    $('select[name="itemType"]').on('change', function(){
      alert('dfksld;fksl;kl');
        var itemTypeId = $(this).val();
        if(itemTypeId) {
            $.ajax({
                url: 'itemType/get/'+itemTypeId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                    $('select[name="itemCategory"]').empty();
                    $('select[name="itemCategory"]').append('<option value="">Please Select</option>');

                    $.each(data, function(key, value){

                        $('select[name="itemCategory"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="itemCategory"]').empty();
            $('select[name="itemCategory"]').append('<option value="">Please Select</option>');

        }

    });
    //--------------// get เลขทะเบียนครุภัณฑ์ตามชนิด
    $('select[name="itemCategory"]').on('change', function(){
        var itemCategoryId = $(this).val();
        if(itemCategoryId) {
            $.ajax({
                url: 'item/get/'+itemCategoryId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                  $.each(data, function(key, value){
                    $('input[name="itemTypeId"]').val(value);
                    //  $('select[name="itemCategory"]').append('<option value="'+ key +'">' + value + '</option>');
                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            //
        }

    });
// get itemtypeMenu หน้าจัดซื้อ
    $('select[name="itemTypeMenu"]').on('change', function(){
        var itemTypeId = $(this).val();
        //alert(itemTypeId);
        if(itemTypeId) {
            $.ajax({
                url: 'itemType/get/'+itemTypeId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                //    alert(data);
                    $('select[name="itemCategoryMenu"]').empty();
                    $('select[name="itemCategoryMenu"]').append('<option value="">Please Select</option>');
                    $('select[name="itemNameMenu"]').empty();
                    $('select[name="itemNameMenu"]').append('<option value="">Please Select</option>');
                    $.each(data, function(key, value){

                        $('select[name="itemCategoryMenu"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="itemCategoryMenu"]').empty();
            $('select[name="itemCategoryMenu"]').append('<option value="">Please Select</option>');
            $('select[name="itemNameMenu"]').empty();
            $('select[name="itemNameMenu"]').append('<option value="">Please Select</option>');
        }
    });
    //--------------// get ชื่อ ครุภัณฑ์ครุภัณฑ์ตามชนด
    $('select[name="itemCategoryMenu"]').on('change', function(){
        var itemId = $(this).val();
        if(itemId) {
            $.ajax({
                url: 'itemName/get/'+itemId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                      $('select[name="itemNameMenu"]').empty();
                      $('select[name="itemNameMenu"]').append('<option value="">Please Select</option>');
                  $.each(data, function(key, value){
                  //  $('input[name="itemNameMenu"]').val(value);
                      $('select[name="itemNameMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //    $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
          $('select[name="itemNameMenu"]').empty();
          $('select[name="itemNameMenu"]').append('<option value="">Please Select</option>');
        }

    });

    //--------------// get ชื่อ หน่วยของไอเทม
    $('select[name="itemNameMenu"]').on('change', function(){
        var itemId = $(this).val();
        if(itemId) {
            $.ajax({
                url: 'itemUnit/get/'+itemId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                      $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
                  $.each(data, function(key, value){
                    $('input[name="itemUnitMemu"]').val(value);
                    //  $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //  $('#itemUnit').text(value);

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                      $('select[name="itemUnitMenu"]').empty();
                      $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
    });

    //--------------// get เลข itemId
    $('select[name="itemNameMenu"]').on('change', function(){
        var itemId = $(this).val();
        if(itemId) {
            $.ajax({
                url: 'itemId/get/'+itemId,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                      $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
                  $.each(data, function(key, value){
                    $('input[name="itemIdMemu"]').val(key+'-'+value);
                    //  $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //  $('#itemUnit').text(value);

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                      $('select[name="itemUnitMenu"]').empty();
                      $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
    });

    //--------------// get เลข จัดซื้อจัดจ้าง
    $('select[name="type"]').on('change', function(){
        var type = $(this).val();
        if(type) {
            $.ajax({
                url: 'type/get/'+type,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
                  $.each(data, function(key, value){

                    $('input[name="purchaseId"]').val((value*1)+1);

                    //  $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //  $('#itemUnit').text(value);

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
    });

    //--------------// get วันที่ จัดซื้อจัดจ้าง
    $('select[name="type"]').on('change', function(){
        var type = $(this).val();
        if(type) {
            $.ajax({
                url: 'date/get/'+type,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                  //  alert(data);
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
                  $.each(data, function(key, value){

                    $('input[name="dateOfPurcase"]').val(value);
				//	$('input[name="dateOfPurcase2"]').val(value);
                    $('input[name="dateOfPurcase"]').datepicker("setStartDate", value);
                    //  $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //  $('#itemUnit').text(value);

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
    });

    //--------------// get ชนิดของพัสดุในหน้าทะเบียนพัสดุ
    $('select[name="itemName"]').on('change', function(){
        var url = $(this).val();
        window.location = '/purchase/home/items/'+url;
      //  alert(url);
    });
	
	
	    //--------------// ดึงเลข document date
    $('select[name="documenttype"],select[name="workgroups"]').on('change', function(){
 var documenttype = $("#documenttype").val();
 var workgroups = $("#workgroups").val();
 
 
	if(documenttype == 1)
	{	$("#documentto").val("");
		$('#documentto').prop('readonly', true);
	}
	else if(documenttype == 2)
	{
		
		$('#documentto').prop('readonly', false);
		
	}
	else
	{	$("#documentto").val("");
		$('#documentto').prop('readonly', true);
	}
 
 
 
        if(documenttype) {
            $.ajax({
                url: '../getdocumentdate/'+documenttype+'/'+workgroups,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
					//alert("lppplll");
					
                },
				
                success:function(data) {

                  $.each(data, function(key, value){

                      $('input[name="documentDate"]').val(value);
                    $('input[name="documentDate"]').datepicker("setStartDate", value);

   

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
  
				
				
				
    });
	
	
	
	
	    //--------------// get documentid
 $('select[name="documenttype"],select[name="workgroups"]').on('change', function(){
 var documenttype = $("#documenttype").val();
 var workgroups = $("#workgroups").val();
 

 
 
 
 
 
 
        if(documenttype) {
            $.ajax({
                url: '../getdocumentid/'+documenttype+'/'+workgroups,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
					//alert("lppplll");
					
                },
				
                success:function(data) {
                    
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
                  $.each(data, function(key, value){

                    $('input[name="documentId"]').val((value*1)+1);

                    //  $('select[name="itemUnitMenu"]').append('<option value="'+ key +'">' + value + '</option>');
                  //  $('#itemUnit').text(value);

                  });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
                    //  $('select[name="itemUnitMenu"]').empty();
                    //  $('select[name="itemUnitMenu"]').append('<option value="">หน่วย</option>');
        }
  
				
				
				
    });
	
	
	
	
	
	
	
	

});

/// ฟังชั่นการบวก ตัตัวเลขในฟอม
function calculateSum() {
  //------------ นำค่าแต่ละแถวมารวมกัน

    var $input = $(this);
    var $row = $input.closest('tr');
    var sum = 1;
      $row.find(".expenses").each(function() {
        sum = sum * parseFloat(this.value) || 0;
        //  alert(sum);
      });

      $row.find(".expenses_sum").val(sum.toFixed(2));
    //-------------- นำค่ามารวมกันทั้งหมด
    var test_qty = 0;
      $(".expenses_sum").each(function(){
        test_qty += parseFloat($(this).val())||0;
        //$("#App_tax_No3").val(test_qty.toFixed(2));
        $('#App_tax_No3').autoNumeric('set', test_qty)
      });
   // ------ คิดภาษี
    var tax = $("#purchaseTax").val();
   // var sumtax =  test_qty*tax/100||0;
	//var sumtax = test_qty-(test_qty*tax/107);
   // var itemprice = test_qty-sumtax || 0;

		var itemprice = test_qty-(test_qty*tax/107);
		var sumtax = test_qty-itemprice;


      $('#App_tax_No1').autoNumeric('set', itemprice)
      $('#App_tax_No2').autoNumeric('set', sumtax)
      if($('#App_tax_No1').val()==0)
      {
        $('#App_tax_No3').autoNumeric('set', 0);
      }
}
$(document).ready(function(){
$("#App_tax_No3").autoNumeric("init", {
digitGroupSeparator: '.',
decimalCharacter: ',',
currencySymbol: '€ '
});
$("#App_tax_No2").autoNumeric("init", {
digitGroupSeparator: '.',
decimalCharacter: ',',
currencySymbol: '€ '
});
  $("#App_tax_No1").autoNumeric("init", {
digitGroupSeparator: '.',
decimalCharacter: ',',
currencySymbol: '€ '
});
});


function formcheck() {
	  var x = $("#workgroups");
    if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#workgroups").focus();
        return false;
    }
	  var x = $("#commandId");
    if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#commandId").focus();
        return false;
    }
  var x = $("#purchaseId");
    if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#purchaseId").focus();
        return false;
    }
    var x = $("#purchasetype2");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }
      var x = $("#dateOfPurcase");
        if (x.val() == "") {
            alert("กรุณากรอกข้อมูลให้ครบ !");
            x.focus();
            return false;
        }
    var x = $("#purchaseName");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }

      var x = $("#purchaseReason");
        if (x.val() == "") {
            alert("กรุณากรอกข้อมูลให้ครบ !");
            x.focus();
            return false;
        }
        if($("#tblUser tr").length < 2)
        {
          alert("กรุณาเพิ่มกรรมการตรวจรับ !");
          return false;
        }
     if($("#tblData tr").length < 2)
     {
       alert("กรุณาเพิ่มวัสดุ/ครุภัณฑ์ !");
       return false;
     }
     if($('#App_tax_No3').val()==0)
     {
       alert("ราคาของวัสดุ/ครุภัณฑ์ต้องมากกว่า 0 !");
       return false;
     }
}

function formcheck2() {
    var x = $("#purchaseAcceptSlip");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }
}
function formcheck3() {
    var x = $("#itemIdMemu");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }
      var x = $("#itemCategoryMenu");
        if (x.val() == "") {
            alert("กรุณากรอกข้อมูลให้ครบ !");
            x.focus();
            return false;
        }
        var x = $("#itemNameMenu");
          if (x.val() == "") {
              alert("กรุณากรอกข้อมูลให้ครบ !");
              x.focus();
              return false;
          }
          var x = $("#ItemDetail");
            if (x.val() == "") {
                alert("กรุณากรอกข้อมูลให้ครบ !");
                x.focus();
                return false;
            }
            var x = $("#bItemNumber");
              if (x.val() == "") {
                  alert("กรุณากรอกข้อมูลให้ครบ !");
                  x.focus();
                  return false;
              }
              var x = $("#bItemPrice");
                if (x.val() == "") {
                    alert("กรุณากรอกข้อมูลให้ครบ !");
                    x.focus();
                    return false;
                }
								  var x = $("#itembudgettype");
                  if (x.val() == "") {
                      alert("กรุณากรอกข้อมูลให้ครบ !");
                      x.focus();
                      return false;
                  }
                var x = $("#type");
                  if (x.val() == "") {
                      alert("กรุณากรอกข้อมูลให้ครบ !");
                      x.focus();
                      return false;
                  }
				 


}
function formcheckprocess() {
    var x = $("#processdate");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	      var x = $("#process");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  var x = $("#budget");
      if (x.val() == "" ) {
		  
		    //var regex=/^[0-9]+$/;
			//if (x.match(regex))
			//{
			//	alert("Must input numbers");
			//	x.focus();
			//	return false;
			//}
		  alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	  	  var x = $("#budget");		  
		  var regex=/^[0-9]+$/;
		  var y =x.val();
		 // alert(y.match(regex));
			if (!y.match(regex))
			{
				alert("Must input numbers");
				x.focus();
				return false;
			}
		  var x = $("#budget");
		  var z = $("#budgetleft");
		  var regex=/^[0-9]+$/;
		  var y =x.val();
		 // alert(y.match(regex));
			if (!y.match(regex))
			{
				alert("Must input numbers");
				x.focus();
				return false;
			}
			if(Number(x.val()) > Number(z.val()))
			{
				alert("งบประมาณที่เหลือไม่เพียงพอ..");
				x.focus();
				return false;
			}
	  
}
function formcheckrepairlog() {
    var x = $("#dateofrepair");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	      var x = $("#text");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  	      var x = $("#itemplace");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  var x = $("#price");
      if (x.val() == "" ) {
		  
		    //var regex=/^[0-9]+$/;
			//if (x.match(regex))
			//{
			//	alert("Must input numbers");
			//	x.focus();
			//	return false;
			//}
		  alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	  	  var x = $("#price");		  
		  var regex=/^[0-9]+$/;
		  var y =x.val();
		 // alert(y.match(regex));
			if (!y.match(regex))
			{
				alert("Must input numbers");
				x.focus();
				return false;
			}

      
	  
	  
	     var x = $("#budget");
      if (x.val() == "0") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
    var x = $("#workgroups");
      if (x.val() == "0") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }	  
	  
	  
	  
	  
	  
	  
}

function formcheckprocessto() {
    var x = $("#processdate");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	      var x = $("#processfrom");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  	      var x = $("#processto");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  var x = $("#processfrom");
	  var y = $("#processto");
	  
	  if (x.val() == y.val()) {
          alert("กรุณากรอกข้อมูลให้ถูกต้องห้ามเลือกโครงการเดียวกัน !");
          x.focus();
          return false;
      }
	  
	  var x = $("#budget");
      if (x.val() == "" ) {
		  
		    //var regex=/^[0-9]+$/;
			//if (x.match(regex))
			//{
			//	alert("Must input numbers");
			//	x.focus();
			//	return false;
			//}
		  alert("กรุณากรอกข้อมูลให้ถูกต้อง !");
          x.focus();
          return false;
      }
	  
	  	 		  var x = $("#budget");
		  var z = $("#budgetleft");
		  var regex=/^[0-9]+$/;
		  var y =x.val();
		 // alert(y.match(regex));
			if (!y.match(regex))
			{
				alert("Must input numbers");
				x.focus();
				return false;
			}
			if(Number(x.val()) > Number(z.val()))
			{
				alert("งบประมาณที่เหลือไม่เพียงพอ..");
				x.focus();
				return false;
			}
}



function formcheckturakan() {
    var x = $("#documenttype");
    if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#documenttype").focus();
        return false;
    }
	var x = $("#workgroups");
	if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#workgroups").focus();
        return false;
    }
		var x = $("#documentDate");
	if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#documentDate").focus();
        return false;
    }
		var x = $("#document");
	if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#document").focus();
        return false;
    }
	
	
}
function formcheckproject() {
    var x = $("#projectName");
    if (x.val() == "") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#projectName").focus();
        return false;
    }
	var x = $("#workgroups");
	if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#workgroups").focus();
        return false;
    }
	var x = $("#projectBudgetofschool");
	if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#projectBudgetofschool").focus();
        return false;
    }
		  	  var x = $("#projectBudget");		  
		  var regex=/^[0-9]+$/;
		  var y =x.val();
		 // alert(y.match(regex));
			if (!y.match(regex))
			{
				alert("Must input numbers");
				x.focus();
				return false;
			}
			if(y <=0)
			{
				alert("Input must more then zero");
				x.focus();
				return false;
			}
			
			
			
		var x = $("#projectYear");
	if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#projectYear").focus();
        return false;
    }
		var x = $("#projectTerm");
	if (x.val() == "0") {
        alert("กรุณากรอกข้อมูลให้ครบ !");
        $("#projectTerm").focus();
        return false;
    }
	
	
}
function formcheckbudgetofschool() {
    var x = $("#budgettype");
      if (x.val() == "0") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }
	 var x = $("#budgetofschool");
      if (x.val() == "") {
          alert("กรุณากรอกข้อมูลให้ครบ !");
          x.focus();
          return false;
      }
}
//------ ลิสทะเบียนครุภัณ์

$(document).ready(function(){
    $('select[name="itemTypeMenu"]').on('change', function(){
    var cat = $("#itemTypeMenu").val();
    //var items = $('#itemNameMenu').val();
    //alert(cat);
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'itemlib3/'+cat,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#productData").html(response);
      }
    });
  });
});

$(document).ready(function(){
    $('select[name="itemCategoryMenu"]').on('change', function(){
    var cat = $("#itemCategoryMenu").val();
    //var items = $('#itemNameMenu').val();
    //alert(cat);
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'itemlib2/'+cat,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#productData").html(response);
      }
    });
  });
});
$(document).ready(function(){
    $('select[name="itemNameMenu"]').on('change', function(){
    var cat = $("#itemCategoryMenu").val();
    var items = $('#itemNameMenu').val();
  //  alert();
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'itemlib1/'+items,
      data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#productData").html(response);
      }
    });
  });
});


//------ ลิสผู้ค้า
$(document).ready(function(){
    $('select[name="partnerType"]').on('change', function(){
    var cat = $("#partnerType").val();


    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'partner1/'+cat,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#partnerData").html(response);
      }
    });
  });
});

//------ ลิสผู้ค้า
$(document).ready(function(){
    $('input[name="partnerId"]').on('keyup', function(){
    var word = $("#partnerId").val();


    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'partnerId/'+word,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#partnerData").html(response);
      }
    });
  });
});


//------ ลิสitemtype
$(document).ready(function(){
    $('select[name="itemtype"]').on('change', function(){
    var cat = $("#itemtype").val();

  //  alert();
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'itemtype1/'+cat,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#itemData").html(response);
      }
    });
  });
});

//------ ลิสitemtype
$(document).ready(function(){
    $('input[name="itemtypeNo"]').on('keyup', function(){
    var word = $("#itemtypeNo").val();
    var word2 = $("#itemtype").val();

    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'itemtypeNo/'+word+'/'+word2,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#itemData").html(response);
      }
    });
  });
});



//------ ลิสitem
$(document).ready(function(){
    $('select[name="itemType"]').on('change', function(){
    var cat = $("#itemType").val();

    //alert();
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'item1/'+cat,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#itemData").html(response);
      }
    });
  });
});

//------ ลิสitem
$(document).ready(function(){
    $('select[name="itemCategory"]').on('change', function(){
    var cat = $("#itemType").val();
    var cat2 = $('#itemCategory').val();

  //  alert(cat2);
    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'item2/'+cat+'/'+cat2,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#itemData").html(response);
      }
    });
  });
});

//------ ลิสitem
$(document).ready(function(){
    $('input[name="itemCategoryID"]').on('keyup', function(){
    var word = $("#itemType").val();
    var word2 = $("#itemCategory").val();
    var word3 = $('#itemCategoryID').val();

    $.ajax({
      type: 'get',
      dataType: 'html',
      url: 'item3/'+word+'/'+word2+'/'+word3,
    //  data: 'cat_id=' + cat + '&items=' + items,
      success:function(response){
        console.log(response);
        $("#itemData").html(response);
      }
    });
  });
});

//------ คำนวนค่าของสิ่งก่อสร้าง
$(document).ready(function(){

    $('input[name="bItemNumber"]').on('keyup', function(){
    var bItemNumber = $("#bItemNumber").val();
    var bItemPrice = $("#bItemPrice").val();
    var bItemTotel = parseFloat(bItemNumber)*parseFloat(bItemPrice);
    //$('#bItemTotel').val(bItemTotel.toFixed(2));
    $('#bItemTotel').autoNumeric('set', bItemTotel)
  });

  $('input[name="bItemPrice"]').on('keyup', function(){
  var bItemNumber = $("#bItemNumber").val();
  var bItemPrice = $("#bItemPrice").val();
  var bItemTotel = parseFloat(bItemNumber)*parseFloat(bItemPrice);
  $('#bItemTotel').autoNumeric('set', bItemTotel)
  });

  $("#bItemTotel").autoNumeric("init", {
  digitGroupSeparator: '.',
  decimalCharacter: ',',
  currencySymbol: '€ '
  });

  //$('select').selectpicker();

});
