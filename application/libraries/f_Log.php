<?php
class fLog_Modeler {
	
	private $user_role;
	
	public $user_id;
	
	public $log_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	
	public $section_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	
	public $section_id;
	
	public $user_model = null;
	
	public $alert_model = null;
	
	public $ftree_model = null;

	public $device = null;

	public $equipment = null;

	public $repair_model = null;
	
	public $completion = null;

	public $employee = null;

	public $session;

	public $spare =null;
	
	public $CI =null;

	function set_user($user_id) {
		$this->user_id = $user_id;
		return true;
	}
	function set_date() {
		date_default_timezone_set ( ECNS_TIMEZONE );
		return date ( "Y-m-d H:i:s" );
	}
	protected function set_user_role($role) {
		$this->user_role = $role;
		return true;
	}
	protected function get_user_role() {
		return $this->user_role;
	}
	protected function init_table() {

		$this->CI = &get_instance();

		$ci = &get_instance ();

		$ci->load->library('session');
		
		$this->session = $ci->session;

		$ci->load->model ( 'Repair_model' );
		
		$this->repair_model = new repair_model ();
		
		$ci->load->model ( 'flog_model' );
		
		$this->log_model = new flog_model ();

		$ci->load->model ( 'user_model' );
		
		$this->user_model = new user_model ();

		$ci->load->model ( 'alert_model' );
		
		$this->alert_model = new alert_model ();

		$ci->load->model ( 'ftree_model' );
		
		$this->ftree_model = new ftree_model ();	

		$ci->load->model ( 'device_model' );
		
		$this->device = new device_model ();

		$ci->load->model ( 'equipment_model' );
		
		$this->equipment = new equipment_model ();	

		//call spare model	

		$ci->load->model ( 'employee_model' );
		
		$this->employee = new employee_model ();	

		$ci->load->model ( 'wh_spare_model' );
		
		$this->spare = new wh_spare_model ();		

		$ci->load->model ( 'completion_model' );
		
		$this->completion = new completion_model ();

		$ci->load->model ( 'section_model' );
		
		$this->section_model = new section_model ();

	}

	protected function set_table($table) {

		if ($this->log_model->get_table ())

			$this->log_model->unset_table ();

		$this->log_model->set_table ( $table );
	}

	protected function get_location() {

		return $this->log_model->get_select ( 'name', null, 'location' );

	}

	protected function get_equipment() {

		return $this->log_model->get_select ( 'equipment', null, 'equipment2' );

	}

	protected function get_select($name, $table = null, $where = null) {
		if ($table) {
			$this->log_model->set_table ( $table );
		}
		if ($where)
			return $this->log_model->get_select ( $name, $where, $table );
		else
			return $this->log_model->get_select ( $name, null, $table );
	}

	protected function get_row($name, $array, $table = null) {
    
            return $this->log_model->get_row ( $name, $array, $table );
	
	}

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

        //select section by user_section id return array
    protected function sel_section(){
        //тухайн хүний хэсгээр гэмтлийг нээх боломжтой
        if(in_array($this->section_id, array(1, 2, 3, 4))){
            $section= $this->log_model->get_select('name', array('type'=>'industry', 'section_id'=>$this->section_id), 'section');
            unset($section[0]);

        }else{
            $section= $this->log_model->get_select('name', array('type'=>'industry'), 'section');
            $section[0] = 'Сонгох..';

        }
        return $section;
    }

}
class fLog_Driver extends fLog_Modeler {
	
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

	// options here
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
                    switch ($log) {
                        case 'N': //НЭЭЛТТЭЙ ГЭМТЭЛ
                            $where .= " status IN ('A', 'C','N') AND ";
                            break;

                        case 'Y':
                            $where .= " status = 'Y' AND ";
                            break;

                        case 'Q':
                            $where .= " status = 'Q' AND ";
                            break;

                        case 'F':
                            $where .= " status = 'F' AND ";
                            break;

                        case 'G':
                            $where .= " filename IS NOT NULL AND ";
                            break;

                    }
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

	// theme generator
	protected function theme_view($view, $vars = array()) {
		$vars = (is_object ( $vars )) ? get_object_vars ( $vars ) : $vars;

		$file_exists = FALSE;

		$ext = pathinfo ( $view, PATHINFO_EXTENSION );
		$file = ($ext == '') ? $view . '.php' : $view;

		$view_file = 'assets/flog/theme/';

		if (file_exists ( $view_file . $file )) {
			$path = $view_file . $file;
			$file_exists = TRUE;
		}

		if (! $file_exists) {
			throw new Exception ( 'Unable to load the requested file: ' . $file, 16 );
		}

		extract ( $vars );

		// region buffering...
		ob_start ();

		include ($path);

		$buffer = ob_get_contents ();
		@ob_end_clean ();
		// endregion

		return $buffer;
	}

	// call grid _ form here when first time accessed
	protected function grid_form() {
		// collect nessecarry data in here and sent to view via data
		// for this instance there's don't need data here
		$data ['title'] = '';
		// view form here pass collect data from here

		$employee = $this->get_select ( 'fullname', 'employee' );
		$employee [0] = 'Сонгох..';
		$data ['employee'] = $employee;
		// location
		$reason = $this->get_select ( 'reason', 'f_reason', null );
		$reason [0] = 'Сонгох..';
		$data ['reason'] = $reason;

		$completion = $this->get_select ( 'completion_type', 'f_completion_type' );
		$completion [0] = 'Сонгох..';
		$data ['completion'] = $completion;

		$sparetype = $this->get_select ( 'sparetype', 'f_sparetype' );
		$sparetype [0] = 'Сонгох..';
		$data ['sparetype'] = $sparetype;

		$log_type = $this->get_select ( 'log_type', 'f_log_type' );
		$log_type [0] = 'Сонгох..';
		$data ['log_type'] = $log_type;
		// end collect data

		$data ['location'] = $this->get_location ();
		$data ['equipment'] = $this->get_equipment ();

                $data ['industy_id'] = $this->check_section ();
                $data ['section_id'] = $this->section_id;

		//sererity
		$s_data= $this->log_model->get_select_column ( 'value', 'value', 'value2', 'settings', array (
		'settings' => 'severity'
		) );
		unset($s_data[0]);
		$s_data[null]="Сонгох..";

		$data ['severity'] = $s_data;
		$v_data = $this->log_model->get_select_column ( 'value', 'value', 'name', 'settings', array (
		'settings' => 'sev_level'
		) );

		$v_data[0] = "Сонгох..";
		$data ['ser_level'] = $v_data;

		// employee with ITA
		$return = $this->theme_view ( 'grid.php', $data );
		return $return;
	}

	function check_log($id, $status){
		//get_row($column, $where_arr = array(), $table) {
		$status_2 = $this->log_model->get_row('status', array('id'=>$id), 'f_log');
		if($status == $status_2)
			return TRUE;
		else
			return FALSE;
	}

	// create form here
	protected function create_form() {
		// collect data here
		$CI = &get_instance ();

		$equipment [0] = 'Сонгох..';

		// print_r($employees);

        //тухайн хүний хэсгээр гэмтлийг нээх боломжтой
        if(in_array($this->section_id, array(1, 2, 3, 4, 10))){

			$data['section_id']=$this->section_id;
			
			//Nubia hesgiig end songoh bolomjtoi!!!

            $query = $this->log_model->get_query ( 'location_id, location', 'vw_loc_equip', "where section_id = $this->section_id" );
            
            foreach ( $query->result () as $row ) {
            	
                    $arr_location[$row->location_id] = $row->location;
            }

            $arr_location[0] = 'Сонгох..';

			$data['location']= $arr_location;
			
			// Nubia davhar nemeh!
			$section = array();
			
			$section= $this->section_model->dropdown_where_in(array($this->section_id, 10));
			
		    // $section= $this->log_model->get_select('name', array('type'=>'industry', 'section_id'=>$this->section_id), 'section');

			$data['section']=$section;
			

        }else{

			$location = $this->get_select ( 'name', 'location' );
			
			$location [0] = 'Сонгох..';
			
			$data ['location'] = $location;
			
			$section= $this->log_model->get_select('name', array('type'=>'industry'), 'section');
			
			$section[0] = 'Сонгох..';
			
			$data['section']=$section;
			
            $data['section_id']=0;

        }

		// reason
		$reason = $this->get_select ( 'reason', 'f_reason' );
		$reason [0] = 'Сонгох..';
		$data ['reason'] = $reason;

		$f_equipment = $this->get_select ( 'equipment', 'f_equipment' );
		$f_equipment [0] = 'Сонгох..';
		$data ['f_equipment'] = $f_equipment;
		// end collect data

		$log_type = $this->get_select ( 'log_type', 'f_log_type' );
		$log_type [0] = 'Сонгох..';
		$data ['log_type'] = $log_type;
		// end collect data

		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		$data ['created_dt'] = $CI->input->get_post ( 'created_dt' );
		$data ['category_id'] = $CI->input->get_post ( 'category_id' );
		$location_id = $CI->input->get_post ( 'location_id' );
		$data ['location_id'] = $location_id;

		$selected = $CI->input->get_post ( 'selected' );

		// equipment_selected
		if ($selected == 'y' && $equipment_id) {
            // $this->set_table('vw_loc_equip');

	        $section_id = $CI->input->get_post ( 'section_id' );

			$data['section_id']=$section_id;
			
			//device-r update hiih heregtei baina!

			$equipment = $this->device->dropdown_by('equipment_id', 'device', array('location_id' =>$location_id, 'section_id'=>$section_id));

            // $equipment = $this->log_model->get_dropdown_by ( 'equipment', 'equipment_id', array (
            //                 'location_id' => $location_id,
            //                 'section_id' => $section_id
			// ), 'vw_loc_equip' );
			
            $equipment [0] = 'Сонгох..';

           // Хэрэв тухайн төхөөрөмжийг сонговол тухайн байршил сонгоход
           //session үүссэн байвал устгана!
           if($CI->session->userdata('error_'.$location_id.$equipment_id))

           	  $CI->session->unset_userdata('error_'.$location_id.$equipment_id);

           if ($CI->session->userdata('parent_error_'.$location_id.$equipment_id))
           	
           	  $CI->session->unset_userdata('parent_error_'.$location_id.$equipment_id);

		} else
            $equipment_id = 0;

		$data ['equipment_id'] = $equipment_id;
		$data ['equipment'] = $equipment;
		$data ['ftree'] = $this->ftree ( $equipment_id );
		$return = $this->theme_view ( 'create.php', $data );
		return $return;
	}

