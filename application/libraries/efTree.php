<?php
class eFtree_Modeler {
	private $user_role;
	public $user_id;
	public $my_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	public $new_model = null;
	public $alert_model = null;
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
		$ci->load->model ( 'my_model_old' );
		$this->my_model = new my_model_old ();
		$ci->load->model ( 'new_model' );
		$this->new_model = new new_model ();
		$ci->load->model ( 'alert_model' );
		$this->alert_model = new alert_model ();
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
	
	// delete by id and its' child delete
	protected function _delete($ftree_id) {
		$this->my_model->set_table ( 'f_tree' );
		// delete ftree_id by parent
		// $this->my_model->delete(array('id'=>$ftree_id));
		// return 1;
		// else return 0;

		// herev $ftree_id -r parent = 0 buyu parent bol tuhain equipment_id -r bugdiig ustgah heregtei ba
		$ftree = $this->get_all(array('id' =>$ftree_id), 'f_tree');		
		//print_r($ftree);
		if($ftree['parent']==0){ //parent bol bugdiig ustaga
		   $this->my_model->delete(array('equipment_id' =>$ftree['equipment_id']));
		   return 1;
		}else{
		   // delete if has parent_id = $ftree_id
		   // herev dooroo muchritei bol buh muchriig ustgah
		   if ($this->my_model->get_row ( 'id', array ('parent' => $ftree_id 	) )) {
		   	$this->my_model->delete ( array ('parent' => $ftree_id 	) );
		   }	
		   if ($this->my_model->delete ( array (	'id' => $ftree_id 	) ))
		      return 1;
		}
	}

	protected function set_time() {
		date_default_timezone_set ( ECNS_TIMEZONE );
		return date ( "Y-m-d H:i:m" );
	}
}

class eFtree_Driver extends eFtree_Modeler {
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
		$form_validation->set_rules ( 'equipment_id', 'Тоног төрөөрөмж дугаар', 'required' );
		$form_validation->set_rules ( 'event_id', 'Event', 'required' );
		$form_validation->set_rules ( 'parent', 'Эцэг', 'required' );
		$form_validation->set_rules ( 'node', 'Мөчир', 'required' );
		
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
		$data ['equipment_id'] = $i->get_post ( 'equipment_id' );
		$data ['parent'] = $i->get_post ( 'parent' );
		$data ['node'] = $i->get_post ( 'node' );
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
			
			$this->my_model->set_table ( 'ftree' );
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
	
	// = add tree
	protected function add_tree() {
		$CI = &get_instance ();
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		$event_id = $CI->input->get_post ( 'event_id' );
		$gate = $CI->input->get_post ( 'gate' );
		$val_result = $this->add_tree_validation ();

		if ($val_result->success) {

			$this->my_model->set_table ( 'equipment2' );

			$equipment = $this->my_model->get_row ( 'equipment', array (
					'equipment_id' => $equipment_id 
			) );

			$this->my_model->set_table ( 'f_event' );

			$event = $this->my_model->get_row ( 'event', array (
					'id' => $event_id 
			) );
			
			$data ['equipment_id'] = $equipment_id;
			$data ['module'] = $equipment;
			$data ['event_id'] = $event_id;
			$data ['parent'] = 0;
			$data ['gate'] = $gate;
			$data ['updated_by'] = $this->user_id;
			$data ['updated_at'] = $this->set_time ();
			
			// herev tend yamar neg tohioldol bval guitsetgehgui
			// where parent_0 and equipment = $equipment_id
			$this->my_model->set_table ( 'f_tree' );
			
			$where_arr = array (
					'parent' => 0,
					'equipment_id' => $equipment_id 
			);
			
			if ($this->my_model->get_result ( $where_arr ))
				$status = array (
						'status' => 'failed',
						'message' => 'Энэ төхөөрөмж дээр аль хэдийн "АЛДААНЫ МОД" үүссэн байна!' 
				);
			elseif ($this->my_model->insert ( $data ) !== FALSE) {
				// herev nemeh ym bol email ilgeeh heregtei gandavaa.d tuvshinbayar.g rii
				// $this->alert_model->email('gandavaa.d@mcaa.gov.mn, g_tuvshinbayar@mcaa.gov.mn', 'Гэрчилгээний хугацаа', "$equipment дээр Алдааны мод нэмлээ", 'html');
				
				$status = array (
						'status' => 'success',
						'message' => 'Бүртгэлийг шинээр нэмлээ!' 
				);
			}
		} else {
			$status = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 
			);
		}
		
