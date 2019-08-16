
var base_url = document.location.origin;
if(base_url.search("ecns")<0)  base_url = base_url+ '/ecns';

// EVENT ACTION CALLING FUNCTION
function call_event(action){
    var form;
    var status=null;
    var lastval;
    var log_name;
    var closed_date;
    var provedby;
    var sum;
    form = document.eventlist;

    if(form){
        for(var i=0; i<form.elements.length; i++) {
            if (form.elements[i].type == "checkbox") {
            if(form.elements[i].checked){
                lastval =form.elements[i].value;
                status= true;
                break;
            }else status = false;
            }
        }         
    }else
       alert("Техник үйлчилгээнээс нэгийг сонгоно уу?");


    switch(action){
        case 'close':
            if(status==true){
                log_name = 'log'+lastval.toString();
                var x = document.getElementById(log_name);
                closed_date ='date'+lastval.toString();
                var cdate = document.getElementById(closed_date);

                if(x.value==""){
                    alert("Техник үйлчилгээний дугаар идэвхижээгүй байна!!!\n Техник үйлчилгээний дугаарыг ЕЗИ идэвхижүүлнэ.");   
                }else{
                    if(cdate.value !="")
                        alert("Энэ техник үйлчилгээ хаагдсан байна!!!\n Хаагдсан техник үйлчилгээг хааж болохгүй.");   
                    else
                        document.location=base_url+"/eventlog/close/"+lastval;
                }
            }else
                alert("Хаах техник үйлчилгээг сонгоно уу?");
            break;

        case 'edit':
            if(status==true){
                document.location=base_url+"/eventlog/edit/"+lastval;
            }
            else
                alert('Засах техник үйлчилгээг сонгоно уу?');
            break;

        case 'prove':
            if(status==true){   
                closed_date ='date'+lastval.toString();
                var cdate = document.getElementById(closed_date);
                provedby ='prove'+lastval.toString();
                var proved =document.getElementById(provedby);
                if(cdate.value=="")
                    alert("Энэ техник үйлчилгээг хаагаагүй байна!\n Хаасаны дараа үйлдэл гүйцэтгэж болно.");   
                else{
                    if(proved.value)
                        alert("Техник үйлчилгээг хаагдсан байна!\n Боломжгүй.");   
                    else    document.location=base_url+"/eventlog/prove/"+lastval;                        
                }
            }else
            alert('Хаасан техник үйлчилгээг сонгоно уу!!!\nЕЗИ техник үйлчилгээтэй танилцсанаар гэмтэл бүрэн хаагдана.');
            break;

        case 'activate':
            if(status==true){
                log_name ='log'+lastval.toString();
                var x = document.getElementById(log_name);
                if(x.value!="")
                    alert('Энэ техник үйлчилгээний дугаарыг идэвхижүүлсэн байна!!\n Өөр Техник үйлчилгээг сонгоно уу!!!')
                else
                    document.location=base_url+"/eventlog/activate/"+lastval;
            }     
            else
                alert('Идэвхижүүлэх техник үйлчилгээг сонгоно уу?');
            break;

        case 'delete':
            if(status==true){
                var answer = confirm("Ta энэ техник үйлчилгээг үнэхээр устгах уу!!!");
                if(answer)
                    document.location=base_url+"/eventlog/delete/"+lastval;
                else
                    alert('Устгах үйлдэл цуцлагдлаа!');
            }
            else
                alert('Устгах техник үйлчилгээг сонгоно уу?');
            break;

        default:
           alert("Техник үйлчилгээг сонгоно уу!!!");
        break;
    } 
}
// END HERE
function submitform(page){
   //document.myform.submit();
   document.location=base_url+"/index.php/order/created_datetime/"+page;
}

function show_alert(msg){
    alert(msg);
}

// Ajax function loader
function showSector(str){
    showPosition(str);
    var xmlhttp;    
    if (str=="")
    {
    document.getElementById("txtHint").innerHTML="";
    return;
    }    
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/get_table?q="+str,true);
    xmlhttp.send();
}

