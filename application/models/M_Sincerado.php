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
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda');
		if (isset($params['idPresupuestoValido'])) $this->db->where('pv.idPresupuestoValido', $params['idPresupuestoValido']);
		
		return $this->db->get();
	}
}
