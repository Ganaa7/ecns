<?php

/*
 * @Ganaa developer
 * 2017-12-15
 * Trainer part of train
 */

require_once('Core.php');


class Guidance_Loader extends Core
{

    protected $guidance;
    
    protected $file;
    
    function __construct()
    {

        parent::__construct();

        $this->Obj->load->model ( 'guidance_model' );

        $this->guidance = new guidance_model ();       

        $this->Obj->load->model ( 'Gfile_model' );

        $this->file = new Gfile_model ();        

    }

}

class Guidance_Layout extends Guidance_Loader{

    private $echo_die = false;

    protected $view_as_string;

    protected $upload_path = "download/guidance_file/";

    function __construct(){

        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/guidance/view/');    

    }

      
    // here is all database and logics    
    protected function index_form(){
       
       $data['action'] = $this->guidance->get_action();

       return $this->theme_view ($data);        

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
    
    //call all hutulbur grid here
    protected function grid(){

        @ob_end_clean ();
        
        $this->init_grid();
        
        $this->set_echo_die();        
                
        // herev filter hiisen bval where should be set
        $this->check_filter();
                
        // нийт тоо 
        $this->set_count($this->guidance);                
        
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
        
        $rows = $this->guidance->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=$this->guidance->last_query();  

        $json['rows']=$rows;

        echo json_encode($json);                

    }

}


class Guidance_Crud extends Guidance_Layout
{
    
    function __construct()
    {
        parent::__construct();
    }

    protected function filterby(){

        $section_id = $this->input->get_post('id');
            
        $dropdown_array = $this->equipment->dropdown_by( 'equipment_id', 'equipment', array('section_id'=>$section_id));

        //echo $this->guidance->last_query();

        echo json_encode($dropdown_array);

    }
      
    protected function add(){ 

         //гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!        

        if($this->guidance->validate($this->guidance->validate)){ 

            $data = $this->guidance->array_from_post(array('number', 'section_id', 'equipment_id', 'guidance', 'location', 'hours', 'minute', 'date', 'file_id')); 

            $data['createdby']=$this->session->userdata('fullname');

            $data['created_at'] =date('Y-m-d H:i:s');

            if ($id=$this->guidance->insert($data) === FALSE){

               $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                 ); 

            }else{

               $return = array (
                      'status' => 'success',
                      'message' => $data['guidance'].' хөтөлбөрийг амжилттай хадгаллаа' 
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

    //Upload  File here
        
    protected function upload() {
        // update hiihed user_id -g update hiine

        $file_name = $_FILES ['userfile'] ['name'];

        $id = $this->input->get_post('cert_id');

        $is_cyrilic = ( bool ) preg_match ( '/[\p{Cyrillic}]/u', $file_name );

        $is_latin = ( bool ) preg_match ( '/[{ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ}]/u', $file_name );
        
        if ($is_cyrilic) {

            $json = array (
                    'status' => 'error',
                    'message' => 'Файлын нэрийг Криллээр бичсэн байна! Файлын нэрийг Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
            );

        } elseif ($is_latin) {

            $json = array (
                    'status' => 'error',
                    'message' => 'Файлын нэр танигдахгүй байна! Файлын нэрээ Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
            );

        } else {

            $f_name = $this->key_gen ( 5 ) . '-' . $file_name;
            // config here
            $config = array (
                    'allowed_types' => 'pdf',
                    'upload_path' => $this->upload_path,
                    'max_size' => 100000,
                    'file_name' => $f_name 
            );
            
            $CI = &get_instance ();

            $CI->load->library ( 'upload', $config ); 

            if ($CI->upload->do_upload ()) {

                // collect uploaded data
                $f_data = $CI->upload->data ();

                $json = array (
                        'name' => $f_data ['file_name'],
                        'size' => $f_data ['file_size'],
                        'type' => $f_data ['file_type'],
                        'delete_url' => $this->upload_path . $f_name,                        
                );

                // update current cert file by id
                
                if ($file_id = $this->file->insert(array (  'file_name' => $f_data ['file_name']), array ('guidance_id' => $this->guidance->get_next_id()) )) {   
                                 
                    $json ['status'] = 'success';
                    $json ['file_id'] = $file_id;
                } else {
                    $json ['status'] = 'failed';
                    $json ['message'] = 'Өгөгдлийн санд алдаа гарлаа!';
                }
            } else {
                $json = array (
                        'status' => 'error',
                        'message' => $CI->upload->display_errors ( '', '' ) 
                );
            }
        }
        echo json_encode($json);
    }

    protected function delete_file() {        

        $file_name = $this->input->get_post ( 'file_name' );

        
        //var_dump($_SERVER['DOCUMENT_ROOT']."/download/guidance_file/");

        if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/download/guidance_file/" . $file_name )) {

            // file is unlicked?
            if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . "/download/guidance_file/" . $file_name )) {

                // guidance-s ustgahaar                                 
                if($delete_id = $this->file->delete_by( array('file_name'=>$file_name)))

                    $json = array (                       

                       'status' => 'success',

                       'message' => '[' . $file_name . '] амжилттай устгалаа!' 

                    );
                else
                     $json = array (                       

                       'status' => 'failed',

                       'message' => 'Өгөгдлийн санд алдаа гарлаа' 

                    );

            } else

                $json = array (
                        'status' => 'failed',
                        'message' => '[' . $file_name . '] устгахад алдаа гарлаа!' 
                );

        } else {
            // check if this file exists in the db?
            // update the row
            if($delete_id = $this->file->delete_by( array('file_name'=>$file_name)))            
            
            $json = array (

                'status' => 'failed',

                'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!' 

            );
        }
        
        echo json_encode($json);
    }
    
