<script type="text/javascript">
   $(document).ready(function() {      
     var counts = $('#count').val();          
     var i;
     // Сэлбэгийн тоо хэмжээг аваад тухайн сэлбэг бүрийн addSerial-г нуух
     $('input[name="addSerial[]"]').hide();    
     
//     //$('input[name="addPallet"]').hide();    
//     $('input[name="addSerial[]"]').click(function(){
//        // 
//        alert('hello');
//        //$('input[name="addPallet"]').hide();
//     });
//     
//     //tooltip
//     $(document).tooltip({
//        track: true;
//     });     
   });
      
</script>
<div style="margin: 15px; font-size: 90%;">

	<form action="/ecns/warehouse/insIncomeDtl" name="income" method="post">
		<input type='hidden' name='storeman_id' value="<?echo $storeman_id;?>">

		<input type='hidden' name='income_no' value="<? echo $income_no ?>"> <input
			type='hidden' name='income_date' value="<? echo $income_date ?>"> <input
			type='hidden' name='purpose' value="<? echo $purpose ?>" /> <input
			type='hidden' name='supplier_id' value="<? echo $supplier_id ?>"> <input
			type='hidden' name='accountant_id' value="<? echo $accountant_id ?>">
        
    <? 
/* $this->load->view('warehouse/plugin/exfilter'); */
				// print_r($spare);
				// echo "</br>";
				// print_r($qty);
				// echo "</br>";
				
				echo "<input type='hidden' name ='count' id='count' value ='$count'/>";
				$j = 1;
				for($i = 0; $i < $count; ++ $i) {
					$id_beginQty = $j . "_beginQty";
					$name_beginQty = $j . "_qty";
					$name_pallet_id = $j . "_pallet_id[]";
					$id_pallet_id = $j . "_pallet1";
					$name_palletQty = $j . "_palletQty[]";
					$div_serial = $j . "_wrapSerial";
					$removePallet_id = "removePallet_" . $j;
					$sparePalletcnt = "p_cnt_" . $j;
					$isSerial = "isSerial[]";
					$isSerialId = "isSerial_" . $j;
					$addSerialname = 'addSerial[]';
					$addSerialId = 'addSerial_' . $j;
					echo "<fieldset style='margin: 0 20px;'>";
					echo "<legend>$spare[$i] -н агуулахын мэдээлэл</legend>";
					echo "<label for='code'>Тоо/хэмжээ:</label>";
					echo "<label style='text-align:left';>$qty[$i]</label>";
					echo "<input type='hidden' name ='$name_beginQty' id='$id_beginQty' value='$qty[$i]' ";
					echo "<p align='right' id='palletBtn'>";
					echo "<label></label><input type='button' name='addPallet' onclick='makePallet($j);' value='Тавиур +' title='Тавиур + гэж байгаа бол Нэмэх тавиурыг эхний тавиураас өөр байхааар сонго'/>";
					echo "<input type='button' name='remPallet' id='$removePallet_id' onclick='removePallet($j)' value='Тавиур -' />";
					echo "</p>";
					echo "<p><label for=''>Агуулах:</label>";
					echo form_dropdown ( 'warehouse_id', $warehouse, null, 'id=warehouse_id' );
					echo "</p>";
					echo "<p><label for=''>Тавиур:</label>";
					echo form_dropdown ( $name_pallet_id, $pallet, NULL, "id=$id_pallet_id" );
					echo "<label for='code'>Тавиур дээрх тоо/ш:</label>";
					echo "<input name='$name_palletQty' id='palletQty1' size='4' value='$qty[$i]' maxlength='4' style='width:40px' title='Тавиур дээрх тоо/ш-н нийлбэрүүд нь нийт тоо/ш тэй тэнцүү байх ёстой!'";
					echo "</p>";
					echo "<input type='hidden' name='$sparePalletcnt' value=1 id='$sparePalletcnt' />";
					echo "<input type='hidden' name='spare_id[]' value='$spare_id[$i]' />";
					echo "<input type='hidden' name='serialCLD' id='serialCLD' value=no />";
					echo "<span id='pallet_$j'>";
					echo "</span>";
					echo "<div>";
					echo "<p><span style='color:red; font-style:italic;'>Сериал дугаар нэмэх бол [Тавиур +] [Тавиур -] хасаж болохгүй! Тиймд тавиураа сонгож, тоо/ш шалгаж гүйцээд дараах сонголтыг хий! </span></p>";
					echo "<p align='right'>";
					echo "<input type='checkbox' id='$isSerialId' name='$isSerial' value='no' onclick='showAddSpare($j);'/>Сериал дугаартай.";
					echo "<input type='button' name='$addSerialname' id='$addSerialId' onclick='makeSerial($j)' value='Сериал +' /></p>";
					echo "<div id ='$div_serial'>";
					echo "</div>";
					echo "</div>";
					echo "</fieldset>";
					$j ++;
				}
				echo "<div class='submits'>";
				echo "<input type='submit' value='Орлогод авах' name ='submit'/>";
				echo "<input type='button' value='Цуцлах' name ='cancel' onclick=\"javascript:document.location='/ecns/warehouse/income'\"/>";
				echo "</div>";
				
				?>
    </form>
	<script language="JavaScript" type="text/javascript"
		xml:space="preserve">   
