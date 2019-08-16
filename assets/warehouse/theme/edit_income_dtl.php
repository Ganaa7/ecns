<link rel="stylesheet" href="<?=base_url()?>assets/chosen/chosen.css">
<script src="<?=base_url()?>assets/chosen/chosen.jquery.js"
	type="text/javascript"></script>
<style>
fieldset label {
	font-weight: bold;
}
</style>
<script type="text/javascript">
   $(document).ready(function() {      
     var counts = $('#count').val();          
     var i;
     // Сэлбэгийн тоо хэмжээг аваад тухайн сэлбэг бүрийн addSerial-г нуух
     $('input[name="addSerial[]"]').hide();    
     $(".chosen-select").chosen();
          
        var flag='';   var error = false; 
        $('#btn_id_sub').click(function(){
            //Тухайн сэлбэг бүрээр тоолох
            var total_spare = $('#count').val();
            for(var s=1; s<=total_spare; s++){
               var subtotal = 0;
               var total = $("#"+s+"_qty").val();
               var total_pallet = $('#p_cnt_'+s).val();                       
               // Тухайн тавиурын тоогоор гүйж 
               for (var i = 1; i <= total_pallet; i++){        
                   var current_pal_qty = $('#'+s+"palletQty"+i);
                 if (current_pal_qty.val()==""){
                    alert("Тавиур дахь тоо ширхэгийг оруулна уу");
                    current_pal_qty.focus();                                                        
                    error =true;                           
                    break;                           
                 }
                 else{
                    subtotal +=parseInt(current_pal_qty.val());               
                    error =false;                            
                 }
               }
               // нийт тоо тавиур дээрх тооноос зөрж буйг тогтоох
               if(total!=subtotal){
                    var msg="Орлогод авч буй тоо тавиур дахь тооноос зөрж байна.\n Тавиур дахь тоог шалгана уу!\n Тавиур дахь тоо хүрэхгүй бол [Өөр тавиурт +] дар!";
                    alert(msg);
                    error =true;  
                    break;
                 }
            }                    
             //end here
                    
            if(error ==false){
		   if($("input[name='isSerial[]'").val()=='yes'){
		   		$('.input_serial').each(function( index ) {
				    if($( this ).val()==''){
				     	flag='no';
				    }				    
		   			console.log('log:'+$(this).val());
				});
		   }

		   if($("input[name='isSerial[]'").val()=='yes'){
			   if(flag=='no'){
			      alert('Нэг сериал дугаарын утга хоосон байна утгийг оруулна уу!')
			   }else{			   
				 flag='';
			     $( "#income" ).submit();			
			  }
			}else{
				flag='';
			   $( "#income" ).submit();
			}
              }
        });

   });
      
</script>
<div style="margin: 15px; font-size: 90%;">
	<form action="<?=base_url()?>wh_spare/index/edit_income" name="income" id="income"
		method="post">
            <input type="hidden" name="invoice_id" value="<?=$invoice_id?>">
		<input type='hidden' name='income_no' value="<? echo $income_no ?>"> 
		<input	type='hidden' name='income_date' value="<? echo $income_date ?>">    
		<input	type='hidden' name='supplier_id' value="<? echo $supplier_id ?>">            
    <?php 
