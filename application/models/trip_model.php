<?php

class trip_model extends MY_Model {

    public $_table = 'trip'; // you MUST mention the table name
    public $_table_dtl = 'trip_dtl'; // you MUST mention the table name
    public $_table_route = 'trip_route'; // you MUST mention the table name

    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $before_dropdown;
    public $protected = array(); // ...Or 
    
    private $fields  = array('id', 'trip_no', 'section', 'location', 'purpose', 'transport', 'distance', 'start_dt', 'end_dt', 'employee', 'est_dt');
    // spublic $belongs_to = array( 'section' => array( 'primary_key' => 'section_id', 'model'=>'section_model' ) ); 
   
    public $validate = array(
        array( 'field' => 'trip_no', 
               'label' => 'Томилолт №',
               'rules' => 'required' ),
        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required'),
        array( 'field' => 'routes',
               'label' => 'Маршрут',
               'rules' => 'required' ),
        array( 'field' => 'employee_id[]',
               'label' => 'ИТА-д',
               'rules' => 'required|numeric|trim' ),
        array( 'field' => 'purpose',
               'label' => 'Томилолтын зорилго',
               'rules' => 'required' ),
        array( 'field' => 'start_dt',
               'label' => 'Эхлэх огноо',
               'rules' => 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' ),
        array( 'field' => 'end_dt',
               'label' => 'Дуусах огноо',
               'rules' => 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' ),        
        array( 'field' => 'transport',
               'label' => 'Тээврийн хэрэгсэл',
               'rules' => 'required' )
    ); 
      
    
    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');
        parent::__construct();            
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

    
    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {        
        $my_arr = $crow = array();
        $this->db->select("id, trip_no, trip_section.section, location, purpose, transport, distance, start_dt, end_dt, GROUP_CONCAT(employee SEPARATOR ';') as employee, est_dt"); 
        $this->db->from($this->_table);
        $this->db->join('trip_dtl', "trip_dtl.trip_id = $this->_table.id", 'left');
        // $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');
         $this->db->join('(SELECT GROUP_CONCAT(DISTINCT section SEPARATOR ",") as section, trip_id  FROM trip_dtl group by trip_id) as trip_section ', "trip_section.trip_id = $this->_table.id", 'left');
        //$this->db->join('(SELECT concat(from_route, "-", to_route) as location, trip_id from trip_route where is_come ="N" group by trip_id order by num asc ) as trip_route', "trip_route.trip_id = $this->_table.id", 'left');
        $this->db->join('(SELECT GROUP_CONCAT(concat(from_route, "->", to_route) SEPARATOR ";") as location, trip_id  FROM trip_route group by trip_id) as trip_route ', "trip_route.trip_id = $this->_table.id", 'left');
        $this->db->join('(SELECT CONCAT(sum(distance), " км") as distance, trip_id from trip_route group by trip_id) as distance', "distance.trip_id = $this->_table.id", 'left');

        $this->db->join('(SELECT est_dt, trip_id from trip_route where is_come ="N" and est_dt is not null group by trip_id order by num asc ) as trip_status', "trip_status.trip_id = $this->_table.id", 'left');
                
        
        if ($where)          
           $this->db->where($where, NULL, FALSE);

        $this->db->group_by('trip_dtl.trip_id'); 
                
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
    
    function get_count($where = null) {  

        $this->db->select(" * ");
                  
        $this->db->from('trip');
        // $this->db->join('section', "section.section_id = $this->_table.section_id", 'left'); 
        
        if($where){
           $this->db->join('trip_dtl', "trip_dtl.trip_id = $this->_table.id", 'left');
           $this->db->join('(SELECT GROUP_CONCAT(DISTINCT section SEPARATOR ",") as section, trip_id  FROM trip_dtl group by trip_id) as trip_section ', "trip_section.trip_id = $this->_table.id", 'left'); 
           $this->db->join('(SELECT GROUP_CONCAT(concat(from_route, "->", to_route) SEPARATOR ";") as location, trip_id  FROM trip_route group by trip_id) as trip_route ', "trip_route.trip_id = $this->_table.id", 'left');
          $this->db->where($where, NULL, FALSE);
        }
        
        $this->db->group_by('trip_dtl.trip_id'); 

        $query  = $this->db->get();

        if( $query->num_rows () > 0){           
                  
           return $query->num_rows();
        }
        else return 0;
    }

    function insert_batch($data){
       $this->db->insert_batch($this->_table_dtl, $data);
    }

    function insert_route($data){
       $this->db->insert_batch($this->_table_route, $data);
    }
    
    function get_action() {
       $role = $this->session->userdata ( 'role' );      

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='trip' and controller='trip'" )->result ();
       foreach ( $result as $row ) {
           $result_array [$row->functions] = $row->functions;
       }
       //echo $this->db->last_query();
       return $result_array;
    } 

    function get_dtl($id){                
        $this->db->select ( 'employee_id, employee' );        
        $this->db->where ( array('trip_id'=>$id));
        $Query = $this->db->get('trip_dtl');

        if ($Query->num_rows () > 0) {
          foreach ( $Query->result_array () as $row ) {
             $data [$row ['employee_id']] = $row ['employee'];
          }
        }
        $Query->free_result ();
        if(isset($data)) return $data;
        else return array(0=>'');
    }

    function get_spot(){                
        $this->db->select ('id, CONCAT(sum , " - " , aimag) as spot', FALSE);        
        //$this->db->where ( array('trip_id'=>$id));
        $Query = $this->db->get('aimag_sum');

        if ($Query->num_rows () > 0) {
          foreach ( $Query->result_array () as $row ) {
             $data [$row ['id']] = $row ['spot'];
          }
        }
        $Query->free_result ();
        
        if(isset($data)) return $data;
        
        else return array(0=>'');

    }


    function report($where = null)
    {
        $this->db->select("id, trip_no, trip_section.section, location, purpose, transport, distance, start_dt, end_dt, GROUP_CONCAT(employee SEPARATOR ';') as employee, est_dt"); 
        $this->db->from($this->_table);
        $this->db->join('trip_dtl', "trip_dtl.trip_id = $this->_table.id", 'left');
        // $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');
        $this->db->join('(SELECT GROUP_CONCAT(DISTINCT section SEPARATOR ",") as section, trip_id  FROM trip_dtl group by trip_id) as trip_section ', "trip_section.trip_id = $this->_table.id", 'left');
        //$this->db->join('(SELECT concat(from_route, "-", to_route) as location, trip_id from trip_route where is_come ="N" group by trip_id order by num asc ) as trip_route', "trip_route.trip_id = $this->_table.id", 'left');
        $this->db->join('(SELECT GROUP_CONCAT(concat(from_route, "->", to_route) SEPARATOR ";") as location, trip_id  FROM trip_route group by trip_id) as trip_route ', "trip_route.trip_id = $this->_table.id", 'left');
        $this->db->join('(SELECT CONCAT(sum(distance), " км") as distance, trip_id from trip_route group by trip_id) as distance', "distance.trip_id = $this->_table.id", 'left');

        $this->db->join('(SELECT est_dt, trip_id from trip_route where is_come ="N" and est_dt is not null group by trip_id order by num asc ) as trip_status', "trip_status.trip_id = $this->_table.id", 'left');
                
        if ($where)          
           $this->db->where($where, NULL, FALSE);

         $this->db->group_by('trip_dtl.trip_id'); 

         // $this->db->order_by('section');

        return $this->db->get()->result();

    }


}
	