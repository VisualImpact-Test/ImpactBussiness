<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My404 extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
        $config['css']['style']=array();
		$config['single'] = true;
		$config['view'] = 'templates/404';
		$this->view($config);
    }
}
