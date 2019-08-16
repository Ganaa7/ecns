<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Reason_model extends MY_Model {    
    
    public $_table = 'f_reason';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}