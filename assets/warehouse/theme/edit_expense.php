
<link rel="stylesheet" href="<?=base_url();?>assets/chosen/docsupport/prism.css">
<link rel="stylesheet" href="<?=base_url();?>assets/chosen/chosen.css">
<script src="<?=base_url();?>assets/chosen/chosen.jquery.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/js/moneymask/jquery.maskMoney.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/validate/jquery.validate.js"
	type="text/javascript"></script>
<script src="<?=base_url();?>assets/warehouse/js/edit_expense.js"
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

/*.feedback {
	width: 100%;
	margin: 0 auto;
}
*/
span.field{
    display: inline-block;
    padding-top: 5px;
    line-height: 1.5;
    font-weight: bold;    
}
</style>

<form name="spare_dialog" id="spare_dialog">
   <p class="feedback"></p>
   <input type="hidden" name='section_id' id="section_id" >
   <input type="hidden" name='sector_id' id='sector_id' >
   <input type="hidden" name='equipment_id' id="equipment_id" >
   <input type="hidden" name='spare_id' id="spare_id" >
   <input type="hidden" name='amt' id="amt" >
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
  	<label>Сэлбэгийн төрөл</label>
  	<span id="spare_type_txt"></span>
  </div>
  <div>
  	<label>Парт №:</label>
    <span id="part_number_txt"></span>
  </div>  
  <div>
  	<label>Сэлбэгийн нэр</label>
    <span id="spare_txt" ></span>
  </div>  
  <div>
	<label>Хэмжих нэгж:</label>
	<span id="measure_txt"></span>
  </div>
  <div>
  	<label>Зарлага тоо:</label>
    <input type="text" name="qty" id="qty" size="5">
  </div>
  <div id="qty_wrap">  	
  </div> 
</form>

<!-- expense edit  -->
<form name="spare_edit_dialog" id="spare_edit_dialog">
   <p class="feedback"></p>
   <input type="hidden" name='section_id' id="section_id" >
   <input type="hidden" name='sector_id' id='sector_id' >
   <input type="hidden" name='equipment_id' id="equipment_id" >
   <input type="hidden" name='spare_id' id="spare_id" >
   <input type="hidden" name='amt' id="amt">
   <input type="hidden" name='cnt' id="cnt">
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
  	<label>Сэлбэгийн төрөл</label>
  	<span id="spare_type_txt"></span>
  </div>
  <div>
  	<label>Парт №:</label>
    <span id="part_number_txt"></span>
  </div>  
  <div>
  	<label>Сэлбэгийн нэр</label>
    <span id="spare_txt" ></span>
  </div>  
  <div>
	<label>Хэмжих нэгж:</label>
	<span id="measure_txt"></span>
  </div>
  <div>
  	<label>Зарлага тоо:</label>
    <input type="text" name="qty" id="qty" size="5">
  </div>
  <div id="qty_wrap_edit">  	
  </div> 
</form>



