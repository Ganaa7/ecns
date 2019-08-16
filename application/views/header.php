<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>eCNS::ХНАА Цахим бүртгэлийн систем</title>
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>assets/css/style.css">
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>assets/css/print.css" media="print">

<link rel="stylesheet" type="text/css"	href="<?php echo base_url();?>assets/css/my_theme/jquery-ui-1.10.3.custom.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/ui.jqgrid.css">

<style type="text/css">
.ui-autocomplete-loading {
	background: white
		url('<? echo base_url();?>images/ui-anim_basic_16x16.gif') right
		center no-repeat;
}

</style>

<!-- jq.min.js      myscript -->
<script src="<?php echo base_url();?>assets/js/jq.min.js"
            type="text/javascript"></script>

<script src="<?php  echo base_url();?>assets/js/jqui.min.js"
	type="text/javascript"></script>

<script src="<?php  echo base_url();?>assets/js/myscript.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/gen_validator.js"
	type="text/javascript"></script>

<link rel="shortcut icon" type="image/png" href="<?=base_url();?>images/favicon-32x32.png"/>
<link rel="icon" type="image/png" href="<?=base_url();?>images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="<?=base_url();?>images/favicon-16x16.png" sizes="16x16" />

<script src="<?php echo base_url();?>assets/js/script.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"
	type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/js/cal/fullcalendar.min.js"
	type="text/javascript"></script>
<script type="text/javascript"
	src="<? echo base_url();?>assets/js/timepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"
	src="<? echo base_url();?>assets/js/timepicker/jquery.ui.datepicker-mn.js"></script>

<!-- notifier related script -->
<script type="text/javascript"
	src="<? echo base_url();?>assets/js/jquery.timer.js"></script>
<script type="text/javascript"
	src="<? echo base_url();?>assets/js/notify.js"></script>
<!-- only used notifier -->

<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/css/fullcalendar.css">
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/css/fullcalendar.print.css"
	media='print'>
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/css/jquery-ui-timepicker-addon.css">
<!--end tp-->

<!-- context-menu heree -->
<script src="<?=base_url();?>assets/context-master/dist/jquery.contextMenu.js" type="text/javascript"></script>
<link href="<?=base_url();?>assets/context-master/dist/jquery.contextMenu.css" rel="stylesheet" type="text/css">
<!-- end menu here -->

<link rel="stylesheet" href="<?php echo base_url();?>/assets/multiselect/css/common.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/multiselect/css/ui.multiselect.css">


<!-- mulitselect -->
<script type="text/javascript" src="<? echo base_url();?>assets/multiselect/js/plugins/localisation/jquery.localisation-min.js"></script>

<script type="text/javascript" src="<? echo base_url();?>assets/multiselect/js/ui.multiselect.js"></script>

<!-- choosen here -->
<link rel="stylesheet"
  href="<? echo base_url();?>assets/chosen/chosen.css">

<script src="<?=base_url();?>assets/chosen/chosen.jquery.js" type="text/javascript"></script>
	

<? if(isset($output)){ ?>
<?   foreach($css_files as $file): ?>
       <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>">
   <?   endforeach; ?>
   <?   foreach($js_files as $file): ?>
           <script src="<?php echo $file; ?>"></script>
   <? endforeach; ?>
<? } ?>      
<!-- module script -->
 	<?php if ($this->config->item ( 'module_script' )) {    
 		echo $this->config->item('module_script');
	 } ?>

<!-- module script -->
<?php if ($this->config->item ( 'module_css' )) { ?>

  		<link type="text/css" rel="stylesheet" href="<?=$this->config->item('module_css'); ?>">
		 
 <?php } ?>

</head>
<body>
	<input type="hidden" id='token'
		value=<?=$this->session->userdata('token');?>>
	<div id="header">
	
	<div id="toolbar">
         <?php if($this->session->userdata('home')=='Y') {?>
         <div id="toolbar-left">
				<a href='http://cns.mcaa.gov.mn/' target="_blank">ХОЛБОО, НАВИГАЦИ, АЖИГЛАЛТЫН АЛБА</a>
			</div>
			<h1>
				<select name="systems">
					<option><a href="<?=base_url();?>">Цахим систем нүүр::eCNS Home</a></option>
				</select>
			</h1>
         <?php
									
} else {
		if ($this->config->item ( 'module_menu' )) {
							?>
		<div id="toolbar-left">
		<a href="<? echo base_url();?>">Цахим систем нүүр::eCNS Home</a>
		</div>
		<?php
							echo "<h1>";
							echo "<a href='" . $this->config->item ( 'module_menu_link' ) . "'>" . $this->config->item ( 'module_menu' ) . "</a>";
							echo "</h1>";
						} else {
							echo "<div id='toolbar-left'>";
							echo "<a href='http://cns.mcaa.gov.mn/'>ХОЛБОО, НАВИГАЦИ, АЖИГЛАЛТЫН АЛБА</a>";
							echo "</div>";
							echo "<h1>";
							echo "<a href='#'>Цахим систем::eCNS system</a>";
							echo "</h1>";
						}
						?>
                  <?php
									}
									?>         
     </div>
		<div id="menu">         
        <?php
								$menu = $this->config->item ( 'user_menu' );
								if ($menu != '') {
									echo $menu;
								} else {
									echo "<ul class='nav-sub clearfix'>";
									echo "<li><span>Version 1.9</span></li>";
									echo "</ul>";
								}
								?>


     </div>
		</ul>
		<div id="nav-bar">
			<h1>
         <?php
									
		if ($this->session->userdata ( 'access' )) {
										if (isset ( $title ))
											echo $title;
										if ($this->session->userdata ( 'sec_code' )) {
											$sec_code = $this->session->userdata ( 'sec_code' );
											switch ($sec_code) {
												case 'COM' :
													echo "::Холбооны хэсэг";
													break;
												case 'NAV' :
													echo "::Навигацийн хэсэг";
													break;
												case 'SUR' :
													echo "::Бодит Ажиглалт Автоматжуулалтын хэсэг";
													break;
												case 'ELC' :
													echo "::Цахилгааны хэсэг хэсэг";
													break;
												default :
													echo "";
													break;
											}
										}
									} else {
										echo "Нэвтрэх хэсэг";
									}
									?>
         <span
					style="float: right; font-size: 11pt; font-weight: normal;"><?php
					if ($this->session->userdata ( 'loggedin' )) {
						echo "Тавтай морил: " . $this->session->userdata ( 'fullname' );
						echo " [ " . $this->session->userdata ( 'position' ) . " ]";
						echo "<a href='".base_url()."user/logout'> [гарах]</a>";
						// echo $base_url('logout');
					}
					?>
         </span>
			</h1>

		</div>

	</div>
	
	<div id="container">