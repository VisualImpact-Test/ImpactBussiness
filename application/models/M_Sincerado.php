<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Sincerado extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function obtenerInformacionDelPresupuestoValido($params = [])
	{
		$this->db
			->select('pv.*, os.nombre as ordenServicio, mon.nombreMoneda as moneda, os.fechaIni, os.chkUtilizarCliente, os.idCliente, os.idCuenta, os.idCentroCosto')
			->from('compras.presupuestoValido pv')
			->join('compras.presupuestoHistorico ph', 'ph.idPresupuesto = pv.idPresupuesto AND ph.idPresupuestoHistorico = pv.idPresupuestoHistorico')
			->join('compras.ordenServicio os', 'os.idOrdenServicio = ph.idOrdenServicio')
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda')
			->order_by('pv.idPresupuestoValido desc');
		if (isset($params['idPresupuestoValido'])) $this->db->where('pv.idPresupuestoValido', $params['idPresupuestoValido']);

		return $this->db->get();
	}
	public function getSinceradoCargo($id, $idH = null)
	{
		$this->db
			->select('pc.*, c.nombre as cargo')
			->from('compras.sinceradoCargo pc')
			->join('rrhh.dbo.CargoTrabajo c', 'c.idCargoTrabajo = pc.idCargo', 'LEFT')
			->where('pc.idSincerado', $id);

		$this->db->where('pc.estado', 1);

		$query = $this->db->get();
		return $query;
	}
	public function getSincerado()
	{
		$this->db
			->select('
			s.idSincerado,
			s.idPresupuesto,
			s.idPresupuestoHistorico,
			s.idOrdenServicio,
			s.fecha_seleccionada,
			s.estado,
			os.nombre,
			os.idCuenta,
			os.idCentroCosto,
			os.chkUtilizarCliente,
			c.razonSocial as cuenta,
			cc.subcanal AS centroCosto,
			mon.nombreMoneda as moneda')
			->from('compras.sincerado s')
			->join('compras.ordenServicio os', 'os.idOrdenServicio = s.idOrdenServicio')
			->join('compras.presupuestoHistorico p', 'p.idPresupuesto = s.idPresupuesto AND p.idPresupuestoHistorico = s.idPresupuestoHistorico')
			->join('rrhh.dbo.Empresa c', 'os.idCuenta = c.idEmpresa', 'LEFT')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = os.idCentroCosto', 'LEFT')
			->join('compras.cliente cl', 'cl.idCliente = os.idCliente', 'LEFT')
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda', 'LEFT');
		// ->where('pc.idSincerado', $id);

		$this->db->where('s.estado', 1);

		$query = $this->db->get();
		return $query;
	}
}
