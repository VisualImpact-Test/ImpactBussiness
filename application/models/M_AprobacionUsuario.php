<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_AprobacionUsuario extends MY_Model
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
	public function obtenerInformacionUsuario($params = [])
	{
		$sql = "
			SELECT 
				u.idUsuario AS id, (u.nombres + ' ' + u.apeMaterno + ' ' + u.apePaterno) AS value
			FROM sistema.usuario u
			INNER JOIN compras.requerimientoInternoUsuarioAprobacion riU ON riU.idUsuario != u.idUsuario
		";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function obtenerUsuarioAprobados($params = [])
	{
		$sql = "
			SELECT u.idUsuario AS id, u.nombres, u.apeMaterno, u.apePaterno, ut.nombre AS usuarioTipo,
			riU.estado
			FROM compras.requerimientoInternoUsuarioAprobacion riU
			INNER JOIN sistema.usuario u ON riU.idUsuario = u.idUsuario
			INNER JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
			INNER JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario
		";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function insertarUsuarioAprobar($params = [])
	{
		$query = $this->db->insert($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}
}
