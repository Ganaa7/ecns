<?=$library_src; ?>
<? if($this->session->userdata('message')) {  ?>
<div id="message" align="center">
	<p>
  <?php
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<? } ?>
<div><? $this->load->view('warehouse/plugin/search'); ?>
    <div style="position: absolute; top: 52px; right: 20px;">
		<input type='button' name='income' value="Зарлага гаргах"
			title='Сэлбэгийг зарлага гаргахад ашиглана.'
			onclick="javascript:document.location='/ecns/warehouse/expensePage'" />
	</div>
</div>
<div align="center" style="margin: 0 20px 20px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>