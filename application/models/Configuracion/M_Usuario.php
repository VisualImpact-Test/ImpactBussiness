<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Usuario extends MY_Model
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

	public function obtenerInformacionUsuarios($params = [])
	{
		$db = $this->db
			->select('u.*, uf.fechaReg, uf.nombre_archivo')
			->from('sistema.usuario u')
			->join('sistema.usuarioFirma uf', 'u.idUsuarioFirma=uf.idUsuarioFirma', 'left');

		if (isset($params['idUsuario'])) {
			$db->where('idUsuario', $params['idUsuario']);
		}
		if (isset($params['demo'])) {
			$db->where_in('demo', $params['demo']);
		}

		return $db->get();
	}
	public function getUsuarioFirmaHistorico(String $id)
	{
		$this->db
		->select('*')
		->from('sistema.usuarioFirmaHistorico')
		->where('idUsuario', $id)
		->where('fecFin', null);

		return $this->db->get();
	}
	public function guardarDatos(String $tabla, array $datos)
	{
		$this->db->insert($tabla,$datos);
		return $this->db->insert_id();
	}
	public function actualizarDatos(String $tabla, array $datos, array $filtro)
	{
		$query = $this->db->update($tabla,$datos,$filtro);
		if ($query) {
			return true;
		}
	}


}
