<?php
class new_model extends CI_Model {
	private $table;
	private $primary_key = null;
	private $columns = array ();
	function __construct() {
		parent::__construct ();
		// $this->table=$table;
	}
	function set_table($table) {
		$this->unset_table ();
		
		if (! ($this->db->table_exists ( $table ))){			
			return false;			
		}else {			
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
	function get_primary_key() {
		return $this->primary_key;
	}
	function get_columns() {
		return $this->columns;
	}
	function set_fields() {
		$this->columns = array();
		foreach ( $this->db->query ( "SHOW COLUMNS FROM `{$this->table}`" )->result () as $field ) {
			if ($field->Key == 'PRI')
				$this->primary_key = $field->Field;
			$this->columns [$field->Field] = $field->Field;			
		}		
		//echo $this->table;
		return true;
	}
	
	// select_like
	function get_like($column, $keyword, $table) {
		$this->set_table ( $table );
		$this->db->select ( $column );
		$this->db->from ( $this->table );
		
		$this->db->like ( $column, $keyword, 'both' );
		$row = $this->db->get ()->row_array ();
		
		if ($row)
			return $row [$column];
		else
			return null;
	}
	
	// төхөөрөмж
	function get_select($column, $where = null, $table) { // , $join_table =null, $join_id = null
		$this->set_table ( $table );
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
	
	// primary key-s өөр key-р column үүсгэхэд ашиглана
	function get_dropdown_by($column, $key, $where = null, $table) {
		$this->db->select ( '*' );
		if ($where)
			$this->db->where ( $where );
		$Query = $this->db->get ( $table );
		
		if ($Query->num_rows () > 0) {
			foreach ( $Query->result_array () as $row ) {
				$data [$row [$key]] = $row [$column];
			}
		} else
			$data [0] = 'Ямар нэг утга олдсонгүй!';
		
		$Query->free_result ();
		return $data;
	}
	
	// primary key-s өөр key-р column үүсгэхэд ашиглана
	function get_dropdown_concat($select, $col, $key, $table, $where = null) {
		if ($where)
			$qry = $this->db->query ( "SELECT CONCAT($select) as $col, $key FROM $table WHERE $where" );
		else
			$qry = $this->db->query ( "SELECT CONCAT($select) as $col, $key FROM $table " );
		
		if ($qry->num_rows () > 0) {
			foreach ( $qry->result_array () as $row ) {
				$data [$row [$key]] = $row [$col];
			}
		} else
			$data [0] = 'Ямар нэг утга одсонгүй!';
		$qry->free_result ();
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
		
		$data [0] = '';
		
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
	function get_row($column, $where_arr = array(), $table) {
		$this->set_table ( $table );
		$this->db->select ( $column );
		$this->db->where ( $where_arr );
		$query = $this->db->get ( $this->table );
		
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
	function get_result($array, $table) {
		$this->set_table ( $table );
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
		$this->set_table ( $table );
		$this->db->select ( '*' );
		$this->db->where ( $array );
		return $this->db->get ( $table )->row ();
	}
	
	// get simple query don't need any other simples
	function get_simple($where = null, $table) {
		$this->set_table ( $table );
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
	function insert($data, $table) {
		$this->set_table ( $table );
		if (! $this->table) {
			$this->set_table ( 'log' );
		}
		$this->db->insert ( $table, $data );
		return $this->db->insert_id ();
	}
	function insert_batch($table, $data) {
		$this->db->insert_batch ( $table, $data );
		return $this->db->affected_rows ();
	}
	
	// this check_table and set_it
	function update($data, $where_arr, $table) {
		$this->set_table ( $table );
		$this->db->trans_start ();
		if (! $this->table) {
			$this->set_table ( 'log' );
		}
		$this->db->where ( $where_arr );
		$this->db->update ( $this->table, $data );
		$this->db->trans_complete ();
		if ($this->db->trans_status () === TRUE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function delete($where, $table) {
		$this->set_table ( $table );
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
	    $this->unset_table();
		$this->set_table ( $table );
		
		$return_arr = array ();
		// if table bval
		if ($array)
			$this->db->where ( $array );
		
		$res = $this->db->get ( $this->table )->result ();
		// // table null bol view_logs -g table bolgono
		foreach ( $res as $row ) {
			foreach ( $this->columns as $column => $val ) {
				$return_arr [$column] = $row->$val;
			}
		}
		return $return_arr;
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
	function get_flog_num($equipment_id) {
		//herev parent id bval gediig todorhoiloh heregtei
		//herev log bval 
                $this->set_table ( 'f_log' );
                
                // Тухайн төхөөрөмжийн утгуудийг авах
                $row = $this->get_result_row(array('equipment_id'=>$equipment_id), 'equipment2');
                $log_query = $this->db->query("SELECT * FROM f_log where section_id= $row->section_id and sector_id = $row->sector_id and status !='C' and SUBSTRING(log_num, 3, 3) = '$row->code'");               
                if ($log_query->num_rows () > 0) {
                    //хэрэв их утга байвал тухайн утгийн хамгий ихийг авна
                    $qry_max_log = $this->db->query("SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num FROM f_log where section_id=$row->section_id"
                    . " and sector_id = $row->sector_id and status !='C' and SUBSTRING(log_num, 3, 3) = '$row->code' ORDER BY max_log_num+0 DESC LIMIT 1");               
                        $max_log = (int)$qry_max_log->row ()->max_log_num;                                                
                        $max_log ++;
                }                
		//хэрэв тухайн төхөөрөмж Parent-ттай бол parent-г авна.
		//байгаад тухайн паренттай төхөөрөмж үүссэн бол хамгийн иөх утгийг авна
		
//		$parent_id = $this->get_row('parent_id', array('equipment_id' =>$equipment_id), 'equipment2');
//		
//		if($parent_id){ //baina uu?
//			// Хэрэв parent_id -р тухайн гэмтэл байгаа эсэхийг шалгана
//			$qry = $this->db->query("SELECT * FROM f_log where equipment_id in (select equipment_id from equipment2 where parent_id = $parent_id) and status !='C'");
//
//			if ($qry->num_rows () > 0) {
//				//хэрэв ямар нэг утга олдож байвал тухайн утгын хамгийн ихийг нэмнэ.
//				$qry_max_log = $this->db->query ( "SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num
//	                                           FROM f_log A WHERE A.equipment_id IN (select equipment_id from equipment2 where parent_id = $parent_id)
//	                                            ORDER BY max_log_num+0 DESC LIMIT 1;" );
//				if($qry_max_log->num_rows()>0){
//					$max_log = $qry_max_log->row()->max_log_num;				
//					$max_log++;
//					//ECHO "PARENT_1:".$max_log;
//				}else{
//					$max_log = $this->get_row ( 'max_log_num', array (
//						'equipment_id' => $parent_id 
//					), 'equipment2' );		
//					//ECHO "PARENT_2:".$max_log;
//				}// else aldaa avsan log-n dugaarin id-gaar ymarch log_uusgeh bolomjgui ba
//			}else{				
//				$qry_max_log = $this->db->query ( "SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num
//	                                           FROM f_log A WHERE A.equipment_id = $equipment_id
//	                                            ORDER BY max_log_num+0 DESC LIMIT 1;" );
//				
//				$max_log = $qry_max_log->row ()->max_log_num;
//				if($max_log){
//					$max_log++;
//				}else{
//					$max_log = $this->get_row ( 'max_log_num', array (
//						'equipment_id' => $equipment_id 
//					), 'equipment2' );					
//				}				
//			}
//		}else{
//			$log_query = $this->db->query("SELECT * FROM f_log where equipment_id = $equipment_id and status !='C'");
//			// var_dump($this->db->last_query());
//			if ($log_query->num_rows () > 0) {
//				//хэрэв ямар нэг утга олдож байвал тухайн утгын хамгийн ихийг нэмнэ.
//				$qry_max_log = $this->db->query ( "SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num
//	                                           FROM f_log A WHERE A.equipment_id = $equipment_id
//	                                            ORDER BY max_log_num+0 DESC LIMIT 1;" );
//
//				$max_log = $qry_max_log->row ()->max_log_num;
//				if (is_numeric ( $max_log )) {
//					$max_log ++;
//				}
//				// else aldaa avsan log-n dugaarin id-gaar ymarch log_uusgeh bolomjgui ba
//			}else {				
//				// else there's no active log in flog
//				// generate from equipment2 flogname from log			
//				$max_log = $this->get_row ( 'max_log_num', array (
//						'equipment_id' => $equipment_id 
//				), 'equipment2' );
//				if (is_null ( $max_log ))
//					$max_log = 1;
//			}
//		}
	
//		if($parent_id){
//			// code ni 3 үгсийн урттай байх ёстой!
//			$sql_join = "SELECT substring(B.code, 1, 1) as sec_code, A.code as code
//	                           FROM equipment2 A 
//	                           LEFT JOIN section B ON A.section_id = B.section_id
//	                           WHERE A.equipment_id = $parent_id";			
//		}else 
			$sql_join = "SELECT substring(B.code, 1, 1) as sec_code, A.code as code
	                           FROM equipment2 A 
	                           LEFT JOIN section B ON A.section_id = B.section_id
	                           WHERE A.equipment_id = $equipment_id";				    
		$qry_code = $this->db->query ( $sql_join );
		$code = $qry_code->row ()->code;
		$sec_code = $qry_code->row ()->sec_code;
		// хэрэв 3 бол зөв Бүртгэгсдэн гсн үг
		if (strlen ( $code ) == 3) {
			return $sec_code . '-' . $code . $max_log;
		} else {
			// хэрэв тм биш бол тухайн 3 үсгийн кодыг equipment name-с gen хийж авах шаардалагатй
			// code-g эхний утгаар авах ёстой
			$new_code = $this->db->query ( " SELECT substring(equipment, 1, 3) as newcode FROM equipment2 WHERE equipment_id=$equipment_id" )->row ()->newcode;
			// update equipment_id = by $equipment_id by new code
			$this->update ( array (
					'code' => strtoupper ( $new_code ) 
			), array (
					'equipment_id' => $equipment_id 
			), 'equipment2' );
			return $sec_code . '-' . strtoupper ( $new_code ) . $max_log; // call attributes
		}
	}
	function get_action($role) {
		$result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='flog' and controller='flog'" )->result ();
		foreach ( $result as $row ) {
			$result_array [$row->functions] = $row->functions;
		}
		return $result_array;
	}
	function get_action_wh_spare($role) {
		$query = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='wh_spare' and controller='wh_spare'" );
        if($query->num_rows()>0){
            $result = $query->result ();
            foreach ( $result as $row ) {
                $result_array [$row->functions] = $row->functions;
            }
        }else
            $result_array = array();

		return $result_array;
	}
	function get_actionby($role, $app, $controller) {
		$result = $this->db->query ( "SELECT functions FROM role_insystem where $role = 'Y' AND apps='$app' and controller='$controller' order by id asc " )->result ();
		foreach ( $result as $row ) {
			$result_array [$row->functions] = $row->functions;
		}
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
	function get_as_column($sel_column, $where_col = null, $where = null, $seperator, $table) {
		$this->set_table ( $table );
		$this->db->select ( $sel_column );
		if ($where_col)
			$this->db->where_in ( $where_col, $where );
		$result = $this->db->get ( $this->table )->result ();
		$columns = '';
		foreach ( $result as $row ) {
			$columns .= $seperator . $row->$sel_column;
		}
		return substr ( $columns, 1 );
	}
	
	// Warehouse
	function field_equipment($equipment_id = null) {
		$this->db->select ( '*' );
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
				$select .= $row->equipment;
				$select .= "</option>";
			}
		}
		$select .= "</select></span>";
		return $select;
	}
} 
// end class here

