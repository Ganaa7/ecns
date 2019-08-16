<?=$library_src; ?>
<!--<script type="text/javascript">-->
<!-- $(document).ready(function(){
    news = $("#news");
    news.dialog({
        autoOpen: false,
        width: 600,
        height: 300,
        resizable: false,    
        modal: true,
        close: function () {      
           $(this).dialog("close");
        }
    });
   news.dialog('option', 'title', 'Мэдээлэл');
   news.dialog({ 
      buttons: {       
         "Хаах": function () {
             news.dialog("close");
          }
       }
   }); 
   news.dialog('open');
 }); -->
<!--  </script> -->
<!-- <div id="news" style="font-size:12pt; line-height:1.5em; text-align:justify;">    
    Гэмтэл дутагдлын системийн шинэчлэлтэй холбоотойгоор зарим зураг, утгуудыг шинэчлэх шаардлагатай тул вэб хөтөчийн хадгалсан утгуудыг  
    [CTRL+SHIFT+DELETE] товчнуудын хослолыг дарж <strong>нэг удаа</strong> цэвэрлэнэ үү! <br>
    Энэ системийн шинэчлэлтэй холбоотой санал, алдаа, дутагдалыг <a href="mailto:gandavaa.d@mcaa.gov.mn">gandavaa.d@mcaa.gov.mn</a> имэйл хаягаар, 1725 утсанд өгнө үү!
    <P>Баярлалаа!</p>
</div> -->
<style type="text/css">
.field {
	display: inline;
	width: auto;
	line-height: 2em;
}

.def {
	color: #00004C;
	font-size: 10pt;
	font-style: italic;
	padding: 2px 0;
	text-indent: 12em;
}

.ui-pg-input {
	width: 14px;
}

p.success {
	background: rgba(64, 211, 64, 0.97);
}
</style>

<?php if($this->session->userdata('message')) {  ?>
<div id="message" align="center">
	<p><?
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<?php } ?>
<?php $this->load->view('plugin/log_filter'); ?>

<div style="margin-left:5px; padding:5px 0; font-size: 9pt;">
	<strong><i>ГЭМТЭЛ, ДУТАГДЛЫН ӨНГӨ:</i></strong>
		ШИНЭЭР НЭЭСЭН болон ЕЗИ танилцах гэмтэл: <span style="background-color: #FF9900; font-style: italic">УЛБАР ШАР</span>,
		Хаагдаагүй гэмтэл: <span
			style="background-color: rgb(255, 113, 86); font-style: italic">УЛААН ӨНГӨ</span>,
		ЭРСДЛИЙН ҮНЭЛГЭЭ хийх: <span
			style="background-color: #FFDE00; font-style: italic">ШАР ӨНГӨ</span>, 
		ТАЙЛАНГИЙН ФАЙЛ хавсаргах: <span
			style="background-color: #73B0E6; font-style: italic">ХӨХ ӨНГӨ</span>,
		ХААГДСАН гэмтэл: <span
			style="background-color: #fff; font-style: italic">ЦАГААН ӨНГӨ</span>-р 
		тус тус харагдахыг анхаарна уу!	
</div>

<div id="main_wrap" style="margin: 0 20px 20px">

	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>
<input type="hidden" name='user_role' id="user_role"
	value="<?=$out->role?>" />
<?php
foreach ( $out->action as $key => $value ) {
	echo "<input type='hidden' name='actions' class='action' value='$value'/>";
}
?>

<form id="open_dialog" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="created_datetime">Гэмтэл гарсан огноо/хугацаа:</label> <input
			type="text" name="created_datetime" id="created_datetime"
			class="text ui-widget-content ui-corner-all">
	</div>
	<div class="field">
		<label for="location_id">Байршил:</label>
      <?=form_dropdown('location_id',$out->location, 'class="text ui-widget-content ui-corner-all"')?>
    </div>
	<div class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
       <?=form_dropdown('equipment_id',$out->equipment, 'class="text ui-widget-content ui-corner-all"')?>
    </div>

	<label for="defect">Гэмтэл</label>
	<textarea name="defect" id="defect"
		style="width: 480px; height: 100px;"></textarea>

	<label for="reason">Шалтгаан</label>
	<textarea class="reason" name="reason"
		style="width: 480px; height: 100px;"></textarea>
</form>

