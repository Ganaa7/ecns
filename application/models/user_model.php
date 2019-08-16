<?php
/*
 * Хэрэглэгчийн тохиргоо хандах table
 * Эрх болон бусад бүх тохиргоонуудыг өгөх, тохируулах
 * модел.
 */
class user_model extends CI_Model {
	public $system;
	public $role;
	function __construct() {
		// Call the Model constructor
		$this->load->database ();
		$this->load->library ( 'session' );
		$this->load->library ( 'config' );
		$this->load->helper ( 'url' );
		$this->load->helper ( 'cookie' );
		$this->load->model ( 'main_model' );
		$this->role = $this->session->userdata ( 'role' );
		$this->system = $this->main_model->get_row ( 'value', array (
				'settings' => 'system' 
		), 'settings' );
	}
	function user_system() {		
		$parent_id = $this->main_model->get_row ( 'id', array (
				'controller' => 'main',
				'functions' => 'index' 
		), 'role_insystem' );


		// herev tuhain hereglegch онцгойлсон тохиолдолд жн: Зөвхөн холбооны хэсэгт харуулах бол
		//section_id-г өгөх хэрэгтэй!
		$this->db->where ( array (
				$this->role => 'Y',
				'parent' => $parent_id			
			) );

		$this->db->order_by ( "order", "asc" );
		$query = $this->db->get ( 'role_insystem' );
		return $query->result ();
	}
	// if userpass and name set right then true else false
	function user_check($username, $password) {
		$Q = $this->db->get_where ( 'view_employee', array (
				'username' => $username,
				'password' => md5 ( $password ) 
		), 1, 0 );
		if ($Q->num_rows () > 0) {
			$row = $Q->row_array ();
			$this->session->set_userdata ( 'loggedin', true);
			$this->session->set_userdata ( 'access', 'OK' );
			$this->session->set_userdata ( 'role', $row ['role'] );
			$this->session->set_userdata ( 'employee_id', $row ['employee_id'] );
			$this->session->set_userdata ( 'fullname', $row ['fullname'] );
			$this->session->set_userdata ( 'position', $row ['position'] );
			$this->session->set_userdata ( 'sec_code', $row ['sec_code'] );
			$this->session->set_userdata ( 'section_id', $row ['section_id'] );
			$this->session->set_userdata ( 'username', $row ['username'] );
			
			if ($row ['role'] == 'ADMIN' || $row ['role'] == 'CHIEFENG' || $row ['role'] == 'TECHENG' || $row ['role'] == 'SUPERVISOR' || $row ['role'] == 'HEADMAN' || $row ['role'] == 'CHIEF' || $row ['role'] == 'QENG') {
				$this->session->set_userdata ( 'access_type', 'ADMIN' );
			} else {
				$this->session->set_userdata ( 'access_type', 'USER' );
			}
			
			if ($row ['sec_code'] == 'COM' || $row ['sec_code'] == 'SUR' || $row ['sec_code'] == 'NAV' || $row ['sec_code'] == 'ELC') {
				$this->session->set_userdata ( 'user_type', 'industry' );
			} else
				$this->session->set_userdata ( 'user_type', 'govern' );
			
			return TRUE;
		} else
			return FALSE;
	}
	function get_user_menu($employee_id) {
		return $this->user_model->display_menu ( $apps, $role, 0, 1 );
	}
	function display_menu($apps, $role, $parent, $level) {
		static $menu;
		static $status = 1;
		$sql = "SELECT a.id, a.menu, a.link, a.controller, a.functions, a.is_link, temp.Count FROM role_insystem a  
                     LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count 
                     FROM role_insystem GROUP BY parent) as temp ON a.id = temp.parent 
                  WHERE a.$role='Y' AND a.parent=" . $parent . " AND a.apps ='$apps'
                     ORDER BY a.order";
		$query = $this->db->query ( $sql );
		if ($status == 1) {
			$menu .= "<ul class='nav-sub clearfix'>";
			$status = 2;
		} else
			$menu .= "<ul>";
		
		foreach ( $query->result () as $row ) {
			if ($row->Count > 0) {
				if($row->is_link=='Y') $menu .= "<li>" . $row->link;
				else $menu .= "<li>"."<a href='".base_url().$row->controller."/".$row->functions."'>".$row->menu."</a>";
				$this->user_model->display_menu ( $apps, $role, $row->id, $level + 1 );
				$menu .= "</li>";
			} elseif ($row->Count == 0) {
				if($row->is_link=='Y') $menu .= "<li>" . $row->link. "</li>";
				else $menu .= "<li>"."<a href='".base_url().$row->controller."/".$row->functions."'>".$row->menu."</a></li>";
				//$menu .= "<li>" . $row->link . "</li>";
			} else	;
		}
		$menu .= "</ul>";
		
		return $menu;
	}
	
	// Event-n table-diig uusgej hadgalah EventLog-t hereglegdene
	function set_event_table($role, $sec_code) {
		if (isset ( $role )) {
			switch ($role) {
				case 'CHIEF' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'UNITCHIEF' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'ENG' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'SUPENG' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				default :
					$table = 'view_el_events';
					break;
			}
		} else {
			switch ($sec_code) {
				case 'COM' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'SUR' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'NAV' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				case 'ELC' :
					$table = 'view_el_events_' . strtolower ( $sec_code );
					break;
				
				default :
					$table = 'view_el_events';
					break;
			}
		}
		return $table;
	}
	function set_user_table($role, $sec_code) {
		switch ($role) {
			case 'CHIEF' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'UNITCHIEF' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'ENG' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'SUPENG' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			default :
				$table = 'view_logs';
				break;
		}
		return $table;
	}
	function set_table($sec_code) {
		switch ($sec_code) {
			case 'COM' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'SUR' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'NAV' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			case 'ELC' :
				$table = 'view_logs_' . strtolower ( $sec_code );
				break;
			
			default :
				$table = 'view_logs';
				break;
		}
		return $table;
	}
	function get_section_name($sec_code) {
		switch ($sec_code) {
			case 'COM' :
				$table = 'Холбооны хэсэг';
				break;
			case 'SUR' :
				$table = 'Бодит Ажиглалтын хэсэг';
				break;
			case 'NAV' :
				$table = 'Навигацын хэсэг';
				break;
			case 'ELC' :
				$table = 'Цахилгааны хэсэг';
				break;
		}
		return $table;
	}
	function check_email($email) {
		// $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		//
		// $regex='/^[a-zA-Z0-9_-.+]+@[a-zA-Z]+(\.[a-zA-Z]{4}+)*(\.[a-z]{2})$/';
		$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([a-z0-9]+)*(\.[a-z]{3}+)*(\.[a-z]{2})$/";
		if (preg_match ( $regex, $email ))
			return TRUE;
		else
			FALSE;
	}
	function setGroup($sec_code) {
		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'ELC' || $sec_code == 'SUR') {
			switch ($this->role) {
				case 'ENG' :
					return 'ENG';
					break;
				case 'SUPENG' :
					return 'ENG';
					break;
				case 'UNITCHIEF' :
					return 'ENG';
					break;
				case 'TECH' :
					return 'ENG';
					break;
				case 'CHIEF' :
					return 'ENG_CHIEF';
					break;
			}
		} else {
			switch ($this->role) {
				case 'CHIEF' :
					return 'CHIEF';
					break;
				case 'SUPERVISOR' :
					return 'CHIEF';
					break;
				case 'ADMIN' :
					return 'CHIEF';
					break;
				default :
					return 'USER';
					break;
			}
		}
	}
}
?>