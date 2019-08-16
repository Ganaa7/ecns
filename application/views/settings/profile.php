<?php $this->load->view('header'); ?>
<script type="text/javascript">
    function password_check(){        
        var newpass, renewpass;

        newpass=document.getElementById('newpass').value;
        renewpass=document.getElementById('renewpass').value;

        if(null!=newpass){           
           if(newpass!=renewpass){                            
               alert("Шинэ нууц үг, давтан оруулсан нууц үг таарахгүй байна.!\n Нууц үгээ дахин оруулна уу!");      
               document.getElementById("renewpass").focus();
               return false;
           }else return true;
        }else
            return true;
} 
</script>
<div style="margin: 15px;">
 <?php
	
if ($this->session->userdata ( 'message' )) {
		echo "<div id='message'>";
		echo $this->session->userdata ( 'message' );
		echo "</div>";
		$this->session->unset_userdata ( 'message' );
	}
	?>
<?php $attribute=array('name'=>'profile','id'=>'prof', 'onsubmit'=>'return password_check()');?>    
<?php if($action=='personal'){?>
<?=form_open('settings/personal/'.$employee_id, $attribute)?>
<?php } else{?>
<?=form_open('settings/profile/'.$action.'/'.$employee_id, $attribute)?>
<?php

}
// Засвар хийж байгаа тохиолдолд ийшээ хандана
if (isset ( $cols )) {
	foreach ( $cols as $row ) {
		$data = array (
				'name' => 'username',
				'value' => $row->username 
		);
		?>
        <fieldset style="margin-left: 20px">
		<legend>Нэвтрэх</legend>
		<div>
            <?php echo "<p><label for='username'>Нэвтрэх нэр:</label>"; ?>
            <?php
		
if ($this->session->userdata ( 'access_type' ) == 'ADMIN')
			echo form_input ( $data );
		else {
			$data ['disabled'] = 'disabled';
			echo form_input ( $data );
		}
		echo "</p>";
		?>
            <?php echo "<p><label for='username'>Овог:</label>"; ?>
            <?php
		echo form_input ( 'last_name', $row->last_name );
		echo "</p>";
		echo "<p><label for='username'>Нэр:</label>";
		echo form_input ( 'first_name', $row->first_name );
		echo "</p>"?>
        </div>
	</fieldset>

	<fieldset style="margin-left: 20px">
		<legend>Албан тушаал</legend>         
        <?php echo '<p>Ямар ХЭСЭГ-т харьяалагдахыг зөв шалгаж сонгоно уу! Зөвхөн өөрийн хэсгийн мэдээллийг ашиглана.</p>'?>
        <div>
            <?php echo "<p><label for='section'>Хэсэг:</label>"; ?>
              <?php $js ='onchange="showSector(this.value)"';?>     
            <?
		
if (isset ( $section )) {
			if ($this->session->userdata ( 'access_type' ) == 'ADMIN')
				echo form_dropdown ( 'section_id', $section, $row->section_id, $js );
			else
				echo form_dropdown ( 'section_id', $section, '', $js );
		} else
			echo form_input ( 'section', $row->section, 'disabled' );
		echo "</p>"?>
            <?php echo "<p><label for='sector'>Тасаг:</label>"; ?>
            <?php if(isset($section))   {?>            
            <span id="txtHint">Хэсгийг сонгоход тасгийн мэдээлэл
				харагдана...</span>     
              <?php
		
} else
			echo form_input ( 'sector_id', $row->sector, 'disabled' );
		echo "</p>"?>
            <?php echo "<p><label for='position'>Албан тушаал:</label>"; ?>
            <?php if(isset($position)){ ?>
                  <span id="txtPos">Хэсгийг сонгоход албан тушаалын
				мэдээлэл харагдана...</span>     
            <?php
		
} else
			echo form_input ( 'position', $row->position, 'disabled' );
		echo "</p>"?>
        </div>
		<!-- хэрэв Админ бол тухайн хэрэглэгчийн дүрийг солих боломжтой -->
<!--         <?php //if($this->session->userdata('role')=='ADMIN'&& isset($role)){ ?>
        <div>
            <?php// echo "<p><label for='email'>Системийн нэмэлт эрх:</label>"; ?>
            <?php //echo form_dropdown('role', $role, $row->role); echo "</p>"?>
            <?php //echo "<p><label for='email'>Тайлбар:</label>"; ?>
            <?php //echo "Системийг өөр эрхээр хэрэглэх шаардлагатай тохиолдолд хэрэглэнэ.</label></p>"; ?>
        </div> 
        <?php //}else { ?>
        <div>
            <?php //echo "<p><label for='email'>Ажил үүрэг:</label>"; ?>
            <?php
			
//echo form_input ( 'position', $row->role, 'disabled' );
//			echo "</p>";
			?>
        </div>
        <?php //} ?> -->
        </fieldset>


	<fieldset style="margin-left: 20px">
		<legend>Холбоо барих</legend>
		<div>
            <?php echo "<p><label for='email'>Имэйл хаяг:</label>"; ?>
            <?
		
if ($this->session->userdata ( 'access_type' ) == 'ADMIN')
			echo form_input ( 'email', $row->email );
		else
			echo form_input ( 'email', $row->email, 'disabled' );
		echo "</p>";
		?>
           <?php echo "<p><label for='workphone'>Ажлын утас:</label>"; ?>
           <?php echo form_input('workphone', $row->workphone); echo "</p>"?>
           <?php echo "<p><label for='cellphone'>Гар утас:</label>"; ?>
           <?php
		echo form_input ( 'cellphone', $row->cellphone );
		echo "</p>"?>
        </div>
	</fieldset>
	<fieldset style="margin-left: 20px">
		<legend>Нууц үг</legend>
		<div>
            <?php echo "<p><label for='email'>Шинэ нууц үг</label>"; ?>
            <?php
		$pass = array (
				'name' => 'newpass',
				'id' => 'newpass',
				'type' => 'password' 
		);
		echo form_input ( $pass );
		echo "</p>";
		?>
            <?php echo "<p><label for='cellphone'>Дахин оруул</label>"; ?>
            <?php
		$repass = array (
				'name' => 'renewpass',
				'id' => 'renewpass',
				'type' => 'password' 
		);
		echo form_input ( $repass );
		echo "</p>"?>           
        </div>
	</fieldset>
	<div class="submits">
        <?=form_submit('close', 'Засах')?>
        <? $attr = array('class' =>'button good'); ?>
        <?
		
if ($action == 'edit')
			echo anchor ( 'settings/employee', 'Болих', $attr );
		else
			echo anchor ( 'shiftlog/index', 'Болих', $attr );
		?>
         <script language="JavaScript" type="text/javascript"
			xml:space="preserve">
            var profile_form  = new Validator("profile");
            profile_form.addValidation("last_name","req","Овог оо бичнэ үү");
            profile_form.addValidation("first_name","req","Өөрийн нэрээ бичнэ үү");
            profile_form.addValidation("username","req","Нэрээ оруулна уу!");            
           
        </script>

	</div>
<?php
	
}
} else {
	// Шинээр бүртгүүлж байгаа тохиолдолд
	?>      <fieldset style="margin-left: 20px">
		<legend>Нэвтрэх</legend>
		<div>
            <?php echo "<p><label for='username'>Нэвтрэх нэр:</label>"; ?>
            <? echo form_input('username', 'Имэйл хаягны өмнөх нэрийг хадгална', 'disabled'); echo "</p>"?>
            <?php echo "<p><label for='username'>Овог:</label>"; ?>
            <? echo form_input('last_name'); echo "</p>"?>
            <?php echo "<p><label for='username'>Нэр:</label>"; ?>
            <? echo form_input('first_name'); echo "</p>"?>
        </div>
	</fieldset>
	<fieldset style="margin-left: 20px">
		<legend>Албан тушаал</legend>
		<div>
            <?php $js ='onchange="showSector(this.value)"';?>     
            <?php echo "<p><label for='section'>Хэсэг:</label>"; ?>
            <?php
	
echo form_dropdown ( 'section_id', $section, $section_id, $js );
	echo "</p>"?>
            <?php echo "<p><label for='sector'>Тасаг:</label>"; ?>
            <span id="txtHint">Хэсгийг сонгоно уу..</span>            
            <?php echo "</p>"?>
            <?php echo "<p><label for='position'>Албан тушаал:</label>"; ?>
            <span id="txtPos">Хэсгийг сонгоно уу..</span>             
            <?php echo "</p>"?>
        </div>
	</fieldset>

	<fieldset style="margin-left: 20px">
		<legend>Ашиглах системүүд</legend>
		<div>
			<span style='margin: 15px;'><span>Гэмтэл дутагдлын бүртгэл</span><input
				type="checkbox" name="shiftlog" value="" /> </span> <span
				style='margin: 15px;'><span>Техник үйлчилгээний бүртгэл</span><input
				type="checkbox" name="eventlog" value="" /> </span> <span>Сэлбэг
				хангалтын бүртгэл</span><input type="checkbox" name="warehouse"
				value="" />
		</div>
	</fieldset>

	<fieldset style="margin-left: 20px">
		<legend>Холбоо барих</legend>
		<div>
            <?php echo "<p><label for='email'>Имэйл хаяг:</label>"; ?>
            <? echo form_input('email'); echo "</p>"?>
            <?php echo "<label></label><small style='font-size:8pt; font-style:italic; margin-top:-3px;'>Имэйл хаягт зөвхөн [@mcaa.gov.mn] хаягыг авна.</small>"; ?>            
            <?php echo "<p><label for='workphone'>Ажлын утас:</label>"; ?>
          
            <? echo form_input('workphone'); echo "</p>"?>
            <?php echo "<p><label for='cellphone'>Гар утас:</label>"; ?>
            <? echo form_input('cellphone'); echo "</p>"?>
        </div>
	</fieldset>

	<fieldset style="margin-left: 20px">
		<legend>Нууц үг</legend>
		<div>
            <?php echo "<p><label for='password'>Шинэ нууц үг</label>"; ?>
            <?php
	$pass = array (
			'name' => 'newpass',
			'type' => 'password',
			'id' => 'new_pass' 
	);
	echo form_input ( $pass );
	echo "</p>";
	?>
            <?php echo "<p><label for='cellphone'>Дахин оруул</label>"; ?>
            <?php
	$repass = array (
			'name' => 'renewpass',
			'type' => 'password',
			'id' => 'renew_pass' 
	)
	;
	echo form_input ( $repass );
	echo "</p>"?>
            <?php
	
if ($this->session->flashdata ( 'error' ))
		echo $this->session->flashdata ( 'error' );
	?>
        </div>
	</fieldset>
	<div class="submits">
        <?=form_submit('close', 'Бүртгэх')?>
        <? $attr = array('class' =>'button good'); ?>
        <?=anchor('settings/employee', 'Болих', $attr);?>           
        </div>
	<script language="JavaScript" type="text/javascript"
		xml:space="preserve">
            var profile_form  = new Validator("profile");
            profile_form.addValidation("last_name","req","Овог оо бичнэ үү");
            profile_form.addValidation("first_name","req","Өөрийн нэрээ бичнэ үү");
            profile_form.addValidation("username","req","Нэрээ оруулна уу!");
            profile_form.addValidation("newpass","req","Шинэ нууц үгээ оруулна уу!");
            profile_form.addValidation("renewpass","req","Шинэ нууц үгээ дахин оруулна уу!");
            profile_form.addValidation("section_id","dontselect=0");    
        </script>
<?php } ?>
<?=form_close();?>          

</div>
<?php $this->load->view('footer'); ?>