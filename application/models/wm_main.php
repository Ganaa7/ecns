<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class wm_main extends CI_Model {
	public $sort_by;
	public $sort_order;
	public $offset;
	public $limit;
	public $total_rows;
	public $section_id;
	public $sector_id;
	public $equipment_id;
	public $sparetype_id;
	public $total_sql;
	function __construct() {
		// Call the Model constructor
		$this->load->database ();
		$this->load->helper ( 'array' );
		$this->load->model ( 'main_model' );
		$this->load->library ( 'session' );
	}
	function call_filter($table, $section_id, $sector_id, $equipment_id, $sparetype_id) {
		// ene function utguudiig avaad butsaaj ugnu
		$this->sort_by = 'section';
		$this->sort_order = 'desc';
		if ($this->input->get_post ( 'flag' ) == 1) {
			$this->offset = 0;
		}
		$this->limit = 15;
		$set_total = $this->set_total ( $table, $section_id, $sector_id, $equipment_id, $sparetype_id );
		$this->db->select ( '*' );
		if ($section_id) {
			$this->db->where ( 'section_id', $section_id );
		}
		
		if ($sector_id) {
			$this->db->where ( 'sector_id', $sector_id );
		}
		
		if ($equipment_id) {
			$this->db->where ( 'equipment_id', $equipment_id );
		}
		if ($sparetype_id) {
			$this->db->where ( 'sparetype_id', $sparetype_id );
		}
	}
	function set_total($table, $section_id, $sector_id, $equipment_id, $sparetype_id) {
		$this->db->select ( '*' );
		// $this->db->from($table);
		if ($section_id) {
			$this->db->where ( 'section_id', $section_id );
		}
		if ($sector_id) {
			$this->db->where ( 'sector_id', $sector_id );
		}
		if ($equipment_id) {
			$this->db->where ( 'equipment_id', $equipment_id );
		}
		if ($sparetype_id) {
			$this->db->where ( 'sparetype_id', $sparetype_id );
		}
		$this->total_rows = $this->db->get ( $table )->num_rows ();
		$this->total_sql = $this->db->last_query ();
	}
	function container($sort_by, $sort_order, $offset) {
		// ene function ni aguulahiin medeeg butsaana
		$this->total_rows = $this->db->get ( 'wm_view_container' )->num_rows ();
		$this->sort_by = $sort_by;
		$this->sort_order = $sort_order;
		$this->offset = $offset;
		$limit = 15;
		$this->section_id = $this->input->get_post ( 'section_id' );
		$this->sector_id = $this->input->get_post ( 'sector_id' );
		$this->sparetype_id = $this->input->get_post ( 'sparetype_id' );
		$this->equipment_id = $this->input->get_post ( 'equipment_id' );
		// $this->set_offset($this->section_id, $this->equipment_id, $this->sparetype_id);
		$this->set_filter_values ( $this->section_id, $this->sector_id, $this->equipment_id, $this->sparetype_id );
		$table = 'wm_view_container';
		$url = 'warehouse/index/';
		// call pagination
		// $sort_columns = array('log_num', 'created_datetime', 'location', 'equipment', 'reason', 'defect',
		// 'closed_datetime', 'duration_time', 'completion', 'createdby', 'closedby', 'provedby');
		// if($submit){
		$this->call_filter ( 'wm_view_container', $this->section_id, $this->sector_id, $this->equipment_id, $this->sparetype_id );
		// }
		$this->db->order_by ( $sort_by, $sort_order );
		$this->db->limit ( $limit, $this->offset );
		
		$query = $this->db->get ( $table );
		$data ['query'] = $query;
		$data ['last_sql'] = $this->db->last_query ();
		$carray = $this->call_pagination ( $url, $this->total_rows, $this->limit, 5 );
		$data ['total_rows'] = $carray ['total_rows'];
		$data ['base_url'] = $carray ['base_url'];
		$data ['per_page'] = $carray ['per_page'];
		$data ['offset'] = $this->offset;
		return $data;
	}
	
	// call pagination
	function call_pagination($url, $total_rows, $limit, $segment) {
		$config = array ();
		if (isset ( $this->sort_by ))
			$baseurl = $url . "/$this->sort_by/$this->sort_order/";
		else
			$baseurl = $url;
		$config ['total_rows'] = $total_rows;
		$config ['base_url'] = site_url ( $baseurl );
		$config ['per_page'] = $limit;
		$config ['num_links'] = 10;
		$config ['first_link'] = '&lt&ltЭхнийх';
		$config ['next_link'] = 'Дараах&gt';
		$config ['prev_link'] = '&ltӨмнөх';
		$config ['last_link'] = 'Сүүлийнх&gt&gt';
		if ($segment == 0)
			$config ['cur_page'] = $segment;
		$config ['uri_segment'] = $segment;
		$this->pagination->initialize ( $config );
		
		return $config;
	}
	function set_limit($newvar) {
		$this->limit = $newvar;
	}
	function spare($sort_by = 'section_id', $sort_order = 'asc', $offset = 0) {
		// filter hiisen esehiig shalgana
		$section_id = $this->input->get_post ( 'section_id' );
		$sector_id = $this->input->get_post ( 'sector_id' );
		$equipment_id = $this->input->get_post ( 'equipment_id' );
		$sparetype_id = $this->input->get_post ( 'sparetype_id' );
		$this->sort_by = $sort_by;
		$this->sort_order = $sort_order;
		$this->set_filter_values ( $section_id, $sector_id, $equipment_id, $sparetype_id );
		// set_total and get where
		
		if ($this->input->get_post ( 'flag' ) == 1 || isset ( $this->section_id ) || isset ( $this->sector_id ) || isset ( $this->equipment_id ) || isset ( $this->sparetype_id )) {
			$this->call_filter ( 'wm_view_spare', $this->section_id, $this->sector_id, $this->equipment_id, $this->sparetype_id );
		} else
			$this->total_rows = $this->db->get ( 'wm_view_spare' )->num_rows ();
			
			// paginnation here
		$data ['total_rows'] = $this->total_rows;
		
		$this->limit = 15;
		if ($sort_by)
			$this->db->order_by ( $sort_by, $sort_order );
		$this->db->limit ( $this->limit, $offset );
		$data ['query'] = $this->db->get ( 'wm_view_spare' );
		$url = "/wm_settings/spare/";
		$carray = $this->call_pagination ( $url, $this->total_rows, $this->limit, 5 );
		return $data;
	}
	function set_filter_values($section_id, $sector_id, $equipment_id, $sparetype_id) {
		if ($this->input->get_post ( 'flag' ) == 1) {
			if (isset ( $section_id )) {
				$this->session->set_userdata ( 'fsection_id', $section_id );
				$this->section_id = $section_id;
			}
			
			if (isset ( $sector_id )) {
				$this->session->set_userdata ( 'fsector_id', $sector_id );
				$this->sector_id = $sector_id;
			}
			
			if (isset ( $equipment_id )) {
				$this->session->set_userdata ( 'fequipment_id', $equipment_id );
				$this->equipment_id = $equipment_id;
			}
			if (isset ( $sparetype_id )) {
				$this->session->set_userdata ( 'fsparetype_id', $sparetype_id );
				$this->sparetype_id = $sparetype_id;
			}
		} else {
			if ($this->session->userdata ( 'fsection_id' ) != "")
				$this->section_id = $this->session->userdata ( 'fsection_id' );
			else
				$this->section_id = null;
			if ($this->session->userdata ( 'fsector_id' ) != "")
				$this->sector_id = $this->session->userdata ( 'fsector_id' );
			
			if ($this->session->userdata ( 'fequipment_id' ) != "")
				$this->equipment_id = $this->session->userdata ( 'fequipment_id' );
			
			if ($this->session->userdata ( 'fsparetype_id' ) != "")
				$this->sparetype_id = $this->session->userdata ( 'fsparetype_id' );
		}
	}
	function unset_fvalues() {
		$this->session->unset_userdata ( 'fsection_id' );
		$this->session->unset_userdata ( 'fsector_id' );
		$this->session->unset_userdata ( 'fequipment_id' );
		$this->session->unset_userdata ( 'fsparetype_id' );
	}
	
	// curent page is changed change the session values
	function check_curpage($cur_page) {
		if ($this->input->cookie ( 'cur_page' ) != $cur_page) {
			$this->input->set_cookie ( "cur_page", $cur_page, time () + 3600 );
			$this->wm_main->unset_fvalues ();
		}
	}
	function wm_grid($table, $url, $sort_by, $sort_order, $offset) {
		$section_id = $this->input->get_post ( 'section_id' );
		$sector_id = $this->input->get_post ( 'sector_id' );
		$equipment_id = $this->input->get_post ( 'equipment_id' );
		$sparetype_id = $this->input->get_post ( 'sparetype_id' );
		
		$this->set_filter_values ( $section_id, $sector_id, $equipment_id, $sparetype_id );
		$this->sort_by = $sort_by;
		$this->sort_order = $sort_order;
		// set_total and get where
		
		if ($this->input->get_post ( 'flag' ) == 1 || isset ( $this->section_id ) || isset ( $this->sector_id ) || isset ( $this->equipment_id ) || isset ( $this->sparetype_id )) {
			$this->call_filter ( $table, $this->section_id, $this->sector_id, $this->equipment_id, $this->sparetype_id );
		} else
			$this->total_rows = $this->db->get ( $table )->num_rows ();
			
			// paginnation here
		$data ['total_rows'] = $this->total_rows;
		$this->limit = 15;
		$this->db->order_by ( $this->sort_by, $this->sort_order );
		$this->db->limit ( $this->limit, $offset );
		$data ['query'] = $this->db->get ( $table );
		$carray = $this->call_pagination ( $url, $this->total_rows, $this->limit, 5 );
		
		return $data;
	}
	
	// check by year registered in table
	function checkRest($spare_id, $year) {
		$query = $this->db->get_where ( 'wm_restrecord', array (
				'spare_id' => $spare_id,
				'year' => $year 
		) );
		if ($query->num_rows () > 0) {
			$ret = true;
		} else {
			$ret = false;
		}
		$query->free_result ();
		return $ret;
	}
}
?>
