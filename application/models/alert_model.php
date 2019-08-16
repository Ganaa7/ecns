<?php
class alert_model extends CI_Model {
	private $now;
	function __construct() {
		// Call the Model constructor
		$this->load->database ();

		date_default_timezone_set ( ECNS_TIMEZONE );

		$this->now = date ( 'Y-m-d H:i:s' );

	}

	function mail_send($to, $subject, $body){

		$mail = new PHPMailer(true);

		// $to = $_POST['email'];

		// $subject = "ECNS email";

		// $body = $_POST['body'];

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.mcaa.gov.mn';                     // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = '*******';           // SMTP username
			$mail->Password   = '*******';                            // SMTP password
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

	function email($to, $subject, $body, $mailtype = null) {
		$config = Array (

				'smtp_host' => SMTPSERVER,
				'smtp_port' => SMTPPORT,
				'smtp_user' => SMTPUSER,
				'smtp_pass' => SMTPPASSWORD,
				'mailtype' => $mailtype,
				'starttls' => true,
            	'smtp_crypto' =>'security'
		);
		
		$this->load->library ( 'email', $config );
		
		$this->email->set_newline ( "\r\n" );
		$this->email->from ( 'mgluser3@gmail.com', 'eCNS-Tуслах' );
		$this->email->to ( $to );
		$this->email->bcc ( 'ans_admin@mcaa.gov.mn' );
		$this->email->subject ( $subject );
		$this->email->message ( $body );

		echo $this->email->print_debugger();
		
		if ($this->email->send ()) {
			return true;
		} else {
			return false;
		}
		
	}

	function set_notify($app, $group, $group_section, $msg) {
		$data = array (
				'app' => $app,
				'group' => $group,
				'group_sections' => $group_section,
				'msg' => $msg,
				'updated' => $this->now 
		);
		
		$this->db->insert ( 'notify', $data );
		if ($this->db->affected_rows ()) {
			return true;
		} else
			return false;
	}

	public function quality(){
		// quality хэсгийн ИТА нарын имэйлийг авна
		//тэгээд явуулна
	}
	public function sent_log($log_id, $status) {
		// get user emails
		// status = OPENED, CLOSED, PROCESS
		$emails = array ();
		$this->db->select ( 'email' );
		$result_email = $this->db->get ( 'view_shiftlog_email_conf' )->result ();
		
		foreach ( $result_email as $row ) {
			$emails ['email'] = $row->email;
		}
		$result = $this->main_model->get_values ( 'view_logs', 'log_id', $log_id );
		if ($status == 'open') {
			foreach ( $result as $row ) {
				$log_num = $row->log_num;
				$created_datetime = $row->created_datetime;
				$location = $row->location;
				$equipment = $row->equipment;
				$defect = $row->defect;
				$createdby = $row->createdby;
				$section = $row->section;
				$activatedby = $row->activatedby;
			}
			$body = "$created_datetime $section хэсгийн $equipment төхөөрөмж дээр $defect гэмтэл гарлаа.\n 
        Тус $equipment төхөөрөмж дээр $log_num дугаартай гэмтлийн бүртгэл нээлээ.
        \n Нээсэн ИТА: $createdby\n Мэдээлсэн ЕЗИ:$activatedby";
			$this->alert_model->email ( 'ecns@mcaa.gov.mn', 'Ecns Alert', $emails, 'Гэмтэл гарлаа', $body );
		}
		if ($status == 'close') {
			foreach ( $result as $row ) {
				$log_num = $row->log_num;
				$created_datetime = $row->closed_datetime;
				$location = $row->location;
				$equipment = $row->equipment;
				$completion = $row->completion;
				$createdby = $row->closedby;
				$section = $row->section;
				$provedby = $row->provedby;
			}
			$body = "$section хэсгийн $equipment төхөөрөмж дээр гарсан гэмтлийг $completion.\n 
            $equipment $log_num дугаартай гэмтлийн бүртгэлийг хаалаа.\n 
            Хаасан ИТА: $createdby\n Мэдээлсэн ЕЗИ:$provedby";
			$this->alert_model->email ( 'ecns@mcaa.gov.mn', 'Ecns Alert', $emails, 'Гэмтлийг заслаа', $body );
		}
		
		// if($status =='create'){
		// $emails=array();
		// $this->db->select('email');
		// $this->db->get('view_employee');
		// $res_role=$this->db->where('role', 'SUPERVISOR');
		// $final_res=$res_role->result();
		//
		// foreach($final_res as $row){
		// $emails['email']=$row->email;
		// }
		// foreach($result as $row){
		// $created_datetime=$row->created_datetime;
		// $location=$row->location;
		// $equipment=$row->equipment;
		// $completion=$row->defect;
		// $createdby=$row->createdby;
		// $section=$row->section;
		// }
		// $body = "$section хэсгийн $equipment төхөөрөмж дээр гэмтэл гарсныг тогтоолоо.\n
		// Гэмтлийн дугаарыг идэвхижүүлж өгнө үү!.\n
		// Гэмтэл нээсэн ИТА: $createdby\n";
		// $this->alert_model->email('ecns@mcaa.gov.mn','Ecns Alert', $emails, 'Гэмтлийг заслаа', $body);
		// }
	}


}
?>
