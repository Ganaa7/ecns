<?php
$this->load->view ( 'header' );
$this->load->view ( $page );

$exception_pages = array(
	'event/event',
	'event/index'
);

 if (in_array($page, $exception_pages) == FALSE)
	$this->load->view ( 'footer' );

?>
