<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */

require_once('Core.php');

class Equipment_Layout extends Core{

    protected $device;

    private $echo_die = false;

    // protected $view_as_string;

    // private $theme_location = 'assets/apps/equipment/theme/';

    public function __construct()
    {
        parent::__construct();

        $this->Obj->load->model ( 'device_model' );

        $this->device = new Device_model ();

        parent::set_file('grid.php');

        parent::set_location('assets/apps/equipment/theme/');
        
    }
    // here is all database and logics

    protected function index_form(){

       // here is only grid in index form
       $data['action'] =$this->equipment->get_action();
       
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

        // нийт тоо
        $this->set_count($this->equipment);

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

        if($this->section_id<5){
          if($this->where){
             $this->where .= " and section.section_id = $this->section_id";
          }else
             $this->where =" section.section_id = $this->section_id";
        }

        $json['rows'] = $this->equipment->get_query($this->where, $this->sidx, $this->sord , $this->start, $this->limit);

        // echo $this->equipment->last_query();

        echo json_encode($json);
    }

 
}

class Equipment_Crud extends Equipment_Layout{

     public function __construct()
    {
        parent::__construct();
        
    }

    protected function add(){
        
        //Сэлбэгийн дугаарыг бодох:
        //1. hamgiin bagiig avaad 
        $this->Obj->form_validation->set_message('exact_length', " %s утга тохирохгүй байна!");

        if($this->equipment->validate($this->equipment->validate)){

            $data = $this->equipment->array_from_post(array('section_id', 'sector_id', 'equipment', 'code', 'intend', 'spec', 'year_init'));
            
            $data['created_at'] =date('Y-m-d H:i:s');
            
            if($this->input->get_post('spare_type_id')==1)
            // if spare_id_type = 1 bol shineer olgono
                $data['sp_id']=$this->equipment->get_spare_id($data['section_id'], $data['sector_id']);
            else
                //spare_type_equiment_id утгаар from equipment spare_id-г авч хэрэглэнэ
                $data['parent_id'] = $this->input->get_post('equipment_spare_id');
                        
            if ($id = $this->equipment->insert($data, TRUE)){
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

       $equipment = $this->equipment->get($id);

       $spare = $this->wh_spare->get_many_by(array('equipment_id'=>$equipment->sp_id, 'section_id'=>$equipment->section_id,'sector_id' =>$equipment->sector_id));

       // $this->equipment->last_query();

       $ftree = $this->ftree->get_by('equipment_id', $id);


       if($spare){

            $return = array (
                'status' => 'failed',
                'message' => '"'.$equipment->equipment.'" төхөөрөмж дээр сэлбэг хадгалсан байгаа тул устгах боломжгүй байна!'
             );        

       }elseif($ftree){

            $return = array (
                'status' => 'failed',
                'message' => '"'.$equipment->equipment.'" төхөөрөмж дээр Адааны мод үүссэн тул устгах боломжгүй байна!'
             );        

       }else{

          if($this->equipment->delete($id)){

             $return = array (
                'status' => 'success',
                'message' => '"'.$equipment->equipment.'" төхөөрөмжийг амжилттай устгалаа'
             );

           }else

              $return = array (
                   'status' => 'failed',
                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа'.$this->Obj->db->_error_message()
              );


       }
       
       echo json_encode($return);
    }

    protected function get(){

       $id = $this->input->get_post ( 'id' );

       $library = $this->equipment->with('section')->get($id);

       echo json_encode($library);
    }

    protected function get_spare(){

       $section_id = $this->input->get_post ( 'section_id' );
       
       $sector_id = $this->input->get_post ( 'sector_id' );

       $query = $this->Obj->db->query("select sp_id, equipment from equipment2 where section_id = $section_id and sector_id = $sector_id and sp_id is not null order  by equipment");
       
       if($query->num_rows()>0){

            foreach ($query->result() as $row) {

                $result_array[$row->sp_id] = $row->equipment;
            }
       }else $result_array = null;
        

       echo json_encode($result_array);
    }

    protected function edit(){

        $id = $this->input->get_post ( 'equipment_id' );

        // check if old is new same
        $data = $this->equipment->array_from_post(array('equipment_id', 'section_id', 'sector_id', 'equipment', 'code', 'intend', 'intend', 'year_init', 'sp_id'));
        
        // validate library at work

        $intend = $data ['intend'];
         
        unset ( $data ['intend'] );

        if($this->equipment->validate($data)){

            //check sp_id is null
            if(!$data['sp_id']){

                 $data['sp_id']=$this->equipment->get_spare_id($data['section_id'], $data['sector_id']);

            }

            if ($this->equipment->update($id, $data)){               
                
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
    protected function section_select(){

        $data = array ();

        $id =$this->input->get_post ( 'id' );  
        
        if($id)
          $data = $this->sector->dropdown_by('sector_id', 'name', array('section_id'=>$id) );
        else
          $data = $this->sector->dropdown('name');

        echo json_encode($data);
    }

    protected function device_grid(){

        $json =array();

        $equipment_id = $this->input->get_post('id');

        $rows = $this->device->get_many_by(array('equipment_id'=>$equipment_id));

        // print_r($rows);

        if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
        
            header ( "Content-type: application/xhtml+xml;charset=utf-8" );

        } else {
            
            header ( "Content-type: text/xml;charset=utf-8" );
        
        }

        echo "<?xml version='1.0' encoding='utf-8'?>";

        echo "<rows>";

        foreach ( $rows as $row ) {
            echo "<row>";
            echo "<cell>" . $row->device . "</cell>";
            echo "<cell><![CDATA[" . $row->location . "]]></cell>";
            echo "<cell></cell>";
            echo "<cell></cell>";
            echo "<cell></cell>";
            // echo "<cell>" . $row->year_init . "</cell>";
            // echo "<cell>" . $row->spec ."км"."</cell>";            
            // echo "<cell>" . $row->is_come ."</cell>";            
            // echo "<cell>" . $row->infoby     ."</cell>";            
            // echo "<cell>" . $row->comment ."</cell>";            
            echo "</row>";
        }
        echo "</rows>";        
    
    }
      

}

class Equipment_Module extends Equipment_Crud{

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

                    case 'filter' :

                          $this->section_select();                            
                          break;

                    case 'device' :

                      $this->device_grid();  
                                                
                      break;

                    case 'get_spare' :

                      $this->get_spare();  

                      break;
                
    	       }

              }else{


                  switch ($this->get_status ()) {

                    default : // index page loaded
                            $this->index_form();
                            break;
                       }

                 }
                 return $this->get_layout();

    }
}
