
<link rel="stylesheet" href="<?=base_url();?>assets/chosen/docsupport/prism.css">
<link rel="stylesheet" href="<?=base_url();?>assets/chosen/chosen.css">
<script src="<?=base_url();?>assets/chosen/chosen.jquery.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/js/moneymask/jquery.maskMoney.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/validate/jquery.validate.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/warehouse/js/income_edit.js"
	type="text/javascript"></script>
<style>
 
table {
	border-collapse: collapse;
	width: 100%;
}

th, td {
	padding: 8px;
	text-align: left;
	/*border-bottom: 1px solid #ddd;*/
}

th span {
	margin-right: 10px;
}

table.spare {
	width: 90%;
}

.spare th {
	padding: 10px;
	text-align: center;
}

.spare td {
	padding: 10px;
	text-align: left;
	font-weight: bold;
}

#create_form {
	display: block;
}

fieldset {
	width: 100%;
	margin-top: 0;
}

.error {
	background-color: rgb(233, 121, 121);
}

#amt {
	font-weight: bold;
	font-size: 12pt;
}

#qty {
	font-weight: bold;
	font-size: 13pt;
}

.chosen-select {
	width: 250px;
}

.sub {
	width: 24%;
	display: inline-block;
}

.sub_2 {
	width: 22%;
	display: inline-block;
	text-align: left;
}

.feedback {
	width: 100%;
	margin: 0 auto;
}

span.field{
    display: inline-block;
    padding-top: 5px;
    line-height: 1.5;
    font-weight: bold;    
}
</style>

<div id="dialog" title="Basic dialog">
<form name="add_spare" id="add_spare_form">
  <input type="hidden" name='section_id' id="section_id" >
  <input type="hidden" name='sector_id' id='sector_id' >
  <input type="hidden" name='equipment_id' id="equipment_id" >
  <div>
  <label>Хэсэг:</label> <span class="field" id="txt_section"></span>
  </div>
  <div>
  <label>Тасаг:</label> <span class="field" id="txt_sector"></span>
  </div>
  <div>
  <label>Төхөөрөмж:</label> <span id="txt_equipment" class="field"></span>
  </div>
  <div>
  	<label>Үйлдвэрлэгч:</label>
    <?php echo form_dropdown ( 'manufacture_id', 314, null, 'id="spare_type_id" required ' ); ?>
  </div>  
  <div>
  	<label>Хэмжих нэгж:</label>
  	<?php echo form_dropdown ( 'measure_id', $measure, null, 'id="measure_id" required ' ); ?>
  </div> 
  <div>
  	<label>Сэлбэгийн төрөл</label>
  	<?php echo form_dropdown ( 'spare_type_id', $spare_type, null, 'id="spare_type_id" required ' ); ?>
  </div>
  <div>
  	<label>Сэлбэгийн нэр</label>
    <input type="text" name='spare' id="spare" >
  </div> 
  <div>
  	<label>Парт №:</label>
    <input type="text" name='part_number' id="part_number" >
  </div>
  
</form>
</div>

<form name="form_supplier" id="form_supplier">
	<p class="feedback" style="padding: 0"></p>
	<div>
  	    <label>Нийлүүлэгчийн нэр:</label> 
		<input type="text" name="supplier" id="supplier_id">
  	</div>
  	<div>
  	    <label>Харьяа улс:</label>  	    
  		<?php
		echo form_dropdown ( 'country_id', $country, null, 'id="country_id"');
		?>
  	</div>
  	<div>
  	    <label>Хаяг:</label>
  		<textarea name="address" id="address"></textarea>
  	</div>
  	<div>
  	    <label>Утас:</label>
  		<input type="text" name="phone" id="phone">
  	</div>
</form>

