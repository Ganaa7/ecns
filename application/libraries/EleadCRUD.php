<?php
/*
 * PHP elead CRUD
 *
 * Create lists and actions for electronic system.
 *
 * LICENSE
 *
 * elead CRUD-ийн эрх хуулиар эзэмшигчэд хадгалагдана.
 *
 * Copyright (C) 2011 - 2013 Gandavaa.D
 *
 * @package elead CRUD
 * @copyright Copyright (c) 2011 through 2013, Gandavaa Dugarsuren
 * @license
 * @version 1.0.0
 * @author Gandavaa Dugarsuren <ganaa7@gmail.com>
 */

/*
 * PSEUDO Code
 * get_segments from url
 * indicate functions from segment
 * set function
 * call fn
 * result fn
 *
 * 1. set_table =
 * 2 talbe join query
 * 3 get_result
 * 4 show result to grid
 * 5 prepare grid with column and with
 * 6 prepare js sent value to jqgrid script
 * 7 print output
 * ? how to sent jquery
 *
 */
// /********table related ***************/
// protected function setTable($table){
// $this->table = $table;
// }
//
// protected function getTable(){
// return $this->table;
// }
//
// protected function setFnCall(){
// if($this->getState()=='ajaxResult'){
// return $this->ajaxResult();
// }
// }
//
// protected function setModel(){
// $ci=&get_instance();
// $ci->load->model('eleadCrudModel');
// $this->eleadModel = new eleadCrudModel();
// }
//
// protected function ajaxResult(){
// $this->setModel();
// }
//
// protected function getResult($result){
// // foreach($result as $row){
// // foreach($fields as value
// // }
// }
//

// }
class eleadModelDriver {
	private $controller;
	private $method;
	private $state;
	private $table;
	public $eleadModel;
	protected $url;
	private function setStateFromUrl() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		$this->state = $CI->uri->segment ( 3 );
	}
	protected function setControllerName() {
		$this->controller = $this->getControllerName ();
	}
	protected function setMethodName() {
		$this->method = $this->getMethodName ();
	}
	protected function getControllerName() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		return $CI->router->class;
	}
	protected function getMethodName() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		return $CI->router->method;
	}
	protected function getState() {
		$this->setStateFromUrl ();
		return $this->state;
	}
}

