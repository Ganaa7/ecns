<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class wm_order_model extends MY_Model {    
    public $_table = 'wm_order';
    public $primary_key = 'order_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
    
    
}