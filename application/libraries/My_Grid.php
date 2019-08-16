<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/**
 * CodeIgniter Grid library
 *
 * @package CodeIgniter Grid
 * @author  Gandavaa Dugarsuren <ganaa7@gmail.com>
 * @link    https://github.com/Ganaa7/CodeIgniter-My_Grid
 * @diskrioption: its used for codeigniter controller works with jqgrid.js
 * 
 */


class My_Grid{
	// grid for which table or view
	private $table;
	// has a inputs 
	// page 	
	private $page;
	// limit per page
	private $limit;
	// sidx asc desc
	private $sidx =1;
	// order by 
	private $sord;   
	private $filters;
	private $search;
	private $where = '';	
	
	private $count =0;
	private $total_pages = 0;

	private $start;
	private $query;
	private $columns = array();
	public $ls_qry;

	private $CI;

	public function __construct(){
		// here set initial tables input		
		$CI = &get_instance();
		$this->page = $CI->input->get_post('page');
		$this->limit = $CI->input->get_post('rows');
		$this->sidx = $CI->input->get_post('sidx');
		$this->sord = $CI->input->get_post('sord');
		$this->filters = $CI->input->get_post('filters');
	    $this->search = $CI->input->get_post('_search'); 	    
		// call ci database
		$this->limit = 10;

	}

	function run($table){
        $this->table = $table;
		// herev filter hiisen bval where should be set
	    $this->where =$this->check_filter();	    
	    // нийт тоо 
	    $this->count=$this->set_count();
	    
	    //нийт хуудсыг тоог олно
	    $this->set_total_pages();
	    
	    if($this->page > $this->total_pages)  $this->page=$this->total_pages;    

	    $this->set_start();

	    //get final result as json
	    if($this->check_column()){
	       $this->get_rows();	    	
	    }else
	    	echo "json none";
	    
	}

	//set columns as array to coluns var
	function set_column(){
		$this->columns = func_get_args();

		if(isset($this->columns[0]) && is_array($this->columns[0])){
		   $this->columns = $this->columns[0];
		}
		// print_r($this->columns);		
	}
	private function check_column(){
		if(sizeof($this->columns)>0)
			return true;
		else return false;
	}

	private function set_page(){
		if($this->page > $this->total_pages)  
			$this->page=$this->total_pages;        
	}

	//fn set start 
	private function set_start(){
		$this->start =$this->limit*$this->page - $this->limit;  	    
		if($this->start <0) 
	   	$this->start = 0;
	}

	//fn get query as result
	private function get_rows(){
		//get_columns as array
		$json =array();  
		$rows= array();
		$json['page']=$this->page;
	    $json['total']=$this->total_pages;
	    $json['records']=$this->count;	    
	    $qry = $this->get_query('*', $this->table, $this->where, $this->sidx, $this->sord);	   	    
	    $res=$qry->result();
      	// // table null bol view_logs -g table bolgono      
	      	foreach ($res as $row){           
	          foreach($this->columns as $column=>$val){
	             $crow[$column]=$row->$val;
	          }
	           array_push($rows, $crow); 
	      }     
       
      $json['rows']=$rows;
            
      echo json_encode($json);
		
	}

	//fn get count of query
	private function set_count(){
		//herev filter hiigdsen bol
		if(strlen($this->where)>1){
		   $query= $this->get_query(" count(*) as count ", $this->table, $this->where);      
		}else{
		   $query= $this->get_query(" count(*) as count ", $this->table);      
		}

	    if($query->num_rows() > 0){
	       $countRow = $query->row_array(); 
	       return $countRow['count']; 
	    } else return 0;
	} 

	//fn set_total count of query
	private function set_total_pages(){
		if( $this->count > 0 )			 
			 	$this->total_pages = ceil($this->count/$this->limit);  
	    else $this->total_pages = 0;
	}

	//if filter has set or search set set to query where filter
	private function check_filter(){
	   if(($this->search=='true')&&($this->filters != "")){
	      return $this->filter($this->filters); 
	   }else 
	   	 return '';
	}

	//fn output as json
  	private function filter($filters){            
       $filters = json_decode($filters);
       $where = " where ";
       // herev where 
       $whereArray = array();
       $rules = $filters->rules;
       $groupOperation = $filters->groupOp;
       foreach($rules as $rule) {
          $fieldName = $rule->field;
          $fieldData = mysql_real_escape_string($rule->data);
          switch ($rule->op) {
             case "eq":
                  $fieldOperation = " = '".$fieldData."'";
                  break;
             case "ne":
                  $fieldOperation = " != '".$fieldData."'";
                  break;
             case "lt":
                  $fieldOperation = " < '".$fieldData."'";
                  break;
             case "gt":
                  $fieldOperation = " > '".$fieldData."'";
                  break;
             case "le":
                  $fieldOperation = " <= '".$fieldData."'";
                  break;
             case "ge":
                  $fieldOperation = " >= '".$fieldData."'";
                  break;
             case "nu":
                  $fieldOperation = " = ''";
                  break;
             case "nn":
                  $fieldOperation = " != ''";
                  break;
             case "in":
                  $fieldOperation = " IN (".$fieldData.")";
                  break;
             case "ni":
                  $fieldOperation = " NOT IN '".$fieldData."'";
                  break;
             case "bw":
                  $fieldOperation = " LIKE '".$fieldData."%'";
                  break;
             case "bn":
                  $fieldOperation = " NOT LIKE '".$fieldData."%'";
                  break;
             case "ew":
                  $fieldOperation = " LIKE '%".$fieldData."'";
                  break;
             case "en":
                  $fieldOperation = " NOT LIKE '%".$fieldData."'";
                  break;
             case "cn":
                  $fieldOperation = " LIKE '%".$fieldData."%'";
                  break;
             case "nc":
                  $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                  break;
              default:
                  $fieldOperation = "";
                  break;
           }
           if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
       }
       if (count($whereArray)>0) {
          $where .= join(" ".$groupOperation." ", $whereArray);
       }else {
          $where = "";
       }
        return $where;
    } 

    //get query from ci-db-query
    public function get_query($select, $table=null, $where=null, $sidx=null, $sord=null, $start=null, 
    	$limit=null){    	
    	 //return $this->my_model->get_query($select, $table, $where, $sidx, $sord, $start, $limit);
    	$CI = &get_instance();              	
    	$CI->load->database();
    	  $sql = "";
     if($where)
        $sql .="SELECT $select FROM $table $where";
     else
        $sql .= "SELECT $select FROM $table ";
     if($sidx&&$sord){
        $sql .=" ORDER BY $sidx $sord";
     }
     if($limit){
        $sql .=" LIMIT $start , $limit";
     }
     	//echo $sql;
      // if(!$query=$CI->db->query($sql)){
      //    $error = $CI->db->error();
      // }
      return $CI->db->query($sql);
    } 

    // public set_where($where){
    // 	$this->where = ' WHERE '.$where;

    // }

    // get view
}

	