<div class="gray-bg" style="padding-left: 0px; padding-top: 20px;">
	<form id="expense_spare_form" name="expense_spare_form" class="cmxform">
		<fieldset>
			<legend>Сэлбэгийн мэдээлэл:</legend>
			<div id="feedback" class="error"></div>
			<div style="margin-bottom: 10px; display: block; width: 100%;"
				class="form-wrapper">
				<div class="sub" id="cont">
					<div style="margin-left: 10px;">Хэсэг:</div>
	  				<?php
							echo form_dropdown ( 'section_id', $section, null, 'id="section_id" style="margin-left:10px;" required ' );
							?>
	  			</div>

				<div class="sub" id="cont">
					<div>Тасаг:</div>
  					<?php
				$sector = array ('' => 'Сонгох' 	);
							echo form_dropdown ( 'sector_id', $sector, null, 'id="sector_id" required ' );
							?>
  				</div>
				<div class="sub" id="cont">
					<div>Төхөрөмж:</div>
				<?php
				$equipment = array (
						'' => 'Сонгох' 
				);
				echo form_dropdown ( 'equipment_id', $equipment, null, 'id="equipment_id" required ' );
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
			<input type="hidden" name="equipment" id="equipment"> 
					<input type="hidden" name="measure" id="measure"> 
					<input type="hidden" name="type_id" id="type_id">			
		</fieldset>
	</form>
	<hr>
	<br>
	<h3 align="center">Зарлагын баримт</h3>
	<div class="error" align="center">
	<?php
	echo validation_errors ();
	if (isset ( $flag ))
		echo "<p>" . $flag . "</p>";
        

               
	?>        
 	</div>
	<form id="expense_form" method="POST">
                <input id='invoice_id' name='invoice_id' type='hidden' value="<?=$invoice_id;?>">
                
		<p class="feedback"></p>		
		<table width="100%" class="ftree" cellspacing="5" cellpadding="5" >
			<tbody>
				<tr>
					<th><span>Зарлага баримт №:</span> <input type="text" name="expense_no"
						id="expense_no"
						<?php if(isset($expense_no)) echo "value='".$expense_no."'"; ?>
						placeholder="Зарлага №" required /></th>
					<th><span>Баримт огноо:</span> <input type="text"
						name="expense_date" id="expense_date" placeholder="Баримт огноо"
						<?php if(isset($expense_date)) echo "value='".$expense_date."'"; ?>
						required></th>			
					<th><span>Хүлээн авсан:</span>
						<?php echo form_dropdown('recievedby_id', $employee, $recievedby_id, 'id="recievedby_id" class="chosen-select"'); ?>
					</th>						
				</tr>	
				<tr >					
					<th><span>Хэсэг:</span>
						<?php
							echo form_dropdown ( 'section_id', $section, $section_id, 'id="section_id" style="margin-left:10px;" required ' );
							?>
					</th>
					<th><span>Тасаг:</span>
						<?php echo form_dropdown ( 'sector_id', $sectors, $sector_id, 'id="sector_id" required ' );
							?>
					</th>                                        
					<th><div>Зориулалт:</div>	
						<textarea name="intend" id="" cols=60 rows=3><?=$intend?></textarea>		
					</th>
				</tr>			
			</tbody>
		</table>

		<!-- Тоолуур нийт хэдэн сэлбэг орсоныг тоолно -->		
                <?php 
                $cnt = 1;
                echo "<input type='hidden' id='count' name='count' value='$count'>";
               ?>

		<table class="spare" border="1" align="center"
			style="margin-top: 20px" id="expense_table">
			<tr>
				<th>#</th>
				<th align="center">Сэлбэгийн нэр</th>
				<th width="10%">Үнэ /нэгж/</th>
				<th>Тоо /хэмжээ/</th>
				<th>Нийт үнэ</th>

			</tr>
                        <?php 
                        $i=1;
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
                            // echo "<input id='spare_amt_$cnt' name='spare_amt[]' value ='$row->amt'/>";
                            echo "</td>";
                            echo "<td>";
                            echo "<span id='spare_qty_txt_$cnt'>$row->qty</span>";                            
                            echo "</td>";
                            echo "<td>";
                            echo "<span id='spare_qty_total_$cnt'>$row->total</span>";
                            echo "<a data-id='$row->spare_id' id='edit_$row->spare_id' href='#' onclick = edit_it('$cnt','$row->spare_id','$invoice_id')> (Засах) </a>";
                            echo "</td>";
                            echo "<input type='hidden' id='spare_id_$cnt' name='spare_id[]' value='$row->spare_id'>";
                            echo "<input type='hidden' id='qty_$cnt' name='qty[]' value='$row->qty'>";
                            echo "</tr>";                                                        
                            foreach ($res_dtl as $dtl){          
                               
                               echo "<input type='hidden' class='dtl_id_$row->spare_id' name='dtl_id[".$row->spare_id."][".$i."]' value='$dtl->id'>";                             
                               $i++;
                               
                            }
                            $cnt++;
                            }
                         ?>			
		</table>
                
                <?php 
                  
                  
                ?>

		<p align="right" style="margin-top: 20px; margin-right: 30px;">
			<input type="button" id="submit_btn" value="Зарлага гаргах"	name="order" class="submit">
			<input type="button" value="Цуцлах"	name="cancel" onclick="javascript:document.location='/wh_spare/index/expense'">
		</p>
	</form>
</div>
<script>
    $(function(){
        //console.log($('#qty[1]').val()); 
        console.log();
        <?php 
        //тухайн утгаар count-g bodoj gargah function
            for($i=1; $i<$cnt; $i++){
                    // echo "$('#spare_amt_$i').hide();";
                    // echo "$('#spare_qty_$i').hide();";
                    echo "$('#save_$i').hide();";
            }  
        ?>      
     });
    
 </script>