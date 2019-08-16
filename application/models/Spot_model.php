<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Spot_model extends MY_Model {    
    public $_table = 'aimag_sum';
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}