<form id="edit_dialog" action="" method="POST">
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id"> <input type="hidden"
		id="closed" name="closed">	
	<input type="hidden" id="closedby_id" name="closedby_id" >	
	<div class="field">
		<label for="log_num">Гэмтлийн дугаар:</label> <input type="text"
			name='log_num' id="log_num"
			class="text ui-widget-content ui-corner-all" />
	</div>
	<!--       <div class="field"> 
        <label for="edit_createdby_id">Гэмтэл нээсэн:</label> -->
          <?php //form_dropdown('createdby_id',$out->employee, null, 'class="text ui-widget-content ui-corner-all" id="createdby_id"')?>
<!--       </div> -->
	<div class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_datetime" id="created_datetime_e"
			class="text ui-widget-content ui-corner-all">
	</div>
	<div class="field">
		<label for="location_id">Байршил:</label>
          <?=form_dropdown('location_id',$out->location, null, 'class="text ui-widget-content ui-corner-all" id="location_id"')?>
      </div>
	<div class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
          <?=form_dropdown('equipment_id',$out->equipment, null, 'class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
      </div>
	<label for="defect">Гэмтэл</label>
	<textarea name="defect" id="defect" style="width: 350px; height: 70px;"></textarea>

	<label for="reason">Шалтгаан</label>
	<textarea name="reason" id="reason" style="width: 350px; height: 70px;"></textarea>

	<div id="wrap_closed">
		<div class="field">
			<label for="closed_datetime">Хаасан хугацаа:</label> <input
				type="text" name='closed_datetime' id="closed_datetime"
				class="text ui-widget-content ui-corner-all" />
		</div>
		<div class='field'>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <input type="text"
				name='duration' id="duration" disabled />
		</div>
		<!--  <div class='field'>
            <label for="closedby_id">Гэмтэл Хаасан:</label> -->
            <? //=form_dropdown('closedby_id',$out->employee, null, 'class="text ui-widget-content ui-corner-all" id="closedby_id"')?>
        <!-- </div> -->
		<div class="field">
			<label for="completion">Засварласан байдал:</label>
			<textarea name="completion" id="completion"
				style="width: 350px; height: 70px;"></textarea>
		</div>
	</div>
</form>


<!-- Хаах form Дээр зөвхөн ИТА болон бусад хүмүүс ажиллана -->
<form action="" id="close_dialog" method="POST">
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id"> 
	<input type="hidden" id="log_num" name="log_num"> 
	<input type="hidden" id="closed"	name="closed"> 
	<input type="hidden" id="equipment_id" 	name="equipment_id">
	<input type="hidden" id="created_datetime"	name="created_datetime">
	<input type="hidden" id="created_datetime"	name="created_datetime">
	<div>
		<label for="section">Хэсэг, тасаг:</label> <span id="section"></span>
	</div>
	<div>
		<label for="log_num_txt">Гэмтлийн дугаар:</label> <span
			id="log_num_txt" /></span>
	</div>
	<div>
		<label for="createdby_id">Бүртгэл нээсэн ИТА:</label> <span
			id="createdby_id"></span>
	</div>
	<div>
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <span
			id="created_datetime_txt"></span>
	</div>
	<div>
		<label>Байршил:</label> <span id="location"></span>
	</div>
	<div>
		<label>Тоног төхөөрөмж:</label> <span id="equipment"></span>
	</div>
	<div>
		<label for="reason">Шалтгаан:</label>
		<p id="reason" class="def"></p>

		<label for="defect">Гэмтэл:</label>
		<p id="defect" class="def"></p>
	</div>
	<div>
		<label for="closed_datetime">Хаасан хугацаа:</label> <input
			type="text" id="c_closed_datetime" name="closed_datetime" />
	</div>
	<div>
		<label for="duration">Үргэлжилсэн хугацаа:</label>
		<div class="position:right">Үргэлжилсэн хугацааг автоматаар тооцно</div>
	</div>
	<div>
		<label for="completion">Засварласан байдал:</label>
		<textarea id="completion" name="completion"
			style="width: 450px; height: 70px;"></textarea>
	</div>
</form>

<form action="" id="view_dialog">
	<div id="wrap_log_num" class="field">
		<label for="log_num">Гэмтлийн дугаар:</label> <input type="text"
			name='log_num' id="log_num"
			class="text ui-widget-content ui-corner-all" disabled />
	</div>
	<div id="wrap_createdby" class="field">
		<label for="edit_createdby_id">Гэмтэл нээсэн:</label>
        <?=form_dropdown('createdby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="createdby_id"')?>
    </div>
	<div id="wrap_created_datetime" class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_datetime" id="created_datetime"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div id="wrap_location" class="field">
		<label for="location_id">Байршил:</label>
        <?=form_dropdown('location_id',$out->location, null, 'disabled class="text ui-widget-content ui-corner-all" id="location_id"')?>
    </div>
	<div id="wrap_equipment" class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
        <?=form_dropdown('equipment_id',$out->equipment, null, 'disabled class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
    </div>
	<label for="defect">Гэмтэл</label>
	<textarea name="defect" id="defect" style="width: 350px; height: 70px;"></textarea>

	<label for="reason">Шалтгаан</label>
	<textarea name="reason" id="reason" style="width: 350px; height: 70px;"></textarea>
	<div id="wrap_closed">
		<div class="field">
			<label for="closed_datetime">Хаасан хугацаа:</label> <input
				type="text" name='closed_datetime' id="closed_datetime"
				class="text ui-widget-content ui-corner-all" disabled />
		</div>
		<div class='field'>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <input type="text"
				name='duration' id="duration" disabled />
		</div>
		<div class='field'>
			<label for="closedby_id">Гэмтэл Хаасан:</label>
          <?=form_dropdown('closedby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="closedby_id"')?>
      </div>
		<div class="field">
			<label for="completion">Засварласан байдал:</label>
			<textarea name="completion" id="completion"
				style="width: 350px; height: 70px;"></textarea>
		</div>		
	</div>
	<div id="show_file">
		
	</div>
</form>

<form action="" id="quality_dialog" method="post">
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id" /> <input type="hidden"
		id="log_num" name="log_num" />
	<div class="field">
		<label for="log_num">Гэмтлийн дугаар:</label> <input type="text"
			name='log_num' id="log_num"
			class="text ui-widget-content ui-corner-all" disabled />
	</div>
	<div class="field">
		<label for="edit_createdby_id">Гэмтэл нээсэн:</label>
        <?=form_dropdown('createdby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="createdby_id"')?>
    </div>
	<div class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_datetime" id="created_datetime"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="location_id">Байршил:</label>
        <?=form_dropdown('location_id',$out->location, null, 'disabled class="text ui-widget-content ui-corner-all" id="location_id"')?>
    </div>
	<div class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
        <?=form_dropdown('equipment_id',$out->equipment, null, 'disabled class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
    </div>
	<label for="reason">Шалтгаан</label>
	<textarea name="reason" id="reason" style="width: 350px; height: 70px;"
		disabled></textarea>

	<label for="defect">Гэмтэл</label>
	<textarea name="defect" id="defect" style="width: 350px; height: 70px;"
		disabled></textarea>
	<div id="wrap_closed">
		<div class="field">
			<label for="closed_datetime">Хаасан хугацаа:</label> <input
				type="text" name='closed_datetime' id="closed_datetime"
				class="text ui-widget-content ui-corner-all" disabled />
		</div>
		<div class='field'>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <input type="text"
				name='duration' id="duration" disabled />
		</div>
		<div class='field'>
			<label for="closedby_id">Гэмтэл Хаасан:</label>
          <?=form_dropdown('closedby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="closedby_id"')?>
      </div>
		<div class="field">
			<label for="completion">Засварласан байдал:</label>
			<textarea name="completion" id="completion"
				style="width: 350px; height: 70px;" disabled></textarea>
		</div>
	</div>	
     <div>
      <p style='font-size:10pt;'><i>
        Аюулыг шинжилж эрсдлийг тогтоох хүснэгтийг ҮАЗ-ын 6.15 Аюулгүй ажиллагааны удирдлагын журмаас харна уу!   </i>
      </p>
      <label for="level">Хүндрэлийн түвшин:</label>
        <?=form_dropdown('level', $out->severity, null, 'class="text ui-widget-content ui-corner-all" id="level"')?>
    </div>
    <div>        
      <label for="inst">Магадлалын түвшин :</label>
        <?=form_dropdown('inst', $out->ser_level, null, 'class="text ui-widget-content ui-corner-all" id="inst"')?>
    </div>  
</form>

<!-- Approve dialog энд байна  -->
<form id="approve_dialog" action="" method="POST">
    <p class="feedback"></p>
    <input type="hidden" id="log_id" name="log_id">
    <input type="hidden" id="log_num" name="log_num">
    <input type="hidden" id="closed" name="closed">
    <input type="hidden" id="equipment_id" name="equipment_id">
    
    <div><label for="section">Хэсэг, тасаг:</label>
      <span id="section"></span>
    </div>
    <div >
      <label for="log_num_txt">Гэмтлийн дугаар:</label>
      <span id="log_num_txt"/></span>
    </div>

    <div> 
      <label for="createdby_id">Бүртгэл нээсэн ИТА:</label>
        <span id="createdby_id"></span>          
    </div>
    <div> 
      <label for="created_datetime">Гэмтэл гарсан хугацаа:</label>
      <span id="created_datetime"></span>
    </div>
    <div>
        <label for="location_id">Байршил:</label>
        <span id="location_id"></span>          
    </div>
    <div>
        <label for="equipment_id">Тоног төхөөрөмж:</label>
        <span id="equipment_id"></span>          
    </div>
    <div>
        <label for="reason">Шалтгаан:</label>
        <p id="reason" class="def"></p>

        <label for="defect">Гэмтэл:</label>
        <p id="defect" class="def"></p>
    </div>
    <div id="wrap_closed">
        <div>
            <label>Хаасан хугацаа:</label>
            <span id="closed_datetime" ></span>
        </div>
        <div>
            <label for="duration">Үргэлжилсэн хугацаа:</label>            
            <span id="duration"></span>
        </div>
        <div>
            <label for="closedby_id">Бүртгэл Хаасан:</label>
            <span id='closedby_id'></span>
        </div>
        <div>
             <label for="completion">Засварласан байдал:</label>
            <p id="completion" class="def"></p>
        </div>        
    </div>    
</form>

<!-- File Upload here -->
<?php echo form_open_multipart('ecns/certificate/upload_file/', 'id="file_dialog"'); ?>
	<p class="feedback"></p>
	<input type="hidden" id='log_id' name='log_id'>
	<div id="wrap_log_num" class="field">
		<label for="log_num">Гэмтлийн дугаар:</label> <input type="text"
			name='log_num' id="log_num"
			class="text ui-widget-content ui-corner-all" disabled />
	</div>
	<div id="wrap_createdby" class="field">
		<label for="edit_createdby_id">Гэмтэл нээсэн:</label>
        <?=form_dropdown('createdby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="createdby_id"')?>
    </div>
	<div id="wrap_created_datetime" class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_datetime" id="created_datetime"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div id="wrap_location" class="field">
		<label for="location_id">Байршил:</label>
        <?=form_dropdown('location_id',$out->location, null, 'disabled class="text ui-widget-content ui-corner-all" id="location_id"')?>
    </div>
	<div id="wrap_equipment" class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
        <?=form_dropdown('equipment_id',$out->equipment, null, 'disabled class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
    </div>
	<label for="defect">Гэмтэл</label>
	<textarea name="defect" id="defect" style="width: 350px; height: 70px;"></textarea>

	<label for="reason">Шалтгаан</label>
	<textarea name="reason" id="reason" style="width: 350px; height: 70px;"></textarea>
	<div id="wrap_closed">
		<div class="field">
			<label for="closed_datetime">Хаасан хугацаа:</label> <input
				type="text" name='closed_datetime' id="closed_datetime"
				class="text ui-widget-content ui-corner-all" disabled />
		</div>
		<div class='field'>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <input type="text"
				name='duration' id="duration" disabled />
		</div>
		<div class='field'>
			<label for="closedby_id">Гэмтэл Хаасан:</label>
          <?=form_dropdown('closedby_id',$out->employee, null, 'disabled class="text ui-widget-content ui-corner-all" id="closedby_id"')?>
      </div>
		<div class="field">
			<label for="completion">Засварласан байдал:</label>
			<textarea name="completion" id="completion"
				style="width: 350px; height: 70px;"></textarea>
		</div>
	</div>
	<div class="field" id='_file'>
		<Label>Тайлангийн файл:</Label>
		<input type="file" name="userfile"	value="" id="cert_file">
			<i>Файлаа оруулсны дараа дэлгэрэнгүй хэсэг дээр дарж татаж авах боломжтой!</i>
	</div>
<?=form_close()?>	