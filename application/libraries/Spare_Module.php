<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */

require_once('Core.php');

class Spare_Loader extends Core
{

    protected $h_invoice; 

    protected $h_invoice_dtl;    

    protected $store;   

    protected $spare_model;

    protected $site_model;
    
    function __construct(){

        parent::__construct();

        $this->Obj->load->model ( 'site_model');
        $this->site_model = new site_model();     
        
        $this->Obj->load->model ( 'wh_spare_model' );
        $this->spare_model = new wh_spare_model();     

        $this->Obj->load->model ( 'h_invoice_model' );
        $this->h_invoice = new h_invoice_model();     

        $this->Obj->load->model ( 'h_invoice_dtl');
        $this->h_invoice_dtl = new h_invoice_dtl();     

        $this->Obj->load->model ( 'store_model' );

        $this->store = new store_model ();

    }
}

class Spare_Layout extends Spare_Loader{
    
    private $echo_die = false;
   
     function __construct(){

        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/h_spare/view/');

    }

    function get_values($id){

        $dtl = $sub_data = array();
        
        $spare_id = $this->input->get_post('spare_id');
        $site_id = $this->input->get_post('site_id');
        $serial = $this->input->get_post('serial');
        $barcode = $this->input->get_post('barcode');

        for ($i=0; $i <count($site_id) ; $i++) { 
            $sub_data ['invoice_id'] = $id;
            $sub_data ['site_id'] = $site_id[$i];
            $site = $this->site_model->get($site_id[$i]);
            $sub_data ['site'] = $site->site;
            $sub_data ['serial'] = $serial[$i];
            $sub_data ['barcode'] = $barcode[$i];           
            $spare=$this->spare_model->get($spare_id);
            $sub_data ['qty'] = 1;
            $sub_data ['spare'] = $spare->spare;            
            $sub_data ['spare_id'] = $spare_id;                        
            array_push($dtl, $sub_data);
        }        
        return $dtl;
    } 

    protected function index_form(){
       // here is only grid in index form 
        $data['action']=$this->h_invoice->get_action();

       return $this->theme_view ($data);        
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
                
        $this->check_filter();

        $this->set_count($this->h_invoice);     

        $this->set_total_page();
        
        $this->set_page();

        $this->set_start();

        $json=$rows= array();  
        $json['page']=$this->page;
        $json['total']=$this->total_pages;
        $json['records']=$this->count;  

        if($this->section_id<5){
           if($this->where){                    
               $this->where .= " and _wh_vw_spare.section_id = $this->section_id";    
           }else
              $this->where =" _wh_vw_spare.section_id = $this->section_id";    
        }

        $rows = $this->h_invoice->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=($this->session->userdata('role')=='ADMIN') ? $this->h_invoice->last_query(): 'null';

        $json['rows']=$rows;

        echo json_encode($json);                
    }

    function sub_grid(){
        $json =array();
        $spare_id = $this->input->get_post('id');        
        //$spare_invoice= $this->h_invoice->get($invoice_id);
        $rows = $this->h_invoice_dtl->get_many_by(array('spare_id'=>$spare_id));
         
       if (stristr ( $_SERVER ["HTTP_ACCEPT"], "application/xhtml+xml" )) {
            header ( "Content-type: application/xhtml+xml;charset=utf-8" );
        } else {
            header ( "Content-type: text/xml;charset=utf-8" );
        }
        echo "<?xml version='1.0' encoding='utf-8'?>";
        echo "<rows>";
        $i= 1;
        foreach ( $rows as $row ) {
            
            echo "<row>";
            echo "<cell>" . $row->id. "</cell>";
            echo "<cell>" . $i . "</cell>";
            echo "<cell>" . $row->site. "</cell>";            
            echo "<cell>" . $row->qty . "</cell>";
            echo "<cell>" . $row->barcode ."</cell>"; 
            echo "<cell>" . $row->serial . "</cell>";
            echo "</row>";
            $i++;
        }
        echo "</rows>";      
        
    }   
}

class Spare_Crud extends Spare_Layout{

    function __construct(){

        parent::__construct();

    }

