<?php $this->load->view('header'); ?>
<script type="text/javascript">
   $(document).ready(function() {      
      var total_pallet = 0;
      var counts = $('#count').val();      
      $('select[name="pallet_id[]"]').each(function(){
            total_pallet++;            
      });      
      if(total_pallet!=counts)        
         $('#count').val(total_pallet);    
     $('input[name="addSerial"]').hide();
   });
      

</script>
<div style="margin: 15px;">
    
   <?php $attribute = array('name' => 'Balance'); ?>
   <?=form_open('/wm_settings/doBeginBalance/'.$action.'/'.$id, $attribute) ?>           
    <h3></h3>
	<fieldset style="margin-left: 20px">
		<legend>Ерөнхий мэдээлэл</legend>
		<div> 
          <?
										
echo "<p><label for=''>Сэлбэг:</label>";
										$this->load->view ( 'warehouse/plugin/spare' );
										echo "</p>";
										?>
          <? /*                    
         echo "<p><label for=''>Хэсэг:</label>"; 
         echo form_dropdown('section_id', $section, $section_id, 'onclick=getEquipment(this.value);');          
         echo "</p>";  
         echo "<p><label for='measure_name'>Төхөөрөмж:</label>"; 
         echo "<span id ='equipment'>";
         $temp_equip =array('0'=>"?");
         echo form_dropdown('equipment_id', $temp_equip, 0, 'id=equipment_id width=320px' );
         echo "</span>";
         echo "</p>";        
         echo "<p><label for='code'>Сэлбэгийн төрөл:</label>"; 
         echo form_dropdown('sparetype_id', $sparetype, $sparetype_id, 'onclick=callSpare(this.value);');
         echo "</p>";          
          ?>               
         <?php echo "<p><label for=''>Сэлбэг:</label>"; ?>
         <span id="spare">
            <? $tmp_spare=array('0'=>'Сэлбэгийг сонгоно уу!'); ?>
            <?=form_dropdown('spare_id', $tmp_spare, 0, 'id=spare_id');?>
         </span>
         <?php echo "</p>";  ?>
         
         <?php echo "<p><label for='code'>Эхний үлдэгдэл:</label>"; ?>
         <?php echo "<input name ='beginQty', value='$beginQty' id='beginQty' size='5' maxlength='5' onkeyup='copyValue(this.value)' style='width:40px;'"; ?>
         <?php echo "</p>"; ?>   
         
         <?php echo "<p><label for='code'>Огноо:</label>"; ?>
         <?php echo form_input('order_date', $beginDate  , 'id=order_date style=width:80px'); ?>
            <button id="order_btn" name="date" class ="btn_timer"></button>
            <script type="text/javascript">//<![CDATA[
               Calendar.setup({
               inputField : "order_date",
               trigger    : "order_btn",
               onSelect   : function() { this.hide() },
               showTime   : 24,
               dateFormat : "%Y-%m-%d"
               });
            //]]></script>  
         <?php echo "</p>"; ?>         
      </div>  
    </fieldset>
    <fieldset style="margin-left: 20px">
    <legend>Агуулахын мэдээлэл</legend>  
    <div id="pallet">
      <p align="right"><label></label><input type="button" name="addPallet" onclick="callPallet();" value="Тавиур +" />
         <input type="button" name="removePallet" id="removePallet" value="Тавиур -" />
      </p>         

      <p align="right" style="color:red; font-style: italic;">Өөр тавиурт нэмэх гэж байгаа бол нэмэх гэж байгаа тавиурыг эхний утгаас өөөрөөр сонгоорой!</p>      
      <?php echo "<p><label for=''>Агуулах:</label>"; ?>            
      <?=form_dropdown('warehouse_id', $warehouse, $warehouse_id, 'id=warehouse_id'); echo "</p>";?>                                 
      <?php echo "<p><label for=''>Тавиур:</label>"; ?>            
         <?=form_dropdown('pallet_id[]', $pallet, $pallet_id, 'id=pallet1'); ?>
      <?php echo "<label for='code'>Тавиур дээрх тоо/ш:</label>"; ?>
      <?php echo "<input name='palletQty[]' id='palletQty1' size='5' maxlength='5' style='width:40px'"; ?>      
      <? echo "</p>";?>      
       <?php echo "<input type='hidden' name='count' value=1 id='count' />"; ?>
       <?php echo "<input type='hidden' name='pallet1-1' value=1 />"; ?>
       <?php echo "<input type='hidden' name='serialCLD' id='serialCLD' value=no />"; ?>
      <span id="pallet">
      </span>      
    </div>
    </fieldset>
      <fieldset  style="margin-left: 20px">
    <legend>Сэлбэгийн мэдээлэл</legend>     
    <p align="right">Сериал дугаар байгаа:<input type="checkbox" id="isSerial" name="isSerial" value="yes" onclick="showAddSpare();"/>Тийм<input type="button" name="addSerial" onclick="setSerial('Balance')" value="Сериал +" /></p>             
    <div id ="palletQty">
       
    </div>
      </fieldset>
      <? $backpage=$this->session->userdata('backpage'); 
         $this->session->unset_userdata('backpage');
      ?>
      <div class="submits">
      <input type="submit" value="Нэмэх" name ="submit"/>
      <input type="button" value="Цуцлах" name ="cancel" onclick ="javascript:document.location='<? echo $backpage;?>'"/>
      </div>   
      <?=form_close();?>
    
      <script language="JavaScript" type="text/javascript" xml:space="preserve">   
         var chk_form  = new Validator("Balance");
         chk_form.setAddnlValidationFunction(chkSpare);
         //chk_form.addValidation("section_id","dontselect=0");               
         // chk_form.addValidation("sparetype_id","dontselect=0");  //
        
         function chkPalletQty(){
            var palletQty, beginQty, total, subtotal=0;
            var flag=true;            
            beginQty=document.getElementById("beginQty");            
            palletQty=document.getElementsByName("palletQty[]");
            total =beginQty.value;
            
            for (var i = 0; i < palletQty.length; i++){        
               if (palletQty[i].value==""){
                  alert("Тавиур дахь тоо ширхэгийг оруулна уу");
                  palletQty[i].focus();           
                  flag=false;            
               }
               subtotal +=parseInt(palletQty[i].value);               
            }
            
            if(total!=subtotal){
               var msg="Эхний үлдэгдлийн тоо тавиур дахь тооноос зөрж байна.\n Тавиур дахь тоог шалгана уу!\n Тавиур дахь тоо хүрэхгүй бол [Өөр тавиурт +] дар!";
               alert(msg);
               flag=false;
            }
               
            if(flag==false)
               return false;
            else return true;
         }
        
         function chkSpare(){
           var spare=$("#spare_id");
           if(spare.val()===0||spare.val()===null||spare.val===''){
              alert("Сэлбэг сонгогдоогүй байна! Жагсаалтаас сонгоно уу!");
              $('#spare').focus();                
              return false;
           }else              
              return true;
        }
        
         function chkSerialPart(){
           var palletCnt=document.getElementById("count").value;
           var palletQty, i=1, j=1, pallet, selPallet, pSerial, pPartnumber, serialErr=true, partErr=true, isSerial;           
           var CLD=document.getElementById('serialCLD').value;   
           var isSerial=document.getElementById('isSerial');   
           //alert(palletCnt);
           // ehnii Pallet-s davtana   
           if(isSerial.checked==true&&CLD=='yes'){
               do{
                    //pqName="palletQty".i;
                    pallet =document.getElementById("pallet"+i);              
                    selPallet=pallet.options[pallet.selectedIndex].value;
                    palletQty=document.getElementById("palletQty"+i).value;
                    //alert(pallet+" iin utga "+selPallet+" Too shirheg "+palletQty);              
                    do{
                       // pallet дахь сериал болон   
                       pSerial=document.getElementById(selPallet+'serial'+j).value;                       
                       if(pSerial==""||pSerial==undefined||pSerial==null){
                          serialErr = false;
                       }

                       //alert(pSerial);
                       //alert(pPartnumber);                 
                       j++;
                    }while(j<=palletQty)

                    i++;
               }while(i<palletCnt)
                 
               if(serialErr==true)
                   return true;
               else {
                   alert("Сериал болон Парт дугаарыг шалгана уу! Хоосон бүртгэхгүй!")
                   return false;
               }              
           }else{
              var msg ="Хэрэв сериал нэмэх бол Тийм сонголтийг хийгээд Сериал+ Товчийг дарж утгийг өгнө үү!\n\
              Энэ сэлбэгийн эхний үлдэгдлийг сериал дугааргүй хадгалах уу!";
              var r=confirm(msg);
              return r;           
           }
               
        }        
         chk_form.setAddnlValidationFunction(chkSpare);        
         chk_form.addValidation("order_date","req", "Огноог оруулна уу!");   
         chk_form.addValidation("beginQty","req", "Эхний үлдэгдэл оруулна уу!");              
         chk_form.setAddnlValidationFunction(chkPalletQty);
         chk_form.setAddnlValidationFunction(chkSerialPart);
        
         $("#removePallet").click(function () {
            $("#pallet #pwrap:last-child").remove();
            var bcount = document.getElementById('count').value;   
            if(bcount >1)
               document.getElementById('count').value=bcount-1;               
         });
      </script>
      </scri
</div>

 