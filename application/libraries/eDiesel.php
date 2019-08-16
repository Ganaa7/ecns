<?php
class eDiesel_Modeler {
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
		$ci->load->model ( 'my_model_old' );
		$this->my_model = new my_model_old ();
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
class eDiesel_Driver extends eDiesel_Modeler {
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
		$filters = json_decode ( $filters );
		$where = " where ";
		$whereArray = array ();
		$rules = $filters->rules;
		$groupOperation = $filters->groupOp;
		foreach ( $rules as $rule ) {
			$fieldName = $rule->field;
			$fieldData = mysql_real_escape_string ( $rule->data );
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
	
	// =edit validation
	protected function input_validation() {
		$validation_result = ( object ) array (
				'success' => false 
		);
		$form_validation = $this->form_validation ();
		$form_validation->set_rules ( 'pk_id', 'Дугаар', 'required' );
		$form_validation->set_rules ( 'bank_fuel', 'Банкинд байга түлшний хэмжээ', 'required|decimal' );
		$form_validation->set_message ( 'decimal', ' "%s" нь бутархай тоогоор оруулах ёстой! Жишээ нь: 100.1 /литр/' );
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
		$data ['bank_id'] = $i->get_post ( 'bank_id' );
		$data ['fuel'] = $i->get_post ( 'bank_fuel' );
		$data ['checkedby_id'] = $this->user_id;
		// here employee_id
		// here date time
		date_default_timezone_set ( ECNS_TIMEZONE );
		
		$data ['datetime'] = date ( "Y-m-d H:i:s" );
		return $data;
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
		$table = $this->my_model->check_table ( 'view_diesel' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		
		$query = $this->get_query ( " count(*) as count ", 'view_diesel' );
		
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
		$Qry = $this->get_query ( ' * ', 'view_diesel', $where, $sidx, $sord, $start, $limit );
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		$sub_t = 0;
		$flag = true;
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['code'] = $this->get_seccode ();
		
		foreach ( $Qry->result () as $row ) {
			$crow ['id'] = $row->id;
			$crow ['location'] = $row->location;
			$crow ['code'] = $row->code;
			$crow ['main_equipment'] = $row->main_equipment;
			$crow ['main_equipment_id'] = $row->main_equipment_id;
			$crow ['equipment'] = $row->equipment;
			$crow ['power'] = $row->power;
			$crow ['consumption'] = $row->consumption . ' л/ц';
			$crow ['bank'] = $row->bank . ' л';
			$crow ['bank_id'] = $row->bank_id;
			$crow ['capacity'] = $row->capacity . ' л';
			$crow ['total'] = $row->total . ' л';
			$crow ['filled'] = $row->filled;
			$crow ['fuel'] = $row->fuel . ' л';
			$crow ['workhour'] = round ( $row->workhour, 1 );
			// switch ($row->code) {
			// case 'BTG':
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'BMB':
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'HEN':
			// if($row->main_equipment=='DVOR')
			// $crow['workhour']=round($row->workhour, 1);
			// else
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'BRU':
			// if($row->main_equipment=='DVOR')
			// $crow['workhour']=round($row->workhour, 1);
			// else
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'IUL':
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'SHD':
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			// case 'MZT':
			// $crow['workhour']=round($row->workhour*2, 1);
			// break;
			
			// default:
			// $crow['workhour']=round($row->workhour, 1);
			// break;
			// }
			
			$crow ['checkedby'] = $row->checkedby;
			$crow ['uptime'] = date ( "Y/m/d H:i", strtotime ( $row->uptime ) );
			
			// get_sum attr of m_equipment_id from bank
			$this->set_table ( 'diesel' );
			$CI->config->set_item ( 's_t', $this->my_model->get_count ( 'id', 'bank_id = ' . $row->bank_id ) );
			$sub_t = $CI->config->item ( 's_t' );
			$crow ['sub_t'] = $sub_t;
			//Ажилглалтийн байгууламжуудыг үлдэгдлийг нэг харуулах
            $radar_bank = array(11, 12, 14, 16, 17, 18, 19);
			if(in_array($row->bank_id, $radar_bank )){
                if ($sub_t > 1) {
                    if ($flag == true) {
                        $crow ['attr'] = array (
                            'location' => array (
                                'rowspan' => $sub_t
                            ),
                            'main_equipment' => array (
                                'rowspan' => $sub_t
                            ),
                            'capacity' => array (
                                'rowspan' => $sub_t
                            ),
                            'total' => array (
                                'rowspan' => $sub_t
                            ),                                                
                            'filled' => array (
                                'rowspan' => $sub_t
                            ),'fuel' => array (
                                'rowspan' => $sub_t
                            ),
                            'workhour' => array (
                                'rowspan' => $sub_t
                            ),
                            'checkedby' => array (
                                'rowspan' => $sub_t
                            ),
                            'uptime' => array (
                                'rowspan' => $sub_t
                            )
                        );
                        $flag = false;
                    } else {
                        $crow ['attr'] = array (
                            'location' => array (
                                'display' => 'none'
                            ),
                            'main_equipment' => array (
                                'display' => 'none'
                            ),
                            'capacity' => array (
                                'display' => 'none'
                            ),
                            'total' => array (
                                'display' => 'none'
                            ),
                            'filled' => array (
                                'rowspan' => 1
                            ),                            
                            'fuel' => array (
                                'display' => 'none'
                            ),
                            'workhour' => array (
                                'rowspan' => 1
                            ),
                            'checkedby' => array (
                                'display' => 'none'
                            ),
                            'uptime' => array (
                                'display' => 'none'
                            )
                        );
                        $flag = true;
                    }
                } else {
                    $crow ['attr'] = array (
                        'location' => array (
                            'rowspan' => 1
                        ),
                        'main_equipment' => array (
                            'rowspan' => 1
                        ),
                        'capacity' => array (
                            'rowspan' => 1
                        ),
                        'total' => array (
                            'rowspan' => 1
                        ),
                          'filled' => array (
                                'rowspan' => 1
                            ),                          
                        'fuel' => array (
                            'rowspan' => 1
                        ),
                        'workhour' => array (
                            'rowspan' => 1
                        ),
                        'checkedby' => array (
                            'rowspan' => 1
                        ),
                        'uptime' => array (
                            'rowspan' => 1
                        )
                    );
                }
            }else{
                if ($sub_t > 1) {
                    if ($flag == true) {
                        $crow ['attr'] = array (
                            'location' => array (
                                'rowspan' => $sub_t
                            ),
                            'main_equipment' => array (
                                'rowspan' => $sub_t
                            ),
                            'capacity' => array (
                                'rowspan' => $sub_t
                            ),
                            'total' => array (
                                'rowspan' => $sub_t
                            ),
                              'filled' => array (
                                'rowspan' =>  $sub_t
                            ),
                            'fuel' => array (
                                'rowspan' => $sub_t
                            ),
                            'workhour' => array (
                                'rowspan' => 1
                            ),
                            'checkedby' => array (
                                'rowspan' => $sub_t
                            ),
                            'uptime' => array (
                                'rowspan' => $sub_t
                            )
                        );
                        $flag = false;
                    } else {
                        $crow ['attr'] = array (
                            'location' => array (
                                'display' => 'none'
                            ),
                            'main_equipment' => array (
                                'display' => 'none'
                            ),
                            'capacity' => array (
                                'display' => 'none'
                            ),
                            'total' => array (
                                'display' => 'none'
                            ),
                             'filled' => array (
                                'rowspan' =>  1
                            ), 
                            'fuel' => array (
                                'display' => 'none'
                            ),
                            'workhour' => array (
                                'rowspan' => 1
                            ),
                            'checkedby' => array (
                                'display' => 'none'
                            ),
                            'uptime' => array (
                                'display' => 'none'
                            )
                        );
                        $flag = true;
                    }
                } else {
                    $crow ['attr'] = array (
                        'location' => array (
                            'rowspan' => 1
                        ),
                        'main_equipment' => array (
                            'rowspan' => 1
                        ),
                        'capacity' => array (
                            'rowspan' => 1
                        ),
                        'total' => array (
                            'rowspan' => 1
                        ),
                         'filled' => array (
                                'rowspan' =>  1
                            ),                         
                        'fuel' => array (
                            'rowspan' => 1
                        ),
                        'workhour' => array (
                            'rowspan' => 1
                        ),
                        'checkedby' => array (
                            'rowspan' => 1
                        ),
                        'uptime' => array (
                            'rowspan' => 1
                        )
                    );

                }
			}
			array_push ( $row_bind, $crow );
		}
		$json ['rows'] = $row_bind;
		$Qry->free_result ();
		return json_encode ( $json );
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
	protected function get($id) {
		// check this role can call this funciton
		$data = $this->get_all ( array (
				'id' => $id 
		), 'view_diesel' );
		return $data;
	}
	
	// =insert()
	protected function insert() {
		$data = $this->get_input_data ();
		$edata = array ();
		
		$validation_result = $this->input_validation ();
		if ($validation_result->success) {
			$this->my_model->set_table ( 'bank_fuel' );
			if ($this->my_model->insert ( $data )) {
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
class eDiesel extends eDiesel_Driver {
	private $state;
	private $id;
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
		$this->id = $CI->uri->segment ( 4 );
	}
	
	// used for show column
	function column() {
		// TODO: харагдах fielduud ba.
		$args = func_get_args ();
		$this->column = $args;
	}
	function set_role($role) {
		return $this->set_user_role ( $role );
	}
	function get_role() {
		return $this->get_user_role ();
	}
	
	// check state if state is null then check function
	function check_state() {
		// if ($this->get_role () == 'ENG') {
		// 	$this->state = 'ownpage';
		// } else if (! $this->state) {
		// 	$this->state = 'none';
		// }
		return $this->state;
	}
	function run() {
		// initalizing pros
		$this->init_table ();
		$data = array ();
		$data ['view'] = true;
		
		switch ($this->check_state ()) {
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
			case 'page' :
				$data ['page'] = 'diesel\page';
				$data ['title'] = 'Гэрчилгээ';
				$data ['json'] = json_encode ( $this->get ( $this->id ) );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// Edit засах хуудас дуудахад энд
			case 'insert' :
				$data ['page'] = 'diesel\index';
				$data ['title'] = '';
				$data ['json'] = json_encode ( $this->insert () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			// index page duudahad end orno!!
			default :
				$data ['page'] = 'diesel\index';
				$data ['title'] = 'Дизель';
				// $data['action']=$this->action();
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