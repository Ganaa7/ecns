<?php

class Plan_model extends MY_Model {

    public $_table = 'plan'; // you MUST mention the table name

    public $primary_key = 'id'; // you MUST mention the primary key

    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    
    public $before_dropdown;

    // public $belongs_to = array( 'equipment');
    public $has_many = array( 'plan_detail' => array( 'model' => 'plan_detail_model' ) );

    private $fields  = array('id', 'work', 'date', 'section', 'detail', 'completion', 'percent');

    function __construct() {
        // $this->validation->set_message('numeric', 'The {field} field can not be the word "test"');
        parent::__construct();
    }

    public $before_create = array( 'timestamps' );
    
    protected function timestamps($book)
    {
        $book['created_at'] = $book['updated_at'] = date('Y-m-d H:i:s');
        return $book;
    }

    public $validate = array(
        // removed by Ganaa
        // array( 'field' => 'spare_id',
        //        'label' => 'Сэлбэг дугаар',
        //        'rules' => 'required|is_unique[wh_spare.id]'),

        array( 'field' => 'work',
               'label' => 'Төлөвлөсөн ажил',
               'rules' => 'required'),

        array( 'field' => 'date',
               'label' => 'Гүйцэтгэх хугацаа',
               'rules' => 'required'),

        array( 'field' => 'section_id',
               'label' => 'Хэсэг',
               'rules' => 'required|is_natural_no_zero')
       
        // array( 'field' => 'comment',
        //        'label' => 'Биелэлт',
        //        'rules' => 'required')

    );


    function get_action() {
       $role = $this->session->userdata ( 'role' );

       $result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='plan' and controller='plan'" )->result ();
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
        $this->db->select("plan.id, section, work, group_concat(plan_detail.detail separator ', ') AS detail, 
                            date, FORMAT(sum(plan_detail.percent)/count(plan_detail.percent), 1) AS percent,
                            group_concat(plan_detail.completion separator ', ') AS completion", FALSE);
        $this->db->from($this->_table);        
        $this->db->join('plan_detail', "plan_detail.plan_id = $this->_table.id", 'left');
        $this->db->join('section', "section.section_id = plan.section_id", 'left');
        $this->db->group_by('plan.id, section, work, date');

        if ($where)
           $this->db->where($where, NULL, FALSE);

         // $this->db->group_by('id');

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

  


}
