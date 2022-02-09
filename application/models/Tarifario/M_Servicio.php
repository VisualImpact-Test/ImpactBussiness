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

	public function obtenerRazonSocProveedor()
	{
		$proveedor_estados = (object )[
			'pendiente' => 1,
			'activo' => 2,
			'inactivo' => 3
		];

		$query = $this->db->select('p.idProveedor as id, p.razonSocial as value')
			->join('compras.estadoProveedor ep', 'p.idEstado = ep.idEstado')
			->where_in('ep.idEstado', [$proveedor_estados->pendiente, $proveedor_estados->activo])
			->get('compras.proveedor p');

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query->result_array();
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
			'tarif_s.estado as tarifa_servicio_estado_id',
			"case tarif_s.estado when 1 then 'Activo' else 'Inactivo' end as tarifa_servicio_estado",
		]);
		$this->db->from('compras.tarifarioServicio tarif_s');
		$this->db->join('compras.servicio s', 'tarif_s.idServicio = s.idServicio');
		$this->db->join('compras.tipoServicio tipo_s', 's.idTipoServicio = tipo_s.idTipoServicio');
		$this->db->join('compras.proveedor p', 'tarif_s.idProveedor = p.idProveedor');

		if (!empty($params['chMostrar'])) {
			$this->db->where('tarif_s.flag_actual', $params['chMostrar']);
		}

		if (!empty($params['tipoServicio'])) {
			$this->db->where('tipo_s.idTipoServicio', $params['tipoServicio']);
		}

		if (!empty($params['razonSocProveedor'])) {
			$this->db->where('p.idProveedor', $params['razonSocProveedor']);
		}

		if (!empty($params['servicio'])) {
			$this->db->like('s.nombre', $params['servicio'], 'both');
		}

		if (!empty($params['precioMinimo']) && !empty($params['precioMaximo'])) {
			$this->db->group_start();
		}

		if (!empty($params['precioMinimo'])) {
			$this->db->where('tarif_s.costo', $params['precioMinimo']);
		}

		if (!empty($params['precioMaximo'])) {
			$this->db->where('tarif_s.costo', $params['precioMaximo']);
		}

		if (!empty($params['precioMinimo']) && !empty($params['precioMaximo'])) {
			$this->db->group_end();
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query->result_array();
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerHistorialTarifarioServicio($params = [])
	{
		$query = $this->db->select([
			'tarif_s.idTarifarioServicio',
			'CONVERT(VARCHAR, tsh.fecIni, 103) AS fecIni',
			"ISNULL(CONVERT(VARCHAR, tsh.fecFin, 103), 'En curso') AS fecFin",
			"tsh.costo",
			"p.razonSocial AS proveedor"
		])
		->from('compras.tarifarioServicio tarif_s')
		->join('compras.tarifarioServicioHistorico tsh', 'tarif_s.idTarifarioServicio = tsh.idTarifarioServicio')
		->join('compras.proveedor p', 'tarif_s.idProveedor = p.idProveedor')
		->where('tarif_s.idTarifarioServicio', $params['idTarifarioServicio'])
		->order_by('tsh.idTarifarioServicioHistorico', 'DESC')
		->get();

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query->result_array();
			$this->resultado['estado'] = true;
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
