<?php
class my_model_old extends CI_Model {
	private $table;
	private $primary_key = null;
	private $columns = array ();
	function __construct() {
		parent::__construct ();
		// $this->table=$table;
	}
	function get_list() {
		$this->db->select ();
		return $this->db->get ( $this->table )->result ();
	}
	function set_table($table) {
		if (! ($this->db->table_exists ( $table )))
			return false;
		else {
			unset ( $this->table );
			$this->table = $table;
			$this->set_fields ();
			return true;
		}
	}
	function unset_table() {
		if ($this->table)
			unset ( $this->table );
		return true;
	}
	function get_table() {
		return $this->table;
	}
	function check_table($table) {
		if (strrchr ( $table, 'view' )) {
			// if view bol uussen bgaa esehiig shalgana
			if (! $this->db->table_exists ( $table )) {
				return $this->create_view ( $table );
			}
		} else
			return $table;
	}
	function get_primary_key() {
		return $this->primary_key;
	}
	function get_columns() {
		return $this->columns;
	}
	function set_fields() {
		foreach ( $this->db->query ( "SHOW COLUMNS FROM `{$this->table}`" )->result () as $field ) {
			if ($field->Key == 'PRI')
				$this->primary_key = $field->Field;
			$this->columns [$field->Field] = $field->Field;
		}
		return true;
	}
	
	// select_like
	function get_like($column, $keyword) {
		$this->db->select ( $column );
		if ($this->table)
			$this->db->from ( $this->table );
		
		$this->db->like ( $column, $keyword, 'both' );
		$row = $this->db->get ()->row_array ();
		
		if ($row)
			return $row [$column];
		else
			return null;
	}
	
