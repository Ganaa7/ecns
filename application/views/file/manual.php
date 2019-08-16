<?
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
  $(function() {
    $( "#accordion" ).accordion({      collapsible: true, heightStyle: "content"});
    $( "#sub1" ).accordion({      collapsible: true, heightStyle: "content"});
    $( "#sub2" ).accordion({      collapsible: true, heightStyle: "content"});
    $( "#sub3" ).accordion({      collapsible: true, heightStyle: "content"});
    $( "#sub4" ).accordion({      collapsible: true, heightStyle: "content"});
  });
  </script>
<style>
ul {
	line-height: 1.75;
}
</style>
<div style="margin: 10px 20px; font-size: 85%;">
	<div id="accordion">
   <?
			$i = 1;
			foreach ( $headqry as $crow ) {
				echo "<h3>$crow->category</h3>";
				echo "<div id='sub$i'>";
				$i ++;
				foreach ( $bodyqry as $brow ) {
					// if($crow->Id ==$brow->parent_id){
					echo "<h3>$brow->type</h3>";
					echo "<div>";
					echo "<ul>";
					foreach ( $fres as $row ) {
						if ($brow->id == $row->type_id && $row->category_id == $crow->id) {
							echo "<li>";
							echo "<a target='_blank' href='".base_url()."manual/download/$row->filename'>" . $row->title . "</a>";
							echo "<span style='margin-left:7px'>:<small><strong>Индекс:$row->docIndex </strong></small><small><strong>Огноо:$row->created</strong></small></span>";
							echo "</li>";
						}
					}
					echo "</ul>";
					echo "</div>";
					// }
				}
				echo "</div>";
			}
			?>
  
</div>
</div>