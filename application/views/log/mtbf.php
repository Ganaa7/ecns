<div id="main_wrap" style="margin: 30px 50px 20px">
	<script>
           $(function() {
              $( "#start" ).datepicker({dateFormat: 'yy-mm-dd'});
              $( "#end" ).datepicker({dateFormat: 'yy-mm-dd'});
           });
     </script>
	<?=form_open('log/mtbf')?>
     </p>
	<?=form_dropdown('location_id',$location, 'class="text ui-widget-content ui-corner-all"')?>
	<?=form_dropdown('equipment_id',$equipment, 'class="text ui-widget-content ui-corner-all"')?>	
     Эхлэх огноо:<input type="text" name="start" id="start"
		style="width: 100px;" value=<?php if(isset($start)) echo $start?>>
	Дуусах огоо: <input type="text" name="end" id="end"
		style="width: 100px;" value=<?php if(isset($end)) echo $end;?>> <input
		type="submit" nam="submit" value="Тооцох">
	</p>
	<?=form_close()?> 
     <?php
					
					if ($start && $end) {
						echo "<pre><code>" . $last_query . "</code></pre>";
						echo "<strong><h4>Тооцох хугацаа: " . $start . "-с " . $end . " хооронд</h4></strong>";
						// echo "strtotime:".strtotime($start);
						// "strtoend:".strtotime($end);
						$diff = (strtotime ( $end ) - strtotime ( $start )) / 3600;
						echo "diff:" . $diff . "hours";
						$cnt = 1;
						echo "<table class='tbcss'><th>#</th><th>Тоног төхөөрөмж</th><th>Байршил</th><th>Ажиллаагүй цаг</th><th>Actual o/t</th><th>MTBF</th><th>Aviability</th>";
						foreach ( $result as $row ) {
							echo "<tr>";
							echo "<td>";
							echo $cnt ++;
							echo "</td>";
							echo "<td>";
							echo $row->location;
							echo "</td>";
							echo "<td>";
							echo $row->equipment;
							echo "</td>";
							echo "<td>";
							echo floor ( $row->sec / 3600 );
							echo "</td>";
							echo "<td>";
							echo $diff - floor ( $row->sec / 3600 );
							echo "</td>";
							echo "<td>";
							if ($row->count != 0) {
								echo floor ( ($diff - floor ( $row->sec / 3600 )) / $row->count );
							} else
								echo 0;
							echo "</td>";
							echo "<td>";
							echo round ( (($diff - floor ( $row->sec / 3600 )) / $diff) * 100, 2 ) . "%";
							echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					
					?>
          </div>
</div>