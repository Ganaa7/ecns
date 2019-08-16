<?php

require_once('Core.php');

class eManual_Modeler {

	private $user_role;

	public $user_id;

	public $my_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?

	public $section_id;

	public $session;

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
       $ci->load->model ( 'manual_model' );

		$this->my_model = new manual_model ();
		
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
		if ($where)
			return $this->my_model->get_select ( $name, $where, $table );
		else
			return $this->my_model->get_select ( $name, null, $table );
	}
	
	// Хэрэв view bval
	protected function get_all($array = null, $table = null) {
		return $this->my_model->get_all ( $array, $table );
	}
	protected function get_query($select, $table = null, $where = null, $sidx = null, $sord = null, $start = null, $limit = null) {
		return $this->my_model->get_query ( $select, $table, $where, $sidx, $sord, $start, $limit );
	}
	
	// =get_section by user_id
	protected function get_seccode() {
		$this->section_id = $this->my_model->get_row ( 'section_id', array (
				'employee_id' => $this->user_id), 'employee' );
		//$this->my_model->set_table ( 'section' );
		return $this->my_model->get_row ( 'code', array (
				'section_id' => $this->section_id), 'section' );
	}
}
class eManual_Driver extends eManual_Modeler {
	private $form_validation;
	private $validation;
	private $input;
	private $upload_path = "download/taz_files/";
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
	private function key_gen($len) {
		$alphabet = "abcdefghijklmnopqrstuwxyz01234567890";
		$key = array (); // remember to declare $pass as an array
		$alphaLength = strlen ( $alphabet ) - 1; // put the length -1 in cache
		for($i = 0; $i < $len; $i ++) {
			$n = rand ( 0, $alphaLength );
			$key [] = $alphabet [$n];
		}
		return implode ( $key ); // turn the array into a string
	}
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
	protected function add_validation() {
		$validation_result = ( object ) array (
				'success' => false 
		);
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'section_id', 'Хэсэг', 'required' );
		$form_validation->set_rules ( 'manual', 'Техник ашиглалтын заавар', 'required' );
		$form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required' );
		$form_validation->set_rules ( 'doc_index', 'Индекс', 'required|max_length[19]' );

