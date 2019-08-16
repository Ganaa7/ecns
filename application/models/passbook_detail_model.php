<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Passbook_detail_model extends MY_Model {    

    public $_table = 'passbook_detail';
    
    public $primary_key = 'id';

     function __construct() {

        parent::__construct();

        $this->load->database ();
    }
   
   
}