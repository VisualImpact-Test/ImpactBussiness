<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_OrdenServicio extends MY_Model
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

	public function obtenerDocumento($id)
	{
		$this->db
			->select('osd.*, d.nombre_archivo')
			->from('compras.ordenServicioDocumento osd')
			->join('compras.documento d', 'd.idDocumento = osd.idDocumento', 'LEFT')
			->where('osd.idOrdenServicio', $id)
			->where('estado', 1)
			->order_by('idOrdenServicioDocumento');
		return $this->db->get();
	}
	public function obtenerInformacionOrdenServicio()
	{
		$this->db
			->select('l.idOrdenServicio, l.idCliente, l.estado, l.observacion, l.chkAprobado, mon.nombreMoneda as moneda, ubi_zc.departamento, ubi_zc.provincia, l.idDistrito,
			ubi_zc.distrito, cli.nombre as cliente, l.chkPresupuesto, pr.idPresupuesto')
			->from('compras.ordenServicio l')
			->join('compras.moneda mon', 'mon.idMoneda = l.idMoneda', 'LEFT')
			->join('General.dbo.ubigeo ubi_zc', 'l.idDepartamento = ubi_zc.cod_departamento AND ISNULL(l.idProvincia, 1) = (CASE WHEN l.idProvincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
					AND ISNULL(l.idDistrito , 1) = (CASE WHEN l.idDistrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
					AND ubi_zc.estado = 1', 'LEFT')
			->join('compras.cliente cli', 'cli.idCliente = l.idCliente', 'LEFT')
			->join('compras.presupuesto pr', 'pr.idOrdenServicio = l.idOrdenServicio', 'LEFT')
			// ->join('rrhh.dbo.Empresa c', 'l.idCuenta = c.idEmpresa', 'LEFT')
			// ->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = l.idCanal AND cc.idEmpresa = c.idEmpresa', 'LEFT')
			->order_by('l.idOrdenServicio desc');
		return $this->db->get();
	}

	public function getOrdenServicio($id)
	{
		$query = $this->db
			->select('l.*, ubi_zc.provincia, ubi_zc.distrito')
			->from('compras.ordenServicio l')
			->join('General.dbo.ubigeo ubi_zc', 'l.idDepartamento = ubi_zc.cod_departamento AND ISNULL(l.idProvincia, 1) = (CASE WHEN l.idProvincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
				AND ISNULL(l.idDistrito , 1) = (CASE WHEN l.idDistrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
				AND ubi_zc.estado = 1', 'LEFT')
			->where('idOrdenServicio', $id)
			->where('l.estado', 1)
			->get();

		return $query->row_array();
	}

	public function getOrdenServicioCargo($id)
	{
		$query = $this->db
			->select('lc.*, c.nombre as cargo')
			->from('compras.ordenServicioCargo lc')
			->join('compras.cargo c', 'c.idCargo = lc.idCargo', 'LEFT')
			->where('lc.estado', 1)
			->where('lc.idOrdenServicio', $id)
			->order_by('lc.idOrdenServicioCargo')
			->get();
		return $query;
	}

	public function getOrdenServicioDetalle($id)
	{
		$query = $this->db
			->select('ld.*, tp.nombre as tipoPresupuesto, tp.mostrarDetalle')
			->from('compras.ordenServicioDetalle ld')
			->join('compras.tipoPresupuesto tp', 'tp.idTipoPresupuesto = ld.idTipoPresupuesto', 'LEFT')
			->where('ld.estado', 1)
			->where('ld.idOrdenServicio', $id)
			->order_by('ld.idOrdenServicioDetalle')
			->get();
		return $query;
	}
	public function getOrdenServicioDetalleSub($id)
	{
		$query = $this->db
			->select('lds.*, tpd.*, tp.idTipoPresupuesto, it.costo, it.idProveedor')
			->from('compras.ordenServicioDetalleSub lds')
			->join('compras.ordenServicioDetalle ld', 'ld.idOrdenServicioDetalle = lds.idOrdenServicioDetalle', 'LEFT')
			->join('compras.tipoPresupuestoDetalle tpd', 'tpd.idTipoPresupuestoDetalle = lds.idTipoPresupuestoDetalle', 'LEFT')
			->join('compras.tipoPresupuesto tp', 'tp.idTipoPresupuesto = ld.idTipoPresupuesto', 'LEFT')
			->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')
			->where('ld.estado', 1)
			->where('ld.idOrdenServicio', $id)
			->order_by('lds.idOrdenServicioDetalle')
			->get();
		return $query;
	}
	public function getPresupuestoDetalle($id)
	{
		$query = $this->db
			->select('pd.*, tp.nombre as tipoPresupuesto, tp.mostrarDetalle')
			->from('compras.presupuestoDetalle pd')
			->join('compras.tipoPresupuesto tp', 'tp.idTipoPresupuesto = pd.idTipoPresupuesto')
			->where('pd.estado', 1)
			->where('pd.idPresupuesto', $id)
			->get();
		return $query;
	}

	public function getPresupuestoCargo($id)
	{
		$query = $this->db
			->select('pc.*, c.nombre as cargo')
			->from('compras.presupuestoCargo pc')
			->join('compras.cargo c', 'c.idCargo = pc.idCargo')
			->where('pc.estado', 1)
			->where('pc.idPresupuesto', $id)
			->get();
		return $query;
	}

	public function getPresupuestoDetalleSub($id)
	{
		$query = $this->db
			->select('pds.*, tpd.nombre, tpd.tipo')
			->from('compras.presupuestoDetalleSub pds')
			->join('compras.tipoPresupuestoDetalle tpd', 'tpd.idTipoPresupuestoDetalle = pds.idTipoPresupuestoDetalle', 'LEFT')
			->where('pds.estado', 1)
			->where('pds.idPresupuestoDetalle', $id)
			->where('tpd.idTipoPresupuesto != ' . COD_SUELDO)
			->get();
		return $query;
	}

	public function getPresupuestoDetalleSueldo($id)
	{
		$query = $this->db
			->select('pds.*, tpd.nombre, tpd.tipo')
			->from('compras.presupuestoDetalleSueldo pds')
			->join('compras.tipoPresupuestoDetalle tpd', 'tpd.idTipoPresupuestoDetalle = pds.idTipoPresupuestoDetalle', 'LEFT')
			->where('pds.estado', 1)
			->where('pds.idPresupuestoDetalle', $id)
			->get();
		return $query;
	}

	public function anularPresupuesto($id)
	{

		$preDet = $this->db->where('idPresupuesto', $id)->where('estado', 1)->get('compras.presupuestoDetalle')->result_array();

		foreach ($preDet as $v) {
			$updateDet[] = [
				'estado' => 0,
				'idPresupuestoDetalle' => $v['idPresupuestoDetalle']
			];

			$preDetSub = $this->db->where('idPresupuestoDetalle', $v['idPresupuestoDetalle'])->where('estado', 1)->get('compras.presupuestoDetalleSub')->result_array();
			foreach ($preDetSub as $vd) {
				$updateDetSub[] = [
					'estado' => 0,
					'idPresupuestoDetalleSub' => $vd['idPresupuestoDetalleSub']
				];
			}
		}

		$this->db->update('compras.presupuesto', ['estado' => 0], ['idPresupuesto' => $id]);
		$this->db->update('compras.presupuestoHistorico', ['estado' => 0], ['idPresupuesto' => $id]);
		$this->db->update('compras.presupuestoCargo', ['estado' => 0], ['idPresupuesto' => $id]);
		$this->db->update('compras.presupuestoDetalle', ['estado' => 0], ['idPresupuesto' => $id]);
		$this->db->update_batch('compras.presupuestoDetalleSueldo', $updateDet, 'idPresupuestoDetalle');
		$this->db->update_batch('compras.presupuestoDetalleSub', $updateDet, 'idPresupuestoDetalle');
		$this->db->update_batch('compras.presupuestoDetalleSubCargo', $updateDetSub, 'idPresupuestoDetalleSub');

		return true;
	}

	public function getDocumento($id)
	{
		$query = $this->db
			->select('d.*, a.nombre as area, p.nombre as personal, d.nombre as documento')
			->from('compras.documento d')
			->join('compras.area a', 'a.idArea = d.idArea', 'LEFT')
			->join('compras.personal p', 'p.idPersonal = d.idPersonal', 'LEFT')
			->where('d.idDocumento', $id)
			->get();
		return $query;
	}
}