        $form_validation->set_rules ( 'update_date', 'Батлагдсан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );
		$form_validation->set_rules ( 'uploaded_file', 'ТАЗ-ын файл', 'required' );

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

	// =edit validation
	protected function edit_validation() {
		$validation_result = ( object ) array (
				'success' => false 
		);
        $form_validation = $this->form_validation ();
        $form_validation->set_rules ( 'section_id', 'Хэсэг', 'required|is_natural_no_zero' );
        $form_validation->set_rules ( 'manual', 'Техник ашиглалтын заавар', 'required' );
        $form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' );
        $form_validation->set_rules ( 'doc_index', 'Индекс', 'required|max_length[19]' );

        $form_validation->set_rules ( 'update_date', 'Батлагдсан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]' );
        $form_validation->set_rules ( 'uploaded_file', 'ТАЗ-ын файл', 'required' );

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
		if($i->get_post ( 'id' ))
            $data['id']=$i->get_post('id');
		$data ['section_id'] = $i->get_post ( 'section_id' );
		$data ['manual'] = $i->get_post ( 'manual' );
		$data ['equipment_id'] = $i->get_post ( 'equipment_id' );
		$data ['doc_index'] = $i->get_post ( 'doc_index' );
		$data ['update_date'] = $i->get_post ( 'update_date' );
		$data ['filename'] = $i->get_post ( 'uploaded_file' );
		return $data;
	}

	//filter equipment here
    function filter_by() {
        $json_arr = array ();
        $data = array ();
        $CI = &get_instance ();

        $section_id= $CI->input->get_post ( 'section_id' );

        if ($section_id!=0) { //section filter hiine
            if($section_id ==3)
               $query = $this->my_model->get_query ( 'equipment_id, equipment', 'equipment2', "where section_id = $section_id and  parent_id = 0", 'equipment_id', 'DESC' );
            else
               $query = $this->my_model->get_query ( 'equipment_id, equipment', 'equipment2', "where section_id = $section_id", 'equipment_id', 'DESC' );
            if($query->num_rows()>0){
                $data ['0'] = 'Бүгд';
                foreach ( $query->result () as $row ) {
                    $data [$row->equipment_id] = $row->equipment;
                }
            }else
                $data [''] = 'Бүгд';
        }else
            $data [0] = 'Нэг хэсгийг сонгоно уу!';
        // echo $this->log_model->last_query();

        return $data;
    }
	
	// insert data
	protected function add() {
		$validation_result = $this->add_validation ();
		if ($validation_result->success) {
			// get_input data here
			$data = $this->get_input_data ();

			$data['updated_at'] = date('Y-m-d');
			
			if ($this->my_model->insert ( $data, 'manual' ) !== FALSE) {
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
		if (in_array ( $this->get_seccode (), $sec_array )) {
			$where = " WHERE section_id = " . $this->section_id . "";
		}
		
		if (($search == 'true') && ($filters != "")) {
			$where = $this->filter ( $filters );
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );


		//manual table join with section_id, equipment2
        if ($where)
            $query = $this->get_query ( " count(*) as count ", 'vw_manual', $where );
        else
            $query = $this->get_query ( " count(*) as count ", 'vw_manual' );

		if ($query->num_rows () > 0) {
			$countRow = $query->row_array ();
			$count = $countRow ['count'];
		}
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages)   $page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
		   $start = 0;

        $Qry = $this->get_query ( ' * ', 'vw_manual', $where, $sidx, $sord, $start, $limit );

        $json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['code'] = $this->get_seccode ();
		$json ['sql'] = $this->my_model->last_query();

		foreach ( $Qry->result () as $row ) {
			$crow ['id'] = $row->id;
            $crow ['section'] = $row->section;
            $crow ['equipment'] = $row->equipment;
            $crow ['doc_index'] = $row->doc_index;
			$crow ['manual'] = $row->manual;
			$crow ['filename'] = $row->filename;
			$crow ['update_date'] = $row->update_date;
			$crow ['updatedby'] = $row->updatedby;
			$crow ['section'] = $row->section;
			$crow ['updated_at'] = $row->updated_at;
			array_push ( $row_bind, $crow );
		}
		$json ['rows'] = $row_bind;
		$Qry->free_result ();
		return json_encode ( $json );
	}
	protected function delete() {
		$CI = &get_instance ();
		$id = $CI->input->post ( 'id' );
		$manual = $this->my_model->get_row ( 'manual', array (
				'id' => $id
		), 'manual');

		if ($this->my_model->delete ( array (
				'id' => $id 
		), 'manual' )) {
			// get certificate no via id
			$return = array (
					'status' => 'success',
					'message' => '"' . $manual. '" ТАЗ-ыг амжилттай устгагдлаа!'
			);
		} else {
			$return = array (
					'status' => 'failed',
					'message' => $manual. ' ТАЗ-ыг устгахад алдаа гарлаа!'
			);
		}
		return $return;
	}
	
	// =get manual id -all about
	protected function get() {
	    $CI=&get_instance();
	    $id = $CI->input->get_post('id', TRUE);

		$data = $this->get_all ( array ('id' => $id	), 'vw_manual' );
		return $data;
	}

	// =update update here
	protected function update() {
		// update hiihed user_id -g update hiine
		$data = $this->get_input_data ();
		$id = $data ['id'];

		$validation_result = $this->edit_validation ();

		if ($validation_result->success) {
			$flag = $this->my_model->update ( $data, array (
					'id' => $id	), 'manual' );

			if ($flag) {
				$return = array (
						'status' => 'success',
						'message' => 'Амжилттай хадгаллаа' 
				);
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Таны өөрчлөлтийг хадгалж чадсангүй! Дахин оролдоно уу!'
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
	
	// =upload here
	protected function upload() {
		// update hiihed user_id -g update hiine
		$file_name = $_FILES ['userfile'] ['name'];
		
		$is_cyrilic = ( bool ) preg_match ( '/[\p{Cyrillic}]/u', $file_name );
		$is_latin = ( bool ) preg_match ( '/[{ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ}]/u', $file_name );
        $has_space = (bool) preg_match("/\\s/", $file_name);
		
		if ($is_cyrilic) {
			$json = array (
					'status' => 'error',
					'message' => 'Файлын нэрийг Криллээр бичсэн байна! Файлын нэрийг Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
			);
		} elseif ($is_latin) {
			$json = array (
					'status' => 'error',
					'message' => 'Файлын нэр танигдахгүй байна! Файлын нэрээ Unicode үсгээр нэрлээд дахин байршлуулна уу!' 
			);
		}elseif($has_space){
            $json = array (
                'status' => 'error',
                'message' => 'Файлын нэрэнд зай авсан байна! Файлын нэрээ зайгүй үсгээр өгөөд дахин байршлуулна уу!'
            );
        }
        else {
			$f_name = $this->key_gen ( 5 ) . '-' . $file_name;
			// config here
			$config = array (
					'allowed_types' => 'pdf',
					'upload_path' => $this->upload_path,
					'max_size' => 65000,
					'file_name' => $f_name 
			);
			$CI = &get_instance ();
			$CI->load->library ( 'upload', $config );
			// //хэрэв энэ файл сервер дээр байршсан бол устгах
			// if (file_exists($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$file_name)){
			// //file-г устгах хэрэгтэй
			// unlink($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/".$file_name;
			// }
			// if successfully uploaded
			if ($CI->upload->do_upload ()) {
				// collect uploaded data
				$f_data = $CI->upload->data ();
				$json = array (
						'name' => $f_data ['file_name'],
						'size' => $f_data ['file_size'],
						'type' => $f_data ['file_type'],
						'delete_url' => $this->upload_path . $f_name
				);
				$json ['status'] = 'success';
				$json ['filename'] = $f_name;

				if($CI->input->get_post('id')){ // Хэрэв id bval edit hiij baina gsn ug

					$id = $CI->input->get_post('id');
					
					$this->my_model->update(array('filename' => $f_name, 'updated_at' => date('Y-m-d')), 
											array ('id' => $id ), 'manual' );
						
                    $json ['id'] = $CI->input->get_post('id');
                }
			} else {
				$json = array (
						'status' => 'error',
						'message' => $CI->upload->display_errors ( '', '' ) 
				);
			}
		}
		return $json;
	}
	
	// delete file here
	protected function del_file() {
		$CI = &get_instance ();
		$id = $CI->input->get_post ( 'id' );
		$file_name = $CI->input->get_post ( 'file_name' );
		// check file exists
		// var_dump($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/");
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/taz_files/" . $file_name )) {
			// file is unlicked?
			if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/taz_files/" . $file_name )) {
				$this->my_model->update ( array (
						'filename' => null), array (
						'id' => $id ), 'manual' );
				$json = array (
						'status' => 'success',
						'message' => '[' . $file_name . '] амжилттай устгалаа!' 
				);
			} else
				$json = array (
						'status' => 'failed',
						'message' => '[' . $file_name . '] устгахад алдаа гарлаа!' 
				);
		} else {
			// check if this file exists in the db?
			$this->my_model->update ( array (
					'filename' => null
			), array (
					'id' => $id 
			), 'manual' );
			
			$json = array (
					'status' => 'failed',
					'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!' 
			)
			;
		}
		
		return $json;
	}

	protected function action() {
		$res_array = array ();
		$CI = &get_instance ();
		$res_array = $this->my_model->get_actionby ( $this->get_role (), 'manual', 'manual' );
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

	protected function set_session(){
        $CI = &get_instance ();
        $CI->load->library ( 'session' );
        $this->session = $CI->session;
        return $this->session;
    }

}
class eManual extends eManual_Driver{
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
//		if ($this->get_role () == 'ENG') {
//			$this->state = 'ownpage';
//		} else 
                    if (! $this->state) {
			$this->state = 'none';
		}
		return $this->state;
	}
	function run() {
		// initalizing pros
		$this->init_table ();
		$data = array ();
		$data ['view'] = true;
		
		switch ($this->check_state ()) {

            case 'filter' :
                $data ['json'] = json_encode ( $this->filter_by() );
                $data ['view'] = false;
                return ( object ) $data;
                break;
            case 'add' :
                $data ['json'] = json_encode ( $this->add() );
                $data ['view'] = false;
                return ( object ) $data;
                break;

			case 'grid' :
				$data ['json'] = $this->grid ();
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// =delete case here
			case 'delete' :
				$return = $this->delete ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// Show Trainer page shows
			case 'get' :
//				$data ['page'] = 'certificate\page';
//				$data ['title'] = 'Гэрчилгээ';
				$data ['json'] = json_encode ( $this->get ( $this->cert_id ) );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// Show Trainer page shows
			case 'edit' :
				$data ['json'] = json_encode ( $this->update () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			// upload here
			case 'upload' :
				$data ['json'] = json_encode ( $this->upload () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'del_file' :
				$data ['json'] = json_encode ( $this->del_file () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			// call here license
			case 'license' :
				$data ['page'] = 'certificate\index';
				$data ['title'] = 'Гэрчилгээ бүртгэл';
				$data ['json'] = json_encode ( $this->get_license ( $this->cert_id ) );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			default :
				$data ['page'] = 'manual\index';
				$data ['title'] = 'ТАЗ-ын бүртгэл';
				$data ['sec_code'] = $this->get_seccode ();
				$data ['action'] = $this->action ();
				$data ['role'] = $this->get_role ();
				$data ['equipment'] = $this->get_select ( 'equipment', 'equipment2' );
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