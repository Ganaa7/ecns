<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style type="text/css">
#dateform {
	width: 670px;
}

#dateform label.error {
	margin-left: 0px;
	width: auto;
	display: inline;
	color: red;
}
</style>
<script>
$(function() {
    //datepicker
   $( "#startdate" ).datepicker(
      { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true }); 
   $( "#enddate" ).datepicker(
      { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true }); 
      
       $("#dateform").validate({
      rules: {
         startdate: { required: true, minlength: 10, maxlength: 10 },
         enddate: {  required: true, minlength: 10, maxlength: 10 }
                   },
         messages: {
            startdate: {  required: "Эхлэх огноог оруул!",
                minlength: "Эхлэх огноо буруу, шалга!" ,
                maxlength: "Эхлэх огноо буруу, шалга!" },
            enddate: {
                    required: "Дуусах огноог оруул!",
                    minlength: "Дуусах огноо буруу, шалгана уу!" ,
                    maxlength: "Дуусах огноо буруу, шалгана уу!" }
        }
    });   
});
        
</script>
<? // echo $last_qry; ?>
<div style="margin-top: 20px;">
	<form action="" method="post" id="dateform">
		<label>Тайлант хугацаа:</label> <input type='text' size='9'
			name="startdate" id="startdate" value="<?echo $startdate;?>">-с <input
			type='text' size='9' name="enddate" id="enddate"
			value="<?=$enddate?>"> <input type="submit" value='Хүртэл' />
	</form>
</div>
<div id="report" style="margin-top: 20px;">
	<div id="print_btn" align="right" style="padding-right: 100px;">
		<input type="button" value="Хуудсыг хэвлэ" class="button"
			onclick="window.print();return false;"> <input id="printbtn2"
			type="button" value="EXCEL файл" class="button"
			onclick="document.location='<? echo $file_link; ?>'">
	</div>
	<h4 align="right" style="margin-right: 10%;">
		ИНЕГ-ТХҮАлба
		</h3>
		<h3 align="center" style="font-style: italic;">СЭЛБЭГ ХАНГАЛТЫН
			ЗАРЛАГЫН ТАЙЛАН1</h3>
		<div style="margin: 10px 5px 5px 0;">
			<strong>Тайлант хугацаа: <? echo $startdate; echo "-с "; echo $enddate; echo "хүртэл";?> </strong>
		</div>
		<div style="margin-bottom: 10px">
			<strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
		</div>


		<div id="equiphead">
			<div align="left" style="margin: 5px auto;"></div>
		</div>
<?
$exp_arr = array ();
$data = array ();

foreach ( $r_result as $row ) {
	array_push ( $data, $row->expense_no );
}
print_r($data);
$exp_array = (array_count_values ( $data ));
echo "<br>";

print_r($exp_array);
?>
<table border="0" cellpadding="3" cellspacing="0" align="center"
			class="report">    
    <?php
				
				echo "<thead>
        <th>Баримтын №</th>        
        <th>Зарлага огноо</th>        
        <th style='width:200px;'>Сэлбэг</th>                
        <th>Хэм нэгж</th>               
        <th align='center'>Тоо хэмжээ</th>               
        <th>Хүлээн авсан</th>
        <th>Хэсэг</th>
        <th>Зориулалт</th>
        </thead>";
				
				echo "<tbody>";
				$tmpMod = 0;
				$i = 1;
				$prevRow = 0;
				$prevColspan = '';
				foreach ( $r_result as $row ) {
					if ($tmpMod == 0) {
						$tmpMod = 1;
						$curColspan = $exp_array [$row->expense_no];
						$curRow = $row->expense_no;
					} else {
						$curColspan = $exp_array [$row->expense_no];
						$curRow = $row->expense_no;
					}
					if ($curColspan > 1) {
						if ($prevRow != $curRow) {
							echo "<tr><td rowspan='$curColspan'>$row->expense_no</td><td rowspan='$curColspan'>$row->expense_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td rowspan='$curColspan'>$row->receiveby</td><td rowspan='$curColspan'>$row->section</td><td rowspan='$curColspan'>$row->intend</td></tr>";
							$i ++;
						} else {
							if ($curColspan == $prevColspan) {
								// no colspan print
								echo "<tr><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td></tr>";
							} else {
								// colspan print
								echo "<tr><td rowspan='$curColspan'>$row->expense_no</td><td rowspan='$curColspan'>$row->expense_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td rowspan='$curColspan'>$row->receiveby</td><td rowspan='$curColspan'>$row->section</td><td rowspan='$curColspan'>$row->intend</td></tr>";
								$i ++;
							}
						}
					} else {
						echo "<tr><td>$row->expense_no</td><td>$row->expense_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td>$row->receiveby</td><td>$row->section</td><td>$row->intend</td></tr>";
						$i ++;
					}
					
					$prevColspan = $curColspan;
					$prevRow = $curRow;
				}
				/*
				 * foreach ($r_result as $row){
				 * echo "<tr>";
				 * echo "<td rowspan='$row->count'>";
				 * echo $row->expense_no; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->expense_date; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->spare; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->short_code; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->qty; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->receiveby; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->section; echo "</td>";
				 * echo "<td id='cell'>"; echo $row->intend; echo "</td>";
				 * echo "</tr>";
				 * }
				 *
				 */
				?>     
    <tr style="background-color: #CCCCCC;">
				<td colspan="4"><strong>Нийт:</strong></td>
				<td colspan="1"><? echo $total; ?></td>
				<td colspan="3"></td>
			</tr>
			</tbody>
		</table>

</div>