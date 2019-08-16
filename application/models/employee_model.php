<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
	
class Employee_model extends MY_Model {    
   
   public $_table = 'employee';
    
   protected $primary_key = 'employee_id';

   protected $soft_delete = TRUE;

   protected $soft_delete_key = 'deleted_status';

   protected $before_get = array('order_method');  

   public $belongs_to = array('position' => 
                                  array('model' => 'position_model', 'primary_key' => 'position_id' ) );

   protected $after_dropdown = array('options');

   public $validate = array(

        array( 'field' => 'first_name',
               'label' => 'Нэр',
               'rules' => 'required|trim'),

        array( 'field' => 'last_name',
               'label' => 'Эцэг эх',
               'rules' => 'required|trim'),

        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|trim|is_natural_no_zero'),

        array( 'field' => 'position_id',
               'label' => 'Албан тушаал',
               'rules' => 'required|trim|is_natural_no_zero'),

        array( 'field' => 'sector_id',
               'label' => 'Тасаг',
               'rules' => 'required|trim'),

        array( 'field' => 'username',
               'label' => 'Хэрэглэгчийн нэр',
               'rules' => 'required|trim|is_unique[employee.username]'),

        array( 'field' => 'password',
               'label' => 'Нууц үг',
               'rules' => 'required|trim'),

         array( 'field' => 're_password',
               'label' => 'Нууц үг давтах',
               'rules' => 'required|trim|matches[password]')

    );
   
    private $fields  = array('employee_id','fullname', 'position', 'section', 'sector', 'username', 'email', 'workphone');
    
   function order_method(){

      $this->db->order_by("first_name", "asc"); 

   }

   protected function options($data){

      $data['0'] = 'ИТА-уудаас сонго';

      ksort($data);

      return $data;
   }

   function array_from_post($fields){
        $data = array();
        foreach ($fields as $field) {
          $data[$field]=$this->input->post($field);
        }
        return $data;
    }
   
    function __construct() {

        parent::__construct();

        $this->load->database ();
    }

    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {

        $sql = "";

        $this->db->select("employee_id, first_name, last_name, fullname, position.name as position, location_id, (CASE sector.sector_id WHEN 0 THEN ' '  ELSE sector.name  END) AS sector, section.name as section, email, username, workphone");

        $this->db->from($this->_table);

        $this->db->join("section", "section.section_id = employee.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = employee.sector_id", 'left');

        $this->db->join("position", "$this->_table.position_id = position.position_id", 'left');

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


    function get_count($where){

        $this->db->select("COUNT(*) as count ");

        $this->db->from($this->_table);

        $this->db->join("section", "section.section_id = employee.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = employee.sector_id", 'left');

        if ($where)
           $this->db->where($where, NULL, FALSE);

         $query = $this->db->get();

        if($query->num_rows()>0){

           $row = $query->row();

           return $row->count;

        }else  return 0;

    }

    // төхөөрөмж
    function with_drop_down($column, $where = null, $in = false) { // , $join_table =null, $join_id = null      
        $this->db->select ( '*' );      
        if($where)
           $this->db->where ( $where );      
        
        $Query = $this->db->get ( $this->_table );
        
        if ($Query->num_rows () > 0) {
          foreach ( $Query->result_array () as $row ) {
            $data [$row [$this->primary_key]] = $row [$column];
          }
        }
        $Query->free_result ();
        return $data;
    }

    function in_drop_down($column, $filter, $where =null) { // , $join_table =null, $join_id = null      
      $this->db->select ( '*' );      
      if($where)
         $this->db->where_in($filter, $where, true );      
      
      $Query = $this->db->get ( $this->_table );
      
      if ($Query->num_rows () > 0) {
        foreach ( $Query->result_array () as $row ) {
          $data [$row [$this->primary_key]] = $row [$column];
        }
      }
      $Query->free_result ();
     // echo $this->db->last_query();
      return $data;
    }

    function get_action() {

       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='user' and controller='index'" )->result ();

       if($result){

         foreach ( $result as $row ) {

            $result_array [$row->functions] = $row->functions;

         }

         return $result_array;

       }else

          return array();
    }

    function hash_2($string){

		  return hash('md5', $string); //.config_item('encryption_key')

    }
   
   function hash ($string)
	 {
      return hash('sha512', $string . config_item('encryption_key'));
	 }

    
}