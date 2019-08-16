<?php
class eLog_Modeler {
	private $UIXVaQIb;
	public $gRFrTihi;
	public $log_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	public $aJMhhCCx;
	function set_user($gRFrTihi) {
		$this->gRFrTihi = $gRFrTihi;
		return true;
	}
	protected function szIteSQq($role) {
		$this->UIXVaQIb = $role;
		return true;
	}
	protected function oHCsbHYa() {
		return $this->UIXVaQIb;
	}
	protected function AQzFljBx() {
		$ci = &get_instance ();
		$ci->load->model ( 'my_model_old' );
		$this->log_model = new my_model_old();
	}
	protected function set_table($ubhNuzdG) {
		if ($this->log_model->get_table ())
			$this->log_model->unset_table ();
		$this->log_model->set_table ( $ubhNuzdG );
	}
	protected function bCdryorw() {
		return $this->log_model->get_select ( 'name' );
	}
	protected function get_select($name, $ubhNuzdG = null, $TGNtTtey = null) {
		if ($ubhNuzdG) {
			$this->log_model->set_table ( $ubhNuzdG );
		}
		if ($TGNtTtey)
			return $this->log_model->get_select ( $name, $TGNtTtey );
		else
			return $this->log_model->get_select ( $name );
	}
	protected function get_row($name, $bTzCfgno, $ubhNuzdG = null) {
		if ($ubhNuzdG) {
			$this->log_model->set_table ( $ubhNuzdG );
		} else
			$this->log_model->set_table ( 'log' );
		
		return $this->log_model->get_row ( $name, $bTzCfgno );
	}
	
	// Хэрэв view bval
	protected function get_all($bTzCfgno = null, $ubhNuzdG = null) {
		return $this->log_model->get_all ( $bTzCfgno, $ubhNuzdG );
	}
	protected function get_query($mUlRezNv, $ubhNuzdG = null, $TGNtTtey = null, $SfFGewWk = null, $UNWzlkWO = null, $kTrVAKHm = null, $limit = null) {
		if ($ubhNuzdG) {
			$this->log_model->set_table ( $ubhNuzdG );
		} else {
			$this->log_model->set_table ( 'log' );
		}
		return $this->log_model->get_query ( $mUlRezNv, $ubhNuzdG, $TGNtTtey, $SfFGewWk, $UNWzlkWO, $kTrVAKHm, $limit );
	}
}
class eLog_Driver extends eLog_Modeler {
	private function secs_to_str($d) {
		$periods = array (
				'ц' => 3600,
				"'" => 60,
				'с' => 1 
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
	) // doesn't contain
;
	// protected filter function
	private function ojjquhXb($filters) {
		$filters = json_decode ( $filters );
		$TGNtTtey = " where ";
		$TudCNpLq = array ();
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
				$TudCNpLq [] = $fieldName . $fieldOperation;
		}
		if (count ( $TudCNpLq ) > 0) {
			$TGNtTtey .= join ( " " . $groupOperation . " ", $TudCNpLq );
		} else {
			$TGNtTtey = "";
		}
		return $TGNtTtey;
	}
	private function ZiTFYSDu($aJMhhCCx = null, $SLXMgdTl = null, $vSbKdrtR = null, $OoUrvGAF = null, $date_option = null, $start_dt = null, $VBwJGxie = null) {
		$TGNtTtey = ' WHERE ';
		if ($aJMhhCCx)
			$TGNtTtey .= "section_id =$aJMhhCCx AND ";
		if ($SLXMgdTl) {
			$TGNtTtey .= "sector_id =$SLXMgdTl AND ";
		}
		if ($vSbKdrtR) {
			$TGNtTtey .= "equipment_id =$vSbKdrtR AND ";
		}
		if ($OoUrvGAF) {
			if ($OoUrvGAF == 'N')
				$TGNtTtey .= " closed IN ('A', 'C', 'N') AND ";
			else
				$TGNtTtey .= " closed IN ('Y') AND ";
		}
		if ($start_dt && $VBwJGxie) {
			$TGNtTtey .= " ( DATE_FORMAT($date_option, '%Y-%m-%d') BETWEEN '$start_dt' AND '$VBwJGxie') AND ";
		} elseif ($start_dt) {
			$TGNtTtey .= " DATE_FORMAT($date_option, '%Y-%m-%d') >= '$start_dt' AND ";
		} else if ($VBwJGxie) {
			$TGNtTtey .= " DATE_FORMAT($date_option, '%Y-%m-%d') <= '$VBwJGxie' AND ";
		}
		return substr ( $TGNtTtey, 0, strlen ( $TGNtTtey ) - 4 );
	}
	private function VaKuEtUM($VDecSahb, $oper, $yuHaHvIL) {
		if ($oper == 'bw' || $oper == 'bn')
			$yuHaHvIL .= '%';
		if ($oper == 'ew' || $oper == 'en')
			$yuHaHvIL = '%' . $yuHaHvIL;
		if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')
			$yuHaHvIL = '%' . $yuHaHvIL . '%';
		return " WHERE $VDecSahb {$this->ops[$oper]} '$yuHaHvIL' ";
	}
	
