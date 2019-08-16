<?php $this->load->view('header'); ?> 
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
<? } ?>
<script type="text/javascript">
   function spareAddForm(){
      var section_id, page_no, order_date;      
         section_id=document.getElementById("section_id");
         page_no=document.getElementById("page_no");
         order_date=document.getElementById("order_date");
            
      if(page_no.value==0){
         alert("Хуудасны дугаарыг өгнө үү!");         
         document.getElementById("page_no").focus();
         return 0;
      }         
      else 
         if(section_id.value==0){
            alert("Хэсгээс сонгоно уу?");
            document.getElementById("section_id").focus();
            return 0;
         }else 
            if(order_date.value==0){
               alert("Огноог оруулна уу?");
               document.getElementById("order_date").focus();
            }
            else document.location='/ecns/warehouse/spareAddForm/'+section_id.value+"/"+page_no.value+'/'+order_date.value;
   }
</script>
<?php $attribute = array('name' => 'formSparelist'); ?>
   <?= form_open('/warehouse/addSparelist/', $attribute)?>
<div style="margin: 15px;">
	<div>
		<h2 align="center">Байгууламжийн сэлбэгийн захиалгийн хуудас</h2>
		<div>
			<label>Хуудас №:</label> <input name="page_no" id="page_no"
				value="<? echo $page_no; ?>" />
		</div>
		<div>
			<label>Хэсгийн нэр:</label>      
      <?php
						
echo form_dropdown ( 'section_id', $section, $section_id, "id='section_id'" );
						
						?>
      </div>
		<div>
			<label>Огноо:</label><input name="order_date" id="order_date"
				value="<?php echo $order_date; ?>" />
			<button id="f_button" name="date" class="btn_timer"></button>
			<script type="text/javascript">//<![CDATA[
         Calendar.setup({
         inputField : "order_date",
         trigger    : "f_button",
         onSelect   : function() { this.hide() },
         showTime   : 24,
         dateFormat : "%Y-%m-%d"
         });
      //]]></script>
			<span style="padding-left: 40%"><input type="button"
				value="Төхөөрөмж, Сэлбэг +" name="cancel" onclick="spareAddForm();" />
			</span>
		</div>
		<div align="center">
			<table class="tdborder" border="1" cellpadding="5"
				style="font-size: 8pt;">
				<tr class="tdborder">
					<th class="tdborder">№</th>
					<th class="tdborder">Тоног төхөөрөмж</th>
					<th>Сэлбэг</th>
					<th>Үйлдвэрлэгч</th>
					<th>Парт дугаар</th>
					<th>Ашиглагдаж буй тоо ширхэг</th>
					<th>Сэлбэгийн байх ёстой тоо</th>
					<th>Агуулах үлдэгдэл</th>
					<th>Ажлын байр дээрх үлдэгдэл</th>
					<th>Захиалах тоо ширхэг</th>
					<th>Тайлбар</th>
					<th></th>
				</tr>         
            <?php
												
$count = 1;
												if (isset ( $result )) {
													foreach ( $result as $row ) {
														echo "<tr>";
														echo "<td>";
														echo $count ++;
														echo "</td>";
														echo "<td>";
														echo $row->equipment;
														echo "</td>";
														echo "<td>";
														echo $row->spare;
														echo "<input type='hidden' name='spare_id[]' value='$row->spare_id'/>";
														echo "</td>";
														echo "<td>";
														echo $row->manufacture;
														echo "</td>";
														echo "<td>";
														echo $row->part_number;
														echo "<input type='hidden' name='part_number[]' value='$row->part_number'/>";
														echo "</td>";
														echo "<td>";
														echo $row->usingQty;
														echo "<input type='hidden' name='usingQty[]' value='$row->usingQty'/>";
														echo "</td>";
														echo "<td>";
														echo $row->needQty;
														echo "<input type='hidden' name='needQty[]' value='$row->needQty'/>";
														echo "</td>";
														echo "<td>";
														echo $row->restQty;
														echo "<input type='hidden' name='restQty[]' value='$row->restQty'/>";
														echo "</td>";
														echo "<td>";
														echo $row->injobQty;
														echo "<input type='hidden' name='injobQty[]' value='$row->injobQty'/>";
														echo "</td>";
														echo "<td>";
														echo $row->orderQty;
														echo "<input type='hidden' name='orderQty[]' value='$row->orderQty'/>";
														echo "</td>";
														echo "<td>";
														echo $row->comment;
														echo "<input type='hidden' name='comment[]' value='$row->comment'/>";
														echo "</td>";
														echo "<td>";
														echo "<a href='/ecns/warehouse/doSpareListPage/delete/$row->id'>Устгах</a>";
														echo "</td>";
														echo "</tr>";
													}
												}
												echo "<input type='hidden' id='count' name='count' value='$count'/>";
												?>
      </table>
		</div>

	</div>
	<p>
		Анх:Сэлбэгийн захиалгийн хуудаст [Төхөөрөмж, Сэлбэг+ ] товч дээр дарж
		тоног төхөөрөмийг нэмнэ! </br> Байгууламжын сэлбэгт зөвхөн ААА
		сэлбэгүүд хамаарахыг анхаарна уу!
	</p>
	<div class="submits">
   <?=form_submit('add_part', 'Бүртгэх')?>    
   <? $backpage=$this->session->userdata('backpage'); $this->session->unset_userdata('backpage'); ?>       
   <input type="button" value="Болих" name="cancel"
			onclick="javascript:document.location='<? echo $backpage;?>'" />
	</div>   
   <?=form_close();?>
   <script language="JavaScript" type="text/javascript"
		xml:space="preserve">   
      var chk_form  = new Validator("formSparelist");                          
      chk_form.addValidation("page_no","req", "Захиалгийн хуудасны дугаар оруулна уу?");  
      chk_form.addValidation("section_id","dontselect=0");              
      chk_form.addValidation("order_date","req", "Огноог оруулна уу?");                                              
      chk_form.setAddnlValidationFunction(chkCount);
      function chkCount(){
          if($('#count').val()==1){
             alert('Захиалгад Тоног төхөөрөмж нэмнэ үү!\n Төхөөрөмж, Сэлбэг + товчыг дар!');
              return false;
          }else 
              return true;  
      }
   </script>
</div>