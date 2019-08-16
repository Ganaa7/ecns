<?php
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
        
   $("#dateform").validate({
      rules: {
         startdate: { required: true, minlength: 10, maxlength: 10 }         
                   },
         messages: {
            startdate: {  required: "Эхлэх огноог оруул!",
                minlength: "Эхлэх огноо буруу байна шалга!" ,
                maxlength: "Эхлэх огноо буруу байна шалга!" }            
        }
    });   
   
});
        
</script>


<div style="margin-top:20px;">
   <form action="" method="post" id="dateform">   
   <span>Эцсийн үлдэгдлийн огноо:</span>
   <input type='text' size='9' name="startdate" id="startdate" value="<?echo $startdate;?>">
   <?php echo form_dropdown('section_id', $section); ?>
   <input type="submit" value='Харуул'/>
   </form>
</div>

<div id="print_btn" align="right" style="padding-right:100px; margin:0px;">        
   <input type="button" value="Хуудсыг хэвлэ" class="button" onclick="window.print();return false;">
   <input id="printbtn2" type="button" value="EXCEL файл" class="button" 
   onclick="document.location='<?php echo  $file_link; ?>'"  >
</div>

<div>  
<h4 align="left" style ="margin-left:500px;">ИНЕГ-ТХНҮАлба</h3>
<h3 align="center" style="width:70%; font-style:italic;">СЭЛБЭГ ХАНГАЛТЫН ОРЛОГИЙН ТАЙЛАН </h3>
<div style="margin:10px 5px 5px 0;"><strong>Тайлант хугацаа: <?php echo $startdate; ?> </strong></div>
<div style="margin-bottom:10px"><strong><span>Тайлан гаргасан: <?php echo $this->session->userdata('fullname');?></span></strong>
    </div>
<?php 
// echo $last_qry;
// echo "<br>";
// echo  $last_query;
if(isset($result)){
//   $inc_arr=array();
//   $data=array();
//      foreach ($result as $row){        
//        array_push($data, $row->income_no);
//   }
//   
//   var_dump($data);
//   $inc_array=(array_count_values($data));
////   $inc_array = asort($inc_array);
//   var_dump($inc_array);
?>    
<table border ="0" cellpadding="3" cellspacing="0" align="center" class="report" style="width:680px;" >    
    <?php       
    echo "<thead>
    <th>Д/д</th>
    <th>Тасаг</th>
    <th style='width:100px;'>Тоног төхөөрөмж</th>        
    <th style='width:200px;'>Сэлбэг</th>                
    <th>Сэлбэгийн төрөл</th>                
    <th>Насжилт</th>                
    <th>Хэм.нэгж</th>                
    <th>Тоо хэмжээ</th>
    <th>Үнэ</th>
    <th>Нийт үнэ</th>    
    </thead>";
    echo "<tbody>";
    $tmpMod = 0;
    $num=1;
    $i=1;
    $prevRow =0;
    $prevColspan='';
    foreach ($result as $row){       
      
              echo "<tr><td>$i</td><td>$row->sector</td><td>$row->equipment</td><td>$row->spare</td><td>$row->sparetype</td><td>$row->years_old</td><td>$row->short_code</td><td>$row->qty</td><td>$row->amt</td><td>$row->total</td></tr>";           
              $i++;
      
//       $num++;
//       $prevColspan= $curColspan;
//       $prevRow=$curRow;
    }
}    
?>
    <tr style="background-color: #CCCCCC;"><td colspan="7"><strong>Нийт:</strong></td><td><?php echo $qty; ?></td>
    <td>      
    </td>
    <td>
      <?php  echo $total; ?>
    </td>    
    </tr>
    </tbody>    
</table>

</div>