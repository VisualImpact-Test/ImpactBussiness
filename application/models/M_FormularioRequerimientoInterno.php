<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_FormularioRequerimientoInterno extends MY_Model
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
	public function loginSolicitanteInterno($params = [])
	{
		$sql = "
		SELECT * FROM compras.solicitanteInterno
			WHERE correoElectronico = '{$params['email']}'
				AND clave = '{$params['password']}'
		";

		return $this->db->query($sql);
	}
	public function obtenerItemTipo($params = [])
	{
		$sql = "
			SELECT
				idItemTipo AS id, nombre AS value
			FROM compras.itemTipo
			WHERE estado = 1 AND (idItemTipo = 1 OR idItemTipo = 2);
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function obtenerItemServicio($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idProveedor']) ? ' AND it.idProveedor = ' . $params['idProveedor'] : '';
		$filtros .= !empty($params['idItem']) ? ' AND i.idItem = ' . $params['idItem'] : '';
		

		$sql = "
		DECLARE @fechaHoy DATE = GETDATE();
				WITH listTarifario AS (
					SELECT
						ta.*
						/* , ROW_NUMBER() OVER(PARTITION BY ta.idItem ORDER BY ta.idItem,ta.flag_actual) ntarifario */
						, ROW_NUMBER() OVER(PARTITION BY ta.idItem ORDER BY ta.idItem, fechaVigencia desc, CASE WHEN ta.flag_actual IS NULL THEN 2 ELSE ta.flag_actual END) ntarifario
						, art.peso pesoLogistica
					FROM compras.item a
					JOIN compras.itemTarifario ta ON a.idItem = ta.idItem
					LEFT JOIN visualimpact.logistica.articulo art ON art.idArticulo = a.idItemLogistica
					WHERE (ta.flag_actual = 1 OR ta.flag_actual IS NULL)
					
				), listImagenes AS (
					SELECT idItem, COUNT(*) as cantidadImagenes
					FROM compras.itemImagen
					WHERE estado = 1
					GROUP BY idItem
				)
				select 
					i.idItem as value,
					i.nombre + ' ' + ISNULL(i.caracteristicas, '') as label,
					it.costo,
					it.idProveedor,
					pr.razonSocial as proveedor,
					i.idItemTipo as tipo,
					CASE
					WHEN it.fechaVigencia IS NULL THEN 'gray'
					WHEN ISNULL(DATEDIFF(DAY,it.fechaVigencia,@fechaHoy),0) <= -2 THEN 'green'
					WHEN ISNULL(DATEDIFF(DAY,it.fechaVigencia,@fechaHoy),0) > -2 AND ISNULL(DATEDIFF(DAY,it.fechaVigencia,@fechaHoy),0) <= 0 THEN 'yellow'
					ELSE 'red' END
					AS semaforoVigencia,
					ISNULL(DATEDIFF(DAY,it.fechaVigencia,@fechaHoy),0)*-1 AS diasVigencia,
					i.idItemLogistica,
					it.pesoLogistica,
					ISNULL(i.flagCuenta,0) flagCuenta,
					CASE WHEN ISNULL(DATEDIFF(DAY,it.fechaVigencia,@fechaHoy),0) > 15 THEN 1 ELSE 0 END cotizacionInterna,
					i.caracteristicas,
					ISNULL(img.cantidadImagenes, 0) as cantidadImagenes,
					i.idUnidadMedida
				from compras.item i
				JOIN compras.itemTipo iTipo ON i.idItemTipo = iTipo.idItemTipo
				JOIN listTarifario it on it.idItem = i.idItem and it.ntarifario=1
				LEFT JOIN compras.proveedor pr ON it.idProveedor = pr.idProveedor
				LEFT JOIN listImagenes img ON img.idItem = i.idItem
				WHERE i.estado = 1 AND (iTipo.nombre = 'Articulo' OR iTipo.nombre = 'Servicio')
				{$filtros}
				order by 2
		";

		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	public function insertarRequerimientoInterno($params = [])
	{
		$query = $this->db->insert($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}
	public function insertarRequerimientoInternoDetalle($params = [])
	{
		$insertArchivos = [];
		foreach ($params['insert'] as $k => $insert) {
			$queryRequerimientoInternoDetalle = $this->db->insert($params['tabla'], $insert);
			$idRequerimientoInternoDetalle = $this->db->insert_id();

			if (!empty($params['archivosDeImagen'][$k])) {
				foreach ($params['archivosDeImagen'][$k] as $archivo) {
					$insertArchivos[] = [
						'idRequerimientoInterno' => $insert['idRequerimientoInterno'],
						'idRequerimientoInternoDetalle' => $idRequerimientoInternoDetalle,
						'idTipoArchivo' => $archivo['idTipoArchivo'],
						'nombre_inicial' => $archivo['nombre_inicial'],
						'nombre_archivo' => '../item/' . $archivo['nombre_archivo'],
						'nombre_unico' => $archivo['nombre_unico'],
						'extension' => $archivo['extension'],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}

			if (!empty($params['archivos'][$k])) {
				foreach ($params['archivos'][$k] as $archivo) {
					$tipoArchivo = explode('/', $archivo['type']);
					$archivoName = $this->saveFileWasabi($archivo);

					$insertArchivos[] = [
						'idRequerimientoInterno' => $insert['idRequerimientoInterno'],
						'idRequerimientoInternoDetalle' => $idRequerimientoInternoDetalle,
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}
		}

		if ($queryRequerimientoInternoDetalle) {
			$this->resultado['query'] = $queryRequerimientoInternoDetalle;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();

			if (!empty($insertArchivos)) {
				$this->db->insert_batch('compras.requerimientoInternoDetalleArchivos', $insertArchivos);
			}
		}

		return $this->resultado;
	}
	public function actualizarRequerimientoInterno($params = [])
	{
		$query = $this->db->update($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}

	public function obtenerInformacionRequerimientoInterno($params = [])
	{
		$sql = "
			SELECT ri.idRequerimientoInterno, ri.codRequerimientoInterno, CONVERT(DATE, ri.fechaEmision) AS fechaEmision,
			ri.nombre AS nombreRequerimiento, c.nombre AS cuenta, cc.canal AS centroCosto,
			rie.nombre AS estado, rie.icono, ri.estado AS reqIntEstado, riE.idRequerimientoInternoEstado
			FROM compras.requerimientoInterno ri
			LEFT JOIN rrhh.dbo.Empresa c ON c.idEmpresa = ri.idCuenta
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idEmpresaCanal = ri.idCentroCosto
			LEFT JOIN compras.requerimientoInternoEstado rie ON rie.idRequerimientoInternoEstado = ri.idRequerimientoInternoEstado
			WHERE ri.idUsuario = " . $params . " 
			ORDER BY fechaEmision DESC
		";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function insertarAprobacionFinanzas($data = [])
	{
		$insert = true;
		$archivoName = $this->saveFileWasabi($data);
		$tipoArchivo = explode('/', $data['type']);
		$insertArchivos[] = [
			'idRequerimientoInterno' => $data['idRequerimientoInterno'],
			'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
			'nombre_inicial' => $data['name'],
			'nombre_archivo' => $archivoName,
			'nombre_unico' => $data['nombreUnico'],
			'extension' => FILES_WASABI[$tipoArchivo[1]],
			'estado' => true,
			'idUsuarioReg' => $this->idUsuario,
			'flag_aprobacion' => true,
		];

		if (!empty($insertArchivos)) {
			$insert = $this->db->insert_batch('compras.requerimientoInternoDetalleArchivos', $insertArchivos);
		}
		return $insert;
	}
	public function obtenerInformacionRequerimientoInternoDetalle($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idRequerimientoInterno']) ? ' AND ri.idRequerimientoInterno = ' . $params['idRequerimientoInterno'] : '';
		
		$sql = "
			SELECT ri.idRequerimientoInterno, ri.nombre AS requerimientoInterno, c.nombre AS cuenta, rid.costoReferencial,
				cc.nombre AS cuentaCentroCosto, it.nombre AS itemTipo, i.nombre AS item, rid.cantidad, ei.nombre AS estadoItem,
				ri.codRequerimientoInterno, riE.nombre AS requerimientoIEstado, CONVERT(VARCHAR, ri.fechaEmision, 103) AS fechaEmision,
				ei.idItemEstado, riE.nombre AS requerimientoInternoDetalleEstado, CONVERT( VARCHAR, rid.fechaCreacion, 103) AS fechaCreacion, 
				CONVERT( VARCHAR, rid.fechaModificacion, 103) + ' ' + CONVERT( VARCHAR, rid.fechaModificacion, 108) AS fechaModificacion,
				p.razonSocial AS proveedor, ri.idTipoMoneda, ri.idUsuarioAprobacion, p.idProveedor,
				ri.comentario, rid.idRequerimientoInternoDetalle, rid.idItem, rid.idItemTipo, p.idProveedor AS id, p.razonSocial AS value
			FROM compras.requerimientoInterno ri
			INNER JOIN compras.requerimientoInternoDetalle rid ON rid.idRequerimientoInterno = ri.idRequerimientoInterno
			INNER JOIN compras.proveedor p ON p.idProveedor = rid.idProveedor
			INNER JOIN compras.item i ON i.idItem = rid.idItem
			INNER JOIN compras.itemTipo it ON rid.idItemTipo = it.idItemTipo
			INNER JOIN compras.itemEstado ei ON ei.idItemEstado = rid.idItemEstado
			INNER JOIN compras.requerimientoInternoEstado riE ON riE.idRequerimientoInternoEstado = ri.idRequerimientoInternoEstado
			INNER JOIN visualImpact.logistica.cuenta c ON ri.idCuenta = c.idCuenta
			INNER JOIN visualImpact.logistica.cuentaCentroCosto cc ON ri.idCentroCosto = cc.idCuentaCentroCosto
			{$filtros}
			ORDER BY itemTipo, rid.idRequerimientoInternoDetalle
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenerInformacionProveedores($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['estadoProveedor']) ? ' AND p.idProveedorEstado != ' . $params['estadoProveedor'] : '';
		$orden = !empty($params['order_by']) ? 'ORDER BY ' . $params['order_by'] : "ORDER BY p.idProveedor DESC";


		$sql = "
			SELECT p.idProveedor AS id, p.razonSocial AS value FROM compras.proveedor p
			JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = p.idProveedorEstado
			WHERE 1 = 1
			{$filtros}
			{$orden}
		";

		$query = $this->db->query($sql);

		if ($query) {

			//echo $this->db->last_query();
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenerInformacionRequerimientoInternoArchivos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idRequerimientoInterno']) ? " AND ri.idRequerimientoInterno = {$params['idRequerimientoInterno']} "  : "";
		$filtros .= !empty($params['aprobacion']) ? " AND rida.flag_aprobacion = {$params['aprobacion']} " : "";
		$filtros .= !empty($params['idTipoArchivo']) ? " AND rida.idTipoArchivo = {$params['idTipoArchivo']} " : "";

		$sql = "
			SELECT
				ri.idRequerimientoInterno,
				rida.idRequerimientoInternoDetalleArchivo,
				rida.idTipoArchivo,
				rida.nombre_inicial,
				rida.nombre_archivo,
				rida.extension,
				rida.idRequerimientoInternoDetalle
			FROM
			compras.requerimientoInterno ri
			LEFT JOIN compras.requerimientoInternoDetalleArchivos rida ON rida.idRequerimientoInterno = ri.idRequerimientoInterno
			WHERE
			1 = 1 AND rida.nombre_archivo is not null and rida.estado = 1 
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
	public function obtenerCuenta($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idUsuario']) ? " AND cu.idUsuario = {$params['idUsuario']} "  : "";
		
		$sql = "
			DECLARE @hoy DATE = GETDATE();
			SELECT DISTINCT
				emp.idEmpresa id,
				emp.razonSocial value
			FROM
			rrhh.dbo.Empresa emp
			JOIN rrhh.dbo.empleadoCanalSubCanal ec ON ec.idEmpresa = emp.idEmpresa
				AND General.dbo.fn_fechaVigente(ec.fecInicio,ec.fecFin,@hoy,@hoy)=1
			JOIN rrhh.dbo.Empleado e ON e.idEmpleado = ec.idEmpleado
			JOIN compras.requerimientoInternoCuenta_Usuario cu ON cu.idCuenta = emp.idEmpresa
			WHERE
				e.flag = 'activo'
				AND emp.estado = 1
				{$filtros}
			ORDER BY emp.razonSocial
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function obtenerUsuarioAprobar($params = [])
	{
		$sql = "
			SELECT DISTINCT riu.idUsuarioAprobacion AS id, u.nombres + ' ' + u.apePaterno + ' ' + u.apeMaterno AS value,
				ut.nombre
			FROM sistema.usuario u
			INNER JOIN sistema.usuarioHistorico uh ON uh.idUsuario = uh.idUsuario
			INNER JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario
			INNER JOIN compras.requerimientoInternoUsuarioAprobacion riu ON riu.idUsuario = u.idUsuario
			WHERE ut.nombre = 'ADMINISTRADOR'
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	public function obtenerPrecioProveedorTarifario($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['estadoProveedor']) ? ' AND p.idProveedorEstado != ' . $params['estadoProveedor'] : '';
		$orden = !empty($params['order_by']) ? 'ORDER BY ' . $params['order_by'] : "ORDER BY p.idProveedor DESC";


		$sql = "
			SELECT p.idProveedor AS id, p.razonSocial AS value FROM compras.proveedor p
			JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = p.idProveedorEstado
			WHERE 1 = 1
			{$filtros}
			{$orden}
		";

		$query = $this->db->query($sql);

		if ($query) {

			//echo $this->db->last_query();
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
