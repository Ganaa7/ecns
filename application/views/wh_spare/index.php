<?=$library_src; ?>
<script src="<?php echo base_url();?>assets/warehouse/js/order.js"></script>


<style type="text/css">
.def {
	color: #00004C;
	font-size: 11pt;
	font-style: italic;
	font-weight: 400;
	/*padding: 2px 0;*/
	text-indent: 12em;
}

.warning {
	border: 1px solid #dddddd;
	background-color: rgba(255, 216, 0, 1);
}

/*"activated"*/
.argent {
	border: 1px solid #dddddd;
	background-color: rgb(255, 113, 86);
}

.ui-pg-input {
	width: 14px;
}
</style>

<?php
echo $out->form;
?>
<input type="hidden" name='user_role' id="user_role"
	value="<?=$out->role?>" />
<?php
if (isset ( $out->action )) {
	foreach ( $out->action as $key => $value ) {
		echo "<input type='hidden' name='actions' class='action' value='$value'/>";
	}
}
?>

<script type="text/javascript">
 $( function() {    
  $.contextMenu({
        selector: '.context-menu',         
        items: {
            <?php foreach ($out->action as $key => $value ) {
              # code...
              switch ($value) {
                  
                 case 'delete':?>
                 
                  "barcode": {name: "Баркод хэвлэ", icon: "edit  ",                               
                     callback: function(itemKey){

                        var id = $(this).attr('id');  

                        var qty = $(this).find( "td:eq(6)" ).text();

                        // console.log('its clicked'+parseInt(qty));

                        print_barcode(id, parseInt(qty));

                        return true;          

                      }
                    },
                    <?php break;
                }

            } ?>

            "sep1": "---------",
            "help": {name: "Тусламж", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
  });

});
</script>
