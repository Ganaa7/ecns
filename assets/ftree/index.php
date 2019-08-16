<!DOCTYPE HTML>
<html>
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>jQuery Tree</title>
<link rel="stylesheet" type="text/css"
	href="http://fonts.googleapis.com/css?family=Cabin:400,700,600" />
<link href="style.css" rel="stylesheet" type="text/css">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.tree.js"></script>
<script>
            $(document).ready(function() {
                $('.tree').tree_structure({
                    'add_option': true,
                    'edit_option': true,
                    'delete_option': true,
                    'confirm_before_delete': true,
                    'animate_option': true,
                    'fullwidth_option': false,
                    'align_option': 'center',
                    'draggable_option': true
                });                
            });
        </script>
<style>
ul.tree li .centeral {
	position: relative;
	display: block;
	z-index: 999;
	background: pink;
	text-align: center;
	margin: auto;
	width: 40px;
	margin-top: 0px;
	border: 1px solid pink;
	border-radius: 25px;
}
</style>
<todo> 1.basic elemetiig olj class nemeh </todo>
</head>
<body>
        <?php
								include_once 'db.php';
								$store_all_id = array ();
								$id_result = mysqli_query ( $con, "SELECT * FROM tree" );
								while ( $all_id = mysqli_fetch_array ( $id_result ) ) {
									array_push ( $store_all_id, $all_id ['parent_id'] );
								}
								
								print_r ( $store_all_id );
								
								echo "<div class='overflow'><div>";
								in_parent ( 0, $store_all_id, $con );
								echo "</div></div>";
								function in_parent($in_parent, $store_all_id, $con) {
									if (in_array ( $in_parent, $store_all_id )) {
										$result = mysqli_query ( $con, "SELECT a.*, IFNULL(temp.Count, 0) as count FROM tree a  
                       LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count 
                       FROM tree GROUP BY parent_id) as temp ON a.id = temp.parent_id
                       WHERE a.parent_id = $in_parent" );
										
										echo $in_parent == 0 ? "<ul class='tree'>" : "<ul>";
										while ( $row = mysqli_fetch_array ( $result ) ) {
											echo "<li";
											if ($row ['hide'])
												echo " class='thide'";
											if ($row ['count']) {
												
												echo "><div id=" . $row ['id'] . "><span class='first_name'>" . $row ['first_name'] . "</span></div>";
												echo '<span class="centeral">' . $row ['gate'] . '</span>';
											} else
												echo "><div class='basic' id=" . $row ['id'] . "><span class='first_name'>" . $row ['first_name'] . "</span></div>";
											in_parent ( $row ['id'], $store_all_id, $con );
											echo "</li>";
										}
										echo "</ul>";
									}
								}
								
								mysqli_close ( $con );
								?>


        <br>
	<!--         <div id="diamond-narrow"></div>
        <div class="overflow">
            <div>
                <ul class="tree">
                <li>

                <span class="vertical" style="margin-left: 85.5px; left: 34.5px;"></span>
                <span class="horizontal" style="width: 0px; margin-left: 85.5px; left: 34.5px;">And</span>
                
                <div id="1" class="ui-droppable ui-draggable parent">
                    <span class="highlight" title="Click for Highlight | dblClick" style="display: none;"></span>
                    <span class="add_action" title="Click for Add" style="display: none;"></span>
                    <span class="delete_action" title="Click for Delete" style="display: none;"></span>
                    <span class="edit_action" title="Click for Edit" style="display: none;"></span>
                    <span class="first_name">AIRCON 2100 Ажиллгааг                    
                    </span>                

                </div>                
                <span class="centeral">c-And</span>
                        
                        <ul>
                            <li> 
                                <span class="vertical" style="height: 34px; margin-top: -0px; margin-left: 00px; left: 0px;"></span>
                                <span class="horizontal" style="width: 50px; margin-top: -34px; margin-left: 30px; left: 40px;">And</span>
                                <div id="143302" class="ui-droppable ui-draggable"><span class="highlight" title="Click for Highlight | dblClick" style="display: none;"></span><span class="add_action" title="Click for Add" style="display: none;"></span><span class="delete_action" title="Click for Delete" style="display: none;"></span><span class="edit_action" title="Click for Edit" style="display: none;"></span><span class="first_name">tree</span>    
                                </div></li>
                                <li><span class="vertical" style="height: 34px; margin-top: -34px; margin-left: 30px; left: 140px;"></span><span class="horizontal" style="width: 50px; margin-top: -34px; margin-left: -20px; left: 140px;">And</span>
                                <div id="143303" class="ui-droppable ui-draggable current"><span class="highlight" title="Click for Highlight | dblClick" style="display: none;"></span>
                                <span class="add_action" title="Click for Add" style="display: none;"></span>
                                <span class="delete_action" title="Click for Delete" style="display: none;"></span>
                                <span class="edit_action" title="Click for Edit" style="display: none;">
                                </span><span class="first_name">tree</span>
                                </div>
                         </li>
                        </ul>
                    </li>
                    </ul>
            </div>
        </div> -->
</body>
</html>