<?=$library_src; ?>
<div id="main_wrap" style="margin: 20px auto; width: 1260px">    
<?php if($role=='ADMIN'||$role=='CLRK'||$role=='HEADMAN'){ ?>
  <p style='margin: 10px;'>
		Үр дүн тооцох огноо: <input type="text" id="outcome_date"
			style="width: 80px"> <a href="/ecns/outcome/init" class="button">Үр
			дүн тооцох</a>
	</p>
<?php } ?>
  <table id="rowed5" class="scroll"></table>

	<div id="pager" class="scroll" style="text-align: center;"></div>     
  
   <?php
			// foreach ($out->action as $key => $value) {
			// echo "<input type='hidden' name='actions' class='action' value='$value'/>";
			// }
			?>
</div>