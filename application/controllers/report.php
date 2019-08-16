<?php
/*
 * This controller controlls
 * all reports in system
 */
class report extends CNS_Controller {
	public $where_array = array ();
	public $counter = 1;
	public $role;
	public function __construct() {
		parent::__construct ();
		$this->load->helper ( 'url' );
		$this->load->helper ( 'form' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'log_model' );
		$this->load->model ( 'main_model' );
		$this->load->model ( 'user_model' );
		$this->load->library ( 'session' );
		$this->load->library ( 'config' );
		if ($this->session->userdata ( 'role' )) {
			$this->role = $this->session->userdata ( 'role' );
			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'log', $this->role, 0, 1 ) );
			$this->session->unset_userdata ( 'home' );
			$this->config->set_item ( 'module_menu', 'Гэмтэл дутагдлын бүртгэл' );
		}
	}
	function log_equipments($sec_code = null) {
		// Хэрэв Админ бол үйлдвэрлэлийн хэсгийг
		// Холбоо гэж сонгоно уруу шууд орно.
		if ($this->session->userdata ( 'access_type' ) == 'ADMIN') {
			if (isset ( $_POST ['sec_code'] ) && $_POST ['sec_code'] != '0') {
				$table = 'view_logs';
				// $this->user_model->set_table($_POST['sec_code']);
				$sec_code = $_POST ['sec_code'];
				$this->where_array ['sec_code'] = $sec_code;
			} else {
				$data ['fields'] = $this->db->get ( 'view_logs_com' )->result ();
				$sec_code = 'COM';
				$table = 'view_logs_com';
				$this->where_array ['sec_code'] = $sec_code;
			}
		} else {
			$sec_code = $this->session->userdata ( 'sec_code' );
			$table = $this->session->userdata ( 'table' );
		}
		$data ['sec_code'] = $sec_code;
		
		if (isset ( $_POST ['equipment_id'] ) && $_POST ['equipment_id'] != '0') {
			$data ['equipment_id'] = $_POST ['equipment_id'];
			$this->where_array ['equipment_id'] = $_POST ['equipment_id'];
			$where_arr = array (
					'equipment_id' => $_POST ['equipment_id'] 
			);
			$data ['equip_name'] = $this->main_model->get_row ( 'name', $where_arr, 'equipment' );
		} else {
			$data ['equipment_id'] = 0;
			$data ['equip_name'] = 'Бүгд';
		}
		if (isset ( $_POST ['month'] ) && $_POST ['month'] != 0) {
			$data ['month'] = $_POST ['month'];
			$this->where_array ['MONTH(closed_datetime)'] = $_POST ['month'];
		} elseif (! isset ( $_POST ['month'] )) {
			$this->where_array ['MONTH(closed_datetime)'] = date ( "m", time () );
			$data ['month'] = date ( "m", time () );
		} else {
			$data ['month'] = 0;
		}
		
		$data ['fields'] = $this->db->get_where ( $table, $this->where_array )->result ();
		// $month= date("m", time());
		$data ['sql'] = $this->db->last_query ();
		// $data['sql'] = $month;
		
		$data ['months'] = array (
				0 => 'Хугацаагүй',
				'01' => '01 сар',
				'02' => '02 сар',
				'03' => '03 сар',
				'04' => '04 сар',
				'05' => '05 сар',
				'06' => '06 сар',
				'07' => '07 сар',
				'08' => '08 сар',
				'09' => '09 сар',
				'10' => '10 сар',
				'11' => '11 сар',
				'12' => '12 сар' 
		);
		
		$data ['sec_name'] = $this->user_model->get_section_name ( $sec_code );
		$data ['equipment'] = $this->main_model->get_equipments ( $sec_code );
		$data ['section'] = $this->main_model->get_section ();
		$data ['title'] = 'Тоног төхөөрөмжийн тайлан';
		$this->load->view ( 'log/report/equipments', $data );
	}
	function log_bytime() {
		// hesgiin dargaas uur bol
		if ($this->session->userdata ( 'user_type' ) == 'govern') {
			if (isset ( $_POST ['sec_code'] ) && $_POST ['sec_code'] != '0') {
				$table = 'view_logs'; // $this->user_model->set_table($_POST['sec_code']);
				$sec_code = $_POST ['sec_code'];
			} else {
				$sec_code = '0';
				$table = 'view_logs';
				// $sec_code = $this->session->userdata('sec_code');
				// echo $table;
			}
			// if($this->input->get_post('sec_code')) $sec_code =$_POST['sec_code'];
			// else $sec_code = $this->session->userdata('sec_code');
			// $table ='view_logs';
			$data ['section'] = $this->main_model->get_shift_section ( 'A' );
			// hesgiin darga bol
		} else {
			$sec_code = $this->session->userdata ( 'sec_code' ); // elc
			$table = 'view_logs';
			// $this->user_model->set_table($sec_code);
			$data ['section'] = $this->main_model->get_sectionby_code ( $sec_code, 'N' );
		}
		$data ['sec_code'] = $sec_code;
		$data ['table'] = $table;
		
		if ($this->input->get_post ( 'equipment_id' ))
			$data ['equipment_id'] = $this->input->get_post ( 'equipment_id' );
		else
			$data ['equipment_id'] = 0;
		
		if (isset ( $_POST ['log'] )) {
			$data ['log'] = $_POST ['log'];
		} else
			$data ['log'] = '0';
			
			// begin filter
		if ($this->input->get_post ( 'filter' )) {
			$start_dt = $this->input->get_post ( 'start_date' );
			$end_dt = $this->input->get_post ( 'end_date' );
			
			$data ['sec_code'] = $sec_code;
			$data ['table'] = $table;
			$data ['filter'] = 1;
			
			if ($start_dt && $end_dt) {
				$data ['start_date'] = $start_dt;
				$data ['end_date'] = $end_dt;
				$between = "((DATE_FORMAT(closed_datetime, '%Y-%m-%d') >= '$start_dt' AND DATE_FORMAT(closed_datetime, '%Y-%m-%d') <='$end_dt')
                          OR (DATE_FORMAT(created_datetime, '%Y-%m-%d') >= '$start_dt' AND DATE_FORMAT(created_datetime, '%Y-%m-%d') <='$end_dt'))";
			} else {
				$data ['start_date'] = 0;
				$data ['end_date'] = 0;
			}
			
			if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'SUR' || $sec_code == 'ELC')
				$data ['sections'] = $this->db->get_where ( 'view_industry', array (
						'sec_code' => $sec_code 
				) )->result ();
			else {
				$data ['sections'] = $this->db->get ( 'view_industry' )->result ();
			}
			// var_dump($data['sections']);
			foreach ( $data ['sections'] as $row ) {
				// Холбооны утгуудыг авах
				$this->db->select ( '*' );
				$this->db->from ( $table );
				$this->db->where ( 'sec_code', $row->sec_code );
				
				if ($this->input->get_post ( 'equipment_id' ))
					$this->db->where ( 'equipment_id ', $this->input->get_post ( 'equipment_id' ) );
				if ($this->input->get_post ( 'log' )) {
					if($this->input->get_post ( 'log' ) =='Y')
					   $this->db->where_in( 'closed', array('Y', 'Q', 'F'));
					else 
						$this->db->where( 'closed', $this->input->get_post('log'));
				}
				if (isset ( $between )) {
					$this->db->where ( $between );
					$data ['date'] = "$start_dt-с $end_dt хооронд";
				} else
					$data ['date'] = NULL;
				$data [$row->sec_code] = $this->db->get ()->result ();
			}
			// end filter here
		} else
			$data ['date'] = NULL;
		$data ['sec_code'] = $sec_code;
		//$data ['last_sql'] = $this->db->last_query ();
		$data ['equipment'] = $this->main_model->get_equipments ( $sec_code );
		$data ['title'] = 'Тоног төхөөрөмжийн тайлан';
		$this->load->view ( 'log/report/logbytime', $data );
	}
	function export_xls($sec_code, $equipment_id, $log, $start_date, $end_date) {		
		date_default_timezone_set("Asia/Ulan_Bator");
		$name = date('his');
		 

		echo "<head>";
		echo "<meta content='text/html'; charset='utf-8' http-equiv='Content-Type'>
                <title>eCNS::ХНАА Цахим бүртгэлийн систем</title>";
		echo "</head>";
		echo "<div id='container'><div><p>";
		if ($sec_code == '0') {
			$data ['sections'] = $this->db->get ( 'view_industry' )->result ();
			$table = 'view_logs';
		} else {
			$data ['sections'] = $this->db->get_where ( 'view_industry', array (
					'sec_code' => $sec_code 
			) )->result ();
			$table = $this->user_model->set_table ( $sec_code );
		}
		// $table='view_logs';
		
		$modified = $this->session->userdata ( 'username' );
		error_reporting ( E_ALL );
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		$this->load->helper ( 'PHPExcel' );
		/**
		 * Include PHPExcel
		 */
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		
		// Set document properties
		// echo date('H:i:s') , " Хуудасны утгуудыг өглөө" , PHP_EOL;
		// echo "<br/>";
		$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Shiftlog report" )->setSubject ( "Shiftlog Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'C1', 'Гэмтэл дутагдлын тайлан' );
		
		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
		
		$i = 3;
		$start_border;
		static $end_border;
		foreach ( $data ['sections'] as $row ) {
			// Холбооны утгуудыг авах
			$this->db->select ( '*' );
			$this->db->from ( 'view_logs' );
			$this->db->where ( 'sec_code', $row->sec_code );
			
			// доорх утгуудыг xls файлаар харуулахад алдаа гарч байна.
			// 2015-10-27нд засав
			// $temp_table= $this->user_model->set_table($row->sec_code);
			//
			// if($sec_code=='0')
			// $this->db->from($temp_table);
			// else {
			// $this->db->from($table);
			// $this->db->where('sec_code', $sec_code);
			// }
			if (isset ( $equipment_id ) && $equipment_id != 0)
				$this->db->where ( 'equipment_id ', $equipment_id );
			// нээгдээгүй байвал
			if (isset ( $log ) && $log == 'A') {
				$this->db->where ( 'closed', $log );
				$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Гэмтлийн №' )->setCellValue ( 'B2', 'Гэмтэл нээсэн огноо/цаг' )->setCellValue ( 'C2', 'Байршил' )->setCellValue ( 'D2', 'Төхөөрөмж' )->setCellValue ( 'E2', 'Шалтгаан' )->setCellValue ( 'F2', 'Гэмтэл' )->setCellValue ( 'G2', 'Гэмтэл нээсэн' );
				
				$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G2' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setARGB ( 'a9a9a9' );
				
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 12 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 18 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 14 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 12 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 17 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 22 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setWidth ( 15 );
			} else {
				$this->db->where_in( 'closed', array('Y', 'Q', 'F'));
				//$this->db->where ( 'closed', $log );
				$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Гэмтлийн №' )->setCellValue ( 'B2', 'Гэмтэл нээсэн огноо/цаг' )->setCellValue ( 'C2', 'Байршил' )->setCellValue ( 'D2', 'Төхөөрөмж' )->setCellValue ( 'E2', 'Шалтгаан' )->setCellValue ( 'F2', 'Гэмтэл' )->setCellValue ( 'G2', 'Гэмтэл нээсэн' )->setCellValue ( 'H2', 'Гэмтэл хаасан огноо/цаг' )->setCellValue ( 'I2', 'Үргэлжилсэн хугацаа ' )->setCellValue ( 'J2', 'Гүйцэтгэл' )->setCellValue ( 'K2', 'Гэмтэл хаасан' );
				
				$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K2' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor ()->setARGB ( 'a9a9a9' );
				
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 9 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 10 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 11.5 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 10.3 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 13.5 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 16 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setWidth ( 11 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'H' )->setWidth ( 10 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'I' )->setWidth ( 8 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'J' )->setWidth ( 25 );
				$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'J' )->setWidth ( 11 );
			}
			
			if ($start_date && $end_date) {
				$between = "((DATE_FORMAT(closed_datetime, '%Y-%m-%d') >= '$start_date' AND DATE_FORMAT(closed_datetime, '%Y-%m-%d') <='$end_date')
                          OR (DATE_FORMAT(created_datetime, '%Y-%m-%d') >= '$start_date' AND DATE_FORMAT(created_datetime, '%Y-%m-%d') <='$end_date'))";
				// $between ="DATE_FORMAT(created_datetime,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
				$this->db->where ( $between );
				$date = "$start_date-с $end_date хооронд";
			} else
				$data ['date'] = NULL;
			
			$sections = $this->db->get ()->result ();
			
			 //echo $this->db->last_query();
			
			$cnt = 1;
			foreach ( $sections as $cols ) {
				if ($cnt == 1) {
					if ($cols->sec_code == 'COM') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Холбооны хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
						$start_border = $i;
					}
					
					if ($cols->sec_code == 'NAV') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Навигацын хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					if ($cols->sec_code == 'SUR') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Ажиглалтын хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					if ($cols->sec_code == 'ELC') {
						$objPHPExcel->setActiveSheetIndex ()->setCellValue ( 'A' . $i, 'Гэрэл суулт цахилгааны хэсэг' );
						if ($log == 'A')
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':G' . $i );
						else
							$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':K' . $i );
					}
					$i ++;
					$cnt = 0;
				}
				if ($log == 'A') {
					$end_border = $i;
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->log_num );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->created_datetime );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->location );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->equipment );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->reason );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->defect );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $i, $cols->createdby );
					$i ++;
				} else {
					$end_border = $i;
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $cols->log_num );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $cols->created_datetime );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $cols->location );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $cols->equipment );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $cols->reason );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $cols->defect );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $i, $cols->createdby );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $i, $cols->closed_datetime );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $i, $cols->duration_time );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $i, $cols->completion );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $i, $cols->closedby );
					$i ++;
				}
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet ()->setTitle ( 'Report' );
		
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
		if ($log == 'A') {
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->applyFromArray ( $styleArray );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getAlignment ()->setWrapText ( true );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:G' . $end_border )->getFont ()->setSize ( 9 );
		} else {
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->applyFromArray ( $styleArray );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:K' . $end_border )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->getAlignment ()->setWrapText ( true );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $end_border )->getFont ()->setSize ( 10 );
		}
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		// Save Excel 2007 file
		// echo date('H:i:s') , " Write to Excel2007 format" , PHP_EOL;
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\report_'.$name.'.xlsx', __FILE__ ) );
		
		
		// echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', __FILE__) , PHP_EOL;
		// Echo memory peak usage
		// echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , PHP_EOL;
		// Echo done
		// echo date('H:i:s') , " Done writing file" , PHP_EOL;
		
		//$source = 'E:\xampp\htdocs\ecns\application\controllers\report.xlsx';
		//$destination = 'E:\xampp\htdocs\ecns\download\report.xlsx';
		
		//if (copy ( $source, $destination )) {
		//	unlink ( $source );
			// return $destination;
			// echo date('H:i:s')." Амжилттай хууллаа";
		//}
		// else echo "can't coppied";
		
		$url = base_url ();
		
		// echo anchor($url.'download/report.xlsx', 'XLS file-г хадгалах', 'title="News title"');
		// echo "</p></div></div>";
		redirect ( $url . 'download/report_'.$name.'.xlsx' );
		$this->load->helper('download');
		$data = file_get_contents($destination);
		$name = 'report'.$name.'.xlsx';
		force_download($name, $data);
	}
}      