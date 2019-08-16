<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Event_detail_model extends MY_Model {    
    
    public $_table = 'm_event_dtl';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}