	// төхөөрөмж
	function get_select($column, $where = null) { // , $join_table =null, $join_id = null
		$data [0] = '';
		$this->db->select ( '*' );
		if ($where)
			$this->db->where ( $where );
		$Query = $this->db->get ( $this->table );
		
		if ($Query->num_rows () > 0) {
			foreach ( $Query->result_array () as $row ) {
				$data [$row [$this->primary_key]] = $row [$column];
			}
		}
		$Query->free_result ();
		return $data;
	}
	function get_select_column($value, $col1, $col2, $table, $array) { // , $join_table =null, $join_id = null
		if ($array) {
			$this->db->where ( $array );
		}
		if ($table) {
			$this->set_table ( $table );
		} else
			$this->set_table ( 'log' );
		
		$data [''] = '';
		
		$Query = $this->db->get ( $table );
		if ($Query->num_rows () > 0) {
			foreach ( $Query->result_array () as $row ) {
				$data [$row [$value]] = $row [$col1] . ' - ' . $row [$col2];
			}
		}
		$Query->free_result ();
		return $data;
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
	
	// өгсөн утгаар мөрийн утгыг авах фүнкц
	function get_row($column, $where_arr) {
		$this->db->select ( $column );
		$this->db->where ( $where_arr );
		if ($this->table)
			$query = $this->db->get ( $this->table );
		else
			$query = $this->db->get ( 'log' );
		
		$row = $query->row_array ();
		if ($row)
			return $row [$column];
		else
			return null;
	}
	function get_relation($main_table, $id, $join_table, $where_arr = null) {
		$join_id = $this->get_column ( $join_table, TRUE );
		$this->db->select ( $main_table . ".*, " . $join_table . ".*" );
		$this->db->from ( $main_table );
		$this->db->join ( $join_table, $main_table . '.' . $id . '=' . $join_table . '.' . $join_id, 'left' );
		$this->db->where ( $where_arr );
		$query = $this->db->get ()->row ();
		return $query;
	}
	function get_result($array) {
		$this->db->select ( '*' );
		$this->db->where ( $array );
		$result = $this->db->get ( $this->table )->result ();
		return $result;
	}
	function get_query($select, $table, $where = null, $sidx = null, $sord = null, $start = null, $limit = null) {
		$sql = "";
		if ($where)
			$sql .= "SELECT $select FROM $table $where";
		else
			$sql .= "SELECT $select FROM $table ";
		if ($sidx && $sord) {
			$sql .= " ORDER BY $sidx $sord";
		}
		if ($limit) {
			$sql .= " LIMIT $start , $limit";
		}
		return $this->db->query ( $sql );
	}
	function get_result_row($array, $table) {
		$this->db->select ( '*' );
		$this->db->where ( $array );
		return $this->db->get ( $table )->row ();
	}
	
	// get simple query don't need any other simples
	function get_simple($where = null) {
		if ($where)
			$sql = "SELECT * FROM " . $this->table . " WHERE " . $where;
		else
			$sql = "SELECT * FROM " . $this->table;
		return $query = $this->db->query ( $sql );
	}
	function check_id($column, $array, $table) {
		$id = $this->get_row ( $column, $array, $table );
		if ($id)
			return TRUE;
		else
			return FALSE;
	}
	
	// return data results
	function get_data() {
		unset ( $data );
		foreach ( $this->input->post () as $key => $val ) {
			$data [$key] = $val;
		}
		return $data;
	}
	function insert($data) {
		if (! $this->table) {
			$this->set_table ( 'log' );
		}
		$this->db->insert ( $this->table, $data );
		return $this->db->affected_rows ();
	}
	function insert_batch($table, $data) {
		return $this->db->insert_batch ( $table, $data );
	}
	
	// this check_table and set_it
	function update($data, $where_arr) {
		if (! $this->table) {
			$this->set_table ( 'log' );
		}
		if($this->table=='log')
		   $this->db->set('updated_at', 'NOW()', FALSE);
		
		$this->db->where ( $where_arr );
		$this->db->update ( $this->table, $data );
		return $this->db->affected_rows ();
	}
	function delete($where) {
		if (! ($this->table)) {
			$this->set_table ( 'log' );
		}
		// $this->db->where('log_id', $id);
		$this->db->delete ( $this->table, $where );
		return $this->db->affected_rows ();
	}
	function last_query() {
		return $this->db->last_query ();
	}
	function get_all($array, $table) {
		$return_arr = array ();
		// if table bval
		if ($array)
			$this->db->where ( $array );
			// if($table){
			// ene table view uu esvel table uu
		if (strrchr ( $table, 'view' )) {
			// if view bol uussen bgaa esehiig shalgana
			if (! $this->db->table_exists ( $table )) {
				$this->create_view ( $table );
			}
		}
		$this->set_table ( $table );
		$res = $this->db->get ( $table )->result ();
		// // table null bol view_logs -g table bolgono
		foreach ( $res as $row ) {
			foreach ( $this->columns as $column => $val ) {
				$return_arr [$column] = $row->$val;
			}
		}
		return $return_arr;
	}
	function create_view($view) {
		switch ($view) {
			case 'view_logs' :
				// code...
				// sec_to_time -г өөрчилсөн
				$query_view = "CREATE VIEW view_logs AS select 
            a.log_id AS log_id, a.log_num AS log_num, a.createdby_id AS createdby_id, date_format(a.created_datetime, '%Y-%m-%d %H:%i') AS created_datetime,
            a.location_id AS location_id, a.equipment_id AS equipment_id, a.reason AS reason, a.defect AS defect, a.completion AS completion, a.duration_time AS duration_time,
            date_format(a.closed_datetime, '%Y-%m-%d %H:%i') AS closed_datetime, a.section_id AS section_id, l.name AS section, d.sector_id AS sector_id, 
            a.sec_code AS sec_code, b.fullname AS createdby, b.last_name AS createdby_lname, b.position AS createdby_position, c.name AS location,
            d.name AS equipment, a.closed AS closed, a.closedby_id AS closedby_id, f.fullname AS closedby, f.last_name AS closedby_lname, f.position AS closedby_position,
            a.filename AS filename, a.level AS level, a.inst AS inst, concat(a.inst, a.level) AS q_level, concat(h.fullname, ' | ', e.fullname) AS ezi, e.fullname as provedby
        from log a left join view_employee b ON a.createdby_id = b.employee_id left join location c ON a.location_id = c.location_id left join equipment d ON a.equipment_id = d.equipment_id
          left join view_employee f ON a.closedby_id = f.employee_id left join view_employee e ON a.proveby_id = e.employee_id left join view_employee h ON a.activatedby_id = h.employee_id
          left join section l ON a.section_id = l.section_id ";
				
				$query = $this->db->query ( $query_view );
				return $view;
				break;
			
			default :
				// code...
				break;
			
			// if($this->db->table_exists('view_logs'))
			// return 'view_logs';
		}
	}
	function get_log_num($equipment_id) {
		$qry_max_log = $this->db->query ( "SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num
                                           FROM log A WHERE A.equipment_id = $equipment_id
                                            ORDER BY max_log_num+0 DESC LIMIT 1;" );
		$max_log = $qry_max_log->row ()->max_log_num;
		
		if (isset ( $max_log )) {
			$max_log ++;
		} else {
			$this->set_table ( 'equipment' );
			$max_log = $this->get_row ( 'max_log_num', array (
					'equipment_id' => $equipment_id 
			) );
		}
		
		$sql_join = "SELECT concat(substring(B.sec_code, 1, 1), '-', b.code) as log_code
                           FROM equipment B WHERE B.equipment_id = $equipment_id";
		
		$code_head = $this->db->query ( $sql_join )->row ()->log_code;
		
		return $code_head . $max_log; // call attributes
	}
	function get_action($role) {
		$result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='log' and controller='log'" )->result ();
		foreach ( $result as $row ) {
			$result_array [$row->functions] = $row->functions;
		}
		
		return $result_array;
	}
	
	function get_actionby($role, $app, $controller) {

		$query =$this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='$app' and controller='$controller' order by id asc " );

		if($query->num_rows() > 0){
			
			foreach ( $query->result() as $row ) {

				$result_array [$row->functions] = $row->functions;
			}

		}else $result_array = null; 
		 
		return $result_array;
	}
	function gen_password($length = 8) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_';
		$count = mb_strlen ( $chars );
		for($i = 0, $result = ''; $i < $length; $i ++) {
			$index = rand ( 0, $count - 1 );
			$result .= mb_substr ( $chars, $index, 1 );
		}
		return $result;
	}
	function user_check($username, $password) {
		$Q = $this->db->get_where ( 'view_employee', array (
				'username' => $username,
				'password' => md5 ( $password ) 
		), 1, 0 );
		if ($Q->num_rows () > 0) {
			$row = $Q->row_array ();
			$this->session->set_userdata ( 'access', 'OK' );
			$this->session->set_userdata ( 'role', $row ['role'] );
			$this->session->set_userdata ( 'employee_id', $row ['employee_id'] );
			$this->session->set_userdata ( 'fullname', $row ['fullname'] );
			$this->session->set_userdata ( 'position', $row ['position'] );
			$this->session->set_userdata ( 'sec_code', $row ['sec_code'] );
			$this->session->set_userdata ( 'section_id', $row ['section_id'] );
			$this->session->set_userdata ( 'username', $row ['username'] );
			
			if ($row ['role'] == 'ADMIN' || $row ['role'] == 'CHIEFENG' || $row ['role'] == 'TECHENG' || $row ['role'] == 'SUPERVISOR' || $row ['role'] == 'HEADMAN' || $row ['role'] == 'CHIEF' || $row ['role'] == 'QENG') {
				$this->session->set_userdata ( 'access_type', 'ADMIN' );
			} else {
				$this->session->set_userdata ( 'access_type', 'USER' );
			}
			
			if ($row ['sec_code'] == 'COM' || $row ['sec_code'] == 'SUR' || $row ['sec_code'] == 'NAV' || $row ['sec_code'] == 'ELC') {
				$this->session->set_userdata ( 'user_type', 'industry' );
			} else
				$this->session->set_userdata ( 'user_type', 'govern' );
			
			return TRUE;
		} else
			return FALSE;
	}
	function get_count($column, $where) {
		$sql = "SELECT count($column) as total from $this->table WHERE " . $where;
		$query = $this->db->query ( $sql );
		$row = $query->row_array ();
		
		if ($row)
			return $row ['total'];
		else
			return 0;
	}
	function exc_query($sql) {
		// Select * from sql
		$query = $this->db->query ( $sql );
		return $this->db->affected_rows ();
	}
	
	// exequite query return query
	function get_query_as_sql($sql) {
		$query = $this->db->query ( $sql );
		return $query->result ();
	}
	function get_as_query($sql) {
		return $query = $this->db->query ( $sql );
	}
	function get_as_column($sel_column, $where_col = null, $where = null, $where_2 =null, $seperator) {
		$this->db->select ( $sel_column );
		if ($where_col)
			$this->db->where_in ( $where_col, $where );
		 if($where_2)
		 	$this->db->where($where_2);		
		$result = $this->db->get ( $this->table )->result ();
		$columns = '';
		foreach ( $result as $row ) {
			$columns .= $seperator . $row->$sel_column;
		}
		return substr ( $columns, 1 );
	}

	function get_as_column_new($sel_column, $where = null, $seperator) {
		$this->db->select ( $sel_column );
		if ($where)
			$this->db->where($where);
		$result = $this->db->get ( $this->table )->result ();
		$columns = '';
		foreach ( $result as $row ) {
			$columns .= $seperator . $row->$sel_column;
		}
		return substr ( $columns, 1 );
	}


	
	/* warehouse */
}

