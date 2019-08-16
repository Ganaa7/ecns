<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Sparetype_model extends MY_Model {    
    public $_table = 'wh_sparetype';
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}