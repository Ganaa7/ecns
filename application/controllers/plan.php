<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Plan extends CNS_Controller {

   function __construct(){

      parent::__construct ();

      $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'plan', $this->role, 0, 1 ) );

	   $this->config->set_item ( 'module_menu', 'Төлөвлөгөөт ажлын  бүртгэл' );
      
      $this->config->set_item ( 'module_menu_link', '/ecns/plan' );
      
      $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

      $this->load->library ( 'ePlan' );
      
   }


	public function index(){

		  $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/plan/plan.js', TRUE ));

		 $plan= new ePlan();


		 $this->data['section']=$this->section_model->dropdown_by('section_id', 'name', array('section_id <'=>5));

		 //herev user_group_id  = section_id bol 

		 $this->data['equipment']=$this->equipment_model->dropdown_by('equipment_id', 'equipment',array('section_id' =>1 ));

		 $this->data['employee']=$this->employee_model->dropdown('fullname');

		 $this->data['plan'] = $plan->run ();

		 $this->data['page']='\plan\index';
		 
		 $this->data['title']='Төлөвлөгөөт ажил';

		 $this->load->view('index', $this->data);


	   
	   // $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/library/library.js', TRUE ));



	   
	
	}
}
