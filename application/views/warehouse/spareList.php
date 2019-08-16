<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php  $this->load->library('session'); ?>

<?php

if ($this->session->userdata ( 'message' )) {
	?>
<div id="message" align="center">
	<p>
  <?php
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<?php

}
?>
<script type="text/javascript">
    function fsparelist(action){
       var form;
       form =document.sparelist;
       var id, status;

       if(form){
          for(var i=0; i<form.elements.length; i++){
             if (form.elements[i].type == "checkbox"){
                if(form.elements[i].checked){
                   id =form.elements[i].value;
                   status= true;
                   break;
                }else status = false;
             }
         }         
      }else
         alert("Форм алдаатай байна!");
      
      switch(action){
         case 'new':
            document.location="/ecns/warehouse/spareOrderPage/new/"+id;
            break;
         case 'edit':
            if(status==false){
               alert("Захиалгаас сонгоно уу!");
            }else
               document.location="/ecns/warehouse/spareOrderPage/edit/"+id;
            
            break;            
         case 'delete':
            var answer = confirm("Энэ захиалгийг устгахдаа итгэлтэй байна уу? \n Захиалганд хамаарах бүх жагсаалт устах болно");
            if(answer)
               document.location="/ecns/warehouse/spareOrderPage/delete/"+id;
            break;
            
      }
    }
</script>
<div id="section" align="center"></div>

<div id="body" align="center">
	<fieldset style="margin-left: 20px">
		<legend>БАЙГУУЛАМЖИЙН СЭЛБЭГИЙН ЗАХИАЛГЫН ХУУДАС</legend>
		<p>ИНД 171.79 Үйл ажиллагааны заавар 6.4 бүлэг - Хавсралт 1.</p>
		<div align="right" style="margin-bottom: 15px; margin-right: 10%">
			<input type="button" name="action" onclick="fsparelist('new');"
				value="ЗАХИАЛГИЙН ХУУДАС НЭМЭХ"> <input type="button" name="action"
				onclick="fsparelist('delete');" value="ЗАХИАЛГА УСТГАХ">
		</div>
  
<?php $atribute=array('name'=>'sparelist');  ?>
   <?=form_open('warehouse/sparelist/', $atribute)?>      

      <table class="wm_list" width="100%">
			<thead>
				<th>#</th>
				<th>Захиалга #</th>
				<th>Огноо</th>
				<th>Хэсэг</th>
				<th>Төхөөрөмж</th>
				<th>Сэлбэг</th>
				<th>Парт №</th>
				<th>Ашиглагдаж буй тоо/ш</th>
				<th>Сэлбэгэнд байх ёстой тоо/ш</th>
				<th>Агуулахад үлдэгдэл/ш</th>
				<th>ИТА гар дээр үлдэгдэл/ш</th>
				<th>Захиалах тоо/ш</th>
			</thead>
			<tbody>        
               <?php
															
$count = 1;
															foreach ( $sparelist as $row ) {
																if ($count != $row->cnt || $row->cnt == 1) {
																	echo "<tr onclick='set_checkbox(document.sparelist.sparelist, $row->sparelist_id)'>";
																	echo "<td rowspan=$row->cnt>";
																	echo "<input type ='checkbox' name='sparelist' id='sparelist' value='$row->sparelist_id'/>";
																	echo "</td>";
																	echo "<td width=50 rowspan=$row->cnt>";
																	echo $row->page_no;
																	echo "</td>";
																	echo "<td rowspan=$row->cnt width=60>";
																	echo $row->ordered_date;
																	echo "</td>";
																	echo "<td rowspan=$row->cnt>";
																	echo $row->section;
																	echo "</td>";
																	$count = $row->cnt;
																} else
																	echo "<tr>";
																echo "<td>";
																echo $row->equipment;
																echo "</td>";
																echo "<td>";
																echo $row->spare;
																echo "</td>";
																echo "<td>";
																echo $row->part_number;
																echo "</td>";
																echo "<td  width=60>";
																echo $row->uqty;
																echo "</td>";
																echo "<td width=60>";
																echo $row->nqty;
																echo "</td>";
																echo "<td width=60>";
																echo $row->eqty;
																echo "</td>";
																echo "<td width=60>";
																echo $row->injobQty;
																echo "</td>";
																echo "<td width=60>";
																echo $row->orderQty;
																echo "</td>";
																echo "</tr>";
															}
															?>                
            </tbody>
		</table>  
   <?php echo $this->pagination->create_links(); ?>
   <?=form_close()?>
<!--  body end here  -->
	</fieldset>
</div>


<? // echo $execsql; ?>