	// close form here
	protected function close_form($id) {
		//check this closed or not!
		$status = $this->check_log($id, 'A');

        if ($id != 0 && $status) {
	    
		    $CI = &get_instance ();
		    
		    // collect data from log_id
		    $log = $this->get_all ( array ('log_id' => $id ), 'vw_flog' );
		    
		    // collect data here
		    $data ['log'] = $log;
		    // $log['parent_id']-r tuhain gemliig avah
		    
		    // $this->log_model->get_row('')
		    $parent_id = $log ['log_id'];

		    $l_query = $this->log_model->get_as_query ( "SELECT CONCAT(log_num, ', ' ,section, ', ', created_dt, ', ' , equipment , ', ', node) as log FROM vw_flog WHERE log_id = $parent_id" );
		   
			if ($l_query->num_rows () > 0) {
		        
		        foreach ( $l_query->result () as $row ) {
		           
		           $parent_log = $row->log;

		        }
		        
		        $data ['parent_log'] = $parent_log;
		    }

		    $location = $this->get_select ( 'name', 'location' );
		    $location [0] = 'Сонгох..';
		    $data ['location'] = $location;

		    $equipment [0] = 'Сонгох..';
		    $data ['equipment'] = $equipment;

		    $ftree [0] = 'Сонгох..';
		    $data ['ftree'] = $ftree;

		    static $count;
		    $data['count']=++$count;

		    $log_type = $this->get_select ( 'log_type', 'f_log_type' );
		    $log_type [0] = 'Сонгох..';
		    $data ['log_type'] = $log_type;
		    // end collect data

		    // reason
		    $reason = $this->get_select ( 'reason', 'f_reason' );
		    $reason [0] = 'Сонгох..';
		    $data ['reason'] = $reason;

		    $f_equipment = $this->get_select ( 'equipment', 'f_equipment' );
		    $f_equipment [0] = 'Сонгох..';
		    $data ['f_equipment'] = $f_equipment;
		    // end collect data

		    if($CI->session->userdata('error_'.$log['location_id'].$log['equipment_id'] ))
		        
		        $error = $CI->session->userdata('error_'.$log['location_id'].$log['equipment_id'] );
		    
		    else
		        $error = array();

		    $result = $this->log_model->get_result ( array (
		                            'log_id' => $id
					), 'vw_flog_detail' );
					
			$inputs = '';
			
			$tags = '';
			
		    $parent_error = array();

		    foreach ( $result as $row ) {
		            //$tags .= "<li>" . $row->node . "</li>";
		            $tags .= "<a class='btn-tag' id='tag_".$row->id."'>" . $row->node . "</a>";
		            $inputs .= "<input type='hidden' name='node[]' class='node' id='node_" . $row->id . "' value='" . $row->id . "'/>";
		            if(!in_array($row->id, $error)){
		       array_push($error, $row->id);
		       $CI->session->set_userdata('error_'.$log['location_id'].$log['equipment_id'], $error);
		    }
		    // create parent_error session

			    if($row->p_error==1){
			       array_push($parent_error, $row->id);
			       $CI->session->set_userdata('parent_error_'.$log['location_id'].$log['equipment_id'], $parent_error);
			    }
		    }
		    // echo $log['equipment_id'];
		    $data['equipment_id'] = $log['equipment_id'];
		     //echo "<br>";
		    $data['parent_error']=$CI->session->userdata('parent_error_'.$log['location_id'].$log['equipment_id']);

			$data ['inputs'] = $inputs;
			
		    $data ['tags'] = $tags;

		    $data ['ftree'] = $this->ftree ( $log['equipment_id'] );

		    $query = $this->log_model->get_query ( 'equipment_id, equipment', 'vw_loc_equip', array (
		                    'location_id' => $log ['location_id']
		    ) );
		    foreach ( $query->result () as $row ) {
		            $data_equip [$row->equipment_id] = $row->equipment;
		    }

		    $data['error'] = $error;
		    $data ['equipment'] = $data_equip;

			$f_completion_type = $this->get_select ( 'completion_type', 'f_completion_type' );
			
		    $f_completion_type [0] = 'Сонгох..';
		    $data ['completion_type'] = $f_completion_type;

		    //TODO: SPARE тухайн хэсгийн болон төхөөрөмжийн дугаараар хайх ёстой!
		    $data['spare'] = $this->get_spare_by($log['equipment_id']);

		    $sparetype = $this->get_select ( 'sparetype', 'f_sparetype' );
		    $sparetype [0] = 'Сонгох..';
		    $data ['sparetype'] = $sparetype;

		    // $spare = $this->log_model->get_select ('spare',array('equipment_id' =>$log['equipment_id']), 'wh_spare');
		    // $spare[0] = 'Сонгох..';
			// $data['spare'] = $spare;
			
			//ХЭРЭВ Nubia хэсэг бол бүх ажилтнуудыг оруул!
			if($section_id = 10)

				$data['employees'] = $this->employee->dropdown('employee_id', 'fullname');
					
			else
				
				$data['employees'] = $this->employee->dropdown_by('employee_id', 'fullname', 
					array('section_id'=>$log ['section_id']));

		    $return = $this->theme_view ( 'close.php', $data );
	    
	    } else {
	            $data ['message'] = ' Гэмтэл олдсонгүй! Буруу хандалт! Тусламж хэсгийг унших хэрэгтэй!';
	            $return = $this->theme_view ( 'error.php', $data );
	        }
	        return $return;
	}

