<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class position_detail_model extends MY_Model {

    public $_table = 'position_detail';

    public $primary_key = 'id';

	 public $belongs_to = array( 

		'position' => array('model' => 'position_model', 'primary_key' => 'position_id' )	

	);

    function __construct() {

        parent::__construct();

        $this->load->database ();

    }

    //  public $validate = array(

    //     array( 'field' => 'employee_id',
    //            'label' => 'Ажилтан',
    //            'rules' => 'required'),

    //     array( 'field' => 'position_id',
    //            'label' => 'Албан тушаал',
    //            'rules' => 'required|is_natural_no_zero'),

    //     array( 'field' => 'appointed_date',
    //            'label' => 'Томилогдсон огноо',
    //            'rules' => 'required|exact_length[10]')

        
    // );



}