     protected function add(){ 
      //check гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!
        if($this->h_invoice_dtl->validate($this->h_invoice_dtl->validate)){                                   
            $data = $this->h_invoice->array_from_post(array('date'));            
            $data['createdby_id']=$this->session->userdata('employee_id');
            $data['createdby']=$this->session->userdata('fullname');
            $data['created_at'] =date('Y-m-d H:i:s');
            $data['type'] ='income';

            
            $id = $this->h_invoice->insert($data, TRUE);    
                        
            $this->Obj->db->trans_begin();                
          
            $this->h_invoice_dtl->insert_batch($this->get_values($id));    

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

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' ) 
           );
        }
        echo json_encode($return);
    }


    protected function use_qty(){ 

        //get spare_id 
        $spare_id = $this->input->get_post('spare_id');

        // get by spare in store in spare_id = 1        
        $store = $this->store->get_by('spare_id', $spare_id);

        
        if(isset($store)) unset($this->store->validate[2]);

        //print_r($this->store->validate);

        if($this->store->validate($this->store->validate)){                                   

            $data = $this->store->array_from_post(array('spare_id', 'date', 'using_qty'));            

            if(sizeof($store)){

                 if ($id = $this->store->update_by(array('spare_id' => $spare_id), $data)){          
                    $return = array (
                          'status' => 'success',
                          'message' => 'Мэдээллийг амжилттай шинэчиллээ' 
                    );               
                }else{
                    $return = array (
                        'status' => 'failed',
                          'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                     );  
                }   

            }else{

                $data['createdby']=$this->session->userdata('fullname');

                $data['created_at'] =date('Y-m-d H:i:s');


                if ($id = $this->store->insert($data, TRUE)){          
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

            }

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' ) 
           );
        }
        echo json_encode($return);
    }

    protected function need(){ 

        //get spare_id 
        $spare_id = $this->input->get_post('spare_id');

        // get by spare in store in spare_id = 1        
        $store = $this->store->get_by('spare_id', $spare_id);

        //Хэрэв тухайн need 

        unset($this->store->validate[3]);

        //print_r($store);

        //if(isset($store)) unset($this->store->validate[2]);


        if($this->store->validate($this->store->validate)){                                   

            $data = $this->store->array_from_post(array('spare_id', 'date', 'need_qty'));

            $data['createdby']=$this->session->userdata('fullname');

            $data['created_at'] =date('Y-m-d H:i:s');
                
            if(sizeof($store)){

                 if ($id = $this->store->update_by(array('spare_id' => $spare_id), $data)){   

                    $return = array (
                          'status' => 'success',
                          'message' => 'Амжилттай шинэчиллээ' 
                    );   

                 }else{

                    $return = array (
                        'status' => 'failed',
                          'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                     );  
                }

            }else{

                if ($id = $this->store->insert($data, TRUE)){          
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
               
            }
                                     

        }else{
           $return = array (
              'status' => 'failed',
              'message' => validation_errors ( '', '<br>' ) 
           );
        }
        echo json_encode($return);
    }

    protected function section_select(){
        $data = array ();
        $id =$this->input->get_post ( 'id' );    
        if($id)
          $data = $this->employee_model->in_drop_down('fullname', 'section_id', $id);
        else
          $data = $this->employee_model->dropdown('fullname');

        echo json_encode($data);
    }

    protected function delete(){     
        $id = $this->input->get_post ( 'id' );
        
        $detail = $this->h_invoice_dtl->get($id);        
        //print_r($detail);
        $count = $this->h_invoice_dtl->count_by('invoice_id', $detail->invoice_id);
        
        if($count==1){ //if its last row
             $this->h_invoice->delete($detail->invoice_id);
        }

        if($this->h_invoice_dtl->delete($id)){
          $return = array (
                'status' => 'success',
                'message' => $detail->spare.'-н тоо хэмжээнээс 1ш-г устгалаа'                
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
        $spare = $this->h_invoice->get($id);
        
         $return = array (
                'json' => $spare                
            );
       

        echo json_encode($return);
    }

}

class Spare_Module extends Spare_Crud{

    private $state = null;
    protected $table ='h_spare';
    protected $where;
    
    protected $is_ajax_request =FALSE;
    
    protected $page;
    protected $limit;
    protected $sidx;
    protected $sord;        
    
    protected $search ;    
    protected $filter ;
    
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
        $this->state = $CI->uri->segment ( 4 );                                
        if (! $this->state)
           $this->state = 'open';   
           
    }
    
    //хэрэв ajax baival yah uu?        
    // don't need status
    protected function get_status(){
        return $this->state;
    }

    //init run
    function run() {            
        // check state add, edit, delete
   
        $data = array ();
        $data ['view'] = true;    

        if($this->is_ajax_request){
            $this->set_echo_die();    
            header ( 'Content-type: application/json; charset=utf-8' );

            switch ($this->get_status()) {
                case 'add':                      
                    // echo "hi".$this->is_ajax_request;    
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

               case 'edit':
                     $this->edit();                    
                    break;

               case 'use':
                     $this->use_qty();                    
                    break;

               case 'need':
                     $this->need();                    
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
                        $data ['title'] = 'Хэрөлборыйдөйбы';                        
                        $this->index_form();                                                    
                        break;
               }  

        }    
        return $this->get_layout();
    }
}