	// edit form here
	protected function edit_form($id) {

            if ($id != 0) {
                    $CI = &get_instance ();

                    $result = $this->log_model->get_result ( array (
                                    'log_id' => $id
                    ), 'vw_flog' );

                    foreach ( $result as $row ) {
                        $log_num = $row->log_num;
                        $created_dt = $row->created_dt;
                        $closed_dt = $row->closed_dt;
                        $status = $row->status;
                        $reason_id = $row->reason_id;
                        $equipment_id = $row->equipment_id;
                        $section_id= $row->section_id;
                        $location_id = $row->location_id;

                        $equip_com_id = $row->equip_com_id;
                        $parent_id = $row->parent_id;
                        $log_type_id = $row->type_id;
                        $comment = $row->comment;
                        
                        //repairedby_id
                        $repairedby_id = $row->repairedby_id;

                        if ($status == 'Y' || $status == 'N'||$status == 'Q'||$status == 'F') {
                                $completion_id = $row->completion_id;
                                $closed_dt = $row->closed_dt;
                                $is_spare = $row->is_spare;
                                $closed_comment = $row->closed_comment;
                        }
                    }

                    // session get here
                    $data['error'] = $CI->session->userdata('error_'.$equipment_id);

                    $data ['id'] = $id;

                    $flag = $CI->input->get_post ( 'flag' );

                    if (! isset ( $flag )) $flag = 'init';
                            // edit bval засахаар утга ирсэн гсн үг
                            // collect data from post
                    if ($flag == 'edit') {

                        $data ['id'] = $id;
                        $data ['log_num'] = $CI->input->get_post ( 'log_num' );
                        $data ['created_dt'] = $CI->input->get_post ( 'created_dt' );
                        $data ['closed_dt'] = $CI->input->get_post ( 'closed_dt' );
                        $new_equipment_id = $CI->input->get_post ( 'equipment_id' );
                        $data ['equipment_id'] = $new_equipment_id;
                        //hereggui bolson
                        $category_id = $CI->input->get_post ( 'category_id' );
                        $section_id = $CI->input->get_post ( 'section_id' );

                        $data ['section_id'] = $section_id;
                        $location_id = $CI->input->get_post ( 'location_id' );
                        $data ['location_id'] = $location_id;
                        $reason_id = $CI->input->get_post ( 'reason_id' );
                        $status = $CI->input->get_post ( 'status' );
                        $data ['reason_id'] = $reason_id;
                        $data ['status'] = $status;
                            // get all data from query by id
                    } else {

                        $flag = 'edit';
                        $data ['log_num'] = $log_num;
                        $data ['created_dt'] = $created_dt;
                        $data ['closed_dt'] = $closed_dt;
                        $data ['equipment_id'] = $equipment_id;
                        $new_equipment_id = $equipment_id;
//                            $category_id = $row->category_id;
                        $data ['section_id'] = $section_id;
                        $data ['location_id'] = $location_id;
                        $data ['reason_id'] = $reason_id;
                        $data ['status'] = $status;
                        $data ['log_type_id'] = $log_type_id;
                          // $result->free_result();

                    }

                    // herev reason id = 6 bol gadnii bguullagaas hamaarsan
                    if ($reason_id == 6) {
                        // тухайн байршилд тухайн төрлөөр тоног төхөөрөмжийг харуулах хэрэгтэй
                        $equip_com = $this->log_model->get_dropdown_by ( 'equip_comp', 'id', 
                        	array ( 'location_id' => $location_id), 'vw_f_equip_comp' );

                        // $equip_com[0] = 'Сонгох..';
                        $data ['equip_com'] = $equip_com;
                        $data ['equip_com_id'] = $equip_com_id;
                            
                    } elseif ($reason_id == 5) { // бусад гэмтлээс хамаарсан
                        // тухайн гэмтлийг харуулах parent_id-r log-г харуулна
                        $data ['parent_log'] = $this->log_model->get_dropdown_concat ( " log_num, ', ' ,section, ', ', created_dt, ', ' , equipment , ', ', node", "log", 'log_id', "vw_flog", " log_id = $parent_id" );
                    }

                    $data ['status'] = $status;

                    $data ['flag'] = $flag;
                    // хэрэв Log-н анхний төхөөрөмжөөс өөр төхөөрөмж сонгосон байвал
                    // тухайн тоног төхөөрөмжийг сольно
                    $equipment_id = $this->log_model->get_row ( 'equipment_id', 
                    	array ( 'id' => $id ), 'f_log' );

                    if ($equipment_id == $new_equipment_id) {
                        // create node by input type hidden collects tag datas from detail
                        $result = $this->log_model->get_result ( array (
                                        'log_id' => $data ['id']
                        ), 'vw_flog_detail' );
                        $inputs = '';
                        $tags = '';
                        foreach ( $result as $row ) {
                            $tags .= "<a class='btn-tag' id='tag_".$row->id."'>" . $row->node . "</a>";
                            $inputs .= "<input type='hidden' name='node[]' class='node' id='node_" . $row->id . "' value='" . $row->id . "'/>";
                        }
                        $data ['inputs'] = $inputs;
                        $data ['tags'] = $tags;

                    } else { // өөр бол тухайн тоног төхөөрөмжийн node-Г харуулахгүй
                        $data ['inputs'] = '';
                        $data ['tags'] = '';
                    }

                    $location = $this->get_select ( 'name', 'location' );
                    $location [0] = 'Сонгох..';
                    $data ['location'] = $location;
                    //category hereggui bolson
//                    $category = $this->log_model->get_dropdown_by ( 'category', 'id', null, 'f_category' );
//                    $category [0] = 'Сонгох..';
//                    $data ['category'] = $category;
                    $data['section']=$this->sel_section();
                    // reason
                    $reason = $this->get_select ( 'reason', 'f_reason' );
                    $reason [0] = 'Сонгох..';
                    $data ['reason'] = $reason;

                    //Тухайн байршилдахь төхөөрөмжийг сонгох
                    $equipment [0] = 'Сонгох..';
                    $equipment = $this->log_model->get_dropdown_by ( 'equipment', 'equipment_id', array (
                                    'location_id' => $location_id,
                                    'section_id' => $section_id
                    ), 'vw_loc_equip' );

                    $data ['equipment'] = $equipment;
                    $data ['ftree'] = $this->ftree ( $data ['equipment_id'] );
                    $data ['comment'] = $comment;

                    $log_type = $this->get_select ( 'log_type', 'f_log_type' );
                    $log_type [0] = 'Сонгох..';
                    $data ['log_type'] = $log_type;
                    $data ['log_type_id'] = $log_type_id;

                    // if($CI->session->userdata('error_'.$equipment_id))
                    // 	$CI->session->unset_userdata('error_'.$equipment_id);

                    // хэрэв flag edit бвал даараах үйлдлийг хйинэ үүнд
                    // closed date duration automatic completion id засах бол
                    if ($status == 'N' || $status == 'Y'||$status == 'Q'||$status == 'F') {
                        $data ['completion_id'] = $completion_id;
                        $data ['closed_dt'] = $closed_dt;
                        // $data ['is_spare'] = $is_spare;
                        $data ['closed_comment'] = $closed_comment;

                        $f_completion_type = $this->get_select ( 'completion_type', 'f_completion_type' );
                        $f_completion_type [0] = 'Сонгох..';

                        $data ['completion_type'] = $f_completion_type;
                        $sparetype = $this->get_select ( 'sparetype', 'f_sparetype' );
                        $sparetype [0] = 'Сонгох..';
                        $data ['sparetype'] = $sparetype;

                        if (intval($completion_id) == 7) {
                            // хэрэв тухайн log_id-р f_log_spare table-д байвал лог байга гэж үзнэ is_spare ==Y
                            $spares = $this->log_model->get_all ( array ('log_id' => $id ), 'f_log_spare' );
                            if ($spares) {
                                
                                $data ['sparetype_id'] = $spares ['sparetype_id'];
                                
                                $data ['spare_id'] = $spares ['spare_id'];

                                $data ['spare_name'] = $spares ['spare'];
                                
                                $data ['serial'] = $spares ['serial'];
                                
                                $data ['part_number'] = $spares ['part_number'];
                                
                                $data ['qty'] = $spares ['qty'];
                            }
                                // var_dump($spares);
                        }
                    }

                    //TODO: employee called here
                    $data['repairedby_id'] = $repairedby_id;
                    
                    $data['employees'] = $this->employee->dropdown_by('employee_id', 'fullname', 
		    			array('section_id'=>$section_id));

                    // TODO: get_spare
                    $data['spare'] = $this->get_spare_by($equipment_id);

                    $return = $this->theme_view ( 'edit.php', $data );
		} else {
			$data ['message'] = 'Уучлаарай, Гэмтэл олдсонгүй! Буруу хандалт!';
			$return = $this->theme_view ( 'error.php', $data );
		}
		return $return;
	}
	// Тухайн тоног төхөөрөмжөөр Ftree-г үүсгэнэ
	protected function ftree($equipment_id = null) {

		$CI = &get_instance ();
		if ($equipment_id == null)
			$equipment_id = $CI->input->get_post ( 'equipment_id' );
		if ($equipment_id == null)
			$equipment_id = 0;

		$store_all_id = array ();
		$query = $this->log_model->get_simple ( "equipment_id = $equipment_id", 'f_tree' );
		foreach ( $query->result_array () as $row ) {
			array_push ( $store_all_id, $row ['parent'] );
		}
		$tree = '';
		if ($equipment_id !== 0&&in_array(0, $store_all_id)) {
			$tree.="<div style='margin-bottom:10px;' id='sidetreecontrol'>Үйлдлүүд: <a href='?#'>Хумих</a> | <a href='?#'>Дэлгэх</a> | <a id='reset' href='#'>Сэргээх</a></div>";

			//you can add here sub systems

			$tree .= $this->ftree_model->tree_parent ( 0, $equipment_id, $store_all_id );
			$tree .= "</div>";
		}else if($equipment_id !==0){
        	$tree.="<div style='color:red'><i><strong>Энэ төхөөрөмж дээр Алдааны мод үүсгээгүй байна! Тухайн тоног төхөөрөмж хариуцсан инженертэй холбогдоно уу!</strong></i></div>";
        }else
        	$tree.="<div style='color:blue;'><i>Тухайн байршил дахь тоног төхөөрөмжийг сонгоход алдааны мод автоматаар гарч ирнэ!</i></div>";
		return $tree;
	}

