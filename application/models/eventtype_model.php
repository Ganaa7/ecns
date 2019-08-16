<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class eventtype_model extends MY_Model {

    public $_table = 'eventtype';
    public $primary_key = 'eventtype_id';

    public $after_dropdown = array('before_select');

    function before_select($data){

      $data['0'] = 'Нэг утгийг сонго';

      ksort($data);
      
      return $data;
    }

    function __construct() {
        parent::__construct();
        $this->load->database ();
    }



}
