<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Purpose_model extends MY_Model {    
    public $_table = 'trip_purpose';
    public $primary_key = 'purpose_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}