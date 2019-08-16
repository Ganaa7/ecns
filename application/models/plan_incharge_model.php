<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Plan_incharge_model extends MY_Model {    

    public $_table = 'plan_incharge';
        
    public $primary_key = 'id';
   
    function __construct() {
        
        parent::__construct();

        $this->load->database ();

    } 

    
   

}