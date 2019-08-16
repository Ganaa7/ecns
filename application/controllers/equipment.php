<?php

if (! defined ( 'BASEPATH' )) 	exit ( 'No direct script access allowed' );

class equipment extends CNS_Controller {

    public function __construct() {

        parent::__construct ();

        $this->load->library ( 'Equipment_module' );

        $this->load->model ( 'certificate_model' );
        
        $this->load->model ( 'wh_spare_model' );
      
        $this->load->model ( 'repair_model' );

        $this->load->model ( 'material_model' );

        $this->load->model ( 'f_log_model' );
        
        $this->load->model ( 'Country_model' );
        
        $this->load->model ( 'Manufacture_model' );
        
        $this->load->model ( 'event_model' );
        
        $this->load->library ( 'Device_module' );

        $this->load->model ( 'Parameter_model' );
        
        $this->load->model ( 'Passbook_model' );
        
        $this->load->model ( 'passbook_detail_model' );
        
        $this->load->model ( 'event_detail_model' );

        $this->load->model ( 'ftree_model' );

        $this->load->model ( 'sparetype_model' );

        $this->load->model ( 'manufacture_model' );        

        $this->load->model ( 'measures_model' );
        
        $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'equipment', $this->role, 0, 1 ));
        
        $this->config->set_item ( 'module_menu', 'Тоног төхөөрөмжийн паспорт' );

        $this->config->set_item ( 'module_menu_link', '/ecns/equipment');

        $this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

        $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/device/js/device.js', TRUE ));
        
    }

    function lists(){

        $equipment= new Equipment_Module();

        $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/equipment/js/equipment.js', TRUE ));

        $this->data['equipment_OBJ'] =$equipment->run ();

        $this->data['section']=$this->section_model->dropdown_by('section_id', 'name', array('type'=>'industry'));

        $this->data['sector']=$this->sector_model->dropdown_by('sector_id', 'name', array('section_id'=>1));
        
        $this->data['equipment']=$this->equipment_model->dropdown_by('equipment_id', 'equipment',array('section_id' =>1 ));

        $this->data['title'] = "Тоног төхөөрөмжийн бүртгэл";

        $this->data['page']= 'equipment/index';

        $this->load->view("index", $this->data );
        
    }

    function index(){

        $device= new Device_Module();

        $this->data['country'] = $this->Country_model->dropdown_by('country_id', 'country');
        
        $this->data['manufacture'] = $this->Manufacture_model->dropdown_by('Manufacture_id', 'Manufacture');

        $this->data['equipment_OBJ'] =$device->run ();

        $this->data['section']=$this->section_model->dropdown_by('section_id', 'name', array('section_id <'=>5));

        $this->data['sector']=$this->sector_model->dropdown_by('sector_id', 'name', array('section_id'=>1));
        
        $this->data['location']=$this->location_model->dropdown_by('location_id', 'location');

        if($this->session->userdata('section_id')>5)
        
        $this->data['equipment']=$this->equipment_model->dropdown('equipment_id', 'equipment');
        
        else $this->data['equipment']=$this->equipment_model->dropdown_by('equipment_id', 'equipment',  array('section_id' =>$this->session->userdata('section_id')));

        $this->data['title'] = "Тоног төхөөрөмжийн пасспорт";

        $this->data['page']= 'device/index';

        $this->load->view("index", $this->data );

    }


    protected function ftree($equipment_id = null) {

        $CI = &get_instance ();
        if ($equipment_id == null)
            $equipment_id = $CI->input->get_post ( 'equipment_id' );
        if ($equipment_id == null)
            $equipment_id = 0;

        $store_all_id = array ();
        $query = $this->log_model->get_simple ( "equipment_id = $equipment_id", 'f_tree' );
        foreach ( $query->result_array () as $row ) {
            array_push ( $store_all_id, $row ['parent'] );
        }
        $tree = '';
        if ($equipment_id !== 0&&in_array(0, $store_all_id)) {
            $tree.="<div style='margin-bottom:10px;' id='sidetreecontrol'>Үйлдлүүд: <a href='?#'>Хумих</a> | <a href='?#'>Дэлгэх</a> | <a id='reset' href='#'>Сэргээх</a></div>";

            //you can add here sub systems

            $tree .= $this->ftree_model->tree_parent ( 0, $equipment_id, $store_all_id );
            $tree .= "</div>";
        }else if($equipment_id !==0){
            $tree.="<div style='color:red'><i><strong>Энэ төхөөрөмж дээр Алдааны мод үүсгээгүй байна! Тухайн тоног төхөөрөмж хариуцсан инженертэй холбогдоно уу!</strong></i></div>";
        }else
            $tree.="<div style='color:blue;'><i>Тухайн байршил дахь тоног төхөөрөмжийг сонгоход алдааны мод автоматаар гарч ирнэ!</i></div>";
        return $tree;
    }

    function grid(){
        $this->equipment_model->grid();
    }
    
    // Параметер
    public function parameter()
    {
        $crud = new grocery_CRUD ();

        $crud->set_table ( 'parameters' );

        $crud->set_subject ( 'Техникийн үндсэн үзүүлэлтүүд' );

        $crud->add_fields ('device_id', 'parameters', 'measure', 'value');

        $crud->set_relation ( 'device_id', 'device', 'device' );

        $crud->columns ( 'device_id', 'parameters', 'measure', 'value');

        $crud->display_as ( 'device_id', 'Төхөөрөмж' )->display_as ( 'parameters', 'Үзүүлэлт' )->display_as ( 'measure', 'Хэмших нэгж' )->display_as ( 'value', 'Утга' );
        
        $output = $crud->render ();

        $this->_settings_output ( $output );
    }    
    
    public function material()
    {
        $crud = new grocery_CRUD ();

        $crud->set_table ( 'materials' );

        $crud->set_subject ( 'Техникийн иж болгогч зүйлс' );

        $crud->add_fields ('device_id', 'materials', 'qty', 'part_number');

        $crud->set_relation ( 'device_id', 'device', 'device' );

        $crud->columns ('device_id', 'materials', 'qty', 'part_number');

        $crud->display_as ( 'device_id', 'Төхөөрөмж' )->display_as ( 'materials', 'Иж болгогч зүйлс' )->display_as ( 'qty', 'Тоо ширхэг' )->display_as ( 'part_number', 'Үйлдвэрийн №' );

        $output = $crud->render ();

        $this->_settings_output ( $output );
    } 

    // Засвар
    function maintenance()
    {
        $crud = new grocery_CRUD ();

        $crud->set_table ( 'm_event' );

        $crud->set_subject ( 'ТҮ' );

        $crud->add_fields ('section_id', 'equipment_id', 'location_id', 'start', 'end', 'done', 'eventtype_id');

        $crud->add_fields ('section_id', 'equipment_id', 'location_id', 'start', 'end', 'done', 'eventtype_id');

        $crud->set_relation ( 'eventtype_id', 'eventtype', 'eventtype' );

        $crud->set_relation ( 'section_id', 'section', 'section' );

        $crud->set_relation ( 'location_id', 'location', 'location' );

        $crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );

        $crud->columns ( 'section_id', 'location_id', 'equipment_id', 'eventtype_id', 'start', 'end',  'done');

        $crud->display_as ( 'section_id', 'Хэсэг' )->display_as ( 'equipment_id', 'Төхөөрөмж' )->display_as ( 'location_id', 'Байршил' )->display_as ( 'start', 'Эхэлсэн' )->display_as ( 'end', 'Дууссан' )->display_as ( 'eventtype_id', 'Хийгдсэн ТҮ-ний төрөл')->display_as ( 'done', 'Хийгдсэн ТҮ');

        $output = $crud->render ();

        $this->_settings_output ( $output );
    }

    // Техник үйлчилгээ
    function repair()
    {
        $crud = new grocery_CRUD ();

        $crud->set_table ( 'repair' );

        $crud->set_subject ( 'Засвар үйлчилгээ' );

        $crud->add_fields ('device_id', 'equipment_id', 'location_id', 'spare_id', 'reason', 'repair', 'opened_dt', 'closed_dt','repaired');

        $crud->set_relation ( 'device_id', 'device', 'device' );

        $crud->set_relation ( 'location_id', 'location', 'location' );

        $crud->set_relation ( 'equipment_id', 'equipment2', 'equipment' );

        $crud->columns ('device_id', 'equipment_id', 'location_id', 'spare_id', 'reason', 'repair', 'opened_dt', 'closed_dt','repaired');

        $crud->set_relation ( 'spare_id', 'wh_spare', 'spare' );

        $crud->display_as ( 'section_id', 'Хэсэг' )
             ->display_as ( 'equipment_id', 'Төхөөрөмж' )
             ->display_as ( 'location_id', 'Байршил' )
             ->display_as ( 'spare_id', 'Сэлбэг' )
             ->display_as ( 'reason', 'Шалтгаан' )
             ->display_as ( 'repair', 'Засварласан байдал')
             ->display_as ( 'opened_dt', 'Засвар эхэлсэн хугацаа')
             ->display_as ( 'repairedby', 'Засвар хийж гүйцэтгэсэн ИТА');


        $output = $crud->render ();

        $this->_settings_output ( $output );
    }

    function _settings_output($output = null) {

        $this->load->view ( 'settings.php', $output );

    }
    
    // edit heseg end baina
    function edit($device_id){

        $device = $this->device_model->
                        with('equipment')->
                        with('certificate')->
                        with('location')->
                        with('manufacture')->
                        with('country')->                       
                        get($device_id);

        // тухайн хэсгээр шүүнэ
        if($this->session->userdata('section_id')<=5){

            $this->data['section'] = $this->section_model->dropdown_by
            ('section_id', 'name', array('section_id'=> $this->session->userdata('section_id')) );    

            // $section[0] = 'Нэг хэсгийг сонго!';    
            // $this->data['section']= $section;

            $this->data['equipment']=$this->equipment_model->dropdown_by('equipment_id', 'equipment', 
                array('section_id' =>$this->session->userdata('section_id')));
        
        }else{
             
             $this->data['section']= $this->section_model->dropdown_by('section_id', 'name', 
                    array(
                        'section_id < ' =>5
                    )
                ) ;

             $this->data['equipment']=$this->equipment_model->dropdown('equipment');
                
        }

        // $this->data['certificate'] = $this->certifcate->get_by(array('section_id' => $this->session->update('section_id')));

        $this->data['country']=$this->Country_model->dropdown('country');

        $this->data['manufacture']=$this->Manufacture_model->dropdown('manufacture');

        $this->data['location']=$this->location_model->dropdown_by('location_id', 'location');

        $this->data['event'] = $this->event_model->with('eventtype')->get_many_by(array('location_id'=>$device->location_id, 'equipment_id'=>$device->equipment_id));   

        // repair_model байгаа эсэхийг шалгана
        
        // if($this->repair_model->with('wh_spare')->get_many_by(array('device_id'=>$device->id))){
            
        //     $this->data['repair'] = $this->repair_model->with('wh_spare')->get_many_by(array('device_id'=>$device->id));
        // }
                
        //тухайн log-n medeelliig repairluu hiine

        $where = "completion_id in (3, 7) AND location_id=$device->location_id and equipment_id=$device->equipment_id";

        $this->data['log'] = $this->f_log_model->   
                with('completion')->
                with('reason')->
                with('equipment')->get_many_by($where);

        //array('completion_id'=>3, 'completion_id'=>7, 'location_id'=>$device->location_id, 'equipment_id'=>$device->equipment_id)
        $this->data['device']=$device;

        if($device->equipment)        
           
           $this->data['employee']=$this->employee_model->dropdown_by('employee_id', 'fullname');

        else $this->data['employee']=$this->employee_model->dropdown_by('employee_id', 'fullname', array(
                        'section_id < ' =>5
              ));

        if(isset($device->equipment->sp_id))
          $this->data['spare']=$this->wh_spare_model->dropdown_by('id', 'spare', array('equipment_id'=>$device->equipment->sp_id, 'section_id' => $device->equipment->section_id));

        else
           $this->data['spare']=$this->wh_spare_model->dropdown_by('id', 'spare', array('equipment_id'=>$device->equipment->parent_id, 'section_id' => $device->equipment->section_id));

        $this->data['material'] = $this->material_model->get_many_by('device_id', $device_id);

        $this->data['parameter'] = $this->parameter_model->get_many_by('device_id', $device_id);

        $certificate = $this->certificate_model->dropdown_by('id', 'cert_no', 
            array('section_id' => $device->section_id, 'location_id' =>$device->location_id));

        $certificate['0'] = 'Гэрчилгээгүй';
        
        $this->data['certificate'] = $certificate;

        $this->data ['eventtype'] = $this->eventtype_model->dropdown('eventtype');
        
        $this->data['page']  = 'device/edit';
        
        $this->load->view('index', $this->data);

    }

    //  Харуулах хэсэг
    function add($device_id){

        if($device = $this->device_model->
                        with('equipment')->
                        with('certificate')->
                        with('location')->
                        with('manufacture')->
                        with('country')->                       
                        with('section')->                       
                        get($device_id)){


                // get all event_m -s location_id equipment_id-r utguudiig avna
            if(!$device->country){

               $device->country = $this->Country_model->dropdown('country');
               
            }
            // $this->data['repair'] = $this->repair_model->with('wh_spare')->get_many_by(array('device_id'=>$device->id));   

            //тухайн log-n medeelliig avah heregetei ba
            $where = "completion_id in (3, 7) AND location_id=$device->location_id and equipment_id=$device->equipment_id and passbook_id is null";

            $this->data['logs'] = $this->f_log_model->
                with('completion')->
                with('reason')->
                with('equipment')->get_many_by($where);

            $this->data['device']=$device;

            if($passbook = $this->Passbook_model->with('device')->get_many_by(array('device_id' => $device_id, 'equipment_id'=>$device->equipment_id))){

               $this->data['passbooks'] = $passbook;

               $this->data['count'] = $this->Passbook_model->count_by('device_id', $device->id);

               // $this->data['passbook_detail'] = $this->passbook_detail
            }  
            
            $passbook_list = $this->passbook_model->dropdown_by('id', 'passbook_no',  array('device_id'=>$device_id));

            $passbook_list[0]='Бүгдийг сонго';

            ksort($passbook_list);
            
            $this->data['passbook_list']=$passbook_list;
                        
            $employee = $this->employee_model->dropdown('employee_id', 'fullname');
            // echo $this->db->last_query();

            $employee[0]='Орон нутгийн ИТА';

            $this->data['employee']=$employee;

            if(isset($device->equipment->sp_id)){

                $spares = $this->wh_spare_model->dropdown_sord('spare', array('section_id'=>$device->section_id), "spare asc");

            }else

                $spares = $this->wh_spare_model->dropdown_sord('spare', array('section_id'=>$device->section_id, 'type_id'=>2), "spare asc");
            
            $this->data['wh_spare']= $spares;
            
            $this->data['material'] = $this->material_model->get_many_by('device_id', $device_id);

            $this->data['parameter'] = $this->parameter_model->get_many_by('device_id', $device_id);

            $this->data ['eventtype'] = $this->eventtype_model->dropdown('eventtype');
            
            $this->data ['node'] = $this->tree_model->dropdown_by('id', 'module', array('equipment_id'=>$device->equipment_id));

            $this->data['events'] = $this->event_model->get_events($device->equipment_id, $device->location_id);

            $this->data['sparetype']=$this->sparetype_model->dropdown('sparetype');  
      
            $this->data['manufacture']=$this->manufacture_model->dropdown('manufacture');          

            $this->data['measure']=$this->measures_model->dropdown('measure');  

        }else{ 

            $this->data['device']=null;

        }

        // $this->data['page']  = 'equipment/passport';
        $this->data['page']  = 'device/add_more'; 
        
        $this->load->view('index', $this->data);

    }

    //  Харуулах хэсэг
    function view($device_id){

        if($device = $this->device_model->
                        with('equipment')->
                        with('certificate')->
                        with('location')->
                        with('manufacture')->
                        with('country')->                       
                        with('section')->                       
                        get($device_id)){

            // get all event_m -s location_id equipment_id-r utguudiig avna
            if(!$device->country){

               $device->country = $this->Country_model->dropdown('country');
               
            }
   
            // $this->data['repair'] = $this->repair_model->with('wh_spare')->get_many_by(array('device_id'=>$device->id));   

            //тухайн log-n medeelliig avah heregetei ba
            $where = "completion_id in (3, 7) AND location_id=$device->location_id and equipment_id=$device->equipment_id";

            // $this->data['log'] = $this->f_log_model->
            //     with('completion')->
            //     with('reason')->
            //     with('equipment')->get_many_by($where);

            // echo $this->db->last_query();
            $this->data['device']=$device;

            if($passbook = $this->Passbook_model->with('device')->get_many_by(array('device_id' => $device_id, 'equipment_id'=>$device->equipment_id))){

               $this->data['passbooks'] = $passbook;

               $this->data['count'] = $this->Passbook_model->count_by('device_id', $device->id);

               // $this->data['passbook_detail'] = $this->passbook_detail

            }
           
            $employee = $this->employee_model->dropdown_by('employee_id', 'fullname', array('section_id'=>$device->equipment->section_id));

            $employee[0]='Орон нутгийн ИТА';
            $this->data['employee']=$employee;
           $this->data['wh_spare']=$this->wh_spare_model->dropdown_by('id', 'spare', array('equipment_id'=>$device->equipment_id));

            $this->data['material'] = $this->material_model->get_many_by('device_id', $device_id);

            $this->data['parameter'] = $this->parameter_model->get_many_by('device_id', $device_id);

            $this->data ['eventtype'] = $this->eventtype_model->dropdown('eventtype');
            
            $this->data ['node'] = $this->tree_model->dropdown_by('id', 'module', array('equipment_id'=>$device->equipment_id));

            $this->data['events'] = $this->event_model->get_events($device->equipment_id, $device->location_id);

        }else{ 

            $this->data['device']=null;

        }

        // $this->data['page']  = 'equipment/passport';
        $this->data['page']  = 'device/passport';
        
        $this->load->view('index', $this->data);

    }

    function help()
    {
        $this->load->view('device/help');
    }

    // its takes location_id, equiopment_id, and certificate_id, 
    function manufacture()
    {
        $crud = new grocery_CRUD ();

        $crud->set_table ( 'wm_manufacture' );

        $crud->set_subject ( 'Үйлдвэрлэгч' );

        $crud->add_fields ('manufacture', 'description', 'country_id');

        $crud->set_relation ( 'country_id', 'country', 'country' );

        $crud->required_fields ( 'manufacture', 'country_id');

        $crud->display_as ( 'manufacture', 'Үйлдвэрлэгч' )
             ->display_as ( 'description', 'Тодорхойлолт' )
             ->display_as ( 'country_id', 'Улс' );

        if (! in_array ( $this->session->userdata ( 'role' ), array (
            'ADMIN',
            'TECHENG' 
        ) )) {

        $crud->unset_delete ();

        }

        $output = $crud->render ();

        $this->_settings_output ( $output );
    }

    function passbook(){

        echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam atque facere dicta consectetur repellendus officia ducimus alias perspiciatis, reprehenderit doloribus tempora expedita fugiat ipsum deserunt magni itaque saepe sit ullam.";


    }

    function add_passbook(){

        $this->input->get_post('device_id');

        $this->input->get_post('equipment_id');

        print_r($this->input->get_post('node_id'));


    }

  

}
