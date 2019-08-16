<?
/*
 * created by Ganaa
 * created dt Sep 24, 2013 @9:50am
 * updated Oct, 22, 2013
 */
?>
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
<script src="<? echo base_url();?>assets/js/event.js"></script>
<div id='calendar'></div>
<input type="hidden" id="role" value="<? echo $group; ?>" />

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
				<td><textarea name='event' id='event' col="40" row="10" style="width: 200px;"></textarea></td>
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
				<td><textarea name='done' id='done' col="40" row="10"></textarea></td>
			</tr>
			<tr id="rowDoneby">
				<td>Хаасан ИТА:</td>
				<td><span id="doneby"></span></td>
			</tr>
		</table>
	</div>
</form>
