<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Contract_model extends MY_Model {    
    
    public $_table = 'contract';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}