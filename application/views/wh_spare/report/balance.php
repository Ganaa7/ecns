<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

$this->load->view ( 'warehouse/plugin/refilter' );
?>
<div id="report" style="margin-top: 20px;">
	<h4 align="right">
		ХНБАҮАлба
		</h3>
		<h3 align="center" style="font-style: italic;">БАЙГУУЛАМЖИЙН СЭЛБЭГ
			ХАНГАМЖИЙН ҮЛДЭГДЛИЙН ТАЙЛАН</h3>
		<div style="margin: 5px auto;">
			<strong>Тайлант огноо: <?php echo $date; ?></strong>
		</div>
		<strong><span>Тайлан гаргасан: <? echo $this->session->userdata('fullname');?></span></strong>
		<div style="margin: 5px auto;">
			<strong><span>Хэсэг: <? echo $section; ?></span></strong>
		</div>
		<div id="print_btn" align="right" style="padding-right: 100px;">
			<input id="printButton" type="button" value="Хуудсыг хэвлэ"
				class="button" onclick="window.print();return false; showXLS();"> <input
				id="printbtn2" type="button" value="EXCEL файл" class="button"
				onclick="document.location='<? echo $file_link; ?>'">
		</div>
		<div id="equiphead">
			<div align="left" style="margin: 5px auto;"></div>
		</div>
<?php echo $last_query; ?>
<table border="0" cellpadding="3" cellspacing="0" align="center"
			class="report">    
    <?php
				echo "<thead>
        <th>Дугаар №</th>        
        <th>Төхөөрөмж</th>        
        <th>Сэлбэгийн нэр</th>        
        <th>Парт дугаар</th>                
        <th>Хэмжих нэгж</th>               
        <th>Эхний үлдэгдэл</th>
        <th>Орлого</th>
        <th>Зарлага</th>
        <th>Үлдэгдэл</th>
        </thead>";
				
				echo "<tbody>";
				$cnt = 1;
				foreach ( $r_result as $row ) {
					echo "<tr>";
					echo "<td id='cell'><div style='width:45px; font-size:7pt;'>";
					echo $cnt ++;
					echo "</div></td>";
					echo "<td id='cell'><div style='width:45px; font-size:7pt;'>";
					echo $row->equipment;
					echo "</div></td>";
					echo "<td id='cell'><div style='width:45px; font-size:7pt;'>";
					echo $row->spare;
					echo "</div></td>";
					echo "<td id='cell'><div style='width:65px;'>";
					echo $row->part_number;
					echo "</div></td>";
					echo "<td id='cell'><div style='width:56px;'>";
					echo $row->short_code;
					echo "</td>";
					echo "<td id='cell'><div>";
					echo $row->bqty;
					echo "</td>";
					echo "<td id='cell'><div>";
					echo $row->inqty;
					echo "</td>";
					echo "<td id='cell'><div style='width:100px;'>";
					echo $row->exqty;
					echo "</td>";
					echo "<td id='cell'><div style='width:90px;'>";
					echo $row->eqty;
					echo "</td>";
					echo "</tr>";
				}     
 ?>
    </tbody>
		</table>

</div>