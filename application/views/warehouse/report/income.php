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
                minlength: "Эхлэх огноо буруу байна шалга!" ,
                maxlength: "Эхлэх огноо буруу байна шалга!" },
            enddate: {
                    required: "Дуусах огноог оруул!",
                    minlength: "Дуусах огноо буруу байна шалгана уу!" ,
                    maxlength: "Дуусах огноо буруу байна шалгана уу!" }
        }
    });   
   
});
        
</script>
<div style="margin-top: 20px;">
	<form action="" method="post" id="dateform">
		<span for="startdate">Тайлант хугацаа</span> <input type='text'
			size='9' name="startdate" id="startdate" value="<?echo $startdate;?>">-с
		<input type='text' size='9' name="enddate" id="enddate"
			value="<?=$enddate?>">хооронд<input type="submit" value='Харуул' />
	</form>
</div>

<div id="print_btn" align="right"
	style="padding-right: 100px; margin: 0px;">
	<input type="button" value="Хуудсыг хэвлэ" class="button"
		onclick="window.print();return false;"> <input id="printbtn2"
		type="button" value="EXCEL файл" class="button"
		onclick="document.location='<? echo $file_link; ?>'">
</div>

<div>
	<h4 align="left" style="margin-left: 500px;">
		ИНЕГ-ТХҮАлба
		</h3>
		<h3 align="center" style="width: 70%; font-style: italic;">СЭЛБЭГ
			ХАНГАЛТЫНИЙН ОРЛОГИЙН ТАЙЛАН</h3>
		<div style="margin: 10px 5px 5px 0;">
			<strong>Тайлант хугацаа: <? echo $startdate; echo "-с "; echo $enddate; echo "хүртэл";?> </strong>
		</div>
		<div style="margin-bottom: 10px">
			<strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
		</div>
<?php 
if(isset($r_result)){
   $inc_arr=array();
   $data=array();
      foreach ($r_result as $row){        
        array_push($data, $row->income_no);        
   }
   $inc_array=(array_count_values($data));
   
?>    
<table border="0" cellpadding="3" cellspacing="0" align="center"
			class="report" style="width: 620px;">    
    <?php       
    echo "<thead>
    <th>Д/д</th>
    <th>Орлого №</th>
    <th>Орлого огноо</th>        
    <th style='width:200px;'>Сэлбэг</th>                
    <th>Хэм.нэгж</th>                
    <th>Тоо хэмжээ</th>
    <th>Нийлүүлэгч</th>                
    </thead>";
    echo "<tbody>";
    $tmpMod = 0;
    $num=1;
    $i=1;
    $prevRow =0;
    $prevColspan='';
    foreach ($r_result as $row){
       if($tmpMod==0){     
          $tmpMod = 1;
          $curColspan = $inc_array[$row->income_no];
          $curRow = $row->income_no;
       }else{     
          $curColspan = $inc_array[$row->income_no];
          $curRow=$row->income_no;
       }
       if($curColspan>1){
          if($prevRow!=$curRow){
             echo "<tr><td rowspan='$curColspan'>$i</td><td rowspan='$curColspan'>$row->income_no</td><td rowspan='$curColspan'>$row->income_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td rowspan='$curColspan'>$row->supplier</td></tr>";              
             $i++;
          }else{
            if($curColspan==$prevColspan){
            //no colspan print     
               echo "<tr><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td></tr>";
            }else{
            // colspan print
                echo "<tr><td rowspan='$curColspan'>$i</td><td rowspan='$curColspan'>$row->income_no</td><td rowspan='$curColspan'>$row->income_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td rowspan='$curColspan'>$row->supplier</td></tr>";
                $i++;
            }
          }
       }  else {
              echo "<tr><td>$i</td><td>$row->income_no</td><td>$row->income_date</td><td>$row->spare</td><td>$row->short_code</td><td>$row->qty</td><td>$row->supplier</td></tr>";           
              $i++;
       }
       $num++;
       $prevColspan= $curColspan;
       $prevRow=$curRow;
    }
 
?>
    <tr style="background-color: #CCCCCC;">
				<td colspan="5"><strong>Нийт:</strong></td>
				<td colspan="1"><? echo $total; ?></td>
				<td></td>
			</tr>
			</tbody>
		</table>