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

	public function obtenerCuenta($params = [])
	{
		$sql = "
		SELECT DISTINCT
			emp.idEmpresa id,
			emp.razonSocial value
		FROM 
		rrhh.dbo.Empresa emp
		JOIN rrhh.dbo.empleadoCanalSubCanal ec ON ec.idEmpresa = emp.idEmpresa
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

	public function obtenerCuentaCentroCosto($params = [])
	{
		$sql = "
		SELECT DISTINCT
			c.idEmpresa idDependiente,
			CONVERT(VARCHAR,c.idEmpresa) +' - ' + CONVERT(VARCHAR,c.idCanal) id,
			c.canal value
		FROM 
		rrhh.dbo.empresa_Canal c
		JOIN rrhh.dbo.empleadoCanalSubCanal ec ON ec.idEmpresa = c.idCanal
		JOIN rrhh.dbo.Empleado e ON e.idEmpleado = ec.idEmpleado
		WHERE 
			e.flag = 'activo'
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

		$sql = "
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
				, p.fechaRequerida
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
				, od.idOper
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND cotizacionInterna = 1) nuevos
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			LEFT JOIN compras.operDetalle od ON od.idCotizacion = p.idCotizacion
				AND od.estado = 1
			
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
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
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
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
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




	public function obtenerInformacionCotizacionDetalle($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? ' AND p.idCotizacion = ' . $params['idCotizacion'] : '';
		$filtros .= !empty($params['idsCotizacion']) ? ' AND p.idCotizacion IN('. $params['idsCotizacion'].')' : '';

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
				, pr.razonSocial AS proveedor
				, cde.nombre AS cotizacionDetalleEstado
				, CONVERT( VARCHAR, pd.fechaCreacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaCreacion, 108) AS fechaCreacion
				, CONVERT( VARCHAR, pd.fechaModificacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaModificacion, 108) AS fechaModificacion
				, pd.caracteristicas
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			JOIN compras.itemTipo it ON pd.idItemTipo = it.idItemTipo
			JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			JOIN compras.cotizacionDetalleEstado cde ON pd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
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

	public function obtenerItemServicio()
	{
		$sql = "
		DECLARE @fechaHoy DATE = GETDATE();
		WITH listTarifario AS (
			SELECT
				a.idItem AS value
				, a.nombre AS label
				, ta.costo
				, pr.idProveedor
				, pr.razonSocial AS proveedor
				, a.idItemTipo AS tipo
				, DATEDIFF(DAY,ta.fechaVigencia,@fechaHoy) AS diasVigencia
			FROM compras.item a
			JOIN compras.itemTarifario ta ON a.idItem = ta.idItem
			LEFT JOIN compras.proveedor pr ON ta.idProveedor = pr.idProveedor
			WHERE (ta.flag_actual = 1 OR ta.flag_actual IS NULL)
		), lst_tarifario_det AS(
			SELECT
				lt.value
				, lt.label
				, lt.costo
				, lt.idProveedor
				, lt.proveedor
				, lt.tipo
				, CASE
					WHEN diasVigencia <= 7 THEN 'green'
					WHEN diasVigencia > 7 AND diasVigencia < 15 THEN 'yellow'
					ELSE 'red' END
					AS semaforoVigencia
				, diasVigencia
			FROM listTarifario lt
		)

		SELECT
		ls.*,
		CASE WHEN ls.diasVigencia > 15 THEN 1 ELSE 0 END cotizacionInterna
		FROM
		lst_tarifario_det ls
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.item', 'id' => null];
		return $result;
	}

	public function validarExistenciaCotizacion($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? ' AND p.idCotizacion != ' . $params['idCotizacion'] : '';

		$sql = "
			SELECT
				idCotizacion
			FROM compras.cotizacion p
			WHERE
			(p.nombre LIKE '%{$params['nombre']}%')
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
		foreach($params['insert'] as $k => $insert){
			$queryCotizacionDetalle = $this->db->insert($params['tabla'], $insert);
			$idCotizacionDetalle = $this->db->insert_id();

			if(!empty($params['archivos'][$k])){
				foreach($params['archivos'][$k] as $archivo){
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/',$archivo['type']);
					$insertArchivos[] = [
						'idCotizacion' => $insert['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'idTipoArchivo' => $tipoArchivo[0] == 'image' ? TIPO_IMAGEN : TIPO_PDF,
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => $tipoArchivo[1],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}
		}

		if ($queryCotizacionDetalle) {
			$this->resultado['query'] = $queryCotizacionDetalle;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();

			if(!empty($insertArchivos)){
				$this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
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


		$sql = "
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			ISNULL(cd.nombre,'') item,
			cd.idItem,
			cd.cantidad,
			cd.costo,
			cd.subtotal,
			c.total,
			cd.idItemTipo,
			cd.caracteristicas,
			cd.gap,
			cd.precio,
			cd.enlaces,
			cd.idProveedor,
			p.razonSocial
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			LEFT JOIN compras.proveedor p ON p.idProveedor = cd.idProveedor
			LEFT JOIN compras.item i ON i.idItem = cd.idItem
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
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";


		$sql = "
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
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
	public function obtenerInformacionDetalleCotizacionProveedores($params = [])
	{
		$filtros = "";

		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";

		$sqlUnion ="";
		if(!empty($params['union'])){
			$sqlUnion = "
			UNION
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			cd.nombre,
			it.idProveedor,
			CASE WHEN ith.idItemTarifarioHistorico IS NOT NULL THEN 1 ELSE 0 END  respuestasProveedor
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
				(SELECT DISTINCT CASE WHEN costo IS NOT NULL AND costo <> 0 THEN 1 ELSE 0 END  FROM compras.cotizacionDetalleProveedorDetalle WHERE idCotizacionDetalleProveedor = c.idCotizacionDetalleProveedor AND idItem = cd.idItem) respuestasProveedor
			FROM
			compras.cotizacionDetalleProveedor c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			WHERE
			1 = 1
			{$filtros}
			{$sqlUnion}
			)
			SELECT
			idCotizacion,
			idCotizacionDetalle,
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
	public function obtenerInformacionDetalleCotizacionProveedoresParaVista($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND c.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";

		$sqlUnion = "";
		if(!empty($params['union'])){
			$sqlUnion = "
			UNION
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			cd.idProveedor,
			(ith.costo * cd.cantidad) subTotal,
			cd.cantidad,
			p.razonSocial,
			ith.costo costoUnitario
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

			SELECT
			c.idCotizacion,
			cd.idCotizacionDetalle,
			cd.idItem,
			c.idProveedor,
			cd.costo subTotal,
			cdl.cantidad,
			p.razonSocial,
			(cd.costo / cdl.cantidad) costoUnitario
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
				AND u.demo = 0
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
			'' cuentas,
			'' centrosCosto,
			'' ordenCompra,
			CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, o.fechaReg, 103) AS fechaReg,
			ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') usuarioRegistro,
			ur.nombres + ' ' + ISNULL(ur.apePaterno,'') + ' ' + ISNULL(ur.apeMaterno,'') usuarioReceptor
		FROM compras.oper o 
		JOIN compras.operDetalle od ON od.idOper = o.idOper
		LEFT JOIN sistema.usuario ue ON ue.idUsuario = o.idUsuarioReg
		LEFT JOIN sistema.usuario ur ON ur.idUsuario = o.idUsuarioReceptor
		WHERE o.estado = 1
		{$filtros}
	";

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
				, CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega
				, CONVERT(VARCHAR, o.fechaReg, 103) AS fechaReg
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') usuario
			FROM compras.ordenCompra o
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
	public function obtenertipoServicios($params = [])
	{
		$sql = "
			SELECT
				ts.idTipoServicio id,
				ts.nombre value,
				ts.costo,
				um.nombre unidadMedida
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
}
