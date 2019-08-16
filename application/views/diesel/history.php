<div id="main_wrap" style="margin: 20px auto; width: 1260px">
<style>
	table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<form action="/ecns/diesel/history" method="post">	
<Label><h4>Дизель генератор банк:</h4></Label>
	<?php 

	echo form_dropdown('bank_id', $banks, $bank_id, "onchange='this.form.submit()' style='max-width:50em;'");?>

	<?php if(isset($result)){
		echo "<table style='width:100%; margin-top:15px;'>";
		echo "<tr>";
    		echo "<th>Огноо</th>";
    		echo "<th>Банкны үлдэгдэл хэмжээ</th>";
    		echo "<th>Үлдэгдэл шалгаж оруулсан ИТА</th>";
    
  			echo "</tr>";
			foreach ($result as $row) {
				echo "<tr>";
				echo "<td>".$row->datetime."</td>";
				echo "<td>".$row->fuel."</td>";
				echo "<td>".$row->checkedby."</td>";
				echo "</tr>";				
			}

		echo "</table>";

		}?>
</div>
</form>
