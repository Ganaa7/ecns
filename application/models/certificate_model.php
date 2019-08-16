<?php

class certificate_model extends MY_Model {

    public $_table = 'certificate'; // you MUST mention the table name

    public $primary_key = 'id'; // you MUST mention the primary key

    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    
    public $before_dropdown;

    public $protected = array(); // ...Or

    public $belongs_to = array( 'equipment', 'location', 'device');    

    private $fields  = array('id', 'cert_no', 'location', 'equipment', 'specs', 'section_id', 'equipment_id', 'serial_no_year', 'issueddate', 'validdate', 'cert_file');

    private $validdate;

    public $after_dropdown = array('options');

    protected function options($data){

       $data['0'] = 'Нэг утгийг сонго';

       ksort($data);

       return $data;
    }
   

    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');
        parent::__construct();
        
        $this->validdate = $this->input->get_post('validdate');
    }

    public $before_create = array( 'timestamps' );
    
    protected function timestamps($book)
    {
        $book['updated_at'] = date('Y-m-d H:i:s');
        return $book;
    }

    public $validate = array(
        // removed by Ganaa
        // array( 'field' => 'spare_id',
        //        'label' => 'Сэлбэг дугаар',
        //        'rules' => 'required|is_unique[wh_spare.id]'),


        array( 'field' => 'cert_no',
               'label' => 'Гэрчилгээ #',
               'rules' => 'required'),

        array( 'field' => 'location_id',
               'label' => 'Байршил',
               'rules' => 'required'),

        array( 'field' => 'specs',
               'label' => 'Тодорхойлолт',
               'rules' => 'required'),

        array( 'field' => 'equipment_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'serial_no_year',
               'label' => 'Сериал но',
               'rules' => 'required'),

        array( 'field' => 'issueddate',
               'label' => 'Олгосон огноо',
               'rules' => 'trim|required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|callback_compare_date'),

        array( 'field' => 'validdate',
               'label' => 'Хүчинтэй огноо',
               'rules' => 'trim|required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|callback_compare_date'),

        array( 'field' => 'cert_file',
               'label' => 'Гэрчилгээ файл',
               'rules' => 'required')
    );


    function compare_date() {

      $in = $this->form_inputs ();

      $form_validation = $this->form_validation ();

      $start_date = strtotime ( $this->input->get_post ( 'issueddate' ) );
      
      $end_date = strtotime ( $this->input->get_post ( 'validdate' ) );
      
      if ($end_date > $start_date)
        return True;
      else {
        $form_validation->set_message ( 'validdate', '%s should be greater than Contract Start Date.' );
        return False;
      }
    }


    function get_action() {
       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='certificate' and controller='certificate'" )->result ();

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

        //   LEFT JOIN `location` `b` ON ((`a`.`location_id` = `b`.`location_id`)))
        // LEFT JOIN `equipment2` `d` ON ((`a`.`equipment_id` = `d`.`equipment_id`)))
        // LEFT JOIN `section` `e` ON ((`d`.`section_id` = `e`.`section_id`)))

        $this->db->select("$this->_table.id, cert_no, equipment2.equipment, location.location_id, location.name location, specs,  equipment2.equipment_id, intend, serial_no_year, issueddate, validdate, comment, cert_file, section.section_id, section");

        $this->db->from($this->_table);
        
        $this->db->join('location', "location.location_id = $this->_table.location_id", 'left');
        
        $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'left');
        
        $this->db->join('section', "section.section_id = equipment2.section_id", 'left');
        
        if ($where)
           $this->db->where($where, NULL, FALSE);

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

        $this->db->select("COUNT(*) as count ");
         
        $this->db->from($this->_table);

        $this->db->join('location', "location.location_id = $this->_table.location_id", 'left');
        
        $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'left');
        
        $this->db->join('section', "section.section_id = equipment2.section_id", 'left');

        if ($where)
          
           $this->db->where($where, NULL, FALSE);

         $query = $this->db->get();

        if($query->num_rows()>0){

           $row = $query->row();

           return $row->count;

        }else  return 0;

    }

    function ext_dropdown($key_col, $column, $filter=null, $where =null) { // , $join_table =null, $join_id = null
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


    


}
