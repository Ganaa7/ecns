<?php
/*
 * 2012-09-18
 * This file used for warehouse systems
 * Createdby: Gandavaa
 *
 */

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class wh_spare extends CNS_Controller {

	public $role;	

	public $startdate;

	public $enddate;

	function __construct() {

		parent::__construct ();
		
		$objdata = ( object ) array ();

		$this->load->library ( 'Warehouse' );

		$this->load->model ( 'wh_spare_model' );		
		
		$this->load->model ( 'spare_model' );		

		$this->load->model ( 'site_model' );		
		
		if ($this->session->userdata ( 'role' )) {

			$this->role = $this->session->userdata ( 'role' );

			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'wh_spare', $this->role, 0, 1 ) );

			$this->session->unset_userdata ( 'home' );

			$this->config->set_item ( 'module_menu', 'Сэлбэг хангамжийн бүртгэл' );

			$this->config->set_item ( 'module_menu_link', '/wh_spare' );

			$this->config->set_item ( 'javascript_location', '/assets/js' );
		}

		$this->load->library ( 'Spare_Module' );
	}

	function index() {
			
		$data ['library_src'] = $this->javascript->external ( base_url().'assets/warehouse/js/warehouse.js', TRUE );		
		$user_id = $this->session->userdata ( 'employee_id' );

		$log = new Warehouse ();

		$log->set_user ( $user_id );

		$log->set_role ( $this->role );		

		$out = $log->run ();		

		if ($out->view) {

			$data ['out'] = $out;

			$data ['page'] = 'wh_spare\index';

			$data ['form'] = $out->form;

			$data ['title'] = "Сэлбэгийн хангамжийн бүртгэл";

			$this->load->view ( 'index', $data );

		}else {
			
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

		$data ['title'] = 'Сэлбэг хангалт тусламж';

		$this->load->view ( 'help\warehouse', $data );

	}

	function barcode() {

		$data ['title'] = 'Barcode үүсгүүр';
		
		$text = $this->input->get_post ( 'text' );

		$size = $this->input->get_post ( 'size' );

		$code_type = $this->input->get_post ( 'codetype' );

		$orientation = $this->input->get_post ( 'orientation' );
		
		if ($this->input->get_post ( 'generate' ) == 'Үүсгэх') {
			$print = 'Y';
			$sizefactor = 1;
			$filepath = null;
		}
		$this->load->view ( 'warehouse\barcode', $data );
	}

	function get_exp_dtl(){		

		$spare_id = $this->input->get_post('spare_id');

		$invoice_id = intval($this->input->get_post('invoice_id'));		
		
		// тухайн сэлбэгээр агуулахын тавиурт байгаа сэлбэг регистерүүдийг харуулна!		

		if($invoice_id){

			$qry = "SELECT b.id, c.pallet, b.barcode, b.amt
                FROM wh_invoice A
                JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                        (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                WHERE invoicedate <=curdate()
				union 
				SELECT a.id, c.pallet, a.barcode, a.amt FROM wh_invoice_dtl A
					LEFT JOIN wm_view_pallet C on A.pallet_id = C.pallet_id
				WHERE invoice_id = $invoice_id and aqty = -1;				
                ";
		}else
			$qry = "SELECT *
                FROM wh_invoice A
                JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                        (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                WHERE invoicedate <=curdate()";
				
		$query = $this->db->query ( $qry );
		
		$cnt = 1;

		$rowId = 1;

		$data = array();

		if ($query->num_rows () > 0) {			

        echo "<table width='300' align ='right' cellpadding='0' cellspacing ='0'>";
        echo "<tr>";
        echo "<th>";
        echo "#";
        echo "</th>";
        echo "<th>";
        echo "№";
        echo "</th>";
        echo "<th>";
        echo "Тавиур";
        echo "</th>";
        echo "<th>";
        echo "Баркод";
        echo "</th>";
        echo "<th>";
        echo "Үнэ /нэг бүр/";
        echo "</th>";
        echo "</tr>";

        foreach ( $query->result () as $row ) {
          echo "<tr>";
          echo "<td>";
          if($invoice_id)
              echo "<input id='check_$rowId' type='checkbox' name='spare_pk[]' value='$row->id' onclick='check_it_edit(this)'/>";
          else
              echo "<input id='check_$rowId' type='checkbox' name='spare_pk[]' value='$row->id' onclick='checkit(this)'/>";
          echo "</td>";
          echo "<td>";

          echo "</td>";
          echo "<td>";
          echo $row->pallet;
          echo "</td>";
          echo "<td>";
          echo $row->barcode;
          echo "</td>";
          echo "<td>";
          echo $row->amt;
          echo "<input type='hidden' id='amt_$rowId' value='$row->amt'>";
          echo "</td>";
          echo "</tr>";
          $cnt ++;
          $rowId = $cnt;
        }	
		} else {
			$data['id']=null;
		}
	}

	function report($type) {
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

		if($type=='income'){

			$this->db->where ( "income_date >=", $this->startdate );
			
	        $this->db->where ( "income_date <=", $this->enddate );

	        $this->db->order_by("invoice_id","asc");

			$data ['startdate'] = $this->startdate;
			
			$data ['enddate'] = $this->enddate;
			
			$data ['r_result'] = $this->db->get ( '_wh_vw_income' )->result ();
			
	        $last_qry = $this->db->last_query ();
	        
	        $sql = "SELECT sum(aqty) as t_qty, FORMAT(sum(amt),2) as total
	                        FROM wh_invoice_dtl a   join wh_income b ON a.invoice_id = b.invoice_id
	                         WHERE b.income_date >='$this->startdate' and b.income_date <= '$this->enddate'";

			$row = $this->db->query ( $sql )->row ();
			
			$data ['t_qty'] = $row->t_qty;	
					
	        $data ['total'] = $row->total;

			$data ['last_qry'] = $last_qry;
			
			$data ['title'] = "Орлогийн тайлан";
			
			$data ['page'] = 'wh_spare\report\income';
			
	        $data ['file_link'] = $this->report_xls ('income');

			$this->load->view ( 'index', $data );
			
		}else{
	        //expense here
	        $this->startdate = $this->input->get_post ( 'startdate' );
	        $this->enddate = $this->input->get_post ( 'enddate' );
	        $this->main_model->access_check ();
	        $this->main_model->check_byrole ( 'warehouse', $this->role );
	        if ($this->startdate && $this->enddate) {
	                $this->db->where ( "expense_date >=", $this->startdate );
	                $this->db->where ( "expense_date <=", $this->enddate );
	        } else {
	                $this->enddate = date ( "Y-m-d" );
	                $this->startdate = date ( 'Y' ) . "-" . date ( 'm' ) . "-01";
	                $this->db->where ( "expense_date >=", $this->startdate );
	                $this->db->where ( "expense_date <=", $this->enddate );
	        }

	        $this->db->order_by( "invoice_id", "asc");

	        $data ['startdate'] = $this->startdate;
	        $data ['enddate'] = $this->enddate;

	        $data ['r_result'] = $this->db->get ( 'vw_expense_dtl' )->result ();
	        $data ['last_qry'] = $this->db->last_query ();

	        $sql = "SELECT  abs(SUM(qty)) as qty, FORMAT(sum(total), 2) as total from    `vw_expense_dtl` `a`
	                        WHERE expense_date >='$this->startdate' and expense_date <= '$this->enddate'";
	        $row = $this->db->query ( $sql )->row ();
	        $data ['qty'] = $row->qty;			
	        $data ['total'] = $row->total;
	        echo $this->db->last_query();
	        $data ['title'] = "Зарлагийн тайлан";
	        $data ['page'] = 'wh_spare\report\expense';			
	        $data ['file_link'] = $this->report_xls( 'expense' );
	        $this->load->view ( 'index', $data );
		}
	}

	//its generates excel files to the files
	private function report_xls($type){
		
		if($this->startdate&&$this->enddate){
		   $start_date = $this->startdate;
		   $end_date = $this->enddate;
		}else{
			$start_date = $this->input->get_post ( 'startdate' );
			$end_date = $this->input->get_post ( 'enddate' );
		}

		if($type=='income'){
			
			$this->db->where ( "income_date >=", $start_date );
			$this->db->where ( "income_date <=", $end_date );				

			$result = $this->db->get ( '_wh_vw_income' )->result ();
			date_default_timezone_set ( 'Asia/Ulan_Bator' );
			
			$modified = $this->session->userdata ( 'fullname' );
			$this->load->helper ( 'PHPExcel' );
			
			$objPHPExcel = new PHPExcel ();
			// Set document properties
			$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Warehouse income report" )->setSubject ( "Warehouse Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Тайлант огноо:' . $this->startdate . "-" . $this->enddate )->setCellValue ( 'E2', 'Тайлан гаргасан:' . $modified );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ОРЛОГЫН ТАЙЛАН' );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A4', 'Д/д' )->setCellValue ( 'B4', 'Орлогын №' )->setCellValue ( 'C4', 'Орлого огноо' )->setCellValue ( 'D4', 'Нэр төрөл' )->setCellValue ( 'E4', 'Хэм, нэгж' )->setCellValue ( 'F4', 'Тоо, ширхэг' )->setCellValue ( 'G4', 'Нэг бүрийн үнэ' )->setCellValue ( 'H4', 'Нийт үнэ' )->setCellValue ( 'I4', 'Нийлүүлэгч' );
			// balance
			$j = 5; // rows
			$cnt = 1;
			
			foreach ( $result as $row ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $cnt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $j, $row->income_no );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $j, $row->income_date );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, $row->spare );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $j, $row->short_code );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $j, $row->qty );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $j, $row->amt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $j, $row->total );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $j, $row->supplier );
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
			$objPHPExcel->getActiveSheet ()->setTitle ( 'Income' );		
			
			// echo $this->db->last_query();

		}else{
			$this->db->where ( "expense_date >=", $start_date );
			$this->db->where ( "expense_date <=", $end_date );	

			$result = $this->db->get ( 'vw_expense_dtl' )->result ();
                       
			date_default_timezone_set ( 'Asia/Ulan_Bator' );
			$date = date ( 'Y/m/d' );
			$modified = $this->session->userdata ( 'fullname' );
			$this->load->helper ( 'PHPExcel' );
			
			$objPHPExcel = new PHPExcel ();
			// Set document properties
			$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Warehouse income report" )->setSubject ( "Warehouse Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Тайлант огноо:' . $this->startdate . "-" . $this->enddate )->setCellValue ( 'E2', 'Тайлан гаргасан:' . $modified );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ЗАРЛАГИЙН ТАЙЛАН' );
			
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A4', ' №' )->setCellValue ( 'A4', 'Баримт №' )->setCellValue ( 'B4', 'Зарлага огноо' )->setCellValue ( 'C4', 'Сэлбэг' )->setCellValue ( 'D4', 'Хэм.нэгж' )->setCellValue ( 'E4', 'Тоо ширхэг' )->setCellValue ( 'F4', 'Үнэ' )->setCellValue ( 'G4', 'Нийт' )->setCellValue ( 'H4', 'Хэсэг' )->setCellValue ( 'I4', 'Зориулалт' )->setCellValue ( 'J4', 'Хүлээн авсан' );
			// balance
			$j = 5; // rows
			$cnt = 1;
			foreach ( $result as $row ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $cnt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $row->expense_no );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $j, $row->expense_date );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $j, $row->spare );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, $row->short_code );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $j, $row->qty );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $j, $row->amt );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $j, $row->total );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $j, $row->section );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $j, $row->intend );
				$objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $j, $row->receivedby );
				$j ++;
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
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A4:J' . $eborder )->applyFromArray ( $styleArray );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A4:J' . $eborder )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A4:J' . $eborder )->getAlignment ()->setWrapText ( true );
			$objPHPExcel->getActiveSheet ()->getStyle ( 'A4:J' . $eborder )->getFont ()->setSize ( 9 );
			
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$objPHPExcel->getActiveSheet ()->setTitle ( 'Expense' );
			


		}
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\wh_report_'.$type.'.xlsx', __FILE__ ) );

		$url = base_url ();
		$file_url = $url . 'download/wh_report_'.$type.'.xlsx';
		//redirect ( $url . 'download/wh_report_'.$type.'.xlsx' );		
		//echo $this->db->last_query();
		return $file_url;

	}


	function print_order($order_id){	
        //get order_id and select * from orders where order_id =1
        $section_id=0;
        $sql = "SELECT *, day(order_date) as oDay, month(order_date) as oMonth, year(order_date) as oYear, 
                      day(registed_date) rDay,  month(registed_date) rMonth, year(registed_date) rYear,
                      day(prove_date) pDay,  month(prove_date) pMonth, year(prove_date) pYear
                  FROM wh_view_order WHERE order_id = $order_id";
        $hResult=$this->db->query($sql)->result();
        foreach ($hResult as $row){
           $data['order_no'] =$row->order_no;
           $data['oDay'] =$row->oDay;
           $data['oMonth'] =$row->oMonth;
           $data['oYear'] =$row->oYear;
           $data['rDay'] =$row->rDay;
           $data['rMonth'] =$row->rMonth;
           $data['rYear'] =$row->rYear;
           $data['pDay'] =$row->pDay;
           $data['pMonth'] =$row->pMonth;
           $data['pYear'] =$row->pYear;
           $data['chiefeng'] =$row->chiefeng;
           $data['chief'] =$row->chief;
           $data['ordertype'] =$row->order_type;

           $section_id=$row->section_id;
        }

        $query=$this->db->query("SELECT * FROM view_employee where section_id = $section_id and role ='CHIEF' LIMIT 1");
        // echo $this->db->last_query();
        if($query->num_rows()>0){
           $c_row = $query->row(); 
           $data['section_chief']=$c_row->fullname;
        }else
           $data['section_chief']='';

        $data['dtlResult']=$this->db->query("SELECT * FROM wm_orderdetail WHERE order_id = $order_id")->result();      
        $data['page']='wh_spare\report\order';

        $this->load->view('wh_spare\report\order',$data); 
   }
   
        //Агуулахын үлдэгдэлийн тайлан
    function general(){

       date_default_timezone_set ( ECNS_TIMEZONE );
       $startdate = $this->input->get_post ( 'startdate' );
       if ($startdate) {
            $this->startdate = $startdate;               
       } else {
            $this->startdate = date ( 'Y' ) . "-" . date ( 'm' ) ."-". date('d');                
       }            
       //echo "select hi!";
       $data['startdate']=$this->startdate;
       $section = $this->spare_model->get_select('name', array('spare'=>'yes'), 'section');
       unset($section[0]);
       $data['section'] =$section;
               
       //function get_select($column, $where = null, $table) 
       $section_id= $this->input->get_post ( 'section_id' );
       if(!$section_id){
           $section_id = 1;
       }
    //    disabled at 2019-06-19
    //    $sql = "select `b`.`spare_id` AS `spare_id`, `c`.`section_id` AS `section_id`,   `c`.`section` AS `section`,
    //         `c`.`sector` AS `sector`, `c`.`equipment` AS `equipment`, `c`.`equipment_id` AS `equipment_id`,
    //         `c`.`spare` AS `spare`, `c`.`sparetype` AS `sparetype`,  `c`.`short_code` AS `short_code`,  floor(((to_days(curdate()) - to_days(`b`.`min_date`)) / 365)) AS `years_old`,
    //         round(sum(`a`.`aqty`), 2) AS `qty`,  round((sum(`a`.`amt`) / sum(`a`.`aqty`)), 2) AS `amt`,
    //         round(sum(`a`.`amt`), 2) AS `total`   
    //         from  (((`wh_invoice_dtl` `a`   join `wh_invoice` `d` ON ((`a`.`invoice_id` = `d`.`id`)))
    //             left join `vw_spare_mindate` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
    //             join `_wh_vw_spare` `c` ON ((`b`.`spare_id` = `c`.`spare_id`)))
    //         where   (`d`.`invoicedate` <= '$this->startdate') and c.section_id = $section_id
    //         group by `b`.`spare_id` , `b`.`min_date` , `c`.`spare` , `c`.`section` , `c`.`sector` , `c`.`short_code`
	//         having (sum(`a`.`aqty`) > 0) order by sector_id";          
	$sql = " SELECT `a`.`spare_id` AS `spare_id`,`c`.`section_id` AS `section_id`, 	`c`.`section` AS `section`,
			`c`.`sector` AS `sector`, `c`.`equipment` AS `equipment`,	`c`.`equipment_id` AS `equipment_id`,
			`c`.`spare` AS `spare`, `c`.`sparetype` AS `sparetype`,	`c`.`short_code` AS `short_code`,
			FLOOR(((TO_DAYS(CURDATE()) - TO_DAYS(`b`.`min_date`)) / 365)) AS `years_old`, 	ROUND(SUM(`a`.`aqty`), 2) AS `qty`,
			ROUND((SUM(`a`.`amt`) / SUM(`a`.`aqty`)), 2) AS `amt`, 	ROUND(SUM(`a`.`amt`), 2) AS `total`
		FROM
			(((`wh_invoice_dtl` `a`
			JOIN `wh_invoice` `d` ON ((`a`.`invoice_id` = `d`.`id`)))
			JOIN `vw_spare_mindate` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
			JOIN `_wh_vw_spare` `c` ON ((`a`.`spare_id` = `c`.`spare_id`)))
		WHERE
			(`d`.`invoicedate` <= CURDATE())
		GROUP BY `a`.`spare_id` , `b`.`min_date` , `c`.`spare` , `c`.`section` , `c`.`sector` , `c`.`short_code`
		HAVING (SUM(`a`.`aqty`) <> 0)";
	
	   
        $data ['result'] = $this->db->query( $sql )->result ();
        $result = $this->db->query ( $sql );            
        
		$data['last_qry']=$this->db->last_query();
		
        //disabled at 2019-06-19
        // $sql2 = "select sum(temp.qty) as qty, FORMAT(sum(temp.total),2) as total 
        //     from (select SUM(a.aqty) as qty, sum(a.amt) as total
        //     from  (((`wh_invoice_dtl` `a`   join `wh_invoice` `d` ON ((`a`.`invoice_id` = `d`.`id`)))
        //         left join `vw_spare_mindate` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
        //         join `_wh_vw_spare` `c` ON ((`b`.`spare_id` = `c`.`spare_id`)))
        //     where   (`d`.`invoicedate` <= '$this->startdate') and c.section_id = $section_id
        //     group by `b`.`spare_id` , `b`.`min_date` , `c`.`spare` , `c`.`section` , `c`.`sector` , `c`.`short_code`
		//     having (sum(`a`.`aqty`) > 0)) as temp";
		
		$sql2 = " select sum(qty) as qty, sum(total) as total from 
			(SELECT SUM(a.aqty) as qty, ROUND(SUM(`a`.`amt`), 2) AS `total`
			FROM
				(((`wh_invoice_dtl` `a`
				JOIN `wh_invoice` `d` ON ((`a`.`invoice_id` = `d`.`id`)))
				JOIN `vw_spare_mindate` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
				JOIN `_wh_vw_spare` `c` ON ((`a`.`spare_id` = `c`.`spare_id`)))
			WHERE
				(`d`.`invoicedate` <= '$this->startdate' and c.section_id = $section_id)
			GROUP BY `a`.`spare_id` , `b`.`min_date` , `c`.`spare` , `c`.`section` , `c`.`sector` , `c`.`short_code`
			HAVING (SUM(`a`.`aqty`) <> 0)) as temp";
    
        
        $row = $this->db->query ( $sql2 )->row ();
        $data ['qty'] = $row->qty;			
        $data ['total'] = $row->total;
        $data['last_query']=$this->db->last_query();
        
        $data ['title'] = "Зарлагийн тайлан";
        $data ['page'] = 'wh_spare\report\general';	
        $data ['file_link'] = $this->general_xls($this->startdate, $section_id );
        $this->load->view ( 'index', $data );
    }
    
   function general_xls($startdate, $section_id) {
        $section = $this->spare_model->get_row('name', array('section_id'=>$section_id), 'section');
        
        $sql = "select `b`.`spare_id` AS `spare_id`, `c`.`section_id` AS `section_id`,   `c`.`section` AS `section`,
            `c`.`sector` AS `sector`, `c`.`equipment` AS `equipment`, `c`.`equipment_id` AS `equipment_id`,
            `c`.`spare` AS `spare`, `c`.`sparetype` AS `sparetype`,  `c`.`short_code` AS `short_code`,  floor(((to_days(curdate()) - to_days(`b`.`min_date`)) / 365)) AS `years_old`,
            round(sum(`a`.`aqty`), 2) AS `qty`,  round((sum(`a`.`amt`) / sum(`a`.`aqty`)), 2) AS `amt`,
            round(sum(`a`.`amt`), 2) AS `total`   
            from  (((`wh_invoice_dtl` `a`   join `wh_invoice` `d` ON ((`a`.`invoice_id` = `d`.`id`)))
                left join `vw_spare_mindate` `b` ON ((`a`.`spare_id` = `b`.`spare_id`)))
                join `_wh_vw_spare` `c` ON ((`b`.`spare_id` = `c`.`spare_id`)))
            where   (`d`.`invoicedate` <= '$startdate') and c.section_id = $section_id
            group by `b`.`spare_id` , `b`.`min_date` , `c`.`spare` , `c`.`section` , `c`.`sector` , `c`.`short_code`
            having (sum(`a`.`aqty`) > 0) order by sector_id";  

        $result = $this->db->query ($sql)->result ();
        
        $modified = $this->session->userdata ( 'fullname' );
        $this->load->helper ( 'PHPExcel' );

        $objPHPExcel = new PHPExcel ();
        // Set document properties
        $objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Warehouse income report" )->setSubject ( "Warehouse Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );

        $objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );

        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Тайлант огноо:' . $startdate   )->setCellValue ( 'E2', 'Тайлан гаргасан:' . $modified );
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A3', 'Хэсэг:' . $section);

        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ҮЛДЭГДЛИЙН ТАЙЛАН' );

        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A4', 'Д/д' )->setCellValue ( 'B4', 'Тасаг' )->setCellValue ( 'C4', 'Тоног төхөөрөмж' )
                    ->setCellValue ( 'D4', 'Сэлбэг' )->setCellValue ( 'E4', 'Төрөл' )->setCellValue ( 'F4', 'Насжилт' )
                    ->setCellValue ( 'G4', 'Тоо/ширхэг' )->setCellValue ( 'H4', 'Үнэ/нэгж' )->setCellValue ( 'I4', 'Нийт' );
        // balance
        $j = 5; 
        $cnt = 1;

        foreach ( $result as $row ) {
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $cnt );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $j, $row->sector );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $j, $row->equipment);
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, $row->spare );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $j, $row->sparetype );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $j, $row->years_old );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $j, $row->qty );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $j, $row->amt );
                $objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $j, $row->total );
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
        $objPHPExcel->getActiveSheet ()->setTitle ( 'Ерөнхий журнал' );	
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\wh_report_general.xlsx', __FILE__ ) );

        $url = base_url ();
        $file_url = $url . 'download/wh_report_general.xlsx';
        //redirect ( $url . 'download/wh_report_'.$type.'.xlsx' );		            
        return $file_url;
        
    }


    // h_spare buyu gar deerh selbegiin jagsaalt
    function spare(){

	    $trip= new Spare_Module();
	    
       $out = $trip->run ();
       
       $this->data['spare'] = $out;
		 
		 $this->data['section']=$this->section_model->dropdown('name');
		 
		 $this->data['spares']=$this->wh_spare_model->dropdown('spare');
		
		 $this->data['site']=$this->site_model->dropdown('site');

       $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/h_spare/js/h_spare.js', TRUE ));
        
        $this->data['page']='wh_spare\h_spare\index';
        $this->load->view('index', $this->data);
    }


    function print_barcode(){

    	// Тухайн spare_id -гаар тухайн үлдэгдлийн баркодуудыг хэвлэнэ.    	

    	$spare_id = $this->input->get_post('spare_id');

    	$qty = $this->input->get_post('qty');

    	$qry = "SELECT *
                FROM wh_invoice A
                JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                        (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $spare_id) and spare_id =  $spare_id) B ON A.id =B.invoice_id 
                left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                left join _wh_vw_spare D on B.spare_id = D.spare_id
                WHERE invoicedate <=curdate()";

       $query = $this->db->query( $qry );

      $data['query']  = $query;

       echo $this->db->last_query();
			
		  $this->load->helper ( 'PHPExcel' );
		 	$objPHPExcel = new PHPExcel ();
		 	$objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( 'USEr' )->setTitle ( "ECNS Shiftlog report" )->setSubject ( "Shiftlog Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Barcode file" );
			
	 					
	 		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'Code' )->setCellValue ( 'B1', 'pallet' )->setCellValue ( 'C1', 'section' )->setCellValue ( 'D1', 'sector' )->setCellValue ( 'E1', 'equipment' )->setCellValue ( 'F1', 'spare' );


			
	 		$objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
	 		$i = 2;

	 		if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $i, $row->barcode );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $i, $row->pallet );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $i, $row->section );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $i, $row->sector );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $i, $row->equipment );
					$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $i, $row->spare );
					$i ++;
				}

			}
			
			$objPHPExcel->setActiveSheetIndex ( 0 );
			// Save Excel 2007 file
			// echo date('H:i:s') , " Write to Excel2007 format" , PHP_EOL;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( str_replace ( '.php', '.xlsx', __FILE__ ) );
			$filename = 'E:\xampp\htdocs\ecns\application\controllers\wh_spare.xlsx';
			$destination = 'E:\xampp\htdocs\ecns\download\wh_spare.xlsx';
				
			if (file_exists ( $filename )) {
			// move to the direcotry download
			   $result = copy ( $filename, $destination );
			   if ($result) {
				   unlink ( $filename );
				   // echo "amjilttai huullaa";
				   // $this->income				   
				   $data ['msg'] = "Амжилттай хадгаллаа! <br> XLS файлыг амжилттай үүсгэлээ!";
				   $data ['link'] = "<a class='button' href='" . base_url () . "download/wh_spare.xlsx'>Баркодын файл хадгалах</a>";
				// redirect(base_url().'download/Warehouse.xlsx');
		 		} else
		 			$data ['msg'] = "erroro to move to the folder";
		 		// then download
		 	} else {
		 		$data ['msg'] = "sorry file not created";
			 	$data ['link'] ="";
			 	$data ['msg'] = "Алдаа: No:WH_1- Агуулахад өгөгдлийг хадгалж чадсангүй!";
			 
		 	}
		 
		$data ['page'] = 'wh_spare\print_barcode';

      $this->load->view('index', $data);

    }

   

}      