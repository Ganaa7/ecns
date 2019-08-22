<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class tl_model extends MY_Model {    

    public $_table = 'training_location';
    
    public $primary_key = 'id';
   
    function __construct() {
        parent::__construct();     
    }
}