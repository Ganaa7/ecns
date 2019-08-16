<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class trainer_model extends MY_Model {  

    public $_table = 'trainer';

    public $primary_key = 'trainer_id';

    private $fields  = array('trainer_id', 'register', 'firstname', 'lastname', 'fullname', 'gender', 'birthdate',	
    	'position_id', 'occupation', 'phone', 'email', 'education', 'rel_type', 'rel_phone');
   
    function __construct() {
        parent::__construct();
        $this->load->database ();
    }



    public $validate = array(

        // removed by Ganaa         
       
        
        array( 'field' => 'lastname',
               'label' => 'Эцэг эхийн нэр',
               'rules' => 'required'),

        array( 'field' => 'firstname',
               'label' => 'Өөрийн нэр',
               'rules' => 'required'),
        
        array( 'field' => 'birthdate', 
               'label' => 'Төрсөн огноо',
               'rules' => 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]'),

        array( 'field' => 'register', 
               'label' => 'Регистерийн дугаар',
               'rules' => 'required|exact_length[10]'),  

        array( 'field' => 'nationality', 
               'label' => 'Үндэс угсаа',
               'rules' => 'required'),
        
        array( 'field' => 'org_id',
               'label' => 'Байгууллага',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'location_id',
               'label' => 'Байршил',
               'rules' => 'is_natural_no_zero' ),

        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'is_natural_no_zero' ),  
      
        array( 'field' => 'position_id',
               'label' => 'Албан тушаал',
               'rules' => 'required|is_natural_no_zero'),    

        array( 'field' => 'email',
               'label' => 'Имэйл хаяг',
               'rules' => 'required' ),

        array( 'field' => 'phone',
               'label' => 'Гар утас',
               'rules' => 'required' ),  

        array( 'field' => 'rel_phone',
               'label' => 'Холбогдох хүний утас',
               'rules' => 'required' ),    
	
	     array( 'field' => 'occupation',
               'label' => 'Боловсролын байдал:Төгссөн сургууль, мэргэжил',
               'rules' => 'required' ),      

        array( 'field' => 'license_no',
               'label' => 'Мэргэжлийн үнэмлэх ',
               'rules' => 'required' ),    

        array( 'field' => 'license_type_id',
               'label' => 'Үнэмлэхний төрөл ',
               'rules' => 'required' ),

        array( 'field' => 'initial_date',
               'label' => 'Үнэмлэх анх олгосон огноо ',
               'rules' => 'required' ),

        array( 'field' => 'initial_date',
               'label' => 'Үнэмлэх анх олгосон огноо ',
               'rules' => 'required' ),

        array( 'field' => 'issued_date',
               'label' => 'Олгосон огноо ',
               'rules' => 'required' ),

        array( 'field' => 'valid_date',
               'label' => 'Хүчинтэй огноо ',
               'rules' => 'required' ),

        array( 'field' => 'expired_date',
               'label' => 'Дуусах огноо ',
               'rules' => 'required' ),

        array( 'field' => 'license_equipments',
               'label' => 'Ажиллах тоног төхөөрөмжүүд ',
               'rules' => 'required' )


    ); 


    function get_action() {

       $role = $this->session->userdata ( 'role' );      

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='training' and controller='training'" )->result ();

       if($result){
         foreach ( $result as $row ) {
            $result_array [$row->functions] = $row->functions;
         }       
         return $result_array;
       }else
          return array();
    }  


   function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {        

	    $my_arr = $crow = array();

	    $this->db->_protect_identifiers=false;

	    $this->db->select(" trainer.trainer_id, trainer.register, trainer.firstname, trainer.lastname, trainer.fullname, trainer.gender, trainer.birthdate,	trainer.position_id, trainer.occupation, trainer.phone, trainer.email, trainer.education, trainer.rel_type, trainer.rel_phone, trainer.up_date,
         trainer.location_id,    IF((trainer.aa_license <> NULL),  CONCAT(trainer.license_no, '/', trainer.aa_license),  trainer.license_no) AS license_no, trainer.license_type_id, trainer.issued_date, trainer.valid_date, trainer.expired_date, trainer.license_equipment, trainer.aa_license, trainer.aa_group, location.name as location,  CONCAT(licence_type.code, '-', licence_type.licence_type) AS license_type, position.name AS position,
            section.name AS section", false); 

	    $this->db->from($this->_table);

	    $this->db->join('location', "location.location_id = $this->_table.location_id", 'left');

	    $this->db->join('licence_type', "licence_type.id = $this->_table.license_type_id", 'left');

	    $this->db->join('position', "position.position_id = $this->_table.position_id", 'left');
	    
	    $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');

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
	    
	    foreach ($result as $rows)  {

	            foreach ($this->fields as $field) {

	               $crow[$field] = $rows->$field;
	            
	            }

	            $my_arr[$i] = (object)$crow;

	            $i++;	        
	     }        

	     return $my_arr;    
    } 



    function get_count($where){

	    $this->db->select(" trainer.trainer_id, trainer.register, trainer.firstntrainerme, trainer.lastname, trainer.fullname, trainer.gender, trainer.birthdate, trainer.position_id, trainer.occupation, trainer.phone, trainer.email, trainer.education, trainer.rel_type, trainer.rel_phone, trainer.up_date, trainer.location_id, IF((trainer.aa_license <> NULL),  CONCAT(trainer.license_no, '/', trainer.aa_license),  trainer.license_no) AS license_no, trainer.license_type_id, trainer.issued_date, trainer.valid_date, trainer.expired_date, trainer.license_equipment, trainer.aa_license, trainer.aa_group, c.name,  CONCAT(d.value, '-', d.name) AS license_type, e.name AS position, b.name AS section", false); 

	    $this->db->from($this->_table );

	    $this->db->join('location c', "c.location_id = $this->_table.location_id", 'left');

	    $this->db->join('view_license_type d', "d.id = $this->_table.license_type_id", 'left');

	    $this->db->join('position e', "e.position_id = $this->_table.position_id", 'left');
	    
	    $this->db->join('section f', "f.section_id = $this->_table.section_id", 'left');

        if(strlen($where)>1){
          
           $this->db->where($where, NULL, FALSE);

        }

           $num_rows = $this->db->count_all_results();          

        return $num_rows;
    }

    function last_query(){
  
       return $this->db->last_query();
  
    }
    
   



}
    