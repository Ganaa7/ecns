<?php
/*
 * 2013/01/10
 * Parts list add parts from here
 */
?>
<? $this->load->view('header'); ?> 
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
<?php } ?>
<script type="text/javascript">   

   function action_spare(action){    
      var form;
      form =document.spare;
      var partId, status;

      if(form){
         for(var i=0; i<form.elements.length; i++){
               if (form.elements[i].type == "checkbox"){
                  if(form.elements[i].checked){
                     partId =form.elements[i].value;
                     status= true;
                     break;
                  }else status = false;
               }
         }         
      }else
         alert("Форм алдаатай байна!");
      
      switch(action){
         case 'new':
            document.location="/ecns/wm_settings/form_spare/new/"+partId;
            break;
         case 'edit':
            if(status==false){
                    alert("Сэлбэгийг сонгоно уу!");
            }else
               document.location="/ecns/wm_settings/form_spare/edit/"+partId;
            
            break;
            
         case 'delete':
            var answer = confirm("Энэ сэлбэгийг устгахдаа итгэлтэй байна уу?");
            if(answer)
               document.location="/ecns/wm_settings/form_spare/delete/"+partId;
            break;
            
      }  
   }
</script>

<div
	style="position: relative; float: right; margin-top: 20px; margin-bottom: 15px; margin-right: 10%">    
    <?
				foreach ( $functions as $function => $value ) {
					echo "<input type=button name=action onclick=action_spare('$function'); value= $value />";
				}
				?>
</div>
<? 
// call filter
$this->load->view ( 'warehouse/plugin/filter' );
?>

<div style="clear: both;"></div>
<div align="center">
        <?php $atribute=array('name'=>'spare');  ?>
        <?=form_open('wm_settings/part/', $atribute)?>        
            <table class="wm_table">
		<thead>
			<th>#</th>                                        
                    <?php foreach($columns as $field_name => $field_display): ?>
                     <th
				<?php if($sort_by == $field_name) echo "class=\"sort_$sort_order\"" ?>>
                     <?php
																					
echo anchor ( "wm_settings/spare/$field_name/" . (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'), $field_display );
																					?>
                     </th>
                    <?php endforeach; ?> 
                    <th>Part №</th>
			<th>Хэмжих нэгж</th>
			<th>Үйлдвэрлэгч</th>
			<th>Улс</th>
		</thead>
		<tbody>        
                    <?php
																				
foreach ( $parts as $row ) {
																					echo "<tr onclick='set_checkbox(document.spare.spare, $row->spare_id)'>";
																					echo "<td>";
																					echo "<input type ='checkbox' name='spare' id='spare' value='$row->spare_id'/>";
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
																					echo $row->measure;
																					echo "</td>";
																					echo "<td>";
																					echo $row->manufacture;
																					echo "</td>";
																					echo "<td>";
																					echo $row->country;
																					echo "</td>";
																					
																					echo "</tr>";
																				}
																				?>
                </tbody>
	</table>  
        <? echo "[ Хуудас:"; echo $this->pagination->create_links(); echo " ]"; ?>
        <? echo "<br/>"; ?>
        <?
								if ($this->session->userdata ( 'role' ) == 'ADMIN')
									echo $lastsql;
								?>
        <?=form_close()?>
         
    </div>
<? $this->load->view('footer'); ?> 