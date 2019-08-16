<?php


require_once('Core.php');

class Employee_Layout extends Core{

    protected $employee;

    private $echo_die = false;

    public function __construct()
    {
        parent::__construct();

        $this->Obj->load->model ( 'employee_model' );
        
        $this->employee = new Employee_model ();

        $this->Obj->load->model ( 'section_position_model' );

        $this->sec_pos = new Section_position_model ();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/employee/theme/');
        
    }
    // here is all database and logics

    protected function index_form(){

       // here is only grid in index form
       $data['action'] =$this->employee->get_action();
       
       return $this->theme_view ( $data );

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

    //show grid as json here
    protected function grid(){
      
        @ob_end_clean ();

        $this->init_grid();

        $this->set_echo_die();

        // herev filter hiisen bval where should be set
        $this->check_filter();

        // нийт тоо
        $this->set_count($this->employee);

        //нийт хуудсыг тоог олно
        $this->set_total_page();

        // hedenhuudas baigaag toolno
        $this->set_page();

        //ehlen start-g togtoono
        $this->set_start();

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

        $json['rows'] = $this->employee->get_query($this->where, $this->sidx, $this->sord , $this->start, $this->limit);

        $json['sql'] = $this->equipment->last_query();

        echo json_encode($json);
    }

 
}

class Employee_Crud extends Employee_Layout{

     public function __construct()
    {
        parent::__construct();
        
    }

    protected function add(){
        
        //Сэлбэгийн дугаарыг бодох:

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->employee->validate($this->employee->validate)){

            $data = $this->employee->array_from_post(array('section_id', 'sector_id', 'position_id', 'first_name', 'last_name', 'username', 'password'));

            $data['created_at'] =date('Y-m-d H:i:s');

            $data['password'] = $this->employee->hash_2($data['password']);

            $data['fullname'] = $data['first_name'].'.'.substr($data['last_name'], 0, 4);
            
            $data['email'] = $data['username'].'@mcaa.gov.mn';
           
            if ($id = $this->employee->insert($data, TRUE)){

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

    protected function delete(){

       $id = $this->input->get_post ( 'id' );

       $employee = $this->employee->get($id);
      
       if($employee){

          if($this->employee->delete($id)){

             $return = array (
                'status' => 'success',
                'message' => '"'.$employee->fullname.'" амжилттай устгалаа'
             );

           }else{

              $return = array (
                   
                  'status' => 'failed',
                  
                  'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'.$this->Obj->db->_error_message()
                  
              );
              
            }

       }
       
       echo json_encode($return);
    }

    protected function get(){

       $id = $this->input->get_post ( 'id' );

       $employee = $this->employee->with('section')->get($id);

       echo json_encode($employee);
    }

    protected function edit(){

        $id = $this->input->get_post ( 'employee_id' );

        // check if old is new same
        $data = $this->employee->array_from_post(array('section_id', 'sector_id', 'position_id', 'first_name', 'last_name', 'password'));

        unset($this->employee->validate[5]);

        if($this->input->get_post('is_change')<>1){

            unset($data['password']);

            unset($data['re_password']);

            unset($this->employee->validate[6]);

            unset($this->employee->validate[7]);
        }
      
        if($this->employee->validate($data)){

            $data['fullname'] = trim($data['first_name']).'.'.substr($data['last_name'], 0, 4);

            if($this->input->get_post('is_change')==1){
                
                $data['password'] = $this->employee->hash_2($data['password']);
            }

            if ($this->Obj->db->update('employee', $data, array('employee_id' => $id))){ 
                
                  $return = array (
                    'status' => 'success',
                    'message' =>'"'.$data['first_name'].'" ажилтны мэдээллийг амжилттай засварлалаа'

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

     // filter function heree    
    protected function section_select(){

        $data = array ();

        $id =$this->input->get_post ( 'id' );  
        
        if($id)

          $data = $this->sector->dropdown_by('sector_id', 'name', array('section_id'=>$id) );
          
        else

          $data = $this->sector->dropdown('name');

        //position-g haana nemeh u?

        if(count($data)==0){
            
            $data= array(0 => 'Тасаг байхгүй');
        }

        echo json_encode($data);
    }

     // filter function heree    
    protected function position_select(){

        $data = array ();

        $id =$this->input->get_post ( 'id' );  
        
        if($id)

          $data = $this->sec_pos->ext_dropdown('position_id', 'name', null, array('section_id'=>$id) );
          
        else

          $data = $this->sec_pos->dropdown('position');

        //position-g haana nemeh u?

        echo json_encode($data);
    }
      

}

class Employee_Module extends Employee_Crud{

    protected $where;

    private $state = null;

    protected $is_ajax_request =FALSE;

    // grid data
    function __construct() {
        parent::__construct();
        $this->set_status_url();
        // $this->init_library();
    }

    //status-g url-s avah!
    private function set_status_url() {

        $CI = &get_instance ();

        $CI->load->helper ( 'url' );

        if($CI->input->is_ajax_request()){
            
            $this->is_ajax_request = TRUE;
        }

        $this->state = $CI->uri->segment (3);

        if (! $this->state)

           $this->state = 'open';
       
    }

    //хэрэв ajax baival yah uu?

    // don't need status
    protected function get_status(){
        return $this->state;
    }

    function run() {
            // check state add, edit, delete
            
            $data = array ();
            $data ['view'] = true;

            if($this->is_ajax_request){

                $this->set_echo_die();

                header ( 'Content-type: application/json; charset=utf-8' );

                switch ($this->get_status ()) {

                    case 'grid' :

                        $this->grid ();

                        break;

                    case 'add' :

                        $this->add ();  

                        break;
                    
                    case 'get' :

                        $this->get ();   

                        break; 

                    case 'edit' :

                        $this->edit();                            

                        break;

                    case 'delete' :

                        $this->delete();                            
                        break;

                    case 'filter' :

                          $this->section_select();                            
                          break;

                    case 'filter_pos' :

                          $this->position_select();                            
                          break;

                
    	       }

              }else{

                  switch ($this->get_status ()) {

                    default : // index page loaded
                            $this->index_form();
                            break;
                       }

                 }
                 return $this->get_layout();

    }
}
