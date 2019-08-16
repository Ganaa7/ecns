<?=$library_src; ?>
<? if($this->session->userdata('message')) {  ?>
<div id="message" align="center">
	<p><?
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<? } ?>
<? // $this->load->view('warehouse/plugin/search'); ?>    
<? $this->load->view('warehouse/plugin/filterSse'); ?>
<div align="center" style="margin: 0 20px 20px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>