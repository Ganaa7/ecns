
 <?=form_open('user/sent_forgot')?>  

<div class="warning" style="padding: 10px; margin: 20px;">
	Та доорх хэсэгт өөрийн имэйлийн өмнөх нэрийг оруулж, {Шинэ нууц үг авах} товч дээр дарснаар таны имэйл хаягруу шинэ нууц үг олгох имэйл очих болно!
</div>
   

<div align="center" id="login-box">
	<div align="center" style="font-weight:bold;color: red; padding-bottom: 10px;">
   	<?php echo validation_errors(); ?>
   	<?php if (isset ( $error ))
			echo $error; 
			echo $this->session->flashdata('error');

	?></div>
	<div>
		<label><strong>Таны нэвтрэх нэр:</strong></label><input name="email" />
		<strong>@mcaa.gov.mn</strong>
	</div>
	<div style="margin-top: 20px; margin-left: 20px;">
		<input type="submit" name="index" value="Шинэ нууц үг авах">

	</div>

</div>
<div align="right">
	<p style="margin-right: 100px;">
		<a href="<?=base_url();?>user/login" class="button good"><< Буцах </a>
	</p>
</div>

<?=form_close();?>