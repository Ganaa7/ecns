<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Print_history_model extends MY_Model {

    public $_table = 'print_history';

    public $primary_key = 'id';

	
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }



}
