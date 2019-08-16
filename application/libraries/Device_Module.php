<?php
/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */

require_once('Core.php');

class Device_Layout extends Core{

    protected $device;
    
    protected $country;
    
    protected $manufacture;
    
    protected $location;
    
    protected $loc_equip;

    protected $certificate;
    
    protected $material;

    protected $parameter;
    
    protected $repair;
    
    protected $flog;
    
    protected $tree;
    
    protected $event;
    
    protected $passbook;

    protected $passbook_detail;
    
    protected $event_type;
    
    protected $event_detail;

    private $echo_die = false;

    protected $repair_spare;

    protected $repair_employee;

    protected $spare;

    // protected $view_as_string;

    // private $theme_location = 'assets/apps/equipment/theme/';

    public function __construct()
    {
        parent::__construct();

        $this->Obj->load->model ( 'event_model' );

        $this->event = new event_model (); 

        $this->Obj->load->model ( 'f_log_model' );

        $this->flog = new f_log_model (); 

        $this->Obj->load->model ( 'repair_model' );

        $this->repair = new repair_model (); 

        $this->Obj->load->model ( 'material_model' );

        $this->material = new material_model ();

        $this->Obj->load->model ( 'parameter_model' );

        $this->parameter = new parameter_model ();

        $this->Obj->load->model ( 'device_model' );

        $this->device = new Device_model ();

        $this->Obj->load->model ( 'Country_model' );

        $this->country = new Country_model ();
        
        $this->Obj->load->model ( 'Manufacture_model' );

        $this->manufacture = new Manufacture_model ();       

        $this->Obj->load->model ( 'location_model' );

        $this->location = new location_model ();

        $this->Obj->load->model ( 'loc_equip_model' );

        $this->loc_equip = new loc_equip_model ();       

        $this->Obj->load->model ( 'certificate_model' );

        $this->certificate = new certificate_model ();      

        $this->Obj->load->model ( 'eventtype_model' );

        $this->eventtype = new eventtype_model (); 

        $this->Obj->load->model ( 'passbook_model' );

        $this->passbook = new passbook_model ();

        $this->Obj->load->model ( 'passbook_detail_model' );

        $this->passbook_detail = new passbook_detail_model ();

        $this->Obj->load->model ( 'tree_model' );

        $this->tree = new tree_model ();   

        $this->Obj->load->model ( 'event_detail_model' );  $this->event_detail = new event_detail_model ();

        $this->Obj->load->model ( 'repair_spare_model' );  
        $this->repair_spare = new repair_spare_model ();     

        $this->Obj->load->model ( 'repair_employee_model' );  
        $this->repair_employee = new repair_employee_model ();       

        $this->Obj->load->model ( 'wh_spare_model' );  
        $this->spare = new wh_spare_model ();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/device/theme/');
        
    }
    // here is all database and logics

    protected function index_form(){

       // here is only grid in index form
       $data['action'] =$this->device->get_action();
       
       return $this->theme_view ( $data );
    }

    protected function create_form(){

       parent::set_file('create.php');

       $data['certificate'] = array(0 =>'Байршил, төхөөрөмжийг сонгоход харагдана');

       if($this->session->userdata('section_id')<=5){

            $data['section']= $this->section->dropdown_by
            ('section_id', 'name', array('section_id'=> $this->session->userdata('section_id')) );    

            $data['equipment']=$this->equipment->dropdown_by('equipment_id', 'equipment', 
                array('section_id' =>$this->session->userdata('section_id')));
        
        }else{

             $data['section']= $this->section->dropdown_by('section_id', 'name', 
                    array(
                        'section_id < ' =>5
                    )
                ) ; 
              $data['equipment']=$this->equipment->dropdown('equipment');
                
        }
       
        $data['country']=$this->country->dropdown('country');

        $data['manufacture']=$this->manufacture->dropdown('manufacture');

        $data['location']=$this->location->dropdown_by('location_id', 'location');

        $data['action'] =$this->device->get_action();

       // here is only grid in index form       
       return $this->theme_view ( $data );
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
        $this->check_filter();

        // section setby user section
        $this->check_section();

        // нийт тоо
        $this->set_count($this->device);

        //нийт хуудсыг тоог олно
        $this->set_total_page();

        // hedenhuudas baigaag toolno
        $this->set_page();

        //ehlen start-g togtoono
        $this->set_start();

        //get final result as json
        $json=$rows= array();

        $json['page']=$this->page;

        $json['total']=$this->total_pages;

        $json['records']=$this->count;

        $json['rows'] = $this->device->get_query($this->where, $this->sidx, $this->sord , $this->start, $this->limit);

        $json['sql']=$this->device->last_query();  // echo $this->device->last_query();

        echo json_encode($json);
    }

    // filter function heree    
    protected function filterby(){

        $data = array ();

        $id =$this->input->get_post ( 'id' );  
        
        $target =$this->input->get_post ( 'target' );  

        if($id){
           
           if($target=='location'){
             
              $data = $this->equipment->order_by(array('equipment' => 'desc'))->dropdown_by('equipment_id', 'equipment', array('section_id'=>$id));

           }else{

              $section_id =$this->input->get_post ( 'section' );  

               $data = $this->equipment->order_by(array('equipment' => 'desc'))->dropdown_by('equipment_id', 'equipment', 
                array('section_id'=>intval($section_id)));

              // $data = $this->loc_equip->dropdown_ext(array('loc_equip.location_id'=>$id, 'loc_equip.section_id'=>$section_id), TRUE); 
           }
        }

      //   echo $this->Obj->db->last_query();

        echo json_encode($data);
    }

