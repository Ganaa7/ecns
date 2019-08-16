<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>eCNS::ТХҮА Цахим бүртгэлийн систем</title>
<link rel="icon" type="image/png" href="<?=base_url();?>/images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="<?=base_url();?>/images/favicon-16x16.png" sizes="16x16" />
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

       <?php if(isset($output)){ ?>
         <?php   foreach($css_files as $file): ?>
                 <link type="text/css" rel="stylesheet"
	href="<?php echo $file; ?>">
            <?php   endforeach; ?>
            <?php   foreach($js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
            <?php endforeach; ?>
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
                    <div id="toolbar-right">
				<a href="#" id="notificationLink" class="notifier white"></a> <span
					id='notifier' class="red"></span>
				<div id="notificationContainer">
					<div id="notificationTitle">Notifications</div>
					<div id="notificationsBody" class="notifications">Lorem ipsum dolor
						sit amet, consectetur adipisicing elit. Quidem id vel esse
						sapiente, explicabo debitis eaque voluptas reiciendis incidunt,
						natus quisquam! Fugiat, non temporibus maiores, eaque in vitae
						tempora laboriosam.</div>
					<div id="notificationFooter">
						<a href="#">See All</a>
					</div>
				</div>

			</div>
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
									echo "<li><span>Version 1.1.0</span></li>";
									echo "</ul>";
								}
								?>


     </div>
		
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
					if ($this->session->userdata ( 'access' )) {
						echo "Тавтай морил: " . $this->session->userdata ( 'fullname' );
						echo " [ " . $this->session->userdata ( 'position' ) . " ]";
						echo "<a href='".base_url()."user/logout'> [гарах]</a>";						
					}
					?>
         </span>
			</h1>

		</div>

	</div>
	<div id="container">