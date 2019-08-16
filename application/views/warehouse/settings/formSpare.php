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
   <?= form_open('/wm_settings/addSpare/'.$action, $attribute) ?>        
   
 <?php
	
if ($this->session->userdata ( 'message' )) {
		echo "<div id='message'>";
		echo $this->session->userdata ( 'message' );
		echo "</div>";
		$this->session->unset_userdata ( 'message' );
	}
	?>
  <? if($action=='edit') echo form_hidden('spare_id', $spare_id); ?>
       <h3>Сэлбэгийн тохиргоо</h3>
	<div>
           <?php echo "<p><label for='email'>Хэсэг:</label>"; ?>            
           <?=form_dropdown('section_id', $section, $section_id, 'onclick=getEquipment(this.value)'); echo "</p>";?>           
           <?php
											
echo "<p><label for='email'>Тоног төхөөрөмж:</label>";
											echo "<span id ='equipment'>";
											$temp_equip = array (
													'-1' => "Төхөөрөмж байхгүй" 
											);
											echo form_dropdown ( 'equipment_id', $temp_equip, 0, 'id=equipment_id' );
											echo "</span>";
											echo "</p>";
											?>       
           <?php echo "<p><label for='sparetype_id'>Сэлбэгийн төрөл:</label>"; ?>            
           <?=form_dropdown('sparetype_id', $sparetype, $sparetype_id, 'id=sparetype'); echo "</p>";?>                                 
           <?php echo "<p><label for='workphone'>Сэлбэгийн нэр:</label>"; ?>
           <?php
											
if (isset ( $spare )) {
												echo form_input ( 'spare', $spare );
												echo "</p>";
											} else {
												echo form_input ( 'spare' );
												echo "</p>";
											}
											?>

           <?php echo "<p><label for='partnumber'>Парт дугаар:</label>"; ?>
           <?php
											
if (isset ( $part_number ))
												echo form_input ( 'part_number', $part_number );
											else
												echo form_input ( 'part_number' );
											echo "</p>";
											?>
           <?php echo "<p><label for=''>Хэмжих нэгж:</label>"; ?>            
           <?=form_dropdown('measure_id', $measure, $measure_id); echo "</p>";?>           
           <?php echo "<p><label for='email'>Үйлдвэрлэгч:</label>"; ?>            
           <?=form_dropdown('manufacture_id', $manufacture, $manufacture_id); echo "</p>";?>           
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
           chk_form.addValidation("spare","req","Сэлбэгийн нэрийг оруулна уу!");        
           chk_form.addValidation("part_number","req","Сэлбэгийн дугаарыг оруулна уу!");        
           chk_form.addValidation("sparetype_id","dontselect=0");    
           chk_form.addValidation("measure_id","dontselect=0");    
           chk_form.addValidation("manufacture_id","dontselect=0");
        </script>

</div>

