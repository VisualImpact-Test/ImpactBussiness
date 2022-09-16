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

	public function obtenerCuentaCentroCosto($params = [])
	{
		$filtros = '';
		!empty($params['estadoCentroCosto']) ? $filtros .= " AND c.estado_centro = 1" : "";

		$sql = "
		DECLARE @hoy DATE = GETDATE();
		SELECT DISTINCT
			c.idEmpresa idDependiente,
			c.idEmpresaCanal id,
			c.subcanal value
		FROM
		rrhh.dbo.empresa_Canal c
		JOIN rrhh.dbo.empleadoCanalSubCanal ec ON ec.idEmpresa = c.idCanal
			AND General.dbo.fn_fechaVigente(ec.fecInicio,ec.fecFin,@hoy,@hoy)=1
		JOIN rrhh.dbo.Empresa emp ON emp.idEmpresa = c.idEmpresa
		JOIN rrhh.dbo.Empleado e ON e.idEmpleado = ec.idEmpleado
		WHERE
			e.flag = 'activo'
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
				ROW_NUMBER() OVER (PARTITION BY idCotizacion,idCotizacionEstado  ORDER BY idCotizacionEstado) fila
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
				, od.idOper
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND cotizacionInterna = 1) nuevos
				, ISNULL((SELECT CASE WHEN DATEDIFF(DAY,fechaReg,@hoy) <= p.diasValidez THEN 1 ELSE 0 END FROM lst_historico_estado WHERE idCotizacion = p.idCotizacion AND p.idCotizacionEstado IN(4,5) AND idCotizacionEstado = 4 AND fila = 1),1) cotizacionValidaCliente
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN rrhh.dbo.Empresa c ON p.idCuenta = c.idEmpresa
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idEmpresaCanal = p.idCentroCosto
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
				--, CONVERT( VARCHAR, pd.fechaCreacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaCreacion, 108) AS fechaCreacion
				, CONVERT( VARCHAR, pd.fechaCreacion, 103)  AS fechaCreacion
				, CONVERT( VARCHAR, pd.fechaModificacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaModificacion, 108) AS fechaModificacion
				, pd.caracteristicas
				, pd.caracteristicasCompras
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
				, ISNULL(DATEDIFF(DAY,ta.fechaVigencia,@fechaHoy),0) AS diasVigencia
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

			if(!empty($params['archivoExistente'][$k])){

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

				$archivosExistentes [] = [
						'idCotizacion' => $params['idCotizacion'],
						'idCotizacionDetalle' => $idCotizacionDetalle,
						'idTipoArchivo'=> $row['idTipoArchivo'],
						'nombre_inicial' => $row['nombre_inicial'],
						'nombre_archivo' => $row['nombre_archivo'],
						'nombre_unico' => $row['nombre_unico'],
						'extension' => $row['extension'],
						'idUsuarioReg' => $row['idUsuarioReg'],
						'estado' => true,


				];

			}

			if(!empty($archivosExistentes)){
				$this->db->insert_batch('compras.cotizacionDetalleArchivos', $archivosExistentes);
			}

			}

			//Sub Items
			if(!empty($params['insertSubItem'][$k])){
				foreach($params['insertSubItem'][$k] as $subItem){
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
			if(!empty($insertSubItem)){
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
		$filtros .= !empty($params['noTipoItem']) ? " AND cd.idItemTipo NOT IN({$params['noTipoItem']}) " : "";


		$sql = "
			SELECT
			cd.idCotizacion,
			cd.idCotizacionDetalle,
			ISNULL(cd.nombre,'') item,
			cd.idItem,
			cd.cantidad,
			cd.costo,
			cd.caracteristicasCompras,
			cd.subtotal,
			c.total,
			cd.idItemTipo,
			cd.caracteristicas,
			cd.gap,
			cd.precio,
			cd.enlaces,
			cd.idProveedor,
			p.razonSocial,
			cd.caracteristicasCompras
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

	public function obtenerInformacionDetalleCotizacionSub($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idCotizacion']) ? " AND cd.idCotizacion IN (" . $params['idCotizacion'] . ")" : "";
		$filtros .= !empty($params['idItemEstado']) ? " AND cd.idItemEstado = {$params['idItemEstado']}" : "";
		$filtros .= !empty($params['idCotizacionDetalle']) ? " AND cd.idCotizacionDetalle IN ({$params['idCotizacionDetalle']})" : "";
		$filtros .= !empty($params['cotizacionInterna']) ? " AND cd.cotizacionInterna = 1 " : "";
		$filtros .= !empty($params['noTipoItem']) ? " AND cd.idItemTipo NOT IN({$params['noTipoItem']}) " : "";


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
				cds.subtotal,
				cds.monto,
				ts.nombre tipoServicio,
				um.nombre unidadMedida

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
				p.razonSocial,
				(SELECT DISTINCT CASE WHEN costo IS NOT NULL AND costo <> 0 THEN 1 ELSE 0 END  FROM compras.cotizacionDetalleProveedorDetalle WHERE idCotizacionDetalleProveedor = c.idCotizacionDetalleProveedor AND idItem = cd.idItem) respuestasProveedor
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
	public function obtenerArchivoCotizacionDetalleProveedors(array $param=[])
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
			--ur.nombres + ' ' + ISNULL(ur.apePaterno,'') + ' ' + ISNULL(ur.apeMaterno,'') usuarioReceptor,
			'Coordinadora de compras' usuarioReceptor
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


	public function insertarCotizacionAnexos($data = []){
		$insert = true;

		foreach($data['anexos'] as $archivo){
			$archivoName = $this->saveFileWasabi($archivo);
			$tipoArchivo = explode('/',$archivo['type']);
			$insertArchivos[] = [
				'idCotizacion' => $data['idCotizacion'],
				'idTipoArchivo' => $tipoArchivo[0] == 'image' ? TIPO_IMAGEN : TIPO_PDF,
				'nombre_inicial' => $archivo['name'],
				'nombre_archivo' => $archivoName,
				'nombre_unico' => $archivo['nombreUnico'],
				'extension' => $tipoArchivo[1],
				'estado' => true,
				'idUsuarioReg' => $this->idUsuario,
				'flag_anexo' => true,
			];
		}



		if(!empty($insertArchivos)){
			$insert = $this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
		}

		if(!empty($data['anexoExistente'])){

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

			$imagenesExistentes [] = [
					'idCotizacion' => $data['idCotizacion'],
					'idTipoArchivo'=> $row['idTipoArchivo'],
					'nombre_inicial' => $row['nombre_inicial'],
					'nombre_archivo' => $row['nombre_archivo'],
					'nombre_unico' => $row['nombre_unico'],
					'extension' => $row['extension'],
					'idUsuarioReg' => $row['idUsuarioReg'],
					'flag_anexo' => $row['flag_anexo'],
					'estado' => true,


			];

		}

		if(!empty($imagenesExistentes)){
			$insert = $this->db->insert_batch('compras.cotizacionDetalleArchivos', $imagenesExistentes);
		}

		}

		if(!empty($data['anexosEliminados'])){
			$data['anexosEliminados'] = checkAndConvertToArray($data['anexosEliminados']);

			foreach($data['anexosEliminados'] as $anexoEliminado){
				$this->db->delete('compras.cotizacionDetalleArchivos',['idCotizacionDetalleArchivo' => $anexoEliminado]);
			}
		}

		return $insert;
	}

	public function actualizarCotizacionDetalleArchivos($params = [])
	{
		$insertArchivos = [];
		foreach($params['update'] as $k => $update){
			$idCotizacionDetalle = $update['idCotizacionDetalle'];
			unset($update['idCotizacionDetalle']);
			$queryCotizacionDetalle = $this->db->update($params['tabla'], $update, ['idCotizacionDetalle' => $idCotizacionDetalle ]);

			if(!empty($params['archivos'][$k])){
				foreach($params['archivos'][$k] as $archivo){
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/',$archivo['type']);
					$insertArchivos[] = [
						'idCotizacion' => $update['idCotizacion'],
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

			//Sub Items
			if(!empty($params['insertSubItem'][$k])){
				foreach($params['insertSubItem'][$k] as $subItem){
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
					];
				}
			}
			//Sub Items Actualizar
			if(!empty($params['subDetalle'][$k])){
				foreach($params['subDetalle'][$k] as $subItem){
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
			if(!empty($insertSubItem)){
				$this->db->insert_batch('compras.cotizacionDetalleSub', $insertSubItem);
			}
			if(!empty($updateSubItem)){
				$this->db->update_batch('compras.cotizacionDetalleSub', $updateSubItem,'idCotizacionDetalleSub');
			}

			if(!empty($params['archivoEliminado'])){
				$params['archivoEliminado'] = checkAndConvertToArray($params['archivoEliminado']);
				foreach($params['archivoEliminado'] as $archivoEliminado){
					$this->db->delete('compras.cotizacionDetalleArchivos',['idCotizacionDetalleArchivo' => $archivoEliminado]);
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



	public function obtenerCosto($params = []) {

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

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerCotizacionDetalleTarifario($params = []) {

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
			ABS(DATEDIFF(DAY,@fechaHoy,ISNULL(ci.fechaVigencia,@fechaHoy))) AS diasVigencia,
			cd.idItem AS idItem,
			cd.cantidad AS cantidad,
			ci.costo AS costo,
			cd.caracteristicasCompras AS caracteristicasCompras,
			cd.subtotal AS subtotal,
			c.total AS total,
			cd.idItemTipo AS idItemTipo,
			cd.caracteristicas AS caracteristicas,
			cd.gap AS gap,
			cd.precio AS precio,
			cd.enlaces AS enlaces,
			ci.idProveedor AS idProveedor,
			p.razonSocial AS razonSocial,
			ci.flag_actual AS flag_actual
			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			LEFT JOIN compras.proveedor p ON p.idProveedor = cd.idProveedor
			LEFT JOIN compras.item i ON i.idItem = cd.idItem
			LEFT JOIN compras.itemTarifario ci ON ci.idItem = cd.idItem
			AND flag_actual = 1
			WHERE
			1 = 1
			and cd.idCotizacion in {$filtros}

			 ), lst_tarifario_det AS(
			 SELECT
			lt.idCotizacion ,
			lt.idCotizacionDetalle ,
			lt.Vigencia ,
			lt.item ,
			lt.diasVigencia,
			lt.idItem ,
			lt.cantidad ,
			lt.costo ,
			lt.caracteristicasCompras ,
			lt.subtotal ,
			lt.total ,
			lt.idItemTipo ,
			lt.caracteristicas ,
			lt.gap ,
			lt.precio ,
			lt.enlaces ,
			lt.idProveedor ,
			lt.razonSocial ,
			lt.flag_actual ,
			CASE
				WHEN diasVigencia <= 7 AND lt.idProveedor is not null THEN 'green'
				WHEN diasVigencia > 7 AND diasVigencia < 15 THEN 'yellow'
				ELSE 'red' END
				AS semaforoVigencia

		FROM listItem lt
	)
	 SELECT
	 ls.*,
	CASE
	 WHEN ls.diasVigencia > 15 or idProveedor is null THEN 1
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
				ts.costo,
				cds.subtotal,
				cds.monto,
				UPPER(ts.nombre) tipoServicio,
				um.nombre unidadMedida

			FROM
			compras.cotizacion c
			JOIN compras.cotizacionDetalle cd ON c.idCotizacion = cd.idCotizacion
			JOIN compras.cotizacionDetalleSub cds ON cds.idCotizacionDetalle = cd.idCotizacionDetalle
			LEFT JOIN compras.tipoServicio ts ON ts.idTipoServicio = cds.idTipoServicio
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

}
