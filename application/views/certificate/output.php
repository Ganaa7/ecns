<? $this->load->view('header');  ?>
<script>
	$(function() {			
		$(".pDiv").prepend("<div class='form-button-box'><input type='button' id='save-and-go-back-button' class='btn btn-small' value='Буцах'></div>");
		$('#save-and-go-back-button').click(function(){  
			window.location.href =base_url+"certificate/index";
		});
	});
</script>
<div style="margin: 20px 10px;">
<?
echo $output;
?>
</div>
<? $this->load->view('footer'); ?>