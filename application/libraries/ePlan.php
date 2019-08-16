<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */

class Plan_Modeler{

    protected $where;
    // All model loaded here
    protected $plan;
    
    protected $employee;
    
    protected $plan_detail;
    
    protected $plan_incharge;

    protected $section_model;
    
    protected $employee_model;

    protected $equipment;

    protected $input;

    protected $session;

    protected $section_id;

    protected $Obj;

    //load all model_driver here
    function init_plan() {

        $CI = &get_instance ();

        $this->Obj=$CI;

        $CI->load->helper('form');

        $CI->load->helper('url');

        $CI->load->model ( 'plan_model');

        $this->plan = new plan_model();

        $CI->load->model ( 'employee_model');

        $this->employee = new employee_model();

        $CI->load->model ( 'plan_detail_model');

        $this->plan_detail = new plan_detail_model();

        $CI->load->model ( 'plan_incharge_model');

        $this->plan_incharge = new plan_incharge_model();

        $CI->load->library('session');

        $this->session=$CI->session;

        $this->section_id = $CI->session->userdata('section_id');

        $this->input = $CI->input;

        $CI->load->model ( 'section_model' );
        $this->section_model = new section_model ();

        $CI->load->model ( 'equipment_model' );
        $this->equipment = new equipment_model ();

    }

    protected function init_grid(){
        
        $this->page = $this->input->get_post('page');
        
        $this->limit = $this->input->get_post('rows');
        
        $this->sidx = $this->input->get_post('sidx');
        
        $this->sord = $this->input->get_post('sord');
       
        $this->filters = $this->input->get_post('filters');
	    
        $this->search = $this->input->get_post('_search');
    }

    protected function check_filter(){
	   if(($this->search=='true')&&($this->filters != "")){
	      return $this->filter($this->filters);
	   }else
	   	 return '';
    }

    // grid filter function here
    protected function filter($filters) {
        $db = get_instance()->db->conn_id;
        $filters = json_decode ( $filters );
        $where = " where ";
        $whereArray = array ();
        $rules = $filters->rules;
        $groupOperation = $filters->groupOp;
        foreach ( $rules as $rule ) {
            $fieldName = $rule->field;
            $fieldData = mysqli_real_escape_string ($db, $rule->data );
            switch ($rule->op) {
                case "eq" :
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                case "ne" :
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                case "lt" :
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                case "gt" :
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                case "le" :
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                case "ge" :
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                case "nu" :
                        $fieldOperation = " = ''";
                        break;
                case "nn" :
                        $fieldOperation = " != ''";
                        break;
                case "in" :
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                case "ni" :
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                case "bw" :
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                case "bn" :
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                case "ew" :
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                case "en" :
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                case "cn" :
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                case "nc" :
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                default :
                        $fieldOperation = "";
                        break;
            }
            if ($fieldOperation != "")
                    $whereArray [] = $fieldName . $fieldOperation;
        }
        if (count ( $whereArray ) > 0) {
                $this->where .= join ( " " . $groupOperation . " ", $whereArray );
        } else {
                $this->where = "";
        }
        return $this->where;
    }

    // Set Count
    protected function set_count(){
        if(strlen($this->where)>1){
           $num_rows= $this->plan->get_count($this->where);
        }else{
           $num_rows= $this->plan->count_all();
        }
        if($num_rows>0){
           $this->count=$num_rows;
        } else $this->count=0;
    }

    // grid page here
    protected function set_page(){
       if($this->page > $this->total_pages)
       $this->page=$this->total_pages;
    }

    //grid total pages here
    protected function set_total_page() {
        if( $this->count > 0 )
           $this->total_pages =  ceil($this->count/$this->limit);
        else $this->total_pages = 0;
    }

    //herev filter hiigdsen bol
    protected function set_start() {
        $this->start =$this->limit*$this->page - $this->limit;
  	  if($this->start <0)
  	    $this->start = 0;
      }

 }

class Plan_Layout extends Plan_Modeler{
    
    private $echo_die = false;
   
    protected $view_as_string;

    private $theme_location = 'assets/apps/plan/view/';
    
    // private $upload_path = "download/plan_files/";

