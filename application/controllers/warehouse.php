<?
/*
 * 2012-09-18
 * This file used for warehouse systems
 */
if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class warehouse extends CNS_Controller {

	public $error = array ();

	public static $section_id;

	public static $back_page;

	function __construct() {

		parent::__construct ();

		$this->load->helper ( 'navigation' );

		$this->load->helper ( 'cookie' );

		$this->load->model ( 'wm_main' );
		
		$this->main_model->access_check ();
		
		$this->config->set_item ( 'user_menu', $this->user_model->display_menu ( 'warehouse', $this->role, 0, 1 ) );

		$this->session->unset_userdata ( 'home' );

		$this->config->set_item ( 'module_menu', 'Сэлбэг хангамжийн бүртгэл' );

		$this->config->set_item ( 'module_menu_link', '/ecns/warehouse' );

		$this->config->set_item ( 'javascript_location', '/ecns/assets/js' );
		
	}

	function index() {
		
		$this->main_model->access_check ();

		$this->main_model->check_byrole ( 'warehouse', $this->role );

		$this->wm_main->check_curpage ( 'index' );

		$data ['library_src'] = $this->javascript->external (base_url().'assets/js/warehouse/grid.min.js', TRUE );

		$data ['section_id'] = $this->wm_main->section_id;

		$data ['sector_id'] = $this->wm_main->sector_id;

		$data ['equipment_id'] = $this->wm_main->equipment_id;

		$data ['sparetype_id'] = $this->wm_main->sparetype_id;

		$data ['page'] = 'warehouse\grid\grid';

		$data ['title'] = "АГУУЛАХЫН БҮРТГЭЛ";

		$this->load->view ( 'index', $data );
	}
	
	// орлого жагсаалт
	function income() {

		$this->main_model->access_check ();

		$this->main_model->check_byrole ( 'warehouse', $this->role );

		$data ['library_src'] = $this->javascript->external ( base_url().'assets/js/warehouse/income.min.js', TRUE );

		$data ['page'] = 'warehouse\grid\income';

		$data ['title'] = "Орлогын жагсаалт";

		$this->load->view ( 'index', $data );
	}
	
	// Зарлага жагсаалт

	function expense() {		

		$this->main_model->check_byrole ( 'warehouse', $this->role );

		$data ['library_src'] = $this->javascript->external ( base_url().'assets/js/warehouse/expense.min.js', TRUE );

		$data ['page'] = 'warehouse\grid\expense';

		$data ['title'] = "Зарлагын жагсаалт";

		$this->load->view ( 'index', $data );

	}

	// Орлого устгах
	function invoiceDel($invoice_id, $status = null) {

		$this->main_model->access_check ();

		if ($this->db->delete ( 'wm_invoice', array (		
				
				'id' => $invoice_id 
		))) {
		
			if (! $status)
		
				$this->session->set_userdata ( 'message', ' Орлогийг амжилттай устгалаа' );
		
			else
		
				$this->session->set_userdata ( 'message', ' Зарлага амжилттай устгалаа' );
		
		} else
		
			$this->session->set_userdata ( 'message', 'Устгахад алдаа гарлаа' );
		
		if (! $status)
		
			redirect ( '/warehouse/income' );
		
		else
		
			redirect ( '/warehouse/expense' );
	}
	
	// орлого авахад энэ хуудсыг дуудна.
	function incomePage() {
		// call page
		$this->main_model->access_check ();
		$data ['supplier'] = $this->wm_model->getSupplier ();
		$data ['accountant'] = $this->wm_model->getAccountant ();
		$data ['page'] = 'warehouse\income\incomePage';
		$data ['title'] = 'Орлого авах';
		$this->load->view ( 'index', $data );
	}
	
	// incomdDetail
	function incomeDetail() {
		// insert Income Invoice
		$count = $this->input->get_post ( 'count' );
		if ($count != 0) {
			// $this->wm_model->insIncome(); // invoice_id;
			$income_no = $this->input->get_post ( 'income_no' );
			// check invoice_id байгаа эсэхийг шалгана.!!!
			$query = $this->db->query ( "SELECT * FROM wm_income WHERE income_no = '$income_no'" );
			if ($query->num_rows () == 0) {
				// alert here
				$invoice_id = $this->main_model->get_maxId ( 'wm_invoice', 'id' );
				// $this->wm_model->insIncome(); // invoice_id;
				$data = $this->wm_model->preIncomeDtl ();
				$data ['storeman_id'] = $this->session->userdata ( 'employee_id' );
				$data ['income_date'] = $this->input->get_post ( 'income_date' );
				$data ['income_no'] = $income_no;
				// purpose +
				$data ['purpose'] = $this->input->get_post ( 'purpose' );
				// get supplier_id +
				$data ['supplier_id'] = $this->input->get_post ( 'supplier_id' );
				// get accountant_id +
				$data ['accountant_id'] = $this->input->get_post ( 'accountant_id' );
				
				$data ['warehouse'] = $this->wm_model->getWarehouse ();
				$data ['pallet'] = $this->wm_model->getPallet ();
				$data ['page'] = 'warehouse\income\incomeDetail';
				$data ['title'] = 'Орлогын дэлгэрэнгүй:Сэлбэгийг тавиурт тавих';
				$this->load->view ( 'index', $data );
			} else {
				$this->session->set_userdata ( 'message', "$income_no дугаартай орлого хадгалагдсан байна, дахин шалгаж орулна уу!" );
				redirect ( '/warehouse/incomePage' );
			}
		} else {
			$this->session->set_userdata ( 'message', 'Орлогын жагсаалтад нэг сэлбэгийг нэмнэ үү.' );
			redirect ( '/warehouse/income' );
		}
	}
	
	// insert income Detail
	function insIncomeDtl() {
		$result = $this->wm_model->insIncomeDtl ();
		if ($result) {
			$this->session->set_userdata ( 'message', 'Орлогыг амжилттай хадгаллаа.' );
			redirect ( '/warehouse/income' );
		} else {
			$this->session->set_userdata ( 'message', 'Орлогыг хадгалахад алдаа гарлаа!' );
			redirect ( '/warehouse/incomePage' );
		}
	}
	
	// Эхний үлдэгдэл нэмэх
	function beginbalance() {
		$data ['page'] = 'warehouse\income\beginBalance';
		$data ['title'] = 'Эхний үлдэгдэл жагсаалт';
		$this->load->view ( 'index', $data );
	}
	function balancePallet() {
		// sent post variable of spare_id
		$count = $this->input->get_post ( 'count' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		$qty = $this->input->get_post ( 'qty' );
		$data ['invoiceDate'] = $this->input->get_post ( 'invoicedate' );
		
		// count ni нийт хэдэн сэлбэг байгааг илэрхийлнэ.
		// 0-с эхлэнэ.
		// spare_id гаар сэлбэгүүдйн нэрийг авна.
		$spare = array ();
		for($i = 0; $i < $count; $i ++) {
			$spare [$i] = $this->main_model->get_row ( 'spare', array (
					'spare_id' => $spare_id [$i] 
			), 'wm_spare' );
		}
		$data [] = array ();
		$data ['spare'] = $spare;
		$data ['qty'] = $qty;
		$data ['count'] = $count;
		$data ['spare_id'] = $spare_id;
		$data ['warehouse'] = $this->wm_model->getWarehouse ();
		$data ['pallet'] = $this->wm_model->getPallet ();
		$data ['page'] = 'warehouse\income\balanceDetail';
		$data ['title'] = 'Орлогын дэлгэрэнгүй:Сэлбэгийг тавиурт тавих';
		$this->load->view ( 'index', $data );
	}
	function balanceDtl() {
		$spareId = $this->input->get_post ( 'spare_id' );
		$spare_cnt = $this->input->get_post ( 'count' );
		$isInvoice = false;
		$spares = '';
		
		for($i = 0; $i < $spare_cnt; $i ++) {
			$spare_id = $spareId [$i];
			$query = $this->db->query ( "SELECT * FROM wm_invoicedetail WHERE spare_id=$spare_id" );
			if ($query->num_rows () > 0) {
				$isInvoice = true;
				$spares = $spares . ", " . $this->main_model->get_row ( 'spare', array (
						'spare_id' => $spare_id 
				), 'wm_spare' );
			}
		}
		if ($isInvoice) {
			$this->session->set_userdata ( 'message', substr ( $spares, 1 ) . " дээр эхний үлдэгдэл авсан байгаа тул эхний үлдэгдэл авах боломжгүй. </br>Шууд орлого авна уу!" );
			redirect ( '/warehouse/income' );
		} else {
			$result = $this->wm_model->insBeginbalance ();
			if ($result) {
				$this->session->set_userdata ( 'message', 'Орлогийг амжилттай хадгаллаа.' );
				redirect ( '/warehouse/income' );
			} else {
				$this->session->set_userdata ( 'message', 'Орлогийг хадгалахад алдаа гарлаа!' );
				redirect ( '/warehouse/beginBalance' );
			}
		}
	}
	function order() {
		$data ['order_no'] = $this->main_model->get_maxId ( 'wm_order', 'order_no' );
		$data ['steward'] = $this->wm_model->getSteward ();
		$data ['page'] = 'warehouse\order\order';
		$data ['title'] = 'Захиалгийн жагсаалт';
		$this->load->view ( 'index', $data );
	}
	
	// Захиалгийн хуудас
	function orderPage() {
		// call page
		$data ['measure'] = $this->wm_model->getMeasure ();
		$data ['page'] = 'warehouse\order\orderPage';
		$data ['title'] = 'Захиалгын жагсаалт';
		$this->load->view ( 'index', $data );
	}
	// edgeer ashiglagdaj bgaag shalga
	function insOrder() {
		// here is validation
		// check сэлбэг байгаа эсэхийг шалгах
		// validation
		$this->load->library ( 'form_validation' );
		$section_id = $this->input->post ( 'section_id' );
		$order_date = $this->input->post ( 'order_date' );
		$order_no = $this->input->post ( 'order_no' );
		$count = $this->input->post ( 'count' );
		
		$this->form_validation->set_rules ( 'order_no', 'Захиалгын дугаар', 'required' );
		$this->form_validation->set_rules ( 'section_id', 'Захиалсан хэсэг', 'required|is_natural_no_zero' );
		$this->form_validation->set_rules ( 'order_date', 'Захиалгын огноо', 'required' );
		$this->form_validation->set_rules ( 'count', 'Сэлбэг', 'required|is_natural_no_zero' );
		
		$this->form_validation->set_message ( 'is_natural_no_zero', ' "%s" -н утга шаардлагатай. Утга сонгоно уу?' );
		
		if ($this->form_validation->run () != FALSE) {
			// Захиалгийн дугаар хадгалагдсан эсэхийг шалгана
			if ($this->wm_model->is_orderno_set () == FALSE) {
				if ($this->wm_model->makeOrder () == TRUE) {
					$this->session->set_userdata ( 'message', 'Захиалгийг амжилттай хадгаллаа.' );
					redirect ( '/warehouse/order' );
				} else {
					$this->session->set_userdata ( 'message', 'Захиалга хадгалахад алдаа гарлаа.' );
					redirect ( '/warehouse/orderPage' );
				}
			} else {
				$this->session->set_userdata ( 'message', $order_no . ' дугаартай захиалга аль хэдийн хадгалагдсан байна. Дугаараа шалгаад дахин оролдоно уу!' );
				redirect ( '/warehouse/orderPage' );
			}
		} else {
			$this->session->set_userdata ( 'message', validation_errors ( '', '<br>' ) );
			redirect ( '/warehouse/orderPage' );
		}
		
		// $result=$this->wm_model->makeOrder();
		// $order_date=$this->input->get_post('order_date');
		// if($result){
		// $this->session->set_userdata('message', 'Захиалгийг амжилттай хадгаллаа.');
		// redirect('/warehouse/order');
		// }else{
		// $this->session->set_userdata('message', 'Захиалгийг хадгалахад алдаа гарлаа!');
		// redirect('/warehouse/orderPage');
		// }
	}
	
	// Loading grid
	function grid() {
		$data ['section_id'] = $this->wm_main->section_id;
		$data ['sector_id'] = $this->wm_main->sector_id;
		$data ['equipment_id'] = $this->wm_main->equipment_id;
		$data ['sparetype_id'] = $this->wm_main->sparetype_id;
		$data ['page'] = 'warehouse\grid\test';
		$data ['title'] = 'Захиалга';
		$this->load->view ( 'index', $data );
	}
	function expensePage() {
		$data ['accountant'] = $this->wm_model->getAccountant ();
		$data ['section'] = $this->wm_model->getSection ();
		$data ['recieved'] = $this->main_model->getEmployee ();
		$data ['page'] = 'warehouse\expensePage';
		$data ['title'] = 'Захиалгын жагсаалт';
		$this->load->view ( 'index', $data );
	}
	// insExpense
	function insExpense() {
		$count = $this->input->get_post ( 'cnt' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		// $spare_qty = $this->input->get_post('qty');
		$j = 1;
		$data = array ();
		// эхлээд invoice нэмнэ...
		$invoiceId = $this->main_model->get_maxId ( 'wm_invoice', 'id' );
		$expenseNo = $this->input->get_post ( 'expenseNo' );
		
		date_default_timezone_set ( 'Asia/Ulan_Bator' );
		/* id, invoicetype, invoicedate, actionby_id, actiondate */
		$query = $this->db->query ( "SELECT * FROM wm_expense WHERE expense_no = '$expenseNo'" );
		if ($query->num_rows () == 0) {
			$dataInv ['id'] = $invoiceId;
			$dataInv ['invoicetype'] = 'isExpense';
			$dataInv ['invoicedate'] = $this->input->get_post ( 'expenseDate' );
			$dataInv ['actionby_id'] = $this->session->userdata ( 'employee_id' );
			$dataInv ['actiondate'] = date ( "Y-m-d H:i:s" );
			
			$invRes = $this->db->insert ( 'wm_invoice', $dataInv );
			// echo "Invoice";
			// echo "<pre>";
			// print_r($dataInv);
			// echo "</pre>";
			if ($invRes) {
				/*
				 * invoice_id, expense_no, expense_date, spare_id, qty,
				 * intend, section_id, storeman_id, checkby_id, receiveby_id
				 */
				$dataExp ['invoice_id'] = $invoiceId;
				$dataExp ['expense_no'] = $expenseNo;
				$dataExp ['expense_date'] = $this->input->get_post ( 'expenseDate' );
				$dataExp ['intend'] = $this->input->get_post ( 'intend' );
				$dataExp ['section_id'] = $this->input->get_post ( 'section_id' );
				$dataExp ['storeman_id'] = $this->session->userdata ( 'employee_id' );
				$dataExp ['checkby_id'] = $this->input->get_post ( 'checkby_id' );
				
				if ($this->input->get_post ( 'receiveby_id' ))
					$dataExp ['receiveby_id'] = $this->input->get_post ( 'receiveby_id' );
				else
					$dataExp ['receiveby'] = $this->input->get_post ( 'receiveby' );
					// echo "Expense";
					// echo "<pre>";
					// print_r($dataExp);
					// echo "</pre>";
				
				$expRes = $this->db->insert ( 'wm_expense', $dataExp );
				if ($expRes) {
					// invoiceDtl нэмнэ
					for($i = 0; $i < $count; $i ++) {
						$invoiceDtl = $this->input->get_post ( $j . '_rowId' );
						// echo "Invoice DTL";
						// print_r($invoiceDtl);
						// echo "</br>";
						$j ++;
						for($l = 0; $l < count ( $invoiceDtl ); $l ++) {
							$subData ['invoice_id'] = $invoiceId;
							$subData ['spare_id'] = $spare_id [$i];
							$subData ['serial'] = $this->main_model->get_row ( 'serial', array (
									'id' => $invoiceDtl [$l] 
							), 'wm_invoiceDetail' );
							$subData ['serial_x'] = $this->main_model->get_row ( 'serial_x', array (
									'id' => $invoiceDtl [$l] 
							), 'wm_invoiceDetail' );
							$subData ['pallet_id'] = $this->main_model->get_row ( 'pallet_id', array (
									'id' => $invoiceDtl [$l] 
							), 'wm_invoiceDetail' );
							$subData ['aqty'] = - 1;
							array_push ( $data, $subData );
						}
					}
					// insert invoiceDetail
					// invoice_id, spare_id, pallet_id, serial, aqty
					$res = $this->db->insert_batch ( 'wm_invoicedetail', $data );
					if ($res) {
						$this->session->set_userdata ( "message", "<strong>[$expenseNo]</strong> дугаартай сэлбэгийн зарлагыг амжилттай хадгаллаа" );
					} else
						$this->session->set_userdata ( "message", "<strong>[$expenseNo]</strong> дугаартай сэлбэгийн зарлагыг хадгалахад алдаа гарлаа." );
					// print_r($data);
				}
				// end if invRes
			}
			// echo "data:";
			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";
			// echo "<pre>";
			// print_r($invoiceDtl);
			// echo "</pre>";
			redirect ( '/warehouse/expense' );
		} else {
			$this->session->set_userdata ( "message", "<strong>[$expenseNo]</strong> дугаартай зарлага хадгалагдсан байна! Зарлагийн дугаарыг давтагдахгүйгээр оруулна уу!" );
			redirect ( '/warehouse/expensePage' );
		}
	}
	function expenseDel($expense_id) {
		// this action only accessed user and has access this function
		// $this->db->get_where('wm_income', array('income_id'=>$income_id));
		$result = $this->db->get_where ( 'wm_expense', array (
				'expense_id' => $expense_id 
		) )->result ();
		foreach ( $result as $row ) {
			$data ['expense_id'] = $row->expense_id;
			$data ['expense_date'] = $row->expense_date;
			$data ['spare_id'] = $row->spare_id;
			$data ['qty'] = $row->qty;
			$data ['intend'] = $row->intend;
			$data ['storeman_id'] = $row->storeman_id;
			$data ['expensedby_id'] = $row->expensedby_id;
			$data ['receivedby_id'] = $row->receivedby_id;
			$spare_id = $row->spare_id;
		}
		date_default_timezone_set ( ECNS_TIMEZONE );
		$data ['deletedate'] = date ( "Y-m-d H:i:s", time () );
		$data ['deletedby_id'] = $this->session->userdata ( 'employee_id' );
		$res_explog = $this->db->insert ( 'wm_log_expense', $data );
		if ($res_explog) {
			$result = $this->db->query ( "CALL incexpDelContainer($spare_id, $expense_id, 'E')" );
			if ($result) {
				$result = $this->db->delete ( 'wm_expense', array (
						'expense_id' => $expense_id 
				) );
				// update wm_log_income deleted date, deleteby_id;
				$this->session->set_userdata ( 'message', 'Зарлага амжилттай устгалаа.' );
			} else
				$this->session->set_userdata ( 'message', 'Зарлага устгах процедур дээр алдаа гарлаа.' );
		} else
			$this->session->set_userdata ( 'message', 'Зарлага устгахад алдаа гарлаа. /log table/' );
		redirect ( '/warehouse/expense' );
	}
	// Захиалгын жагсаалтын
	
	// Baiguullagiin selbegiin jagsaalt
	function spareList() {
		$this->main_model->access_check ();
		$this->main_model->check_byrole ( 'warehouse', $this->role );
		$this->session->set_userdata ( 'backpage', '/ecns/warehouse/spareList' );
		$data ['page'] = 'warehouse\spareList';
		$data ['title'] = 'Байгууламжийн сэлбэгийн захиалгууд';
		$data ['section'] = $this->main_model->get_industry ();
		$sparelist = $this->db->get ( 'wm_view_sparelist' );
		$data ['sparelist'] = $sparelist->result ();
		$sparelist->free_result ();
		
		if (isset ( $_POST ['section_id'] )) {
			$data ['section_id'] = $_POST ['section_id'];
			$data ['equipments'] = $this->main_model->getEquipment ( $_POST ['section_id'] );
		}
		if (isset ( $_POST ['equipment_id'] )) {
			$data ['equipment_id'] = $_POST ['equipment_id'];
		}
		// $data['count']=$this->db->query("SELECT COUNT(*) FROM ")
		$this->load->view ( 'index', $data );
	}
	
	// call Page
	function spareOrderPage($action, $id = null) {
		if (is_null ( $action ))
			$action = 'new';
		switch ($action) {
			case 'new' :
				$this->db->empty_table ( 'wm_temp_list' );
				$data = $this->wm_model->getTemplist ();
				$data ['page_no'] = $this->main_model->get_maxId ( 'wm_sparelist', 'page_no' );
				$data ['status'] = 0;
				$data ['section_id'] = 0;
				$data ['order_date'] = '';
				$data ['section'] = $this->main_model->get_industry ();
				$data ['title'] = "Байгууламжийн сэлбэгийн захиалгийн хуудас";
				$this->load->view ( 'warehouse/spareOrderPage', $data );
				break;
			case 'delete' :
				$result = $this->db->delete ( 'wm_sparelist', array (
						'id' => $id 
				) );
				if ($result)
					$this->session->set_userdata ( 'message', 'Захиалгийн жагсаалтыг амжилттай устгалаа.' );
				else
					$this->session->set_userdata ( 'message', 'Захиалгийн жагсаалтыг устгахад алдаа гарлаа.' );
				redirect ( '/warehouse/sparelist' );
				break;
			default :
				$data = $this->wm_model->getTemplist ();
				$data ['page_no'] = $this->main_model->get_maxId ( 'wm_sparelist', 'page_no' );
				$data ['status'] = 1;
				$data ['title'] = "Байгууламжийн сэлбэгийн захиалгийн хуудас";
				if (isset ( $data ['result'] )) {
					$query = $this->db->query ( 'SELECT section_id, order_date FROM wm_temp_list LIMIT 1' );
					$row = $query->row ();
					$data ['section_id'] = $row->section_id;
					$data ['order_date'] = $row->order_date;
				} else {
					$data ['section_id'] = 0;
					$data ['order_date'] = '';
				}
				$this->load->view ( '/warehouse/spareOrderPage', $data );
				break;
		}
	}
	function spareAddForm($section_id, $page_no, $order_date) {
		$this->session->set_userdata ( 'backpage', '/ecns/warehouse/spareList' );
		$data ['page_no'] = $page_no;
		$data ['order_date'] = $order_date;
		$data ['section_id'] = $section_id;
		$data ['section'] = $this->main_model->get_row ( 'name', array (
				'section_id' => $section_id 
		), 'section' );
		$data ['equipment'] = $this->main_model->getEquipment ( $section_id );
		$data ['equipment_id'] = 0;
		$data ['sparetype'] = $this->wm_model->getSparetype ();
		$data ['sparetype_id'] = 0;
		$data ['page'] = 'warehouse\spareAddForm';
		$data ['title'] = 'Тоног төхөөрөмж нэмэх';
		$this->load->view ( 'index', $data );
	}
	function doSpareListPage($action, $id = null) {
		switch ($action) {
			case 'delete' :
				$this->db->where ( 'id', $id );
				$result = $this->db->delete ( 'wm_temp_list' );
				if ($result)
					$this->session->set_userdata ( 'message', 'Жагсаалтаас амжилттай устгалаа.' );
				else
					$this->session->set_userdata ( 'message', 'Жагсаалтаас устгахад алдаа гарлаа.' );
				redirect ( '/warehouse/spareOrderPage/temp', 'refresh' );
				break;
		}
	}
	
	// Add template page
	function addSpareOrderPage() {
		$spare_id = $this->input->get_post ( 'spare_id' );
		if ($spare_id) {
			$data ['usingQty'] = $this->input->get_post ( 'usingQty' );
			$data ['needQty'] = $this->input->get_post ( 'needQty' );
			$data ['injobQty'] = $this->input->get_post ( 'injobQty' );
			$data ['orderQty'] = $this->input->get_post ( 'orderQty' );
			$data ['comment'] = $this->input->get_post ( 'comment' );
			$data ['page_no'] = $this->input->get_post ( 'page_no' );
			$section_id = $this->input->get_post ( 'section_id' );
			$data ['section_id'] = $section_id;
			$data ['order_date'] = $this->input->get_post ( 'order_date' );
			$data ['spare_id'] = $spare_id;
			$data ['spare'] = $this->main_model->get_row ( 'spare', array (
					'spare_id' => $spare_id 
			), 'wm_spare' );
			$data ['part_number'] = $this->main_model->get_row ( 'part_number', array (
					'spare_id' => $spare_id 
			), 'wm_spare' );
			$data ['equipment'] = $this->main_model->get_row ( 'equipment', array (
					'spare_id' => $spare_id 
			), 'wm_view_spare' );
			$data ['manufacture'] = $this->main_model->get_row ( 'manufacture', array (
					'spare_id' => $spare_id 
			), 'wm_view_spare' );
			$data ['employee_id'] = $this->session->userdata ( 'employee_id' );
			// if there is spare_id in spare_list don't add it
			$saved_spare = $this->main_model->get_row ( 'spare_id', array (
					'spare_id' => $spare_id 
			), 'wm_spareorderlist' );
			if (! $saved_spare) {
				$row_spare = $this->main_model->get_row ( 'spare_id', array (
						'spare_id' => $spare_id 
				), 'wm_temp_list' );
				// Spare_id find spare_id from temp_list //
				if (! $row_spare) {
					$result = $this->db->insert ( 'wm_temp_list', $data );
					if ($result)
						$this->session->set_userdata ( 'message', 'Жагсаалтад 1 сэлбэг төхөөрөмжийг нэмлээ.' );
					else
						$this->session->set_userdata ( 'message', 'Нэмэхэд алдаа гарлаа.' );
				} else
					$this->session->set_userdata ( 'message', 'Энэ төхөөрөмж хадгалагдсан байна. Өөр сэлбэг сонго!' );
			} else
				$this->session->set_userdata ( 'message', 'Энэ сэлбэгийн захиалга аль хэдийн хадгалагдсан байна!' );
			
			$section_id = $this->input->get_post ( 'section_id' );
			$data = $this->wm_model->getTemplist ();
			$data ["section_id"] = $section_id;
			$data ['order_date'] = $this->input->get_post ( 'order_date' );
			$data ['page_no'] = $this->input->get_post ( 'page_no' );
			$data ['title'] = "Байгууламжийн сэлбэгийн захиалгийн хуудас";
			$this->load->view ( 'warehouse/spareOrderPage', $data );
		} else
			redirect ( '/warehouse/spareOrderPage/temp' );
	}
	
	// Жагсаалтын хуудас уруу нэмэх
	function addSparelist() {
		$this->db->empty_table ( 'wm_temp_list' );
		$spare_id = $this->input->get_post ( 'spare_id' );
		$injobQty = $this->input->get_post ( 'injobQty' );
		$orderQty = $this->input->get_post ( 'orderQty' );
		$comment = $this->input->get_post ( 'comment' );
		$count = $this->input->get_post ( 'count' );
		// Main table here
		$id = $this->main_model->get_maxId ( 'wm_sparelist', 'id' );
		$data_main ['id'] = $id;
		$data_main ['page_no'] = $this->input->get_post ( 'page_no' );
		$data_main ['section_id'] = $this->input->get_post ( 'section_id' );
		$data_main ['ordered_date'] = $this->input->get_post ( 'order_date' );
		$data_main ['createdby_id'] = $this->session->userdata ( 'employee_id' );
		$data_main ['recievedby_id'] = 0;
		
		$result = $this->db->insert ( 'wm_sparelist', $data_main );
		
		if ($result) {
			$this->session->set_userdata ( 'message', 'Сэлбэгийн захиалгийн жагсаалтийг амжилттай бүртгэлээ.' );
			// insert wm_sparelistorder-t data-g hiine
			for($i = 0; $i < $count - 1; $i ++) {
				// table "wm_spareorderlist"
				$data ['id'] = $this->main_model->get_maxId ( 'wm_spareorderlist', 'id' );
				$data ['sparelist_id'] = $id;
				$data ['spare_id'] = $spare_id [$i];
				$data ['injobQty'] = $injobQty [$i];
				$data ['orderQty'] = $orderQty [$i];
				$data ['comment'] = $comment [$i];
				// print_r($data);
				$result_detail = $this->db->insert ( 'wm_spareorderlist', $data );
				if ($result_detail)
					$this->session->set_userdata ( 'message', 'Сэлбэгийн захиалгийн жагсаалтийг амжилттай бүртгэлээ.' );
				else
					$this->session->set_userdata ( 'message', 'Сэлбэгийн захиалгийн жагсаалтийг бүртгэхэд алдаа гарлаа.' );
			}
		} else
			$this->session->set_userdata ( 'message', 'Сэлбэгийн захиалгийн жагсаалтийг бүртгэхэд алдаа гарлаа.' );
			// $this->sparelist();
		redirect ( '/warehouse/spareList' );
	}
	
	// Сэлбэг Үлдэгдлийн бүртгэл
	function restspare() {
		$data ['library_src'] = $this->javascript->external ( '/ecns/assets/js/warehouse/restspare.min.js', TRUE );
		$data ['page'] = 'warehouse\grid\restspare';
		$data ['title'] = "БАЙГУУЛАМЖЫН НЭГДСЭН БҮРТГЭЛ /Хавсралт-5/";
		$this->load->view ( 'index', $data );
	}
	
	// Агуулахын Тавиур дээрх сэлбэгийн мэдээлэл
	function pallet() {
		$data ['page'] = 'warehouse\pallet';
		$data ['title'] = 'Тавиур дахь сэлбэгүүд';
		$this->load->view ( 'index', $data );
	}
	
	// Сэлбэгийг хайхад гарах зүйлс
	function spareJson() {
		$spare = $_GET ['term'];
		$this->db->select ( "spare_id, spare_equip, part_number, measure" );
		$this->db->from ( 'wm_view_spare' );
		//$this->db->from ( 'wm_view_spare' );
		$query = $this->db->get ();
		
		$q = strtolower ( $spare );
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc ())
			$q = stripslashes ( $q );
		$result = array ();
		foreach ( $query->result () as $row ) {
			if (strpos ( strtolower ( $row->spare_equip ), $q ) !== false) {
				array_push ( $result, array (
						"id" => $row->spare_id,
						"value" => $row->spare_equip,
						"part" => $row->part_number,
						"measure" => $row->measure 
				) );
			}
			if (count ( $result ) > 15)
				break;
		}
		/*
		 * foreach ($data as $key=>$value) {
		 * if(strpos(strtolower($value), $q) !== false) {
		 * array_push($result, array("id"=>$key, "value" =>$value));
		 * }
		 * if(count($result) > 11)
		 * break;
		 * }
		 */
		echo json_encode ( $result );
	}
	function help() {
		$data ['title'] = 'Сэлбэг хангалт тусламж';
		$this->load->view ( 'help\warehouse', $data );
	}
	function barcode() {
		$data ['title'] = 'Barcode үүсгүүр';
		
		$text = $this->input->get_post ( 'text' );
		$size = $this->input->get_post ( 'size' );
		$code_type = $this->input->get_post ( 'codetype' );
		$orientation = $this->input->get_post ( 'orientation' );
		
		if ($this->input->get_post ( 'generate' ) == 'Үүсгэх') {
			$print = 'Y';
			$sizefactor = 1;
			$filepath = null;
		}
		$this->load->view ( 'warehouse\barcode', $data );
	}
}      