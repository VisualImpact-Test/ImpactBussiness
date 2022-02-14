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
		$query = $this->db->select('idTipoServicio AS id, nombre AS value')
			->where('estado', 1)
			->get('compras.tipoServicio');

		if ($query->num_rows() > 0) {
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
			->where('ep.idEstado', $proveedor_estados->activo)
			->get('compras.proveedor p');

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query->result_array();
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionTarifarioServicios($params = [])
	{
		$this->db->select([
			'ROW_NUMBER() OVER(ORDER BY tarif_s.idTarifarioServicio ASC) as num_fila',
			'tarif_s.idTarifarioServicio',
			'tarif_s.idServicio',
			'tipo_s.nombre as tipo_servicio_nombre',
			's.nombre as servico_nombre',
			'p.razonSocial as proveedor_nombre',
			'p.idProveedor',
			'tarif_s.flag_actual',
			'tarif_s.costo as tarifa_servicio_costo',
			'tarif_s.estado as tarifa_servicio_estado_id',
			"case tarif_s.estado when 1 then 'Activo' else 'Inactivo' end as tarifa_servicio_estado",
		]);
		$this->db->from('compras.tarifarioServicio tarif_s');
		$this->db->join('compras.servicio s', 'tarif_s.idServicio = s.idServicio');
		$this->db->join('compras.tipoServicio tipo_s', 's.idTipoServicio = tipo_s.idTipoServicio');
		$this->db->join('compras.proveedor p', 'tarif_s.idProveedor = p.idProveedor');

		if (!empty($params['idTarifarioServicio'])) {
			$this->db->where('tarif_s.idTarifarioServicio', $params['idTarifarioServicio']);
		}

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
			if (isset($params['row_array'])) $this->resultado['query'] = $query->row_array();
			else $this->resultado['query'] = $query->result_array();
			$this->resultado['estado'] = true;
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

	public function actualizarServicio($params = [])
	{
		$query = $this->db->where('idTarifarioServicio', $params['idTarifarioServicio'])
			->update(
				'compras.tarifarioServicio',
				['estado' => ($params['estado'] == 1) ? 0 : 1]
			);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarTarifarioServicio($params = [], $table)
	{
		$query = $this->db->update($table, $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}

	public function obtenerServicios()
	{
		return $this->db->select('idServicio as value, nombre as label')
			->get('compras.servicio')
			->result_array();
	}

	public function validarTarifarioServicio($params = [], $validar = null)
	{
		$this->db->select('tarif_s.idTarifarioServicio');
		$this->db->from('compras.tarifarioServicio tarif_s');
		$this->db->group_start();
			$this->db->where('tarif_s.idServicio', $params['idServicio']);
			if ($validar === 'actual') $this->db->where('tarif_s.flag_actual', 1);
			if ($validar === 'existe') $this->db->where('tarif_s.idProveedor', $params['idProveedor']);
		$this->db->group_end();
		if (isset($params['idTarifarioServicio'])) {
			$this->db->where('tarif_s.idTarifarioServicio !=', $params['idTarifarioServicio']);	
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$this->resultado['query'] = $query->row_array();
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function insertarTarifarioServicio($params = [], $tabla)
	{
		$query = $this->db->insert($tabla, $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
