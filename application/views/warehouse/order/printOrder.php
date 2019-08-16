<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
        
<?
/*
 * 6/17/2013
 * I can do it through C
 * Захиалгийн хуудас
 */
?>
<style type="text/css">
#orderpage label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}

.header {
	font-size: 0.6em;
	text-align: left;
}

.headright {
	font-size: 0.6em;
	margin: 300;
	font-style: italic;
}

td {
	border-bottom: none;
	border-top: none;
}
</style>
<? if($this->session->userdata('message')) {  ?>
<div id="message" align="center">
		<p>
  <?
	echo $this->session->userdata ( 'message' );
	$this->session->unset_userdata ( 'message' );
	?>
   </p>
	</div>
<? } ?>
<span class="header">ИНД-171.79REV.0001</span>
	<span class="headright">БАЙГУУЛАМЖИЙН СЭЛБЭГИЙН ХАНГАЛТ, ТҮҮНИЙ НӨӨЦИЙГ
		БҮРДҮҮЛЭХ ЖУРАМ</span>
	<div style="float: right; margin-top: 15px">Хавсралт 3.2</div>
	<div style="margin: 20px 0">БАТЛАВ: ТХҮА-НЫ ДАРГА
		..................</div>

	<div align="center">

		<form method="post" action="" name="orderpage" id="orderpage">
			<div style='margin-bottom: 0px; margin-top: 15px;'>
				<span align="center"><h3>ЗАХИАЛГА ӨГӨХ ХУУДАС № <? echo $order_no ?></h3></span>
				<div align="right" style="margin-right: 50px;"></div>
			</div>
			<div style="float: left; margin-top: 15px">....................../Хэсгийн нэр/</div>

			<table id="orderPage" border="1" width="97%" height="100"
				cellpadding="5" cellspacing="0">
				<tr valign="middle" id="orderlist">
					<th width="16" height="29">№</th>
					<th width="180" align="center">ШААРДЛАГАТАЙ ТОНОГ ТӨХӨӨРӨМЖ,
						СЭЛБЭГ, ХЭРЭГСЭЛ, НЭР МАРК</th>
					<th width="10">ХЭМЖИХ</br> НЭГЖ
					</th>
					<th width="10">ТОО</br>ШИРХЭГ
					</th>
					<th width="250">ШААРДАХ БОЛСОН ҮНДЭСЛЭЛ:<br />/ХААНА ЯМАР ЗОРИЛГООР
						ХЭРЭГЛЭХИЙГ ТОДОРХОЙ БИЧНЭ./
					</th>
				</tr>
				<tr align="center">
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
				</tr>
				<!-- here is query results -->
				<tr>
    <?
				$i = 1;
				foreach ( $dtlResult as $row ) {
					echo "<tr>";
					echo "<td width='16' >" . $i ++ . "</td>";
					echo "<td width='180'><strong>" . $row->spare . "</strong></td>";
					echo "<td width='10' align='center'>" . $row->measure . "</td>";
					echo "<td width='10' align='center'>" . $row->qty . "</td>";
					echo "<td width='250'>" . $row->reason . "</td>";
					echo "</tr>";
				}
				?>
    </tr>
			</table>
			<div style='margin: 5px 15px 0; float: left;'>
				<p>
					<label>Захиалсан: Хэсгийн дарга.................................:</label>
					<span class="label">/<? echo $section_chief;?>/</span> <span
						style='margin: 100px;'><? echo $oYear; ?> оны <? echo $oMonth; ?> сарын <? echo $oDay; ?> өдөр</span>
				</p>
				<p>
					<label>Зөвшөөрсөн: Ерөнхий инженер..............................:</label>
					<span class="label">/<? echo ($chiefeng) ? $chiefeng: '..................';?>/</span>
					<span style='margin: 100px;'><? echo ($pYear)? $pYear: '....'; ?> оны <? echo ($pMonth)? $pMonth: '....'; ?> сарын <? echo ($pDay)? $pDay : '....'?> өдөр</span>
				</p>
				<p>
					<label>Захиалга хүлээн авсан: ХГХ-ын дарга......................:</label>
					<span class="label">/<? echo ($chief) ? $chief: '..................';?>/</span>
					<span style='margin: 100px;'><? echo ($rYear)? $rYear: '....'; ?> оны <? echo ($rMonth)? $rMonth: '....'; ?> сарын <? echo ($rDay)? $rDay : '....'?> өдөр</span>
				</p>

			</div>
		</form>

	</div>
</body>
</html>

