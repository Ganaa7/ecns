<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Trip_dtl_model extends MY_Model {    
    public $_table = 'trip_dtl';
    public $primary_key = 'dtl_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
   

  function get_dtl($trip_id){                
        $this->db->select ( 'section_id, section' );        
        $this->db->where ( array('trip_id'=>$trip_id));
        $Query = $this->db->get('trip_dtl');

        if ($Query->num_rows () > 0) {
          foreach ( $Query->result_array () as $row ) {
             $data [$row ['section_id']] = $row ['section_id'];
          }
        }
        $Query->free_result ();
        if(isset($data)) return $data;
        else return array(0=>'');
    }
    
}