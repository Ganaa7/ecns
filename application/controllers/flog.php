<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class flog extends CNS_Controller {
	
	public static $user_seccode;
	
	public static $user_section_id;
	

	public $objdata;
	
	function __construct() {

	   parent::__construct ();
		
	   $objdata = ( object ) array ();
		
	   $this->load->model ( 'ftree_model' );        
		
       $this->load->model ( 'flog_model' );
       
       $this->load->model ( 'new_model' );

		  // ------------------- FILE ---------------------//
	   $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'flog', $this->role, 0, 1 ) );
	    
	   $this->config->set_item ( 'module_menu', 'Гэмтэл дутагдлын бүртгэл' );

	   $this->config->set_item ( 'module_menu_link', '/flog' );

	   $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
	      
	   $this->user_seccode = $this->session->userdata ( 'sec_code' );
	     
	   $this->user_section_id = $this->session->userdata ( 'section_id' );
	   
	   $this->user_id = $this->session->userdata ( 'employee_id' );

	   $this->group = $this->user_model->setGroup ( $this->user_seccode );
			
	}

	function index() {

		$this->main_model->access_check ();

		$data ['library_src'] = $this->javascript->external ( base_url().'assets/flog/js/flog.js', TRUE );	
		
		$log = new f_Log ();

		$log->set_user ( $this->user_id );

      	$log->set_section ( $this->user_section_id );                

		$log->set_role ( $this->role );

		$out = $log->run ();
		
		if ($out->view) {

			$data ['out'] = $out;

			$data ['page'] = 'flog\index';

			$data ['form'] = $out->form;

			$data ['title'] = "Гэмтэл дутагдлын бүртгэл";

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

	function ftree($equipment_id) {
		
		$js_ar = array ();
		
		array_push ( $js_ar, $this->javascript->external ( base_url().'assets/ftree/js/jquery-1.11.1.min.js', TRUE ) );
		
		array_push ( $js_ar, $this->javascript->external ( base_url().'assets/ftree/js/jquery-migrate-1.2.1.min.js', TRUE ) );
		
		array_push ( $js_ar, $this->javascript->external ( base_url().'assets/ftree/js/jquery-ui.js', TRUE ) );
		
		array_push ( $js_ar, $this->javascript->external ( base_url().'assets/ftree/js/jquery.tree_2.js', TRUE ) );
		
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
		$data ['ftree'] = $tree;
		$data ['libraries'] = $libraries;
		
		$data ['page'] = 'ftree\ftree';
		$data ['title'] = "Гэмтлийн мод";
		$this->load->view ( 'index', $data );
	}
	
	function f_equipment() {
		if ($this->main_model->get_authority ( 'flog', 'flog', 'f_equipment', $this->role ) == 'f_equipment') {
			$crud = new grocery_CRUD ();
			$crud->set_subject ( 'Тоног төхөөрөмж' );
			$crud->set_table ( 'f_equipment' );
			$crud->columns ( 'id', 'equipment', 'sord' );
			$crud->display_as ( 'id', '#' )->display_as ( 'equipment', 'Тоног төхөөрөмжийн нэр' )->display_as ( 'sord', 'Дараалал' );
			
			$this->load->view ( 'flog/output', $crud->render () );
		} else {
			$this->load->view ( '43.html' );
		}
	}
	
	function loc_equip() {
		try {
			if ($this->main_model->get_authority ( 'flog', 'flog', 'loc_equip', $this->role ) == 'loc_equip') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'loc_equip' );
				$role_array = array (
						'ENG',
						'SUPENG',
						'TECH',
						'UNITCHIEF',
						'CHIEF' 
				);
				// ENGINEER GROUP

				// ECHO $role;
				if (! in_array ( $this->role, array (
						'TECHENG',
						'UNITCHIEF',
						'CHIEF',
						'ADMIN',
						'SUPENG' 
				) )) {
					$crud->unset_add ();
					$crud->unset_edit ();
					$crud->unset_delete ();
					$crud->field_type ( 'section_id', 'hidden' );

					$crud->callback_field ( 'section_id', array (
						$this,
						'section_field_callback' 
					) );

					$crud->field_type ( 'section_id', 'hidden' );
				}
				
				// $crud->unset_add_fields('training');
				$crud->columns ( 'location_id', 'equipment_id', 'section_id' );
				$crud->fields ( 'location_id', 'equipment_id', 'section_id' );

				$crud->required_fields('location_id', 'equipment_id', 'location_id');
				$crud->set_subject ( 'Тоног төхөөрөмж' );
				$crud->set_relation ( 'section_id', 'section', 'name' );
				$crud->set_relation ( 'equipment_id', 'equipment2', 'equipment', array('is_group!=' => 'y'));
				$crud->set_relation ( 'location_id', 'location', 'name' );
				// $crud->unset_back_to_list();
				$crud->display_as ( 'equipment_id', 'Тоног төхөөрөмж' )->display_as ( 'section_id', 'Хэсэг' )->display_as ( 'location_id', 'Байршил' );
				
				if (in_array ( $this->role, $role_array )) {
					$crud->where ( 'loc_equip.section_id', $this->user_section_id );
				}
			
				// $crud->required_fields( 'equipment', 'intend', 'section_id', 'code', 'power', 'frequency');
				$crud->order_by ( 'location_id', 'desc' );
				

				$crud->callback_field ( 'equipment_id', array (
						$this,
						'equipment_field_callback' 
				) );

				$crud->callback_before_insert ( array (
						$this,
						'insert_locequip_callback' 
				) );

				$crud->callback_before_update ( array (
						$this,
						'update_locequip_callback' 
				) );
				
				$output = $crud->render ();
				$this->load->view ( 'flog/output', $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	function insert_locequip_callback($post_array) {
		$equipment_id = $post_array ['equipment_id'];
		$section_id = $this->new_model->get_row ( 'section_id', array (
				'equipment_id' => $equipment_id 
		), 'equipment2' );
		$post_array ['section_id'] = $section_id;
		return $post_array;
	}

	function update_locequip_callback($post_array) {

		$equipment_id = $post_array ['equipment_id'];
		$post_array ['section_id'] = $this->new_model->get_row ( 'section_id', array (
				'equipment_id' => $equipment_id 
		), 'equipment2' );
		return $post_array;
	}
	function section_field_callback($section_id) {
		return '<input type="hidden" value="' . $section_id . '" name="section_id">';
	}
	function equipment_field_callback($equipment_id) {
		// equipment_id - r filter hiij dropdown uusgeh
		// herev user_section_id in (1, 2, 3, 4) bval filter hii
		if (in_array ( $this->user_section_id, array (
				1,
				2,
				3,
				4 
		) ))
			$equipments = $this->new_model->get_select ( 'equipment', array (
					'section_id' => $this->user_section_id , "is_group" =>NULL
			), 'equipment2' );
		else
			$equipments = $this->new_model->get_select ( 'equipment', array('is_group'=>NULL), 'equipment2' );
		$equipments ['0'] = "Доорх жагсаалтаас сонго!";
		
		return form_dropdown ( 'equipment_id', $equipments, null, "id = 'equipment_id'" );
	}

	function comp_equip() {
		if ($this->main_model->get_authority ( 'flog', 'flog', 'comp_equip', $this->role ) == 'comp_equip') {
			$crud = new grocery_CRUD ();
			$crud->set_subject ( 'Компани' );
			$crud->set_table ( 'f_org_equip' );
			$crud->columns ( 'company', 'location_id', 'equipment_id' );
			$crud->set_relation ( 'equipment_id', 'f_equipment', 'equipment' );
			$crud->set_relation ( 'location_id', 'location', 'name' );
			$crud->display_as ( 'company', 'Компани, байгууллага' )->display_as ( 'location_id', 'Байршил' )->display_as ( 'equipment_id', 'Тоног төхөөрөмж' );
			
			$this->load->view ( 'flog/output', $crud->render () );
		} else {
			$this->load->view ( '43.html' );
		}
	}
	function equip_comp() {
		if ($this->main_model->get_authority ( 'flog', 'flog', 'equip_comp', $this->role ) == null) {
			$crud = new grocery_CRUD ();
			$crud->set_subject ( 'Тоног төхөөрөмжийн компани байршил' );
			$crud->set_table ( 'f_equip_comp' );
			$crud->columns ( 'id', 'equipment_id', 'location_id', 'company_id' );
			$crud->display_as ( 'id', '#' )->display_as ( 'location_id', 'Байршил' )->display_as ( 'company_id', 'Компани' )->display_as ( 'equipment_id', 'Тоног төхөөрөмж' );
			
			$crud->set_relation ( 'equipment_id', 'f_equipment', 'equipment' );
			$crud->set_relation ( 'location_id', 'location', 'name' );
			$crud->set_relation ( 'company_id', 'f_company', 'company' );
			
			$this->load->view ( 'flog/output', $crud->render () );
		} else {
			$this->load->view ( '43.html' );
		}
	}

	function help() {
		$data ['title'] = 'Гэмтэл /Шинэ/ бүртгэл тусламж';
		$this->load->view ( 'flog\help', $data );
	}

	//get logic ajax
	function jx_logic(){
		$errors=array();
		$id = $this->input->get_post('id');
		$equipment_id = $this->input->get_post('equipment_id');
		//location_id-g nemsen!
		$location_id = $this->input->get_post('location_id');
		//session uussen esehiig shalgana
		//array_push($errors, $id);
		if(!$this->session->userdata('error_'.$location_id.$equipment_id)){
		   $this->session->set_userdata('error_'.$location_id.$equipment_id, $errors);
		}
		$logic ='';

		if ($id){
			//edited 
			//$result = $this->ftree_model->calc_logic($id, $equipment_id, $id, '' );			
			$result = $this->flog_model->calc_logic($id, $location_id, $equipment_id, $id, '' );			
			$error =$this->session->userdata('error_'.$location_id.$equipment_id);						
			$status = array (
					'status' => 'success',
					'id' => $id, 
					'logic' =>$result, 
					'error'=>$error,
					'location_id'=>$location_id
			);
		}else
			$status = array (
					'status' => 'failed',
					'node' => 'Алдааны модны мөчрөөс сонгогдоогүй байна!' 
			);
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $status ); 		 
	}
	
	//reserve logic
	function jx_reverse(){		
		$id = $this->input->get_post('id');
		$equipment_id = $this->input->get_post('equipment_id');
		// added by logic
		$location_id = $this->input->get_post('location_id');

		//! if there is equipment_id is stored parent_error equipment should be removed
		if($this->session->userdata('parent_error_'.$location_id.$equipment_id)){
		   $parent_error = $this->session->userdata('parent_error_'.$location_id.$equipment_id);
		    if(($key = array_search($id, $parent_error)) !== false) {
		       unset($parent_error[$key]);
		       $this->session->unset_userdata('parent_error_'.$location_id.$equipment_id);

		       $this->session->set_userdata('parent_error_'.$location_id.$equipment_id, $parent_error);		       
			}
		}
		//ftree chnaged flog_model
		$result = $this->flog_model->rev_logic($id, $location_id, $equipment_id, '');
		$status = array (
					'status' => 'success',
					'id' => $id, 
					'result' =>$result					
		);
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $status ); 
	}

	//esreg logic-g tootsoh buyu false uguh tohioldold	
	// Тухайн basic || бусад элементүүдийн түвшиний элементүүд-р 
	//нийт модод алдаа өгсөн эсэхийг шалгана, мөн parent дээр алдаа өгсөн эсэхийг шалгана.
	// хэрэв Мод бүгд алдаа өгсөн бол done эсрэг тохиолдолд тухайн Parent алдаа өгсөн эсэхийг шалгана! 

	function jx_rev_logic(){
		// Туайн session-д байгаа утгуудаар Unselect id -с бусад id nuud deer f_logic_true bolson eseh-g shalgah function!!!
		$errors=array();
		$id = $this->input->get_post('id');
		$equipment_id = $this->input->get_post('equipment_id');

		//! if there is equipment_id is stored parent_error equipment should be removed
		if($this->session->userdata('parent_error_'.$equipment_id)){
		   $parent_error = $this->session->userdata('parent_error_'.$equipment_id);
		    if(($key = array_search($id, $parent_error)) !== false) {
		       unset($parent_error[$key]);
		       $this->session->unset_userdata('parent_error_'.$equipment_id);

		       $this->session->set_userdata('parent_error_'.$equipment_id, $parent_error);		       
			}
		}
		
		$failed_nodes =$this->session->userdata('error_'.$equipment_id);		
		
		//var_dump($failed_nodes);

		if(($key = array_search($id, $failed_nodes)) !== false) {
		    unset($failed_nodes[$key]);
			$this->session->set_userdata('error_'.$equipment_id, $failed_nodes);
		}

		$failed_nodes =$this->session->userdata('error_'.$equipment_id);
		
		//echo "second:";
		//var_dump($failed_nodes);

		// 2. тухайн id-р тухайн элементүүдийн true эсэхийг шалгаж үзнэ.
		$status = false;
		if($failed_nodes){
			foreach ($failed_nodes as $key => $value) {
				$result = $this->ftree_model->calc_logic($value, $equipment_id, $id, '' );
				if($result['result']=='true'){				
					$status=true;				
					break;				
				}else 
				   $status=false;
			}
			$failed_nodes =$this->session->userdata('error_'.$equipment_id);	
			$return = array (
					'status' => 'success',
					'id' => $id, 
					'logic' =>$result, 
					'error'=>$failed_nodes,
					's'=>$status
			);	
		}else
			$return = array (
					'status' => 'success',
					'id' => $id, 
					'logic' =>false, 
					'error'=>0,
					's'=>$status
			);	

		echo json_encode ( $return ); 		 
	}

	function jx_reset(){
		$equipment_id = $this->input->get_post('equipment_id');
		$location_id = $this->input->get_post('location_id');

		$this->session->unset_userdata('parent_error_'.$location_id.$equipment_id);

		//parent error-g bas ustgah		
		$this->session->unset_userdata('error_'.$location_id.$equipment_id);

		if(!$this->session->userdata('error_'.$location_id.$equipment_id)){
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
		print_r($this->session->userdata('error_'.$location_id.$equipment_id));
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $status ); 	
	}

	//remove parent
	function rm_prt(){
		$equipment_id = $this->input->get_post('equipment_id');
		$id = $this->input->get_post('id');
		$result = $this->ftree_model->rm_parent($id, $equipment_id);
		//зөвхөн тухайн node-n parent-diig session-с устгах ёстой!
		if($result)
			$status = array (
				'status' => 'success',
				'equipment_id' =>$equipment_id
			);
		echo json_encode ( $status ); 
	}

	//parent has error
	function jx_pr_error(){
		$p_error = array();
		$equipment_id = $this->input->get_post('equipment_id');
		
		//added soon!!!
		$location_id = $this->input->get_post('location_id');
		
		//node_id
		$id = $this->input->get_post('id');
		$node_type = $this->input->get_post('node_type');
		
		if(!$this->session->userdata('parent_error_'.$location_id.$equipment_id)){
		   $this->session->set_userdata('parent_error_'.$location_id.$equipment_id, $p_error);
		}
		
		$p_error= $this->session->userdata('parent_error_'.$location_id.$equipment_id);
		// herev array_d parent_ burtgeegui bol bol burtgene!
		
		//if this parent's been registered parent_error 
		// not allowed to add to the parent _error 
		//! changed ftree_model = flog_model
		if($this->flog_model->chk_parent_error($id, $location_id, $equipment_id)){
			// if parent has error don't store thsi id
			$status = array (
				'status' => 'not_allowed',
				'p_error' =>$this->session->userdata('parent_error_'.$location_id.$equipment_id)		
			);			
		}else if($node_type=='not_basic'){			
			if(!in_array($id, $p_error)){	// if not registered	
			     array_push($p_error, $id);		     
			     $this->session->set_userdata('parent_error_'.$location_id.$equipment_id, $p_error);
			     $status = array (
					'status' => 'success',
					'p_error' =>$p_error
				);
			}else{ // else registered				
				$status = array (
					'status' => 'not_allowed',
					'p_error' =>$p_error
				);
			} 	
		}else{ //basic
			$status = array (
				'status' => 'success', 'error'=>$p_error				
			);
		}		
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $status ); 	
	}

	// function it's has registered error
	function jx_unchk_pr_error(){		
		$p_error = array();
		$equipment_id = $this->input->get_post('equipment_id');
		
		$location_id = $this->input->get_post('location_id');

		$id = $this->input->get_post('id');
		$node_type = $this->input->get_post('node_type');

		$p_error= $this->session->userdata('parent_error_'.$location_id.$equipment_id);
		
		// echo "equipment".$equipment_id;
		// var_dump($p_error);
		if($p_error){
			if(in_array($id, $p_error)){	// if registered			     
				if(($key = array_search($id, $p_error)) !== false) {
			       unset($p_error[$key]);
			       $this->session->set_userdata('parent_error_'.$location_id.$equipment_id, $p_error);
				}
			    $status = array (
					'status' => 'success',
					'stored' =>true
				);
			}else
				$status = array (
					'status' => 'success',
					'stored' =>false
				);				
		}else
			$status = array (
					'status' => 'success',
					'stored' =>false
				);				
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $status ); 
	}

	//function create 
	function report() {
		// hesgiin dargaas uur bol
		if ($this->session->userdata ( 'user_type' ) == 'govern') {
			$section_id = $this->input->get_post('section_id', TRUE);

			if (isset ($section_id ) && $section_id != 0) {
				$table = 'vw_flog'; // $this->user_model->set_table($_POST['sec_code']);
				$where = array('section_id' => $section_id);
				$data ['equipment'] = $this->new_model->get_select('equipment', array('section_id' => $section_id), 'equipment2');		
				$data ['equipment'][0]='БҮГД';
				// $sec_code = $_POST ['sec_code'];
			} else {
				$where = array('section_id' =>0);
				$table = 'vw_flog';
				// $sec_code = $this->session->userdata('sec_code');
				$data ['equipment'] = $this->new_model->get_select('equipment', null, 'equipment2');		
				$data ['equipment'][0]='БҮГД';
			}
			// if($this->input->get_post('sec_code')) $sec_code =$_POST['sec_code'];
			// else $sec_code = $this->session->userdata('sec_code');
			// $table ='view_logs';
			$data ['section'] = $this->new_model->get_select('name', array('type' =>'industry'), 'section');
			$data ['section'][0] ='БҮГД';	
			// hesgiin darga bol
		} else {
			$sec_code = $this->session->userdata ( 'sec_code' ); // elc
			$section_id = $this->session->userdata ( 'section_id' ); // elc
			// $this->user_model->set_table($sec_code);
			$data ['section'] = $this->new_model->get_select('name', array('code' => $sec_code, 'type'=>'industry' ), 'section');
			$data ['section'][0] ='БҮГД';
			$data ['equipment'] = $this->new_model->get_select('equipment', array('section_id' =>$section_id), 'equipment2');
			$data ['equipment'][0]='БҮГД';
                        $table = 'vw_flog';
		}
		$data ['section_id'] = $section_id;		
				
		 if ($this->input->get_post ( 'equipment_id' )){
			$equipment_id = $this->input->get_post ( 'equipment_id' );		
		 	$data ['equipment_id'] = $this->input->get_post ( 'equipment_id' );
		 }else
		 	$data ['equipment_id'] = 0;
		
		if (isset ( $_POST ['log'] )) {
			$data ['log'] = $_POST ['log'];
		} else
			$data ['log'] = '0';
			
			// begin filter
		if ($this->input->get_post ( 'filter' )) {
			$start_dt = $this->input->get_post ( 'start_date' );
			$end_dt = $this->input->get_post ( 'end_date' );
			
			$data ['section_id'] = $section_id;
			$data ['table'] = $table;
			$data ['filter'] = 1;

			if ($start_dt && $end_dt) {
				$data ['start_date'] = $start_dt;
				$data ['end_date'] = $end_dt;
				$between = "((DATE_FORMAT(closed_dt, '%Y-%m-%d') >= '$start_dt' AND DATE_FORMAT(closed_dt, '%Y-%m-%d') <='$end_dt')
                          OR (DATE_FORMAT(created_dt, '%Y-%m-%d') >= '$start_dt' AND DATE_FORMAT(created_dt, '%Y-%m-%d') <='$end_dt'))";
			} else {
				$data ['start_date'] = 0;
				$data ['end_date'] = 0;
			}
			
			if ($section_id == 1 || $section_id == 2 || $section_id == 3 || $section_id == 4)
				$data ['sections'] = $this->db->get_where ( 'view_industry', array (
						'section_id' => $section_id 
				) )->result ();
			else {
				$data ['sections'] = $this->db->get ( 'view_industry' )->result ();
			}

			//var_dump($data['sections']);			
			foreach ( $data ['sections'] as $row ) {
				// Холбооны утгуудыг авах
				$this->db->select ( '*' );
				$this->db->from ( 'vw_flog' );
				$this->db->where ( 'section_id', $row->section_id );
				
				if ($this->input->get_post ( 'equipment_id' ))
					$this->db->where ( 'equipment_id ', $this->input->get_post ( 'equipment_id' ) );
				if ($this->input->get_post ( 'log' )) {
					if($this->input->get_post ( 'log' ) =='Y')
					   $this->db->where_in( 'status', array('Y', 'Q', 'F'));
					else 
						$this->db->where( 'status', $this->input->get_post('log'));
				}
				if (isset ( $between )) {
					$this->db->where ( $between );
					$data ['date'] = "$start_dt-с $end_dt хооронд";
				} else
					$data ['date'] = NULL;
				$data [$row->sec_code] = $this->db->get ()->result ();
			}
		} else
			$data ['date'] = NULL;
		// $data ['sec_code'] = $sec_code;
		$data ['last_sql'] = $this->db->last_query ();
		// $data ['equipment'] = $this->main_model->get_equipments ( $sec_code );
		$data ['title'] = 'Тоног төхөөрөмжийн тайлан';
		$this->load->view ( 'flog/report/report_dtl', $data );
	}

	function export_xls($section_id, $equipment_id, $log, $start_date, $end_date) {	
		//check validation it has report authentication
			
		date_default_timezone_set("Asia/Ulan_Bator");
		$name = date('ymd');

		echo "<head>";
		echo "<meta content='text/html'; charset='utf-8' http-equiv='Content-Type'>
                <title>eCNS::ТХНҮА Цахим бүртгэлийн систем</title>";
		echo "</head>";
		echo "<div id='container'><div><p>";
		if (!$section_id) {
			$data ['sections'] = $this->db->get ( 'view_industry' )->result ();
			//$table = 'vw_flog';
		} else {
			$data ['sections'] = $this->db->get_where ( 'view_industry', array (
					'section_id' => $section_id
			) )->result ();
			// $table = $this->user_model->set_table ( $sec_code );
		}
		// $table='view_logs';
		//var_dump($data['sections']);
		
		$modified = $this->session->userdata ( 'username' );
		error_reporting ( E_ALL );
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$this->load->helper ( 'PHPExcel' );
		/**
		 * Include PHPExcel
		 */
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		
		// Set document properties
		// echo date('H:i:s') , " Хуудасны утгуудыг өглөө" , PHP_EOL;
		// echo "<br/>";
		$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Shiftlog report" )->setSubject ( "Shiftlog Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'C1', 'Гэмтэл дутагдлын тайлан' );
		
		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
		
		$i = 3;
		$start_border;
		static $end_border;
		foreach ( $data ['sections'] as $row ) {
			// Холбооны утгуудыг авах
			$this->db->select ( '*' );
			$this->db->from ( 'vw_flog' );
                if (isset ( $section_id ) && $section_id != 0)
			$this->db->where ( 'section_id', $row->section_id );
                else 
                        $this->db->where('section_id', $row->section_id);


                    echo $this->db->last_query();
			
			// доорх утгуудыг xls файлаар харуулахад алдаа гарч байна.
			// 2015-10-27нд засав
			// $temp_table= $this->user_model->set_table($row->sec_code);
			//
			// if($sec_code=='0')
			// $this->db->from($temp_table);
			// else {
			// $this->db->from($table);
			// $this->db->where('sec_code', $sec_code);
			// }
			if (isset ( $equipment_id ) && $equipment_id != 0)
				$this->db->where ( 'equipment_id ', $equipment_id );
			// нээгдээгүй байвал
			if (isset ( $log ) && $log == 'A') {
				$this->db->where ( 'status', $log );
				$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Гэмтлийн №' )->setCellValue ( 'B2', 'Гэмтэл нээсэн огноо/цаг' )->setCellValue ( 'C2', 'Байршил' )->setCellValue ( 'D2', 'Төхөөрөмж' )->setCellValue ( 'E2', 'Шалтгаан' )->setCellValue ( 'F2', 'Гэмтэл' )->setCellValue ( 'G2', 'Гэмтэл нээсэн' );
				
				$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G2' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setARGB ( 'a9a9a9' );
				
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 12 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 18 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 14 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 12 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 17 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 22 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setWidth ( 15 );
			} else {
				$this->db->where_in( 'status', array('Y', 'Q', 'F'));			
				$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Гэмтлийн №' )->setCellValue ( 'B2', 'Гэмтэл нээсэн огноо/цаг' )->setCellValue ( 'C2', 'Байршил' )->setCellValue ( 'D2', 'Төхөөрөмж' )->setCellValue ( 'E2', 'Шалтгаан' )->setCellValue ( 'F2', 'Гэмтэл' )->setCellValue ( 'G2', 'Гэмтэл нээсэн' )->setCellValue ( 'H2', 'Гэмтэл хаасан огноо/цаг' )->setCellValue ( 'I2', 'Үргэлжилсэн хугацаа ' )->setCellValue ( 'J2', 'Гүйцэтгэл' )->setCellValue ( 'K2', 'Гэмтэл хаасан' );
				
				$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K2' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setARGB ( 'a9a9a9' );
				
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 9 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 10 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 11.5 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 10.3 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 13.5 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 16 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setWidth ( 11 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'H' )->setWidth ( 10 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'I' )->setWidth ( 8 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'J' )->setWidth ( 25 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'J' )->setWidth ( 11 );
			}
			
			if ($start_date && $end_date) {
				$between = "((DATE_FORMAT(closed_dt, '%Y-%m-%d') >= '$start_date' AND DATE_FORMAT(closed_dt, '%Y-%m-%d') <='$end_date')
                          OR (DATE_FORMAT(created_dt, '%Y-%m-%d') >= '$start_date' AND DATE_FORMAT(created_dt, '%Y-%m-%d') <='$end_date'))";
				
				$this->db->where ( $between );
				$date = "$start_date-с $end_date хооронд";
			} else
				$data ['date'] = NULL;
			
			$sections = $this->db->get()->result ();
			//echo $this->db->last_query();
			
			$cnt = 1;
			foreach ( $sections as $cols ) {
				if ($cnt == 1) {
					if ($cols->section == 'Холбоо') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Холбооны хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
						$start_border = $i;
					}
					
					if ($cols->section == 'Навигаци') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Навигацын хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					if ($cols->section == 'Ажиглалт') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Ажиглалтын хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					if ($cols->section == 'Гэрэл суулт, цахилгаан') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Гэрэл суулт цахилгааны хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					$i ++;
					$cnt = 0;
				}
				if ($log == 'A') {
					$end_border = $i;
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->log_num );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->created_dt );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->location );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->equipment );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->reason );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->node );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $i, $cols->createdby );
					$i ++;
				} else {
					$end_border = $i;
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->log_num );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->created_dt );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->location );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->equipment );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->reason );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->node );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $i, $cols->createdby );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $i, $cols->closed_dt );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $i, $cols->duration );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $i, $cols->completion );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $i, $cols->closedby );
					$i ++;
				}
			}
		}
				
		$objPHPExcel->getActiveSheet ()->setTitle ( 'Report' );
		
		$styleArray = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
								'color' => array (
										'argb' => '000000' 
								) 
						) 
				) 
		);
		if ($log == 'A') {
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->applyFromArray ( $styleArray );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getAlignment ()->setWrapText ( true );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getFont ()->setSize ( 9 );
		} else {
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->applyFromArray ( $styleArray );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:K' . $end_border )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->getAlignment ()->setWrapText ( true );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->getFont ()->setSize ( 10 );
		}
		
		
		$objPHPExcel->setActiveSheetIndex ( 0 );
		        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\log_files\report\report_'.$name.'.xlsx', __FILE__ ) );
		
		//$sections = $this->db->get ()->result ();

		
		$url = base_url ();
		
		// echo anchor($url.'download/report.xlsx', 'XLS file-г хадгалах', 'title="News title"');
		// echo "</p></div></div>";
		echo $url;
		redirect ( $url . 'download/log_files/report/report_'.$name.'.xlsx' );
		
		
		//force_download($name, $data);
	}

	function export(){
			
		date_default_timezone_set("Asia/Ulan_Bator");
		
		$name = date('ymd');
		$this->load->helper ( 'PHPExcel' );

		//its logged user or not check authentication
		$query = $this->session->userdata('flog_qry');
		//remove limit
		$log_query = substr($query, 0, strpos($query, 'LIMIT'));

		echo $log_query;

		$result = $this->db->query($log_query)->result ();

		$modified = $this->session->userdata ( 'username' );


		$objPHPExcel = new PHPExcel ();		
		
		$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS flog report" )->setSubject ( "flog Xls" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007-2010 " )->setCategory ( "Log xls file" );

		// Add some data
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'C1', 'Гэмтэл дутагдлын файл' );
		
		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );

		//Төрөл, Ангилал, Хэсэг, Нээсэн цаг, Байршил, Төхөөрөмж, Гэмтэл/дутагдал, Гэмтсэн хэсэг, Хаасан/t, Үргэлжилсэн хугацаа, гүйцэтгэл, Нээсэн Ита, ХАасан ИТА,
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Гэмтлийн №' )->setCellValue('B2', 'Хүндрэл түвшин')->setCellValue('C2', 'Магадлал түвшин')->setCellValue ( 'D2', 'Ангилал' )->setCellValue ( 'E2', 'Хэсэг' )->setCellValue ( 'F2', 'Нээсэн цаг' )->setCellValue ( 'G2', 'Байршил' )->setCellValue ( 'H2', 'Төхөөрөмж' )->setCellValue ( 'I2', 'Гэмтэл төрөл' )->setCellValue( 'J2', 'Гэмтсэн дэд хэсэг' )->setCellValue('K2', 'Шалтгаан')->setCellValue( 'L2', 'Хаасан хугацаа')->setCellValue( 'M2', 'Үргэлжилсэн хугацаа')->setCellValue( 'N2', 'Гүйцэтгэл')->setCellValue('O2', 'Нээсэн ИТА')->setCellValue( 'P2', 'Хаасан ИТА');

		$i = 3;

		foreach ($result as $cols) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->log_num );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->level );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->num );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->category );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->section );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->created_dt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $i, $cols->location );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $i, $cols->equipment );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $i, $cols->log_type );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $i, $cols->node );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $i, $cols->reason );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $i, $cols->closed_dt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $i, $cols->duration );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'N' . $i, $cols->completion );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'O' . $i, $cols->createdby );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'p' . $i, $cols->closedby );
				$i++;
		}

		// echo date('H:i:s') , " Write to Excel2007 format" , PHP_EOL;
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\log_files\report\log_file_'.$name.'.xlsx', __FILE__ ) );
		$url = base_url ();
		redirect ( $url . 'download/log_files/report/log_file_'.$name.'.xlsx' );

	}
        
        
	function warnpage($log_id) {		            
		$log_data = $this->flog_model->get_all(array('id'=>$log_id), 'vw_flog');
		$data ['log_cols'] = $log_data;
		$created_employee_id = $log_data['createdby_id'];
		$activated_employee_id = $log_data[ 'activeby_id'];
	//    var_dump($log_data);           

		$data ['log_id'] = $log_id;
		// Үүсгэсэн хүний ажлын утас, дугаар, албан тушаал авна.
		$cemp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $created_employee_id );
		foreach ( $cemp_cols as $row ) {
				$data ['cr_workphone'] = $row->workphone;
				$data ['cr_position'] = $row->position;
				$data ['cr_fullname'] = $row->fullname;
				$data ['cr_sector'] = $row->section_sector;
		}
		
		$closed_by_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $log_data['closedby_id'] );
		foreach ( $closed_by_cols as $row ) {
				$data ['cl_workphone'] = $row->workphone;
				$data ['cl_position'] = $row->position;
				$data ['cl_fullname'] = $row->fullname;
				$data ['cl_sector'] = $row->section_sector;                    
		}

		$act_emp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $activated_employee_id );
		foreach ( $act_emp_cols as $row ) {
				$data ['act_workphone'] = $row->workphone;
				$data ['act_position'] = $row->position;
				$data ['act_fullname'] = $row->fullname;
		}
		// $data['employee_cols']=$this->main_model->get_values('view_employee', 'employee_id', $employee_id);
		$this->load->view ( 'flog/report/warnpage', $data );
	}
        
        function dashboard(){            
            $data ['page'] = 'flog\dashboard';			
            $data ['title'] = "Гэмтэл дутагдлын бүртгэл";
            $this->load->view ( '\flog\dashboard');            
        }
}
?>     

