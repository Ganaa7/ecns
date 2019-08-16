<?php

class User extends CNS_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->model ( 'user_m' );

        $this->load->model ( 'alert_model' );
        
        $this->load->model ( 'position_model' );

        $this->load->helper('cookie');

        if($this->role){

            $this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'user', $this->role, 0, 1 ));

            $this->config->set_item ( 'module_menu', 'Системийн хэрэглэгчид' );

            $this->config->set_item ( 'module_menu_link', '/ecns/user');

        }

    }

    function index(){

        $employee = new Employee_Module();

        $this->config->set_item ( 'module_script', $this->javascript->external ( base_url().'assets/apps/employee/js/employee.js', TRUE ));

        $this->data['equipment_OBJ'] = $employee->run ();

        $this->data['section']=$this->section_model->dropdown_by('section_id', 'name');

        $this->data['sector']=$this->sector_model->dropdown_by('sector_id', 'name');
        
        $this->data['position']=$this->position_model->dropdown_by('position_id', 'name');
        
        $this->data['title'] = "ИТА бүртгэл";

        $this->data['page']= 'employee/index';

        $this->load->view("index", $this->data );

    }

    function login(){
    	
    	$home = base_url();

    	$this->user_m->loggedin() == FALSE || redirect($home);
    	
    	$rules = $this->user_m->rules;

    	$this->form_validation->set_rules($rules);

    	if ($this->form_validation->run() == TRUE) {             
    		// We can login and redirect
    		if ($this->user_m->login() == TRUE) {
                
                if ( $this->input->get_post('remember_me') ) {

                       $remember_me= array(
                           'name'   => 'remember2',
                           'value'  => 'test', 
                           'expire' => (86400 * 7), 
                           'secure' => TRUE
                       );

                    $this->input->set_cookie($remember_me);

                    setcookie ( "username", $this->input->get_post('username'), time () + (86400 * 7) );

                    setcookie ( "password", $this->input->get_post('password'), time () + (86400 * 7) );

                    setcookie ( "remember_me", $_POST ['remember_me'], time () + (86400 * 7) );

                }

    			redirect($home);
    		}
    		else {
    			$this->session->set_flashdata('error', 'Уучлаарай, Таны нэвтрэх нэр эсвэл нууц үг буруу байна!');
    			
                redirect('user/login', 'refresh');
    		}
    	}

        $this->data['page'] = 'user/login';

        $this->load->view('index', $this->data);
    }

    public function logout(){

    	$this->user_m->logout();

    	redirect(base_url().'user/login');
    }

    function forgot() {

        $data ['title'] = 'Нууц үгээ мартсан';

        $this->data['page'] = 'user/forgot';

        $this->load->view ( 'index', $this->data );
        
    }

    function sent_forgot() {

        $user_name = $this->input->get_post('email');

        $email = $this->input->get_post('email').'@mcaa.gov.mn';
        // get user_email by user 
                //validatioan here
        $this->form_validation->set_rules('email', 'Нэвтрэх нэр', 'required');


        if($this->form_validation->run()==TRUE){

            //update recovery code for user_id 
            $email_user = $this->user_m->get_by('email', $email);

            print_r($user_name);        
     
            if($email_user){
                // then generate link for this user 
                $code =  $this->user_m->generator(7, TRUE);

                // password recovery here 

                $this->user_m->update($email_user->employee_id, array('recovery_code' =>$code));

                // update user_code for generated email sented for his/her email???
                $id  = $this->user_m->update_by(array('email'=>$email), array('recovery_code'=>$code, 'recovery_at'=>date("Y-m-d H:i:s")));

                
                $recovery_link = "\n\n"."<a href='".base_url()."user/pass_recovery?code=$code&id=$email_user->employee_id'><strong>Энд дарж шинэ нууц үгээ оруул!</strong></a>";

                // send email link to user 
                $body = "Сайн байна уу?  $email_user->fullname\n <br> Та нууц үгээ доорх холбоос дээр дарж шинэ нууц үгээ оруулна уу!\n  <br>".$recovery_link;

                
                $body = $body . " \n\n <br>Та системд нэвтэрсний дараа Тохиргоо->Хувийн цэс уруу орж солих боломжтой.";

                $body = $body . " \n <br> Хүндэтгэсэн:" . FROM_NAME;

                IF ($this->alert_model->email ( $email, REQUEST_RENEW_PASS, $body, 'html' )) {     

                    $this->data ['error'] = "Таны имэйл хаяг уруу шинэ нууц үгийг илгээлээ. \n Та имэйлээ шалгаад зааврын дагуу нууц үгээ шинэчлэнэ үү!\n";

                } else
                    
                    $this->data ['error'] = "Таны имэйл илгээхэд алдаа гарлаа. \n Та ерөнхий инженер болон системийн Админтай холбогдоно уу! \n Ерөний инженерийн эрхээр ажилчдын нууц үгийг өөрчлөх боломжтой \n 
                        <a href ='user/login'>";

                $this->data['page'] = 'user/result'; 

                $this->load->view('index', $this->data);

            }else{
                              
               $this->data['error']=  '"'.(string)$email.'" нэртэй хэрэглэгч бүртгэгдээгүй байна! та нэвтрэх нэрээ шалгаад дахин оролдоно уу!';

               $this->load->view ( 'forgot', $this->data );

               //redirect('/user/forgot','refresh');
                // echo 'hast errror';
            }

        }else{

            $this->load->view ( 'forgot' );
            // redirect('/user/forgot','refresh'); 

        }
           

    }

    function pass_recovery(){
        
        $code = $this->input->get_post('code');

        $id = $this->input->get_post('id');

        // check the user recvoery code is valid 
        $user = $this->user_m->get($id);

        //this user has valid recovery code
        if($user->recovery_code==$code){

            //show the  page that recovery pass code generate
            
            //load view recovey
            $this->data['username'] = $user->username;

            $this->data['page'] = 'user/recovery';

            $this->load->view('index', $this->data);


        }else{//its not recovery code 
            $this->data['error']=  '"'.(string)$user->email.'" таны илгээсэн код буруу байгаа тул дахин оролдоно уу!';
             
        }   

    }

    function reset(){

        $username = $this->input->get_post('username');

        $this->data['username'] = $username;

        $password = $this->input->get_post('password');

        $re_password = $this->input->get_post('re_password');

        //user sent new password && confirm password
       // print_r($username);
        
        $this->form_validation->set_rules('username', 'Нэвтрэх нэр', 'required');
        $this->form_validation->set_rules('password', 'Нууц үг', 'min_length[5]|required');

        $this->form_validation->set_rules('passconf', 'Нууц үг давтах', 'required|min_length[5]|matches[password]');

        $this->form_validation->set_message('min_length', '%s утгын урт доод тал нь 5 тэмдэгтийн уртта байх ёстой!');
        $this->form_validation->set_message('matches', '%s утга нь [Нууц үг]-ын оруулсан үгтэй таарахгүй байна! Ижилхэн нууц үг оруулна уу!');

        if ($this->form_validation->run() == FALSE){

           $this->data['page'] = 'user/recovery';
           $this->load->view('index', $this->data);
            
        }else{
            // if is true then store new password to database by generated hash by pass user
            $email = $username.'@mcaa.gov.mn';

            $this->user_m->update_by(array('email'=>$email), array('password'=>$this->user_m->hash_2($password)));
            
            $this->data['error']=$email." таны нууц үг амжилттай шинэчлэгдлээ!";

            $this->data['page'] = 'user/success';

            $this->load->view('index', $this->data);
        }


    }
}