    // here is all database and logics
    protected function index_form(){
       // here is only grid in index form
       $data['action']=$this->action();
       return $this->theme_view ( 'grid.php', $data);
    }

    protected function get_layout(){
        if($this->echo_die){
            die ();
        }else{
            return $this->views_as_string;
            die ();
        }
    }

    protected function set_echo_die(){
        $this->echo_die = true;
    }


    // theme generator
    protected function theme_view($view, $vars = array(), $return =FALSE) {
        $vars = (is_object ( $vars )) ? get_object_vars ( $vars ) : $vars;

        $file_exists = FALSE;

        $ext = pathinfo ( $view, PATHINFO_EXTENSION );
        $file = ($ext == '') ? $view . '.php' : $view;

        $view_file = $this->theme_location;

        if (file_exists ( $view_file . $file )) {
                $path = $view_file . $file;
                $file_exists = TRUE;
        }

        if (! $file_exists) {
            throw new Exception ( 'Unable to load the requested file: ' . $file, 16 );
        }

        extract ( $vars );

        // region buffering...
        ob_start ();

        include ($path);

        $buffer = ob_get_contents ();
        @ob_end_clean ();
        // endregion

        if ($return === TRUE) {
            return $buffer;
        }

       $this->views_as_string .= $buffer;
    }

    //show grid as json here
    protected function grid(){
        @ob_end_clean ();

        $this->init_grid();

        $this->set_echo_die();

        // herev filter hiisen bval where should be set
        $this->check_filter();

        // нийт тоо
        $this->set_count();

//        //нийт хуудсыг тоог олно
        $this->set_total_page();
//
//        // hedenhuudas baigaag toolno
        $this->set_page();
//
//        //ehlen start-g togtoono
         $this->set_start();
//
         //get final result as json
            $json=$rows= array();
            $json['page']=$this->page;
            $json['total']=$this->total_pages;
            $json['records']=$this->count;

          if($this->section_id<5){
               if($this->where){
                   $this->where .= " and section.section_id = $this->section_id";
               }else
                  $this->where =" section.section_id = $this->section_id";
            }

        $rows = $this->plan->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=($this->session->userdata('role')=='ADMIN') ? $this->plan->last_query(): 'null';

        $json['rows']=$rows;

        echo json_encode($json);
    }

