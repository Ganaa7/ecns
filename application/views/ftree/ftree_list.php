<?=$library_src; ?>

<div id="main_wrap" style="margin: 20px auto; width: 1260px">
	<div style="margin-bottom: 10px;">
		<span style="color: red">Юуны түрүүн <strong>[FAULT TREE] буюу Алдааны
				мод</strong> хэрхэн үүсгэхийг <a href="/ecns/ftree/help">энд дарж</a>
			танилцана уу! Мөн Тохиргоо -> Тусламж цэснээс харж болно.
		</span>
	</div>
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div> 
  <?php
		foreach ( $out->action as $key => $value ) {
			echo "<input type='hidden' name='actions' class='action' value='$value'/>";
		}
		?>
  

</div>

<form id="addForm" action="" method="POST">
	<p class="feedback"></p>
	<div>
		<span>Тоног төхөөрөмж:</span>
      <?php echo form_dropdown('equipment_id', $equipment, null, 'disabled id="equipment"' ); ?>
    </div>
	<!-- <input type="text" name='equipment' value='АРМ-950'> <br> -->
	<div>
		<span>Алдаа тохиолдол:</span>
      <?php echo form_dropdown('event_id', $event, null, 'id = "event_id"' ); ?>
    </div>
	<div>
		<span>Логик хаалга:</span> <select name='gate'>
			<option value="And">And</option>
			<option value="OR">OR</option>
		</select>
	</div>
</form>

<form id="copyForm" action="" method="POST">
	<p class="feedback"></p>
	<div id="copy_txt" style="text-align: justify; padding: 10px;"></div>
	<!-- <input type="text" name='equipment' value='АРМ-950'> <br> -->
</form>
