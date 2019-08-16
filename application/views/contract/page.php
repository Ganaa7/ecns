<?php
$c = array ();

var_dump ( $out );

// print_r($json_array);

( object ) $c = ( object ) $out->data;

?>
<style>
.main {
	width: 100%;
	margin: 0 auto;
	text-align: center;
}

.anket td {
	text-align: left;
	text-indent: 1.5em;
}

.red {
	color: red;
}
</style>
<div class="main">
	<table class='anket' align="center" width="70%" border="0"
		cellpadding="5" cellspacing="5">
		<tr>
			<th colspan="2">
				<h3 class='red'>ТОНОГ ТӨХӨӨРӨМЖИЙН АШИГЛАЛТАД ТЭНЦЭХ</h3>
				<h2 class='red'>ГЭРЧИЛГЭЭ</h2>
			</th>
		</tr>
		<tr>
			<th colspan="2"><hr></th>
		</tr>
		<tr>
			<th colspan="2">№:<?=$c->cert_no;?></th>
		</tr>
		<tr>
			<td>1. Тоног төхөөрөмжийн маяг загвар:</td>
			<td><strong><?=$c->equipment?></strong></td>
		</tr>
		<tr>
			<td>2. Үйлдвэрийн болон сери дугаар:</td>
			<td><strong><?=$c->serial_no_year;?></strong></td>
		</tr>
		<tr>
			<td>3. Хүчин чадал, ажиллах давтамж:</td>
			<td>here</td>
		</tr>
		<tr>
			<td>4. Зориулалт:</td>
			<td>Here</td>
		</tr>
		<tr>
			<td colspan='2'>5. Тус Гэрчилгээ нь дээр дурьдсан тоног төхөөрөмжийг
				ИНД-171.53-ийн дагуу шаардлагад тэнцэж байгааг гэрчилнэ.</td>
		</tr>
		<tr>
			<td colspan='2'>				
				6. Энэхүү Гэрчилгээ нь <?=date('Y', strtotime($c->issueddate));?> оны <?=date('m', strtotime($c->issueddate));?> сарын <?=date('d', strtotime($c->issueddate));?> өдөр хүртэл хүчинтэй.				

			</td>

		</tr>
		<tr>
			<td>Олгосон он-сар-өдөр:</td>
			<td><?=$c->validdate;?></td>
		</tr>

	</table>
</div>