	// node_select from ftree node when select clicked
	protected function select_node() {
		$CI = & get_instance ();
		$node_id = $CI->input->get_post ( 'id' );
		// query where id = $node_id
		$node = $this->log_model->get_row ( 'node', array (
				'id' => $node_id
		), 'vw_ftree' );
		if ($node)
			$status = array (
					'status' => 'success',
					'node' => $node
			);
		else
			$status = array (
					'status' => 'failed',
					'node' => 'Алдааны модны мөчрөөс сонгогдоогүй байна!'
			);
		return $status;
	}
	// gemtel hadgalgah
	protected function add() {
		$CI = &get_instance ();
		// $CI->load->library('session');
		$CI->load->library ( 'form_validation' );

		$CI->form_validation->set_rules ( 'created_dt', 'Гэмтлийн огноо', 'required|max_length[19]|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		$CI->form_validation->set_rules ( 'section_id', 'Хэсэг', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'location_id', 'Байршил', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'node', 'Гэмтлийн мод', 'required' );
		$CI->form_validation->set_rules ( 'reason_id', 'Шалтгаан', 'required|is_natural_no_zero' );
		$is_reason = $CI->input->get_post ( 'is_reason' );

		if ($is_reason && $is_reason == 6) // бол гадны байгууллагын төхөөрөмжийг сонгосон байх шаардлагатай
			$CI->form_validation->set_rules ( 'equip_com_id', 'Гадны байгууллагын тоног төхөөрөмжөөс сонгох шаардлагатай', 'required|is_natural_no_zero' );

		if ($is_reason && $is_reason == 5) // Бусад гэмтлээс шалтгаалсан гэмтэл
			$CI->form_validation->set_rules ( 'parent_id', 'Бусад гэмтлийн утга сонгох шаардлагатай', 'required|is_natural_no_zero' );

		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
		$CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );

		if ($CI->form_validation->run () != FALSE) {
			// createdby_id хэрэгтэй
			if ($is_reason && $is_reason == 6) // Бол утгийг хадгал
				$data ['equip_com_id'] = $CI->input->get_post ( 'equip_com_id' );
			if ($is_reason && $is_reason == 5) // бусдаас хамаарсан гэмтэл Бол
				$data ['parent_id'] = $CI->input->get_post ( 'parent_id' );

			$section_id = $CI->input->get_post ( 'section_id' );

			$equipment_id = $CI->input->get_post ( 'equipment_id' );

			$data ['createdby_id'] = $this->user_id;

			$data ['section_id'] = $section_id;

			$data ['created_dt'] = $CI->input->get_post ( 'created_dt' );

			$data ['equipment_id'] = $CI->input->get_post ( 'equipment_id' );

			$location_id = $CI->input->get_post ( 'location_id' );

			$data ['location_id'] = $location_id;

			$nodes = $CI->input->get_post ( 'nodes' );

			$data ['reason_id'] = $CI->input->get_post ( 'reason_id' );

			$comment = $CI->input->get_post ('comment', TRUE);

			if(empty($comment))

			   $data ['comment'] = null;

			else $data ['comment'] = $CI->input->get_post ( 'comment', TRUE);

			// Гэмтлийн төрөл
			$data ['type_id'] = $CI->input->get_post ( 'type_id' );
			// Нээсэн гэмтэл

			$data ['status'] = 'C';

			$equipment = $this->get_row ( 'equipment', array (
					'equipment_id' => $equipment_id
			), 'equipment2' );

			$data ['category_id'] = $this->get_row ( 'category_id', array (
					'equipment_id' => $equipment_id
			), 'equipment2' );

			$data ['sector_id'] = $this->get_row ( 'sector_id', array (
					'equipment_id' => $equipment_id
			), 'equipment2' );

			if ($this->log_model->insert ( $data, "f_log" ) !== FALSE) {
				// tuhain aldaanuudiig save hiine!

				// $this->f_p_error
				$log_id = $CI->db->insert_id ();
				// herev gemtel гарсан бол бүртгэл нээх

				$idata = array ();

				$ndata = array ();

				$node = json_decode ( $nodes );

				for($i = 0; $i < sizeof ( $node ); $i ++) {

					// end tuhain gemtliin ali ni parent_erro bol update
					$parent_error = $CI->session->userdata('parent_error_'.$location_id.$equipment_id);

					if($parent_error){

					   if(in_array($node [$i]->node_id, $parent_error))

						  $ndata['p_error']=1;
						  
					   else

							 $ndata['p_error']=0;
							 
					}else //yamarch p_error uuseegui

					   	  $ndata['p_error']=-1;

					$ndata ['node_id'] = $node [$i]->node_id;

					// get node by name from get_row from node table

					$ndata['node'] = $this->log_model->get_row('node', array('id' =>$node[$i]->node_id, 'equipment_id'=>$equipment_id), 'vw_ftree');

					$ndata ['log_id'] = $log_id;

					array_push ( $idata, $ndata );
				}
				
				if($this->log_model->insert_batch ( 'f_log_dtl', $idata )){
					$section_id = $data ['section_id'];
					// UNSET USER SESSION DATAS
 					$CI->session->unset_userdata('parent_error_'.$location_id.$equipment_id);
 					$CI->session->unset_userdata('error_'.$location_id.$equipment_id);

					$return = array (
							'status' => 'success',
							'message' => '<strong>"' . $equipment . '"</strong> төхөөрөмж дээр ' . $data ['created_dt'] . ' -д шинэ гэмтэл бүртгэл нээлээ!'
					);
				}else
					$return = array (
							'status' => 'failed',
							'message' => 'Хадгалахд алдаа гарлаа'
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
	protected function update() {

		$CI = &get_instance ();

		$CI->load->library ( 'form_validation' );

		// log_id -р тухайн моделийн утгуудыг авна.
		$log_id = $CI->input->post ( 'id' );

		$CI->form_validation->set_rules ( 'created_dt', 'Нээсэн огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		$CI->form_validation->set_rules ( 'location_id', 'Байршил', 'required|is_natural_no_zero' ); //
		$CI->form_validation->set_rules ( 'equipment_id', 'Тоног төхөөрөмж', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'node', 'Гэмтлийн мод', 'required' );
		$CI->form_validation->set_rules ( 'reason_id', 'Шалтгаан', 'required|is_natural_no_zero' );
		
		$equipment_id = $CI->input->post ( 'equipment_id' );
		
		$log_data = array (
				// 'createdby_id' => $CI->input->post('createdby_id'),
				'created_dt' => $CI->input->post ( 'created_dt' ),
				'location_id' => $CI->input->post ( 'location_id' ),
				'category_id' => $CI->input->post ( 'category_id' ),
				'equipment_id' => $equipment_id,
				'reason_id' => $CI->input->post ( 'reason_id' ),
				'editedby_id' => $this->user_id,
				'edited_dt' => $this->set_date (),
				'repairedby_id' => $CI->input->post ( 'repairedby_id' )
		);
		if($CI->input->post("comment")){
			$log_data['comment'] = $CI->input->post('comment');
		}
		// GET STATUS FROM FLOG
		$status = $this->log_model->get_row ( 'status', array (
				'id' => $log_id
		), 'f_log' );
		// хаах логийг засвал
		if ($status == 'Y' || $status == 'N'||$status == 'F'||$status == 'Q') {

			$CI->form_validation->set_rules ( 'closed_dt', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' ); // |valid_email|max_length[50]		                                                                                                                                   // $CI->form_validation->set_rules('closedby_id', 'Хаасан ИТА', 'required|is_natural_no_zero');
			$CI->form_validation->set_rules ( 'duration', 'Хаасан огноо', 'greater_than[0]' );

			$CI->form_validation->set_rules ( 'completion_id', 'Засварласан байдал', 'required|is_natural_no_zero' );
		
			$log_data ['closed_dt'] = $CI->input->post ( 'closed_dt' );
			$log_data ['editedby_id'] = $this->user_id;
			$log_data ['completion_id'] = $CI->input->post ( 'completion_id' );
			$log_data ['type_id'] = $CI->input->post ( 'type_id' );
			$log_data ['duration'] = strtotime ( $CI->input->post ( 'closed_dt' ) ) - strtotime ( $CI->input->post ( 'created_dt' ) );

			// Хэрэв хаагдсан байвал
			$completion_id = $CI->input->post('completion_id');
			//$log_data ['is_spare'] = $is_spare;
			if (intval($completion_id) == 7) {
				// check sparetype_id and spare
				$CI->form_validation->set_rules ( 'sparetype_id', 'Сэлбэгийн төрөл', 'required|is_natural_no_zero' );
				$CI->form_validation->set_rules ( 'spare_id', 'Сэлбэгийн нэр', 'required|is_natural_no_zero' );

				$CI->form_validation->set_rules ( 'part_number', 'Парт дугаар', 'required' );

				$sparetype_id = $CI->input->post ( 'sparetype_id' );

				$spare = $CI->input->post ( 'spare' );
				
				$spare_id = $CI->input->post ( 'spare_id' );

				$part_number = $CI->input->post ( 'part_number' );

				$qty = $CI->input->post ( 'qty' );

				$fdata = array (
						'spare' => $spare,
						'spare_id' => $spare_id,
						'sparetype_id' => $sparetype_id,
						'log_id' => $log_id,
						'part_number' => $part_number,
						'qty' => $qty
				);

			}

			if($CI->input->post("closed_comment")){
				$log_data['closed_comment'] = $CI->input->post('closed_comment');
			}
		}
	
		$CI->form_validation->set_message ( 'regex_match', ' "%s" оруулах формат тохирохгүй байна!' );
		
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
		
		$CI->form_validation->set_message ( 'min_length', ' "%s" утга дор хаяж "%s" тэмдэгтийн урттай байх шаардлагатай тул дэлгэрэнгүй бичнэ үү!' );
		
		$CI->form_validation->set_message ( 'greater_than', ' "%s" "Нээсэн огноо"-с их байх шаардлагатай!' );

		$reason_id = $CI->input->get_post ( 'reason_id' );

		if ($reason_id == 6) // бол гадны байгууллагын төхөөрөмжийг сонгосон байх шаардлагатай
			$CI->form_validation->set_rules ( 'equip_com_id', 'Гадны байгууллагын тоног төхөөрөмжөөс сонгох шаардлагатай', 'required|is_natural_no_zero' );
		if ($reason_id == 5) // Бусад гэмтлээс шалтгаалсан гэмтэл
			$CI->form_validation->set_rules ( 'parent_id', 'Бусад гэмтлийн утга сонгох шаардлагатай', 'required|is_natural_no_zero' );

			// Set the form validation rules
		if ($CI->form_validation->run () !== FALSE) {
			if ($reason_id == 5) { // busad gemtlees hamaarsan bol
			                   // parent_id-g uptdate hiine com_equip_id = 0
				$log_data ['parent_id'] = $CI->input->get_post ( 'parent_id' );
				$log_data ['equip_com_id'] = null;
			} elseif ($reason_id == 6) {
				$log_data ['equip_com_id'] = $CI->input->get_post ( 'equip_com_id' );
				$log_data ['parent_id'] = null;
			}
			if ($status != 'C') {
				$old_equipment_id = $this->log_model->get_row ( 'equipment_id', array (
						'id' => $log_id
				), 'f_log' );
				if ($old_equipment_id != $CI->input->post ( 'equipment_id' )) {
					$log_data ['log_num'] = $this->log_model->get_flog_num ( $CI->input->post ( 'equipment_id' ) );
					$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн төхөөрөмжийг өөрчилсөн тул <strong>"' . $log_data ['log_num'] . '"</strong> дугаар олгож утгуудыг амжилттай заслаа!';
				} else
					$msg = '<strong>"' . $CI->input->post ( 'log_num' ) . '"</strong> гэмтлийн утга амжилттай хадгаллаа!';
			} else {
				$msg = ' Гэмтлийн утга амжилттай засагдлаа!';
			}

			// Passed the form validation
			if ($this->log_model->update ( $log_data, array (
					'id' => $log_id
			), 'f_log' ) == TRUE) {
				// $this->action_log($log_id, 'update');
				$nodes = $CI->input->get_post ( 'nodes' );
				// log_dtl insert
				$idata = array ();
				$ndata = array ();
				$node = json_decode ( $nodes );
				// $this->log_model->delete ( array (
				// 		'log_id' => $log_id
				// ), 'f_log_dtl' );

				for($i = 0; $i < sizeof ( $node ); $i ++) {
					$ndata ['node_id'] = $node [$i]->node_id;
					$ndata ['log_id'] = $log_id;
					$ndata['node'] = $this->log_model->get_row('node', array('id' =>$node[$i]->node_id, 'equipment_id'=>$equipment_id), 'vw_ftree');
					array_push ( $idata, $ndata );
				}
				if($this->log_model->delete(array('log_id'=>$log_id), 'f_log_dtl')){
				   $this->log_model->insert_batch ( 'f_log_dtl', $idata );
				}

				// herev tuhain gemteld id-r utga bval ustgah heregtei
				$spare_log_id = $this->log_model->get_row ( 'log_id', array ('log_id' => $log_id), 'f_log_spare' );

				if ($spare_log_id) {
					$this->log_model->delete ( array ('log_id' => $log_id), 'f_log_spare' );
				}

				// TODO:add here
				$section_id = $CI->input->get_post('section_id');
				
				$location_id = $CI->input->get_post('location_id');
				
				$equipment_id = $CI->input->get_post('equipment_id');
				
				$part_number = $CI->input->get_post('part_number');

				$device = $this->device->get_by(array('location_id'=>$location_id, 'equipment_id'=>$equipment_id));

				$spare_id = ($CI->input->get_post('spare_id')) ? $CI->input->get_post('spare_id') : 0;

				$repairedby_id = ($CI->input->get_post('repairedby_id')) ? $CI->input->get_post('repairedby_id') : 0;

				$employee = $this->employee->get($repairedby_id);

				$qty = ($CI->input->get_post('qty')) ? $CI->input->get_post('qty'): 0;

				// ene utgaar log bgaa esehiig shalgaad bval update esvel insert
				
				// TODO: хуучин утгийг устгана.
				$repair_date = date('Y-m-d', strtotime($CI->input->get_post('closed_dt')));
				
				if($repair = $this->repair_model->get_by(  array('log_id' =>$log_id) ) ){

					// Сэлбэг сольж утга байгаа бол
					if(isset($completion_id)&&$completion_id==7){
				   
					    $this->log_model->insert ( $fdata, 'f_log_spare' );

					    $reason  = 'Сэлбэг сольж';

					    $this->repair_model->update_by(

					   		array('log_id'=>$log_id),

					      	array( 
					      		'device_id' => $device->id,
					      		
					      		'log_id' =>$log_id,
					      		
					      		'spare_id'=>$spare_id, 
					      		
					      		'reason'=>$reason, 
					      		
					      		'repair'=>$CI->input->get_post('closed_comment'),
					      		
					      		'qty' => $qty, 
					      		
					      		'part_number' => $CI->input->get_post('part_number'),
					      		
					      		'repair_date' =>$repair_date , 
					      		
					      		'duration' => $log_data ['duration'],
					      		
					      		'repairedby_id' => $repairedby_id, 
					      		
					      		'repairedby' =>$employee->fullname )
					    );
				  
					// Засварласан утга байгаа бол
					}else if(isset($completion_id)&&$completion_id==3){

					   $reason = 'Засварлаж';

					   $this->repair_model->update_by(
	  						
	  						array('log_id'=>$log_id),

					      	array( 'spare_id'=>$spare_id, 
					      		   'reason'=>$reason, 
					      		   'repair'=>$CI->input->get_post('closed_comment'),
					      		   'qty' => 0, 
					      		   'part_number' => '',
					      		   'repair_date' => $CI->input->get_post('closed_dt'), 
					      		   'duration' => $log_data ['duration'],
					      		   'repairedby_id' => $repairedby_id, 
					      		   'repairedby' =>$employee->fullname ));				  
					
					// хуучин утгийг устгана учир нь дээрх 2-с өөр тохиолдолд Засварт оруулах шаардлаггүй   
					}else{

						$this->repair_model->delete_by( array('log_id'=>$log_id));
					}	

				}else{

					  $this->insert_repair($completion_id, $device->id, $log_id, $spare_id, '', $CI->input->get_post('closed_comment'), $CI->input->post ( 'part_number' ), $qty, $CI->input->post ( 'closed_dt'), $log_data ['duration'], $repairedby_id, $employee->fullname);

				}

				$return = array (
						'status' => 'success',
						'message' => $msg
				);
				

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
		
		$filterBtn = $CI->input->get_post ( 'filterBtn' );

		$search = $CI->input->get_post ( '_search' );

		$where = "";

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

		if ($filterBtn && $filters=="" ) {
			
			$where = $this->get_where_ids ( $section_id, $sector_id, $equipment_id, $log, $date_option, $start_dt, $end_dt );

			$CI->session->unset_userdata('flog_filter');

			$CI->session->set_userdata('flog_filter', $where);
			
			// $qry = $where;

		} else if ($this->check_section ()) {

			$where = $this->get_where_ids ( $this->check_section () );
		}

		if (($search == 'true') && ($filters != "")) {

			// хэрэв session-d flog-filter bgaa bol where deer nemeh heregtei
			$filter = $CI->session->userdata('flog_filter');
			
			if($filter){

				$where = $this->filter ( $filters ) ." AND ".substr($filter, 7);

			}else{

				$where = $this->filter ( $filters );
			}
			
		}

		date_default_timezone_set ( ECNS_TIMEZONE );

		$data = date ( "Y-m-d" );

		$query = $this->get_query ( " count(*) as count ", 'vw_flog', $where );

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
		$Qry = $this->get_query ( ' * ', 'vw_flog', $where, $sidx, $sord, $start, $limit );

		$last_qry = $this->log_model->last_query ();

		$CI->session->set_userdata('flog_qry', $last_qry);

		$xml = "<?php xml version='1.0' encoding='utf-8'?>";
		$xml .= "<rows>";
		$xml .= "<page>" . $page . "</page>";
		$xml .= "<total>" . $total_pages . "</total>";
		$xml .= "<records>" . $count . "</records>";
		if (isset ( $Qry ))
                    $xml .= "<limit>" . $limit . "</limit>";
		$xml .= "<start>" . $start . "</start>";
		foreach ( $Qry->result () as $row ) {
                    $xml .= "<row id='" . $row->log_id . "'>";
                    $xml .= "<cell><![CDATA[" . $row->q_level . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->log_num . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->section . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->created_dt . "]]></cell>";
                    $xml .= "<cell>" . $row->location . "</cell>";
                    $xml .= "<cell><![CDATA[" . $row->equipment . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->log_type . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->node . "]]></cell>";
                    $xml .= "<cell>" . $row->closed_dt . "</cell>";
                    $xml .= "<cell>" . $row->duration . "</cell>";
                    $xml .= "<cell>".$row->reason."</cell>";
                    $xml .= "<cell><![CDATA[" . $row->completion . "]]></cell>";
                    $xml .= "<cell></cell>";
                    $xml .= "<cell><![CDATA[" . $row->status . "]]></cell>";
                    $xml .= "<cell><![CDATA[" . $row->equipment_id . "]]></cell>";
                    $xml .= "</row>";
		}
		$xml .= "</rows>";

		$Qry->free_result ();

		return $xml;
	}
	protected function delete() {
		$CI = &get_instance ();
		
		if ($this->log_model->delete ( array ('id' => $CI->input->post ( 'id' )), 'f_log' )) {
			
		// The user was successfully removed from the table
			$return = array (
					'status' => 'success',
					'message' => 'Энэ гэмтэл амжилттай устгагдлаа!'
			);


			// хэрэв delete хийвэл тухайн ажлын дугаараа авах хэрэгтэй байна!
			$this->repair_model->delete_by(array('log_id' =>$CI->input->post('id') ) );


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
		), 'vw_flog' );

		$log ['log_id'] = $log_id;
		if ($log ['is_spare'] == 'Y') {
			$spare = $this->log_model->get_all ( array (
					'log_id' => $log_id
			), 'f_log_spare' );
			$log ['sparetype_id'] =isset($spare ['sparetype_id']) ?  $spare ['sparetype_id'] : 0;
			$log ['spare']= isset($spare ['spare'])? $spare ['spare'] : '';

		}
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
	// status created 'C'
	// actived A
	// closed N
	// finished Y
	protected function active() {
		$CI = &get_instance ();
		$CI->load->library ( 'session' );
		$CI->load->library ( 'form_validation' );

		$log_id = $CI->input->get_post ( 'log_id' );
		$closed = $CI->input->get_post ( 'status' );
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		$log_num = $CI->input->get_post ( 'log_num' );

		if ($log_num == '-') {
			// if log_num bhgui bol ene gemteld log_num shineer ugch
			//Хэрэв төхөөрөмж тухайн төхөөрөмж парент буюу parent_id бвал
			$log_num = $this->log_model->get_flog_num ( $equipment_id );
			$data ['log_num'] = $log_num;
			$status = 'success';
			$data ['activeby_id'] = $this->user_id;
			$data ['status'] = 'A';
			$msg = 'Гэмтэл бүртгэл нээхийг зөвшөөрч <strong>"' . $log_num . '"</strong> дугаар өглөө!';
		} elseif ($closed == "N") {
			$data ['proveby_id'] = $this->user_id;
			$status = 'success';
			// $data ['status'] = 'Y';
			$data ['status'] = 'Q'; // Буюу quality хийх зөвшөөрөл өгнө
			$msg = $log_num . ' дугаартай гэмтлийг хаахыг хүсэлтийг зөвшөөрлөө!';
		} else {
			$status = 'failed';
			$msg = $log_num . ' дугаартай гэмтлийг хүсэлтийг аль хэдийн зөвшөөрсөн байна!';
		}
		// $CI->form_validation->set_rules('inst', 'Гэмтлийн зэрэглэл', 'required'); //
		// $CI->form_validation->set_rules('level', 'Давталт', 'required'); // required|
		// if($CI->form_validation->run() != FALSE){
                if($status == 'failed'){
                    $msg = $log_num . ' дугаартай гэмтлийг хүсэлтийг аль хэдийн зөвшөөрсөн байна!';
                    $return = array (
                        'status' => $status,
                        'message' => $msg
                    );
                }else{
                    if ($this->log_model->update ( $data, array (
                                    'id' => $log_id
                    ), 'f_log' ) !== FALSE) {
                            $return = array (
                                            'status' => $status,
                                            'message' => $msg
                                            //'db' => $this->log_model->last_query ()
                            );
                            // print out the JSON encoded success/user details
                    } else {
                            $return = array (
                                            'status' => 'failed',
                                            'message' => 'Хадгалахад алдаа гарлаа ' . $CI->input->post ( 'id' )
                            );
                    }
                }
		// }else{
		// $return = array(
		// 'status' =>'failed',
		// 'message' =>validation_errors('', '<br>')
		// );
		// }
		return $return;
	}
	// Гэмтэл хаах фүнкц энд байна
	protected function close() {

		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );

		$log_id = $CI->input->post ( 'log_id' );

		$created_dt = strtotime ( $CI->input->post ( 'created_dt' ) );

		$closed_dt = strtotime ( $CI->input->post ( 'closed_dt' ) );

		$is_spare = $CI->input->get_post ( 'is_spare' );

		$reason_id = $CI->input->get_post ( 'reason_id' );

		$completion_id = $CI->input->get_post ( 'completion_id' );

		//parent error get location_id updated at @03/02/2018
		$location_id = $CI->input->get_post ( 'location_id' );
		
		$equipment_id = $CI->input->get_post ( 'equipment_id' );
		
		$repairedby_id = $CI->input->get_post ( 'repairedby_id' );

		$CI->form_validation->set_rules ( 'closed_dt', 'Хаасан огноо', 'required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/]' );
		
		$CI->form_validation->set_rules ( 'completion_id', 'Засварласан байдал', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'duration', 'Хаасан огноо', 'required|greater_than[0]' );

		$CI->form_validation->set_rules ( 'node', 'Гэмтлийн мод', 'required' );
		
		// $CI->form_validation->set_rules ( 'repairedby_id', 'Засварласан ИТА', 'required|is_natural_no_zero' );

		$diff_date = $closed_dt - $created_dt;
		 // Бусад гэмтлээс шалтгаалсан гэмтэл
		$CI->form_validation->set_rules ( 'reason_id', 'Шалтгаан', 'required|regex_match[/^[1.2.3.4.5.6.8.9]+$/]' );

		$CI->form_validation->set_rules ( 'closed_comment', 'Хаахад хийсэн тайлбар', 'required|min_length[30]' );

		// TODO: ХЭРЭВ СЭЛБЭГ СОЛЬЖ ЗАССАН ГЭМТЭЛ БОЛ
		$spare_id = $CI->input->post ( 'spare_id' ) ? $spare_id = $CI->input->post ( 'spare_id' ): 0;
		
		$sparetype_id = $CI->input->post ( 'sparetype_id' ) ? $spare_id = $CI->input->post ( 'sparetype_id' ): 0;
		
		if ($completion_id == 7) {
			// check sparetype_id and spare
			$CI->form_validation->set_rules ( 'sparetype_id', 'Сэлбэгийн төрөл', 'required|is_natural_no_zero' );
			$CI->form_validation->set_rules ( 'spare_id', 'Сэлбэг', 'required|is_natural_no_zero' );
			$CI->form_validation->set_rules ( 'part_number', 'Партдугаар (part number)', 'required' );
			$CI->form_validation->set_rules ( 'qty', 'Тоо ширхэг', 'required|numeric' );
			
			$sparetype_id = $CI->input->post ( 'sparetype_id' );
			
			$spare_id = $CI->input->post ( 'spare_id' );
			
			$spare = $CI->input->post ( 'spare' );
			
			$part_number = $CI->input->post ( 'part_number' );

			$fdata = array (

					'spare_id' => $spare_id,

					'spare' => $spare,

					'sparetype_id' => $sparetype_id,

					'log_id' => $log_id,

					'part_number' => $part_number,

					'qty' => $CI->input->post ( 'qty' )
			);
		}

		$closed_comment = $CI->input->get_post ('closed_comment', TRUE);

		if(empty($comment))
		   $data ['closed_comment'] = null;

		else $data ['closed_comment'] = $closed_comment;

		$data = array (
			'closed_dt' => $CI->input->post ( 'closed_dt' ),

			'completion_id' => $CI->input->post ( 'completion_id' ),

			'reason_id' => $reason_id,

			'duration' => $diff_date,

			'status' => 'N',

			'closedby_id' => $this->user_id,

			'is_spare' => $is_spare,

			'closed_comment'=>$closed_comment,

			'repairedby_id'=>$repairedby_id
		);

		// duration-g togtooh yostoi
		$CI->form_validation->set_message ( 'regex_match', '"%s" утгийг [Тодорхойлох боломжгүй] утгаар хадгалагдахгүй!' );
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгогдоогүй байна' );
		$CI->form_validation->set_message ( 'greater_than', ' "%s" "Нээсэн огноо"-с их утга байх ёстой!' );
		$CI->form_validation->set_message ( 'min_length', ' "%s" утга дор хаяж "%s" тэмдэгтийн урттай байх ёстой тул дэлгэрэнгүй бичнэ үү!' );

		$equipment_id = $CI->input->get_post('equipment_id');
		//parent_error-д тухайн equipment-r ямар нэг Node бвал

		$qty = $CI->input->get_post('qty');
		// Validation хийх ёстой!!!
		$parent_error = $CI->session->userdata('parent_error_'.$location_id.$equipment_id);
		//if parent error has! show error validation
		
		if($parent_error){
			$return = array (
							'status' => 'failed',
							'message' => 'Хаах үйлдэлд дэд мөчрийг сонгосон тул хадгалах боломжгүй! тухайн мөчрийн хамгийн доод мөчрийг сонгож хаана уу! Тусламж цэсээс харна уу!'
					);

		}else{
			if ($CI->form_validation->run () != FALSE) {
				// Passed the form validation
				if ($log_id) {

					if ($this->log_model->update ( $data, array ('id' => $log_id), 'f_log' ) == TRUE) {
						
						$equipment = $this->equipment->get($equipment_id);

						$device = $this->device->get_by(array('section_id'=>$equipment->section_id, 'equipment_id'=>$equipment->equipment_id));

						$completion = $this->completion->get($CI->input->post ( 'completion_id' ));

						$employee = $this->employee->get($repairedby_id);

						// Сэлбэг сольж

						if ($completion_id == 7) {							

						   // ene utgaar log bgaa esehiig shalgaad bval update esvel insert
						   $id = $this->log_model->insert ( $fdata, 'f_log_spare' );

						  
						}

						//TODO: repair here
						if( $completion_id==7|| $completion_id==3)
						   
						   $this->insert_repair($completion_id, $device->id, $log_id, $spare_id, $sparetype_id, $completion->completion_type, $closed_comment, $CI->input->post ( 'part_number' ), $qty, $CI->input->post ( 'closed_dt'), $diff_date, $repairedby_id, $employee->fullname);


						$return = array (
							'status' => 'success',
							'message' => $CI->input->post ( 'log_num' ) . ' гэмтлийн утга амжилттай хадгаллаа!',
							'data' => $data
						);

						$nodes = $CI->input->get_post ( 'nodes' );
						// log_dtl insert
						$idata = array ();
						$ndata = array ();
						$node = json_decode ( $nodes );
						$this->log_model->delete ( array (
								'log_id' => $log_id
						), 'f_log_dtl' );
						for($i = 0; $i < sizeof ( $node ); $i ++) {
							$ndata ['node_id'] = $node [$i]->node_id;
							$ndata['node'] = $this->log_model->get_row('node', array('id' =>$node[$i]->node_id, 'equipment_id'=>$equipment_id), 'vw_ftree');
							$ndata ['log_id'] = $log_id;
							array_push ( $idata, $ndata );
						}
						$this->log_model->insert_batch ( 'f_log_dtl', $idata );
						// herev tuhain gemteld id-r utga bval ustgah heregtei

						$return = array (
								'status' => 'success',
								'message' => $CI->input->post ( 'log_num' ) . ' гэмтлийн утга амжилттай хадгаллаа!'
						);
					}
					// set the output status, message and table row html
					// print out the JSON encoded success/user details
				} else {
					$return = array (
							'status' => 'failed',
							'message' => 'Гэмтлийн дугаар байхгүй байна.'
					);
				}
			} else {
				$return = array (
						'status' => 'failed',
						'message' => validation_errors ( '', '<br>' )
				);
			}
		}

		// Set the form validation rules

		return $return;
	}

        // чанарын
	protected function quality(){
		$CI = &get_instance ();
		$CI->load->library ( 'form_validation' );
		$log_id = $CI->input->post ( 'log_id' );
		$level = $CI->input->post ( 'level' );
		$num = $CI->input->post ( 'num' );

		$require_file = $CI->input->post ( 'require_file' );

		$CI->form_validation->set_rules ( 'level', 'Хүндрэл', 'required' );
		$CI->form_validation->set_rules ( 'num', 'Магадлал', 'required|is_natural_no_zero' );
	    $CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" талбарт нэг утга сонгох шаардлагатай!' );

	    $Q_index = $num.$level; //ersdliin index

	    // хэрэв тухайн индекс нь ҮАЗ 6.15-10-н эрсдлийн хүснэгтийн дагуу доорх байдлаар байвал тайлангийн файл нэхнэ!
	    if(in_array($Q_index, array('5A', '4A', '3A', '2A', '1A', '5B', '4B', '3B', '2B', '1B', '2C', '1C'))){

	    	$data = array (
				'level'=>$level,
				'num' =>$num,
				'status' =>'F',
				'qualityby_id' => $this->user_id
				);

		}else{

			// check files other file is attached
			if($require_file==0)
				$CI->form_validation->set_rules ( 'require_file', 'Тайлангийн файл', 'required|is_natural_no_zero' );
			
			else if($require_file==1)

				$data = array (
					'level'=>$level,
					'num' =>$num,
					'status' =>'F',
					'qualityby_id' => $this->user_id
				);

			else
				$data = array (
					'level'=>$level,
					'num' =>$num,
					'status' =>'Y',
					'qualityby_id' => $this->user_id
				);

		}

		if ($CI->form_validation->run () != FALSE) {
			// Passed the form validation
			if ($log_id) {
				if ($this->log_model->update ( $data, array (
						'id' => $log_id
				), 'f_log' ) == TRUE) {
					// insert to f_log_spare table here
					$return = array (
							'status' => 'success',
							'message' => $CI->input->post ( 'log_num' ) . ' гэмтлийн утга амжилттай хадгаллаа!',
							'data' => $data
					);
				}
				// set the output status, message and table row html
				// print out the JSON encoded success/user details
			} else {
				$return = array (
						'status' => 'failed',
						'message' => 'Гэмтлийн дугаар байхгүй байна.'
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

        //файл шаардах хийх
    protected function attach(){
        $CI = &get_instance();
        $log_id = $CI->input->post ( 'id' );

        $data = array (
            'status' =>'F',
            'file_requiredby_id' => $this->user_id
        );

        if ($this->log_model->update ( $data, array ('id' => $log_id), 'f_log') == TRUE) {
            // insert to f_log_spare table here
            $msg = array (
                            'status' => 'success',
                            'message' => 'Тухайн гэмтэлд файл хавсаргах шаардлагатайг тэмдэглэлээ!'

            );
        }else
            $msg = array (
                            'status' => 'failure',
                            'message' => ' Гэмтлийг хадгалахад алдаа гарлаа!'

            );
        return $msg;

    }
        //файл болих хийх
      protected function cancel(){
        $CI = &get_instance();
        $log_id = $CI->input->post ( 'id' );

        //check if this log has qualified then stauts =='Y'
        //eslse staus Q
        $level =$this->log_model->get_row('level', array('id'=>$log_id), 'f_log');
        if($level){
            $data = array (
                'status' =>'Y'
            );
        } else {
            $data = array (
                'status' =>'Q'
            );
        }

        if ($this->log_model->update ( $data, array ('id' => $log_id), 'f_log') == TRUE) {
            // insert to f_log_spare table here
            $msg = array (
                            'status' => 'success',
                            'message' => 'Тухайн гэмтэлд файл хавсаргахыг цуцаллаа!'

            );
        }else
            $msg = array (
                            'status' => 'failure',
                            'message' => ' Гэмтлийг хадгалахад алдаа гарлаа!'

            );
        return $msg;

    }

        //file upload here
	protected function upload() {
		// update hiihed user_id -g update hiine
		$file_name = $_FILES ['userfile'] ['name'];
		$log_id = $_POST ['log_id'];

		$file_name = str_replace(' ', '_', $file_name);

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
						'status' => 'Y',
						'file_uploadedby' =>$this->session->userdata('fullname')
				), array (
						'id' => $log_id
				), 'f_log') ==TRUE) {
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

	//ges spare_call function 

	private function get_spare_by($equipment_id){

	   $equipment = $this->equipment->get($equipment_id);

       if(isset($equipment->sp_id))
       
          $spare = $this->spare->dropdown_by('id', 'spare', array('equipment_id'=> $equipment->sp_id));

       else       
          $spare = $this->spare->dropdown_by('id', 'spare', array('section_id'=> $equipment->section_id, 'equipment_id' =>$equipment->sp_id));

       return $spare;

	}


	// check the update repair files

	function insert_repair($completion_id, $device_id, $log_id=null, $spare_id=null,  $reason=null, $repair=null, $part_number=null, $qty=null, $datetime=null, $duration=null, $repairedby_id = null, $repairedby=null){

		if($completion_id==7){

			

			$repair_data = array('device_id' =>$device_id,
								'log_id' => $log_id,
								'repair_date' =>date('Y-m-d', strtotime($datetime)),
								'reason' =>'Сэлбэг сольж',
								'repair' =>$repair,
								'duration' =>$duration
							);

			$id = $this->repair_model->insert($repair_data, TRUE);
		
			// inserted_id 
			$inserted_id = $this->CI->db->insert_id();
						
			// TODO: insert_repair
			$spare = $this->spare_model->get($spare_id);

			$data = array(

				'repair_id' => $inserted_id,
			
				'spare_id' => $spare_id,
			
				'spare' => $this->CI->input->get_post('spare'),

				'qty' => $qty,
				
				'partnumber' => $partnumber			
			);

			$this->CI->db->insert('repair_spare', $data); 
			
			$this->CI->db->insert('repair_employee', array(
				'repair_id'=>$inserted_id, 
				'employee_id'=>$repairedby_id,
				'employee' => $repairedby,
				'created_at' => date('Y-m-d H:i:s')
			)); 
			

		}else if ($completion_id==3){
								   
		   $repair_data = array('device_id' =>$device_id,
		   					     'log_id' => $log_id,
								'repair_date' =>date('Y-m-d', strtotime($datetime)),
								'reason' =>'Засварласан',
								'repair' => $repair,
								'duration' =>$duration

							);

			$id = $this->repair_model->insert($repair_data, TRUE);
			
			$this->CI->db->insert('repair_employee', array(
				'repair_id'=> $this->CI->db->insert_id(),
				'employee_id'=>$repairedby_id,
				'employee' => $repairedby,
				'created_at' => date('Y-m-d H:i:s')
			)); 
	    }

	    return $id;

	}

	
	function check_default($post_string){
		var_dump('its called!');
	   return $post_string == '0' ? FALSE : TRUE;
	}

	// action loaded here
	protected function action() {
		$res_array = array ();
		$CI = &get_instance ();
		$res_array = $this->log_model->get_action ( $this->get_role () );

		return $res_array;
	}
	protected function check_id($id) {
		$id = $this->log_model->get_row ( 'id', array (
				'id' => $id
		), 'f_log' );
		if ($id)
			return $id;
		else
			return 0;
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

	// log_action
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

	// input filter
	function input_filter() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();

		$id = $CI->input->get_post ( 'id' );
		// category_id changed section_id
		$section_id = $CI->input->get_post ( 'cat_id' );
		$field = $CI->input->get_post ( 'field' );
		$table = $CI->input->get_post ( 'table' );
		$data ['0'] = 'Тоног төхөөрөмж сонго..';
                //тухайн төхөөрөмжөөр хэсгийг харуулах

                //тухайн section-р төхөөрөмжийг харуулах

                //тухайн байршлаар шүүх
		

		if ($id != 0) {

			// Updated after loc_equiment changed to device

			$device = $this->device->dropdown_by('equipment_id', 'device', array('location_id' =>$id, 'section_id'=>$section_id));
			
			// if ($table == 'vw_loc_equip') {
			// 	$query = $this->log_model->get_query ( 'equipment_id, equipment', 'vw_loc_equip', "where location_id=$id and section_id= $section_id" );
			// 	foreach ( $query->result () as $row ) {
			// 		$data [$row->equipment_id] = $row->equipment;
			// 	}
			
			// } else {
			// 	 $data[0]='Энэ байршилд ямарч төхөөрөмж алга';
			// }
		}
		return $device;
	}

	
    function front_filter(){
        $CI =&get_instance ();
        $json_arr = array ();
        $data = array ();

        $id = $CI->input->get_post ( 'id' );
        $field = $CI->input->get_post ( 'field' );
        $table = $CI->input->get_post ( 'table' );

        if($table=='equipment'){
           $CI->db->select ( $table . '_id, equipment' );
           $CI->db->where('is_group IS NULL', null, false);
        }
        else
           $CI->db->select ( $table . '_id, name' );


        if ($id != 0) {
                switch ($field) {
                        case 'section_id' :
                                $CI->db->where ( array (
                                                "section_id" => $id
                                ) );
                                break;

                        case 'sector_id' :
                                $CI->db->where ( array (
                                                "sector_id" => $id
                                ) );
                                break;

                        default :
                                $CI->db->where ( array (
                                                "equipment_id" => $id
                                ) );
                                break;
                }
        }

        if ($table == 'sector') {
                $query = $CI->db->get ( $table );
                foreach ( $query->result () as $row ) {
                        $data [$row->sector_id] = $row->name;
                }
        } else {
                $query = $CI->db->get ('equipment2' );
                foreach ( $query->result () as $row ) {
                        $data [$row->equipment_id] = $row->equipment;
                }
        }
        if ($query->num_rows () > 0) {

				$data [0] = 'Бүгд';
				
        } else if ($query->num_rows () == 0) {

                $data [0] = 'Байхгүй';
        }
		
		// echo $CI->db->last_query();

		$json_arr = $data;
		
		$query->free_result ();
		
        return $json_arr;
    }

	// gadnii baiguullagaas shuult hiihed ene utgaar shuune
	function filter_fequip() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		$data ['0'] = 'Сонгох..';

		$location_id = $CI->input->get_post ( 'location_id' );
		if ($location_id) {
			$query = $this->log_model->get_query ( 'id, equipment_id, equip_comp', 'vw_f_equip_comp', "where location_id=$location_id" );
			if ($query->num_rows > 0)
				foreach ( $query->result () as $row ) {
					$data [$row->id] = $row->equip_comp;
				}
			else
				$data ['0'] = 'Сонгосон байршилд гадны байгууллагын тоног төхөөрөмж алга!';
		}
		return $data;

		// category_id
	}

	// reason 5 busad gemtlees hamaarsan
	function filter_reason() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		$location_id = $CI->input->get_post ( 'location_id' );
		$action = $CI->input->get_post ( 'action' );
		$log_id = $CI->input->get_post ( 'id' );
		$new_date = $CI->input->get_post ( 'created_dt' );
		// where location_id and not current log_id and between 72 hours before log
		// $date = date("Y-m-d H:m:s", strtotime('-24 hours', time()));
		// $start_dt =
		// $end_dt =
		// created_dt BETWEEN DATE_SUB('2016-08-18 12:00:00' ,INTERVAL 3 DAY) AND '2016-08-18 12:00:00'
		if ($location_id && $new_date) {
			if ($action == 'create')
				$where = " location_id = $location_id and created_dt between DATE_SUB('$new_date' ,INTERVAL 3 DAY) and DATE_ADD('$new_date', INTERVAL 3 DAY)";
			elseif ($action == 'edit'||$action == 'close') {
				$where = " log_id!=$log_id and location_id = $location_id and created_dt between DATE_SUB('$new_date' ,INTERVAL 3 DAY) and DATE_ADD('$new_date', INTERVAL 3 DAY)";
			}

			$data = $this->log_model->get_dropdown_concat ( " log_num, ', ' ,section, ', ', created_dt, ', ' , equipment , ', ', node", "log", 'log_id', "vw_flog", $where );

			//echo $this->log_model->last_query();
		}
		return $data;
	}

	// form section filter from form
	function filter_section() {

		$json_arr = array ();

		$data = array ();

		$CI = &get_instance ();

		// $sec_id = $CI->input->get_post ( 'section_id' );
		$section_id = $CI->input->get_post ( 'section_id' );

		// echo "sec_id".$section_id;

		// $query = $this->log_model->get_query ( 'location_id, location', 'vw_loc_equip', "where section_id= $sec_id", "location", "asc" );

		$query = $this->device->get_location(array('section_id'=>$section_id));

		// echo $CI->db->last_query();

		$data[0]='Нэг утгийг сонго';

		foreach ( $query->result() as $row ) {

			// var_dump($row);

			$data [$row->location_id] = $row->location;

		}
		// var_dump($data);

		return $data;
	}

	// form section filter from form
	function filter_get() {

		$json_arr = array ();
		$data = array ();

		$CI = &get_instance ();

		$sec_id = $CI->input->get_post ( 'section_id' );

		// $data ['0'] = 'Сонгох...';

		$data = $this->employee->dropdown_by('employee_id', 'fullname', array('section_id'=>$sec_id));

		return $data;
	}

	// tag_node болгоход ашиглагдах ajax
	// node-g avch
	protected function get_node() {
		$CI = &get_instance ();
		$json_data = array ();
		$log_id = $CI->input->get_post ( 'id' );
		// tuhain log_id -r tuhani node_diig avch array bolgono
		$query = $this->log_model->get_query ( '*', 'vw_flog_detail', "where log_id=$log_id" );
		foreach ( $query->result () as $row ) {
			array_push ( $json_data, $row->node );
		}
		return $json_data;
	}

        //файл болих хийх
    protected function dashboard(){

        $CI = &get_instance();
        $CI->load->helper ( 'url' );
        //check if it has parameter
        $type_id = $CI->uri->segment ( 4 );
        //echo $type_id;
        if(isset($type_id)){
           $result =$this->log_model->get_query_as_sql("call proc_statistic($type_id)");

            $dataset = array();
            $crow = array();

            foreach ($result as $row){
                $crow['date'] = $row->date;
                $crow['c_value'] = $row->c_value;
                $crow['c_duration'] = $row->c_duration;
                $crow['n_value'] = $row->n_value;
                $crow['n_duration'] = $row->n_duration;
                $crow['s_value'] = $row->s_value;
                $crow['s_duration'] = $row->s_duration;
                $crow['e_value'] = $row->e_value;
                $crow['e_duration'] = $row->e_duration;
                array_push ( $dataset, $crow );
            }
              return $dataset;
        }

    }

    //file delete
    	// delete file here
	protected function del_file() {
		$CI = &get_instance ();
		$id = $CI->input->get_post ( 'id' );
		$file_name = $CI->input->get_post ( 'file_name' );
		// check file exists
		 // echo $_SERVER['DOCUMENT_ROOT'];
		 // echo "<br>";
		 // echo $this->upload_path;
		 // echo "<br>";
		 // echo base_url();
		 // echo "<br>";
		 // echo  $_SERVER ['DOCUMENT_ROOT'] . '/ecns/'.$this->upload_path . $file_name ;


		if (file_exists ( $_SERVER ['DOCUMENT_ROOT'] .'/ecns/'.$this->upload_path . $file_name )) {
			// file is unlicked?
			if (unlink ( $_SERVER ['DOCUMENT_ROOT'] . '/ecns/'.$this->upload_path . $file_name )) {

				$this->log_model->update ( array (
						'filename' => null,
						'status'=>'F'
				), array (
						'id' => $id
				), 'f_log' );
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
			// update the row
			$this->log_model->update ( array (
					'filename' => null,
					'status' =>'F'
			), array (
					'id' => $id
			), 'f_log' );

			$json = array (
					'status' => 'failed',
					'message' => '[' . $file_name . '] сервер дээр байршаагүй байна!'
			)
			;
		}

		return $json;
	}

	protected function get_spare(){
		
		$CI = &get_instance ();
		$id = $CI->input->get_post ( 'spare_id' );

		$spare = $this->spare->get($id);

		return $spare;
	}
}
class f_Log extends fLog_Driver {
	private $state;
	public $data = array ();
	function __construct() {
		$this->setStateFromUrl ();
	}
	private function setStateFromUrl() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		$this->state = $CI->uri->segment ( 3 );
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
	function get_id() {
		$CI = &get_instance ();
		$CI->load->helper ( 'url' );
		return $CI->uri->segment ( 4 );
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
				// энэ эрх уруу хандаж болох эсэхийг заана.
				$data ['role'] = $this->get_user_role ();
				$data ['action'] = $this->action ();
				// call index page form
				$data ['form'] = $this->grid_form ();
				return ( object ) $data;
				break;

			// log neehed duudna
			case 'create_form' :
				$data ['form'] = $this->create_form ();
				$data ['role'] = $this->get_user_role ();
				$data ['action'] = $this->action ();

				return ( object ) $data;
				break;

			// log haah form duudna
			case 'close_form' :
				$data ['form'] = $this->close_form ( $this->check_id ( $this->get_id () ) );
				$data ['role'] = $this->get_user_role ();
				$data ['action'] = $this->action ();
				return ( object ) $data;
				break;

			case 'close' :
				$data ['json'] = json_encode ($this->close() );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			// edit hiihed
			case 'edit' :
				$id = $this->check_id ( $this->get_id () );
				$data ['form'] = $this->edit_form ( $id );
				//certificate here
				$data ['role'] = $this->get_user_role ();
				$data ['action'] = $this->action ();
				return ( object ) $data;
				break;

			// reason 5 busad gemtlees hamaarsan gemtel
			case 'jx_reason' :
				$data ['json'] = json_encode ( $this->filter_reason () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			// form f_equipment filter when change gadnii/busad bguullagaas
			case 'jx_fequip' :
				$data ['json'] = json_encode ( $this->filter_fequip () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
			// form input filter ajax
			case 'input_jx' :
				$data ['json'] = json_encode ( $this->input_filter () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			// create form filter from ajax post
			case 'filter_sec' :
				$data ['json'] = json_encode ( $this->filter_section () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'filter_get' :
				$data ['json'] = json_encode ( $this->filter_get () );

				$data ['view'] = false;

				return ( object ) $data;

				break;

			case 'ftree' :
				$data ['json'] = json_encode ( $this->ftree () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'node_select' :
				$data ['json'] = json_encode ( $this->select_node () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'jx_node' :
				$json = $this->get_node ();
				$data ['json'] = json_encode ( $json );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			// add create here
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

			case 'update' :
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

			// үнэлэх
			case 'quality' :
				$return = $this->quality ();
				$data ['json'] = json_encode ( $return );
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

            case 'jx_front_filter':
                $data ['json'] = json_encode ( $this->front_filter () );
                $data ['view'] = false;
                return ( object ) $data;
                break;

            case '_attach':
                $data ['json'] = json_encode ( $this->attach () );
                $data ['view'] = false;
                return ( object ) $data;
                break;

            case '_cancel':
                $data ['json'] = json_encode ( $this->cancel() );
                $data ['view'] = false;
                return ( object ) $data;
                break;

            case 'dashboard':
                $data ['json'] = json_encode ( $this->dashboard() );
                $data ['view'] = false;
                return ( object ) $data;
                break;
            
            case 'del_file' :
				$data ['json'] = json_encode ( $this->del_file () );
				$data ['view'] = false;
				return ( object ) $data;
				break;

			case 'get_spare' :
				$data ['json'] = json_encode ( $this->get_spare () );
				$data ['view'] = false;
				return ( object ) $data;
				break;
		}
	}
}
