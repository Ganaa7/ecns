<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Repair_employee_model extends MY_Model {    

    public $_table = 'repair_employee';

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

 
}