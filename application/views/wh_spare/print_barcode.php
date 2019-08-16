<style>
th {
	background-color: #dddddd;
	color: black;
}

td {
	text-align: center;
}
</style>

<div>

	<div class="success"
		style="width: 100%; text-align: center; color: red; margin: 20px;">
  	<?php
			
		echo $msg;
			echo "<br>";
			echo "<br>";
			echo "<strong>" . $link . "</strong>";
			?>
  </div>

	<table border="1" cellpadding="3" cellspacing="0"
		style="margin: auto; width: 90%;">
		<tr>
			<th>Barcode</th>
			<th>Тавиур</th>
			<th>Хэсэг</th>
			<th>Тасаг</th>
			<th>Төхөөрөмж</th>
			<th>Сэлбэг</th>
		</tr>  	
  
  <?php
foreach ( $query->result () as $row ) {

			echo "<tr>";
			echo "<td>" . $row->barcode . "</td>";
			echo "<td>" . $row->pallet . "</td>";
			echo "<td>" . $row->section . "</td>";
			echo "<td>" . $row->sector . "</td>";
			echo "<td>" . $row->equipment . "</td>";
			echo "<td>" . $row->spare . "</td>";
			echo "</tr>";
		}
		?>
  </table>



</div>