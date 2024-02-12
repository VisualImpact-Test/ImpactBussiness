<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_TiposServicio extends MY_Model
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

	public function obtenerInformacionTiposServicio($params = [])
	{
		$db = $this->db
			->select('tT.nombre as tipoTransporte, ts.*, tsu.nombre as ubigeo, um.nombre as unidadMedida, it.nombre as itemTipo')
			->from('compras.tipoServicio ts')
			->join('compras.tipoServicioUbigeo tsu', 'ts.idTipoServicioUbigeo=tsu.idTipoServicioUbigeo', 'left')
			->join('compras.unidadMedida um', 'um.idUnidadMedida=ts.idUnidadMedida', 'left')
			->join('compras.itemTipo it', 'it.idItemTipo=ts.idItemTipo', 'left')
			->join('VisualImpact.logistica.tipo_transporte tT', 'tT.idTipoTransporte=ts.idTipoTransporte', 'left');

		if (isset($params['idTipoServicio'])) {
			$db->where('idTipoServicio', $params['idTipoServicio']);
		}
		if (isset($params['NO_idTipoServicio'])) {
			$db->where('ts.idTipoServicio !=', $params['NO_idTipoServicio']);
		}
		if (isset($params['idTipoServicioUbigeo'])) {
			$db->where('ts.idTipoServicioUbigeo', $params['idTipoServicioUbigeo']);
		}
		if (isset($params['idItemTipo'])) {
			$db->where('ts.idItemTipo', $params['idItemTipo']);
		}
		if (isset($params['idUnidadMedida'])) {
			$db->where('ts.idUnidadMedida', $params['idUnidadMedida']);
		}
		if (isset($params['nombre'])) {
			$db->where('ts.nombre', $params['nombre']);
		}

		return $db->get();
	}

	public function obtenerTipoServicioUbigeo($params = [])
	{
		$db = $this->db
			->select('tsu.*')
			->from('compras.tipoServicioUbigeo tsu');

		if (isset($params['estado'])) {
			$db->where_in('estado', $params['estado']);
		}
		return $db->get();
	}

	public function obtenerUnidadMedida($params = [])
	{
		$db = $this->db
			->select('um.*')
			->from('compras.unidadMedida um');

		if (isset($params['estado'])) {
			$db->where_in('um.estado', $params['estado']);
		}
		return $db->get();
	}

	public function guardarDatos(String $tabla, array $datos)
	{
		if ($this->db->insert($tabla,$datos)) {
			$rpta = [ 'estado' => true, 'id' => $this->db->insert_id() ];
		}
		return $rpta;
	}
	public function actualizarDatos(String $tabla, array $datos, array $filtro)
	{
		$query = $this->db->update($tabla, $datos, $filtro);
		if ($query) {
			$rpta = [ 'estado' => true ];
		}
		return $rpta;
	}


}
