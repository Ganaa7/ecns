<?
/*
 * created by Ganaa
 * created dt Sep 24, 2013 @9:50am
 * call event page here
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
<? echo $group?>
<script
	src="<? echo base_url();?>assets/event/js/<?=strtolower($group)?>.js"></script>
<div id='calendar'></div>
<input type="hidden" id="role" value="<? echo $group; ?>" />

<a href="#" id="date1">GOTODATe</a>
<? if($group=='CHIEF'){ ?>
<div id="dialog" title="Үйл ажиллагаа">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<p class="feedback">fsdafsdfsdfdssdfsdafsdaf</p>
		<table>
			<tr>
				<td>Тоног төхөөрөмж:</td>
				<td><span id='cequipment_id'></span></td>
			</tr>
			<tr>
				<td>Байршил:</td>
				<td><span id='location_id'></span></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><span id='event'></span></td>
			</tr>
			<tr id='tdone'>
				<td>Гүйцэтгэл:</td>
				<td><span id='done'></span></td>
			</tr>
			<tr>
				<td>Эхэлсэн огноо:</td>
				<td><span type="text" id="startdate" /></span></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><span type="text" id="enddate" /></span></td>
			</tr>
		</table>
		<input type='hidden' name='eventid' id='eventid' />
	</div>
</div>
<!--Засах-->
<!-- ашиглах эрхүүд АДМИН, ENG, TECH, SUPENG-->
<div id="edialog" title="Үйл ажиллагаа засах">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Үйл ажиллагаа:</td>
				<td><textarea name='event' id='eevent' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="estartdate" size=16></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="eenddate" size=16></td>
			</tr>
		</table>
		<input type='hidden' name='eventid' id='eeventid' />
	</div>
</div>
<? } elseif($group=='ENG') {?>
<!-- Шинэ -->
<!-- ашиглах эрхүүд АДМИН, ENG, TECH, SUPENG-->
<div id="cDialog" title="Шинэ бүртгэл үүсгэх">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<input type='hidden' id='createdby_id' value=<? echo $createdby_id; ?> />
		<p class="feedback">fsdafsdfsdfdssdfsdafsdaf</p>
		<table>
			<tr>
				<td>Тоног төхөөрөмж</td>
				<td><? echo form_dropdown('equipment_id',$equipment, null, 'id=equipment_id'); ?></td>
			</tr>
			<tr>
				<td>Байршил</td>
				<td><? echo form_dropdown('location_id',$location, null, 'id=location_id'); ?></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><textarea name='event' id='cEvent' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="cStartdate" size=16 /></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="cEnddate" size=16 /></td>
			</tr>
		</table>
	</div>
</div>
<div id="dialog" title="Гүйцэтгэл">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<input type='hidden' id='createdby_id' value=<? echo $createdby_id; ?> />
		<table>
			<tr>
				<td>Тоног төхөөрөмж</td>
				<td><? echo form_dropdown('equipment_id',$equipment, null, 'id=fequipment_id'); ?></td>
			</tr>
			<tr>
				<td>Байршил</td>
				<td><? echo form_dropdown('location_id',$location, null, 'id=flocation_id'); ?></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><textarea name='event' id='event' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="startdate" size=16 /></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="enddate" size=16 /></td>
			</tr>
			<tr id="trdone">
				<td>Гүйцэтгэл:</td>
				<td><textarea name='done' id='cdone' col="40" row="10"></textarea></td>
			</tr>
		</table>
	</div>
</div>
<? }else{ ?>
<div id="dialog" title="Үйл ажиллагаа">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Тоног төхөөрөмж:</td>
				<td><span id='equipment'></span></td>
			</tr>
			<tr>
				<td>Байршил:</td>
				<td><span id='location'></span></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><span id='event'></span></td>
			</tr>
			<tr id='tdone'>
				<td>Гүйцэтгэл:</td>
				<td><span id='done'></span></td>
			</tr>
			<tr>
				<td>Эхэлсэн огноо:</td>
				<td><span type="text" id="start" /></span></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><span type="text" id="end" /></span></td>
			</tr>
			<tr>
				<td>Нээсэн:</td>
				<td><span type="text" id="createdby" /></span></td>
			</tr>
		</table>
	</div>
</div>

<? } ?>
