<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class f_log_model extends MY_Model {    

    public $_table = 'f_log';

    // protected 
    public $belongs_to = array(
      'completion' => array('model' => 'completion_model', 'primary_key' => 'completion_id' ), 
      'reason' => array('model' => 'reason_model', 'primary_key' => 'reason_id' ),
      'equipment' => array('model' => 'equipment_model', 'primary_key' => 'equipment_id' )
    );

    public $has_many = array( 'log_detail' => array( 'model' => 'Log_detail_model', 'primary_key' => 'log_id' ) );

   // private $fields  = array( 'device_id', 'parameters', 'measure', 'value');

   // public $validate = array(

   //      array( 'field' => 'device_id',
   //             'label' => 'Төхөөрөмж',
   //             'rules' => 'required'),

   //      array( 'field' => 'parameters',
   //             'label' => 'Үзүүлэлт',
   //             'rules' => 'required'),

   //      array( 'field' => 'measure',
   //             'label' => 'Хэмжих нэгж',
   //             'rules' => 'required'),

   //      array( 'field' => 'value',
   //             'label' => 'Утга',
   //             'rules' => 'required'),
   
   //    );

   function __construct() {

        parent::__construct();

        $this->load->database ();
        
   }

}