    protected function add(){
      //check гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->plan->validate($this->plan->validate)){

            $data = $this->plan->array_from_post(array('section_id', 'work', 'date'));

            $data['createdby_id']=$this->session->userdata('employee_id');

            $data['created_at'] =date('Y-m-d H:i:s');
            
            if ($id = $this->plan->insert($data, TRUE)){
              $return = array (
                    'status' => 'success',
                    'message' => $data['work'].' ажлыг амжилттай хадгаллаа'
              );
            }else{
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }           

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }
        echo json_encode($return);
    }

    // add detail
    protected function add_detail(){

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->plan_detail->validate($this->plan_detail->validate)){

            $data = $this->plan->array_from_post(array('plan_id', 'number', 'detail', 'completion', 'percent'));

            $data['created_at'] =date('Y-m-d H:i:s');
           
            $this->Obj->db->trans_begin();    
            
            $id = $this->plan_detail->insert($data, TRUE);

            $dtl_data =$this->get_incharge($id);

            // print_r($dtl_data);

            $this->plan_detail->insert_batch($dtl_data);

            if ($this->Obj->db->trans_status() === FALSE){
               
               $this->Obj->db->trans_rollback();
               
               $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                 );  

            }else{
                
                $this->Obj->db->trans_commit();

                $return = array (
                      'status' => 'success',
                      'message' => 'Амжилттай хадгаллаа' 
                );
            }       

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }
        echo json_encode($return);
    }

    protected function completion(){

          $dtl_id = $this->input->get_post( 'id' );

         
          $set_validation = array(
                 // removed by Ganaa
                 
                 array( 'field' => 'completion',
                        'label' => 'Гүйцэтгэл',
                        'rules' => 'required'),

                 array( 'field' => 'percent',
                        'label' => 'Биелэлт',
                        'rules' => 'required|is_numeric')                
             );  

          $this->Obj->form_validation->set_rules($set_validation);


         if ($this->Obj->form_validation->run()){

             $data = $this->plan->array_from_post(array('completion', 'percent'));

             // print_r($data);

             if($id = $this->Obj->db->update('plan_detail', $data, array('id' => $dtl_id))){

                 $return = array (
                      'status' => 'success',
                      'message' => 'Амжилттай хадгаллаа' 
                );

            }else{    

                  $return = array (
                    
                    'status' => 'failed',

                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                 );  
             }

        }else{
        
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        
        }

        echo json_encode($return);

    }

    //function expense_dtl 
   protected function grid_dtl(){
    
        $id = $this->input->get ( 'id' );
        $result = $this->Obj->db->query("SELECT A.id as id, number, detail, group_concat(B.employee separator ', ') AS reponsible,completion, percent FROM plan_detail A left join plan_incharge B ON A.id = B.detail_id
            WHERE A.plan_id = $id group by A.id, number, detail, percent;")->result();
        $json_dtl = '';
        $json_dtl .= "<?xml version='1.0' encoding='utf-8'?>";
        $json_dtl .=  "<rows>";
        // be sure to put text data in CDATA
        $count = 1;
        foreach ( $result as $row ) {
            $json_dtl .= "<row>";
            $json_dtl .= "<cell>" . $row->id . "</cell>";            
            $json_dtl .= "<cell><![CDATA[" . $row->detail . "]]></cell>";
            $json_dtl .= "<cell>" . $row->reponsible . "</cell>";
            $json_dtl .= "<cell>" . $row->completion . "</cell>";
            $json_dtl .= "<cell>" . $row->percent . "</cell>";            
            $json_dtl .= "</row>";
        }
        $json_dtl .= "</rows>";
        $count ++;

        header ( "Content-type: text/xml;charset=utf-8" );
        echo $json_dtl;
   }

    //collect data in charge table
   private function get_incharge($id){
        $dtl = $sub_data = array();

        $employee = $this->input->get_post('employee_id');            

        for ($i=0; $i <count($employee) ; $i++) { 

            $sub_data ['detail_id'] = $id;
            
            $sub_data ['employee_id'] = $employee[$i];

            $ita=$this->employee->get($employee[$i]);

            $sub_data ['employee'] = $ita->fullname;

            $sub_data ['section_id'] = $ita->section_id;
            
            $section = $this->section_model->get($ita->section_id);

            $sub_data ['section'] = $section->name;

            # code...
            array_push($dtl, $sub_data);
        }
        return $dtl;
   } 

   protected function dtl_delete(){
      
        $id = $this->input->get_post ( 'id' );


        if($this->plan_detail->delete($id)){

          //plan_id -r тухайн       
          $return = array (
                'status' => 'success',
                'message' => ' Амжилттай устгалаа'
            );

        }else        
            $return = array (
                    'status' => 'failed',
                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'

                );

        echo json_encode($return);
   }

    // filter function heree    
   protected function section_select(){

        $data = array ();

        $id =$this->input->get_post ( 'id' );  
        
        if($id)
          $data = $this->equipment->dropdown_by('equipment', 'equipment_id', array('section_id'=>$id) );
        else
          $data = $this->equipment->dropdown('equipment');

        echo json_encode($data);
   }

   protected function delete(){

        $id = $this->input->get_post ( 'id' );

        $plan = $this->plan->get($id);

        // also delete form folder 
        
        if($this->plan->delete($id)){

          $return = array (

                'status' => 'success',

                'message' => $plan->date.' үүсгэсэн ажлыг амжилттай устгалаа'
            );

        }else        

            $return = array (

                'status' => 'failed',

                'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'

            );

        echo json_encode($return);

    }

   protected function get(){
        $id = $this->input->get_post ( 'id' );

        $plan = $this->plan->get($id);

         echo json_encode($plan);
    }

    protected function get_dtl(){

        $id = $this->input->get_post ( 'id' );
        
        $plan_dtl = $this->plan_detail->get($id);

        $plan_dtl->employee = $this->plan_incharge->dropdown_by('employee_id', 'employee', array('detail_id'=>$id));

        // $this->plan_incharge()

        echo json_encode($plan_dtl);
    }


    public function action(){
        return $this->plan->get_action();
    }

    protected function edit(){

        $id = $this->input->get_post ( 'id' );

        // check if old is new same
        $data = $this->plan->array_from_post(array('section_id', 'work', 'date'));
        
        // validate plan at work

        if($this->plan->validate($data)){

            if ($this->plan->update($id, $data)){               
                
                $return = array (
                    'status' => 'success',
                    'message' =>'"'.$data['work'].'" төлөвлөгөөг амжилттай засварлалаа'

                );
               
            }else{
                    
                $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                 );
            }
        }else{
            
            $return = array (
                'status' => 'failed',
                'message' => validation_errors ( '', '<br>' ),
                'rules' =>$data
            );
        }
        
        echo json_encode($return);
    }

    protected function edit_dtl(){

        $id = $this->input->get_post ( 'id' );

        $number = $this->input->get_post ( 'number' );

        $detail = $this->input->get_post ( 'detail' );

        $completion = $this->input->get_post ( 'completion' );

        $percent = $this->input->get_post ( 'percent' );

       
        if($this->plan_detail->validate($this->plan_detail->validate)){

            $data = $this->plan->array_from_post(array('number', 'detail', 'completion', 'percent'));

            $data['updated_at'] =date('Y-m-d H:i:s');

            $this->Obj->db->trans_begin();    
            
            $new_id = $this->plan_detail->update($id, $data);
           
            $this->plan_incharge->delete_by('detail_id', $id);

            $this->plan_detail->insert_batch($this->get_incharge($id));

            if ($this->Obj->db->trans_status() === FALSE){
            
               $this->Obj->db->trans_rollback();
            
               $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                 );  
            
            }else{

                $this->Obj->db->trans_commit();
                
                $return = array (
                    'status' => 'success',
                    'message' => 'Мэдээллийг амжилттай амжилттай засварлалаа'
                    
                );    
            }   

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );

        }

        echo json_encode($return);


    }


}

