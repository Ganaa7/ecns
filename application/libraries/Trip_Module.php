<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */

require_once('Core.php');


/**
 * used for trip controller
 */
class Trip_Load extends Core
{

  // trip
   protected $trip;

   protected $trip_dtl;

   protected $trip_route;

   protected $distance;

   protected $purpose;      
    
   protected $spot; 

  
  function __construct()
  {
      parent::__construct();

      // trip
      $this->Obj->load->model ( 'trip_model' );

      $this->trip = new trip_model();

      $this->Obj->load->model ( 'trip_dtl_model' );
      
      $this->trip_dtl = new trip_dtl_model();     

      $this->Obj->load->model ( 'trip_route_model' );

      $this->trip_route = new trip_route_model();     

      $this->Obj->load->model ( 'distance_model' );

      $this->distance = new distance_model();     

      $this->Obj->load->model ( 'spot_model' );
     
      $this->spot = new spot_model();

      $this->Obj->load->model ( 'purpose_model' );

      $this->purpose = new purpose_model (); 

  }

   protected function my_filter(){

        if(($this->search=='true')&&($this->filters != "")){

        /// echo $this->filter($this->filters);

        return $this->filter_($this->filters);

        }else
        return 'null';
    }


   protected function filter_($filters) {

         $db = get_instance()->db->conn_id;

         // echo "filtering";

         $filters = json_decode ( $filters );

         // var_dump($filters);

         $where = " where ";

         $whereArray = array ();

         $rules = $filters->rules;

         $groupOperation = $filters->groupOp;

         foreach ( $rules as $rule ) {

             $fieldName = $rule->field;

             $fieldData = mysqli_real_escape_string ($db, $rule->data );

             switch ($rule->op) {
                 case "eq" :
                         if($fieldName =='start_dt'){

                             $fieldOperation = " >= '" . $fieldData . "' ";

                         }elseif($fieldName =='end_dt'){

                            $fieldOperation = " <= '" . $fieldData . "' ";
                             
                         }else
                            
                            $fieldOperation = " = '" . $fieldData . "'";

                         break;
                 case "ne" :
                         $fieldOperation = " != '" . $fieldData . "'";
                         break;
                 case "lt" :
                         $fieldOperation = " < '" . $fieldData . "'";
                         break;
                 case "gt" :
                         $fieldOperation = " > '" . $fieldData . "'";
                         break;
                 case "le" :
                         $fieldOperation = " <= '" . $fieldData . "'";
                         break;
                 case "ge" :
                         $fieldOperation = " >= '" . $fieldData . "'";
                         break;
                 case "nu" :
                         $fieldOperation = " = ''";
                         break;
                 case "nn" :
                         $fieldOperation = " != ''";
                         break;
                 case "in" :
                         $fieldOperation = " IN (" . $fieldData . ")";
                         break;
                 case "ni" :
                         $fieldOperation = " NOT IN '" . $fieldData . "'";
                         break;
                 case "bw" :
                         $fieldOperation = " LIKE '" . $fieldData . "%'";
                         break;
                 case "bn" :
                         $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                         break;
                 case "ew" :
                         $fieldOperation = " LIKE '%" . $fieldData . "'";
                         break;
                 case "en" :
                         $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                         break;
                 case "cn" :
                        if($fieldName =='start_dt')

                             $fieldOperation = " >= '" . $fieldData . "' ";

                        elseif($fieldName =='end_dt')

                            $fieldOperation = " <= '" . $fieldData . "' ";
                             
                        else
                                $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;

                 case "nc" :
                         $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                         break;
                 default :
                         $fieldOperation = "";
                         break;
             }
             if ($fieldOperation != "")
                     $whereArray [] = $fieldName . $fieldOperation;
         }
         if (count ( $whereArray ) > 0) {
                 $this->where .= join ( " " . $groupOperation . " ", $whereArray );
         } else {
                 $this->where = "";
         }
         return $this->where;
     }
}


class Trip_Layout extends Trip_Load{

    private $echo_die = false;

    protected $view_as_string;