<div class="gray-bg" style="padding-left: 0px; padding-top: 20px;">
    <?php if($is_expense) {        
        ?>
    <div class="error" style="padding:10px;text-align:center; font-weight: bold;">Энэ сэлбэгээр аль хэдийн зарлага гаргасан тул засварлах боломжгүй!</div>
    <?php }?>
    
	<form id="spare_form" name="spare_form" class="cmxform">
		<fieldset>
			<legend>Сэлбэгийн мэдээлэл:</legend>
			<div id="feedback" class="error"></div>
			<div style="margin-bottom: 10px; display: block; width: 100%;"
				class="form-wrapper">
				<div class="sub" id="cont">
					<div style="margin-left: 10px;">Хэсэг:</div>
	  				<?php
							echo form_dropdown ( 'section_id', $section, null, 'id="_id_section" style="margin-left:10px;" required ' );
							?>
	  			</div>

				<div class="sub" id="cont">
					<div>Тасаг:</div>
  					<?php
				$sector = array ('' => 'Сонгох' 	);
							echo form_dropdown ( 'sector_id', $sector, null, 'id="_id_sector" required ' );
							?>
  				</div>
				<div class="sub" id="cont">
					<div>Төхөрөмж:</div>
				<?php
				$equipment = array (
						'' => 'Сонгох' 
				);
				echo form_dropdown ( 'equipment_id', $equipment, null, 'id="_id_equipment" required ' );
				?>
				</div>
				<div class="sub" id="cont">
					<div>Сэлбэг</div>
	  				<?php
	  						
							echo form_dropdown ( 'spare_id', $spare, null, 'id="spare_id" class="chosen-select" required' );
							?>					

	  				</div>
				<div style="clear: both;"></div>
			</div>
			<div style="margin-top: 10px;" class="form-wrapper">
				<div class="sub_2">
					<div style="margin-left: 10px;">Төрөл:</div>
					<input type="text" name="type" id="type" required
						style="margin-left: 10px;">
				</div>
				<div class="sub_2">
					<div>Парт дугаар:</div>
					<input type="text" name="part_number" id="part_number" required>
				</div>
				<div class="sub_2">
					<div>Нэг бүрийн үнэ:</div>
					<input type="text" name="amt" id="amt" required>
				</div>
				<div class="sub_2" style="padding-left: 40px;">
					<div>Тоо/ширхэг:</div>
					<input type="text" name="qty" id="qty" size="4" required> <input
						type="hidden" name="equipment" id="equipment"> <input
						type="hidden" name="measure" id="measure"> <input type="hidden"
						name="type_id" id="type_id"> <input type="button" name="add_btn"
						id="add_btn" value="Орлогод нэмэх">
				</div>

			</div>
		</fieldset>
	</form>
	<hr>
	<br>
	<h3 align="center">Орлогод авах сэлбэгийн жагсаалт</h3>
    <?php
    if(validation_errors()){
        echo "<div class=\"error\" align=\"center\">";
        echo validation_errors ();
        if (isset ( $flag ))
            echo "<p>" . $flag . "</p>";
        echo "</div>";
    }
    ?>
