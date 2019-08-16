<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Measures_model extends MY_Model {    
    public $_table = 'wm_measures';
    public $primary_key = 'measure_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}