<script type="text/javascript">   
   function getSpare(id){      
      var xmlhttp;          
      if (id==""){
         document.getElementById("spare").innerHTML="";
         return;
      }
      if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
      }else{// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
       }
       xmlhttp.onreadystatechange=function()
       {
          if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("spare").innerHTML=xmlhttp.responseText;
         }
       }
       xmlhttp.open("GET","/ecns/ajax/getSpare?equipment_id="+id,true);
       xmlhttp.send();   
   }  
   
</script>
<div style="margin: 15px;">
   <?php $attribute = array('name' => 'formSupplier'); ?>
   <?= form_open('/warehouse/formSparelist/'.'add', $attribute) ?>    
<div>
   <?php echo "<p><label for='email'>Тоног төхөөрөмж:</label>"; ?>            
   <?=form_dropdown('equipment_id', $equipment, $equipment_id, "onchange='getSpare(this.value);'"); echo "</p>";?>     
   <?php echo "<p><label for='spare'>Сэлбэгийн нэр:</label>"; ?>
   <span id="spare">Төхөөрөмж сонгоно уу?</span>
		<!-- Сэлбэгийн төрөл, үйлдвэрлэгч хамтдаа сонгогдоно. -->
		<p>
			<label>Ашиглагдаж буй тоо ширхэг:</label> <span><input type="text"
				maxlength="4" style="width: 5%" /></span>
		</p>
		<p>
			<label>Сэлбэгэнд байх ёстой тоо ширхэг:</label> <span><input
				type="text" maxlength="4" style="width: 5%" /></span>
		</p>
		<p>
			<label>Агуулах үлдэгдэл:</label> <span><input type="text"
				maxlength="4" style="width: 5%" /></span>
		</p>
		<p>
			<label>Ажлын байран дахь үлдэгдэл:</label> <span><input type="text"
				maxlength="4" style="width: 5%" /></span>
		</p>
		<p>
			<label>Захиалах тоо ширхэг:</label> <span><input type="text"
				maxlength="4" style="width: 5%" /></span>
		</p>
	</div>
	<div class="submits">
   <?=form_submit('submit', 'Бүртгэх')?>        
   <input type="button" value="Болих" name="cancel" />
	</div>   
   <?=form_close();?>
</div>
