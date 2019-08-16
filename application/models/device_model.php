<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Device_model extends MY_Model {  

    public $_table = 'device';

    public $repair;

    public $pass_no;

    // protected 
    public $belongs_to = array('equipment' => array('model' => 'equipment_model', 'primary_key' => 'equipment_id' ), 
        'certificate' => array('model' => 'certificate_model', 'primary_key' => 'certificate_id') ,
        'location' => array( 'model' => 'location_model', 'primary_key' => 'location_id'),
        'manufacture' => array( 'model' => 'Manufacture_model', 'primary_key' => 'manufacture_id'),       
        'country' => array( 'model' => 'Country_model', 'primary_key' => 'country_id'),
        'section' => array( 'model' => 'section_model', 'primary_key' => 'section_id')

        );

    public $has_many = array('repair' => array( 'model' => 'Repair_model', 'primary_key' => 'device_id'));
   
    private $fields  = array( 'id','passport_no',  'device','cert_no', 'location', 'section', 'equipment', 'mark', 'part_number', 'year_init');

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

   public $validate = array(

        array( 'field' => 'passport_no',
               'label' => 'Пасспортын №',
               'rules' => 'required'),

        array( 'field' => 'location_id',
               'label' => 'Байршил',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'equipment_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'mark',
               'label' => 'Марк, төрөл',
               'rules' => 'required'),

        array( 'field' => 'certificate_id',
               'label' => 'Гэрчилгээ',
               'rules' => 'is_natural'),

        array( 'field' => 'year_init',
               'label' => 'Ашиглалтад орсон он',
               'rules' => 'required'), 

        array( 'field' => 'intend',
               'label' => 'Зориулалт',
               'rules' => 'required'),

        array( 'field' => 'power',
               'label' => 'Хүчин чадал',
               'rules' => 'required'),       

        array( 'field' => 'serial_number',
               'label' => 'Үйлдвэрийн №',
               'rules' => 'required'),

        array( 'field' => 'part_number',
               'label' => 'Загвар модель №(part_number)',
               'rules' => 'required'),   

         array( 'field' => 'factory_date',
               'label' => 'Үйлдвэрлэсэн огноо',
               'rules' => 'required'),

        // array( 'field' => 'invoice_no',
        //        'label' => 'Санхүүгийн бүртгэл',
        //        'rules' => 'required'),

        array( 'field' => 'order_no',
               'label' => 'Ашиглалтад оруулсан тушаал',
               'rules' => 'required'),

        array( 'field' => 'order_date',
               'label' => 'Ашиглалтад оруулсан огноо',
               'rules' => 'required'),

        array( 'field' => 'repair_time',
               'label' => 'Засвар хоорондын хугацаа',
               'rules' => 'required'),  
        
        array( 'field' => 'maintenance_time',
               'label' => 'Техник үйлчилгээ хоорондын хугацаа',
               'rules' => 'required'),   
  
       array( 'field' => 'lifetime',
               'label' => 'Ашиглалтын хугацаа',
               'rules' => 'required'),
      );
 

   
      function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {

        $sql = "";

        $this->db->select("device.id, device.passport_no, cert_no, location.location, section, device, equipment, mark, part_number, device.year_init");

        $this->db->from($this->_table);

        $this->db->join("equipment2", "device.equipment_id=equipment2.equipment_id", 'inner');

        $this->db->join("section", "section.section_id = equipment2.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = equipment2.sector_id", 'left');
        
        $this->db->join("certificate", "certificate.id = device.certificate_id", 'left');
       //  $this->db->join('(SELECT GROUP_CONCAT(DISTINCT cert_no SEPARATOR ",") as cert_no, equipment_id, location_id FROM certificate group by equipment_id, location_id) as certificate', "certificate.equipment_id = $this->_table.equipment_id and certificate.location_id = $this->_table.location_id", 'left' );
        
        $this->db->join("location", "device.location_id = location.location_id", 'left');
        
        // $this->db->join("certificate", "device.location_id = certificate.location_id and device.equipment_id = certificate.equipment_id", 'left');

        if ($where)
           $this->db->where($where, NULL, FALSE);

        if ($sidx && $sord) {
            $this->db->order_by($sidx, $sord);
        }

        if ($limit)
            $this->db->limit($limit, $start);

        // echo $this->db->last_query();

        $result = $this->db->get()->result();

        $i = 0;

        if($result){

           foreach ($result as $rows) {
              foreach ($this->fields as $field) {
                 $crow[$field] = $rows->$field;
              }
              
              $my_arr[$i] = (object)$crow;
              $i++;
          }

        }else{
           $my_arr=array();
        }

        return $my_arr;      
    }


   function get_action() {

       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='equipment' and controller='device'" )->result ();

       if($result){

         foreach ( $result as $row ) {

            $result_array [$row->functions] = $row->functions;

         }

         return $result_array;

       }else

          return array();
   
   }

   function last_query(){

   	return $this->db->last_query();
   }

    function array_from_post($fields){
        $data = array();
        foreach ($fields as $field) {
          $data[$field]=$this->input->post($field);
        }
        return $data;
    }


   public function get_passport_no($section_id)
   {
      $query_string = "SELECT SUBSTRING(passport_no, 8, length(passport_no)) max_num
                            FROM device A WHERE section_id = $section_id 
                            ORDER BY max_num+0 DESC LIMIT 1;";

      $max_qry = $this->db->query($query_string);

      // echo $max_qry->num_rows();

      if($max_qry->num_rows()>0){

         $max_no = intval($max_qry->row()->max_num)+1;         
    
      }else

         $max_no = 1;

      return $max_no;

   }


    function get_count($where){

        $this->db->select("COUNT(*) as count ");

        $this->db->from($this->_table);

        $this->db->join("equipment2", "device.equipment_id=equipment2.equipment_id", 'inner');

        $this->db->join("section", "section.section_id = equipment2.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = equipment2.sector_id", 'left');
        
        $this->db->join("location", "device.location_id = location.location_id", 'left');
        
        $this->db->join("certificate", "device.location_id = certificate.location_id and device.equipment_id = certificate.equipment_id", 'left');
        

        if ($where)
           $this->db->where($where, NULL, FALSE);

         $query = $this->db->get();

        if($query->num_rows()>0){

           $row = $query->row();

           return $row->count;

        }else  return 0;

    }


    function get_location($where=null){

       $this->db->select('device.location_id as location_id, location.location as location');

       $this->db->from($this->_table);

       $this->db->join('location', $this->_table.".location_id = location.location_id", 'left');

       if($where) $this->db->where($where, NULL, FALSE);
       
       return $this->db->get();

    }
    

} 

   ?>