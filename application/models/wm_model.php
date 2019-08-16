<?php
class wm_model extends CI_Model {
   

    function __construct() {
		// Call the Model constructor
                parent::__construct();
		$this->load->database ();
		$this->load->helper ( 'array' );
		$this->load->model ( 'main_model' );		
		$this->load->library ( 'session' );
	}
	function getSupplier() {
		$Q_w = $this->db->get ( 'wm_supplier' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['supplier_id']] = $row ['supplier'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function getManufacture() {
		$Q_w = $this->db->get ( 'wm_manufacture' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['manufacture_id']] = $row ['manufacture'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function getWarehouse() {
		$Q_w = $this->db->get ( 'wm_warehouse' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['warehouse_id']] = $row ['warehouse'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function getPallet() {
		$Q_w = $this->db->get ( 'wm_pallet' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['pallet_id']] = $row ['code'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function getSection() {
		$Q_l = $this->db->get ( 'section' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['section_id']] = $row ['name'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	function getSectionCode($section_id = null) {
		if (isset ( $section_id ))
			$this->db->where ( 'section_id', $section_id );
		$Q_l = $this->db->get ( 'wm_view_section' );
		
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result () as $row ) {
				$sec_code = $row->sec_code;
			}
		}
		$Q_l->free_result ();
		return $sec_code;
	}
	function getEquipment($section_id = null) {
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Бүх төхөөрөмж';
		$this->db->select ( 'equipment_id, name' );
		$this->db->from ( 'equipment' );
		if (isset ( $section_id ))
			$this->db->where ( 'section_id', $section_id );
		
		$Q_e = $this->db->get ();
		if ($Q_e->num_rows () > 0) {
			foreach ( $Q_e->result_array () as $row ) {
				$data [$row ['equipment_id']] = $row ['name'];
			}
		}
		$Q_e->free_result ();
		return $data;
	}
	function filterSection() {
		$data [0] = 'Бүгдийг харуул';
		$Q_l = $this->db->get ( 'wm_view_section' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['section_id']] = $row ['name'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	function getSparetype() {
		$data [0] = 'Нэг төрлийг сонгоно уу?';
		$Q_l = $this->db->get ( 'wm_sparetype' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['sparetype_id']] = $row ['sparetype'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	function getSpare() {
		$data [0] = 'Нэгийг сонгоно уу?';
		$Q_l = $this->db->get ( 'wm_spare' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['spare_id']] = $row ['spare'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	function getAccountant() {
		$data = array ();
		$this->db->select ( 'employee_id, fullname' );
		$this->db->from ( 'employee' );
		$this->db->join ( 'position', 'employee.position_id = position.position_id' );
		$this->db->where ( 'role', 'ACT' );
		$Q_w = $this->db->get ();
		
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['employee_id']] = $row ['fullname'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	// Хангамжийн нярав инженерүүд
	function getSteward() {
		$data = array ();
		$this->db->select ( 'employee_id, fullname' );
		$this->db->from ( 'wm_view_employee' );
		$Q_w = $this->db->get ();
		
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['employee_id']] = $row ['fullname'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function getMeasure() {
		$Q_w = $this->db->get ( 'wm_measures' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['measure_id']] = $row ['measure'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}



	// insert Income Invoice
	function insIncome() {
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$id = $this->main_model->get_maxId ( 'wm_invoice', 'id' );
		$storeman_id = $this->input->get_post ( 'storeman_id' );
		$income_date = $this->input->get_post ( 'income_date' );
		$income_no = $this->input->get_post ( 'income_no' );
		
		// `invoicetype` is isIncome
		$query = $this->db->query ( "SELECT * FROM wm_income WHERE income_no = '$income_no'" );
		if ($id || $query->num_rows == 0) {
			$invData ['id'] = $id;
			$invData ['invoicetype'] = 'isIncome';
			$invData ['invoicedate'] = $income_date;
			$invData ['actionby_id'] = $storeman_id;
			$invData ['actiondate'] = date ( "Y-m-d H:i:s" );
			
			// орлогийг бүртгэх толгой
			$result = $this->db->insert ( 'wm_invoice', $invData );
			if ($result) {
				// actionby_id
				// `income_id` + is inovoice_id;
				$data ['invoice_id'] = $id;
				// `income_no`,
				$data ['income_no'] = $income_no;
				// income_date +
				$data ['income_date'] = $this->input->get_post ( 'income_date' );
				// purpose +
				$data ['purpose'] = $this->input->get_post ( 'purpose' );
				// get supplier_id +
				$data ['supplier_id'] = $this->input->get_post ( 'supplier_id' );
				// get accountant_id +
				$data ['accountant_id'] = $this->input->get_post ( 'accountant_id' );
				$data ['storeman_id'] = $storeman_id;
				// get storeman_id +
				$data ['isBalance'] = 'N';
				// get order_no odoogoor shiidegdeegui
				$result_2 = $this->db->insert ( 'wm_income', $data );
				// $sql="call insInvIncome($id, '$incomed_no', '$income_date', '$purpose', $supplier_id, $accountant_id, $storeman_id, '$isBalance', $storeman_id)";
				// echo $sql;
				// echo $income_no; IN in_income_date, IN in_purpose , IN in_supplier_id , IN in_accountant_id, IN in_storeman_id, IN in_isBalance, IN in_actionby_id
				// echo $this->db->last_query();
				if ($result_2) {
					return $id;
				} else {
					return null;
				}
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	// prepare Income Detail
	function preIncomeDtl() {
		// sent post variable of spare_id
		$count = $this->input->get_post ( 'count' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		$qty = $this->input->get_post ( 'qty' );
		// count ni нийт хэдэн сэлбэг байгааг илэрхийлнэ.
		// 0-с эхлэнэ.
		// spare_id гаар сэлбэгүүдйн нэрийг авна.
		$spare = array ();
		for($i = 0; $i < $count; $i ++) {
			$spare [$i] = $this->main_model->get_row ( 'spare', array (
					'spare_id' => $spare_id [$i] 
			), 'wm_spare' );
		}
		$data [] = array ();
		$data ['spare'] = $spare;
		$data ['qty'] = $qty;
		$data ['count'] = $count;
		$data ['spare_id'] = $spare_id;
		return $data;
		
		/*
		 * echo "spare:";
		 * print_r ($spare);
		 * // spare_id[0] id = 1
		 * // spare_id[1] id = 2
		 * // php гээр үүсгэнэ динамик
		 * echo "count:";
		 * echo $count;
		 * echo "spare_id:";
		 * print_r ($spare_id);
		 * echo "</br>";
		 * echo "qty:";
		 * print_r ($qty);
		 *
		 */
	}
	
	// before call income dtl
	function get_income_dtl() {
		// sent post variable of spare_id
		$count = $this->input->get_post ( 'count' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		
		$qty = $this->input->get_post ( 'qty' );
		$amt = $this->input->get_post ( 'amt' );
		// count ni нийт хэдэн сэлбэг байгааг илэрхийлнэ.
		// 0-с эхлэнэ.
		// spare_id гаар сэлбэгүүдйн нэрийг авна.
		$spare = array ();
		for($i = 0; $i < $count; $i ++) {
			$spare [$i] = $this->main_model->get_row ( 'spare', array (
					'id' => $spare_id [$i] 
			), 'wh_spare' );
		}
		$data [] = array ();
		$data ['spare'] = $spare;
		$data ['qty'] = $qty;
		$data ['amt'] = $amt;
		$data ['count'] = $count;
		$data ['spare_id'] = $spare_id;
		return $data;
	}
	
	// max_id-Г салгаж авч буцаах шаардлагагүй болсон
	function get_max_barcode($sector_id, $equipment_id) {
		$query = $this->db->query ( "SELECT CONVERT(SUBSTRING(barcode, 9, 14), unsigned INTEGER) as code FROM wh_invoice_dtl A join _wh_vw_spare B on A.spare_id = B.spare_id 
        where B.sector_id = $sector_id and equipment_id = $equipment_id      
        Order by CONVERT(SUBSTRING(barcode, 9, 14), unsigned INTEGER) desc limit 1" );
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$max_id = $row ['code'];
			$query->free_result ();
			return $max_id;
		} else
			return 1;
	}
	function gen_barcode($type_id, $barcode, $i) {
		// дагалдах сэлбэг бол 
		if ($type_id == 1) {
			// толгой буюу эхний			
			$barcode_head = substr ( $barcode, 0, 8 );			
			// сүүлийн хэсгийн
			$barcode_end = substr ( $barcode, 8, strlen ( $barcode ) ); // -1 bj magadgui
			$start = intval ( $barcode_end );			
			$end = $start + $i;
			return $barcode_head . sprintf ( '%06d', $end );
		} else
			return $barcode;
	}
	
	// add new wh_income
	function add_income() {
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$id = $this->main_model->get_maxId ( 'wh_invoice', 'id' );
		// $storeman_id = $this->session->userdata ( 'employee_id' );		
		$income_date = $this->input->get_post ( 'income_date' );
		$income_no = $this->input->get_post ( 'income_no' );

		//$query = $this->db->query ( "SELECT * FROM wm_income WHERE income_no = '$income_no'" );
		if ($id) {
			$invData ['id'] = $id;
			$invData ['invoicetype'] = 'income';
			$invData ['invoicedate'] = $income_date;
			$invData ['actionby_id'] = $this->session->userdata ( 'employee_id' );
			$invData ['actiondate'] = date ( "Y-m-d H:i:s" );
			
			// орлогийг бүртгэх толгой
			$result = $this->db->insert ( 'wh_invoice', $invData );
			if ($result) {
				// actionby_id
				// `income_id` + is inovoice_id;
				$data ['invoice_id'] = $id;
				// `income_no`,
				$data ['income_no'] = $income_no;
				// income_date +
				$data ['income_date'] = $this->input->get_post ( 'income_date' );
				// purpose +
				// get supplier_id +
				$data ['supplier_id'] = $this->input->get_post ( 'supplier_id' );
				// get accountant_id +
				$data ['storeman_id'] =  $this->session->userdata ( 'employee_id' );
				// get storeman_id +
				$data ['isBalance'] = 'N';
				// get order_no odoogoor shiidegdeegui
				$result_2 = $this->db->insert ( 'wh_income', $data );
				// $sql="call insInvIncome($id, '$incomed_no', '$income_date', '$purpose', $supplier_id, $accountant_id, $storeman_id, '$isBalance', $storeman_id)";
				// echo $sql;
				// echo $income_no; IN in_income_date, IN in_purpose , IN in_supplier_id , IN in_accountant_id, IN in_storeman_id, IN in_isBalance, IN in_actionby_id
				// echo $this->db->last_query();				
				if ($result_2) {
					return $id;
				} else {
					return null;
				}
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	function add_income_dtl() {
		$count = $this->input->get_post ( 'count' ); // хэдэн spare байгаа тоо
		$spare = $this->input->get_post ( 'spare' ); // хэдэн spare байгаа тоо
		// invoice-d нэмээд
		$invoice_id = $this->add_income ();
		// $invoice_id = 1;		
		if($invoice_id){
			$data = array ();
			$j = 1;					
			$serial_x = $this->main_model->get_maxId ( 'wh_invoice_dtl', 'serial_x' );			
			// Нийт сэлбэгийн тоогоор давталт хийнэ.
			for($i = 1; $i <= $count; $i ++) {			
				// Тухайн сэлбэгийг хэдэн тавиурт тавьсан тоо
				// tuhain taviur-n dugaar
				$pallet_id = $spare [$i] ['pallet'];							
				$pallet_cnt = sizeof($pallet_id);				
				// echo "pcoount".$pallet_cnt."<br>";
				// echo $i." төхөөрөмж ".$spare[$i]['id']."\n";
				// echo " type_id: ".$spare[$i]['type_id']."\n";
							
				// тухайн тавиурын тоо ширхэг
				$pallet_qty =  $spare [$i] ['pallet_qty'];
				// echo $i."-t pqty:";
				// print_r($pallet_qty);			
				
				$barcode =  $spare [$i] ['barcode'];
				//$this->input->get_post ('spare['.$i.'][pallet_qty][]' );
				
				// too дүн
				$Amt = $spare [$i] ['amt'];
				//$this->input->get_post ('spare['.$i.'][amt]');
				
				// тухайн хэдэн тавиур байгаа тоогоор
				$k = 0;
				// used for barcode 
				$bar_id = 0;
				
				for($n=1; $n <= $pallet_cnt; $n++){
					///echo "\n qty:". $palletQty[$n];
					//echo "N:". $n."<br>";				
					// echo "<br>pallet_qty [$n]:".$pallet_qty [$i];
					// echo "<br>";
					// echo "pqty:".$pallet_qty[$k];
					// тавиурт байгаа сэлбэгийн тоо ширхэг				
					for($l = 1; $l <= $pallet_qty [$k]; $l ++) {
						$sData ['invoice_id'] = $invoice_id;
						$sData ['spare_id'] = $spare[$i]['id'];
						$sData ['pallet_id'] = $pallet_id [$k];					
						// эхлэх баркодууд энд байна		
						$sData ['barcode'] = $this->gen_barcode ( $spare[$i]['type_id'], $barcode, $bar_id++);
						// $sData['barcode']=$barcode[$i];
						// TODO: Serialig olj avah
						// echo "<br>i=:".$i."_n=".$n."serial_=".$l."<br>";
						//echo "<br>";
						if (!empty($_POST[$i . '_' . $n . 'serial' . $l])&&isset($_POST[$i . '_' . $n . 'serial' . $l])){
							$sData ['serial'] = $_POST[$i . '_' . $n . 'serial' . $l];
							$sData ['serial_x'] = $serial_x ++;
							// $sData['serial_x'] = null;
							// $this->input->get_post ( $i . '_' . $n . 'serial' . $l );
						} else {
							$sData ['serial'] = null;
							$sData ['serial_x'] =$serial_x ++;
						}
						$sData ['aqty'] = 1;
						$sData ['amt'] = $Amt;
						
						// echo "<br>";
						//print_r($sData);
						// echo "<br>";
						// echo "<br>serial_:".$this->input->get_post($j.'_'.$k.'serial'.$p);
						array_push ( $data, $sData );
						unset($sData);					
					}
					$k ++;								
				}
				$j ++;
			}							
			// after finished looping 
			// echo "<br>";
			// print_r($data);
			$db_debug = $this->db->db_debug; 
			$this->db->db_debug = FALSE;
			$this->db->insert_batch ( 'wh_invoice_dtl', $data );

			if($this->db->affected_rows() > 0){			   
			   return $invoice_id;
			}else{
			   $this->db->delete('wh_invoice', array('id' => $invoice_id)); 
			   return null;			   
			}
			$this->db->db_debug = $db_debug	;
		}else
			return null;
	}
        
        //Орлого засах фүнкц
    function edit_income() {
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$invoice_id = $this->input->get_post ( 'invoice_id' );		
		$income_date = $this->input->get_post ( 'income_date' );
		$income_no = $this->input->get_post ( 'income_no' );
		// delete invoice by invoice_id
                $this->db->delete('wh_income', array('invoice_id' => $invoice_id));                 
                $this->db->delete('wh_invoice', array('id' => $invoice_id));                 
                $this->db->delete('wh_invoice_dtl', array('invoice_id' => $invoice_id));                 
                // delete invoice_detail
                $id = $this->main_model->get_maxId ( 'wh_invoice', 'id' );
		if ($id) {
			$invData ['id'] = $id;
			$invData ['invoicetype'] = 'income';
			$invData ['invoicedate'] = $income_date;
			$invData ['actionby_id'] = $this->session->userdata ( 'employee_id' );
			$invData ['actiondate'] = date ( "Y-m-d H:i:s" );
			
			// орлогийг бүртгэх толгой
			$result = $this->db->insert ( 'wh_invoice', $invData );
			if ($result) {
				// actionby_id
				// `income_id` + is inovoice_id;
				$data ['invoice_id'] = $id;
				// `income_no`,
				$data ['income_no'] = $income_no;
				// income_date +
				$data ['income_date'] = $this->input->get_post ( 'income_date' );
				// purpose +
				// get supplier_id +
				$data ['supplier_id'] = $this->input->get_post ( 'supplier_id' );
				// get accountant_id +
				$data ['storeman_id'] =  $this->session->userdata ( 'employee_id' );
				// get storeman_id +
				$data ['isBalance'] = 'N';
				// get order_no odoogoor shiidegdeegui
				$result_2 = $this->db->insert ( 'wh_income', $data );
				// $sql="call insInvIncome($id, '$incomed_no', '$income_date', '$purpose', $supplier_id, $accountant_id, $storeman_id, '$isBalance', $storeman_id)";
				// echo $sql;
				// echo $income_no; IN in_income_date, IN in_purpose , IN in_supplier_id , IN in_accountant_id, IN in_storeman_id, IN in_isBalance, IN in_actionby_id
				// echo $this->db->last_query();				
				if ($result_2) {
					return $id;
				} else {
					return null;
				}
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
        
        //орлого засвар хадгалах
	function edit_income_dtl() {
		$count = $this->input->get_post ( 'count' ); // хэдэн spare байгаа тоо
		$spare = $this->input->get_post ( 'spare' ); // хэдэн spare байгаа тоо
		// invoice-d нэмээд
		$invoice_id = $this->edit_income ();
//		echo $invoice_id;		
		if($invoice_id){
                    $data = array ();
                    $j = 1;					
                    $serial_x = $this->main_model->get_maxId ( 'wh_invoice_dtl', 'serial_x' );			
                    // Нийт сэлбэгийн тоогоор давталт хийнэ.
                    for($i = 1; $i <= $count; $i ++) {			
                            // Тухайн сэлбэгийг хэдэн тавиурт тавьсан тоо
                            // tuhain taviur-n dugaar
                            $pallet_id = $spare [$i] ['pallet'];							
                            $pallet_cnt = sizeof($pallet_id);				
                            // echo "pcoount".$pallet_cnt."<br>";
                            // echo $i." төхөөрөмж ".$spare[$i]['id']."\n";
                            // echo " type_id: ".$spare[$i]['type_id']."\n";

                            // тухайн тавиурын тоо ширхэг
                            $pallet_qty =  $spare [$i] ['pallet_qty'];
                            // echo $i."-t pqty:";
                            // print_r($pallet_qty);			

                            $barcode =  $spare [$i] ['barcode'];
                            //$this->input->get_post ('spare['.$i.'][pallet_qty][]' );

                            // too дүн
                            $Amt = $spare [$i] ['amt'];
                            //$this->input->get_post ('spare['.$i.'][amt]');

                            // тухайн хэдэн тавиур байгаа тоогоор
                            $k = 0;
                            // used for barcode 
                            $bar_id = 0;

                            for($n=1; $n <= $pallet_cnt; $n++){
                                    ///echo "\n qty:". $palletQty[$n];
                                    //echo "N:". $n."<br>";				
                                    // echo "<br>pallet_qty [$n]:".$pallet_qty [$i];
                                    // echo "<br>";
                                    // echo "pqty:".$pallet_qty[$k];
                                    // тавиурт байгаа сэлбэгийн тоо ширхэг				
                                    for($l = 1; $l <= $pallet_qty [$k]; $l ++) {
                                            $sData ['invoice_id'] = $invoice_id;
                                            $sData ['spare_id'] = $spare[$i]['id'];
                                            $sData ['pallet_id'] = $pallet_id [$k];					
                                            // эхлэх баркодууд энд байна		
                                            $sData ['barcode'] = $this->gen_barcode ( $spare[$i]['type_id'], $barcode, $bar_id++);
                                            // $sData['barcode']=$barcode[$i];
                                            // TODO: Serialig olj avah
                                            // echo "<br>i=:".$i."_n=".$n."serial_=".$l."<br>";
                                            //echo "<br>";
                                            if (!empty($_POST[$i . '_' . $n . 'serial' . $l])&&isset($_POST[$i . '_' . $n . 'serial' . $l])){
                                                    $sData ['serial'] = $_POST[$i . '_' . $n . 'serial' . $l];
                                                    $sData ['serial_x'] = $serial_x ++;
                                                    // $sData['serial_x'] = null;
                                                    // $this->input->get_post ( $i . '_' . $n . 'serial' . $l );
                                            } else {
                                                    $sData ['serial'] = null;
                                                    $sData ['serial_x'] =$serial_x ++;
                                            }
                                            $sData ['aqty'] = 1;
                                            $sData ['amt'] = $Amt;

                                            // echo "<br>";
                                            //print_r($sData);
                                            // echo "<br>";
                                            // echo "<br>serial_:".$this->input->get_post($j.'_'.$k.'serial'.$p);
                                            array_push ( $data, $sData );
                                            unset($sData);					
                                    }
                                    $k ++;								
                            }
                            $j ++;
                    }							
                    // after finished looping 
                    // echo "<br>";
                    // print_r($data);
                    $db_debug = $this->db->db_debug; 
                    $this->db->db_debug = FALSE;
                    $this->db->insert_batch ( 'wh_invoice_dtl', $data );

                    if($this->db->affected_rows() > 0){			   
                       return $invoice_id;
                    }else{
                       $this->db->delete('wh_invoice', array('id' => $invoice_id)); 
                       return null;			   
                    }
                    $this->db->db_debug = $db_debug	;
		}else
			return null;
	}
	
	// shine add_income 2 turshilt
	function add_income_dtl_2() {
		$count = $this->input->get_post ( 'count' ); // хэдэн spare байгаа тоо
		                                        // invoice-d нэмээд
		//$invoice_id = $this->add_income ();
		$data = array ();
		$j = 1;
		$spareId = $this->input->get_post ( 'spare_id' );
		$barcode = $this->input->get_post ( 'barcode' );
		$type_id = $this->input->get_post ( 'type_id' );
		// print_r($barcode);
		
		$serial_x = $this->main_model->get_maxId ( 'wm_invoicedetail', 'serial_x' );
		// Нийт сэлбэгийн тоогоор
		for($i = 0; $i < $count; $i ++) {
			$pallet_cnt [$i] = $this->input->get_post ( 'p_cnt_' . $j );
			// тухайн сэлбэгийг хэдэн тавиурт тавьсан тоо
			$palletId = $this->input->get_post ( $j . '_pallet_id' );
			$palletQty = $this->input->get_post ( $j . '_palletQty' );
			$Amt = $this->input->get_post ( $j . '_amt' );
			// echo "<br/> p_cnt:$pallet_cnt[$i]";
			$k = 1;
			for($n = 0; $n < $pallet_cnt [$i]; $n ++) {
				// echo "\n qty:". $palletQty[$n];
				$p = 1;
				for($l = 0; $l < $palletQty [$n]; $l ++) {
					$sData ['invoice_id'] = $invoice_id;
					$sData ['spare_id'] = $spareId [$i];
					$sData ['pallet_id'] = $palletId [$n];					
					// эхлэх баркод дээр нэмэх замаар үүсгэнэ.
					$sData ['barcode'] = $this->gen_barcode ( $type_id [$i], $barcode [$i], $l );
					// $sData['barcode']=$barcode[$i];
					
					if ($this->input->get_post ( $j . '_' . $k . 'serial' . $p )) {
						$sData ['serial'] = $this->input->get_post ( $j . '_' . $k . 'serial' . $p );
						$sData ['serial_x'] = $serial_x ++;
					} else {
						$sData ['serial_x'] = $serial_x ++;
					}
					$sData ['aqty'] = 1;
					$sData ['amt'] = $Amt;
					// print_r($sData);
					// echo "<br>serial_:".$this->input->get_post($j.'_'.$k.'serial'.$p);
					array_push ( $data, $sData );
					$p ++;
				}
				$k ++;
			}
			$j ++;
		}
		
		print_r ( $data );
		
		// if ($this->db->insert_batch('wh_invoice_dtl', $data))
		// return $invoice_id;
		// else return false;
	}
	function insIncomeDtl() {
		$spare_cnt = $this->input->get_post ( 'count' ); // хэдэн spare байгаа тоо
		$invoice_id = $this->insIncome ();
		$data = array ();
		$j = 1;
		$spareId = $this->input->get_post ( 'spare_id' );
		$serial_x = $this->main_model->get_maxId ( 'wm_invoicedetail', 'serial_x' );
		for($i = 0; $i < $spare_cnt; $i ++) {
			// $qty[$i]=$this->input->get_post($j.'_qty'); //тухайн сэлбэг хэдэн ширхэг байгаа
			$pallet_cnt [$i] = $this->input->get_post ( 'p_cnt_' . $j ); // тухайн сэлбэгийг хэдэн тавиурт тавьсан тоо
			$palletId = $this->input->get_post ( $j . '_pallet_id' );
			$palletQty = $this->input->get_post ( $j . '_palletQty' );
			// echo "<br/> p_cnt:$pallet_cnt[$i]";
			$k = 1;
			for($n = 0; $n < $pallet_cnt [$i]; $n ++) {
				// echo "\n qty:". $palletQty[$n];
				$p = 1;
				for($l = 0; $l < $palletQty [$n]; $l ++) {
					$sData ['invoice_id'] = $invoice_id;
					$sData ['spare_id'] = $spareId [$i];
					$sData ['pallet_id'] = $palletId [$n];
					if ($this->input->get_post ( $j . '_' . $k . 'serial' . $p )) {
						$sData ['serial'] = $this->input->get_post ( $j . '_' . $k . 'serial' . $p );
						$sData ['serial_x'] = $serial_x ++;
					} else {
						$sData ['serial_x'] = $serial_x ++;
					}
					$sData ['aqty'] = 1;
					// echo "<br>serial_:".$this->input->get_post($j.'_'.$k.'serial'.$p);
					array_push ( $data, $sData );
					$p ++;
				}
				$k ++;
			}
			$j ++;
		}
		$res = $this->db->insert_batch ( 'wm_invoicedetail', $data );
		return $res;
		// echo "data:";
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
	}
	
	// insert Begin balance
	function insBeginbalance() {
		// id, invoicetype, invoicedate, actionby_id, actiondate
		$this->db->trans_begin ();
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$invoice_id = $this->main_model->get_maxId ( 'wm_invoice', 'id' );
		$invData ['id'] = $invoice_id;
		$invData ['invoicedate'] = $this->input->get_post ( 'invoiceDate' );
		$invData ['invoicetype'] = 'isBegin';
		$invData ['actionby_id'] = $this->session->userdata ( 'employee_id' );
		$invData ['actiondate'] = date ( "Y-m-d H:i:s" );
		
		$result = $this->db->insert ( 'wm_invoice', $invData );
		if ($this->db->trans_status () === TRUE) {
			$incData ['invoice_id'] = $invoice_id;
			$incData ['income_no'] = '';
			$incData ['income_date'] = $this->input->get_post ( 'invoiceDate' );
			$incData ['purpose'] = 'Эхний үлдэгдэл';
			$incData ['supplier_id'] = null;
			$incData ['storeman_id'] = $this->session->userdata ( 'employee_id' );
			$incData ['order_no'] = null;
			$incData ['isBalance'] = 'Y';
			$result = $this->db->insert ( 'wm_income', $incData );
			
			$this->db->trans_commit ();
			$spare_cnt = $this->input->get_post ( 'count' ); // хэдэн spare байгаа тоо
			$data = array ();
			$j = 1;
			$spareId = $this->input->get_post ( 'spare_id' );
			$serial_x = $this->main_model->get_maxId ( 'wm_invoicedetail', 'serial_x' );
			for($i = 0; $i < $spare_cnt; $i ++) {
				$pallet_cnt [$i] = $this->input->get_post ( 'p_cnt_' . $j ); // тухайн сэлбэгийг хэдэн тавиурт тавьсан тоо
				$palletId = $this->input->get_post ( $j . '_pallet_id' );
				$palletQty = $this->input->get_post ( $j . '_palletQty' );
				// echo "<br/> p_cnt:$pallet_cnt[$i]";
				$k = 1;
				for($n = 0; $n < $pallet_cnt [$i]; $n ++) {
					// echo "\n qty:". $palletQty[$n];
					$p = 1;
					for($l = 0; $l < $palletQty [$n]; $l ++) {
						$sData ['invoice_id'] = $invoice_id;
						$sData ['spare_id'] = $spareId [$i];
						$sData ['pallet_id'] = $palletId [$n];
						if ($this->input->get_post ( $j . '_' . $k . 'serial' . $p )) {
							$sData ['serial'] = $this->input->get_post ( $j . '_' . $k . 'serial' . $p );
							$sData ['serial_x'] = $serial_x ++;
						} else
							$sData ['serial_x'] = $serial_x ++;
						$sData ['aqty'] = 1;
						// echo "<br>serial_:".$this->input->get_post($j.'_'.$k.'serial'.$p);
						array_push ( $data, $sData );
						$p ++;
					}
					$k ++;
				}
				$j ++;
			} // end for
			$res = $this->db->insert_batch ( 'wm_invoicedetail', $data );
			return $res;
		} else {
			
			$this->db->trans_rollback ();
			return null;
		}
	}
	function getIntendType() {
		$Q_w = $this->db->get ( 'wm_intendtype' );
		if ($Q_w->num_rows () > 0) {
			foreach ( $Q_w->result_array () as $row ) {
				$data [$row ['intendtype_id']] = $row ['intendName'];
			}
		}
		$Q_w->free_result ();
		return $data;
	}
	function tblSparelist() {
		$this->table->set_heading ( '#', 'Захиалгийн #', 'Огноо', 'Хэсэг', 'Жагсаалт гаргасан', 'Хүлээн эвсан' );
		
		$result = $this->db->get ( 'wm_view_sparelist' )->result ();
		
		foreach ( $result as $row ) {
			$this->table->add_row ( "<input type='checkbox' name='sparelist_id' value='$row->id'/>", $row->orderpage_no, $row->ordered_date, $row->section, $row->createdby, $row->recievedby );
		}
		$tmpl = array (
				'table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="wm_table">',
				
				'heading_row_start' => '<tr>',
				'heading_row_end' => '</tr>',
				'heading_cell_start' => '<th>',
				'heading_cell_end' => '</th>',
				
				'row_start' => '<tr>',
				'row_end' => '</tr>',
				'cell_start' => '<td>',
				'cell_end' => '</td>',
				
				'row_alt_start' => '<tr>',
				'row_alt_end' => '</tr>',
				'cell_alt_start' => '<td>',
				'cell_alt_end' => '</td>',
				
				'table_close' => '</table>' 
		);
		
		$this->table->set_template ( $tmpl );
		
		$table = $this->table->generate ();
		return $table;
	}
	
	// register Order
	function makeOrder() {
		
		date_default_timezone_set ( 'Asia/Ulan_Bator' );

		$count = $this->input->get_post ( 'count' );

		if ($count != 0 || $count != null) {

			$order_no = $this->input->get_post ( 'order_no' );

			$orderby_id = $this->input->get_post ( 'employee_id' );

			$order_date = $this->input->get_post ( 'order_date' );
			
			$order_type = $this->input->get_post ( 'order_type' );

			$spare = $this->input->get_post ( 'spare' );

			$qty = $this->input->get_post ( 'qty' );

			$reason = $this->input->get_post ( 'reason' );

			$measure = $this->input->get_post ( 'measure' );
			$order_id = $this->main_model->get_maxId ( 'wm_order', 'order_id' );

			$status_id = $this->main_model->get_row ( 'status_id', array (				
					'status' => 'new'
			), 'wm_view_orderstatus' );
			
			// insert into order
			$data = array (
					'order_no' => $order_no,
					'order_id' => $order_id, // call getMax_id
					'orderby_id' => $orderby_id,
					'steward_id' => $this->session->userdata ( 'employee_id' ),
					'order_date' => $order_date,
					'order_type' => $order_type,
					'status_id' => $status_id,
					'section_id' => $this->input->get_post ( 'section_id' ),
					'registed_date' => date ( "Y-m-d" ) 
			);
			// print_r($data);
			
			if ($this->db->insert ( 'wm_order', $data ))
				$this->session->set_userdata ( 'message', 'Захиалгийг амжилттай хадгаллаа.' );
			else
				$this->session->set_userdata ( 'message', 'Захиалгийг хадгалахад алдаа гарлаа!' );
			
			$i = 0;
			$j = 1;
			do {
				// insert detail to order_list
				$dataDtl = array (
						'order_id' => $order_id,
						'order' => $j,
						'spare' => $spare [$i],
						'measure' => $measure [$i],
						'qty' => $qty [$i],
						'reason' => $reason [$i] 
				);
				if ($this->db->insert ( 'wm_orderdetail', $dataDtl ))
					$flag_ins = 1;
				else
					$flag_ins = 0;
				$i ++;
				$j ++;
			} while ( $i < ($count) );
			if ($flag_ins == 1)
				return true;
			else
				return false;
		} else {
			return false;
		}
	}
	
	// энэ захиалгийн дугаар бүртгэгдсэн эсэхийг шалгах
	function is_orderno_set() {
		
		$order_no = $this->input->get_post ( 'order_no' );
		
		$order_date = $this->input->get_post ( 'order_date' );

		$old_order_no = $this->main_model->get_row ( 'order_no', 
			array (
				'order_no' => $order_no, 
				
				'order_date' => $order_date 

			), 'wm_order' );
		
		if ($old_order_no) {
			return TRUE;
		} else
			return FALSE;
	}
	function orderList() {
		// $this->db->where('role', $role);
		// Хэрэв $role-р утга байвал үйлдлийг харуулна. эсрэг тохиолдолд үгүй.
		// $query=$this->db->get_where('wm_view_order', array('role'=>$role));
		// if($query->num_rows()>0){
		$result = $this->db->get ( 'wm_view_order' )->result ();
		// }else {
		// $result=$this->db->select
		// }
		return $result;
	}
	
	// Захиалгийн үйлдлүүдийг дуудах
	function orderAction($role) {
		$result = $this->db->get_where ( 'wm_orderaction', array (
				'role' => $role 
		) )->result ();
		return $result;
	}
	function get_serial_today() {
		date_default_timezone_set ( ECNS_TIMEZONE );
		$today = date ( "Ymd", time () );
		// where substr(today)
		$query = $this->db->query ( "SELECT serial_number FROM wm_invincomedetail 
                                    WHERE SUBSTRING(serial_number, 1,8) ='$today'
                                ORDER BY serial_number ASC LIMIT 1" );
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row )
				$today_max_serial = $row->serial_number;
			if (isset ( $today_max_serial )) {
				$serial_cnt = ( int ) substr ( $today_max_serial, 8, 0 );
				$serial_cnt ++;
			}
		} else {
			$serial_cnt = 0;
			$serial_cnt ++;
		}
		return $serial_cnt;
	} // end get serial today
	function getTemplist() {
		// $data['section']=$this->main_model->get_industry();
		// $data['section']=$this->main_model->get_industry();
		$query = $this->db->get ( 'wm_temp_list' );
		if ($query->num_rows () > 0) {
			$data ['result'] = $query->result ();
			$temp_query = $this->db->query ( "SELECT section_id FROM wm_temp_list limit 1" );
			$temp_row = $temp_query->row_array ();
			$temp_data = array ();
			if ($temp_row)
				$temp_section_id = $temp_row ['section_id'];
			$temp_section = $this->main_model->get_row ( 'name', array (
					'section_id' => $temp_section_id 
			), 'section' );
			$temp_data [$temp_section_id] = $temp_section;
			$data ['section'] = $temp_data;
		} else {
			$data ['section'] = $this->main_model->get_industry ();
		}
		$query->free_result ();
		return $data;
	} // end templist

	function insert($data, $table) {
		$this->db->insert ( $table, $data );
		return $this->db->insert_id ();
	}

	function get_max_id($table, $id) {
		$query = $this->db->query ( "SELECT $id FROM $table Order by $id desc limit 1" );
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$max_id = $row [$id];
			$query->free_result ();
			return $max_id + 1;
		} else
			return 1;
	}

	function insert_trans($table, $data){
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$this->db->insert($table, $data); # Inserting data
		# Updating data		
		$this->db->trans_complete(); # Completing transaction

		/*Optional*/
		if ($this->db->trans_status() === FALSE) {
		    # Something went wrong.
		    $this->db->trans_rollback();
		    return FALSE;
		} 
		else {
		    # Everything is Perfect. 
		    # Committing data to the database.
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	function add_expense($invoice_id){
		$count = $this->input->get_post ( 'count' ); 
		$spare_id = $this->input->get_post ( 'spare_id' );

		$j = 1;
		$data = array();
		//print_r($this->input->get_post ( $j . '_dtl_id' ));
		// хэдэн spare байгаа тоо
		for($i = 0; $i < $count; $i ++) {		    
			$detail = $this->input->get_post ( $j . '_dtl_id' );			
			$j ++;
			$l = 0;
			// echo sizeof($detail );
			// echo "----------";
			// print_r($detail);
			while($l < sizeof( $detail )){
				$subData ['invoice_id'] = $invoice_id;
				$subData ['spare_id'] = $spare_id [$i];				
				$row = $this->get_rows(array ('id' => $detail[$l]), 'wh_invoice_dtl');
				$subData['serial'] = $row['serial'];
				$subData['serial_x'] = $row['serial_x'];
				$subData['pallet_id'] = $row['pallet_id'];
				$subData['barcode'] = $row['barcode'];
				$subData ['aqty'] = - 1;
				$subData['amt'] = -$row['amt'];

				//print_r($subData);
				$l ++;

				array_push ( $data, $subData );
			}
		}

		// insert invoiceDetail
		// invoice_id, spare_id, pallet_id, serial, aqty
		//expense
		$exp_data['invoice_id'] = $invoice_id;
		$exp_data['expense_no'] = $this->input->post ( 'expense_no' );
		$exp_data['expense_date'] = $this->input->post ( 'expense_date' );
		$exp_data['section_id'] = $this->input->post ( 'section_id' );
		$exp_data['sector_id'] = $this->input->post ( 'sector_id' );
		$exp_data['intend'] = $this->input->post ( 'intend' );   
		$exp_data['recievedby_id'] = $this->input->post ( 'recievedby_id' );   
			
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
		$this->db->insert_batch ( 'wh_invoice_dtl', $data );

		$this->db->insert( 'wh_expense', $exp_data );
		# Updating data		
		$this->db->trans_complete(); # Completing transaction

		/*Optional*/
		if ($this->db->trans_status() === FALSE) {
		    # Something went wrong.
		    $this->db->trans_rollback();
		    //expense delete
		    $this->db->delete('wh_invoice', array('id' => $invoice_id)); 
		    //wh_expense delete
		    //$this->db->delete('wh_expense')
		    return FALSE;
		} 
		else {
		    # Everything is Perfect. 
		    # Committing data to the database.
		    $this->db->trans_commit();
		    return TRUE;
		}

	}

	function get_rows($where, $table) {				
                $this->db->select('*');
		$this->db->where( $where );
		$query = $this->db->get ( $table );		
		$row = $query->row_array ();
		return $row;
	}


	function last_query(){
		return $this->db->last_query();
	}

	function field_equipment($equipment_id = null) {
		$this->db->select ( 'sp_id, equipment' );
		$select = '';
		if (isset ( $equipment_id ) && $equipment_id != null)
			$this->db->where ( array (
					'sp_id' => $equipment_id 
			) );
		else
			$select .= "<option>Сонго...</option>";
		
		$query = $this->db->get ( 'equipment2' );
		$cols = $query->result ();
		// $select = "<select name='sector_id'>";
		$select = "<span id='equipment'><select name='equipment_id' id='equipment_id' data-placeholder='Сонго...'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->sp_id'>";
				$select .= $row->equipment;
				$select .= "</option>";
			}
		}
		$select .= "</select></span>";
		return $select;
	}


	function get_end_qty($spare_id){
		
		$query =  $this->db->query ("SELECT * FROM wm_endqty");		
		
		if ($query->num_rows () > 0) {
			
			$row = $query->row_array ();
			
			$qty = $row ['EndQty'];
		}else

			$qty = 0;

		return $qty;
	}



	
}
?>
