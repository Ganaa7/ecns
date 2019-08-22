<?php
class eContract_Modeler {

	public $ci;

	public $input;

	private $user_role;

	public $user_id;

	public $my_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?

	public $section_id;

	function set_user($user_id) {
		$this->user_id = $user_id;
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

		$this->ci = $ci;

		$this->input = $ci->input;

		$ci->load->model ( 'my_model_old' );
		$this->my_model = new my_model_old();
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
			$this->my_model->set_table ( 'certificate' );
		}
		return $this->my_model->get_query ( $select, $table, $where, $sidx, $sord, $start, $limit );
	}
	
	// =get_section by user_id
	protected function get_seccode() {
		$this->my_model->set_table ( 'employee' );
		$this->section_id = $this->my_model->get_row ( 'section_id', array (
				'employee_id' => $this->user_id 
		) );
		$this->my_model->set_table ( 'section' );
		return $this->my_model->get_row ( 'code', array (
				'section_id' => $this->section_id 
		) );
	}
}
class eContract_Driver extends eContract_Modeler {
	private $form_validation;
	private $validation;
	public $input;
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
	) // doesn't contain
;
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
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'register', 'Регистерийн дугаар', 'required' );
		$form_validation->set_rules ( 'lastname', 'Эцэг эхийн нэр', 'required' );
		$form_validation->set_rules ( 'firstname', 'Өөрийн нэр', 'required' );
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
		$start_date = strtotime ( $in->get_post ( 'issueddate' ) );
		$end_date = strtotime ( $in->get_post ( 'validdate' ) );
		
		if ($end_date > $start_date)
			return True;
		else {
			$form_validation->set_message ( 'compareDate', '%s should be greater than Contract Start Date.' );
			return False;
		}
	}
	
	// =edit validation
	protected function edit_validation() {
		$validation_result = ( object ) array (
				'success' => false 
		);
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'cert_no', 'Гэрчилгээ дугаар', 'required' );
		$form_validation->set_rules ( 'serial_no_year', 'Үйлдвэрийн сери дугаар', 'required' );
		$form_validation->set_rules ( 'intend', 'Зориулалт', 'required' );
		
		$form_validation->set_rules ( 'location_id', 'Байршил', 'is_natural_no_zero' );
		$form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' );
		$form_validation->set_rules ( 'issueddate', 'Олгосон хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|callback_compare_date' );
		$form_validation->set_rules ( 'validdate', 'Хүчинтэй хугацаа', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|callback_compare_date' );
		
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
	// get inputs here
	protected function get_input_data() {
		// inputs declaretion from ci
		$i = $this->form_inputs ();
		$data ['id'] = $i->get_post ( 'id' );
		$data ['cert_no'] = $i->get_post ( 'cert_no' );
		$data ['location_id'] = $i->get_post ( 'location_id' );
		$data ['equipment_id'] = $i->get_post ( 'equipment_id' );
		$data ['intend'] = $i->get_post ( 'intend' );
		$data ['serial_no_year'] = $i->get_post ( 'serial_no_year' );
		$data ['issueddate'] = $i->get_post ( 'issueddate' );
		$data ['validdate'] = $i->get_post ( 'validdate' );
		return $data;
	}
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
	// =grid
	protected function grid() {
		$CI = &get_instance ();
		// here is calling grid
		$page = $CI->input->get_post ( 'page' );
		$limit = $CI->input->get_post ( 'rows' );
		$sidx = $CI->input->get_post ( 'sidx' );
		if (! $sidx)
			$sidx = 1;
		
		$sord = $CI->input->get_post ( 'sord' );
		$table = $this->my_model->check_table ( 'view_contract' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		$section_id = $CI->input->get_post ( 'section_id' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		
		$date_option = $CI->input->get_post ( 'date_option' );
		$start_dt = $CI->input->get_post ( 'start_dt' );
		$end_dt = $CI->input->get_post ( 'end_dt' );
		
		$sec_array = array (
				'COM',
				'NAV',
				'SUR',
				'ELC' 
		);
		// if(in_array($this->get_seccode(), $sec_array)){
		// $where = " WHERE section_id = ".$this->section_id."";
		// }
		
		if (($search == 'true') && ($filters != "")) {
			$where = $this->filter ( $filters );
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		
		if ($where)
			$query = $this->get_query ( " count(*) as count ", 'view_contract', $where );
		else
			$query = $this->get_query ( " count(*) as count ", 'view_contract' );
		
		if ($query->num_rows () > 0) {
			$countRow = $query->row_array ();
			$count = $countRow ['count'];
		}
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		$Qry = $this->get_query ( ' * ', 'view_contract', $where, $sidx, $sord, $start, $limit );
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['code'] = $this->get_seccode ();
		
		foreach ( $Qry->result () as $row ) {
			$crow ['id'] = $row->id;
			$crow ['contract_no'] = $row->contract_no;
			$crow ['category'] = $row->category;
			$crow ['title'] = $row->title;
			$crow ['sides'] = $row->sides;
			$crow ['filename'] = $row->filename;
			$crow ['approved'] = $row->approved;
			$crow ['expireddate'] = $row->expireddate;
			$crow ['invoice_file'] = $row->invoice_file;
			array_push ( $row_bind, $crow );
		}
		$json ['rows'] = $row_bind;
		$Qry->free_result ();
		return json_encode ( $json );
	}

	//=archive
	protected function archive() {
		$CI = &get_instance ();
		// here is calling grid
		$page = $CI->input->get_post ( 'page' );
		$limit = $CI->input->get_post ( 'rows' );
		$sidx = $CI->input->get_post ( 'sidx' );
		if (! $sidx)
			$sidx = 1;
		
		$sord = $CI->input->get_post ( 'sord' );
		$table = $this->my_model->check_table ( 'contract_archive' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		$section_id = $CI->input->get_post ( 'section_id' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		
		$date_option = $CI->input->get_post ( 'date_option' );
		$start_dt = $CI->input->get_post ( 'start_dt' );
		$end_dt = $CI->input->get_post ( 'end_dt' );
		
		$sec_array = array (
				'COM',
				'NAV',
				'SUR',
				'ELC' 
		);
			
		if (($search == 'true') && ($filters != "")) {
			$where = $this->filter ( $filters );
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		
		if ($where)
			$query = $this->get_query ( " count(*) as count ", 'vw_contract_archive', $where );
		else
			$query = $this->get_query ( " count(*) as count ", 'vw_contract_archive' );
		
		if ($query->num_rows () > 0) {
			$countRow = $query->row_array ();
			$count = $countRow ['count'];
		}
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		$Qry = $this->get_query ( ' * ', 'vw_contract_archive', $where, $sidx, $sord, $start, $limit );
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;		
		
		foreach ( $Qry->result () as $row ) {
			$crow ['id'] = $row->id;
			$crow ['section'] = $row->section;
			$crow ['contract_no'] = $row->contract_no;			
			$crow ['title'] = $row->contract;
			$crow ['sides'] = $row->sides;
			$crow ['filename'] = $row->filename;			
			array_push ( $row_bind, $crow );
		}
		$json ['rows'] = $row_bind;
		$Qry->free_result ();
		return  $json ;
	}


    //=freq_grid
    protected function freq_grid(){
        $CI = &get_instance ();
        // here is calling grid
        $page = $CI->input->get_post ( 'page' );
        $limit = $CI->input->get_post ( 'rows' );
        $sidx = $CI->input->get_post ( 'sidx' );
        if (! $sidx)
            $sidx = 1;

        $sord = $CI->input->get_post ( 'sord' );
        $table = $this->my_model->check_table ( 'contract_archive' );
        $filters = $CI->input->get_post ( 'filters' );
        $search = $CI->input->get_post ( '_search' );

        $where = ""; // if there is no search request sent by jqgrid, $where should be empty
        $searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
        $searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
        $searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;

        $section_id = $CI->input->get_post ( 'section_id' );
        $equipment_id = $CI->input->get_post ( 'equipment_id' );

        $date_option = $CI->input->get_post ( 'date_option' );
        $start_dt = $CI->input->get_post ( 'start_dt' );
        $end_dt = $CI->input->get_post ( 'end_dt' );

        $sec_array = array (
            'COM',
            'NAV',
            'SUR',
            'ELC'
        );

        if (($search == 'true') && ($filters != "")) {
            $where = $this->filter ( $filters );
        }

        date_default_timezone_set ( ECNS_TIMEZONE );
        $data = date ( "Y-m-d" );

        if ($where)
            $query = $this->get_query ( " count(*) as count ", 'vw_contract_archive', $where );
        else
            $query = $this->get_query ( " count(*) as count ", 'vw_contract_archive' );

        if ($query->num_rows () > 0) {
            $countRow = $query->row_array ();
            $count = $countRow ['count'];
        }

        if ($count > 0) {
            $total_pages = ceil ( $count / $limit );
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        $Qry = $this->get_query ( ' * ', 'vw_contract_archive', $where, $sidx, $sord, $start, $limit );

        $json = array ();
        $row_bind = array ();
        $crow = array ();

        $json ['page'] = $page;
        $json ['total'] = $total_pages;
        $json ['records'] = $count;

        foreach ( $Qry->result () as $row ) {
            $crow ['id'] = $row->id;
            $crow ['section'] = $row->section;
            $crow ['contract_no'] = $row->contract_no;
            $crow ['title'] = $row->contract;
            $crow ['sides'] = $row->sides;
            $crow ['filename'] = $row->filename;
            array_push ( $row_bind, $crow );
        }
        $json ['rows'] = $row_bind;
        $Qry->free_result ();
        return  $json ;
    }

	protected function delete() {
		$CI = &get_instance ();
		$this->my_model->set_table ( 'certificate' );
		$id = $CI->input->post ( 'id' );
		$cert_no = $this->my_model->get_row ( 'cert_no', array (
				'id' => $id 
		) );
		
		if ($this->my_model->delete ( array (
				'id' => $id 
		) )) {
			// get certificate no via id
			$return = array (
					'status' => 'success',
					'message' => '"' . $cert_no . '" дугаартай гэрчилгээ амжилттай устгагдлаа!' 
			);
		} else {
			$return = array (
					'status' => 'failed',
					'message' => $CI->input->post ( 'id' ) . ' устгахад алдаа гарлаа!' 
			);
		}
		return $return;
	}
	
	// =get cert_id
	protected function get($cert_id) {
		$data = $this->get_all ( array (
				'id' => $cert_id 
		), 'view_certificate' );
		return $data;
	}
	
	// =update update here
	protected function update() {
		$data = $this->get_input_data ();
		$edata = array ();
		$edata ['intend'] = $data ['intend'];
		$id = $data ['id'];
		unset ( $data ['intend'] );
		unset ( $data ['id'] );
		
		$validation_result = $this->edit_validation ();
		if ($validation_result->success) {
			$this->my_model->set_table ( 'certificate' );
			$flag1 = $this->my_model->update ( $data, array (
					'id' => $id 
			) ) ? true : false;
			$this->my_model->set_table ( 'equipment2' );
			$flag2 = $this->my_model->update ( $edata, array (
					'equipment_id' => $data ['equipment_id'] 
			) );
			
			if ($flag1 || $flag1) {
				$return = array (
						'status' => 'success',
						'message' => 'Амжилттай хадгаллаа' 
				);
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Таны өөрчлөлтийг хадгалж чадсангүй! Дахин оролдоно уу!',
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

	protected function set_outservice() {

		$id = $this->ci->input->get_post ( 'id' );

		//(x) тухайн ID-r contract-n утгйиг авна.
		//(x) утгаар contract-arcive table ruu huulna -рүү хуулна.
		
		$contract = $this->ci->db->get_where('contract', array('id' => $id))->row();

		$file = "D:/xampp/htdocs/ecns/download/contract_files/$contract->filename";
		
		$new_file = "D:/xampp/htdocs/ecns/download/contract_archive_files/$contract->filename";
		
		if (!copy($file, $new_file)) {

			$return = array(
				'status' => 'failed',
				'message' => $id . ' -тай гэрээний файлыг хуулахад алдаа гарлаа!'
			);

		}else{

			$this->ci->db->insert('contract_archive', array(
				'ordering' => $contract->ordering,
				'contract_no' => $contract->contract_no,
				'filename' => $contract->filename,
				'title' => $contract->title,
				'sides' => $contract->sides,
				'year' => date('Y', strtotime($contract->approved))
			));

			// тухайн contract-n file-г бас хуулна.

			if ($this->ci->db->update('contract', array('status' => 'outservice'), array('id' => $id))) {

				// get certificate no via id
				$return = array(
					'status' => 'success',
					'message' => '"' . $id . '"-тай гэрээг ашиглалтаас амжилттай хаслаа!'
				);
			} else {

				$return = array(
					'status' => 'failed',
					'message' => $id . ' -тай гэрээн дээр энэ үйлдлийг хийхэд алдаа гарлаа!'
				);
			}

		}
	
		return $return;
	}

	protected function action() {
	
		$res_array = array ();

		$CI = &get_instance ();

		$res_array = $this->my_model->get_actionby ( $this->get_role (), 'contract', 'contract' );

		return $res_array;

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
}

class eContract extends eContract_Driver {

	private $controller;

	private $method;

	private $state;

	private $cert_id;

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
		$this->cert_id = $CI->uri->segment ( 4 );
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
		return $this->state;
	}
	function run() {
		// initalizing pros
		$this->init_table ();
		$data = array ();
		$data ['view'] = true;
		$data ['state'] = $this->check_state ();
		
		switch ($this->check_state ()) {
			case 'grid' :
				$data ['json'] = $this->grid ();
				$data ['view'] = false;

				return ( object ) $data;
				break;
					
			case 'archive':
			$return= $this->archive();
			$data['json']=json_encode($return);
			$data['view']=false;
			return (object)$data;
			break;

            case 'idx_freq':
                $return= $this->archive();
                $data['json']=json_encode($return);
                $data['view']=false;
                return (object)$data;
                break;    


            case 'outservice':
                $return= $this->set_outservice();
                $data['json']=json_encode($return);
                $data['view']=false;
                return (object)$data;
                break;
					
			
			default :
				if($this->state=='archived')
				   $data ['page'] = 'contract\archive';
				else if($this->state=='frequency')
				   $data ['page'] = 'contract\frequency';
				else
				$data ['page'] = 'contract\index';
				
				$data ['action'] = $this->action();
				
				$data['sql'] = $this->my_model->last_query();

				$data ['title'] = 'Гэрээний бүртгэл';

				$data ['sec_code'] = $this->get_seccode ();
				
				$data ['role'] = $this->get_role ();
				$data ['equipment'] = $this->get_select ( 'equipment', 'equipment2' );
				$data ['location'] = $this->get_select ( 'name', 'location' );
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