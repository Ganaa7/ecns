<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Gfile_model extends MY_Model {    
    public $_table = 'guidance_file';
    public $primary_key = 'file_id';
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
 
 }

 ?>