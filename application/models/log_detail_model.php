<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Log_detail_model extends MY_Model {    

    public $_table = 'f_log_dtl';

    // protected 
   // public $belongs_to = array('device' => array('model' => 'device_model', 'primary_key' => 'device_id' ));

   function __construct() {

        parent::__construct();

        $this->load->database ();
        
   }

}