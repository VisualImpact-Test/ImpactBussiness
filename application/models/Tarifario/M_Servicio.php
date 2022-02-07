<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Servicio extends MY_Model
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

	public function obtenerTipoServicio($params = [])
	{
		$sql = "
			SELECT
				idTipoServicio AS id
				, nombre AS value
			FROM compras.tipoServicio
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionServicios($params = [])
	{
		$this->db->select([
			'ROW_NUMBER() OVER(ORDER BY tarif_s.idTarifarioServicio ASC) as num_fila',
			'tarif_s.idTarifarioServicio',
			'tarif_s.idServicio',
			'tipo_s.nombre as tipo_servicio_nombre',
			's.nombre as servico_nombre',
			'p.razonSocial as proveedor_nombre',
			'tarif_s.costo as tarifa_servicio_costo',
			"case tarif_s.estado when 1 then 'Activo' else 'Inactivo' end as tarifa_servicio_estado",
		]);
		$this->db->from('compras.tarifarioServicio tarif_s');
		$this->db->join('compras.servicio s', 'tarif_s.idServicio = s.idServicio');
		$this->db->join('compras.tipoServicio tipo_s', 's.idTipoServicio = tipo_s.idTipoServicio');
		$this->db->join('compras.proveedor p', 'tarif_s.idProveedor = p.idProveedor');

		if (!empty($params['tipoServicio'])) {
			$this->db->where('tipo_s.idTipoServicio', $params['tipoServicio']);
		}
		
		if (!empty($params['servicio'])) {
			$this->db->where('tipo_s.nombre', $params['servicio']);
		}

		if (!empty($params['idServicio'])) {
			$this->db->where('s.idServicio', $params['idServicio']);
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerServiciosLogistica()
	{
		$sql = "
			SELECT
				a.idServicio AS value
				, ISNULL(a.codigo + ' - ','') + a.nombre AS label
				, um.idUnidadMedida AS idum
				, um.nombre AS um
				--, c.idCuenta
				--, c.nombre
			FROM visualimpact.logistica.servicio a
			LEFT JOIN visualimpact.logistica.servicio_det ad ON a.idServicio = ad.idServicio
			LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
			--LEFT JOIN visualimpact.logistica.servicio_marca am on a.idMarca = am.idMarca
			--LEFT JOIN visualimpact.logistica.servicio_marca_cuenta amc ON am.idMarca = amc.idMarca
			--LEFT JOIN visualimpact.logistica.cuenta c ON amc.idCuenta = c.idCuenta
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.servicio', 'id' => null];
		return $result;
	}

	public function validarExistenciaServicio($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idServicio']) ? ' AND a.idServicio != ' . $params['idServicio'] : '';

		$sql = "
			SELECT
				idServicio
			FROM compras.servicio a
			WHERE
			(a.nombre LIKE '%{$params['nombre']}%')
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function insertarServicio($params = [])
	{
		$query = $this->db->insert($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarServicio($params = [])
	{
		$query = $this->db->update($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
