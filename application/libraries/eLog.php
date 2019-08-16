<?php
class eLog_Modeler {
	private $user_role;
	public $user_id;
	public $log_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	public $section_id;
	public $user_model = null;
	public $alert_model = null;
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
		$this->log_model = new my_model_old ();
		$ci->load->model ( 'user_model' );
		$this->user_model = new user_model ();
		$ci->load->model ( 'alert_model' );
		$this->alert_model = new alert_model ();
	}
	protected function set_table($table) {
		if ($this->log_model->get_table ())
			$this->log_model->unset_table ();
		$this->log_model->set_table ( $table );
	}
	protected function get_location() {
		return $this->log_model->get_select ( 'name' );
	}
	protected function get_select($name, $table = null, $where = null) {
		if ($table) {
			$this->log_model->set_table ( $table );
		}
		if ($where)
			return $this->log_model->get_select ( $name, $where );
		else
			return $this->log_model->get_select ( $name );
	}
	protected function get_row($name, $array, $table = null) {
		if ($table) {
			$this->log_model->set_table ( $table );
		} else
			$this->log_model->set_table ( 'log' );
		
		return $this->log_model->get_row ( $name, $array );
	}
	
	// Хэрэв view bval
	protected function get_all($array = null, $table = null) {
		return $this->log_model->get_all ( $array, $table );
	}
	protected function get_query($select, $table = null, $where = null, $sidx = null, $sord = null, $start = null, $limit = null) {
		if ($table) {
			$this->log_model->set_table ( $table );
		} else {
			$this->log_model->set_table ( 'log' );
		}
		return $this->log_model->get_query ( $select, $table, $where, $sidx, $sord, $start, $limit );
	}
	protected function get_role() {
		return $this->user_role;
	}
}
class eLog_Driver extends eLog_Modeler {
	public $objdata;
	private $upload_path = "download/log_files/";
	private function secs_to_str($d, $hour = "ц", $min = "'", $sec = "''") {
		$periods = array (
				$hour => 3600,
				$min => 60,
				$sec => 1 
		);
		$parts = array ();
		foreach ( $periods as $name => $dur ) {
			$div = floor ( $d / $dur );
			if ($div == 0)
				continue;
			else if ($div == 1) {
				$div = (strlen ( $div ) == 1) ? "0" . $div : $div;
				$parts [] = $div . "" . $name;
			} else {
				$div = (strlen ( $div ) == 1) ? "0" . $div : $div;
				$parts [] = $div . "" . $name . "";
			}
			
			$d %= $dur;
		}
		$last = array_pop ( $parts );
		if (empty ( $parts ))
			return $last;
		else
			return join ( ', ', $parts ) . ":" . $last;
	}
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
	private function ojjquhXb($filters) {
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
                            $where .= " closed IN ('C', 'A', 'N', 'O', 'F') AND ";
                    else
                            $where .= " closed IN ('Y') AND ";
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
	
	// insert data
	protected function add() {
            $CI = &get_instance ();
            // $CI->load->library('session');
            $CI->load->library ( 'form_validation' );

            $CI->form_validation->set_rules ( 'created_datetime', 'Гэмтэл гарсан огноо', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' ); // /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/
            $CI->form_validation->set_rules ( 'location_id', 'Байршил', 'required|is_natural_no_zero' ); //
            $CI->form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' ); //
            $CI->form_validation->set_rules ( 'defect', 'Гарсан  гэмтэл', 'required' ); //
            $CI->form_validation->set_rules ( 'reason', 'Шалтгаан', 'required' ); //

            $CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
            $CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );

            if ($CI->form_validation->run () != FALSE) {
                // createdby_id хэрэгтэй
                $equipment_id = $CI->input->get_post ( 'equipment_id' );
                $data ['createdby_id'] = $this->user_id;
                $data ['created_datetime'] = $CI->input->get_post ( 'created_datetime' );
                $data ['equipment_id'] = $equipment_id;
                $data ['location_id'] = $CI->input->get_post ( 'location_id' );
                $data ['defect'] = $CI->input->get_post ( 'defect' );
                $data ['reason'] = $CI->input->get_post ( 'reason' );
                $data ['closed'] = 'C';
                $data ['section_id'] = $this->get_row ( 'section_id', array (
                                'equipment_id' => $equipment_id 
                ), 'equipment' );
                $data ['sec_code'] = $this->get_row ( 'sec_code', array (
                                'equipment_id' => $equipment_id 
                ), 'equipment' );
                $equipment = $this->get_row ( 'name', array (
                                'equipment_id' => $equipment_id 
                ), 'equipment' );
                $this->set_table ( 'log' );

                if ($this->log_model->insert ( $data ) !== FALSE) {
                    // herev gemtel гарсан бол бүртгэл нээх
                    $section_id = $data ['section_id'];
                    //$this->alert_model->set_notify ( 'log', "$this->user_id", "$section_id, 8, 9", "$equipment төхөөрөмж дээр гэмтэлийн бүртгэл нээлээ!" );
                    $return = array (
                        'status' => 'success',
                        'message' => '<strong>"' . $equipment . '"</strong> төхөөрөмж дээр ' . $data ['created_datetime'] . ' -д шинэ гэмтэл бүртгэл нээлээ!' 
                    );
                } else {
                        $return = array (
                                        'status' => 'failed',
                                        'message' => 'Хадгалахад алдаа гарлаа ' 
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
		if (! $sidx)
			$sidx = 1;
		
		$sord = $CI->input->get_post ( 'sord' );
		$table = $this->log_model->check_table ( 'view_logs' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$where = "";
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		// var_dump("section_id".$this->input->get_post('section_id'), $this->input->get_post('sector_id'), $this->input->get_post('equipment_id'), $this->input->get_post('log'), $this->input->get_post('start_dt'),$this->input->get_post('end_dt'));
		// if ($this->input->get_post('_search') == 'true'){
		// $where=$this->get_where_clause($searchField,$searchOper,$searchString);
		// //list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
		// }else{
		//
		// if($section_id||$sector_id||$equipment_id||$log||$date_option||$start_dt||$end_dt){
		// $where=$this->get_where_ids($section_id,$sector_id,$equipment_id,$log, $date_option, $start_dt,$end_dt);
		// }
		// }
		$section_id = $CI->input->get_post ( 'section_id' );
		$sector_id = $CI->input->get_post ( 'sector_id' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		$log = $CI->input->get_post ( 'log' );
		$date_option = $CI->input->get_post ( 'date_option' );
		$start_dt = $CI->input->get_post ( 'start_dt' );
		$end_dt = $CI->input->get_post ( 'end_dt' );
		
		if ($section_id || $sector_id || $equipment_id || $log || $date_option || $start_dt || $end_dt) {
			$where = $this->get_where_ids ( $section_id, $sector_id, $equipment_id, $log, $date_option, $start_dt, $end_dt );
			$qry = $where;
		} else if ($this->check_section ()) {
			$where = $this->get_where_ids ( $this->check_section () );
		}
		
		if (($search == 'true') && ($filters != "")) {
			$where = $this->ojjquhXb ( $filters );
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		
		$query = $this->get_query ( " count(*) as count ", 'view_logs', $where );
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
		$Qry = $this->get_query ( ' * ', 'view_logs', $where, $sidx, $sord, $start, $limit );
		$qry2 = $this->log_model->last_query ();
		
		$xml = "<?php xml version='1.0' encoding='utf-8'?>";
		$xml .= "<rows>";
		$xml .= "<page>" . $page . "</page>";
		$xml .= "<total>" . $total_pages . "</total>";
		$xml .= "<records>" . $count . "</records>";
		if (isset ( $qry ))
			$xml .= "<query>" . $qry . "</query>";
		$xml .= "<query2>" . $qry2 . "</query2>";
		$xml .= "<limit>" . $limit . "</limit>";
		$xml .= "<start>" . $start . "</start>";
		foreach ( $Qry->result () as $row ) {
			$xml .= "<row id='" . $row->log_id . "'>";
			$xml .= "<cell><![CDATA[" . $row->q_level . "]]></cell>";
			$xml .= "<cell><![CDATA[" . $row->section . "]]></cell>";
			$xml .= "<cell><![CDATA[" . $row->log_num . "]]></cell>";
			$xml .= "<cell><![CDATA[" . $row->created_datetime . "]]></cell>";
			$xml .= "<cell>" . $row->location . "</cell>";
			$xml .= "<cell><![CDATA[" . $row->equipment . "]]></cell>";
			$xml .= "<cell><![CDATA[" . $row->defect . "]]></cell>";
			$xml .= "<cell>" . $row->closed_datetime . "</cell>";
			$xml .= "<cell>" . $row->duration_time . "</cell>";
			// $xml .= "<cell>". date('H:i:s', $row->duration_time)."</cell>";
			$xml .= "<cell><![CDATA[" . $row->completion . "]]></cell>";
			$xml .= "<cell><![CDATA[" . $row->ezi . "]]></cell>";
			$xml .= "<cell>" . $row->closed . "</cell>";
			$xml .= "</row>";
		}
		$xml .= "</rows>";
		
		$Qry->free_result ();
		return $xml;
	}
	protected function update() {
		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );
		// log_id -р тухайн моделийн утгуудыг авна.
		$log_id = $CI->input->post ( 'log_id' );
		
		$CI->form_validation->set_rules ( 'created_datetime', 'Нээсэн огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		$CI->form_validation->set_rules ( 'location_id', 'Байршил', 'required|is_natural_no_zero' ); //
		$CI->form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' ); // |exact_length[10]
		$CI->form_validation->set_rules ( 'reason', 'Шалтгаан', 'required' ); //
		$CI->form_validation->set_rules ( 'defect', 'Гэмтэл', 'required' ); // |valid_email|max_length[50]
		
		$closed = $this->get_row ( 'closed', array (
				'log_id' => $log_id 
		) );
		
		$log_data = array (
				// 'createdby_id' => $CI->input->post('createdby_id'),
				'created_datetime' => $CI->input->post ( 'created_datetime' ),
				'location_id' => $CI->input->post ( 'location_id' ),
				'equipment_id' => $CI->input->post ( 'equipment_id' ),
				'reason' => $CI->input->post ( 'reason' ),
				'defect' => $CI->input->post ( 'defect' ),
				'editedby_id' => $this->user_id 
		);
		
		$old_equipment_id = $this->get_row ( 'equipment_id', array (
				'log_id' => $log_id 
		) );
		
		if ($old_equipment_id != $CI->input->post ( 'equipment_id' )) {
			$log_data ['log_num'] = $this->log_model->get_log_num ( $CI->input->post ( 'equipment_id' ) );
			$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн төхөөрөмжийг өөрчилсөн тул <strong>"' . $log_data ['log_num'] . '"</strong> дугаар олгож утгуудыг амжилттай заслаа!';
		} else
			$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн утга амжилттай хадгаллаа!';
		
		if ($closed == 'N'||$closed == 'Q') { // N bolon Q tohioldold l hiih bolomjtoi
			$CI->form_validation->set_rules ( 'closed_datetime', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' ); // |valid_email|max_length[50]
           // $CI->form_validation->set_rules('closedby_id', 'Хаасан ИТА', 'required|is_natural_no_zero'); //
			$CI->form_validation->set_rules ( 'duration_time', 'Хаасан огноо', 'required|greater_than[0]' );
			$CI->form_validation->set_rules ( 'completion', 'Гүйцэтгэл', 'required|min_length[30]' ); //			
			$log_data ['closed_datetime'] = $CI->input->post ( 'closed_datetime' );
			unset($log_data['closedby_id']);
			// $log_data ['closedby_id'] = $CI->input->post ( 'closedby_id' );
			$log_data ['completion'] = $CI->input->post ( 'completion' );
			$log_data ['duration_time'] = strtotime ( $CI->input->post ( 'closed_datetime' ) ) - strtotime ( $CI->input->post ( 'created_datetime' ) );			
		}
		$CI->form_validation->set_message ( 'greater_than', ' "%s" "Нээсэн огноо"-с их байх шаардлагатай!' );
		$CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
		$CI->form_validation->set_message ( 'min_length', ' "%s" утга дор хаяж "%s" тэмдэгтийн урттай байх шаардлагатай тул дэлгэрэнгүй бичнэ үү!' );
		
		// Set the form validation rules
		if ($CI->form_validation->run () !== FALSE) {
			// Passed the form validation
			if ($this->log_model->update ( $log_data, array (
					'log_id' => $log_id 
			) ) !== FALSE) {
				$this->action_log ( $log_id, 'update' );
				$return = array (
						'status' => 'success',
						'message' => $msg 
				);
				// set the output status, message and table row html
				// print out the JSON encoded success/user details
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Хадгалахад алдаа гарлаа ' . $CI->input->post ( 'id' ) 
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
	protected function delete() {
		$CI = &get_instance ();
		
		if ($this->log_model->delete ( array (
				'log_id' => $CI->input->post ( 'id' ) 
		) )) {
			$this->action_log ( $CI->input->post ( 'id' ), 'delete' );
			// The user was successfully removed from the table
			$return = array (
					'status' => 'success',
					'message' => 'Энэ гэмтэл амжилттай устгагдлаа!' 
			);
		} else {
			$return = array (
					'status' => 'failed',
					'message' => $CI->input->post ( 'id' ) . ' устгахад алдаа гарлаа!' 
			);
		}
		return $return;
	}
	protected function get() {
		$CI = &get_instance ();
		$log_id = $CI->input->post ( 'id' );
		$log = $this->get_all ( array (
				'log_id' => $log_id 
		), 'view_logs' );
		$log ['log_id'] = $log_id;
		$duration = $log ['duration_time'];
		$log ['duration_time'] = $duration;
		// var_dump($msg);
		if ($log)
			$return = array (
					'status' => 'success',
					'message' => 'successfully',
					'log' => $log 
			);
		else
			$return = array (
					'status' => 'failed',
					'message' => "This id couldn't update: " . $CI->input->post ( 'id' ) 
			);
		
		return $return;
	}
	// active hiihed аюулгүй ажиллагааны чанарыг 
	// Зөвшөөрөх үйлдэл хийгдсэнээр тухайн гэмтэл, дутагдлын мэдээг ИТА, болон Чанарын хэлтэс уруу 
	// Имэйлээр автоматаар илгээгдэх болно!
	protected function active() {
		$CI = &get_instance ();
		$CI->load->library ( 'session' );
		$CI->load->library ( 'form_validation' );
		$CI->load->library('../controllers/alert');
		
		$log_id = $CI->input->get_post ( 'log_id' );
		$closed = $CI->input->get_post ( 'closed' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		
		$log_num = $CI->input->post ( 'log_num' );
		
		// $data ['inst'] = $CI->input->post ( 'inst' );
		// $data ['level'] = $CI->input->post ( 'level' );	

		if ($log_num == null) {
			// if log_num bhgui bol ene gemteld log_num shineer ugch
			$this->log_model->set_table ( 'log' );
			$log_num = $this->log_model->get_log_num ( $equipment_id );
			$data ['log_num'] = $log_num;
			// user_id -g activatedby -d utgiig ugnu
			$status = 'success';
			$data ['activatedby_id'] = $this->user_id;
			$data ['closed'] = 'A';
			$msg = 'Гэмтэл бүртгэл нээхийг зөвшөөрч <strong>"' . $log_num . '"</strong> дугаар өглөө!';
		} elseif ($closed == "N") {
			$data ['proveby_id'] = $this->user_id;
			$status = 'success';
			$data ['closed'] = 'Q'; //Q үсэг тавих ёстой!!!
			// emails // chanar-n ajilchid
			//alert controller duudah heregtei ba 
			//$log_id - r ni section_id avch yavuulah
			if($CI->alert->quality($log_num))
			   $msg = $log_num . ' дугаартай гэмтлийг хаахыг хүсэлтийг зөвшөөрлөө!';
			else $msg = $log_num . ' дугаартай гэмтлийг хаахыг хүсэлтийг зөвшөөрлөө!';
		} else {
			$status = 'failed';
			$msg = $log_num . ' дугаартай гэмтлийг хүсэлтийг аль хэдийн зөвшөөрсөн байна!';
		}		
		// $CI->form_validation->set_rules ( 'inst', 'Гэмтлийн зэрэглэл', 'required' ); //
		// $CI->form_validation->set_rules ( 'level', 'Давталт', 'required' ); // required|		
		//if ($CI->form_validation->run () != FALSE) {

		$this->log_model->set_table ( 'log' );
		if ($this->log_model->update ( $data, array (
				'log_id' => $log_id 
		) ) !== FALSE) {
			$return = array (
					'status' => $status,
					'message' => $msg,
					'db' => $this->log_model->last_query () 
			);
			// print out the JSON encoded success/user details
		} else {
			$return = array (
					'status' => 'failed',
					'message' => 'Хадгалахад алдаа гарлаа ' . $CI->input->post ( 'id' ) 
			);
		}
		// } else {
		// 	$return = array (
		// 			'status' => 'failed',
		// 			'message' => validation_errors ( '', '<br>' ) 
		// 	);
		// }
		return $return;
	}
	protected function close() {
		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );
		$log_id = $CI->input->post ( 'log_id' );
		$created_dt = strtotime ( $CI->input->post ( 'created_datetime' ) );
		$closed_dt = strtotime ( $CI->input->post ( 'closed_datetime' ) );
		
		$CI->form_validation->set_rules ( 'closed_datetime', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		$CI->form_validation->set_rules ( 'duration_time', 'Хаасан огноо', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'completion', 'Засварласан байдал', 'required|min_length[30]' );
		
		$diff_date = $closed_dt - $created_dt;
		
		$data = array (
				'closed_datetime' => $CI->input->post ( 'closed_datetime' ),
				'completion' => $CI->input->post ( 'completion' ),
				'duration_time' => $diff_date,
				'closed' => 'N',
				'closedby_id' => $this->user_id 
		);
		
		// duration-g togtooh yostoi
		$CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" "Нээсэн огноо"-с их байх шаардлагатай' );
		$CI->form_validation->set_message ( 'min_length', ' "%s" утга дор хаяж "%s" тэмдэгтийн урттай байх шаардлагатай тул дэлгэрэнгүй бичнэ үү!' );
		
		// Set the form validation rules
		if ($CI->form_validation->run () != FALSE) {
			// Passed the form validation
			if ($this->log_model->update ( $data, array (
					'log_id' => $log_id 
			) ) !== FALSE) {
				$this->action_log ( $log_id, 'closed' );
				$return = array (
						'status' => 'success',
						'message' => $CI->input->post ( 'log_num' ) . ' гэмтлийн утга амжилттай хадгаллаа!' 
				);
				// set the output status, message and table row html
				// print out the JSON encoded success/user details
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Хадгалахад алдаа гарлаа ' . $CI->input->post ( 'id' ) 
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
	protected function quality() {
		$CI = &get_instance ();
		$CI->load->library ( 'session' );
		$CI->load->library ( 'form_validation' );

		//check flog is it quality engineer or not?
		$log_id = $CI->input->post ( 'log_id' );
		$level = $CI->input->post ( 'level' ); //A, B, C
		$inst = $CI->input->post ( 'inst' );	//nomater
		if($level =='A'||$level =='B'||$level =='C') //бол файл хавсаргах шаардлагатай
		   $data ['closed'] = 'F';
		else // гэмтэл бүрэн хаагдсан!!!
		   $data ['closed'] = 'Y';

		$data ['inst'] = $inst;
		$data ['level'] = $level;
		
		$data ['qualityby_id'] = $this->user_id;
		
		$CI->form_validation->set_rules ( 'level', 'Хүндрэл түвшин', 'required' ); // required|
		$CI->form_validation->set_rules ( 'inst', 'Магадлал түвшин', 'required' ); //
		
		if ($CI->form_validation->run () != FALSE) {
			if ($this->log_model->update ( $data, array (
					'log_id' => $log_id 
			) ) !== FALSE) {
				$return = array (
						'status' => 'success',
						'message' => '"' . $CI->input->post ( 'log_num' ) . '" дугаартай гэмтлийг эрдлийг тогтоох үйлдэл амжилттай хийгдлээ.' 
				);
				// 'db'=>$this->log_model->last_query()
				// print out the JSON encoded success/user details
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Хадгалахад алдаа гарлаа ' . $CI->input->post ( 'id' ) 
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
	protected function action() {
            $res_array = array ();		
            $CI = &get_instance ();
            $res_array = $this->log_model->get_action ( $this->get_role () );				
            return $res_array;
	}
	function check_section() {
		// $this->log_model->query($this->section_id
		$sec_code = $this->get_row ( 'code', array (
				'section_id' => $this->section_id 
		), 'section' );
		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'SUR' || $sec_code == 'ELC') {
			return $this->section_id;
		} else
			return null;
	}
	function action_log($log_id, $action) {
            $CI = &get_instance ();
            date_default_timezone_set ( ECNS_TIMEZONE );
            $dt = date ( "Y-m-d H:i:s" );
            switch ($action) {
                    case 'update' :
                            $action_data = array (
                                            'log_id' => $log_id,
                                            'action' => 'edited',
                                            'equipment_id' => $CI->input->post ( 'equipment_id' ),
                                            'reason' => $CI->input->post ( 'reason' ),
                                            'defect' => $CI->input->post ( 'defect' ),
                                            'actionby_id' => $this->user_id,
                                            'datetime' => $CI->input->post ( 'created_datetime' ),
                                            'action_date' => $dt 
                            );
                            if ($CI->input->post ( 'completion' )) {
                                    $action_data ['completion'] = $CI->input->post ( 'completion' );
                                    $action_data ['datetime'] = $CI->input->post ( 'closed_datetime' );
                            }
                            break;

                    case 'delete' :
                            $action_data ['log_id'] = $log_id;
                            $action_data ['action'] = 'delete';
                            $action_data ['actionby_id'] = $this->user_id;
                            $action_data ['action_date'] = $dt;
                            break;

                    default :
                            $action_data ['log_id'] = $log_id;
                            $action_data ['action'] = 'close';
                            $action_data ['datetime'] = $CI->input->post ( 'closed_datetime' );
                            $action_data ['completion'] = $CI->input->post ( 'completion' );
                            $action_data ['actionby_id'] = $this->user_id;
                            $action_data ['action_date'] = $dt;
                            break;
            }
            $this->set_table ( 'log_action' );
            $this->log_model->insert ( $action_data );
            return true;
	}
	
	// check_file
	protected function check_file() {
		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );
		$log_id = $CI->input->post ( 'log_id' );
		$filename = $this->log_model->get_row ( 'filename', array (
				'log_id' => $log_id 
		) );
		$log_num = $this->log_model->get_row ( 'log_num', array (
				'log_id' => $log_id 
		) );
		// $this->log_model->update($data, array('log_id'=>$log_id);
		if ($filename)
			$return = array (
					'status' => 'success',
					'filename' => $filename,
					'log_id' => $log_id,
					'log_num' => $log_num 
			);
		else
			$return = array (
					'status' => 'failed',
					'filename' => $filename,
					'log_id' => $log_id,
					'log_num' => $log_num 
			);
		return $return;
	}

	// =file upload by ajax from file_dialog FLOG
	protected function upload() {
		// update hiihed user_id -g update hiine
		$file_name = $_FILES ['userfile'] ['name'];
		$log_id = $_POST ['log_id'];
		
		$is_cyrilic = ( bool ) preg_match ( '/[\p{Cyrillic}]/u', $file_name );
		$is_latin = ( bool ) preg_match ( '/[{ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ}]/u', $file_name );
		
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
		} else {
			$f_name = $this->key_gen ( 5 ) . '-' . $file_name;
			// config here
			$config = array (
					'allowed_types' => 'pdf|doc|docx|odt|xls|xlsx',
					'upload_path' => $this->upload_path,
					'max_size' => 10000,
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
						'delete_url' => $this->upload_path . $f_name,
						'log_id' => $log_id 
				);
				// update current cert file by id
				if ($this->log_model->update( array (
						'filename' => $f_name,
						'closed' => 'Y'
				), array (
						'log_id' => $log_id 
				) )) {
					$json ['status'] = 'success';
				} else {
					$json ['status'] = 'failed';
					$json ['message'] = 'Өгөгдлийн санд алдаа гарлаа!';
				}
			} else {
				$json = array (
						'status' => 'failed',
						'message' => $CI->upload->display_errors ( '', '' ) 
				);
			}
		}
		return $json;
	}

	// delete file from report
	protected function del_file() {
		$CI = &get_instance ();
		$id = $CI->input->get_post ( 'id' );
		$file_name = $CI->input->get_post ( 'file_name' );		
		// check is it has athentication for use this function

		// check file exists
		// var_dump($_SERVER['DOCUMENT_ROOT']."/ecns/download/cert_files/");
		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/log_files/" . $file_name )) {
			// file is unlicked?
			if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . "/ecns/download/log_files/" . $file_name )) {
				$this->log_model->set_table ( 'log' );
				$this->log_model->update ( array (
						'filename' => null, 
						'closed' => 'F'
				), array (
						'log_id' => $id 
				) );
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
                    $json = array (
                                    'status' => 'failed',
                                    'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!' 
                    );
		}
		
		return $json;
	}
	
	// xls files
	function get_columns() {
		$columns = array ();
		$cols = array (
				'section',
				'location',
				'equipment',
				'created_datetime',
				'closed_datetime',
				'defect',
				'reason',
				'completion',
				'createdby',
				'closedby',
				'ezi' 
		);
		$display_as = array (
				'section' => 'Хэсэг',
				'location' => 'Байршил',
				'equipment' => 'Төхөөрөмж',
				'created_datetime' => 'Эхэлсэн',
				'closed_datetime' => 'Дууссан',
				'defect' => 'Гэмтэл',
				'reason' => 'Шалтгаан',
				'completion' => 'Гүйцэтгэл',
				'createdby' => 'Нээсэн',
				'closedby' => 'Хаасан',
				'ezi' => 'ЕЗИ' 
		);
		
		foreach ( $cols as $col_num => $column ) {
			$columns [$col_num] = ( object ) array (
					'field_name' => $column,
					'display_as' => $display_as [$column] 
			);
		}
		
		return $columns;
	}
	function get_list() {
		$group = $this->user_model->setGroup ( $this->get_role () );
		$CI = &get_instance ();
		$CI->db->select ( '*' );
		$CI->db->from ( 'view_logs' );
		if ($group == 'ENG') {
			$CI->db->where ( 'section_id', $this->section_id );
		}
		$CI->db->order_by ( 'section asc, created_datetime desc' );
		return $CI->db->get ()->result ();
	}
	protected function exportToExcel() {
		$objdata->columns = $this->get_columns ();
		$objdata->list = $this->get_list ();
		$this->_export_to_excel ( $objdata );
	}
	protected function _export_to_excel($objdata) {
		$string_to_export = "";
		// print_r($objdata);
		// get columns
		foreach ( $objdata->columns as $column ) {
			$string_to_export .= $column->display_as . "\t";
		}
		$string_to_export .= "\n";
		// get lists
		foreach ( $objdata->list as $num_row => $row ) {
			foreach ( $objdata->columns as $column ) {
				$string_to_export .= $this->_trim_export_string ( $row->{$column->field_name} ) . "\t";
			}
			$string_to_export .= "\n";
		}
		
		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" . mb_convert_encoding ( $string_to_export, 'UTF-16LE', 'UTF-8' );
		$filename = "export-" . date ( "Y-m-d_H:i:s" ) . ".xls";
		header ( 'Content-type: application/vnd.ms-excel;charset=UTF-8' );
		header ( 'Content-Disposition: attachment; filename=' . $filename );
		header ( "Cache-Control: no-cache" );
		// header('Content-Type: text/html; charset=utf-8');
		echo $string_to_export;
		die ();
	}
	protected function _trim_export_string($value) {
		$value = str_replace ( array (
				"&nbsp;",
				"&amp;",
				"&gt;",
				"&lt;" 
		), array (
				" ",
				"&",
				">",
				"<" 
		), $value );
		return strip_tags ( str_replace ( array (
				"\t",
				"\n",
				"\r" 
		), "", $value ) );
	}
}
class eLog extends eLog_Driver {
	private $controller;
	private $method;
	private $state;
	public $url;
	public $script;
	public $data = array ();
	function __construct() {
		$this->setStateFromUrl ();
		$script = " ";
		// $this->user_id = $user_id;
	}
	private function setStateFromUrl() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		$this->state = $CI->uri->segment ( 3 );
	}
	protected function setControllerName() {
		$this->controller = $this->getControllerName ();
	}
	protected function setMethodName() {
		$this->method = $this->getMethodName ();
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
	
	// section_id
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
		if (! $this->state)
			$this->state = 'open';
		return $this->state;
	}
	function run() {
		// check state add, edit, delete
		// initalizing pros
		$this->init_table ();
		$data = array ();
		$data ['view'] = true;
		
		switch ($this->check_state ()) {
			case 'grid' :
				$data ['json'] = null;
				$data ['xml'] = $this->grid ();
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'open' :
				// calling form datas hezee haana yu
				// энэ эрх уруу хандаж болох эсэхийг заана.
				$data ['role'] = $this->get_user_role ();
				$this->set_table ( 'location' );
				$location = $this->get_location ();
				$location[0]='Байршлаас сонго..';
				$data ['location'] = $location;
				$data ['state'] = $this->getState ();
				$data ['action'] = $this->action ();
				
				// equipment
				$data ['industy_id'] = $this->check_section ();
				
				$this->set_table ( 'equipment' );
				$data ['equipment'] = $this->get_select ( 'name' );
				$data ['employee'] = $this->get_select ( 'fullname', 'employee' );
				$severity = $this->log_model->get_select_column ( 'value', 'value', 'value2', 'settings', array (
						'settings' => 'severity' 
				) );
				$severity['']='Сонгох..';
				$data ['severity'] = $severity;
				$level = $this->log_model->get_select_column ( 'value', 'value', 'name', 'settings', array (
						'settings' => 'sev_level' 
				) );
				$level['']= 'Сонгох..';
				$data ['ser_level'] = $level;
				return ( object ) $data;
				break;
			// log create hiihed ashiglana
			case 'add' :
				$return = $this->add ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'catch' :
				$return = $this->get ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'delete' :
				$return = $this->delete ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'ajax_update' :
				$return = $this->update ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'active' :
				$return = $this->active ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'close' :
				$return = $this->close ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			// үнэлэх
			case 'quality' :
				$return = $this->quality ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'check_file' :
				$return = $this->check_file ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'del_file' :								
				$data ['json'] = json_encode ( $this->del_file () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			//file upload
			case 'upload' :
				// $data ['page'] = 'certificate\index';				
				$data ['json'] = json_encode ( $this->upload () );
				$data ['view'] = false;
				$data ['xml'] = null;
				return ( object ) $data;
				break;
			
			case 'excel' :
				$return = $this->exportToExcel ();
				// $data['json']=json_encode($return);
				// $data['view']=false;
				// return (object)$data;
				break;
		}
	}
	// opens log and doing options
	function setOpen() {
		// call open_form
		$result = $this->log_model->get_list ();
		
		foreach ( $result as $row ) {
			// echo $row->location_id;
			echo "<br>";
			// echo $row->section_id;
		}
	}
}
