<?=$library_src; ?>
	
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/ftree/style.css">
	
<script src="<?=base_url();?>assets/js/timepicker/jquery-ui-timepicker-addon.js"
	type="text/javascript"></script>

<script src="<?=base_url();?>assets/treeview/js/jquery.tree_log.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/treeview/js/jquery.cookie.js"	type="text/javascript"></script>

<script src="<?=base_url();?>assets/js/timepicker/jquery-ui-timepicker-addon.js"
	type="text/javascript"></script>

<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/treeview/css/jquery.treeview.css"/>

<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/treeview/css/screen.css">

<!-- choosen here -->
<link rel="stylesheet"
	href="<? echo base_url();?>assets/chosen/chosen.css">

<script src="<?=base_url();?>assets/chosen/chosen.jquery.js" type="text/javascript"></script>

<!-- choosen end here -->
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/ftree/style.css">

<style type="text/css">
.def {
	color: #00004C;
	font-size: 11pt;
	font-style: italic;
	font-weight: 400;
	/*padding: 2px 0;*/
	text-indent: 12em;
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



	