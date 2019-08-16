<div align="center" id="login-box">
   <?=form_open('user/reset')?>
   <div align="center" style="font-weight:bold;color: red; padding-bottom: 10px;">
   	<?php echo validation_errors(); ?>
   	<?php
if (isset ( $error ))
	echo $error; 
	echo $this->session->flashdata('error');
?></div>

<table class="login" border="0" width="470" cellpadding="2"
		cellspacing="5">				
		<tr>
			<td width="100" class="Username">Нэвтрэх нэр:</td>
			<td><input name="username" type="text" title="Username" value="<?=$username?>"
				size="40" maxlength="2048" class="login" />@mcaa.gov.mn</td>
		</tr>		
		<tr>
			<td class="login_name"><span>Шинэ нууц үг:</span></td>
			<td><input name="password" type="password" title="Password" value=""
				size="40" maxlength="2048" class="login" /></td>
		</tr>		
		<tr>
			<td class="login_name"><span>Шинэ нууц үг давтан оруулах:</span></td>
			<td><input name="passconf" type="password" title="Password" value=""
				size="40" maxlength="2048" class="login" /></td>
		</tr>
		
		<tr>
			<td></td>
			<td style="text-align: right; padding-right: 160px;"><input
				type="submit" name="login" value="Нууц үгээ шинэчлэх"></td>
		</tr>
		<tr>
			<td colspan='2' align="center">				
				<a href="<?=base_url();?>user/login" class="">Нэвтрэх хэсэг рүү буцах</a>
			</a></td>
		</tr>
		
	</table>
	</form>
</div>