	// insert data
	protected function xSNpJTak() {
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
			$vSbKdrtR = $CI->input->get_post ( 'equipment_id' );
			$data ['createdby_id'] = $this->gRFrTihi;
			$data ['created_datetime'] = $CI->input->get_post ( 'created_datetime' );
			$data ['equipment_id'] = $vSbKdrtR;
			$data ['location_id'] = $CI->input->get_post ( 'location_id' );
			$data ['defect'] = $CI->input->get_post ( 'defect' );
			$data ['reason'] = $CI->input->get_post ( 'reason' );
			$data ['closed'] = 'C';
			$data ['section_id'] = $this->get_row ( 'section_id', array (
					'equipment_id' => $vSbKdrtR 
			), 'equipment' );
			$data ['sec_code'] = $this->get_row ( 'sec_code', array (
					'equipment_id' => $vSbKdrtR 
			), 'equipment' );
			$equipment = $this->get_row ( 'name', array (
					'equipment_id' => $vSbKdrtR 
			), 'equipment' );
			$this->set_table ( 'log' );
			
			if ($this->log_model->insert ( $data ) !== FALSE) {
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
	protected function RfFZenfI() {
		$CI = &get_instance ();
		// here is calling grid
		$page = $CI->input->get_post ( 'page' );
		$limit = $CI->input->get_post ( 'rows' );
		$SfFGewWk = $CI->input->get_post ( 'sidx' );
		if (! $SfFGewWk)
			$SfFGewWk = 1;
		
		$UNWzlkWO = $CI->input->get_post ( 'sord' );
		$ubhNuzdG = $this->log_model->check_table ( 'view_logs' );
		$filters = $CI->input->get_post ( 'filters' );
		$search = $CI->input->get_post ( '_search' );
		
		$TGNtTtey = ""; // if there is no search request sent by jqgrid, $where should be empty
		
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
		$aJMhhCCx = $CI->input->get_post ( 'section_id' );
		$SLXMgdTl = $CI->input->get_post ( 'sector_id' );
		$vSbKdrtR = $CI->input->get_post ( 'equipment_id' );
		$OoUrvGAF = $CI->input->get_post ( 'log' );
		$date_option = $CI->input->get_post ( 'date_option' );
		$start_dt = $CI->input->get_post ( 'start_dt' );
		$VBwJGxie = $CI->input->get_post ( 'end_dt' );
		
		if ($aJMhhCCx || $SLXMgdTl || $vSbKdrtR || $OoUrvGAF || $date_option || $start_dt || $VBwJGxie) {
			$TGNtTtey = $this->ZiTFYSDu ( $aJMhhCCx, $SLXMgdTl, $vSbKdrtR, $OoUrvGAF, $date_option, $start_dt, $VBwJGxie );
			$dBnnDhYP = $TGNtTtey;
		} else if ($this->YetLunGk ()) {
			$TGNtTtey = $this->ZiTFYSDu ( $this->YetLunGk () );
		}
		
		if (($search == 'true') && ($filters != "")) {
			$TGNtTtey = $this->ojjquhXb ( $filters );
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		
		$ViuNNOsY = $this->get_query ( " count(*) as count ", 'view_logs', $TGNtTtey );
		if ($ViuNNOsY->num_rows () > 0) {
			$PJNYlsrN = $ViuNNOsY->row_array ();
			$MEtLowIE = $PJNYlsrN ['count'];
		}
		
		if ($MEtLowIE > 0) {
			$idaSyWPr = ceil ( $MEtLowIE / $limit );
		} else {
			$idaSyWPr = 0;
		}
		
		if ($page > $idaSyWPr)
			$page = $idaSyWPr;
		$kTrVAKHm = $limit * $page - $limit;
		
		if ($kTrVAKHm < 0)
			$kTrVAKHm = 0;
		$anEvyIOz = $this->get_query ( ' * ', 'view_logs', $TGNtTtey, $SfFGewWk, $UNWzlkWO, $kTrVAKHm, $limit );
		$FruUtynH = $this->log_model->last_query ();
		
		$TTbeRqVN = "<?php xml version='1.0' encoding='utf-8'?>";
		$TTbeRqVN .= "<rows>";
		$TTbeRqVN .= "<page>" . $page . "</page>";
		$TTbeRqVN .= "<total>" . $idaSyWPr . "</total>";
		$TTbeRqVN .= "<records>" . $MEtLowIE . "</records>";
		if (isset ( $dBnnDhYP ))
			$TTbeRqVN .= "<query>" . $dBnnDhYP . "</query>";
		$TTbeRqVN .= "<query2>" . $FruUtynH . "</query2>";
		foreach ( $anEvyIOz->result () as $row ) {
			$TTbeRqVN .= "<row id='" . $row->log_id . "'>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->q_level . "]]></cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->section . "]]></cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->log_num . "]]></cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->created_datetime . "]]></cell>";
			$TTbeRqVN .= "<cell>" . $row->location . "</cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->equipment . "]]></cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->defect . "]]></cell>";
			$TTbeRqVN .= "<cell>" . $row->closed_datetime . "</cell>";
			$TTbeRqVN .= "<cell>" . $row->duration_time . "</cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->completion . "]]></cell>";
			$TTbeRqVN .= "<cell><![CDATA[" . $row->ezi . "]]></cell>";
			$TTbeRqVN .= "<cell>" . $row->closed . "</cell>";
			$TTbeRqVN .= "</row>";
		}
		$TTbeRqVN .= "</rows>";
		
		$anEvyIOz->free_result ();
		return $TTbeRqVN;
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
		$asYaxepU = array (
				// 'createdby_id' => $CI->input->post('createdby_id'),
				'created_datetime' => $CI->input->post ( 'created_datetime' ),
				'location_id' => $CI->input->post ( 'location_id' ),
				'equipment_id' => $CI->input->post ( 'equipment_id' ),
				'reason' => $CI->input->post ( 'reason' ),
				'defect' => $CI->input->post ( 'defect' ),
				'editedby_id' => $this->gRFrTihi 
		);
		
		$bnYRoTEf = $this->get_row ( 'equipment_id', array (
				'log_id' => $log_id 
		) );
		
		if ($bnYRoTEf != $CI->input->post ( 'equipment_id' )) {
			$asYaxepU ['log_num'] = $this->log_model->get_log_num ( $CI->input->post ( 'equipment_id' ) );
			$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн төхөөрөмжийг өөрчилсөн тул <strong>"' . $asYaxepU ['log_num'] . '"</strong> дугаар олгож утгуудыг амжилттай заслаа!';
		} else
			$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн утга амжилттай хадгаллаа!';
		
		if ($closed == 'Y') {
			$CI->form_validation->set_rules ( 'closed_datetime', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' ); // |valid_email|max_length[50]
			                                                                                                                                              // $CI->form_validation->set_rules('closedby_id', 'Хаасан ИТА', 'required|is_natural_no_zero'); //
			$CI->form_validation->set_rules ( 'duration_time', 'Хаасан огноо', 'required|greater_than[0]' );
			$CI->form_validation->set_rules ( 'completion', 'Гүйцэтгэл', 'required|min_length[30]' ); //
			
			$asYaxepU ['closed_datetime'] = $CI->input->post ( 'closed_datetime' );
			$asYaxepU ['closedby_id'] = $CI->input->post ( 'closedby_id' );
			$asYaxepU ['completion'] = $CI->input->post ( 'completion' );
			$asYaxepU ['duration_time'] = strtotime ( $CI->input->post ( 'closed_datetime' ) ) - strtotime ( $CI->input->post ( 'created_datetime' ) );
		}
		$CI->form_validation->set_message ( 'greater_than', ' "%s" "Нээсэн огноо"-с их байх шаардлагатай!' );
		$CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
		$CI->form_validation->set_message ( 'min_length', ' "%s" утга дор хаяж "%s" тэмдэгтийн урттай байх шаардлагатай тул дэлгэрэнгүй бичнэ үү!' );
		
		// Set the form validation rules
		if ($CI->form_validation->run () != FALSE) {
			// Passed the form validation
			$this->log_model->set_table ( 'log' );
			if ($this->log_model->update ( $asYaxepU, array (
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
	protected function jrZIYwOG() {
		$CI = &get_instance ();
		$log_id = $CI->input->post ( 'id' );
		$OoUrvGAF = $this->get_all ( array (
				'log_id' => $log_id 
		), 'view_logs' );
		$OoUrvGAF ['log_id'] = $log_id;
		$duration = $OoUrvGAF ['duration_time'];
		$OoUrvGAF ['duration_time'] = $duration;
		
		// var_dump($msg);
		if ($OoUrvGAF)
			$return = array (
					'status' => 'success',
					'message' => 'successfully',
					'log' => $OoUrvGAF 
			);
		else
			$return = array (
					'status' => 'failed',
					'message' => "This id couldn't update: " . $CI->input->post ( 'id' ) 
			);
		
		return $return;
	}
	protected function thtTDuTC() {
		$CI = &get_instance ();
		$CI->load->library ( 'session' );
		$CI->load->library ( 'form_validation' );
		
		$log_id = $CI->input->get_post ( 'log_id' );
		$closed = $CI->input->get_post ( 'closed' );
		$vSbKdrtR = $CI->input->get_post ( 'equipment_id' );
		
		$log_num = $CI->input->post ( 'log_num' );
		
		$data ['inst'] = $CI->input->post ( 'inst' );
		$data ['level'] = $CI->input->post ( 'level' );
		
		if ($log_num == null) {
			// if log_num bhgui bol ene gemteld log_num shineer ugch
			$log_num = $this->log_model->get_log_num ( $vSbKdrtR );
			$data ['log_num'] = $log_num;
			// user_id -g activatedby -d utgiig ugnu
			$ZoEUdtyI = 'success';
			$data ['activatedby_id'] = $this->gRFrTihi;
			$data ['closed'] = 'A';
			$msg = 'Гэмтэл бүртгэл нээхийг зөвшөөрч <strong>"' . $log_num . '"</strong> дугаар өглөө!';
		} elseif ($closed == "N") {
			$data ['proveby_id'] = $this->gRFrTihi;
			$ZoEUdtyI = 'success';
			$data ['closed'] = 'Y';
			$msg = $log_num . ' дугаартай гэмтлийг хаахыг хүсэлтийг зөвшөөрлөө!';
		} else {
			$ZoEUdtyI = 'failed';
			$msg = $log_num . ' дугаартай гэмтлийг хүсэлтийг аль хэдийн зөвшөөрсөн байна!';
		}
		
		$CI->form_validation->set_rules ( 'inst', 'Гэмтлийн зэрэглэл', 'required' ); //
		$CI->form_validation->set_rules ( 'level', 'Давталт', 'required' ); // required|
		
		if ($CI->form_validation->run () != FALSE) {
			$this->log_model->set_table ( 'log' );
			if ($this->log_model->update ( $data, array (
					'log_id' => $log_id 
			) ) !== FALSE) {
				$return = array (
						'status' => $ZoEUdtyI,
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
		} else {
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 
			);
		}
		return $return;
	}
	protected function GXrxpHSd() {
		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );
		$log_id = $CI->input->post ( 'log_id' );
		$radXwPXn = strtotime ( $CI->input->post ( 'created_datetime' ) );
		$vItBOrFs = strtotime ( $CI->input->post ( 'closed_datetime' ) );
		
		$CI->form_validation->set_rules ( 'closed_datetime', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		$CI->form_validation->set_rules ( 'duration_time', 'Хаасан огноо', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'completion', 'Засварласан байдал', 'required|min_length[30]' );
		
		$jYeBQUDy = $vItBOrFs - $radXwPXn;
		
		$data = array (
				'closed_datetime' => $CI->input->post ( 'closed_datetime' ),
				'completion' => $CI->input->post ( 'completion' ),
				'duration_time' => $jYeBQUDy,
				'closed' => 'N',
				'closedby_id' => $this->gRFrTihi 
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
	protected function elKhuern() {
		$CI = &get_instance ();
		$CI->load->library ( 'session' );
		$CI->load->library ( 'form_validation' );
		
		$log_id = $CI->input->post ( 'log_id' );
		
		$data ['inst'] = $CI->input->post ( 'inst' );
		$data ['level'] = $CI->input->post ( 'level' );
		$data ['qualityby_id'] = $this->gRFrTihi;
		
		$CI->form_validation->set_rules ( 'inst', 'Гэмтлийн зэрэглэл', 'required' ); //
		$CI->form_validation->set_rules ( 'level', 'Давталт', 'required' ); // required|
		
		if ($CI->form_validation->run () != FALSE) {
			if ($this->log_model->update ( $data, array (
					'log_id' => $log_id 
			) ) !== FALSE) {
				$return = array (
						'status' => 'success',
						'message' => '"' . $CI->input->post ( 'log_num' ) . '" дугаартай гэмтлийг амжилттай үнэллээ хадгаллаа.' 
				);
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
	protected function QzUBshXH() {
		$res_array = array ();
		
		$CI = &get_instance ();
		$res_array = $this->log_model->get_action ( $this->EbBtTFIO () );
		return $res_array;
	}
	function YetLunGk() {
		// $this->log_model->query($this->section_id
		$sec_code = $this->get_row ( 'code', array (
				'section_id' => $this->aJMhhCCx 
		), 'section' );
		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'SUR' || $sec_code == 'ELC') {
			return $this->aJMhhCCx;
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
						'actionby_id' => $this->gRFrTihi,
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
				$action_data ['actionby_id'] = $this->gRFrTihi;
				$action_data ['action_date'] = $dt;
				break;
			
			default :
				$action_data ['log_id'] = $log_id;
				$action_data ['action'] = 'close';
				$action_data ['datetime'] = $CI->input->post ( 'closed_datetime' );
				$action_data ['completion'] = $CI->input->post ( 'completion' );
				$action_data ['actionby_id'] = $this->gRFrTihi;
				$action_data ['action_date'] = $dt;
				break;
		}
		$this->set_table ( 'log_action' );
		$this->log_model->insert ( $action_data );
		return true;
	}
	protected function Selkjruhc() {
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
}
class eLog extends eLog_Driver {
	private $JYDpvPNd;
	private $method;
	private $TwngOTxl;
	public $url;
	public $script;
	public $data = array ();
	function __construct() {
		$this->zcljkBnz ();
		$script = " ";
	}
	private function zcljkBnz() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		$this->TwngOTxl = $CI->uri->segment ( 3 );
	}
	protected function xeSMvuIa() {
		$this->JYDpvPNd = $this->TskTFROM ();
	}
	protected function HIfciqbS() {
		$this->method = $this->NBdPPYPA ();
	}
	function TskTFROM() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		return $CI->router->class;
	}
	function NBdPPYPA() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		return $CI->router->method;
	}
	function pNaNRNCZ() {
		return $this->TwngOTxl;
	}
	
	// section_id
	function set_section($aJMhhCCx) {
		$this->aJMhhCCx = $aJMhhCCx;
		return true;
	}
	function set_role($role) {
		return $this->szIteSQq ( $role );
	}
	function EbBtTFIO() {
		return $this->oHCsbHYa ();
	}
	function VRqrbaib() {
		if (! $this->TwngOTxl)
			$this->TwngOTxl = 'open';
		return $this->TwngOTxl;
	}
	function run() {
		// echo $this->state;
		// initalizing pros
		$this->AQzFljBx ();
		$data = array ();
		$data ['view'] = true;
		
		switch ($this->VRqrbaib ()) {
			case 'grid' :
				$data ['json'] = null;
				$data ['xml'] = $this->RfFZenfI ();
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'open' :
				// calling form datas hezee haana yu
				// энэ эрх уруу хандаж болох эсэхийг заана.
				$data ['role'] = $this->oHCsbHYa ();
				$this->set_table ( 'location' );
				$data ['location'] = $this->bCdryorw ();
				$data ['state'] = $this->pNaNRNCZ ();
				$data ['action'] = $this->QzUBshXH ();
				
				// equipment
				$data ['industy_id'] = $this->YetLunGk ();
				
				$this->set_table ( 'equipment' );
				$data ['equipment'] = $this->get_select ( 'name' );
				$data ['employee'] = $this->get_select ( 'fullname', 'employee' );
				$data ['severity'] = $this->log_model->get_select_column ( 'value', 'value', 'value2', 'settings', array (
						'settings' => 'severity' 
				) );
				$data ['ser_level'] = $this->log_model->get_select_column ( 'value', 'value', 'name', 'settings', array (
						'settings' => 'sev_level' 
				) );
				
				return ( object ) $data;
				break;
			
			case 'add' :
				$return = $this->xSNpJTak ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'catch' :
				$return = $this->jrZIYwOG ();
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
				$return = $this->thtTDuTC ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'close' :
				$return = $this->GXrxpHSd ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			// үнэлэх
			case 'quality' :
				$return = $this->elKhuern ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			
			case 'check_file' :
				$return = $this->Selkjruhc ();
				$data ['json'] = json_encode ( $return );
				$data ['view'] = false;
				return ( object ) $data;
				break;
		}
	}
	// opens log and doing options
	function ZqSUopHx() {
		// call open_form
		$result = $this->log_model->get_list ();
		
		foreach ( $result as $row ) {
			echo $row->cAHIfCnA;
			echo "<br>";
		}
	}
}
