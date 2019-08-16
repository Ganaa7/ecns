<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class License_equipment_model extends MY_Model {    

    public $_table = 'license_equipment';
    
    public $primary_key = 'id';
   
    function __construct() {

        parent::__construct();

    }

    
} 