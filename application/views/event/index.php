<link rel="stylesheet"
  href="<? echo base_url();?>assets/chosen/chosen.css">

<script src="<?=base_url();?>assets/chosen/chosen.jquery.js" type="text/javascript"></script>


<style>
body {
	font-size: 14px;
	font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
}

#calendar {
	width: 90%;
	height: 80%;
	margin: 20px auto;
}
</style>
<div class="container">
<script src="<? echo base_url();?>assets/apps/maintenance/new_event.js"></script>
<div class="main">
<div id='calendar'>

</div>

</div>
<input type="hidden" id="role" value="<? echo $group; ?>" />

<?php $intterupt = array(
				2=>'Нэг сонголтыг сонго',
				1=>'Тасалдана',
				0=>'Тасалдахгүй'
			);

?>

<form id="event_form" action="" method="Post" title="Шинэ бүртгэл">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<input type='hidden' id='createdby_id' name="createdby_id"
			value=<? echo $this->session->userdata('employee_id'); ?> />
		<p class="feedback"></p>
		<table>
			<tr>
				<td>Дугаар:</td>
				<td><span id="eventId"></span></td>
			</tr>
			<tr>
				<td>Байршил</td>
				<td><? echo form_dropdown('location_id',$location, null, 'id=location_id'); ?></td>
			</tr>
			<tr>
				<td>Тоног төхөөрөмж</td>
				<td><? echo form_dropdown('equipment_id',$equipment, null, 'id=equipment_id'); ?></td>
			</tr>
			<tr>
				<td>Төрөл</td>
				<td><? echo form_dropdown('eventtype_id',$eventtype, null, 'id=eventtype_id'); ?></td>
			</tr>	
			<tr>
				<td>Ажлын тодорхойлолт:</td>
				<td><textarea name='event' id='event' col="80" row="10" style="width: 270px; height: 70px;"></textarea></td>
			</tr>
			<tr>
				<td>Үйлчилгээ тасалдах эсэх?</td>
				<td><? echo form_dropdown('is_interrupt',$intterupt, null, 'id=is_interrupt'); ?></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" name="start" id="startdate" size=16 /></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" name="end" id="enddate" size=16 /></td>
			</tr>
			<tr>
				<td>Нээсэн ИТА:</td>
				<td><span id="createdbyId"></span></td>
			</tr>
			<tr id="trdone">
				<td>Гүйцэтгэл:</td>
				<td><textarea name='done' id='done' col="80" row="10"  style="width: 270px; height: 70px;"></textarea></td>
			</tr>
			<tr id="rowDoneby">
				<td colspan="2">
					<span>Гүйцэтгэсэн ИТА:</span>
					<!-- <span id="doneby"></span> -->
				<?=form_dropdown('doneby_id[]', $employee, null, 'data-placeholder="ИТА-с сонго, олонг сонгох боломжтой" class="multiselect" multiple="multiple" id="doneby_id"');  ?>

				</td>
			</tr>
		</table>
	</div>
</form>

</body>

<script type="text/javascript">

	$(document).ready(function(){

	   $('.available').css('width', '160px');
   
   	   $('.selected').css('width', '160px');

   	});
	

</script>

</html>
