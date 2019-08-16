<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Loc_equip_model extends MY_Model {

    public $_table = 'loc_equip';

    public $primary_key = 'id';

		public $has_many  = 'equipment';

    function __construct() {
        parent::__construct();
        $this->load->database ();
    }

    public function dropdown_ext($where, $equipment=null)
    {
    	$data ['0'] = 'Сонгох...';

    	$this->db->select('equipment2.equipment_id, equipment', null);
        $this->db->from($this->_table);        
        $this->db->join('location', "location.location_id = $this->_table.location_id", 'inner');
        $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'inner');

        if($where)

           $this->db->where($where);

        $query = $this->db->get();

        // echo $this->db->last_query();

        if($equipment)

        	foreach ( $query->result () as $row ) {
			  
			  $data [$row->equipment_id] = $row->equipment;
			}	

        else

        	foreach ( $query->result () as $row ) {
			  
			  $data [$row->location_id] = $row->location;
			}	
		
	
		return $data;
    }
 	
 	// public function dropdown_equipment($where=null)
  //   {
  //   	$data ['0'] = 'Сонгох...';

  //   	$this->db->select('*');
  //       $this->db->from($this->_table);        
  //       $this->db->join('location', "location.location_id = $this->_table.location_id", 'inner');
  //       $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'inner');

  //       if($where)

  //          $this->db->where($where);

  //       $query = $this->db->get();

  //       // echo $this->db->last_query();
		
		// foreach ( $query->result () as $row ) {
		// 	$data [$row->location_id] = $row->location;
		// }
		// return $data;
  //   }



}