/* $this->load->view('warehouse/plugin/exfilter'); */
//var_dump ( $spare );
// echo "</br>";
// print_r($qty);
// echo "</br>";
// echo "error:".$error;                
	echo "<input type='hidden' name ='count' id='count' value ='" . sizeof ( $spare ) . "'/>";
				$j = 1;
				// жагсаалтад буй нийт тоо
				for($i = 1; $i <= sizeof ( $spare ); ++ $i) {
					// $id_beginQty = $j."_beginQty";
					// $name_Amt = $j."_amt";
					// $name_beginQty = $j."_qty";
					// $name_pallet_id = $j."_pallet_id[]";
					// $id_pallet_id = $j."_pallet1";
					// $name_palletQty = $j."_palletQty[]";

					//serial_wrapper
					$div_serial =$i."_wrapSerial";
					$removePallet_id = "removePallet_" . $i;
					
					//тухайн сэлбэгийг хэдэн тавиур ашилаж буйг заана					
					$sparePalletcnt ="p_cnt_".$i;

					$isSerial = "isSerial[]";
					$isSerialId = "isSerial_" . $i;
					$addSerialname = 'addSerial[]';
					$addSerialId = 'addSerial_' . $i;
					echo "<fieldset style='margin: 0 20px;'>";
					echo "<legend>" . $spare [$i] ['name'] . " -н сэлбэгийн мэдээлэл</legend>";
					// эхний сэлбэгийн нийт тоо
					echo "<input type='hidden' name =spare[$i][qty] id='" . $i . "_qty' value='" . $spare [$i] ['qty'] . "' >";
					echo "<input type='hidden' name =spare[$i][id] value='" . $spare [$i] ['id'] . "' >";					
					echo "<input type='hidden' name =spare[$i][type_id] value='" . $spare [$i] ['type_id'] . "' >";
					echo "<input type='hidden' name =spare[$i][barcode] value='" . $spare [$i] ['barcode'] . "' >";
					
					// Тавиурын мэдээлэл энд байна!
					echo "<p align='right' id='palletBtn'>";
					echo "<input type='button' name='remPallet_".$i."' id='$removePallet_id' onclick='removePallet($i)' value='Тавиур -' style='float:right'/>";
					echo "<input type='button' name='addPallet_".$i."' onclick='makePallet($i);' value='Тавиур +' title='Тавиур + гэж байгаа бол Нэмэх тавиурыг эхний тавиураас өөр байхааар сонго' style='float:right'/>";
					echo "</p>";
					// Сэлбэгийн нэр
					echo "<p>";
					echo "<label>Сэлбэг:</label>";
					echo "<label>" . $spare [$i] ['name'] . "</label>";
					echo "</p>";
					echo "<p>";
					// Сэлбэгийн Үнэ
					echo "<label>Үнэ</label>";
					echo "<input type='hidden' name ='spare[$i][amt]' id='" . $i . "_amt' value='" . $spare [$i] ['amt'] . "'>";
					echo "<label>" . $spare [$i] ['amt'] . " ₮</label>";
					echo "</p>";
					
					echo "<p>";
					echo "<label>Тоо/хэмжээ:</label>";
					echo "<label>" . $spare [$i] ['qty'] . "</label>";
					echo "</p>";
					echo "<p><label>Эхний barcode:</label>";
					echo "<label>" . $spare [$i] ['barcode'] . "</label>";
					echo "</p>";
					// Агуулах
					echo "<p><label>Агуулах:</label>";
					echo form_dropdown ( 'warehouse_id', $warehouse, null, 'id=warehouse_id' );
					echo "</p>";
					echo "<p><label>Тавиур:</label>";
					// CHANGE 1
					echo form_dropdown ( "spare[$i][pallet][]", $pallet, NULL, "'id=" . $i . "_pallet1 class='chosen-select'" );
					echo "<label for='code'>Тавиур дээрх тоо/ш:</label>";
					// CHANGE 1
					echo "<input name=spare[$i][pallet_qty][] id='".$i."palletQty1' size='4' value='" . $spare [$i] ['qty'] . "' maxlength='4' style='width:40px' title='Тавиур дээрх тоо/ш-н нийлбэрүүд нь нийт тоо/ш тэй тэнцүү байх ёстой!'";
					echo "</p>";
					echo "<input type='hidden' name='$sparePalletcnt' value=1 id='$sparePalletcnt' />";
					echo "<input type='hidden' name='serialCLD' id='serialCLD' value=no />";
					// шинэ pallet-г нэмэх хэсэг pallet wrapper 
					echo "<span id='pallet_$i'>";

					echo "</span>";

					echo "<div>";
					echo "<p><span style='color:red; font-style:italic;'>Сериал дугаар нэмэх бол [Тавиур +] [Тавиур -] хасаж болохгүй! Тиймд тавиураа сонгож, тоо/ш шалгаж гүйцээд дараах сонголтыг хий! </span></p>";
					echo "<p align='right'>";
					echo "<input type='checkbox' id='$isSerialId' name='$isSerial' value='no' onclick='showAddSpare($i);'/>Сериал дугаартай.";
					echo "<input type='button' name='$addSerialname' id='$addSerialId' onclick='makeSerial($i)' value='Сериал +' /></p>";
					echo "<div id ='$div_serial'>";
					echo "</div>";
					echo "</div>";
					echo "</fieldset>";
					$j ++;
				}
				echo "<div class='submits'>";
				echo "<input type='button' value='Орлогод авах' name ='btn_sub' id='btn_id_sub'/>";
				echo "<input type='button' value='Цуцлах' name ='cancel' onclick=\"javascript:document.location='".base_url()."wh_income/index'\"/>";
				echo "</div>";
				
				?>
    </form>
	<script language="JavaScript" type="text/javascript"
		xml:space="preserve">  
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
     </script>
</div>

