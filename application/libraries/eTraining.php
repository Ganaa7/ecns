<?php

class eTraining_Modeler {

	private $user_role;

	public $user_id;

	public $my_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?

	public $section_id;
	
   protected $trainer_m;

   protected $location;
   
   protected $position_history;
   
   protected $exam_history;
   
   protected $license_type;
   
   protected $employee;
   
	 protected $position;
	 
   protected $remark;
   
   protected $now;
	 
	 protected $Obj;


	function set_user($user_id) {

		$this->user_id = $user_id;

		// if(!$this->user_id)
		return true;

	}

	protected function set_user_role($role) {

		$this->user_role = $role;

		return true;

	}

	protected function get_user_role() {

		return $this->user_role;

	}

	protected function init_table() {

		$ci = &get_instance ();

		$this->Obj=$ci;

		$ci->load->model ( 'my_model_old' );

		$ci->load->model ( 'trainer_model' );
		
		$ci->load->model ( 'position_history_model' );
		
		$ci->load->model ( 'exam_history_model' );

		$ci->load->model ( 'employee_model' );

		$ci->load->model ( 'location_model' );

		$this->location = new location_model ();	

		$ci->load->model ( 'remark_model' );

		$this->remark = new remark_model ();	
		
		$ci->load->model ( 'position_model' );

		$this->my_model = new my_model_old ();	

		$ci->load->model ( 'print_history_model' );

		$this->print_history = new print_history_model();
		
		$ci->load->model ( 'license_type_model' );

		$this->license_type = new License_type_model();

		$this->trainer_m = new trainer_model ();
		
		$this->position_history = new position_history_model ();
		
		$this->exam_history = new exam_history_model ();
		
		$this->employee = new employee_model ();
		
		$this->position = new position_model ();

		date_default_timezone_set ( ECNS_TIMEZONE );
		
		$this->now = date ( 'Y-m-d H:i:s' );

		return true;
	}

	protected function set_table($table) {

		if ($this->my_model->get_table ())

			$this->my_model->unset_table ();

		$this->my_model->set_table ( $table );

		return true;

	}

	protected function get_location() {

		return $this->my_model->get_select ( 'name' );

	}

	protected function get_select($name, $table = null, $where = null) {

		if ($table) {

			$this->my_model->set_table ( $table );

		}

		if ($where)

			return $this->my_model->get_select ( $name, $where );

		else

			return $this->my_model->get_select ( $name );
	}
	
	// Хэрэв view bval
	protected function get_all($array = null, $table = null) {

		return $this->my_model->get_all ( $array, $table );

	}

	protected function get_query($select, $table = null, $where = null, $sidx = null, $sord = null, $start = null, $limit = null) {

		if ($table) {

			$this->my_model->set_table ( $table );

		} else {

			$this->my_model->set_table ( 'log' );

		}

		return $this->my_model->get_query ( $select, $table, $where, $sidx, $sord, $start, $limit );
	}
}

class eTraining_Driver extends eTraining_Modeler {

	private $form_validation;

	private $validation;

	private $input;

	public $ops = array (
			'eq' => '=', // equal
			'ne' => '<>', // not equal
			'lt' => '<', // less than
			'le' => '<=', // less than or equal
			'gt' => '>', // greater than
			'ge' => '>=', // greater than or equal
			'bw' => 'LIKE', // begins with
			'bn' => 'NOT LIKE', // doesn't begin with
			'in' => 'LIKE', // is in
			'ni' => 'NOT LIKE', // is not in
			'ew' => 'LIKE', // ends with
			'en' => 'NOT LIKE', // doesn't end with
			'cn' => 'LIKE', // contains
			'nc' => 'NOT LIKE' 
	);

	// protected filter function
	private function filter($filters) {

		$db = get_instance()->db->conn_id;  

		$filters = json_decode ( $filters );

		$where = " where ";

		$whereArray = array ();

		$rules = $filters->rules;

		$groupOperation = $filters->groupOp;

		foreach ( $rules as $rule ) {

			$fieldName = $rule->field;

			$fieldData = mysqli_real_escape_string ($db, $rule->data );

			switch ($rule->op) {

				case "eq" :

					$fieldOperation = " = '" . $fieldData . "'";

					break;

				case "ne" :

					$fieldOperation = " != '" . $fieldData . "'";

					break;

				case "lt" :

					$fieldOperation = " < '" . $fieldData . "'";

					break;

				case "gt" :

					$fieldOperation = " > '" . $fieldData . "'";

					break;

				case "le" :

					$fieldOperation = " <= '" . $fieldData . "'";

					break;

				case "ge" :

					$fieldOperation = " >= '" . $fieldData . "'";

					break;

				case "nu" :
					$fieldOperation = " = ''";
					break;
				case "nn" :
					$fieldOperation = " != ''";
					break;
				case "in" :
					$fieldOperation = " IN (" . $fieldData . ")";
					break;
				case "ni" :
					$fieldOperation = " NOT IN '" . $fieldData . "'";
					break;
				case "bw" :
					$fieldOperation = " LIKE '" . $fieldData . "%'";
					break;
				case "bn" :
					$fieldOperation = " NOT LIKE '" . $fieldData . "%'";
					break;
				case "ew" :
					$fieldOperation = " LIKE '%" . $fieldData . "'";
					break;
				case "en" :
					$fieldOperation = " NOT LIKE '%" . $fieldData . "'";
					break;
				case "cn" :
					$fieldOperation = " LIKE '%" . $fieldData . "%'";
					break;
				case "nc" :
					$fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
					break;
				default :
					$fieldOperation = "";
					break;
			}

			if ($fieldOperation != "")

				$whereArray [] = $fieldName . $fieldOperation;
		}

		if (count ( $whereArray ) > 0) {

			$where .= join ( " " . $groupOperation . " ", $whereArray );

		} else {

			$where = "";
			
		}
		return $where;
	}

