<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class License_type_model extends MY_Model {    

    public $_table = 'licence_type';
    
    public $primary_key = 'id';

 
    function __construct() {

        parent::__construct();

        $this->load->database ();
    }
   
   
}