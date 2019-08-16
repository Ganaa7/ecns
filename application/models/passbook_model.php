<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Passbook_model extends MY_Model {    

    public $_table = 'passbook';

    protected $soft_delete = FALSE;

    public $belongs_to = array( 'device', 'equipment' );
    
    public $primary_key = 'id';

     function __construct() {

        parent::__construct();

        $this->load->database ();
    }

     public $validate = array(

        array( 'field' => 'passbook_no',
               'label' => 'Дэд хэсгийн нэр',
               'rules' => 'required'),

        array( 'field' => 'node_id',
               'label' => 'Модиулууд',
               'rules' => 'required')
	   );
   
   
}