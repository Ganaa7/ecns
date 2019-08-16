<?php 

function generate_grid(){
	// This plugin used for jqgrid deployment
	var $jqScript = " jQuery('#grid').jqGrid({ ";
	var $jqEnd = "});";
	var $ajax_url = 'here';
	var $data_type = "json";
	var $height = "xml";
	var $width = "xml";
	var $colNames = "";
	var $colModel = "";
	var $jsonReader =" jsonReader : {
	                    page: 'page',
	                    total: 'total',
	                    records: 'records',
	                    root:'rows',
	                    repeatitems: false,
	                    id: 'id'}";

	var $varRoot = "rows";
	var $rowNum = 20;
	var $rowList = "rowList:[10,20,30]";
	var $pager= '#pager';
	var $sortname = $sort_name;
	var $viewrecords =TRUE;
	var $sortorder =$sort_order;
	var $varCaption = ".: Гэрчилгээ :.";

	$jqGrid_script = $jqScript.
			"url: '".$ajax_url."',".
			"datatype:'".$data_type."',".
			"mtype:'GET',".
			"height: '".$height."',".
			"width: '".$width."',".
			"colNames: '".$colNames."',".
			"colModel:".$colModel.",";

			if($data_type="json")
				$jqGrid_script .= $jsonReader.",";
			$jqGrid_script .= "rowNum:".$rowNum.",".
			$rowList.",".
			"pager:'".$pager."',".
			"sortname:'".$sort_name."',".
			"viewrecords: true,".
			"sortorder:'".$sort_order."',".
			"caption:'".$varCaption."'";	      
	echo $jqGrid_script;
}
