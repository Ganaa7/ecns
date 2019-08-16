<?php

/*
 * created 07/16/2012
 * this feed to codeIgniter
 */
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class Feed extends CNS_Controller {

	function Feed() {
		parent::__construct ();
		$this->load->model ( 'log_model' );
		$this->load->helper ( 'xml' );
	}
	
	function index() {
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = 'Гэмтэл дутагдлын систем';
		$data ['feed_url'] = '';
		$data ['page_description'] = 'ХНБАҮА-ны гэмтэл дутагдлын систем';
		$data ['page_language'] = 'mn';
		$data ['creator_email'] = 'gandavaa.d@mcaa.gov.mn';
		$data ['posts'] = $this->db->get ( 'view_logs' );
		header ( "Content-Type: application/rss+xml" );
		$this->load->view ( 'feed/rss', $data );
	}
}

?>
