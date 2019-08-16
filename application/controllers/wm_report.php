<?php
/*
 * This controller extends Report of warehouse
 * created : 2013/02/25
 * by Ganaa
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wm_report extends CNS_Controller{

   public $role;

   public $startdate;

   public $enddate;
   
   public function __construct() {        
      parent::__construct();

      $this->load->model('main_model');

      $this->load->model('user_model');

      $this->load->model('wm_model');

      $this->load->model('wm_main');

      $this->setDate();
        
        if($this->session->userdata('role')){
           $this->role =$this->session->userdata('role');         
           $this->config->set_item('user_menu', $this->user_model->display_menu('warehouse', $this->role, 0, 1));        
//           $this->session->unset_userdata('home');
           $this->config->set_item('module_menu', 'Сэлбэг хангамжийн бүртгэл');
           $this->config->set_item('module_menu_link', '/ecns/warehouse');
        }   
        
   }    
   
   private function setDate(){
      date_default_timezone_set(ECNS_TIMEZONE);
      $enddate=$this->input->get_post('enddate');
      $startdate=$this->input->get_post('startdate');
      if($enddate&&$startdate){
          $this->startdate=$startdate;
          $this->enddate=$enddate;
      }else{         
         $this->startdate = date('Y')."-".date('m')."-01";
         $this->enddate=date("Y-m-d");
      }
   }
    
   function income(){
      $this->main_model->access_check();
      //income table-s дуудаж харуулна
      
      $this->main_model->check_byrole('warehouse', $this->role);
      $this->db->where("income_date >", $this->startdate);         
      $this->db->where("income_date <=", $this->enddate);          
      
      $data['startdate']=$this->startdate; 
      $data['enddate']=$this->enddate;
      $data['r_result']=$this->db->get('wm_view_repincome')->result();
      $sql ="SELECT sum(qty) as total FROM wm_view_repincome WHERE income_date >'$this->startdate' and income_date <= '$this->enddate'";
      $row=$this->db->query($sql)->row();
      $data['total']=$row->total;
      $last_qry=$this->db->last_query();
      $data['last_qry']=$last_qry;              
      $data['title']="Орлогийн тайлан";
      $data['page']='warehouse\report\income';
      $data['file_link']=$this->exp_income();       
      $this->load->view('index', $data);
    }
    
   function expense(){        
      $this->startdate=$this->input->get_post('startdate');
      $this->enddate=$this->input->get_post('enddate');       
      $this->main_model->access_check();
      $this->main_model->check_byrole('warehouse', $this->role);
      if($this->startdate&&$this->enddate){
          $this->db->where("expense_date >", $this->startdate);
          $this->db->where("expense_date <=", $this->enddate);          
      }else{
         $this->enddate=date("Y-m-d");
         $this->startdate = date('Y')."-".date('m')."-01";
         $this->db->where("expense_date >", $this->startdate);           
         $this->db->where("expense_date <=", $this->enddate);
      }
      $data['startdate']=$this->startdate; 
      $data['enddate']=$this->enddate;
       
      $data['r_result']=$this->db->get('wm_view_expReport')->result();
      $sql ="SELECT sum(qty) as total FROM wm_view_expReport WHERE expense_date >'$this->startdate' and expense_date <= '$this->enddate'";
      $row=$this->db->query($sql)->row();
      $data['total']=$row->total;      
      $data['last_qry']=$this->db->last_query();
      $data['title']="Зарлагийн тайлан";
      $data['page']='warehouse\report\expense';
      $data['file_link']=$this->expExpense('wm_view_expReport');
      $this->load->view('index', $data);
    }
    

    
    
    // balance here
    function balance(){
       $this->main_model->access_check();       
       $this->main_model->check_byrole('warehouse', $this->role);
       $section_id =$this->input->get_post('section_id');
       if(isset($section_id)){
          $data['section']=$this->main_model->get_row('name', array('section_id'=>$section_id), 'section');
       }
       $data['section_id']=$section_id;
       $sparetype_id =$this->input->get_post('sparetype_id');
       $data['sparetype_id']=$sparetype_id;
       $equipment_id=$this->input->get_post('equipment_id');
       $data['equipment_id']= $equipment_id;
       $todate=$this->input->get_post('todate');
       $data['todate']=$todate;       
                           
       if(isset($section_id) && $section_id!=0){
          $this->db->where('section_id', $section_id);
       }
       if($equipment_id){
          $this->db->where('equipment_id', $equipment_id);          
       }
       if($sparetype_id){
          $this->db->where('sparetype_id', $sparetype_id);                    
       }
       
       if($todate){
           $array = array('date <='=> $todate);
          $this->db->where($array);                    
       }
       $query=$this->db->get("wm_report_general");       
       $data['r_result']=$query->result();
    
       // Дугаар, сэлбэгийн нэр, Парт дугаар, Хэмжих нэгж, Эхний үлдэгдэл, Орлого, Зарлага, Эцсийн үлдэгдэл
       // beginbalance, income, expense, warehouse
       // $data['r_result']=$this->db->query($sqls)->result();
       date_default_timezone_set('Asia/Ulan_Bator');
       $data['date']=date('Y/m/d');
       $data['last_query']=$this->db->last_query();
       $data['title']="Үлдэгдлийн тайлан";
       $data['page']='warehouse\report\balance';
       $data['file_link']=$this->export_xls('wm_report_general', $section_id, $equipment_id, $sparetype_id);
       
       $this->load->view('index', $data);
    }
    
    function exp_income(){
       $this->startdate=$this->input->get_post('startdate');
       $this->enddate=$this->input->get_post('enddate');  
       $this->db->where("income_date >", $this->startdate);
       $this->db->where("income_date <=", $this->enddate);                 
       $result=$this->db->get('wm_view_repincome')->result();
       date_default_timezone_set('Asia/Ulan_Bator');
       
       $modified = $this->session->userdata('fullname');
       $this->load->helper('PHPExcel');
       
       $objPHPExcel = new PHPExcel();
       // Set document properties        
       $objPHPExcel->getProperties()->setCreator("Ecns system")
                   ->setLastModifiedBy($modified)
                   ->setTitle("ECNS Warehouse income report")
                   ->setSubject("Warehouse Report")
                   ->setDescription("Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes.")
                   ->setKeywords("office 2007 openxml php")
                   ->setCategory("Reportresult file");
       
       $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(14);
       
       $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Тайлант огноо:'.$this->startdate."-".$this->enddate)
                            ->setCellValue('E2', 'Тайлан гаргасан:'.$modified);
       
       $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ОРЛОГЫН ТАЙЛАН');   
          
       $objPHPExcel->setActiveSheetIndex(0)                            
                    ->setCellValue('A4', 'Д/д')
                    ->setCellValue('B4', 'Орлогын №')
                    ->setCellValue('C4', 'Орлого огноо')                
                    ->setCellValue('D4', 'Нэр төрөл')
                    ->setCellValue('E4', 'Хэм, нэгж')                			
                    ->setCellValue('F4', 'Тоо, ширхэг')
                    ->setCellValue('G4', 'Нийлүүлэгч');             
            //balance
        $j = 5; // rows
        $cnt = 1;
           
        foreach ($result as $row){          
           $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $row->income_no);
           $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $row->income_date);                        
           $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $row->spare);
           $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $row->short_code);
           $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $row->qty);                        
           $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $row->supplier); 
           $j++; $cnt++;
        }
          $styleArray = array(
                     'borders' => array(
                             'allborders' => array(
                                     'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                     'color' => array('argb' => '000000'),
                             ),
                     ),
          );
          $eborder=$j-1;

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->applyFromArray($styleArray);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setWrapText(true);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getFont()->setSize(9);

          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->setTitle('Income'); 
          $url =$this->excel_footer($objPHPExcel);
          return $url;
         
         
    }
    
    function expExpense($table){
       $this->startdate=$this->input->get_post('startdate');
       $this->enddate=$this->input->get_post('enddate');  
       $this->db->where("expense_date >", $this->startdate);
       $this->db->where("expense_date <=", $this->enddate);          
          
       $result=$this->db->get($table)->result();
       date_default_timezone_set('Asia/Ulan_Bator');
       $date=date('Y/m/d');
       $modified = $this->session->userdata('fullname');
       $this->load->helper('PHPExcel');
       
       $objPHPExcel = new PHPExcel();
       // Set document properties        
       $objPHPExcel->getProperties()->setCreator("Ecns system")
                   ->setLastModifiedBy($modified)
                   ->setTitle("ECNS Warehouse income report")
                   ->setSubject("Warehouse Report")
                   ->setDescription("Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes.")
                   ->setKeywords("office 2007 openxml php")
                   ->setCategory("Reportresult file");
       
       $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(14);
       
       $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Тайлант огноо:'.$this->startdate."-".$this->enddate)
                            ->setCellValue('E2', 'Тайлан гаргасан:'.$modified);
       
        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ЗАРЛАГИЙН ТАЙЛАН');   
          
          $objPHPExcel->setActiveSheetIndex(0)                            
                    ->setCellValue('A4', ' №')
                    ->setCellValue('A4', 'Баримт №')
                    ->setCellValue('B4', 'Зарлага огноо')                
                    ->setCellValue('C4', 'Сэлбэг')                    
                    ->setCellValue('D4', 'Хэм.нэгж')                			
                    ->setCellValue('E4', 'Тоо ширхэг')
                    ->setCellValue('F4', 'Сэлбэг')
                    ->setCellValue('G4', 'Захиалагч')             
                    ->setCellValue('H4', 'Хэсэг')             
                    ->setCellValue('I4', 'Зориулалт');             
            //balance
          $j = 5; // rows
          $cnt = 1;
          foreach ($result as $row){
             $objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $cnt);
             $objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $row->expense_no);
             $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $row->expense_date);                        
             $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $row->spare);
             $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $row->short_code);
             $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $row->qty);                        
             $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $row->receiveby);             
             $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $row->section);             
             $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $row->intend);             
             $j++;
          }

          $styleArray = array(
                     'borders' => array(
                             'allborders' => array(
                                     'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                     'color' => array('argb' => '000000'),
                             ),
                     ),
          );
          $eborder=$j-1;

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->applyFromArray($styleArray);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setWrapText(true);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getFont()->setSize(9);

          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->setTitle('Expense'); 
          $url =$this->excel_footer($objPHPExcel);
          return $url;
    }
            
    function export_xls($table, $section_id=null, $equipment_id=null, $sparetype_id=null){
       $this->load->helper('PHPExcel');
       /** Include PHPExcel */
       // Create new PHPExcel object  
       date_default_timezone_set('Asia/Ulan_Bator');
       $date=date('Y/m/d');
       $modified = $this->session->userdata('fullname');
       
       if($section_id)
          $section =$this->main_model->get_row('name', array('section_id'=>$section_id),'section');
       else
          $section ='Бүх хэсэг';
       
       if($section_id){
          $this->db->where('section_id', $section_id);          
       }       
       if($equipment_id){
          $this->db->where('equipment_id', $equipment_id);          
       }       
       if($sparetype_id){
          $this->db->where('sparetype_id', $sparetype_id);          
       }
       
       $results=$this->db->get($table)->result();
       
       $objPHPExcel = new PHPExcel();
       // Set document properties        
       $objPHPExcel->getProperties()->setCreator("Ecns system")
                   ->setLastModifiedBy($modified)
                   ->setTitle("ECNS Shiftlog report")
                   ->setSubject("Shiftlog Report")
                   ->setDescription("Ecns report document for Office 2007 XLSX, generated using ECNS PHP classes.")
                   ->setKeywords("office 2007 openxml php")
                   ->setCategory("Reportresult file");
       
       $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(14);
       
       $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Тайлант огноо:'.$date)
                            ->setCellValue('E2', 'Тайлан гаргасан:'.$modified)
                            ->setCellValue('A3', 'Хэсэг:'.$section);
       
       if($table=='wm_report_general'){
          $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('C1', 'АГУУЛАХЫН ҮЛДЭГДЛИЙН ТАЙЛАН');   
          
          $objPHPExcel->setActiveSheetIndex(0)                            
                    ->setCellValue('A4', 'Дугаар №')
                    ->setCellValue('B4', 'Төхөөрөмж')                
                    ->setCellValue('C4', 'Сэлбэгийн нэр')
                    ->setCellValue('D4', 'Парт дугаар')                			
                    ->setCellValue('E4', 'Хэмжих нэгж')
                    ->setCellValue('F4', 'Эхний үлдэгдэл')
                    ->setCellValue('G4', 'Орлого')
                    ->setCellValue('H4', 'Зарлага')
                    ->setCellValue('I4', 'Үлдэгдэл');
            //balance
          $i = 5; // rows
          $cnt = 1;
          foreach ($results as $row){
             $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $cnt);
             $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->equipment);
             $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row->spare);                        
             $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->part_number);
             $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row->short_code);
             $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->bqty);                        
             $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->inqty);
             $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->exqty);
             $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->eqty);
             $i++;
          }
          $styleArray = array(
                     'borders' => array(
                             'allborders' => array(
                                     'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                     'color' => array('argb' => '000000'),
                             ),
                     ),
          );
          $eborder=$i-1;
          $objPHPExcel->getActiveSheet()->getStyle('A4:I'.$eborder)->applyFromArray($styleArray);
          $objPHPExcel->getActiveSheet()->getStyle('A4:I'.$eborder)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

          $objPHPExcel->getActiveSheet()->getStyle('A4:I'.$eborder)->getAlignment()->setWrapText(true);
          $objPHPExcel->getActiveSheet()->getStyle('A4:I'.$eborder)->getFont()->setSize(9);

          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->setTitle('Balance');  
       } 
       else{
          // income
          
          $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'БАЙГУУЛАМЖИЙН СЭЛБЭГ ХАНГАЛТЫН ОРЛОГЫН ТАЙЛАН');   
          
          $objPHPExcel->setActiveSheetIndex(0)                            
                    ->setCellValue('A4', 'Орлогын №')
                    ->setCellValue('B4', 'Орлогодсон огноо')                
                    ->setCellValue('C4', 'Сэлбэг')
                    ->setCellValue('D4', 'Тоо хэмжээ')                			
                    ->setCellValue('E4', 'Нийлүүлэгч')
                    ->setCellValue('F4', 'Санхүүч')
                    ->setCellValue('G4', 'Нярав');             
            //balance
          $j = 5; // rows
          $cnt = 1;
          foreach ($results as $row){
             $objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $cnt);
             $objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $row->income_no);
             $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $row->incomed_date);                        
             $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $row->spare);
             $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $row->qty);
             $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $row->supplier);                        
             $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $row->accountant);             
             $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $row->storeman);             
             $j++;
          }

          $styleArray = array(
                     'borders' => array(
                             'allborders' => array(
                                     'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                     'color' => array('argb' => '000000'),
                             ),
                     ),
          );
          $eborder=$j-1;

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->applyFromArray($styleArray);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getAlignment()->setWrapText(true);
          $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$eborder)->getFont()->setSize(9);

          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->setTitle('Income'); 
          
       }
       
       
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
       $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

       $source = 'E:\xampp\htdocs\ecns\application\controllers\wm_report.xlsx';            
       $destination = 'E:\xampp\htdocs\ecns\download\wm_report.xlsx';            
            
       if(copy($source, $destination)){
          unlink($source);
          //return $destination;                    
          $copied=1;
       }else $copied=0;

       $url=base_url();
       $file_url=$url.'/download/wm_report.xlsx';
       return $file_url;
    }
    
    function printOrder($order_id){
      //get order_id and select * from orders where order_id =1
      $sql = "SELECT *, day(order_date) as oDay, month(order_date) as oMonth, year(order_date) as oYear, 
                    day(registed_date) rDay,  month(registed_date) rMonth, year(registed_date) rYear,
                    day(prove_date) pDay,  month(prove_date) pMonth, year(prove_date) pYear
                FROM wm_view_order WHERE order_id = $order_id";
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
      $data['page']='warehouse\order\orderPage';
      
      $this->load->view('warehouse\order\printOrder',$data); 
   }
   
    function excel_footer($objPHPExcel){
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
       $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

       $source = 'E:\xampp\htdocs\ecns\application\controllers\wm_report.xlsx';            
       $destination = 'E:\xampp\htdocs\ecns\download\report_income.xlsx';            
            
       if(copy($source, $destination)){
          unlink($source);
          //return $destination;                    
          $copied=1;
       }else $copied=0;

       $url=base_url();
       $file_url=$url.'/download/report_income.xlsx';
       return $file_url;
   }
}
?>