function showPosition(str){
    var xmlhttp;    
    if (str=="")
    {
    document.getElementById("txtPos").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("txtPos").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/get_position?q="+str,true);
    xmlhttp.send();
}

//select one employee
function set_checkbox(field, check_value){
    // declare form checkbox name 
    if(field.length){
       for (i = 0; i <field.length; i++){

          if(field[i].value ==check_value)
              field[i].checked = true;
          else 
             field[i].checked = false;       
       }
    }else
       if(!field.checked)
          field.checked = true;


    //if there is one before checked in checkbox
    //uncheck it and check the one checkbox
    
}

function showPart(str){
    var element =  document.getElementById('equipment');
    
    if (typeof(element) != 'undefined' && element != null)
    {
       document.getElementById("equipment").value=str;
    }
    
    var xmlhttp;     
    if (str=="")
    {
    document.getElementById("txtPart").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("txtPart").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/getPart?id="+str,true);
    xmlhttp.send();
    
}

function showType(str){
    var xmlhttp;    
    if (str=="")
    {
    document.getElementById("txtType").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("txtType").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/getType?id="+str,true);
    xmlhttp.send();
    
}

function showItemtype(section_id){
    var part;
    var material;
    part=document.getElementById("part");
    material=document.getElementById("material");
    document.getElementById("section_id").value=section_id;
    if(part.value==0&&material.value==0)
        showEquipment(section_id);
    else
        if(part.value==1)
            showEquipment(section_id);
        else
            if(material.value=1)
                showMaterial(section_id);
    
}

function showEquipment(section_id){       
    var xmlhttp;        
    if (section_id=="")
    {
    document.getElementById("txtEquipment").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("txtEquipment").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/getEquipment?section_id="+section_id,true);
    xmlhttp.send();
    
}

function showMaterial(section_id){
    var xmlhttp;    
    if (section_id=="")
    {
    document.getElementById("varMaterial").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("varMaterial").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/getMaterial?section_id="+section_id,true);
    xmlhttp.send();
    
}

function showSerial(choose){  
    var qty=document.getElementById("quantity").value;
    
    if(qty==""){
        alert("Тоо хэмжээг оруулна уу!");
        return ;
    }
            
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("serial").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",base_url+"/ajax/getSerial?qty="+qty+"&choose="+choose,true);
    xmlhttp.send();

}
              
function checkFilter(){
    var form;
    form =document.filterOrder;
    var section = document.getElementById("section_id");
    var itemtype = document.getElementById("itemtype_id");
    var item = document.getElementById("item_id");
    var equipment = document.getElementById("equipment_id");
    var equipment = document.getElementById("equipment_id");
    var item= document.getElementById("item_id");
    var error=new Array(); 
    var isError = false;
    
    var whole_msg = "<p class='error'><ul>\n";
    if(section.value=="0")
      error[0]="Хэсэг сонгогдоогүй байна! Хэсгийг сонгоно уу!";  
    
    if(itemtype.value=="0")
        error[1]="Барааны төрлийг сонгоогүй байна сонгоно уу!";  
    else{
        if(itemtype.value=="1")
            if(equipment.value=="0")
                error[2]="Тухайн төхөөрөмжийг сонгоогүй байна сонгоно уу!";  
        if(itemtype.value=="2")                                       
            if(item.value=="0")
                error[3]="Захиалах материалыг сонгоно уу!";  
    }
    
    for(var n=0; n<error.length; n++){
        if(error[n]!=null){
           whole_msg += "<li>" + error[n]+ "</li>\n";
           isError = true;
        }
    }
    whole_msg += "</ul></p>";
    
    if(isError==true){
        document.getElementById("error").innerHTML=whole_msg;
//        return false;
    }
    //    return true;
    
}

function flagit(id){
    if(id==1){
       document.getElementById("link1").style.color="#0a328c";
       document.getElementById("link2").style.color="#828282";
    }else{        
       document.getElementById("link2").style.color="#0a328c";        
       document.getElementById("link1").style.color="#828282";
    }
 }
 
 function setEquipment(sec_code){   
   $.post( base_url+'/ajax/setEquipment', {sec_code:sec_code}, function(newOption) {   
      var select = $('#equipment_id');
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
 

//updated : 2017-11-21

function showMessage(message, p_class){
   if (!$('p#notification').length){
      //$('#main_wrap').prepend('<p id="notification"></p>');
      $('#nav-bar').prepend('<p id="notification"></p>');
   }
   var paragraph = $('p#notification');
   paragraph.hide();
   paragraph.removeClass();
   // remove all classes from the <p>
   paragraph.addClass(p_class);
   // add the class supplied
   paragraph.html(message);
   // change the text inside
   paragraph.fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}


// function reload(){
//     $("#grid").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
// }