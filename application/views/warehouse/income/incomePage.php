<?
/*
 * 6/17/2013
 * I can do it through C
 * Захиалгийн хуудас
 */
?>
<style>
textarea.reason {
	height: 8em;
	width: 80%;
}

label.no {
	padding: 20px 5px 0 0;
	margin: 0;
}
</style>
<script type="text/javascript">
$(function() {
    var part=$('#part_number'), spare=$('#spare'), spare_id=$('#spare_id'), measure=$('#measure'), qty=$('#qty');
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
                if(measure.text==""||measure.text==null){                       
                   alert("Сэлбэг сонгогдоогүй байна. Сэлбэгийн нэр хэсэгт бичихэд гарч ирэх Сэлбэгээс сонго!");
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
                   inputStr ="<input type='hidden' id='spare_id_"+i+"' name='spare_id[]' value='"+spare_id.val()+"'>";
                   inputStr +="<input type='hidden' id='qty_"+i+"' name='qty[]' value='"+qty.val()+"'>";
                   appent_txt ="<tr id="+i+"><td>"+i+"</td><td>"+spare.val()+" Парт дугаар:"+part.text()+"</td><td>"+measure.text()+"</td><td>"+qty.val()+"</td></tr>"+inputStr;
                   $("#incomePage").append(appent_txt);                   
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
    $( "#spare" ).autocomplete({
       source: "spareJson",
       minLength: 2,
       select: function( event, ui ) {
          if(ui.item.value){             
             spare_id.val(ui.item.id);
             part.text(ui.item.part);
             measure.text(ui.item.measure);
          }
       },
       search:function( event, ui){
          part.text("");
          spare_id.val(0);
          measure.text("");
          qty.val("");
       }
    });
    
     //tooltip
     $( document ).tooltip({
        track: true
     });
     
     //datepicker
     $( "#datepicker" ).datepicker(
             { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true });
});
//
//function rm_Row(row){
//   var count =$("#count").val();   
//   $('#'+row).remove();   
//   $('#count').val(--count);
//   
//   $('#spare_id_'+row).remove();
//   $('#qty_'+row).remove();   
//}
</script>
<?
if ($this->session->userdata ( 'message' )) {
	?>
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
	<form method="post" action="/ecns/warehouse/incomeDetail"
		name="incomePage">
		<table align="center" border='0' cellpadding='5' cellspacing='0'
			width='95%'>
			<tr>
				<td align="center" colspan='2'><h3>
						ОРЛОГЫН ПАДААН №<input type='text' size='3' name='income_no' /></td>
			</tr>
			<tr>
				<td rowspan='3' align='left' valign='middle'><label class='no'>Гүйлгээний
						утга:</label>
				<textarea class="reason" cols="80" rows="6" name="purpose" /></textarea>
				</td>
				<td><label>Орлогод авсан огноо:</label> <input type="text"
					name="income_date" id="datepicker" size="8" /></td>
			</tr>
			<tr>
				<td><label>Бэлтгэн нийлүүлэгч:</label> <?=form_dropdown('supplier_id', $supplier, 0, "style='width:140px' title='Нийлүүлэгч жагсаалтад байхгүй тохиолдолд Тохиргоо->Нийлүүлэгч хэсэгт шинээр нэмнэ үү!'");?>
          </td>
			</tr>
		</table>
		<div align="right" style="margin-right: 50px;">
			<input type="button" id="dialog-link" value="Сэлбэг +" />
		</div>
		<table border='1' id='incomePage' width="97%" height="100"
			cellpadding="5" cellspacing="0">
			<tr valign="middle">
				<th width="16" height="29">Д/д</th>
				<th width="180" align="center">Сэлбэгийн, барааны нэр</th>
				<th width="10">Хэмжих</br> нэгж
				</th>
				<th width="10">Тоо</br>ширхэг
				</th>
			</tr>
			<tr id="remove_tr">
				<td colspan="8"></td>
			</tr>
		</table>
		<input type='hidden' id='count' name='count' value="0"> </span>
		<div>
			<label>Бараа олгосон нярав:</label> <span class="label"><? echo $this->session->userdata('fullname');?></span>
		</div>
		<div>
			<label>Хүлээн авсан:</label> <span class="label"><? echo $this->session->userdata('fullname');?></span>
		</div>
		<div>
			<label>Шалгасан нягтлан бодогч:</label> <span class="label"><? echo form_dropdown('accountant_id', $accountant, 0, "style='width:140px'"); ?></span>
		</div>
		<p align="right">
			<input type="submit" value="Тавиурт тавих" name="order" /> <input
				type="button" value="Болих" name="cancel"
				onclick="javascript:document.location='/ecns/warehouse/income'" />
		</p>
	</form>
	<script language="JavaScript" type="text/javascript"
		xml:space="preserve">
       var chk_incPage  = new Validator("incomePage");                    
       chk_incPage.addValidation("income_no","req", "Орлогийн дугаарыг оруулна уу!");
       chk_incPage.addValidation("income_date","req", "Орлогод авсан огноог оруулна уу!");
       chk_incPage.addValidation("reason","req", "Гүйлгээний утгийг оруулна уу!");       
    </script>
	<div id="dialog" title="Сэлбэг +">
		<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
			<table>
				<tr>
					<td>Сэлбэгийн нэр:</td>
					<td><input type="text" id="spare" size="40" /></td>
				</tr>
				<tr>
					<td>Парт дугаар:</td>
					<td><span type="text" id="part_number" /></span></td>
				</tr>
				<tr>
					<td>Хэмжээс:</td>
					<td><span type="text" id="measure" /></span></td>
				</tr>
				<tr>
					<td>Тоо хэмжээ:</td>
					<td><input id="qty" size="6" type="text" /></td>
				</tr>
				<input type="hidden" id="spare_id" />
			</table>
		</div>
	</div>