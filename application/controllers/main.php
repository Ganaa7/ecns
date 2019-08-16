<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class main extends CNS_Controller {

	private $system;

	public function __construct() {

		parent::__construct ();

		if($this->role){

			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'home', $this->role, 0, 1 ) );

			$this->config->set_item ( 'module_menu', 'Цахим систем нүүр::eCNS Home' );

			$this->config->set_item ( 'module_menu_link', '/index.php' );

			$this->system = $this->main_model->get_row ( 'value', array (
						'settings' => 'system' 
				), 'settings' );
		}
		
	}
	
	function index() {
		
		// echo $this->session->userdata('user_type' );
		// Refresh хийхэд сүүлд нэвтэрсэн систем уруу орно.
		if ((bool)$this->session->userdata ( 'loggedin' ) && $this->session->userdata ( 'loggedin' ) == TRUE) {
			$this->home ();
		} else {

			$data ['page'] = 'user/login';
			
			$this->load->view ( 'index.php', $data );
		}
	}

	// // ITS NOT USED ANYMORE	
	// function login_user() {

	// 	$access = $this->session->userdata ( 'loggedin' );
	// 	if (isset ( $access ) && $access == TRUE) {
	// 		redirect ( '' );
	// 	} else {
	// 		if (isset ( $_POST ["username"] ))
	// 			$username = $_POST ["username"];
	// 		else
	// 			$username = '';
	// 		if (isset ( $_POST ["password"] ))
	// 			$password = $_POST ["password"];
	// 		else
	// 			$password = '';
			
	// 		$username = stripslashes ( $username );
	// 		$password = stripslashes ( $password );			
			
	// 		if ($this->user_model->user_check ( $username, $password )) {
	// 			// setcookie("remote", $_SERVER["REMOTE_ADDR"], time()+60*60*24*30);
	// 			setcookie ( "remember_me", $_POST ['remember_me'], time () + 3600 );
	// 			setcookie ( "username", $username, time () + 60 * 60 );
	// 			setcookie ( "password", $password, time () + 60 * 60 );
	// 			redirect ( '' );
	// 			$this->file_model->write_login ();
	// 		} else {
	// 			$this->session->set_userdata ( 'error', 'Уучлаарай, Таны нэвтрэх нэр эсвэл нууц үг буруу байна!' );
	// 			$data ['error'] = $this->session->userdata ( 'error' );
	// 			$data ['page'] = 'login';
	// 			$this->load->view ( 'index', $data );
	// 		}
	// 	}
	// }

	function home() {
		if ($this->session->userdata ( 'role' )||(bool) $this->session->userdata('loggedin')) {			
			
			$this->session->set_userdata ( 'token', base64_encode ( $this->my_model_old->gen_password ( 10 ) ) );

			$data ['title'] = 'Үндсэн цонх';
			$data ['page'] = 'home';
			
			$data ['system'] = $this->system;
			$data ['sys_result'] = $this->user_model->user_system ();
			
			$this->load->view ( 'index', $data );
		} else
			redirect ( '/user/login' );
	}
	
	// signout of system
	function logout() {
		// write logged out user
		$this->file_model->write_logout ();
		delete_cookie ( 'access' );
		$this->session->unset_userdata ( 'employee_id' );
		$this->session->unset_userdata ( 'access' );
		$this->session->unset_userdata ( 'usermenu' );
		$this->session->unset_userdata ( 'fullname' );
		$this->session->unset_userdata ( 'position' );
		$this->session->unset_userdata ( 'role' );
		$this->session->unset_userdata ( 'sector' );
		$this->session->unset_userdata ( 'sec_code' );
		$this->session->unset_userdata ( 'section_id' );
		$this->session->unset_userdata ( 'access_type' );
		$this->session->unset_userdata ( 'limit' );
		$this->session->unset_userdata ( 'fsec_code' );
		$this->session->unset_userdata ( 'fequipment_id' );
		$this->session->unset_userdata ( 'fsector_id' );
		$this->session->unset_userdata ( 'fsection_id' );
		$this->session->unset_userdata ( 'fsparetype_id' );
		$this->session->unset_userdata ( 'message' );
		$this->session->unset_userdata ( 'log' );
		$this->session->unset_userdata ( 'start_date' );
		$this->session->unset_userdata ( 'end_date' );
		$this->session->unset_userdata ( 'user_type' );
		$this->session->unset_userdata ( 'home' );
		$this->session->sess_destroy ();
		
		$data ['msg'] = 'Та цахим системээс гарлаа';
		$data ['page'] = 'logout';
		$data ['title'] = 'Систем гарах';
		redirect ( '' );
	}
	

	function system_log() {
		$data ['title'] = 'Системийн бүртгэл';
		$data ['file'] = $this->file_model->read_file ();
		$this->load->view ( 'system_log', $data );
	}

	// function forgot() {
	// 	$data ['title'] = 'Нууц үгээ мартсан';
	// 	$this->load->view ( 'forgot', $data );
	// }

	// function gen_password($length){
	// 	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	// 	$str = '';
		
	// 	$size = strlen ( $chars );
	// 	for($i = 0; $i < $length; $i ++) {
	// 		$str .= $chars [rand ( 0, $size - 1 )];
	// 	}
	// 	return $str;
	// }

	// function sent_forgot() {
	// 	if (isset ( $_POST ['email'] ))
	// 		$email_id = trim ( $_POST ['email'] );
	// 	else
	// 		$email_id = '';
	// 	$email = $email_id . "@mcaa.gov.mn";
	// 	$wh_arr = array (
	// 			'email' => $email 
	// 	);
	// 	if ($this->main_model->get_row ( 'email', $wh_arr, 'employee' )) {
	// 		$new_password = $this->gen_password ( 5 );
	// 		$body = "Таны $email_id нэртэй ECNS системийн эрхийн нууц үг солигдлоо.\n Та нэвтрэх нэр, нууц үгээ оруулж системд нэвтэрнэ үү! Таны";
	// 		$body = $body . " \n нэвтрэх нэр:$email_id ";
	// 		$body = $body . " \n нууц үг:$new_password ";
	// 		$body = $body . " \n\n Та өөрийн нууц үгийг системд нэвтэрсний дараа Тохиргоо->Хувийн цэс уруу орж солих боломжтой.";
	// 		$body = $body . " \n Хүндэтгэсэн:" . FROM_NAME;
	// 		IF ($this->alert_model->email ( $email, FORGOT_PASSWORD, $body )) {
	// 			$this->db->where ( 'email', $email );
	// 			$result = $this->db->update ( 'employee', array (
	// 					'password' => md5 ( $new_password ) 
	// 			) );
	// 			$data ['msg'] = "Таны имэйл хаяг уруу шинэ нууц үгийг илгээлээ. \n Та имэйлээ шалгаад шинэ нууц үгээр нэвтрэнэ үү!\n <a href ='main/index'> <<--Нэвтрэх хэсэг уруу буцах<<</a>";
	// 		} else
	// 			$data ['msg'] = "Таны имэйл илгээхэд алдаа гарлаа. \n Та ерөнхий инженер болон системийн Админтай холбогдоно уу! \n Ерөний инженерийн эрхээр ажилчдын нууц үгийг өөрчлөх боломжтой \n <a href ='main/index'> <<--Нэвтрэх хэсэг уруу буцах<<</a>";
			
	// 		$this->load->view ( 'forgot', $data );
	// 	} else {
	// 		$data ['msg'] = "Та нэвтрэх нэрээ шалгаад дахин оруулна уу? Ийм имэйл хаяг олдсонгүй";
	// 		$this->load->view ( 'forgot', $data );
	// 	}
	// }


	function help($apps) {
		if ($apps == 'shiftlog') {
			$data ['title'] = 'Гэмтэл дутагдлын тусламж';
			$link = "help/" . $apps;
		} else if ($apps == 'warehouse') {
			$data ['title'] = 'Сэлбэг хангалтын тусламж';
			$link = "help/" . $apps;
		} else {
			$data ['title'] = 'Төлөвлөгөөт ажлын тусламж';
			$link = "help/" . $apps;
		}
		
		$this->load->view ( $link, $data );
	}
}
?>