    protected function delete(){

        $id = $this->input->get_post ( 'id' );

        $guidance = $this->guidance->get($id);

        // $file_name = $guidance->file_name;

        // $file_id = $guidance->file_id;
        $file=$this->file->get($guidance->file_id);
        
        if($guidance_id = $this->guidance->delete($id)){

            if($file){

                if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/download/guidance_file/" . $file->file_name )) {

                    //file moved to the tash folder
                    rename($_SERVER ['DOCUMENT_ROOT'] . "/download/guidance_file/" . $file->file_name , 
                    $_SERVER ['DOCUMENT_ROOT'] . "/download/guidance_file/trash/" . $file->file_name);

                  }
                  $this->file->delete($file->file_id);            
            }            

            $return = array (

               'status' => 'success',

               'message' => $guidance->guidance.' хөтөлбөрийг амжилттай устгалаа'                

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

        $guidance = $this->guidance->with('Gfile')->get($id);

        $equipment = $this->equipment->get_by(array('equipment_id' =>$guidance->equipment_id));

        $guidance->equipment=$this->equipment->dropdown_by( 'equipment_id', 'equipment', array('section_id'=>$equipment->section_id));
         
        
         $return = array (
                'json' => $guidance

                // 'equipment' => $drop_down_equipment
            );
       
        echo json_encode($return);
    }

    protected function edit(){     

        $id = $this->input->get_post ( 'id' );
        //get olld trip id 
        $guidance = $this->guidance->get($id);

        // check if old is new same        
        
        $data = $this->guidance->array_from_post(array('id', 'section_id', 'number', 'equipment_id', 'guidance', 'location', 'hours', 'minute', 'date', 'file_id'));      
        //$rules = $this->trip_model->validate;        

        $this->guidance->validate[0]['rules'] = 'required';            
       
        if($this->guidance->validate($data)){ 
            
            if ($id = $this->guidance->update($id, $data)){               
                $msg = array (
                    'status' => 'success',
                    'message' =>$guidance->id .' дугаартай хөтөлбөрийг амжилттай засварлалаа'
                    
                ); 
               
            }else{
                $msg = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                 );  
               
                      
            }                   
        }else{
            $msg = array (
                'status' => 'failed',
                'message' => validation_errors ( '', '<br>' ), 
                'rules' =>$data
            );
        }  
            

        echo json_encode($msg);
    }

}

class Guidance_Module extends Guidance_Crud{

    private $state = null;

    protected $is_ajax_request =FALSE;
    
 
    
    // grid data 
    function __construct() {

        $this->set_status_url();    

        parent::__construct();

        // $this->init_library();
    }
    
    //status-g url-s avah!
    private function set_status_url() {

        $CI = &get_instance ();

        $CI->load->helper ( 'url' );

        if($CI->input->is_ajax_request()){   

            $this->is_ajax_request = TRUE;   

        }
        $this->state = $CI->uri->segment ( 4 );  

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

                    case 'grid':                        
                        // call action here

                        $this->grid ();  

                    break;

                    // subgrid
                    case 'sub_grid':      

                        $this->sub_grid();
                        // call action here                        
                    break;

                    case 'filter':

                        $this->filterby();

                        break;

                   case 'delete':

                        $this->delete();

                        break;

                   case 'get':

                        $this->get();

                        break;        

                   case 'edit':

                        $this->edit();    

                        break;   

                   case 'upload':

                        $this->upload();    

                        break; 

                   case 'delete_file':

                        $this->delete_file();    

                        break;

                   default:
                        # code...
                        break;
                }
            }else{

                switch ($this->get_status ()) {               
                
                // create action

                    case 'create':                                            

                        $this->create_form();

                        break;

           
                    case 'delete' :

                        $return = $this->delete ();

                        $data ['json'] = json_encode ( $return );

                        $data ['view'] = false;

                        return ( object ) $data;

                        break;

                       
                    default : // index page loaded
                            
                        $data ['title'] = 'Хэрөлборыйдөйбы';                        

                        $this->index_form();                                                    

                        break;
                   }  

            }    
            return $this->get_layout();
    }
}