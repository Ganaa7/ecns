<style>
.hide {
	display: none;
}
</style>
<script>
  // $(function() {
  //   $( "#dialog" ).dialog({
  //   	width:600,
  //   	height: 856,
  //     modal: true,
  //      buttons: {        
  //       "Хаах": function() {
  //         $( this ).dialog( "close" );
  //       }
  //     }
  //   });

  // });
</script>

<div class="apps-box">
    <?php
				foreach ( $sys_result as $sys ) {
					echo "<div class='app_titles' id='$sys->controller'><a href='".base_url()."$sys->controller/index' class='home_apps'><img src='".base_url()."/images/$sys->controller.png'/><span>$sys->menu</span></a></div>";
				}

     //   if($this->session->userdata('sec_code')=='COM')
       //   echo "<div class='app_titles' id='comunication'><a href='/ecns/comunication/index' class='home_apps'>
         // <img src='/ecns/images/comunication.png'><span>Холбоо</span></a></div></div>";
				?>
</div>
<!-- <div id="dialog">
   <h4>Мэдээ: ИНД 171.79 "Үйл ажиллагааны заавар" ажиллагааны зааврын 6.2, 6.3, 6.17 журмууд шинэчлэгдэн батлагдлаа .</h4>
   <small>2016 оны 10 сарын 25нд.</small>
   <img src="<?php //echo base_url('/assets/images/a-10.png')?>" alt="" height="650" width="500">
   <p>
    Шинэчлэгдсэн журмуудтай <strong><a href='<?php // echo base_url('/document/index/')?>'>энд дарж</a></strong> танилцана уу! ТТА хэсэг.
   </p>
</div>
 -->
<div class="clearfix"></div>