		return $status;
	}

	protected function add_tree_validation() {

		$validation_result = ( object ) array (
				'success' => false 
		);
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'equipment_id', 'Тоног төрөөрөмж', 'required' );
		$form_validation->set_rules ( 'event_id', 'Event', 'required' );
		$form_validation->set_rules ( 'gate', 'Логик хаалга', 'required' );
		
		// run form validation
		if ($form_validation->run ()) {
			$validation_result->success = true;
		} else
			$validation_result->error_message = $form_validation->error_string ();
		$validation_result->error_fields = $form_validation->_error_array;
		return $validation_result;

	}
	protected function add_node_validation() {
		$validation_result = ( object ) array (
				'success' => false 
		);
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'module', 'Мөчир', 'required' );
		$form_validation->set_rules ( 'node_type', 'Мөчир төрөл', 'required|min_length[5]' );
		$form_validation->set_rules ( 'event_id', 'Event', 'required|is_natural_no_zero' );
		$form_validation->set_rules ( 'gate', 'Логик хаалга', 'required' );
		$form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
		
		// run form validation
		if ($form_validation->run ()) {
			$validation_result->success = true;
		} else
			$validation_result->error_message = $form_validation->error_string ();
		$validation_result->error_fields = $form_validation->_error_array;
		return $validation_result;
	}
	
	// insert
	protected function add_node() {
		$CI = &get_instance ();
		$ftree_id = $CI->input->get_post ( 'ftree_id' );
		
		$this->my_model->set_table ( 'f_tree' );
		$equipment_id = $this->my_model->get_row ( 'equipment_id', array (
				'id' => $ftree_id 
		) );
		
		$module = $CI->input->get_post ( 'module' );
		$node_type = $CI->input->get_post ( 'node_type' );
		$event_id = $CI->input->get_post ( 'event_id' );
		$gate = $CI->input->get_post ( 'gate' );
				
		$an_val = $this->add_node_validation ();
		
		if ($an_val->success) {
			$data ['parent'] = $ftree_id;
			$data ['equipment_id'] = $equipment_id;
			$data ['module'] = $module;
			$data ['node_type'] = $node_type;
			$data ['event_id'] = $event_id;
			$data ['gate'] = $gate;
			$data ['updated_by'] = $this->user_id;
			$data ['updated_at'] = $this->set_time ();
			
			// herev tend yamar neg tohioldol bval guitsetgehgui
			// where parent_0 and equipment = $equipment_id
			$this->my_model->set_table ( 'f_tree' );

			if ($this->my_model->insert ( $data ) !== FALSE) {
				$status = array (
						'status' => 'success',
						'message' => ' Мөчир амжилттай нэмлээ!' 
				);
			}
		} else {
			$status = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 
			);
		}
		
		return $status;
	}
	protected function delete_node() {
		$CI = &get_instance ();
		$ftree_id = $CI->input->get_post ( 'id' );
		
		// herev tend yamar neg tohioldol bval guitsetgehgui
		// where parent_0 and equipment = $equipment_id
		$this->my_model->set_table ( 'f_tree' );
		
		if ($this->_delete ( $ftree_id ) !== FALSE) {
			$status = array (
					'status' => 'success',
					'message' => 'Мөчрийг амжилттай устгалаа!' 
			);
		} else {
			$status = array (
					'status' => 'failed',
					'message' => 'Устгахад алдаа гарлаа!' 
			);
		}
		return $status;
	}
	
	// call edit
	protected function edit_node() {
		$CI = &get_instance ();
		$ftree_id = $CI->input->get_post ( 'id' );
		$this->my_model->set_table ( 'f_tree' );
		$equipment_id = $this->my_model->get_row ( 'equipment_id', array (
				'id' => $ftree_id 
		) );

		$module = $CI->input->get_post ( 'module' );
		$node_type = $CI->input->get_post ( 'node_type' );
		$event_id = $CI->input->get_post ( 'event_id' );
		$gate = $CI->input->get_post ( 'gate' );
		
		$an_val = $this->add_node_validation ();

		$this->my_model->set_table ( 'f_tree' );
		$has_id = $this->my_model->get_row('id', array('parent'=>$ftree_id));
		
		if($has_id&&$node_type=='undevelop'){
			$status = array (
				'status' => 'failed',
				'message' => 'Дэд хэсгийг [undevelop]- тодорхой бус төрлөөр сонгох боломжгүй '
			);
		}else{
			if ($an_val->success) {
				$data ['module'] = $module;
				$data ['node_type'] = $node_type;
				$data ['event_id'] = $event_id;
				$data ['gate'] = $gate;
				
				// herev tend yamar neg tohioldol bval guitsetgehgui
				// where parent_0 and equipment = $equipment_id
				$this->my_model->set_table ( 'f_tree' );
				if ($this->my_model->update ( $data, array (
						'id' => $ftree_id 
				) ) !== FALSE) {
					$status = array (
							'status' => 'success',
							'message' => 'Утгийг амжилттай заслаа!' 
					);
				}
			} else {
				$status = array (
						'status' => 'failed',
						'message' => validation_errors ( '', '<br>' ) 
				);
			}
		}
		
		return $status;
	}
	
	// drug node
	protected function drag_node() {
		// $sql ="UPDATE tree SET parent_id='" . $_REQUEST['parentid'] . "' WHERE id='id'";
		$CI = &get_instance ();
		$data ['parent'] = $CI->input->get_post ( 'parentid' );
		$ftree_id = $CI->input->get_post ( 'id' );
		$this->my_model->set_table ( 'f_tree' );
		if ($this->my_model->update ( $data, array (
				'id' => $ftree_id 
		) ) !== FALSE) {
			$status = array (
					'status' => 'success',
					'message' => 'Мөчир амжилттай зөөгдлөө!' 
			);
		} else {
			$status = array (
					'status' => 'failed',
					'message' => 'Зөөлт амжилтгүй боллоо!' 
			);
		}
		
		return $status;
	}
	
	// event add
	protected function event() {
		// $sql ="UPDATE tree SET parent_id='" . $_REQUEST['parentid'] . "' WHERE id='id'";
		$CI = &get_instance ();
		// end employee_id-g avna
		$event = $CI->input->get_post ( 'event' );
		
		// herev ene event burtgegdsen bval
		$data ['event'] = $event;
		$data ['updatedby_id'] = $this->user_id;
		$data ['updated_at'] = $this->set_time ();
		// get_like add in my model
		
		$this->my_model->set_table ( 'f_event' );
		if (! $this->my_model->get_like ( 'event', $event )) {
			if ($this->my_model->insert ( $data ) !== FALSE) {
				$status = array (
						'status' => 'success',
						'message' => 'Event амжилттай хадгалагдлаа!' 
				);
			} else {
				$status = array (
						'status' => 'failed',
						'message' => 'Event хадгалахад алдаа гарлаа!' 
				);
			}
		} else {
			// dahin burgeh shaardlaggui!!
			$status = array (
					'status' => 'failed',
					'message' => "$event аль хэдийн бүртгэгдсэн байна! Өөр утга оруулна уу!" 
			);
		}
		return $status;
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
		$table = $this->my_model->check_table ( 'vw_ftree_list' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		$section_id = $CI->input->get_post ( 'section_id' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		
		$sec_array = array (
				'COM',
				'NAV',
				'SUR',
				'ELC' 
		);
		
		if (($search == 'true') && ($filters != "")) {
			$where = $this->filter ( $filters );
		}
		
		if (in_array ( $this->get_seccode (), $sec_array )) {
			if ($where) {
				$where .= " and section_id = " . $this->check_section ();
			} else
				$where .= " WHERE section_id = " . $this->check_section ();
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		
		if ($where)
			$query = $this->get_query ( " count(*) as count ", 'vw_ftree_list', $where );
		else
			$query = $this->get_query ( " count(*) as count ", 'vw_ftree_list' );
		
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
		$Qry = $this->get_query ( ' * ', 'vw_ftree_list', $where, $sidx, $sord, $start, $limit );
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['code'] = $this->get_seccode ();
		
		foreach ( $Qry->result () as $row ) {
			$crow ['id'] = $row->equipment_id;
			$crow ['equipment'] = $row->equipment;
			$crow ['section'] = $row->section;
			$crow ['sector'] = $row->sector;
			$crow ['node'] = $row->node;
			$crow ['updated_by'] = $row->updated_by;
			$crow ['updated_at'] = $row->updated_at;
			
			array_push ( $row_bind, $crow );
		}
		$json ['rows'] = $row_bind;
		$Qry->free_result ();
		return json_encode ( $json );
	}
	protected function action() {
		$res_array = array ();
		$CI = &get_instance ();
		
		$res_array = $this->my_model->get_actionby ( $this->get_role (), 'ftree', 'index' );

		return ($res_array) ?  $res_array : array();
	}
	protected function delete() {
		$CI = &get_instance ();
		$this->my_model->set_table ( 'vw_ftree_list' );
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
			$this->my_model->set_table ( 'f_tree' );
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
	
	// copy paste uildel
	protected function copy() {
		// $sql ="UPDATE tree SET parent_id='" . $_REQUEST['parentid'] . "' WHERE id='id'";
		$CI = &get_instance ();
		// end employee_id-g avna
		$copy_id = $CI->input->get_post ( 'copy_id' );
		$target_id = $CI->input->get_post ( 'target_id' );
		
		// target_id deer utgatai bval aldaanii message ugnu
		$has_tree = $this->new_model->get_row ( 'module', array (
				'equipment_id' => $target_id 
		), 'f_tree' );
		$copied_equip = $this->new_model->get_row ( 'equipment', array (
				'equipment_id' => $copy_id 
		), 'equipment2' );
		$target_equip = $this->new_model->get_row ( 'equipment', array (
				'equipment_id' => $target_id 
		), 'equipment2' );
		
		if ($has_tree)
			$status = array (
					'status' => 'failed',
					'message' => "[$target_equip]" . ' дээр Алдааны мод үүссэн байгаа тул хуулах боломжгүй! Алдааны модыг устгасны дараа [PASTE] үйлдэл боломжтой!' 
			);
		else {
			
			$result = $CI->db->query ( "CALL copy_ftree($copy_id, $target_id);" )->result ();
			
			if ($result) {
				$status = array (
						'status' => 'success',
						'message' => "[$copied_equip] төхөөрөмжийн Алдааны Модыг [$target_equip] төхөөрөмж дээр амжилттай хууллаа!" 
				);
			} else {
				// dahin burgeh shaardlaggui!!
				$status = array (
						'status' => 'failed',
						'message' => "Энэ үйлдлийг хийхэд алдаа гарлаа!" 
				);
			}
		}
		
		return $status;
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
	function check_section() {
		// $this->log_model->query($this->section_id
		$sec_code = $this->new_model->get_row ( 'code', array (
				'section_id' => $this->section_id 
		), 'section' );
		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'SUR' || $sec_code == 'ELC') {
			return $this->section_id;
		} else
			return null;
	}
}
class eFtree extends eFtree_Driver {
	private $controller;
	private $method;
	private $state;
	private $cert_id;
	public $url;
	public $script;
	public $data = array ();
	protected $column;
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
			// Add tree here
			
			case 'add_tree' :
				$data ['json'] = json_encode ( $this->add_tree () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'grid' :
				$data ['json'] = $this->grid ();
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// edit form here
			case 'edit_node' :
				
				$data ['json'] = json_encode ( $this->edit_node () );
				$data ['xml'] = '';
				$data ['view'] = false;
				return ( object ) $data;
				
				break;
			
			// // Show Trainer page shows
			case 'add' :
				$data ['json'] = json_encode ( $this->get ( $this->cert_id ) );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'delete_node' :
				$data ['json'] = json_encode ( $this->delete_node () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'add_node' :
				$data ['json'] = json_encode ( $this->add_node () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'drag_node' :
				$data ['json'] = json_encode ( $this->drag_node () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'event' :
				$data ['json'] = json_encode ( $this->event () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'copy' :
				$data ['json'] = json_encode ( $this->copy () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			default :
				$data ['action'] = $this->action ();
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