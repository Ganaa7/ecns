$(document).ready(function(){
	alert('hello there');
   $('#outcome_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  


  //  var lastsel2;
  // jQuery("#rowed5").jqGrid({        	
  //   datatype: "local",
  //   height: 250,
  //   colNames:['ID Number','Name', 'Stock', 'Ship via','Notes'],
  //   colModel:[
  //     {name:'id',index:'id', width:90, sorttype:"int", editable: true},
  //     {name:'name',index:'name', width:150,editable: true, editoptions:{size:"20",maxlength:"30"}},
  //     {name:'stock',index:'stock', width:60, editable: true, edittype:"checkbox",editoptions: {value:"Yes:No"}},
  //     {name:'ship',index:'ship', width:90, editable: true, edittype:"select",formatter:'select', editoptions:{value:"FE:FedEx;IN:InTime;TN:TNT;AR:ARAMEX"}},                       
  //     {name:'note',index:'note', width:200, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}}                      
  //             ],
  //   onSelectRow: function(id){
  //     if(id && id!==lastsel2){
  //       jQuery('#rowed5').restoreRow(lastsel2);
  //       jQuery('#rowed5').editRow(id,true);
  //         lastsel2=id;
  //     }
  //   },
  //   editurl: "server.php",
  //   caption: "Input Types"
  // });
  // var mydata2 = [
  //   {id:"12345",name:"Desktop Computer",note:"note",stock:"Yes",ship:"FE"},
  //   {id:"23456",name:"Laptop",note:"Long text ",stock:"Yes",ship:"IN"},
  //   {id:"34567",name:"LCD Monitor",note:"note3",stock:"Yes",ship:"TN"},
  //   {id:"45678",name:"Speakers",note:"note",stock:"No",ship:"AR"},
  //   {id:"56789",name:"Laser Printer",note:"note2",stock:"Yes",ship:"FE"},
  //   {id:"67890",name:"Play Station",note:"note3",stock:"No", ship:"FE"},
  //   {id:"76543",name:"Mobile Telephone",note:"note",stock:"Yes",ship:"AR"},
  //   {id:"87654",name:"Server",note:"note2",stock:"Yes",ship:"TN"},
  //   {id:"98765",name:"Matrix Printer",note:"note3",stock:"No", ship:"FE"}
  //   ];
  // for(var i=0;i<mydata2.length;i++)
  //   jQuery("#rowed5").addRowData(mydata2[i].id,mydata2[i]);

});