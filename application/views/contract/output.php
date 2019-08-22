<?php $this->load->view('header');  ?>

<style>
	div.available {
		width: 220px !important;
	}
</style>

<script>
	$(function() {
		$(".pDiv").prepend("<div class='form-button-box'><input type='button' id='save-and-go-back-button' class='btn btn-small' value='Буцах'></div>");
		$('#save-and-go-back-button').click(function() {
			window.location.href = "/ecns/contract/index";
		});
	});
</script>

<div style="margin: 20px 10px;">

	<?php
	echo $output;
	?>
</div>
