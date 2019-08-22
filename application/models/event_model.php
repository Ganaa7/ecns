<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class event_model extends MY_Model {

     public $_table = 'm_event';
    
     public $primary_key = 'id';

     public $belongs_to = array( 'section', 'eventtype');
    
     public $section_id;
     
     protected $before_get = array('order_method');  
     
     function order_method(){
    
        $this->db->order_by("start", "asc"); 
    
     }
   
    function __construct() {

        parent::__construct();

        $this->section_id = $this->session->userdata('section_id');

        $this->load->database ();
    }

    private $fields  = array('equipment_id', 'location_id', 'eventtype_id', 'is_interrupt', 'event', 'start', 'end', 'section_id', 'duration', 'allDay');

    public $validate = array(
        array( 'field' => 'equipment_id',
               'label' => 'Төхөөрөмж',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'location_id',
               'label' => 'Байршил',
               'rules' => 'required|is_natural_no_zero'),

        array( 'field' => 'eventtype_id',
               'label' => 'Ү/a төрөл',
               'rules' => 'required'),
        
        array( 'field' => 'event',
               'label' => 'Ажлын тодорхойлолт',
               'rules' => 'required|min_length[5]|max_length[700]'),

        array( 'field' => 'is_interrupt',
               'label' => 'Үйлчилгээ тасалдах эсэх',
               'rules' => 'required|less_than[2]'),

        array( 'field' => 'start',
               'label' => 'Эхлэх хугацаа',
               'rules' => 'required|min_length[16]|max_length[18]'),

        array( 'field' => 'end',
               'label' => 'Дуусах хугацаа',
               'rules' => 'required|min_length[16]|max_length[18]|callback_check_duration'),

        //event_done-g hiilee
        array( 'field' => 'done',
               'label' => 'Гүйцэтгэл',
               'rules' => 'required'),

         array( 'field' => 'doneby_id[]',
               'label' => 'Гүйцэтгэсэн ИТА',
               'rules' => 'required|is_natural_no_zero')
     

    );

    function array_from_post($fields){
        $data = array();
        foreach ($fields as $field) {
          if($field =='event'){
            $data['title'] =$this->input->post($field);
          }else
          $data[$field]=$this->input->post($field);
        }
        return $data;
    }

    // public $before_create = array( 'process(start)' );

    // protected function process($row)
    // {
    //
    //     print_r($row);
    //     // return $row;
    // }

    function check_duration($end) {

      $start = $this->input->get_post ( 'start' );

      // echo $this->getDuration ( $start, $end );

      if ($this->getDuration ( $start, $end ) > 0) {

        return TRUE;

      } else {

        $this->form_validation->set_message ( 'check_duration', ' %s хугацаа нь эхлэх хугацаанаас их байх шаардлагатай.' );

        return FALSE;

      }

    }

    function getDuration($start, $end) {

      $start_dt = strtotime ( $start ); // 2012-04-01

      $end_dt = strtotime ( $end ); // 2012-04-17

      return $end_dt - $start_dt;

    }

    function get_group($sec_code, $role) {

  		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'ELC' || $sec_code == 'SUR' || $sec_code =="CHI") {

  			switch ($role) {

  				case 'ENG' :

  					return 'ENG';

  					break;

  				case 'SUPENG' :

  					return 'ENG_CHIEF';

  					break;

  				case 'UNITCHIEF' :

  					return 'ENG';

  					break;

  				case 'TECH' :

  					return 'ENG';

  					break;

  				case 'CHIEF' :

  					return 'ENG_CHIEF';

  					break;
  			}

  		} else {

  			switch ($role) {

  				case 'CHIEF' :

  					return 'CHIEF';

  					break;

  				case 'SUPERVISOR' :

  					return 'CHIEF';

  					break;

  				case 'ADMIN' :

  					return 'CHIEF';

  					break;

  				default :

  					return 'USER';

  					break;
  			}
  		}
  	}

    function getAllDay($start, $end) {

      // Нэг өдөр 4-с дээш цагаар ажиллавал All-day болно

      $start_day =date_create( $start );

      $end_day = date_create( $end  );

      $diff = date_diff($start_day,$end_day);

       if (intval($diff->format('%a'))== 0) { // neg udur

        // if (strtotime ( $end ) - strtotime ( $start ) > 14400 && strtotime ( $end ) - strtotime ( $start ) < 86400) {

          return null;

        // }

      } else

        return 't';

    }

    function get_event($group, $start =false, $end =false){

      $this->db->select ('m_event.*, section.name as section, eventtype, equipment2.equipment, location.location, concat(if(isnull(location.name), "-", location.name),  "[" , if(isnull(equipment), "", equipment),  "] -",  if(isnull(eventtype),
                    "",  eventtype)) AS title, m_event.title AS event, a.fullname as createdby, b.fullname as doneby ', FALSE);
      $this->db->from($this->_table);
      $this->db->join('equipment2', "equipment2.equipment_id = $this->_table.equipment_id", 'left');
      $this->db->join('eventtype', "eventtype.eventtype_id = $this->_table.eventtype_id", 'left');
      $this->db->join('section', "section.section_id = $this->_table.section_id", 'left');
      $this->db->join('location', "location.location_id = $this->_table.location_id", 'left');
      $this->db->join('employee a', "a.employee_id = $this->_table.createdby_id", 'left');
      $this->db->join('employee b', "b.employee_id = $this->_table.doneby_id", 'left');
     
      if ($group == 'ENG' || $group == 'ENG_CHIEF') {

         $this->db->where ( 'm_event.section_id', $this->section_id );
         
         if($this->section_id < 5)

            $this->db->or_where ( 'm_event.section_id', 10 );

      }

       if ($start && $end) {

         $array = array('start >=' => $start, 'end <=' => $end);

         $this->db->where ($array);

      }

      $this->db->order_by ( 'id', 'asc' );

      $equery = $this->db->get ();

      return  $equery->result ();

    }

    function get_equipment_location($location_id){
       $sql = "SELECT A.* FROM equipment2 A ".
       " inner join  loc_equip B on A.equipment_id = B.equipment_id WHERE B.location_id = $location_id";

       $query = $this->db->query($sql);

       if ($query->num_rows () > 0) {
         foreach ( $query->result_array () as $row ) {
           $data [$row ['equipment_id']] = $row ['equipment'];
         }
       } else
         $data [0] = 'Ямар нэг утга олдсонгүй!';

       $query->free_result ();
       return $data;


    }


    function authorize($id) {

      // check activated? or note
      $event = $this->get($id);

      $activatedby_id = $event->activedby_id;

      $employee_id = $this->session->userdata('employee_id');

      if ($activatedby_id && $event->done) {

        // байвал энэ event-г approve хийх хэрэгтэй.

        $data ['approvedby_id'] = $employee_id;

        $equery = $this->db->query ( "SELECT value from settings  WHERE name in
          (SELECT section_id FROM m_event where id = $id) and settings = 'event'" );

         //echo $this->db->last_query();

        $row = $equery->row_array ();

        $data ['color'] = $row ['value'];

        $this->db->where ( 'id', $id );

        $this->db->update ( 'm_event', $data );

        return $this->db->affected_rows ();

      } elseif ($activatedby_id == null && $event->doneby_id == null) {

        $data ['activedby_id'] = $employee_id;

        $equery = $this->db->query ( "SELECT value from settings WHERE name in (SELECT section_id FROM
           m_event where id = $id) and settings = 'eauthor'" );

        $row = $equery->row_array ();

        $data ['color'] = $row ['value'];

        $this->db->where ( 'id', $id );

        $this->db->update ( 'm_event', $data );

        return $this->db->affected_rows ();

      } else {

        return 0;

      }

    }

    function get_columns() {

    $columns = array ();

    $cols = array (
        'eventtype',
        'section',
        'start',
        'end',
        'title',
        'event',
        'done',
        'equipment',
        'location',
        'createdby',
        'doneby'
    );

    $display_as = array (
        'eventtype' => 'Төрөл',
        'section' => 'Хэсэг',
        'start' => 'Эхэлсэн',
        'end' => 'Дууссан',
        'title' => 'Гарчиг',
        'event' => 'Техник үйлчилгээ',
        'done' => 'Гүйцэтгэл',
        'equipment' => 'Төхөөрөмж',
        'location' => 'Байршил',
        'createdby' => 'Үүсгэсэн',
        'doneby' => 'Дуусгасан'
    );

    foreach ( $cols as $col_num => $column ) {

      $columns [$col_num] = ( object ) array (

          'field_name' => $column,

          'display_as' => $display_as [$column]

      );
    }

    return $columns;
  }


  function get_list() {

     $this->db->select ( '*' );

     $this->db->from ( 'view_events' );

     if ($this->group == 'ENG') {

      $this->db->where ( 'section_id', $this->section_id );

     }

     // $this->db->order_by('section', 'asc');
     $this->db->order_by ( 'section asc, start desc, end desc' );

     return $this->db->get ()->result ();
   }


  function _export_to_excel($objdata) {

    $string_to_export = "";

    // print_r($objdata);
    // get columns
    foreach ( $objdata->columns as $column ) {

      $string_to_export .= $column->display_as . "\t";
    }

    $string_to_export .= "\n";

    // get lists
    foreach ( $objdata->list as $num_row => $row ) {

      foreach ( $objdata->columns as $column ) {

        $string_to_export .= $this->_trim_export_string ( $row->{$column->field_name} ) . "\t";
      }

      $string_to_export .= "\n";
    }

    // Convert to UTF-16LE and Prepend BOM
    $string_to_export = "\xFF\xFE" . mb_convert_encoding ( $string_to_export, 'UTF-16LE', 'UTF-8' );
    // var_dump($string_to_export);

    $filename = "export-" . date ( "Y-m-d_H:i:s" ) . ".xls";

    header ( 'Content-type: application/vnd.ms-excel;charset=UTF-16LE' );

    header ( 'Content-Disposition: attachment; filename=' . $filename );

    header ( "Cache-Control: no-cache" );

    echo $string_to_export;

    die ();
  }


  function _trim_export_string($value) {

    $value = str_replace ( array (
        "&nbsp;",
        "&amp;",
        "&gt;",
        "&lt;"
    ), array (
        " ",
        "&",
        ">",
        "<"
    ), $value );

    return strip_tags ( str_replace ( array (
        "\t",
        "\n",
        "\r"
    ), "", $value ) );
  }


  function get_dropdown_col($array){

    $this->db->where ( $array );

    $qry = $this->db->get ( $this->_table );

    if ($qry->num_rows () > 0) {

      foreach ( $qry->result_array () as $row ) {

        $data [$row ['id']] = $row ['title']. 'Гүйцэтгэл:'. $row['title'].date('Y-m-d', strtotime($row ['start']));

      }

    }

    $qry->free_result ();

    return $data;
  }

  function get_events($equipment_id, $location_id){

    $query = $this->db->query("Select * from m_event where equipment_id= $equipment_id and location_id =$location_id and passbook_id is null");

     if ($query->num_rows () > 0) {

          $data = $query->result();

      }else

          $data  = array();

    $query->free_result ();

    return $data;


  }




}
