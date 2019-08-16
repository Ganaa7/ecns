<?php

/*
 * @Ganaa developer
 * 2017-08-15
 * its part of ECNS
 */
require_once('Core.php');

class Wh_Loader extends Core{
    
    protected $wm_model;

    protected $wh_spare;
    
    protected $invoice_dtl;             

    function __construct() {

      parent::__construct();
     
        $this->Obj->load->model ( 'wh_spare_model' );

        $this->wh_spare = new wh_spare_model();     

        $this->Obj->load->model ( 'wm_model' );

        $this->wm_model = new wm_model();  

        $this->Obj->load->model ( 'wh_invoice_dtl' );

        $this->invoice_dtl = new wh_invoice_dtl();        
    }            
 
}

class Wh_Layout extends Wh_Loader{

    private $echo_die = false;

    public function __construct()
    {
        parent::__construct();

        parent::set_file('grid.php');

        parent::set_location('assets/warehouse/settings/');
        
    }
    
    // here is all database and logics
    //load index form 
    protected function index_form(){       

        $data['action']=$this->wh_spare->get_action();

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
                
        // herev filter hiisen bval where should be set
        $this->check_filter();
              
        // нийт тоо 
        $this->set_count($this->wh_spare);                
        
        $this->set_total_page();

        $this->set_page();

        $this->set_start();

        $json=$rows= array();  

        $json['page']=$this->page;
        
        $json['total']=$this->total_pages;

        $json['records']=$this->count;     

        if($this->section_id<5){
           if($this->where){                    
               $this->where .= " and wh_spare.section_id = $this->section_id";    
           }else
              $this->where =" wh_spare.section_id = $this->section_id";    
        }

        $rows = $this->wh_spare->get_query($this->where, $this->sidx, $this->sord, $this->start, $this->limit);

        $json['sql']=$this->wh_spare->last_query();   

        $json['where']=$this->section_id;

        $json['rows']=$rows;
        
        echo json_encode($json);                
    }  
        
}

class Wh_Crud extends Wh_Layout {

