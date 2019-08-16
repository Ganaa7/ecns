/* 
 * warehouse js functions
 */
// When Page is Loaded Then Load this 
function getEquipment(id){
   var xmlhttp;
   if (id===""){
      document.getElementById("equipment").innerHTML="";
      return;
   }
   if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function(){
      if(xmlhttp.readyState==4 && xmlhttp.status==200){
         document.getElementById("equipment").innerHTML=xmlhttp.responseText;
      }
    }
   xmlhttp.open("GET","/ecns/wm_ajax/getEquipment?section_id="+id,true);
   xmlhttp.send();      
}
// spare list
function callSpare(id){      
   var xmlhttp;   
   var equipment_id;
   equipment_id=document.getElementById("equipment_id").value;
    
   if (id==""){
      document.getElementById("spare").innerHTML="";
      return;
   }    
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   
   xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
         document.getElementById("spare").innerHTML=xmlhttp.responseText;
      }
   }
   
   xmlhttp.open("GET","/ecns/wm_ajax/getSpare?id="+id+"&equipment_id="+equipment_id,true);
     xmlhttp.send();      
  }  
//Зарлага гаргахад ашиглагдах
function expenseDetail(spare_id){     
     if(spare_id!==null){        
        var xmlhttp;         
        if (spare_id===""){
           document.getElementById("spares").innerHTML="";
           return;
        }
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
        }else{// code for IE6, IE5
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
           if (xmlhttp.readyState==4 && xmlhttp.status==200){
              document.getElementById("spares").innerHTML=xmlhttp.responseText;
           }
        }
        xmlhttp.open("GET","/ecns/wm_ajax/getSpareDetial?spare_id="+spare_id,true);
        xmlhttp.send();      
     }else
        alert("Сэлбэг сонгогдоогүй байна! Сэлбэгийг сонгоно уу?");
  }  
function showDetail(id){
    if($('#exPallet').text()!="")
       $("#exPallet").remove();
  
    $("#"+id).after("<tr id='exPallet'><td colspan ='9'><span id ='detail'></span></td></tr>");
    
    var xmlhttp;    
    if (id===""){
       document.getElementById("detail").innerHTML="";
       return;
    }
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
    }else
    {// code for IE6, IE5
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState===4 && xmlhttp.status===200)
      {
         document.getElementById("detail").innerHTML=xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET","/ecns/wm_ajax/getBalanceDetail?ct_id="+id,true);
    xmlhttp.send();   
       
}   
// main detail
function getDetail(id){   
   if($('#exPallet').text()!="")
      $("#exPallet").remove();
  
   $("#"+id).after("<tr id='exPallet'><td colspan ='9'><span id ='Detail'></span></td></tr>");
  
   var xmlhttp;    
   if (id===""){
      document.getElementById("Detail").innerHTML="";
      return;
   }
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState===4 && xmlhttp.status===200){
         document.getElementById("Detail").innerHTML=xmlhttp.responseText;
      }
   };
   xmlhttp.open("GET","/ecns/wm_ajax/getDetail?spare_id="+id,true);
   xmlhttp.send();   
}
//warehouse BALANCE AND INCOME USED SCRIPTS  
function callPallet(){          
    var type='text';     
    var count = document.getElementById("count").value;
    var idName="detail"+count++;
  
    //Create an input type dynamically.    
    var labelPallet =document.createElement("label");
    labelPallet.innerHTML ='Тавиур:';
     
    var tagSpan = document.createElement("span");
    tagSpan.id=idName;     
    var label = document.createElement("label");
    label.id="label";
    label.innerHTML ='Тавиур дээрх тоо/ш:';
     
    var counter = document.createElement("input");
    counter.setAttribute("type", "hidden");
    counter.setAttribute("name", "count");
    counter.setAttribute("value", count);
    counter.setAttribute("id", "count");
     
    var element = document.createElement("input");

     //Assign different attributes to the element.
    var palletId = "palletQty"+count;
    var wrapId = "pwrap";
     
    var palletDiv =document.createElement("p");     
    palletDiv.setAttribute("id", wrapId);
     
     element.setAttribute("type", type);     
     element.setAttribute("name", "palletQty[]");
     element.setAttribute("style", "width:30px");
     element.setAttribute("size", 5);
     element.setAttribute("maxlength", 5);
     element.setAttribute("id", palletId);
     
     
     var foo = document.getElementById("pallet");
     // call Pallet here
     getPallet(1, idName, count);
     
    //Append the element in page (in span).
     foo.appendChild(palletDiv);     
     palletDiv.appendChild(labelPallet);
     palletDiv.appendChild(tagSpan);
     palletDiv.appendChild(label);
     palletDiv.appendChild(element);
     palletDiv.appendChild(counter);     
     document.getElementById("count").value=count;        
  }
