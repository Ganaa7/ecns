<div align="center" id="login-box">

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
			<td colspan='2' align="center">				
				<a href="http://mail.mcaa.gov.mn" class="button">Имэйл шалгах</a>
			</a></td>
		</tr>
		
	</table>
	
</div>
