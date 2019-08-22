<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Material_model extends MY_Model {    

    public $_table = 'materials';

    public $before_create = array( 'timestamps' );
    
    protected function timestamps($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');        
        return $data;
    }

   public $belongs_to = array('device' => array('model' => 'device_model', 'primary_key' => 'device_id' ));

   public $validate = array(

        array( 'field' => 'device_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required'),

        array( 'field' => 'materials',
               'label' => 'Материал',
               'rules' => 'required'),

        array( 'field' => 'qty',
               'label' => 'Тоо хэмжээ',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'part_number',
               'label' => 'Үйлдэврийн №',
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