function getPallet(spare_cnt, idName, idcnt){
    var xmlhttp, warehouse_id;
    warehouse_id = document.getElementById("warehouse_id").value;
    if (idName===""){
       document.getElementById(idName).innerHTML="";
       return;
    }   
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
       xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
       if (xmlhttp.readyState===4 && xmlhttp.status===200){
          document.getElementById(idName).innerHTML=xmlhttp.responseText;
       }
    };
    xmlhttp.open("GET","/ecns/wm_ajax/getPallet?warehouse_id="+warehouse_id+"&idcnt="+idcnt+"&spare_cnt="+spare_cnt,true);
    xmlhttp.send();    
  }  
function copyValue(val){     
   document.getElementById('palletQty1').value=val;  
} 
function showAddSpare(spare_cnt){
     if($("#isSerial_"+spare_cnt).attr('checked')){
         $("#isSerial_"+spare_cnt).val('yes');
         $('#addSerial_'+spare_cnt).show();
     }else {
         $("#isSerial_"+spare_cnt).val('no');
         $('#addSerial_'+spare_cnt).hide();
     }     
}
//Сериал дуудах хэсэг
function setSerial(formName){
   var setserial=confirm("Сериал + тохиолдолд Тавиур + - болохгүй тул Тавиурт тавих тоо/ш шалгаж дуусаад сериал нэмнэ үү!");
      if(setserial===true){ // serial + тохиолдолд
         var Myform= document.forms[formName];
         var total =parseInt($("#beginQty").val()); //нийт тоо хэмжээ
         // dung ehnii duntei tentsuu bgaa         
         var pallet_id = Myform.elements['pallet_id[]'];  
         var palletQty=document.getElementsByName("palletQty[]");
         var subtotal =0, flag =0, i=0;
         do {   
           subtotal +=parseInt(palletQty[i].value);
           i++;
         }while(i < palletQty.length)
            
         if(total === subtotal){
            document.getElementById('serialCLD').value ='yes';
           for (i = 0 ; i<pallet_id.length ; ++i){           
              //var pallet_qty = document.getElementById('palletQty'+j).value;
              if(palletQty[i].value===0) alert("Тоо ширхэгийг оруулаагүй байна! Тоо ширхэгийг оруулна уу!");
              else{                 
                 callDetail(pallet_id[i].value, palletQty[i].value);
                 flag=1;
              }              
           }
        }else
            alert("Нийт үлдэглийн тоо тавиур дээрх тоотой тэнцэхгүй байна!!!");
        if(flag===1){
              $('input[name="addPallet"]').attr('disabled', true);
              $('input[name="removePallet"]').attr('disabled', true); 
              //$('input[name="removePallet"]').remove(); 
              //$('input[name="addPallet"]').remove(); 
           }         
     }
  }  
// end serial  
function callDetail(spare_cnt, pallet_id, val){     
     var spanqty= document.getElementById(spare_cnt+"_wrapSerial");   
     var id_serial = spare_cnt+'_serial_'+pallet_id;
     var div = document.createElement("div");
     div.setAttribute("id", id_serial);
     spanqty.appendChild(div);
     var xmlhttp;
     if (id_serial==""){
        document.getElementById(id_serial).innerHTML="";
        return;
     }
     if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
     }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
              document.getElementById(id_serial).innerHTML=xmlhttp.responseText;
        }
     }     
     xmlhttp.open("GET","/ecns/wm_ajax/palletSerial?pallet_id="+pallet_id+"&qty="+val+"&spare_cnt="+spare_cnt,true);
     xmlhttp.send();      
  }
// when key up in added palletQty call this function
function subQty(count){    
    var Qty=document.getElementById("palletQty1").value;
    var subCount =document.getElementById("palletQty"+count).value;
    //alert("Qty:"+Qty +"sub"+subCount);
    document.getElementById("palletQty1").value=Qty-subCount;
    return;
}
// ene function Захиалгийн жагсаалтад сэлбэг нэмэхэд дуудагдана
function getSpare(id){      
   var xmlhttp;          
   if (id==""){
      document.getElementById("spare").innerHTML="";
      return;
   }
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   
   xmlhttp.onreadystatechange=function(){
     if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("spare").innerHTML=xmlhttp.responseText;
     }
   }  
   xmlhttp.open("GET","/ecns/wm_ajax/getSpare?equipment_id="+id,true);
   xmlhttp.send();   
}

function getSelect(section_id){
   var sector_id;
   // Sector selection   
   $.post( '/ecns/wm_ajax/getSector', {section_id:section_id}, function(secOption) {
      var select = $('#sector_id');
      if(select.prop) {
         var options = select.prop('options');
      }else {
         var options = select.attr('options');
      }        
      $('option', select).remove();
      $.each(secOption, function(val, text) {         
         options[options.length] = new Option(text, val);                  
      });
      
   }).done(function(){
       sector_id =$('#sector_id :selected').val();
       $('#flag1').text(sector_id);       
       getEquipments(sector_id);
      });
}

