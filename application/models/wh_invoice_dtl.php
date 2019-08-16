<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class wh_invoice_dtl extends MY_Model {    

    public $_table = 'wh_invoice_dtl';
    
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }
   

} 

   ?>