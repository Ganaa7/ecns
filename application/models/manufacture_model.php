<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Manufacture_model extends MY_Model {    
    public $_table = 'wm_manufacture';
    public $primary_key = 'manufacture_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}