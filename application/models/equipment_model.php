<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Equipment_model extends MY_Model {

    public $_table = 'equipment2';

    public $after_dropdown = array('before_select');

    function before_select($data){

      $data['0'] = 'Нэг утгийг сонго';

      ksort($data);
      
      return $data;
    }

    protected $belongs_to = array('section', 'sector');
    //belongs to equipment as parent id solve this!!!

    public $primary_key = 'equipment_id';

    function __construct() {

        parent::__construct();

        $this->load->database ();
        
    }

    private $fields  = array('equipment_id','equipment', 'section', 'sector', 'intend', 'spec', 'code', 'sp_id', 'year_init');

    public $validate = array(

        array( 'field' => 'equipment',
               'label' => 'Төхөөрөмж',
               'rules' => 'required'),

        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'sector_id',
               'label' => 'Тасаг',
               'rules' => 'required|is_natural_no_zero'),

         array( 'field' => 'intend',
               'label' => 'Зориулалт',
               'rules' => 'required'),

        array( 'field' => 'code',
               'label' => 'Код',
               'rules' => 'required|exact_length[3]'),

        array( 'field' => 'spec',
               'label' => 'Үзүүлэлт',
               'rules' => 'required')

    );

    function grid(){
       
       $this->db->select('*');

       $this->db->from($this->_table);

       $this->db->join('employee', "employee.employee_id = $this->table.createdby_id", 'left');
       
       $query = $this->db->get();
       
       echo $this->db->last_query();

    }

    function ext_dropdown($key_col, $column, $filter=null, $where =null) { // , $join_table =null, $join_id = null
       $this->db->select ( '*' );
       if($where)
          $this->db->where_in($filter, $where, true );

       $Query = $this->db->get ( $this->_table );

       if ($Query->num_rows () > 0) {
         foreach ( $Query->result_array () as $row ) {
           $data [$row [$key_col]] = $row [$column];
         }
       }
       $Query->free_result ();

       return $data;
    }

    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {

        $sql = "";

        $this->db->select("equipment2.equipment_id, equipment, spec, intend, equipment2.code, sp_id, year_init, section.section_id, section.name as section, sector.sector_id, sector.name as sector");

        $this->db->from($this->_table);

        $this->db->join("section", "section.section_id = equipment2.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = equipment2.sector_id", 'left');

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

        $this->db->join("section", "section.section_id = equipment2.section_id", 'left');

        $this->db->join("sector", "sector.sector_id = equipment2.sector_id", 'left');

        if ($where)
           $this->db->where($where, NULL, FALSE);

         $query = $this->db->get();

        if($query->num_rows()>0){

           $row = $query->row();

           return $row->count;

        }else  return 0;

    }


    function get_action() {

       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='equipment' and controller='index'" )->result ();

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

    function get_spare_id($section_id, $sector_id){

        $query = "SELECT id FROM numbers a WHERE id not IN
                  (SELECT sp_id FROM equipment2 WHERE sp_id IS NOT NULL AND section_id = $section_id AND sector_id = $sector_id)
                  ORDER BY id ASC LIMIT 1";

        $query = $this->db->query($query);

        // хэрэв утга байвал гэт мин-г авна
        if ($query->num_rows() > 0){

            $row = $query->row();

            $new_id = $row->id;

        }else{

           $query = $this->db->query("SELECT sp_id FROM equipment2 WHERE section_id = $section_id AND sector_id = $sector_id ORDER BY sp_id desc limit 1");

           $row_2 = $query->row();

           $new_id = intval($row_2->sp_id)+1;

        }

        return $new_id;
    }

    function last_query(){
       return $this->db->last_query();
    }

    function get_equipment(){

      if(in_array($this->session->userdata('sec_code'), array('COM', 'NAV', 'ELC', 'SUR')))

        
          return $this->dropdown_by('equipment_id', 'equipment',array('section_id' =>$this->session->userdata('section_id')));

      else

        return   $this->dropdown('equipment');

       
     
     }

}
