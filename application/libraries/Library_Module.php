<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */
require_once('Core.php');


class Library_Layout extends Core{

    public function __construct(){

        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/library/view/');

    }

    private $echo_die = false;
    // protected $view_as_string;

    // private $theme_location = 'assets/apps/library/view/';
    // private $upload_path = "download/library_files/";

    // here is all database and logics
    protected function index_form(){
       // here is only grid in index form
       $data['action']=$this->library->get_action();

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

    //show grid as json here
    protected function grid(){
        @ob_end_clean ();

        $this->init_grid();

        $this->set_echo_die();

        // herev filter hiisen bval where should be set
        $this->check_filter();

        // нийт тоо
        $this->set_count($this->library);

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

        $rows = $this->library->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=($this->session->userdata('role')=='ADMIN') ? $this->library->last_query(): 'null';

        $json['rows']=$rows;

        echo json_encode($json);
    }

}

class Library_Crud extends Library_Layout{

    function __construct(){

        parent::__construct();
    }

       protected function add(){
      //check гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->library->validate($this->library->validate)){

            $data = $this->library->array_from_post(array('title', 'author', 'year_of_pub', 'isbn', 'equipment_id', 'ebook'));

            $data['createdby_id']=$this->session->userdata('employee_id');

            $data['created_at'] =date('Y-m-d H:i:s');

            
            if ($id = $this->library->insert($data, TRUE)){
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


    //file upload here
    protected function upload() {
        // update hiihed user_id -g update hiine
        $file_name = $_FILES ['userfile']['name'];
       
        $file_name = str_replace(' ', '_', $file_name);
        $file_name = str_replace('.', '_', $file_name);
                
        $is_cyrilic = ( bool ) preg_match ( '/[\p{Cyrillic}]/u', $file_name );
        $is_latin = ( bool ) preg_match ( '/[{ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ}]/u', $file_name );

        if ($is_cyrilic) {
            $json = array (
                    'status' => 'failed',
                    'message' => 'Файлын нэрийг Криллээр бичсэн байна! Файлын нэрийг Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
            );
        } elseif ($is_latin) {
            $json = array (
                    'status' => 'failed',
                    'message' => 'Файлын нэр танигдахгүй байна! Файлын нэрээ Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
            );
        } else {

            $f_name = key_gen ( 5 ) . '_' . $file_name;
        
            //echo $f_name;
            // config here
            $config = array (
                    'allowed_types' => 'pdf|doc|docx',
                    'upload_path' => $this->upload_path,
                    'max_size' => 124000,
                    'file_name' => $f_name 
            );
            
            $this->Obj->load->library ( 'upload', $config );

            // if successfully uploaded
            if ($this->Obj->upload->do_upload ()){

                $id = $this->input->get_post('id');
                
                if(isset($id)) $this->library->update($id, array('ebook'=>$f_name));

                // collect uploaded data
                $f_data = $this->Obj->upload->data ();
                
                $json = array (
                        'name' => $f_data ['file_name'],
                        'size' => $f_data ['file_size'],
                        'type' => $f_data ['file_type'],
                        'delete_url' => $this->upload_path . $f_name,                        
                );

                $json ['status'] = 'success';
            }   
            else 
                $json = array (
                        'status' => 'failed',
                        'message' => $this->Obj->upload->display_errors ( '', '' ) 
                );
            
        }
        
        echo json_encode($json); 
    
    }


    protected function delete_file(){
        
        $file_name = $this->input->get_post ( 'file_name' );

        $form = $this->input->get_post ( 'form' );

        if($form=='#edit'){
            $id = $this->input->get_post ( 'id' );
            
           //$this->library->update(intval($id), array('ebook' =>NULL));
            $this->Obj->db->where('id', $id);
            $this->Obj->db->update('library', array('ebook' =>NULL)); 
            
        }


        if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] .'/ecns/'.$this->upload_path . $file_name )) {
            // file is unlicked?
            if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . '/ecns/'.$this->upload_path . $file_name )) {
                
                $json = array (
                        'status' => 'success',
                        'message' => '[' . $file_name . '] амжилттай устгалаа!' 
                );
                    
            } else
                $json = array (
                        'status' => 'failed',
                        'message' => '[' . $file_name . '] устгахад алдаа гарлаа!' 
                );
        } else {            
            $json = array (
                    'status' => 'failed',
                    'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!' 
            );
        }
        
        echo json_encode($json); 
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

        $ebook = $this->library->get($id);

        // also delete form folder 
        if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] .'/ecns/'.$this->upload_path . $ebook->ebook )) {
           
           // file is unlicked?
           unlink ( $_SERVER ['DOCUMENT_ROOT'] . '/ecns/'.$this->upload_path . $ebook->ebook );
        }
                

        if($this->library->delete($id)){
          $return = array (
                'status' => 'success',
                'message' => $ebook->title.' номыг амжилттай устгалаа'
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

        
        $library = $this->library->with('equipment')->get($id);

        // $equipment = $this->equipment->with('section')->get($library->equipment->equipment_id);

        //$library['sections']=$this->section->get($equipment->section_id);
        
        // $library->equipment->equipment_id;
       // var_dump($library);
        

        echo json_encode($library);
    }


    protected function edit(){
        $id = $this->input->get_post ( 'id' );

        // check if old is new same
        $data = $this->library->array_from_post(array('id', 'equipment_id', 'title', 'author', 'isbn', 'year_of_pub', 'ebook'));
        
        // validate library at work

        if($this->library->validate($data)){

            if ($this->library->update($id, $data)){               
                
                $return = array (
                    'status' => 'success',
                    'message' =>'"'.$data['title'].'" номыг амжилттай засварлалаа'

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

}

class Library_Module extends Library_Crud{
    private $state = null;

    protected $is_ajax_request =FALSE;

    // grid data
    function __construct() {
       
        parent::__construct();

        $this->set_status_url();
    }

    //status-g url-s avah!
    private function set_status_url() {

        if($this->input->is_ajax_request()){

            $this->is_ajax_request = TRUE;
        }

        $this->state = $this->Obj->uri->segment (3 );

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
        
        // check state add, edit, delete
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


                case 'filter':
                    $this->section_select();
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

                case 'del_file':
                     $this->delete_file();
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