class ePlan extends Plan_Layout{
    private $state = null;

    protected $is_ajax_request =FALSE;

    // grid data
    function __construct() {
        $this->set_status_url();
        $this->init_plan();
    }

    //status-g url-s avah!
    private function set_status_url() {
        $CI = &get_instance ();
        $CI->load->helper ( 'url' );
        if($CI->input->is_ajax_request()){
            $this->is_ajax_request = TRUE;
        }
        $this->state = $CI->uri->segment (3 );
        if (! $this->state)
           $this->state = 'open';

    }

    //хэрэв ajax baival yah uu?
    // don't need status
    protected function get_status(){
        return $this->state;
    }

    //init run
    function run() {

            $data = array ();

            $data ['view'] = true;

            if($this->is_ajax_request){
                $this->set_echo_die();
                header ( 'Content-type: application/json; charset=utf-8' );

                switch ($this->get_status()) {
                    case 'add':
                        // echo "hi".$this->is_ajax_request;
                        $this->add();
                        break;
                    
                    case 'add_detail':
                                                
                        $this->add_detail();

                        break;

                    case 'grid':
                        // call action here
                        $this->grid ();
                    break;             

                    case 'detail':
                        // call action here
                        $this->grid_dtl();
                    break;

                    case 'completion':
                        // call action here
                        $this->completion();
                    break;

                    case 'filter':
                        $this->section_select();
                        break;

                   case 'delete':
                        $this->delete();
                        break;           

                  case 'dtl_delete':
                        $this->dtl_delete();
                        break;

                   case 'get':
                        $this->get();
                        break;  

                    case 'get_dtl':

                        $this->get_dtl();

                        break;

                   case 'edit':
                         $this->edit();
                        break; 

                    case 'edit_dtl':

                         $this->edit_dtl();
                    
                        break;                   
                }

            }else{
                switch ($this->get_status ()) {
                
                      default : // index page loaded
                        //$data ['page'] = 'trip\index';
                        $data ['title'] = 'input utga';
                        $this->index_form();
                        break;
                }

            }
            return $this->get_layout();
    }
}