</div>
<form id="income_form" method="POST" action="<?=base_url();?>wh_spare/index/edit_income_dtl">
		<p class="feedback"></p>
		<table width="100%" class="ftree" cellspacing="5" cellpadding="5">
			<tbody>
				<tr>
					<th><span>Орлого №:</span> <input type="text" name="income_no"
						id="income_no"
						<?php if(isset($income_no)) echo "value='".$income_no."'"; ?>
						placeholder="Орлого дугаар" required /></th>
					<th><span>Орлого огноо:</span> <input type="text"
						name="income_date" id="income_date" placeholder="Орлого огноо"
						<?php if(isset($income_date)) echo "value='".$income_date."'"; ?>
						required></th>
					<th><span>Нийлүүлэгч:</span>	
				<?php
				if (isset ( $supplier_id ))
					echo form_dropdown ( 'supplier_id', $supplier, $supplier_id, 'id="supplier_id" title="Please select something!" required class="chosen-select"');
				else
					echo form_dropdown ( 'supplier_id', $supplier, null, 'id="supplier_id"  title="Please select something!" required class="chosen-select"' );
				?>				
			</th>
				</tr>
				<tr colspan="3">

				</tr>

			</tbody>
		</table>


		<!-- Тоолуур нийт хэдэн сэлбэг орсоныг тоолно -->

                <input type="hidden" name="invoice_id" id="invoice_id" value="<?=$invoice_id?>">
        <?php
        
        $cnt = 1;
        echo "<input type='hidden' id='count' name='count' value='$count'>";           
		echo "<table class='spare' border='1' align='center'";
			echo "style='margin-top: 20px' id='income_table'>";
			echo "<tr>";
				echo "<th>#</th>";
				echo "<th align='center'>Сэлбэгийн нэр</th>";
				echo "<th width='10%'>Үнэ /нэг бүр/</th>";
				echo "<th>Тоо /хэмжээ/</th>";
				echo "<th>Нийт үнэ</th>";
			echo "</tr>";


            foreach ($result as $row){
                echo "<tr id='row_$row->spare_id'>";
                echo "<td>";
                    echo $cnt;
                echo "</td>";
                echo "<td>";
                    echo $row->spare;
                echo "</td>";
                echo "<td>";
                   echo "<span id='spare_amt_txt_$cnt'>$row->amt</span>";
                   echo "<input id='spare_amt_$cnt' name='spare_amt[]' value ='$row->amt'/>";
                echo "</td>";
                echo "<td>";
                    echo "<span id='spare_qty_txt_$cnt'>$row->qty</span>";
                    echo "<input id='spare_qty_$cnt' name='spare_qty[]' value ='$row->qty'/>";
                echo "</td>";
                echo "<td>";
                    echo "<span id='total_row_$row->spare_id'>$row->total</span>";
                    echo "<a class='remove_it' data-id='$row->spare_id' href='#' onclick = remove_it('$row->spare_id','$invoice_id')> (Устгах) </a><a data-id='$row->spare_id' id='edit_$row->spare_id' href='#' onclick = edit_it('$cnt','$row->spare_id','$invoice_id')> (Засах) </a>"
                        ."<a id='save_$row->spare_id' href='#' onclick = save_it('$cnt','$row->spare_id') style='display:none;'> (Хадгал) </a>";
                echo "</td>";
                
                echo "<input type='hidden' id='spare[$cnt]' name='spare[$cnt][name]' value='$row->spare'>";
                echo "<input type='hidden' id='spare[$cnt]' name='spare[$cnt][id]' value='$row->spare_id'>";
                echo "<input type='hidden' id='order[$cnt]' name='spare[$cnt][order]' value='$cnt'>";
                echo "<input type='hidden' id='equpment[$cnt]' name='spare[$cnt][equipment_id]' value='$row->equipment_id'>";
                echo "<input type='hidden' id='sector_id[$cnt]' name='spare[$cnt][sector_id]' value='$row->sector_id'>";
                echo "<input type='hidden' id='type_id[$cnt]' name='spare[$cnt][type_id]' value='$row->type_id'>";
                echo "<input type='hidden' id='qty[$cnt]' name='spare[$cnt][qty]' value='$row->qty'>";
                echo "<input type='hidden' id='amt[$cnt]' name='spare[$cnt][amt]' value='$row->amt'>";
                echo "</tr>";
                //echo "<input type='hidden' id='spare_id' name='spare_id' value='$row->spare_id'>";
              
                $cnt++;
            }
		echo "</table>";
                
        ?>
              
            
            <p align="right" style="margin-top: 20px; margin-right: 30px;">
            <?php if(!$is_expense) {        ?>
            <input type="button" id="edit_income_btn" value="АГУУЛАХАД Байршуулах" name="order" class="submit">
            <?php } ?>
            <input type="button" value="Буцах" name="cancel" onclick="javascript:document.location=base_url+'/wh_spare/index/income'">
            </p>
              
	</form>


	<!-- add dialog -->
	<form id="add_dialog" action="" method="POST">
		<p class="feedback"></p>
		<div class="field">
			<label for="section_id">Хэсэг:</label>
      <?=form_dropdown('section_id',$section, 'class="text ui-widget-content ui-corner-all"')?>
    </div>
		<div class="field">
			<label for="sector_id">Тасаг:</label>
       <?=form_dropdown('sector_id',$sector, 'class="text ui-widget-content ui-corner-all"')?>
    </div>
		<div class="field">
			<label for="equipment_id">Тоног төхөөрөмж:</label>
       <?=form_dropdown('equipment_id',$equipment, 'class="text ui-widget-content ui-corner-all"')?>
    </div>
	</form>


 <script>
    $(function(){
        //console.log($('#qty[1]').val()); 
        console.log();
        <?php 
        //тухайн утгаар count-g bodoj gargah function
            for($i=1; $i<$cnt; $i++){
                    echo "$('#spare_amt_$i').hide();";
                    echo "$('#spare_qty_$i').hide();";
                    echo "$('#save_$i').hide();";
            }  
        ?>
                
     });
    
 </script>