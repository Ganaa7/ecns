<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
  class log_model extends CI_Model {
      public $sec_code;
      static public $table;
      public $equipment_id;
      private $log;
      private $start_dt;
      private $end_dt;      
      public $fsec_code; 
      private $offset; 

    function __construct(){
        // Call the Model constructor
        $this->load->database();        
        $this->load->model('main_model');        
        $this->load->model('user_model');        
        $this->load->library('session');
        $this->load->library('config');         
        
        $this->sec_code= $this->session->userdata('sec_code');        
        $this->table =$this->user_model->set_user_table($this->role, $this->sec_code);   
        
    }
    function check_offset($sec_code, $equipment_id, $offset){
        if(($sec_code!=$this->fsec_code)||($equipment_id!=$this->equipment_id)){
           $this->offset=0;
           $this->unset_value('equipment_id');
           $this->unset_value('log');
           $this->unset_value('start_date');
        }else
           $this->offset=$offset;        
        return;
    }
            
    function unset_value($value){
       switch($value){
          case 'fsec_code':
             $this->session->unset_userdata('fsec_code');
             $this->fsec_code=null;
          break;
          case 'equipment_id':
             $this->session->unset_userdata('fequipment_id');
             $this->equipment_id=null;
          break;
          case 'log':
             $this->session->unset_userdata('log');
             $this->log=null;  
          break;
          default:
             $this->start_dt=null;
              $this->end_dt=null;  
             $this->session->unset_userdata('start_date');
             $this->session->unset_userdata('end_date');             
          break;
       }        
    }    
    
    function get_value($value){
        switch ($value){
            case 'equipment':
                $ret_vale =$this->equipment_id;
                break;
            case 'start_date':
                $ret_vale=$this->start_dt;
                break;
            case 'end_date':
                $ret_vale=$this->end_dt;
                break;  
            case 'log':
                $ret_vale=$this->log;
                break;  
        }
        return $ret_vale;
    }
    
    function set_filter($sec_code, $equipment_id, $log, $filter, $start_date, $end_date){        
        if($filter){
           if($sec_code){
               $this->session->set_userdata('fsec_code', $sec_code);
               $this->fsec_code=$sec_code;
           }
           if(isset($equipment_id)){           
             $this->session->set_userdata('fequipment_id', $equipment_id);
             $this->equipment_id=$equipment_id;
           }
           if($log){
               $this->session->set_userdata('log', $log);
               $this->log=$log;  
           }
           if($start_date||$end_date){
              $this->session->set_userdata('start_date', $start_date);
              $this->session->set_userdata('end_date', $end_date);
              $this->start_dt=$start_date;
              $this->end_dt=$end_date;              
           }else{
               $this->unset_value('start_date');
           }
        }else{
            if($this->session->userdata('fequipment_id'))
               $this->equipment_id=$this->session->userdata('fequipment_id');
            if($this->session->userdata('log'))
               $this->log=$this->session->userdata('log');
            if($this->session->userdata('fsec_code')) 
                  $this->fsec_code=$this->session->userdata('fsec_code');
            else
              $this->fsec_code=null;    
            
            if($this->session->userdata('start_date')||$this->session->userdata('end_date')){
               $this->start_dt =$this->session->userdata('start_date');
               $this->end_dt =$this->session->userdata('end_date');
            }       
        }        
    }
    // function filter the query;
    function filter(){    
       if($this->equipment_id)
          $this->db->where('equipment_id', $this->equipment_id);
       if($this->log)
          $this->db->where('closed', $this->log); 
       if($this->start_dt){
           $between ="((DATE_FORMAT(closed_datetime, '%Y-%m-%d') >= '$this->start_dt' AND DATE_FORMAT(closed_datetime, '%Y-%m-%d') <='$this->end_dt')
               OR (DATE_FORMAT(created_datetime, '%Y-%m-%d') >= '$this->start_dt' AND DATE_FORMAT(created_datetime, '%Y-%m-%d') <='$this->end_dt'))";
           $this->db->where($between);                 
       }   
    }    
    
    // Хэрэв ордер хийгдсэн бол тухайн ордероор quer-г ажилуулна
    function get_logs($table, $limit, $sort_by, $sort_order){
        
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
                                              
        if($this->config->item('access_type')=='ADMIN'){
            $sort_columns = array('q_level', 'sec_code', 'log_num', 'created_datetime', 'location', 'equipment', 'defect',
                'closed_datetime', 'duration_time', 'completion', 'createdby', 'closedby', 'filename' ); //'reason', 
            $ret['fields']= array(
                'q_level'=>'Зэрэглэл',
                'sec_code' => 'Хэсэг', 
                'log_num' => 'Гэмтэл №', 
                'created_datetime' =>'Нээсэн хугацаа', 
                'location' =>'Байршил', 
                'equipment'=>'Төхөөрөмж', 
                'defect'=>'Гэмтэл', 
//                'reason'=>'Шалтгаан',      
                // 'process'=>'Засварын явц',                 
                'closed_datetime'=>'Хаасан хугацаа',
                'duration_time'=>'Үргэлж/цаг', 
                'completion'=>'Гүйцэтгэл',                
//                'createdby'=>'Нээсэн ИТА', 
//                'closedby'=>'Хаасан ИТА',         
                'provedby'=>'Танилцсан ЕЗИ',         
                'filename'=>'Хавсралт файл'
                
            );
        }else{
            $sort_columns = array('q_level', 'log_num', 'created_datetime', 'location', 'equipment', 'defect', 
                'closed_datetime', 'duration_time', 'completion', 'createdby', 'closedby', 'provedby' ); //'reason', 
            $ret['fields']= array(              
                'q_level'=>'Зэрэглэл',
                'log_num' => 'Гэмтэл №', 
                'created_datetime' =>'Нээсэн хугацаа', 
                'location' =>'Байршил', 
                'equipment'=>'Төхөөрөмж', 
                'defect'=>'Гэмтэл',                 
//                'reason'=>'Шалтгаан', 
                // 'process'=>'Засварын явц',                 
                'closed_datetime'=>'Хаасан хугацаа',
                'duration_time'=>'Үргэлж/цаг', 
                'completion'=>'Гүйцэтгэл', 
//              'createdby'=>'Нээсэн ИТА', 
//              'closedby'=>'Хаасан ИТА',
                'provedby'=>'Танилцсан ЕЗИ',
                'filename'=>'Хавсралт файл'
                
            );
        }
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'created_datetime';        
        // results query
        $this->db->select('*');     
        $this->db->from($table);                
        $this->filter();              
        $this->db->limit($limit, $this->offset); 
        $this->db->order_by($sort_by, $sort_order);   
        $query = $this->db->get();        
        $ret['last_query']=$this->db->last_query();        
        /* $query =$this->db->query("SELECT log_id, sec_code, log_num, created_datetime, location, equipment, reason, closed_datetime,
           duration_time, completion, createdby, closedby FROM $table order by $sort_by $sort_order limit $offset, $limit");
        */
        $ret['result']=$query->result();   
        $query->free_result();
        //$ret['num_rows']=$query->num_rows();
        return $ret;
     }

     // create log by username
    function createLog(){
         //$max_logid=$this->getlastId($_POST['equipment_id'])+1;
       if($this->sec_code){
          $data = array(            
             'created_datetime' =>$_POST['created_datetime'],
             'location_id'=>$_POST['location_id'],
             'createdby_id'=>$this->session->userdata('employee_id'),
             'equipment_id'=>$_POST['equipment_id'],
             'sec_code'=>$this->session->userdata('sec_code'),
             'section_id'=>$this->session->userdata('section_id'),
             'reason'=>$_POST['reason'],
             'defect'=>$_POST['defect'],
             'closed'=>'N'
            );
            
         $result = $this->db->insert('log', $data);            
         return $result;
         }
      }
      
    function get_max($table, $field){
         $this->db->select_max($field);
         $query = $this->db->get($table);
         $row=$query->row();
         return $row->$field; // call attributes
      }
      
    function get_logNum($equipment_id){
       $qry_max_log = $this->db->query("SELECT SUBSTRING(log_num, 6, length(log_num)) max_log_num
                                           FROM log A WHERE A.equipment_id = $equipment_id
                                            ORDER BY max_log_num+0 DESC LIMIT 1;");
       $max_log=$qry_max_log->row()->max_log_num;
       
       if(isset($max_log)){
          $max_log++;
       }else
           $max_log =$this->main_model->get_row('max_log_num', array('equipment_id'=>$equipment_id), 'equipment');
           
       $sql_join = "SELECT concat(substring(B.sec_code, 1, 1), '-', b.code) as log_code
                           FROM equipment B WHERE B.equipment_id = $equipment_id";
           
       $code_head = $this->db->query($sql_join)->row()->log_code;
              
       return $code_head.$max_log;// call attributes                          
    }
      // $id -r шүүж логийн дугаарыг олно
    function get_close($log_id){
         $table=$this->table;         
         $this->db->select('log_id, log_num, created_datetime, createdby, createdby_position, closedby_lname, closedby_position, defect,
            equipment, location, reason'); 
         $this->db->where('log_id', $log_id);          
         $query=$this->db->get($table);   
         $result =$query->result();
         $query->free_result();
         return $result;
    }
      
      // total count of logs
//    function get_total($view){
//         $query = $this->db->query("SELECT count(log_id) as total FROM $view");
//         $row=$query->row();
//         return $row->total;          
//    }
      
      //Log haagdahad hereglegdene
    function mod_check_datetime(){
        // strtotime($_POST['created_datetime']);
         $cdate= $_POST['created_datetime'];
         $fdate= $_POST['closed_datetime'];
         $created_datetime= strtotime($cdate); //2012-04-01
         $closed_datetime = strtotime($fdate); //2012-04-17
         // closed datetime ni created datetime-s ih bol 
         // tuhain fielduudeer update hiine
         // herev
         if($closed_datetime > $created_datetime)
            return TRUE;      
         else return FALSE;      
    }
      
      // Log close
    function mod_close_log(){
         // update by log_id
         // closeby, closed_datetime, reaction, employee_id            
         $employee_id=$this->session->userdata('employee_id');
         $open= $_POST['created_datetime'];
         $closed= $_POST['closed_datetime'];
         $completion= $_POST['completion'];
         $reason= $_POST['reason'];
         $created_dt= strtotime($open); //2012-04-01
         $closed_dt = strtotime($closed); //2012-04-17
         $diff_dt = $closed_dt-$created_dt;
         
         
         $data = array(                
               'closedby_id' => $employee_id,
               'closed_datetime' => $_POST['closed_datetime'],               
               'duration_time'=> ($diff_dt),
               'completion'=> $completion,
               'reason'=> $reason
            );
         $log_id =$_POST['log_id'];
         $this->db->where('log_id', $log_id);
         $result=$this->db->update('log', $data);
         date_default_timezone_set(ECNS_TIMEZONE);
         $now =date("Y-m-d H:i:s", time());
         $ins_data=array(
             'log_id'=>$log_id,
             'process'=>'Хэвийн явагдсан',
             'datetime'=>$now,
             'addedby_id'=>$this->session->userdata('employee_id'),
             'transmit'=>'N'             
         );
         $this->db->insert('log_process', $ins_data);
         return $result; 
    }
            
    //Funciton get select * from where id
    function get_values($table, $where, $value){
         $this->db->where($where, $value); 
         $query = $this->db->get($table);
         return $query->result();
     }
     
    //Update log
    function update_log(){
        //Хэрэв тухайн гэмтлийн төхөөрөмжийг сольбол тухайн гэмтлийн дугаарыг хамгийн max дугаараар авна.
        $old_equipid=$this->main_model->get_row('equipment_id', array('log_id'=>$_POST['log_id']), 'log');
        if($old_equipid<>$_POST['equipment_id']){
            $log_num =$this->log_model->get_logNum($_POST['equipment_id']);
        }else
            $log_num = $_POST['log_num'];
            
         if(!isset($_POST['closed_datetime'])){
            $data = array(
               'log_id' => $_POST['log_id'],
               'log_num' => $log_num,
               'createdby_id' => $_POST['createdby_id'],
               'created_datetime'=>$_POST['created_datetime'],
               'location_id'=>$_POST['location_id'],
               'equipment_id'=>$_POST['equipment_id'],
               'reason'=>$_POST['reason'],
               'defect'=>$_POST['defect']
            );
         }else{            
            $data = array(
               'log_id' => $_POST['log_id'],
               'log_num' =>$log_num,
               'createdby_id' => $_POST['createdby_id'],
               'created_datetime'=>$_POST['created_datetime'],
               'location_id'=>$_POST['location_id'],
               'equipment_id'=>$_POST['equipment_id'],
               'reason'=>$_POST['reason'],
               'defect'=>$_POST['defect'],
               'completion'=>$_POST['completion'],
               'closedby_id' => $_POST['closedby_id'],
               'closed_datetime' => $_POST['closed_datetime'],
               'duration_time'=> $this->log_model->set_duration($_POST['created_datetime'], $_POST['closed_datetime'])
            );
         }
         $log_id =$_POST['log_id'];
         $this->db->where('log_id', $log_id);
         $result=$this->db->update('log', $data);
         
         return $result;
      }
      
    //Delete log
    function delete_log($log_id, $employee_id = NULL){
         if(isset($employee_id)){   
           $this->db->where('createdby_id', $employee_id);
           $this->db->where('log_id', $log_id);                
           $this->db->delete('log'); 
        }else{
           $result=$this->db->where('log_id', $log_id);         
           $this->db->delete('log');
        }
            return $this->db->affected_rows();
       }
      
    // Set duration
    function set_duration($open, $closed){
         $created_dt= strtotime($open); //2012-04-01
         $closed_dt = strtotime($closed); //2012-04-17
         return $diff_dt = $closed_dt-$created_dt;
      }
      
    function get_query_result($sql){
         $query =$this->db->query($sql);
         return $query->result();          
      }
      
    function time_diff($log_id){
       $table=$this->table;
       date_default_timezone_set('Asia/Ulan_Bator');
       $today= date("Y-m-d H:i:s");        
       $created_dt= $this->main_model->get_row('created_datetime', array('log_id'=>$log_id), $table);
       return ceil((strtotime($today)-strtotime($created_dt))/( 60 * 60 ));
    }
    
    function check_log($log_id){
        $wh_data = array('log_id'=>$log_id);
        $log_num=$this->main_model->get_row('log_num', $wh_data, 'log');
        if(is_null($log_num))
           return FALSE;
        else 
           return TRUE;
    }


 }
?>
