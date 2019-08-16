<?php $this->load->view('header'); ?>
<script language="JavaScript" type="text/javascript">
/*   function getSpare(){
      //equipment_id-r 1 songolt hiij 
      // 1 spare-g songono
      var equipment=document.getElementById("equipment_id");
      var sparetype = document.getElementById('sparetype');
      if(equipment.value==0)
         sparetype.options[sparetype.selectedIndex].value=2;
   }
   */
</script>
<?php

if ($this->session->userdata ( 'message' )) {
	?>
<div id="message" align="center">
	<p>
  <?php
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<?php } ?>
<div style="margin: 15px;">
   <?php $attribute = array('name' => 'formSpare'); ?>
   <?= form_open('/warehouse/doRestSpare/'.$action, $attribute) ?>        
   
 <?php
	
if ($this->session->userdata ( 'message' )) {
		echo "<div id='message'>";
		echo $this->session->userdata ( 'message' );
		echo "</div>";
		$this->session->unset_userdata ( 'message' );
	}
	?>
  <?
		if ($action == 'update')
			echo "<input type='hidden' name='id' value='$id'/>";
		?>
       <h3>Нэгдсэн бүртгэл</h3>
	<div>
           <? echo "<p><label for='year'>Огноо:</label>"; ?>
           <?=form_dropdown('year', $year, $this_year)?>
           <? echo "</p>"; ?>
           <? echo "<p><label for='email'>Хэсэг:</label>"; ?>            
           <?=form_dropdown('section_id', $section, $section_id, 'onclick=getEquipment(this.value)'); echo "</p>";?>           
           <?php
											
echo "<p><label for='email'>Тоног төхөөрөмж:</label>";
											echo "<span id ='equipment'>";
											$temp_equip = array (
													'-1' => "Төхөөрөмж байхгүй" 
											);
											echo form_dropdown ( 'equipment_id', $equipment, $equipment_id, 'id=equipment_id' );
											echo "</span>";
											echo "</p>";
											echo "<p><label for='code'>Сэлбэгийн төрөл:</label>";
											echo form_dropdown ( 'sparetype_id', $sparetype, $sparetype_id, 'onclick=callSpare(this.value);' );
											echo "</p>";
											?>
            <?php echo "<p><label for=''>Сэлбэг:</label>"; ?>
         <span id="spare">
            <? $tmp_spare=array('0'=>'Сэлбэгийг сонгоно уу!'); ?>
            <?=form_dropdown('spare_id', $spare, $spare_id, 'id=spare_id');?>
         </span>
         <? echo "</p>"; ?>    
            
         <? echo "<p><label for='code'>Ашиглагдаж буй тоо/ш:</label>"; ?>
         <?
									
if ($action == 'update')
										echo "<input name ='uqty' size='5' maxlength='5' style='width:40px;' value='$uqty' >";
									else
										echo "<input name ='uqty' size='5' maxlength='5' style='width:40px;'/>";
									?>           
         <? echo "</p>"; ?>   
            
         <? echo "<p><label for='code'>Байх ёстой тоо/ш:</label>"; ?>
         <?
									
if ($action == 'update')
										echo "<input name ='nqty' id='nqty' size='5' maxlength='5' style='width:40px;' value ='$nqty'>";
									else
										echo "<input name ='nqty' id='nqty' size='5' maxlength='5' style='width:40px;' >";
									echo "</p>";
									?> 
            
         <? echo "<p><label for='comment'>Тайлбар:</label>"; ?>
         <?
									
echo "<textarea name ='comment'>";
									if ($action == 'update')
										echo $comment;
									echo "</textarea>";
									?>
         <? echo "</p>"; ?> 

        </div>
	<div class="submits">
        <?=form_submit('add_part', 'Бүртгэх')?>        
        <?
								
$backpage = $this->session->userdata ( 'backpage' );
								$this->session->unset_userdata ( 'backpage' );
								?>
        <input type="button" value="Болих" name="cancel"
			onclick="javascript:document.location='<? echo $backpage;?>'" />
	</div>   
        <?=form_close();?>
        <script language="JavaScript" type="text/javascript"
		xml:space="preserve">   
           var chk_form  = new Validator("formSpare");    
           chk_form.addValidation("section_id","req", "Хэсгийг сонгоно уу!");        
           chk_form.addValidation("equipment_id","dontselect=-1");                        
           chk_form.addValidation("sparetype_id","dontselect=0");    
           chk_form.addValidation("spare_id","dontselect=0");               
           chk_form.addValidation("uqty","req","Ашиглагдаж буй тоо/ш оруулна уу!");        
           chk_form.addValidation("nqty","req","Байх ёстой тоо/ш оруулна уу!");        
           
           
        </script>

</div>

