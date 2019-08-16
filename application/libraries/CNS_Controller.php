<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_Controller
 *
 * @author Ganaa
 *
 */

class CNS_Controller extends MY_Controller
{

	protected $role;
	
	// protected $user_id;
 
	function __construct ()
	{
		parent::__construct();

		date_default_timezone_set ( 'Asia/Ulan_Bator' );

		$this->data['meta_title'] = 'eCns';

		$this->load->model ( 'employee_model' );

		$this->role = $this->session->userdata ( 'role' );

     	$this->user_id = $this->session->userdata ( 'employee_id' );

		// Login check
		$exception_uris = array(
			'user/login',
			'user/logout',
			'user/forgot',
			'user/sent_forgot',
			'user/pass_recovery',
			'user/reset'
		);

		if (in_array(uri_string(), $exception_uris) == FALSE) { //edgeer url-s uur url-d bgaad

			$uri= uri_string();
			// echo "uri".$uri;

		 	if ($this->user_m->loggedin() == FALSE&&$uri!="") {  //nevterj oroogui bol
		 		//if(base_url().'index' != uri_string())
		 		
		 		$this->session->set_flashdata('error', 'Уучлаарай, Систем дэх таны мэдээлэл хадгалагдах хугацаа дууссан тул дахин нэвтэрх шаардлагтай!');

		 		redirect(base_url().'user/login');
		 	}

		}



	}

}
