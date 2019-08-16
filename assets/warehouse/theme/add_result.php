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
		
foreach ( $query as $cols ) {
			echo "<tr>";
			echo "<td>" . $cols->barcode . "</td>";
			echo "<td>" . $cols->pallet . "</td>";
			echo "<td>" . $cols->section . "</td>";
			echo "<td>" . $cols->sector . "</td>";
			echo "<td>" . $cols->equipment . "</td>";
			echo "<td>" . $cols->spare . "</td>";
			echo "</tr>";
		}
		?>
  </table>



</div>