	private function get_where_ids($section_id = null, $sector_id = null, $equipment_id = null, $log = null, $date_option = null, $start_dt = null, $end_dt = null) {

		$where = ' WHERE ';

		if ($section_id)

			$where .= "section_id =$section_id AND ";

		if ($sector_id) {

			$where .= "sector_id =$sector_id AND ";

		}

		if ($equipment_id) {

			$where .= "equipment_id =$equipment_id AND ";

		}

		if ($log) {

			if ($log == 'N')

				$where .= " closed IN ('A', 'C') AND ";

			else

				$where .= " closed IN ('N', 'Y') AND ";
		}

		if ($start_dt && $end_dt) {

			$where .= " ( DATE_FORMAT($date_option, '%Y-%m-%d') BETWEEN '$start_dt' AND '$end_dt') AND ";

		} elseif ($start_dt) {

			$where .= " DATE_FORMAT($date_option, '%Y-%m-%d') >= '$start_dt' AND ";

		} else if ($end_dt) {

			$where .= " DATE_FORMAT($date_option, '%Y-%m-%d') <= '$end_dt' AND ";

		}

		return substr ( $where, 0, strlen ( $where ) - 4 );
	}

	private function get_where_clause($col, $oper, $val) {

		if ($oper == 'bw' || $oper == 'bn')

			$val .= '%';

		if ($oper == 'ew' || $oper == 'en')

			$val = '%' . $val;

		if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')

			$val = '%' . $val . '%';

		return " WHERE $col {$this->ops[$oper]} '$val' ";
	}
	
	// insert validation when insert data
	protected function insert_validation() {
		
		$validation_result = ( object ) array (
				'success' => false 
		);
		// validation fields =
		// required fields heregtei
		// add fields heregtei
		$form_validation = $this->form_validation ();

		$form_validation->set_rules ( 'register', 'Регистерийн дугаар', 'required' );

		$form_validation->set_rules ( 'lastname', 'Эцэг эхийн нэр', 'required' );

		$form_validation->set_rules ( 'firstname', 'Өөрийн нэр', 'required' ); //

		$form_validation->set_rules ( 'birthdate', 'Төрсөн огноо', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		$form_validation->set_rules ( 'org_id', 'Байгууллага', 'required|is_natural_no_zero' );
		
		$form_validation->set_rules ( 'location_id', 'Байршил', 'is_natural_no_zero' );

		$form_validation->set_rules ( 'position_id', 'Албан тушаал', 'required|is_natural_no_zero' );

		$form_validation->set_rules ( 'address', 'Гэрийн хаяг', 'required' );

		$form_validation->set_rules ( 'mobile', 'Утасны дугаар', 'required' );

		$form_validation->set_rules ( 'email', 'Имэйл хаяг', 'required' );

		$form_validation->set_rules ( 'rel_phone', 'Холбогдох хүний утас', 'required' );

		$form_validation->set_rules ( 'education', 'Боловсрол', 'required' );

		$form_validation->set_rules ( 'license_no', 'Мэргэжлийн үнэмлэх', 'required' );

		$form_validation->set_rules ( 'issued_date', 'Олгосон хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		$form_validation->set_rules ( 'valid_date', 'Хүчинтэй хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		$form_validation->set_rules ( 'school[]', 'Сургууль', 'required' );

		$form_validation->set_rules ( 'enter_dt[]', 'Орсон огноо', 'required|callback_compare_date' );

		$form_validation->set_rules ( 'grade_dt[]', 'Төгссөн огноо', 'required|callback_compare_date' );

		$form_validation->set_rules ( 'detail[]', 'Эзэмшсэн мэргэжил, боловсрол зэрэг', 'required' );

		$form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );

		$form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );

		// run form validation
		if ($form_validation->run ()) {

			$validation_result->success = true;

		} else

			$validation_result->error_message = $form_validation->error_string ();

		$validation_result->error_fields = $form_validation->_error_array;

		return $validation_result;
	}

	protected function compare_date() {

		$in = $this->form_inputs ();

		$form_validation = $this->form_validation ();

		$start_date = strtotime ( $in->get_post ( 'enter_dt[]' ) );

		$end_date = strtotime ( $in->get_post ( 'grade_dt[]' ) );

		
		if ($end_date > $start_date)

			return True;

		else {

			$form_validation->set_message ( 'compareDate', '%s should be greater than Contract Start Date.' );
			return False;
		}
	}
	
	// get inputs here
	protected function get_input_data() {
		// inputs declaretion from ci
		$in = $this->form_inputs ();

		$data ['trainer_id'] = $in->get_post ( 'trainer_id' );

		$data ['register'] = $in->get_post ( 'register' );

		$data ['lastname'] = $in->get_post ( 'lastname' );

		$data ['firstname'] = $in->get_post ( 'firstname' );

		$data ['gender'] = $in->get_post ( 'gender' );

		$data ['birthdate'] = $in->get_post ( 'birthdate' );

		$data ['organization_id'] = $in->get_post ( 'org_id' );

		$data ['location_id'] = $in->get_post ( 'location_id' );

		$data ['position_id'] = $in->get_post ( 'position_id' );

		$data ['address'] = $in->get_post ( 'address' );

		$data ['mobile'] = $in->get_post ( 'mobile' );

		$data ['phone'] = $in->get_post ( 'phone' );

		$data ['email'] = $in->get_post ( 'email' );

		$data ['rel_type'] = $in->get_post ( 'rel_type' );

		$data ['rel_phone'] = $in->get_post ( 'rel_phone' );

		$data ['education'] = $in->get_post ( 'education' );

		$data ['license_no'] = $in->get_post ( 'license_no' );

		$data ['license_type_id'] = $in->get_post ( 'license_type_id' );

		$data ['aa_license'] = $in->get_post ( 'license_type_id' );
		
		$data ['issued_date'] = $in->get_post ( 'issued_date' );

		$data ['valid_date'] = $in->get_post ( 'valid_date' );

		$data ['expired_date'] = date ( 'Y-m-d', strtotime ( $in->get_post ( 'valid_date' ) . "-1 month" ) );
		
		$school = $in->get_post ( 'school' );

		$entered = $in->get_post ( 'enter_dt' );

		$finished = $in->get_post ( 'grade_dt' );

		$education = $in->get_post ( 'detail' );

		$training = array ();

		$data ['edu_bind'] = array ();
		
		for($i = 0; $i < count ( $in->get_post ( 'school' ) ); $i ++) {

			$training ['trainer_id'] = $data ['trainer_id'];

			$training ['school'] = $school [$i];

			$training ['entered'] = $entered [$i];

			$training ['finished'] = $finished [$i];

			$training ['education'] = $education [$i];

			array_push ( $data ['edu_bind'], $training );
		}
		

		return $data;
	}
	
