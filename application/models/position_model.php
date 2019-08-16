<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Position_model extends MY_Model {    
    public $_table = 'position';
    public $primary_key = 'position_id';
   
    function __construct() {
        parent::__construct();     
    }
}