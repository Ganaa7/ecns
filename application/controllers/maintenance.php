
<?php
if (! defined ( 'BASEPATH' )) 	exit ( 'No direct script access allowed' );

// Make new event models
// TODO: Make link models to calendar


class maintenance extends CNS_Controller {

    public $group;

    public $objdata;
    
    public $section_id;

    public $user_id;

    public function __construct() {

      parent::__construct ();

      $this->load->model('event_model');
      
      $this->load->model('event_detail_model');

      $this->load->model('eventtype_model');
      
      $this->load->model('location_model');

      $this->load->model('employee_model');

      $objdata = ( object ) array ();

      

        $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'maintenance', $this->role, 0, 1 ) );

        $this->config->set_item ( 'module_menu', 'Техник үйлчилгээний бүртгэл' );

        $this->config->set_item ( 'module_menu_link', '/maintenance' );

        $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

        $this->user_seccode = $this->session->userdata ( 'sec_code' );

        $this->user_id = $this->session->userdata ( 'employee_id' );

        $this->table = $this->user_model->set_event_table ( $this->role, $this->user_seccode );

        $this->section_id = $this->session->userdata ( "section_id" );

        $this->group = $this->event_model->get_group($this->session->userdata ('sec_code'), $this->session->userdata ('role'));
  

  }

  function index() {

    $this->data ['title'] = 'Техник үйлчилгээ';

    $this->data ['page'] = 'event\index';

    //ямар группын хэрэглэгчийг тодорхойлох
    $this->data['group'] =$this->event_model->get_group($this->session->userdata ('sec_code'), $this->session->userdata ('role'));

    $this->data['equipment']=$this->equipment_model->get_equipment();

    $location = $this->location_model->dropdown('name');

    if(in_array($this->section_id, array(1, 2, 3, 4, 10))){

      $this->data['employee'] = $this->employee_model->dropdown_by('employee_id', 'fullname', array('section_id' => $this->section_id));

      $this->db->select('employee_id, fullname');

      //NUBiA-н хэсгүүдийн      
      if($this->section_id < 5)

         $this->db->where_in('section_id', array($this->section_id, 10));
      
      else  
      
         $this->db->where_in('section_id', array($this->section_id, 1, 2, 3, 4));

      $Query = $this->db->get('view_employee'); 

      if ($Query->num_rows() > 0) {

        foreach ($Query->result_array() as $row) {
          
          $employee[$row['employee_id'] ] = $row['fullname'];

        }
      }

      $this->data['employee'] = $employee;

    }    
      
    else

      $this->data['employee'] = $this->employee_model->dropdown('fullname');

          
    $location[0] = 'Бүх байршил';

    ksort($location);

    $this->data ['location'] = $location;

    $this->data ['eventtype'] = $this->eventtype_model->dropdown('eventtype');

    $this->load->view ( 'index', $this->data );

  }

  function filter(){
      // filter function heree
        $data = array ();

        $id =$this->input->get_post ( 'id' );

        if($id){
          
          if ($this->group == 'ENG' || $this->group == 'ENG_CHIEF'){

            $data = $this->location_model->get_location_equipment($id, $this->section_id);

          }else{
            $data = $this->location_model->get_location_equipment($id);
          }
          
        }            
        else
          $data = $this->location_model->dropdown('location');

        header ( 'Content-type: application/json; charset=utf-8' );

        echo json_encode($data);

  }

  
  function add(){

      header ( 'Content-type: application/json; charset=utf-8' );

      $this->form_validation->set_message('less_than', '[ %s ] нэг утга сонгох шаардлагатай!');

      unset($this->event_model->validate[7]);

      unset($this->event_model->validate[8]);

      // print_r($this->event_model->validate);

      if($this->event_model->validate($this->event_model->validate)){

        if($this->event_model->check_duration($this->input->get_post('end'))){
          // echo $this->event_model->check_duration($this->input->get_post('end'));

          $data = $this->event_model->array_from_post(array('location_id', 'equipment_id', 'eventtype_id',  'is_interrupt', 'event', 'start', 'end'));

          $data['createdby_id'] = $this->user_id;

          $data['duration'] = $this->event_model->getDuration ( $data['start'], $data['end'] );

          $equipment = $this->equipment_model->get($data['equipment_id']);

          $data['section_id'] =$equipment->section_id;

          $data['createdDt']= date ( "Y-m-d H:i:s" );

          $data['allDay'] = $this->event_model->getAllDay ( $this->input->get_post('start'),  $this->input->get_post('end') );

          // print_r($data);

          if ($add = $this->event_model->insert ( $data )) {

            $return = array (
                'status' => 'success',
                'message' => "[" . $this->input->get_post( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "]  хугацаанд \"" . $this->input->get_post ( 'event' ) . "\" -г амжилттай хадгаллаа."
            );

            echo json_encode ( $return );

          } else {

            $return = array (
                'status' => 'failed',
                'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'
            );

            echo json_encode ( $return );
          }


        }else{

          $return = array (

            'status' => 'failed',

            'message' => 'Дуусах огноо эхлэх огнооноос их байх ёстой'

        );

          echo json_encode ( $return );

        }



      }else {

        $return = array (

            'status' => 'failed',

            'message' => validation_errors ( '', '<br>' )

        );

        echo json_encode ( $return );

      }
  }

  // edit functions here

  function edit(){

      header ( 'Content-type: application/json; charset=utf-8' );

      $id = $this->input->get_post('eventId');

      $event = $this->event_model->get($id);

      if($event->activedby_id && $event->approvedby_id ==null){

          unset($this->event_model->validate[7]);
          unset($this->event_model->validate[8]);
      }

      if($this->event_model->validate($this->event_model->validate)){

         $data = $this->event_model->array_from_post(array('location_id', 'equipment_id', 'eventtype_id', 'event', 'start', 'end', 'done'));

         $data['duration'] = $this->event_model->getDuration ( $data['start'], $data['end'] );

         $data['allDay'] = $this->event_model->getAllDay ( $this->input->get_post('start'),  $this->input->get_post('end') );

         // update here

         if ($this->event_model->update($id, $data)){

           $return = array (

               'status' => 'success',

               'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."

           );

           $this->set_doneby($id, "update");

           echo json_encode ( $return );

         }else{

           $return = array (

               'status' => 'failed',

               'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

           );

           echo json_encode ( $return );

         }

      }else{

         $return = array (
             'status' => 'failed',
             'message' => validation_errors ( '', '<br>' )
         );

         echo json_encode ( $return );
         // return the error message

      }
  }


  function delete(){
       // code...
      $id = $this->input->get_post ( 'eventId' );

   		if ($this->event_model->delete ( $id )) {

   			$return = array (

   					'status' => 'success',

   					'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай устгалаа!"

   			);

        //  TODO: Устгахыг засварлах устгах action хийсэн эрхийг барьж авах
        
        // if($event = $this->event_detail_model->get_by(array('event_id'=>$id)))

          //  $this->event_detail_model->delete_by(array('event_id' =>$id));

   		} else {

   			$return = array (

   					'status' => 'failed',

   					'message' => 'Зөвшөөрлийн өгөгдлийг хадгалах үед өгөгдлийн санд алдаа гарлаа! #21'

   			);

   		}

      $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $return ) );
  }

  function get_one(){

    header ( 'Content-type: application/json;' );

    $id = $this->input->get_post ( 'id' );

    $data = $this->event_model->with('section')->get($id);

    $employee = $this->employee_model->get(intval($data->createdby_id));

    // print_r($employee);

    $data->createdby = $employee->fullname;

    // Тухайн байршил дээрх төхөөрөмжүүдийг авах
    $data->equipments = $this->event_model->get_equipment_location($data->location_id);

    //roduces: WHERE username IN ('Frank', 'Todd', 'James')

    if($data->activedby_id){

        $activedby = $this->employee_model->get_by('employee_id',  $data->activedby_id);

        $data->activatedby = $activedby->fullname;

        $data->active = true;
    }
    else
      $data->active = false;


    if($data->approvedby_id){

        $approved = $this->employee_model->get_by('employee_id',  $data->approvedby_id);

        $data->approvedby = $activedby->fullname;
    }

    if($data->doneby_id){

      $data->isdone = true;

    }else $data->isdone = false;


    echo json_encode ( $data );

  }

  // Maintenance from Passbook

  function get_event_dtl(){

     header ( 'Content-type: application/json;' );

     $id = $this->input->get_post ( 'id' );

     $data = $this->event_model->with('section')->get($id);

     $employee = $this->employee_model->get(intval($data->createdby_id));

     $data->equipments = $this->event_model->get_equipment_location($data->location_id);

     $data->createdby = $employee->fullname;

     $data_ita =array();

      // Тухайн байршил дээрх төхөөрөмжүүдийг авах
     // $data->equipments = $this->event_model->get_equipment_location($data->location_id);

     if($data->activedby_id){

          $activedby = $this->employee_model->get_by('employee_id',  $data->activedby_id);

          $data->activatedby = $activedby->fullname;

          $data->active = true;
      }
      else
         $data->active = false;

      if($data->approvedby_id){

          $approved = $this->employee_model->get_by('employee_id',  $data->approvedby_id);

          $data->approvedby = $activedby->fullname;
      }

      // $event_detail_model

      $event_details = $this->event_detail_model->get_many_by(array('event_id'=>$id));

      foreach ($event_details as $row) {
          
        $data_ita [$row->employee_id] = $row->employee;

      }

      if($event_details){

         $data->isdone = true;

         $data->itas = $data_ita;

      }else $data->isdone = false;


      echo json_encode ( $data );


  }

  // Тухайн эвентийг авах
  function get_event() {

    $data = $this->event_model->get_event($this->group);

    $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );

  }


  function authorize() {

    // check validation
    $id = $this->input->get_post ( 'eventId' );

    unset($this->event_model->validate[7]);
    unset($this->event_model->validate[8]);

    if($this->event_model->validate($this->event_model->validate)){

      if ($this->role != 'CHIEF') {

        if ($this->event_model->authorize ( $id ) > 0) {

          $return = array (

              'status' => 'success',

              'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."
          );

        } elseif ($this->event_model->authorize ( $id ) == 0) {

          $return = array (

              'status' => 'failed',

              'message' => 'Аль хэдийн зөвшөөрсөн байна! Дахиж зөвшөөрөх хэрэггүй!'

          );

        } else {

          $return = array (
              'status' => 'failed',
              'message' => 'Зөвшөөрлийн өгөгдлийг хадгалах үед өгөгдлийн санд алдаа гарлаа! #21'
          );

        }

      } else {

        $return = array (
            'status' => 'failed',
            'message' => "Хэсгийн даргаас дээш албан тушаалтан зөвшөөрөх үйлдэл боломжтой."
        );

      }

    } else {

      $return = array (
          'status' => 'failed',
          'message' => validation_errors ( '', '<br>' )
      );

    }

      echo json_encode ( $return );

  } // end authorized


  function move() {

    $id = $this->input->get_post ( 'id' );

    $start = $this->input->get_post ( 'start' );

    $end = $this->input->get_post ( 'end' );

    $action = $this->input->get_post ( 'action' );

    // $this->setEvent ( $id );
    $event = $this->event_model->with('eventtype')->get($id);

    // print_r($event);

    $data ['start'] = $start;
    $data ['end'] = $end;

    $data ['duration'] = $this->event_model->getDuration ( $start, $end );

    $data ['allDay'] = $this->event_model->getAllDay ( $start, $end );

    // echo $this->db->last_query();
    //
    // print_r($data);

    if ($action == 'move')

      $msg = 'зөөлөө';

    else {

      $msg = 'сунгалаа';

    }

    switch ($this->group) {

      case 'ENG' :

        $return = array (
            'status' => 'failed',
            'message' => 'Энэ үйлдэл хийгдсэнгүй, Хэсгийн дарга болон дээш албан тушаалтанд боломжтой!'
        );

        echo json_encode ( $return );

        break;

      // Hesgiin darga зөөх үйлдэл зөвхөн тухайн хэсгийнх бол зөөнө
      case 'CHIEF' :

        if ($this->role == 'ENG_CHIEF') {

          $section_id =$event->section_id;

          if ($this->section_id == $section_id) { // өөрийн хэсгийн Event байна.

              // update хийж болно.
            if ($this->db->update('m_event', $data, "id = $id")) {

              $return = array (

                  'status' => 'success',

                  'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $event->eventtype->eventtype->eventtype . "\" -г хугацааг амжилттай $msg."

              );

              echo json_encode ( $return );

            } else {

              $return = array (

                  'status' => 'failed',

                  'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

              );

              echo json_encode ( $return );

            }

          } else {

            $return = array (

                'status' => 'failed',

                'message' => 'Энэ тус хэсгийн үйл ажиллагаа биш тул зөвшөөрөхгүй.'

            );

            echo json_encode ( $return );

          }

        } else { // CHIEF ENG, SUPERVISOR BVAL

          if ($this->db->update('m_event', $data, "id = $id")) {

            $return = array (

                'status' => 'success',

                'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $event->eventtype->eventtype . "\" -г хугацааг амжилттай $msg."
            );

            echo json_encode ( $return );

          } else {

            $return = array (
                'status' => 'failed',

                'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

            );

            echo json_encode ( $return );
          }
        }

        break;

      case 'ENG_CHIEF' :

        if ($this->role == 'CHIEF') {


          if ($this->section_id == $event->section_id) { // өөрийн хэсгийн Event байна.

              // update хийж болно.
            if ($this->db->update('m_event', $data, "id = $id")) {

              $return = array (

                  'status' => 'success',

                  'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $event->eventtype->eventtype . "\" -г хугацааг амжилттай $msg."

              );

              echo json_encode ( $return );

            } else {

              $return = array (

                  'status' => 'failed',

                  'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

              );

              echo json_encode ( $return );

            }

          } else {

            $return = array (

                'status' => 'failed',

                'message' => 'Энэ тус хэсгийн үйл ажиллагаа биш тул зөвшөөрөхгүй.'

            );

            echo json_encode ( $return );

          }

        } else { // CHIEF ENG, SUPERVISOR BVAL

          if ($this->db->update('m_event', $data, "id = $id")) {

            $return = array (

                'status' => 'success',

                'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $event->eventtype->eventtype . "\" -г хугацааг амжилттай $msg."

            );

            echo json_encode ( $return );

          } else {

            $return = array (

                'status' => 'failed',

                'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

            );

            echo json_encode ( $return );
          }

        }

        break;

      // Chief bval бүгдийг зөөнө
      default :

        $return = array (
            'status' => 'failed',
            'message' => "Энэ үйлдэл хийгдсэнгүй, Хэсгийн дарга болон дээш албан тушаалтанд боломжтой!"
        );

        echo json_encode ( $return );

        break;
    }

  }

  // Done hiih
  function done() {

        $data = $this->event_model->array_from_post(array('location_id', 'equipment_id', 'eventtype_id', 'event', 'start', 'end', 'done'));
        // $title = $this->input->get_post ( 'event' );    
        // $start = $this->input->get_post ( 'start' );    
        // $end = $this->input->get_post ( 'end' );   
        // $data ['location_id'] = $this->input->get_post ( 'location_id' );    
        // $data ['equipment_id'] = $this->input->get_post ( 'equipment_id' );    
        // $data ['eventtype_id'] = $this->input->get_post ( 'eventtype_id' );    
        // $data ['done'] = $this->input->get_post ( 'done' );

        // $data ['doneby_id'] = $this->user_id;

        if($this->event_model->validate($this->event_model->validate)){

           $data ['duration'] = $this->event_model->getDuration ( $data['start'], $data['end'] );

           $data ['allDay'] = $this->event_model->getAllDay ($data['start'], $data['end']);

           $eventId = $this->input->get_post ( 'eventId' );

           if ($this->db->update('m_event', $data, array('id' => $eventId))) {

              $this->set_doneby($eventId, 'create');

              $return = array (

                  'status' => 'success',

                  'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."
              );

            } else {

              $return = array (

                  'status' => 'failed',

                  'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

              );


            }

          }else {

            $return = array (

                'status' => 'failed',

                'message' => validation_errors ( '', '<br>' )

            );

        }

       $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $return ) );   

  }

  function report(){
     
     date_default_timezone_set ( ECNS_TIMEZONE );

     $startdate = $this->input->get_post ( 'startdate' );

     $enddate = $this->input->get_post ( 'enddate' );

     if ($enddate && $startdate) {

       $startdate = $startdate;

       $enddate = $enddate;

     } else {
       
       $startdate = date ( 'Y' ) . "-" . date ( 'm' ) . "-01";

       $enddate = date ( "Y-m-d" );

     }

    $data ['r_result'] =$this->event_model->get_event($this->group, $startdate, $enddate);

    $data ['startdate'] = $startdate;
    $data ['enddate'] = $enddate;

    $data ['page'] = 'event\report';      
    
    $data ['file_link'] = $this->export_excel($startdate, $enddate);

    $this->load->view ( 'index', $data );

  }


  function export_excel($start, $end){

      //Тухайн query-g avaad hevelh heregtei

      $result = $this->event_model->get_event($this->group, $start, $end);

      // echo $this->db->last_query();

      date_default_timezone_set ( 'Asia/Ulan_Bator' );
      
      $modified = $this->session->userdata ( 'fullname' );
      $this->load->helper ( 'PHPExcel' );
      
      $objPHPExcel = new PHPExcel ();
      // Set document properties
      $objPHPExcel->getProperties ()->setCreator ( "Ecns system" )->setLastModifiedBy ( $modified )->setTitle ( "ECNS Maintenance report" )->setSubject ( "Maintenance Report" )->setDescription ( "Ecns report document for Office 2007 XLSX, generated by ECNS." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Reportresult file" );
      
      $objPHPExcel->getActiveSheet ()->getStyle ( 'C1' )->getFont ()->setSize ( 14 );
      
      // $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Тайлант огноо:' . $this->startdate . "-" . $this->enddate )->setCellValue ( 'E2', 'Тайлан гаргасан:' . $modified );
      
      $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'ТОНОГ ТӨХӨӨРӨМЖИЙН ТЕХНИК ҮЙЛЧИЛГЭЭНИЙ ТАЙЛАН' );
      
      $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A2', 'Д/д' )->setCellValue ( 'B2', 'Төрөл')->setCellValue ( 'C2', 'Хэсэг' )->setCellValue ( 'D2', 'Үйлчилгээ тасалдсан эсэх' )->setCellValue ( 'E2', 'Эхэлсэн' )->setCellValue ( 'F2', 'Дууссан' )->setCellValue ( 'G2', 'Төхөөрөмж' )->setCellValue ( 'H2', 'Байршил' )->setCellValue ( 'I2', 'Техник үйлчилгээ' )->setCellValue ( 'J2', 'Гүйцэтгэл' )->setCellValue('K2', 'Бүтгэл нээсэн')->setCellValue('L2', 'Бүрт.Хаасан');
      // balance

      $j = 3; // rows
      $cnt = 1;
      
      foreach ( $result as $row ) {
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $j, $cnt );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $j, $row->eventtype );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $j, $row->section );
        // $objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, $row->is_interrupt);
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $j, ($row->is_interrupt ==0 ) ? 'Үгүй': 'Тийм' );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $j, $row->start );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $j, $row->end );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $j, $row->equipment );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $j, $row->location );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $j, $row->event );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $j, $row->done );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $j, $row->createdby );
        $objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $j, $row->doneby );
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
      
      $objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $eborder )->applyFromArray ( $styleArray );
      $objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $eborder )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
      
      $objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $eborder )->getAlignment ()->setWrapText ( true );
      $objPHPExcel->getActiveSheet ()->getStyle ( 'A2:K' . $eborder )->getFont ()->setSize ( 9 );
      
      $objPHPExcel->setActiveSheetIndex ( 0 );
      $objPHPExcel->getActiveSheet ()->setTitle ( 'Maintenance' );   
      
      // echo $this->db->last_query();
      $file_name = ''.date ( "Y-m-d_H_i_s" );

      $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );

      $objWriter->save ( str_replace ( __FILE__, 'E:\xampp\htdocs\ecns\download\maintenance_'.$file_name.'.xlsx', __FILE__ ) );

      
     $url = base_url ();
        
     $file_url = $url . 'download/maintenance_'.$file_name.'.xlsx';

     return $file_url;

  }

  function help() {

    $data ['title'] = 'Техник үйлчилгээ';

    $this->load->view ( 'help\event', $data );

  }

  private function set_doneby($event_id, $flag){

     $done_array = $this->input->get_post('doneby_id');

     $my_data = $sub_data = array();

     for ($i=0; $i < sizeof($done_array); $i++) { 

       $sub_data['employee_id'] = $done_array[$i];

       $employees = $this->employee_model->get($done_array[$i]);

       $sub_data['employee'] = $employees->fullname;
        
       $sub_data['event_id'] = $event_id;

       if($flag =='create') $sub_data['created_at']= date('Y-m-d H:i:s');

       else $sub_data['updated_at']= date('Y-m-d H:i:s');

       array_push($my_data, $sub_data);
        # code...
     }
    
     $this->db->insert_batch('m_event_dtl', $my_data); 

  }



}
