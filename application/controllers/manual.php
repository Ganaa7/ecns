<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class manual extends CNS_Controller {

	var $file;

	var $path;

	public function __construct() {
		
		parent::__construct ();

		$this->load->model ( 'manual_model' );

      	$this->load->library ( 'eManual' );
	
		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'manual', $this->role, 0, 1 ) );

		$this->config->set_item ( 'module_menu', 'Техник ашиглалтын заавар' );

		$this->config->set_item ( 'module_menu_link', '/manual' );

		$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
		
		$this->path = "download" . DIRECTORY_SEPARATOR . "manual_files" . DIRECTORY_SEPARATOR;			

	}
	
	function index() {

		$data ['title'] = 'Техник ашиглалтын заавар';
		
		$data ['page'] = 'file\manual';

        $data ['library_src'] = $this->javascript->external ( base_url().'assets/manual/manual.js', TRUE );

        $cert = new eManual ();

        $cert->set_user ( $this->user_id );

        $cert->set_role ( $this->role );

        $out = $cert->run ();

        if ($out->view) {

            $data ['out'] = $out;

            $data ['page'] = $out->page;

            $data ['title'] = $out->title;

            $section = $this->manual_model->get_select ( 'name', array('type'=>'industry'), 'section' );

            $section[0]='Бүх хэсэг';

            $data['section']=$section;

            $equipment= $this->manual_model->get_select ( 'equipment', array('is_group'=>NULL), 'equipment2' );

            $equipment[0]='Бүх төхөөрөмж';
            
            $data ['equipment'] =$equipment;
            //echo $this->manual_model->last_query();

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
	function help() {
		// $data['title']='Төлөвлөгөөт ажил';
		// $this->load->view('help\event', $data);
		echo $this->path;
	}
	function download() {
		$file = $this->uri->segment ( 3 );
		// echo $file;
		$file_path = $this->path . $file;
		$file_size = $this->get_filesize ( $file_path );
		$this->setFile ( $file );
		
		if ($file_size >= 45) {
			// BEGIN DOWNLOAD
			force_download ( $file, $file_path, 'large' );
		} else {
			// READ FILE CONTENTS
			$file_data = file_get_contents ( $this->path . $file );
			// BEGIN DOWNLOAD
			force_download ( $file, $file_data, 'small' );
		}
		
		// force_download($file, read_file($this->file), 'large');
	}
	function setFile($file) {
		$this->file = $this->path . $file;
		// echo $this->file;
	}
	function dir_file_info_test() {
		$files = get_dir_file_info ( $this->path );
		print_r ( $files );
	}
	function read_test($file) {
		$this->setFile ( $file );
		$string = read_file ( $this->file );
		echo $string;
	}

	function _settings_output($output = null) {
	    $this->load->view ( 'settings.php', $output );
	}
	function get_filesize($file_path) {
		$file_info = get_file_info ( $file_path );
		$file_size = round ( (round ( $file_info ['size'] / 1024 )) / 1024 );
		// return MB
		return $file_size;
	}


	function view_pdf(){
            //$data['file']=$filename;
            $data ['page'] = 'pdf\web\viewer.html';
            $this->load->view('pdf\web\viewer', $data);
        }
        
}

