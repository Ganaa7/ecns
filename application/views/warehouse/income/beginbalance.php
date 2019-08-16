<?
/*
 * 6/17/2013
 * I can do it through C
 * Захиалгийн хуудас
 */
?>
<style>
textarea.reason {
	height: 4em;
	width: 50%;
}

#beginbalance label.error {
	font-style: italic;
	color: red;
	margin-left: 10px;
	width: auto;
	display: inline;
}

#newsletter_topics label.error {
	display: none;
	margin-left: 103px;
}
</style>
<script type="text/javascript">
$(function() {
    var part=$('#part_number'), spare=$('#spare'), spare_id=$('#spare_id'), measure=$('#measure'), qty=$('#qty');
    var i=1, append_txt, inputStr;
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 450,
        modal:true,
        buttons: [
        {
             text: "Гүйцсэн",
             click: function() {
                //validate spare, qty, reason
                if(spare.val()==0||spare.val==null||spare_id==0||spare_id==null){                       
                   alert("Нэг сэлбэг сонгоно уу?");
                   spare.focus();
                }else if(qty.val()==0||qty.val()==null||(typeof qty.val() !== 'number' && qty.val() % 1 !== 0)){
                   alert("Тоо хэмжээг оруулна уу? Бүхэл тоогоор оруулна уу!");
                   qty.focus();
                }else{
                   // add spare, qty, reason to input in lists
                   $("#remove_tr").remove();     
                   $("#count").val(i);
                 //  inputStr ="<input type='hidden' id='count' name='count' value='"+i+"'>";
                   inputStr ="<input type='hidden' id='spare_id_"+i+"' name='spare_id[]' value='"+spare_id.val()+"'>";
                   inputStr +="<input type='hidden' name='qty[]' value='"+qty.val()+"'>";
                   appent_txt ="<tr id="+i+"><td>"+i+"</td><td>"+spare.val()+" Парт дугаар:"+part.text()+"</td><td>"+measure.text()+"</td><td>"+qty.val()+"</td></tr>"+inputStr;
                   $("#incomePage").append(appent_txt);
                   i++;
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
     $( "#invoicedate" ).datepicker(
       { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true });
    
    //validataion here
   $( "#order" ).click(function( event ) {
       var count=$('#count').val(), flag =true;
       if(count==='0'||count===null){
          
          alert('Сэлбэг нэмээгүй байна!');
          flag =false;
          count.focus();
       }
       if($('#invoicedate').val()===null||$('#invoicedate').val()===""){
          alert('Эхний үлдэгдлийн огноог өгөөгүй байна!'); 
          flag =false;
          $('#invoicedate').focus();
       }
       if(flag===true) document.beginbalance.submit();
    });
   
});

//function rm_Row(row){
//   $('#'+row).remove();
//   $('#count').remove();
//   $("#spare_id_"+row).remove();   
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
	<form method="post" action="/ecns/warehouse/balancePallet"
		name="beginbalance" id="beginbalance">
		<div style='margin-bottom: 0px; margin-top: 15px;'>
			<p align="right">
				Эхний үлдэгдлийн огноо: <input type="text" name="invoicedate"
					id="invoicedate" size="8" />
			</p>
			<div align="right" style="margin-right: 50px;">
				<input type="button" id="dialog-link" value="Сэлбэг +" />
			</div>
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

		<input type='hidden' id='count' name='count' value="0" /> </span>
		<div>
			<label>Эхний үлдэгэдэл бүртгэсэн:</label> <span class="label"><? echo $this->session->userdata('fullname');?></span>
		</div>
		<p align="right">
			<input type="button" value="Тавиурт тавих" name="order" id="order" />
			<input type="button" value="Болих" name="cancel"
				onclick="javascript:document.location='/ecns/warehouse/income'" />
		</p>
	</form>
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
</div>
