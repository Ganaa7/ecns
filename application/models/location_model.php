<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Location_model extends MY_Model {

    public $_table = 'location';

    public $primary_key = 'location_id';

     protected $soft_delete = TRUE;

    public $after_dropdown = array('options');

    protected function options($data){

       $data['0'] = 'Нэг байршлыг сонго';

       ksort($data);

       return $data;

    }

    function __construct() {

        parent::__construct();

        $this->load->database ();

    }

    private $fields  = array('location_id','location', 'code', 'latitude', 'longitude');

    public $validate = array(

        array( 'field' => 'location',
               'label' => 'Байршил',
               'rules' => 'required'),

        array( 'field' => 'code',
               'label' => 'Код',
               'rules' => 'required|max_length[3]'),

        array( 'field' => 'latitude',
               'label' => 'Өргөрөг',
               'rules' => 'required|is_natural_no_zero'),

         array( 'field' => 'longitude',
               'label' => 'Уртараг',
               'rules' => 'required')

    );


    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {

        $sql = "";

        $this->db->select("*");

        $this->db->from($this->_table);

        if ($where)
           $this->db->where($where, NULL, FALSE);

        if ($sidx && $sord) {
            $this->db->order_by($sidx, $sord);
        }

        if ($limit)
            $this->db->limit($limit, $start);

        // echo $this->db->last_query();

        $result = $this->db->get()->result();

        $i = 0;

        if($result){

           foreach ($result as $rows) {
              foreach ($this->fields as $field) {
                 $crow[$field] = $rows->$field;
              }
              
              $my_arr[$i] = (object)$crow;
              $i++;
          }

        }else{
           $my_arr=array();
        }

        return $my_arr;      
    }

		function get_location_equipment($id, $section_id =false){
	
      if($section_id)
			   $sql = "SELECT A.*, B.equipment, C.location FROM loc_equip A inner join ".
             " equipment2 B on A.equipment_id = B.equipment_id".
             " inner join location C on A.location_id = C.location_id WHERE".
             " A.location_id  = $id and B.section_id in( $section_id, 10)";
      else 
        $sql = "SELECT A.*, B.equipment, C.location FROM loc_equip A inner join ".
						 " equipment2 B on A.equipment_id = B.equipment_id".
						 " inner join location C on A.location_id = C.location_id WHERE".
						 " A.location_id  = $id";

      $Query = $this->db->query ( $sql );

      // echo $this->db->last_query();

      if ($Query->num_rows () > 0) {
        foreach ( $Query->result_array () as $row ) {
          $data [$row['equipment_id']] = $row['equipment'];
        }
      }else
			    $data[0]=' Төхөөрөмж байхгүй';

      $Query->free_result ();

      return $data;

    }
    
    function get_action() {

       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='location' and controller='index'" )->result ();

       if($result){

         foreach ( $result as $row ) {

            $result_array [$row->functions] = $row->functions;

         }

         return $result_array;

       }else

          return array();
    }

}
