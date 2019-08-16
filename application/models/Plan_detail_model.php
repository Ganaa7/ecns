<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Plan_detail_model extends MY_Model {    

    public $_table = 'plan_detail';
    public $_table_incharge = 'plan_incharge';
    
    public $primary_key = 'id';
   
    function __construct() {
        
        parent::__construct();

        $this->load->database ();

    } 

     public $validate = array(
     // removed by Ganaa
     
     array( 'field' => 'number',
            'label' => 'Дугаар',
            'rules' => 'required|is_numeric'),

     array( 'field' => 'detail',
            'label' => 'Хэрэгжүүлэх арга хэмжээ',
            'rules' => 'required'),

      array( 'field' => 'employee_id',
            'label' => 'Хариуцсан ИТА',
            'rules' => 'required')

     // array( 'field' => 'completion',
     //        'label' => 'Гүйцэтгэл',
     //        'rules' => 'required'),

     // array( 'field' => 'percent',
     //        'label' => 'Биелэлт',
     //        'rules' => 'required|is_numeric')
    
    

 );    

    function insert_batch($data){
       
       $this->db->insert_batch($this->_table_incharge, $data);
       
    }
   

}