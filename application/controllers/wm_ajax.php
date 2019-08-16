<?php
/*
 * This controller used to warehouse Ajax in warehouse
 */
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class wm_ajax extends CNS_Controller {

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

	public function __construct() {
		parent::__construct ();
		
		$this->load->model ( 'main_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'wm_model' );
		$this->load->model ( 'wm_order_model' );	
	}
	
	// WAREHOUSE AJAX FUNCTIONS
	function getEquipment() {
		$section_id = $this->input->get_post ( 'section_id' );
		$this->db->select ( 'equipment_id, name' );
		$this->db->from ( 'equipment' );
		$this->db->where ( 'section_id', $section_id );
		$query = $this->db->get ();
		echo "<select name='equipment_id' id='equipment_id' width=200px>";
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				echo "<option value='$row->equipment_id'>";
				echo $row->name;
				echo "</option>";
			}
		}
		echo "</select>";
		$query->free_result ();
	}
	function getSpare() {
		$equipment_id = $_GET ['equipment_id'];
		if (isset ( $_GET ['id'] ))
			$sparetype_id = $_GET ['id'];
		$this->db->select ( '*' );
		$this->db->from ( 'wm_view_spare' );
		if (isset ( $sparetype_id ))
			$this->db->where ( 'sparetype_id', $sparetype_id );
		$this->db->where ( 'equipment_id', $equipment_id );
		$query = $this->db->get ();
		// echo $this->db->last_query($query);
		$rows = $query->result ();
		
		echo "<select name='spare_id' id='spare_id' width=200px>";
		if ($query->num_rows () > 0)
			foreach ( $rows as $row ) {
				echo "<option value='$row->spare_id'>";
				echo $row->spare;
				echo "</option>";
			}
		else {
			echo "<option value='0'>";
			echo "Сэлбэг байхгүй";
			echo "</option>";
		}
		echo "</select>";
	}
	function getOrderItem() {
		$order_id = $_GET ['order_id'];
		$this->db->select ( '*' );
		$this->db->where ( 'order_id', $order_id );
		$order_result = $this->db->get ( 'view_wm_orderItem' )->result ();
		$employee = $this->main_model->get_employee ( 'SUP' );
		
		echo "<table class='wm_table'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Сонго</th>";
		echo "<th>#</th>";
		echo "<th>Төхөөрөмж</th>";
		echo "<th>Сэлбэг/Материал</th>";
		echo "<th>Part No</th>";
		echo "<th>Тоо хэмжээ</th>";
		echo "<th>Ширхэг</th>";
		echo "<th>Захиалах болсон шаардлага</th>";
		echo "<th>Хангамжийн Инженер</th>";
		echo "<th>Үйлдэл</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach ( $order_result as $row ) {
			echo "<tr onclick='set_checkbox(document.orderItem.pk_id, $row->pk_id );'>";
			echo "<td><input type='checkbox' name='pk_id' value='$row->pk_id'/></td>";
			echo "<td>" . $row->order_id . "</td>";
			echo "<td>" . $row->equipment . "</td>";
			echo "<td>" . $row->item_name . "</td>";
			echo "<td>" . $row->part_number . "</td>";
			echo "<td>" . $row->quantity . "</td>";
			echo "<td>" . $row->measure . "</td>";
			echo "<td>" . $row->order_reason . "</td>";
			echo "<td>";
			if (isset ( $row->steward_id ))
				echo $row->steward;
			else
				echo form_dropdown ( 'employee_id' . $row->pk_id, $employee, 0, "id='employee_id$row->pk_id'" );
			echo "</td>";
			echo "<td>";
			if (isset ( $row->steward_id ))
				echo "<a href='/ecns/warehouse/orderTask/$row->pk_id/cancel'>Цуцлах</a>";
			else
				echo "<a href='#' onclick='orderTask($row->pk_id);'/>Үүрэг оноох</a>";
			echo "</td>";
		}
		echo "</tbody>";
		echo "</table>";
	}
	function getDetail() {
		$spare_id = $_GET ['spare_id'];
		$this->db->select ( '*' );
		$this->db->where ( 'spare_id', $spare_id );
		$result = $this->db->get ( 'wm_view_containerdetail' )->result ();
		
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='wm_table'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>#</th>";
		echo "<th>Агуулах</th>";
		echo "<th>Тавиур</th>";
		echo "<th>Тоо хэмжээ</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		$count = 1;
		foreach ( $result as $row ) {
			echo "<tr>";
			echo "<td>";
			echo $count;
			echo "</td>";
			echo "<td>";
			echo $row->warehouse;
			echo "</td>";
			echo "<td>";
			echo $row->pallet;
			echo "</td>";
			echo "<td>";
			echo $row->qty;
			echo "</td>";
			echo "</tr>";
			$count ++;
		}
		echo "</tbody>";
		echo "</table>";
	}
	function getPallet() {
		$warehouse_id = $_GET ['warehouse_id'];
		$idcnt = $_GET ['idcnt'];
		$spare_cnt = $_GET ['spare_cnt'];
		
		$this->db->select ( 'pallet_id, pallet_code' );
		$this->db->from ( 'wm_view_pallet' );
		$this->db->where ( 'warehouse_id', $warehouse_id );
		$query = $this->db->get ();
		$id = $spare_cnt . "_pallet" . $idcnt;
		//changed idcnt
		$name = 'spare['.$spare_cnt."][pallet][]";
		//$name = $spare_cnt . "_pallet_id[]";
		//spare[0]['pallet'][]
		
		echo "<select name='$name' id='$id' class='czn-select'>";
		$i = 1;
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				if ($i == $idcnt)
					echo "<option value='$row->pallet_id' selected>";
				else
					echo "<option value='$row->pallet_id'>";
				echo $row->pallet_code;
				echo "</option>";
				$i ++;
			}
		}
		echo "</select>";
		$query->free_result ();
	}
	function getBalanceDetail() {
		$ct_id = $_GET ['ct_id'];
		$this->db->select ( '*' );
		$this->db->from ( 'wm_view_balancedetail' );
		$this->db->where ( 'ct_id', $ct_id );
		$query = $this->db->get ();
		
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='wm_table'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>#</th>";
		echo "<th>Агуулах</th>";
		echo "<th>Тавиур</th>";
		echo "<th>Тавиур дээрх тоо/ш</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		$count = 1;
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				echo "<tr>";
				echo "<td>";
				echo $count ++;
				echo "</td>";
				echo "<td>";
				echo $row->warehouse;
				echo "</td>";
				echo "<td>";
				echo $row->pallet;
				echo "</td>";
				echo "<td>";
				echo $row->qty;
				echo "</td>";
				echo "</tr>";
			}
		}
		echo "</tbody>";
		echo "</table>";
		$query->free_result ();
	}
	
	// function used warehouse management
	function palletSerial() {
		// ali taviur
		$pallet_id = $_GET ['pallet_id'];
		// hed dehi selbeg
		$spare_cnt = $_GET ['spare_cnt'];
		$pallet = $this->main_model->get_row ( 'pallet', array (
				'pallet_id' => $pallet_id 
		), "wm_pallet" );
		// too hemjee
		$qty = $_GET ['qty'];
		echo "[$pallet] тавиур дээрх сэлбэгийн сериал дугаарыг оруулна уу!";
		echo "<table cellpadding='0' cellspacing='0' style='font-size:10pt;'>";
		$j = 1;
		for($i = 1; $i <= $qty; $i ++) {
			$serial = $spare_cnt . "_" . $pallet_id . 'serial' . $i;
			// $serial_name=$spare_cnt."_".$pallet_id.'serial[]';
			echo "<tr>";
			echo "<td>";
			echo "<label>$spare_cnt -р сэлбэгийн <br> $pallet_id-р тавиур дээрх $i сериал:</label>";
			echo "</td>";
			echo "<td>";
			echo "<input type='text' name='$serial' id='$serial' style='width:140px;' class='input_serial' maxlength=20/>";
			echo "</td>";
			$j++;
			/*
			 * echo "<td>";
			 * echo "<label>Парт дугаар:</label>";
			 * echo "</td>";
			 * echo "<td>";
			 * echo "<input type='text' name='$part' id='$part' />";
			 * echo "</td>";
			 *
			 */
		}
		echo "</table>";
	}
	// spareDetail
	function getSpareDetial() {
		$spare_id = $_GET ['spare_id'];
		// тухайн сэлбэгээр агуулахын тавиурт байгаа сэлбэг регистерүүдийг харуулна!
		$qry = "SELECT *
                FROM wm_invoice A
                JOIN (SELECT * FROM wm_invoicedetail WHERE serial_x not in 
                        (SELECT serial_x FROM wm_invoicedetail where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                WHERE invoicedate <=curdate()";
		
		$query = $this->db->query ( $qry );
		$cnt = 1;
		$rowId = 1;
		if ($query->num_rows () > 0) {
			echo "<table width='300' align ='right' cellpadding='0' cellspacing ='0'>";
			echo "<tr>";
			echo "<th>";
			echo "#";
			echo "</th>";
			echo "<th>";
			echo "№";
			echo "</th>";
			echo "<th>";
			echo "Тавиур";
			echo "</th>";
			echo "<th>";
			echo "Сериал дугаар";
			echo "</th>";
			echo "</tr>";
			foreach ( $query->result () as $row ) {
				echo "<tr>";
				echo "<td>";
				echo "<input id='check_$rowId' type='checkbox' name='spare_pk[]' value='$row->id' onclick='checkit(this)'/>";
				echo "</td>";
				echo "<td>";
				echo $cnt ++;
				$rowId = $cnt;
				echo "</td>";
				echo "<td>";
				echo $row->pallet;
				echo "</td>";
				echo "<td>";
				echo $row->serial;
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
			$query->free_result ();
		} else {
			echo "<p style='font-style:italic; color:red'>Агуулахад тус сэлбэг байхгүй байна!</p>";
			echo "<input type='hidden' name='sparelist' value='' id='sparelist'/>";
		}
	}
	function getSerial() {
		$qty = $_GET ['qty'];
		$choose = $_GET ['choose'];
		$pallet_result = $this->db->get ( 'wm_pallet' )->result ();
		if ($choose == 'user') { // Сериал Номер оруулах
			for($i = 0; $i < $qty; $i ++) {
				$cnt = $i;
				$part = ++ $cnt;
				echo "<div><label>$part-р сэлбэгийн сериал №:</label>";
				echo "<input name ='serial_number$cnt' style='width:120px' id='serial_number$cnt'/>";
				echo "<label>Парт номер №:</label>";
				echo "<input name='part_number$cnt' style=''>";
				echo "<span> Тавиур:</span>";
				if ($cnt == 1) {
					echo "<select name='pallet1' id='pallet1' onchange='syncPallets()'>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				} else {
					echo "<select name='pallet$cnt' id='pallet$cnt'>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				}
				echo "</div>";
			}
		} elseif ($choose == 'auto') { // Автоматаар өгөх
			date_default_timezone_set ( ECNS_TIMEZONE );
			$today = date ( "Ymd", time () );
			$serial_cnt = $this->wm_model->get_serial_today ();
			
			for($i = $serial_cnt; $i <= $qty; $i ++) {
				$serial = $today . $i;
				echo "<div><label>$i-р сэлбэгийн сериал №:</label>";
				echo "<input name ='serial_number$i' style='width:120px' value=$serial id='serial_number$i' />";
				echo "<span> Тавиур:</span>";
				if ($i == 1) {
					echo "<select name='pallet1' id='pallet1' onchange='syncPallets()' value =$i>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				} else {
					echo "<select name='pallet$i' id='pallet$i'>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				}
			}
		} else { // Сериал номер шаардлагаггүй
			for($i = 0; $i < $qty; $i ++) {
				$cnt = $i;
				$part = ++ $cnt;
				echo "<div>";
				echo "<label> $cnt -р Тавиур:</label>";
				if ($cnt == 1) {
					echo "<select name='pallet1' id='pallet1' onchange='syncPallets()'>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				} else {
					echo "<select name='pallet$cnt' id='pallet$cnt'>";
					foreach ( $pallet_result as $row ) {
						echo "<option value=" . $row->pallet_id . ">";
						echo $row->code;
						echo "</option>";
					}
					echo "</select>";
				}
				echo "</div>";
			}
		}
	} // end get function
	function fSpare() {
		$id = $_POST ['equipment_id'];
		$json_arr = array ();
		$data = array ();
		// query your DataBase here looking for a match to $input
		$this->db->select ( 'spare_id, spare' );
		$this->db->where ( array (
				'equipment_id' => $id 
		) );
		$query = $this->db->get ( 'wm_view_spare' );
		
		foreach ( $query->result () as $row ) {
			$data [$row->spare_id] = $row->spare;
			$json_arr = $data;
		}
		header ( 'Content-type: application/json;' );
		echo json_encode ( $json_arr );
	}
	function getEqty() {
		$json_arr = array ();
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$spare_id = $_POST ["spare_id"];
		$order_date = substr ( $_POST ["order_date"], 1, 4 );
		$query = $this->db->get_where ( 'wm_view_contains', array (
				'spare_id' => $spare_id,
				'year(date)' => $order_date 
		) );
		$row = $query->row_array ();
		$query->free_result ();
		
		if ($row)
			$data ['eqty'] = $row ["eqty"];
		else
			$data ['eqty'] = 0;
		
		$this_year = date ( "Y" );
		$query = $this->db->get_where ( 'wm_restrecord', array (
				'spare_id' => $spare_id,
				'year' => $this_year 
		) );
		$row = $query->row_array ();
		$query->free_result ();
		if ($row) {
			$data ['uqty'] = $row ["uqty"];
			$data ["nqty"] = $row ["nqty"];
		} else {
			$data ["nqty"] = 0;
			$data ['uqty'] = 0;
		}
		$json_arr = $data;
		header ( 'Content-type: application/json;' );
		echo json_encode ( $json_arr );
	}
	function getRestSpare() {
		$json_arr = array ();
		$data = array ();
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		// $this_year = date("Y");
		$query = $this->db->get_where ( 'wm_view_restspare', array (
				'spare_id' => $spare_id 
		) );
		$row = $query->row_array ();
		$query->free_result ();
		if ($row) {
			$data ['spare'] = $row ["spare"];
			$data ['part_number'] = $row ["part_number"];
			$data ['year'] = $row ["year"];
			$data ['date'] = $row ["date"];
			$data ['usingQty'] = $row ["usingQty"];
			$data ["needQty"] = $row ["needQty"];
			$data ["launchyear"] = $row ["launchyear"];
			$data ["usingYear"] = $row ["usingYear"];
		}
		$json_arr = $data;
		header ( 'Content-type: application/json;' );
		echo json_encode ( $json_arr );
	}
	
	// using Filter, Search Plugins
	function getEquipments() {
		// equipments
		$section_id = $this->input->get_post ( 'section_id' );
		$sector_id = $this->input->get_post ( 'sector_id' );
		
		// $sector_id=$_GET['sector_id'];
		$json_arr = array ();
		
		$data = array ();
		$this->db->select ( 'equipment_id, name' );
		// filter by section_id
		if ($section_id)
			$this->db->where ( array (
					'section_id' => $section_id 
			) );
		if ($sector_id)
			$this->db->where ( array (
					'sector_id' => $sector_id 
			) );
		
		$data [0] = 'Төхөөрөмжүүд';
		$query = $this->db->get ( 'equipment' );
		
		foreach ( $query->result () as $row ) {
			$data [$row->equipment_id] = $row->name;
			$json_arr = $data;
		}
		$query->free_result ();
		header ( 'Content-type: application/json;' );
		echo json_encode ( $json_arr );
	}
	// using for Search, Filter plugins
	function getSector() {
		$section_id = $_POST ['section_id'];
		$this->db->select ( 'sector_id, name' );
		$this->db->from ( 'sector' );
		$this->db->where ( 'section_id', $section_id );
		$query = $this->db->get ();
		
		$json_arr = array ();
		$data = array ();
		$data [0] = 'Бүх тасаг';
		
		foreach ( $query->result () as $row ) {
			$data [$row->sector_id] = $row->name;
			$json_arr = $data;
		}
		$query->free_result ();
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $json_arr );
	}
	function getSection() {
		$this->db->select ( 'section_id, name' );
		$this->db->from ( 'section' );
		$query = $this->db->get ();
		$json_arr = array ();
		$data = array ();
		$data [0] = 'Бүх хэсэг';
		
		foreach ( $query->result () as $row ) {
			$data [$row->sector_id] = $row->name;
			$json_arr = $data;
		}
		$query->free_result ();
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $json_arr );
	}
	function getEmployee() {
		$section_id = $_POST ['section_id'];
		$this->db->select ( 'employee_id, fullname, position' );
		$this->db->from ( 'view_employee' );
		$this->db->where ( 'section_id', $section_id );
		$query = $this->db->get ();
		$json_arr = array ();
		$data = array ();
		foreach ( $query->result () as $row ) {
			$data [$row->employee_id] = $row->fullname . "-" . $row->position;
			$json_arr = $data;
		}
		$query->free_result ();
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $json_arr );
	}
	
	// aguulahiin tavuir deerh selbegiin medeelliig butsaah function
	function palletInfo() {
		// pallet-n
		$json = array ();
		$data = array ();
		$pallet = $this->input->get_post ( 'pallet_name' );
		// $this->db->select('*');
		// $this->db->where('SUBSTR(pallet, 1, 4)=', $pallet);
		$query = $this->db->query ( "SELECT * FROM wm_view_endqty_pallet A left join wm_view_spare B ON A.spare_id = B.spare_id
                              WHERE SUBSTR(pallet, 1, 4)='$pallet'
                              order by A.pallet" );
		// echo $pallet;
		foreach ( $query->result_array () as $row ) {
			$data ['equipment'] = $row ['equipment'];
			$data ['spare'] = $row ['spare'];
			$data ['pallet'] = $row ['pallet'];
			$data ['qty'] = $row ['qty'];
			$data ['measure'] = $row ['measure'];
			array_push ( $json, $data );
		}
		
		header ( 'Content-type: application/json; charset=utf-8' );
		print json_encode ( $json );
	}
	
	// return order Actions
	function orderAction() {
		$json = array ();
		$data = array ();
		$order_id = $this->input->get_post ( 'order_id' );
		$this->db->select ( '*' );
		$this->db->where ( 'order_id', $order_id );
		$query = $this->db->get ( 'wm_view_orderact' );
		foreach ( $query->result_array () as $row ) {
			$data ['status_id'] = $row ['status_id'];
			$data ['status'] = $row ['name'];
			$data ['value'] = $row ['value'];
			$data ['role'] = $row ['role'];
			array_push ( $json, $data );
		}
		header ( 'Content-type: application/json; charset=utf-8' );
		print json_encode ( $json );
	}
	function getWhereClause($col, $oper, $val) {
		// global $ops;
		if ($oper == 'bw' || $oper == 'bn')
			$val .= '%';
		if ($oper == 'ew' || $oper == 'en')
			$val = '%' . $val;
		if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')
			$val = '%' . $val . '%';
		return " WHERE $col {$this->ops[$oper]} '$val' ";
	}
	
	// container for grid
	function invoice() {
		$page = $this->input->get ( 'page', TRUE );
		$limit = $this->input->get ( 'rows', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$sord = $this->input->get ( 'sord', TRUE );
		$spare_id = $this->input->get ( 'spare_id', true );
		if (isset ( $spare_id ) && $spare_id)
			$where = "spare_id = $spare_id";
		
		if (! $sidx)
			$sidx = 1;
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		if ($_GET ['_search'] == 'true') {
			if (isset ( $_GET ['section_id'] )) {
				$section_id = ($this->input->get ( 'section_id', true ) != 0) ? $this->input->get ( 'section_id', true ) : null;
				$sector_id = ($this->input->get ( 'sector_id', true ) != 0) ? $this->input->get ( 'sector_id', true ) : null;
				$equipment_id = ($this->input->get ( 'equipment_id', true ) != 0) ? $this->input->get ( 'equipment_id', true ) : null;
				$where = $this->getWhereIds ( $section_id, $sector_id, $equipment_id );
			} else {
				$where = $this->getWhereClause ( $searchField, $searchOper, $searchString );
			}
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
		} else if (isset ( $spare_id ) && $spare_id) {
			$where = " WHERE spare_id =$spare_id";
		}
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		// the actual query for the grid data
		if ($_GET ['_search'] == 'true') {
			$cnt_Sql = "SELECT count(*) as count FROM wm_view_ext_invoice $where ";
		} else {
			$cnt_Sql = "SELECT count(*) as count FROM wm_view_ext_invoice";
			// $SQL = "call proc_warehouse('$data', '$data', null, null, null, '$sidx' , '$sord' , $start , $limit)";
		}
		
		$query = $this->db->query ( $cnt_Sql );
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
			$SQL = "SELECT * FROM wm_view_ext_invoice $where ORDER BY $sidx $sord LIMIT $start , $limit";
		} else {
			$SQL = "SELECT * FROM wm_view_ext_invoice ORDER BY $sidx $sord LIMIT $start , $limit";
		}
		$Qry = $this->db->query ( $SQL );
		// we should set the appropriate header information
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		// echo "<sql>".$last_qry."</sql>";
		// echo "<count>".$count."</count>";
		// echo "<starts>".$start."</starts>";
		// echo "<limit>".$limit."</limit>";
		echo "<page>" . $page . "</page>";
		echo "<total>" . $total_pages . "</total>";
		echo "<records>" . $count . "</records>";
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->spare_id . "'>";
			echo "<cell>" . $row->spare_id . "</cell>";
			echo "<cell>" . $row->enddate . "</cell>";
			echo "<cell><![CDATA[" . $row->spare . "]]></cell>";
			echo "<cell><![CDATA[" . $row->sparetype . "]]></cell>";
			echo "<cell>" . $row->part_number . "</cell>";
			echo "<cell>" . $row->measure . "</cell>";
			echo "<cell>" . $row->endQty . "</cell>";
			echo "<cell><![CDATA[" . $row->equipment . "]]></cell>";
			echo "<cell>" . $row->sector . "</cell>";
			echo "<cell>" . $row->section . "</cell>";
			echo "</row>";
		}
		echo "</rows>";
		$Qry->free_result ();
		// echo $where;
	}
	
	// subgrid
	function invDetail() {
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data = date ( "Y-m-d" );
		$id = $this->input->get ( 'id' ); // get the invoice data passed to this request via params array in
		                               // subGridModel. We do not use it here - this is only demostration
		                               // $date_inv = $_GET['invdate'];
		                               // connect to the database
		                               // $db = mysql_connect('localhost', 'root', '') or die("Connection Error: " . mysql_error()); mysql_select_db('northwind') or die("Error conecting to db.");
		                               // construct the query
		$sord = $this->input->get ( 'sord', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		if (! $sord)
			$sord = 'asc';
		$result = $this->db->query ( "CALL proc_invoiceDtl($id, '$data', '$sidx', '$sord');" )->result ();
		// $result =$this->db->query("SELECT * FROM wm_view_endqty_pallet WHERE spare_id =$id ORDER BY '$sidx', '$sord'")->result();
		
		// set the header information
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		// be sure to put text data in CDATA
		foreach ( $result as $row ) {
			echo "<row>";
			echo "<cell>" . $row->id . "</cell>";
			echo "<cell><![CDATA[" . $row->warehouse . "]]></cell>";
			echo "<cell>" . $row->pallet . "</cell>";
			echo "<cell>" . $row->qty . "</cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	function setWhereClause($col, $oper, $val) {
		// global $ops;
		if ($oper == 'bw' || $oper == 'bn')
			$val .= '%';
		if ($oper == 'ew' || $oper == 'en')
			$val = '%' . $val;
		if ($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni')
			$val = '%' . $val . '%';
		return array (
				$col,
				$this->ops [$oper],
				$val 
		);
	}
	function showList($col, $ope, $val) {
		list ( $column, $operator, $value ) = setWhereClause ( $col, $oper, $val );
		echo "col:" . $column;
		echo "</br>";
		echo "oper:" . $operator;
		echo "</br>";
		echo "value:" . $value;
		echo "</br>";
	}
	
	// grid used for incgrid view
	function income() {
		$spare_id = $this->input->get ( 'spare_id', true );
		$page = $this->input->get ( 'page', TRUE );
		$limit = $this->input->get ( 'rows', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$sord = $this->input->get ( 'sord', TRUE );
		
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
		$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
		$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
		if ($_GET ['_search'] == 'true') {
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
			$where = $this->getWhereClause ( $searchField, $searchOper, $searchString );
		} else if (isset ( $spare_id ) && $spare_id) {
			$where = " WHERE spare_ids like '%$spare_id%'";
		}
		
		if (! $sidx)
			$sidx = 1;
		$Sqlstr = "SELECT COUNT(*) AS count FROM wm_view_income";
		$query = $this->db->query ( $Sqlstr ); // $row = mysql_fetch_array($result,MYSQL_ASSOC);
		
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
		if ($_GET ['_search'] == 'true' || isset ( $spare_id ))
			$SQL = "SELECT A.*, spare_ids FROM wm_view_income A
               LEFT JOIN wm_view_invDtlspare B ON A.invoice_id = B.invoice_id $where
               ORDER BY $sidx $sord LIMIT $start , $limit";
		else
			$SQL = "SELECT * FROM wm_view_income ORDER BY $sidx $sord LIMIT $start , $limit";
		$Qry = $this->db->query ( $SQL );
		// we should set the appropriate header information
		// echo $SQL;
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		/*
		 * header("Content-type: application/json;charset=utf-8");
		 * $i=0;
		 * foreach($Qry->result() as $row) {
		 * $json['rows'][$i]['id']=$row->spare_id;
		 * $json['rows'][$i]['cell']=array($row->spare, $row->part_number, $row->sparetype, $row->equipment, $row->section, $row->sector);
		 * $i++;
		 * }
		 * print json_encode($json);
		 */
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		echo "<page>" . $page . "</page>";
		echo "<total>" . $total_pages . "</total>";
		echo "<records>" . $count . "</records>";
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->invoice_id . "'>";
			echo "<cell>" . $row->income_no . "</cell>";
			echo "<cell>" . $row->income_date . "</cell>";
			echo "<cell>" . $row->purpose . "</cell>";
			echo "<cell>" . $row->supplier . "</cell>";
			echo "<cell>" . $row->accountant . "</cell>";
			echo "<cell><![CDATA[" . $row->storeman . "]]></cell>";
			echo "<cell><![CDATA[" . $row->isbalance . "]]></cell>";
			echo "<cell>action</cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	
	// subgrid for incexp view
	function incomeDtl() {
		$invoice_id = $this->input->get ( 'id' );
		$sord = $this->input->get ( 'sord', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$result = $this->db->query ( "CALL proc_incomeDtl($invoice_id, '$sidx','$sord');" )->result ();
		// set the header information
		// header("Content-type: application/json;charset=utf-8");
		// $json =array();
		// be sure to put text data in CDATA
		/*
		 * foreach($result as $row) {
		 * $json['rows']=array("income_no"=>$row->income_no,
		 * "income_date"=>$row->incomed_date,
		 * "qty"=>$row->qty,
		 * "supplier"=>$row->supplier,
		 * "accountant"=>$row->accountant,
		 * "storeman"=>$row->storeman);
		 * }
		 * print json_encode($json);
		 */
		// if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
		// header("Content-type: application/xhtml+xml;charset=utf-8"); }
		// else {
		header ( "Content-type:text/xml;charset=utf-8" );
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		// be sure to put text data in CDATA
		$count = 1;
		foreach ( $result as $row ) {
			echo "<row>";
			echo "<cell>" . $row->id . "</cell>";
			echo "<cell><![CDATA[" . $row->spare . "]]></cell>";
			echo "<cell>" . $row->measure . "</cell>";
			echo "<cell>" . $row->qty . "</cell>";
			echo "</row>";
		}
		echo "</rows>";
		$count ++;
	}
	
	// grid used for expgrid view
	function expense() {
		$spare_id = $this->input->get ( 'spare_id', true );
		$page = $this->input->get ( 'page', TRUE );
		$limit = $this->input->get ( 'rows', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$sord = $this->input->get ( 'sord', TRUE );
		$where = "";
		if ($_GET ['_search'] == 'true') {
			$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
			$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
			$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
			$where = $this->getWhereClause ( $searchField, $searchOper, $searchString );
		} else if (isset ( $spare_id ) && $spare_id) {
			$where = " WHERE spare_ids like '%$spare_id%'";
		}
		
		if (! $sidx)
			$sidx = 1;
		$csql = "SELECT COUNT(*) AS count FROM wm_view_expense";
		// if(isset($spare_id)&&$spare_id!=0) $csql=$csql." WHERE spare_id =$spare_id";
		$query = $this->db->query ( $csql ); // $row = mysql_fetch_array($result,MYSQL_ASSOC);
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
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		if ($_GET ['_search'] == 'true' || isset ( $spare_id ))
			$SQL = "SELECT A.*, spare_ids FROM wm_view_expense A
                  LEFT JOIN wm_view_invDtlspare B ON A.invoice_id = B.invoice_id $where
                ORDER BY $sidx $sord LIMIT $start , $limit";
		else
			$SQL = "SELECT * FROM wm_view_expense ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$Qry = $this->db->query ( $SQL );
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
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->invoice_id . "'>";
			echo "<cell>" . $row->expense_no . "</cell>";
			echo "<cell>" . $row->expense_date . "</cell>";
			echo "<cell>" . $row->intend . "</cell>";
			echo "<cell>" . $row->section . "</cell>";
			echo "<cell>" . $row->storeman . "</cell>";
			echo "<cell><![CDATA[" . $row->receiveby . "]]></cell>";
			echo "<cell><![CDATA[" . $row->checkby . "]]></cell>";
			echo "<cell>action</cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	
	// subgrid for expgrid view
	function expenseDtl() {
		$sord = $this->input->get ( 'sord', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$id = $this->input->get ( 'id' );
		$result = $this->db->query ( "CALL proc_incomeDtl($id, '$sidx','$sord');" )->result ();
		
		// set the header information
		header ( "Content-type: application/json;charset=utf-8" );
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		// be sure to put text data in CDATA
		$count = 1;
		foreach ( $result as $row ) {
			echo "<row>";
			echo "<cell>" . $row->id . "</cell>";
			echo "<cell><![CDATA[" . $row->spare . "]]></cell>";
			echo "<cell>" . $row->measure . "</cell>";
			echo "<cell>" . $row->qty . "</cell>";
			
			echo "</row>";
		}
		echo "</rows>";
		$count ++;
	}
	function restspare() {
		$spare_id = $this->input->get ( 'spare_id', true );
		$section_id = $this->input->get ( 'section_id', true );
		$sector_id = $this->input->get ( 'sector_id', true );
		$equipment_id = $this->input->get ( 'equipment_id', true );
		
		$page = $this->input->get_post ( 'page', TRUE );
		$limit = $this->input->get_post ( 'rows', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$sord = $this->input->get ( 'sord', TRUE );
		$where = ""; // if there is no search request sent by jqgrid, $where should be empty
		
		if ($this->input->get ( '_search', true ) == 'true') {
			if (isset ( $_GET ['section_id'] )) {
				$section_id = ($this->input->get ( 'section_id', true ) != 0) ? $this->input->get ( 'section_id', true ) : null;
				$sector_id = ($this->input->get ( 'sector_id', true ) != 0) ? $this->input->get ( 'sector_id', true ) : null;
				$equipment_id = ($this->input->get ( 'equipment_id', true ) != 0) ? $this->input->get ( 'equipment_id', true ) : null;
				$where = $this->getWhereIds ( $section_id, $sector_id, $equipment_id );
			} else {
				$searchField = isset ( $_GET ['searchField'] ) ? $_GET ['searchField'] : null;
				$searchOper = isset ( $_GET ['searchOper'] ) ? $_GET ['searchOper'] : null;
				$searchString = isset ( $_GET ['searchString'] ) ? $_GET ['searchString'] : null;
				$where = $this->getWhereClause ( $searchField, $searchOper, $searchString );
			}
			// list($searchField, $searchOper, $searchString) =$this->setWhereClause($searchField,$searchOper,$searchString);
		} else if (isset ( $spare_id ) && $spare_id) {
			$where = " WHERE spare_id =$spare_id";
		}
		if (! $sidx) {
			$sidx = 1;
		}
		if ($this->input->get ( '_search', true ) == 'true') {
			$csql = "SELECT COUNT(*) AS count FROM wm_view_restspare $where ";
		} else {
			$csql = "SELECT COUNT(*) AS count FROM wm_view_restspare";
		}
		// if(isset($spare_id)&&$spare_id!=0) $csql=$csql." WHERE spare_id =$spare_id";
		$query = $this->db->query ( $csql ); // $row = mysql_fetch_array($result,MYSQL_ASSOC);
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$count = $row ['count'];
		}
		// calculate the total pages for the query
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages) {
			$page = $total_pages;
		}
		
		$start = $limit * $page - $limit;
		if ($start < 0) {
			$start = 0;
		}
		// ! Хайлтыг шийдэх
		if ($_GET ['_search'] == 'true') {
			$SQL = "SELECT * FROM wm_view_restspare $where ORDER BY $sidx $sord LIMIT $start , $limit";
		} else {
			$SQL = "SELECT * FROM wm_view_restspare ORDER BY $sidx $sord LIMIT $start , $limit";
		}
		
		$Qry = $this->db->query ( $SQL );
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		$last_sql = $this->db->last_query ();
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		echo "<page>" . $page . "</page>";
		echo "<total>" . $total_pages . "</total>";
		echo "<limit>" . $limit . "</limit>";
		echo "<records>" . $count . "</records>";
		echo "<sql>" . $last_sql . "</sql>";
		
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->spare_id . "'>";
			echo "<cell>" . $row->year . "</cell>";
			echo "<cell>" . $row->section . "</cell>";
			echo "<cell>" . $row->sector . "</cell>";
			echo "<cell>" . $row->equipment . "</cell>";
			echo "<cell>" . $row->spare . "</cell>";
			// echo "<cell>". $row->launchyear."</cell>";
			// echo "<cell>". $row->usingYear."</cell>";
			echo "<cell>" . $row->usingQty . "</cell>";
			echo "<cell>" . $row->needQty . "</cell>";
			echo "<cell>" . $row->beginQty . "</cell>";
			echo "<cell>" . $row->endQty . "</cell>";
			echo "<cell><![CDATA[" . $row->recordby . "]]></cell>";
			echo "<cell><![CDATA[" . $row->action . "]]></cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	private function getWhereIds($section_id, $sector_id, $equipment_id) {
		$where = ' WHERE ';
		if (isset ( $section_id ))
			$where .= "section_id =$section_id ";
		if (isset ( $sector_id )) {
			if (strlen ( $where ) > 7)
				$where .= "and sector_id =$sector_id ";
			else
				$where .= "sector_id =$sector_id ";
		}
		if (isset ( $equipment_id )) {
			if (strlen ( $where ) > 7)
				$where .= "and equipment_id=$equipment_id ";
			else
				$where .= "equipment_id =$equipment_id ";
		}
		return $where;
	}
	function insrest() {
		$spare_id = $this->input->get_post ( 'spare_id' );
		$regdate = $this->input->get_post ( 'regdate' );
		$launchYear = $this->input->get_post ( 'launchYear' );
		$usingYear = $this->input->get_post ( 'usingYear' );
		$usingQty = $this->input->get_post ( 'usingQty' );
		$needQty = $this->input->get_post ( 'needQty' );
		// wm_restspare
		$data ['spare_id'] = $spare_id;
		$data ['year'] = substr ( $regdate, 0, 4 );
		$data ['date'] = $regdate;
		$data ['usingQty'] = $usingQty;
		$data ['needQty'] = $needQty;
		$data ['launchYear'] = $launchYear;
		$data ['usingYear'] = $usingYear;
		$data ['recordby_id'] = $this->session->userdata ( 'employee_id' );
		
		$this->db->insert ( 'wm_restspare', $data );
		/*
		 * echo $usingQty; echo "<br>";
		 * echo $needQty; echo "<br>";
		 *
		 */
	}
	function uRest() {
		$spare_id = $this->input->get_post ( 'spare_id' );
		$regdate = $this->input->get_post ( 'regdate' );
		$launchYear = $this->input->get_post ( 'launchYear' );
		$usingYear = $this->input->get_post ( 'usingYear' );
		$usingQty = $this->input->get_post ( 'usingQty' );
		$needQty = $this->input->get_post ( 'needQty' );
		// wm_restspare
		$data ['year'] = substr ( $regdate, 0, 4 );
		$data ['date'] = $regdate;
		$data ['usingQty'] = $usingQty;
		$data ['needQty'] = $needQty;
		$data ['launchYear'] = $launchYear;
		$data ['usingYear'] = $usingYear;
		$data ['recordby_id'] = $this->session->userdata ( 'employee_id' );
		
		$this->db->where ( 'spare_id', $spare_id );
		$this->db->update ( 'wm_restspare', $data );
		/*
		 * echo $usingQty; echo "<br>";
		 * echo $needQty; echo "<br>";
		 */
	}
	function delRest() {
		$spare_id = $this->input->get_post ( 'spare_id' );
		$this->db->delete ( 'wm_restspare', array (
				'spare_id' => $spare_id 
		) );
	}
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
	function order() {
		$page = $this->input->get ( 'page', TRUE );
		$limit = $this->input->get ( 'rows', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		$sord = $this->input->get ( 'sord', TRUE );
		$role = $this->session->userdata ( 'role' );
		$sec_code = $this->session->userdata ( 'sec_code' );
		$spare = $this->input->get ( 'spare' );
		
		$filters = $this->input->get_post ( 'filters' );
		$search = $this->input->get_post ( '_search' );
		
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
		$query = $this->db->query ( "SELECT COUNT(*) AS count FROM wm_view_order" );
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
			$SQL = "SELECT A.*, B.spares FROM wm_view_order A 
                    LEFT JOIN (SELECT order_id, GROUP_CONCAT(DISTINCT spare) as spares
                    FROM wm_orderdetail
                    GROUP BY order_id) B ON A.order_id = B.order_id
                $where ORDER BY $sidx $sord LIMIT $start , $limit";
		else
			$SQL = "SELECT * FROM wm_view_order ORDER BY $sidx $sord LIMIT $start , $limit";
		$Qry = $this->db->query ( $SQL );
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
		// // be sure to put text data in CDATA
		foreach ( $Qry->result () as $row ) {
			echo "<row id='" . $row->order_id . "'>";
			echo "<cell>" . $row->order_no . "</cell>";
			echo "<cell>" . $row->order_date . "</cell>";
			echo "<cell>" . $row->section . "</cell>";
			echo "<cell>" . $row->orderby . "</cell>";
			echo "<cell>" . $row->registed_date . "</cell>";
			echo "<cell>" . $row->comment . "</cell>";
			echo "<cell>" . $row->status . "</cell>";
			echo "<cell><![CDATA[" . $row->steward . "]]></cell>";
			if ($sec_code == 'SUP' && $role == 'CHIEF')
				echo "<cell><![CDATA[SUPCHIEF]]></cell>";
			else
				echo "<cell><![CDATA[" . $role . "]]></cell>";
			echo "<cell>" . $row->status_id . "</cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	function orderDtl() {
		$order_id = $this->input->get ( 'id' );
		$sord = $this->input->get ( 'sord', TRUE );
		$sidx = $this->input->get ( 'sidx', TRUE );
		
		$result = $this->db->query ( "SELECT * FROM wm_orderdetail WHERE order_id =$order_id ORDER BY $sidx $sord " )->result ();
		
		if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
			header ( "Content-type: application/xhtml+xml;charset=utf-8" );
		} else {
			header ( "Content-type: text/xml;charset=utf-8" );
		}
		echo "<?xml version='1.0' encoding='utf-8'?>";
		echo "<rows>";
		// be sure to put text data in CDATA
		foreach ( $result as $row ) {
			echo "<row>";
			echo "<cell>" . $row->order . "</cell>";
			echo "<cell><![CDATA[" . $row->spare . "]]></cell>";
			echo "<cell><![CDATA[" . $row->measure . "]]></cell>";
			echo "<cell>" . $row->qty . "</cell>";
			echo "<cell><![CDATA[" . $row->reason . "]]></cell>";
			echo "</row>";
		}
		echo "</rows>";
	}
	function orderSet($action) {
		date_default_timezone_set ( ECNS_TIMEZONE );
		
		$data = array ();
		$order_id = $this->input->get_post ( 'order_id' );
		$status_id = $this->main_model->get_row ( 'id', array (
				'name' => $action 
		), 'wm_orderaction' );

		echo $action;
		echo $status_id;

		// echo $this->db->last_query();

		// echo $action;
		$data ['status_id'] = $status_id;

		// add the order_id
		$order = $this->wm_order_model->get($order_id);
		//order_id
		//print_r($order);
			
		switch ($action) {
			// temdeglel hiih ued
			case 'comment' :
				$data ['comment'] = $this->input->get_post ( 'comment' );
				break;
			// if action 'cancel' coming
			case 'cancel' :
				$data ['comment'] = $order->comment."\r\n"."-".$this->input->get_post ( 'comment' );
				// $data ['comment'] = $this->input->get_post ( 'comment' );
				$data['status_id']= $status_id;
				break;
			// if register action coming
			case 'register' :
				$data ['comment'] = $order->comment."\r\n"."-".$this->input->get_post ( 'comment' );
				$data ['steward_id'] = $this->input->get_post ( 'steward_id' );
				// $data['order_no']=$this->input->get_post('order_no');
				// $data['registed_date']=$this->input->get_post('regdate');
				// $data['steward_id']=$this->input->get_post('steward_id');
				// $data['chief_id']=$this->session->userdata('employee_id');
				break;
			// default action here
			case 'finish' :
				$data['status_id']= $status_id;
				break;
			default :
				$data ['prove_date'] = date ( "Y-m-d", time () );
				$data ['chiefeng_id'] = $this->session->userdata ( 'employee_id' );
				break;
		}
		$this->db->where ( 'order_id', $order_id );
		$result = $this->db->update ( 'wm_order', $data );
		echo $result;
	}
	
	// Insert order Zahialg-n burtgelees hiine
	function InsertOrder() {
		$this->load->library ( 'form_validation' );
		$section_id = $this->input->post ( 'section_id' );
		$order_date = $this->input->post ( 'order_date' );
		$order_no = $this->input->post ( 'order_no' );
		$count = $this->input->post ( 'count' );
		
		$this->form_validation->set_rules ( 'order_no', 'Захиалгын дугаар', 'required' );
		$this->form_validation->set_rules ( 'section_id', 'Захиалсан хэсэг', 'required|is_natural_no_zero' );
		$this->form_validation->set_rules ( 'order_date', 'Захиалгын огноо', 'required' );
		$this->form_validation->set_rules ( 'count', 'Сэлбэг', 'required|is_natural_no_zero' );
		
		$this->form_validation->set_message ( 'is_natural_no_zero', ' "%s" -н утга шаардлагатай. Утга сонгоно уу?' );
		
		if ($this->form_validation->run () != FALSE) {
			if ($this->wm_model->makeOrder () == TRUE) {
				$return = array (
						'status' => 'success',
						'message' => '<strong>Сайн байна уу!</strong> төхөөрөмж дээр шинэ гэмтэл бүртгэл нээлээ!' 
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
		$data ['json'] = json_encode ( $return );
		$order = ( object ) $data;
		echo $order->json;
	}
	// order Delete
	function orderDelete() {
		$order_id = $this->input->get_post ( 'order_id' );
		$query = $this->db->get_where ( 'wm_order', array (
				'order_id' => $order_id 
		) );
		$row = $query->row_array ();
		if ($row)
			$order_no = $row ["order_no"];
		
		$this->db->delete ( 'wm_order', array (
				'order_id' => $order_id 
		) );
		if ($this->db->affected_rows () > 0)
			echo $order_no;
		else
			echo 0;
	}
	function getorderNo() {
		$order_no = $this->main_model->get_maxId ( 'wm_order', 'order_no' );
		$json ['order_no'] = $order_no;
		header ( 'Content-type: application/json; charset=utf-8' );
		print json_encode ( $json );
	}
	function chkSection() {
		$section_id = $this->input->get_post ( 'section_id' );
		
		if ($this->wm_model->getSectionCode ( $section_id ) == 'ATC')
			$return = array (
					'status' => 'success' 
			);
		
		else
			$return = array (
					'status' => 'failed' 
			);
		
		echo json_encode ( $return );
	}

	function set_status(){
	   	
	}
} // end main
?>