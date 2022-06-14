<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_FormularioProveedor extends MY_Model
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
		$query = $this->db->insert_batch($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function obtenerInformacionProveedor($params = [])
	{

		$sql = "
			SELECT DISTINCT
				p.razonSocial
				, p.nroDocumento
				, r.nombre AS rubro
				, mp.nombre AS metodoPago
				, ubi.departamento
				, ubi.provincia
				, ubi.distrito
				, p.direccion
				, ubi_zc.departamento AS zc_departamento
				, (CASE WHEN zc.cod_provincia IS NULL THEN NULL ELSE ubi_zc.provincia END) AS zc_provincia
				, (CASE WHEN zc.cod_distrito IS NULL THEN NULL ELSE ubi_zc.distrito END) AS zc_distrito
				, p.nombreContacto
				, p.correoContacto
				, p.numeroContacto
				, p.informacionAdicional
			FROM compras.proveedor p
			JOIN General.dbo.ubigeo ubi ON p.cod_ubigeo = ubi.cod_ubigeo
			JOIN compras.rubro r ON p.idRubro = r.idRubro
			JOIN compras.proveedorMetodoPago at ON at.idproveedor = p.idProveedor
			JOIN compras.metodoPago mp ON p.idMetodoPago = mp.idMetodoPago
			JOIN compras.zonaCobertura zc ON p.idProveedor = zc.idProveedor
			JOIN General.dbo.ubigeo ubi_zc ON zc.cod_departamento = ubi_zc.cod_departamento
			AND ISNULL(zc.cod_provincia, 1) = (CASE WHEN zc.cod_provincia IS NULL THEN 1 ELSE ubi_zc.cod_provincia END)
			AND ISNULL(zc.cod_distrito, 1) = (CASE WHEN zc.cod_distrito IS NULL THEN 1 ELSE ubi_zc.cod_distrito END)
			AND ubi_zc.estado = 1
			WHERE p.idProveedor = {$params['idProveedor']}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function loginProveedor($params = []){
		$sql = "
		SELECT 
			idProveedor,
			razonSocial,
			nroDocumento,
			idProveedorEstado
		FROM 
			compras.proveedor
		WHERE 
			nroDocumento like '%{$params['ruc']}%'
			AND correoContacto like '%{$params['email']}%'
		";

		return $this->db->query($sql);
	}
	public function obtenerInformacionCotizacionProveedor($params = [])
	{	
		$filtros = "WHERE 1 = 1";
		$filtros = !empty($params['idProveedor']) ? "AND cdp.idProveedor = {$params['idProveedor']}" : '' ;

		$sql = "
		SELECT 
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			cdpd.costo
		FROM 
		compras.cotizacionDetalleProveedor cdp 
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.item i ON i.idItem = cdpd.idItem
			AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
		$filtros
		";

		return $this->db->query($sql);
	}
}