    public function __construct()
    {
        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/trip/view/');        
    }

       
    // here is all database and logics     
    protected function index_form(){
       // here is only grid in index form                   
       
       $data['action']=$this->trip->get_action();

       return $this->theme_view ( $data);        
    }
    
    protected function get_layout(){
       if($this->echo_die){                        
          die ();
       }else{
         return $this->views_as_string;
         die ();
       }        
    }
    
    protected function set_echo_die(){
       $this->echo_die = true;
    }
    
    //show grid as json here
    protected function grid(){

        @ob_end_clean ();
        
        $this->init_grid();
        
        $this->set_echo_die();        
                
        // herev filter hiisen bval where should be set
        $this->my_filter();

        // echo $this->where;
                
        // нийт тоо 
        $this->set_count($this->trip);                
        
//       нийт хуудсыг тоог олно
        $this->set_total_page();
//                
//        // hedenhuudas baigaag toolno
        $this->set_page();
//        
//        //ehlen start-g togtoono
         $this->set_start();
//
//        //get final result as json
        $json=$rows= array();  
        $json['page']=$this->page;
        $json['total']=$this->total_pages;
        $json['records']=$this->count;      
        
        // $json['sql']=$this->trip->last_query();	    
        $rows = $this->trip->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);
        
        $json['sql2']=$this->trip->last_query();       

        $json['rows']=$rows;
        echo json_encode($json);                
    }

    // : subgrid
    function sub_grid(){
        $json =array();
        $trip_id = $this->input->get_post('id');
        //echo $trip_id;
        $trip = $this->trip->get($trip_id);
        $rows = $this->trip_route->get_many_by(array('trip_id'=>$trip_id));

        if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
            header ( "Content-type: application/xhtml+xml;charset=utf-8" );
        } else {
            header ( "Content-type: text/xml;charset=utf-8" );
        }
        echo "<?xml version='1.0' encoding='utf-8'?>";
        echo "<rows>";
        // be sure to put text data in CDATA
        foreach ( $rows as $row ) {
            echo "<row>";
            echo "<cell>" . $row->route_id . "</cell>";
            echo "<cell>" . $row->num . "</cell>";
            echo "<cell><![CDATA[" . $row->from_route . "]]></cell>";
            echo "<cell>" . $row->to_route . "</cell>";
            echo "<cell>" . $row->distance ."км"."</cell>";            
                        
            if($row->out_dt) echo "<cell>" . date("Y-m-d H:i", strtotime($row->out_dt)) . "</cell>";
            else echo "<cell>" .$row->out_dt . "</cell>";            
            if($row->out_dt&&$row->est_dt==null){
              //$date = new DateTime($row->out_dt);      
              if($trip->transport=='Машин')        
                 $est_hours = round($row->distance/60, 0, PHP_ROUND_HALF_UP);
              else 
                $est_hours = round($row->distance/850, 0, PHP_ROUND_HALF_UP);
              //$date->add("+".$est_hours." hours");
               //echo "<cell>" . $est_hours. "</cell>";  
               echo "<cell>" . date("Y-m-d H:i:s", strtotime($row->out_dt . " +".$est_hours." hours")). "</cell>";  
              //date("Y-m-d H:i", (strtotime($row->out_dt)+s
            }else echo "<cell>" . $row->est_dt . "</cell>";  
            echo "<cell>" . $row->is_come ."</cell>";            
            echo "<cell>" . $row->infoby     ."</cell>";            
            echo "<cell>" . $row->comment ."</cell>";            
            echo "</row>";
        }
        echo "</rows>";        
    
    }
      
}

class Trip_Crud extends Trip_Layout{

   public function __construct(){

      parent::__construct();

   }

   private function get_detail($id){

      $dtl = $sub_data = array();
        $employee = $this->input->get_post('employee_id');            
        for ($i=0; $i <count($employee) ; $i++) { 
            $sub_data ['employee_id'] = $employee[$i];
            $ita=$this->employee->get($employee[$i]);
            $sub_data ['employee'] = $ita->fullname;
            $sub_data ['trip_id'] = $id;
            $sub_data ['section_id'] = $ita->section_id;
            
            $section = $this->section->get($ita->section_id);
            $sub_data ['section'] = $section->name;

            # code...
            array_push($dtl, $sub_data);
        }
        return $dtl;
   } 

