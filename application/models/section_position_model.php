<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Section_position_model extends MY_Model {    
    
  public $_table = 'section_position';
    public $primary_key = 'id';

    public $belongs_to = array('position' => 
                                  array('model' => 'position_model', 'primary_key' => 'position_id' ) );

   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }

     function ext_dropdown($key_col, $column, $filter=null, $where =null) { // , $join_table =null, $join_id = null      
      
      $this->db->select ( 'position.position_id, position.name' );      
      
      if($where)
        
         $this->db->where($where);
         
         $this->db->from($this->_table);
      
         $this->db->join('position', $this->_table.'.position_id = position.position_id');
      
         $Query =  $this->db->get();
      
      if ($Query->num_rows () > 0) {
        foreach ( $Query->result_array () as $row ) {
          $data [$row [$key_col]] = $row [$column];
        }
      }
      $Query->free_result ();
     // echo $this->db->last_query();
      return $data;
    }
    
   
    
 
}