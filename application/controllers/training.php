<?php
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class training extends CNS_Controller {

	protected $trainer;

	protected $pos_his;
	
	protected $position_detail;

	protected $license_equipment;
	
	protected $organization;

	public function __construct() {

		parent::__construct ();

		$this->load->model ( 'trainer_model' );		

		$this->trainer = new trainer_model();

		$this->load->model ( 'tl_model' );		

		$this->load->library ( 'eTraining' );		
		
		$this->load->library ( 'Guidance_Module' );	

		$this->load->library ( 'Trainer_Module' );		

		$this->load->model ( 'position_detail_model' );		
		
		$this->position_detail = new position_detail_model();		


		$this->load->model ( 'position_history_model' );		
		
		$this->pos_his = new position_history_model();

		$this->load->model ( 'license_equipment_model' );		

		$this->license_equipment = new license_equipment_model();

		$this->load->model ( 'location_model' );		

		// $this->location = new location_model();

		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'training', $this->role, 0, 1 ) );
		
		$this->config->set_item ( 'module_menu', 'Сургалтын бүртгэл' );

		$this->config->set_item ( 'module_menu_link', '/training' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

	}

	function index() {

			$this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/training/training.js', TRUE ));

			$this->config->set_item ( 'module_css', base_url().'assets/apps/training/css/license.css', TRUE );

			$user_id = $this->session->userdata('employee_id');

			$t = new eTraining ();

			$t->set_user ( $user_id );

			$t->set_role ( $this->role );

			$t->fields ( 'trainer_id', 'firstname', 'lastname', 'gender' );

			$out = $t->run ();
			
			if ($out->view) {

				$data ['out'] = $out;

				$data ['page'] = $out->page;

				$data ['title'] = $out->title;

				$data['employee']=$this->employee_model->dropdown( 'fullname');

				$license_equip = $this->license_equipment->dropdown( 'code');
				
				$license_equip[0] = 'Нэг төхөөрөмжийг сонго';
				
				$data['license_equipment']=$license_equip;
				
				$this->my_model_old->set_table ( 'location' );

				$data ['location'] = $this->my_model_old->get_select ( 'name' );

				$this->my_model_old->set_table ( 'organization' );

				$data ['organization'] = $this->my_model_old->get_select ( 'organization' );

				$this->my_model_old->set_table ( 'position' );

				$data ['position'] = $this->my_model_old->get_select ( 'name' );
				
				$data ['position_detail'] = $this->position_detail->dropdown('detail');
				
				$this->my_model_old->set_table ( 'settings' );

				$sdata = $this->my_model_old->get_select ( 'value', array ('settings' => 'license_type') );
		
				unset ( $sdata [0] );

				$data ['license_type'] = $sdata;

				//position history
							
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


	function trainer($sec_code = null) {
		try {

			if ($this->main_model->get_authority ( 'training', 'training', 'edit', $this->role ) == 'edit' || $this->main_model->get_authority ( 'training', 'training', 'add', $this->role )) {

				$crud = new grocery_CRUD ();

				$crud->set_table ( 'trainer' );

				$role_array = array (
					'ENG',
					'SUPENG',
					'TECH',
					'UNITCHIEF' 
				);

				// ENGINEER GROUP
				if (in_array ( $this->role, $role_array )) {

					$crud->where ( 'license.trainer_id', $this->user_id );

				}

				// $crud->unset_add_fields('training');
				$crud->columns( 'register', 'firstname', 'lastname', 'gender', 'birthdate', 'nationality', 'org_id', 'location_id', 'section_id', 'position_id', 'workphone', 'phone', 'email', 'rel_type', 'rel_phone', 'occupation', 'education', 'license_no', 'license_type_id', 'issued_date', 'valid_date',
					'initial_date', 'license_equipment' );

				$crud->fields ( 'register', 'firstname', 'lastname', 'fullname', 'gender', 'birthdate', 'nationality','org_id', 'location_id', 'section_id', 'position_id', 'workphone', 'phone', 'email', 'rel_type', 'rel_phone', 'occupation', 'education', 'license_no', 'license_type_id', 'issued_date', 
					'valid_date', 
					'initial_date',
					'license_equipment' );

				$crud->set_subject ( 'Үнэмлэхний бүртгэл' );

				$crud->set_relation ( 'position_id', 'position', 'name' );

				$crud->set_relation ( 'section_id', 'section', 'name' );

				$crud->set_relation ( 'org_id', 'organization', 'shortname' );

				$crud->set_relation ( 'location_id', 'location', 'name' );

				$crud->set_relation ( 'license_type_id', 'settings', '{value} - {name}', array (
						'settings' => 'license_type' 
				) );

				$crud->unset_back_to_list ();

				$crud->display_as ( 'register', '1.1. Регистрийн дугаар' )
					 ->display_as ( 'firstname', '1.2. Нэр' )
					 ->display_as ( 'lastname', 'Овог' )
					 ->display_as ( 'gender', '1.3. Хүйс' )
					 ->display_as ( 'birthdate', '1.4. Төрсөн он/сар/өдөр' )
					 ->display_as ( 'org_id', '1.5. Харьяа байгууллага' )
					 ->display_as ( 'location_id', '1.6. Байршил' )
					 ->display_as ( 'section_id', '1.7. Хэсэг, тасаг' )
					 ->display_as ( 'position_id', '1.8. Албан тушаал' )
					 ->display_as ( 'workphone', '1.9. Ажлын утас' )
					 ->display_as ( 'phone', '1.10. Гар утас' )
					 ->display_as ( 'email', '1.11. Имэйл' )
					 ->display_as ( 'rel_type', ' 1.12. Онцгой шаардлага гарсан үед холбогдох хүн' )
					 ->display_as ( 'rel_phone', ' Тэр хүнтэй холбогдох утас' )
					 ->display_as ( 'occupation', '1.13. Мэргэжил' )
					 ->display_as ( 'education', '1.14. Боловсролын байдал/Төгссөн сургууль, мэргэжил, зэрэг' )
					 ->display_as ( 'license_no', '1.15. Мэргэжлийн үнэмлэх №' )
					 ->display_as ( 'license_type_id', '1.16. Үнэмлэхний төрөл' )
					 ->display_as ( 'aa_license', 'А/А-ын үнэмлэх' )
					 ->display_as ( 'aa_group', 'А/А-ын групп' )
					 ->display_as ( 'issued_date', '1.17.Үнэмлэх олгосон огноо' )
					 ->display_as ( 'valid_date', '1.18.Үнэмлэх хүчинтэй огноо' )
					 ->display_as ( 'expired_date', 'Дуусах хугацаа' )
					 ->display_as ( 'initial_date', '1.19. Анх олгосон огноо' )
					 ->display_as ( 'license_equipment', '1.20.Ажиллах төхөөрөмж' );

				$crud->field_type ( 'fullname', 'invisible' );				
				
				$crud->callback_before_update ( array ($this, 'update_trainer_cb') );

				$crud->callback_before_insert ( array (	$this,	'insert_training_cb' ) );
				
				$crud->required_fields ( 'license_no', 'license_type_id', 'issued_date', 'valid_date', 'expired_date', 'license_equipment', 'location_id', 'initial_date' );

				$crud->order_by ( 'valid_date', 'desc' );
				
				$output = $crud->render ();

				$this->load->view ( 'training/output', $output );

			} else {

				$this->load->view ( '43.html' );

			}
		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}

	// callback insert training
	function insert_training_cb($post_array) {

		$post_array ['fullname'] = $post_array ['firstname'] . '.' . mb_substr ( $post_array ['lastname'], 0, 1, 'utf-8' );

		return $post_array;

	}

	function update_trainer_cb($post_array) {

		$post_array ['fullname'] = $post_array ['firstname'] . '.' . mb_substr ( $post_array ['lastname'], 0, 1, 'utf-8' );

		$post_array ['up_date'] = date ( "Y-m-d H:i:s" );

		return $post_array;

	}
	
	// training edit, add
	function studying() {

		if ($this->main_model->get_authority ( 'training', 'training', 'training', $this->role ) == 'training') {

			$crud = new grocery_CRUD ();

			$crud->set_subject ( 'Сургалтын бүртгэл' );

			$crud->set_table ( 'training' );

			$crud->columns ( 'id', 'type_id', 'training', 'date', 'time', 'orderN', 'file', 'trainers' );

			$crud->set_relation ( 'type_id', 'training_type', 'type' );

			$crud->set_relation_n_n ( 'trainers', 'training_attendance', 'trainer', 'training_id', 'trainer_id', 'fullname', 'sord' );

			$crud->display_as ( 'id', '#' )->display_as ( 'training', 'Сургалт' )->display_as ( 'type_id', 'Сургалтын төрөл' )->display_as ( 'date', 'Огноо' )->display_as ( 'trainers', 'Хамрагдсан суралцагчид' )->display_as ( 'time', 'Сургалтын цаг' )->display_as ( 'place', 'Болсон газар' )->display_as ( 'orderN', 'Тушаалын дугаар' )->display_as ( 'file', 'Файл: /Тушаал, хөтөлбөр/' );
			
			$crud->set_field_upload ( 'file', 'download/training_files' );

			$crud->order_by ( 'date', 'desc' );
			
			// if (! in_array ( $this->session->userdata ( 'role' ), array (
			// 		'ADMIN',
			// 		'TECHENG' 
			// ) )) {

			// 	$crud->unset_add ();

			// 	$crud->unset_edit ();

			// 	$crud->unset_delete ();

			// }

			$output = $crud->render ();

			$this->load->view ( 'training/output', $output );

		} else {
			$this->load->view ( '43.html' );
		}
	}


	function trainer2(){
		$this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/trainer/trainer.js', TRUE ));

		$trainer = new Trainer_Module();

      $this->data['trainer'] = $trainer->run();
        
      $this->data['page']='trainer\index';

      $this->load->view('index', $this->data);
	}

	
	function print_history() {

		if ($this->main_model->get_authority ( 'training', 'training', 'training', $this->role ) == 'training') {

			$crud = new grocery_CRUD ();

			$crud->set_subject ( 'Үнэмлэх хэвлэлт бүртгэл' );

			$crud->set_table ( 'print_history' );

			$crud->columns ( 'id', 'page_number', 'license_number', 'page', 'trainer', 'printed_by', 'printed_date');

			$crud->display_as ( 'id', '#' )
				->display_as ( 'page_number', 'Хуудас дугаар' )
				->display_as ( 'license_number', 'Үнэмлэх дугаар' )
				->display_as ( 'page', 'Хуудас' )
				->display_as ( 'trainer', 'Суралцагч' )
				->display_as ( 'printed_by', 'Хэвлэсэн' )
				->display_as ( 'printed_date', 'Хэвлэсэн огноо' );

			$crud->order_by ( 'license_number', 'asc' );
			
			if (! in_array ( $this->session->userdata ( 'role' ), array (
					'ADMIN',
					'TECHENG' 
			) )) {

				$crud->unset_add ();

				$crud->unset_edit ();

				$crud->unset_delete ();

			}

			$output = $crud->render ();

			$this->load->view ( 'training/output', $output );

		} else {
			$this->load->view ( '43.html' );
		}
	}

	function guidance(){

	    $section = $this->section_model->dropdown_by('section_id', 'name', array('type'=>'industry'));	
	    $section[0] = 'Нэг хэсгийг сонго!';
	    $section[7] = 'ТТИХ';
	    asort($section, 1);
	    $this->data['section']=$section;

		//$equipment = $this->equipment_model->dropdown('equipment');		

		$equipment[0] = 'Дээрх хэсгээс эхлээд сонго';

		//asort($equipment, 1);

		$this->data['equipment']=$equipment;
		
		$this->data['location']=$this->tl_model->dropdown('location', 'location');		
		
		$this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/guidance/guidance.js', TRUE ));

		$trip= new Guidance_Module();

      $out = $trip->run ();
        
      $this->data['guidance'] = $out;
        
      $this->data['page']='guidance\index';

      $this->load->view('index', $this->data);

    }


  	function license_print() {

  		$user_id = 1;

  		$this->data['trainer'] = $this->trainer->get(1);

		 // var_dump($this->data['trainer']);
  		
  		$this->data['page'] = 'training\license';
 
        $this->load->view('index', $this->data);

  		// load view license_print

	}

	function license_equipment(){

		if ($this->main_model->get_authority ( 'training', 'training', 'license_equipment', $this->role ) == 'license_equipment') {

			$crud = new grocery_CRUD ();

			$crud->set_subject ( 'Үнэмлэхийн төхөөрөмж бүртгэл' );

			$crud->set_table ( 'license_equipment' );

			$crud->columns ( 'id', 'code', 'equipment');

			// $crud->set_relation ( 'type_id', 'training_type', 'type' );

			// $crud->set_relation_n_n ( 'trainers', 'training_attendance', 'trainer', 'training_id', 'trainer_id', 'fullname', 'sord' );

			$crud->display_as ( 'id', '#' )
					->display_as ( 'code', 'Код' )
					->display_as ( 'equipment', 'Төхөөрөмжийн нэр' );
					
			
			$crud->order_by ( 'id', 'asc' );
			
			if (! in_array ( $this->session->userdata ( 'role' ), array (
					'ADMIN',
					'TECHENG' 
			) )) {

				$crud->unset_add ();

				$crud->unset_edit ();

				$crud->unset_delete ();

			}

			$output = $crud->render ();

			$this->load->view ( 'training/output', $output );

		} else {
			$this->load->view ( '43.html' );
		}

	}

	function enigneer_position(){

			// if ($this->main_model->get_authority ( 'training', 'training', 'license_equipment', $this->role ) == 'license_equipment') {

			$crud = new grocery_CRUD ();

			$crud->set_subject ( 'ИТА-ын албан тушаал' );

			$crud->set_table ( 'position_detail' );

			$crud->columns ('position_id', 'detail', 'sord');
			
			$crud->fields ('position_id', 'detail', 'sord');

			$crud->set_relation ( 'position_id', 'position', 'name' );

			$crud->display_as ( 'position_id', 'Албан тушаал' )

					->display_as ( 'detail', 'Албан тушаалын дэлгэрэнгүй' );
					// ->display_as ( 'equipment', 'Төхөөрөмжийн нэр' );
					
			
			$crud->order_by ( 'position_id', 'asc' );
			
			if (! in_array ( $this->session->userdata ( 'role' ), array (
					'ADMIN',
					'TECHENG' 
			) )) {

				$crud->unset_add ();

				$crud->unset_edit ();

				$crud->unset_delete ();

			}

			$output = $crud->render ();

			$this->load->view ( 'training/output', $output );

		// } else {
		// 	$this->load->view ( '43.html' );
		// }

	}


}

?>