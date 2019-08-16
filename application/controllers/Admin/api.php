<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
class api extends MY_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->helper ( 'url' );
		$this->load->helper ( 'form' );
	}


	function page_404(){
		if($this->uri->segment(1)=='ecns'){
			$this->load->view('405.html');
		}else
			show_404();

	}

}
