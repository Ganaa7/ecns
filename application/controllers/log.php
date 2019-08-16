<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class log extends CNS_Controller {

	public $role;

	public static $user_seccode;

	public static $user_section_id;

	public $gallery_path;

	public $gallery_path_url;

	public $objdata;

	function __construct() {

		parent::__construct ();
		$this->gallery_path = "download" . DIRECTORY_SEPARATOR . "log_files" . DIRECTORY_SEPARATOR;
		// realpath(APPPATH . '../download');
		$this->gallery_path_url = base_url () . 'download/log_files/';
		
		$objdata = ( object ) array ();
		
		// -------------Log Class ----------------------
		$this->load->library ( 'eLog' );
		$this->load->model ( 'my_model_old' );
		$this->load->model ( 'main_model' );
		$this->load->model ( 'user_model' );
		
		if ($this->session->userdata ( 'role' )) {
			$this->role = $this->session->userdata ( 'role' );
			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'log', $this->role, 0, 1 ) );
			$this->session->unset_userdata ( 'home' );
			$this->config->set_item ( 'module_menu', 'Гэмтэл дутагдлын бүртгэл /хуучин/' );
			$this->config->set_item ( 'module_menu_link', '/log' );
			$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
			// $this->config->set_item('my_script', $this->my_model_old->get_act_script($this->role));
			$this->user_seccode = $this->session->userdata ( 'sec_code' );
			$this->user_section_id = $this->session->userdata ( 'section_id' );
			$this->group = $this->user_model->setGroup ( $this->user_seccode );
		}
	}
	function index() {		
//		 $data['library_src']=$this->javascript->external('/ecns/assets/js/log.js', TRUE);
		$data ['library_src'] = $this->javascript->external ( base_url().'assets/js/log.min.js', TRUE );		
		$user_id = $this->session->userdata ( 'employee_id' );
		$log = new elog ();
		$log->set_user ( $user_id );
		$log->set_section ( $this->user_section_id );
		$log->set_role ( $this->role );
		$out = $log->run ();
		
		if ($out->view) {
			$data ['out'] = $out;
			$data ['page'] = 'log\index';
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
	function mtbf() {
		echo "mean time between failure calculation here";
		$data ['library_jq'] = $this->javascript->external ( base_url().'assets/js/jq.min.js', TRUE );
		$data ['library_jqu'] = $this->javascript->external ( base_url().'assets/js/jqui.min.js', TRUE );
		
		$data ['location'] = $this->main_model->get_locations ();
		$data ['equipment'] = $this->main_model->getEquipment ();
		$equipment_id = $this->input->get_post ( 'equipment_id' );
		
		$data ['equipment_id'] = $equipment_id;
		$location_id = $this->input->get_post ( 'location_id' );
		$data ['location_id'] = $location_id;
		
		$start = $this->input->get_post ( 'start' );
		$end = $this->input->get_post ( 'end' );
		
		$data ['start'] = $start;
		$data ['end'] = $end;
		$data ['diff'] = strtotime ( $end ) - strtotime ( $start );
		// equipment_id, location_id гаар шүүж тухайн мэдээллийг авна.
		$second1 = 0;
		$second2 = 0;
		$result_f = $this->db->query ( "SELECT equipment_id, location_id, count(equipment_id) as failures FROM ecns_launched.view_logs 
                                        GROUP BY equipment_id, location_id" )->result ();
		if ($end && $start) {
			$result = $this->db->query ( "  SELECT A.equipment_id, B.name as equipment, A.location_id, C.name as location, sec, count 
                                        FROM equipment B 
                                        left join (SELECT equipment_id, location_id, Sum(sec_diff) as sec, Count(equipment_id) as count
                                                FROM view_none_ot 
                                                WHERE DATE_FORMAT(st, '%Y-%m-%d') >= '$start' and DATE_FORMAT(et, '%Y-%m-%d')<='$end'
                                                GROUP BY equipment_id, location_id
                                                ) as A on B.equipment_id= A.equipment_id
                                        left join location C on A.location_id = C.location_id
                                         " )->result ();
			$data ['result'] = $result;
		}
		/*
		 * here calculation to between
		 * SELECT equipment_id, location_id, count(log_id) as cnt FROM ecns_launched.log
		 * GROUP BY equipment_id, location_id
		 */
		$data ['result_f'] = $result_f;
		$data ['last_query'] = $this->db->last_query ();
		$data ['page'] = 'log\mtbf';
		$data ['title'] = "Гэмтэл дутагдлын бүртгэл";
		$this->load->view ( 'index', $data );
	}
	function mtbf_calc() {
		$equipment_id = $this->input->get_post ( 'equipment_id' );
		$location_id = $this->input->get_post ( 'location_id' );
	}
	function log_plugin() {
		$json_arr = array ();
		$data = array ();
		
		$id = $this->input->get_post ( 'id' );
		$field = $this->input->get_post ( 'field' );
		$table = $this->input->get_post ( 'table' );
		
		$this->db->select ( $table . '_id, name' );
		if ($id != 0) {
			switch ($field) {
				case 'section_id' :
					$this->db->where ( array (
							"section_id" => $id 
					) );
					break;
				
				case 'sector_id' :
					$this->db->where ( array (
							"sector_id" => $id 
					) );
					break;
				
				default :
					$this->db->where ( array (
							"equipment_id" => $id 
					) );
					break;
			}
		}
		
		if ($table == 'sector') {
			$query = $this->db->get ( $table );
			foreach ( $query->result () as $row ) {
				$data [$row->sector_id] = $row->name;
			}
		} else {
			$query = $this->db->get ( $table );
			foreach ( $query->result () as $row ) {
				$data [$row->equipment_id] = $row->name;
			}
		}
		if ($query->num_rows () > 1) {
			$data [0] = 'Бүгд';
		} else if ($query->num_rows () == 0) {
			$data [0] = 'Байхгүй';
		}
		$json_arr = $data;
		$query->free_result ();
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $json_arr );
	}
	function warnpage($log_id) {
		$this->main_model->access_check ();
		$data ['log_cols'] = $this->main_model->get_values ( 'view_logs', 'log_id', $log_id );
		$this->my_model_old->set_table ( 'summary' );
		$data ['summary'] = $this->my_model_old->get_row ( 'summary', array (
				'log_id' => $log_id 
		) );
		
		$created_employee_id = $this->main_model->get_row ( 'createdby_id', array (
				'log_id' => $log_id 
		), 'log' );
		$activated_employee_id = $this->main_model->get_row ( 'activatedby_id', array (
				'log_id' => $log_id 
		), 'log' );
		$data ['log_id'] = $log_id;
		// Үүсгэсэн хүний ажлын утас, дугаар, албан тушаал авна.
		$cemp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $created_employee_id );
		foreach ( $cemp_cols as $row ) {
			$data ['cr_workphone'] = $row->workphone;
			$data ['cr_position'] = $row->position;
			$data ['cr_fullname'] = $row->fullname;
			$data ['cr_sector'] = $row->section_sector;
		}
		
		$act_emp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $activated_employee_id );
		foreach ( $act_emp_cols as $row ) {
			$data ['act_workphone'] = $row->workphone;
			$data ['act_position'] = $row->position;
			$data ['act_fullname'] = $row->fullname;
		}
		// $data['employee_cols']=$this->main_model->get_values('view_employee', 'employee_id', $employee_id);
		$this->load->view ( 'log/report/warnpage', $data );
	}
	// warnpage
	function file_view($log_id) {
		$data ['cols'] = $this->main_model->get_values ( 'view_logs', 'log_id', $log_id );
		$data ['log_id'] = $log_id;
		$this->load->view ( 'log/fileupload', $data );
	}
	
	// Activate Log numbers in form
	function fileupload($log_id) {
		if ($this->main_model->get_authority ( 'log', 'log', 'fileupload', $this->role ) == 'fileupload') {
			if ($log_id) {
				$data ['cols'] = $this->main_model->get_values ( 'view_logs', 'log_id', $log_id );
				$data ['log_id'] = $log_id;
				if ($this->input->get_post ( 'upload' )) {
					$config = array (
							'allowed_types' => 'doc|docx|pdf',
							'upload_path' => $this->gallery_path,
							'max_size' => 10000 
					);
					$this->load->library ( 'upload', $config );
					
					if (! $this->upload->do_upload ()) {
						$this->session->set_userdata ( 'message', $this->upload->display_errors () );
						redirect ( 'log/index' );
					} else {
						$upload_data = $this->upload->data ();
						$filename = $upload_data ['file_name'];
						
						$idata ['filename'] = $filename;
						$this->db->where ( 'log_id', $log_id );
						$this->db->update ( 'log', $idata );
						
						$this->session->set_userdata ( 'message', "<strong>$filename</strong> файлыг амжилттай байршууллаа." );
						redirect ( 'log/index' );
					}
				} else {
					$this->session->set_userdata ( 'message', "Нэг гэмтлийг сонгох хэрэгтэй." );
					redirect ( 'log/index' );
				}
			} else {
				$data ['title'] = 'Гэмтлийн дэлгэрэнгүй';
				$this->load->view ( 'log/fileupload', $data );
			}
		} else
			$this->load->view ( '43.html' );
	}
	function download() {
		$file = $this->uri->segment ( 3 );
		echo $file;
		$file_path = $this->gallery_path . $file;
		echo "</br>";
		$new=$this->gallery_path_url.$file;
		echo $new;

		$file_size = $this->get_filesize ( $file_path );
		$this->setFile ( $file );

		
		if ($file_size >= 45) {
			// BEGIN DOWNLOAD
			force_download ( $file, $file_path, 'large' );
		} else {
			// READ FILE CONTENTS
			$file_data = file_get_contents ($new, FILE_USE_INCLUDE_PATH );
			// BEGIN DOWNLOAD
			force_download ( $file, $file_path, 'small' );
		}
		// force_download($file, read_file($this->file), 'large');
	}
	function get_filesize($file_path) {
		$file_info = get_file_info ( $file_path );
		$file_size = round ( (round ( $file_info ['size'] / 1024 )) / 1024 );
		return $file_size;
	}
	function setFile($file) {
		$this->file = $this->gallery_path . $file;
	}
	
	// xls file download hiih
	function get_columns() {
		$columns = array ();
		$cols = array (
				'section',
				'location',
				'equipment',
				'created_datetime',
				'closed_datetime',
				'defect',
				'reason',
				'completion',
				'createdby',
				'closedby',
				'ezi' 
		);
		$display_as = array (
				'section' => 'Хэсэг',
				'location' => 'Байршил',
				'equipment' => 'Төхөөрөмж',
				'created_datetime' => 'Эхэлсэн',
				'closed_datetime' => 'Дууссан',
				'defect' => 'Гэмтэл',
				'reason' => 'Шалтгаан',
				'completion' => 'Гүйцэтгэл',
				'createdby' => 'Нээсэн',
				'closedby' => 'Хаасан',
				'ezi' => 'ЕЗИ' 
		);
		
		foreach ( $cols as $col_num => $column ) {
			$columns [$col_num] = ( object ) array (
					'field_name' => $column,
					'display_as' => $display_as [$column] 
			);
		}
		
		return $columns;
	}
	function get_list() {
		$this->db->select ( 'section, location,  equipment, created_datetime,  closed_datetime, defect, reason, completion, createdby, closedby, ezi' );
		$this->db->from ( 'view_logs' );
		if ($this->group == 'ENG') {
			$this->db->where ( 'section_id', $this->section_id );
		}
		$this->db->where ( "date_format(STR_TO_DATE(created_datetime, '%Y-%m-%d'), '%Y-%d')>=", '2014-10' );
		$this->db->where ( "section_id", '4' );
		$this->db->order_by ( 'section asc, created_datetime desc' );
		// echo $this->db->last_query();
		return $this->db->get ()->result ();
	}
	function exportToExcel() {
		$objdata->columns = $this->get_columns ();
		$objdata->list = $this->get_list ();
		
		// var_dump($objdata);
		$this->_export_to_excel ( $objdata );
	}
	protected function _export_to_excel($objdata) {
		$string_to_export = "";
		// print_r($objdata);
		// get columns
		foreach ( $objdata->columns as $column ) {
			$string_to_export .= $column->display_as . "\t";
		}
		$string_to_export .= "\n";
		// get lists
		foreach ( $objdata->list as $num_row => $row ) {
			foreach ( $objdata->columns as $column ) {
				$string_to_export .= $this->_trim_export_string ( $row->{$column->field_name} ) . "\t";
			}
			$string_to_export .= "\n";
		}
		// header('Content-Type: text/html; charset=utf-8');
		// var_dump($string_to_export);
		// Convert to UTF-16LE and Prepend BOM
		
		$string_to_export = "\xFF\xFE" . mb_convert_encoding ( $string_to_export, 'UTF-16LE', 'UTF-8' );
		// if(mb_check_encoding($string_to_export, 'UTF-16LE'))
		// echo "yes";
		// else
		// echo "no ";
		// var_dump($string_to_export);
		$filename = "export-" . date ( "Y-m-d_H:i:s" ) . ".xls";
		header ( 'Content-type: application/vnd.ms-excel;charset=UTF-16LE' );
		header ( 'Content-Disposition: attachment; filename=' . $filename );
		header ( "Cache-Control: no-cache" );
		echo $string_to_export;
		die ();
	}
	protected function _trim_export_string($value) {
		$value = str_replace ( array (
				"&nbsp;",
				"&amp;",
				"&gt;",
				"&lt;" 
		), array (
				" ",
				"&",
				">",
				"<" 
		), $value );
		return strip_tags ( str_replace ( array (
				"\t",
				"\n",
				"\r" 
		), "", $value ) );
	}
}
?>     

