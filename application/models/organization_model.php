<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class organization_model extends MY_Model {    
    
    public $_table = 'organization';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}