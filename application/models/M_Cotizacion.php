<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Cotizacion extends MY_Model
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

	public function obtenerDetallePDV($id)
	{
		return $this->db->distinct()->select("l.*, departamento, provincia, case when l.idDistrito is null then '' else distrito end as distrito")->where('idCotizacionDetalle', $id)
			->join('General.dbo.ubigeo ubi_zc', 'l.idDepartamento = ubi_zc.cod_departamento AND ISNULL(l.idProvincia, 1) = (CASE WHEN l.idProvincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
		AND ISNULL(l.idDistrito , 1) = ubi_zc.cod_distrito
		AND ubi_zc.estado = 1', 'left')
			->get('compras.cotizacionDetalleUbigeo l')->result_array();
	}
	public function obtenerCuenta($params = [])
	{
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
		WHERE
			e.flag = 'activo'
			AND emp.estado = 1
		ORDER BY emp.razonSocial
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function getPropuestaItemArchivos($params = [])
	{
		$this->db
			->select('*')
			->from('compras.propuestaItemArchivo pia')
			->join('compras.propuestaItem pi', 'pia.idPropuestaItem = pi.idPropuestaItem', 'LEFT')
			->join('compras.cotizacionDetalleProveedorDetalle pd', 'pd.idCotizacionDetalleProveedorDetalle = pi.idCotizacionDetalleProveedorDetalle', 'LEFT')
			->join('compras.cotizacionDetalle cd', 'cd.idCotizacionDetalle = pd.idCotizacionDetalle', 'LEFT')
			->where('cd.idCotizacionDetalle', $params['idCotizacionDetalle'])
			->where('cd.estado', '1');
		return $this->db->get();
	}

	public function getCotizacionProveedorArchivosSeleccionados($params = [])
	{
		// PARA TRAER LOS ARCHIVOS EN LAS COTIZACIONES DEL PROVEEDOR QUE HAN SIDO "SELECCIONADAS" EN LA COTIZACION
		// PARA QUE LOGISTICA PUEDA VISUALIZAR LOS ARCHIVOS QUE ADJUNTA EL PROVEEDOR

		$this->db
			->select('cdpda.*')
			->from('compras.cotizacionDetalleProveedorDetalleArchivos cdpda')
			->join('compras.cotizacionDetalleProveedorDetalle cdpd', 'cdpd.idCotizacionDetalleProveedorDetalle=cdpda.idCotizacionDetalleProveedorDetalle', 'LEFT')
			->join('compras.cotizacionDetalle cd', 'cd.idCotizacionDetalle=cdpd.idCotizacionDetalle', 'LEFT')
			->join('compras.cotizacionDetalleProveedor cdp', 'cdp.idCotizacion=cd.idCotizacion and cdp.idProveedor=cd.idProveedor and cdp.idCotizacionDetalleProveedor=cdpd.idCotizacionDetalleProveedor', 'LEFT')
			->where('cd.idProveedor is not null') //Este where es importante para que funcione, segun la requerido.
			->where('cdp.estado', '1')
			->where('cdpda.estado', '1')
			->where('cdpd.estado', '1')
			->where('cd.estado', '1')
			->where('cd.idCotizacionDetalle', $params['idCotizacionDetalle']);
		return $this->db->get();
	}

	public function obtenerCuentaCentroCosto($params = [])
	{
		$filtros = '';
		!empty($params['estadoCentroCosto']) ? $filtros .= " AND c.estado_centro = 1" : "";

		$sql = "
			DECLARE @hoy DATE = GETDATE();
			SELECT DISTINCT
				c.idEmpresa idDependiente,
				c.idEmpresaCanal id,
				c.canal + ' - ' + c.subcanal value
			FROM
			rrhh.dbo.empresa_Canal c
			JOIN rrhh.dbo.empleadoCanalSubCanal ec ON ec.idEmpresa = c.idEmpresa
				AND General.dbo.fn_fechaVigente(ec.fecInicio,ec.fecFin,@hoy,@hoy)=1
			JOIN rrhh.dbo.Empresa emp ON emp.idEmpresa = c.idEmpresa
			JOIN rrhh.dbo.Empleado e ON e.idEmpleado = ec.idEmpleado
			WHERE
				e.flag = 'activo' 
				-- Excluir canal Trade -- Se quito la exclusiòn por el correo de margarita 2023-11-02
				-- AND c.idCanal not in (1)
				AND c.subcanal IS NOT NULL
				{$filtros}
			ORDER BY id
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerItemTipo($params = [])
	{
		$sql = "
			SELECT
				idItemTipo AS id
				, nombre AS value
			FROM compras.itemTipo
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerPrioridadCotizacion($params = [])
	{
		$sql = "
			SELECT
				idPrioridad AS id
				, nombre AS value
			FROM compras.cotizacionPrioridad
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionCotizacion($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['cuenta']) ? ' AND p.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND p.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['cotizacion']) ? " AND p.nombre LIKE '%" . $params['cotizacion'] . "%'" : "";
		$filtros .= !empty($params['estadoCotizacion']) ? " AND p.idCotizacionEstado IN (" . $params['estadoCotizacion'] . ")" : "";
		$filtros .= !empty($params['id']) ? " AND p.idCotizacion IN (" . $params['id'] . ")" : "";
		$filtros .= !empty($params['idDiferente']) ? " AND p.idCotizacion !=" . $params['idDiferente'] : '';
		$filtros .= !empty($params['idUsuarioReg']) ? ' AND p.idUsuarioReg = ' . $params['idUsuarioReg'] : '';
		$filtros .= !empty($params['fechaDesde']) ? ' AND p.fechaEmision >= Convert(date,\'' . $params['fechaDesde'] . '\') ' : '';
		$filtros .= !empty($params['fechaHasta']) ? ' AND p.fechaEmision <= Convert(date,\'' . $params['fechaHasta'] . '\') ' : '';
		$filtros .= !empty($params['ocGenerado']) ? ($params['ocGenerado'] != '0' ? " AND p.idCotizacionEstado < 8 " : '') : '';
		$filtros .= $this->idTipoUsuario != '1' ? " AND p.idSolicitante != 1" : '';

		$codTransporte = COD_TRANSPORTE['id'];
		$sql = "
			DECLARE @hoy DATE = GETDATE();
			WITH lst_historico_estado AS (
				SELECT 
				idCotizacionEstadoHistorico,
				idCotizacionEstado,
				idCotizacionInternaEstado,
				idCotizacion,
				fechaReg,
				idUsuarioReg,
				estado,
				ROW_NUMBER() OVER (PARTITION BY idCotizacion,idCotizacionEstado ORDER BY idCotizacionEstado) fila
				FROM
				compras.cotizacionEstadoHistorico
			)
			SELECT DISTINCT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, p.idCuenta
				, p.idCentroCosto idCuentaCentroCosto
				--, cc.nombre AS cuentaCentroCosto
				, c.razonSocial AS cuenta
				, cc.subcanal AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, ce.icono
				, p.estado
				, p.fechaRequerida
				, p.diasValidez
				, p.idSolicitante
				, p.fechaDeadline
				, p.flagIgv igv
				, p.fee
				, p.idCotizacionEstado
				, p.idPrioridad
				, p.motivo
				, p.comentario
				, p.total
				, p.codOrdenCompra
				, p.motivoAprobacion
				, p.montoOrdenCompra
				, od.idOper
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND cotizacionInterna = 1) nuevos
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND estado = 1) cantDetalle
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND idItemTipo = $codTransporte) cantidadTransporte
				, ISNULL((SELECT CASE WHEN DATEDIFF(DAY,fechaReg,@hoy) <= p.diasValidez THEN 1 ELSE 0 END FROM lst_historico_estado WHERE idCotizacion = p.idCotizacion AND p.idCotizacionEstado IN(4,5) AND idCotizacionEstado = 4 AND fila = 1),1) cotizacionValidaCliente
				, p.mostrarPrecio AS flagMostrarPrecio
				, u.nombres + ' ' + u.apePaterno + ' ' + u.apeMaterno as usuario
				, (SELECT CASE WHEN COUNT(idCotizacionDetalle)>0 THEN 1 ELSE 0 END FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND idItemTipo = 5) tipoPersonal
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN rrhh.dbo.Empresa c ON p.idCuenta = c.idEmpresa
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idEmpresaCanal = p.idCentroCosto
			LEFT JOIN compras.operDetalle od ON od.idCotizacion = p.idCotizacion
				AND od.estado = 1
			LEFT JOIN sistema.usuario u ON u.idUsuario=p.idUsuarioReg
			WHERE 1 = 1
			{$filtros}
			ORDER BY p.idCotizacion DESC
		";
		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerInformacionCotizacionFiltro($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['cuenta']) ? ' AND p.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND p.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['cotizacion']) ? " AND p.nombre LIKE '%" . $params['cotizacion'] . "%'" : "";
		$filtros .= !empty($params['estadoCotizacion']) ? " AND p.idCotizacionEstado IN (" . $params['estadoCotizacion'] . ")" : "";
		$filtros .= !empty($params['id']) ? " AND p.idCotizacion IN (" . $params['id'] . ")" : "";

		$sql = "
			SELECT DISTINCT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, p.idCuenta
				, p.idCentroCosto idCuentaCentroCosto
				, c.nombre AS cuenta
				, cc.canal AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
				, p.fechaRequerida
				, p.flagIgv igv
				, p.fee
				, p.idCotizacionEstado
				, p.idPrioridad
				, p.motivo
				, p.comentario
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND idItemEstado = 2) nuevos
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			-- LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			-- LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			LEFT JOIN rrhh.dbo.Empresa c ON p.idCuenta = c.idEmpresa
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idCanal = p.idCentroCosto AND cc.idEmpresa = c.idEmpresa
			WHERE
			1 = 1
			{$filtros}
			ORDER BY p.idCotizacion DESC
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obternerCotizacionDetalle($params = [])
	{
		$this->db
			->select('cd.*,
							c.nombre as cotizacion,
							c.fechaDeadline,
							c.fechaRequerida,
							c.motivo,
							c.comentario,
							c.diasValidez,
							c.idPrioridad,
							c.total,
							c.total_fee_igv,
							c.flagIgv,
							c.fee,
							prioridad.nombre as prioridad,
							cu.nombre AS cuenta,
							cc.nombre AS centroCosto,
							sol.nombre AS solicitante,
							it.nombre AS itemTipo,
							proveedor.razonSocial AS proveedor,
							i.caracteristicas AS caracteristicaItem,
							case when proveedor.idProveedor is null then itf.costo else cd.costo end as costo', false)
			->from('compras.cotizacionDetalle cd')
			->join('compras.cotizacion c', 'c.idCotizacion = cd.idCotizacion', 'LEFT')
			->join('compras.cotizacionPrioridad prioridad', 'prioridad.idPrioridad = c.idPrioridad', 'LEFT')
			->join('compras.item i', 'i.idItem = cd.idItem', 'LEFT')
			->join('visualImpact.logistica.cuenta cu', 'c.idCuenta = cu.idCuenta', 'LEFT')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'c.idCentroCosto = cc.idCuentaCentroCosto', 'LEFT')
			->join('compras.solicitante sol', 'c.idSolicitante = sol.idSolicitante', 'LEFT')
			->join('compras.itemTipo it', 'it.idItemTipo = cd.idItemTipo', 'LEFT')
			->join('compras.cotizacionDetalleSub cds', 'cd.idItemTipo = 7 AND cds.idCotizacionDetalle = cd.idCotizacionDetalle', 'LEFT')
			->join('compras.proveedor', 'proveedor.idProveedor = isNull(cd.idProveedor, cds.idProveedorDistribucion)', 'LEFT');

		if (isset($params['idProveedor'])) $this->db->join('compras.itemTarifario itf', 'itf.idItem = i.idItem AND itf.estado = 1 AND itf.idProveedor = ' . $params['idProveedor'], 'LEFT');

		if (isset($params['idCotizacion'])) $this->db->where("c.idCotizacion in ({$params['idCotizacion']})");
		// if (isset($params['idProveedor'])) $this->db->where('proveedor.idProveedor', $params['idProveedor']);

		if (isset($params['idCotizacionDetalle'])) $this->db->where("cd.idCotizacionDetalle in ({$params['idCotizacionDetalle']})");
		return $this->db->get();
	}

	public function obtenerCotizacionDetalleSub($params = [])
	{
		$this->db
			->select('
				cds.*,
				um.nombre as unidadMedida,
				ts.nombre as tipoServicio,
				il.nombre as itemLogistica')
			->from('compras.cotizacionDetalleSub cds')
			->join('compras.unidadMedida um', 'um.idUnidadMedida = cds.idUnidadMedida', 'LEFT')
			->join('compras.tipoServicio ts', 'ts.idTipoServicio = cds.idTipoServicio', 'LEFT')
			->join('compras.item il', 'il.idItem = cds.idItem', 'LEFT');

		if (isset($params['idCotizacionDetalle'])) $this->db->where('cds.idCotizacionDetalle', $params['idCotizacionDetalle']);
		return $this->db->get();
	}

	function getZonas($params = [])
	{

		if (!isset($params['otroAlmacen'])) {
			$params['otroAlmacen'] = '0';
		}
		if ($params['otroAlmacen'] != '1') {
			$this->db
				->select('a.idAlmacen, a.nombre, ttd.dias')
				->from('visualImpact.logistica.almacen a')
				->join('visualImpact.logistica.tipo_transporte_det ttd', 'a.idUbigeo = ttd.cod_ubigeo and ttd.idTipoTransporte = 1');
		} else {
			$this->db
				->select('a.idLocalTercero as idAlmacen, a.nombreLocal as nombre, isnull(ttd.dias, 0) as dias')
				->from('visualImpact.logistica.localesTerceros a')
				->join('visualImpact.logistica.tipo_transporte_det ttd', 'a.cod_ubigeo = ttd.cod_ubigeo and ttd.idTipoTransporte = 1', 'left')
				->where('a.cod_ubigeo != 0');
		}

		$this->db
			->where('a.estado', 1)
			->where('isnull(ttd.estado, 1) = 1')
			->order_by('2');

		if (isset($params['idZona'])) {
			if ($params['otroAlmacen'] != '1') {
				$this->db->where('a.idAlmacen', $params['idZona']);
			} else {
				$this->db->where('a.idLocalTercero', $params['idZona']);
			}
		}
		if (isset($params['nombre'])) {
			if ($params['otroAlmacen'] != '1') {
				$this->db->where('a.nombre', $params['nombre']);
			} else {
				$this->db->where('a.nombreLocal', $params['nombre']);
			}
		}

		return $this->db->get();
	}
	public function obtenerMaxDiasEntrega($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idsCotizacion']) ? ' AND idCotizacion IN(' . $params['idsCotizacion'] . ')' : '';
		$sql = "
			SELECT isNull(max(diasEntrega),0) as diasEntrega 
			FROM compras.cotizacionDetalle
			WHERE 1 = 1
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
	public function obtenerInformacionCotizacionDetalle($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? ' AND p.idCotizacion = ' . $params['idCotizacion'] : '';
		$filtros .= !empty($params['idsCotizacion']) ? ' AND p.idCotizacion IN(' . $params['idsCotizacion'] . ')' : '';

		$sql = "
			SELECT
				p.idCotizacion
				, pd.idCotizacionDetalle
				, p.nombre AS cotizacion
				, p.flagIgv
				, p.total
				, p.total_fee
				, p.total_fee_igv
				, p.fee
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, p.codCotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, ce.nombre AS cotizacionEstado
				, it.idItemTipo
				, it.nombre AS itemTipo
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, pd.precio
				, pd.gap
				, pd.subtotal
				, pd.subtotal subTotal
				, ei.idItemEstado
				, ei.nombre AS estadoItem
				, pd.idProveedor
				, pr.razonSocial AS proveedor
				, cde.nombre AS cotizacionDetalleEstado
				, CONVERT( VARCHAR, pd.fechaCreacion, 103) AS fechaCreacion
				, CONVERT( VARCHAR, pd.fechaModificacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaModificacion, 108) AS fechaModificacion
				, pd.caracteristicas
				, pd.caracteristicasCompras
				, im.nombre as itemMarca
				, REPLACE(p.comentario, CHAR(10), '<br>') comentario
				, pd.flagAlternativo
				, pd.nombreAlternativo
				, ss.nombre as solicitante
				, pd.flagRedondear
				, p.mostrarPrecio
				, pd.costoPacking
				, pd.flagMostrarDetalle
				, pd.adicionales
				, pd.asignacionFamiliar
				, pd.segurovidaley
				, pd.refrigerio
				, pd.incentivos
				, pd.sueldo
				, pd.cts
				, pd.movilidad
				, pd.essalud
				, pd.vacaciones
				, pd.gratificacion
				, pd.mesInicio
				, CASE WHEN pd.mesInicio=pd.mesFin THEN null ELSE pd.mesFin END mesFin
				, p.feePersonal
				, pd.idCargo
				,ctt.nombre cargo
				,pd.sueldo+pd.movilidad+pd.incentivos+pd.refrigerio+pd.asignacionFamiliar subtotalPersonal
				,pd.sueldo+pd.movilidad+pd.incentivos+pd.refrigerio+pd.asignacionFamiliar+pd.segurovidaley+pd.cts+pd.essalud+pd.vacaciones+pd.gratificacion totalPersonal
				,pd.asignacionFamiliar+pd.segurovidaley+pd.cts+pd.essalud+pd.vacaciones+pd.gratificacion totalCargasSociales
				,1.4626*(pd.sueldo+pd.asignacionFamiliar)+(pd.movilidad+pd.refrigerio) totalSueldo
				,1.4626*(pd.incentivos) totalIncentivo
				,pd.cantidad_personal
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			JOIN compras.itemTipo it ON pd.idItemTipo = it.idItemTipo
			LEFT JOIN compras.item i ON pd.idItem = i.idItem
			LEFT JOIN compras.itemMarca im ON im.idItemMarca = i.idItemMarca
			JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			JOIN compras.cotizacionDetalleEstado cde ON pd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			LEFT JOIN compras.solicitante ss ON ss.idSolicitante = p.idSolicitante
			LEFT JOIN rrhh.dbo.CargoTrabajo ctt ON ctt.idCargoTrabajo=pd.idCargo 																			
			WHERE 1 = 1
			{$filtros}
			ORDER BY itemTipo, pd.idCotizacionDetalle
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function getImagenCotiProv($params = [])
	{
		$sql = "SELECT idTipoArchivo, '../cotizacion/' + nombre_archivo as nombre_archivo FROM compras.cotizacionDetalleArchivos WHERE idTipoArchivo = 2 AND idCotizacionDetalle = " . $params['idCotizacionDetalle'];
		$sql .= " UNION select idTipoArchivo, '../cotizacionProveedor/' + nombre_archivo as nombre_archivo FROM compras.cotizacionDetalleProveedorDetalleArchivos cdpda LEFT JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdpd.idCotizacionDetalleProveedorDetalle=cdpda.idCotizacionDetalleProveedorDetalle ";
		$sql .= " LEFT JOIN compras.cotizacionDetalleProveedor cdp ON cdp.idCotizacionDetalleProveedor=cdpd.idCotizacionDetalleProveedor";
		$sql .= " WHERE cdpd.idCotizacionDetalle =" . $params['idCotizacionDetalle'];
		$sql .= " AND cdp.idProveedor =" . $params['idProveedor'];
		$sql .= " AND cdpda.idTipoArchivo = 2";
		return $this->db->query($sql);
	}

	public function validarExistenciaCotizacion($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? ' AND p.idCotizacion != ' . $params['idCotizacion'] : '';
		// $filtros .= !empty($params['nombre']) ? " AND p.nombre != '" . $params['nombre']. "'" : '';

		$sql = "
			SELECT
				idCotizacion
			FROM compras.cotizacion p
			WHERE
			(p.nombre LIKE '{$params['nombre']}')
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

	public function obtenerCuentaDeLaCotizacionDetalle($param = NULL)
	{
		$this->db->select('*')->from('compras.cotizacion')->where('idCotizacion', $param);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data['idCuenta'];
	}

	public function obtenerCentroCostoDeLaCotizacionDetalle($param = NULL)
	{
		$this->db->select('*')->from('compras.cotizacion')->where('idCotizacion', $param);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data['idCentroCosto'];
	}
	public function insertarCotizacion($params = [])
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

	public function insertarCotizacionDetalle($params = [])
	{
		$insertArchivos = [];
		foreach ($params['insert'] as $k => $insert) {
			$queryCotizacionDetalle = $this->db->insert($params['tabla'], $insert);
			$idCotizacionDetalle = $this->db->insert_id();

			if (!empty($params['archivosDeImagen'][$k])) {
				foreach ($params['archivosDeImagen'][$k] as $archivo) {
					$insertArchivos[] = [
						'idCotizacion' => $insert['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
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

					// $extension = '';

					// if ($tipoArchivo[0] == 'image') {
					// 	$extension = $tipoArchivo[1];
					// } else if ($tipoArchivo[1] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
					// 	$extension = 'xlsx';
					// } else if ($tipoArchivo[1] == 'vnd.openxmlformats-officedocument.presentationml.presentation') {
					// 	$extension = 'pptx';
					// } else if ($tipoArchivo[1] == 'vnd.ms-excel') {
					// 	$extension = 'xls';
					// } else if ($tipoArchivo[1] == 'vnd.ms-powerpoint') {
					// 	$extension = 'ppt';
					// } else if ($tipoArchivo[1] == 'pdf') {
					// 	$extension = 'pdf';
					// }

					// $archivo['extensionVisible'] = $extension;
					$archivoName = $this->saveFileWasabi($archivo);

					$insertArchivos[] = [
						'idCotizacion' => $insert['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]], // ($tipoArchivo[0] == 'image' ? TIPO_IMAGEN : ($extension == 'pdf' ? TIPO_PDF : TIPO_OTROS)),
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}

			if (!empty($params['archivoExistente'][$k])) {

				$id = implode(',', $params['archivoExistente'][$k]);

				$sql = "
					SELECT
						da.idCotizacionDetalleArchivo,
						da.idCotizacion,
						da.idCotizacionDetalle,
						da.idTipoArchivo,
						da.nombre_inicial,
						da.nombre_archivo,
						da.nombre_unico,
						da.extension,
						da.idUsuarioReg
					FROM compras.cotizacionDetalleArchivos da
					WHERE idCotizacionDetalleArchivo in ($id);
				";

				$query = $this->db->query($sql)->result_array();

				$archivosExistentes = [];

				foreach ($query as $row) {

					$archivosExistentes[] = [
						'idCotizacion' => $params['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'idTipoArchivo' => $row['idTipoArchivo'],
						'nombre_inicial' => $row['nombre_inicial'],
						'nombre_archivo' => $row['nombre_archivo'],
						'nombre_unico' => $row['nombre_unico'],
						'extension' => $row['extension'],
						'idUsuarioReg' => $row['idUsuarioReg'],
						'estado' => true,
					];
				}

				if (!empty($archivosExistentes)) {
					$this->db->insert_batch('compras.cotizacionDetalleArchivos', $archivosExistentes);
				}
			}

			if (!empty($params['insertUbigeo'][$k])) {
				foreach ($params['insertUbigeo'][$k] as $kiu => $viu) {
					$this->db->insert('compras.cotizacionDetalleUbigeo', [
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'idDepartamento' => $viu['idDepartamento'],
						'idProvincia' => $viu['idProvincia'],
						'idDistrito' => !empty($viu['idDistrito']) ? $viu['idDistrito'] : null,
						'cantidadPDV' => $viu['cantidadPDV'],
					]);
				}
			}

			//Sub Items
			if (!empty($params['insertSubItem'][$k])) {
				foreach ($params['insertSubItem'][$k] as $subItem) {
					$insertSubItem[] = [
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : '',
						'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : '',
						'idUnidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : '',
						'idTipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : '',
						'costo' => !empty($subItem['costo']) ? $subItem['costo'] : '',
						'talla' => !empty($subItem['talla']) ? $subItem['talla'] : '',
						'tela' => !empty($subItem['tela']) ? $subItem['tela'] : '',
						'genero' => !empty($subItem['genero']) ? $subItem['genero'] : NULL,
						'color' => !empty($subItem['color']) ? $subItem['color'] : '',
						'monto' => !empty($subItem['monto']) ? $subItem['monto'] : '',
						'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : '',
						'costoDistribucion' => !empty($subItem['costoDistribucion']) ? $subItem['costoDistribucion'] : NULL,
						'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
						'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
						'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
						'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
						'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
						'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : '0',
						'peso' => !empty($subItem['peso']) ? $subItem['peso'] : '0',
						//
						'idZona' => !empty($subItem['idZona']) ? $subItem['idZona'] : NULL,
						'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
						'gap' => !empty($subItem['gap']) ? $subItem['gap'] : NULL,
						'pesoVisual' => !empty($subItem['pesoVisual']) ? $subItem['pesoVisual'] : NULL,
						'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
						//
						'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : '0',
						'flagOtrosPuntos' => !empty($subItem['flagOtrosPuntos']) ? $subItem['flagOtrosPuntos'] : '0',
						//
						'cod_departamento' => !empty($subItem['cod_departamento']) ? $subItem['cod_departamento'] : null,
						'cod_provincia' => !empty($subItem['cod_provincia']) ? $subItem['cod_provincia'] : null,
						'cod_distrito' => !empty($subItem['cod_distrito']) ? $subItem['cod_distrito'] : null,
						'idTipoServicioUbigeo' => !empty($subItem['idTipoServicioUbigeo']) ? $subItem['idTipoServicioUbigeo'] : null,
					];
				}
			}
		}

		if ($queryCotizacionDetalle) {
			$this->resultado['query'] = $queryCotizacionDetalle;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();

			if (!empty($insertArchivos)) {
				$this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
			}
			if (!empty($insertSubItem)) {
				$this->db->insert_batch('compras.cotizacionDetalleSub', $insertSubItem);
			}
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarCotizacion($params = [])
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
	public function actualizarCotizacionDetalle($params = [])
	{
		$query = $this->db->update_batch($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerProveedor($params = [])
	{
		$sql = "
			SELECT
				idProveedor AS id
				, razonSocial AS value
			FROM compras.proveedor
			WHERE idEstado = 2
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function insertarTarifarioItem($params = [])
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

	//OBTEBER ARTUCUOI
	public function obtenerItem($nombreItem)
	{
		$sql = "
		SELECT
		idItem
		FROM compras.item
		WHERE nombre = '" . $nombreItem . "'
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerInformacionDetalleCotizacion($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";
		$filtros .= !empty($params['noTipoItem']) ? " AND ( cd.requiereOrdenCompra = 1 OR cd.idItemTipo NOT IN({$params['noTipoItem']}) )" : "";
		$filtros .= !empty($params['noOC']) ? " AND ocd.idOrdenCompraDetalle is null " : "";

		$sql = "
			SELECT
			DISTINCT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			ISNULL(cd.nombre,'') item,
			i.nombre as itemNombre,
			cd.idItem,
			cd.cantidad,
			cd.costo,
			cd.caracteristicasCompras,
			cd.caracteristicasProveedor,
			cd.subtotal,
			ROUND (cd.subtotal/((ISNULL(CONVERT(float,cd.gap),0)/100)+1),2) subtotalSinGap,
			c.total,
			cd.idItemTipo,
			cd.caracteristicas,
			cd.gap,
			cd.precio,
			-- cd.enlaces,
			CAST(cd.enlaces AS VARCHAR(MAX)) as enlaces,
			p.idProveedor,
			p.razonSocial,
			cd.flagRedondear,
			c.codOrdenCompra,
			cd.flagAlternativo,
			cd.nombreAlternativo,
			cd.tituloParaOc,
			um.nombre as unidadMedida,
			cd.cantPdv,
			cd.tipoServicio,
			cd.requiereOrdenCompra,
			cd.tsCosto,
			cd.costoPacking,
			cd.flagMovilidadSolicitado,
			cd.costoMovilidad,
			cd.flagPersonalSolicitado,
			cd.costoPersonal,
			ts.idUnidadMedida as umTs,
			umts.nombre as umTipoServicio,
			cd.flagDetallePDV,
			cd.flagPackingSolicitado,
			cd.flagMostrarDetalle
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			LEFT JOIN ( SELECT idCotizacionDetalle, MAX(idProveedorDistribucion) as idProveedorDistribucion, CAST(MAX(CAST(requiereOrdenCompra as INT)) AS BIT) as requiereOrdenCompra from compras.cotizacionDetalleSub group by idCotizacionDetalle) cds ON cds.idCotizacionDetalle = cd.idCotizacionDetalle
			LEFT JOIN compras.proveedor p ON p.idProveedor = ISNULL(cd.idProveedor,cds.idProveedorDistribucion)
			LEFT JOIN compras.item i ON i.idItem = cd.idItem
			LEFT JOIN compras.ordenCompraDetalle ocd ON ocd.idCotizacionDetalle = cd.idCotizacionDetalle AND ocd.estado = 1
			LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = i.idUnidadMedida
			LEFT JOIN compras.tipoServicio ts ON ts.idTipoServicio = cd.tipoServicio
			LEFT JOIN compras.unidadMedida umts ON umts.idUnidadMedida = ts.idUnidadMedida
			WHERE 
			1 = 1 and cd.estado=1
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

	public function obtenerInformacionDetalleCotizacionSub($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";
		$filtros .= !empty($params['noTipoItem']) ? " AND ( cds.requiereOrdenCompra = 1 OR cd.idItemTipo NOT IN({$params['noTipoItem']}) )" : "";

		$sql = "
			SELECT
				cd.idCotizacion,
				cd.idItemTipo,
				cds.idCotizacionDetalleSub,
				cds.idCotizacionDetalle,
				cds.idTipoServicio,
				cds.idUnidadMedida,
				cds.nombre,
				cds.talla,
				cds.tela,
				cds.color,
				cds.cantidad,
				cds.costo,
				(cds.cantidad * cds.costo) subtotal,
				-- cds.subtotal corregir
				cds.monto,
				cds.cantidadPdv,
				cds.idItem,
				cds.idDistribucionTachado,
				ts.nombre tipoServicio,
				um.nombre unidadMedida,
				cds.idProveedorDistribucion,
				cds.cantidadReal,
				cds.requiereOrdenCompra,
				c.codOrdenCompra,
				cds.genero,
				cds.sucursal,
				cds.razonSocial,
				cds.tipoElemento,
				cds.marca,
				cds.cantidad
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			JOIN compras.cotizacionDetalleSub cds ON cds.idCotizacionDetalle = cd.idCotizacionDetalle
			LEFT JOIN compras.tipoServicioUbigeo ts ON ts.idTipoServicioUbigeo = cds.idTipoServicio
			LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cds.idUnidadMedida
			WHERE
			1 = 1
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

	public function obtenerInformacionDetalleCotizacionArchivos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND c.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";
		$filtros .= !empty($params['anexo']) ? " AND cda.flag_anexo = 1 " : "";

		$sql = "
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			cda.idCotizacionDetalleArchivo,
			cda.idTipoArchivo,
			cda.nombre_inicial,
			cda.nombre_archivo,
			cda.extension
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			JOIN compras.cotizacionDetalleArchivos cda ON cda.idCotizacionDetalle = cd.idCotizacionDetalle
			WHERE
			1 = 1
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

	public function obtenerInformacionCotizacionArchivos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND c.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['anexo']) ? " AND cda.flag_anexo = 1 " : "";
		$filtros .= !empty($params['idTipoArchivo']) ? " AND cda.idTipoArchivo = {$params['idTipoArchivo']} " : "";

		$sql = "
			SELECT
				c.idCotizacion,
				cda.idCotizacionDetalleArchivo,
				cda.idTipoArchivo,
				cda.nombre_inicial,
				cda.nombre_archivo,
				cda.extension
			FROM
			compras.cotizacion c
			LEFT JOIN compras.cotizacionDetalleArchivos cda ON cda.idCotizacion = c.idCotizacion
			WHERE
			1 = 1 AND cda.nombre_archivo is not null
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

	public function obtenerInformacionDetalleCotizacionProveedores($params = [])
	{
		$filtros = "";

		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";

		$sqlUnion = "";
		if (!empty($params['union'])) {
			$sqlUnion = "
			UNION
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			cd.nombre,
			it.idProveedor,
			p.razonSocial,
			CASE WHEN ith.idItemTarifarioHistorico IS NOT NULL THEN 1 ELSE 0 END respuestasProveedor
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON cd.idCotizacion = c.idCotizacion
			JOIN compras.itemTarifario it ON it.idItem = cd.idItem
			JOIN compras.itemTarifarioHistorico ith ON ith.idItemTarifario = it.idItemTarifario
				AND General.dbo.fn_fechaVigente(ith.fecIni,ith.fecFin,cd.fechaCreacion,cd.fechaCreacion) = 1
			JOIN compras.proveedor p ON it.idProveedor = p.idProveedor
			WHERE
			cd.cotizacionInterna = 0
			{$filtros}
			";
		}
		$sql = "
		WITH lst_respuestas_proveedor AS(
			SELECT
				c.idCotizacion,
				cd.idCotizacionDetalle,
				cd.idItem,
				cd.nombre,
				c.idProveedor,
				p.razonSocial,
				(SELECT DISTINCT CASE WHEN costo IS NOT NULL AND costo <> 0 THEN 1 ELSE 0 END FROM compras.cotizacionDetalleProveedorDetalle WHERE idCotizacionDetalleProveedor = c.idCotizacionDetalleProveedor AND idItem = cd.idItem) respuestasProveedor
			FROM
				compras.cotizacionDetalleProveedor c
				JOIN compras.cotizacionDetalleProveedorDetalle cdp ON cdp.idCotizacionDetalleProveedor = c.idCotizacionDetalleProveedor
				JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdp.idCotizacionDetalle
				JOIN compras.proveedor p ON p.idProveedor = c.idProveedor
			WHERE
				1 = 1
				{$filtros}
				{$sqlUnion}
			)
			SELECT
			idCotizacion,
			idCotizacionDetalle,
			idProveedor,
			razonSocial,
			SUM(respuestasProveedor) OVER (PARTITION BY idCotizacionDetalle) cotizacionesConfirmadas
			FROM lst_respuestas_proveedor
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerArchivoCotizacionDetalleProveedors(array $param = [])
	{
		$this->db
			->select('*')
			->from('compras.cotizacionDetalleProveedorDetalleArchivos')
			->where('idCotizacionDetalleProveedorDetalle', $param['idCotizacionDetalleProveedorDetalle']);

		return $this->db->get();
	}

	public function obtenerInformacionDetalleCotizacionProveedoresParaVista($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND c.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";

		$filtroE = !empty($params['idCotizacion']) ? " AND idCotizacion IN (" . $params['idCotizacion'] . ")" : "";

		$sqlUnion = "";
		if (!empty($params['union'])) {
			$sqlUnion = "
			UNION
			SELECT
			'' idCotizacionDetalleProveedorDetalle,
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			p.idProveedor,
			(ith.costo * cd.cantidad) subTotal,
			cd.cantidad,
			p.razonSocial,
			ith.costo costoUnitario,
			0 as diasEntrega
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON cd.idCotizacion = c.idCotizacion
			JOIN compras.itemTarifario it ON it.idItem = cd.idItem
			JOIN compras.itemTarifarioHistorico ith ON ith.idItemTarifario = it.idItemTarifario
				AND General.dbo.fn_fechaVigente(ith.fecIni,ith.fecFin,cd.fechaCreacion,cd.fechaCreacion) = 1
			JOIN compras.proveedor p ON it.idProveedor = p.idProveedor
			WHERE
			p.idProveedor not in (Select idProveedor from compras.cotizacionDetalleProveedor where 1 = 1 {$filtroE}) and
			it.estado = 1 
			{$filtros}
			";
		}

		$sql = "
			SELECT
			cd.idCotizacionDetalleProveedorDetalle,
			c.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			c.idProveedor,
			cd.costo subTotal,
			cd.cantidad,
			p.razonSocial,
			(cd.costo / cd.cantidad) costoUnitario,
			cd.diasEntrega
			FROM
			compras.cotizacionDetalleProveedor c
			JOIN compras.cotizacionDetalleProveedorDetalle cd ON cd.idCotizacionDetalleProveedor = c.idCotizacionDetalleProveedor
			JOIN compras.cotizacionDetalle cdl ON cdl.idCotizacionDetalle = cd.idCotizacionDetalle
			JOIN compras.proveedor p ON p.idProveedor = c.idProveedor
			WHERE
			1 = 1
			AND (cd.costo IS NOT NULL AND cd.costo <> 0)
			{$filtros}
			{$sqlUnion}
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function insertar($params = [])
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

	public function obtenerSolicitante($params = [])
	{
		$sql = "
			SELECT
				idSolicitante AS id
				, nombre AS value
			FROM compras.solicitante
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerUsuarios($input = [])
	{
		$filtros = "";
		!empty($input['idUsuario']) ? $filtros .= " AND u.idUsuario = {$input['idUsuario']}" : "";

		$sql = "
			DECLARE @fecha DATE = GETDATE();
			SELECT DISTINCT
				u.idUsuario
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, u.numDocumento
				, u.externo
				, u.ultimo_cambio_pwd
				, flag_anuncio_visto
				, DATEDIFF(day, u.ultimo_cambio_pwd, @fecha) AS dias_pasados
				--, td.breve tipoDocumento
				--, e.idEmpleado
				--, ut.idTipoUsuario
				--, ut.nombre tipoUsuario
				--, e.archFoto foto
			FROM
				sistema.usuario u
				-- JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
				-- 	AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
				-- LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				-- LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				-- LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.estado = 1
				--AND u.demo = 0
				$filtros
			;
		";
		return $this->db->query($sql);
	}

	public function obtenerInformacionOper($params = [])
	{
		$filtros = '';
		!empty($params['idOper']) ? $filtros .= " AND o.idOper IN({$params['idOper']})" : '';

		$sql = "
		SELECT
			o.idOper,
			od.idCotizacion,
			o.requerimiento,
			o.concepto,
			CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, o.fechaReg, 103) AS fechaReg,
			ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') usuarioRegistro,
			'Coordinadora de compras' usuarioReceptor,
			cuenta.nombre as cuenta,
			centrocosto.subcanal as centroCosto,
			ss.nombre as solicitante
			,p.codOrdenCompra
		FROM compras.oper o
		JOIN compras.operDetalle od ON od.idOper = o.idOper
		LEFT JOIN compras.cotizacion p ON p.idCotizacion = od.idCotizacion
		LEFT JOIN compras.solicitante ss ON ss.idSolicitante = p.idSolicitante
		LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg
		LEFT JOIN sistema.usuario ur ON ur.idUsuario = o.idUsuarioReceptor
		LEFT JOIN rrhh.dbo.Empresa cuenta ON p.idCuenta = cuenta.idEmpresa
		LEFT JOIN rrhh.dbo.empresa_Canal centrocosto ON centrocosto.idEmpresaCanal = p.idCentroCosto
		WHERE 1=1 /* o.estado = 1 */ 
		{$filtros}";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionOrdenCompra($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['cuenta']) ? ' AND p.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND p.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['id']) ? " AND o.idOrdenCompra IN (" . $params['id'] . ")" : "";

		$sql = "
			SELECT
				o.idOrdenCompra
				, o.idCuenta
				, o.idCentroCosto
				, o.requerimiento
				, o.entrega
				, o.observacion
				, o.total
				, CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntregaLabel
				, o.fechaEntrega 
				, CONVERT(VARCHAR, o.fechaReg, 103) AS fechaReg
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') usuario
				, mp.nombre metodoPago
				, o.comentario
				, o.observacion
				, m.nombreMoneda monedaPlural
				, m.simbolo simboloMoneda
				, o.igv
				, pr.razonSocial
				, pr.nroDocumento as ruc
				, o.mostrar_imagenes
				, o.mostrar_imagenesCoti
				, o.enlaces
			FROM compras.ordenCompra o
			JOIN compras.moneda m ON m.idMoneda = o.idMoneda
			JOIN compras.monedaDet md ON md.idMoneda = m.idMoneda
				AND General.dbo.fn_fechaVigente(md.fecIni,md.fecFin,o.fechaReg,o.fechaReg)=1
			JOIN compras.metodoPago mp ON mp.idMetodoPago = o.idMetodoPago
			LEFT JOIN compras.proveedor pr ON pr.idProveedor = o.idProveedor
			LEFT JOIN visualImpact.logistica.cuenta c ON o.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON o.idCentroCosto = cc.idCuentaCentroCosto
			LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg
			WHERE
			o.estado = 1
			{$filtros}
			ORDER BY o.idOrdenCompra DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenerInformacionOrdenCompraCotizacion($params = [])
	{
		$filtros = "";

		$sql = "
			SELECT DISTINCT
			ocd.idCotizacionDetalle,
			ocd.idOrdenCompra,
			c.codCotizacion,
			c.nombre,
			ISNULL(codCotizacion + ' - ','') + c.nombre cotizacionCodNombre
			FROM 
			compras.ordenCompraDetalle ocd
			JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = ocd.idCotizacionDetalle
			JOIN compras.cotizacion c ON c.idCotizacion = cd.idCotizacion
			WHERE
			ocd.estado = 1
			{$filtros}
			ORDER BY ocd.idOrdenCompra DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenertipoServicios($params = [])
	{
		$sql = "
			SELECT
				ts.idTipoServicio id,
				ts.nombre value,
				ts.idTipoServicioUbigeo,
				ts.costo,
				um.nombre unidadMedida,
				um.idUnidadMedida
			FROM compras.tipoServicio ts
			JOIN compras.unidadMedida um ON um.idUnidadMedida = ts.idUnidadMedida
			WHERE ts.estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function insertarCotizacionAnexos($data = [])
	{
		$insert = true;

		foreach ($data['anexos'] as $archivo) {
			$archivoName = $this->saveFileWasabi($archivo);
			$tipoArchivo = explode('/', $archivo['type']);
			$insertArchivos[] = [
				'idCotizacion' => $data['idCotizacion'],
				// 'idTipoArchivo' => $tipoArchivo[0] == 'image' ? TIPO_IMAGEN : TIPO_PDF,
				'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
				'nombre_inicial' => $archivo['name'],
				'nombre_archivo' => $archivoName,
				'nombre_unico' => $archivo['nombreUnico'],
				// 'extension' => $tipoArchivo[1],
				'extension' => FILES_WASABI[$tipoArchivo[1]],
				'estado' => true,
				'idUsuarioReg' => $this->idUsuario,
				'flag_anexo' => true,
			];
		}

		if (!empty($insertArchivos)) {
			$insert = $this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
		}

		if (!empty($data['anexoExistente'])) {

			$id = implode(',', $data['anexoExistente']);

			$sql = "
				SELECT
					da.idCotizacionDetalleArchivo,
					da.idCotizacion,
					da.idTipoArchivo,
					da.nombre_inicial,
					da.nombre_archivo,
					da.nombre_unico,
					da.extension,
					da.idUsuarioReg,
					da.flag_anexo
				FROM compras.cotizacionDetalleArchivos da
				WHERE idCotizacionDetalleArchivo in ($id);
			";

			$query = $this->db->query($sql)->result_array();

			$imagenesExistentes = [];

			foreach ($query as $row) {

				$imagenesExistentes[] = [
					'idCotizacion' => $data['idCotizacion'],
					'idTipoArchivo' => $row['idTipoArchivo'],
					'nombre_inicial' => $row['nombre_inicial'],
					'nombre_archivo' => $row['nombre_archivo'],
					'nombre_unico' => $row['nombre_unico'],
					'extension' => $row['extension'],
					'idUsuarioReg' => $row['idUsuarioReg'],
					'flag_anexo' => $row['flag_anexo'],
					'estado' => true,
				];
			}

			if (!empty($imagenesExistentes)) {
				$insert = $this->db->insert_batch('compras.cotizacionDetalleArchivos', $imagenesExistentes);
			}
		}

		if (!empty($data['anexosEliminados'])) {
			$data['anexosEliminados'] = checkAndConvertToArray($data['anexosEliminados']);

			foreach ($data['anexosEliminados'] as $anexoEliminado) {
				$this->db->delete('compras.cotizacionDetalleArchivos', ['idCotizacionDetalleArchivo' => $anexoEliminado]);
			}
		}

		return $insert;
	}

	public function actualizarCotizacionDetalleArchivos($params = [])
	{

		$insertArchivos = [];
		foreach ($params['update'] as $k => $update) {
			$idCotizacionDetalle = $update['idCotizacionDetalle'];
			unset($update['idCotizacionDetalle']);
			$queryCotizacionDetalle = $this->db->update($params['tabla'], $update, ['idCotizacionDetalle' => $idCotizacionDetalle]);

			if (!empty($params['archivos'][$k])) {

				foreach ($params['archivos'][$k] as $archivo) {
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);

					$insertArchivos[] = [
						'idCotizacion' => $update['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
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

			//Sub Items
			if (!empty($params['insertSubItem'][$k])) {
				foreach ($params['insertSubItem'][$k] as $subItem) {
					$insertSubItem[] = [
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : '',
						'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : '',
						'idUnidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : '',
						'idTipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : '',
						'costo' => !empty($subItem['costo']) ? $subItem['costo'] : '',
						'talla' => !empty($subItem['talla']) ? $subItem['talla'] : '',
						'tela' => !empty($subItem['tela']) ? $subItem['tela'] : '',
						'color' => !empty($subItem['color']) ? $subItem['color'] : '',
						'monto' => !empty($subItem['monto']) ? $subItem['monto'] : '',
						'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : '',
						'genero' => !empty($subItem['genero']) ? $subItem['genero'] : '',
						// 'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
						'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
						'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
						'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,
						'sucursal' => !empty($subItem['sucursal']) ? $subItem['sucursal'] : NULL,
						'razonSocial' => !empty($subItem['razonSocial']) ? $subItem['razonSocial'] : NULL,
						'tipoElemento' => !empty($subItem['tipoElemento']) ? $subItem['tipoElemento'] : NULL,
						'marca' => !empty($subItem['marca']) ? $subItem['marca'] : NULL,
						'peso' => !empty($subItem['peso']) ? $subItem['peso'] : NULL,
						'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : NULL,
					];
				}
			}
			//Sub Items Actualizar
			if (!empty($params['subDetalle'][$k])) {
				foreach ($params['subDetalle'][$k] as $subItem) {
					if (isset($subItem['idCotizacionDetalleSub'])) {
						$updateSubItem[] = [
							'idCotizacionDetalleSub' => $subItem['idCotizacionDetalleSub'],
							'idCotizacionDetalle' => $idCotizacionDetalle,
							'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : '',
							'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : '',
							'idUnidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : '',
							'idTipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : '',
							'costo' => !empty($subItem['costo']) ? $subItem['costo'] : '',
							'talla' => !empty($subItem['talla']) ? $subItem['talla'] : '',
							'tela' => !empty($subItem['tela']) ? $subItem['tela'] : '',
							'color' => !empty($subItem['color']) ? $subItem['color'] : '',
							'monto' => !empty($subItem['monto']) ? $subItem['monto'] : '',
							'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : '',
							'costoDistribucion' => !empty($subItem['costoDistribucion']) ? $subItem['costoDistribucion'] : NULL, //$post
							'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
							'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
							'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
							'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
							'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
							'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,
							'genero' => !empty($subItem['genero']) ? $subItem['genero'] : NULL,
							'sucursal' => !empty($subItem['sucursal']) ? $subItem['sucursal'] : NULL,
							'razonSocial' => !empty($subItem['razonSocial']) ? $subItem['razonSocial'] : NULL,
							'tipoElemento' => !empty($subItem['tipoElemento']) ? $subItem['tipoElemento'] : NULL,
							'marca' => !empty($subItem['marca']) ? $subItem['marca'] : NULL,
							'peso' => !empty($subItem['peso']) ? $subItem['peso'] : NULL,
							'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : NULL,
							'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
							'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
							'cod_departamento' => !empty($subItem['cod_departamento']) ? $subItem['cod_departamento'] : NULL,
							'cod_provincia' => !empty($subItem['cod_provincia']) ? $subItem['cod_provincia'] : NULL,
							'cod_distrito' => !empty($subItem['cod_distrito']) ? $subItem['cod_distrito'] : NULL,
							'idTipoServicioUbigeo' => !empty($subItem['idTipoServicioUbigeo']) ? $subItem['idTipoServicioUbigeo'] : NULL,

						];
					} else {
						$insertSubItem[] = [
							'idCotizacionDetalle' => $idCotizacionDetalle,
							'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : '',
							'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : '',
							'idUnidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : '',
							'idTipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : '',
							'costo' => !empty($subItem['costo']) ? $subItem['costo'] : '',
							'talla' => !empty($subItem['talla']) ? $subItem['talla'] : '',
							'tela' => !empty($subItem['tela']) ? $subItem['tela'] : '',
							'color' => !empty($subItem['color']) ? $subItem['color'] : '',
							'monto' => !empty($subItem['monto']) ? $subItem['monto'] : '',
							'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : '',
							'costoDistribucion' => !empty($subItem['costoDistribucion']) ? $subItem['costoDistribucion'] : NULL, //$post
							'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
							'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
							'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
							'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
							'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
							'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,
							'genero' => !empty($subItem['genero']) ? $subItem['genero'] : NULL,
							'sucursal' => !empty($subItem['sucursal']) ? $subItem['sucursal'] : NULL,
							'razonSocial' => !empty($subItem['razonSocial']) ? $subItem['razonSocial'] : NULL,
							'tipoElemento' => !empty($subItem['tipoElemento']) ? $subItem['tipoElemento'] : NULL,
							'marca' => !empty($subItem['marca']) ? $subItem['marca'] : NULL,
							'peso' => !empty($subItem['peso']) ? $subItem['peso'] : NULL,
							'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : NULL,
							'idZona' => !empty($subItem['idZona']) ? $subItem['idZona'] : NULL,
							'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
							'gap' => !empty($subItem['gap']) ? $subItem['gap'] : NULL,
							'pesoVisual' => !empty($subItem['pesoVisual']) ? $subItem['pesoVisual'] : NULL,
							'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
							'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : 0,
							'flagOtrosPuntos' => !empty($subItem['flagOtrosPuntos']) ? $subItem['flagOtrosPuntos'] : NULL,
							'cod_departamento' => !empty($subItem['cod_departamento']) ? $subItem['cod_departamento'] : NULL,
							'cod_provincia' => !empty($subItem['cod_provincia']) ? $subItem['cod_provincia'] : NULL,
							'cod_distrito' => !empty($subItem['cod_distrito']) ? $subItem['cod_distrito'] : NULL,
							'idTipoServicioUbigeo' => !empty($subItem['idTipoServicioUbigeo']) ? $subItem['idTipoServicioUbigeo'] : NULL,
						];
					}
				}
			}
		}
		if (!empty($params['insert'])) {
			foreach ($params['insert'] as $k => $insert) {

				$this->db->insert($params['tabla'], $insert);

				$idCotizacionDetalle = $this->db->insert_id();

				if (!empty($params['archivos'][$k])) {
					foreach ($params['archivos'][$k] as $archivo) {
						$archivoName = $this->saveFileWasabi($archivo);
						$tipoArchivo = explode('/', $archivo['type']);
						$codArchivo = FILES_TIPO_WASABI[$tipoArchivo[1]];
						$insertArchivos[] = [
							'idCotizacion' => $insert['idCotizacion'],
							'idCotizacionDetalle' => $idCotizacionDetalle,
							'idTipoArchivo' => $codArchivo, //$tipoArchivo[0] == 'image' ? TIPO_IMAGEN : TIPO_PDF,
							'nombre_inicial' => $archivo['name'],
							'nombre_archivo' => $archivoName,
							'nombre_unico' => $archivo['nombreUnico'],
							'extension' => FILES_WASABI[$tipoArchivo[1]], //$tipoArchivo[1],
							'estado' => true,
							'idUsuarioReg' => $this->idUsuario
						];
					}
				}

				//Sub Items
				if (!empty($params['newInsertSubItem'][$k])) {

					foreach ($params['newInsertSubItem'][$k] as $subItem) {
						$insertSubItem[] = [
							'idCotizacionDetalle' => $idCotizacionDetalle,
							'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : '',
							'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : '',
							'idUnidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : '',
							'idTipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : '',
							'costo' => !empty($subItem['costo']) ? $subItem['costo'] : '',
							'talla' => !empty($subItem['talla']) ? $subItem['talla'] : '',
							'tela' => !empty($subItem['tela']) ? $subItem['tela'] : '',
							'color' => !empty($subItem['color']) ? $subItem['color'] : '',
							'genero' => !empty($subItem['genero']) ? $subItem['genero'] : '',
							'monto' => !empty($subItem['monto']) ? $subItem['monto'] : '',
							'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : '',
							'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
							'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
							'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,

							//// Los que faltaban ...
							'costoDistribucion' => !empty($subItem['costoDistribucion']) ? $subItem['costoDistribucion'] : NULL,
							'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
							'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
							'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
							'sucursal' => !empty($subItem['sucursal']) ? $subItem['sucursal'] : NULL,
							'razonSocial' => !empty($subItem['razonSocial']) ? $subItem['razonSocial'] : NULL,
							'tipoElemento' => !empty($subItem['tipoElemento']) ? $subItem['tipoElemento'] : NULL,
							'marca' => !empty($subItem['marca']) ? $subItem['marca'] : NULL,
							'peso' => !empty($subItem['peso']) ? $subItem['peso'] : NULL,
							'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : NULL,
							'idZona' => !empty($subItem['idZona']) ? $subItem['idZona'] : NULL,
							'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
							'gap' => !empty($subItem['gap']) ? $subItem['gap'] : NULL,
							'pesoVisual' => !empty($subItem['pesoVisual']) ? $subItem['pesoVisual'] : NULL,
							'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
							'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : 0,
							'flagOtrosPuntos' => !empty($subItem['flagOtrosPuntos']) ? $subItem['flagOtrosPuntos'] : NULL,
						];
					}
				}
			}
		}

		if ($queryCotizacionDetalle) {
			$this->resultado['query'] = $queryCotizacionDetalle;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();

			if (!empty($insertArchivos)) {
				$this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
			}
			if (!empty($insertSubItem)) {

				$this->db->insert_batch('compras.cotizacionDetalleSub', $insertSubItem);
			}

			if (!empty($updateSubItem)) {
				$this->db->update_batch('compras.cotizacionDetalleSub', $updateSubItem, 'idCotizacionDetalleSub');
			}

			if (!empty($params['archivoEliminado'])) {
				$params['archivoEliminado'] = checkAndConvertToArray($params['archivoEliminado']);
				foreach ($params['archivoEliminado'] as $archivoEliminado) {
					$this->db->delete('compras.cotizacionDetalleArchivos', ['idCotizacionDetalleArchivo' => $archivoEliminado]);
				}
			}
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenerInformacionOperSolicitud($params = [])
	{
		$filtros = '';
		!empty($params['idOper']) ? $filtros .= " AND o.idOper IN({$params['idOper']})" : '';

		$sql = "
		SELECT
			o.idOper,
			o.requerimiento,
			o.concepto,
			'' cuentas,
			'' centrosCosto,
			'' ordenCompra,
			CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, o.fechaReg, 103) AS fechaReg,
			ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') usuarioRegistro,
			--ur.nombres + ' ' + ISNULL(ur.apePaterno,'') + ' ' + ISNULL(ur.apeMaterno,'') usuarioReceptor,
			'Coordinadora de compras' usuarioReceptor,
			o.observacion
		FROM compras.oper o
		LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg
		LEFT JOIN sistema.usuario ur ON ur.idUsuario = o.idUsuarioReceptor
		WHERE o.estado = 1
		{$filtros}
		ORDER BY o.idOper DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerOperDetalleCotizacion($params = [])
	{
		$filtros = '';
		!empty($params['idOper']) ? $filtros .= " AND op.idOper IN({$params['idOper']})" : '';

		$sql = "
		SELECT 
		op.idOperDetalle,
		op.idOper,
		c.codCotizacion,
		c.nombre,
		ISNULL(codCotizacion + ' - ','') + c.nombre cotizacionCodNombre
		FROM 
		compras.operDetalle op
		JOIN compras.cotizacion c ON c.idCotizacion = op.idCotizacion
		WHERE op.estado = 1
		{$filtros}
		ORDER BY op.idOper DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerGapEmpresas($params = [])
	{

		$filtros = '';

		$sql = "
		DECLARE @fecha DATE = GETDATE();
		SELECT
		g.idEmpresa,
		gd.gap
		FROM
		compras.gap g
		JOIN compras.gapDetalle gd ON g.idGap = gd.idGap
			AND g.estado = 1
			AND General.dbo.fn_fechaVigente(gd.fechaInicio,gd.fechaFin,@fecha,@fecha) = 1
			AND gd.estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCosto($params = [])
	{

		$filtros = "";
		$filtros .= !empty($params['id']) ? " (" . $params['id'] . ")" : "";

		$sql = "
			DECLARE @fechaInicio date = getDate()-15, @fechaFin date = getDate(), @fechaHoy date = getDate();
			WITH listTarifario AS (
		select
		ci. costo as CostoActual,
		ci. fechaVigencia as Vigencia,
		ci. flag_actual as Flag,
		ci. idItem as idItem,
		ci. idProveedor as idProveedor,
		cd.idCotizacion,
		DATEDIFF(DAY,@fechaHoy,ci.fechaVigencia) AS diasVigencia
		from compras.itemTarifario ci
		JOIN compras.cotizacionDetalle cd ON ci. idItem = cd. idItem
			WHERE 1 = 1

			 AND cd.idCotizacion IN {$filtros}
			AND General.dbo.fn_fechaVigente(@fechaHoy,
			ci.fechaVigencia,@fechaInicio,@fechaFin) = 1
			AND flag_actual = 1
			), lst_tarifario_det AS(
			SELECT
			lt.CostoActual,
			lt.Vigencia,
			lt.Flag,
			lt.idItem,
			lt.idProveedor,
				CASE
					WHEN diasVigencia <= -2 THEN 'green'
					WHEN diasVigencia > -2 AND diasVigencia <= 0 THEN 'yellow'
					ELSE 'red' END
					AS semaforoVigencia
				, diasVigencia
			FROM listTarifario lt
		)
		SELECT
		ls.*,
		CASE WHEN ls.diasVigencia > 0 THEN 1 ELSE 0 END cotizacionInterna
		FROM
		lst_tarifario_det ls
		";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerCotizacionDetalleTarifario($params = [])
	{

		$filtros = "";

		$filtros .= !empty($params['idCotizacion']) ? " (" . $params['idCotizacion'] . ")" : "";

		$sql = "
			DECLARE @fechaInicio date = getDate()-15, @fechaFin date = getDate(), @fechaHoy date = getDate();
			WITH listItem AS (
				SELECT
					cd.idCotizacion AS idCotizacion,
					cd.idCotizacionDetalle AS idCotizacionDetalle,
					ci.fechaVigencia as Vigencia,
					ISNULL(cd.nombre,'') item,
					i.nombre as itemNombre,
					ABS(DATEDIFF(DAY,@fechaHoy,ISNULL(ci.fechaVigencia,@fechaHoy))) AS diasVigencia,
					cd.idItem AS idItem,
					cd.cantidad AS cantidad,
					ci.costo AS costo,
					cd.caracteristicasCompras AS caracteristicasCompras,
					cd.caracteristicasProveedor AS caracteristicasProveedor,
					cd.subtotal AS subtotal,
					c.total AS total,
					cd.idItemTipo AS idItemTipo,
					cd.caracteristicas AS caracteristicas,
					cd.gap AS gap,
					cd.precio AS precio,
					cd.enlaces AS enlaces,
					ci.idProveedor AS idProveedor,
					p.razonSocial AS razonSocial,
					ci.flag_actual AS flag_actual,
					cd.costo as costoCotizacion,
					cd.flagRedondear,
					cd.cantPdv,
					cd.flagPackingSolicitado,
					cd.costoPacking,
					cd.flagMovilidadSolicitado,
					cd.costoMovilidad,
					cd.flagPersonalSolicitado,
					cd.costoPersonal,
					cd.flagMostrarDetalle,
					cd.requiereOrdenCompra
				FROM
					compras.cotizacion c
				JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
				LEFT JOIN compras.proveedor p ON p.idProveedor = cd.idProveedor
				LEFT JOIN compras.item i ON i.idItem = cd.idItem
				LEFT JOIN compras.itemTarifario ci ON ci.idItem = cd.idItem AND flag_actual = 1
				WHERE
				1 = 1 and cd.estado=1
				and cd.idCotizacion in {$filtros}

			), lst_tarifario_det AS(
				SELECT
				lt.idCotizacion,
				lt.idCotizacionDetalle,
				lt.Vigencia,
				lt.item,
				lt.itemNombre,
				lt.diasVigencia,
				lt.idItem,
				lt.cantidad,
				lt.costo,
				lt.caracteristicasCompras,
				lt.caracteristicasProveedor,
				lt.subtotal,
				lt.total,
				lt.idItemTipo,
				lt.caracteristicas,
				lt.gap,
				lt.precio,
				lt.enlaces,
				lt.idProveedor,
				lt.razonSocial,
				lt.flag_actual,
				lt.costoCotizacion,
				lt.flagRedondear,
				CASE
				-- WHEN diasVigencia <= 7 AND lt.idProveedor is not null THEN 'green'
				WHEN diasVigencia <= -2 AND lt.idProveedor is not null THEN 'green'
				-- WHEN diasVigencia > 7 AND diasVigencia < 15 THEN 'yellow'
				WHEN diasVigencia > -2 AND diasVigencia <= 0 THEN 'yellow'
				ELSE 'red' END
				AS semaforoVigencia,
				lt.cantPdv,
				lt.flagPackingSolicitado,
				lt.flagMostrarDetalle,
				lt.requiereOrdenCompra

				FROM listItem lt
			)
			SELECT
			ls.*,
			CASE
			-- WHEN ls.diasVigencia > 15 or idProveedor is null THEN 1
			WHEN ls.diasVigencia > 0 or idProveedor is null THEN 1
			ELSE 0
			END cotizacionInterna
			FROM
			lst_tarifario_det ls
		";

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerInformacionDetalleCotizacionSubdis($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";

		$sql = "
		SELECT
				cd.idCotizacion,
				cd.idItemTipo,
				cds.idCotizacionDetalleSub,
				cds.idCotizacionDetalle,
				cds.idTipoServicio,
				cds.idUnidadMedida,
				cds.nombre,
				cds.talla,
				cds.tela,
				cds.color,
				cds.cantidad,
				cds.costo costoSubItem,
				ts.costo,
				cds.subtotal,
				cds.monto,
				cds.cantidadPdv,
				cds.idItem,
				cds.idDistribucionTachado,
				UPPER(ts.nombre) tipoServicio,
				um.nombre unidadMedida,
				cds.genero,
				cds.idProveedorDistribucion,
				cds.cantidadReal,
				cds.requiereOrdenCompra,
				cds.sucursal,
				cds.razonSocial,
				cds.marca,
				cds.tipoElemento,
				ISNULL(il.nombre, il2.nombre) as itemLogistica,
				cds.peso,
				cds.flagOtrosPuntos,
				cds.idZona,
				cds.dias,
				cds.costoVisual,
				cds.cod_departamento,
				cds.cod_provincia,
				cds.cod_distrito,
				cds.idTipoServicioUbigeo
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			JOIN compras.cotizacionDetalleSub cds ON cds.idCotizacionDetalle = cd.idCotizacionDetalle
			LEFT JOIN compras.tipoServicio ts ON ts.idTipoServicio = cds.idTipoServicio
			LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cds.idUnidadMedida
			LEFT JOIN visualImpact.logistica.articulo il ON il.idArticulo = cds.idItem and cds.flagItemInterno = 0
			LEFT JOIN compras.item il2 ON il2.idItem = cds.idItem and cds.flagItemInterno = 1
			WHERE
			1 = 1
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

	public function obtenerCostoDistribucion($params = [])
	{
		$filtros = "";
		$sql = "
			DECLARE @hoy DATE = GETDATE();
			SELECT 
				* 
			FROM compras.distribucionCosto
			WHERE 
			General.dbo.fn_fechaVigente(fecIni,fecFin,@hoy,@hoy) = 1
			{$filtros} 
			";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerMonedas($params = [])
	{
		$filtros = "";
		$sql = "
			DECLARE @hoy DATE = GETDATE();
			SELECT 
			m.idMoneda,
			m.nombre moneda,
			m.icono,
			md.valor
			FROM compras.moneda m
			JOIN compras.monedaDet md ON md.idMoneda = m.idMoneda
			WHERE 
			General.dbo.fn_fechaVigente(md.fecIni,md.fecFin,@hoy,@hoy) = 1
			{$filtros} 
			";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function getPropuestasItem($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN({$params['idCotizacion']})" : '';
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN({$params['idCotizacionDetalle']})" : '';

		$sql = "
			SELECT
				cd.idCotizacionDetalle,
				--pia.idPropuestaItemArchivo,
				--pia.nombre_inicial archivo,
				--pia.nombre_archivo archivoWasabi,
				m.nombre motivo,
				UPPER(p.razonSocial) proveedor,
				p.idProveedor,
				(pi.costo * pi.cantidad) subtotal,
				pi.*
			FROM
			compras.propuestaItem pi
			JOIN compras.propuestaMotivo m ON m.idPropuestaMotivo = pi.idPropuestaMotivo
			JOIN compras.cotizacionDetalleProveedorDetalle pd ON pd.idCotizacionDetalleProveedorDetalle = pi.idCotizacionDetalleProveedorDetalle
			JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = pd.idCotizacionDetalle
			JOIN compras.cotizacionDetalleProveedor pp ON pp.idCotizacionDetalleProveedor = pd.idCotizacionDetalleProveedor
			JOIN compras.proveedor p ON p.idProveedor = pp.idProveedor
			-- JOIN compras.propuestaItemArchivo pia ON pia.idPropuestaItem = pi.idPropuestaItem
			WHERE 
			1 = 1
			{$filtros} 
			";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function getTachadoDistribucion($params = [])
	{
		$filtros = "";

		$sql = "
			SELECT
			*
			FROM
			compras.distribucionTachado
			WHERE 
			1 = 1
			AND estado = 1
			{$filtros} 
			";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function infoHistorialCotizacionDescende($id)
	{
		$sql = 'select top (1) 
				auth.fechaReg as fechaRegistro,
				auth.horaReg as horaRegistro,
				cot.nombre as nombreCotizacion,
				cot.codCotizacion as codigoCotizacion,
				cot.fechaEmision as fechaCreacion,
				us.nombres as nombreUsuario,
				us.apePaterno as apellidoUsuario,
				est.nombre as nombreEstado
				from ImpactBussiness.compras.cotizacionEstadoHistorico auth
				inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
				inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
				inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
				where auth.idCotizacion = 586 order by idCotizacionEstadoHistorico DESC';

		return $this->db->query($sql)->result_array();
	}

	public function getCotizacionDelProveedor()
	{
		$this->db
			->distinct()
			->select('cdpd.idCotizacionDetalleProveedorDetalle, cdp.idCotizacion, c.nombre, p.razonSocial, cdp.idProveedor, cdpd.idCotizacionDetalle')
			->from('compras.cotizacionDetalleProveedorDetalle cdpd')
			->join('compras.cotizacionDetalleProveedor cdp', 'cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor', 'left')
			->join('compras.cotizacion c', 'c.idCotizacion = cdp.idCotizacion', 'left')
			->join('compras.proveedor p', 'p.idProveedor = cdp.idProveedor', 'left')
			->where('fechaEntrega is not null')
			->where('cdpd.estado = 1')
			// ->group_by('cdp.idCotizacion, c.nombre, p.razonSocial, cdp.idProveedor')
			->order_by('2 desc, 5');

		return $this->db->get();
	}

	public function obtener_cargos($idCentro = null)
	{
		$filtro = '';
		if (!empty($idCentro)) {
			$filtro .= 'AND idArea IN (
				select idArea from rrhh.dbo.empresa_Canal WHERE idEmpresaCanal=' . $idCentro . '
			)';
		}
		$sql = "
			select idCargoTrabajo,nombre from rrhh.dbo.CargoTrabajo where 1=1 AND flag=1 $filtro
		";
		return $this->db->query($sql);
	}

	public function getAll_Cargos($params = [])
	{
		$this->db
			->select("ct.idCargoTrabajo, ct.nombre + ' / ' + a.nombre as cargo, e.idEmpresa, e.nombre as empresa, ISNULL(su.sueldo, 0) as sueldo", false)
			->from('rrhh.dbo.CargoTrabajo ct')
			->join('rrhh.dbo.Area a', 'ct.idArea = a.idArea', 'INNER')
			->join('rrhh.dbo.Empresa e', 'a.idEmpresa = e.idEmpresa', 'INNER')
			->join('(
				select min(sueldo) sueldo, idCargoTrabajo from rrhh.dbo.sueldo where estado=1 group by idCargoTrabajo
			) as su', 'su.idCargoTrabajo=ct.idCargoTrabajo', 'LEFT')
			->order_by('ct.nombre');

		$this->db->where('ct.flag', 1);
		if (isset($params['idCuenta'])) $this->db->where('e.idEmpresa', $params['idCuenta']);
		if (isset($params['soloCargosOcupados'])) $this->db->where("ct.idCargoTrabajo IN (
				SELECT DISTINCT e.idCargoTrabajo 
				FROM rrhh.dbo.empleado e
				INNER JOIN rrhh.dbo.empleadoCargoTrabajo ec ON e.idEmpleado=ec.idEmpleado WHERE e.flag = 'activo' AND (
					ec.fecFin is null
					OR ec.fecFin >= GETDATE()
				)
			)");

		return $this->db->get();
	}

	public function obtener_sueldos($idCuenta, $idCentro, $idCargo)
	{
		$filtro = '';
		if (!empty($idCuenta)) $filtro .= 'AND idEmpresa=' . $idCuenta;
		if (!empty($idCargo)) $filtro .= 'AND idCargoTrabajo=' . $idCargo;
		if (!empty($idCentro)) {
			$filtro .= 'AND idSubcanal IN (
				SELECT idSubcanal FROM rrhh.dbo.empresa_Canal WHERE idEmpresaCanal=' . $idCentro . '
			)';
		}
		$sql = "
			SELECT *,1025*0.1 asignacionFamiliar FROM rrhh.dbo.sueldo WHERE 1=1 $filtro
		";

		return $this->db->query($sql);
	}

	public function obtener_conceptos_adicionales($idTipo, $cantidad)
	{
		$sql = "
			DECLARE 
				@fecha DATE = GETDATE()
			SELECT *,SUM(total) OVER() total_final FROM (
			SELECT 
				cc.idConcepto
				, cc.nombre
				, cc.id_campo
				, cc.grupo
				, ccc.costo
				, ccc.costo*$cantidad as total
			FROM 
				rrhh.dbo.conceptosCotizacion cc
				JOIN rrhh.dbo.conceptosCotizacionCosto ccc
					ON ccc.idConcepto=cc.idConcepto
					AND @fecha BETWEEN ccc.fecIni AND ISNULL(ccc.fecFin,@fecha)
				JOIN rrhh.dbo.conceptosCotizacionTipo cct
					ON cct.idConcepto=cc.idConcepto
			WHERE
				cct.idTipo=$idTipo
			)a
		";
		return $this->db->query($sql);
	}

	public function obtenerPeriodo()
	{
		$sql = "
			select distinct idMes,anio, mes+'-'+anio periodo from General.dbo.tiempo 
			WHERE fecha>=GETDATE() ORDER BY anio,idMes
			";
		return $this->db->query($sql);
	}

	public function obtenerDetalleItemPersonal($id)
	{
		$sql = "
			SELECT 
				cd.idCotizacionDetalle
				, cd.nombre
				, cd.cantidad_personal
			FROM 
				ImpactBussiness.compras.cotizacionDetalle cd
			WHERE 
				idCotizacion=$id 
				AND idItemTipo=5
		";
		return $this->db->query($sql);
	}
}
