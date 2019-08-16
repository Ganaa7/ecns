<?php
class Warehouse_Modeler {
	private $user_role;
	public $user_id;
	public $log_model = null; // хэрэв энд модел зарласан бол тухайн table-р нь зарлавал ямар вэ?
	public $section_id;
	public $user_model = null;
	public $alert_model = null;
	public $ftree_model = null;
	public $wm_model = null;
	
	public $wh_spare = null;

	protected $input;  

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

		$ci = &get_instance ();

		$ci->load->model ( 'spare_model' );
		$this->log_model = new spare_model ();

		$ci->load->model ( 'user_model' );
		$this->user_model = new user_model ();
		$ci->load->model ( 'alert_model' );
		$this->alert_model = new alert_model ();
		$ci->load->model ( 'ftree_model' );
		$this->ftree_model = new ftree_model ();
		$ci->load->model ( 'wm_model' );
		$this->wm_model = new wm_model ();

		$this->input = $ci->input;

		$ci->load->model ( 'wh_spare_model' );

        $this->wh_spare = new wh_spare_model();     


	}
	protected function set_table($table) {
            if ($this->log_model->get_table()) {
               $this->log_model->unset_table();
            }
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
class Warehouse_Driver extends Warehouse_Modeler {
	public $objdata;
	
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
				$where .= " closed IN ('A', 'C','N') AND ";
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
	
	// theme generator
	protected function theme_view($view, $vars = array()) {
		$vars = (is_object ( $vars )) ? get_object_vars ( $vars ) : $vars;
		
		$file_exists = FALSE;
		
		$ext = pathinfo ( $view, PATHINFO_EXTENSION );
		$file = ($ext == '') ? $view . '.php' : $view;
		
		$view_file = 'assets/warehouse/theme/';
		
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

		
		$data ['location'] = $this->get_location ();
		$data ['equipment'] = $this->get_equipment ();
		
		// employee with ITA
		$return = $this->theme_view ( 'grid.php', $data );
		return $return;
	}
	
	// Орлого авах форм энд бичнэ get_income_form
	protected function get_income_form($data = null) {
		// collect data here
		$CI = &get_instance ();
		//equipment-n spare-d zoriulsan id baih yostoi!!!
		 //get_dropdown_by($column, $key, $where = null, $table) 
		$equipment =$this->log_model->get_dropdown_by('equipment','sp_id', array('sp_id'=>TRUE), 'equipment2');

		//$equipment = $this->get_select ( 'equipment', 'equipment2' );
		$equipment [null] = 'Сонгох..';
		$data ['equipment'] = $equipment;
		
		$section = $this->get_select ( 'name', 'section', array (
				'type' => 'industry' 
		) );

		unset ( $section [0] );
		
		$section [8] = 'Хангамжийн хэсэг';

		$section [null] = 'Сонгох';
		
		$data ['section'] = $section;
		
		$sector = $this->get_select ( 'name', 'sector' );
		$sector [0] = 'Нэг тасаг сонго!..';
		$data ['sector'] = $sector;
		
		$spare = $this->get_select ( 'spare', 'wh_spare' );
		$spare [0] = 'Сонгох..';			
		$data ['spare'] = $spare;

		$spare_type = $this->get_select ( 'sparetype', 'wh_sparetype' );
		$spare_type [0] = 'Сонгох..';			
		$data ['spare_type'] = $spare_type;
		
		$supplier = $this->get_select ( 'supplier', 'wm_supplier' );
		$supplier [null] = 'Сонгох..';
		$supplier [-1] = 'Шинэ нийлүүлэгч  нэмэх';
		unset ( $supplier [0] );
		$data ['supplier'] = $supplier;

		$measure = $this->get_select ( 'measure', 'wm_measures' );
		$measure [0] = 'Сонгох..';
		$data ['measure'] = $measure;

		$manufacture = $this->get_select ( 'manufacture', 'wm_manufacture' );
		$manufacture [0] = 'Сонгох..';
		$data ['manufacture'] = $manufacture;

		$country = $this->get_select ( 'country', 'country' );
		$country [0] = 'Сонгох..';
		$data ['country'] = $country;
		
		$return = $this->theme_view ( 'get_income.php', $data );
		return $return;
	}

        //Орлого засах хэсэг тухайн invoice_id-гаар тухайн хэсгийн бүх төхөөрөмжүүдээс сонгоно.
        //Submit: хийхэд тухайн төхөөрмжүүдийн invoice_id дээр Update Хийнэ.
	protected function income_edit_form($data = null) {
	    //check validation if this function is accesable for user!!
		// collect data here
      $CI = &get_instance ();
		//equipment-n spare-d zoriulsan id baih yostoi!!!
		 //get_dropdown_by($column, $key, $where = null, $table)
         $id = $CI->uri->segment ( 4 );  
         $data['invoice_id']=$id;
        
         $equipment =$this->log_model->get_dropdown_by('equipment','sp_id', array('sp_id'=>TRUE), 'equipment2');

         //$equipment = $this->get_select ( 'equipment', 'equipment2' );
         $equipment [null] = 'Сонгох..';
         $data ['equipment'] = $equipment;

         $section = $this->get_select ( 'name', 'section', array (
                         'type' => 'industry'
         ) );
         unset ( $section [0] );
         $section [8] = 'Хангамжийн хэсэг';
         $section [null] = 'Сонгох';
         $data ['section'] = $section;

         $sector = $this->get_select ( 'name', 'sector' );
         $sector [0] = 'Нэг тасаг сонго!..';
         $data ['sector'] = $sector;

         $spare = $this->get_select ( 'spare', 'wh_spare' );
         $spare [0] = 'Сонгох..';
         $data ['spare'] = $spare;

         $spare_type = $this->get_select ( 'sparetype', 'wh_sparetype' );
         $spare_type [0] = 'Сонгох..';
         $data ['spare_type'] = $spare_type;

         $supplier = $this->get_select ( 'supplier', 'wm_supplier' );
         $supplier [null] = 'Сонгох..';
         $supplier [-1] = 'Шинэ нийлүүлэгч  нэмэх';
         unset ( $supplier [0] );
         $data ['supplier'] = $supplier;

         $measure = $this->get_select ( 'measure', 'wm_measures' );
         $measure [0] = 'Сонгох..';
         $data ['measure'] = $measure;

         $manufacture = $this->get_select ( 'manufacture', 'wm_manufacture' );
         $manufacture [0] = 'Сонгох..';
         $data ['manufacture'] = $manufacture;

         $country = $this->get_select ( 'country', 'country' );
         $country [0] = 'Сонгох..';
         $data ['country'] = $country;

         //$this->log_model->get('wh_vw_income');
         $result = $this->log_model->get_result(array('invoice_id'=>$id),'_wh_vw_income');
         $data['result'] = $result;
        
          // тухайн id-гаар Зарлага гаргасан эсэхийг шалгана
         //хэрэв зарлага гарсан тохиолдолд зарлага огноо, зарлага тоог шалгана
         $data['is_expense']=false;
         $data['income_date']= $this->log_model->get_row('income_date', array('invoice_id'=>$id), 'wh_income');
         $data['supplier_id']= $this->log_model->get_row('supplier_id', array('invoice_id'=>$id), 'wh_income');
         $data['income_no']= $this->log_model->get_row('income_no', array('invoice_id'=>$id), 'wh_income');
                     
         $sql= $this->log_model->last_query();
         $qry = $this->log_model->get_as_query($sql);
         $data['count'] = $qry->num_rows();
         
         $return = $this->theme_view ( 'edit_income.php', $data );
         return $return;
	}
        
        //Зарлага засах хэсэг
	protected function expense_edit_form($data = null) {
	    $CI = &get_instance ();
            //equipment-n spare-d zoriulsan id baih yostoi!
            //get_dropdown_by($column, $key, $where = null, $table) 
            $id = $CI->uri->segment ( 4 );  
            $data['invoice_id']=$id;            
            $equipment =$this->log_model->get_dropdown_by('equipment','sp_id', array('sp_id'=>TRUE), 'equipment2');

            $employee = $this->get_select ( 'fullname', 'employee' );		
            $employee [0] = 'Сонго.';
            $data ['employee'] = $employee;

            $equipment [null] = 'Тасгийг сонго.';
            $data ['equipment'] = $equipment;

            $section = $this->get_select ( 'name', 'section', array (
                            'type' => 'industry' 
            ) );
            unset ( $section [0] );
            $section [8] = 'Хангамжийн хэсэг';
            $section [null] = 'Сонгох';
            $data ['section'] = $section;

            $sector = $this->get_select ( 'name', 'sector' );
            $sector [0] = 'Нэг тасаг сонго!..';
            $data ['sectors'] = $sector;

            //$spare = $this->get_select ( 'spare', 'wh_spare' );
            $spare [0] = 'Төхөөрөмжийг сонго.';			
            $data ['spare'] = $spare;

            $spare_type = $this->get_select ( 'sparetype', 'wh_sparetype' );
            $spare_type [0] = 'Сонгох..';			
            $data ['spare_type'] = $spare_type;

            $measure = $this->get_select ( 'measure', 'wm_measures' );
            $measure [0] = 'Сонгох..';
            $data ['measure'] = $measure;

            $manufacture = $this->get_select ( 'manufacture', 'wm_manufacture' );
            $manufacture [0] = 'Сонгох..';
            $data ['manufacture'] = $manufacture;
                        
            $result = $this->log_model->get_result(array('invoice_id'=>$id),'vw_expense_dtl');
            $data['result'] = $result;
            //count all records
            $sql= $this->log_model->last_query();
            $qry = $this->log_model->get_as_query($sql);
            $data['count'] = $qry->num_rows();
            
            $data['expense_date']= $this->log_model->get_row('expense_date', array('invoice_id'=>$id), 'wh_expense');
            $data['recievedby_id']= $this->log_model->get_row('recievedby_id', array('invoice_id'=>$id), 'wh_expense');
            $data['expense_no']= $this->log_model->get_row('expense_no', array('invoice_id'=>$id), 'wh_expense');
            $data['section_id']= $this->log_model->get_row('section_id', array('invoice_id'=>$id), 'wh_expense');
            $data['sector_id']= $this->log_model->get_row('sector_id', array('invoice_id'=>$id), 'wh_expense');
            $data['intend']= $this->log_model->get_row('intend', array('invoice_id'=>$id), 'wh_expense');
            
            $result2 = $this->log_model->get_result(array('invoice_id'=>$id),'wh_invoice_dtl');
            $data['res_dtl'] = $result2;
            
            return $this->theme_view ( 'edit_expense.php', $data );
	}

	// Зарлага авах форм энд бичнэ get_income_form
	protected function get_expense_form($data = null) {
		// collect data here
		$CI = &get_instance ();
		//equipment-n spare-d zoriulsan id baih yostoi!!!
		 //get_dropdown_by($column, $key, $where = null, $table) 
		$equipment =$this->log_model->get_dropdown_by('equipment','sp_id', array('sp_id'=>TRUE), 'equipment2');

		$employee = $this->get_select ( 'fullname', 'employee' );		
		$employee [0] = 'Сонго.';
		$data ['employee'] = $employee;


		$equipment [null] = 'Тасгийг сонго.';
		$data ['equipment'] = $equipment;
		
		$section = $this->get_select ( 'name', 'section', array (
				'type' => 'industry' 
		) );
		unset ( $section [0] );
		$section [8] = 'Хангамжийн хэсэг';
		$section [null] = 'Сонгох';
		$data ['section'] = $section;
		
		$sector = $this->get_select ( 'name', 'sector' );
		$sector [0] = 'Нэг тасаг сонго!..';
		$data ['sector'] = $sector;
		
		//$spare = $this->get_select ( 'spare', 'wh_spare' );
		$spare [0] = 'Төхөөрөмжийг сонго.';			
		$data ['spare'] = $spare;

		$spare_type = $this->get_select ( 'sparetype', 'wh_sparetype' );
		$spare_type [0] = 'Сонгох..';			
		$data ['spare_type'] = $spare_type;
		
		$measure = $this->get_select ( 'measure', 'wm_measures' );
		$measure [0] = 'Сонгох..';
		$data ['measure'] = $measure;

		$manufacture = $this->get_select ( 'manufacture', 'wm_manufacture' );
		$manufacture [0] = 'Сонгох..';
		$data ['manufacture'] = $manufacture;
				
		$return = $this->theme_view ( 'get_expense.php', $data );
		return $return;
	}

	protected function expense_grid_form(){
		$CI =& get_instance();
		$data['title'] = 'Зарлага жагсаалт';
		return $this->theme_view('expense.php', $data);
	}

	protected function jx_income_valid() {
		$CI = &get_instance ();
		// $CI->load->library('session');
		$CI->load->library ( 'form_validation' );
		
		$return = array (
				'status' => 'failed',
				'message' => validation_errors ( '', '<br>' ) 
		);
		
		return $return;
	}
	
	// Орлогийн жагсаалт форм
	protected function income_grid_form() {
		// $data['action']=$this->action();
		$CI = & get_instance ();
		$data ['title'] = 'Орлого жагсаалт';
		return $this->theme_view ( 'income.php', $data );
	}
	
	// Шинээр баркод үүсгэнэ
	private function gen_barcode($spares) {
		$barcode = array ();		
		$equipments = array ();
		foreach ( $spares as $spare ) {
			$equipments [] = $spare ['equipment_id'];
		}
		array_multisort ( $equipments, SORT_ASC, $spares );

		$flag = 0;
		
		// бүх сэлбэгүүдэд barcode Үүгсэх хэсэг
		for($i = 0; $i < sizeof ( $spares ); $i++) {
			// ehnii equipment-n barcode uusgeh`
			//echo "i=".$i."<br>";
			if ($flag == 0) {
				$max_id = intval ( $this->wm_model->get_max_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'] ) );
				//echo $max_id;
				// barcode bgaa esehiig shalgaad bhgui bol hiine
				$spares [$i] ['barcode'] = $this->set_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'], ++$max_id);	
				$flag=1;			
				// echo "barcode".$spares[$i]['barcode'];
				// echo "_qty: ".$spares[$i]['qty'];
			} else {				
				$j=$i-1;

				if ($spares [$i] ['equipment_id'] == $spares [$j] ['equipment_id']) {
					// ижил бол before barcode + odoonii barcode-g nemne
					$before_barcode = intval ( substr ( $spares [$j] ['barcode'], 8, 6 ) );
					// echo "bef_bar".$before_barcode;
					// хэрэв өмнөх type_id  = 2 буюу дагалдах бвал нь өөр байвал дараах type-id-г өөрчлөх хэрэгтэй болно.
					   // $spares [$i] ['barcode'] = $this->set_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'], $before_barcode + intval ( $spares [$l] ['qty'] ) + 1 );						
					 if($spares[$j]['type_id']==2){
					 	// get before barcode _id + 1
					 	$spares [$i] ['barcode'] = $this->set_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'], $before_barcode + 1 );	
					 }else{
					    // umnuh spare-n barcode-g avaad odoogiin qty -g nemne
					   $spares [$i] ['barcode'] = $this->set_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'], $before_barcode + intval ( $spares [$j] ['qty'] ) );	
					 }
					
				} else { // эсрэг бол
					$max_id = intval ( $this->wm_model->get_max_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'] ) );
					$spares [$i] ['barcode'] = $this->set_barcode ( $spares [$i] ['sector_id'], $spares [$i] ['equipment_id'], ++$max_id );
					// echo "<br>barcode".$spares[$i]['barcode'];
				}
				// echo "_qty: ".$spares[$i]['qty'];
			}
		}
		// resort by sort_id
		$orders = array ();
		foreach ( $spares as $spare ) {
			$orders [] = $spare ['order'];
		}

		array_multisort ( $orders, SORT_ASC, $spares );

		// var_dump($spares);
		// if(sizeof($spares)>1){			 
		// 	array_combine(range(1, count($spares)), $spares);
		// 	return $new_spare;
		// }else
		return array_combine(range(1, count($spares)), $spares);
		
	}
	
	// return barcode()
	private function set_barcode($sector_id, $equipment_id, $id) {
		// gen barcode here
		$head = "0" . $sector_id;
		// echo "barcode".$spare_id[$i];
		$mid = sprintf ( '%03d', $equipment_id );
		// $bar_end = sprintf('%06d', $spare_id[$i]);
		$barcode = $head . '-' . $mid . '-' . sprintf ( '%06d', $id );
		return $barcode;
	}
	
	// агуулахд тавихад энэ форм дуудагдана
	protected function income_dtl_form() {
		$CI = &get_instance ();
		$data ['title'] = 'Орлого авах';
		$count = $CI->input->get_post ( 'count' );
		
		$CI->load->library ( 'form_validation' );
		// validation here
		$CI->form_validation->set_rules ( 'spare_id', 'Сэлбэг', 'required' );
		$CI->form_validation->set_rules ( 'income_date', 'Орлогийн огноо', 'required' );
		$CI->form_validation->set_rules ( 'income_no', 'Орлогийн дугаар', 'required' ); // |is_unique[wh_income.income_no]
		$CI->form_validation->set_rules ( 'supplier_id', 'Нийлүүлэгч', 'required|is_natural_no_zero' );
		
		$data ['income_no'] = $CI->input->get_post ( 'income_no' );
		$data ['income_date'] = $CI->input->get_post ( 'income_date' );
		$data ['supplier_id'] = $CI->input->get_post ( 'supplier_id' );
		$match = $this->get_row ( 'income_no', array (
				'income_date' => $data ['income_date'],
				'income_no' => $data ['income_no'] 
		), 'wh_income' );
		if ($match) {
			$flag = FALSE;
			$data ['flag'] = '[' . $data ['income_no'] . '] дугаартай орлого [' . $data ['income_date'] . '] огноо дээр аль хэдийн орлого авсан тул дахиж авах боломжгүй!';
		} else {
			$flag = TRUE;
		}
		
		if (($CI->form_validation->run () == TRUE) && $flag == TRUE) {
			// bar code generator
			// $data=$this->wm_model->get_income_dtl();
			$spare = $CI->input->get_post ( 'spare' );
			// тухайн $spare section, sector, equipment -ын утга 0 байвал утгийг авч өгөх хэрэгтэй
			$spare_id = $CI->input->get_post ( 'spare_id' );
			// тухайн сэлбэгийн тоног төхөөрөмжөөр бар код үүсгэх
			// $data['barcode']= $this->get_barcode($count, $spare_id);
			$qty = $CI->input->get_post ( 'qty' );
			$data ['qty'] = $qty;
			$data ['spare'] = $this->gen_barcode ( $spare );
			$data ['income_no'] = $CI->input->get_post ( 'income_no' );
			$data ['income_date'] = $CI->input->get_post ( 'income_date' );
			$data ['pallet'] = $this->wm_model->getPallet ();
			$data ['warehouse'] = $this->wm_model->getWarehouse ();
			// $data['spare_id']=$CI->input->get_post('spare_id');
			$data ['amt'] = $CI->input->get_post ( 'amt' );
			$data ['type_id'] = $CI->input->get_post ( 'type_id' );
			$data ['supplier_id'] = $CI->input->get_post ( 'supplier_id' );
			// return $this->theme_view('income_dtl.php', $data);
			return $this->theme_view ( 'income_dtl_new.php', $data );
		}else {
		 	return $this->get_income_form ( $data );
		 }
	}

	public function _check_match($income_no, $income_date) {
		// $match=$this->get_row('income_no', array('income_date' =>$income_date, 'income_no'=>$income_no), 'wh_income');
		$match = 0;
		if ($match == 0) {
			$this->form_validation->set_message ( 'check_match', ' %s аль хэдийн энэ огноогоор орлого авсан тул дахиж авах боломжгүй!' );
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// Жагсаалт
	protected function _grid() {
		$CI = &get_instance ();
		$spare_id = $CI->input->get ( 'spare_id', true );
		$page = $CI->input->get ( 'page', TRUE );
		$limit = $CI->input->get ( 'rows', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );
		$sord = $CI->input->get ( 'sord', TRUE );
        $barcode= $CI->input->get ( 'barcode', TRUE );
                //print_r($barcode);
		$filters = $CI->input->get_post ( 'filters' );
               
		//Хэрэв filter hiisen bol
		if($filters){
			$filters = (json_decode($filters));
			$rules = $filters->rules;		
			$i=0;
			foreach ( $rules as $rule ) {
				
				if($rule->field!=="years_old"){
					
					if($rule->data=='0'){
					
						unset($filters->rules[$i]);
					
					}
				}
				
				$i++;
			}
	
			$filters=json_encode($filters);
		// print_r($filters);
		}	
                		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		if ($_GET ['_search'] == 'true') {
                    $where = $this->filter ( $filters );
                    // list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
                    // $where=$this->get_where_ids($searchField,$searchOper,$searchString);
		}
		// else if(isset($spare_id)&&$spare_id){
		// $where=" WHERE spare_ids like '%$spare_id%'";
		// }
                
                //barcode-g filter hiih 
//                if($barcode){
//                    $query = $this->get_query("call general_ledger($barcode);");
//                    //echo $barcode;
//                }
                 		
		if (!$sidx) $sidx = 1;
		
		if ($_GET ['_search'] == 'true')
		   $query = $this->get_query ( "COUNT(*) AS count", 'vw_general_ledger', $where );
		else
		   $query = $this->get_query ( "COUNT(*) AS count", 'vw_general_ledger' );
		
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$count = $row ['count'];
		}
		// calculate the total pages for the query
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		} // if for some reasons the requested page is greater than the total // set the requested page to total page
		if ($page > $total_pages)
                    $page = $total_pages;
			// calculate the starting position of the rows
		$start = $limit * $page - $limit;
		// if for some reasons start position is negative set it to 0
		// typical case is that the user type 0 for the requested page
		if ($start < 0)
			$start = 0;
			// the actual query for the grid data
		if ($_GET ['_search'] == 'true') {			
			$SQL = "SELECT A.* FROM vw_general_ledger A $where ORDER BY $sidx $sord LIMIT $start , $limit";
			$SQL_udata = "SELECT sum(total) as sum from vw_general_ledger $where";
		} 		// $SQL="SELECT A.*, spare_ids FROM _wh_vw_income A
		// LEFT JOIN wm_view_invDtlspare B ON A.invoice_id = B.invoice_id $where
		// ORDER BY $sidx $sord LIMIT $start , $limit";
		else {
			$SQL = "SELECT * FROM vw_general_ledger ORDER BY $sidx $sord LIMIT $start , $limit";
			$SQL_udata = "SELECT sum(total) as sum from vw_general_ledger";
		}
		$Qry = $CI->db->query ( $SQL );


		
		$u_result = $CI->db->query ( $SQL_udata )->result ();
		
		foreach ( $u_result as $row ) {
			$udata ['spare_id'] = '';			
			$udata ['spare'] = '';
			$udata ['equipment'] = '';
			$udata ['sparetype'] = '';
			$udata ['years_old'] = '';
			$udata ['qty'] = '';
			$udata ['amt'] = 'Нийт дүн:';
			$udata ['total'] = $row->sum;
			$udata ['short_code'] = '';
			$udata ['section'] = '';
			$udata ['sector'] = '';
		}
		
		// get user data total by where by all total income
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['where'] = $where;		
		$json ['sql'] = $this->wm_model->last_query();		
		$json ['filters'] = $filters;		
		
		/*$xml = "<?php xml version='1.0' encoding='utf-8'?>";
		$xml .= "<rows>";
		$xml .= "<page>" . $page . "</page>";
		$xml .= "<total>" . $total_pages . "</total>";
		$xml .= "<records>" . $count . "</records>";
		*/
		// be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {	

			$crow ['id'] = $row->spare_id;			
			$crow ['spare_id'] = $row->spare_id;			
			$crow ['spare'] = $row->spare;
			$crow ['equipment_id'] = $row->equipment;
			$crow ['sparetype'] = $row->sparetype;
			$crow ['years_old'] = $row->years_old;
			$crow ['amt'] = $row->amt;
			$crow ['qty'] = $row->qty;
			$crow ['total'] = $row->total;
			$crow ['short_code'] = $row->short_code;
			$crow ['section'] = $row->section;
			$crow ['sector'] = $row->sector;
			array_push ( $row_bind, $crow );
		}
		$json['sql']= $this->wm_model->last_query();
		
		$Qry->free_result ();
		$json ['rows'] = $row_bind;
		$json ['userdata'] = $udata;
		
		
		return json_encode ( $json );
	}
	
	// Зөвхөн орлогын засвар
	protected function income_grid() {
		$CI = &get_instance ();
		$spare_id = $CI->input->get ( 'spare_id', true );
		$page = $CI->input->get ( 'page', TRUE );
		$limit = $CI->input->get ( 'rows', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );
		$sord = $CI->input->get ( 'sord', TRUE );
		$filters = $CI->input->get_post ( 'filters' );
                
                		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		if ($_GET ['_search'] == 'true') {
			$where = $this->filter ( $filters );
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
			// $where=$this->get_where_ids($searchField,$searchOper,$searchString);
		}
		// else if(isset($spare_id)&&$spare_id){
		// $where=" WHERE spare_ids like '%$spare_id%'";
		// }
		
		if (! $sidx)
			$sidx = 1;
		
		if ($_GET ['_search'] == 'true')
			$query = $this->get_query ( "COUNT(*) AS count", 'vw_wh_income_header', $where );
		else
			$query = $this->get_query ( "COUNT(*) AS count", 'vw_wh_income_header' );
		
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$count = $row ['count'];
		}
		// calculate the total pages for the query
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		} // if for some reasons the requested page is greater than the total // set the requested page to total page
		if ($page > $total_pages)
			$page = $total_pages;
			// calculate the starting position of the rows
		$start = $limit * $page - $limit;
		// if for some reasons start position is negative set it to 0
		// typical case is that the user type 0 for the requested page
		if ($start < 0)
			$start = 0;
			// the actual query for the grid data
		if ($_GET ['_search'] == 'true') {
			
			$SQL = "SELECT * FROM vw_wh_income_header $where ORDER BY $sidx $sord LIMIT $start , $limit";
			// $SQL_udata = "SELECT sum(total) as sum from _wh_vw_income $where";
		} 		// $SQL="SELECT A.*, spare_ids FROM _wh_vw_income A
		// LEFT JOIN wm_view_invDtlspare B ON A.invoice_id = B.invoice_id $where
		// ORDER BY $sidx $sord LIMIT $start , $limit";
		else {
			$SQL = "SELECT * FROM vw_wh_income_header ORDER BY $sidx $sord LIMIT $start , $limit";
			// $SQL_udata = "SELECT sum(total) as sum from _wh_vw_income";
		}
		$Qry = $CI->db->query ( $SQL );
			
		// get user data total by where by all total income
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['where'] = $where;
		
		$xml = "<?php xml version='1.0' encoding='utf-8'?>";
		$xml .= "<rows>";
		$xml .= "<page>" . $page . "</page>";
		$xml .= "<total>" . $total_pages . "</total>";
		$xml .= "<records>" . $count . "</records>";
		// be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {			
			$crow ['id'] = $row->invoice_id;
			$crow ['invoice_id'] = $row->invoice_id;
			$crow ['income_no'] = $row->income_no;
			$crow ['income_date'] = $row->income_date;			
			$crow ['spare'] = $row->spare;
			$crow ['t_qty'] = $row->t_qty;
			$crow ['t_amt'] = $row->t_amt;			
			$crow ['storeman'] = $row->storeman;			
			$crow ['supplier'] = $row->supplier;
			array_push ( $row_bind, $crow );
		}
		
		$Qry->free_result ();
		$json ['rows'] = $row_bind;		
		
		return json_encode ( $json );
	}

	protected function income_sub_dtl() {
		$CI = &get_instance ();
		$invoice_id = $CI->input->get ( 'id' );
		$sord = $CI->input->get ( 'sord', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );
		$result = $CI->db->query ( "CALL proc_incomeDtl($invoice_id, '$sidx','$sord');" )->result ();
		// set the header information
		// header("Content-type: application/json;charset=utf-8");
		// $json =array();
		// be sure to put text data in CDATA
		
		$json_dtl = '';
		$json_dtl .= "<?xml version='1.0' encoding='utf-8'?>";
		$json_dtl .=  "<rows>";
		// be sure to put text data in CDATA
		$count = 1;
		foreach ( $result as $row ) {
			$json_dtl .= "<row>";
			$json_dtl .= "<cell>" . $row->id . "</cell>";
			$json_dtl .= "<cell><![CDATA[" . $row->spare . "]]></cell>";
			$json_dtl .= "<cell>" . $row->equipment . "</cell>";
			$json_dtl .= "<cell>" . $row->sector . "</cell>";
			$json_dtl .= "<cell>" . $row->section . "</cell>";
			$json_dtl .= "<cell>" . $row->qty . "</cell>";
			$json_dtl .= "<cell>" . $row->measure . "</cell>";
			$json_dtl .= "<cell>" . $row->amt . "</cell>";
			$json_dtl .= "<cell>" . $row->qty*$row->amt . "</cell>";			
			$json_dtl .= "</row>";
		}
		$json_dtl .= "</rows>";
		$count ++;
		return $json_dtl;
	}

	// expense grid
	protected function expense_grid() {
		$CI = &get_instance ();
		$spare_id = $CI->input->get ( 'spare_id', true );
		$page = $CI->input->get ( 'page', TRUE );
		$limit = $CI->input->get ( 'rows', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );
		$sord = $CI->input->get ( 'sord', TRUE );
		$filters = $CI->input->get_post ( 'filters' );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		
		if ($_GET ['_search'] == 'true') {
			$where = $this->filter ( $filters );			
		}
		
		if (! $sidx)
			$sidx = 1;
		
		if ($_GET ['_search'] == 'true')
			$query = $this->get_query ( "COUNT(*) AS count", 'vw_expense_header', $where );
		else
			$query = $this->get_query ( "COUNT(*) AS count", 'vw_expense_header' );
		
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$count = $row ['count'];
		}
		// calculate the total pages for the query
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		} // if for some reasons the requested page is greater than the total // set the requested page to total page
		if ($page > $total_pages)
			$page = $total_pages;
			// calculate the starting position of the rows
		$start = $limit * $page - $limit;
		// if for some reasons start position is negative set it to 0
		// typical case is that the user type 0 for the requested page
		if ($start < 0) {
                    $start = 0;
                }
        // the actual query for the grid data
		if ($_GET ['_search'] === 'true') {			
		   $SQL = "SELECT * FROM vw_expense_header $where ORDER BY $sidx $sord LIMIT $start , $limit";			
		} 
		else {
			$SQL = "SELECT * FROM vw_expense_header ORDER BY $sidx $sord LIMIT $start , $limit";
			// $SQL_udata = "SELECT sum(total) as sum from _wh_vw_income";
		}
		$Qry = $CI->db->query ( $SQL );
			
		// get user data total by where by all total income
		
		$json = array ();
		$row_bind = array ();
		$crow = array ();
		
		$json ['page'] = $page;
		$json ['total'] = $total_pages;
		$json ['records'] = $count;
		$json ['where'] = $where;
		
		$xml = "<?php xml version='1.0' encoding='utf-8'?>";
		$xml .= "<rows>";
		$xml .= "<page>" . $page . "</page>";
		$xml .= "<total>" . $total_pages . "</total>";
		$xml .= "<records>" . $count . "</records>";
		// be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {			
			$crow ['id'] = $row->invoice_id;
			$crow ['invoice_id'] = $row->invoice_id;
			$crow ['expense_no'] = $row->expense_no;
			$crow ['expense_date'] = $row->expense_date;			
			$crow ['spare'] = $row->spare;
			$crow ['t_qty'] = $row->t_qty;
			$crow ['t_amt'] = $row->t_amt;			
			$crow ['section'] = $row->section;
			$crow ['sector'] = $row->sector;			
			$crow ['recievedby'] = $row->recievedby;			
			array_push ( $row_bind, $crow );
		}
		
		$Qry->free_result ();
		$json ['rows'] = $row_bind;		
		
		return json_encode ( $json );
	}

	//function expense_dtl 
	protected function expense_dtl(){
		$CI = &get_instance ();
		$invoice_id = $CI->input->get ( 'id' );
		$result = $CI->db->query("select `a`.`spare_id` AS `spare_id`, b.section, b.sector, b.equipment, 
        		`b`.`spare` AS `spare`, `b`.`measure` AS `measure`,  ABS(sum(`a`.`aqty`)) AS `qty`, 
		ABS(a.amt) as amt from (`wh_invoice_dtl` `a`
			left join `_wh_vw_spare` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
			WHERE a.invoice_id = $invoice_id and aqty =-1  group by `a`.`invoice_id` , `a`.`spare_id`;")->result();
		$json_dtl = '';
		$json_dtl .= "<?xml version='1.0' encoding='utf-8'?>";
		$json_dtl .=  "<rows>";
		// be sure to put text data in CDATA
		$count = 1;
		foreach ( $result as $row ) {
			$json_dtl .= "<row>";
			$json_dtl .= "<cell>" . $row->spare_id . "</cell>";
			$json_dtl .= "<cell><![CDATA[" . $row->spare . "]]></cell>";
			$json_dtl .= "<cell>" . $row->equipment . "</cell>";
			$json_dtl .= "<cell>" . $row->sector . "</cell>";
			$json_dtl .= "<cell>" . $row->section . "</cell>";
			$json_dtl .= "<cell>" . $row->qty . "</cell>";
			$json_dtl .= "<cell>" . $row->measure . "</cell>";
			$json_dtl .= "<cell>" . $row->amt . "</cell>";
			$json_dtl .= "<cell>" . $row->qty*$row->amt . "</cell>";			
			$json_dtl .= "</row>";
		}
		$json_dtl .= "</rows>";
		$count ++;
		return $json_dtl;
	}

	//delete expense
	protected function expense_delete() {
                
		$CI = &get_instance ();
		$id = $CI->input->post ( 'id' );
                $msg = $this->check_id($id);
                if($msg['status']='success'){
                    $CI->db->trans_begin();
                    $CI->db->query("DELETE FROM wh_expense where invoice_id = $id");
                    $CI->db->query("DELETE FROM wh_invoice where id = $id");		
                    $CI->db->query("DELETE FROM wh_invoice_dtl where invoice_id = $id");				
                    $CI->db->trans_complete();

                    if ($CI->db->trans_status() === FALSE) {
                       $CI->db->trans_rollback();
                       $return = array (
                                    'status' => 'failed',
                                    'message' => $CI->input->post ( 'id' ) . ' устгахад алдаа гарлаа!' 
                       );
                    }else{
                       // $this->action_log($CI->input->post('id'),'delete');
                       $CI->db->trans_commit();
                       // The user was successfully removed from the table
                       $return = array (
                                    'status' => 'success',
                                    'message' => 'Зарлага амжилттай устгагдлаа!' 
                       );
                    }                    
                }else{
                     $return = array (
                                    'status' => 'failure',
                                    'message' => $msg['message']
                       );
                }
		
		return $return;
	}

	// орлогод авах хэсэг
	protected function add_income() {
		$CI = &get_instance ();
		$CI->load->helper ( 'PHPExcel' );		
		if ($invoice_id = $this->wm_model->add_income_dtl()) {		
		 	// orlogod avsnii daraa barcode xls file uusgej ugnu!
		 	// excel file-g shuud tataj avah bolno!
		 	$objPHPExcel = new PHPExcel ();
		 	$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( 'USEr' )->setTitle ( "ECNS Shiftlog report" )->setSubject ( "Shiftlog Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Barcode file" );
			
	 		$qry = $CI->db->query ( "SELECT A.barcode, B.section, B.sector, B.equipment, B.spare, C.pallet FROM 	wh_invoice_dtl A 	
	             left join _wh_vw_spare B ON A.spare_id = B.spare_id	
	             left join wm_pallet C ON A.pallet_id = C.pallet_id
	              where A.invoice_id = $invoice_id" )->result ();
			
	 		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'Code' )->setCellValue ( 'B1', 'pallet' )->setCellValue ( 'C1', 'section' )->setCellValue ( 'D1', 'sector' )->setCellValue ( 'E1', 'equipment' )->setCellValue ( 'F1', 'spare' );
			
	 		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
	 		$i = 2;
			foreach ( $qry as $cols ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->barcode );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->pallet );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->section );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->sector );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->equipment );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->spare );
				$i ++;
			}
			
			$objPHPExcel->setActiveSheetIndex ( 0 );
			// Save Excel 2007 file
			// echo date('H:i:s') , " Write to Excel2007 format" , PHP_EOL;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( str_replace ( '.php', '.xlsx', __FILE__ ) );
			$filename = 'D:\xampp\htdocs\ecns\application\libraries\Warehouse.xlsx';
			$destination = 'D:\xampp\htdocs\ecns\download\Warehouse.xlsx';
				
			if (file_exists ( $filename )) {
			// move to the direcotry download
			   $result = copy ( $filename, $destination );
			   if ($result) {
				   unlink ( $filename );
				   // echo "amjilttai huullaa";
				   // $this->income
				   $data ['query'] = $qry;
				   $data ['msg'] = "Амжилттай хадгаллаа! <br> XLS файлыг амжилттай үүсгэлээ!";
				   $data ['link'] = "<a class='button' href='" . base_url () . "download/Warehouse.xlsx'>Баркодын файл хадгалах</a>";
				// redirect(base_url().'download/Warehouse.xlsx');
		 		} else
		 			$data ['msg'] = "erroro to move to the folder";
		 		// then download
		 	} else {
		 		$data ['msg'] = "sorry file not created";
		 	}
		 } else {
		 	$data ['query'] = null;
		 	$data ['link'] ="";
		 	$data ['msg'] = "Алдаа: No:WH_1- Агуулахад өгөгдлийг хадгалж чадсангүй!";
		 }
		 //echo "invoice_id: ".$invoice_id;
		 return $this->theme_view ( 'add_result.php', $data );
	}

        //Орлого устгахад хэрэв зарлага тухайн сэлбэгээр гарсан тохиолдоллд устгахыг хориглоно1
	protected function delete() {
		$CI = &get_instance ();
		$id = $CI->input->post ( 'id' );
                $msg = $this->check_id($id);
                //устгах боломжтой 
                if($msg['status']=='success'){
                        // -г устгах wh_income 
                    // wh_invoice
                    // wh_invoice_dtl
                    $CI->db->trans_begin();
                    $CI->db->query("DELETE FROM wh_income where invoice_id = $id");
                    $CI->db->query("DELETE FROM wh_invoice where id = $id");		
                    $CI->db->query("DELETE FROM wh_invoice_dtl where invoice_id = $id");				
                    $CI->db->trans_complete();

                    if ($CI->db->trans_status() === FALSE) {
                       $CI->db->trans_rollback();
                       $return = array (
                                    'status' => 'failed',
                                    'message' => $CI->input->post ( 'id' ) . ' устгахад алдаа гарлаа!' 
                       );
                    }else{
                       // $this->action_log($CI->input->post('id'),'delete');
                       $CI->db->trans_commit();
                       // The user was successfully removed from the table
                       $return = array (
                                    'status' => 'success',
                                    'message' => 'Орлогийг амжилттай устгагдлаа!' 
                       );
                    }
                }else                        
                    $return = array (
                        'status' => 'failure',
                        'message' => $msg['message']
                    );

		return $return;
	}

	//add spare from ajax 
	protected function add_spare(){		
	   $CI = &get_instance ();

	   $section_id = $CI->input->post ( 'section_id', TRUE );
	   $sector_id = $CI->input->post ( 'sector_id', TRUE );
	   $equipment_id = $CI->input->post ( 'equipment_id', TRUE );
	   $manufacture_id = $CI->input->post ( 'manufacture_id', TRUE );
	   $measure_id = $CI->input->post ( 'measure_id', TRUE );
	   $spare_type_id = $CI->input->post ( 'spare_type_id', TRUE );	   
	   $spare = $CI->input->post ( 'spare', TRUE );
	   $part_number = $CI->input->post ( 'part_number', TRUE );

	   $CI->load->library ( 'form_validation' );
		// validation here
		$CI->form_validation->set_rules ( 'section_id', 'Хэсэг', 'required' );
		$CI->form_validation->set_rules ( 'sector_id', 'Тасаг', 'required' );
		$CI->form_validation->set_rules ( 'equipment_id', 'Төхөөрөмж', 'required' );
		$CI->form_validation->set_rules ( 'manufacture_id', 'Үйлдвэрлэгч', 'required' );
		$CI->form_validation->set_rules ( 'measure_id', 'Хэмжих нэгж', 'required' );
		$CI->form_validation->set_rules ( 'part_number', 'Парт №', 'required' );
		$CI->form_validation->set_rules ( 'spare_type_id', 'Сэлбэгийн төрөл', 'required|is_natural_no_zero' );		
		$CI->form_validation->set_rules ( 'spare', 'Сэлбэг', 'required' );
		
		if ($CI->form_validation->run () == TRUE) {
		   // bar code generator
		   $data['type_id']=$spare_type_id;
		   $data['section_id']=$section_id;
		   $data['sector_id']=$sector_id;
		   $data['equipment_id']=$equipment_id;
		   $data['measure_id']=$measure_id;
		   $data['manufacture_id']=$manufacture_id;
		   $data['spare']=$spare;
		   $data['part_number']=$part_number;

		   //insert table
		   if($id=$this->wm_model->insert($data, 'wh_spare')){
		   	  $return = array (
			  		'status' => 'success',
			  		'message' => "Амжилттай хадгаллаа!",
			  		'spare_id'=>$id,
			  		'spare'=>$spare
			  );
		   }else
		   	$return = array (
			  		'status' => 'failed',
			  		'message' => "This id couldn't insert: " . $CI->input->post ( 'spare' ) 
			  );
		
		}else	
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 

			);

			return $return;

	}	

	//add supplier from ajax request
	protected function add_supplier(){		
	   $CI = &get_instance ();

	   $supplier = $CI->input->post ( 'supplier', TRUE );
	   $country_id = $CI->input->post ( 'country_id', TRUE );
	   $address = $CI->input->post ( 'address', TRUE );
	   $phone = $CI->input->post ( 'phone', TRUE );
	   
	   $CI->load->library ( 'form_validation' );
		// validation here
		$CI->form_validation->set_rules ( 'supplier', 'Нийлүүлэгч', 'required' );
		$CI->form_validation->set_rules ( 'country_id', 'Харьяа улс', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'address', 'Хаяг', 'required' );
		$CI->form_validation->set_rules ( 'phone', 'Утас', 'required' );
		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );

		if ($CI->form_validation->run () == TRUE) {
		   // bar code generator
		   $data['supplier']=$supplier;
		   $data['country_id']=$country_id;
		   $data['address']=$address;
		   $data['phone']=$phone;
		   
		   //insert table
	    if($id=$this->wm_model->insert($data, 'wm_supplier')){
	   	  $return = array (
		  		'status' => 'success',
		  		'message' => "Амжилттай хадгаллаа!",
		  		'supplier_id'=>$id,
		  		'supplier'=>$supplier
		  );
	    }else
	   	   $return = array (
		  		'status' => 'failed',
		  		'message' => "This id couldn't insert: " . $CI->input->post ( 'supplier' ) 
		   );		
		}else	
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 

			);

		return $return;

	}	
	// invoice all warehouse data here
	protected function invoice() {
		$CI = &get_instance ();
		$page = $CI->input->get ( 'page', TRUE );
		$limit = $CI->input->get ( 'rows', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );
		$sord = $CI->input->get ( 'sord', TRUE );
		$filters = $CI->input->get_post ( 'filters' );
		$spare_id = $CI->input->get ( 'spare_id', true );
		if (isset ( $spare_id ) && $spare_id)
			$where = "spare_id = $spare_id";
		
		if (! $sidx)
			$sidx = 1;
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		if ($_GET ['_search'] == 'true') {
			
			$where = $this->filter ( $filters );
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		// the actual query for the grid data
		if ($_GET ['_search'] == 'true') {
			$cnt_Sql = "SELECT count(*) as count FROM _wh_vw_spare $where ";
		} else {
			$cnt_Sql = "SELECT count(*) as count FROM _wh_vw_spare";
			// $SQL = "call proc_warehouse('$data', '$data', null, null, null, '$sidx' , '$sord' , $start , $limit)";
		}
		
		$query = $CI->db->query ( $cnt_Sql );
		// $query = $this->db->query("call proc_countwarehouse('$data', '$data')");
		if ($query->num_rows () > 0) {
			$countRow = $query->row_array ();
			$count = $countRow ['count'];
			// $count = $query->num_rows();
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
		
		if ($_GET ['_search'] == 'true') {
			$SQL = "SELECT a.spare_id, a.spare, a.measure, IFNULL(C.end_qty, 0) as end_qty, a.equipment, a.sector, a.section
           FROM _wh_vw_spare A left join _wh_vw_endqty as C on A.spare_id = C.spare_id
               $where ORDER BY $sidx $sord LIMIT $start , $limit";
		} else {
			$SQL = "SELECT a.spare_id, a.spare, a.measure, IFNULL(C.end_qty, 0) as end_qty, a.equipment, a.sector, a.section FROM _wh_vw_spare A left join _wh_vw_endqty C on A.spare_id = C.spare_id
               ORDER BY $sidx $sord LIMIT $start , $limit";
		}
		$Qry = $CI->db->query ( $SQL );
		
		$last_qry = $CI->db->last_query ();
		// we should set the appropriate header information
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		echo "<page>" . $page . "</page>";
		echo "<total>" . $total_pages . "</total>";
		echo "<records>" . $count . "</records>";
		echo "<sql>" . $last_qry . "</sql>";
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->spare_id . "'>";
			echo "<cell>" . $row->spare_id . "</cell>";
			echo "<cell><![CDATA[" . $row->spare . "]]></cell>";
			echo "<cell><![CDATA[" . $row->equipment . "]]></cell>";
			echo "<cell>" . $row->end_qty . "</cell>";
			echo "<cell>" . $row->measure . "</cell>";
			echo "<cell>" . $row->sector . "</cell>";
			echo "<cell>" . $row->section . "</cell>";
			echo "</row>";
		}
		echo "</rows>";
		$Qry->free_result ();
		// echo $where;
	}
	// get where clause
	public function getWhereClause($col, $oper, $val) {
		// global $ops;
		if ($oper == 'bw' || $oper == 'bn')
			$val .= '%';
		if ($oper == 'ew' || $oper == 'en')
			$val = '%' . $val;
		if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')
			$val = '%' . $val . '%';
		return " WHERE $col {$this->ops[$oper]} '$val' ";
	}
	
	// action loaded here
	protected function action() {
		$res_array = array ();
		$CI = &get_instance ();
		$res_array = $this->log_model->get_action_wh_spare ( $this->get_role () );
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
	
	// form section filter from form
	function jx_filter() {
		// get table name
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		
		// target equipment bol section id-r fllter hiine
		$target = $CI->input->get_post ( 'target' );
		if ($target == 'equipment') {
			$section_id = $CI->input->get_post ( 'id' );
			// echo $target;
			$query = $this->log_model->get_query ( 'equipment_id, equipment', '_wh_vw_spare', "where section_id = $section_id" );
			
			foreach ( $query->result () as $row ) {
				$data [$row->equipment_id] = $row->equipment;
			}
		}
		
		$data ['0'] = 'Сонгох...';
		return $data;
	}
	function get_equipment_by() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		
		$name = $CI->input->get_post ( 'name' );		
		$flag = $CI->input->get_post ( 'flag' );
		
		if ($flag == 'yes') { //section filter hiine
			if($name=='')
			   $query = $this->log_model->get_query ( 'sp_id, equipment', 'vw_equipment', null, 'equipment_id', 'DESC' );
			else
			   $query = $this->log_model->get_query ( 'sp_id, equipment', 'vw_equipment', "where section = '$name'", 'equipment_id', 'DESC' );
		}else{ // sector filter Хийхэд
			$section = $CI->input->get_post ( 'section' );
			$query = $this->log_model->get_query ( 'sp_id, equipment', 'vw_equipment', "where sector = '$name' and section = '$section'",'equipment_id', 'DESC' );
		}		
			// echo $this->log_model->last_query();
		if($query->num_rows()>0){
			$data ['0'] = 'Бүгд';
			foreach ( $query->result () as $row ) {
				$data [$row->sp_id] = $row->equipment;
			}
		}else
			$data [''] = 'Бүгд';
		return $data;
	}
	// section id-r filter hiih
	function get_equipment_by_id() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		$id = $CI->input->get_post ( 'id' );
		$flag = $CI->input->get_post ( 'flag' );

		if ($flag == 'yes') {
			$query = $this->log_model->get_query ( 'sp_id, equipment', 'vw_equipment', "where section_id = '$id' and sp_id is not null", "equipment", "asc" );
		} else {
			$section_id = $CI->input->get_post ( 'section_id' );
			$query = $this->log_model->get_query ( 'sp_id, equipment', 'vw_equipment', "where sector_id = '$id' and section_id = $section_id and sp_id is not null", "equipment", "asc" );
		}
		// echo $this->log_model->last_query();
		$data ['0'] = 'Бүгд';
		foreach ( $query->result () as $row ) {
			$data [$row->sp_id] = $row->equipment;
		}
		
		return $data;
	}
	// тухайн тоног төх-н Id-р сэлбэгийг дуудах
	function get_spare_id() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		$sp_id = $CI->input->get_post ( 'id' );
		$section_id = $CI->input->get_post ( 'section_id' );
		$sector_id = $CI->input->get_post ( 'sector_id' );
		$flag = $CI->input->get_post ( 'flag' );
		//_wh_vh_spare bol sp_id ym
		$query = $this->log_model->get_query ( 'spare_id, spare', '_wh_vw_spare', "where equipment_id = '$sp_id' and section_id = $section_id and sector_id = $sector_id", "spare_id", "desc" );

		$data ['0'] = 'Бүгд';
		if($sp_id!=0)
		   $data ['-1'] = 'Шинэ сэлбэг нэмэх';

		foreach ( $query->result () as $row ) {
			$data [$row->spare_id] = $row->spare;
		}
		
		return $data;
	}
	// section _id -r filter hiih
	function get_section_id() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		
		$id = $CI->input->get_post ( 'id' );
		
		$flag = $CI->input->get_post ( 'flag' );
		if ($flag == 'yes') {
			$query = $this->log_model->get_query ( 'sector_id, name as sector', 'view_sector', "where section_id = '$id'", "sector_id", "desc" );
		} else
			$query = $this->log_model->get_query ( 'sector_id, name as sector', 'view_sector', "where section_id = '$id'", "sector_id", "asc" );
			// echo $this->log_model->last_query();
		$data ['0'] = 'Бүгд';
		
		foreach ( $query->result () as $row ) {
			$data [$row->sector_id] = $row->sector;
		}
		
		return $data;
	}
	protected function get() {
		$CI = &get_instance ();
		$spare_id = $CI->input->post ( 'id' );		
		$field = $CI->input->post ( 'field' );

		$spare = $this->log_model->get_all ( array (
				'spare_id' => $spare_id 
		), '_wh_vw_spare' );

		if($field=='end_qty'){
			$end_qty = $this->log_model->get_row('end_qty', array('spare_id'=>$spare_id), '_wh_vw_endqty');
			if($end_qty==null)		
			  $spare['end_qty']= 0;
			else
			  $spare['end_qty']= $end_qty;
		}
		// echo $this->log_model->last_query();
		
		// var_dump($spare);
		if ($spare)
			$return = array (
					'status' => 'success',
					'message' => 'successfully',
					'spare' => $spare 
			);
		else
			$return = array (
					'status' => 'failed',
					'message' => "This id couldn't update: " . $CI->input->post ( 'id' ) 
			);
		
		return $return;
	}

	// //get info with uldegdel avah heregtei ba
	protected function get_exp_dtl() {
		$CI = &get_instance ();
		$spare_id = $CI->input->post ( 'spare_id' );
		$spare = $this->log_model->get_all ( array (
				'spare_id' => $spare_id 
		), '_wh_vw_spare' );


		// тухайн сэлбэгээр агуулахын тавиурт байгаа сэлбэг регистерүүдийг харуулна!		
		$qry = "SELECT *
                FROM wh_invoice A
                JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                        (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                WHERE invoicedate <=curdate()";
				
		$query = $this->log_model->get_as_query( $qry );
		$cnt = 1;
		$rowId = 1;
		$data = array();

		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				//get id by id
				$data['id']= $row->id;
				$data['pallet']= $row->pallet;				
				$data['barcode']= $row->barcode;				
			}
		} else {
			$data['id']=null;
		}
		// echo "<table width='300' align ='right' cellpadding='0' cellspacing ='0'>";
		// 	echo "<tr>";
		// 	echo "<th>";
		// 	echo "#";
		// 	echo "</th>";
		// 	echo "<th>";
		// 	echo "№";
		// 	echo "</th>";
		// 	echo "<th>";
		// 	echo "Тавиур";
		// 	echo "</th>";
		// 	echo "<th>";
		// 	echo "Сериал дугаар";
		// 	echo "</th>";
		// 	echo "</tr>";
		// 	foreach ( $query->result () as $row ) {
		// 		echo "<tr>";
		// 		echo "<td>";
		// 		echo "<input id='check_$rowId' type='checkbox' name='spare_pk[]' value='$row->id' onclick='checkit(this)'/>";
		// 		echo "</td>";
		// 		echo "<td>";
		// 		echo $cnt ++;
		// 		$rowId = $cnt;
		// 		echo "</td>";
		// 		echo "<td>";
		// 		echo $row->pallet;
		// 		echo "</td>";
		// 		echo "<td>";
		// 		echo $row->serial;
		// 		echo "</td>";
		// 		echo "</tr>";
		// 	}
		// 	echo "</table>";
		// 	$query->free_result ();
		//else
		// echo "<p style='font-style:italic; color:red'>Агуулахад тус сэлбэг байхгүй байна!</p>";
		// 	echo "<input type='hidden' name='sparelist' value='' id='sparelist'/>";
		
		// echo $this->log_model->last_query();
		// $end_qty = $this->log_model->get_row('end_qty', array('spare_id'=>$spare_id), '_wh_vw_endqty');
		// $spare['end_qty']=$end_qty;

		// // var_dump($spare);
		// if ($spare)
			// $return = array (
			// 		'status' => 'success',
			// 		'message' => 'successfully',
			// 		'data' => $data 
			// );
		// else
		// 	$return = array (
		// 			'status' => 'failed',
		// 			'message' => "This id couldn't update: " . $CI->input->post ( 'id' ) 
		// 	);
		
		 return $data;
	}
	protected function get_inv() {
		$CI = &get_instance ();
		$spare_id = $CI->input->post ( 'id' );
		// getquer by
		$spare = $this->log_model->get_all ( array (
				'spare_id' => $spare_id 
		), '_wh_vw_spare' );

		// echo $this->log_model->last_query();
		// var_dump($spare);
		if ($spare_id) {			
			// get via invoice_id wh_invoice_dtl
				$sql = "SELECT B.spare_id, pallet, warehouse, sum(aqty) as qty, sum(amt) as amt 
                FROM wh_invoice A
                JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                        (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id                
                WHERE invoicedate <=curdate()
                GROUP BY B.spare_id, pallet, warehouse";

			$result = $CI->db->query ($sql )->result ();
			//"CALL pro_invoice_dtl($spare_id, '2016-01-01', 'id', 'desc')" )->result ();

			if ($result) {
				$i = 0;
				$c_qty = 0;
				$c_amt = 0;
				foreach ( $result as $row ) {
					$pallet [$i] ['warehouse'] = $row->warehouse;
					$pallet [$i] ['pallet'] = $row->pallet;
					$pallet [$i] ['qty'] = $row->qty;
					$pallet [$i] ['amt'] = $row->amt;
					$i ++;
					$c_qty += $row->qty;
					$c_amt += $row->amt;

				}
			}

			$spare['qty'] =$c_qty;
			$spare['total'] =$c_amt;
			
			$return = array (
					'status' => 'success',
					'message' => 'successfully',
					'spare' => $spare,
					'pallet' => $pallet 
			);
		} else
			$return = array (
					'status' => 'failed',
					'message' => "This id couldn't update: " . $CI->input->post ( 'id' ) 
			);
		return $return;
	}
    
    // tag_node болгоход ашиглагдах ajax
    protected function set_expense(){
	   $CI = &get_instance ();
	   $CI->load->library ( 'form_validation' );

	   $count = $CI->input->post ( 'count' );
	   $expense_no = $CI->input->post ( 'expense_no' );
	   $expense_date = $CI->input->post ( 'expense_date' );
	   $spare_id = $CI->input->post ( 'spare_id' );
	   $qty = $CI->input->post ( 'qty' );
	   $section_id = $CI->input->post ( 'section_id' );
	   $sector_id = $CI->input->post ( 'sector_id' );

	   // print_r($expense_no);
	   // print_r($expense_date);
	   // print_r($spare_id);
	   // print_r($qty);
		// validation here
		$CI->form_validation->set_rules ( 'count', 'Сэлбэг', 'is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'expense_no', 'Баримтын №', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'expense_date', 'Зарлага огноо', 'required' );
		$CI->form_validation->set_rules ( 'spare_id', 'Сэлбэг', 'required' );
		$CI->form_validation->set_rules ( 'section_id', 'Хэсэг', 'required|is_natural_no_zero' );
		$CI->form_validation->set_rules ( 'sector_id', 'Тасаг', 'required|is_natural_no_zero' );

		$CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );

		$match = $this->get_row ( 'expense_no', array (
				'expense_date' => $expense_date,
				'expense_no' => $expense_no 
		), 'wh_expense' );

		if ($match) {
			$flag = FALSE;			
		} else {
			$flag = TRUE;
		}
		
		//echo $flag;
		if ($CI->form_validation->run () == TRUE && $flag==TRUE) {
		   date_default_timezone_set ( 'Asia/Ulan_Bator' );
		   $id = $this->wm_model->get_max_id( 'wh_invoice', 'id' );		
		//$query = $this->db->query ( "SELECT * FROM wm_income WHERE income_no = '$income_no'" );
			if ($id) {
				$inv_data['id'] = $id;
				$inv_data['invoicetype'] = 'expense';
				$inv_data['invoicedate'] = $expense_date;
				$inv_data['actionby_id'] = $this->user_id;
				$inv_data['actiondate'] = date ( "Y-m-d H:i:s" );			
				// зарлагийн утга өгөх
				//$res_invoice = $CI->db->insert ( 'wh_invoice', $inv_data);

				$res_invoice = $this->wm_model->insert_trans('wh_invoice', $inv_data);

				if($res_invoice){
				   //transaction insert 				
			   	  // count for spares get_sparedt_id by _invoice_dtl
			   	  // get_barcode  insert by batch
			      $res_exp_dtl = $this->wm_model->add_expense($id); 
			      if($res_exp_dtl)
			      	 $return = array (
	  				    'status' => 'success',
	  				    'message' => "Амжилттай хадгаллаа" 
	   				 );
			      else
			      	 $return = array (
	  				    'status' => 'failed',
	  				    'message' => "Хадгалахад алдаа гарлаа, ямар нэг зүйл болсонгүй" 
	   				 );
			
				}else{
					$return = array (
			  			'status' => 'failed',
			  			'message' => "Хадгалахад алдаа гарлаа, ямар нэг зүйл болсонгүй" 
			   		);
				}
	 		}
		}else{
			if(validation_errors()){
				$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' ) 

				);	
			}else
 				$return = array (
					'status' => 'failed',
					'message' => '[' . $expense_no . '] дугаартай зарлага ['. $expense_date . '] огноо дээр аль хэдийн орлого авсан тул дахиж авах боломжгүй!'

			);
		}

		return $return;

	}
    
    // zarlaga edit hiih 
    protected function edit_expense(){
	   $CI = &get_instance ();
	   $CI->load->library ( 'form_validation' );

	   $count = $CI->input->post ( 'count' );
	   $expense_no = $CI->input->post ( 'expense_no' );
	   $expense_date = $CI->input->post ( 'expense_date' );
	   $spare_id = $CI->input->post ( 'spare_id' );
           
	   $section_id = $CI->input->post ( 'section_id' );
	   $sector_id = $CI->input->post ( 'sector_id' );
	   $invoice_id = $CI->input->post ( 'invoice_id' );
	   $recievedby_id = $CI->input->post ( 'recievedby_id' );
	   $intend = $CI->input->post ('intend', TRUE);
           	   
            $CI->form_validation->set_rules ( 'count', 'Сэлбэг', 'is_natural_no_zero' );
            $CI->form_validation->set_rules ( 'expense_no', 'Баримтын №', 'required|is_natural_no_zero' );
            $CI->form_validation->set_rules ( 'expense_date', 'Зарлага огноо', 'required' );
            $CI->form_validation->set_rules ( 'spare_id', 'Сэлбэг', 'required' );
            $CI->form_validation->set_rules ( 'section_id', 'Хэсэг', 'required|is_natural_no_zero' );
            $CI->form_validation->set_rules ( 'sector_id', 'Тасаг', 'required|is_natural_no_zero' );
            $CI->form_validation->set_rules ( 'recievedby_id', 'Хүлээн авсан', 'required|is_natural_no_zero' );
            $CI->form_validation->set_rules ( 'intend', 'Зориулалт', 'required' );

            $CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" нэг утга сонгох шаардлагатай!' );
                             
            if ($CI->form_validation->run () == TRUE) {
               date_default_timezone_set ( 'Asia/Ulan_Bator' );
               //1. тухайн id-р устгана expense, invoice_dtl
               //2. insert expense, invoice_dtl хийнэ
               // хэрэв 2 амжилттай болбол transaction хийнэ
               //эс бөгөөс transaction rollback хийнэ.               
               //Detail_id гаар дэд хэсэгт хийх фүнкц
               
	       $data = array();
	       // print_r($this->input->get_post ( $j . '_dtl_id' ));               
//               print_r($CI->input->get_post('dtl_id'));
               
               $dtl_id = $CI->input->get_post('dtl_id');
               // тухайн утгын array-с 
//               echo "spare";
//               print_r($CI->input->get_post("spare_id"));
               foreach ($spare_id as $spare){
                   for($l=1;$l<=sizeof($dtl_id[$spare]);$l++){                       
                       $subData ['invoice_id'] = $invoice_id;
                       $subData ['spare_id'] = $spare;				
                       //print_r(array ('id' => $dtl_id[$spare][$l]));
                        $CI->db->select('*');
                        $CI->db->where('id', $dtl_id[$spare][$l]);
                        $query = $CI->db->get ('wh_invoice_dtl' );		
                        $row = $query->row_array ();
                                                
                        $subData['serial'] = $row['serial'];
                        $subData['serial_x'] = $row['serial_x'];
                        $subData['pallet_id'] = $row['pallet_id'];
                        $subData['barcode'] = $row['barcode'];
                        $subData ['aqty'] = - 1;
                        $subData['amt'] = -$row['amt'];
                        //print_r($subData);
                        array_push ( $data, $subData );
                   }
               }
               //print_r($data);
                              
//		for($i = 0; $i < $count; $i ++) {		    	
//			$detail = $CI->input->get_post ( $j . '_dtl_id' );
//                          print_r($detail);
//			$j ++;
//			$l = 0;		
//                        echo "size". sizeof($detail);
//			while($l < sizeof( $detail )){
//				$subData ['invoice_id'] = $invoice_id;
//				$subData ['spare_id'] = $spare_id [$i];				
//				$row = $this->wm_model->get_rows(array ('id' => $detail[$l]), 'wh_invoice_dtl');                              
//				$subData['serial'] = $row['serial'];
//				$subData['serial_x'] = $row['serial_x'];
//				$subData['pallet_id'] = $row['pallet_id'];
//				$subData['barcode'] = $row['barcode'];
//				$subData ['aqty'] = - 1;
//				$subData['amt'] = $row['amt'];
//				//print_r($subData);
//				$l ++;
//
//				array_push ( $data, $subData );
//			}
//		}
               
                
               $CI->db->trans_start();
               $CI->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
               //1.Update wh_invoice
               //2.Update wh_expense
               //3.delete wh_invoice_dtl
               
               $CI->db->query("UPDATE wh_invoice SET invoicedate = $expense_date, actionby_id = $this->user_id, actiondate = NOW() where id = $invoice_id");
               $CI->db->query("UPDATE wh_expense SET expense_no = $expense_no, expense_date = '$expense_date', intend = '$intend', section_id=$section_id, sector_id = $sector_id, recievedby_id = $recievedby_id WHERE invoice_id = $invoice_id");
               $CI->db->query("DELETE FROM wh_invoice_dtl where invoice_id = $invoice_id");                              
               $CI->db->insert_batch ( 'wh_invoice_dtl', $data );
                                                            
               $CI->db->trans_complete(); # Completing transaction

               if ($CI->db->trans_status() === FALSE){
                   //хэрэв худал бол тухайн утгуудыг дахин сэргээх шаардлагатай!
                   $CI->db->trans_rollback();    
                   $message= array (
                            'status' => 'failed',
                            'message' => "Алдаа №207. Зарлага засвар хийхэд гаргахад алдаа гарлаа, ямар нэг зүйл болсонгүй" 
                         );
               }else
               {
                   // хэрэв тухайн query амжилттай болоод 
                   // мөн шинэ insert амжилттай болбол trans_commit хийх үү? 
                   // тухайн Dtl -id гаар утгуудыг insert Хийх ба хэрэв алдаа гарвал
                   // алдааг мэдээллээд rollback хийнэ.
                    $CI->db->trans_commit();
                    $message = array (
                        'status' => 'success',
                        'message' => "Амжилттай хадгаллаа" 
                        );
               }               
              
            }else{
                if(validation_errors()){
                    $message= array (
                        'status' => 'failed',
                        'message' => validation_errors ( '', '<br>' ) 
                    );	                
                }
            }
            return $message;
	}

    //Захиалга өгөх хэсэг
    protected function order_form(){
        $data ['order_no'] = $this->log_model->get_max_id ( 'wm_order', 'order_no' );
        $data ['steward'] = $this->wm_model->getSteward ();
        return $this->theme_view ( 'order.php', $data);
    }

    //Жагсаалт харуулах хэсэг
    protected  function order_list(){
        $CI = &get_instance ();
        $page = $CI->input->get ( 'page', TRUE );
        $limit = $CI->input->get ( 'rows', TRUE );
        $sidx = $CI->input->get ( 'sidx', TRUE );
        $sord = $CI->input->get ( 'sord', TRUE );
        $spare = $CI->input->get ( 'spare' );

        $filters = $CI->input->get_post ( 'filters' );
        $search = $CI->input->get_post ( '_search' );

        if (! $sidx)
            $sidx = 1;
        $where = ""; // if there is no search request sent by jqgrid, $where should be empty
        $searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : false;
        $searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : false;
        $searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : false;

        if (($search == 'true') && ($filters != "")) {
            $where = $this->filter ( $filters );
        } else if ($_GET ['_search'] == 'true') {
            $where = $this->getWhereClause ( $searchField, $searchOper, $searchString );
        }

        if ($spare && isset ( $spare )) {
            $where = " WHERE spares like '%$spare%'";
        }

        // echo $_GET['searchString'];
        $query = $this->log_model->get_as_query ( "SELECT COUNT(*) AS count FROM wh_view_order" );
        if ($query->num_rows () > 0) {
            $countRow = $query->row_array ();
            $count = $countRow ['count'];
        }
        $query->free_result ();
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
        // if(isset($spare_id)&&$spare_id) $where = " WHERE spare_id =$spare_id ";
        // the actual query for the grid data
        if ($_GET ['_search'] == 'true' || isset ( $spare ))

            $SQL = "SELECT A.*, B.spares FROM wh_view_order A 

                    LEFT JOIN (SELECT order_id, GROUP_CONCAT(DISTINCT spare) as spares

                    FROM wm_orderdetail

                    GROUP BY order_id) B ON A.order_id = B.order_id
                    
                $where ORDER BY $sidx $sord LIMIT $start , $limit";
        else
            $SQL = "SELECT * FROM wh_view_order ORDER BY $sidx $sord LIMIT $start , $limit";
        $Qry = $this->log_model->get_as_query ( $SQL );
        // we should set the appropriate header information

//        if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
//            header ( "Content-type: application/xhtml+xml;charset=utf-8" );
//        } else {
//            header ( "Content-type: text/xml;charset=utf-8" );
//        }
        $today = strtotime(date('Y-m-d'));

		  $json = array ();
		  $row_bind = array ();
		  $crow = array ();
		
		  $json ['page'] = $page;
		  $json ['total'] = $total_pages;
		  $json ['records'] = $count;
		  $json ['where'] = $where;
        
        /* $xml_dtl = '';
        $xml_dtl .= "<?xml version='1.0' encoding='utf-8'?>";
        $xml_dtl.=  "<rows>";
        $xml_dtl.=  "<page>".$page."</page>";
        $xml_dtl.=  "<total>".$total_pages."</total>";        
        $xml_dtl.=  "<records>".$count."</records>";*/

        

        // // be sure to put text data in CDATA
        foreach ( $Qry->result () as $row ) {
	        	
	        	$crow ['id'] = $row->order_id;				

				$crow ['order_no'] = $row->order_no;

				$crow ['orders_date'] = $row->order_date;			

				$crow ['section'] = $row->section;

				$crow ['ordertype'] = $row->ordertype;

				$crow ['orderby'] = $row->orderby;

				$crow ['registed_date'] = $row->registed_date;			

				$crow ['diff_days'] = $row->diff_days;

				$crow ['comment'] = $row->comment;			

				$crow ['status'] = $row->status;		

				$crow ['steward'] = $row->steward;		
				
				$crow ['role'] = $this->get_role();		

				$crow ['status_id'] = $row->status_id;		

				array_push ( $row_bind, $crow );
        }

         $Qry->free_result ();
		
		   $json ['rows'] = $row_bind;		
		
		   return json_encode ( $json );
    }

    protected  function order_dtl() {
        $CI = &get_instance ();
        $order_id  = $CI->uri->segment ( 4 );
		$sord = $CI->input->get ( 'sord', TRUE );
		$sidx = $CI->input->get ( 'sidx', TRUE );

		$result = $CI->db->query ( "SELECT * FROM wm_orderdetail WHERE order_id =$order_id ORDER BY $sidx $sord " )->result ();

		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
            header ( "Content-type: text/xml;charset=utf-8" );
        }
        $dtl_xml = '';
        $dtl_xml .="<?xml version='1.0' encoding='utf-8'?>";
        $dtl_xml.="<rows>";
        // be sure to put text data in CDATA
        foreach ( $result as $row ) {
            $dtl_xml.="<row>";
            $dtl_xml.="<cell>" . $row->order . "</cell>";
            $dtl_xml.="<cell><![CDATA[" . $row->spare . "]]></cell>";
            $dtl_xml.="<cell><![CDATA[" . $row->measure . "]]></cell>";
            $dtl_xml.="<cell>" . $row->qty . "</cell>";
            $dtl_xml.="<cell><![CDATA[" . $row->reason . "]]></cell>";
            $dtl_xml.="</row>";
        }
        $dtl_xml.="</rows>";
        return $dtl_xml;
        }

    //Захиалга бүртгэх хэсэг
    protected  function set_order_form(){
        $data ['measure'] = $this->wm_model->getMeasure ();
        //get_select($column, $where = null, $table)
        $section = $this->log_model->get_select('name', null, 'section');
        $section[0]='Бүх хэсгүүд';
        $data['section'] = $section;

        $employee = $this->log_model->get_select('fullname', null, 'employee');
        $employee[0]='Бүх амжилчид';
        $data['employee'] = $employee;

        $data['user_id']=$this->log_model->get_row('fullname', array('employee_id'=>$this->user_id), 'view_employee');
        //$data ['page'] = 'warehouse\order\orderPage';
        return $this->theme_view ( 'set_order.php', $data);

    }

    //Spare filter хийж байна!
    protected function  spare_filter(){
        $CI = &get_instance();
        //spare filter hiij baina
        $spare = $CI->input->get_post('term');
//        $CI->db->select ( "spare_id, concat(spare, equipment) as spare_equip, part_number, measure" );
//        $CI->db->from ( '_wh_vw_spare' );
        //$this->db->from ( 'wm_view_spare' );
        $query = $CI->db->query ("SELECT spare_id, concat(spare , ' ( ', equipment, ')') as spare_equip, part_number, measure FROM _wh_vw_spare");

        $q = strtolower ( $spare );
        // remove slashes if they were magically added
        if (get_magic_quotes_gpc ())
            $q = stripslashes ( $q );
        $result = array ();
        foreach ( $query->result () as $row ) {
            if (strpos ( strtolower ( $row->spare_equip ), $q ) !== false) {
                array_push ( $result, array (
                    "id" => $row->spare_id,
                    "value" => $row->spare_equip,
                    "part" => $row->part_number,
                    "measure" => $row->measure
                ) );
            }
            if (count ( $result ) > 15)
                break;
        }
        /*
         * foreach ($data as $key=>$value) {
         * if(strpos(strtolower($value), $q) !== false) {
         * array_push($result, array("id"=>$key, "value" =>$value));
         * }
         * if(count($result) > 11)
         * break;
         * }
         */
        return $result;

    }

    //Захиалгийг нэмэх фүнкцийг энд хийлээ.
    protected  function  add_order(){
        $CI=&get_instance();
        $CI->load->library ( 'form_validation' );
        $section_id = $CI->input->post ( 'section_id' );
        $order_date = $CI->input->post ( 'order_date' );
        $order_type = $CI->input->post ( 'order_type' );
        $order_no = $CI->input->post ( 'order_no' );
        $count = $CI->input->post ( 'count' );

        $CI->form_validation->set_rules ( 'order_no', 'Захиалгын дугаар', 'required' );
        $CI->form_validation->set_rules ( 'section_id', 'Захиалсан хэсэг', 'required|is_natural_no_zero' );
        $CI->form_validation->set_rules ( 'order_type', 'Захиалга төрөл', 'required' );
        $CI->form_validation->set_rules ( 'order_date', 'Захиалгын огноо', 'required' );
        $CI->form_validation->set_rules ( 'count', 'Сэлбэг', 'required|is_natural_no_zero' );

        $CI->form_validation->set_message ( 'is_natural_no_zero', ' "%s" -н утга шаардлагатай. Утга сонгоно уу?' );
        //var_dump($CI->input->get_post('spare'));

        if ($CI->form_validation->run () != FALSE) {
            
            // Захиалгийн дугаар хадгалагдсан эсэхийг шалгана
            if ($this->wm_model->is_orderno_set () == FALSE) {

                if ($this->wm_model->makeOrder () == TRUE) {
                    $return = array (
                        'status' => 'success',
                        'message' => "Захиалгийг амжилттай хадгаллаа."
                    );
//                    $this->session->set_userdata ( 'message', 'Захиалгийг амжилттай хадгаллаа.' );
//                    redirect ( '/warehouse/order' );

                } else {
                    $return = array (
                        'status' => 'failure',
                        'message' => "Захиалга хадгалахад алдаа гарлаа."
                    );
                }
            } else {
               $return = array (
                    'status' => 'failure',
                    'message' => $order_no . ' дугаартай захиалга '.$order_date.' аль хэдийн хадгалагдсан байна. Дугаараа шалгаад дахин оролдоно уу!'
                );
//                $this->session->set_userdata ( 'message', $order_no . ' дугаартай захиалга аль хэдийн хадгалагдсан байна. Дугаараа шалгаад дахин оролдоно уу!' );
//                redirect ( '/warehouse/orderPage' );
            }
        } else {
            $return = array (
                'status' => 'failure',
                'message' => validation_errors ( '', '<br>' )
            );
//            $this->session->set_userdata ( 'message', validation_errors ( '', '<br>' ) );
//            redirect ( '/warehouse/orderPage' );
        }
        return $return;

    }
    
    //тухайн орлогийн засварын фүнкц
    protected  function edit_income_check(){
        $CI=&get_instance();
        $CI->load->library ( 'form_validation' );        

        $CI->form_validation->set_rules ( 'spare', 'Сэлбэг', 'required' );
        $CI->form_validation->set_rules ( 'income_no', 'Орлого дугаар', 'required' );
        $CI->form_validation->set_rules ( 'income_date', 'Орлого огноо', 'required' );
        
        //1. тухайн орлого дахь сэлбэгээр зарлага гараад тухайн сэлбэг нь одоо байгаа сэлбэгээс өөр байвал болохгүй                
        $invoice_id = $CI->input->get_post('invoice_id');
        $result= $this->log_model->get_dropdown_by('spare_id', 'spare_id', array('invoice_id'=>$invoice_id), 'wh_invoice_dtl');        
        $result2= $this->log_model->get_result(array('invoice_id'=>$invoice_id), 'wh_invoice_dtl');       
        
        //print_r($result);
        $count = $CI->input->get_post('count');
        $spare = $CI->input->get_post('spare');
        $spares = array();
        $income_date = $CI->input->get_post('income_date');       
        $flag = 0;
        
        for($i=1; $i<=$count;$i++){
             //2. орлого авсан огнооноос өмнө зарлага гарсан бол болохгүй.
            $CI->db->select('spare_id');            
            $CI->db->where('expense_date <=', $income_date);
            $CI->db->where('spare_id', $spare[$i]['id']);
            $query = $CI->db->get ('vw_expense_dtl');		
            $row = $query->row_array ();
//            if($row ['spare_id']){
//               $flag_3 = 1;
//            }
            //echo $this->log_model->last_query();            
            array_push($spares, $spare[$i]['id']);
        }
        //print_r($spares);  
        $flag_1 = 0;        
        $flag_2 = 0;        
        foreach ($result2 as $row){
           //  тухайн spare_id гаар зарлага гарсан эсэхийг шалгана            
           if($this->log_model->get_row('spare_id', array('barcode'=>$row->barcode, 'aqty'=>-1), 'wh_invoice_dtl')){
              $flag_2 = 1;                  
           }
           //тухайн сэлбэг өөрчлөгдсөн эсэхийг шалгах
            if (in_array($row->spare_id, $spares)){               
                $flag_1 = 1;
            }else{               
                $flag_1 = 0;
            }
        }        
        //1-р action дууссан
        if($flag_2==1){
            $msg = array (
                'status' => 'failure',
                'message' => 'Тухайн сэлбэгийн утга дээр зарлага гаргасан тул орлого авах болмжгүй'
            );
        }elseif ($CI->form_validation->run () == TRUE) {
            // Захиалгийн дугаар хадгалагдсан эсэхийг шалгана
             $msg = array (
                'status' => 'success',
                'message' => $CI->input->get_post('spare')
             );     

        } else {
            $msg = array (
                'status' => 'failure',
                'message' => validation_errors ( '', '<br>' )
            );
        }       

        return $msg;
    }
    
    //Орлогод авахад засварлах хэсэг
    protected  function edit_income_dtl(){
        //хэрэв тухайн
        $CI=&get_instance();
        $data ['title'] = 'Орлого авах';
	$count = $CI->input->get_post ( 'count' );		        
        //invoice_id утга авах
	$data['invoice_id']= $CI->input->get_post ( 'invoice_id' );		
        // $data=$this->wm_model->get_income_dtl();
        $spare = $CI->input->get_post ( 'spare' );
        // тухайн $spare section, sector, equipment -ын утга 0 байвал утгийг авч өгөх хэрэгтэй
        $spare_id = $CI->input->get_post ( 'spare_id' );
        // тухайн сэлбэгийн тоног төхөөрөмжөөр бар код үүсгэх
        // $data['barcode']= $this->get_barcode($count, $spare_id);
        $qty = $CI->input->get_post ( 'qty' );
        $data ['qty'] = $qty;
        $data ['spare'] = $this->gen_barcode ( $spare );
        $data ['income_no'] = $CI->input->get_post ( 'income_no' );
        $data ['income_date'] = $CI->input->get_post ( 'income_date' );        
        $data ['pallet'] = $this->wm_model->getPallet ();
        $data ['warehouse'] = $this->wm_model->getWarehouse ();
        // $data['spare_id']=$CI->input->get_post('spare_id');
        $data ['amt'] = $CI->input->get_post ( 'amt' );
        $data ['type_id'] = $CI->input->get_post ( 'type_id' );
        $data ['supplier_id'] = $CI->input->get_post ( 'supplier_id' );
        
        // invoice_id -р байгаа сэлбэгүүдийг зарлага гаргасан эсэх
        // хэрэв зарлага гаргасан бол тухайн зарлагийн огноо болон утгийг дахин авах хэрэгтэй
        // $invoice_id -н spare-даар зарлага тухайн огноогоор зарлага гаргасан эсэх
                
        
        //$invoice_id = $CI->input->get_post('invoice_id');
        //$result = $this->log_model->get_result(array('invoice_id'=>$invoice_id), 'wh_invoice_dtl');        
       // $income_date = $data['income_date'];
//        foreach ($result as $row){
//            //тухайн spare_id-р тухайн огнооноос дараа зарлага гаргасан эсэх!                        
//            $query = $CI->db->query("SELECT * FROM vw_expense_dtl where spare_id = $row->spare_id and expense_date <= '$income_date'");
//            //var_dump($spare_id);
//            if($query->num_rows()>0)
//                $error = 1;
//        }        
           return $this->theme_view ( 'edit_income_dtl.php', $data);        
    }
    
    //Орлого засах үйлдэл эцсийн байдлаар хийх VI/23    
    protected function edit_income() {
		$CI = &get_instance ();
		$CI->load->helper ( 'PHPExcel' );		
		if ($invoice_id = $this->wm_model->edit_income_dtl()) {		
		 	// orlogod avsnii daraa barcode xls file uusgej ugnu!
		 	// excel file-g shuud tataj avah bolno!
		 	$objPHPExcel = new PHPExcel ();
		 	$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( 'USEr' )->setTitle ( "ECNS Shiftlog report" )->setSubject ( "Shiftlog Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Barcode file" );
			
                    $qry = $CI->db->query ( "SELECT A.barcode, B.section, B.sector, B.equipment, B.spare, C.pallet FROM 	wh_invoice_dtl A 	
	             left join _wh_vw_spare B ON A.spare_id = B.spare_id	
	             left join wm_pallet C ON A.pallet_id = C.pallet_id
	              where A.invoice_id = $invoice_id" )->result ();
			
	 		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'Code' )->setCellValue ( 'B1', 'pallet' )->setCellValue ( 'C1', 'section' )->setCellValue ( 'D1', 'sector' )->setCellValue ( 'E1', 'equipment' )->setCellValue ( 'F1', 'spare' );
			
	 		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
	 		$i = 2;
			foreach ( $qry as $cols ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->barcode );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->pallet );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->section );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->sector );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->equipment );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->spare );
				$i ++;
			}
			
			$objPHPExcel->setActiveSheetIndex ( 0 );
			// Save Excel 2007 file
			// echo date('H:i:s') , " Write to Excel2007 format" , PHP_EOL;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( str_replace ( '.php', '.xlsx', __FILE__ ) );
			$filename = 'E:\xampp\htdocs\ecns\application\libraries\Warehouse.xlsx';
			$destination = 'E:\xampp\htdocs\ecns\download\Warehouse.xlsx';
				
			if (file_exists ( $filename )) {
			// move to the direcotry download
			   $result = copy ( $filename, $destination );
			   if ($result) {
				   unlink ( $filename );
				   // echo "amjilttai huullaa";
				   // $this->income
				   $data ['query'] = $qry;
				   $data ['msg'] = "Амжилттай хадгаллаа! <br> XLS файлыг амжилттай үүсгэлээ!";
				   $data ['link'] = "<a class='button' href='" . base_url () . "download/Warehouse.xlsx'>Баркодын файл хадгалах</a>";
				// redirect(base_url().'download/Warehouse.xlsx');
		 		} else
		 			$data ['msg'] = "erroro to move to the folder";
		 		// then download
		 	} else {
		 		$data ['msg'] = "sorry file not created";
		 	}
		 } else {
		 	$data ['query'] = null;
		 	$data ['link'] ="";
		 	$data ['msg'] = "Алдаа: No:WH_1- Агуулахад өгөгдлийг хадгалж чадсангүй!";
		 }		 
		 return $this->theme_view ( 'add_result.php', $data );
	}

    // зарлага гарсан эсэхийг шалгах фүнкц
    protected function check_id($invoice_id =null){
        $CI=&get_instance();  
        if(!$invoice_id)
           $invoice_id =$CI->input->get_post('invoice_id');        
        
        $result2= $this->log_model->get_result(array('invoice_id'=>$invoice_id), 'wh_invoice_dtl');       
        //энэ сэлбэг зарлага гаргасан эсэхийг шалгана.
        $flag = 0;                
        foreach ($result2 as $row){
            //  тухайн spare_id гаар зарлага гарсан эсэхийг шалгана            
            if($this->log_model->get_row('spare_id', array('barcode'=>$row->barcode, 'aqty'=>-1), 'wh_invoice_dtl')){
               $flag = 1;                  
            }
        }
            
        if ($flag) {
            // Захиалгийн дугаар хадгалагдсан эсэхийг шалгана
             $msg = array (
                'status' => 'failure',
                'message' => 'Сэлбэг дээр зарлага гаргасан тул жагсаалтаас хасах боломжгүй'
             );     
        } else {
            $msg = array (
                'status' => 'success',
                'message' => "Сэлбэг зарлага гаргаагүй байна."
            );
        }

        return $msg;
    }
    
    
    //тухайн зарлага гаргасан сэлбэгийг засах
    protected function check_edit(){
        $CI=&get_instance();        
        $invoice_id =$CI->input->get_post('invoice_id');
        $spare_id = $CI->input->get_post('spare_id');
        
        $expense_date = $this->log_model->get_row('expense_date', array('invoice_id'=>$invoice_id ), 'wh_expense');
        
        //get expense detail-s id, ba date-r utga bgaa esehiig shalgana        
        $query= $this->log_model->get_as_query("select * from vw_expense_dtl where spare_id = $spare_id and expense_date > '$expense_date'");
                
        //энэ сэлбэг зарлага гаргасан эсэхийг шалгана.
        if($query->num_rows()>0){
        	  $msg = array (
                'status' => 'failure',
                'message' => 'Сэлбэгийн '.$expense_date.' огнооноос хойш зарлага гаргасан тул засах боломжгүй!'
             );     
        }else {	   // Захиалгийн дугаар хадгалагдсан эсэхийг шалгана
         
            $msg = array (
                'status' => 'success',
                'message' => "Сэлбэг зарлага гаргаагүй байна."
            );
        }
        return $msg;
    }


	function get_employee() {
		$json_arr = array ();
		$data = array ();
		$CI = &get_instance ();
		$data ['0'] = 'Сонгох..';
		
		$section_id = $CI->input->get_post ( 'section_id' );
		if ($section_id) {
			$query = $this->log_model->get_query ( 'employee_id, fullname', 'view_employee', "where section_id=$section_id" );
			if ($query->num_rows > 0)
				foreach ( $query->result () as $row ) {
					$data [$row->employee_id] = $row->fullname;
				}
			else
				$data ['0'] = 'Сонгосон хэсэгт ИТА-д бүртгэгдээгүй байна!';
		}
		return $data;		
		// category_id
	}


    protected function get_barcode(){

    	// its removed by IV/19 on change idea 

     // $spare_id = $this->input->get_post ( 'spare_id' );
     
     // $qty = $this->input->get_post ( 'total' );
     
     // $spare = $this->wh_spare->get($spare_id);

     // //max_id = from wm_model
     // $max_id = $this->wm_model->get_max_barcode( $spare->sector_id, $spare->equipment_id);

     // $head = "0" . $spare->sector_id;

     // // echo "barcode".$spare_id[$i];
     // $mid = sprintf ( '%03d', $spare->equipment_id );
     
     // // $bar_end = sprintf('%06d', $spare_id[$i]);
     // $barcode = $head . '-' . $mid . '-' . sprintf ( '%06d', $max_id);


        return  array('barcode' =>$barcode, 'qty' =>$qty);

    }


}
class Warehouse extends Warehouse_Driver {
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

