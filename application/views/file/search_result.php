<div style="margin: 20px 20px; ">

	<?php $this->load->view ( 'plugin\search_plugin' ); ?>

	<?php if(validation_errors()){ ?>
	
		<div style="display: block; background-color:#E97979; font-size:14px; padding: 4px;">
			<?=validation_errors ( '', '<br>' );?>
		</div>

	<?php }else{?>


	<h2 style="text-align:center;margin:auto; width:100%;">Хайлтын үр дүн</h2>

	<div style="margin-top: 20px; font-size: 10pt;">

	
	
	<span >ХАЙСАН УТГА: <strong><i>"<?=$value?>"</i></strong></span>.<span style="margin-left:2px;">

		<?php if($count ==0){?>

		<i>хайсан утгаар ямарч бичиг баримт олдсонгүй.</i>

		<?php }else{ ?>

			<strong>{<?=$count;?>}</strong>  утга олдлоо.

		<?php } ?>
			&nbsp;({elapsed_time} сек)</span>

	</div>

	<div style="margin-top: 30px; margin-left: 20px;">
		
	<ul style="font-size:12pt; line-height: 1.6em;">

		<?php 

			$count = 1;
			foreach ($result as $row) {?>

			<li><a target='_blank' href='<?=base_url();?>pdf/web/viewer.html?file=../../download/doc_files/<?=$row->filename;?>'>
				<?=$row->title;?></a></li>
			
		<?php } ?>
		
	</ul>
	
	</div>

	<?php } ?>

	<div style="float:right; margin-right:10px;">
			<a href="<?=base_url().$back_url;?>" class="button">&nbsp;Буцах&nbsp;</a>				
	</div>

	
</div>