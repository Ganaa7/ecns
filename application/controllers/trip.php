<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class trip extends CNS_Controller {

    public $startdate;
    
    public $enddate;


	function __construct(){

        parent::__construct ();

        $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'trip', $this->role, 0, 1 ) );

        $this->session->unset_userdata ( 'home' );

        $this->config->set_item ( 'module_menu', 'Томилолтын бүртгэл' );

        $this->config->set_item ( 'module_menu_link', '/ecns/trip' );

        $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );
            
        $this->load->library ( 'Trip_Module' );

        $this->load->model ( 'purpose_model' );
        
        $this->load->model ('trip_model');
	}

	function index(){            

        $this->data['section']=$this->section_model->dropdown('name');
        $this->data['location']=$this->location_model->dropdown('name');
        $this->data['purpose']=$this->purpose_model->dropdown('purpose', 'purpose');        
        $this->data['employee']=$this->employee_model->with_drop_down('fullname');  

        $trip= new Trip_Module();
        $out = $trip->run ();
        $this->data['trip'] = $out;

		$this->data['javascript']=base_url().'assets/trip/trip.js';
		$this->load->view('/trip/index', $this->data);
	}

	function distance(){        
		try {
             if ($this->main_model->get_authority ( 'trip', 'trip', 'distance', $this->role )=='distance') {
                $crud = new grocery_CRUD ();
                $crud->set_table ( 'distance' );
                $crud->set_subject ('Чиглэл');                
                $crud->add_fields ( 'from_id', 'to_id', 'distance' );
                $crud->set_relation ( 'from_id', 'location', 'name' );
                $crud->set_relation ( 'to_id', 'location', 'name' );
                $crud->columns ( 'from_id','to_id', 'distance' );
                $crud->display_as ( 'id', '#' )->display_as ( 'from_id', 'Байршил 1' )->display_as ( 'to_id', 'Байршил 2' )->display_as ( 'distance', 'Зай' );
                $output = $crud->render ();
                 // print_r($output);
                $this->view_output( $output );
            } else {
                $this->load->view ( '43.html' );
            }
        } catch ( Exception $e ) {
            show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
        }

	}

    function view_output($output = null) {
        $this->load->view ('/trip/out', $output );
    }


    function report(){

        date_default_timezone_set ( ECNS_TIMEZONE );

		$enddate = $this->input->get_post ( 'enddate' );

		$startdate = $this->input->get_post ( 'startdate' );

		if ($enddate && $startdate) {

			$this->startdate = $startdate;

			$this->enddate = $enddate;

		} else {

			$this->startdate = date ( 'Y' ) . "-" . date ( 'm' ) . "-01";

			$this->enddate = date ( "Y-m-d" );
		}


        $data ['result'] = $this->trip_model->report(" start_dt >= '$this->startdate' and start_dt <='$this->enddate'");

        $data ['startdate'] = $this->startdate;
        
        $data ['enddate'] = $this->enddate;

        // print_r($data ['result']);

        // echo $this->db->last_query();
        
        $data ['file_link'] = $this->report_xls ();

        $data ['page'] = 'trip\report';
        
        $this->load->view ( 'index', $data );

    }


    public function report_xls()
    {
        $result = $this->trip_model->report(" start_dt >= '$this->startdate' and start_dt <='$this->enddate'");

        date_default_timezone_set ( 'Asia/Ulan_Bator' );
        
        $modified = $this->session->userdata ( 'fullname' );

        $this->load->helper ( 'PHPExcel' );
        
        $objPHPExcel = new PHPExcel ();
        // Set document properties
        $objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS maintenance trip report" )->setSubject ( "Trip Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
        
        $objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
        
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Тайлант огноо:' . $this->startdate . "-" . $this->enddate )->setCellValue ( 'E2', 'Тайлан гаргасан:' . $modified );
        
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'ХОЛБОО, НАВИГАЦИ, АЖИЛГЛАЛТЫН АЛБАНЫ ТОМИЛОЛТЫН ТАЙЛАН' );
        
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A4', 'Д/д' )->setCellValue ( 'B4', 'Орлогын №' )->setCellValue ( 'C4', 'Орлого огноо' )->setCellValue ( 'D4', 'Нэр төрөл' )->setCellValue ( 'E4', 'Хэм, нэгж' )->setCellValue ( 'F4', 'Тоо, ширхэг' )->setCellValue ( 'G4', 'Нэг бүрийн үнэ' )->setCellValue ( 'H4', 'Нийт үнэ' )->setCellValue ( 'I4', 'Нийлүүлэгч' );
        // balance
        $j = 5; // rows
        $cnt = 1;
        
        foreach ( $result as $row ) {
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $cnt );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $j, $row->section );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $j, $row->location );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, $row->purpose );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $j, $row->transport );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $j, $row->distance );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $j, $row->start_dt );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $j, $row->end_dt );
            $objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $j, $row->employee );
            $j ++;
            $cnt ++;
        }
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
        $eborder = $j - 1;
        
        $objPHPExcel->getActiveSheet ()->getStyle ( 'A4:I' . $eborder )->applyFromArray ( $styleArray );
        $objPHPExcel->getActiveSheet ()->getStyle ( 'A4:I' . $eborder )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
        
        $objPHPExcel->getActiveSheet ()->getStyle ( 'A4:I' . $eborder )->getAlignment ()->setWrapText ( true );
        $objPHPExcel->getActiveSheet ()->getStyle ( 'A4:I' . $eborder )->getFont ()->setSize ( 9 );
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objPHPExcel->getActiveSheet ()->setTitle ( 'Sheet1' );		
        
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( str_replace ( __FILE__, 'D:\xampp\htdocs\ecns\download\trip_report.xlsx', __FILE__ ) );

        $url = base_url ();
        
		$file_url = $url . 'download/trip_report.xlsx';

		return $file_url;
    }
}