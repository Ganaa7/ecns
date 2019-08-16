<?=$library_src; ?>
<?if ($this->session->userdata ( 'message' )) {?>
<div id="message" align="center">
	<p>
<?
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
</p>
</div>
<? } ?>
<div>     
<? 
// $this->load->view('warehouse/plugin/search');
$this->load->view ( 'warehouse/plugin/filterSse' );
?>
</div>
<div style="right: 20px;">
	<input type='button' name='income' id="dialog-link"
		value="Шинэ бүртгэл" title='Шинээр сэлбэгийн нэгдсэн бүтгэл үүсгэх.' />
</div>
<div align="center" style="margin: 0 20px 0">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager" class="scroll" style="text-align: center;"></div>

</div>

<div id="dialog" title="Шинэ бүртгэл">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Сэлбэгийн нэр:</td>
				<td><input type="text" id="spare2" size="40" /></td>
			</tr>
			<tr>
				<td>Парт дугаар:</td>
				<td><span style="font-style: italic; font-weight: bold;"
					id="part_number" /></span></td>
			</tr>
			<tr>
				<td>Жагсаалтын огноо:</td>
				<td><input type="text" id="regdate" size="10" /></td>
			</tr>
			<!-- <tr><td>Ашиглалтад орсон он:</td><td><input type="text" id="launchYear" size="4" title="Жишээ:2013"/></td></tr>
           <tr><td>Ашигласан жил:</td><td><input type="text" id="usingYear" size="2" title ="Жишээ:10"/></td></tr> -->
			<tr>
				<td>Ашиглаж буй тоо/ш:</td>
				<td><input type="text" id="usingQty" size="3" /></td>
			</tr>
			<tr>
				<td>Шаардлагатай тоо/ш:</td>
				<td><input type="text" id="needQty" size="3" /></td>
			</tr>
		</table>
		<input type="hidden" id="spare_id">
	</div>
</div>

<div id="edialog" title="Нэгдсэн бүртгэл засах">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Сэлбэгийн нэр:</td>
				<td><strong><span id="espare"></span></strong></td>
			</tr>
			<tr>
				<td>Парт дугаар:</td>
				<td><span style="font-style: italic; font-weight: bold;"
					id="epart_number" /></span></td>
			</tr>
			<tr>
				<td>Жагсаалтын огноо:</td>
				<td><input type="text" id="edate" size="10" /></td>
			</tr>
			<!--            <tr><td>Ашиглалтад орсон он:</td><td><input type="text" id="elaunchYear" size="4" title="Жишээ:2013"/></td></tr>
           <tr><td>Ашигласан жил:</td><td><input type="text" id="eUsingYear" size="2" title ="Жишээ:10"/></td></tr> -->
			<tr>
				<td>Ашиглаж буй тоо/ш:</td>
				<td><input type="text" id="eUsingQty" size="3" /></td>
			</tr>
			<tr>
				<td>Шаардлагатай тоо/ш:</td>
				<td><input type="text" id="eNeedQty" size="3" /></td>
			</tr>
		</table>
		<input type="hidden" id="espare_id" />
	</div>
</div>