<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ProveedorServicio extends MY_Model
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

	public function obtenerDatosReporte($data = [])
	{
		$this->db
			->distinct()
			->select("
				CONVERT(VARCHAR, MIN(cd.fechaEntrega), 103) AS fechaEntrega,
				ocd.idOrdenCompra,
				o.seriado,
				CONVERT(VARCHAR, c.fechaEmision, 103) AS fechaEmision,
				c.nombre, 
				c.motivo, 
				c.total,
				c.idCotizacion,
				cc.nombre AS cuentaCentroCosto,
				c.motivoAprobacion,
				pr.razonSocial AS proveedor,
				cu.nombre AS cuenta, 
				pr.idProveedor,
				c.codOrdenCompra, 
				0 AS flagOcLibre,
				o.estadoval,
				REPLACE(CONVERT(VARCHAR, CONVERT(DATE, o.fechaReg), 103), '-', '/') AS fechaReg,
				mp.cantDias,
				REPLACE(CONVERT(VARCHAR, DATEADD(DAY, mp.cantDias, CONVERT(DATE, o.fechaReg)), 103), '-', '/') AS fechaVencimiento")
			->from('compras.ordenCompraDetalle ocd')
			->join('compras.ordenCompra o', 'o.idOrdenCompra = ocd.idOrdenCompra', 'INNER')
			->join('compras.proveedor pr', 'pr.idProveedor = o.idProveedor', 'INNER')
			->join('compras.cotizacionDetalle cd', 'ocd.idCotizacionDetalle = cd.idCotizacionDetalle', 'INNER')
			->join('compras.cotizacion c', 'c.idCotizacion = cd.idCotizacion', 'INNER')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'c.idCentroCosto = cc.idCuentaCentroCosto', 'INNER')
			->join('visualImpact.logistica.cuenta cu', 'c.idCuenta = cu.idCuenta', 'INNER')
			->join('compras.metodoPago mp', 'mp.idMetodoPago = o.idMetodoPago', 'INNER')
			->where('ocd.estado', '1')
			->group_by("
				ocd.idOrdenCompra,
				o.seriado,
				CONVERT(VARCHAR, c.fechaEmision, 103),
				c.nombre, 
				c.motivo, 
				c.total,
				c.idCotizacion,
				cc.nombre,
				c.motivoAprobacion,
				pr.razonSocial,
				cu.nombre, 
				pr.idProveedor,
				c.codOrdenCompra,
				o.estadoval,
				CONVERT(DATE, o.fechaReg),
				mp.cantDias,
				DATEADD(DAY, mp.cantDias, CONVERT(DATE, o.fechaReg))")
			->order_by('ocd.idOrdenCompra DESC');

		if ($this->idUsuario != 1) $this->db->where('pr.demo', 0);
		// isset($params['idProveedor']) ? $this->db->where('cd.idProveedor', $params['idProveedor']) : '';

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE) =', $data['fechaEmision']);
		if (isset($data['idCuenta'])) $this->db->where('c.idCuenta', $data['idCuenta']);
		if (isset($data['idCentroCosto'])) $this->db->where('c.idCentroCosto', $data['idCentroCosto']);
		if (isset($data['codPo_'])) $this->db->like('c.codOrdenCompra', $data['codPo_']);

		return $this->db->get();
	}

	public function obtenerDatosReporte1($data = [])
	{
		$this->db
			->distinct()
			->select("
				CONVERT(VARCHAR, MIN(cd.fechaEntrega), 103) AS fechaEntrega,
				cp.idOrdenCompra,
				cd.seriado,
				CONVERT(VARCHAR, cd.fechaReg, 103) AS fechaEmision,
				(NULL) AS nombre,
				(NULL) AS motivo,
				cd.total,
				(NULL) AS idCotizacion,
				cc.nombre AS cuentaCentroCosto,
				(NULL) AS motivoAprobacion,
				pr.razonSocial AS proveedor,
				cu.nombre AS cuenta,
				cd.idProveedor,
				cd.requerimiento AS codOrdenCompra,
				1 AS flagOcLibre,
				cd.estadoval,
				REPLACE(CONVERT(VARCHAR, CONVERT(DATE, cd.fechaReg), 103), '-', '/') AS fechaReg,
				mp.cantDias,
				REPLACE(CONVERT(VARCHAR, DATEADD(DAY, mp.cantDias, CONVERT(DATE, cd.fechaReg)), 103), '-', '/') AS fechaVencimiento")
			->from('orden.ordenCompra cd')
			->join('orden.ordenCompraDetalle cp', 'cd.idOrdenCompra = cp.idOrdenCompra', 'INNER')
			->join('compras.proveedor pr', 'pr.idProveedor = cd.idProveedor', 'INNER')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'cd.idCentroCosto = cc.idCuentaCentroCosto', 'LEFT')
			->join('visualImpact.logistica.cuenta cu', 'cd.idCuenta = cu.idCuenta', 'INNER')
			->join('compras.metodoPago mp', 'mp.idMetodoPago = cd.idMetodoPago', 'INNER')
			->where('cd.estado', '1')
			->group_by("
				cd.seriado,
				CONVERT(VARCHAR, cd.fechaReg, 103),
				cd.total,
				cc.nombre,
				cu.nombre,
				cd.idProveedor,
				pr.razonSocial,
				cd.requerimiento,
				cp.idOrdenCompra,
				cd.estadoval,
				CONVERT(DATE, cd.fechaReg),
				mp.cantDias,
				DATEADD(DAY, mp.cantDias, CONVERT(DATE, cd.fechaReg))")
			->order_by('cp.idOrdenCompra', 'DESC');

		if ($this->idUsuario != 1) $this->db->where('pr.demo', 0);
		// isset($params['idProveedor']) ? $this->db->where('cd.idProveedor', $params['idProveedor']) : '';

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE) =', $data['fechaEmision']);
		if (isset($data['idCuenta'])) $this->db->where('c.idCuenta', $data['idCuenta']);
		if (isset($data['idCentroCosto'])) $this->db->where('c.idCentroCosto', $data['idCentroCosto']);
		if (isset($data['codPo_'])) $this->db->like('c.codOrdenCompra', $data['codPo_']);

		return $this->db->get();
	}
}
