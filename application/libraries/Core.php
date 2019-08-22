<?php

class Core{

     protected $section;

     protected $equipment;

     protected $employee;

     protected $location;
     
     protected $sector;

     protected $session;

     protected $Obj;

     protected $input;

     protected $wh_spare;
    
     protected $ftree;
     
     protected $where;

     // view used variables
     protected $library;

     private $theme_location;

     private $filename; 

     protected $views_as_string;

  
   	function __construct() {


        $CI = &get_instance ();

        $CI->load->helper('form');

        $CI->load->helper('url');

        $this->Obj=$CI;

        $CI->load->model ( 'Section_model' );

        $this->section = new Section_model ();

        $CI->load->model ( 'Equipment_model' );

        $this->equipment = new Equipment_model (); 

        $CI->load->model ( 'Employee_model' );

        $this->employee = new Employee_model ();

        $CI->load->model ( 'sector_model' );

        $this->sector = new sector_model ();

        $this->input = $CI->input;

        $CI->load->library('session');

        $this->session=$CI->session;

        $CI->load->model ( 'wh_spare_model' );

        $this->wh_spare = new wh_spare_model();

        $CI->load->model ( 'tree_model' );

        $this->ftree = new tree_model();

        // library used variables

        $CI->load->model ( 'library_model');

        $this->library = new library_model();
        
        $this->section_id = $CI->session->userdata('section_id');

        $CI->load->model ( 'location_model' );

        $this->location = new location_model ();  
    
     }

     protected function init_grid(){
         
         $this->page = $this->input->get_post('page');
         
         $this->limit = $this->input->get_post('rows');
         
         $this->sidx = $this->input->get_post('sidx');
         
         $this->sord = $this->input->get_post('sord');
         
         $this->filters = $this->input->get_post('filters');
        
         $this->search = $this->input->get_post('_search');

         $this->limit = 20;
     }

       // set page here
       protected function set_page(){
          if($this->page > $this->total_pages)
          $this->page=$this->total_pages;
       }

       //grid total pages here
       protected function set_total_page() {
           if( $this->count > 0 )
              $this->total_pages =  ceil($this->count/$this->limit);
           else $this->total_pages = 0;
       }

       //herev filter hiigdsen bol
       protected function set_start() {
           $this->start =$this->limit*$this->page - $this->limit;
     	  if($this->start <0)
     	    $this->start = 0;
         }


     protected function check_filter(){

         if(($this->search=='true')&&($this->filters != "")){

            /// echo $this->filter($this->filters);

            return $this->filter($this->filters);

         }else
           return 'null';
     }

      // filter can removed
      protected function filter($filters) {

         $db = get_instance()->db->conn_id;

         // echo "filtering";

         $filters = json_decode ( $filters );

         // var_dump($filters);

         $where = " where ";

         $whereArray = array ();

         $rules = $filters->rules;

         $groupOperation = $filters->groupOp;

         foreach ( $rules as $rule ) {

            // var_dump($rule);

             $fieldName = $rule->field;

             $fieldData = mysqli_real_escape_string ($db, $rule->data );

             switch ($rule->op) {
                 case "eq" :
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

      protected function set_count($dependency){

         if(strlen($this->where)>0){

            $count = $dependency->get_count($this->where);

            // $count= $this->equipment->count_all($this->where);

        }else{

           $count= $dependency->count_all();

        }

      //  echo $this->equipment->last_query();

        // echo $count;

        if($count > 0){

           $this->count=$count;

        } else $this->count=0;
    }


    protected function theme_view($vars = array(), $return =FALSE) {
            
            $vars = (is_object ( $vars )) ? get_object_vars ( $vars ) : $vars;

            $file_exists = FALSE;

            $ext = pathinfo ( $this->filename, PATHINFO_EXTENSION );
            
            $file = ($ext == '') ? $this->filename . '.php' : $this->filename;

            $view_file = $this->theme_location;

            if (file_exists ( $view_file . $file )) {
                    $path = $view_file . $file;
                    $file_exists = TRUE;
            }

            if (! $file_exists) {
                    throw new Exception ( 'Unable to load the requested file: ' . $this->filename, 16 );
            }

            extract ( $vars );

            // region buffering...
            ob_start ();

            include ($path);

            $buffer = ob_get_contents ();
            @ob_end_clean ();
            // endregion

            if ($return === TRUE) {
                return $buffer;
            }

           $this->views_as_string .= $buffer;
    }

    public function set_location($path){

        $this->theme_location = $path;
    }

    protected function set_file($file){

        $this->filename = $file;
    }

    public function get_location(){

        return $this->theme_location;
    }

    protected function key_gen($len) {
        
        $alphabet = "abcdefghijklmnopqrstuwxyz01234567890";

        $key = array (); // remember to declare $pass as an array

        $alphaLength = strlen ( $alphabet ) - 1; // put the length -1 in cache

        for($i = 0; $i < $len; $i ++) {

           $n = rand ( 0, $alphaLength );

           $key [] = $alphabet [$n];
        }
        
        return implode ( $key ); // turn the array into a string
    }

    protected function check_section(){

        if($this->section_id<5){
        
           if($this->where)
        
              $this->where .= " and section.section_id = $this->section_id";
        
           else
        
              $this->where =" section.section_id = $this->section_id";
        }
    }

 }
