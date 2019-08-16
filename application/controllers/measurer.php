<?php
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class measurer extends CNS_Controller {

	public static $user_seccode;

	public static $user_section_id;

	public $gallery_path;

	public $gallery_path_url;

	public $objdata;

	function __construct() {
		parent::__construct ();
		
			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'measurer', $this->role, 0, 1 ) );

			$this->config->set_item ( 'module_menu', 'Хэмжилтийн хэрэгсэл' );

			$this->config->set_item ( 'module_menu_link', '/ecns/measurer' );

			$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

			$this->user_seccode = $this->session->userdata ( 'sec_code' );

			$this->user_section_id = $this->session->userdata ( 'section_id' );
		}
	}
	function help() {
		$data ['page'] = 'measurer\help';
		$data ['title'] = "Алдааны мод тусламж";
		$this->load->view ( 'index', $data );
	}
	function index() {
		
		$data ['library_src'] = $this->javascript->external ( '/ecns/assets/measurer/js/measurer.js', TRUE );

		$t = new eMeasurer ();

		$t->set_user ( $this->user_id );

		$t->set_role ( $this->role );
		
		$out = $t->run ();
		
		if ($out->view) {
			$data ['out'] = $out;
			$data ['page'] = 'measurer\measurer_list';
			$data ['title'] = 'Хэмжилтийн хэрэгсэл';
			$this->my_model_old->set_table ( 'location' );
			$data ['location'] = $this->my_model_old->get_select ( 'name' );
			$this->my_model_old->set_table ( 'equipment2' );
			$data ['equipment'] = $this->my_model_old->get_select ( 'equipment' );
			$this->my_model_old->set_table ( 'f_event' );
			$data ['event'] = $this->my_model_old->get_select ( 'event' );
			// here is get result studying
			$this->load->view ( 'index', $data );
		} else {
			if ($out->json) {
				header ( 'Content-type: application/json; charset=utf-8' );
				echo $out->json;
			} else {
				if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" ))
					header ( "Content-type: application/xhtml+xml;charset=utf-8" );
				else
					header ( "Content-type: text/xml;charset=utf-8" );
				echo $out->xml;
			}
		}
	}
	function settings() {
		try {
			if ($this->main_model->get_authority ( 'measurer', 'measurer', 'settings', $this->role ) == 'settings') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'measurer' );
				$crud->set_relation ( 'section_id', 'section', 'name', array (
						'type' => 'industry' 
				), 'section_id ASC' );
				$crud->set_subject ( 'Хэмжилтийн хэрэгсэл' );
				$crud->fields ( 'id', 'section_id', 'measurer', 'purpose', 'manufacturer', 'type', 'serial', 'cert_date', 'valid_date', 'cert_file', 'updatedby_id', 'updated_date' );
				
				$crud->add_fields ( 'section_id', 'measurer', 'purpose', 'manufacturer', 'type', 'serial', 'cert_date', 'valid_date', 'cert_file', 'updatedby_id' );
				
				$crud->required_fields ( 'section_id', 'measurer', 'type', 'serial', 'cert_date', 'valid_date' );
				
				$crud->columns ( 'id', 'section_id', 'measurer', 'purpose', 'manufacturer', 'type', 'serial', 'cert_date', 'valid_date', 'cert_file' );
				
				// $crud->callback_field('updatedby_id',array($this,'before_update_updatedby_id'));
				
				$crud->display_as ( 'id', '#' )->display_as ( 'section_id', 'Хэсгийн нэр' )->display_as ( 'measurer', 'Хэмжих хэрэгсэл' )->display_as ( 'purpose', 'Зориулалт' )->display_as ( 'type', 'Загвар,маяг' )->display_as ( 'serial', 'Сериал дугаар' )->display_as ( 'cert_date', 'Баталгаажуулсан огноо' )->display_as ( 'cert_file', 'Сертификат файлаар' )->display_as ( 'valid_date', 'Батал\дуусах\t' )->display_as ( 'updated_date', 'Шинэчлэсэн огноо' );
				
				$crud->field_type ( 'updatedby_id', 'invisible' );
				
				$crud->set_field_upload ( 'cert_file', 'download/doc_files' );
				
				$crud->callback_before_insert ( array (
						$this,
						'before_insert_updatedby_id' 
				) );
				
				$output = $crud->render ();
				$this->view_out ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	function before_insert_updatedby_id($post_array) {
		$post_array ['updatedby_id'] = $this->session->userdata ( 'employee_id' );
		return $post_array;
	}
	function view_out($output = null) {
		$this->load->view ( 'settings.php', $output );
	}
}