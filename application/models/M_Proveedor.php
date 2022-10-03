<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Proveedor extends MY_Model
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

	public function obtenerRubro($params = [])
	{
		$sql = "
			SELECT
				idRubro AS id
				, nombre AS value
			FROM compras.rubro
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
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
	public function obtenerUltimaRespuestaEstado($idProveedor)
	{
		$this->db
		->select('*')
		->from('compras.proveedorEstadoHistorico')
		->where('idProveedor',$idProveedor)
		->order_by('fechaReg desc');
		return $this->db->get();
	}
	public function obtenerInformacionProveedores($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['proveedorEstado']) ? ' AND p.idProveedorEstado = ' . $params['proveedorEstado'] : '';
		$filtros .= !empty($params['rubroProveedor']) ? ' AND p.idRubro = ' . $params['rubroProveedor'] : '';
		$filtros .= !empty($params['metodoPagoProveedor']) ? ' AND p.idMetodoPago = ' . $params['metodoPagoProveedor'] : '';
		$filtros .= !empty($params['idProveedor']) ? ' AND p.idProveedor = ' . $params['idProveedor'] : '';

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
			FROM compras.proveedor p
			JOIN General.dbo.ubigeo ubi ON p.cod_ubigeo = ubi.cod_ubigeo
			JOIN compras.proveedorRubro pr ON pr.idProveedor = p.idProveedor
			JOIN compras.rubro r ON pr.idRubro = r.idRubro
			JOIN compras.proveedorMetodoPago at ON at.idproveedor = p.idProveedor
			JOIN compras.metodoPago mp ON at.idMetodoPago = mp.idMetodoPago
			JOIN compras.zonaCobertura zc ON p.idProveedor = zc.idProveedor
			JOIN General.dbo.ubigeo ubi_zc ON zc.cod_departamento = ubi_zc.cod_departamento
			AND ISNULL(zc.cod_provincia, 1) = (CASE WHEN zc.cod_provincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
			AND ISNULL(zc.cod_distrito, 1) = (CASE WHEN zc.cod_distrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
			JOIN compras.proveedorEstado ep ON p.idProveedorEstado = ep.idProveedorEstado
			AND ubi_zc.estado = 1
			WHERE 1 = 1
			{$filtros}
			ORDER BY p.idProveedor DESC
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
		log_message('error', $sql);


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

	public function insertarProveedorCobertura($params = [])
	{
		if(!empty($params['where'])){
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



}
