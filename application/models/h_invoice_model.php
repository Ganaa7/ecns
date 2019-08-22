<?php

class h_invoice_model extends MY_Model {

    public $_table = 'h_invoice'; // you MUST mention the table name    

    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $before_dropdown;
    public $protected = array(); // ...Or 
    
    private $fields  = array('spare_id','section', 'equipment', 'spare', 'part_number', 'aqty', 'using_qty', 'need_qty', 'qty', 'measure');
    // ямар ямар утгуудаар Гридийг харуулах вэ?
    //Хэсэг, төхөөрөмж, сэлбэг нэр, тоо ширхэг, огноо!
    public $belongs_to = array( 'h_invoice_dtl' => array( 'primary_key' => 'invoie_id', 'model'=>'h_spare_dtl' ) ); 
    public $validate = array(
        array( 'field' => 'id', 
               'label' => '№',
               'rules' => 'required|is_unique[h_invoice.id]' ),
        array( 'field' => 'section',
               'label' => 'Хэсэг',
               'rules' => 'required'),
        array( 'field' => 'equipment',
               'label' => 'Төхөөрөмж',
               'rules' => 'required' ),
        array( 'field' => 'spare',
               'label' => 'Сэлбэг',
               'rules' => 'required|numeric|trim' ),        
        array( 'field' => 'qty',
               'label' => 'Бэлэн',
               'rules' => 'required' ),
        array( 'field' => 'measure',
               'label' => 'Хэмжих нэгж',
               'rules' => 'required' )        
    ); 
          
    function __construct() {      
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
    // үлдэгдлийг энд харуулна
    function get_query($where = null, $sidx = null, $sord = null, $start = null, $limit = null) {        
        $my_arr = $crow = array();
        //IFNULL(vw_general_ledger.qty,0) 
        $this->db->select("_wh_vw_spare.spare_id, _wh_vw_spare.section, _wh_vw_spare.equipment, _wh_vw_spare.spare,_wh_vw_spare.part_number, vw_general_ledger.qty as aqty, using_qty, need_qty, detail.qty as qty, measure"); 
        $this->db->from("_wh_vw_spare");
        //$this->db->join('h_invoice_dtl', "_wh_vw_spare.spare_id = h_invoice_dtl.spare_id", 'left');               

        $this->db->join('(SELECT spare_id, sum(qty) as qty FROM h_invoice_dtl GROUP BY spare_id )  as detail', "detail.spare_id = _wh_vw_spare.spare_id", 'left');

        $this->db->join("vw_general_ledger", "vw_general_ledger.spare_id = _wh_vw_spare.spare_id", 'left');           
        $this->db->join("h_store", "_wh_vw_spare.spare_id = h_store.spare_id", 'left');                        
        //тухайн сэлбэгээр агуулахд байгаа одоогийн огноогоор сэлбэгийг харуулна
        //$this->db->join("_wh_vw_spare", "h_invoice_dtl.spare_id = _wh_vw_spare.spare_id", 'left');
        if ($where)          
           $this->db->where($where, NULL, FALSE);
         $this->db->group_by('_wh_vw_spare.spare_id'); 
                
        if ($sidx && $sord) {                
           $this->db->order_by($sidx, $sord);
        }
        if ($limit)         
           $this->db->limit($limit, $start);
        
        $result = $this->db->get()->result();
        
        $i = 0;
        if($result){
            foreach ($result as $rows){
               foreach ($this->fields as $field) {
                 if($field=='aqty'||$field=='qty')                  
                    $crow[$field] = ($rows->$field==null)? 0:$rows->$field;
                  else
                    $crow[$field] = $rows->$field;
               }
               $my_arr[$i] = (object)$crow;
               $i++;           
            }      
        }  
        return $my_arr;    
    } 
    
    function get_count($where = null) {            

        $this->db->from("_wh_vw_spare");      
        
        if(strlen($where)>1){
        
            $this->db->join('h_invoice_dtl', "_wh_vw_spare.spare_id = h_invoice_dtl.spare_id", 'left');                
            
            $this->db->join("vw_general_ledger", "vw_general_ledger.spare_id = h_invoice_dtl.spare_id", 'left'); 
                                   
            $this->db->where($where, NULL, FALSE);

            $num_rows = $this->db->count_all_results();        
        }else{

            $this->db->join('h_invoice_dtl', "_wh_vw_spare.spare_id = h_invoice_dtl.spare_id", 'left');                
            
            $this->db->join("vw_general_ledger", "vw_general_ledger.spare_id = h_invoice_dtl.spare_id", 'left');

            $num_rows = $this->db->count_all_results();        
        }

        return ($num_rows >0) ? $num_rows : 0;
    }

    function count_all(){

        $this->db->from("_wh_vw_spare");      

        $this->db->join('h_invoice_dtl', "_wh_vw_spare.spare_id = h_invoice_dtl.spare_id", 'left');                
            
        $this->db->join("vw_general_ledger", "vw_general_ledger.spare_id = h_invoice_dtl.spare_id", 'left');

        return $this->db->count_all_results();     

    }


    function insert_batch($data){
       $this->db->insert_batch($this->_table_dtl, $data);
    }
    
    function get_action() {
       $role = $this->session->userdata ( 'role' );      

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='wh_spare' and controller='h_spare'" )->result ();
       foreach ( $result as $row ) {
           $result_array [$row->functions] = $row->functions;
       }
       //echo $this->db->last_query();
       return $result_array;
    } 

  
}
	