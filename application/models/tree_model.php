<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class tree_model extends MY_Model {    

    public $_table = 'f_tree';
    
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
   

} 

   ?>