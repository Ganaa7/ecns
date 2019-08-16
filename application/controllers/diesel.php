<?php

if (! defined ( 'BASEPATH' )) 	exit ( 'No direct script access allowed' );

class diesel extends CNS_Controller {

	public function __construct() {

		parent::__construct ();

		$this->load->model ( 'diesel_model' );
		
		$this->load->library ( 'eDiesel' );

		$this->employee_id = $this->session->userdata ( 'employee_id' );

		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'diesel', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Дизель генераторуудын түлш' );

		$this->config->set_item ( 'module_menu_link', 'index' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );			

	}

	
	function index() {
		$user_id = $this->session->userdata ( 'employee_id' );
		$data ['library_src'] = $this->javascript->external ( base_url().'assets/js/diesel.js', TRUE );
		$cert = new eDiesel ();
		$cert->set_user ( $user_id );
		$cert->set_role ( $this->role );
		$out = $cert->run ();
		
		if ($out->view) {
			$data ['out'] = $out;
			$data ['page'] = $out->page;
			$data ['title'] = $out->title;
			$data ['action'] = $this->main_model->get_authority ( 'diesel', 'diesel', 'add', $this->role );			
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
	function motors() {
		try {
			if ($this->main_model->get_authority ( 'diesel', 'diesel', 'motors', $this->role ) == 'motors') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'diesel' );
				$role_array = array (
						'ENG',
						'SUPENG',
						'TECH',
						'UNITCHIEF' 
				);
				$crud->columns ( 'location_id', 'main_equipment_id', 'equipment_id', 'power', 'consumption', 'bank', 'bank_id' );
				$crud->fields ( 'location_id', 'main_equipment_id', 'equipment_id', 'power', 'consumption', 'bank', 'bank_id' );
				$crud->set_subject ( 'Дизель генераторууд' );
				
				$crud->set_relation ( 'main_equipment_id', 'equipment2', 'equipment' );
				$crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );
				$crud->set_relation ( 'location_id', 'location', 'name' );
				$crud->set_relation ( 'bank_id', 'banks', '{baiguulamj}-{capacity}' );
				// $crud->unset_back_to_list();
				$crud->display_as ( 'location_id', 'Байршил' )->display_as ( 'main_equipment_id', 'Байгууламж' )->display_as ( 'equipment_id', 'Дизель генератор' )->display_as ( 'power', 'Чадал' )->display_as ( 'consumption', '1 цагт зарцуулагдах түлшний хэмжээ' )->display_as ( 'bank', 'Банкны багтаамж /литр/' )->display_as ( 'bank_id', 'Нэмэлт банк' );
				
				$crud->required_fields ( 'location_id', 'main_equipment_id', 'equipment_id', 'power', 'consumption', 'bank' );
				$crud->order_by ( 'main_equipment_id', 'desc' );
				$output = $crud->render ();
				$this->load->view ( 'diesel/output', $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	function banks() {
		try {
			if ($this->main_model->get_authority ( 'diesel', 'diesel', 'banks', $this->role ) == 'banks') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'banks' );
				$crud->columns ( 'baiguulamj', 'capacity', 'now' );
				$crud->columns ( 'baiguulamj', 'capacity', 'now' );
				$crud->set_subject ( 'Байгууламжид банк байршуулах' );
				$crud->unset_add_fields ( 'now' );
				$crud->unset_edit_fields ( 'now' );
				
				$crud->display_as ( 'baiguulamj', 'Зориулалт' )->display_as ( 'capacity', 'Байршуулах нөөц банкны багтаамж /литр/' )->display_as ( 'bank', 'Байгаа түлшний хэмжээ' );
				$crud->required_fields ( 'capacity' );
				
				$output = $crud->render ();
				$this->load->view ( 'diesel/output', $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	function history(){
		$data ['page'] = '/diesel/history';
		$data ['title'] = 'Банкны түлшний түүх';
		 //get_dropdown_concat($select, $col, $key, $table, $where = null) 		
		$banks= $this->diesel_model->get_dropdown_concat("location, ' ', main_equipment, ' дизел генертор ', capacity,  ' л банк '", 'name', 'bank_id', 'view_diesel', null);
		 //sort($banks);
		//$data['banks'] =asort($banks);
		$data['banks'] =$banks;
		$bank_id = $this->input->get_post('bank_id', TRUE);

		if($bank_id){
			$data['bank_id']=$bank_id;			
			//get_result($array, $table) {
			//function get_query($select, $table, $where = null, $sidx = null, $sord = null, $start = null, $limit = null) {
			$sql = "SELECT a.*, b.fullname as checkedby FROM bank_fuel a left join `view_employee` `b` ON a.checkedby_id = b.employee_id where a.bank_id = $bank_id
			order by a.datetime desc";
			$query =$this->db->query($sql);			
			//echo $this->diesel_model->last_query();
			$data['result'] =$query->result();
		}else
			$data['bank_id']=0;
		//function get_dropdown_by($column, $key, $where = null, $table) {
		$this->load->view('index',$data);		
	}
}
?>