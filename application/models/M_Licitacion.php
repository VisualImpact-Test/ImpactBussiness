<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Licitacion extends MY_Model
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

	public function obtenerDepartamento()
	{
		return $this->db->distinct()->select('cast(cod_departamento as INT) id, departamento nombre')->where('estado', 1)->order_by('departamento')->get('General.dbo.ubigeo');
	}

	public function obtenerProvincia()
	{
		return $this->db->distinct()->select('cast(cod_departamento as INT) cod_departamento, cast(cod_provincia as INT) cod_provincia, provincia as nombre')->where('estado', 1)->order_by('provincia')->get('General.dbo.ubigeo');
	}

	public function obtenerDistrito()
	{
		return $this->db->distinct()->select('cast(cod_departamento as INT) cod_departamento, cast(cod_provincia as INT) cod_provincia, cast(cod_distrito as INT) cod_distrito, distrito as nombre')->where('estado', 1)->order_by('distrito')->get('General.dbo.ubigeo');
	}

	public function obtenerInformacionLicitacion()
	{
		$this->db
			->select('l.idLicitacion, l.idCliente, l.estado, l.observacion, l.chkAprobado, mon.nombreMoneda as moneda, ubi_zc.departamento, ubi_zc.provincia, l.idDistrito,
			ubi_zc.distrito, cli.nombre as cliente')
			->from('compras.licitacion l')
			->join('compras.moneda mon', 'mon.idMoneda = l.idMoneda', 'LEFT')
			->join('General.dbo.ubigeo ubi_zc', 'l.idDepartamento = ubi_zc.cod_departamento AND ISNULL(l.idProvincia, 1) = (CASE WHEN l.idProvincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
					AND ISNULL(l.idDistrito , 1) = (CASE WHEN l.idDistrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
					AND ubi_zc.estado = 1', 'LEFT')
			->join('compras.cliente cli', 'cli.idCliente = l.idCliente', 'LEFT')
			// ->join('rrhh.dbo.Empresa c', 'l.idCuenta = c.idEmpresa', 'LEFT')
			// ->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = l.idCanal AND cc.idEmpresa = c.idEmpresa', 'LEFT')
			->order_by('l.idLicitacion desc');
		return $this->db->get();
	}

	public function getLicitacion($id)
	{
		$query = $this->db
			->select('l.*, ubi_zc.provincia, ubi_zc.distrito')
			->from('compras.licitacion l')
			->join('General.dbo.ubigeo ubi_zc', 'l.idDepartamento = ubi_zc.cod_departamento AND ISNULL(l.idProvincia, 1) = (CASE WHEN l.idProvincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
				AND ISNULL(l.idDistrito , 1) = (CASE WHEN l.idDistrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
				AND ubi_zc.estado = 1', 'LEFT')
			->where('idLicitacion', $id)
			->where('l.estado', 1)
			->get();

		return $query->row_array();
	}

	public function getLicitacionCargo($id)
	{
		$query = $this->db
		->select('lc.*, c.nombre as cargo')
		->from('compras.licitacionCargo lc')
		->join('compras.cargo c', 'c.idCargo = lc.idCargo', 'LEFT')
		->where('lc.estado', 1)
		->where('lc.idLicitacion', $id)
		->order_by('lc.idLicitacionCargo')
		->get();
		return $query;
	}

	public function getLicitacionDetalle($id)
	{
		$query = $this->db
		->select('ld.*, tp.nombre as tipoPresupuesto, tp.mostrarDetalle')
		->from('compras.licitacionDetalle ld')
		->join('compras.tipoPresupuesto tp', 'tp.idTipoPresupuesto = ld.idTipoPresupuesto', 'LEFT')
		->where('ld.estado', 1)
		->where('ld.idLicitacion', $id)
		->order_by('ld.idLicitacionDetalle')
		->get();
		return $query;
	}
	public function getLicitacionDetalleSub($id)
	{
		$query = $this->db
		->select('lds.*, tpd.*, tp.idTipoPresupuesto')
		->from('compras.licitacionDetalleSub lds')
		->join('compras.licitacionDetalle ld', 'ld.idLicitacionDetalle = lds.idLicitacionDetalle', 'LEFT')
		->join('compras.tipoPresupuestoDetalle tpd', 'tpd.idTipoPresupuestoDetalle = lds.idTipoPresupuestoDetalle', 'LEFT')
		->join('compras.tipoPresupuesto tp', 'tp.idTipoPresupuesto = ld.idTipoPresupuesto', 'LEFT')
		->where('ld.estado', 1)
		->where('ld.idLicitacion', $id)
		->order_by('lds.idLicitacionDetalle')
		->get();
		return $query;
	}
}
