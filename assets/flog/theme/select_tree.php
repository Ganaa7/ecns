<script type="text/javascript">
//filter plugin functions
$(function(){
   //log filter 
   var c_form = $("#create_form");  
   //filter section change    
   filter_event(c_form);  

   //call here  select option
   $('#vw_loc_equip_id').change(function(){
      console.log($( "#vw_loc_equip_id option:selected" ).val());
      //call here ftree here :D
      var data = {};
      data['equipment_id'] = $( "#vw_loc_equip_id option:selected" ).val();            

      $.ajax({
	         type:     'POST',
	         url:    '/ecns/flog/index/ftree/',
	         data:   data,	         
	         success:  function(data){ 
	            //get data and append to jquery 
	  	        $('.overflow').append(data);

	         }
	    });
   });
});

function filter_post(form_name, target_id, target_field, target){
    $.post( '/ecns/flog/index/input_jx', {id:target_id, field:target_field, table:target}, function(newOption) {   
           var select = $('#'+target+'_id');
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
 
function filter_event(_form){
    //when cliked section call sector and equipment    
    $("#location_id", _form).change(function() {
       //section _id
       var _id = $(this).val();       
       //filter_post 
       filter_post(_form, _id, 'equipment_id', 'vw_loc_equip');
       //location change        
    });  
}
	
</script>


<script src="/ecns/assets/ftree/js/jquery-1.11.1.min.js"
	type="text/javascript"></script>
<script src="/ecns/assets/ftree/js/jquery-migrate-1.2.1.min.js"
	type="text/javascript"></script>
<script src="/ecns/assets/ftree/js/jquery-ui.js" type="text/javascript"></script>
<script src="/ecns/assets/ftree/js/jquery.tree.js"
	type="text/javascript"></script>

<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/ftree/style.css">
<script>
            $(document).ready(function() {
                $('.tree').tree_structure({
                    'add_option': true,
                    'edit_option': true,
                    'delete_option': true,
                    'confirm_before_delete': true,
                    'animate_option': true,
                    'fullwidth_option': false,
                    'align_option': 'center',
                    'draggable_option': true
                });  
            });

            </script>
<div class="gray-bg" style="padding-left: 40px; padding-top: 20px;">
	<h3>Гэмтэл нээх</h3>
	<style>
table.ftree td.col-r {
	text-align: right;
	font-weight: bold;
}
</style>
	<form id="create_form" name="create_form">
		<table width="100%" class="ftree" cellspacing="15" cellpadding="5">
			<tr>
				<td class="col-r"><span>Нээсэн огноо:</span></td>
				<td><input type="text" name="created_dt" id="created_dt"
					placeholder="Нээсэн огноо" /></td>
			</tr>
			<tr>
				<td class="col-r"><span>Гэмтлийн төрөл:</span></td>
				<td><?php echo form_dropdown('type_id', $log_type);?></td>
			</tr>
			<tr>
				<td class="col-r"><span>Байршил:</span></td>
				<td><?php echo form_dropdown('location_id', $location, null, "id='location_id'");?></td>
			</tr>
			<tr>
				<td class="col-r"><span id='equipment_label'>Тоног төхөөрөмж:</span></td>
				<td>
			<?php echo form_dropdown('equipment_id', $equipment, null, "id='vw_loc_equip_id'");?>
				
			</td>
			</tr>
			<tr>
				<td class="col-r"><span id='ftree_label'>Гэмтлийн мод:</span></td>
				<td>
					<div class="overflow"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: right;"><span
					style="margin-right: 30%;"> <a href="#" id='btn_save'
						class="button">Хадгалах</a> <a href="/ecns/flog" class="button">Болих</a>
				</span></td>
			</tr>
		</table>
	</form>
</div>