		//echo $this->state;
		
        switch ($this->check_state ()) {
                case 'grid' :
                        $data ['json'] = null;
                        $data ['xml'] = $this->_grid ();
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                case 'invoice' :
                        $data ['json'] = null;
                        $data ['xml'] = $this->invoice ();
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

                // орлого жагсаалт харуулна.
                case 'income' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['form'] = $this->income_grid_form ();
                        return ( object ) $data;
                        break;

                // Зарлага жагсаалт харуулна.
                case 'expense' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['form'] = $this->expense_grid_form ();
                        return ( object ) $data;
                        break;

                // орлого жагсаалт jquery ajax
                case 'jx_expense_grid' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['json'] = $this->expense_grid();
                        $data ['view'] = false;

                        return ( object ) $data;
                        break;

                // орлого жагсаалт jquery ajax
                case 'jx_income' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['json'] = $this->income_grid ();
                        $data ['view'] = false;

                        return ( object ) $data;
                        break;	// орлого жагсаалт jquery ajax

                case 'jx_income_sub_dtl' : 
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data['json']=null;
                        $data ['xml'] = $this->income_sub_dtl ();
                        $data ['view'] = false;

                        return ( object ) $data;
                        break;

                // jx_filter
                case 'jx_filter' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.
                        $data ['json'] = json_encode ( $this->jx_filter () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // equipment ig filter hiih
                case 'get_eq_by' :
                        $data ['json'] = json_encode ( $this->get_equipment_by () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // equipment-г id-р filter хийх
                case 'get_equipby_id' :
                        $data ['json'] = json_encode ( $this->get_equipment_by_id () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // equipment-г id-р filter хийх
                case 'get_spare_id' :
                        $data ['json'] = json_encode ( $this->get_spare_id () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // section-г id-р filter хийх
                case 'get_section_id' :
                        $data ['json'] = json_encode ( $this->get_section_id () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;
                // jx_filter
                case 'jx_income_valid' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.
                        $data ['json'] = json_encode ( $this->jx_income_valid () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // орлого авах
                case 'get_income' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['form'] = $this->get_income_form ();
                        return ( object ) $data;
                        break;

                // зарлага гаргах
                case 'get_expense' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data ['form'] = $this->get_expense_form ();
                        return ( object ) $data;
                        break;

                case 'jx_expense':
                        $data ['json'] = json_encode ( $this->set_expense() );
                        $data ['view'] = false;
                        return (object)$data;
                        break;
                
                //edit expense here
                case 'jx_edit_expense':
                    $data ['json'] = json_encode ( $this->edit_expense() );
                    $data ['view'] = false;
                    return (object)$data;
                    break;

                case 'jx_expense_dtl':
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['action'] = $this->action ();
                        $data['json']=null;
                        $data ['xml'] = $this->expense_dtl ();
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // jx_filter
                case 'add_income' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.
                        $data ['role'] = $this->get_user_role ();
                        $data ['xml'] = false;
                        $data ['json'] = null;
                        $data ['form'] = $this->add_income ();
                        $data ['action'] = $this->action ();
                        return ( object ) $data;
                        break;

                // Тухайн сэблэгийг авахад
                case 'get' :
                        $return = $this->get ();
                        $data ['json'] = json_encode ( $return );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                //тухайн exersice
                case 'get_exp_dtl':				
                        $data['json']= json_encode($this->get_exp_dtl());
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // Тухайн invoice avahad
                case 'get_inv' :
                        $return = $this->get_inv ();
                        $data ['json'] = json_encode ( $return );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // Агуулахад тавих
                case 'income_dtl' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.\
                        $data ['role'] = $this->get_user_role ();
                        $data ['form'] = $this->income_dtl_form ();
                        return ( object ) $data;
                        break;

                case 'jx_del' :
                        $return = $this->delete ();
                        $data ['json'] = json_encode ( $return );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                //case delete expense
                case 'jx_expense_del' :
                        $return = $this->expense_delete ();
                        $data ['json'] = json_encode ( $return );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;
                //add spare here
                case 'jx_add_spare' :				
                        $data ['json'] = json_encode ($this->add_spare());
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // jx_add_supplier
                case 'jx_add_supplier' :
                        // энэ эрх уруу хандаж болох эсэхийг заана.
                        $data ['json'] = json_encode ( $this->add_supplier () );
                        $data ['view'] = false;
                        return ( object ) $data;
                        break;

                // орлого засах хэсэг энд байна!
                case 'income_edit':
                    $data ['role'] = $this->get_user_role ();
                    $data ['action'] = $this->action ();
                    $data ['form'] = $this->income_edit_form ();
                    return ( object ) $data;
                    break;

                //тухайн сэлбэгээр зарлага гарсан эсэхийг шалгах хэрэгтэй хэрэв зарлага гарсан бол засахийг хориглох хэрэгтэй!
                //тухайн сэлбэгээр зарлага гарсан эсэхийг шалгах ajax хэрэгтэй
                case 'jx_income_edit':
                    $data ['json'] = json_encode ( $this->add_supplier () );
                    $data ['view'] = false;
                return ( object ) $data;
                break;

                // Захиалга өгөх хэсэг энд байна
                case 'order':
                    $data ['role'] = $this->get_user_role ();
                    $data ['action'] = $this->action ();
                    $data ['form'] = $this->order_form ();
                    return ( object ) $data;
                break;

                //Захиалгийн жагсаалт харуулах
                case 'jx_order':
                    $data['json']=null;
                    $data ['xml'] =  $this->order_list() ;
                    $data ['view'] = false;
                    return (object)$data;
                    break;
                //Захиалгийн жагсаалт дэлгэрэнгүй харуулах
                case 'jx_order_dtl':
                    $data['json']=null;
                    $data ['xml'] =  $this->order_dtl() ;
                    $data ['view'] = false;
                    return (object)$data;
                    break;

                // Захиалга бүртгэх хэсэг энд байна
                case 'set_order':
                    $data ['role'] = $this->get_user_role ();
                    $data ['action'] = $this->action ();
                    $data ['form'] = $this->set_order_form ();
                    return ( object ) $data;
                    break;

                case 'jx_spare':
                    $data ['json'] = json_encode ( $this->spare_filter() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;

                case 'jx_add_order':
                    $data ['json'] = json_encode ( $this->add_order() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;

                //edit income ajax
                  case 'jx_edit_income':
                    $data ['json'] = json_encode ( $this->edit_income_check() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;

                //edit income ajax
                  case 'edit_income_dtl':                
                    $data ['role'] = $this->get_user_role ();
                    $data ['action'] = $this->action ();
                    $data ['form'] = $this->edit_income_dtl();
                    $data ['view'] = true;
                    return ( object ) $data;                
                    break;

                case 'edit_income' :                
                    $data ['role'] = $this->get_user_role ();
                    $data ['xml'] = false;
                    $data ['json'] = null;
                    $data ['form'] = $this->edit_income ();
                    $data ['action'] = $this->action ();
                    return ( object ) $data;
                    break;


                // check remove id 
                  case 'jx_check_id':
                    $data ['json'] = json_encode ( $this->check_id() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;
                
                //edit expense
                case 'expense_edit':
                    $data ['role'] = $this->get_user_role ();
                    $data ['action'] = $this->action ();
                    $data ['form'] = $this->expense_edit_form ();
                    return ( object ) $data;                    
                    break;
               
                //тухайн мөрийг засахад тухайн spare_id-р тухайн огнооноос хойш зарлга гарсан эсэхийг шалгана
                case 'jx_check_edit':
                    $data ['json'] = json_encode ( $this->check_edit() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;
              
                case 'get_employee':
                    $data ['json'] = json_encode ( $this->get_employee() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;

                 case 'get_barcode':
                    $data ['json'] = json_encode ( $this->get_barcode() );
                    $data ['view'] = false;
                    return ( object ) $data;
                    break;
               
        }
    }
}
