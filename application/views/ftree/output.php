<? $this->load->view('header');  ?>
<script>
	$(function() {			
		$(".pDiv").prepend("<div class='form-button-box'><input type='button' id='save-and-go-back-button' class='btn btn-small' value='Буцах'></div>");
		$('#save-and-go-back-button').click(function(){  
			window.location.href = "/ecns/ftree/index";
		});
	});
</script>
<div style="margin: 20px 10px;">
	<p style="margin: 10px; color: red">Гэрчилгээнд бүртгэгдсэн тоног
		төхөөрөмж устгагдах боломжгүйг анхаарна уу!</p>
<?
echo $output;
?>
</div>
<? $this->load->view('footer'); ?>