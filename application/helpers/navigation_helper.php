<?php
/*
 * Page Navigation class
 * Created date: 2012/05/10
 * Created by: Ganaa
 */
class Navigation {
	private $end_page; // current page
	private $current_page; // current page
	private $limit; // limit of page 5, 10, 15
	private $total; // total count of rows
	private $start_limit; // start value of query
	private $next_page = '&nbsp; Дараах >>'; // next page
	private $prev_page = '<< Өмнөх &nbsp;'; // previous page
	private $pages; // contain how many pages ceil (total/limit);
	private $prev_inactive_span = 'inactive';
	private $url_chunk = "http://localhost/ecns/index.php/";
	private $active_span = 'active';
	private $inactive_span = 'inactive';
	private $query;
	private $ci;
	
	// wrapper of css name
	private $divwrappername = 'navigator'; // contain how many pages ceil (total/limit);
	function __construct($function, $page, $limit, $total) {
		$this->current_page = $page;
		$this->limit = $limit;
		$this->total = $total;
		$this->start_limit = ($limit * $page) - $limit;
		$this->set_pages ( $total, $limit );
		$this->set_inactive ();
		$this->ci = & get_instance ();
		$this->ci->load->database ();
		$this->url_chunk .= "$function/";
	}
	private function set_pages($total, $limit) {
		$this->pages = ceil ( $total / $limit );
	}
	private function set_inactive() {
		$this->prev_inactive_span = "<span class ='inactive'>" . $this->prev_page . "</span>";
		$this->next_inactive_span = "<span class ='inactive'>" . $this->next_page . "</span>";
	}
	private function createLink($page) {
		// $strtemp = "<a href='http://localhost/ecns/index.php/shiftlog/index/";
		$strtemp = "<a href='$this->url_chunk" . $page . "'>$page</a>\n";
		// $strtemp .= $page;
		// $strtemp .= "'>$page</a>\n";
		return $strtemp;
	}
	function get_values($value) {
		return $this->$value;
	}
	public function getNavigator() {
		$strnavigator = "<div class=\"$this->divwrappername\">\n";
		
		// Хуудсыг 1-эхлэн дуустал давтахад
		$temp_page = $this->current_page;
		
		if ($this->current_page == 1)
			$strnavigator .= $this->prev_inactive_span;
		else {
			// өмнөх хуудасруу үсэрдэгийг хэвлэх хэрэгтэй.
			$strnavigator .= "<span class=" . $this->active_span . ">";
			$strnavigator .= "<a href='$this->url_chunk" . -- $temp_page . "'>";
			$strnavigator .= $this->prev_page . "</a></span>";
		}
		
		for($i = 1; $i <= $this->pages; $i ++) {
			if ($i == $this->current_page) {
				// $strnavigator .= "<span class =".$this->inactive_span.">";
				$strnavigator .= $i . " ";
				// $strnavigator .="</span>";
			} else
				$strnavigator .= $this->createLink ( $i );
		}
		
		$temp_page = $this->current_page;
		if ($this->current_page == $this->pages)
			$strnavigator .= $this->next_inactive_span;
		else {
			$strnavigator .= "<span class=" . $this->active_span . ">";
			$strnavigator .= "<a href='$this->url_chunk" . ++ $temp_page . "'>";
			$strnavigator .= $this->next_page . "</a></span>";
		}
		$strnavigator .= "</div>";
		
		return $strnavigator;
	}
	public function show_page() {
		$str = "<label> Хуудас:
				<select name='pages'>
				   <option>5</option>
				   <option>10</option>
				   <option>20</option>
				   <option>30</option>
				   <option>40</option>
				</select></label>";
		return $str;
	}
	public function get_value() {
		echo $this->end_page; // current page
		echo "<br>";
		echo $this->current_page; // current page
		echo "<br>";
		echo $this->limit; // limit of page 5, 10, 15
		echo "<br>";
		echo $this->start_limit; // start value of query
		echo $this->next_page = '&nbsp; Дараах >>'; // next page
		echo $this->prev_page = '<< Өмнөх &nbsp;'; // previous page
		echo $this->pages; // contain how many pages ceil (total/limit);
		echo $this->prev_inactive_span = 'inactive';
		echo $this->url_chunk = "http://localhost/ecns/index.php/page/";
		echo $this->active_span = 'active';
		echo $this->inactive_span = 'inactive';
		echo $this->query;
		echo $this->ci;
	}
	public function set_query($query, $start, $limit) {
		$query = $query . " limit $star, $limit";
		return $query;
	}
	public function get_query($table, $order) {
		$this->query .= "SELECT * FROM $table ORDER BY $order";
		$this->query .= " limit $this->start_limit, $this->limit";
		$res_query = $this->ci->db->query ( $this->query ); // same as Select * from logs;
		return $res_query->result ();
	}
}

/*
 * $navigator = new Navigation(1, 5, 20);
 * echo $navigator ->getNavigator();
 * echo $navigator->get_values('pages');
 * echo $navigator->show_page();
 */
?>