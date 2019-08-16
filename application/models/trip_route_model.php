<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Trip_route_model extends MY_Model {    
    public $_table = 'trip_route';
    public $primary_key = 'route_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
   

     function _dropdown($id){

        $this->db->select ( 'num, to_id, to_route' );        
        $this->db->where ( array('trip_id'=>$id));        
        $Query = $this->db->get($this->_table);

        if ($Query->num_rows () > 0) {
          foreach ( $Query->result_array () as $row ) {
             $data [$row ['num']] = $row ['to_id'];
          }          
          
        }
        // $row = $this->db->get_where($this->_table, array('trip_id' => $id, 'num'=>1))->row_array();
        // //array_push($data, $data[$first_key]);	
        // $new[$row['from_id']] = $row['from_route'];
        // array_push($data, $new);

        // print_r($data);

        $Query->free_result ();
        if(isset($data)) return $data;
        else return array(0=>'');
    }
 
    
}