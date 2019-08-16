<?php

require_once('Core.php');

class Certificate_Loader extends Core
{

    protected $certificate;
    
    protected $file;
    
    function __construct()
    {

        parent::__construct();

        $this->Obj->load->model ( 'certificate_model' );

        $this->certificate = new certificate_model ();       
    
    }

}


class Certificate_Layout extends Certificate_Loader
{
	
	 private $echo_die = false;

    protected $view_as_string;

    public $upload_path = "download/cert_files/";


    function __construct(){

        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/certificate/view/');    

	}
	

	protected function my_filter(){

        if(($this->search=='true')&&($this->filters != "")){

        /// echo $this->filter($this->filters);

        return $this->filter_($this->filters);

        }else
        return 'null';
	}
	

	protected function filter_($filters) {

         $db = get_instance()->db->conn_id;

         // echo "filtering";

         $filters = json_decode ( $filters );

         // var_dump($filters);

         $where = " where ";

         $whereArray = array ();

         $rules = $filters->rules;

         $groupOperation = $filters->groupOp;

         foreach ( $rules as $rule ) {

             $fieldName = $rule->field;

             $fieldData = mysqli_real_escape_string ($db, $rule->data );

             switch ($rule->op) {
                 case "eq" :
                         if($fieldName =='issueddate'){

                             $fieldOperation = " >= '" . $fieldData . "' ";

                         }elseif($fieldName =='validdate'){

                            $fieldOperation = " <= '" . $fieldData . "' ";
                             
                         }else
                            
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
                        if($fieldName =='issueddate')

                             $fieldOperation = " >= '" . $fieldData . "' ";

                        elseif($fieldName =='validdate')

                            $fieldOperation = " <= '" . $fieldData . "' ";
                             
                        else
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
	

	
	 protected function index_form(){
       
       $data['action'] = $this->certificate->get_action();

       return $this->theme_view ($data);        

    }

    protected function outservice_form(){

    	 parent::set_file('outservice.php');
       
       $data['action'] = $this->certificate->get_action();

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

        $this->my_filter();

        // section setby user section
        $this->check_section();
   
        if($this->where){                    
        
               $this->where .= " and status is null ";    
        
        }else
              $this->where = " status is null ";    
                
        // нийт тоо 
        $this->set_count($this->certificate);                
        
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
        
        $rows = $this->certificate->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=$this->certificate->last_query();  

        $json['rows']=$rows;

        echo json_encode($json);                

    }

    protected function out_grid(){

        @ob_end_clean ();
        
        $this->init_grid();
        
        $this->set_echo_die();        
                
        // herev filter hiisen bval where should be set
        $this->check_filter();

        if($this->where){                    
        
               $this->where .= " and status = 'outservice' ";    
        
        }
              $this->where = " status = 'outservice' ";    
                
        // нийт тоо 
        $this->set_count($this->certificate);                
        
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
        
        $rows = $this->certificate->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=$this->certificate->last_query();  

        $json['rows']=$rows;

        echo json_encode($json);                

    } 

}

/**
 * Crud class here
 */
class Certificate_Crud extends Certificate_Layout
{
	
	function __construct()
	{
		parent::__construct();

	}

	// =get cert_id
	protected function get() {

		$id = $this->input->get_post('id');

		$data = $this->certificate->with('equipment')->with('location')->get($id);

		 echo json_encode($data);
		
	}

   protected function add(){
        
	   //Сэлбэгийн дугаарыг бодох:
	   //1. hamgiin bagiig avaad 
	   $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

	   unset($this->certificate->validate[2]);

	   if($this->certificate->validate($this->certificate->validate)){

	      $data = $this->certificate->array_from_post(array('cert_no', 'location_id', 'equipment_id', 'serial_no_year', 'issueddate', 'validdate', 'cert_file'));
		  
		  date_default_timezone_set("Asia/Ulan_Bator");
	      
	      $data['updated_at'] = date('Y-m-d H:i:s');
		
		  $equipment = $this->equipment->get($data['equipment_id']);
		  
		  $data['section_id'] = $equipment->section_id;
		  
	      if ($id = $this->certificate->insert($data, TRUE)){
	      
	          $return = array (
	                 'status' => 'success',
	                 'message' => $data['cert_no'].' дугаартай гэрчилгээг амжилттай хадгаллаа'
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

	protected function edit(){

	   $data = $this->certificate->array_from_post(array('id', 'cert_no', 'location_id', 'equipment_id', 'serial_no_year', 'intend', 'issueddate', 'validdate'));   


	  unset($this->certificate->validate[2]);
	   // print_r($this->certificate->validate[7]);

	  date_default_timezone_set("Asia/Ulan_Bator");

	  $data['updated_at'] = date('Y-m-d H:i:s');

      if($this->certificate->validate($data)){

      	$intend = $data['intend'];

      	unset($data['intend']);

      	$this->Obj->db->update('equipment2', array('intend'=>$intend), array('equipment_id'=>$data['equipment_id']));
      	
         if ($this->certificate->update($data['id'], $data)){      
        		 
            $return = array (

                'status' => 'success',
                'message' =>'"'.$data['cert_no'].'" дугаартай гэрчилгээний мэдээллийг амжилттай засварлалаа'

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

	protected function upload() {
		// update hiihed user_id -g update hiine
		$file_name = $_FILES ['userfile'] ['name'];
		
		$cert_id = $_POST ['cert_id'];
				
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
			// //хэрэв энэ файл сервер дээр байршсан бол устгах
			// if (file_exists($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$file_name)){
			// //file-г устгах хэрэгтэй
			// unlink($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$file_name;
			// }
			// if successfully uploaded
			if ($CI->upload->do_upload ()) {
				// collect uploaded data
				$f_data = $CI->upload->data ();
				$json = array (
						'name' => $f_data ['file_name'],
						'size' => $f_data ['file_size'],
						'type' => $f_data ['file_type'],
						'delete_url' => $this->upload_path . $f_name,
						'cert_id' => $cert_id 
				);
				// update current cert file by id

				if($cert_id&&isset($cert_id)){

					if ($CI->db->update('certificate', array ('cert_file' => $f_name ), array('id'=>$cert_id) )) {

						$json ['status'] = 'success';

					} else {

						echo $this->certificate->last_query();

						$json ['status'] = 'failed';

						$json ['message'] = 'Өгөгдлийн санд алдаа гарлаа!';

					}
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

	protected function add_upload() {
		// update hiihed user_id -g update hiine
		$file_name = $_FILES ['userfile'] ['name'];
		
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

				$json['status'] ='success';

				$json['message'] =  'Амжилттай байршууллаа!';

			} else {
				$json = array (
						'status' => 'error',
						'message' => $CI->upload->display_errors ( '', '' ) 
				);
			}
		}
		echo json_encode($json);
	}

	protected function delete() {

		$id = $this->input->get_post('id');

		$certificate = $this->certificate->get($id);

		if($this->certificate->delete($id) && move_uploaded_file($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$certificate->file_name, $_SERVER['DOCUMENT_ROOT']."/ecns/download/trash/")){
			
				$return = array (
						'status' => 'success',
						'message' => '"' . $certificate->cert_no . '" дугаартай гэрчилгээ амжилттай устгагдлаа!' 
				);

		} else {

			$return = array (
					'status' => 'failed',
					'message' => $id . ' устгахад алдаа гарлаа!' 
			);
		}

		 echo json_encode($return);
	}	

	
	protected function delete_file() {

		$file = $this->input->get_post('file_name');

		if(rename($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$file, $_SERVER['DOCUMENT_ROOT']."/ecns/download/trash/cert_files/".$file)){
			
				$return = array (
						'status' => 'success',
						'message' => '"' . $file . '" дугаартай гэрчилгээ амжилттай устгагдлаа!' 
				);

		} else {

			$return = array (
					'status' => 'failed',
					'message' => $file . ' устгахад алдаа гарлаа!' 
			);
		}

		 echo json_encode($return);
	}	


	protected function del_file() {

		$id = $this->input->get_post ( 'id' );

		$file_name = $this->input->get_post ( 'file_name' );

		// check file exists
		// var_dump($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/");
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/cert_files/" . $file_name )) {
			// file is unlicked?
			if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/cert_files/" . $file_name )) {
				
				$this->certificate->update ($id, array (	'cert_file' => null ));

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
			// check if this file exists in the db?  update the row

			$this->certificate->update ($id,  array ('cert_file' => null 	));
			
			$json = array (
					'status' => 'failed',
					'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!' 
			)
			;
		}
		
		echo json_encode($json);
	}

	protected function set_outservice() {

		$id = $this->input->get_post ( 'id' );

		$certificate = $this->certificate->get($id);

		if ($this->Obj->db->update('certificate', array('status'=>'outservice'), array('id'=>$id))) {


			// get certificate no via id
			$return = array (
					'status' => 'success',
					'message' => '"' . $certificate->cert_no . '" дугаартай гэрчилгээ ашиглалтаас амжилттай хаслаа! Ашиглалтаас хасагдсан гэрчилгээний жагсаалтад нэмлээ!' 
			);
		} else {

			$return = array (
					'status' => 'failed',
					'message' => $certificate->cert_no. ' гэрчилээтэй т/т дээр энэ үйлдлийг хийхэд алдаа гарлаа!' 
			);

		}

		echo json_encode($return);
	}

}


class Certificate_Module extends Certificate_Crud {
   
    private $state = null;

    protected $is_ajax_request =FALSE;
    
     
    // grid data 
    function __construct() {

        $this->set_status_url();    

        parent::__construct();
    }


	private function set_status_url() {

        $CI = &get_instance ();

        $CI->load->helper ( 'url' );

        if($CI->input->is_ajax_request()){   

            $this->is_ajax_request = TRUE;   

        }
        $this->state = $CI->uri->segment ( 3 );  

        if (! $this->state)

           $this->state = 'open';   
           
    }

     //хэрэв ajax baival yah uu?        
    // don't need status
    protected function get_status(){

        return $this->state;
    }

    function run() {            
      
	    $data = array ();

	    $data ['view'] = true;    

	    if($this->is_ajax_request){

	       $this->set_echo_die();    

	       header ( 'Content-type: application/json; charset=utf-8' );

	        switch ($this->get_status()) {

	        	  case 'grid' :
					  
					  $this->grid ();					  

				  break;			

				case 'out_grid' :
				   
				   $this->out_grid();

					break;
				
				// =delete case here
				case 'delete' :

					 	$this->delete ();
								
					break;			

				// =set_outservice
				case 'set_outservice' :
					
					 $this->set_outservice ();
					
					break;
				
				// Show Trainer page shows
				case 'page' :

					 $this->get ();

					break;

				
				// Show Trainer page shows
				case 'edit' :
				
						 $this->edit ();
				
		
					break;

				// upload here
				case 'upload' :
					
					$this->upload ();

					break;

				case 'del_file' :
					$this->del_file ();
					
					break;

				// call here license
				case 'license' :
					
	 				$this->get_license ( $this->cert_id );

					break;	

				case 'add' :
					
	 				$this->add ();

					break;	


			case 'add_upload' :
					
	 				$this->add_upload ();

					break;	

		     	

		   case 'delete_file' :
					
	 				$this->delete_file ();

					break;	

		      }


	  	 }else{
	  	 	 switch ($this->get_status ()) {   

			  	 	 // call here license
					case 'outservice' :

						$this->outservice_form();
						
						// $data ['page'] = 'certificate\outservice';

						// $data ['title'] = 'Ашиглалтаас хасагдсан тоног төхөөрөмжийн гэрчилгээний бүртгэл';
						
						// $data ['sec_code'] = $this->get_seccode ();

						// $data ['action'] = $this->action ();

						// $data ['role'] = $this->get_role ();

						// $data ['equipment'] = $this->get_select ( 'equipment', 'equipment2' );
						// $data ['location'] = $this->get_select ( 'name', 'location' );

						// return ( object ) $data;
						break;

					// Ашиглалтаас хасагдсан бол энд дуудна!!!
					
	             default : // index page loaded
                      
                  $data ['title'] = 'Хэрөлборыйдөйбы';                        

                  $this->index_form();                                                    

                  break;

	  	 	 }
	  	 }

	  	 return $this->get_layout();

   }
    

}

if (defined ( 'CI_VERSION' )) {
	$ci = &get_instance ();
	$ci->load->library ( 'Form_validation' );
	class CS_CRUD_Form_validation extends CI_Form_validation {
		public $CI;
		public $_field_data = array ();
		public $_config_rules = array ();
		public $_error_array = array ();
		public $_error_messages = array ();
		public $_error_prefix = '<p>';
		public $_error_suffix = '</p>';
		public $error_string = '';
		public $_safe_form_data = FALSE;
	}
	class CS_CRUD_inputs extends CI_input {
		public $CI;
	}
}