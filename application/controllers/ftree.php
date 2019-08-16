<?php
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class ftree extends CNS_Controller {

	public static $user_seccode;

	public static $user_section_id;

	public $gallery_path;

	public $gallery_path_url;

	public $objdata;

	function __construct() {

		parent::__construct ();
				
		$this->load->model ( 'ftree_model' );		
		
		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'ftree', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Алдааны мод бүртгэл' );

		$this->config->set_item ( 'module_menu_link', '/ecns/ftree' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
		
	}

	function help() {

		$data ['page'] = 'ftree\help';

		$data ['title'] = "Алдааны мод тусламж";

		$this->load->view ( 'index', $data );
	}

	function index() {
		// call grid list and get list as equipment
		// '/ecns/assets/ftree/js/jquery.tree.js'
		$data ['library_src'] = $this->javascript->external ( base_url().'/assets/ftree/js/ftree.js', TRUE );
		
		$t = new eFtree ();

		$t->set_user ( $this->user_id );

		$t->set_role ( $this->role );

		$out = $t->run ();
		
		if ($out->view) {
			$data ['out'] = $out;
			$data ['page'] = 'ftree\ftree_list';
			$data ['title'] = 'Алдааны мод жагсаалт';
			$this->my_model_old->set_table ( 'location' );
			$data ['location'] = $this->my_model_old->get_select ( 'name' );
			$this->my_model_old->set_table ( 'equipment2' );
			$data ['equipment'] = $this->my_model_old->get_select ( 'equipment' );
			$this->my_model_old->set_table ( 'f_event' );
			$data ['event'] = $this->my_model_old->get_select ( 'event' );
			// here is get result studying
			$this->load->view ( 'index', $data );
		} else {
			if ($out->json) {
				header ( 'Content-type: application/json; charset=utf-8' );
				echo $out->json;
			} else {
				if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" ))
					header ( "Content-type: application/xhtml+xml;charset=utf-8" );
				else
					header ( "Content-type: text/xml;charset=utf-8" );
				echo $out->xml;
			}
		}
	}

	function event() {
		$crud = new grocery_CRUD ();
		$crud->set_table ( 'f_event' );
		$crud->set_subject ( 'Тохиолдол' );
		$crud->add_fields ( 'event' );
		$crud->required_fields ( 'event' );
		$crud->columns ( 'event' );
		$crud->display_as ( 'event', 'Тохиолдол' );
		
		$output = $crud->render ();
		$this->view_out ( $output );
	}
	function comptype() {
		$crud = new grocery_CRUD ();
		$crud->set_table ( 'f_completion_type' );
		$crud->set_subject ( 'Гүйцэтгэлийн төрөл' );
		$crud->add_fields ( 'completion_type' );
		$crud->required_fields ( 'completion_type' );
		$crud->columns ( 'completion_type', 'sord' );
		$crud->display_as ( 'completion_type', 'Гүйцэтгэлийн төрөл' );
		
		$output = $crud->render ();
		$this->view_out ( $output );
	}
	function reason() {
		$crud = new grocery_CRUD ();
		$crud->set_table ( 'f_reason' );
		$crud->set_subject ( 'Шалтгаан ' );
		$crud->add_fields ( 'reason' );
		$crud->required_fields ( 'reason' );
		$crud->columns ( 'reason' );
		$crud->display_as ( 'reason', 'Шалтгаан' );
		
		$output = $crud->render ();
		$this->view_out ( $output );
	}
	function view_out($output = null) {
		$this->load->view ( 'settings.php', $output );
	}
	
	// fault tree here
	function tree($equipment_id) {
		// $this->output->enable_profiler();
		$js_ar = array ();
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/ftree/js/jquery-1.11.1.min.js', TRUE ) );
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/ftree/js/jquery-migrate-1.2.1.min.js', TRUE ) );
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/ftree/js/jquery-ui.js', TRUE ) );
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/ftree/js/jquery.tree.js', TRUE ) );
		$this->my_model_old->set_table ( 'equipment2' );
		$equipment = $this->my_model_old->get_row ( 'equipment', array (
				'equipment_id' => $equipment_id 
		) );
		
		$libraries = array (
				'js' 
		);
		$libraries ['js_files'] = $js_ar;
		
		$store_all_id = array ();
		$query = $this->db->query ( "SELECT * FROM f_tree where equipment_id = $equipment_id" );
		foreach ( $query->result_array () as $row ) {
			// for($all_id = $query->row_array())
			array_push ( $store_all_id, $row ['parent'] );
		}
		
		// var_dump($store_all_id);
		$tree = '';		
		$tree .= "<div class='overflow'><div>";
		$tree .= $this->ftree_model->in_parent ( 0, $equipment_id, $store_all_id );		
		$tree .= "</div></div>";
		
		$data ['equipment'] = $equipment;
		$data ['equipment_id'] = $equipment_id;
		$data ['ftree'] = $tree;
		$data ['libraries'] = $libraries;
		
		$data ['page'] = 'ftree\ftree';
		$data ['title'] = "Гэмтлийн мод";
		$this->load->view ( 'index', $data );
	}

	//new treeview here I/13 vertical viewed
	function tree_v($equipment_id){
		$js_ar = array ();
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/treeview/js/jquery.treeview.js', TRUE ) );
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/treeview/js/jquery.cookie.js', TRUE ) );
		array_push ( $js_ar, $this->javascript->external ( base_url().'/assets/treeview/js/jquery.ui-contextmenu.js', TRUE ) );
		$libraries = array ('js');

		$this->my_model_old->set_table ( 'equipment2' );
		$equipment = $this->my_model_old->get_row ( 'equipment', array (
				'equipment_id' => $equipment_id 
		) );
		
		$store_all_id = array ();		
		$query = $this->db->query ( "SELECT * FROM f_tree where equipment_id = $equipment_id" );
		foreach ( $query->result_array () as $row ) {
			// for($all_id = $query->row_array())
			array_push ( $store_all_id, $row ['parent'] );
		}
		 //var_dump($store_all_id);
//		 echo $this->db->last_query();
		$query->free_result ();

		$tree = '';
		$tree .= "<div class='tree'>";
		// $tree .= "<div class='subtree'>	
		// 	<h4>Системд шууд хамааралгүй дэд системүүд</h4>
		// 	<ul>
		// 	<li><span style='padding: 3px 10px; color:#fff; background:#27a9e2;'>Бичлэгийн систем</span></li>
		// 	<li><span style='padding: 3px 10px; color:#fff; background:#27a9e2; margin-top:5px;'>Удирдлагийн блок </span></li>			
		// 	</ul>			
		// 	</div>";

		$tree .= $this->ftree_model->tree_parent ( 0, $equipment_id, $store_all_id );
		$tree .= "</div>";

		$libraries ['js_files'] = $js_ar;
		$data ['equipment'] = $equipment;
		$data ['equipment_id'] = $equipment_id;
		$data ['tree'] = $tree;
		$data ['libraries'] = $libraries;

		$data ['page'] = 'ftree\tree_v';
		$data ['title'] = "Алдааны мод";
		$this->load->view ( 'index', $data );
	}

	function add_node() {
		$parentid = $this->input->get_post ( 'parentid' );
		$module = $this->input->get_post ( 'module' );
		$event_id = $this->input->get_post ( 'event_id' );
		$gate = $this->input->get_post ( 'gate' );
		// utguudiig validate hiine
		// herev validation zuv bol insert hiine
		// database insert
		// herev validation bish bol aldaa ugnu
	}
	
	// ajax function
	function ajax() {
		$action = $this->input->get_post ( 'action' );
		$equipment = $this->main_model->getEquipment ();
		
		$this->my_model_old->set_table ( 'f_event' );
		$event = $this->my_model_old->get_select ( 'event' );
		$event [0] = 'Event сонгох..';
		
		$event_dpdw = form_dropdown ( 'event_id', $event, 0, "class='event' " );
		
		$node [null] = 'Мөчир төрөл сонгох..';
		$node ['basic'] = 'Basic - Үндсэн';
		$node ['undevelop'] = 'Undeveloped - Тодорхой бус';

		// type of node
		$node_type = form_dropdown ( 'node_type', $node, null, "class='node_type'" );
		
		// $dropdown = form_dropdown ( 'equipment_id', $equipment );
		// equipment by dropdown
		// event by dropdown
		// basic event
		
		$form = "<form class='add_data' method='post' action='' autocomplete='off'>           
                <img class='close' src='".base_url()."assets/ftree/images/close.png' />
                <input type='text' class='first_name' name='module'/>
                $node_type

                $event_dpdw               
                
                <select name='gate'>                
                <option value='And'>And</option>
                <option value='OR'>OR</option>                
                </select>
                <input type='submit' class='submit' name='submit' value='Нэмэх'>
            </form>";
		
		echo $form;
	}
	function edit_node() {
		$this->my_model_old->set_table ( 'f_event' );
		$event = $this->my_model_old->get_select ( 'event' );
		$event [0] = 'Event сонгох..';
		// ftree_id-r		
		$ftree_id = $this->input->get_post ( 'edit_ele_id' );

		// ftree_id gaar buh utguudiig avaht
		$this->my_model_old->set_table ( 'f_tree' );
		$result = $this->my_model_old->get_result ( array (
				'id' => $ftree_id 
		) );
		foreach ( $result as $row ) {
			$event_id = $row->event_id;
			$module = $row->module;
			$gate = $row->gate;
		}

		//node type select option
		$node [null] = 'Мөчир төрөл сонгох..';
		$node ['basic'] = 'Basic - Үндсэн';
		$node ['undevelop'] = 'Undeveloped - Тодорхой бус';

		// type of node
		$node_type = form_dropdown ( 'node_type', $node, null, "class='node_type'" );
		
		$event_dpdw = form_dropdown ( 'event_id', $event, $event_id, "class='event' " );
		
		$form = "<form class='edit_data' method='post' action=''>           
                <img class='close' src='".base_url()."assets/ftree/images/close.png' />
                <input type='text' class='first_name' name='module' value='$module'/>                
                $node_type

                $event_dpdw
                <select name='gate'>                
                  <option value='And'>And</option>
                  <option value='OR'>OR</option>
                  <option value='IF'>IF</option>
                </select>                
                <input type='submit' class='edit' name='submit' value='Засах'/>    
            </form>";
		echo $form;
	}
	
	function copy($copy_id, $target_id) {
		$row_bind = array ();
		$crow = array ();
		$c_result = $this->new_model->get_result ( array (
				'equipment_id' => $copy_id 
		), 'f_tree' );
		foreach ( $c_result as $row ) {
			$crow ['id'] = $row->id;
			$crow ['new_id'] = null;
			$crow ['parent'] = $row->parent;
			$crow ['equipment_id'] = $target_id;
			$crow ['module'] = $row->module;
			$crow ['event_id'] = $row->event_id;
			$crow ['level'] = $row->level;
			$crow ['gate'] = $row->gate;
			
			array_push ( $row_bind, $crow );
		}
		echo "target_id" . $target_id;
		// var_dump($row_bind);
		var_dump ( $row_bind [0] );
		echo $row_bind [0] ['id'];
		echo "sizeof: ";
		echo "<br>";
		
		$data = array ();
		
		for($i = 0; $i < sizeof ( $row_bind ); $i ++) {
			// $data
			$data = $row_bind [$i];
			unset ( $data ['new_id'] );
			unset ( $data ['id'] );
			$new_id = $this->new_model->insert ( $data, 'f_tree' );
			$row_bind [$i] ['new_id'] = $new_id;
		}
		var_dump ( $row_bind [0] );		
	}

	//get logic ajax
	function jx_logic(){
		$errors=array();
		$id = $this->input->get_post('id');
		$equipment_id = $this->input->get_post('equipment_id');
		//session uussen esehiig shalgana
		//array_push($errors, $id);
		if(!$this->session->userdata('error_'.$equipment_id)){
		  $this->session->set_userdata('error_'.$equipment_id, $errors);
		}//else{
		  //var_dump($this->session->userdata('error_'.$equipment_id));		  
		//}
		$logic ='';

		if ($id){
			//$logic = $this->ftree_model->get_logic ($id, $equipment_id, $id, '' );
			$result = $this->ftree_model->calc_logic($id, $equipment_id, $id, '' );			
			$error =$this->session->userdata('error_'.$equipment_id);			
			$status = array (
					'status' => 'success',
					'id' => $id, 
					'logic' =>$result, 
					'error'=>$error
			);
		}else
			$status = array (
					'status' => 'failed',
					'node' => 'Алдааны модны мөчрөөс сонгогдоогүй байна!' 
			);
		echo json_encode ( $status ); 		 
	}

	function jx_reset(){
		$equipment_id = $this->input->get_post('equipment_id');
		$this->session->unset_userdata('error_'.$equipment_id);
		if(!$this->session->userdata('error_'.$equipment_id)){
		//if($equipment_id){
			$status = array (
				'status' => 'success',
				'equipment_id' =>$equipment_id
			);
		}else
			$status = array (
				'status' => 'failed',					
				'equipment_id' =>$equipment_id
			);
		print_r($this->session->userdata('error_'.$equipment_id));
		echo json_encode ( $status ); 	
	}

}