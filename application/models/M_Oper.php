<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Oper extends MY_Model
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

	public function obtenerInformacionComprasOper($params = [])
	{
		$this->db
			->select('od.*')
			->from('compras.operDetalle od')
			->join('compras.oper o', 'o.idOper = od.idOper', 'LEFT');

		if (isset($params['idCotizacion'])) $this->db->where('od.idCotizacion', $params['idCotizacion']);
		if (isset($params['idOper'])) $this->db->where('od.idOper', $params['idOper']);
	}
	public function obtenerInformacionOper($params = [])
	{
		$this->db
			->select("o.*,
							od.idOperDetalle,
							od.idItem,
							od.idTipo,
							od.costoUnitario AS costo_item,
							od.cantidad AS cantidad_item,
							od.gap AS gap_item,
							od.costoSubTotal AS cs_item,
							od.costoSubTotalGap AS csg_item,
							od.idProveedor,
							i.nombre AS item,
							'Coordinadora de compras' AS usuarioReceptor,
							ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') AS usuarioRegistro,
							cu.nombre AS cuenta,
							cc.subcanal AS centroCosto
							")
			->from('orden.oper o')
			->join('orden.operDetalle od', 'od.idOper = o.idOper and od.estado=1')
			->join('compras.item i', 'i.idItem = od.idItem', 'LEFT')
			->join('sistema.usuario ue', 'ue.idUsuario = o.idUsuarioReg', 'LEFT')
			->join('rrhh.dbo.empresa cu', 'cu.idEmpresa=o.idCuenta', 'LEFT')
			->join('rrhh.dbo.empresa_canal cc', 'cc.idEmpresaCanal=o.idCentroCosto', 'LEFT');
		// Where
		if (isset($params['idOper'])) {
			$this->db->where('o.idOper', $params['idOper']);
		}

		$this->db->order_by('o.idOper', 'DESC');
		return $this->db->get();
	}

	public function obtenerInformacionOperSinCot($params = [])
	{
		$this->db
			->select("o.*,
							od.idOperDetalle,
							od.idItem,
							od.idTipo,
							od.costoUnitario AS costo_item,
							od.cantidad AS cantidad_item,
							od.gap AS gap_item,
							od.costoSubTotal AS cs_item,
							od.costoSubTotalGap AS csg_item,
							i.nombre AS item,
							'Coordinadora de compras' AS usuarioReceptor,
							ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') AS usuarioRegistro,
							cu.nombre AS cuenta,
							cc.subcanal AS centroCosto,
							o.numeroOC as poCliente
							")
			->from('orden.oper o')
			->join('orden.operDetalle od', 'od.idOper = o.idOper and od.estado=1')
			->join('compras.item i', 'i.idItem = od.idItem', 'LEFT')
			->join('sistema.usuario ue', 'ue.idUsuario = o.idUsuarioReg', 'LEFT')
			->join('rrhh.dbo.empresa cu', 'cu.idEmpresa=o.idCuenta', 'LEFT')
			->join('rrhh.dbo.empresa_canal cc', 'cc.idEmpresaCanal=o.idCentroCosto', 'LEFT');
		// Where
		if (isset($params['idOper'])) {
			$this->db->where('o.idOper', $params['idOper']);
		}

		$this->db->order_by('o.idOper', 'DESC');
		return $this->db->get();
	}

	

	public function obtenerInformacionOperSubItem($params = [])
	{
		$this->db
			->select('ods.*, um.nombre as unidadMedida')
			->from('orden.operDetalleSub ods')
			->join('compras.unidadMedida um', 'um.idUnidadMedida = ods.idUnidadMedida', 'left');
		// Where
		if (isset($params['idOperDetalle'])) {
			$this->db->where('ods.idOperDetalle', $params['idOperDetalle']);
		}
		return $this->db->get();
	}

	public function obtenerCuenta(array $params = [])
	{
		$this->db
			->select('*')
			->from('rrhh.dbo.empresa')
			->where('estado', '1')
			->order_by('nombre');
		return $this->db->get();
	}

	public function obtenerCentroCosto(array $params = [])
	{
		$this->db
			->select('*, idEmpresa as idDependiente')
			->from('rrhh.dbo.empresa_canal')
			// ->where('estado','1')
			->order_by('canal');
		return $this->db->get();
	}
	public function obtenerItem(array $params = [])
	{
		$this->db
			->select('*')
			->from('compras.item')
			->where('estado', '1')
			->order_by('nombre');
		return $this->db->get();
	}

	public function obtenerTipo(array $params = [])
	{
		$this->db
			->select('*')
			->from('compras.itemTipo')
			->where('estado', '1')
			->order_by('nombre');
		return $this->db->get();
	}

	public function obtenerInformacionOperPdf($params = [])
	{
		$sql = "
		SELECT o.*,
			'Coordinadora de compras' AS usuarioReceptor,
			ue.nombres + ' ' + ISNULL(ue.apePaterno, '') + ' ' + ISNULL(ue.apeMaterno, '') AS usuarioRegistro,
			cu.nombre AS cuenta, cc.subcanal AS centroCosto 
			FROM orden.oper o 

			LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg 
			LEFT JOIN rrhh.dbo.empresa cu ON cu.idEmpresa = o.idCuenta 
			LEFT JOIN rrhh.dbo.empresa_canal cc ON cc.idEmpresaCanal = o.idCentroCosto 
			WHERE o.idOper = " . $params['id'] . " ORDER BY o.idOper DESC
		";
		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerInformacionDetalleOper($params = [])
	{
		$sql = "
			SELECT 
				o.*, od.idOperDetalle, od.idItem,
				od.idTipo, od.costoUnitario AS costo_item,
				od.cantidad AS cantidad_item, od.gap AS gap_item,
				od.costoSubTotal AS cs_item, od.costoSubTotalGap AS csg_item,
				i.nombre AS item, 'Coordinadora de compras' AS usuarioReceptor,
				ue.nombres + ' ' + ISNULL(ue.apePaterno, '') + ' ' + ISNULL(ue.apeMaterno, '') AS usuarioRegistro,
				cu.nombre AS cuenta, cc.subcanal AS centroCosto 
				, um.nombre as unidadMedida
			FROM orden.oper o 
			JOIN orden.operDetalle od ON od.idOper = o.idOper and od.estado = 1 
			LEFT JOIN compras.item i ON i.idItem = od.idItem 
			LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg 
			LEFT JOIN rrhh.dbo.empresa cu ON cu.idEmpresa = o.idCuenta 
			LEFT JOIN rrhh.dbo.empresa_canal cc ON cc.idEmpresaCanal = o.idCentroCosto 
			LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = od.idTipo
			WHERE o.idOper = " . $params['idoper'] . " ORDER BY o.idOper DESC
			";
		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerOperDetalleSub($params = [])
	{
		$sql = "
		SELECT * FROM orden.operDetalleSub WHERE idOperDetalle = '3'
		";
		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
