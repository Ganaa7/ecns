<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class file_model extends CI_Model {
	var $file;
	var $path;
	function file_model() {
		parent::__construct ();
		$this->load->helper ( 'file' );
		$this->load->helper ( 'download' );
		$this->load->helper ( 'directory' );
		$this->load->helper ( 'url' );
		$this->load->library ( 'session' );
		$this->path = "application" . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR;
		$this->file = $this->path . "system_log.txt";
	}
	function write_login() {
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$today = date ( "Y-m-d H:i:s" );
		$username = $this->session->userdata ( 'username' );
		$data = $today . " " . $username . " logged in \r";
		write_file ( $this->file, $data, 'a' );
	}
	function write_logout() {
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$today = date ( "Y-m-d H:i:s" );
		$username = $this->session->userdata ( 'username' );
		$data = $today . " " . $username . " logged out \r";
		write_file ( $this->file, $data, 'a' );
	}
	function read_file() {
		$string = read_file ( $this->file );
		return $string;
	}
	/*
	 * function filenames_test() {
	 * $files = get_filenames($this->path, TRUE);
	 * print_r($files);
	 * }
	 *
	 * function dir_file_info_test() {
	 * $files = get_dir_file_info($this->path);
	 * print_r($files);
	 * }
	 *
	 * function file_info_test() {
	 * $info = get_file_info($this->file, 'date');
	 * print_r($info);
	 * }
	 *
	 * function mime_test() {
	 * //echo get_mime_by_extension($this->file);
	 * echo get_mime_by_extension('hello.png');
	 * }
	 */
}

?>