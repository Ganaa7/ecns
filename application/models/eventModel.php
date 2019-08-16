<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class eventModel extends CI_Model {

	public $sec_code;

	public static $table;

	public $employee_id;
	
	function __construct() {
		// Call the Model constructor
		$this->load->database ();

		$this->load->model ( 'main_model' );

		$this->load->library ( 'session' );

		$this->load->library ( 'config' );

		$this->employee_id = $this->session->userdata ( 'employee_id' );

		$this->sec_code = $this->session->userdata ( 'sec_code' );

		$this->table = $this->user_model->set_event_table ( $this->role, $this->sec_code );

	}

	function add($data) {

		$this->db->insert ( 'event', $data );

		return $this->db->insert_id ();

	}

	function get($id = null) {

		$this->db->select ( '*' );

		$this->db->from ( 'event' );

		if (! is_null ( $id ))

			$this->db->where ( 'id', $id );

		$this->db->order_by ( 'id', 'desc' );

		return $this->db->get ()->result ();

	}

	function authorize($id) {

		// check activated? or note

		$activatedby_id = $this->main_model->get_row ( 'activedby_id', array (

				'id' => $id ), 'event' );

		$doneby_id = $this->main_model->get_row ( 'doneby_id', array ('id' => $id ), 'event' );

		if ($activatedby_id && $doneby_id) {

			// байвал энэ event-г approve хийх хэрэгтэй.

			$data ['approvedby_id'] = $this->employee_id;

			$equery = $this->db->query ( "SELECT value from settings  WHERE name in 
				(SELECT a.section_id FROM equipment A JOIN event B ON a.equipment_id = b.equipment_id where b.id = $id) and settings = 'event'" );

			$row = $equery->row_array ();

			$data ['color'] = $row ['value'];

			$this->db->where ( 'id', $id );

			$this->db->update ( 'event', $data );

			return $this->db->affected_rows ();

		} elseif ($activatedby_id == null && $doneby_id == null) {

			$data ['activedby_id'] = $this->employee_id;

			$equery = $this->db->query ( "SELECT value from settings WHERE name in (SELECT a.section_id FROM 
				equipment A JOIN event B ON a.equipment_id = b.equipment_id where b.id = $id) and settings = 'eauthor'" );

			$row = $equery->row_array ();

			$data ['color'] = $row ['value'];

			$this->db->where ( 'id', $id );

			$this->db->update ( 'event', $data );

			return $this->db->affected_rows ();

		} else {
			
			return 0;

		}

	}

	function delete($id) {

		$this->db->where ( 'id', $id );

		$this->db->delete ( 'event' );

		return $this->db->affected_rows ();
	}

	function update($id, $data) {
		
		$this->db->where ( 'id', $id );

		$this->db->update ( 'event', $data );

		return $this->db->affected_rows ();

	}

	function getEventtype($section_id = null) {
	
		// Хэрэв Admin төрлийн хэрэглэгч байвал. бүх sec_code-r shuune.
		$data [0] = 'Үйл ажиллагааны төрөл';

		$this->db->select ( 'eventtype_id, eventtype' );

		$this->db->from ( 'eventtype' );

		$this->db->order_by ( 'order', 'asc' );

		$Q_e = $this->db->get ();

		if ($Q_e->num_rows () > 0) {

			foreach ( $Q_e->result_array () as $row ) {

				$data [$row ['eventtype_id']] = $row ['eventtype'];

			}
		}

		$Q_e->free_result ();

		return $data;
	}

    // өгсөн утгаар мөрийн утгыг авах фүнкц
    function get_row($column, $where_arr = array(), $table) {

        $this->db->select ( $column );

        $this->db->where ( $where_arr );

        $query = $this->db->get ( $table );

        $row = $query->row_array ();

        if ($row)

            return $row [$column];

        else
            return null;
    }
}