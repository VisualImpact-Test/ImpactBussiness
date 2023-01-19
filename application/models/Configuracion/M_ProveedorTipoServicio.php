<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ProveedorTipoServicio extends MY_Model
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

	public function obtenerInformacionProveedorTipoServicio($params = [])
	{
		$db = $this->db
			->select('*')
			->from('compras.proveedorTipoServicio ts');

		if (isset($params['no_idProveedorTipoServicio'])) $db->where('idProveedorTipoServicio !=', $params['no_idProveedorTipoServicio']);
		if (isset($params['nombre'])) $db->where('nombre', $params['nombre']);


		return $db->get();
	}

	public function guardarDatos(String $tabla, array $datos)
	{
		if ($this->db->insert($tabla, $datos)) {
			$rpta = ['estado' => true, 'id' => $this->db->insert_id()];
		}

		return $rpta;
	}

	public function actualizarDatos(String $tabla, array $datos, array $filtro)
	{
		$query = $this->db->update($tabla, $datos, $filtro);
		if ($query) {
			$rpta = ['estado' => true];
		}
		return $rpta;
	}
}
