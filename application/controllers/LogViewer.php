<?php

class LogViewer extends CI_Controller {
	var $aSessTrack= [];
	public function __construct() {
		parent::__construct(); 
        $this->load->library('cilogviewer/Cilogviewer.php');
	}

	public function index() {
		echo $this->cilogviewer->showLogs();
		return;
	}
}
?>