   // tuhain route id-gaar trip-g uusgeh funciton   
   private function set_route($id){
        $route_dtl = $sub_data = array();
        $routes = $this->input->get_post('routes');            
        $route = json_decode ( $routes );
        // print_r($route);
        $flag = 1;
        $trip = $this->trip->get($id);        
        $trip_date = $trip->start_dt;           
        for ($i=0; $i <count($route) ; $i++) { 

            if($flag ==1){
                $from  =$route [$i]->route_id;
                $flag = 0;
                $first_hour = $trip_date." 12:00";
                $sub_data['out_dt'] = $first_hour;     

                $def_to  =$route [$i+1]->route_id;                    
                 $distance = $this->distance->get_distance($from,$def_to);                
                 $est_hours = round($distance/60, 0, PHP_ROUND_HALF_UP);              
                 $sub_data['est_dt'] =  date("Y-m-d H:i:s", strtotime($first_hour . " +".$est_hours." hours"));        

            }else{         
                $sub_data['num'] = $i;                                 
                $to=$route [$i]->route_id;                                
                $sub_data['trip_id'] = $id;
                $from_spot = $this->location->get($from);
                //print_r($from_spot);
                $sub_data['from_route'] = $from_spot->name;
                $sub_data['from_id'] = $from;        
                $to_spot = $this->location->get($to);
                $sub_data['to_route'] = $to_spot->name;
                $sub_data['to_id'] = $to;   
                
                $distance = $this->distance->get_distance($from,$to);                
                $sub_data['distance']=$distance;
                
                $sub_data['is_come'] = 'N';                   
                // echo $this->trip->last_query();
                array_push($route_dtl, $sub_data);
                $sub_data['est_dt']=null;
                $sub_data['out_dt']= null;
                
                $from  =$to;
            }
            
            
        }
        //print_r($route_dtl);
        return $route_dtl;
   }

   private function check_route(){
      $route_map = array();
      $routes = $this->input->get_post('routes');            
      $route = json_decode ( $routes );
      $first_route = $route [0]->route_id;
      //echo count($route);
       for ($i=1; $i <count($route) ; $i++) {  
          array_push($route_map, $route [$i]->route_id);            
       }
       if(in_array($first_route, $route_map)) return true;
       else return false;
       //print_r($route_map);
   }

   protected function add(){ 
      //check гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!    
        
      if($this->trip->validate($this->trip->validate)){
          if($this->check_route()==false){
             $return = array (
                'status' => 'failed',
                'message' => 'Эхэлсэн цэгийг хамгийн сүүлд дахин сонгох ёстой! Маршрут буруу байна!' 
             );
          }else{
             
              $data = $this->trip->array_from_post(array('trip_no', 'purpose', 'transport', 'start_dt', 'end_dt'));

              $this->Obj->db->trans_begin();    
              
              $id=$this->trip->insert($data);

              $this->trip->insert_route($this->set_route($id));

              $this->trip->insert_batch($this->get_detail($id));

              if ($this->Obj->db->trans_status() === FALSE){
                 $this->Obj->db->trans_rollback();
                 $return = array (
                      'status' => 'failed',
                        'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                   );  
              }else{
                  $this->Obj->db->trans_commit();
                  $return = array (
                        'status' => 'success',
                        'message' => 'Амжилттай хадгаллаа' 
                  );
              }                       
          }
          
        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' ) 
           );
        }            

        echo json_encode($return);

    }

    
    protected function create_form(){
        $data['section'] = $this->trip->dropdown('purpose');
        return  $this->theme_view ( 'create.php', $data );
    }

    // filter function heree    
    protected function section_select(){
        $data = array ();
        $id =$this->input->get_post ( 'id' );  
        if($id)
          $data = $this->employee->in_drop_down('fullname', 'section_id', $id);
        else
          $data = $this->employee->dropdown('fullname');

        echo json_encode($data);
    }

