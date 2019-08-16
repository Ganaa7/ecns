<?php
/*
 * This is report file
 */
?>
<?php $this->load->view('header');?> 
<?php

if (isset ( $section )) {
	?>
<div id="section">
        <?php $attribute=array('name'=>'section');  ?>
        <?=form_open('', $attribute)?>        
        <?php $js ='onchange = "this.form.submit();"';?>
        <?if($this->session->userdata('access_type')=='ADMIN'){?>
        <span>Хэсэг:</span>
        <?php
		
if (isset ( $sec_code ) && $sec_code != '0')
			echo form_dropdown ( 'sec_code', $section, $sec_code, $js );
		else
			echo form_dropdown ( 'sec_code', $section, '0', $js );
		echo form_dropdown ( 'equipment_id', $equipment, $equipment_id, $js );
		?>
        <?php
	
} else
		echo form_dropdown ( 'equipment_id', $equipment, $equipment_id, $js );
	
	echo form_dropdown ( 'month', $months, $month, $js, $fd_attr = 'width=10px' );
	?>        
        <?=form_close();?>
    </div>

<div id="report">
	<h4 align="right">
		ИНЕГ-ТХҮАлба
		</h3>
		<h3 align="center">ГЭМТЭЛ СААТЛЫН ТАЙЛАН</h3>
		<h5 align="center">/Тоног төхөөрөмжөөр/</h5>
		<div id="equiphead">
			<div align="left" style="margin: 5px auto;">
				<strong>Хэсэг: <? if(isset($sec_name)) echo $sec_name;?></strong>
			</div>
			<div align="left" style="margin: 5px auto;">
				<strong>Төхөөрөмж: </strong><span
					style="font-style: italic; font-weight: bold"><? if(isset($equip_name)) echo $equip_name;?></span></strong>
			</div>
			<div style="margin: 5px auto;">
				<strong>Огноо: <?php echo date('Y/m/d'); ?></strong>
			</div>
		</div>
		<table border="1" cellpadding="5" align="center" class="report">
			<thead>
				<th>Гэмтсэн огноо/цаг</th>
				<th>Байршил</th>
				<th>Шалтгаан</th>
				<th>Гэмтэл</th>
				<th>Хаагдсан огноо/цаг</th>
				<th>Гүйцэтгэл</th>
				<th>Гэмтэл хаасан</th>
			</thead>
			<tbody>        
    <?php
				
foreach ( $fields as $row ) {
					echo "<tr>";
					echo "<td>";
					echo $row->created_datetime;
					echo "</td>";
					echo "<td>";
					echo $row->location;
					echo "</td>";
					echo "<td>";
					echo $row->reason;
					echo "</td>";
					echo "<td>";
					echo $row->defect;
					echo "</td>";
					echo "<td>";
					echo $row->closed_datetime;
					echo "</td>";
					echo "<td>";
					echo $row->completion;
					echo "</td>";
					echo "<td>";
					echo $row->closedby;
					echo "</td>";
					echo "</tr>";
				}
				?>
    </tbody>
		</table>
		<span>Тайлан гаргасан:<? echo $this->session->userdata('fullname');?></span>
		<div id="print_btn" align="right" style="padding-right: 40px;">
			<input type="button" value="Хуудсыг хэвлэ" class="button"
				onclick="window.print();return false;" />
		</div>

</div>
<? $this->load->view('footer');?> 