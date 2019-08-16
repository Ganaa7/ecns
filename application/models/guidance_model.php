<?php

class Guidance_model extends MY_Model {

    // Hutulbur table
    public $_table = 'guidance'; 
     

    public $primary_key = 'id'; // you MUST mention the primary key
    
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update

    public $before_dropdown;
    
    public $protected = array(); // ...Or 

    private $fields  = array('id', 'number', 'section', 'equipment', 'guidance', 'location', 'hours', 'date', 'file_id');

    public $belongs_to = array( 'Gfile' => array('model' => 'Gfile_model', 'primary_key' => 'file_id' ) );
    
    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');        
        parent::__construct();           
        $this->load->library('form_validation');
        $this->form_validation->set_message('is_natural_no_zero', '[%s] -утгаас нэгийг сонгох шаардлагтай');
    }   
    
    public $validate = array(

        // removed by Ganaa         
        array( 'field' => 'number', 
               'label' => 'Хөтөблөрийн №',
               'rules' => 'required|is_unique[guidance.number]'),
        
        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'equipment_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required|is_natural_no_zero'),
        
        array( 'field' => 'guidance', 
               'label' => 'Хөтөлбөр',
               'rules' => 'required'),
        
        array( 'field' => 'location',
               'label' => 'Явагдах газар',
               'rules' => 'required'),
        
        array( 'field' => 'hours',
               'label' => 'Хугацаа',
               'rules' => 'required' ),
      
        array( 'field' => 'date',
               'label' => 'Батлагдсан огноо',
              'rules' => 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]'),    

        array( 'field' => 'file_id',
               'label' => 'Хөтөлбөрийн файл',
               'rules' => 'required]' ) //|regex_match[/S+

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


    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {        

        $my_arr = $crow = array();

        $this->db->_protect_identifiers=false;

        $this->db->select("guidance.id, number, guidance.guidance, section, equipment, location, CONCAT(hours,' цаг ', minute, ' минут') as hours, date, guidance.file_id, guidance_file.file_name", false); 

        $this->db->from($this->_table);

        $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'left');

        $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');

        $this->db->join('guidance_file', "guidance_file.file_id = $this->_table.file_id", 'left');

        if ($where)          
           $this->db->where($where, NULL, FALSE);
         
         $this->db->group_by('id'); 
                
        if ($sidx && $sord) {                
           $this->db->order_by($sidx, $sord);
        }
        if ($limit)          //$sql .= " LIMIT $start , $limit";
           $this->db->limit($limit, $start);
        
        $result = $this->db->get()->result();
        
        $i = 0;
           foreach ($result as $rows)
        {
               foreach ($this->fields as $field) {
                 $crow[$field] = $rows->$field;
               }
               $my_arr[$i] = (object)$crow;
               $i++;
           
        }        
        return $my_arr;    
    } 
    
      
    function last_query(){
       return $this->db->last_query();
    }


    function get_count($where){

         $this->db->select("guidance.id, number, guidance.guidance, section, equipment, location, CONCAT(hours,' цаг ', minute, ' минут') as hours, date, guidance.file_id, guidance_file.file_name", false); 

        $this->db->from($this->_table);

        $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'left');

        $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');

        $this->db->join('guidance_file', "guidance_file.file_id = $this->_table.file_id", 'left');


        if(strlen($where)>1){
          
           $this->db->where($where, NULL, FALSE);

        }

           $num_rows = $this->db->count_all_results();          

        return $num_rows;
    }



}
	