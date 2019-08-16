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
	width: 70%;
}

#expense label.error {
	font-style: italic;
	color: red;
	margin-left: 10px;
	width: auto;
	display: inline;
}
</style>
<script type="text/javascript">
$(function() {
    var part=$('#part_number'), spare=$('#spare'), spare_id=$('#spare_id'), measure=$('#measure'), qty=$('#qty');
    var i=1, append_txt, inputStr; var spares= new Array();
 
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 450,
        heigth:800,
        modal:true,        
        buttons: [
        {
             text: "Гүйцсэн",
             click: function() {
                 var i;                  
                //validate spare, qty, reason
                if(spare.val()==0||spare.val==null||spare_id==0||spare_id==null){                       
                   alert("Нэг сэлбэг сонгоно уу?");
                   spare.focus();
                }else if(qty.val()==0||qty.val()==null||(typeof qty.val() !== 'number' && qty.val() % 1 !== 0)){
                   alert("Тоо хэмжээг оруулна уу? Бүхэл тоогоор оруулна уу!");
                   qty.focus();
                }else{
                   // add spare, qty, reason to input in lists                                      
                   //inputStr ="<input type='hidden' id='count' name='count' value='"+i+"'>";                   
                   if($("#count").val()>0){ i =$("#count").val(); } else i =0;
                   i++;   
                   $("#count").val(i);
                   inputStr="<input type='hidden' id='spare_id_"+i+"' name='spare_id[]' value='"+spare_id.val()+"'>";
                   inputStr +="<input type='hidden' id='qty_"+i+"' name='qty[]' value='"+qty.val()+"'>";
                   appent_txt ="<tr id="+i+"><td>"+i+"</td><td>"+spare.val()+" Парт дугаар:"+part.text()+"</td><td>"+measure.text()+"</td><td>"+qty.val()+"</td></tr>"+inputStr;
                   $("#incomePage").append(appent_txt);                   
                   
                   // check box select 
                   $.each($("input[name='spare_pk[]']:checked"), function() {
                      //spares.push($(this).val());
                      $('#expense').append('<input type="hidden" name="'+i+'_rowId[]" value="'+$(this).val()+'" />');
                   });     
                   clear();                   
                   $( this ).dialog( "close" );
                }  
             }
         },
        {
             text: "Болих",
             click: function() {                  
                $( this ).dialog( "close" );
                clear();
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
        
   function clear(){
      $('#spares').text('');
      measure.text("");
      part.text("");
      spare.val("");
      spare_id.val(null);
      qty.val(null);
      return;
   }
    $( "#spare" ).autocomplete({
       source: "spareJson",
       minLength: 2,
       select: function( event, ui ) {
          if(ui.item.value){             
             spare_id.val(ui.item.id);
             part.text(ui.item.part);
             measure.text(ui.item.measure);
             expenseDetail(ui.item.id);
          }
       },
       search:function( event, ui){
          part.text("");
          spare_id.val(0);
          measure.text("");
          qty.val("");
          $("#remove_tr").remove();         
       }
    });    
     //tooltip
    $( document ).tooltip({ track: true  });     
     //datepicker
    $( "#expenseDate" ).datepicker(
    { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true });

    $("#expense").validate({
       ignore: [],
       rules: {
          expenseNo: "required",
          intend: "required",
          expenseDate: "required",
          cnt: {
                required: true,
                min: 1
          }
        },
       messages: {
            expenseNo: "Зарлагын дугаарыг оруулаагүй байна!",
            intend: "Гүйлгээний утгийг оруулаагүй байна!",
            expenseDate: "Зарлагын огноог оруулаагүй байна!",
            cnt: {
                    required: "Сэлбэг нэмнэ үү!",
                    min: "Жагсаалтад дор хаяж нэг сэлбэг нэмнэ үү!"
            }
       }
    });
    
    $("#addSerial_1").click(function(){
       //hello 
       alert("hello");
    });
    
    $("#addName").click(function(){
        var section_id= $("select[name='section_id']").val();
        //alert(section_id);
        if(section_id =='0'){
            alert('Хөдөлгөөний хэсгийг сонгоно уу!');
        }else{
            var data = {};
            data['section_id'] = section_id;
            $.ajax({
                 type:     'POST',
                 url:      '/ecns/wm_ajax/chkSection',
                 data:     data, 
                 dataType: 'json',              
                 success: function (json) {                     
                    if(json.status =='success'){
                        $("#receivedId").remove();
                        $("#addName").remove();
                        $( "#receive" ).append( "<input type='text' name ='receiveby'>");               
                    }else{
                        alert("Хөдөлгөөний хэсгийг сонго!");
                    }
                 }               
                });    
        }
    });  
        
    var xTriggered = 0; 

    $("#qty").keyup(function(event) {       
       var chbox = $('input:checkbox'), chboxQty, inQty=$("#qty").val(), i=1;
       xTriggered++;
       //нийт checkbox-г uncheck hiine.       
       chbox.prop('checked', false);
       //check box-Г тоолно
       chboxQty =chbox.length;
       //console.log("ckbox"+chboxQty);      
       //console.log("inqty"+inQty);      
       if(inQty<=chboxQty){
          for(i=1; i<=inQty; i++){
             //console.log("data:"+i);
             $("#check_"+i).attr('checked', true);
          }       
          //var msg = "Qty value:"+inQty+" Handler for .keyup() called " + xTriggered + " time(s).";
          //console.log(msg);
        }else 
           alert('Үлдэгдэл хүрэлцэхгүй байна.Зарлагын тоо хэмжээ Агуулахын тооноос их байх ёсгүй!');
    }).keydown(function( event ) {
       if ( event.which == 13 ) {
          event.preventDefault();
       }
    });
 // end jquery   
 });



function checkit(checkbox){
   count =$('#qty').val();
   if(checkbox.checked===true){
      ++count;         
      //alert("Сонгосон утга:"+count);
   }else{   count--; //alert("Сонгосон утга:"+count);
   }
       document.getElementById('qty').value=count;
       return;
}


function expenseDetail(spare_id){     
     if(spare_id!==null){        
        var xmlhttp;         
        if (spare_id===""){
           document.getElementById("spares").innerHTML="";
           return;
        }
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
        }else{// code for IE6, IE5
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
           if (xmlhttp.readyState==4 && xmlhttp.status==200){
              document.getElementById("spares").innerHTML=xmlhttp.responseText;
           }
        }
        xmlhttp.open("GET","/ecns/wm_ajax/getSpareDetial?spare_id="+spare_id,true);
        xmlhttp.send();      
     }else
        alert("Сэлбэг сонгогдоогүй байна! Сэлбэгийг сонгоно уу?");
  }  
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
	<form method="post" action="/ecns/warehouse/insExpense" name="expense"
		id="expense">
		<div style='margin-bottom: 0px; margin-top: 15px;'>
			<span align="center"><h3>
					ЗАРЛАГЫН ПАДААН №<input type='text' size='3' name='expenseNo'
						id='expenseNo' />
				</h3></span>
			<table border='0' cellspacing='10' width='95%'>
				<tr>
					<td rowspan='2'><label class='no'>Гүйлгээний утга:</label>
					<textarea class="reason" cols="80" rows="2" name="intend"
							id="intend" /></textarea></td>
					<td><label>Огноо:</label><input type="text" name="expenseDate"
						id="expenseDate" size="8" /></td>
				</tr>
				<tr>
					<td><label>Хэсэг:</label> <?=form_dropdown('section_id', $section, 0, "style='width:140px' title='Хэсгийг энэ хэсгээс сонгоно уу!'");?></td>
				</tr>
			</table>
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
		<input type='hidden' id='count' name='cnt' value='0'> </span>
		<div>
			<label>Бараа олгосон нярав:</label> <span class="label"><? echo $this->session->userdata('fullname');?></span>
		</div>
		<div id='receive'>
			<label>Хүлээн авсан:</label>
            <?=form_dropdown('receiveby_id', $recieved, 0, "style='width:140px' id='receivedId'");?>
            <a id="addName" href='#'>Нэр бичих</a>
		</div>
		<div>
			<label>Шалгасан нягтлан бодогч:</label> <span class="label"><? echo form_dropdown('accountant_id', $accountant, 0, "style='width:140px'"); ?></span>
		</div>
		<p align="right">
			<input type="submit" value="Зарлага гаргах" name="order" /> <input
				type="button" value="Болих" name="cancel"
				onclick="javascript:document.location='/ecns/warehouse/income'" />
		</p>
	</form>
	<div id="dialog" title="Сэлбэг +">
		<form id="expDetail">
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
						<td>Хэмжих нэгж:</td>
						<td><span type="text" id="measure" /></span></td>
					</tr>
					<tr>
						<td>Зарлага тоо/хэмжээ:</td>
						<td><input id="qty" size="6" type="text" /></td>
					</tr>
					<tr>
						<td>Агуулахын үлдэгдэл:</td>
						<td>
							<div id="spares"></div>
						</td>
					</tr>
					<input type="hidden" id="spare_id">
				</table>
		
		</form>
	</div>
</div>