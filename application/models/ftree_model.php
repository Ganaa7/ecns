<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ftree_model extends CI_Model {
	private $flag =1;
	private $first_time = 1;
	
	function __construct() {
		// Call the Model constructor
		$this->load->database ();
		$this->load->model ( 'main_model' );
		$this->load->model ( 'user_model' );
		$this->load->helper ( 'url' );
		$this->load->helper ( 'cookie' );
		$this->load->library ( 'session' );
	}
	function get_tree($parent, $level) {
		static $tree;
		static $status = 1;
		$sql = "SELECT a.*, temp.Count FROM vw_ftree a  
                       LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count 
                       FROM f_tree GROUP BY parent) as temp ON a.id = temp.parent 
                    WHERE a.parent=$parent ";
		
		$query = $this->db->query ( $sql );
		foreach ( $query->result () as $row ) {
			if ($row->Count > 0) {
				$tree .= "[root:" . $row->event . ":" . $row->gate;
				$this->ftree_model->get_tree ( $row->id, $level );
				$tree .= "]";
			} elseif ($row->Count == null) {
				$tree .= "<br>";
				$tree .= "[:" . $row->event . "]";
			}
		}
		
		return $tree;
	}
	function in_parent($in_parent, $equipment_id, $store_all_id) {
		static $ftree;
		if (in_array ( $in_parent, $store_all_id )) {
			
			if ($in_parent == 0)
				$ftree = "<ul class='tree'>";
			else
				$ftree .= "<ul>";
			
			$qry = $this->db->query ( "SELECT a.*, IFNULL(temp.Count, 0) as count FROM vw_ftree a  
                       LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count 
                       FROM vw_ftree GROUP BY parent) as temp ON a.id = temp.parent
                       WHERE a.parent = $in_parent and equipment_id = $equipment_id" );
			
			foreach ( $qry->result () as $row ) {
				$ftree .= "<li";
				if ($row->count) {
					$ftree .= "><div id=" . $row->id . "><span class='first_name'>" . $row->node . "</span></div>";
					$ftree .= '<span class="centeral ' . $row->gate . '"></span>';
				} else{					
					$ftree .= "><div class='$row->node_type' id=" . $row->id . "><span class='first_name'>" . $row->node . "</span></div>";
				}
				$this->in_parent ( $row->id, $equipment_id, $store_all_id );
				$ftree .= "</li>";
			}
			$ftree .= "</ul>";
		}
		return $ftree;
	}

	//new ftreeview
	 function tree_parent($in_parent, $equipment_id, $store_all_id) {	 	
	 	static $tree;
        if (in_array($in_parent, $store_all_id)) {
            $qry = $this->db->query("SELECT a.*, IFNULL(temp.Count, 0) as count FROM vw_ftree a  
                   LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count 
                   FROM vw_ftree GROUP BY parent) as temp ON a.id = temp.parent
                   WHERE a.parent = $in_parent and equipment_id=$equipment_id");
             
            if($in_parent == 0){
				$tree .="<ul id='gray' class='treeview-black'>";
				// $tree .="<h4>Алдааны мод</h4>";
            } 
            else $tree .= "<ul>";

            foreach ( $qry->result () as $row ) {
                $tree .= "<li>";  
                    if($row->count){       
                        if($this->flag==1){
	                       $tree .="<span class='module top' id=".$row->id.">" . $row->node . "</span>";
	                       $this->flag=0;
                        }
	                    else
	                    //echo "<div id=" . $row['id'] . ">";	                    
	                    $tree .="<span class='module' id=".$row->id.">" . $row->node . "</span>";
	                    $tree .= "<div id='gate'>[".$row->gate."]</div>";	                    
                    }
                    else{
                       $tree .=  "<span  id=" . $row->id." class='module $row->node_type'>" . $row->node . "</span>";
                    	
                    }
                $this->tree_parent($row->id, $equipment_id, $store_all_id);
                $tree .=  "</li>";
            }
            $tree .=  "</ul>";
        }
        return $tree;
    }

    //find collect logic gates 
    function get_logic($s_id, $equipment_id, $id, $result){    	                         
       $logic='';
       static $result ;
	   $parent = $this->new_model->get_row('parent', array('id'=>$id), 'f_tree');
	   //parent-n gate-g avah 
	   if($parent){
	      //gate-г хөрвүүлэх фүнкц	   			   	
		  $gate = $this->new_model->get_row('gate', array('id'=>$parent), 'f_tree');
		  switch($gate){
		  	case 'AND':
		  		$gate = '&&';
		  		break;
		  	case 'OR':
		  		$gate = '||';
		  		break;
		  }
		  //тухайн Parent-н бүх node-г авах
		  $qry = $this->db->query("SELECT id FROM f_tree where equipment_id = $equipment_id and parent = $parent");
		  
		  //$logic .= '(';
		  foreach ( $qry->result() as $row ) {
		  	echo "r_id:".$row->id."<br>";
			 if($row->id==$s_id){
				$logic .= ' true ' . $gate;
			 } 
			 else{
				$logic .= ' false ' . $gate;
		 	 } 
		  }
		 
		  if(isset($result))
		  	$logic = $logic . $result;
		  else 
		    $logic = substr($logic, 0, -2);

		  $logic .= ')';
		  
		  echo "logic:".$logic."<br>";

		  if(eval("return (".$logic.");"))
		     $result = "true";
		  else $result ="false";

		  echo "result:".$result."<br>";

		  	//$logic = substr($logic, 0, -2);
			$logic .= $this->get_logic($s_id, $equipment_id, $parent, $result);
		}else
			$logic = "";

		return $result."l:".$logic;
    }
    // end get_logic    //find collect logic gates 

    //collect gates
    function calc_logic($s_id, $equipment_id, $id, $result){    	                     
       $logic='';
       static $summary;
       static $result;	
	   // тухайн төх-н алдаатай мөчрүүдийг хадгалах
	   $error = $this->session->userdata('error_'.$equipment_id);
       //static $result ;
	   $parent = $this->new_model->get_row('parent', array('id'=>$id), 'f_tree');
	   //parent-n gate-g avah 
	   if($parent){
	   	  if(!in_array($s_id, $error)){
		     array_push($error, $id);		     
		     $this->session->set_userdata('error_'.$equipment_id, $error);
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
		  if($result=='true')
		  	$logic = $logic . $result;
		  else 
		    $logic = substr($logic, 0, -2);
		
		  //echo "in_logic:".$logic."<br>";		 
		  $summary = '('.$summary.$logic.')'.$parent_gate;

		  if(eval("return (".$logic.");"))
		     $result = "true";
		  else $result ="false";

		  // herev true bval parent-g hadgalah
		  if($result == 'true'&&!in_array($parent, $error)){		     
		     array_push($error, $parent);
		     $this->session->set_userdata('error_'.$equipment_id, $error);     
		  }
		  // echo "<br>result:".$result."<br>";  	  	   
   		   $logic .= $this->calc_logic($s_id, $equipment_id, $parent, $result);
		}else
			$logic = "";
			
		return array('result' =>$result, 'logics'=>substr($summary, 0, -2));
    }    
    // end calc_logic

    // calc logic by reverse 
    function rev_logic($id, $equipment_id, $result){
       $logic='';              
       static $result;
       $error = $this->session->userdata('error_'.$equipment_id);
       $parent = $this->new_model->get_row('parent', array('id'=>$id), 'f_tree');
    
       // var_dump($error);

       if($parent){
       	  $gate = $this->new_model->get_row('gate', array('id'=>$parent), 'f_tree');
       	  //тухайн parent-г аваад        
	       if($this->first_time==1){
	          if(($key = array_search($id, $error)) !== false) {
		         unset($error[$key]);		        
		         $this->session->set_userdata('error_'.$equipment_id, $error);
		 	  }	
	          $this->first_time=0;	       	
	       }else if($result == 'true'&&!in_array($id, $error)){
		     array_push($error, $id);		     
		     $this->session->set_userdata('error_'.$equipment_id, $error);
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
		           $this->session->set_userdata('error_'.$equipment_id, $error);
		 	 	}	
			}			 
			
			$logic .= $this->rev_logic($parent, $equipment_id, $result);			
       }else
			$logic = "";
			
			return array('result' =>$result, 'error'=>$this->session->userdata('error_'.$equipment_id));
       
    }

    function rm_parent($id, $equipment_id){
    	$parent = $this->new_model->get_row('parent', array('id'=>$id, 'equipment_id'=>$equipment_id), 'f_tree');

    	if($parent){
	    	//check if this parent in session     	
	    	$failed_nodes = $this->session->userdata('error_'.$equipment_id);
	    	if(in_array($parent, $failed_nodes)){
	    		//parent bval ustgana
	    		if(($key = array_search($parent, $failed_nodes)) == true) {
			       unset($failed_nodes[$key]);
			       $this->session->set_userdata('error_'.$equipment_id, $failed_nodes);
				}
	    	}

	    	$this->rm_parent($parent, $equipment_id);
    	}else
    	  return null;

    	 return true;
    }

    // tuhain parent parents error has registered
    function chk_parent_error($id, $equipment_id){
       static $has_parent_error;
       //parent-g avna!!
	   $parent = $this->new_model->get_row('parent', array('id'=>$id), 'f_tree');
	   //error registered session here
	   if($parent){
		   $p_error= $this->session->userdata('parent_error_'.$equipment_id);		   
		   if(empty($p_error)) return null;
		   else
		      if(in_array($parent, $p_error)){			
			    //herev bval
		   	    $has_parent_error = true;
		      }

		   $this->chk_parent_error($parent, $equipment_id);	
	   }else
	   	   return null;

   	   if($has_parent_error){
   	   	  return true;
   	   }else return false;
    }


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
}