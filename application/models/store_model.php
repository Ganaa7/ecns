<?php

class Store_model extends MY_Model {

    // Hutulbur table
    public $_table = 'h_store'; 
     

    public $primary_key = 'id'; // you MUST mention the primary key
    
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update

    public $before_dropdown;
    
    public $protected = array(); // ...Or 

    private $fields  = array('id', 'spare_id', 'date', 'need_qty', 'using_qty');

    // public $belongs_to = array( 'Gfile' => array('model' => 'Gfile_model', 'primary_key' => 'file_id' ) );
    
    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');        
        parent::__construct();           
        $this->load->library('form_validation');
        $this->form_validation->set_message('is_natural_no_zero', '[%s] -утгаас нэгийг сонгох шаардлагтай');
    }   
    
    public $validate = array(

        // removed by Ganaa         
        array( 'field' => 'spare_id', 
               'label' => 'Сэлбэг',
               'rules' => 'required'),
                
        array( 'field' => 'date',
               'label' => 'Нэмсэн огноо',
              'rules' => 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]'),    

        array( 'field' => 'need_qty',
               'label' => 'Сэлбэгэнд байх ёстой тоо',
               'rules' => 'required'),
        
        array( 'field' => 'using_qty',
               'label' => 'Ашиглагдаж буй тоо/ш',
               'rules' => 'required' )

    ); 
   
    
    function get_action() {
       $role = $this->session->userdata ( 'role' );      

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='training' and controller='guidance'" )->result ();
       if($result){
         foreach ( $result as $row ) {
            $result_array [$row->functions] = $row->functions;
         }       
         return $result_array;
       }else
          return array();
    }  


    function array_from_post($fields){
        $data = array();
        foreach ($fields as $field) {
          $data[$field]=$this->input->post($field);
        }
        return $data;
    }

    
      
    function last_query(){
       return $this->db->last_query();
    }



}
	