    function get_passport($section_id){

       $max_number = $this->device->get_passport_no($section_id);

       //get max number of device
       switch ($section_id) {
          
          case 1:
            # code...
            $passport_no = 'ТБА04.1'.sprintf('%03d',$max_number);

            break;
          
          case 2:
            # code...
            $passport_no = 'ТБА06.1'.sprintf('%03d',$max_number);

            break;
          
          case 3:
            # code...
            $passport_no = 'ТБА07.1'.sprintf('%03d',$max_number);

            break;
          
          case 4:
            # code...
            $passport_no = 'ТБА05.1'.sprintf('%03d',$max_number);

            break;        
       }

        return  $passport_no;
    }


    function gen_pass_no($section_id =null){

       if(!$section_id) $section_id =$this->input->get_post ( 'section' );  

       $max_number = $this->device->get_passport_no($section_id);

       //get max number of device
       switch ($section_id) {
          
          case 1:
            # code...
            $passport_no = 'ТБА04.1'.sprintf('%03d',$max_number);

            break;
          
          case 2:
            # code...
            $passport_no = 'ТБА06.1'.sprintf('%03d',$max_number);

            break;
          
          case 3:
            # code...
            $passport_no = 'ТБА07.1'.sprintf('%03d',$max_number);

            break;
          
          case 4:
            # code...
            $passport_no = 'ТБА05.1'.sprintf('%03d',$max_number);

            break;        
       }

      echo json_encode($passport_no);
    }
 
}

class Device_Crud extends Device_Layout{

     public function __construct()
    {
        parent::__construct();
        
    }

    protected function add(){
        
        //Сэлбэгийн дугаарыг бодох: 1. hamgiin bagiig avaad 

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        unset($this->device->validate[0]);

        // $this->unset_validate(4, 17);

        if($this->device->validate($this->device->validate)){

            $data = $this->device->array_from_post(array('location_id', 'certificate_id', 'section_id', 'equipment_id', 'device', 'mark', 'part_number', 'year_init', 'intend', 'power', 'country_id', 'manufacture_id','serial_number', 'factory_date', 'order_no', 'order_date', 'invoice_no', 'repair_time', 'maintenance_time', 'lifetime'));

            $data['created_at'] =date('Y-m-d H:i:s');

            $employee = $this->employee->with('position')->get($this->session->userdata('employee_id'));

            $data['createdby'] = $employee->fullname;

            $data['createdby_position'] = $employee->position->name;

            if ($id = $this->device->insert($data, TRUE)){

               $return = array (
                      'status' => 'success',
                      'message' => 'Амжилттай хадгаллаа'
               );

            }else{
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }           
                     

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }
        

        echo json_encode($return);
    }

    protected function delete(){

       $id = $this->input->get_post ( 'id' );

       $device = $this->device->get($id);
       // get location_id , equipment_id -r tuhain log-s medeellig avch shalgana.

      //  $log = $this->flog->get_by( array(
      //                         'location_id' => $device->location_id, 
      //                         'equipment_id' => $device->equipment_id)
      //                     ); 

       //event bgaa esegii shalgana
       $event = $this->event->get_by( 
                            array(
                              'device_id' => $device->id,                               
                            ));

         $return = array (
            'status' => 'failed',
            'message' => ' Устгах үйлдэл түр хориголосон тул боломжгүй!'
         );

      //  if($log){

      //     $return = array (
      //       'status' => 'failed',
      //       'message' => ' Төхөөрөмж дээр "'.$log->log_num.' " дугаартай гэмтэл  "'.$log->created_dt.'"  нээгдсэн тул устгах боломжгүй!'
      //     );

      //  } else
      //   if($event){

      //     $return = array (
      //       'status' => 'failed',
      //       'message' => ' Төхөөрөмж дээр техник үйлчилгээ "'.$event->start.'" хугацаанд хийгдэхээр төлөвлөгдсөн тул устгах боломжгүй!'
      //     );

      //  } else if($this->device->delete($id)){

      //     $passbook_id = $this->passbook->delete_by(array('device_id' =>$id));

      //     $this->Obj->db->delete('passbook_detail', array('passbook_id'=>$passbook_id));                           

      //     $return = array (
      //        'status' => 'success',
      //        'message' => '"'.$device->device.'" төхөөрөмжийг амжилттай устгалаа'
      //     );

      //  } else

      //     $return = array (
      //          'status' => 'failed',
      //           'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'.$this->Obj->db->_error_message()
      //     );

       echo json_encode($return);

    }

    protected function get(){

       $id = $this->input->get_post ( 'id' );

       $device = $this->device->with('equipment')->with('certificate')->with('location')->get($id);

       // print_r($device);

       //get pass_number
       $device->pass_no = $this->get_passport($device->equipment->section_id);

       echo json_encode($device);
    }

