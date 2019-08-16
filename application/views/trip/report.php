<?
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style type="text/css">
   #dateform { width: 670px; }
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
<?php //echo $last_qry; ?>

<div style="margin-top:20px;">
   <form action="" method="post" id="dateform">   
   <span>Тайлант хугацаа</span>
   <input type='text' size='9' name="startdate" id="startdate" value="<?=$startdate?>">-с
   <input type='text' size='9' name="enddate" id="enddate" value="<?=$enddate ?>">хооронд<input type="submit" value='Харуул'/>
   </form>
</div>

<div id="print_btn" align="right" style="padding-right:100px; margin:0px;">        
   <input type="button" value="Хуудсыг хэвлэ" class="button" onclick="window.print();return false;">
   <input id="printbtn2" type="button" value="EXCEL файл" class="button" 
   onclick="document.location='<?php echo $file_link ?>'"  >
</div>

<div>  
<h4 align="left" style ="margin-left:500px;">ИНЕГ-ХНААлба</h3>
<h3 align="center" style="width:70%; font-style:italic;"> ТОМИЛОЛТЫН ТАЙЛАН </h3>
<div style="margin:10px 5px 5px 0;"><strong>Тайлант хугацаа: <? echo $startdate."-с "; echo "$enddate"; echo " хүртэлх хугацаанд";?> </strong></div>
<div style="margin-bottom:10px"><strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
    </div>
<?php 
if(isset($result)){
  
      // var_dump($result);
  
       //$inc_array = asort($inc_array);

?>    
<table border ="0" cellpadding="3" cellspacing="0" align="center" class="report" style="width:90%;" >    
    <?php       
    echo "<thead>
    <th>№</th>
    <th>Хэсэг</th>
    <th style='width:25%;'>Чиглэл</th>        
    <th style='width:5%;'>Зорилго</th>                
    <th>Тээврийн хэрэгсэл</th>                
    <th style='width:5%;'>Зай</th>
    <th style='width:8%;'>Эхлэх огноо</th>
    <th style='width:8%;'>Дуусах огноо</th>
    <th>ИТА</th>                
    </thead>";
    echo "<tbody>";
    $tmpMod = 0;
    $num=1;
    $i=1;
    $prevRow =0;
    $prevColspan='';

    foreach ($result as $row){      
  
      echo "<tr><td>$i</td><td>$row->section</td><td>$row->location</td><td>$row->purpose</td><td>$row->transport</td><td>$row->distance</td><td>$row->start_dt</td><td>$row->end_dt</td><td>$row->employee</td></tr>";           
      $i++;
       
       $num++;
      
    }
    

}    
?>
 
    </tbody>    
</table>

</div>