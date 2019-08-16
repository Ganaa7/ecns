<?php

/*
 * This model used in Alert in Shiftlog system
 */
class alert extends CNS_Controller {
	private $now_dt;

	public function __construct() {

		parent::__construct ();
		
		$this->load->model ( 'alert_model' );

		$this->load->model ( 'main_model' );

		$this->load->model ( 'my_model_old' );
		
		date_default_timezone_set ( ECNS_TIMEZONE );
		
		$this->now_dt = date ( 'Y-m-d H:i:s' );
		
		$this->load->library('PHPMailer/PHPMailer');

		$this->load->library('PHPMailer/Exception');

	}

	private function get_certificate($section_id) {
		//! date between 14-30 хоногтой сунгах шаардлагатай тоног төхөөрөмжүүд
		//2 date between 45 хоногийн доторх төхөөрөмжүүд рүү имйэл илгээнэ.

		$sql = "SELECT *, DATEDIFF(validdate, DATE_FORMAT(NOW(),'%Y-%m-%d')) as diff 
                FROM view_certificate 
                WHERE DATEDIFF(validdate, DATE_FORMAT(NOW(),'%Y-%m-%d'))<= 45 and DATEDIFF(validdate, DATE_FORMAT(NOW(),'%Y-%m-%d')) >0 
                AND section_id = $section_id";
		
		$sql2 = "SELECT *, DATEDIFF(validdate, DATE_FORMAT(NOW(),'%Y-%m-%d')) as diff 
                FROM view_certificate 
                  WHERE DATEDIFF(validdate, DATE_FORMAT(NOW(),'%Y-%m-%d'))<= 0 
                  AND section_id = $section_id";
		
		$result = $this->my_model_old->get_as_query ( $sql );
		$res_tf = $this->my_model_old->get_as_query ( $sql2 );
		$title = 'Гэрчилгээний хугацаа.';
		$cnt = 0;
		$cnt1 = 0;
		$body = '';
		$td_body = '';
		
		foreach ( $result->result() as $row ) {
			$body .= $row->location . ' байршилд <strong>' . $row->equipment . '</strong> т/т-ын <strong>[' . $row->cert_no . ']</strong> дугаартай гэрчилгээ <strong>' . $row->validdate . '</strong> нд </br>';
			$cnt ++;
		}
		// get result time finish
		foreach ( $res_tf->result() as $row ) {
			$td_body .= $row->location . ' байршилд <strong>' . $row->equipment . '</strong> т/т-ын <strong>[' . $row->cert_no . ']</strong> дугаартай гэрчилгээ <strong>' . $row->validdate . '</strong> нд </br>';
			$cnt1 ++;
		}


		if($result->num_rows()>0){
			if ($cnt > 1)
				$body .= ' дуусах бөгөөд гэрчилгээнүүдийг хугацаа дуусах дөхсөн байна.';
			else
				$body .= ' дуусах бөгөөд гэрчилээний хугацаа дуусах дөхсөн байна.';						
		}else 
		   $body = '';
			//return $body . $td_body . '<br> 24 цаг тутам автоматаар илгээх болно. <br> Цахим систем.';
		
		if($res_tf->num_rows()){
			if ($cnt1 > 1)
				$td_body .= ' тус тус гэрчилгээний хугацаа дууссан байна. Яаралтай арга хэмжээ авна уу!';
			else
				$td_body .= ' гэрчилгээний хугацаа дууссан байна. Яаралтай арга хэмжээ авна уу!';						
		}

		if(strlen($body)||strlen($td_body))		
		  return $body.'<br><br>' . $td_body . '<br> 24 цаг тутам автоматаар илгээх болно. <br> Цахим систем.';
		else return null;
	}

