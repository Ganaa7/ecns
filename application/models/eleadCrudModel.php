<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of elead_crud_model
 *
 * @author developer
 */
class eleadCrudModel {
	// put your code here
	protected $table;
	
	// function __construct(){
	// parent::__construct();
	// }
	function getResult() {
		return $this->db->get ( $this->table )->result ();
	}
	function getFields() {
		return $this->db->list_fields ( $this->table );
	}
	function tableExists($table) {
		return $this->db->table_exists ( $table_name );
	}
	function setTable($table) {
		if (! ($this->db->table_exists ( $table )))
			return false;
		$this->table = $table;
		return true;
	}
}
