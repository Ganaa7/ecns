<script type="text/javascript">   
    $(function(){
    // when equipment exist and spare_id exist show to spare_id by selected equipment
    if($("select[name=equipment_id]")&&$('#spare_id')){
       var equipment_id=$("select[name=equipment_id] :selected").val();      
       $.post( '/ecns/wm_ajax/fSpare', {equipment_id:equipment_id}, function(newOption) {
          //console.log(response);   
          var select = $('#spare_id');
          if(select.prop) {
             var options = select.prop('options');
          }else {
             var options = select.attr('options');
          }        
          $('option', select).remove();
          $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);
          });        
        });        
    }// end select
}); 
   // Хэрэв Spare_id сонгогдсон байхад агуулахаас утга авах 
   $(document).ajaxComplete(function(){
      var spare_id =$('#spare_id :selected').val();
      var order_date=$('input[name=order_date]').val();
      
      // Call ajax post by spare_id
      $.post( '/ecns/wm_ajax/getEqty', {spare_id:spare_id, order_date:order_date}, function(response){               
         $.each(response, function(val, text) {
            if(val =='eqty'){
               $('#eqty').val(text);
               $('#leqty').text(text);                   
            }else if(val=='uqty'){
               $('#uqty').val(text);
               $('#luqty').text(text); 
            }else{
               $('#nqty').val(text);
               $('#lnqty').text(text); 
            }
         });   
      });        
    });
// end #eqty
</script>
<div style="margin: 15px;">
	<label>Хэсэг:</label><span>
    <? echo $section; ?></span>
   <?php $attribute = array('name' => 'formSupplier'); ?>
   <?= form_open('/warehouse/addSpareOrderPage/', $attribute) ?>    
   <div>   
   <?
			
if (isset ( $section_id ))
				echo "<input type='hidden' name='section_id' value='$section_id'/>";
			if (isset ( $page_no ))
				echo "<input type='hidden' name='page_no' value='$page_no'/>";
			if (isset ( $order_date ))
				echo "<input type='hidden' name='order_date' id='order_date' value='$order_date'/>";
			?>
   <?php echo "<p><label for='email'>Тоног төхөөрөмж:</label>"; ?>            
   <?=form_dropdown('equipment_id', $equipment, $equipment_id, "onchange='getSpare(this.value);'"); echo "</p>";?>     
   <?php echo "<p><label for='spare'>Сэлбэгийн нэр:</label>"; ?>
   <span id="spare"> <select id="spare_id" style="width: 60px"
			name="spare_id">
				<option value="0">Төхөөрөмжийг сонго!</option>
		</select>
		</span>
		<!-- Сэлбэгийн төрөл, үйлдвэрлэгч хамтдаа сонгогдоно. -->
		<p>
			<label>Ашиглагдаж буй тоо ширхэг:</label> <input type="hidden"
				name="uqty" id="uqty" /> <span id="luqty"></span>

		</p>
		<p>
			<label>Сэлбэгэнд байх ёстой тоо ширхэг:</label> <input type="hidden"
				name="nqty" id="nqty" /> <span id="lnqty"></span>
		</p>
		<p>
			<label>Агуулах үлдэгдэл:</label> <span> <input type="hidden"
				name="restQty" id="eqty" /> <span id="leqty" />
			</span> </span>
		</p>
		<p>
			<label>Ажлын байран дахь үлдэгдэл:</label> <span><input type="text"
				name="injobQty" maxlength="5" style="width: 5%" /></span>
		</p>
		<p>
			<label>Захиалах тоо ширхэг:</label> <span><input type="text"
				name="orderQty" maxlength="5" style="width: 5%" /></span>
		</p>
		<p>
			<label>Тайлбар:</label>
			<textarea name="comment"></textarea>
		</p>
	</div>
	<div class="submits">
   <?=form_submit('submit', 'Бүртгэх', 'id=spl')?>        
   <? $backpage=$this->session->userdata('backpage'); $this->session->unset_userdata('backpage'); ?>       
   <input type="button" value="Болих" name="cancel"
			onclick="javascript:document.location='<? echo $backpage;?>'" />
	</div>   
   <?=form_close();?>
     <script language="JavaScript" type="text/javascript"
		xml:space="preserve">   
         function chkSpare(){
            if($('#spare_id :selected').val()==0){
               alert("Сэлбэгийг сонгоно уу! \n Сонгосон төхөөрөмжөөр Сэлбэг байхгүй байна!!!")
               return false;
            }else
               return true;
        }
        var chk_form  = new Validator("formSupplier");    
        chk_form.setAddnlValidationFunction(chkSpare);   
        chk_form.addValidation("equipment_id","dontselect=0");                                                     
        //chk_form.addValidation("uQty","req", "Ашиглагдаж буй тоо ширхэг оруулна уу?");                   
        //chk_form.addValidation("nQty","req", "Сэлбэгэнд байх ёстой тоо ширхэг оруулна уу?");                           
        chk_form.addValidation("injobQty","req", "Ажлын байран дахь үлдэгдэл оруулна уу?");                   
        chk_form.addValidation("orderQty","req", "Захиалах тоо ширхэг оруулна уу!");                           
       
     </script>
</div>
