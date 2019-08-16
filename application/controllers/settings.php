<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class settings extends CNS_Controller {

	public function __construct() {

		parent::__construct ();

		$this->load->model ( 'main_model' );

		$this->load->model ( 'alert_model' );
		
		$this->load->library ( 'Location_Module' );

		$this->load->helper ( 'text' );

		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'home', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Цахим систем нүүр::eCNS Home' );

		$this->config->set_item ( 'module_menu_link', '/ecns' );
		
	}

	// Хувийн тохиргоо
	function form_personal() {

		$this->main_model->access_check ();

		$employee_id = $this->session->userdata ( 'employee_id' );

		$data ['role'] = $this->role;

		$data ['cols'] = $this->main_model->get_values ( 'view_employee', 'employee_id', $this->session->userdata ( 'employee_id' ) );

		$data ['employee_id'] = $employee_id;

		$data ['action'] = 'personal';

		$data ['title'] = 'Хувийн тохиргоо';

		$this->load->view ( 'settings/profile', $data );

	}

	// Хувийн тохиргоо submit хийхэд
	function personal($employee_id) {

		$this->main_model->access_check ();

		if ($_POST ['newpass'] != '')

			$data = array (

				'employee_id' => $employee_id,

				'first_name' => $_POST ['first_name'],

				'last_name' => $_POST ['last_name'],

				'workphone' => $_POST ['workphone'],

				'cellphone' => $_POST ['cellphone']

			);

		if (isset ( $_POST ['newpass'] ) && $_POST ['newpass'] != '')

			$data ['password'] = md5 ( $_POST ['newpass'] );

		if (isset ( $_POST ['position_id'] ) && $_POST ['position_id'] != '')

			$data ['position_id'] = $_POST ['position_id'];

		if (isset ( $_POST ['section_id'] ) && $_POST ['section_id'] != '')

			$data ['section_id'] = $_POST ['section_id'];

		if (isset ( $data )) {

			if ($this->main_model->update ( 'employee', 'employee_id', $data ))

				$this->session->set_userdata ( 'message', 'Хувийн мэдээллийг амжилттай хадгаллаа!' );
				// echo "Хувийн мэдээллийг амжилттай хадгаллаа!";
			else
				$this->session->set_userdata ( 'message', 'Хувийн мэдээллийг хадгалж чадсангүй!' );
			// echo "Хувийн мэдээллийг хадгалж чадсангүй!";
		}

		redirect ( 'settings/form_personal' );

	}

	// Хэсэг
	function section() {

		try {

			 if ($this->role == 'ADMIN') {

				$crud = new grocery_CRUD ();

				$crud->set_table ( 'section' );

				$crud->set_subject ( 'Хэсэг' );

				$crud->add_fields ( 'name', 'desc', 'code', 'sort' );

				$crud->required_fields ( 'name', 'desc', 'code' );

				$crud->columns ( 'section_id', 'name', 'desc', 'code', 'sort', 'section' );

				$crud->display_as ( 'section_id', '#' )->display_as ( 'name', 'Хэсгийн нэр' )->display_as ( 'desc', 'Тодорхойлолт' )->display_as('section', 'Агуулах хэсэг')->display_as ( 'code', 'Код' )->display_as ( 'sort', 'Дараалал' );

				$output = $crud->render ();

				$this->_settings_output ( $output );

			} else {

				$this->load->view ( '43.html' );

			}

		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}

	// Employee list
	function employee($operation = null) {

		try {

			if ($this->main_model->get_authority ( 'home', 'user', 'index', $this->role ) == 'index') {

				$this->load->library ( 'ajax_grocery_CRUD' );

				$crud = new grocery_CRUD ();

				// herev section id ni zahirgaanaas busad tohioldold filter tavina.
				$crud->columns ( 'employee_id', 'fullname', 'username', 'email', 'position_id', 'section_id', 'sector_id', 'workphone', 'cellphone' );

				$crud->set_table ( 'employee' );

				$crud->set_subject ( 'Инженер, техникийн ажилчид' );

				$crud->set_relation ( 'position_id', 'position', 'name' );

				$crud->set_relation ( 'section_id', 'section', 'name' );

				$crud->set_relation ( 'sector_id', 'sector', 'name' );

				$crud->display_as ( 'employee_id', '#' )
					 ->display_as ( 'fullname', 'Нэр.Овог' )
					 ->display_as ( 'first_name', 'Нэр' )
					 ->display_as ( 'last_name', 'Овог' )
					 ->display_as ( 'username', 'Нэвтрэх' )
					 ->display_as ( 'password', 'Нууц үг' )
					 ->display_as ( 'email', 'Имэйл' )
					 ->display_as ( 'position_id', 'Албан тушаал' )
					 ->display_as ( 'section_id', 'Хэсэг' )
					 ->display_as ( 'sector_id', 'Тасаг' )
					 ->display_as ( 'workphone', 'Ажлын утас' )
					 ->display_as ( 're_password', 'Нууц үг давтан оруулах' )
					 ->display_as ( 'cellphone', 'Гар утас' )
					 ->display_as ( 'is_checked', 'Нууц үг солих' );

				$crud->callback_field ( 'section_id', array (
						$this,
						'section_field_callback'
				) );

				$crud->callback_field ( 'sector_id', array (
						$this,
						'sector_field_callback'
				) );

				$crud->callback_field ( 'position_id', array (
						$this,
						'position_field_callback'

				) );

				$crud->callback_field ( 'is_checked', array (
						$this,
						'employee_callback_field'
				) );

				if ($operation == 'insert_validation' || $operation == 'insert')

					$crud->set_rules ( 'email', 'Email check', 'required|valid_email|is_unique[employee.email]' );

				else

					$crud->set_rules ( 'email', 'Email check', 'required|valid_email' );

				$crud->set_rules ( 'password', 'Password', 'min_length[5]|matches[re_password]' );

				$crud->set_rules ( 're_password', 'Password confirmation' );

				$crud->fields ( 'employee_id', 'first_name', 'last_name', 'fullname', 'username', 'email', 'section_id', 'def_role', 'sector_id', 'position_id', 'workphone', 'cellphone', 'is_checked', 'password', 're_password' );

				$crud->change_field_type ( 're_password', 'password' );

				$crud->field_type ( 'employee_id', 'invisible' );

				$crud->field_type ( 'password', 'password' );

				$crud->field_type ( 'fullname', 'invisible' );

				$crud->field_type ( 'username', 'invisible' );

				$crud->field_type ( 'def_role', 'invisible' );

				// UNSET ACTIONS
				if ($this->role == 'CHIEFENG') {

					$crud->unset_delete ();

				} elseif ($this->role != 'ADMIN') {

					$crud->unset_add ();

					$crud->unset_edit ();

					$crud->unset_delete ();

				}

				$crud->callback_before_update ( array (	$this, 'before_update_employee' ) );

				$crud->callback_before_insert ( array (	$this,	'insert_employee_callback' 	) );

				$crud->callback_after_insert ( array ( $this, 'after_insert_employee' ) );

				$output = $crud->render ();

				$this->_settings_output ( $output );

			} else {

				$this->load->view ( '43.html' );

			}
		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}


	function section_field_callback($section_id) {

		return $this->main_model->sel_section ( $section_id );

	}

	function sector_field_callback($sector_id) {

		return $this->main_model->sel_sector ( $sector_id );

	}

	function position_field_callback($position_id) {

		return $this->main_model->sel_position ( $position_id );

	}

	function equipment_field_callback($equipment_id) {

		return $this->main_model->field_equipment ( $equipment_id );

	}

	function encrypt_password_callback($post_array, $primary_key) {

		$this->load->library ( 'encrypt' );	// Encrypt password only if is not empty. Else don't change the password to an empty field

		if (! empty ( $post_array ['password'] )) {

			$post_array ['password'] = md5 ( $post_array ['password'] );

		} else {

			unset ( $post_array ['password'] );

		}

		return $post_array;

	}

	function before_update_employee($post_array) {

	    if ($post_array ['is_checked'] == 'yes') {

		   $post_array ['password'] = md5 ( $post_array ['password'] );

		   $post_array ['re_password'] = md5 ( $post_array ['re_password'] );

		} else {

			unset ( $post_array ['password'] );

			unset ( $post_array ['re_password'] );

		}

		$first_name = $post_array ['first_name'];

		$last_name = $post_array ['last_name'];

		unset ( $post_array ['is_checked'] );

		$post_array ['fullname'] = $first_name . '.' . mb_substr ( $last_name, 0, 1, 'utf-8' );

		$post_array ['username'] = substr ( $post_array ['email'], 0, strpos ( $post_array ['email'], '@' ) );

		return $post_array;
	}

	function after_update_employee($post_array) {

		$username = substr ( $post_array ['email'], 0, strpos ( $post_array ['email'], '@' ) );

		$password = $post_array ['password'];

		$body = "Таны eCNS систем дэх бүртгэлийг шинэчлэлээ.\n
		Системийн тухай мэдээллийг Тусламж цэснээс үзнэ үү!\n
		Дотоод сүлжээнээс http://ecns.mcaa.gov.mn/ хаягаар хандана уу.\n
		Таны системд нэвтрэх нэр: $username \n
		Нууц үг: $password \n
		Та Тохиргоо->Хувийн цэс уруу хандаж өөрийн нууц үгээ солино.
		Хамтран ажилласанд баярлалаа,
		Хүндэтгэсэн eHelper.";

		$this->alert_model->email ( FROM_EMAIL, FROM_NAME, $post_array ['email'], EDIT_USER, $body );

		return true;
	}

	// insert хийхийн өмнө
	function insert_employee_callback($post_array) {
		// print_r($post_array);
		$this->session->set_userdata ( 'password', $post_array ['password'] );

		if (isset ( $post_array ['is_checked'] ) && $post_array ['is_checked'] == 'yes') {

			$post_array ['password'] = md5 ( $post_array ['password'] );

			$post_array ['re_password'] = md5 ( $post_array ['re_password'] );

		} else {

			unset ( $post_array ['password'] );

		}

		unset ( $post_array ['def_role'] );

		unset ( $post_array ['is_checked'] );

		$post_array ['employee_id'] = $this->main_model->get_maxId ( 'employee', 'employee_id' );

		$post_array ['fullname'] = $post_array ['first_name'] . '.' . mb_substr ( $post_array ['last_name'], 0, 1, 'utf-8' );

		$post_array ['username'] = substr ( $post_array ['email'], 0, strpos ( $post_array ['email'], '@' ) );

		return $post_array;

	}

	function after_insert_employee($post_array) {

		$username = $post_array ['username'];

		$password = $this->session->userdata ( 'password' );

		$this->session->unset_userdata ( 'password' );

		$body = "Таны eCNS системд шинэ хэрэглэгчээр бүртгэлээ. \n
         Системийн тухай мэдээллийг Тусламж цэснээс үзээрэй.\n
         Дотоод сүлжээнээс http://ecns.mcaa.gov.mn/ хаягаар хандана уу.\n
         Таны системд нэвтрэх нэр: $username \n
         Нууц үг: $password \n
         Нэвтэрч орсны дараа Тохиргоо->Хувийн гэсэн цэснээс нууц үгээ өөрчилнө үү.
         Хамтран ажилласанд баярлалаа,
         Хүндэтгэсэн eHelper.";

		$this->alert_model->email ( $_POST ['email'], NEW_USER, $body );

		return true;
	}

	function employee_callback_field($value = '', $primary_key = null) {

		return "
       <script type='text/javascript'>
          $( document ).ready(function() {
             $('#field-password').val('');
             $('#field-re_password').val('');
          });
       </script>
       <input id='is_checked' type='checkbox' name='is_checked' value='yes'>Тийм";

	}

	// Төхөөрөмж
	function equipment() {
		try {
			if ($this->main_model->get_authority ( 'home', 'settings', 'equipment', $this->role ) == 'equipment') {

				$crud = new grocery_CRUD ();

				// // herev section id ni zahirgaanaas busad tohioldold
				// // section_id gaar filter tavina.
				// //Тасгийн ахлагчаас бусад эрхтэй хүмүүс нэмж хасах эрхтэй
				// $crud->set_table('equipment');
				// $crud->set_subject('Тоног төхөөрөмж');
				// //$crud->required_fields('city');
				// // $crud->set_relation('position_id','position','name');
				// $crud->set_relation('section_id','section','{name}-{code}');
				// $crud->set_relation('sector_id','sector','name');
				// $crud->columns('equipment_id', 'name', 'description', 'section_id','max_log_num', 'code', 'sector_id');
				// $crud->display_as('employee_id','#')
				// ->display_as('name','Нэршил')
				// ->display_as('description','Тодорхойлолт')
				// ->display_as('section_id','Хэсэг')
				// ->display_as('max_log_num','Гэмтлийн эхлэх дугаар')
				// ->display_as('code','Код')
				// ->display_as('sector_id','Тасаг');
				// $output = $crud->render();
				// $this->_settings_output($output);
				// }else{
				// $this->load->view('43.html');
				// }
				// }catch(Exception $e){
				// show_error($e->getMessage().' --- '.$e->getTraceAsString());
				// }
				$crud = new grocery_CRUD ();

				$crud->set_table ( 'equipment2' );

				$role_array = array (
						'ENG',
						'SUPENG',
						'TECH',
						'UNITCHIEF'
				);
				// //ENGINEER GROUP
				// if(in_array($this->role, $role_array)){
				// $crud->where('license.trainer_id', $this->employee_id);
				// }
				// $crud->unset_add_fields('training');

				$crud->columns ( 'equipment_id', 'equipment', 'intend', 'section_id', 'sector_id', 'code', 'spec' );

				$crud->add_fields('equipment','intend','section_id','sector_id', 'code', 'spec', 'sp_id');


				$crud->fields ( 'equipment', 'section_id', 'sector_id', 'code', 'intend', 'spec', 'sp_id' );

				$crud->set_rules('sp_id','Төхөөрөмжийн сэлбэгдээрх код','integer|min_length[1]|max_lenght[100]');

				$crud->set_subject ( 'Тоног төхөөрөмж' );

				$crud->set_relation ( 'section_id', 'section', 'name' );

				$crud->set_relation ( 'sector_id', 'sector', 'name' );

				$crud->display_as ( 'equipment_id', '#' )->display_as ( 'equipment', 'Тоног төхөөрөмж' )->display_as ( 'intend', 'Зориулалт' )->display_as ( 'section_id', 'Харьяа хэсэг' )->display_as ( 'sector_id', 'Тасаг' )->display_as ( 'code', 'Код' )->display_as ( 'spec', 'Үзүүлэлт' )->display_as('sp_id', 'Сэлбэгтэй холбох дугаар /0-100 хооронд хэсэг, тасагаар давтагдахгүй утга авна/');

				$crud->field_type ( 'updatedby_id', 'hidden', $this->user_id );

				$role = $this->session->userdata ( 'role' );

				if (! in_array ( $role, array ('TECHENG', 'CHIEF', 'ADMIN' ) )) {

					$crud->unset_add ();

					$crud->unset_edit ();

					$crud->unset_delete ();

				}
				// $crud->required_fields( 'equipment', 'intend', 'section_id', 'code', 'power', 'frequency');

				$crud->order_by ( 'equipment', 'desc' );

				$output = $crud->render ();

				$this->_settings_output ( $output );

			} else {

				$this->load->view ( '43.html' );

			}

		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}


	function locations()
	{
		$location = new Location_Module();

        $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/location/js/location.js', TRUE ));

        $this->data['location'] = $location->run ();

        $this->data['section']=$this->section_model->dropdown_by('section_id', 'name');

        $this->data['sector']=$this->sector_model->dropdown_by('sector_id', 'name');
        
        $this->data['title'] = "Байршил";

        $this->data['page']= 'location/index';

        $this->load->view("index", $this->data );
	}

	// Хэсэг
	function loc_equip() {
		try {

			if ($this->main_model->get_authority ( 'home', 'settings', 'loc_equip', $this->role ) == 'loc_equip') {

				$crud = new grocery_CRUD ();

				$crud->set_table ( 'loc_equip' );

				$crud->set_subject ( 'Байршил дэх тоног төхөөрөмж' );

				$crud->set_relation ( 'location_id', 'location', 'name' );

				$crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );

				$crud->edit_fields ( 'location_id', 'equipment_id' );

				$crud->add_fields ( 'location_id', 'equipment_id' );

				$crud->required_fields ( 'location_id', 'equipment_id' );

				$crud->columns ( 'id', 'location_id', 'equipment_id' );

				$crud->display_as ( 'id', '#' )->display_as ( 'location_id', 'Байршил' )->display_as ( 'equipment_id', 'Тоног төхөөрөмж' );

				$output = $crud->render ();

				$this->_settings_output ( $output );

			} else {

				$this->load->view ( '43.html' );

			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	function module() {
		try {
			if ($this->main_model->get_authority ( 'home', 'settings', 'module', $this->role ) == 'module') {

				$crud = new grocery_CRUD ();

				$crud->set_table ( 'role_insystem' );

				$crud->set_subject ( 'Module' );

				$crud->columns ( 'id', 'order', 'apps', 'controller', 'functions', 'menu', 'parent', 'link', 'ADMIN', 'HEADMAN', 'CHIEFENG', 'CHIEF', 'UNITCHIEF', 'SUPERVISOR', 'ENG', 'TECHENG', 'SUPENG', 'QENG', 'ACT', 'WKR', 'MGR', 'WENG', 'MEN', 'INS' );

				$output = $crud->render ();

				$this->_settings_output ( $output );

			} else {

				$this->load->view ( '43.html' );

			}

		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	// Tохиргоо бичиг баримт
	function document() {

		try {
			// if($this->main_model->get_authority('home','home','document', $this->role)=='document'){
			$crud = new grocery_CRUD ();

			$crud->set_table ( 'files' );

			$crud->display_as ( 'categoryId', 'Бүлэг' )->display_as ( 'fileId', '#' )->display_as ( 'order', 'Эрэмбэ' )->display_as ( 'filename', 'Файлын нэр' )->display_as ( 'title', 'Файлын тодорхойлолт' )->display_as ( 'docIndex', 'Индекс' )->display_as ( 'created', 'Үүсгэсэн Огноо' );


			$crud->set_relation ( 'categoryId', 'category', 'category' );

			$crud->set_subject ( 'Document' );

			// $crud->required_fields('city');
			$crud->columns ( 'fileId', 'categoryId', 'order', 'filename', 'title', 'docIndex', 'created' );

			$output = $crud->render ();

			$this->_settings_output ( $output );

		} catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}

	function _settings_output($output = null) {

		$this->load->view ( 'settings.php', $output );

	}

	function equip(){

		// create settings as equipment
	    $trip= new eSettings(); // eSettings section add here

        $out = $trip->run ();

        $this->data['spare'] = $out;

		// $this->data['section']=$this->section_model->dropdown('name');

        //create javascript as grid
        $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/settings/js/equipment.js', TRUE ));

        // create grid page
        $this->data['page']='settings\equipment';

        $this->load->view('index', $this->data);
	}

 //хуучин шинэ төхөөрөмжүүдийг Огноох
	function sync(){
		try {
			if ($this->main_model->get_authority ( 'home', 'settings', 'sync', $this->role ) == 'sync') {
				$crud = new grocery_CRUD ();

					$crud->set_table ( 'sync_equipment' );
					$crud->display_as ( 'id', '#' )
							 ->display_as ( 'equipment_id', 'Шинэ төхөөрөмж' )
							 ->display_as ( 'old_equipment_id', 'Хуучин төхөөрөмж' );

					$crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );
					$crud->set_relation ( 'old_equipment_id', 'equipment', 'name' );

					$crud->columns ( 'id','old_equipment_id', 'equipment_id');

					$output = $crud->render ();

					$this->_settings_output ( $output );
		 }

		}catch ( Exception $e ) {

			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );

		}
	}


	

}
?>
