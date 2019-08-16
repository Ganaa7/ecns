<? $this->load->view('header');?>
<script type="text/javascript">
$(function() {
    $('#start_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        showOtherMonths: true,
        showWeek: true
    });  
    $('#end_date').datepicker({
       dateFormat: 'yy-mm-dd',
       changeMonth: true,
       showOtherMonths: true,
       showWeek: true
    }); 
});   
</script>

<?php // echo $last_sql; ?>
<fieldset align="center">
	<legend>Шүүх:</legend>
<?php
if (isset ( $section )) {
	?>         
    <div id="section" style="margin: 20px;" align="center">        
        <?php $attribute=array('name'=>'report');  ?>
        <?=form_open('', $attribute)?>        
        <?php $js ='onchange = "this.form.submit();"';?>
        
        <span>Хэсэг:</span>
         <?php 
// if(isset($sec_code)&&$sec_code !='0')
	echo form_dropdown ( 'section_id', $section, $section_id, $js );
	// else echo form_dropdown('sec_code',$section, '0', $js);
	echo form_dropdown ( 'equipment_id', $equipment );
	?>
        <?php
	$filter_option = array (
			'Y' => 'Хаалттай гэмтэл',
			'A' => 'Нээлттэй гэмтэл' 
	);
	echo form_dropdown ( 'log', $filter_option, $log, '' );
	
	
	?>

    </div>
	<div align="center">
       <?php if(isset($start_date)&&$start_date!=0) { ?>
          <input name="start_date" id="start_date" size="8"
			value="<?=$start_date?>" />
       <?php }else{ ?>
        <input name="start_date" id="start_date" size="8" />          
        <?php } ?>
        <span>Хүртэл</span>
        <?php if(isset($end_date)&&$end_date!=0) { ?>
          <input name="end_date" id="end_date" size="8"
			value="<?=$end_date?>" />
       <?php }else{ ?>
            <input name="end_date" id="end_date" size="8" />     
        <?php } ?>
                
        <input type="submit" name="filter" value="Харуул"
			onclick="show_RepHeader(this.value)" />
                
        <?=form_close();?>
    </div>
<?php } ?>

</fieldset>

<div id="report">
	<h4 align="right">
		ИНЕГ-ТХНҮАлба
		</h3>
		<h3 align="center" style="font-style: italic;">ТОНОГ ТӨХӨӨРӨМЖИЙН
			ГЭМТЭЛ ДУТАГДЛЫН ТАЙЛАН</h3>
<?php if(isset($date)) { ?>
<h5 align="center">/Хугацаа:<?php echo $date?>/</h5>
<?php } ?>
<div style="margin: 5px auto;">
			<strong>Тайлан огноо: <?php echo date('Y/m/d'); ?></strong>
		</div>
		<strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
<?php if(isset($filter)){ ?>

<div id="print_btn" align="right" style="margin: 10px;">
			<input typ="button" value="Хуудсыг хэвлэ" class="button"
				onclick="window.print();return false;" />
			<!-- <?php if(isset($start_date)) { ?>  -->
			<a class="button good"
				href="<?=base_url();?>flog/export_xls/<? echo $section_id.'/'.$equipment_id.'/'.$log.'/'.$start_date.'/'.$end_date;?>">XLS
				file уруу хөрвүүлэх</a>
			<!-- <?php }else { ?> -->
			<!-- <a class="button good" href="/ecns/report/export_xls/<?// echo $sec_code.'/'.$equipment_id.'/'.$log;?>">XLS file уруу хөрвүүлэх</a>     -->
			<!-- <?php } ?> -->
		</div>    
<?php
}
if (isset ( $sections ))
	foreach ( $sections as $col ) {
		?>
<div id="equiphead">
			<div align="left" style="margin: 5px auto;">
				<strong>Хэсэг: <? echo $col->name;?></strong>
			</div>
		</div>
		<table border="0" cellpadding="3" cellspacing="0" align="center"
			class="report">    
    <?php
		if ($log == 'A')
			echo "<thead>
        <th>Гэмтэл №</th>        
        <th style='width:56px;'>Гэмтсэн нээсэн огноо/цаг</th>        
        <th>Байршил</th>  
        <th>Төхөөрөмж</th>                
        <th>Шалтгаан</th>        
        <th>Гэмтэл</th>
        <th>Гэмтэл нээсэн</th>
        </thead>";
		else
			echo "<thead>
        <th>Гэмтэл №</th>
        <th>Гэмтсэн нээсэн огноо/цаг</th>                
        <th>Байршил</th>  
        <th>Төхөөрөмж</th>                                
        <th>Шалтгаан</th>        
        <th>Гэмтэл</th>
        <th>Гэмтэл нээсэн</th>        
        <th>Гэмтэл хаасан огноо/цаг</th>
        <th>Үргэлж хугацаа</th>        
        <th>Гүйцэтгэл</th>
        <th>Гэмтэл хаасан</th>
            </thead>";
		?>
    <tbody>        
    <?php
		
//$fields = $col->section_id;
//var_dump($fields);
		 if ($col->section_id == 1)
		 	$fields = $COM;
		 elseif ($col->section_id == 2)
		 	$fields = $NAV;
		 elseif ($col->section_id == 3)
		 	$fields = $SUR;
		 else
		 	$fields = $ELC;
		foreach ( $fields as $row ) {
			echo "<tr>";
			echo "<td id='cell'><div style='width:45px; font-size:7pt;'>";
			echo $row->log_num;
			echo "</div></td>";
			echo "<td id='cell'><div style='width:56px;'>";
			echo $row->created_dt;
			echo "</td>";
			echo "<td id='cell'><div>";
			echo $row->location;
			echo "</td>";
			echo "<td id='cell'><div style='width:65px;'>";
			echo $row->equipment;
			echo "</div></td>";
			echo "<td id='cell'><div style='width:80px;'>";
			echo $row->reason;
			echo "</td>";
			echo "<td id='cell'><div style='width:100px;'>";
			echo $row->node;
			echo "</td>";
			echo "<td id='cell'><div style='width:90px;'>";
			echo $row->createdby;
			echo "</td>";
			if ($log == 'Y') {
				echo "<td id='cell'><div style='width:56px;'>";
				echo $row->closed_dt;
				echo "</td>";
				echo "<td id='cell'><div style='width:42px;'>";
				echo $row->duration;
				echo "</td>";
				echo "<td id='cell'><div>";
				echo $row->completion;
				echo "</td>";
				echo "<td id='cell'><div style='width:85px;'>";
				echo $row->closedby;
				echo "</td>";
			}
			echo "</tr>";
		}
		?>
    </tbody>
		</table>
<? }else{ ?>
    <div align="center" style="margin: 5px auto;">
			<strong style="color: red">Дээрх утгуудыг сонгож. Харуул товчийг
				дарна уу!!!</strong>
		</div>        
<?php } ?>



</div>
<? $this->load->view('footer');?> 