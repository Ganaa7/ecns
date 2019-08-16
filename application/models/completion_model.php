<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Completion_model extends MY_Model {    
    
    public $_table = 'f_completion_type';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}