	private function check_time_diff() {
		$query = $this->my_model_old->get_as_query ( "SELECT * FROM emailer where status = 'new' ORDER BY updated desc LIMIT 0 , 1" );
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				$updated = $row->updated;
			}
		}
		// get emails as binder
		$updated_time = strtotime ( $updated );
		
		$current_time = strtotime ( $this->now_dt );
		
		return round ( ($current_time - $updated_time) / 3600 );
	}

	function set_email($app) {
		if ($app == 'shiftlog') {
			$data = array (
					'shiftlog' => 'Y' 
			);
			$this->db->where_in ( 'id', $_POST ['id'] );
			$result = $this->db->update ( 'email_conf', $data );
			
			$data = array (
					'shiftlog' => 'N' 
			);
			$this->db->where_not_in ( 'id', $_POST ['id'] );
			$result2 = $this->db->update ( 'email_conf', $data );
			
			if ($result) {
				$this->session->set_userdata ( 'message', 'Гэмтэлийн сэрэмжлүүлэх Имэйл-ийн тохиргоог амжилттай хадгаллаа.' );
				redirect ();
			}
		}
	}

	function email() {
		$this->main_model->access_check ();
		if ($this->session->userdata ( 'user_type' ) == 'govern') {
			$data ['result'] = $this->db->query ( "SELECT id, role, section_name, position, shiftlog
            FROM view_email_conf" )->result ();
		} else {
			$sec_code = $this->session->userdata ( 'sec_code' );
			$data ['result'] = $this->db->query ( "SELECT id, role, section_name, position, shiftlog
                FROM view_email_conf 
                WHERE sec_code IN ($sec_code, ENG, GOV)" )->result ();
		}
		$data ['title'] = 'Имэйл тохиргоо';
		$this->load->view ( 'email.php', $data );
	}

	function notify() {
		$user_id = $this->session->userdata ( 'employee_id' );
		$mysql = "SELECT count(id) as cnt FROM notify A 
                  WHERE updated > (SELECT updatetime FROM user_status
                      WHERE user_id = $user_id
                    order by updatetime desc limit 1)";
		
		$result = $this->my_model_old->get_query_as_sql ( $mysql );
		foreach ( $result as $row ) {
			$count = $row->cnt;
		}
		if ($count) {
			$return = array (
					'status' => 'success',
					'count' => $count 
			);
		} else {
			$return = array (
					'status' => 'failed',
					'count' => $count 
			);
		}
		echo json_encode ( $return );
	}
	//@todo: 2017-03-14
	// Албаны дарга, Ерөнхий инж, Чанар, Инженерингийн Тех инж
	// Хэсгийн дарга, 
	// Тасгийн ахлагч нарт имэйлээр гэрчилгээний хугацааг илгээх хэрэгтэй ба
	function emailer() {

		if ($this->input->is_ajax_request () && uri_string () == 'alert/emailer') {
			// check token value
			echo $this->session->userdata('token');
			if ($this->input->get_post ( 'token', TRUE ) == $this->session->userdata ( 'token' )) {
				// check email last updated difference hour t>24 байвал				
				if ($this->check_time_diff () > 24) {
					//get result of certificate
					$this->my_model_old->set_table('section');					
					$result = $this->my_model_old->get_result(array('type' =>'industry'));

					//foreach section 
					foreach ($result as $row) {
						# code...
						// certificate by section _id test
						$cert_body = $this->get_certificate ($row->section_id);					
						
						//herev cert_body utgatai bval bugd uru uyavuulna
						//echo $cert_body;
						if($cert_body){
							// get quality Eng emails
							// get чанарын инженерүүдэд явуул! Хэсгийн дарга нарт явуул
							$this->my_model_old->set_table ( 'view_employee' );					
							//section_id emails
							// $section_emails = $this->my_model_old->get_as_column_new( 'email', array ('section_id'=>$row->section_id, 'role'=>'UNITCHIEF', 'role'=>'CHIEF'), ',' );

							$section_emails = $this->my_model_old->get_as_column ( 'email', 'role', array ('UNITCHIEF', 'CHIEF'), array('section_id' =>$row->section_id), ',');

							$head_emails = $this->my_model_old->get_as_column ( 'email', 'role', array (
									'QENG',						
							 		'CHIEFENG',						 		
							 		'HEADMAN' 
							), null, ',' );

							$tech_chief = $this->my_model_old->get_as_column ( 'email', 'section_id', array (7),
								array('role' =>'CHIEF'), ',' );
							
							$this->my_model_old->set_table ( 'employee' );	
							
							$tech_emails = $this->my_model_old->get_as_column ( 'email', 'section_id', array (7), array('def_role' =>$row->section_id), ',' );

							echo $section_emails;
													
							$emails=$section_emails.','.$head_emails.','.$tech_chief.','.$tech_emails;
							//$emails = 'gandavaa.d@mcaa.gov.mn';

							switch ($row->section_id) {
								case 1:
									$title ='Холбооны хэсгийн төхөөрөмжийн гэрчилгээний хугацаа';
									break;
								case 2:
									$title ='Навигацийн хэсгийн төхөөрөмжийн гэрчилгээний хугацаа';
									break;
								case 3:
									$title ='Ажиглалтын хэсгийн төхөөрөмжийн гэрчилгээний хугацаа';
									break;
								case 4:
									$title ='Гэрэл суулт, цахилгааны хэсгийн төхөөрөмжийн гэрчилгээний хугацаа';
									break;																
							}
							echo $emails;
							//echo "<br>";
							// print_r($emails);
							// холбооны хэсгийн имэйл
							// Навигацийн хэсгийн имэйл
							// Ажиглалтын хэсгийн имэйл
							// ГСЦ хэсгийн имэйл							
							//echo $this->my_model_old->last_query();
							
							// имэйл явуулах фүнкц
							if ($this->alert_model->email ( $emails, $title, $cert_body, 'html' )) {
								// here update tables
								$data = array (
										'title' => 'Гэрчилгээ хугацаа',
										'body' => $emails,
										'status' => 'new',
										'group' => 'all',
										'updated' => $this->now_dt 
								);
								$this->my_model_old->set_table ( "emailer" );
								if ($this->my_model_old->insert ( $data )) {
									$return = array (
											'status' => 'success',
											'message' => 'Мессеж has been sent:' . $this->now_dt 
									);
								} else {
									$return = array (
											'status' => 'failed',
											'message' => 'Couldnot insert data' 
									);
								}
							}else
							   $return = array (
										'status' => 'failed',
										'message' => 'Could not sent message' 
								);


						}
					}
					
				} else
					$return = array (
							'status' => 'success',
							'message' => 'nt:' . $this->now_dt 
					);
				echo json_encode ( $return );
			} else
				echo 'are you jokking me! you: ' . $this->input->ip_address () . ' is suspected!';
		} else
			echo 'are you kidding me! your ip ' . $this->input->ip_address () . ' is suspected!';
	}

	function notified() {
		$user_id = $this->session->userdata ( 'employee_id' );
		$mydata = array (
				'user_id' => $user_id,
				'status' => 'seen',
				'updatetime' => $this->now_dt 
		);
		
		$this->my_model_old->set_table ( 'user_status' );
		if ($this->my_model_old->insert ( $mydata )) {
			$return = array (
					'status' => 'success' 
			);
		} else
			$return = array (
					'status' => 'success' 
			);
		echo json_encode ( $return );
	}

	function test() {
		$this->my_model_old->set_table ( 'view_employee' );
		$emails = $this->my_model_old->get_as_column ( 'email', 'role', array (
				'QENG',
				'CHIEF',
				'CHIEFENG' 
		), ',' );
		var_dump ( $emails );
	}

	//Чанарын Аюулгүй А ИТА нар луу имэйл явуулах
	function quality($log_num){		
		$this->my_model_old->set_table ( 'employee' );		
		//$emails = 'gandavaa.d@mcaa.gov.mn';
		if($log_num){			
			$code = substr($log_num, 0, 1);
			$emails = $this->my_model_old->get_as_column_new ( 'email', array ('section_id'=>5, 'def_role'=>$code), ',' );
			// echo $this->my_model_old->last_query();			
			$head = 'eCNS системд аюулийг шинжилж эрдслийг тогтоох ['.$log_num. '] гэмтэл бүртгэгдлээ';
			$body = 'eCNS системд аюулийг шинжилж эрдслийг тогтоох ['.$log_num. '] гэмтэл бүртгэгдлээ! <br> Аюулийг шинжилж эрдлийг тогтооно уу! <br> Аюулийг шинжлэх гэмтэл [ТОД ШАР] өнгөтэй харагдахыг анхаарна уу! <br> Гэмтлийн аюулийг шинжилж тогтоосны дараа гэмтэл бүрэн хаагдахыг анхаарна уу!
			</div>';

			if ($this->alert_model->email ( $emails, $head, $body, 'html' )) {
				// here update tables
				$data = array (
						'title' => 'Гэрчилгээ хугацаа',
						'body' => $emails.' '.$body,
						'status' => 'quality',
						'group' => 'qeng',
						'updated' => $this->now_dt 
				);
				$this->my_model_old->set_table ( "emailer" );
				if ($this->my_model_old->insert ( $data )) {
					$return = true;
				} else {
					$return = false;
				}
			} else
				$return =false;
		}
		
		return $return;
	}

	function test_email(){
		if($this->alert_model->email ('mgluser3d@gmail.com', 'Its Test email', 'Hello new mail its test email from server'.$this->input->ip_address (), 'html' ))
                    echo "bbb has been sent ";
		else 
                    echo "its not worked man!";
		echo "IP:". $this->input->ip_address ();
	}

	function sent_mail_test(){
		echo mail('gandavaa.d@mcaa.gov.mn','Works!','An email has been generated from your localhost, congratulations!');
	}

	function sender(){

		$this->load->library('phpmailer_lib');
        
        // PHPMailer object
		$mail = $this->phpmailer_lib->load();

		$to = "bilguun@mcaa.gov.mn";

		$subject = "Test email from ganaa";

		$body = "Hariu bichne uu ?";

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.mcaa.gov.mn';                     // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'ans_admin@mcaa.gov.mn';           // SMTP username
			$mail->Password   = 'eCn$email2019';                            // SMTP password
			$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       =  25;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom('ans_admin@mcaa.gov.mn', 'Ecns emailer');

			$mail->addAddress($to);                                     // Name is optional
			
			// Content
			$mail->isHTML(true);                                        // Set email format to HTML

			$mail->Subject = $subject;

			$mail->Body    = $body;

			$mail->send();

			echo 'Message has been sent';

		} catch (Exception $e) {

			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

		}


	}

	

}