   protected function add(){ 
      //check гарсан газраас эргэж ирсэн эсэхийг мэдэх хэрэгтэй байна!    
               
        if($this->wh_spare->validate($this->wh_spare->validate)){

            $data = $this->wh_spare->array_from_post(array('spare', 'equipment_id', 'part_number','section_id', 'sector_id', 'type_id', 'measure_id', 'manufacture_id'));

            $this->Obj->db->trans_begin();    
            
            $id=$this->wh_spare->insert($data);            

            if ($this->Obj->db->trans_status() === FALSE){
               $this->Obj->db->trans_rollback();
               $return = array (
                    'status' => 'failed',
                      'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                 );  
            }else{
                $this->Obj->db->trans_commit();
                $spare= $this->wh_spare->get($id);

                $return = array (
                      'status' => 'success',
                      'spare_id'=>$spare->id,
                      'message' => $spare->spare.' сэлбэгийг амжилттай хадгаллаа' 
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

    // filter function heree    
    protected function section_select(){
        $data = array ();
        $id =$this->input->get_post ( 'id' );
      //  print_r($id);
        //echo "<br>";
        // herev ids bhgui bol yu ch selegee hiihgui         
       // echo $ids;
       // $data = $this->employee->with_drop_down('fullname', array('section_id'=>$id));
        if($id)
          $data = $this->employee->in_drop_down('fullname', 'section_id', $id);
        else
          $data = $this->employee->dropdown('fullname');

        echo json_encode($data);
    }

    protected function delete(){     
        $id = $this->input->get_post ( 'spare_id' );
        $spare= $this->wh_spare->get($id);

        if($this->wh_spare->check_spare($id)){
          if($this->wh_spare->delete($id)){
              $return = array (
                  'status' => 'success',
                  'message' => $spare->spare.' сэлбэгийг амжилттай устгалаа' 
              );
            }else
                $return = array (
                    'status' => 'failed',
                    'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                );
        }else
            $return = array (
                    'status' => 'failed',
                    'message' => 'Энэ сэлбэг дээр орлого/зарлага бүртгэгдсэн тул устгах боломжгүй!' 
                );
        echo json_encode($return);
    }

    protected function get(){     
        $spare_id = $this->input->get_post ( 'spare_id' );
        
        $spare = $this->wh_spare->get($spare_id);

         $return = array (
                'json' => $spare                
            );
       

        echo json_encode($return);
    }

    protected function get_equipment(){   

        $sp_id = $this->input->get_post ( 'equipment_id' );
        
        $equipment = $this->equipment->get_by('sp_id', $sp_id);

        echo $this->wh_spare->last_query();

        //$row = $this->article_model->get_by('title', 'Fuzzy Wuzzy');

         $return = array (
                'json' => $equipment                
            );       

        echo json_encode($return);
    }

    //get all section 
    protected function get_sector(){   
        $data = array ();

        // if($id)
        //   $data = $this->equipment->dropdown_by('equipment', 'equipment_id', array('section_id'=>$id) );
        // else
        //   $data = $this->equipment->dropdown('equipment');

        // echo json_encode($data);

        if($this->input->get_post('target') =='sector'){

          $section_id = $this->input->get_post ( 'id' );
        
          // $data = $this->sector->get_many_by('section_id', $section_id);
          
          $data = $this->sector->dropdown_by('name', 'sector_id', array('section_id'=>$section_id) );
          
          $data['Тасгийг сонго']=0;

        } else if($this->input->get_post('target') =='equipment'){

          $sector_id = $this->input->get_post ( 'id' );
          
          $section_id = $this->input->get_post ( 'parent_id' );
        
          // $data = $this->sector->get_many_by('section_id', $section_id);

          $data = $this->equipment->dropdown_by('equipment', 'sp_id', 
          
          array('sector_id'=>$sector_id, 'section_id'=>$section_id) );
                    
                    // echo $this->equipment->last_query();
        }

        asort($data);
        
        //$row = $this->article_model->get_by('title', 'Fuzzy Wuzzy');

        echo json_encode($data);
    }

    protected function edit(){     

       $id = $this->input->get_post ( 'spare_id' );
       
        //get olld trip id 
       $spare = $this->wh_spare->get($id);
        // check if old is new same        
        
       $data = $this->wh_spare->array_from_post(array('spare', 'equipment_id', 'part_number',  'section_id', 'sector_id', 'type_id', 'measure_id', 'manufacture_id'));
       

       $this->wh_spare->validate[0]['rules'] = 'required';       

       //barcode == "y" and barcode =="n"
       //if barcode = Y is herev

       $status = $this->input->get_post('status');

       //хэрэв баркодыг соль       
       if($status&&$status=='barcode'){

          //change barcode of rest files in change barcode here      
         if($this->wh_spare->update($id, array('section_id'=>$data['section_id'], 'sector_id'=>$data['sector_id'], 
          'equipment_id'=>$data['equipment_id'], 'measure_id'=>$data['measure_id'], 'part_number'=>$data['part_number'], 'manufacture_id'=>$data['manufacture_id']))){

             $barcode = $this->get_barcode($data['sector_id'], $data['equipment_id']);

              //үлдэгдэл дээр тухайн бар кодыг update хийх болно!
              $qry = "SELECT *
                  FROM wh_invoice A
                  JOIN (SELECT * FROM wh_invoice_dtl WHERE serial_x not in 
                      (SELECT serial_x FROM wh_invoice_dtl where aqty =-1 and spare_id = $id) and spare_id =  $id) B ON A.id =B.invoice_id 
                  left join wm_view_pallet C ON b.pallet_id= C.pallet_id
                  WHERE invoicedate <=curdate()";

              $query = $this->wh_spare->exec_query($qry);

              // query num rows
              if ( $query->num_rows () > 0 ){  

                $i = 0;

                foreach ( $query->result () as $row ){

                   //loop and update barcode with new barcode
                   $new_code = $this->wm_model->gen_barcode($data['type_id'], $barcode, $i++);

                   $this->invoice_dtl->update_by(array('id'=>$row->id), array('barcode'=>$new_code ));                
                   
                }

              }
              
               $msg = array (
                  
                  'status' => 'success',

                  'message' => 'Бүх үлдэгдлүүдэд бар кодыг өглөө', 

                  'data' =>$barcode
              );             
             
         }else{
            $msg = array (
                  
                'status' => 'failed',

                'message' => 'Өгөгдлийн санд алдаа гарлаа'

            );


         }

       }else{

          if($this->wh_spare->validate($data)){         

            if($this->wh_spare->check_spare($id)){   //if not in wh_spare -d bhgui bol 

               $this->Obj->db->trans_begin();    
               $id = $this->wh_spare->update($id, $data);             
               if ($this->Obj->db->trans_status() === FALSE){
                  $this->Obj->db->trans_rollback();
                  $msg = array (
                       'status' => 'failed',
                         'message' => 'Өгөгдлийн санд хадгалахад алдаа гарлаа' 
                    );  
               }else{

                   $this->Obj->db->trans_commit();

                   $msg = array (
                       'status' => 'success',
                       'message' => '№'.$data['spare'].' сэлбэгийг амжилттай засварлалаа'                    
                   );    
               } 

            }else{ //herev ter wh_invoice-d baigaa bol!
                // $equiopment, $section, $sector_id солих 

                //$data дотороосоо авна Section_id-г, sector_id =
                if(($spare->section_id != $data['section_id'])||($spare->sector_id != $data['sector_id'])||($spare->equipment_id != $data['equipment_id']) || ($spare->type_id != $data['type_id'])){

                  // үлдэгдлийг засварлах
                  
                  //Barcode-г засварлах
                  $msg = array ('status' =>'barcode', 'message' => 'Үлдэгдэлд байгаа бүх баркодыг өөрчлөх шаардлагатай болж байна!');

                   
                }else{ //хэрэв баркод өөрчлөх шаарлдагаггүй бол 

                    //shuud update hiih heregtei
                    $id = $this->wh_spare->update($id, array('measure_id'=>$data['measure_id'], 'part_number'=>$data['part_number'], 'manufacture_id'=>$data['manufacture_id']));  

                    $msg = array (
                       'status' => 'success',
                       'message' => 'Ашиглалтад буй тоо/ш-г өөрчиллөө! Бусад утгуудыг засах боломжгүй учир нь орлого/рлага бүртгэгдсэн байна!' 
                    );
                }                    

            }  

          }else{
              $msg = array (
              'status' => 'failed',
                  'message' => validation_errors ( '', '<br>' ), 
                  'data' =>$data
              );
          }

       }       

        echo json_encode($msg);
    }

    protected function get_barcode($sector_id, $equipment_id){

       // $spare_id = $this->input->get_post ( 'spare_id' );
       
       // $spare = $this->wh_spare->get($spare_id);

       //max_id = from wm_model
       $max_id = $this->wm_model->get_max_barcode( $sector_id, $equipment_id);

       $head = "0" . $sector_id;

       // echo "barcode".$spare_id[$i];
       $mid = sprintf ( '%03d', $equipment_id );
        
       // $bar_end = sprintf('%06d', $spare_id[$i]);
       $barcode = $head . '-' . $mid . '-' . sprintf ( '%06d', $max_id);

       //get wh_spare үлдэгдэлийг авна
       // $qty = $this->wm_model->get_end_qty($spare_id);
       // $return = array (
       //        'json' => $spare                
       //    );       
       return  $barcode;

    }

}

class Wh_Settings_Module extends Wh_Crud{
   
    private $state = null;
    
    protected $is_ajax_request =FALSE;
        
    // grid data 
    function __construct() {

        parent::__construct();
      
        $this->set_status_url();                
    }
    
    //status-g url-s avah!
    private function set_status_url() {
       
        if($this->Obj->input->is_ajax_request()){
            
            $this->is_ajax_request = TRUE;            
        }

        $this->state = $this->Obj->uri->segment ( 3 );                        

        if (! $this->state)  $this->state = 'open';        
    
           
    }
    
    // don't need status
    protected function get_status(){
        return $this->state;
    }

    //check ajax            
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

               case 'delete':
                    $this->delete();
                    break;

               case 'get':
                    $this->get();
                    break;     

              case 'get_by':
                    $this->get_sector();
                    break;        
  
               case 'edit':
                     $this->edit();                    
                    break;        

               case 'get_barcode':
                     $this->get_barcode();                    
                    break;


                default:
                    # code...
                    break;
            }
        }else{
            switch ($this->get_status ()) {               
                default : // index page loaded
                        //$data ['page'] = 'trip\index';
                        $data ['title'] = 'test';   
                        $this->index_form();                                                    
                        break;
               }  

        }    
        return $this->get_layout();
    }
}