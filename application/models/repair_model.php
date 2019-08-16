<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Repair_model extends MY_Model {    
       

   public $_table = 'repair';

   public $before_create = array( 'timestamps' );

   protected $before_get = array('order_method');  
      
   function order_method(){

       $this->db->order_by("repair_date", "asc"); 

   }
    
   protected function timestamps($data)
   {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');        
        
       //  $data['created_id'] = $this->session->user_data('employee_id');        
                
        return $data;
   }

   public $belongs_to = array('wh_spare' => array('model' => 'wh_spare_model', 'primary_key' => 'spare_id' ));

   public $has_many = array( 'repair_spare', 'repair_employee' );

   public $validate = array(

        array( 'field' => 'device_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required'),
        
        array( 'field' => 'repair_date',
               'label' => 'Засвар огноо',
               'rules' => 'required'),  

        array( 'field' => 'reason',
               'label' => 'Засвар хийх болсон шалтгаан',
               'rules' => 'required'),     

        array( 'field' => 'repair',
               'label' => 'Засварын ажлын нэр',
               'rules' => 'required'),  
      
        array( 'field' => 'spare_id',
               'label' => 'Материал сэлбэгийн нэр',
               'rules' => 'required'),

        array( 'field' => 'qty',
               'label' => 'Тоо ширхэг',
               'rules' => 'required'),

        array( 'field' => 'part_number',
               'label' => 'Үйлдэврийн №',
               'rules' => 'required'),
            
        array( 'field' => 'duration',
               'label' => 'Үргэлжилсэн t',
               'rules' => 'required'), 

        array( 'field' => 'repairedby_id',
               'label' => 'Засвар хийсэн ИТА',
               'rules' => 'required'),   
      );

   function __construct() {

       parent::__construct();

       $this->load->database ();

       $this->load->library ( 'form_validation' );
        
   }

   function add(){

      $this->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

      unset($this->repair->validate[1]);

      if($this->repair->validate($this->repair->validate)){

          $data = $this->repair->array_from_post(array('device_id', 'repair_date', 'reason', 'repair', 'spare_id', 'qty', 'part_number', 'duration', 'repairedby_id'));

          $repair_man = $this->employee->get($data['repairedby_id']);

          $data['repairedby'] = $repair_man->fullname;

          if ($id = $this->repair->insert($data, TRUE)){
             
             $return = array (
                  'status' => 'success',
                  'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
             );

          }else{

            echo $this->Obj->db->last_query();
           
              $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                );

          }  

      }else{

         $return = array (
            'status' => 'failed',
            'message' => validation_errors ( '', '<br>' )
         );
      }

   }

  function array_from_post($fields){

      $data = array();
      
      foreach ($fields as $field) {
        $data[$field]=$this->input->post($field);
      }
      return $data;
  }

}