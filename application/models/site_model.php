<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class site_model extends MY_Model {    
    public $_table = 'site';
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
   
    function ext_dropdown($key_col, $column, $filter=null, $where =null) { // , $join_table =null, $join_id = null      
      $this->db->select ( '*' );      
      if($where)
         $this->db->where_in($filter, $where, true );      
      
      $Query = $this->db->get ( $this->_table );
      
      if ($Query->num_rows () > 0) {
        foreach ( $Query->result_array () as $row ) {
          $data [$row [$this->primary_key]] = $row [$column];
        }
      }
      $Query->free_result ();
     // echo $this->db->last_query();
      return $data;
    }
 
}