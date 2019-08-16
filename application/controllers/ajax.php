<?php

if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

class ajax extends CNS_Controller {

	public function __construct() {

		parent::__construct ();		

		$this->load->model ( 'main_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'wm_model' );

		$this->load->library ( 'session' );
		$this->load->library ( 'table' );
		// $this->config->load ( 'config.php' );$request->file('key');$request->file('key');

		
	}
	public function get_table() {
		$section_id = $_GET ['q'];
		$query = $this->db->query ( "SELECT * FROM view_sector WHERE section_id ='$section_id'" );
		$cols = $query->result ();
		echo "<select name='sector_id' id='' class='chosen-select' data-placeholder='Сонго...' onchange='getEquipments(this.value)'>";
		if ($query->num_rows () > 0) {
			foreach ( $cols as $row ) {
				echo "<option value='$row->sector_id'>";
				echo $row->name;
				echo "</option>";
			}
			// echo "<option value='0'>";
			// echo "Тасаг байхгүй";
			// echo "</option>";
		} else {
			echo "<option></option>";
		}
		echo "</select>";
	}
	public function get_position() {
		$section_id = $_GET ['q'];
		if ($section_id != 0) {
			// $sec_code = $this->main_model->get_row('code', array('section_id'=>$section_id), 'section');
			// $query =$this->db->query("SELECT position_id, position FROM view_position_section WHERE $sec_code=1");
			$query = $this->db->query ( "SELECT position_id, position FROM view_section_position WHERE section_id=$section_id" );
			$cols = $query->result ();
			
			echo "<select name='position_id'>";
			if ($query->num_rows () > 0)
				foreach ( $cols as $row ) {
					echo "<option value='$row->position_id'>";
					echo $row->position;
					echo "</option>";
				}
			else {
				echo "<option value='0'>";
				echo "Албан тушаал байхгүй байна";
				echo "</option>";
			}
			echo "</select>";
		} else {
			echo "<select>";
			echo "<option value='0'>";
			echo "Хэсгийг сонгоно уу!";
			echo "</option>";
			echo "</select>";
		}
	}
	// end get position
	function report_header() {
		$log = $_GET ['log'];
		
		if ($log == 'N')
			echo "<thead>
            <th>Гэмтлийн №</th>
            <th>Төхөөрөмж</th>        
            <th>Гэмтсэн нээсэн огноо/цаг</th>        
            <th>Байршил</th>  
            <th>Шалтгаан</th>        
            <th>Гэмтэл</th>
            <th>Бүртгэл нээсэн</th>
            </thead>";
		else
			echo "<thead>
            <th>Гэмтлийн №</th>
            <th>Төхөөрөмж</th>        
            <th>Гэмтсэн нээсэн огноо/цаг</th>        
            <th>Байршил</th>  
            <th>Шалтгаан</th>        
            <th>Гэмтэл</th>
            <th>Бүртгэл нээсэн</th>
            <th>Гэмтэл хаасан огноо/цаг</th>
            <th>Гүйцэтгэл</th>
            <th>Бүртгэл хаасан</th>
            </thead>";
	}
	function setEquipment() {
		// equipments
		$sec_code = $_POST ["sec_code"];
		// $equipment_id=$_POST["equipment_id"];
		
		$json_arr = array ();
		$data = array ();
		
		$this->db->select ( 'equipment_id, name' );
		$this->db->where ( array (
				'sec_code' => $sec_code 
		) );
		// if(isset($equipment_id)){
		// $this->db->where(array('equipment_id!='=>$equipment_id));
		// $sel_equipment=$this->main_model->get_row("name", array('equipment_id'=>$equipment_id), "equipment");
		// $data[$equipment_id]=$sel_equipment;
		// }
		$data [0] = 'Төхөөрөмжүүд';
		$query = $this->db->get ( 'equipment' );
		
		foreach ( $query->result () as $row ) {
			$data [$row->equipment_id] = $row->name;
			$json_arr = $data;
		}
		$query->free_result ();
		header ( 'Content-type: application/json;' );
		echo json_encode ( $json_arr );
	}
}
?>