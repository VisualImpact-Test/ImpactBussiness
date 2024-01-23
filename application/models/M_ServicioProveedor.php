<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ServicioProveedor extends MY_Model
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

    public function obtenerServicioProveedor($params = [])
    {
        $filtros = "";
        $filtros .= !empty($params['idProveedorServicio']) ? " AND f.idProveedorServicio = {$params['idProveedorServicio']}" : '';

        $sql = "
		SELECT 
		*
		FROM finanzas.proveedorServicio f
		left join sistema.usuarioTipoDocumento utd on utd.idTipoDocumento =f.idTipoDocumento
		LEFT JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = f.idProveedorEstado
		LEFT JOIN General.dbo.ubigeo u ON f.departamento = u.cod_departamento and f.provincia = u.cod_provincia  and f.distrito = cod_distrito
		
		
          WHERE 1 = 1 {$filtros}
        ";

        $query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
    }
	
	public function ObtenerDatoServicioProveedor($params = [])
	{
		
		$filtros = "";
		$filtros .= !empty($params['idProveedorServicio']) ? ' AND f.idProveedorServicio = ' . $params['idProveedorServicio'] : '';

		$sql = "SELECT * FROM finanzas.proveedorServicio f
		left join sistema.usuarioTipoDocumento utd on utd.idTipoDocumento =f.idTipoDocumento
		LEFT JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = f.idProveedorEstado
		LEFT JOIN General.dbo.ubigeo u ON f.departamento = u.cod_departamento and f.provincia = u.cod_provincia  and f.distrito = cod_distrito
		where 1=1 
		{$filtros}
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function ObtenerDatoServicioProveedorContacto($params = [])
	{
		
		$filtros = "";
		$filtros .= !empty($params['idProveedorServicio']) ? ' AND idProveedorServicio = ' . $params['idProveedorServicio'] : '';

		$sql = "SELECT * FROM finanzas.proveedorServicioContacto
		WHERE 1=1
		{$filtros}
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}


	public function ObtenerDatosTipoDocumento($params = [])
	{
	
		$sql = "select idTipoDocumento as id , breve as value  from sistema.usuarioTipoDocumento
		where 1=1 and idTipoDocumento in (2,1,3,6)";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
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

	public function obtenerCiudadUbigeo($params=[])
	{

        $filtros = "";
        $filtros .= !empty($params['cod_ubigeo']) ? " AND cod_ubigeo = {$params['cod_ubigeo']}" : '';

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
            {$filtros}
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

    public function actualizarServicioProveedor($params = [])
	{
		$query = $this->db->update($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}

	public function validarExistenciaServicioProveedor($params = [])
	{
		$filtros = "";
		$numDoc = $params['numDocumento'];
        $filtros .= !empty($params['numDocumento']) ? " AND numDocumento = '{$numDoc}'" : '';

		$sql = "
		SELECT * FROM finanzas.proveedorServicio
		where 1 = 1 
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
