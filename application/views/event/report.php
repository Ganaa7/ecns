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
		<h3 align="center" style="width: 70%; font-style: italic;">ТЕХНИК ҮЙЛЧИЛГЭЭНИЙ ТАЙЛАН</h3>
		<div style="margin: 10px 5px 5px 0;">
			<strong>Тайлант хугацаа: <?php echo $startdate; echo "-с "; echo $enddate; echo "хүртэл";?> </strong>
		</div>
		<div style="margin-bottom: 10px">
			<strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
		</div>
<?php 
// if(isset($r_result)){
//    $inc_arr=array();
//    $data=array();
//       foreach ($r_result as $row){        
//         array_push($data, $row->income_no);        
//    }
//    $inc_array=(array_count_values($data));
   
?>    
<table border="0" cellpadding="3" cellspacing="0" align="center"
			class="report" style="width: 620px;">    
    <?php       
    echo "<thead>
    <th>Д/д</th>
    <th>Төрөл</th>
    <th>Хэсэг</th>        
    <th>Эхэлсэн</th>                
    <th>Дууссан</th>                
    <th>Төхөөрөмж</th>
    <th>Байршил</th>
    <th>Техник үйлчилгээ</th>                
    <th>Гүйцэтгэл</th>                
    <th>Бүртгэл нээсэн</th>                
    <th>Бүртгэл хаасан</th>                
    </thead>";
    echo "<tbody>";
    // $tmpMod = 0;
    // $num=1;
     $i=1;
    // $prevRow =0;
    // $prevColspan='';

     $time = strtotime('10/16/2003');

     foreach ($r_result as $row){

        echo "<tr><td>$i</td><td>$row->eventtype</td><td>$row->section</td><td>".date('Y-m-d H:m:s',strtotime($row->start))."</td><td>$row->end</td><td>$row->equipment</td><td>$row->location</td><td>$row->event</td><td>$row->done</td><td>$row->createdby</td><td>$row->doneby</td></tr>";           
        $i++;
       
    }
 
?>
  
			</tbody>
		</table>