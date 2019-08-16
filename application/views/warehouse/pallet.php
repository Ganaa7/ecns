<style>
.hide {
	display: none;
}

.hide_info {
	display: none;
}

.show {
	
}

#C1 {
	border-spacing: 10px;
	margin: 10px 0 30px 20px;
	float: left;
	width: 58%;
	height: 100%;
}

#C2 {
	border-spacing: 10px;
	margin: 10px 0 30px 20px;
	float: left;
	width: 58%;
	height: 100%;
}

p {
	margin-top: 10px;
	height: auto;
}

.row {
	
}

.cell {
	float: left;
	border: 2px solid #FF3300;
	width: 40%;
	height: 50px;
	padding-right: 10px;
	box-shadow: 3px 3px 3px #FF3300;
	line-height: 50px;
	text-align: center;
	font-weight: bold;
	font-size: 12pt;
	vertical-align: middle;
	margin: 5px;
}

.cell:hover {
	cursor: pointer;
}

#pallet_info {
	float: left;
	width: 35%;
	border: 1px solid #FF3300;
	box-shadow: 5px 5px 5px #FF3300;
	height: auto;
	margin-top: 20px;
	padding: 5px 10px;
}

#title {
	font-size: 10pt;
	font-weight: bold;
}
</style>
<div style="margin-top: 20px; margin-left: 20px;">
	<span>Төв агуулах:</span>
	<button data-pallet="C1" disabled>C1 Тавиур</button>
	<button data-pallet="C2">C2 Тавиур</button>
</div>
<input type="hidden" name="flag" id="flag" />
<!--C1 Pallet -->
<div id="C1">
	<div class="row">
		<div class="cell">A1-1:A4-4</div>
		<div class="cell">B1-1:B4-4</div>
	</div>
	<div class="row">
		<div class="cell">C1-1:C4-4</div>
		<div class="cell">D1-1:D4-4</div>
	</div>
	<div class="row">
		<div class="cell">E1-1:E4-4</div>
		<div class="cell">F1-1:F4-4</div>
	</div>
	<div class="row">
		<div class="cell">G1-1:G4-4</div>
		<div class="cell">H1-1:H4-4</div>
	</div>
	<div class="row">
		<div class="cell">I1-1:I4-4</div>
		<div class="cell">J1-1:J4-4</div>
	</div>
	<div class="row">
		<div class="cell">K1-1:K4-4</div>
		<div class="cell">L1-1:L4-4</div>
	</div>
	<div class="row">
		<div class="cell">M1-1:M4-4</div>
		<div class="cell">N1-1:N4-4</div>
	</div>
	<div class="row">
		<div class="cell">O1-1:O4-4</div>
		<div class="cell">P1-1:P4-4</div>
	</div>
	<div class="row">
		<div class="cell">Q1-1:Q4-4</div>
		<div class="cell">R1-1:R4-4</div>
	</div>
</div>
<div id="C2" class="hide">
	<div class="row">
		<div class="cell">A1-1:A4-4</div>
		<div class="cell">B1-1:B4-4</div>
	</div>
	<div class="row">
		<div class="cell">C1-1:C4-4</div>
		<div class="cell">D1-1:D4-4</div>
	</div>
	<div class="row">
		<div class="cell">E1-1:E4-4</div>
		<div class="cell">F1-1:F4-4</div>
	</div>
	<div class="row">
		<div class="cell">G1-1:G4-4</div>
		<div class="cell">H1-1:H4-4</div>
	</div>
	<div class="row">
		<div class="cell">I1-1:I4-4</div>
		<div class="cell">J1-1:J4-4</div>
	</div>
	<div class="row">
		<div class="cell">K1-1:K4-4</div>
		<div class="cell">L1-1:L4-4</div>
	</div>
	<div class="row">
		<div class="cell">M1-1:M4-4</div>
		<div class="cell">N1-1:N4-4</div>
	</div>

</div>
<div id="title"></div>
<div id="pallet_info"></div>
<script>
   (function(){      
      var value ,C2=$("#C2"), C1=$("#C1"), btn=$("button"), pre_pallet="x", p_section, p_info = $("#pallet_info");
      p_info.addClass('hide_info');
      // if first time    
      $("button").click(function(){
         var $this=$(this);
         p_section=$this.data('pallet');
         if(p_section ==='C2'){            
            C1.addClass('hide');
            C2.removeClass('hide');            
            $this.siblings('button')         
                 .removeAttr('disabled')
                 .end()
                 .attr('disabled', 'disabled');            
            //flag.val('C2');
         }else{ //C1             
             C2.addClass('hide');
             C1.removeClass('hide');
             $this.siblings('button')         
                .removeAttr('disabled')
                .end()
                .attr('disabled', 'disabled');
         }
         $('#title').text("");         
         p_info.slideUp(200);
      });
      
      // a tag clicked then we to do something      
      $('.cell').click(function (event){                  
         if($('.hide').attr('id')=='C2') phead='C1'; else phead ='C2';var _html ='';
         psub=$(this).text();          
         $('#title').text(phead+'[' + psub+']');
         pallet=phead+'-'+psub.substring(0,1);         
         if(p_info.text()) p_info.text("");
         $.post( '/ecns/wm_ajax/palletInfo', {pallet_name:pallet}, function(result){          
            if(result.length!=0){
               p_info.slideDown(200);          
               $.each(result, function(i, field){
                  //alert(_html.indexOf(field.pallet));
                  if(_html.indexOf(field.pallet)>0){
                     //_html=_html+"<strong>"+field.pallet+":</strong>тавиурт";
                     value =i+1;                  
                     _html=_html+"</br> "+value;
                     _html=_html+" "+field.spare+" ("+field.equipment+") -";
                     _html=_html+" "+field.qty;
                     _html=_html+" "+field.measure;
                   //  _html=_html+'</div>'
                  }else{                      
                     _html =_html+"<p id='"+field.pallet+"'>";
                     _html =_html+"<strong>"+field.pallet+":</strong></p>";
                     value =1;
                     _html =_html+"</br> "+value;
                     _html =_html+" "+field.spare+" ("+field.equipment+") -";
                     _html =_html+" "+field.qty;
                     _html =_html+" "+field.measure;
                     //_html =_html+ "</div>";
                  }
                  pre_pallet=field.pallet;
               });
               p_info.append(_html);
             }else
                p_info.slideUp(200);
          });
       });
        // Link to open the dialog
       $( "#dialog-link" ).click(function( event ) {
          $( "#dialog" ).dialog( "open" );
          event.preventDefault();
       });
   })();    
</script>
<div style="clear: both"></div>