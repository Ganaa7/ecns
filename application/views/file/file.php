

<script>
  $(function() {
    $( "#tabs" ).tabs();
    $( "#accordion" ).accordion({      collapsible: true, heightStyle: "content"
    });
    $( "#accordion2" ).accordion({      collapsible: true, heightStyle: "content"
    });
     $( "#accordion3" ).accordion({      collapsible: true, heightStyle: "content"
    });

  });
  </script>
<style>
ul {
	line-height: 1.75;
}
</style>
<div style="margin: 20px 20px; font-size: 100%;">
	
	<?php $this->load->view ( 'plugin\search_plugin' ); ?>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Албаны үйл ажиллагааны заавар, журмууд</a></li>
			<li><a href="#tabs-2">Системийн албадуудтай харилцан ажиллах дүрэм,
					журам </a></li>
			<!-- <li><a href="#tabs-2">Байгууллагуудтай хийсэн гэрээ, журам</a></li> -->
			<li><a href="#tabs-3">Хавсралт файлууд</a></li>
		</ul>
		<div id="tabs-2">
			<div id="accordion3" style="margin: 15px 0px;">
       <?
							foreach ( $pres as $crow ) {
								echo "<h3>$crow->category</h3>";
								echo "<ul style='line-height:1.75'>";
								echo "<div>";
								foreach ( $fres as $row ) {
									if ($crow->Id == $row->categoryId) {
										echo "<li>";
										$ext = explode ( '.', $row->filename );
										$file_extension = strtolower ( end ( $ext ) );
										if ($file_extension == 'pdf')
											echo "<a target='_blank' href='".base_url()."pdf/web/viewer.html?file=../../download/doc_files/$row->filename'>" . $row->title . "</a>";
										else
											echo "<a target='_blank' href='".base_url()."document/download/$row->filename'>" . $row->title . "</a>";
										echo "<span style='margin-left:7px'>:<small><strong>Индекс:$row->docIndex </strong></small><small><strong>Огноо:$row->created</strong></small></span>";
										echo "</li>";
									}
								}
								echo "</div>";
								echo "</ul>";
							}
							?>      
       </div>
		</div>
		<div id="tabs-1">
			<div id="accordion" style="margin: 15px 0px;">
         <?
									foreach ( $cres as $crow ) {
										echo "<h3>$crow->category</h3>";
										echo "<div>";
										echo "<ul>";
										foreach ( $fres as $row ) {
											if ($crow->Id == $row->categoryId) {
												echo "<li>";
												$ext = explode ( '.', $row->filename );
												$file_extension = strtolower ( end ( $ext ) );
												if ($file_extension == 'pdf')
													echo "<a target='_blank' href='".base_url()."pdf/web/viewer.html?file=../../download/doc_files/$row->filename'>" . $row->title . "</a>";
												else
													echo "<a target='_blank' href='".base_url()."document/download/$row->filename'>" . $row->title . "</a>";
												echo "<span style='margin-left:7px'>:<small><strong>Индекс:$row->docIndex </strong></small><small><strong>Огноо:$row->created</strong></small></span>";
												echo "</li>";
											}
										}
										echo "</ul>";
										echo "</div>";
									}
									?>         
      </div>
		</div>
		<div id="tabs-3">
			<div id="accordion2" style="margin: 15px 0px;">
         <?
									foreach ( $mres as $crow ) {
										echo "<h3>$crow->category</h3>";
										echo "<div>";
										echo "<ul>";
										foreach ( $fres as $row ) {
											if ($crow->Id == $row->categoryId) {
												echo "<li>";
												$ext = explode ( '.', $row->filename );
												$file_extension = strtolower ( end ( $ext ) );
												if ($file_extension == 'pdf')
													echo "<a target='_blank' href='".base_url()."pdf/web/viewer.html?file=../../download/doc_files/$row->filename'>" . $row->title . "</a>";
												else
													echo "<a target='_blank' href='".base_url()."document/download/$row->filename'>" . $row->title . "</a>";
												echo "<span style='margin-left:7px'>:<small><strong>Индекс:$row->docIndex </strong></small><small><strong>Огноо:$row->created</strong></small></span>";
												echo "</li>";
											}
										}
										echo "</ul>";
										echo "</div>";
									}
									?>
      </div>
		</div>

	</div>

</div>