    protected function delete(){     
        $id = $this->input->get_post ( 'trip_id' );
        $trip = $this->trip->get($id);
        if($this->trip->delete($id)){
          $return = array (
                'status' => 'success',
                'message' => $trip->trip_no.' дугаартай томилолтыг амжилттай устгалаа' 
            );
        }else
            $return = array (
                    'status' => 'failed',
                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                );

        echo json_encode($return);
    }

   protected function get(){    

        $id = $this->input->get_post ( 'id' );

        $trip = $this->trip->get($id);

        $trip_dtl =$this->trip->get_dtl($id);

        $trip_section =$this->trip_dtl->get_dtl($id);

        $trip_route =$this->trip_route->_dropdown($id);        
        //get trip dtl

        $trip->dtl = $trip_dtl;

        $trip->section = $trip_section;
        
        $trip->route = $trip_route;

         $return = array (
                'json' => $trip                
            );
       

        echo json_encode($return);
    }

   protected function get_route(){     
        $route_id = $this->input->get_post ( 'id' );        
        $route  = $this->trip_route->get(intval($route_id));    
        $parent_id = $this->input->get_post ( 'parent_id' );       
        //employee-s avah yostoi end 
        $trip_dtl = $this->trip_dtl->get_many_by('trip_id', $parent_id);

        //check if before trip_num is not come then not call dialog        
        if(intval($route->num)!==1){
           $bef_route  = $this->trip_route->get_by(array('num'=>($route->num)-1, 'trip_id'=>$parent_id));
           if($bef_route->is_come!=='Y'){
              $status = 'false';
              $trip_route = null;
           }else{
              $status = 'true';
              $trip_route = $this->trip_route->get_by(array('route_id'=>$route_id));
           }
        }else if($route->out_dt){            
           $trip_route = $route;
           $status = 'true';           
        }else{
            $trip_route = $route;
            $status = 'false';           
        }             

        $return = array (            
             'json' => $trip_route,
             'dtl'=>$trip_dtl,
             'status'=>$status
           );   
       

        echo json_encode($return);
    }  

    protected function get_basic(){     
        $route_id = $this->input->get_post ( 'id' );        
    
        //check if before trip_num is not come then not call dialog        
        $trip_route = $this->trip_route->get($route_id);
        $parent_id = $this->input->get_post ( 'parent_id' );  

        if($trip_route)
           $return = array (
             'json' => $trip_route,
             'status'=>'true'
           );   
       else $return = array (
             'json' => $trip_route,
             'status'=>'false'
           );   
       
        echo json_encode($return);
    }

    // update route
    protected function update_route($flag = null){     
        $route_id = $this->input->get_post ( 'route_id' ); 
        $parent_id = $this->input->get_post ( 'parent_id' ); 

        $trip = $this->trip->get($parent_id);

        $out_dt = $this->input->get_post ( 'out_dt' );        
        $est_dt = $this->input->get_post ( 'est_dt' );

        $infoby_id = $this->input->get_post ( 'infoby_id' );        

        if($infoby_id&&$infoby_id!==0){
           $infoby = $this->employee->get($infoby_id);
           $data['infoby_id'] = $infoby_id;
           $data['infoby'] = $infoby->fullname;    
        
            if(isset($est_dt)&&$est_dt){    
                $data['est_dt']= $est_dt;
                $data['out_dt']= $out_dt;
                $data['is_come']= 'Y';
            }else{
                $out_dt = $this->input->get_post ( 'out_dt' );
                $distance = $this->input->get_post ( 'distance' );
                if($trip->transport=='Машин')        
                   $time = round($distance/60, 0, PHP_ROUND_HALF_UP);
                else $time = round($distance/800, 0, PHP_ROUND_HALF_UP);

                $data['out_dt']=$out_dt;
                $data['est_dt'] = date("Y-m-d H:i:s", strtotime($out_dt . " +".$time." hours"));    
            } 

        if($this->input->get_post('comment'))
           $data['comment'] = $this->input->get_post ( 'comment' );
        
        $route_id = $this->trip_route->update_by(array('route_id'=>$route_id), $data);

        if($route_id)
         $msg = array (
              'status' => 'success',
              'message' => 'Өөрчлөлтийг амжилттай хадгаллаа!',
              'sql'=>$this->trip->last_query()
           );
        else $msg = array (
              'status' => 'failded',
              'message' => 'Утгуудыг хадгалахад алдаа гарлаа!' 
           );

        }else{
            $msg = array (
              'status' => 'failded',
              'message' => 'Мэдээ өгсөн хүнийг сонгоно уу!' 
           );            
        }
        echo json_encode($msg);
        
    }   


