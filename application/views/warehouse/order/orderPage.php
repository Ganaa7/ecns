<?
/*
 * 6/17/2013
 * I can do it through C
 * Захиалгийн хуудас
 */
?>
<script type="text/javascript">
$(function() {  
    $( "#order_date" ).datepicker({ dateFormat: "yy-mm-dd" }); 
    var part=$('#part_number'), spare=$('#spare'), spare_id=$('#spare_id'), measure=$('#measure'), qty=$('#qty'), reason=$('#reason');
    var append_txt, inputStr;
    
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 450,
        modal:true,
        buttons: [
        {
             text: "Гүйцсэн",
             click: function() {
                var i;
                //validate spare, qty, reason
                if(spare.val()==0){                       
                   alert("Нэг сэлбэг сонгоно уу?");
                   spare.focus();
                }else if(qty.val()==0||qty.val()==null||(typeof qty.val() !== 'number' && qty.val() % 1 !== 0)){
                   alert("Тоо хэмжээг оруулна уу? Бүхэл тоогоор оруулна уу!");
                   qty.focus();
                }else{
                   // add spare, qty, reason to input in lists
                   $("#remove_tr").remove();      
                   if($("#count").val()>0){ i =$("#count").val(); } else i =0;
                   i++;                 
                   $("#count").val(i);
                   //inputStr ="<input type='hidden' id='count' name='count' value='"+i+"'>";
                   inputStr ="<input type='hidden' name='spare[]' id='spare_"+i+"' value='"+spare.val()+"'>";
                   inputStr +="<input type='hidden' name='qty[]' id='qty_"+i+"' value='"+qty.val()+"'>";
                   inputStr +="<input type='hidden' name='measure[]' id='measure_"+i+"' value='"+measure.val()+"'>";
                   inputStr +="<input type='hidden' name='reason[]' id='reason_"+i+"' value='"+reason.val()+"'>";
                   appent_txt ="<tr id="+i+"><td>"+i+"</td><td>"+spare.val()+"</td><td>"+measure.val()+"</td><td>"+qty.val()+"</td><td>"+reason.val()+"</td></tr>"+inputStr;
                   $("#orderTable").append(appent_txt);
                   
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

    // Link to open the dialog
    $( "#dialog-link" ).click(function( event ) {
        $( "#dialog" ).dialog( "open" );
        event.preventDefault();
    });

     // Hover states on the static widgets
    $( "#dialog-link, #icons li" ).hover(
        function() {
           $( this ).addClass( "ui-state-hover" );
        },
        function() {
           $( this ).removeClass( "ui-state-hover" );
        }
    );

    $(function() {
        $( document ).tooltip();
    });   

    $('#dialog-link').click(function(){
       spare.val("");       
       measure.val("");
       qty.val("");
       reason.val("")
    });

//validation-g codeigniter-r hiih heregtei ba :D    
// $( "#order" ).click(function( event ) {
//        // if(order_no==='0'||order_no===null){
//     	  // alert('Захиалгын дугаар өгөөгүй байна! Захиалгийн дугаар давтагдахгүй дугаар байх ёстойг анхаарна уу!');
//        //    return false;
//        // }
//        //  if(count==='0'||count===null){
//        //    alert('Сэлбэг нэмээгүй байна! [Сэлбэг + ] дээр дарж сэлбэгийг нэмнэ үү!');
//        //    return false;
//        // }
//        //  if(section==='0'||section===null){
//        //    alert('Хэсэг сонгогдоогүй! \n Захиалга өгсөн хэсгийг сонгоно уу!');          
//        //    return false;
//        // }
//        //  if(order_date==='0'||order_date===null){
//     	  //  alert('Захиалгын огноо сонгогдоогүй байна! \n Захиалгын огноог сонгоно уу!');          
//        //     return false;
//        // }else document.orderpage.submit();
//        var order_no =$('#order_no').val();
//        var count=$('#count').val();
//        var section=$('#section_id').val();
//        var order_date=  $('#order_date').val();
//     	 var order_form= $('#orderpage');
//      	 var data = {};
//        var inputs = $('input[type="text"], input[type="hidden"], select');                        
//        inputs.each(function(){
//            var el = $(this);
//            data[el.attr('name')] = el.val();
//        });
                
// 		  $.ajax({
//            type:     'POST',
//            url:    '/ecns/wm_ajax/InsertOrder',           
//            data:   data,
//            dataType: 'json', 
//            success:  function(json){ 
//               if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
//               //энд үндсэн утгуудыг нэмэх болно.
//                  $('p.feedback', order_form).removeClass('error').html('').hide();
//                  //window.location.href = "/ecns/warehouse/order";
// 	               showMessage(json.message, 'success');
//                  // show the success message
//               }else{  // ямар нэг юм нэмээгүй тохиолдолд
//                  $('p.feedback', order_form).removeClass('success, notify').addClass('error').html(json.message).show();
//               }
//             }            	  
//          });
//     });


   $( "#spare" ).autocomplete({
       source: "spareJson",
       minLength: 2,
       select: function( event, ui ) {
          if(ui.item.value){             
             spare_id.val(ui.item.id);
             part.text(ui.item.part);
             measure.val(ui.item.measure);             
          }
       },
       search:function( event, ui){
          part.text("");
          spare_id.val(0);
          measure.text("");
          qty.val("");
       }
    });   
});
</script>
<style type="text/css">
#orderpage label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
</style>
<? if($this->session->userdata('message')) {  ?>
<div id="message" align="center">
	<p>
  <?
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<? } ?>

<div align="center">
	<form method="post" name="orderpage" id="orderpage"
		action="/ecns/warehouse/insOrder">
		<!-- action ="/ecns/warehouse/insOrder" -->
		<p class="feedback"></p>
		<div style='margin-bottom: 0px; margin-top: 15px;'>
			<span align="center"><h3>
					ЗАХИАЛГА ӨГӨХ ХУУДАС №<input name="order_no" id="order_no"
						type="text" size="2" />
				</h3></span>
			<div align="right" style="margin-right: 50px;">
				<input type="button" id="dialog-link" value="Сэлбэг +" />
			</div>
		</div>
		<div align="left" style="margin: 10px 20px;">
  <?php
		$section = $this->main_model->get_section ( 'Y' );
		echo "Захиалга өгсөн хэсэг:";
		echo form_dropdown ( 'section_id', $section, 0, "id=section_id" );
		?>
  <span>Захиалга өгсөн огноо:</span> <input id="order_date"
				name="order_date" type="text" size="8" />
		</div>
		<table border="1" id="orderTable" width="97%" height="100"
			cellpadding="5" cellspacing="0">
			<tr valign="middle" id="orderlist">
				<th width="16" height="29">№</th>
				<th width="180" align="center">ШААРДЛАГАТАЙ ТОНОГ ТӨХӨӨРӨМЖ, СЭЛБЭГ,
					ХЭРЭГСЭЛ, НЭР МАРК</th>
				<th width="10">ХЭМЖИХ</br> НЭГЖ
				</th>
				<th width="10">ТОО</br>ШИРХЭГ
				</th>
				<th width="250">ШААРДАХ БОЛСОН ҮНДЭСЛЭЛ:<br />/ХААНА ЯМАР ЗОРИЛГООР
					ХЭРЭГЛЭХИЙГ ТОДОРХОЙ БИЧНЭ./
				</th>
			</tr>
			<tr id="remove_tr">
				<td colspan="8"></td>
			</tr>
		</table>
		<input type="hidden" id="count" name="count" value="0" /> <label>Захиалга
			бүртгэсэн:</label> <span class="label"><?php echo $this->session->userdata('fullname');?></span>
		<p align="right">
			<input type="submit" value="Захиалга өгөх" name="order" id="order"> <input
				type="button" value="Болих" name="cancel"
				onclick="javascript:document.location='/ecns/warehouse/order'" />
		</p>
	</form>
</div>

<div id="dialog" title="Сэлбэг +">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Сэлбэгийн нэр:</td>
				<td><input type="text" id="spare" size="40" /> <span
					style="font-style: italic">Тоног төхөөрөмжийн нэрийг (дотор) бичнэ
						үү</span></td>
			</tr>
			<tr>
				<td>Хэмжих нэгж:</td>
				<td>
              <?php 
// echo form_dropdown('measure', $measure, 0, "id=measure");
														?> 
             <input name="measure" id="measure" title="ширхэг, метр гм" />
				</td>
			</tr>
			<tr>
				<td>Тоо ширхэг:</td>
				<td><input id="qty" type="text" title='' /></td>
			</tr>
			<tr>
				<td style="vertical-align: top;">Шаардагдах болсон үндэслэл:</td>
				<td><textarea name="reason" id="reason"
						title='Хаана ямар зорилгоор хэрэглэхийг тодорхой бичнэ!' /></textarea></td>
			</tr>

		</table>
	</div>
</div>
