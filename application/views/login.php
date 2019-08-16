<?

if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'MSIE' ) == TRUE) {
	echo "<p class='error' align='center'  style='margin-top:2px; padding-top:15px; height:50px;'>";
	echo "<strong><a href='http://support.microsoft.com/kb/982063'>Windows Internet Explorer веб хөтчийн хувилбарууд Apache Server-н ажиллагааг бүрэн дэмжиж ажилладаггүй</a></strong><br> " . "    тул та Firefox, Chrome веб хөтчүүдийг ашиглана уу!";
	echo "</p>";
}

?>
<div align="center" id="login-box">
   <?=form_open('main/login_user')?>
   <? $this->load->helper('cookie'); ?>
    <?/* echo $this->input->cookie('password'); */ ?>
<table class="login" border="0" width="470" cellpadding="2"
		cellspacing="5">
		<tr>
			<td width="100" class="login_name">Нэвтрэх нэр:</td>
			<td><input type="text" name="username" title="username" value=""
				size="40" maxlength="2048" class="login" /> <span class="emailname">@mcaa.gov.mn</span></td>
		</tr>
		<tr>
			<td></td>
			<td>/Имэйл нэрээ оруулна уу!/</td>
		</tr>
		<tr>
			<td class="login_name"><span>Нууц үг:</span></td>
			<td><input name="password" type="password" title="Password" value=""
				size="40" maxlength="2048" class="login" /></td>
		</tr>
		<!-- <tr>
			<td></td>
			<td><input type="checkbox" name="remember_me" value="1"
				<?php// if(isset($_COOKIE['username'])){   echo "checked='checked'";  }else { echo ''; } ?>>Намайг
				сана</td>
		</tr> -->
		<tr>
			<td></td>
			<td style="text-align: right; padding-right: 160px;"><input
				type="submit" name="login" value="Нэвтрэх"></td>
		</tr>
		<tr>
			<td colspan='2' align="center" style="padding-left: 40px;"><a
				href="<?=base_url();?>user/forgot">[&nbsp;Нууц үгээ мартсан бол <strong>энд
						дар!</strong>&nbsp; ]
			</a></td>
		</tr>
		<tr>
			<td colspan="2" style="font-style: italic; line-height: 1.5em;">
				Энэхүү систем нь ИНД 171.79. "ҮЙЛ АЖИЛЛАГААНЫ ЗААВАР"-ын хүрээнд
				хийгдсэн бөгөөд санал хүсэлтээ Инженеринг, Сургалтын хэсэгт гаргана
				уу! <br> Тайлбар: <strong>Нэвтрэх нэр</strong>: @mcaa.gov.mn хаягийн
				өмнөх нэр бүхий хэсэг юм.
			</td>
		</tr>

	</table>
	</form>
</div>
<div align="center" style="color: red"><?php
if (isset ( $error ))
	echo $error;
?></div>