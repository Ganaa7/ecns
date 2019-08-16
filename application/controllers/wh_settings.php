<?php
/*
 * This controller controlls warehouse settings
 * created 2012-01-13
 */
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class wh_settings extends CNS_Controller {

	public $filtered;
	
	public function __construct() {
	
		parent::__construct ();
		
		$this->load->library ( 'Wh_Settings_Module' );

		$this->load->model ( 'wm_model' );
	
		$this->load->model ( 'wm_main' );
			
		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'wh_spare', $this->role, 0, 1 ) );
	
		$this->session->unset_userdata ( 'home' );

		$this->config->set_item ( 'module_menu', 'Сэлбэг хангалтын бүртгэл' );
	
		$this->config->set_item ( 'module_menu_link', '/ecns/warehouse' );
		
      $this->load->model ( 'sparetype_model' );

      $this->load->model ( 'manufacture_model' );        

      $this->load->model ( 'measures_model' );
	}
	// Сэлбэгийн тохиргоо
	function spare2() {
		try {
			if ($this->main_model->get_authority ( 'wh_spare', 'wh_settings', 'spare', $this->role ) == 'spare') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wh_spare' );
				$where = "type in('industry', 'govern')";

					// UNSET ACTIONS
				if ($this->role == 'CHIEFENG') {
					$crud->unset_delete ();
					$crud->unset_edit ();
				} elseif ($this->role != 'ADMIN') {
					//$crud->unset_edit ();
					// $crud->unset_add ();
					$crud->unset_delete ();
				}

				$crud->set_relation ( 'section_id', 'section', 'name', $where, 'section_id asc' );
				$crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );
				// $crud->set_relation('wh_spare.equipment_id','equipment2','equipment2.sp_id');
				$crud->set_relation ( 'type_id', 'wh_sparetype', 'sparetype' );
				$crud->set_relation ( 'sector_id', 'sector', 'name' );
				/* энэ table-н нэрийг өөрчилнө */
				$crud->set_relation ( 'measure_id', 'wm_measures', 'measure' );
				$crud->set_relation ( 'manufacture_id', 'wm_manufacture', 'manufacture' );
				$crud->add_fields ( 'section_id', 'sector_id', 'equipment_id', 'spare', 'type_id', 'desc', 'part_number', 'measure_id', 'manufacture_id' );
				
				$crud->required_fields ( 'section_id', 'sector_id', 'equipment_id', 'spare', 'type_id', 'desc', 'part_number', 'measure_id', 'manufacture_id' );
				$crud->columns ( 'id', 'section_id', 'sector_id', 'equipment_id', 'spare', 'type_id', 'part_number', 'measure_id', 'manufacture_id' );
				// $crud->callback_field('section_id',array($this,'section_field_callback'));
				$crud->callback_field('equipment_id',array($this,'equipment_field_callback'));

				$crud->callback_before_insert(array($this,'insert_callback'));

				$crud->display_as ( 'id', '#' )->display_as ( 'section_id', 'Хэсэг' )->display_as ( 'sector_id', 'Тасаг' )->display_as ( 'equipment_id', 'Төхөөрөмж' )->display_as ( 'type_id', 'Сэлэбэгийн төрөл' )->display_as ( 'spare', 'Сэлбэг' )->display_as ( 'part_number', 'Парт №' )->display_as ( 'measure_id', 'Хэмжих нэгж' )->display_as ( 'manufacture_id', 'Үйлдвэрлэгч' );
				$crud->set_subject ( 'Сэлбэгийн бүртгэл' );
				$crud->fields ( 'section_id', 'sector_id', 'equipment_id', 'spare', 'type_id', 'part_number', 'measure_id', 'manufacture_id' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// warehouse
	function warehouse() {
		try {
			if ($this->main_model->get_authority ( 'warehouse', 'wm_settings', 'warehouse', $this->role ) == 'warehouse') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wm_warehouse' );
				$crud->add_fields ( 'warehouse', 'warehouse_desc', 'address', 'size_sqm' );
				$crud->required_fields ( 'warehouse', 'warehouse_desc', 'address', 'size_sqm' );
				$crud->columns ( 'warehouse_id', 'warehouse', 'warehouse_desc', 'address', 'size_sqm' );
				$crud->display_as ( 'pallet_id', '#' )->display_as ( 'warehouse', 'Агуулах' )->display_as ( 'warehouse_desc', 'Тодорхойлолт' )->display_as ( 'address', 'Хаяг' )->display_as ( 'size_sqm', 'Талбайн хэмжээ (метр2)' );
				
				$crud->set_subject ( 'Агуулахын бүртгэл' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	// Тавиур
	function pallet() {
		try {
			if ($this->main_model->get_authority ( 'warehouse', 'wm_settings', 'pallet', $this->role ) == 'pallet') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wm_pallet' );
				$crud->set_relation ( 'warehouse_id', 'wm_warehouse', 'warehouse' );
				$crud->add_fields ( 'pallet', 'code', 'warehouse_id' );
				$crud->required_fields ( 'pallet', 'code', 'warehouse_id' );
				$crud->columns ( 'pallet_id', 'pallet', 'code', 'warehouse_id' );
				$crud->display_as ( 'pallet_id', '#' )->display_as ( 'pallet', 'Тавиур' )->display_as ( 'code', 'Код' )->display_as ( 'warehouse_id', 'Агуулах' );
				
				$crud->set_subject ( 'Тавиур' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// COUNTRY
	function measure() {
		try {
			if ($this->main_model->get_authority ( 'wh_spare', 'wh_settings', 'measure', $this->role ) == 'measure') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wm_measures' );
				// $crud->set_relation('country_id','country','{country}-{code}');
				$crud->add_fields ( 'measure', 'short_code' );
				$crud->required_fields ( 'measure', 'short_code' );
				$crud->columns ( 'measure_id', 'measure', 'short_code' );
				$crud->display_as ( 'measure_id', '#' )->display_as ( 'measure', 'Хэмжээс' )->display_as ( 'short_code', 'Код' );
				
				$crud->set_subject ( 'Хэмжих нэгж' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// Үйлдвэрлэгч
	function manufacture() {
		try {
			if ($this->main_model->get_authority ( 'wh_spare', 'wh_settings', 'manufacture', $this->role ) == 'manufacture') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wm_manufacture' );
				$crud->set_relation ( 'country_id', 'country', '{country}-{code}' );
				// $crud->required_fields('city');
				$crud->add_fields ( 'manufacture', 'description', 'country_id' );
				$crud->required_fields ( 'manufacture', 'description', 'country_id' );
				$crud->columns ( 'manufacture_id', 'manufacture', 'description', 'country_id' );
				$crud->display_as ( 'manufacture_id', '#' )->display_as ( 'manufacture', 'Үйлдвэрлэгч' )->display_as ( 'description', 'Дэлгэрэнгүй' )->display_as ( 'country_id', 'Улс' );
				
				$crud->set_subject ( 'Үйлдвэрлэгч' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// Нийлүүлэгч
	function supplier() {
		try {
			$this->main_model->access_check ();
			if ($this->main_model->get_authority ( 'wh_spare', 'wh_settings', 'supplier', $this->role ) == 'supplier') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'wm_supplier' );
				$crud->set_relation ( 'country_id', 'country', 'country' );
				
				// $crud->required_fields('city');
				$crud->add_fields ( 'supplier', 'description', 'country_id', 'address', 'phone' );
				$crud->required_fields ( 'supplier', 'description', 'country_id', 'address', 'phone' );
				
				$crud->columns ( 'supplier_id', 'supplier', 'description', 'country_id', 'address', 'phone' );
				$crud->display_as ( 'supplier_id', '#' )->display_as ( 'country_id', 'Улс' )->display_as ( 'supplier', 'Нийлүүлэгч' )->display_as ( 'description', 'Дэлгэрэнгүй' )->display_as ( 'address', 'Хаяг' )->display_as ( 'phone', 'Утас' );
				
				$crud->set_subject ( 'Нийлүүлэгч' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// COUNTRY
	function country() {
		try {
			if ($this->main_model->get_authority ( 'wh_spare', 'wh_settings', 'country', $this->role ) == 'country') {
				$crud = new grocery_CRUD ();
				$crud->set_table ( 'country' );
				// $crud->set_relation('country_id','country','{country}-{code}');
				$crud->add_fields ( 'country', 'desc', 'code' );
				$crud->required_fields ( 'country', 'code' );
				$crud->columns ( 'country_id', 'country', 'desc', 'code' );
				$crud->display_as ( 'country_id', '#' )->display_as ( 'country', 'Улс' )->display_as ( 'desc', 'Дэлгэрэнгүй' )->display_as ( 'code', 'Код' );
				
				$crud->set_subject ( 'Улс' );
				$output = $crud->render ();
				$this->_settings_output ( $output );
			} else {
				$this->load->view ( '43.html' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// Сэлбэгийг хайхад гарах зүйлс
	function spareJson() {
		$spare = $_GET ['term'];
		$data = array ();
		$this->db->select ( "spare_id, spare_equip, part_number, measure" );
		$this->db->from ( 'wm_view_spare' );
		$query = $this->db->get ();
		// foreach($query->result() as $row){
		// $data[$row->spare_id]=$row->spare_equip;
		// }
		
		$q = strtolower ( $spare );
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc ())
			$q = stripslashes ( $q );
		$result = array ();
		foreach ( $query->result () as $row ) {
			if (strpos ( strtolower ( $row->spare_equip ), $q ) !== false) {
				array_push ( $result, array (
						"id" => $row->spare_id,
						"value" => $row->spare_equip,
						"part" => $row->part_number,
						"measure" => $row->measure 
				) );
			}
			if (count ( $result ) > 15)
				break;
		}
		echo json_encode ( $result );
	}
	function section_field_callback($section_id) {
		return $this->main_model->equip_section ( $section_id );
	}
	function equipment_field_callback($equipment_id) {		
		return $this->wm_model->field_equipment ( $equipment_id );
	}
	public function _settings_output($output = null) {
		$this->load->view ( 'warehouse/output.php', $output );
	}

	function insert_callback($post_array) {				
		$post_array['equipment_id'] = $this->main_model->get_row('sp_id',array('equipment_id'=>$post_array['equipment_id']), 'equipment2');		 		
		return $post_array;
	}   

	function spare(){
	   //$this->data ['library_src'] = $this->javascript->external ( base_url().'assets/warehouse/js/settings.js', TRUE );	   
		$user_id = $this->session->userdata ( 'employee_id'); //gov/ insustry
		
		$user = $this->employee_model->get($user_id);
		
	   $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/warehouse/js/settings.js', TRUE ));

	   $this->data['user_type'] = $this->session->userdata('user_type');

	    if(in_array($user->section_id, array(1, 2, 3, 4))){

	       $section=$this->section_model->ext_dropdown('section_id', 'name', 'section_id', $user->section_id);

	       $sector = $this->sector_model->ext_dropdown('sector_id', 'name', 'section_id', $user->section_id);
	   
	       $equipment = $this->equipment_model->ext_dropdown('sp_id', 'equipment', 'section_id', $user->section_id);  
 
	   }else{
		   $section=$this->section_model->dropdown_by('section_id', 'name', array('type'=>'industry'));
	       $sector=$this->sector_model->dropdown('name');
	       $equipment=$this->equipment_model->ext_dropdown('sp_id', 'equipment');  

	   }	

	   $section[0] = 'Нэг хэсгийг сонго!';	    	
	   ksort($section, 1);	       
	   $this->data['section'] = $section;

       $sector[0] = 'Нэг тасгийг сонго!';
       ksort($sector, 1);	  
       $this->data['sector']=$sector;  	

       $equipment[0] = 'Нэг төхөөрөмж сонго!';       
       ksort($equipment, 1);
       $this->data['equipment']= $equipment;
                        
       $this->data['employee']=$this->employee_model->with_drop_down('fullname');  
        
       $this->data['sparetype']=$this->sparetype_model->dropdown('sparetype');  
       
       $this->data['manufacture']=$this->manufacture_model->dropdown('manufacture');          

       $this->data['measure']=$this->measures_model->dropdown('measure');  

       $wh_settings= new Wh_Settings_Module();

       $out = $wh_settings->run ();        
       $this->data['settigns'] = $out;
		// $this->data['javascript']=base_url().'assets/wharehouse/js/settings.js';
		$this->load->view('/wh_spare/settings', $this->data);
	   
	}     
		 
}

?>