    protected function edit(){

        $id = $this->input->get_post ( 'equipment_id' );

        // check if old is new same
        $data = $this->device->array_from_post(array('equipment_id', 'section_id', 'sector_id', 'equipment', 'code', 'intend', 'intend', 'year_init', 'sp_id'));
        
        // validate library at work

        $intend = $data ['intend'];
         
        unset ( $data ['intend'] );

        if($this->device->validate($data)){

            //check sp_id is null
            if(!$data['sp_id']){

                 $data['sp_id']=$this->device->get_spare_id($data['section_id'], $data['sector_id']);

            }

            if ($this->device->update($id, $data)){               
                
                $return = array (
                    'status' => 'success',
                    'message' =>'"'.$data['equipment'].'" төхөөрөмжийн мэдээллийг амжилттай засварлалаа'

                );
               
            }else{
                
                $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                 );
            }

            
           
        }else{
            
            $return = array (
                'status' => 'failed',
                'message' => validation_errors ( '', '<br>' ),
                'rules' =>$data
            );
        }
        
        echo json_encode($return);
    }

     // filter function heree    
    protected function get_cert(){

        $data = array ();

        $equipment_id = $this->input->get_post ( 'equipment_id' );  
       
        $location_id = $this->input->get_post ( 'location_id' );

        $data = array();  
        
        // $certificate[0] = 'Нэг утгыг сонго';

        $certificate = $this->certificate->get_many_by(array('equipment_id'=>$equipment_id, 'location_id'=>$location_id) );

        $data['certificate'] = $certificate;
                

        $data['equipment']=$this->equipment->get($equipment_id);
        
        echo json_encode($data);
    }

    // Add material here
    protected function add_material(){
        
        //Сэлбэгийн дугаарыг бодох: 1. hamgiin bagiig avaad 

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->material->validate($this->device->validate)){

            $data = $this->material->array_from_post(array('device_id', 'materials', 'qty', 'part_number', 'passbook_id'));

            if ($id = $this->material->insert($data, TRUE)){
              $return = array (
                    'status' => 'success',
                    'message' => 'Амжилттай хадгаллаа'
              );
            }else{
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }           

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }
        echo json_encode($return);
    }   


    private function set_doneby($event_id, $flag){

       $done_array = $this->input->get_post('doneby_id');

       $my_data = $sub_data = array();

       for ($i=0; $i < sizeof($done_array); $i++) { 

         $sub_data['employee_id'] = $done_array[$i];

         if($done_array[$i]==0){

             $sub_data['employee'] = 'Орон нутгийн ИТА';

         }else{
          
             $employees = $this->employee->get($done_array[$i]);

             $sub_data['employee'] = $employees->fullname;
              
         }

         $sub_data['event_id'] = $event_id;

         if($flag =='create') $sub_data['created_at']= date('Y-m-d H:i:s');

         else $sub_data['updated_at']= date('Y-m-d H:i:s');

         array_push($my_data, $sub_data);
          # code...
      }
      
      $this->Obj->db->insert_batch('m_event_dtl', $my_data); 

    }  

     // Maintenance
    protected function add_maintenance(){
        
        //Сэлбэгийн дугаарыг бодох: 1. hamgiin bagiig avaad 

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        unset($this->event->validate[0]);
        unset($this->event->validate[1]);
        unset($this->event->validate[9]);

        if($this->event->validate($this->event->validate)){

            $data = $this->event->array_from_post(array('equipment_id', 'location_id', 'eventtype_id', 'event', 'is_interrupt', 'start', 'end', 'done'));

            $passbook_all = $this->input->get_post('passbook_all');

            if(empty($passbook_all)) $data['passbook_id'] = $this->input->get_post('passbook_id');

            // else $data['passbook_id'] = $this->input->get_post('passbook_id');

            $data['createdby_id'] = $this->session->userdata('employee_id');
            
            $data['device_id'] = $this->input->get_post('device_id');
            
            if ($id = $this->event->insert($data, TRUE)){
               
               $return = array (
                    'status' => 'success',
                    'message' => 'Амжилттай хадгаллаа'
               );

               // end event detail-n buyu done employee-s iig hiine!!
               $this->set_doneby($id, 'create');

            }else{
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }   
            
        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }
        echo json_encode($return);
    }  

    protected function edit_maintenance(){
        
        //Сэлбэгийн дугаарыг бодох: 1. hamgiin bagiig avaad 
        $id = $this->input->get_post ( 'id' );

        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        unset($this->event->validate[0]);
        unset($this->event->validate[1]);
        unset($this->event->validate[8]);

        if($this->event->validate($this->event->validate)){

            $data = $this->event->array_from_post(array('equipment_id', 'location_id', 'eventtype_id', 'event', 'is_interrupt', 'start', 'end', 'done'));

            $passbook_all = $this->input->get_post('passbook_all');

            if(!empty($passbook_all)) $data['passbook_id'] = null;
            
            else $data['passbook_id'] = $this->input->get_post('passbook_id');

            $data['createdby_id'] = $this->session->userdata('employee_id');

             if ($this->Obj->db->update('m_event', $data, "id = $id")){
               
               $return = array (
                    'status' => 'success',
                    'message' => 'Техник үйлчилгээг амжилттай хадгаллаа'
               );

               // delete all id from event_dtl

               $this->Obj->db->delete('m_event_dtl', array('event_id' => $id)); 

               $this->set_doneby($id, 'none');

            }else{
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }   
            
        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        echo json_encode($return);

    }  

    protected function del_maintenance(){
       
       // TODO: event_delete-g soft_delete-р update хийх
       if($delete_id =$this->event->delete($this->input->get_post ( 'id' )))

           $return = array (
                  'status' => 'success',
                  'message' => 'Техник үйлчилгээг устгах боломжгүй байна!'
           );
       
       else
            
            $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );

       echo json_encode($return);
    }

    protected function get_material(){

       $id = $this->input->get_post ( 'id' );
       
       $material = $this->material->with('device')->get($id);

       echo json_encode($material);
    }  

    protected function edit_material(){

       $id = $this->input->get_post ( 'id' );

       if($this->material->validate($this->material->validate)){

          $data = $this->material->array_from_post(array('device_id', 'materials', 'qty', 'part_number'));

          if ($id = $this->Obj->db->update('materials', $data, "id = $id")){
             
             $return = array (
                  'status' => 'success',
                  'message' => 'Төхөөрөмжийн үзүүлэлтийг амжилттай хадгаллаа'
             );

          }else{

            echo $this->Obj->db->last_query();
           
              $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                );

          } 

       }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
       }

       echo json_encode($return);
    }  

    protected function del_material(){

       if($delete_id = $this->material->delete($this->input->get_post ( 'id' )))
       
           $return = array (
                  'status' => 'success',
                  'message' => 'Төхөөрөмжийн иж болгогчийг амжилттай устгалаа'
           );
       
       else
            
            $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );

       echo json_encode($return);
    }

    // Add parameters here
    protected function add_parameter(){
        
        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->parameter->validate($this->parameter->validate)){

            $data = $this->parameter->array_from_post(array('device_id', 'parameters', 'measure', 'value', 'passbook_id'));

            if ($id = $this->parameter->insert($data, TRUE)){
               
               $return = array (
                    'status' => 'success',
                    'message' => 'Техникийн үзүүлэлтүүдийг амжилттай хадгаллаа'
               );

            }else{

            //   echo $this->Obj->db->last_query();
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }  

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        echo json_encode($return);
    }

    protected function get_parameter(){

       $id = $this->input->get_post ( 'id' );
       
       $parameter = $this->parameter->with('device')->get($id);

       echo json_encode($parameter);
    }    

    protected function edit_parameter(){

       $id = $this->input->get_post ( 'id' );

       if($this->parameter->validate($this->parameter->validate)){

            $data = $this->parameter->array_from_post(array('device_id', 'parameters', 'measure', 'value'));

            if ($id = $this->Obj->db->update('parameters', $data, "id = $id")){
               
               $return = array (
                    'status' => 'success',
                    'message' => 'Төхөөрөмжийн үзүүлэлтийг амжилттай хадгаллаа'
               );

            }else{

              echo $this->Obj->db->last_query();
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            } 

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

       echo json_encode($return);
    }

    protected function del_parameter(){

       if($delete_id = $this->parameter->delete($this->input->get_post ( 'id' )))
       
           $return = array (
                  'status' => 'success',
                  'message' => 'Төхөөрөмжийн үзүүлэлтийг амжилттай устгалаа'
           );
       
       else
            
            $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );

       echo json_encode($return);
    }

    // Add parameters here
    protected function add_repair(){
        
        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        unset($this->repair->validate[1]);

        //insert repair
        // 2. repair_detail
        // 3. repair_employee

        $no_spare = $this->input->get_post('no_spare');

        if($no_spare){

          unset($this->repair->validate[4]);

          unset($this->repair->validate[5]);

          unset($this->repair->validate[6]);
        }

        if($this->repair->validate($this->repair->validate)){

            $data = $this->repair->array_from_post(array('device_id', 'passbook_id', 'repair_date', 'reason', 'repair', 'no_spare'));
            // $repair_man = $this->employee->get($data['repairedby_id']);

            $duration = $this->input->get_post('duration');

            if(strpos($duration, ':') !== false){

              $stop_pos = strpos($duration, ":");

              $hours = substr ($duration,  0, $stop_pos);
              
              $secs = substr ($duration,  $stop_pos+1, strlen($duration));

              $data['duration'] =($hours) * 3600 + ($secs) * 60; 

              // print_r($data);

              if ($id = $this->repair->insert($data)){
                 
                 $return = array (
                      'status' => 'success',
                      'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
                 );

                 //insert repair_detail
                 if(empty($no_spare)){

                     $spare_array = $this->input->get_post('spare_id');
                   
                     $qty_array = $this->input->get_post('qty');
                     
                     $part_number_array = $this->input->get_post('part_number');
                     
                     $my_data = $sub_data = array();

                     for ($i=0; $i < sizeof($spare_array); $i++) { 

                        $sub_data['repair_id'] = $id;

                        $sub_data['spare_id'] = $spare_array[$i];

                        $spare = $this->spare->get($spare_array[$i]);

                        $sub_data['spare'] = $spare->spare;
                          
                        $sub_data['qty'] = $qty_array[$i];
                        
                        $sub_data['part_number'] = $part_number_array[$i];

                        array_push($my_data, $sub_data);

                      } 

                      $this->Obj->db->insert_batch('repair_spare', $my_data);    

                 }

                 $emp_data = $emp_array = array();

                 $employee_array = $this->input->get_post('repairedby_id');

                 for ($i=0; $i < sizeof($employee_array); $i++) { 

                    $emp_array['repair_id'] = $id;

                    $emp_array['employee_id'] = $employee_array[$i];

                    $employee = $this->employee->get($employee_array[$i]);

                    $emp_array['employee'] = $employee->fullname;
                     
                    array_push($emp_data, $emp_array);

                 }
                  
                 $this->Obj->db->insert_batch('repair_employee', $emp_data); 
                 
              }else{

                  $return = array (
                       'status' => 'failed',
                         'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                    );

              }  

            }else{

                $return = array (
                     'status' => 'failed',
                     'message' => 'Цагийн формат тохирохгүй байна! Цаг:Минут байх ёстой!'
                );

            }

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        echo json_encode($return);
    }

    protected function get_repair(){

       $id = $this->input->get_post ( 'id' );
       
       $repair = $this->repair->with('device')->get($id);

       $repair->spare = $this->repair_spare->get_many_by(array('repair_id'=>$id));

       $repair->new_duration = $this->str_to_time($repair->duration);

       $repair_employees = $this->repair_employee->get_many_by(array('repair_id'=>$id));

       $data_ita =array();

       foreach ($repair_employees as $row) {
         
         $data_ita [$row->employee_id] = $row->employee;

       }

      if($repair_employees){

         $repair->isdone = true;

         $repair->itas = $data_ita;

      }else $repair->isdone = false;

         echo json_encode($repair);

    } 

    // Add parameters here
    protected function update_passport(){
        
        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        $device_id = $this->input->get_post('device_id');

        unset($this->device->validate[5]);
        unset($this->device->validate[0]);
        unset($this->device->validate[1]);
        unset($this->device->validate[2]);
        unset($this->device->validate[3]);

        if($this->device->validate($this->device->validate)){

            $data = $this->device->array_from_post(array('location_id', 'section_id', 'equipment_id', 'certificate_id', 'device', 'mark', 'part_number', 'year_init', 'intend', 'power', 'country_id', 'manufacture_id','serial_number', 'factory_date', 'order_no', 'order_date', 'invoice_no', 'repair_time', 'maintenance_time', 'lifetime'));

            $data['updated_at'] =date('Y-m-d H:i:s');

            $employee = $this->employee->with('position')->get($this->session->userdata('employee_id'));

            $data['updatedby'] = $employee->fullname;

            $data['createdby_position'] = $employee->position->name;

            $device = $this->device->get($device_id);

            // herev device-deer passbook uussen baigaa bol utgiig uurchluh bolomjgui
            
            if ($id = $this->Obj->db->update('device', $data, "id = $device_id")){
               
               // TODO: passbook_update хийнэ:
               
               if($passbook_id = $this->Obj->db->update('passbook', array('equipment_id'=>$data['equipment_id'],
                           'updated_at'=>date('Y-m-d H:i:s')),
                           array('device_id' =>$device_id))){

                  $passbooks =$this->passbook->get_many_by(array('device_id' =>$device_id));

                  foreach ($passbooks as $passbook) {

                     $this->Obj->db->update('m_event', 
                              array( 'equipment_id' =>$data['equipment_id'],
                                    'location_id' =>$data['location_id'] ),

                           array('device_id'=>$device_id, 'passbook_id'=>$passbook->id)
                           );

                  }

               }
               
               $return = array (
                  'passbook_id'=>$passbook->id,
                  'status' => 'success',
                  'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
               );

            }else{

            // echo $this->Obj->db->last_query();
                  $return = array (
                     'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );
            } 

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        echo json_encode($return);
    }

    protected function del_repair(){

       $repair_id = $this->input->get_post ( 'id' );

       $repair = $this->repair->get($repair_id);

       $this->Obj->db->update('f_log', array('passbook_id'=>null), array('id'=>$repair->log_id));
      // $this->Obj->db->update('repair', $data, "id = $id") 
       
       if($delete_id = $this->repair->delete($repair_id)){

           $this->repair_spare->delete_by(array('repair_id' =>$repair_id));
           
           $this->repair_employee->delete_by(array('repair_id' =>$repair_id));
       
           $return = array (
                  'status' => 'success',
                  'message' => 'Төхөөрөмжийн засварын мэдээллийг амжилттай устгалаа'
           );

       }else
            
            $return = array (
                   'status' => 'failed',
                     'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
            );

       echo json_encode($return);
    }

    protected function edit_repair(){

       $id = $this->input->get_post ( 'repair_id' );

       unset($this->repair->validate[0]);

       $no_spare = $this->input->get_post('no_spare');

       if($no_spare){

           unset($this->repair->validate[4]);

           unset($this->repair->validate[5]);

           unset($this->repair->validate[6]);
       }

       if($this->repair->validate($this->repair->validate)){

            $data = $this->repair->array_from_post(array('device_id', 'passbook_id',  'repair_date', 'reason', 'repair', 'no_spare'));

            $data['duration'] = $this->to_seconds($this->input->get_post('duration'));

            $no_spare = $this->input->get_post('no_spare');
            
            if(empty($no_spare)){
               
               $data['no_spare']=0;
                              
            }

            if ($this->Obj->db->update('repair', $data, array('id' => $id))){

               // $this->db->update('mytable', $data, array('id' => $id));

               $this->Obj->db->delete('repair_employee', array('repair_id'=>$id));

               $employee = $this->employee->get( $this->input->get_post ('repairedby_id'));

               $emp_data = $emp_array = array();

               $employee_array = $this->input->get_post('repairedby_id');

               for ($i=0; $i < sizeof($employee_array); $i++) { 

                  $emp_array['repair_id'] = $id;

                  $emp_array['employee_id'] = $employee_array[$i];

                  $employee = $this->employee->get($employee_array[$i]);

                  $emp_array['employee'] = $employee->fullname;
                     
                  array_push($emp_data, $emp_array);

               }

               $this->Obj->db->insert_batch('repair_employee', $emp_data); 

               if(empty($no_spare)){

                  $this->Obj->db->delete('repair_spare', array('repair_id'=>$id));

                  $spare_array = $this->input->get_post('spare_id');
                  
                  $qty_array = $this->input->get_post('qty');
                  
                  $part_number_array = $this->input->get_post('part_number');
                  
                  $my_data = $sub_data = array();

                  for ($i=0; $i < sizeof($spare_array); $i++) { 

                     $sub_data['repair_id'] = $id;

                     $sub_data['spare_id'] = $spare_array[$i];

                     $spare = $this->spare->get($spare_array[$i]);

                     $sub_data['spare'] = $spare->spare;
                        
                     $sub_data['qty'] = $qty_array[$i];
                     
                     $sub_data['part_number'] = $part_number_array[$i];

                     array_push($my_data, $sub_data);

                     } 

                     $this->Obj->db->insert_batch('repair_spare', $my_data);    

               }
                             
               $return = array (
                    'status' => 'success',
                    'message' => 'Төхөөрөмжийн үзүүлэлтийг амжилттай хадгаллаа'
               );

               // echo $this->Obj->db->last_query();

            }else{

              echo $this->Obj->db->last_query();
             
                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            } 

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

       echo json_encode($return);

    }

    protected function set_pass(){

       //update device passport here
        $this->Obj->load->library ( 'form_validation' );

        $this->Obj->form_validation->set_rules ( 'passport_no', 'Пасспортын дугаар', 'required|max_length[10]' );

        $this->Obj->form_validation->set_message ( 'max_length', ' "%s" 10-с их утга авахгүй!' );

        $pass = $this->input->get_post('passport_no');

        $device_id = $this->input->get_post('device_id');
        
        if ($this->Obj->form_validation->run () != FALSE) {

            if ($id = $this->Obj->db->update('device', array('passport_no'=>$pass), "id = $device_id")){
               
               $return = array (
                    'status' => 'success',
                    'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
               );

            }else{

                $return = array (
                     'status' => 'failed',
                       'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'
                  );

            }  

        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        echo json_encode($return);

    }

    function unset_validate($start, $end){

      for ($i=$start; $i <=$end ; $i++) { 
         
         unset($this->device->validate[$i]);

      }

    }

    function add_passbook(){

        $device_id = $this->input->get_post('device_id');

        $equipment_id = $this->input->get_post('equipment_id');

        $equipment = $this->equipment->get($equipment_id);

        // Хэрэв equipment_id хэсэг нь ажиглалтаас өөр байвал дахин оруулж байгаа эсэхийг шалгана.
        if($equipment->section_id < 3){

           if($count = $this->check_added_passbook($device_id, $equipment_id)){

              $return = array (
                    'status' => 'failed',
                    'count'=>$count,
                    'message' => "Энэ төхөөрөмжд дээр бүлэг аль хэдийн нэмсэн байгаа тул дахин нэмэх шаардлаггүй"
               );

              echo json_encode($return);

              exit();
            
           }

        }     
        
        $passbook_no = $this->input->get_post('passbook_no');

        $module_array = $this->input->get_post('node_id');

        $error = array();

        if($this->passbook->validate($this->passbook->validate)){

          // Хэрэв тухайн passbook тухайн модны толгой бол гэсэн сонголт
          if(sizeof($module_array)>1){

              for ($i=0; $i < sizeof($module_array); $i++) { 

                $tree_id = $module_array[$i];

                $tree = $this->tree->get($tree_id);

                if($tree->parent==0) { // энэ parent_id гэсэн үг

                    $error[$i] = 'error';

                    $message = 'Алдааны модны зөвхөн толгойг сонгосон бол бусад дэд модулиудыг сонгох боломжгүй';
                }

              }
          }

          if(sizeof($error)>0){

               $return = array (
                    'status' => 'failed',
                    'message' => $message
               );
          
          }else{

            if($id = $this->passbook->insert(array('device_id'=>$device_id, 'equipment_id'=>$equipment_id, 'passbook_no'=>$passbook_no), true)){

                $my_data = $sub_data = array();

                for ($i=0; $i < sizeof($module_array); $i++) { 

                  $sub_data['module_id'] = $module_array[$i];

                  $tree = $this->tree->get($module_array[$i]);

                  $sub_data['module'] = $tree->module;
                  
                  $sub_data['passbook_id'] = $id;

                  array_push($my_data, $sub_data);
                  # code...
                }

                $this->Obj->db->insert_batch('passbook_detail', $my_data); 

              }

               $return = array (
                        'status' => 'success',
                        'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
                   );
            }
          
        }else{

           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' )
           );
        }

        // add_passbook deteail here

        echo json_encode($return);
    }

    function check_added_passbook($device_id, $equipment_id){

        if($exists = $this->passbook->count_by(array('device_id'=>$device_id))){

          return $exists;

        }else return false;

    }

    function get_passbook(){

        $data_detail = array();

        $passbook_id = $this->input->get_post('id');

        $passbook = $this->passbook->get($passbook_id);

        $passbook_detail = $this->passbook_detail->get_many_by(array('passbook_id'=>$passbook_id));

        foreach ($passbook_detail as $row) {
          
        $data_detail [$row->module_id] = $row->module;

       }
          
        $passbook->detail = $data_detail;

        echo json_encode($passbook);
    }

    function delete_passbook(){

        $passbook_id = $this->input->get_post('id');

        $parameter = $this->parameter->get_by(array('passbook_id' => $passbook_id));

        $material = $this->material->get_by(array('passbook_id' => $passbook_id));
        
        $event = $this->event->get_by(array('passbook_id' => $passbook_id));

        if($parameter || $material || $event){

          $return = array (
              'status' => 'failed',
              'message' => 'Үндсэн үзүүлэлт, иж болгогч зүйлс, ТҮ-ны бүртгэлүүд дээр мэдээлэл хадгалагдсан байгаа тул устгах боломжгүй! Эхлээд тус бүгтгэлүүд дээрээс мэдээллийг устгана уу!'
           );

        }else{


          if($this->Obj->db->delete('passbook', array('id' =>$passbook_id))){

             $this->Obj->db->delete('passbook_detail', array('passbook_id' =>$passbook_id));

             $this->material->update_many(array('passbook_id'=>0), array('passbook_id'=>$passbook_id), FALSE);
             
             $this->parameter->update_many(array('passbook_id'=>0), array('passbook_id'=>$passbook_id), FALSE);

             $this->event->update_many(array('passbook_id'=>0), array('passbook_id'=>$passbook_id), FALSE);

             $return = array (
                'status' => 'success',
                'message' => 'хэсгийг амжилттай устгалаа'
             );

          }else

            $return = array (
              'status' => 'failed',
              'message' => 'Устгахад алдаа гарлаа'
           );

        }


        echo json_encode($return);
    }

    //EDIT PASSBOOK
    function edit_passbook(){

      $passbook_id = $this->input->get_post('passbook_id');

      $device_id = $this->input->get_post('device_id');

      $equipment_id = $this->input->get_post('equipment_id');
      
      $passbook_no = $this->input->get_post('passbook_no');

      $module_array = $this->input->get_post('node_id');

      $error = array();

      if($this->passbook->validate($this->passbook->validate)){

        // Хэрэв тухайн passbook тухайн модны толгой бол гэсэн сонголт
        if(sizeof($module_array)>1){

            for ($i=0; $i < sizeof($module_array); $i++) { 

              $tree_id = $module_array[$i];

              $tree = $this->tree->get($tree_id);

              if($tree->parent==0) { // энэ parent_id гэсэн үг

                  $error[$i] = 'error';

                  $message = 'Алдааны модны зөвхөн толгойг сонгосон бол бусад дэд модулиудыг сонгох боломжгүй';
              }

            }
        }

        if(sizeof($error)>0){

             $return = array (
                  'status' => 'failed',
                  'message' => $message
             );
        
        }else{

          if($update_id = $this->passbook->update($passbook_id, array('device_id'=>$device_id, 'equipment_id'=>$equipment_id, 'passbook_no'=>$passbook_no))){

              $this->Obj->db->delete('passbook_detail', array('passbook_id' =>$passbook_id));

              $my_data = $sub_data = array();

              for ($i=0; $i < sizeof($module_array); $i++) { 

                $sub_data['module_id'] = $module_array[$i];

                $tree = $this->tree->get($module_array[$i]);

                $sub_data['module'] = $tree->module;
                
                $sub_data['passbook_id'] = $passbook_id;

                array_push($my_data, $sub_data);
              }

              $this->Obj->db->insert_batch('passbook_detail', $my_data); 

            }

             $return = array (
                      'status' => 'success',
                      'message' => 'Засварын бүртгэлийг амжилттай хадгаллаа'
                 );
          }
        
      }else{

         $return = array (
            'status' => 'failed',
            'message' => validation_errors ( '', '<br>' )
         );
      }

        // add_passbook deteail here

        echo json_encode($return);
    }

    // collect all events here
    function get_events(){

       $data = array();

       $equipment_id = $this->input->get_post('equipment_id');

       $location_id = $this->input->get_post('location_id');

       $events = $this->event->get_many_by(array('equipment_id' =>$equipment_id, 'location_id'=>$location_id));

       // foreach ($events as $row) {
          
       //    $sub_data[$row->id] = $row->title.' Гүйцэтгэл: '.$row->title.$row->start;

       // }

       // print_r($events);

       $data['events'] = $events;

       echo json_encode($data);

    }


    protected function add_passbook_events(){

       $equipment_id = $this->input->get_post('equipment_id');

       $location_id = $this->input->get_post('location_id');
       
       $device_id = $this->input->get_post('device_id');

       $passbook_id = $this->input->get_post('passbook_id');
       
       $event = $this->input->get_post('event_id');

       // collect all data to update passbook detail

       $my_data = $sub_data = array();

       for ($i=0; $i < sizeof($event); $i++) { 

          // array('event_id'=>$event[$i])

           $data = array(

              'device_id' => $device_id,

              'passbook_id' => $passbook_id

           );
          
           $this->Obj->db->update('m_event', $data, array('id' => $event[$i]));
       
        }

       $return = array (
              'status' => 'success',
              'message' => 'Техник үйлчилгээнүүдийг амжилттай хадгаллаа!'
           );

       echo json_encode($return);

    }

    //gemtlees duudaj oruulah

    protected function add_repair_load(){
       
       $location_id = $this->input->get_post('location_id');
       
       $equipment_id = $this->input->get_post('equipment_id');
       
       $device_id = $this->input->get_post('device_id');
       
       $passbook_id = $this->input->get_post('passbook_id');
       
       $log = $this->input->get_post('log_id');

       // collect all data to update passbook detail

       $my_data = $sub_data = array();

       $success = array();

       for ($i=0; $i < sizeof($log); $i++) { 

          // array('event_id'=>$event[$i])

           $ilog = $this->flog->with('reason')->get($log[$i]);
           
           $data = array(

              'device_id' => $device_id,

              'passbook_id' => $passbook_id,

              'log_id' => $ilog->id,

              'repair_date' => $ilog->closed_dt,

              'reason' => $ilog->reason->reason,
              
              'repair' => $ilog->closed_comment,
              
              'duration'=> $ilog->duration,

              'created_at' =>date('Y-m-d H:i:s'),
              
              'updated_at' =>date('Y-m-d H:i:s')
        
           );

           if($this->Obj->db->insert('repair', $data)){

               // тухайн repair-н дагуу foreach хийж тухайн логийг pasbook_id-гаар Update хийх
               $this->Obj->db->update('f_log', array('passbook_id' =>$passbook_id), array('id'=>$ilog->id));

               $success[$i] = 1;             
            
           }
            
        }

       if(sizeof($success)>0){

           $return = array (
              'status' => 'success',
              'message' => 'Засвар үйлчилгээг амжилттай хадгаллаа!'
           );

       }else {
          
          $return = array (
              'status' => 'failed',
              'message' => 'Засварыг оруулахад алдаа гарлаа!'
           );
           
       }

       echo json_encode($return);

    }


   private function str_to_time($seconds){

		$hours = floor($seconds / 3600);
		$mins = floor($seconds / 60 % 60);
		$secs = floor($seconds % 60);

		$hours = ($hours==0) ? $hours.'0' : $hours;
		$mins = ($mins==0) ? $mins.'0' : $mins;
		$secs = ($secs==0) ? $secs.'0' : $secs;

		return $hours.":".$mins.":".$secs;
   }
   
   private function to_seconds($seconds){

      $parsed = date_parse($seconds);
      
      $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60;

      return $seconds;

   }


}

class Device_Module extends Device_Crud{

    protected $where;

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

        $this->state = $CI->uri->segment (3 );

        if (! $this->state)

           $this->state = 'open';
       
    }

    //хэрэв ajax baival yah uu?

    // don't need status
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

            switch ($this->get_status ()) {
                
                case 'grid' :
                        $this->grid ();
                        break;


                case 'add' :
                        $this->add ();                            
                        break;
                
                case 'get' :
                        $this->get ();                            
                        break; 

                case 'edit' :

                       $this->edit();                            

                       break;

                case 'delete' :

                        $this->delete();                            
                        break;

                case 'filterby' :

                      $this->filterby();                            
                      break;    

                 case 'get_cert' :

                      $this->get_cert();                            

                      break;   

                case 'add_material' :

                      $this->add_material();                            

                      break;     

                case 'del_material' :

                      $this->del_material();                            

                      break;  

                case 'add_maintenance' :

                      $this->add_maintenance();                            

                      break;

                case 'edit_maintenance' :

                      $this->edit_maintenance();                            

                      break; 

                case 'del_maintenance' :

                      $this->del_maintenance();                            

                      break; 

               case 'add_parameter' :

                      $this->add_parameter();                            

                      break; 

               case 'del_parameter' :

                      $this->del_parameter();                            

                      break;   

                case 'add_repair' :

                      $this->add_repair();                            

                      break;  

                case 'get_repair' :
                        
                      $this->get_repair();                            

                      break; 

                     
                case 'get_material' :
                        
                      $this->get_material();                            

                      break;  
                
                case 'edit_material' :
                        
                      $this->edit_material();                            

                      break;  


                case 'get_parameter' :
                        
                      $this->get_parameter();                            

                      break;        

                case 'edit_parameter' :
                        
                      $this->edit_parameter();                            

                      break; 


                case 'update_passport' :

                      $this->update_passport();                            

                      break;  


                case 'edit_repair' :
                        
                      $this->edit_repair();                            

                      break; 
  

                case 'del_repair' :

                      $this->del_repair();                            

                      break;         

                case 'get_pass_no' :

                      $this->gen_pass_no();                            

                      break;               

                case 'set_pass' :

                      $this->set_pass();                            

                      break;


                case 'add_passbook' :

                      $this->add_passbook();                            

                      break;     

                case 'edit_passbook' :

                      $this->edit_passbook();                            

                      break;      

                case 'delete_passbook' :

                      $this->delete_passbook();                            

                      break;  

                case 'get_events' :

                      $this->get_events();                            

                      break;             

                case 'add_passbook_events' :

                      $this->add_passbook_events();                            

                      break;  

                case 'get_passbook' :

                    $this->get_passbook();                            

                    break;  

                case 'add_repair_load' :

                    $this->add_repair_load();                            

                    break;  

                

	              }

        }else{

          switch ($this->get_status ()) {

            case 'create': 

                $this->create_form();

                break;


            default : // index page loaded
                    $this->index_form();
                    break;
               }
        }

        return $this->get_layout();

    }
}