/*         var chk_form  = new Validator("income");
         chk_form.setAddnlValidationFunction(chkSpare);
         //chk_form.addValidation("incomed_date","req", "Огноог оруулна уу!");
         //chk_form.addValidation("qty","req", "Орлогод авах тоо оруулна уу!");              
         chk_form.setAddnlValidationFunction(chkPalletQty);
         chk_form.setAddnlValidationFunction(chkSerialPart);   
        //spare         
        function chkSpare(){
           var spare=$("#spare_id");
           if(spare.val()===0||spare.val()===null||spare.val===''){
              alert("Сэлбэг сонгогдоогүй байна! Жагсаалтаас сонгоно уу!");
              $('#spare').focus();                
              return false;
           }else              
              return true;
        }
        function chkPalletQty(){
            var palletQty, beginQty, total, subtotal=0;
            var flag=true;            
            beginQty=document.getElementById("beginQty");            
            palletQty=document.getElementsByName("palletQty[]");
            total =beginQty.value;
            //alert(palletQty[0].value+"bqty:"+total);
            
            for (var i = 0; i < palletQty.length; i++){        
               if (palletQty[i].value==""){
                  alert("Тавиур дахь тоо ширхэгийг оруулна уу");
                  palletQty[i].focus();           
                  flag=false;            
               }else
                  subtotal +=parseInt(palletQty[i].value);               
            }
            if(total!=subtotal){
               var msg="Орлогод авч буй тоо тавиур дахь тооноос зөрж байна.\n Тавиур дахь тоог шалгана уу!\n Тавиур дахь тоо хүрэхгүй бол [Өөр тавиурт +] дар!";
               alert(msg);
               flag=false;
            }
            return flag;            
         }
        function chkSerialPart(){
            var palletCnt=document.getElementById("count").value;
            var palletQty, i=1, j=1, pallet, selPallet, pSerial, pPartnumber, serialErr=true, partErr=true, isSerial;           
            var CLD=document.getElementById('serialCLD').value;   
            var isSerial=document.getElementById('isSerial');               
            if(isSerial.checked===true&&CLD==='yes'){
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
                   alert("Сериал дугаарыг шалгана уу! Хоосон бүртгэхгүй!")
                   return false;
               }              
           }else{
              var msg ="Хэрэв сериал нэмэх бол Тийм сонголтийг хийгээд Сериал+ Товчийг дарж утгийг өгнө үү!\n\
              Энэ сэлбэгийн орлогийг сериал дугааргүй хадгалах уу!";
              var r=confirm(msg);
              return r;           
           }               
        }    
         */           
       // when click func removePallet(spare_no) then find spare_no by id
         $("#removePallet").click(function () {
            $("#pallet #pwrap:last-child").remove();
            var bcount = document.getElementById('p_cnt').value;   
            if(bcount >1)
               document.getElementById('p_cnt').value=bcount-1;               
         });
         
      </script>
</div>

