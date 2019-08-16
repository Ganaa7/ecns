<?php


require_once('Core.php');

class Location_Layout extends Core{

    protected $location;

    private $echo_die = false;

    public function __construct()
    {
        parent::__construct();

        $this->Obj->load->model ( 'location_model' );

        $this->location = new location_model ();

        $this->Obj->load->model ( 'f_log_model' );

        $this->f_log_model = new f_log_model ();

        $this->Obj->load->model ( 'employee_model' );

        $this->employee_model = new employee_model ();

        $this->Obj->load->model ( 'device_model' );

        $this->device_model = new device_model ();
        
        $this->Obj->load->model ( 'event_model' );

        $this->event_model = new event_model ();
        
        $this->Obj->load->model ( 'trip_model' );

        $this->trip_model = new trip_model ();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/location/theme/');
        
    }
    // here is all database and logics

    protected function index_form(){

       // here is only grid in index form
       $data['action'] =$this->location->get_action();
       
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
        $this->set_count($this->location);

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

        $json['rows'] = $this->location->get_query($this->where, $this->sidx, $this->sord , $this->start, $this->limit);

        $json['sql'] = $this->equipment->last_query();

        echo json_encode($json);
    }

 
}

class Location_Crud extends Location_Layout{

     public function __construct()
    {
        parent::__construct();
        
    }

    protected function add(){
        
        //Сэлбэгийн дугаарыг бодох:

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->location->validate($this->location->validate)){

            $data = $this->location->array_from_post(array('location', 'code', 'latitude', 'longitude'));

            $data['created_at'] =date('Y-m-d H:i:s');
         
            if ($id = $this->location->insert($data, TRUE)){

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

       //  there using location_id hereglesen zuils baigaa yu?

       // flog, certificate, m_event, employee, trip
       $flog  = $this->f_log_model->get_by(array('location_id'=>$id));

       if($flog){

          $return = array (  'status' => 'failed', 'message' => 'Энэ байршил дээр гэмтэл бүртгэгдсэн тул тул устгах боломжгүй!'  );
         
          echo json_encode($return);

          exit();

       }
       
       $employee  = $this->employee_model->get_by(array('location_id'=>$id));

       if($employee){

          $return = array (  'status' => 'failed', 'message' => 'Энэ байршил дээр ИТА бүртгэгдсэн тул тул устгах боломжгүй!'  );
         
          echo json_encode($return);

          exit();

       }

       $device  = $this->device_model->get_by(array('location_id'=>$id));

       if($device){

          $return = array (  'status' => 'failed', 'message' => 'Энэ байршил дээр тоног төхөөрөмж бүртгэгдсэн тул тул устгах боломжгүй!'  );
         
          echo json_encode($return);

          exit();

       }
       
       $trip  = $this->trip_model->get_by(array('location_id'=>$id));

       if($trip){

          $return = array (  'status' => 'failed', 'message' => 'Энэ байршил дээр томилолт бүртгэгдсэн тул тул устгах боломжгүй!'  );
         
          echo json_encode($return);

          exit();

       }
       
       $event  = $this->event_model->get_by(array('location_id'=>$id));

       if($event){

          $return = array (  'status' => 'failed', 'message' => 'Энэ байршил дээр Техник үйлчилгээ бүртгэгдсэн тул тул устгах боломжгүй!'  );
         
          echo json_encode($return);

          exit();

       }
              
       $location = $this->location->get($id);
      
       if($location){

          if($this->location->delete($id)){

             $return = array (
                'status' => 'success',
                'message' => '"'.$location->location.'"-г амжилттай устгалаа'
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

       $location = $this->location->with('section')->get($id);

       echo json_encode($location);
    }

    protected function edit(){

        $id = $this->input->get_post ( 'location_id' );

        // check if old is new same
        $data = $this->location->array_from_post(array('location', 'code', 'latitude', 'longitude'));
 
        if($this->location->validate($data)){

            if ($this->location->update($id, $data)){    
                
                $return = array (
                    'status' => 'success',
                    'message' =>'"'.$data['location'].'" байршлийн мэдээллийг амжилттай засварлалаа'

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

        echo json_encode($data);
    }
      

}

class Location_Module extends Location_Crud{

    protected $where;

    private $state = null;

    protected $is_ajax_request =FALSE;

    // grid data
    function __construct() {

        parent::__construct();

        $this->set_status_url();
    }

    //status-g url-s avah!
    private function set_status_url() {

        $CI = &get_instance ();

        $CI->load->helper ( 'url' );

        if($CI->input->is_ajax_request()){
            
            $this->is_ajax_request = TRUE;
        }

        $this->state = $CI->uri->segment (4);

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
