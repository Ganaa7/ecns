<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Country_model extends MY_Model {    
    
    public $_table = 'country';
    
    public $primary_key = 'country_id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}