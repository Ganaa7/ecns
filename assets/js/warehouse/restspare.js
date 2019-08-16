  $(function() {
      var grid =$("#grid");
      grid.jqGrid({
        url:'/ecns/wm_ajax/restspare',
        datatype: 'xml',
        mtype: 'GET',
        colNames:['Он', 'Хэсэг', 'Тасаг', 'Төхөөрөмж',  'Сэлбэг', 'Ашиглаж буй тоо/ш','Байх ёстой тоо/ш','Эхний/үлд', 'Эцсийн/үлд', 'Хадгалсан', 'Үйлдэл'], /*'Ашиглалтад орсон','Ашигласан жил'*/
        colModel :[ 
          {name:'year', index:'year', width:20, align:'right'}, 
          {name:'section', index:'section', width:30}, 
          {name:'sector', index:'sector', width:40}, 
          {name:'equipment', index:'equipment', width:45},           
          {name:'spare', index:'spare', width:80}, 
          // {name:'launchyear', index:'launchyear', width:35, align:'right'}, 
          // {name:'usingyear', index:'usingyear', width:30, align:'right'},       
          {name:'usingQty', index:'usingQty', width:35, align:'right'}, 
          {name:'needQty', index:'needQty', width:35, align:'right'},           
          {name:'beginQty', index:'beginQty', width:30, align:'right'},           
          {name:'endQty', index:'endQty', width:30, align:'right'},           
          {name:'recordby', index:'recordby', width:30},
          {name:'action', index:'action', width:30, formatter:incomeAction}
        ],
        pager: jQuery('#pager'),
        rowNum:15,
        rowList:[15,20,30],
        sortname: 'spare_id',
        sortorder: 'desc',
        viewrecords: true,
        caption: 'Нэгдсэн бүртгэл::Сэлбэгийн жагсаалт',
        autowidth:true,
        height: 400,
        width:'100%',
        subGrid: false
    }).navGrid("#pager",{edit:false,add:false,del:false,search:true});     
    
    $("#searchbtn").click(function(){
	jQuery("#grid").jqGrid('searchGrid',
		{sopt:['eq','ne', 'cn','bw','lt','gt','ew']}
	);
    });
    
    function incomeAction (cellvalue, options, rowObjcet) {
       if(cellvalue==='action')
          return '<a href ="#" onclick =editRestSpare(' + options.rowId + ')>Засах</a> | <a href = "#" onclick="delRestSpare('+options.rowId+')">Устгах</a>';      
      else return '';
    }   
   
   //search here
    $('#search').click(function (){
       var spare_id=$('#spare_id').val(), spare=$("#spare").val();
       if(spare_id==0&&(spare=!null||spare!="")){
          alert("Ийм сэлбэг байхгүй байна!");
       }else{
          if((spare==null||spare=="")&spare_id!=0){
             spare_id=0;              
          }
          grid.jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare?spare_id='+spare_id, page: 1}).trigger("reloadGrid");
       }
    });
    
   //filter here
    $('#filterBy').click(function (){
       var section_id=$('#section_id').val(), sector_id=$("#sector_id").val(), equipment_id=$("#equipment_id").val();
       if(section_id!="0"||sector_id!="0"||equipment_id!="0"){          
          $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare?section_id='+section_id+'&sector_id='+sector_id+'&equipment_id='+equipment_id, page: 1, search:true }).trigger("reloadGrid");
       }else{
          $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare', page: 1, search:false }).trigger("reloadGrid");
       }
    });
    
    $( "#dialog" ).dialog({
     autoOpen: false,
     width: 450,
     modal:true,
     buttons: [
     {
          text: "Гүйцсэн",
          click: function() {
            //validate spare, qty, reason
            if($('#spare2').val()==0||$('#spare2').val()==null){
                alert("Нэг сэлбэг сонгоно уу?");
                $('#spare2').focus();
            }else if($('#regdate').val()==0||$('#regdate').val()==null){
                alert("Огноог сонгоно уу?");
                $('#regdate').focus();
            }else 
            // if($('#launchYear').val()==0||$('#launchYear').val()==null){
            //     alert("Ашиглалтад орсон оныг оруулна уу!");
            //     $('#launchYear').focus();
            // }else 
            // if($('#usingYear').val()==0||$('#usingYear').val()==null){
            //     alert("Ашигласан оныг оруул!");
            //     $('#usingYear').focus();
            // }else 
            if($('#usingQty').val()==0||$('#usingQty').val()==null){
                alert("Ашигласан тоо хэмжээг оруул!");
                $('#usingQty').focus();
            }else if($('#needQty').val()==0||$('#needQty').val()==null){
                alert("Шаардлагатай тоо хэмжээг оруул!");
                $('#needQty').focus();
            }               
            else{
               $.post( '/ecns/wm_ajax/insrest', {spare_id:$('#spare_id').val(), 
                   regdate:$('#regdate').val(), launchYear:$('#launchYear').val(), 
                   usingYear:$('#usingYear').val(), usingQty:$('#usingQty').val(),
                   needQty:$('#needQty').val()})
                 .done(function(data){
                    $('#dialog').dialog( "close" );
                    $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare', page: 1}).trigger("reloadGrid");
                 });
            }
          }
         },
         {
             text: "Болих",
             click: function() {
                $( this ).dialog( "close" );
             }
         }
        ]
    });    
    
    // Link to open the dialog
    $( "#dialog-link" ).click(function( event ) {
        $( "#dialog" ).dialog( "open" );
        event.preventDefault();
    });
    
    //spare complete
     $( "#spare2" ).autocomplete({
       source: "spareJson",
       minLength: 2,
       select: function( event, ui ) {
          if(ui.item.value){             
             $('#spare_id').val(ui.item.id);
              $('#part_number').text(ui.item.part);
             //measure.text(ui.item.measure);
          }
       },
       search:function( event, ui){
          $('#part_number').text("");
          $('#spare_id').val(0);
          //measure.text("");
          //qty.val("");
       }
    });
    
     //datepicker
     $( "#regdate" ).datepicker(
        { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: true });    
         
     //edit dialog
     $( "#edialog" ).dialog({
     autoOpen: false,
     width: 450,
     modal:true,
     open: function (event, ui) {
        $.post( '/ecns/wm_ajax/getRestSpare', {spare_id:$("#espare_id").val()}, function(result){
           //here get result
          $.each(result, function(value, text) {
            switch(value){                
                case "spare":
                  $('#espare').text(text);
                  break;
                case "part_number":
                  $('#epart_number').text(text);
                  break;
                case "date":
                  $('#edate').val(text);
                  break;
                case "usingQty":
                  $('#eUsingQty').val(text);
                  break;
                case "needQty":
                  $('#eNeedQty').val(text);
                  break;
                case "launchyear":
                  $('#elaunchYear').val(text);
                  break;
                default:
                    $('#eUsingYear').val(text);
                  break;
            }
          });            
        });
	$('#espare').val("");
     },
     buttons: [
     {
          text: "Гүйцсэн",
          click: function() {
            //validate spare, qty, reason
            if($('#edate').val()==null){
                alert("Огноог сонгоно уу?");
                $('#edate').focus();
            }else 
            // if($('#elaunchYear').val()==0||$('#elaunchYear').val()==null){
            //     alert("Ашиглалтад орсон оныг оруулна уу!");
            //     $('#elaunchYear').focus();
            // }else 
            //   if($('#eUsingYear').val()==0||$('#eUsingYear').val()==null){
            //     alert("Ашигласан оныг оруул!");
            //     $('#eUsingYear').focus();
            // }else
             if($('#eUsingQty').val()==0||$('#eUsingQty').val()==null){
                alert("Ашигласан тоо хэмжээг оруул!");
                $('#eUsingQty').focus();
            }else if($('#eNeedQty').val()==0||$('#eNeedQty').val()==null){
                alert("Шаардлагатай тоо хэмжээг оруул!");
                $('#eNeedQty').focus();
            }               
            else{
               $('#edialog').dialog( "close" );                
               $.post( '/ecns/wm_ajax/uRest', {spare_id:$('#espare_id').val(), 
                   regdate:$('#edate').val(), launchYear:$('#elaunchYear').val(), 
                   usingYear:$('#eUsingYear').val(), usingQty:$('#eUsingQty').val(),
                   needQty:$('#eNeedQty').val()})
                 .done(function(data){
                    $('#dialog').dialog( "close" );
                    $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare', page: 1}).trigger("reloadGrid");
                 });                
             }
           }
      },
      {
        text: "Болих",
        click: function() {
           $( this ).dialog( "close" );
        }
      }
      ]
    });
    
    //edate
     $( "#edate" ).datepicker(
        { dateFormat: "yy-mm-dd", showWeek: true, gotoCurrent: false});   
}); 

//function restspare
function editRestSpare(spare_id){    
   $("#espare_id").val(spare_id);      
   $( "#edialog" ).dialog( "open" );
   event.preventDefault();
}
// del Rest Spare 
function delRestSpare(spare_id){             
   var answer = confirm("Энэ сэлбэгийн жагсаалтийг устгахдаа итгэлтэй байна уу?");
   if(answer){
      $.post( '/ecns/wm_ajax/delRest', {spare_id:spare_id})
      .done(function(data){
         grid.jqGrid('setGridParam', { url: '/ecns/wm_ajax/restspare', page: 1}).trigger("reloadGrid");
      });
   }   
} 
