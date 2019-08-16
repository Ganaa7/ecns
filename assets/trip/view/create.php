<!-- View Dialog form -->

<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
	<div id="" class="field">
		<label for="section">Хэсэг:</label> 
		<?php echo form_input('section', set_value('section', $section)); ?>
	</div>
	<div id="" class="field">
		<label for="category">Хэсэг:</label> 
		<input type="text" name="category" id="category">
	</div>
<?php echo form_close();?>