<?php

class document extends CNS_Controller {

	var $file;
	var $path;
	var $delete_path;

	public function __construct() {

		parent::__construct ();
	
		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'document', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Бичиг баримтын бүртгэл' );

		$this->config->set_item ( 'module_menu_link', '/document' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
		
		$this->path = "download" . DIRECTORY_SEPARATOR . "doc_files" . DIRECTORY_SEPARATOR;
		$this->delete_path = "download" . DIRECTORY_SEPARATOR . "trash" . DIRECTORY_SEPARATOR;
			
	}
	
	function index() {

		$data ['title'] = 'Бичиг баримт';

		$data ['page'] = 'file\file';

		$cqry = $this->db->query ( "SELECT * FROM category WHERE type = 'file' ORDER BY ordering asc" );
		$mqry = $this->db->query ( "SELECT * FROM category WHERE type = 'manual' ORDER BY ordering asc" );
		$pqry = $this->db->query ( "SELECT * FROM category WHERE type = 'procedure' ORDER BY ordering asc" );
		
		$fqry = $this->db->query ( "SELECT A.fileId, A.categoryId, A.filename, title, docIndex, DATE_FORMAT(created, '%Y-%m-%d') as created, B.category 
                              FROM files A join category B ON A.categoryId = B.Id                              
                               ORDER BY A.created asc 
                              " );
		
		$data ['pres'] = $pqry->result ();
		$data ['cres'] = $cqry->result ();
		$data ['mres'] = $mqry->result ();
		$data ['fres'] = $fqry->result ();
		$data ['role'] = $this->role;
		$this->load->view ( 'index', $data );
	}
	function help() {
		$data ['title'] = 'Тусламж';
		$this->load->view ( 'help\document', $data );
	}
	function download() {
		$file = $this->uri->segment ( 3 );
		// echo $file;
		$file_path = $this->path . $file;
		$file_size = $this->get_filesize ( $file_path );
		$this->setFile ( $file );
		
		if ($file_size >= 45) {
			// BEGIN DOWNLOAD
			force_download ( $file, $file_path, 'large' );
		} else {
			// READ FILE CONTENTS
			$file_data = file_get_contents ( $this->path . $file );
			// BEGIN DOWNLOAD
			force_download ( $file, $file_data, 'small' );
		}
		
		// force_download($file, read_file($this->file), 'large');
	}
	function setFile($file) {
		$this->file = $this->path . $file;
	}
	function dir_file_info_test() {
		$files = get_dir_file_info ( $this->path );
		print_r ( $files );
	}
	function read_test($file) {
		$this->setFile ( $file );
		$string = read_file ( $this->file );
		echo $string;
	}
	function settings() {
		try {
			if ($this->main_model->get_authority ( 'document', 'index', 'settings', $this->role ) == 'settings') {
				$crud = new grocery_CRUD ();
				$this->load->config ( 'grocery_crud' );
				// get category_id manual then where
				// $crud->where('')
				$crud->set_table ( 'files' );
				$crud->display_as ( 'categoryId', 'Бүлэг' )->display_as ( 'fileId', '#' )->display_as ( 'ordering', 'Эрэмбэ' )->display_as ( 'filename', 'Файлын нэр' )->display_as ( 'title', 'Файлын тодорхойлолт' )->display_as ( 'docIndex', 'Индекс' )->display_as ( 'created', 'Үүсгэсэн Огноо' );
				$crud->set_relation ( 'categoryId', 'category', 'category' );
				$crud->set_subject ( 'Document' );
				$crud->set_field_upload ( 'filename', 'download/doc_files' );
				$crud->callback_delete ( array (
						$this,
						'delete_file' 
				) );
				// $crud->required_fields('city');
				$crud->columns ( 'fileId', 'categoryId', 'ordering', 'filename', 'title', 'docIndex', 'created' );
				$crud->order_by ( 'ordering', 'asc' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// delete files
	// $crud->callback_delete(array($this,'delete_file'));
	function delete_file($primary_key) {
		// folder link
		// copy file
		// delete file
		// $deleted_file = $this->path.$file;
		// $this->upload_path
		$filename = $this->main_model->get_row ( 'filename', array (
				'fileId' => $primary_key 
		), 'files' );
		$file = $this->path . $filename;
		$del_file = $this->delete_path . $filename;
		// copy($old, $new) or die("Unable to copy $old to $new.");
		if (copy ( $file, $del_file )) {
			unlink ( $file );
		}
		return $this->db->delete ( 'files', array (
				'fileId' => $primary_key 
		) );
	}
	function category() {
		try {
			if ($this->main_model->get_authority ( 'document', 'settings', 'category', $this->role ) == 'category') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'category' );
				$crud->display_as ( 'Id', '#' )->display_as ( 'ordering', 'Эрэмбэ' )->display_as ( 'category', 'Бүлэг' )->display_as ( 'description', 'Тодорхойлолт' );
				
				$crud->required_fields ( 'ordering', 'category', 'description' );
				$crud->columns ( 'Id', 'ordering', 'category', 'description' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	function _settings_output($output = null) {
		$this->load->view ( 'settings.php', $output );
	}
	function get_filesize($file_path) {
		$file_info = get_file_info ( $file_path );
		$file_size = round ( (round ( $file_info ['size'] / 1024 )) / 1024 );
		return $file_size;
	}

	function filter(){
		$spare = $this->input->get_post('term');
		$q = strtolower ( $spare );
        // remove slashes if they were magically added
        if (get_magic_quotes_gpc ())
            $q = stripslashes ( $q );

        $query = $this->db->query ("SELECT fileId, concat(title) as title FROM files where title like ('%$q%')");

        $result = array ();
        foreach ( $query->result () as $row ) {            
                array_push ( $result, array (
                    "id" => $row->fileId,
                    "value" => $row->title
                ) );            
            if (count ( $result ) > 15)
                break;
        }


		echo json_encode($result);
	}

	function search(){
		$file_id = $this->input->get_post('file_id', TRUE);
		$filename = $this->input->get_post('filename', TRUE);
		$data['file_id'] = $file_id;

		$search = $this->input->get_post('search', TRUE);
		$data['search'] = $search;

		$this->form_validation->set_rules('search', 'Хайлт', 'required|trim|xss_clean');

		$this->form_validation->set_message ( 'required', ' "%s"-н утга хоосон байна ямар нэг утга бичиж хайна уу!' );

		// $this->form_validation->set_message ( 'alpha_dash_space', ' "%s" Хоосон утгаар хайлт хийх боломжгүй!' );

		if ($this->form_validation->run () == TRUE) {
						// хэрэв file_id байвал тухайн Id-р хайлт хийнэ
			if(isset($file_id)&&$file_id){

			    $query = $this->db->query ("SELECT fileId, concat(title) as title, filename FROM files where fileId = $file_id");

			   $data['value'] = $filename;

			}elseif(isset($search)){
			   
			   $query = $this->db->query ("SELECT fileId, concat(title) as title, filename FROM files where title like ('%$search%')");

			   $data['value'] = $search;
			}

			$data['result'] =$query->result();
			$data['count'] =$query->num_rows();


		//эс тохиолдолд search утгаар хайлт хийнэ
		//result-г тухайн view руу шиднэ!!!
	
		}

		$data['back_url'] ='document/index';

		$data ['title'] = 'Бичиг баримт';
		$data ['page'] = 'file\search_result';
		$this->load->view ( 'index', $data );

			//validation_errors ( '', '<br>' );


	}

	function alpha_dash_space($str)
	{
	    return ( ! preg_match("/^([ .,\-])+$/i", $str)) ? FALSE : TRUE;
	} 

}
?>