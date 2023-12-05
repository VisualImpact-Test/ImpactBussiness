<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Sincerado extends MY_Model
{
	var $resultado = [
		'query' => '',
		'estado' => false,
		'id' => null,
		'msg' => ''
	];
	public function __construct()
	{
		parent::__construct();
	}
}
