<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class library extends CNS_Controller {

   function __construct(){

      parent::__construct ();

      $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'library', $this->role, 0, 1 ) );

		  $this->config->set_item ( 'module_menu', 'Номын сангийн бүртгэл' );

      $this->config->set_item ( 'module_menu_link', '/ecns/library' );

      $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

      $this->load->library ( 'Library_Module' );
      
   }

	public function index(){
    
	   $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/library/library.js', TRUE ));

		 $library= new Library_Module();

		 $this->data['section']=$this->section_model->dropdown_by('section_id', 'name', array('section_id <'=>5));


		 //herev user_group_id  = section_id bol 

		 $this->data['equipment']=$this->equipment_model->dropdown_by('equipment_id', 'equipment',array('section_id' =>1 ));

		 $this->data['library'] = $library->run ();

		 $this->data['page']='\library\index';
		 $this->load->view('index', $this->data);
		 // echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nulla nemo ducimus error perferendis inventore voluptatum est? Hic ipsum eaque sint, et quis adipisci nisi repellat quisquam, qui aspernatur sunt voluptates.";
	}
}
