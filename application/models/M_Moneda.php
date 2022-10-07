<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Moneda extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function obtenerMonedasActivas($params = [])
	{
		$this->db
		->select('*')
		->from('compras.moneda')
		->where('estado','1');
		return $this->db->get();
	}

}
