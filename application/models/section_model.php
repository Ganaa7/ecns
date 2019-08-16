<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Section_model extends MY_Model {    

    public $_table = 'section';
    
    public $primary_key = 'section_id';

    // public $before_dropdown = array('before_select');
    
    public $after_dropdown = array('before_select');

    function before_select($data){

      $data['0'] = 'Нэг утгийг сонго';

      ksort($data);
      
      return $data;
    }

   
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

    function dropdown_where_in($wherein =null) { // , $join_table =null, $join_id = null      
      
      $this->db->select ( 'section_id, name' );      
      
      if($wherein)
         $this->db->where_in('section_id', $wherein, true );      
      
      $Query = $this->db->get ( $this->_table );
      
      if ($Query->num_rows () > 0) {
        foreach ( $Query->result_array () as $row ) {
          $data [$row [$this->primary_key]] = $row ['name'];
        }
      }
      $Query->free_result ();

      return $data;
    }

    

    
 
}