// getEquipments from filter
function getEquipments(sector_id){
   $.post( '/ecns/wm_ajax/getEquipments', {sector_id:sector_id}, function(newOption) {   
      var select = $('#equipment_id');  
      
      //var  select =$('#field-equipment_id');      
        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }          
      
      $('option', select).remove();
      $.each(newOption, function(val, text) {
         options[options.length] = new Option(text, val);
      });
   });
}
function getEmployee(section_id){
   $.post( '/ecns/wm_ajax/getEmployee', {section_id:section_id}, function(newOption) {   
      var select = $('#employee');
      if(select.prop) {
         var options = select.prop('options');
      }else {
         var options = select.attr('options');
      }        
      $('option', select).remove();
      $.each(newOption, function(val, text) {
         options[options.length] = new Option(text, val);
      });
   });
}
//incomeDetail_function    
function makePallet(spare_cnt){     
   var type='text';    
   var palletCnt ="p_cnt_"+spare_cnt;    
   var count = document.getElementById(palletCnt).value; // pallet counter
   var idName=spare_cnt+"detail"+count++;
   //Create an input type dynamically.    
   var labelPallet =document.createElement("label");
   labelPallet.innerHTML ='Тавиур:';
   var tagSpan = document.createElement("span");
   tagSpan.id=idName;
     
   var label = document.createElement("label");
   label.id="label";
   label.innerHTML ='Тавиур дээрх тоо/ш:';
     
   var counter = document.createElement("input");
    counter.setAttribute("type", "hidden");
    counter.setAttribute("name", palletCnt);
    counter.setAttribute("value", count);
    counter.setAttribute("id", palletCnt);    
    
    //Assign different attributes to the element.
    var palletId = "palletQty"+count;
    var wrapId = "pwrap";     
    var palletDiv =document.createElement("p");     
    palletDiv.setAttribute("id", wrapId);
    var palletQtyName=spare_cnt+"_palletQty[]";
     
    var element = document.createElement("input");
    element.setAttribute("type", type);     
    element.setAttribute("name", palletQtyName);
    element.setAttribute("style", "width:30px");
    element.setAttribute("size", 5);
    element.setAttribute("maxlength", 5);
    element.setAttribute("id", palletId);

    //destination div id
    var dist_id = "pallet_"+spare_cnt;
    
    var foo = document.getElementById(dist_id);
    // call Pallet here
    getPallet(spare_cnt, idName, count);
     
    //Append the element in page (in span).
    foo.appendChild(palletDiv);     
    palletDiv.appendChild(labelPallet);
    palletDiv.appendChild(tagSpan);
    palletDiv.appendChild(label);
    palletDiv.appendChild(element);
    palletDiv.appendChild(counter);     
    document.getElementById(palletCnt).value=count;        
  }  
function makeSerial(spare_cnt){      
   //тавиурын товчуудыг идэвхигүй болгоно.  
   $('input[name="addPallet"]').hide();
   $('input[name="remPallet"]').hide();
   var Myform= document.forms['income'];
   var name_bqty = "#"+spare_cnt+"_beginQty";
   var total =parseInt($(name_bqty).val()); //нийт тоо хэмжээ
    // dung ehnii duntei tentsuu bgaa         
   var pallet_id = Myform.elements[spare_cnt+'_pallet_id[]'];  
   var palletQty=document.getElementsByName(spare_cnt+"_palletQty[]");
   var subtotal =0, flag =0, i=0;
   do {   
      subtotal +=parseInt(palletQty[i].value);
      i++;
   }while(i < palletQty.length)            
   if(total === subtotal){
       document.getElementById('serialCLD').value ='yes';
      for (i = 0 ; i<pallet_id.length ; ++i){           
         //var pallet_qty = document.getElementById('palletQty'+j).value;
         callDetail(spare_cnt, pallet_id[i].value, palletQty[i].value);
         flag=1;              
      }
   }else
       alert("Нийт үлдэглийн тоо тавиур дээрх тоотой тэнцэхгүй байна!!!");
   if(flag===1){
         $('input[name="addPallet"]').attr('disabled', true);
         $('input[name="removePallet"]').attr('disabled', true); 
         //$('input[name="removePallet"]').remove(); 
         //$('input[name="addPallet"]').remove(); 
   }     
  }
//this removePallet by spare no
function removePallet(spare_cnt){
   var removeBtn = "#removePallet_"+spare_cnt;
   var palletLastChild = "#pallet_"+spare_cnt+" #pwrap:last-child";
   var palletCnt = "p_cnt_"+spare_cnt;
   $(palletLastChild).remove();
   var bcount = document.getElementById(palletCnt).value;   
   if(bcount >1)
      document.getElementById(palletCnt).value=bcount-1;
}