	// edit data trainig update function here
	protected function update() {

		$CI = &get_instance ();

		// print_r($CI->input->get_post('time'));
		// 1. check validation
		$validation_result = $this->insert_validation ();

		if ($validation_result->success) {

			$data = $this->get_input_data ();

			// get input datas update related tables
			$trainer_id = $data ['trainer_id'];

			$this->set_table ( 'trainer' );

			$license = $data ['license'];

			// delete db from trainer_education insert it :P
			$education_bind = $data ['edu_bind'];

			unset ( $data ['trainer_id'] );

			unset ( $data ['edu_bind'] );

			unset ( $data ['license'] );
			
			$CI->db->trans_begin ();

			$CI->db->where ( 'trainer_id', $trainer_id );

			$CI->db->update ( 'trainer', $data );
			// license update hiine
			$CI->db->where ( 'trainer_id', $trainer_id );

			$CI->db->update ( 'license', $license );

			$CI->db->delete ( 'trainer_education', array (
					'trainer_id' => $trainer_id 
			) );

			$CI->db->insert_batch ( 'trainer_education', $education_bind );
			
			if ($CI->db->trans_status () === FALSE) {

				$CI->db->trans_rollback ();

				$return = array (
					'status' => 'failed',
					'message' => 'Хадгалахад алдаа гарлаа',
					'error_msg' => $CI->db->last_query () 
				);

			} else {

				$CI->db->trans_commit ();

				$return = array (
					'status' => 'success',
					'message' => 'Амжилттай хадгаллаа',
					'error_msg' => $this->my_model->last_query () 
				);
			}
		} else {
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 
			);
		}
		return $return;
	}
	
	// insert data
	protected function insert() {

		$validation_result = $this->insert_validation ();

		if ($validation_result->success) {

			// get_input data here
			$data = $this->get_input_data ();

			$education = array ();

			$education = $data ['edu_bind'];

			unset ( $data ['edu_bind'] );
			
			$this->my_model->set_table ( 'trainer' );

			if ($this->my_model->insert ( $data ) !== FALSE) {

				$return = array (
						'status' => 'success',
						'message' => 'Бүртгэлийг шинээр нэмлээ!' 
				);
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Хадгалахад алдаа гарлаа' 
				);
			}
		} else {
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 
			);
		}
		return $return;
	}

	protected function grid() {

		$CI = &get_instance ();

		// here is calling grid
		$page = $CI->input->get_post ( 'page' );

		$limit = $CI->input->get_post ( 'rows' );

		$sidx = $CI->input->get_post ( 'sidx' );

		if (! $sidx) $sidx = 1;
		
		$sord = $CI->input->get_post ( 'sord' );

		$table = $this->my_model->check_table ( 'view_training' );

		$filters = $CI->input->get_post ( 'filters' );

		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty

		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;

		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;

		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		$section_id = $CI->input->get_post ( 'section_id' );

		$sector_id = $CI->input->get_post ( 'sector_id' );

		$equipment_id = $CI->input->get_post ( 'equipment_id' );

		$log = $CI->input->get_post ( 'log' );

		$date_option = $CI->input->get_post ( 'date_option' );

		$start_dt = $CI->input->get_post ( 'start_dt' );

		$end_dt = $CI->input->get_post ( 'end_dt' );
		
		if (($search == 'true') && ($filters != "")) {

			 $where = $this->filter ( $filters );

			 $where .= " and status is null ";    

		}else {
			
			 $where = " Where status is null ";    
		}

		date_default_timezone_set ( ECNS_TIMEZONE );

		$data = date ( "Y-m-d" );

		// Хэрэв тухайн хэрэглэгч Chief, UNITchief, HEADMAN, chiefeng, techeng, busad tohioldol filter hiij tuhain hereglegchiig haruulahgui 
		// $this->filterby()

		if ($where)	$query = $this->get_query ( " count(*) as count ", 'view_trainer', $where);
		// " AND ". $this->filterby() );

		else $query = $this->get_query ( " count(*) as count ", 'view_trainer');
			//" WHERE".$this->filterby() );

			// echo $this->my_model->last_query ();
		
		if ($query->num_rows () > 0) {

			$countRow = $query->row_array ();

			$count = $countRow ['count'];
		}
		
		if ($count > 0) {

			$total_pages = ceil ( $count / $limit );

		} else {

			$total_pages = 0;

		}
		
		if ($page > $total_pages) $page = $total_pages;

		$start = $limit * $page - $limit;

		if ($start < 0)	$start = 0;
		
		$Qry = $this->get_query ( ' * ', 'view_trainer', $where, $sidx, $sord, $start, $limit );

		$qry2 = $this->my_model->last_query ();
		
		$json = array ();

		$row_bind = array ();

		$crow = array ();
		
		$json ['page'] = $page;

		$json ['total'] = $total_pages;

		$json ['records'] = $count;
		
		foreach ( $Qry->result () as $row ) {

			$crow ['trainer_id'] = $row->trainer_id;

			$crow ['firstname'] = $row->firstname;

			$crow ['fullname'] = $row->fullname;

			$crow ['lastname'] = $row->lastname;

			$crow ['gender'] = $row->gender;

			$crow ['occupation'] = $row->occupation;

			$crow ['location'] = $row->location;

			$crow ['license_no'] = $row->license_no;

			$crow ['issued_date'] = $row->issued_date;

			$crow ['valid_date'] = $row->valid_date;

			$crow ['expired_date'] = $row->expired_date;

			$crow ['email'] = $row->email;

			$crow ['phone'] = $row->phone;

			$crow ['up_date'] = $row->up_date;

			$crow ['license_type'] = $row->license_type;

			$crow ['license_equipment'] = $row->license_equipment;

			$crow ['position'] = $row->position;

			array_push ( $row_bind, $crow );
		}

		$json ['rows'] = $row_bind;

		$Qry->free_result ();

		return json_encode ( $json );
	}

	protected function out_grid() {

		$CI = &get_instance ();

		// here is calling grid
		$page = $CI->input->get_post ( 'page' );

		$limit = $CI->input->get_post ( 'rows' );

		$sidx = $CI->input->get_post ( 'sidx' );

		if (! $sidx) $sidx = 1;
		
		$sord = $CI->input->get_post ( 'sord' );

		$table = $this->my_model->check_table ( 'view_training' );

		$filters = $CI->input->get_post ( 'filters' );

		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty

		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;

		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;

		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		$section_id = $CI->input->get_post ( 'section_id' );

		$sector_id = $CI->input->get_post ( 'sector_id' );

		$equipment_id = $CI->input->get_post ( 'equipment_id' );

		$log = $CI->input->get_post ( 'log' );

		$date_option = $CI->input->get_post ( 'date_option' );

		$start_dt = $CI->input->get_post ( 'start_dt' );

		$end_dt = $CI->input->get_post ( 'end_dt' );
		
		if (($search == 'true') && ($filters != "")) {

			 $where = $this->filter ( $filters );

			 $where .= " and status = 'outservie' ";    

		}else {
			
			 $where = " Where status ='outservice' ";    
		}

		date_default_timezone_set ( ECNS_TIMEZONE );

		$data = date ( "Y-m-d" );

		// Хэрэв тухайн хэрэглэгч Chief, UNITchief, HEADMAN, chiefeng, techeng, busad tohioldol filter hiij tuhain hereglegchiig haruulahgui 
		// $this->filterby()

		if ($where)	$query = $this->get_query ( " count(*) as count ", 'view_trainer', $where);
		// " AND ". $this->filterby() );

		else $query = $this->get_query ( " count(*) as count ", 'view_trainer');
			//" WHERE".$this->filterby() );

			// echo $this->my_model->last_query ();
		
		if ($query->num_rows () > 0) {

			$countRow = $query->row_array ();

			$count = $countRow ['count'];
		}
		
		if ($count > 0) {

			$total_pages = ceil ( $count / $limit );

		} else {

			$total_pages = 0;

		}
		
		if ($page > $total_pages) $page = $total_pages;

		$start = $limit * $page - $limit;

		if ($start < 0)	$start = 0;
		
		$Qry = $this->get_query ( ' * ', 'view_trainer', $where, $sidx, $sord, $start, $limit );

		$qry2 = $this->my_model->last_query ();
		
		$json = array ();

		$row_bind = array ();

		$crow = array ();
		
		$json ['page'] = $page;

		$json ['total'] = $total_pages;

		$json ['records'] = $count;
		
		foreach ( $Qry->result () as $row ) {

			$crow ['trainer_id'] = $row->trainer_id;

			$crow ['firstname'] = $row->firstname;

			$crow ['fullname'] = $row->fullname;

			$crow ['lastname'] = $row->lastname;

			$crow ['gender'] = $row->gender;

			$crow ['occupation'] = $row->occupation;

			$crow ['location'] = $row->location;

			$crow ['license_no'] = $row->license_no;

			$crow ['issued_date'] = $row->issued_date;

			$crow ['valid_date'] = $row->valid_date;

			$crow ['expired_date'] = $row->expired_date;

			$crow ['email'] = $row->email;

			$crow ['phone'] = $row->phone;

			$crow ['up_date'] = $row->up_date;

			$crow ['license_type'] = $row->license_type;

			$crow ['license_equipment'] = $row->license_equipment;

			$crow ['position'] = $row->position;

			array_push ( $row_bind, $crow );
		}

		$json ['rows'] = $row_bind;

		$Qry->free_result ();

		return json_encode ( $json );
	}

	protected function delete() {

		$CI = &get_instance ();

		$trainer_id = $CI->input->post ( 'id' );

		$this->my_model->set_table ( 'training_attendance' );

		if ($this->my_model->get_row ( 'id', array (

				'trainer_id' => $trainer_id 

		) ))
			
			$sql = "DELETE trainer, training_attendance FROM trainer INNER JOIN training_attendance 
                WHERE trainer.trainer_id= training_attendance.trainer_id and trainer.trainer_id =$trainer_id";
		else

			$sql = "DELETE from trainer where trainer_id = $trainer_id";
		
		if ($this->my_model->exc_query ( $sql )) {
			// The user was successfully removed from the table
			$return = array (
					'status' => 'success',
					'message' => 'ИТА-ны бүртгэлийг амжилттай устгагдлаа!' 
			);

		} else {

			$return = array (
					'status' => 'failed',
					'message' => $CI->input->post ( 'id' ) . ' устгахад алдаа гарлаа!' 
			);

		}
		return $return;
	}

	protected function get($trainer_id) {

		$data = $this->get_all ( array ('trainer_id' => $trainer_id ), 'view_trainer' );

		// training_attendance-с trainer_id -r training_id-г авна
		// $data = $this->get_all(array('trainer_id' =>$trainer_id), 'view_training_attendance');
		// trainer утгуудаас өгөгдлүүдийг авч хэрэглэнэ.
		return $data;
	}

	protected function filterby(){

		// $this->user_role
		$roles = array("UNITCHIEF", "HEADMAN", "CHIEFENG", "CHIEF", "ADMIN");

		if (!in_array($this->get_user_role(), $roles)){


			// if($user_id)

  			   $where = " trainer_id = $this->user_id";

  			// else

  			// 	$where = " trainer_id = ";

		}else 
			$where = " ";

		return $where;
	}

	protected function get_attendance($trainer_id) {

		$training = array ();

		$rows = array ();

		// here is get result eductain		
		$this->my_model->set_table ( 'view_training_attendace' );

		$WHERE = 'trainer_id=' . $trainer_id;

		$query = $this->my_model->get_simple ( $WHERE );

		foreach ( $query->result () as $row ) {

			$rows ['training'] = $row->training;

			$rows ['type'] = $row->type;

			$rows ['date'] = $row->date;

			$rows ['time'] = $row->time;

			$rows ['place'] = $row->place;

			$rows ['orderN'] = $row->orderN;

			array_push ( $training, $rows );
		}

		$json ['trainings'] = $training;

		$query->free_result ();

		return json_encode ( $json );

	}

	protected function get_education($trainer_id) {

		$education = array ();

		$rows = array ();

		$this->my_model->set_table ( 'trainer_education' );

		$WHERE = 'trainer_id=' . $trainer_id;

		$query = $this->my_model->get_simple ( $WHERE );

		foreach ( $query->result () as $row ) {

			$rows ['school'] = $row->school;

			$rows ['entered'] = $row->entered;

			$rows ['finished'] = $row->finished;

			$rows ['education'] = $row->education;

			array_push ( $education, $rows );

		}

		$json ['trainer_education'] = $education;

		$query->free_result ();

		return json_encode ( $json );
	}

	protected function action() {

		$res_array = array ();

		$CI = &get_instance ();

		$res_array = $this->my_model->get_actionby ( $this->get_role (), 'training', 'training' );

		return $res_array;
	}

	protected function edit() {

		$data = $this->get_all ( array (
				'trainer_id' => $trainer_id 
		), 'view_trainer' );

		return $data;
	}

	protected function form_validation() {

		if ($this->form_validation === null) {

			$this->form_validation = new CS_CRUD_Form_validation ();

			$CI = &get_instance ();

			$CI->load->library ( 'form_validation' );

			$CI->form_validation = $this->form_validation;

		}

		return $this->form_validation;
	}

	protected function form_inputs() {

		if ($this->input == null) {

			$this->input = new CS_CRUD_inputs ();

		}

		return $this->input;
	}

	protected function get_trainer(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		return $this->trainer_m->get($current_id);

	}	

	protected function get_pos_history(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		return $this->position_history->with('position_detail')->with('employee')->get_many_by('employee_id', $current_id);

	}		

	protected function pos_history_count(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		return $this->position_history->count_by('employee_id', $current_id);

	}

	protected function get_exam_history(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		return $this->exam_history->with('license_equipment')->get_many_by('employee_id', $current_id);

	}	

	protected function get_exam_page(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		$exams = $this->exam_history->with('license_equipment')->get_many_by('employee_id', $current_id);
	}	


	protected function exam_history_count(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		return $this->exam_history->count_by('employee_id', $current_id);

	}

	// Add history values
	protected function add_pos_history(){

		$ci = &get_instance ();

		//collect data
		$employee_id = $ci->input->get_post('employee_id');
		
		$position_id = $ci->input->get_post('position_id');
		
		$appointed_date = $ci->input->get_post('appointed_date');

		//Check if position history
		$ph = $this->position_history->with('position_detail')->with('employee')->get_by(array('employee_id'=>$employee_id, 'position_id' =>$position_id));

		// var_dump($dd);

		// $this->pos_history->validate
		if($this->position_history->validate($this->position_history->validate)){

			//add position history			

			if($this->position_history->insert(array('employee_id'=>$employee_id, 'position_id'=>$position_id, 'appointed_date'=>$appointed_date), FALSE)){

		      $return = array (
	              'status' => 'success',
	              'message' => 'Амжилттай хадгаллаа'
	        );

			}else{

			  $return = array (
	                  'status' => 'failed',
	                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
	               );
			}	

		}else{

        $return = array (
           'status' => 'failed',
           'message' => validation_errors ( '', '<br>' )
        );
		}

	   return json_encode($return);

	}

	// Delete history values

	protected function del_pos_history(){

		$ci = &get_instance ();

		if($delete_id = $this->position_history->delete($ci->input->get_post('id'))){

			    $return = array (
	              'status' => 'success',
	              'message' => 'Ажилтны түүхийг амжилттай устгалаа'
	        );
		}else{

			  $return = array (
               'status' => 'failed',
                 'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );
		}

		 return json_encode($return);
	}

	// Delete remark values

	protected function destroy_remark(){

		$ci = &get_instance ();

		if($delete_id = $this->remark->delete($ci->input->get_post('id'))){

			    $return = array (
	              'status' => 'success',
	              'message' => 'Тусгай тэмдэглэлийг амжилттай устгалаа'
	        );
		}else{

			  $return = array (
							 'status' => 'failed',
							 
               'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );
		}

		 return json_encode($return);
	}


	protected function del_exam_history(){

		$ci = &get_instance ();

		if($delete_id = $this->exam_history->delete($ci->input->get_post('id'))){

			    $return = array (
	              'status' => 'success',
	              'message' => 'Шалтгалтын түүхийг амжилттай устгалаа'
	        );
		}else{

			  $return = array (
               'status' => 'failed',
                 'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );
		}

		 return json_encode($return);
	}



	// Add history values

	protected function add_exam_history(){

		$ci = &get_instance ();

		//collect data
		$employee_id = $ci->input->get_post('employee_id');
		
		$license_equipment_id = $ci->input->get_post('license_equipment_id');
		
		$exam_date = $ci->input->get_post('exam_date');

		$valid_date = $ci->input->get_post('valid_date');

		//Check if position history
		$exam_history = $this->exam_history->with('license_equipment')->get_by(array('employee_id'=>$employee_id, 'license_equipment_id' =>$license_equipment_id));

		// $this->pos_history->validate
		if($this->exam_history->validate($this->exam_history->validate)){

			//add position history			

			if($this->exam_history->insert(array('employee_id'=>$employee_id, 'license_equipment_id'=>$license_equipment_id, 'exam_date'=>$exam_date, 'valid_date'=>$valid_date), FALSE))
			{
			   //нэмсэн утгуудын нэгийг хамгийн бага утгаар issued_date-г update Хийх

			   $valid_date = $this->exam_history->get_valid_date($employee_id);

			   $this->trainer_m->update($employee_id, array('valid_date'=>$valid_date), TRUE);

			   $return = array (
		          'status' => 'success',
		          'message' => 'Шалгалтын төхөөрөмжийг амжилттай нэмлээ'
		       );

			}else{

			    $return = array (
	               'status' => 'failed',
	               'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
	            );
			}	

		}else{

        $return = array (
           'status' => 'failed',
           'message' => validation_errors ( '', '<br>' )
        );
		}

	   return json_encode($return);

	}

	protected function print_history(){
		
		$ci = &get_instance ();

		$license_no = $ci->input->get_post('license_no');		
		
		$trainer_id= $ci->input->get_post('trainer_id');

		$printedby_id = $ci->input->get_post('user_id');
		//collect printed employee by id

		$page_number = $ci->input->get_post('page_number');
		
		$printed = $this->employee->get($printedby_id);
		
		$content = $ci->input->get_post('content');

		$trainer = $this->trainer_m->get($trainer_id);

		if($id = $this->print_history->insert(
			array('license_number'=>$license_no, 
				'page_number'=>$page_number, 
				'trainer_id'=>$trainer_id, 
				'trainer'=>$trainer->fullname, 
				'page'=>$content, 				
				'printed_date'=>$this->now, 
				'printed_by'=>$printed->fullname))){

			 $return = array (
	              'status' => 'success',
	              'message' => 'Амжилттай хадгаллаа'
	        );

		}else{

		  $return = array (
                  'status' => 'failed',
                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
               );
		}	

		 return json_encode($return);


	}

	// add remark

	protected function add_remark(){
		
		$ci = &get_instance ();

		//collect data
		$employee_id = $ci->input->get_post('employee_id');
		
		$remark = $ci->input->get_post('remark');

		// $this->pos_history->validate
		if($this->remark->validate($this->remark->validate)){

			//add position history			

			if($this->remark->insert(array('employee_id'=>$employee_id, 'remark'=>$remark), FALSE))
			{

				$return = array (
		          'status' => 'success',
		          'message' => 'Тусгай тэмдэглэгээг амжилттай нэмлээ'
		       );

			}else{

			    $return = array (
	               'status' => 'failed',
	               'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
	            );
			}	

		}else{

        $return = array (
           'status' => 'failed',
           'message' => validation_errors ( '', '<br>' )
        );
		}

	   return json_encode($return);

	}

	// get_remark 

	protected function get_remark(){

		$ci = &get_instance ();

		$current_id = $ci->input->get_post('id');

		$remark =  $this->remark->get_many_by('employee_id', $current_id);

		// echo $ci->db->last_query();

		return $remark;

	}	


	//outservice here
	protected function outservice() {

	   $ci = &get_instance ();

		 $id = $ci->input->get_post ( 'id' );

		 $trainer = $this->trainer_m->get($id);

		 if ($this->Obj->db->update('trainer', array('status'=>'outservice'), array('trainer_id'=>$id))) {

			 // get certificate no via id
			 $return = array (
					'status' => 'success',
					'message' => '"' . $trainer->fullname . '" нэртэй ИТА-ын мэдээллийг амжилттай архивт хийлээ! Архив хэсгийн жагсаалтаас та харна уу!' 
			 );
		} else {

			$return = array (
					'status' => 'failed',
					'message' => $trainer->fullname. ' ИТА-ын мэдээллийг архивт хийхэд алдаа гарлаа!' 
			);

		}

		echo json_encode($return);
	}

	protected function add_info(){

		$ci = &get_instance ();

		//collect data
		$trainer_id = $ci->input->get_post('trainer_id');

		$license_no = $ci->input->get_post('license_no');
		
		$firstname = $ci->input->get_post('firstname');
		
		$lastname = $ci->input->get_post('lastname');
		
		$education = $ci->input->get_post('education');
		
		$birthdate = $ci->input->get_post('birthdate');

		$register = $ci->input->get_post('register');

		$nationality = $ci->input->get_post('nationality');

		$issued_date = $ci->input->get_post('issued_date');
		
		// $valid_date = $ci->input->get_post('valid_date');
		
		$license_type = $ci->input->get_post('license_type');

		$initial_date = $ci->input->get_post('initial_date');

	    $form_validation = $this->form_validation ();

		$form_validation->set_rules ( 'register', 'Регистерийн дугаар', 'required' );

		$form_validation->set_rules ( 'lastname', 'Эцэг эхийн нэр', 'required' );

		$form_validation->set_rules ( 'firstname', 'Өөрийн нэр', 'required' ); //

		$form_validation->set_rules ( 'birthdate', 'Төрсөн огноо', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		$form_validation->set_rules ( 'education', 'Боловсрол', 'required' );
		
		$form_validation->set_rules ( 'nationality', 'Иргэний харьяалал', 'required' );

		$form_validation->set_rules ( 'license_no', 'Мэргэжлийн үнэмлэх №', 'required' );
		
		$form_validation->set_rules ( 'license_type', 'Үнэмлэх төрөл', 'required' );

		$form_validation->set_rules ( 'issued_date', 'Олгосон хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		// $form_validation->set_rules ( 'valid_date', 'Хүчинтэй хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );

		$form_validation->set_rules ( 'initial_date', 'Анх олгосон хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );


		if ($form_validation->run ()) 
		{

			// tuhain trainer_id-s uur id-r uur license_no-r haihad bval ene license_no-r хэрэглэч олдсон учир хийх боломжгүй

			$old_trainer = $this->trainer_m->get_by(array('trainer_id !='=>$trainer_id, 'license_no'=>$license_no));

			if($old_trainer)
			{

				$return = array (
		          'status' => 'error',
		          'message' => ' №:'.$license_no.'-той үнэмлэх аль хэдийн бүртгэсэн тул дахин бүртгэх боломжгүй!'
		       );

			}else
			{

				$id = $this->trainer_m->update(
					
					$trainer_id,

					array('license_no'=>$license_no, 
						 
						 'firstname'=>$firstname, 
						 
						 'lastname'=>$lastname,
						 
						 'birthdate'=>$birthdate,
						 
						 'register'=>$register,
						 
						 'education'=>$education,
						 
						 'nationality'=>$nationality,

						 'issued_date'=>$issued_date,
						 
						 // 'valid_date'=>$valid_date,
						 
						 'license_type'=>$license_type,
						 
						 'initial_date'=>$initial_date), TRUE);

				// echo $ci->db->last_query();

				if($id){

				   $return = array (
			          'status' => 'success',
			          'message' => 'Мэдээллийг амжилттай хадгаллаа'
			       );

				}
				else{

				   $return = array (
		              'status' => 'failed',
		              'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
		            );
				}	
			}

		} else

			// $form_validation->error_string ();

	        $return = array (
	           'status' => 'failed',
	           'message' => validation_errors ( '', '<br>' )
	        );
		

	   return json_encode($return);

	}

}

class eTraining extends eTraining_Driver {

	private $controller;

	private $method;

	private $state;

	private $trainer_id;

	public $url;

	public $script;

	public $data = array ();

	protected $column;

	protected $required_fields = array ();

	protected $add_fields;

	protected $edit_fields;

	protected $form_validtion = null;

	function __construct() {

		$this->setStateFromUrl ();

		$this->setIdFromUrl ();
		
		$script = " ";

	}

	private function setStateFromUrl() {

		$CI = &get_instance ();

		$CI->load->helper ( 'url' );

		$this->state = $CI->uri->segment ( 3 );

	}

	private function setIdFromUrl() {

		$CI = &get_instance ();

		$CI->load->helper ( 'url' );

		$this->trainer_id = $CI->uri->segment ( 4 );

	}

	protected function setControllerName() {

		$this->controller = $this->getControllerName ();

	}

	protected function setMethodName() {

		$this->method = $this->getMethodName ();

	}
	
	// used for show column
	function column() {
		// TODO: харагдах fielduud ba.
		$args = func_get_args ();

		$this->column = $args;

	}

	public function add_fields() {

		$args = func_get_args ();
		
		if (isset ( $args [0] ) && is_array ( $args [0] )) {

			$args = $args [0];
		}

		$this->add_fields = $args;
		
		return $this;
	}
	
	// edit fields
	public function edit_fields() {

		$args = func_get_args ();
		
		if (isset ( $args [0] ) && is_array ( $args [0] )) {

			$args = $args [0];
		}

		$this->edit_fields = $args;
		
		return $this;
	}

	public function fields() {

		$args = func_get_args ();
		
		if (isset ( $args [0] ) && is_array ( $args [0] )) {
			$args = $args [0];
		}
		return $this;
	}

	public function required_fields() {

		$args = func_get_args ();
		
		if (isset ( $args [0] ) && is_array ( $args [0] )) {
			$args = $args [0];
		}
		
		$this->required_fields = $args;
		
		return $this;
	}

	function getControllerName() {

		$CI = &get_instance ();

		$CI->load->helper ( 'url' );

		return $CI->router->class;

	}

	function getMethodName() {

		$CI = &get_instance ();

		$CI->load->helper ( 'url' );

		return $CI->router->method;

	}

	function getState() {

		return $this->state;

	}

	function set_section($section_id) {

		$this->section_id = $section_id;

		return true;
	}

	function set_role($role) {

		return $this->set_user_role ( $role );

	}

	function get_role() {

		return $this->get_user_role ();

	}
	
	// check state if state is null then check function
	function check_state() {

		if (in_array($this->get_role (), array('ENG', 'UNITCHIEF', 'SUPENG', 'TECH', 'SUPERVISOR'))) {

			$this->state = 'ownpage';

		} else if (! $this->state) {

			$this->state = 'none';

		}

		return $this->state;

	}

	function get_trainer_id() {

		if (! $this->trainer_id) {

			return null;

		} else

			return $this->trainer_id;
	}
	
	// check this is user_id registered as trainer?
	function check_is_trainer($user_id) {
		// my_model get
		$row = $this->my_model->get_result_row ( array (
				'trainer_id' => $user_id 
		), 'trainer' );

		if ($row)

			return TRUE;

		else

			return FALSE;
	}

	function run() {

		// initalizing pros		
		$this->init_table ();

		$data = array ();

		$data ['view'] = true;
		
		switch ($this->check_state ()) {
			
			case 'delete' :

				$return = $this->delete ();

				$data ['json'] = json_encode ( $return );

				$data ['view'] = false;

				return ( object ) $data;

				break;
			
			case 'update' :
				// update function for forms inputs
				$return = $this->update ();

				$data ['json'] = json_encode ( $return );

				$data ['view'] = false;

				return ( object ) $data;

				break;

			case 'outservice' :
				// update function for forms inputs
				$return = $this->outservice ();

				$data ['json'] = json_encode ( $return );

				$data ['view'] = false;

				return ( object ) $data;

				break;
			
			case 'add' :

				$return = $this->insert ();

				$data ['json'] = json_encode ( $return );

				$data ['view'] = false;

				return ( object ) $data;

				break;
			
			case 'new' :
				// check it has module name for accesss this
				$data ['page'] = 'training\new';

				$data ['title'] = 'ИТА бүртгэл';

				return ( object ) $data;
				break;
			
			case 'edit' :

				$data ['page'] = 'training\edit';

				$data ['title'] = 'Сургалт засах';

				$data ['data'] = $this->get ( $this->trainer_id );

				$data ['data'] ['training_json'] = $this->get_attendance ( $this->trainer_id );

				$data ['data'] ['trainer_education'] = $this->get_education ( $this->trainer_id );

				return ( object ) $data;

				break;
			
			case 'grid' :

				$data ['json'] = $this->grid ();

				$data ['view'] = false;

				return ( object ) $data;

				break;
			
			case 'out_grid' :

				$data ['json'] = $this->out_grid ();

				$data ['view'] = false;

				return ( object ) $data;

				break;
			
			// Show Trainer page shows
			case 'page' :

				$data ['page'] = 'training\page';

				$data ['title'] = 'Сургалт инженер техникийн ажилтан';

				$data ['data'] = $this->get ( $this->trainer_id );

				if ($this->check_is_trainer ($this->trainer_id  ))

					$data ['data'] ['is_trainer'] = 'yes';

				else

					$data ['data'] ['is_trianer'] = 'no';

				$data ['data'] ['training_json'] = $this->get_attendance ( $this->trainer_id );

				$data ['data'] ['trainer_education'] = $this->get_education ( $this->trainer_id );

				$data ['data'] ['print_history'] = $this->print_history->get_many_by('trainer_id', $this->trainer_id );

				return ( object ) $data;
				break;
			
			// Show Engineer page shows
			case 'ownpage' :

				$data ['page'] = 'training\page';

				$data ['title'] = 'Сургалт инженер техникийн ажилтан';
				// check this id is registered? as trainer_id
				
				if ($this->check_is_trainer ( $this->user_id ))
				
					$data ['is_trainer'] = 'yes';
				
				else
				
					$data ['is_trianer'] = 'no';

				// print_history

				$data ['data'] ['print_history'] = $this->print_history->get_many_by('trainer_id', $this->user_id );
				
				$data ['data'] = $this->get ( $this->user_id );

				$data ['data'] ['training_json'] = $this->get_attendance ( $this->user_id );

				$data ['data'] ['trainer_education'] = $this->get_education ( $this->user_id );

				return ( object ) $data;

				break;


			case 'print_lc' :

				$data ['page'] = 'training\license';

				$data ['title'] = 'Үнэмлэх хэвлэх';

				$trainer = $this->get_trainer();

				$data ['trainer'] = $trainer;

				$data ['trainer'] = $trainer;

				$data ['pos_history'] = $this->get_pos_history();
				
				$data ['count_ph'] = $this->pos_history_count();				

				$data ['license_type'] = $this->license_type->dropdown_by('code', 'licence_type');						

				$data ['exam_history'] = $this->get_exam_history();
				
				$data ['exam_page'] = $this->get_exam_page();
				
				$data ['count_exam'] = $this->exam_history_count();
				
				$data ['remarks'] = $this->get_remark();
				
				$data ['view'] = true;

				return ( object ) $data;

			break;

			case 'add_pos_his' : //position history

				// $data ['page'] = 'training\license';

				// $data ['title'] = 'Үнэмлэх хэвлэх';

				$data ['json'] = $this->add_pos_history();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;		


			case 'del_pos_his' : // delete position history
				
				$data ['json'] = $this->del_pos_history();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;

			// delete remark
			
			case 'del_remark' : 
				
				$data ['json'] = $this->destroy_remark();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;


			// herev add exam_history here
			case 'add_exam_his' : 				

				$data ['json'] = $this->add_exam_history();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;		

			case 'del_exam_his' : 				

				$data ['json'] = $this->del_exam_history();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;		


			// herev add exam_history here
			case 'add_print' : 				

				$data ['json'] = $this->print_history();
				
				$data ['view'] = false;
				

				return ( object ) $data;

			break;		

			case 'add_info' : 				

				$data ['json'] = $this->add_info();
				
				$data ['view'] = false;
				

				return ( object ) $data;

			break;	

			// herev add remark here
			case 'add_remark' : 				

				$data ['json'] = $this->add_remark();
				
				$data ['view'] = false;

				return ( object ) $data;

			break;		

			
			case 'outgrid':

				$data ['page'] = 'training\outgrid';

				$data ['title'] = 'Сургалтын бүртгэл';

				$data ['action'] = $this->action();

				$data ['role'] = $this->get_role ();

				return ( object ) $data;
			
				default :

				$data ['page'] = 'training\index';

				$data ['title'] = 'Сургалтын бүртгэл';

				$data ['action'] = $this->action ();

				$data ['role'] = $this->get_role ();

				return ( object ) $data;
		}
	}
}

if (defined ( 'CI_VERSION' )) {

	$ci = &get_instance ();

	$ci->load->library ( 'Form_validation' );

	class CS_CRUD_Form_validation extends CI_Form_validation {

		public $CI;

		public $_field_data = array ();

		public $_config_rules = array ();

		public $_error_array = array ();

		public $_error_messages = array ();

		public $_error_prefix = '<p>';

		public $_error_suffix = '</p>';

		public $error_string = '';

		public $_safe_form_data = FALSE;

	}

	class CS_CRUD_inputs extends CI_input {

		public $CI;

	}
}