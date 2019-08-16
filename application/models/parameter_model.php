<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Parameter_model extends MY_Model {    

    public $_table = 'parameters';

    // protected 
   public $belongs_to = array('device' => array('model' => 'device_model', 'primary_key' => 'device_id' ));

   private $fields  = array( 'device_id', 'parameters', 'measure', 'value');

   public $validate = array(

        array( 'field' => 'device_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required'),

        array( 'field' => 'parameters',
               'label' => 'Үзүүлэлт',
               'rules' => 'required'),

        array( 'field' => 'measure',
               'label' => 'Хэмжих нэгж',
               'rules' => 'required'),

        array( 'field' => 'value',
               'label' => 'Утга',
               'rules' => 'required'),
   
      );

   function __construct() {

        parent::__construct();

        $this->load->database ();
        
   }

  function array_from_post($fields){
        $data = array();
        foreach ($fields as $field) {
          $data[$field]=$this->input->post($field);
        }
        return $data;
  }

    
      

}