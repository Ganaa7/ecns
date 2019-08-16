<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class h_invoice_dtl extends MY_Model {    
    public $_table = 'h_invoice_dtl';
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
   
   public $validate = array(        
        array( 'field' => 'spare_id',
               'label' => 'Сэлбэг',
               'rules' => 'required|numeric'),  
        array( 'field' => 'site_id[]',
               'label' => 'Байршил',
               'rules' => 'required|numeric'),
        array( 'field' => 'serial[]',
               'label' => 'Сериал',
               'rules' => 'required' ),
        array( 'field' => 'barcode[]',
               'label' => 'Баркод',
               'rules' => 'required' )        
    );  
   
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

     function insert_batch($data){
       $this->db->insert_batch($this->_table, $data);
    }

 
}