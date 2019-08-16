<?php

class contract extends CNS_Controller{

   var $file;

   var $path;

   public function __construct() {

      parent::__construct();
      
       $this->load->library('eContract');

       $this->config->set_item('user_menu', $this->user_model->display_menu('contract', $this->role, 0, 1)); 

       $this->config->set_item('module_menu', 'Гэрээ, журам, зөвшөөрөл');

       $this->config->set_item('module_menu_link', '/contract');            

       $this->config->set_item('access_type', $this->session->userdata('access_type'));
                   
       $this->path = "download" . DIRECTORY_SEPARATOR . "contract_files"
  			 . DIRECTORY_SEPARATOR;                      
      }

   
   function index(){
      
      $data['library_src']=$this->javascript->external(base_url().'assets/js/contract.js', TRUE);        
   
      $t = new eContract();  

      $t->set_user($this->user_id);       

      $t->set_role($this->role);      

      $out=$t->run();

      if($out->view){

          $data['out']=$out;

          $data['page']=$out->page;

          $data['title']=$out->title;

          $this->my_model_old->set_table('location');

          $data['location']=$this->my_model_old->get_select('name');

          $this->my_model_old->set_table('position');       

          $data['position']=$this->my_model_old->get_select('name'); 

          $this->my_model_old->set_table('settings');    

          $sdata=$this->my_model_old->get_select('value', array('settings' =>'license_type'));  

          $data['organization']=$this->my_model_old->get_select('value', array('settings' =>'org_name'));

          unset($sdata[0]);

          $data['license_type']=$sdata;

          $this->load->view('index',$data);

        }else{
          
            if($out->json){

              header('Content-type: application/json; charset=utf-8');

              echo $out->json;

            }else {
              
              if( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) 

                 header("Content-type: application/xhtml+xml;charset=utf-8"); 

               else 
                
                  header("Content-type: text/xml;charset=utf-8"); 
                
               echo $out->xml;
            }
        }
   }


   function help(){
      $data['title']='Төлөвлөгөөт ажил';
      $this->load->view('help\event', $data);
   }
    
   function download() {     
      $file =  $this->uri->segment(3);       
      //echo $file;       
      $file_path = $this->path.$file;
      $file_size=$this->get_filesize($file_path);
      $this->setFile($file);              
       if ($file_size >= 45) {
            // BEGIN DOWNLOAD            
            force_download($file, $file_path, 'large');
       } else {
            // READ FILE CONTENTS
          $file_data = file_get_contents($this->path.$file);          
          // BEGIN DOWNLOAD
          force_download($file, $file_data, 'small');
        }
    }
    
   function setFile($file) {
       $this->file = $this->path . $file;      
       //echo $this->file;
    }
    
   function dir_file_info_test() {
       $files = get_dir_file_info($this->path);
       print_r($files);
    }
    
   function read_test($file) {
       $this->setFile($file);       
       $string = read_file($this->file);
       echo $string;
    }
    
   function settings(){     
      try{
         $output['title']='Тохиргоо';

      	 if($this->main_model->get_authority('contract','index','settings', $this->role)=='settings'){
            $crud = new grocery_CRUD();	 
            $this->load->config('grocery_crud');   
            //get category_id manual then where      
      	    $crud->set_table('contract');            
              $crud->display_as('category_id','Бүлэг')              
                    ->display_as('id','#')         
                    ->display_as('ordering','Эрэмбэ')         
                    ->display_as('contract_no','Гэрээний дугаар')         
                    ->display_as('filename','Файлын нэр')         
                    ->display_as('invoice_file','Төлбөрийн баримт')         
                    ->display_as('title','Гэрээний тодорхойлолт')         
                    ->display_as('sides','Талууд')                           
                    ->display_as('approved','Батлагдсан огноо')            
                    ->display_as('expireddate','Дуусах огноо');             
              $crud->set_relation('category_id','settings','name', array('settings' =>'contract')); 
              $crud->set_subject('Гэрээ, журам, зөвшөөрөл');
              $crud->set_field_upload('filename','download/contract_files');  
              $crud->set_field_upload('invoice_file','download/contract_files');  
      	    //$crud->required_fields('city');            
      	    $crud->columns('id','ordering','category_id', 'contract_no', 'title', 'filename', 'sides',  'approved', 'expireddate', 'invoice_file');
      	    $output = $crud->render();
      	    $this->_settings_output($output);
      	 }else{
      	    $this->load->view('43.html');
      	 }
      }catch(Exception $e){
	      show_error($e->getMessage().' --- '.$e->getTraceAsString());
      }      
   }   

   function archive(){     
      try{
         $output['title']='Худалдан авалтын гэрээ';
         if($this->main_model->get_authority('contract','index','archive', $this->role)=='archive'){
            $crud = new grocery_CRUD();  
            $this->load->config('grocery_crud');   
            //get category_id manual then where      
            $crud->set_table('contract_archive');            
              $crud->display_as('category_id','Бүлэг')              
                    ->display_as('id','#')         
                    ->display_as('ordering','Эрэмбэ')         
                    ->display_as('year','Он')
                    ->display_as('section_id','Харьяа хэсэг')         
                    ->display_as('contract_no','Гэрээний дугаар')         
                    ->display_as('filename','Файлын нэр')                             
                    ->display_as('title','Гэрээний тодорхойлолт')         
                    ->display_as('sides','Талууд')
                    ->display_as('equipment_id','Төхөөрөмж');
                    
              $crud->set_relation('section_id','section','name', array('type' =>'industry')); 
              $crud->set_relation('equipment_id','equipment2','equipment', array('parent_id' =>null)); 
              $crud->set_subject('Гэрээний архив');
              $crud->set_field_upload('filename','download/contract_archive_files');  
              $crud->set_field_upload('invoice_file','download/contract_archive_files');  
            //$crud->required_fields('city');            
            $crud->columns('id','ordering','contract_no', 'year', 'equipment_id','section_id', 'title', 'filename', 'sides');
            $output = $crud->render();
            $this->_settings_output($output);
         }else{
            $this->load->view('43.html');
         }
      }catch(Exception $e){
        show_error($e->getMessage().' --- '.$e->getTraceAsString());
      }      
   }   


   function vw_archive(){

      echo "lorem10";


   }
  
   function _settings_output($output = null)
   {
      $this->load->view('settings.php', $output);
   }
      
   function get_filesize($file_path){      
      $file_info = get_file_info($file_path);
      $file_size = round((round($file_info['size'] / 1024))/1024);
      //return MB
      return $file_size;
   }
            
}
?>
