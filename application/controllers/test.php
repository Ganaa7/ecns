<?php
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
	
	class test extends CNS_Controller {
	   public function __construct() {
	   	  parent::__construct ();
	   	   $this->load->model ( 'my_model_old' );
		   $this->load->model ( 'main_model' );
		   $this->load->model ( 'user_model' );
		   
		   $this->load->library ( 'eTraining' );
		   $this->load->library ( 'javascript', FALSE );		
	   }

	   	function index() {
			echo gen_grid(array('super'=>1, 'test'=>2),'test','super', 'herro', 'tyslfjdslfds', 1);
	   	}

}
