<?php

class event extends CNS_Controller {

	public $role;

	public $section_id;

	public $employee_id;

	private $group;

	private $event_id;

	private $event;

	private $eventtype;

	public $equipment;

	public $objdata;

	public function __construct() {

		parent::__construct ();

		$this->load->model ( 'alert_model' );

		$this->load->model ( 'eventModel' );

		$objdata = ( object ) array ();

		if ($this->session->userdata ( 'role' )) {

			$this->role = $this->session->userdata ( 'role' );

			$this->employee_id = $this->session->userdata ( 'employee_id' );

			$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'event', $this->role, 0, 1 ) );

			$this->config->set_item ( 'module_menu', 'Техник үйлчилгээний бүртгэл' );

			$this->config->set_item ( 'module_menu_link', '/maintenance' );

			$this->config->set_item ( 'access_type', $this->session->userdata ( 'access_type' ) );

			$this->user_seccode = $this->session->userdata ( 'sec_code' );

			$this->table = $this->user_model->set_event_table ( $this->role, $this->user_seccode );

			$this->section_id = $this->session->userdata ( "section_id" );

			$this->group = $this->setGroup ( $this->user_seccode );
		}
	}

	function index() {

		$this->main_model->access_check ();

		$data ['createdby_id'] = $this->employee_id;

		$data ['group'] = $this->group;

		// $data['equipment']=$this->eventModel->roleComponent($this->role, 'equipment');

		// echo "role:" . $this->role;
		// echo "<br>";
		// echo $this->group;
		// echo "<br/>";
		// echo "section:" . $this->user_seccode;
		// echo "<br/>";
		// echo "section_id:" . $this->section_id;

		if ($this->group == 'ENG' || $this->group == 'ENG_CHIEF')

			$data ['equipment'] = $this->main_model->getEquipment ( $this->section_id );

		else

			$data ['equipment'] = $this->main_model->getEquipment ( NULL );

		$data ['location'] = $this->main_model->get_locations ();

		$data ['eventtype'] = $this->eventModel->getEventtype ();

		$data ['title'] = 'Техник үйлчилгээ';

		$data ['page'] = 'event\event';

		$this->load->view ( 'index', $data );
	}

	function setGroup($sec_code) {

		if ($sec_code == 'COM' || $sec_code == 'NAV' || $sec_code == 'ELC' || $sec_code == 'SUR') {

			switch ($this->role) {

				case 'ENG' :

					return 'ENG';

					break;

				case 'SUPENG' :

					return 'ENG';

					break;

				case 'UNITCHIEF' :

					return 'ENG';

					break;

				case 'TECH' :

					return 'ENG';

					break;

				case 'CHIEF' :

					return 'ENG_CHIEF';

					break;
			}

		} else {

			switch ($this->role) {

				case 'CHIEF' :

					return 'CHIEF';

					break;

				case 'SUPERVISOR' :

					return 'CHIEF';

					break;

				case 'ADMIN' :

					return 'CHIEF';

					break;

				default :

					return 'USER';

					break;
			}
		}
	}

	function setEvent($id) {

		$result = $this->db->query ( "SELECT section, equipment, eventtype, event FROM view_events WHERE id=$id" )->result ();

		foreach ( $result as $row ) {

			$this->equipment = $row->equipment;

			$this->eventtype = $row->eventtype;

			$this->event = $row->event;

		}

	}

	// Тухайн эвентийг авах
	function getevent() {

		$this->db->select ( '*' );

		$this->db->from ( 'view_events' );

		if ($this->group == 'ENG' || $this->group == 'ENG_CHIEF') {

			$this->db->where ( 'section_id', $this->section_id );

		}

		$this->db->order_by ( 'id', 'asc' );

		$equery = $this->db->get ();

		$data = $equery->result ();

		$equery->free_result ();

		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );

	}

	// нэгийг утгийг авах
	function getOne() {

		$id = $this->input->get_post ( 'id' );

		$equery = $this->db->query ( "select * from view_events where id = $id" );

		$row = $equery->row_array ();

		$equery->free_result ();

		if ($row) {

			$data ['equipment_id'] = $row ["equipment_id"];

			$data ['equipment'] = $row ["equipment"];

			$data ['event'] = $row ["event"];

			$data ['eventtype_id'] = $row ["eventtype_id"];

			$data ['location_id'] = $row ["location_id"];

			$data ['location'] = $row ["location"];

			$data ['done'] = $row ["done"];

			$data ['doneby_id'] = $row ["doneby_id"];

			$data ['activedby_id'] = $row ["activedby_id"];

			$data ['approvedby_id'] = $row ["approvedby_id"];

			$data ["createdby_id"] = $row ["createdby_id"];

			$data ["createdby"] = $row ["createdby"];

			$data ["doneby"] = $row ["doneby"];

			if (isset ( $row ['activedby_id'] ))

				$data ['active'] = true;

			else
				$data ['active'] = false;

			if (isset ( $row ['doneby_id'] ) && isset ( $row ['done'] ))

				$data ['isdone'] = true;

			else

				$data ['isdone'] = false;
		}

		$json_arr = $data;

		header ( 'Content-type: application/json;' );

		echo json_encode ( $json_arr );

	}

	function setValidation() {

		date_default_timezone_set ( 'Asia/Ulan_Bator' );

		$this->form_validation->set_rules ( 'equipment_id', 'Төхөөрөмж', 'required|callback_check_select' );

		$this->form_validation->set_rules ( 'location_id', 'Байршил', 'required|callback_check_select' );

		$this->form_validation->set_rules ( 'eventtype_id', 'Ү/a төрөл', 'required|callback_check_select' );

		$this->form_validation->set_rules ( 'event', 'Тодорхойлолт', 'required|min_length[5]|max_length[700]' );

		$this->form_validation->set_rules ( 'start', 'Эхлэх хугацаа', 'required|exact_length[16]' );

		$this->form_validation->set_rules ( 'end', 'Дуусах хугацаа', '
			required|exact_length[16]|callback_check_duration' );
	}

	function plan() {

		$data ['title'] = '2018 оны техник үйлчилгээний хуваарь';

		$data ['page'] = 'event\plan';

		$data['com_plan'] = $this->eventModel->get_row('filename', array('title'=>'2018 оны Техник үйлчилгээний хуваарь - Холбооны хэсэг'), 'files');

		$data['nav_plan'] = $this->eventModel->get_row('filename', array('title'=>'2018 оны Техник үйлчилгээний хуваарь - Навигацийн хэсэг'), 'files');

		$data['sur_plan'] = $this->eventModel->get_row('filename', array('title'=>'2018 оны Техник үйлчилгээний хуваарь - Ажиглалтын хэсэг'), 'files');

		$data['elc_plan'] = $this->eventModel->get_row('filename', array('title'=>'2018 оны Техник үйлчилгээний хуваарь - Гэрэл суулт, цахилгааны хэсэг'), 'files');

		$data ['link'] = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, voluptatem, beatae, doloremque, accusantium ducimus labore hic nostrum enim atque consequuntur assumenda voluptatum ipsam repellat! Quidem, unde ipsam doloribus veritatis reiciendis.";

		$this->load->view ( 'index', $data );

	}

	function norm() {

		$data ['title'] = 'Техник үйлчилгээний материалын орц норм';

		$data ['page'] = 'event\norm';

		$this->load->view ( 'index', $data );

	}

	function add() {

		$this->setValidation ();

		$start = $this->input->get_post ( 'start' );

		$end = $this->input->get_post ( 'end' );

		$duration = $this->getDuration ( $start, $end );

		$equipment_id = $this->input->get_post ( 'equipment_id' );

		$section_id = $this->main_model->get_row ( 'section_id', array (

				'equipment_id' => $equipment_id

		), 'equipment' );

		if ($this->form_validation->run () != FALSE) {

			$id = $this->main_model->get_maxId ( 'event', 'id' );

			$event = array (

					'id' => $id,

					'title' => $this->input->get_post ( 'event' ),

					'start' => $start,

					'end' => $end,

					'createdby_id' => $this->employee_id,

					'equipment_id' => $equipment_id,

					'section_id' => $section_id,

					'location_id' => $this->input->get_post ( 'location_id' ),

					'eventtype_id' => $this->input->get_post ( 'eventtype_id' ),

					'duration' => $duration,

					'createdDt' => date ( "Y-m-d H:i:s" ),

					'allDay' => $this->getAllDay ( $start, $end )

			);

			if ($add = $this->eventModel->add ( $event )) {

				$return = array (

						'status' => 'success',

						'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "]
						хугацаанд \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."
				);

				echo json_encode ( $return );

			} else {

				$return = array (
						'status' => 'failed',
						'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'
				);

				echo json_encode ( $return );
			}

		} else {

			$return = array (

					'status' => 'failed',

					'message' => validation_errors ( '', '<br>' )

			);

			echo json_encode ( $return );

		}

	}

	function edit() {

		$this->setValidation ();

		$id = $this->input->get_post ( 'eventId' );

		$start = $this->input->get_post ( 'start' );

		$end = $this->input->get_post ( 'end' );

		$duration = $this->getDuration ( $start, $end );

		if ($this->form_validation->run () != FALSE) {

			$data = array (

				'title' => $this->input->get_post ( 'event' ),

				'start' => $start,

				'end' => $end,

				'equipment_id' => $this->input->get_post ( 'equipment_id' ),

				'location_id' => $this->input->get_post ( 'location_id' ),

				'eventtype_id' => $this->input->get_post ( 'eventtype_id' ),

				'done' => $this->input->get_post ( 'done' ),

				'duration' => $duration,

				'allDay' => $this->getAllDay ( $start, $end )

			);

			if ($this->eventModel->update ( $id, $data )) {

				$return = array (

						'status' => 'success',

						'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."

				);

				echo json_encode ( $return );

			} else {

				$return = array (

						'status' => 'failed',

						'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

				);

				echo json_encode ( $return );

				echo $this->db->last_query ();
			}
		} else {
			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' )
			);
			echo json_encode ( $return );
			// return the error message
		}
	}

	function delete() {

		$id = $this->input->get_post ( 'eventId' );

		if ($this->eventModel->delete ( $id )) {

			$return = array (

					'status' => 'success',

					'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай устгалаа."

			);

			echo json_encode ( $return );

		} else {

			$return = array (

					'status' => 'failed',

					'message' => 'Зөвшөөрлийн өгөгдлийг хадгалах үед өгөгдлийн санд алдаа гарлаа! #21'

			);

			echo json_encode ( $return );
		}
	}

	function check_select($post_string) {

		if ($post_string == '0')

			$this->form_validation->set_message ( 'check_select', ' %s-с нэг сонголт хийх шаардлагатай.' );

		return $post_string == '0' ? FALSE : TRUE;

	}

	function check_duration($end) {

		$start = $this->input->get_post ( 'start' );

		if ($this->getDuration ( $start, $end ) > 0) {

			return TRUE;

		} else {

			$this->form_validation->set_message ( 'check_duration', ' %s хугацаа нь эхлэх хугацаанаас их байх шаардлагатай.' );

			return FALSE;

		}

	}

	// Done hiih
	function done() {

		$title = $this->input->get_post ( 'event' );

		$start = $this->input->get_post ( 'start' );

		$end = $this->input->get_post ( 'end' );

		$data ['location_id'] = $this->input->get_post ( 'location_id' );

		$data ['equipment_id'] = $this->input->get_post ( 'equipment_id' );

		$data ['eventtype_id'] = $this->input->get_post ( 'eventtype_id' );

		$data ['done'] = $this->input->get_post ( 'done' );

		$data ['doneby_id'] = $this->employee_id;

		$duration = $this->getDuration ( $start, $end );

		$data ['duration'] = $duration;

		$data ['allDay'] = $this->getAllDay ( $start, $end );

		$eventId = $this->input->get_post ( 'eventId' );

		$this->db->where ( 'id', $eventId );

		$this->db->update ( 'event', $data );

		if ($this->db->affected_rows ()) {

			$return = array (

					'status' => 'success',

					'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."
			);

			echo json_encode ( $return );

		} else {

			$return = array (

					'status' => 'failed',

					'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

			);

			echo json_encode ( $return );

		}
	}


	function move() {

		$id = $this->input->get_post ( 'id' );

		$start = $this->input->get_post ( 'start' );

		$end = $this->input->get_post ( 'end' );

		$action = $this->input->get_post ( 'action' );

		$this->setEvent ( $id );

		$data ['start'] = $start;

		$data ['end'] = $end;

		$duration = $this->getDuration ( $start, $end );

		$data ['duration'] = $duration;

		$data ['allDay'] = $this->getAllDay ( $start, $end );

		if ($action == 'move')

			$msg = 'зөөлөө';

		else {

			$msg = 'сунгалаа';

		}

		switch ($this->group) {

			case 'ENG' :

				$return = array (
						'status' => 'failed',
						'message' => 'Энэ үйлдэл хийгдсэнгүй, Хэсгийн дарга болон дээш албан тушаалтанд боломжтой!'
				);

				echo json_encode ( $return );

				break;

			// Hesgiin darga зөөх үйлдэл зөвхөн тухайн хэсгийнх бол зөөнө
			case 'CHIEF' :

				if ($this->role == 'CHIEF') {

					$section_id = $this->main_model->get_row ( "section_id", array (
							'id' => $id
					), 'view_events' );

					if ($this->section_id == $section_id) { // өөрийн хэсгийн Event байна.

					    // update хийж болно.
						if ($this->eventModel->update ( $id, $data )) {

							$return = array (

									'status' => 'success',

									'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->eventtype . "\" -г хугацааг амжилттай $msg."

							);

							echo json_encode ( $return );

						} else {

							$return = array (

									'status' => 'failed',

									'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

							);

							echo json_encode ( $return );

						}

					} else {

						$return = array (

								'status' => 'failed',

								'message' => 'Энэ тус хэсгийн үйл ажиллагаа биш тул зөвшөөрөхгүй.'

						);

						echo json_encode ( $return );

					}

				} else { // CHIEF ENG, SUPERVISOR BVAL

					if ($this->eventModel->update ( $id, $data )) {

						$return = array (

								'status' => 'success',

								'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->eventtype . "\" -г хугацааг амжилттай $msg."
						);

						echo json_encode ( $return );

					} else {

						$return = array (
								'status' => 'failed',

								'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

						);

						echo json_encode ( $return );
					}
				}

				break;

			case 'ENG_CHIEF' :

				if ($this->role == 'CHIEF') {

					$section_id = $this->main_model->get_row ( "section_id", array (
							'id' => $id
					), 'view_events' );

					if ($this->section_id == $section_id) { // өөрийн хэсгийн Event байна.

					    // update хийж болно.
						if ($this->eventModel->update ( $id, $data )) {

							$return = array (

									'status' => 'success',

									'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->eventtype . "\" -г хугацааг амжилттай $msg."

							);

							echo json_encode ( $return );

						} else {

							$return = array (

									'status' => 'failed',

									'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

							);

							echo json_encode ( $return );

						}

					} else {

						$return = array (

								'status' => 'failed',

								'message' => 'Энэ тус хэсгийн үйл ажиллагаа биш тул зөвшөөрөхгүй.'

						);

						echo json_encode ( $return );

					}

				} else { // CHIEF ENG, SUPERVISOR BVAL

					if ($this->eventModel->update ( $id, $data )) {

						$return = array (

								'status' => 'success',

								'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->eventtype . "\" -г хугацааг амжилттай $msg."

						);

						echo json_encode ( $return );

					} else {

						$return = array (

								'status' => 'failed',

								'message' => 'Өгөгдлийн хадгалахад алдаа гарлаа'

						);

						echo json_encode ( $return );
					}

				}

				break;

			// Chief bval бүгдийг зөөнө
			default :

				$return = array (
						'status' => 'failed',
						'message' => "Энэ үйлдэл хийгдсэнгүй, Хэсгийн дарга болон дээш албан тушаалтанд боломжтой!"
				);

				echo json_encode ( $return );

				break;
		}

	}

	function authorize() {

		// check validation
		$this->setValidation ();

		$id = $this->input->get_post ( 'eventId' );

		if ($this->form_validation->run () != FALSE) {

			if ($this->role != 'CHIEF') {

				if ($this->eventModel->authorize ( $id ) > 0) {

					$return = array (

							'status' => 'success',

							'message' => "[" . $this->input->post ( 'start' ) . "]-[" . $this->input->post ( 'end' ) . "] хийгдэх \"" . $this->input->post ( 'event' ) . "\" -г амжилттай хадгаллаа."
					);

					echo json_encode ( $return );

				} elseif ($this->eventModel->authorize ( $id ) == 0) {

					$return = array (

							'status' => 'failed',

							'message' => 'Аль хэдийн зөвшөөрсөн байна! Дахиж зөвшөөрөх хэрэггүй!'

					);

					echo json_encode ( $return );

				} else {

					$return = array (
							'status' => 'failed',
							'message' => 'Зөвшөөрлийн өгөгдлийг хадгалах үед өгөгдлийн санд алдаа гарлаа! #21'
					);

					echo json_encode ( $return );

				}

			} else {

				$return = array (
						'status' => 'failed',
						'message' => "Хэсгийн даргаас дээш албан тушаалтан зөвшөөрөх үйлдэл боломжтой."
				);

				echo json_encode ( $return );

			}

		} else {

			$return = array (
					'status' => 'failed',
					'message' => validation_errors ( '', '<br>' )
			);

			echo json_encode ( $return );

		}

	} // end authorized

	private function getDuration($start, $end) {

		$start_dt = strtotime ( $start ); // 2012-04-01

		$end_dt = strtotime ( $end ); // 2012-04-17

		return $end_dt - $start_dt;

	}

	private function getAllDay($start, $end) {

		// Нэг өдөр 4-с дээш цагаар ажиллавал All-day болно

		$start_day = date ( 'Y-m-d', strtotime ( $start ) );

		$end_day = date ( 'Y-m-d', strtotime ( $end ) );

		if ($end_day - $start_day == 0) { // neg udur

			if (strtotime ( $end ) - strtotime ( $start ) > 14400 && strtotime ( $end ) - strtotime ( $start ) < 86340) {

				return 't';

			}

		} else

			return 'f';

	}

	function help() {

		$data ['title'] = 'Техник үйлчилгээ';

		$this->load->view ( 'help\event', $data );

	}

	function get_columns() {

		$columns = array ();

		$cols = array (
				'eventtype',
				'section',
				'start',
				'end',
				'title',
				'event',
				'done',
				'equipment',
				'location',
				'createdby',
				'doneby'
		);

		$display_as = array (
				'eventtype' => 'Төрөл',
				'section' => 'Хэсэг',
				'start' => 'Эхэлсэн',
				'end' => 'Дууссан',
				'title' => 'Гарчиг',
				'event' => 'Техник үйлчилгээ',
				'done' => 'Гүйцэтгэл',
				'equipment' => 'Төхөөрөмж',
				'location' => 'Байршил',
				'createdby' => 'Үүсгэсэн',
				'doneby' => 'Дуусгасан'
		);

		foreach ( $cols as $col_num => $column ) {

			$columns [$col_num] = ( object ) array (

					'field_name' => $column,

					'display_as' => $display_as [$column]

			);
		}

		return $columns;
	}

	function get_list() {

		$this->db->select ( '*' );

		$this->db->from ( 'view_events' );

		if ($this->group == 'ENG') {

			$this->db->where ( 'section_id', $this->section_id );

		}

		// $this->db->order_by('section', 'asc');
		$this->db->order_by ( 'section asc, start desc, end desc' );

		return $this->db->get ()->result ();

	}

	function exportToExcel() {

		$objdata->columns = $this->get_columns ();

		$objdata->list = $this->get_list ();

		$this->_export_to_excel ( $objdata );
	}

	function _export_to_excel($objdata) {

		$string_to_export = "";

		// print_r($objdata);
		// get columns
		foreach ( $objdata->columns as $column ) {

			$string_to_export .= $column->display_as . "\t";
		}

		$string_to_export .= "\n";

		// get lists
		foreach ( $objdata->list as $num_row => $row ) {

			foreach ( $objdata->columns as $column ) {

				$string_to_export .= $this->_trim_export_string ( $row->{$column->field_name} ) . "\t";
			}

			$string_to_export .= "\n";
		}

		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" . mb_convert_encoding ( $string_to_export, 'UTF-16LE', 'UTF-8' );
		 // var_dump($string_to_export);

		$filename = "export-" . date ( "Y-m-d_H:i:s" ) . ".xls";

		header ( 'Content-type: application/vnd.ms-excel;charset=UTF-16LE' );

		header ( 'Content-Disposition: attachment; filename=' . $filename );

		header ( "Cache-Control: no-cache" );

		echo $string_to_export;

		die ();
	}

	function _trim_export_string($value) {

		$value = str_replace ( array (
				"&nbsp;",
				"&amp;",
				"&gt;",
				"&lt;"
		), array (
				" ",
				"&",
				">",
				"<"
		), $value );

		return strip_tags ( str_replace ( array (
				"\t",
				"\n",
				"\r"
		), "", $value ) );
	}

	function page($id) {

		$this->main_model->access_check ();

		$data ['event_data'] = $this->main_model->get_values ( 'view_events', 'id', $id );

		$created_employee_id = $this->main_model->get_row ( 'createdby_id', array (
				'id' => $id
		), 'event' );

		$activatedby_id = $this->main_model->get_row ( 'activatedby_id', array (
				'id' => $id
		), 'event' );

		$data ['event_id'] = $id;
		// Үүсгэсэн хүний ажлын утас, дугаар, албан тушаал авна.

		$cemp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $created_employee_id );

		foreach ( $cemp_cols as $row ) {

			$data ['cr_workphone'] = $row->workphone;

			$data ['cr_position'] = $row->position;

			$data ['cr_fullname'] = $row->fullname;

			$data ['cr_sector'] = $row->section_sector;
		}

		$act_emp_cols = $this->main_model->get_values ( 'view_employee', 'employee_id', $activated_employee_id );

		foreach ( $act_emp_cols as $row ) {

			$data ['act_workphone'] = $row->workphone;

			$data ['act_position'] = $row->position;

			$data ['act_fullname'] = $row->fullname;
		}

		// $data['employee_cols']=$this->main_model->get_values('view_employee', 'employee_id', $employee_id);
		$this->load->view ( 'event/page', $data );
	}
}
?>
