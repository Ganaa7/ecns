<?php
/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (! function_exists ( 'dump' )) {
	function dump($var, $label = 'Dump', $echo = TRUE) {
		// Store dump in variable
		ob_start ();
		var_dump ( $var );
		$output = ob_get_clean ();
		
		// Add formatting
		$output = preg_replace ( "/\]\=\>\n(\s+)/m", "] => ", $output );
		$output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
		
		// Output
		if ($echo == TRUE) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if (! function_exists ( 'dump_exit' )) {
	function dump_exit($var, $label = 'Dump', $echo = TRUE) {
		dump ( $var, $label, $echo );
		exit ();
	}
}

/**
 * Filter input based on a whitelist.
 * This filter strips out all characters that
 * are NOT:
 * - letters
 * - numbers
 * - Textile Markup special characters.
 *
 * Textile markup special characters are:
 * _-.*#;:|!"+%{}@
 *
 * This filter will also pass cyrillic characters, and characters like é and ë.
 *
 * Typical usage:
 * $string = '_ - . * # ; : | ! " + % { } @ abcdefgABCDEFG12345 éüртхцчшщъыэюьЁуфҐ ' . "\nAnd another line!";
 * echo textile_sanitize($string);
 *
 * @param string $string        	
 * @return string The sanitized string
 * @author Joost van Veen
 */
function textile_sanitize($string) {
	$whitelist = '/[^a-zA-Z0-9а-яА-ЯéüртхцчшщъыэюьЁуфҐ \.\*\+\\n|#;:!"%@{} _-]/';
	return preg_replace ( $whitelist, '', $string );
}
function escape($string) {
	return textile_sanitize ( $string );
}


function gen_grid(){
	$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
		   $args = $args[0];
		}
		print_r($args);		
	// send trough object to grid
	// This plugin used for jqgrid deployment
	$jqScript = " jQuery('#grid').jqGrid({ ";
	$jqEnd = "});";
	$ajax_url = 'here';
	$data_type = "json";
	$height = "xml";
	$width = "xml";
	$colNames = "";
	$colModel = "";
	$jsonReader =" jsonReader : {
	                    page: 'page',
	                    total: 'total',
	                    records: 'records',
	                    root:'rows',
	                    repeatitems: false,
	                    id: 'id'}";

	$varRoot = "rows";
	$rowNum = 20;
	$rowList = "rowList:[10,20,30]";
	$pager= '#pager';
	$sortname = $sort_name;
	$viewrecords =TRUE;
	$sortorder =$sort_order;
	$varCaption = ".: Гэрчилгээ :.";
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
	
	return $jqGrid_script;
}

