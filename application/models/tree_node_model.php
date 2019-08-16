<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class tree_node_model extends MY_Model {    
    
    public $_table = 'f_tree';
    
    public $primary_key = 'id';
   
    function __construct() {
       
        parent::__construct();

        $this->load->database ();
    }
    

}