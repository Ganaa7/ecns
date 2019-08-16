<?php
/*
 * This function add or change item orders
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
   function showOrderItems(order_id){
       var xmlhttp;    
       if (order_id==="")
       {
          document.getElementById("tableOrder").innerHTML="";
          return;
       }
       if (window.XMLHttpRequest)
       {// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
       }
       else
       {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
       }
       xmlhttp.onreadystatechange=function()
       {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
         {
            document.getElementById("tableOrder").innerHTML=xmlhttp.responseText;
         }
        }
       xmlhttp.open("GET","/ecns/wm_ajax/getOrderItem?order_id="+order_id,true);
       xmlhttp.send();        
    }
    
   function orderTask(pk_id){
      // here my functions
      var employee='employee_id'+pk_id;
      var employee_id=document.getElementById(employee);
      if(employee_id.value!=='0')
         document.orderItem.submit();
         //document.location='/ecns/warehouse/orderTask';
      else
           alert('Үүрэг оноох ажилтаныг сонгоно уу?');     
   }
   
   //after page onload call this
   $(function() {
       $( "#regdate" ).datepicker({ dateFormat: "yy-mm-dd" });
       var comment, order_no=$('#orderno'); regdate=$('#regdate');
      //call dialog
      $( "#dialog_c" ).dialog({
         autoOpen: false,
         width: 340,
         modal:true,
         buttons: [
            {
                text: "Хадгалах",
                click: function() {
                   //call
                   comment=$('#cancel_comment').val();
                   $('#comment').val(comment);                                         
                   $('#orderstatus').submit();
                   $( this ).dialog( "close" );
                }
            },
            {
                text: "Болих",
                click: function() {
                   $( this ).dialog( "close" );
                }
            }
         ]
       });
      //close dialog
      // Register Dialog
        $( "#dialog_r" ).dialog({
         autoOpen: false,
         width: 340,
         modal:true,
         buttons: [
            {
                text: "Хадгалах",
                click: function() {
                   //validate for dialog register
                   if( order_no.val()==null||order_no.val()==''||regdate.val()==null||regdate.val()==''){
                       alert('Захиалгийн дугаар эсвэл Огноо хоосон байна. Утгуудыг оруулна уу!');
                   }else{
                      if($('#action').val()=='register'){
                         $("#taskby_id").val($("#steward_id").val());
                         $("#order_no").val(order_no.val());                   
                         $("#order_date").val(regdate.val());
                      } 
                      $('#orderstatus').submit();
                      $( this ).dialog( "close" );
                   }
                }
            },
            {
                text: "Болих",
                click: function() {
                   $( this ).dialog( "close" );
                }
            }
         ]
       });
      //close dialog
      
      // Call dialog
      $( ".cancel" ).click(function( event ) {
         var hstr=$(this).attr('href');
         $("#status_id").val(hstr.substring(1, hstr.indexOf('/')));
         $("#order_id").val(hstr.substring(hstr.indexOf('/')+1, hstr.length));
         $( "#dialog_c" ).dialog( "open" );
         event.preventDefault();
      });
      // Call Register Dialog
      $( ".register" ).click(function( event ) {
         var hstr=$(this).attr('href');
         $("#status_id").val(hstr.substring(1, hstr.indexOf('/')));
         $("#order_id").val(hstr.substring(hstr.indexOf('/')+1, hstr.length)); 
         $("#action").val("register");
         $( "#dialog_r" ).dialog( "open" );
         event.preventDefault();
      });  
      
      $( ".approve" ).click(function( event ) {
         var hstr=$(this).attr('href');
         $("#status_id").val(hstr.substring(1, hstr.indexOf('/')));
         $("#order_id").val(hstr.substring(hstr.indexOf('/')+1, hstr.length));
         $('#orderstatus').submit();
      });   

   });
</script>
<? if($this->session->userdata('message')) { ?>
<div id="message" align="center">
	<p>
  <?php
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<? } ?>

<div style="position: absolute; top: 20px; right: 20px;">
	<input type='button' name='income' value="Захиалга өгөх"
		title='Сэлбэгийг захиалга өгөхөд ашиглана.'
		onclick="javascript:document.location='/ecns/warehouse/orderpage'" />
</div>

<form name="order" id="orderstatus" method="post"
	action="/ecns/warehouse/orderStatus" />
<div style="margin: 40px 25px 20px">
	<h4>Захиалгууд</h4>
	<table class="wm_table">
		<thead>
			<tr>
				<th title="Захиалгын дугаар">Захиалгa №</th>
				<th title="Захиалга авсан огноо">Огноо</th>
				<th title="Захиалга хийсэн хэсэг">Хэсэг</th>
				<th title="Захиалгийн тухай товч мэдээлэл">Захиалга</th>
				<th title="Захиалсан инженер">Захиалсан</th>
				<th title="Хариуцсан хангамжийн инженер">Хариуцсан</th>
				<th title="Тэмдэглэсэн мэдээлэл">Тэмдэглэл</th>
				<th title="Захиалгийн төлөвийн мэдээлэл">Төлөв</th>   
            <? if(($role=='CHIEF'&&$sec_code=='SUP')||$role=='CHIEFENG'){ ?><th
					title="Бүртгэх, Цуцлах гм үйлдүүд">Үйлдэл</th>
            <? } ?>
        </tr>
		</thead>
		<tbody>
            <?  ?>
        </tbody>

	</table>
	<input type="hidden" id="order_id" name="order_id" /> <input
		type="hidden" id="status_id" name="status_id" /> <input type="hidden"
		id="comment" name="comment" /> <input type="hidden" id="taskby_id"
		name="taskby_id" /> <input type="hidden" id="order_no" name="order_no" />
	<input type="hidden" id="order_date" name="order_date" /> <input
		type="hidden" id="action" />
</div>
<script type="text/javascript">
    function cancelOrder(){  
        var answer = confirm("Та энэ захиалгийн үнэхээр цуцлах уу?\n Цуцалсан тохиолдолд захиалгийн жагсаалтаас устах болно.");
        if(answer){
           document.orderSave.submit();
        }else                            
           document.location="/ecns/warehouse/orderSave";
   }
</script>
</form>

<!--<form name="orderItem" action="/ecns/warehouse/orderTask" method="post">
<div id="tableOrder">
    
</div>
</form>
<div align="right" style="width:80%; ">
    <input type="button" value="Үүрэг оноох" name="taskorder" onclick="javascript:document.order.submit();"/>
</div>-->
<!-- Call dialog -->
<div id="dialog_c" class="order" title="Захиалга цуцлах">
	<form name="cancel_order" method="post" id="cancel_order"
		action="/ecns/warehouse/orderlist/" />
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<p>Тайлбар:</p>
		<textarea name="comment" id="cancel_comment" rows="7" cols="37" /></textarea>
	</div>
	</form>
</div>
<!-- Order register-->
<div id="dialog_r" class="register" title="Захиалга бүртгэх">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td><strong>Захиалга №:</strong></td>
				<td><input type="input" name="orderno" id="orderno"
					value="<? echo $order_no; ?>" size="6" /></td>
			</tr>
			<tr>
				<td><strong>Захиалга огноо:</strong></td>
				<td><input type="input" id="regdate" name="regdate" size="12" /></td>
			</tr>
			<tr>
				<td><strong>Хариуцсан Хан/Инж:</strong></td>
				<td>
                    <? echo form_dropdown('steward_id', $steward, null, "id='steward_id'"); ?>  
                    </td>
			</tr>
		</table>
	</div>
</div>