    // update comment
    protected function comment(){     
        $route_id = $this->input->get_post ( 'route_id' );                        
        
        $route = $this->trip_route->get($route_id);

        //herev comment-g out_dt, est_dt tei ued ugvul nothing        
        if($route->out_dt==null&&$route->est_dt==null&&$route->is_come=='N'){
            // end comment-diig hiij ugnu!!!
            $data['is_come']='Y';
            $data['infoby_id']=1000; //Мэдээ өгөөгүй!            
            $data['infoby']='Мэдээ өгөөгүй'; //Мэдээ өгөөгүй!            
        }
    
        if($route->comment)
           //$data['comment']= $this->input->get_post ( 'comment' ); 
            $data['comment']= $route->comment."\r\n".$this->input->get_post ( 'comment' ); 
        else{               
              $data['comment']=$this->input->get_post ( 'comment' ); 
        }
                
        $route_id = $this->trip_route->update_by(array('route_id'=>$route_id), $data);

        if($route_id)
         $msg = array (
              'status' => 'success',
              'message' => 'Тэмдэглэлийг амжилттай хадгаллаа!' 
           );
        else $msg = array (
              'status' => 'failded',
              'message' => 'Утгуудыг хадгалахад алдаа гарлаа!' 
           );
                         
        echo json_encode($msg);
        
    }

    protected function edit_route($id){     
        // 1. tuhain id-r set_route_g avna
        $new_route  = $this->set_route($id);
         // print_r($new_route);

        $old_route  = $this->trip_route->get_many_by(array('trip_id'=>$id));

        // print_r($old_route);
        foreach ($new_route as $key => $value) {
            // echo "key".$key."val".$value['from_id'];
            $route = $this->trip_route->get_by(array('trip_id'=>$id, 'from_id'=>$value['from_id'], 'to_id'=>$value['to_id']));

            if($route){

                $new_route[$key]['out_dt']=$route->out_dt;

                $new_route[$key]['est_dt']=$route->est_dt;

                $new_route[$key]['is_come']=$route->is_come;

                $new_route[$key]['comment']=$route->comment;

                //$new_route[$key]['infoby_id']=$route->infoby_id;
                if($trip_ita =$this->trip_dtl->get_by(array('trip_id'=>$id, 'employee_id'=>$route->infoby_id))){

                    $new_route[$key]['infoby_id']=$trip_ita->employee_id;

                    $new_route[$key]['infoby']=$trip_ita->employee;

                }else{

                    $new_route[$key]['infoby_id']=null;

                    $new_route[$key]['infoby']=null;

                }  

            }else if($key==0){   

                $new_route[$key]['comment']=null;

                $new_route[$key]['infoby_id']=null;
                
                $new_route[$key]['infoby']=null;
            }else{
                $new_route[$key]['out_dt']=null;
                $new_route[$key]['est_dt']=null;
                $new_route[$key]['is_come']='N';
                $new_route[$key]['comment']=null;
                $new_route[$key]['infoby_id']=null;
                $new_route[$key]['infoby']=null;
            }

        }
        // echo "<br>lastroute";    
        return $new_route;  

    }

