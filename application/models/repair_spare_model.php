<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Repair_spare_model extends MY_Model {    

    public $_table = 'repair_spare';
    

    protected $soft_delete = FALSE;

    public $belongs_to = array( 'repair');

    public $primary_key = 'id';

    public $before_create = array( 'timestamps' );
    
     protected function timestamps($data)
     {
          $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');        
          
          return $data;
     }

     function __construct() {

        parent::__construct();

        $this->load->database ();
    }

     public $validate = array(

        array( 'field' => 'passbook_no',
               'label' => 'Дэд хэсгийн нэр',
               'rules' => 'required'),

        array( 'field' => 'node_id',
               'label' => 'Модиулууд',
               'rules' => 'required')
	   );
   
   
}