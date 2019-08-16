<?php
class flog_model extends CI_Model {
	private $table;
	private $primary_key = null;
	private $columns = array ();
	private $flag =1;
	private $first_time = 1;

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
                }else{
                    $max_log = 1;
                }                
		//хэрэв тухайн төхөөрөмж Parent-ттай бол parent-г авна.
		//байгаад тухайн паренттай төхөөрөмж үүссэн бол хамгийн иөх утгийг авна
	
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
        
        //filter here log plugin        
	function filter_equipment($section_id = null) {
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Төхөөрөмжүүд';
		$this->db->select ( 'equipment_id, equipment' );
				
		$this->db->from ( 'equipment2' );                
                $this->db->where('is_group IS NULL', null, false);
                    
		if ($section_id)
			$this->db->where ( 'section_id', $section_id );
		$this->db->order_by ( 'section_id', 'asc' );
		$Q_e = $this->db->get ();
		if ($Q_e->num_rows () > 0) {
			foreach ( $Q_e->result_array () as $row ) {
				$data [$row ['equipment_id']] = $row ['equipment'];
			}
		}
                
		$Q_e->free_result ();
		return $data;
	}

	    //collect gates
    function calc_logic($s_id, $location_id, $equipment_id, $id, $result){                           
       $logic='';
       static $summary;
       static $result;  
	     // тухайн төх-н алдаатай мөчрүүдийг хадгалах
	     $error = $this->session->userdata('error_'.$location_id.$equipment_id);
	       //static $result ;
	     $parent = $this->new_model->get_row('parent', array('id'=>$id), 'f_tree');
	     //parent-n gate-g avah 
	     if($parent){
	        if(!in_array($s_id, $error)){
	           array_push($error, $id);        
	           $this->session->set_userdata('error_'.$location_id.$equipment_id, $error);
	      	} 
	        //тухайн Parent-н хаалгийг авах 
	        $gate = $this->new_model->get_row('gate', array('id'=>$parent), 'f_tree');
	        // хаалгуудыг хөрвүүлэх	       
		    // тухайн Parent-н мөчрүүдээр гүйх      
		    $qry = $this->db->query("SELECT id, gate FROM f_tree where node_type = 'basic' and equipment_id = $equipment_id and parent = $parent");
		      //echo "<br>gate:".$gate."<br>p:".$parent;                  
		      // тухайн мөчрүүд алдааны мөчир дотор болон тухайн мөчир мөн бол Operateor true, else false
		       //хэрэв тухайн мөчир алдаатай мөчрүүд дунд байхгүй бол нэмэх
		      foreach ( $qry->result() as $row ) {
		        //echo "r_id:".$row->id."<br>";
		         if(in_array($row->id, $error)){       
			        $logic .= ' true ' .  $this->switch_gate($gate);
			     }elseif($row->id==$s_id){
			        $logic .= ' true ' .  $this->switch_gate($gate);
			     }else{
			        $logic .= ' false ' .  $this->switch_gate($gate);
			     } 
			    $parent_gate=  $this->switch_gate($row->gate);
		      }     
		      
		      //хэрэв result true         
		      if($result=='true')  $logic = $logic . $result;
		      else  $logic = substr($logic, 0, -2);
		    
		      //echo "in_logic:".$logic."<br>";    
		      $summary = '('.$summary.$logic.')'.$parent_gate;

		      if(eval("return (".$logic.");")) $result = "true";    else $result ="false";

		      // herev true bval parent-g hadgalah
		      if($result == 'true'&&!in_array($parent, $error)){         
		         array_push($error, $parent);
		         $this->session->set_userdata('error_'.$location_id.$equipment_id, $error);     
		      }
		      // echo "<br>result:".$result."<br>";          
		      $logic .= $this->calc_logic($s_id, $location_id, $equipment_id, $parent, $result);
		    }else
		      $logic = ""; 

		      return array('result' =>$result, 'logics'=>substr($summary, 0, -2));
		}    
    // end calc_logic

    function switch_gate($gate){
       switch($gate){
          case 'AND':
            $gate = '&&';
            break;
          case 'OR':
            $gate = '||';
            break;
          default :
            $gate = '&&';
            
        }
        return $gate;
    }


    // tuhain parent parents errors are registered or not?
    function chk_parent_error($id, $location_id, $equipment_id){
       static $has_parent_error;
       //parent-g avna!!
	   $parent = $this->get_row('parent', array('id'=>$id), 'f_tree');
	   //error registered session here
	   if($parent){
		   $p_error= $this->session->userdata('parent_error_'.$location_id.$equipment_id);		   
		   if(empty($p_error)) return null;
		   else
		      if(in_array($parent, $p_error)){			
			    //herev bval
		   	    $has_parent_error = true;
		      }

		   $this->chk_parent_error($parent, $location_id, $equipment_id);	
	   }else
	   	   return null;

   	   if($has_parent_error){
   	   	  return true;
   	   }else return false;
    }


      // calc logic by reverse 
    function rev_logic($id, $location_id, $equipment_id, $result){
       $logic='';              
       static $result;
       $error = $this->session->userdata('error_'.$location_id.$equipment_id);
       $parent = $this->get_row('parent', array('id'=>$id), 'f_tree');    
             
       // if(!$error) $error = array();

       if($parent){
       	  $gate = $this->get_row('gate', array('id'=>$parent), 'f_tree');
       	  //тухайн parent-г аваад        
	       if($this->first_time==1){
	          if(($key = array_search($id, $error)) !== false) {
		         unset($error[$key]);		        
		         $this->session->set_userdata('error_'.$location_id.$equipment_id, $error);
		 	  }	
	          $this->first_time=0;	       	
	       }else if($result == 'true'&&!in_array($id, $error)){
		     array_push($error, $id);		     
		     $this->session->set_userdata('error_'.$location_id.$equipment_id, $error);
		   }	

		   // echo "second";
		   //  var_dump($error);
	       $qry = $this->db->query("SELECT id FROM f_tree where node_type = 'basic' and equipment_id = $equipment_id and parent =$parent");

//	       echo "parent". $parent;
	       // тухайн parent-н gate аваад id болон 
	       foreach ( $qry->result() as $row ) {
	       	  // tuhain id $error dotor bgaa 	       	
	       	  if(in_array($row->id, $error)){	
	       	     $logic .= ' true ' .  $this->switch_gate($gate);	
	       	  }else{
	       	  	$logic .= ' false ' .  $this->switch_gate($gate);
	       	  }
	       }
	        //хэрэв result true		 		  
			if($result=='true')
			   $logic = $logic . $result;
			else 
			   $logic = substr($logic, 0, -2);
		 	
		 	if(eval("return (".$logic.");"))
		       $result = "true";
			else $result ="false";
			
			// echo $logic; 
			if($result=='false'){
				if(($key = array_search($parent, $error)) !== false) {
		           unset($error[$key]);		        
		           $this->session->set_userdata('error_'.$location_id.$equipment_id, $error);
		 	 	}	
			}			 
			
			$logic .= $this->rev_logic($parent, $location_id, $equipment_id, $result);			
       }else
			$logic = "";
			
			return array('result' =>$result, 'error'=>$this->session->userdata('error_'.$location_id.$equipment_id));
    }

} 
// end class here