    protected function edit(){     
        $id = $this->input->get_post ( 'trip_id' );
        //get olld trip id 
        $trip = $this->trip->get($id);

        // check if old is new same 
        $data = $this->trip->array_from_post(array('trip_no', 'purpose', 'transport', 'start_dt', 'end_dt'));      
        //$rules = $this->trip->validate;        

        if($trip->trip_no == $data['trip_no']){
            $this->trip->validate[0]['rules'] = 'required';            
        }
        
        if($this->check_route()==false){
           $msg = array (
              'status' => 'failed',
              'message' => 'Эхэлсэн цэгийг хамгийн сүүлд дахин сонгох ёстой! Маршрут буруу байна!' 
           );
        }else{
            // validate
            if($this->trip->validate($data)){   

                $this->Obj->db->trans_begin();    

                $this->trip->update($id, $data);
                                
                $this->trip_dtl->delete_by('trip_id',$id);

                $this->trip->insert_batch($this->get_detail($id));

                //trip_route-g update hiih heregtei baina!
                $new_route = $this->edit_route($id);

                $this->trip_route->delete_by('trip_id',$id);   

                $this->trip->insert_route($new_route);                    
                
                if ($this->Obj->db->trans_status() === FALSE){

                   $this->Obj->db->trans_rollback();

                   $return = array (
                        'status' => 'failed',
                          'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                     );  
                }else{
                    $this->Obj->db->trans_commit();
                       $msg = array (
                        'status' => 'success',
                        'message' => '№'.$trip->trip_no.' дугаартай томилолтыг амжилттай засварлалаа'
                        
                    );    
                }                   
            }else{
                $msg = array (
                    'status' => 'failed',
                    'message' => validation_errors ( '', '<br>' ), 
                    'rules' =>$data
                );
            }  
        }          

        echo json_encode($msg);
    }     
}

class Trip_Module extends Trip_Crud{

    private $state = null;

    protected $is_ajax_request =FALSE;
    
    
    // grid data 
    function __construct() {

        parent::__construct();

        $this->set_status_url();                

        // $this->init_library();
    }
    
    //status-g url-s avah!
    private function set_status_url() {
        $CI = &get_instance ();
        $CI->load->helper ( 'url' );
        if($CI->input->is_ajax_request()){            
            $this->is_ajax_request = TRUE;            
        }
        $this->state = $CI->uri->segment ( 3 );                        
        if (! $this->state)
           $this->state = 'open'; 
    }
    
    protected function get_status(){
        return $this->state;
    }

    function run() {            
           // check state add, edit, delete    
            $data = array ();
            $data ['view'] = true;    

            if($this->is_ajax_request){
                $this->set_echo_die();    
                header ( 'Content-type: application/json; charset=utf-8' );


                switch ($this->get_status()) {
                    case 'add':                                                
                        $this->add();
                        break;

                    case 'grid':                        
                        // call action here
                        $this->grid ();  
                    break;

                    // subgrid
                    case 'sub_grid':                        
                        $this->sub_grid();
                        // call action here                        
                    break;

                    case 'filter':
                        $this->section_select();
                        break;

                   case 'delete':
                        $this->delete();
                        break;

                   case 'get':
                        $this->get();
                        break;        

                   //ajax request get dtl 
                   case 'get_route':
                        $this->get_route();
                        break;  

                  //ajax request get dtl 
                   case 'get_route_as':
                        $this->get_basic();
                        break;

                   case 'edit':
                         $this->edit();                    
                        break;

                    // save go
                   case 'update_route':
                         $this->update_route();                    
                        break;

                    case 'update_route_by':
                         $this->update_route(1);                    
                        break;
                                        

                    case 'comment':
                         $this->comment();                    
                        break;

                    default:
                        # code...
                        break;
                }
            }else{
                switch ($this->get_status ()) {               
                // create action
                    case 'create':
                        # code...
                        //  echo "create here";
                     $this->create_form();
                        break;

                       
                    case 'delete' :
                            $return = $this->delete ();
                            $data ['json'] = json_encode ( $return );
                            $data ['view'] = false;
                            return ( object ) $data;
                            break;


                        
                    default : // index page loaded
                            //$data ['page'] = 'trip\index';                            
                            $this->index_form();                                                    
                            break;
                   }  

            }    
            return $this->get_layout();
    }
}