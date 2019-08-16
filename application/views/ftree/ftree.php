<?php
foreach ( $libraries ['js_files'] as $value ) {
	echo $value;
}

?>
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/ftree/style.css">
<style>
  div.basic{
    border-radius: 0px 30px
  }

 .undevelop {
    border-radius: 0px 30px
  }

</style>
<script>
            $(document).ready(function() {
                $('.tree').tree_structure({
                    'add_option': true,
                    'edit_option': true,
                    'delete_option': true,
                    'confirm_before_delete': true,
                    'animate_option': true,
                    'fullwidth_option': false,
                    'align_option': 'center',
                    'draggable_option': true
                });  
               //call event dialog here
               eventForm= $('#eventForm');
               eventForm.dialog({
               autoOpen: false,
                width: 460,       
                resizable: false,    
                modal: true,
                close: function () {
                   $('p.feedback', $(this)).html('').hide();          
                   $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');          
                   $(this).dialog("close");
                }
               });           

            });

            </script>

<?php echo "<h4 style='text-align:center; margin-top:15px;'>\"$equipment\" ТӨХӨӨӨРӨМЖИЙН ГЭМТЛИЙН МОД</h4>"; ?>
<div style="float: right; margin-right: 200px; margin-bottom: 10px;">
  <a href="<?=base_url();?>ftree/tree_v/<?=$equipment_id?>/" class="button">Хэвтээгээр харах</a> 
  
  <a href="<?=base_url();?>ftree/help" class="button">Тусламж</a>
  <!-- <a href="#" onclick="_d_event()" class="button">Event нэмэх</a>  -->
</div>
<div style="clear: both"></div>

<form id="eventForm" action="" method="POST">
	<p class="feedback"></p>
	<div>
		Event : <input type="text" name="event" id='event' size="40" />
	</div>
</form>

<?php echo $ftree;?>