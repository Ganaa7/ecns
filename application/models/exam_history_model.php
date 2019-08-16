<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Exam_history_model extends MY_Model {

    public $_table = 'exam_history';

    public $primary_key = 'id';

	 public $belongs_to = array( 

		'license_equipment' => array('model' => 'license_equipment', 'primary_key' => 'license_equipment_id' )

	);

    function __construct() {

        parent::__construct();

        $this->load->database ();

    }

     public $validate = array(

        array( 'field' => 'employee_id',
               'label' => 'Ажилтан',
               'rules' => 'required'),

        array( 'field' => 'license_equipment_id',

               'label' => 'Ажиллах тоног төхөөрөмж',

               'rules' => 'required|is_natural_no_zero'),


        array( 'field' => 'exam_date',

               'label' => 'Шалгасан огноо',

               'rules' => 'required|exact_length[10]'),

        array( 'field' => 'valid_date',

               'label' => 'Хүртэл огноо',

               'rules' => 'required|exact_length[10]')
        
    );


    public function get_valid_date($employee_id){

       $query = $this->db->query("SELECT * from $this->_table WHERE employee_id = $employee_id order by valid_date asc limit 1");

       $row = $query->row_array ();

       if ($row)
      
       return $row ['valid_date'];

    }



}
