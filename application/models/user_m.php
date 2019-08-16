<?php
class User_M extends MY_Model
{
	
	protected $_table = 'employee';

	protected $_order_by = 'name';

	public $belongs_to = array( 'section' );

	public $primary_key = 'employee_id';

	public $rules = array(
		'username' => array(
			'field' => 'username', 
			'label' => 'Нэвтрэх нэр', 
			'rules' => 'trim|required'
		),
		'password' => array(
			'field' => 'password', 
			'label' => 'Нууц үг', 
			'rules' => 'trim|required'
		)
	);

	function __construct ()
	{
		$this->load->model ( 'position_model' );
		
		$this->load->model ( 'file_model' );
		parent::__construct();
	}

	public function login ()
	{

		$user = $this->with('section')->get_by(array(
			
				'username' => $this->security->xss_clean($this->input->post('username')),
			
				'password' => $this->hash_2($this->input->post('password')),
			
			), TRUE);

		if (count($user)) {
			//get user_position: role'=>$user->role,
			// Log in user
			$position = $this->position_model->get($user->position_id);
					
			$data = array(
				'username' => $user->username,
				'name' => $user->fullname,
				'email' => $user->email,
				'user_id' => $user->employee_id,				
				'id' => $user->employee_id,				
				'section_id'=>$user->section_id,
				'employee_id' => $user->employee_id,				
				'fullname' => $user->fullname,
				'position'=> $position->name,
				'sec_code'=>$user->section->code,
				'role' => $position->role,				
				'loggedin' => TRUE,
				'access' =>'OK'
			);

			if ($position->role == 'ADMIN' || $position->role == 'CHIEFENG' || $position->role == 'TECHENG' || $position->role == 'SUPERVISOR' || $position->role == 'HEADMAN' || $position->role == 'CHIEF' || $position->role == 'QENG') {
				$this->session->set_userdata ( 'access_type', 'ADMIN' );
			} else {
				$this->session->set_userdata ( 'access_type', 'USER' );
			}
			
			if ($user->section->type=='industry') {
				$this->session->set_userdata ( 'user_type', 'industry' );
			} else
				$this->session->set_userdata ( 'user_type', 'govern' );

			$this->file_model->write_login ();

			$this->session->set_userdata($data);

			return TRUE;
		}else 
		   return FALSE;
	}

	public function logout ()
	{
		$this->session->sess_destroy();
	}

	public function loggedin ()
	{
		return (bool) $this->session->userdata('loggedin');
	}

	public function hash ($string)
	{
		return hash('sha512', $string . config_item('encryption_key'));
	}

	public function hash_2($string){

		return hash('md5', $string); //.config_item('encryption_key')

	}

	function generator($length, $is_lower) {

	   $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	   $str = '';
		
	   $size = strlen ( $chars );

	   for($i = 0; $i < $length; $i ++) {

			$str .= $chars [rand ( 0, $size - 1 )];
	   }

	   return ($is_lower)?  strtolower($str): $str;
	}

}