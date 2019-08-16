<?php
/*
 * 2013/01/14
 * Warehouse list here
 */
?>
<? $this->load->view('header'); ?> 
<?php if($this->session->userdata('message')) { ?>
<div id="message" align="center">
	<p>
  <?php
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
</div>
<?php } ?>
<script type="text/javascript">
   
   function fbeginBalance(action){    
      var form;
      form =document.beginBalance;
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
               document.location="/ecns/wm_settings/newBeginbalance";
               break;
            case 'edit':
               if(!id){
                  alert("Жагсаалтаас нэг мөрийг сонгоно уу?")
                  return false;
               }
               if(status==false){
                     alert("Сэлбэгийг сонгоно уу!");
               }else
                  document.location="/ecns/wm_settings/formBeginbalance/edit/"+id;

               break;

            case 'delete':
               if(!id){
                  alert("Жагсаалтаас нэг мөрийг сонгоно уу?")
                  return false;
               }
               var answer = confirm("Энэ сэлбэгийн эхний утгийг устгахдаа итгэлтэй байна уу?");
               if(answer)
                  document.location="/ecns/wm_settings/formBeginbalance/delete/"+id;
               break;            
            }
      
   }
// Pallet hiih    
</script>
<? 
// call filter
$this->load->view ( 'warehouse/plugin/filter' );
?>

<div
	style="position: relative; float: right; margin-bottom: 15px; margin-right: 10%">       
   <?
			foreach ( $functions as $function => $value ) {
				echo "<input type=button name=action onclick=fbeginBalance('$value'); value= $function />";
			}
			?>
</div>
<div style="clear: both;"></div>

<div align="center">
        <?php $atribute=array('name'=>'beginBalance');  ?>
        <?=form_open('wm_settings/beginBalance/', $atribute)?>        
            <table class="wm_table">
		<thead>
			<th>#</th>                                        
                     <?php foreach($columns as $field_name => $field_display): ?>
                     <th
				<?php if($sort_by == $field_name) echo "class=\"sort_$sort_order\"" ?>>
                     <?php
																						
echo anchor ( "wm_settings/beginbalance/$field_name/" . (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'), $field_display );
																						?>
                     </th>
                    <?php endforeach; ?>                    
                    <th>Part number</th>
			<th>Тоо хэмжээ</th>
			<th>Огноо</th>
		</thead>
		<tbody>        
                    <?php
																				
foreach ( $result as $row ) {
																					echo "<tr id=$row->id onclick='showDetail($row->id);set_checkbox(document.beginBalance.ct_id, $row->id)'>";
																					echo "<td>";
																					echo "<input type ='checkbox' name='ct_id' id='ct_id' value='$row->id'/>";
																					echo "</td>";
																					echo "<td>";
																					echo $row->section;
																					echo "</td>";
																					echo "<td>";
																					echo $row->equipment;
																					echo "</td>";
																					echo "<td>";
																					echo $row->spare;
																					echo "</td>";
																					echo "<td>";
																					echo $row->sparetype;
																					echo "</td>";
																					echo "<td>";
																					echo $row->part_number;
																					echo "</td>";
																					echo "<td>";
																					echo $row->bqty;
																					echo "</td>";
																					echo "<td>";
																					echo $row->date;
																					echo "</td>";
																					echo "</tr>";
																				}
																				?>                    
                </tbody>
	</table>
	<!-- Detail here -->
	<h3 id="title"></h3>
	<span id="detail"></span>
            
        <?=form_close()?>
   <? echo "[ Хуудас:"; echo $this->pagination->create_links(); echo " ]"; ?>
 </div>

<? $this->load->view('footer'); ?> 