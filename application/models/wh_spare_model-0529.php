<?php

class wh_spare_model extends MY_Model {

    public $_table = 'wh_spare'; // you MUST mention the table name
    
    public $primary_key = 'id'; // you MUST mention the primary key
    
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    
    public $after_dropdown = array('options');

    protected function options($data){

       $data['0'] = 'Сэлбэгээс нэгийг сонго';

       ksort($data);
      
       return $data;
    }

    
    public $protected = array(); // ...Or 

    private $fields  = array('spare_id', 'spare', 'sparetype', 'equipment', 'section', 'sector', 'part_number', 'measure', 'manufacture');
    
   
    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');        
        parent::__construct();            
    }   
    
    public $validate = array(
        // removed by Ganaa 
        // array( 'field' => 'spare_id', 
        //        'label' => 'Сэлбэг дугаар',
        //        'rules' => 'required|is_unique[wh_spare.id]'),
        
        array( 'field' => 'spare', 
               'label' => 'Сэлбэг',
               'rules' => 'required'),
        
        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|is_natural_no_zero'),
        
        array( 'field' => 'sector_id',
               'label' => 'Тасаг',
               'rules' => 'required|is_natural_no_zero'),
        
        array( 'field' => 'equipment_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required|is_natural_no_zero'),
        
        array( 'field' => 'part_number',
               'label' => 'Парт №',
               'rules' => 'required' ),
        array( 'field' => 'type_id',
               'label' => 'Сэлбэгийн төрөл',
              'rules' => 'required|is_natural_no_zero'),
        array( 'field' => 'measure_id',
               'label' => 'Хэмжих нэгж',
               'rules' => 'required|is_natural_no_zero'),        
        array( 'field' => 'manufacture_id',
               'label' => 'Үйлдвэрлэгч',
               'rules' => 'required|is_natural_no_zero')
         
         // array( 'field' => 'required_qty',
         //       'label' => 'Ашиглалтад байгаа тоо',
         //       'rules' => 'required')
    ); 
   

    function get_action() {
       $role = $this->session->userdata ( 'role' );      

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='wh_spare' and controller='settings'" )->result ();
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
        $this->db->select("wh_spare.id as spare_id, section, sector.name as sector, equipment, wh_spare.spare as spare, sparetype, part_number, measure, manufacture"); 
        $this->db->from($this->_table);
        $this->db->join('equipment2', "equipment2.sp_id = $this->_table.equipment_id and equipment2.section_id = $this->_table.section_id", 'left');                 
        $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');                 
        $this->db->join('sector', "sector.sector_id = $this->_table.sector_id", 'left');                 
        $this->db->join('wh_sparetype', "wh_sparetype.id = $this->_table.type_id", 'left');                 
        $this->db->join('wm_measures', "wm_measures.measure_id = $this->_table.measure_id", 'left');                 
        $this->db->join('wm_manufacture', "wm_manufacture.manufacture_id = $this->_table.manufacture_id", 'left');                 
        
        if ($where)          
           $this->db->where($where, NULL, FALSE);
         
         $this->db->group_by('spare_id'); 
                
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

    function update_qty($id, $qty){
        $this->db->where('id', $id);
        $this->db->update($this->_table,array('required_qty'=>$qty));
        return true;
    }
      

    function check_spare($spare_id){
       $this->db->select('*');
       $this->db->where(array('spare_id' =>$spare_id));
       $query = $this->db->get('wh_invoice_dtl');           
       if ($query->num_rows () > 0) return false;
        else return true;       
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


    function exec_query($sql){

        $new_query = $this->db->query ( $sql );

        return $new_query;
    }

    
    function get_count($where){

       if(strlen($where)>1){
           
           $num_rows= $this->wh_spare->count_all($where);      

        }else{

           $num_rows= $this->wh_spare->count_all();      

        }  

        if($num_rows>0)

           return $num_rows;
        else 

           return 0;
    }


}
	