<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class remark_model extends MY_Model {

    public $ci;

    public $_table = 'remark';

    public $primary_key = 'id';
    
    public $belongs_to = array( 
    	
    // 'position' => array('model' => 'position_model', 'primary_key' => 'position_id' ), 
		'employee' => array('model' => 'employee_model', 'primary_key' => 'employee_id' ) 

    );
    
    public $before_create = array( 'timestamps' );
    
    protected function timestamps($data)
    {

        $data['created_dt'] =  date('Y-m-d H:i:s');

        $data['createdby_id'] =  $this->session->userdata('employee_id');

        return $data;
    }

    function __construct() {

        $thsi->ci = &get_instance ();

        parent::__construct();

        $this->load->database ();

    }

     public $validate = array(

        array( 'field' => 'employee_id',
               'label' => 'Ажилтан',
               'rules' => 'required'),

        array( 'field' => 'remark',
               'label' => 'Тусгай тэмдэглэл',
               'rules' => 'required')
       
    );

}
