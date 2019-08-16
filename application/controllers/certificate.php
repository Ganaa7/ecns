<?php

if (! defined ( 'BASEPATH' )) 	exit ( 'No direct script access allowed' );

class certificate extends CNS_Controller {

	private $equipment;

	public function __construct() {
		parent::__construct ();

		$this->load->model ( 'my_model_old' );
	
		$this->load->library ( 'Certificate_Module' );

		$this->equipment = $this->equipment_model;
		
		$this->location = $this->location_model;
		
		$this->section = $this->section_model;
		
		$this->main_model->access_check ();

		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'certificate', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Гэрчилгээний бүртгэл' );

		$this->config->set_item ( 'module_menu_link', 'index' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

	}

	function index() {
		
		$data ['library_src'] = $this->javascript->external (base_url().'assets/js/certificate.js', TRUE );

		$cert = new Certificate_Module ();

		$data ['certificate'] = $cert->run ();

		$data ['location'] = $this->location->dropdown('location_id','name');

		$data ['section'] = $this->section->dropdown('section_id', 'name' );

		$data['equipment']=$this->equipment->dropdown('equipment_id', 'equipment');

		$this->load->view ( 'certificate/certificate', $data );			

	}

} 

?>