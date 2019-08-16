<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Distance_model extends MY_Model {    
    public $_table = 'distance';
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
	function get_distance($from, $to){
		$query = $this->db->query("SELECT distance from $this->_table where from_id  = $from and to_id=$to and distance >0
					UNION
				  SELECT distance from $this->_table where from_id  = $to and to_id=$from and distance >0 ");

		$row = $query->row_array ();
		if ($row)
			return $row ['distance'];
		else
			return 0;
	}
}