/* this class can printing out all Layout design to view or servers */
class eleadLayout extends eleadModelDriver {
	protected $cssFiles = array ();
	protected $jsFiles = array ();
	protected $jsLibFiles = array ();
	protected $jsString = "";
	protected $gridObj = array ();
	protected $columns = array ();
	protected $names = array ();
	protected $ajax_url;
	protected $caption;
	private $library = "esystem";
	// link js
	// prepare js
	// create js file
	// js value from models
	// this can set css files into app
	function setCss($cssFile) {
		$this->cssFiles [sha1 ( $cssFile )] = base_url () . $cssFile;
	}
	function setjs($jsfile) {
		$this->jsFiles [sha1 ( $jsfile )] = base_url () . $jsfile;
	}
	function setjsLib($jsfile) {
		$this->jsLibfiles [sha1 ( $jsfile )] = base_url () . $jsfile;
	}
	function getjs() {
		return $this->jsFiles;
	}
	function getCss() {
		return $this->cssFiles;
	}
	function getjsLib() {
		return $this->jsLibfiles;
	}
	function loadJs() {
		$this->setCss ( 'style.css' );
		$this->setjsLib ( 'jquery.fancybox-1.3.4.js' );
	}
	function setColumns() {
		for($i = 0; $i < func_num_args (); $i ++) {
			$this->columns [$i] = func_get_arg ( $i );
		}
	}
	function setNames() {
		for($i = 0; $i < func_num_args (); $i ++) {
			$this->names [$i] = func_get_arg ( $i );
		}
	}
	function setCaption($caption) {
		$this->caption = $caption;
	}
	// grid js loading....
	function loadGridJs() {
		$this->setjsLib ( 'jquery.fancybox-1.3.4.js' );
		
		$grid_script = "<script type=\"text/javascript\">";
		$grid_script .= "jQuery(document).ready(function(){ jQuery('#grid').jqGrid({
              url:'/ecns/wm_ajax/income',
              datatype: 'xml',
              mtype: 'GET',";
		$col_script = "colNames:[";
		foreach ( $this->columns as $col ) {
			$col_script .= "'$col' ,";
		}
		$col_script = substr ( $col_script, 0, strlen ( $col_script ) - 1 );
		$col_script .= "], ";
		
		$name_script = "colModel: [ ";
		foreach ( $this->names as $name ) {
			$name_script .= " {name:'$name', index:'$name', width: 0}, ";
		}
		
		$name_script = substr ( $name_script, 0, strlen ( $name_script ) - 2 );
		$name_script .= "],";
		
		$grid_script .= $col_script;
		$grid_script .= $name_script;
		
		$grid_script .= "pager: jQuery('#pager'),
              rowNum:15, rowList:[10,20,30], 
              sortname: '$this->names[0]',
              sortorder: 'desc',
              viewrecords: true,
              caption: '$this->caption',
              autowidth:true,
              height: 400,
              width:'100%',
              subGrid: false        
           }).navGrid('#pager',{edit:false,add:false,del:false,search:true}); }); ";
		
		$grid_script .= "</script>";
		return $grid_script;
	}
	
	// function setGridObj(){
	// $argument = func_get_args();
	// $this->gridObj = $argument;
	// }
	//
	// function grid($values){
	// call_user_func_array('$this->setGridObj', $values);
	// }
	function getJStoscript($file) {
		$js_script = "";
		foreach ( $file as $key => $value ) {
			$js_script .= "<script src=\"$value\"></script>";
		}
		return $js_script;
	}
	function getlayout() {
		$this->loadJs ();
		$jsfiles = $this->getjs ();
		$jslibfiles = $this->getjsLib ();
		$cssfiles = $this->getCss ();
		$jsgridFile = $this->loadGridJs ();
		
		$js_script = "";
		$js_script .= $this->getJStoscript ( $jsfiles );
		$js_script .= $this->getJStoscript ( $jslibfiles );
		$js_script .= $jsgridFile;
		return $js_script;
		// return (object)array(
		// 'jsfiles' => $jsfiles,
		// 'jslibfiles' => $jslibfiles,
		// 'css_files' => $cssfiles,
		// 'grid' =>$jsgridFile
		// );
	}
}
class eleadCrudRouter extends eleadLayout {
	public $routes = array (
			0 => 'unknown',
			1 => 'grid',
			2 => 'ajaxjson' 
	);
	function getRoute() {
		$routeString = $this->getRouteFromUrl ();
		
		if ($routeString != 'unknown' && in_array ( $routeString, $this->routes )) {
			$route = array_search ( $routeString, $this->routes );
		} else {
			$route = 0;
		}
		return $route;
	}
	function getRouteFromUrl() {
		$ci = &get_instance ();
		$segements = $ci->uri->segments;
		$operation = 'no';
		
		foreach ( $segements as $num => $value ) {
			if ($value != 'unknown' && in_array ( $value, $this->routes )) {
				$operation = $value;
			}
		}
		return $operation;
	}
}
class eleadCRUD extends eleadCrudRouter {
	protected $config;
	private $default_config_path = "assets/elead_crud/config";
	private $default_asset_path = "assets/elead_crud";
	function initialize() {
		$ci = &get_instance ();
		$this->config = ( object ) array ();
	}
	function render() {
		$route = $this->getRoute ();
		
		switch ($route) {
			case 1 :
				echo "<table>";
				echo "<tr><th>#</th><th>Нэрс</th><th>Овог</th></tr>";
				echo "</table>";
				break;
			case 2 :
				echo "json here here here";
				break;
		}
		
		return $this->getlayout ();
	}
}

?>