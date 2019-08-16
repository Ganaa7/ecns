<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class main_model extends CI_Model {

	function __construct() {

		// Call the Model constructor

		$this->load->database ();

		$this->load->helper ( 'array' );

	}
	
	// get alert
	function get_severity() {

		$this->db->where ( 'settings', 'severity' );

		$data [0] = '';

		$q_result = $this->db->get ( 'settings' );

		if ($q_result->num_rows () > 0) {

			foreach ( $q_result->result_array () as $row )

				$data [$row ['value']] = $row ['value'] . ' - ' . $row ['name'];

		}

		$q_result->free_result ();

		return $data;

	}

	function get_sev_level() {

		$this->db->where ( 'settings', 'sev_level' );

		$data [0] = '';

		$q_result = $this->db->get ( 'settings' );

		if ($q_result->num_rows () > 0) {

			foreach ( $q_result->result_array () as $row )

				$data [$row ['value']] = $row ['value'] . ' - ' . $row ['name'];

		}

		$q_result->free_result ();

		return $data;

	}
	
	// created ashiglagdana
	function get_section($all = null) {

		if ($all == 'Y') {

			$data [0] = 'Хэсгүүдийг сонгоно уу!';

			$q_result = $this->db->get ( 'section' );

			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['section_id']] = $row ['name'];

			} else

				$data [1] = 'Тасаг байхгүй';

		} else {
			
			$data [0] = 'Бүх хэсгүүд';
			
			$q_result = $this->db->get ( 'section' );
			
			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['code']] = $row ['name'];
			}
		}

		$q_result->free_result ();

		return $data;

	}

	function get_industry($code = null) {

		if (isset ( $code )) {

			$data ['ALL'] = 'Бүх хэсэг';

			$q_result = $this->db->get ( 'view_industry' );

			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['sec_code']] = $row ['name'];

			}

		} else {

			$data [0] = 'Бүх хэсэг';

			$q_result = $this->db->get ( 'view_industry' );

			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['section_id']] = $row ['name'];

			}

		}

		$q_result->free_result ();

		return $data;

	}

	function _plugin_section($section_id = null) {

		if (is_null ( $section_id )) {

			$data [0] = 'Бүх хэсэг';

			$q_result = $this->db->get ( 'view_industry' );

			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['section_id']] = $row ['name'];

			}

		} else {

			$this->db->where ( 'section_id', $section_id );

			$q_result = $this->db->get ( 'view_industry' );

			if ($q_result->num_rows () > 0) {

				foreach ( $q_result->result_array () as $row )

					$data [$row ['section_id']] = $row ['name'];

			}

		}

		$q_result->free_result ();

		return $data;

	}

	
	function get_shift_section($code = null) {
		$data [0] = 'Бүх хэсгүүд';
		$q_result = $this->db->get ( 'view_shift_section' );
		if ($q_result->num_rows () > 0) {
			foreach ( $q_result->result_array () as $row ) {
				if (isset ( $code ))
					$data [$row ['sec_code']] = $row ['name'];
				else
					$data [$row ['section_id']] = $row ['name'];
			}
		}
		$q_result->free_result ();
		return $data;
	}
	
	// created ashiglagdana
	function get_sectionby_code($sec_code, $id = null) {
		$data [0] = "Хэсгийг сонгоно уу!";
		$this->db->where ( 'code', $sec_code );
		$q_result = $this->db->get ( 'section' );
		if ($id == 'N') {
			if ($q_result->num_rows () > 0) {
				foreach ( $q_result->result_array () as $row )
					$data [$row ['code']] = $row ['name'];
			} else
				$data [1] = 'Хэсэг байхгүй';
		} else {
			if ($q_result->num_rows () > 0) {
				foreach ( $q_result->result_array () as $row )
					$data [$row ['section_id']] = $row ['name'];
			} else
				$data [1] = 'Хэсэг байхгүй';
		}
		$q_result->free_result ();
		return $data;
	}
	
	// info-г эндээс авч болно.
	function get_sector($sec_code = null) {
		$data [0] = "Тасгийг сонгоно уу!";
		if (isset ( $sec_code )) {
			$this->db->where ( 'sec_code', $sec_code );
		}
		$Q_l = $this->db->get ( 'view_sector' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['sector_id']] = $row ['name'];
			}
		} else
			$data [777] = "Тасаг байхгүй!";
		$Q_l->free_result ();
		return $data;
	}
	
	// info-г эндээс авч болно.
	function getSector($section_id = null) {
		$data [0] = "Бүх тасаг";
		if (isset ( $section_id )) {
			$this->db->where ( 'section_id', $section_id );
		}
		$Q_l = $this->db->get ( 'view_sector' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['sector_id']] = $row ['name'];
			}
		} else
			$data [777] = "Тасаг байхгүй!";
		$Q_l->free_result ();
		return $data;
	}
	
	// position-г эндээс авч болно.
	function get_position($priority = null) {
		$data [0] = "Албан тушаалыг сонгоно уу!";
		if (isset ( $priority ))
			$this->db->where ( 'priority', $priority );
		$Q_l = $this->db->get ( 'position' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['position_id']] = $row ['name'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	
	// төхөөрөмж
	function get_equipments($sec_code) {
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Бүх төхөөрөмж';
		$this->db->where ( 'sec_code', $sec_code );
		
		$Q_e = $this->db->get ( 'equipment' );
		if ($Q_e->num_rows () > 0) {
			foreach ( $Q_e->result_array () as $row ) {
				$data [$row ['equipment_id']] = $row ['name'];
			}
		}
		$Q_e->free_result ();
		return $data;
	}
	function getEquipment($section_id = null) {
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Төхөөрөмжүүд';
		$this->db->select ( 'equipment_id, name' );
		$this->db->from ( 'equipment' );
		if (isset ( $section_id ))
			$this->db->where ( 'section_id', $section_id );
		$this->db->order_by ( 'section_id', 'asc' );
		$Q_e = $this->db->get ();
		if ($Q_e->num_rows () > 0) {
			foreach ( $Q_e->result_array () as $row ) {
				$data [$row ['equipment_id']] = $row ['name'];
			}
		}
		$Q_e->free_result ();
		return $data;
	}
	
	// log plugin
	function get_equipment_lp($section_id = null) {
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Төхөөрөмжүүд';
		$this->db->select ( 'equipment_id, name' );
		$this->db->from ( 'equipment' );                
		if ($section_id)
			$this->db->where ( 'section_id', $section_id );
		$this->db->order_by ( 'section_id', 'asc' );
		$Q_e = $this->db->get ();
		if ($Q_e->num_rows () > 0) {
			foreach ( $Q_e->result_array () as $row ) {
				$data [$row ['equipment_id']] = $row ['name'];
			}
		}
		$Q_e->free_result ();
		return $data;
	}
	
	// get locations
	function get_locations() {
		$data [0] = 'Байршилыг сонгоно уу!';
		$Q_l = $this->db->get ( 'location' );
		if ($Q_l->num_rows () > 0) {
			foreach ( $Q_l->result_array () as $row ) {
				$data [$row ['location_id']] = $row ['name'];
			}
		}
		$Q_l->free_result ();
		return $data;
	}
	
	// get employees
	function get_employee($sec_code) {
		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'SUR' || $sec_code == 'ELC')
			$data [0] = 'ИТА-г сонгоно уу!';
		else
			$data [0] = 'Ажилтаныг сонгоно уу!';
		$this->db->where ( 'sec_code', $sec_code );
		$query = $this->db->get ( 'view_employee' );
		if ($query->num_rows () > 0) {
			foreach ( $query->result_array () as $row ) {
				$data [$row ['employee_id']] = $row ['first_name'] . '-' . $row ['position'];
			}
		}
		$query->free_result ();
		return $data;
	}
	
	// get role
	function get_role() {
		$data [0] = "Нэмэлт эрхийг сонгоно уу!";
		$Q_r = $this->db->get ( 'role' );
		if ($Q_r->num_rows () > 0) {
			foreach ( $Q_r->result_array () as $row ) {
				$data [$row ['role_code']] = $row ['name'];
			}
		}
		$Q_r->free_result ();
		return $data;
	}
	function update($table, $where, $data) {
		$this->db->where ( $where, element ( $where, $data ) );
		$result = $this->db->update ( $table, $data );
		return $result;
	}
	
	// өгсөн утгаар мөрийн утгыг авах фүнкц
	function get_row($column, $where_arr, $table) {
		$this->db->select ( $column );
		$this->db->where ( $where_arr );
		$query = $this->db->get ( $table );
		$row = $query->row_array ();
		if ($row)
			return $row [$column];
		else
			return null;
	}
	
	// тухайн системд тухайн үүрэгтэнд фүнкц ашиглах эрхтэй эсэхийг шалгана
	function get_authority($system, $controller, $function, $role) {
		$arr_vals = array (
				'apps' => $system,
				'controller' => $controller,
				'functions' => $function,
				$role => 'Y' 
		);
		$sys_function = $this->main_model->get_row ( 'functions', $arr_vals, 'role_insystem' );
		return $sys_function;
	}
	
	// тухайн table-с where нөхцөлөөр утгуудыг авах.
	function get_values($table, $where, $value) {
		$this->db->where ( $where, $value );
		$query = $this->db->get ( $table );
		$result = $query->result ();
		$query->free_result ();
		return $result;
	}
	function deleteby_id($table, $where, $value) {
		$this->db->where ( $where, $value );
		return $this->db->delete ( $table );
	}
	function getCountry() {
		$data [0] = 'Нэг Улсыг сонго?';
		$Q_c = $this->db->get ( 'country' );
		if ($Q_c->num_rows () > 0) {
			foreach ( $Q_c->result_array () as $row ) {
				$data [$row ['country_id']] = $row ['country'];
			}
		}
		$Q_c->free_result ();
		return $data;
	}
	
	// WAREHOUSE ITEMS
	function getItem($itemtype_id, $section_id) {
		$data [0] = 'Барааны төрлийг сонгоно уу!';
		$this->db->select ( 'item_id, item_name' );
		$this->db->from ( 'wm_view_item' );
		$this->db->where ( 'itemtype_id', $itemtype_id );
		if (isset ( $section_id ))
			$this->db->where ( 'section_id', $section_id );
		$Query = $this->db->get ();
		if ($Query->num_rows () > 0) {
			foreach ( $Query->result_array () as $row ) {
				$data [$row ['item_id']] = $row ['item_name'];
			}
		}
		$Query->free_result ();
		return $data;
	}
	function get_tableby_order($table, $sort_by, $order_by, $limit, $offset) {
		$this->db->select ( '*' );
		$this->db->from ( $table );
		$this->db->order_by ( $sort_by, $order_by );
		$this->db->limit ( $limit, $offset );
		$query = $this->db->get ();
		return $query->result ();
	}
	function set_view($main_table, $sec_code) {
		switch ($sec_code) {
			case 'COM' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'SUR' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'NAV' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'ELC' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'ENG' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'GOV' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'SUP' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'SEC' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			
			case 'LAB' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			
			default :
				$table = $main_table;
				break;
		}
		return $table;
	}
	function set_view_industry($main_table, $sec_code) {
		switch ($sec_code) {
			case 'COM' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'SUR' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'NAV' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			case 'ELC' :
				$table = $main_table . '_' . strtolower ( $sec_code );
				break;
			
			default :
				$table = $main_table;
		}
		return $table;
	}
	function access_check() {
		$access = $this->session->userdata ( 'access' );
		if (! (isset ( $access ) && $access == 'OK'))
			exit ( "<html><head><meta charset='utf-8'></head><body><p>Шууд хандахыг зөвшөөрөхгүй тул дахин <a href='".base_url()."'>энд дарж</a> нэвтрэнэ үү!!!</p></body></html>" );		
	}
	function get_maxId($table, $id) {
		$query = $this->db->query ( "SELECT $id FROM $table Order by $id desc limit 1" );
		if ($query->num_rows () > 0) {
			$row = $query->row_array ();
			$max_id = $row [$id];
			$query->free_result ();
			return $max_id + 1;
		} else
			return 1;
	}
	function access_role($controller, $role) {
		$where = array (
				'controller' => $controller,
				$role => 'Y' 
		);
		$table = 'role_insystem';
		$role = $this->get_row ( $role, $where, $table );
		
		if ($role != 'Y') {
			return 0;
		} else
			return 1;
	}
	function check_byrole($controller, $role) {
		$role = $this->access_role ( $controller, $role );
		$data ['page'] = '43.html';
		$data ['title'] = 'Алдаа #43';
		$url = $_SERVER ['HTTP_HOST'];
		$main_url = substr ( $url, 0, strpos ( $url, "ecns" ) );
		$base_url = $main_url . '/';
		
		if ($role != 1) {
			exit ( "<!DOCTYPE html>
               <html>
                  <head>
                     <title>Алдаа 43-Хандалтын алдаа</title>
                     <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                  </head>
                  <body>
                     <h2>Алдаа #43</h2>
                     <div>Ингэж хандаад хэрэггүй!!!<br/>Та энэ фүнкцийг хэрэглэх эрхгүй байна!!!<br/>
                        Системийн АДМИН-с асууна уу!</div>
                     <a href='$base_url'>Буцах</a>
                  </body>
               </html>" );
		}
	}
	
	// get Employee
	function getEmployee($section_id = null) {
		if (isset ( $section_id ))
			$this->db->where ( 'section_id', $section_id );
		$this->db->order_by ( 'fullname', 'asc' );
		$query = $this->db->get ( 'view_employee' );
		
		if ($query->num_rows () > 0) {
			foreach ( $query->result_array () as $row ) {
				$data [$row ['employee_id']] = $row ['fullname'] . '-' . $row ['section'] . '-' . $row ['position'];
			}
		}
		$query->free_result ();
		return $data;
	}
	function setSystems($employee_id) {
		// system insert methods
		// хэрэв shiftlog, eventlog, warehouse songogdson bval
		// if() хэрэв shiftlog утгатай байвал insert хийнэ.
		$indata = array ();
		$indata [] = $this->input->get_post ( "shiftlog" );
		$indata [] = $this->input->get_post ( "eventlog" );
		$indata [] = $this->input->get_post ( "warehouse" );
		$sysdata = array ();
		$sysdata [0] ['name'] = 'shiftlog';
		$sysdata [1] ['name'] = 'eventlog';
		$sysdata [2] ['name'] = 'warehouse';
		
		// print_r($indata);
		foreach ( $sysdata as $key => $value ) {
			if ($sysdata [$key] ['name'] == $indata [$key])
				$sysdata [$key] ['action'] = 'ins';
			else
				$sysdata [$key] ['action'] = 'del';
		}
		
		foreach ( $sysdata as $key => $value ) {
			$system = $sysdata [$key] ['name'];
			$qry = "SELECT * FROM user_systems A
                LEFT JOIN systems B ON A.system_id =B.system_id
                where A.employee_id = $employee_id and sys_code='$system'";
			$query = $this->db->query ( $qry );
			
			if ($query->num_rows () > 0) {
				if ($sysdata [$key] ['action'] == 'del') {
					$this->db->query ( "DELETE A FROM user_systems A
                                    LEFT JOIN systems B ON A.system_id =B.system_id
                                    WHERE A.employee_id =$employee_id and sys_code='$system'" );
				}
			} else {
				if ($sysdata [$key] ['action'] == 'ins') {
					$this->db->query ( "INSERT INTO user_systems(employee_id, system_id)
                                    SELECT $employee_id, system_id FROM systems WHERE sys_code='$system'" );
				}
			}
			$query->free_result ();
		}
	}
	function eventCalendar() {
		$js = "";
		
		return $js;
	}
	function equip_section($section_id = null) {
		if (isset ( $section_id ))
			$query = $this->db->query ( "SELECT * FROM section where section_id = $section_id
                UNION
               SELECT * FROM section where section_id != $section_id" );
		else
			$query = $this->db->query ( "SELECT * FROM section " );
		$cols = $query->result ();
		// $select = "<select name='sector_id'>";
		$select = "<select name='section_id' class='chosen-select' data-placeholder='Сонго...' onchange='getEquipment(this.value)'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->section_id'>";
				$select .= $row->name;
				$select .= "</option>";
			}
		}
		$select .= "</select>";
		return $select;
	}
	function sel_section($section_id = null) {
		if (isset ( $section_id ))
			$query = $this->db->query ( "SELECT * FROM section where section_id != $section_id" );
		else
			$query = $this->db->query ( "SELECT * FROM section " );
		
		$cols = $query->result ();
		$qry = $this->db->get_where ( 'section', array (
				'section_id' => $section_id 
		) );
		
		if ($qry->num_rows () > 0) {
			$row = $qry->row ();
			$section_name = $row->name;
		} else
			$section_name = 'Бүгд';
		
		$select = "<select name='section_id' class='chosen-select' data-placeholder='Сонго...' onchange='showSector(this.value)'>";
		if ($query->num_rows () > 0) {
			$select .= "<option value='$section_id'>$section_name</option>";
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->section_id'>";
				$select .= $row->name;
				$select .= "</option>";
			}
		}
		$select .= "</select>";
		return $select;
	}
	function sel_sector($sector_id) {
		if (! isset ( $sector_id ))
			$query = $this->db->query ( "SELECT * FROM sector " );
		else
			$query = $this->db->query ( "SELECT * FROM sector where sector_id = $sector_id" );
		$cols = $query->result ();
		// $select = "<select name='sector_id'>";
		$select = "<span id='txtHint'><select name='sector_id' class='chosen-select' data-placeholder='Сонго...' onchange='getEquipments(this.value)'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->sector_id'>";
				$select .= $row->name;
				$select .= "</option>";
			}
		}
		$select .= "</select></span>";
		return $select;
	}
	function sel_position($position_id = null) {
		if (isset ( $position_id ))
			$query = $this->db->query ( "SELECT * FROM position where position_id = $position_id" );
		else
			$query = $this->db->query ( "SELECT * FROM position" );
		$cols = $query->result ();
		// $select = "<select name='sector_id'>";
		$select = "<span id='txtPos'><select name='position_id' class='chosen-select' data-placeholder='Сонго...'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->position_id'>";
				$select .= $row->name;
				$select .= "</option>";
			}
		}
		$select .= "</select></span>";
		return $select;
	}
	function field_equipment($equipment_id = null) {
		$this->db->select ( 'equipment_id, name' );
		$select = '';
		if (isset ( $equipment_id ) && $equipment_id != null)
			$this->db->where ( array (
					'equipment_id' => $equipment_id 
			) );
		else
			$select .= "<option>Сонго...</option>";
		
		$query = $this->db->get ( 'equipment2' );
		$cols = $query->result ();
		// $select = "<select name='sector_id'>";
		$select = "<span id='equipment'><select name='equipment_id' id='equipment_id' data-placeholder='Сонго...'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				$select .= "<option value='$row->equipment_id'>";
				$select .= $row->name;
				$select .= "</option>";
			}
		}
		$select .= "</select></span>";
		return $select;
	}
	function gen_password() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array (); // remember to declare $pass as an array
		$alphaLength = strlen ( $alphabet ) - 1; // put the length -1 in cache
		for($i = 0; $i < 8; $i ++) {
			$n = rand ( 0, $alphaLength );
			$pass [] = $alphabet [$n];
		}
		return implode ( $pass ); // turn the array into a string
	}
}
?>
