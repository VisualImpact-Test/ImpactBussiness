<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Finanzas extends MY_Model
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

	public function obtenerProveedorServicio($params = [])
	{
		$sql = "
            select ps.idProveedorServicio, ps.ruc, ps.razonSocial, ps.direccion,
                ps.nombreContacto, ps.numeroContacto, ps.correoContacto,
                pe.nombre AS estado, pe.idProveedorEstado, pe.icono AS estadoIcono, pe.nombre, pe.toggle AS estadoToggle,
                u.departamento, u.provincia, u.distrito, psp.monto, psp.diaPago, psp.frecuenciaPago,
                CONVERT(VARCHAR, psp.fechaInicio, 103) AS fechaInicio,
                CONVERT(VARCHAR, psp.fechaTermino, 103) AS fechaTermino, psp.descripcionServicio, md.simbolo
            from finanzas.proveedorServicio ps
            INNER JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = ps.idProveedorEstado
            INNER JOIN General.dbo.ubigeo u ON u.cod_ubigeo = ps.cod_ubigeo
            INNER JOIN finanzas.proveedorServicioPago psp ON psp.idProveedorServicio = ps.idProveedorServicio
            INNER JOIN compras.moneda md ON md.idMoneda = psp.idMoneda
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerComprobante($params = [])
	{
		$sql = "
			SELECT
				  idComprobante AS id
				, nombre AS value
			FROM compras.comprobante
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerArchivo($id)
	{
		$sql = "
			SELECT
			idInformacionBancariaProveedor
			,idProveedorArchivo
			,idProveedor
			,idTipoArchivo
			,nombre_archivo
			FROM compras.proveedorArchivo
			WHERE estado = 1 AND flagPrincipal = 1 AND idProveedor =" . $id;

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerProveedorTipoServicio($params = [])
	{
		$this->db
			->select('*')
			->from('compras.proveedorTipoServicio')
			->order_by('nombre');
		return $this->db->get();
	}

	public function obtenerMetodoPago($params = [])
	{
		$sql = "
			SELECT
				idMetodoPago AS id
				, nombre AS value
			FROM compras.metodoPago
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionBancaria($id)
	{
		$sql = "
		SELECT idInformacionBancariaProveedor
			,idProveedor
      		,cuenta
			,idMoneda
      		,idBanco
      		,idTipoCuentaBanco
      		,cci
  		FROM compras.informacionBancariaProveedor
		WHERE estado = 1 AND idProveedor = " . $id;

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}


	public function obtenerCorreosAdicionales($params = [])
	{
		$this->db
			->select('*')
			->from('compras.proveedorCorreo pc')
			->where('pc.idProveedor', $params['idProveedor'])
			->where('pc.estado', $params['estado']);
		return $this->db->get();
	}


	public function obtenerEstado($params = [])
	{
		$sql = "
			SELECT
				idProveedorEstado AS id
				, nombre AS value
			FROM compras.proveedorEstado
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerProveedorDistribucion($param = [])
	{
		$this->db
			->select('*')
			->from('compras.proveedor');

		$this->db->where('visibleDistribucion', '1');

		return $this->db->get();
	}
	public function obtenerCiudadUbigeo()
	{

		$sql = "
			SELECT
				cod_ubigeo
				, cod_departamento
				, cod_provincia
				, cod_distrito
				, departamento
				, provincia
				, distrito
			FROM General.dbo.ubigeo
			WHERE estado = '1'
			ORDER BY departamento, provincia, distrito
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
	public function obtenerProveedoresActivos($params = [])
	{
		$this->db
			->select('*')
			->from('compras.proveedor')
			->where('idProveedorEstado', '2')
			->order_by('razonSocial asc'); //Activo
		return $this->db->get();
	}
	public function obtenerUltimaRespuestaEstado($idProveedor)
	{
		$this->db
			->select('*')
			->from('compras.proveedorEstadoHistorico')
			->where('idProveedor', $idProveedor)
			->order_by('fechaReg desc');
		return $this->db->get();
	}
	public function obtenerInformacionProveedores($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['estadoProveedor']) ? ' AND p.idProveedorEstado = ' . $params['estadoProveedor'] : '';
		$filtros .= !empty($params['rubroProveedor']) ? ' AND pr.idRubro = ' . $params['rubroProveedor'] : '';
		$filtros .= !empty($params['metodoPagoProveedor']) ? ' AND at.idMetodoPago = ' . $params['metodoPagoProveedor'] : '';
		$filtros .= !empty($params['idProveedor']) ? ' AND p.idProveedor = ' . $params['idProveedor'] : '';
		if ($this->idUsuario != '1') {
			$filtros .= ' AND p.demo != 1';
		}

		$orden = !empty($params['order_by']) ? 'ORDER BY ' . $params['order_by'] : "ORDER BY p.idProveedor DESC";


		$sql = "
		SELECT DISTINCT
		p.idProveedor
		, p.razonSocial
		, p.nroDocumento
		, r.idRubro
		, r.nombre AS rubro
		, mp.idMetodoPago
		, mp.nombre AS metodoPago
		, ubi.cod_departamento
		, ubi.departamento
		, ubi.cod_provincia
		, ubi.provincia
		, ubi.cod_ubigeo
		, ubi.distrito
		, p.direccion
		, ubi_zc.departamento AS zc_departamento
		, zc.cod_departamento AS zc_cod_departamento
		, (CASE WHEN zc.cod_provincia IS NULL THEN NULL ELSE ubi_zc.provincia END) AS zc_provincia
		, (CASE WHEN zc.cod_provincia IS NULL THEN NULL ELSE zc.cod_provincia END) AS zc_cod_provincia
		, (CASE WHEN zc.cod_distrito IS NULL THEN NULL ELSE ubi_zc.distrito END) AS zc_distrito
		, (CASE WHEN zc.cod_distrito IS NULL THEN NULL ELSE zc.cod_distrito END) AS zc_cod_distrito
		, p.nombreContacto
		, p.correoContacto
		, p.numeroContacto
		, p.informacionAdicional
		, ep.idProveedorEstado
		, ep.nombre AS estado
		, ep.icono AS estadoIcono
		, ep.toggle AS estadotoggle
		, p.costo
		, pts.idProveedorTipoServicio
		, ts.nombre as tipoServicio
		, cp.idComprobante
		, cp.nombre as comprobante
		, ibp.idInformacionBancariaProveedor
		, ibp.cuenta
		, ibp.idBanco
				, bc.nombre as banco
		, ibp.idTipoCuentaBanco
				, tcb.nombre as tipoCuenta
		, p.chkDetraccion
		, p.cuentaDetraccion
		, ibp.cci
		FROM  compras.proveedor p
		JOIN General.dbo.ubigeo ubi ON p.cod_ubigeo = ubi.cod_ubigeo
		JOIN compras.proveedorRubro pr ON pr.idProveedor = p.idProveedor
		JOIN compras.informacionBancariaProveedor ibp ON ibp.idProveedor = p.idProveedor
		LEFT JOIN compras.proveedorComprobante pc ON pc.idProveedor = p.idProveedor
		JOIN compras.rubro r ON pr.idRubro = r.idRubro
		LEFT JOIN compras.comprobante cp ON cp.idComprobante = pc.idComprobante
		JOIN compras.proveedorMetodoPago at ON at.idproveedor = p.idProveedor
		LEFT JOIN compras.proveedorProveedorTipoServicio pts ON pts.idproveedor = p.idProveedor and pts.estado=1
		LEFT JOIN compras.proveedorTipoServicio ts ON ts.idProveedorTipoServicio = pts.idProveedorTipoServicio
		JOIN compras.metodoPago mp ON at.idMetodoPago = mp.idMetodoPago
		JOIN compras.zonaCobertura zc ON p.idProveedor = zc.idProveedor
		JOIN General.dbo.ubigeo ubi_zc ON zc.cod_departamento = ubi_zc.cod_departamento
		AND ISNULL(zc.cod_provincia, 1) = (CASE WHEN zc.cod_provincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
		AND ISNULL(zc.cod_distrito, 1) = (CASE WHEN zc.cod_distrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
		JOIN compras.proveedorEstado ep ON p.idProveedorEstado = ep.idProveedorEstado
			LEFT JOIN dbo.banco bc ON bc.idBanco = p.idBanco 
			LEFT JOIN dbo.tipoCuentaBanco tcb ON tcb.idTipoCuentaBanco = p.idTipoCuentaBanco
		-- AND ubi_zc.estado = 1
			WHERE 1 = 1
			{$filtros}
			{$orden}
		";

		// var_dump($sql);
		// exit;

		$query = $this->db->query($sql);

		if ($query) {

			//echo $this->db->last_query();
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function validarExistenciaProveedor($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idProveedor']) ? ' AND p.idProveedor != ' . $params['idProveedor'] : '';

		$sql = "
			SELECT
				idProveedor
			FROM compras.proveedor p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.nroDocumento LIKE '%{$params['nroDocumento']}%'
			)
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

	public function insertarProveedor($params = [])
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

	public function insertarInformacionBancaria($params = [])
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

	public function insertarProveedorCobertura($params = [])
	{
		if (!empty($params['where'])) {
			$this->db->delete($params['tabla'], $params['where']);
		}
		$query = $this->db->insert_batch($params['tabla'], (empty($params['insert'])) ? $params['update'] : $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarProveedor($params = [])
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

	public function actualizarInformacionBancaria($params = [])
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

	public function BorrarProveedorMetodoPago($params = [])
	{
		$query = $this->db->delete($params['tabla'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function proveedorProveedorTipoServicioActualizarSinDuplicar($params)
	{
		// La intension de esta funcion es evitar la duplicidad de datos en la tabla proveedorProveedorTipoServicio.

		// Todo a estado 0
		$this->db->update('compras.proveedorProveedorTipoServicio', ['estado' => 0], ['idProveedor' => $params[0]['idProveedor']]);

		foreach ($params as $key => $value) {
			$query = $this->db->get_where('compras.proveedorProveedorTipoServicio', $value);
			$data = $query->row_array();

			if (empty($data)) { // Registramos los faltantes.
				$this->db->insert('compras.proveedorProveedorTipoServicio', $value);
			} else { // Activamos con 1 los ya registrados.
				$this->db->update('compras.proveedorProveedorTipoServicio', ['estado' => 1], ['idProveedorProveedorTipoServicio' => $data['idProveedorProveedorTipoServicio']]);
			}
		}

		return true;
	}
	public function obtenerZonaCoberturaProveedor($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idProveedor']) ? ' AND zc.idProveedor = ' . $params['idProveedor'] : '';

		$sql = "
			SELECT
			zc.idProveedor,
			zc.cod_departamento,
			zc.cod_provincia,
			zc.cod_distrito,
			(SELECT TOP 1 departamento FROM General.dbo.ubigeo WHERE cod_departamento = zc.cod_departamento) departamento ,
			(SELECT TOP 1 provincia FROM General.dbo.ubigeo WHERE cod_departamento = zc.cod_departamento AND cod_provincia = zc.cod_provincia) provincia ,
			(SELECT TOP 1 distrito FROM General.dbo.ubigeo WHERE cod_departamento = zc.cod_departamento AND cod_provincia = zc.cod_provincia AND cod_distrito = zc.cod_distrito) distrito
			FROM compras.zonaCobertura zc

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

	public function insertarTipoServicio($params = [])
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

	public function validarExistenciaProveedorServicio($params = [])
	{

		if ($params['tipoDocumento'] === 'DNI') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.dni LIKE '%{$params['numeroDocumento']}%'
			)
			
		";
		} elseif ($params['tipoDocumento'] === 'RUC') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.ruc LIKE '%{$params['numeroDocumento']}%'
			)
			
		";
		} elseif ($params['tipoDocumento'] === 'CE') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.carnet_extranjeria LIKE '%{$params['numeroDocumento']}%'
			)
			
		";
		}

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
