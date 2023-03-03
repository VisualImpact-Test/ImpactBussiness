<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Item extends MY_Model
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

	public function obtenerItemMarca($params = [])
	{
		$sql = "
			SELECT
				idItemMarca AS id
				, nombre AS value
			FROM compras.itemMarca
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerTarifarioItemProveedorParaActualizacionMasiva($params = [])
	{
		$this->db
		->select('p.razonSocial as proveedor, i.nombre as item, it.costo, it.fechaVigencia as fecha')
		->from('compras.itemTarifario it')
		->join('compras.item i', 'i.idItem = it.idItem', 'LEFT')
		->join('compras.proveedor p', 'p.idProveedor = it.idProveedor', 'LEFT')
		->where('it.estado', 1)
		->where('i.estado', 1)
		->where('p.idProveedorEstado', 2) // Activo
		->where('it.fechaVigencia < GETDATE()')
		// ->limit('5')
		->order_by('2, 1');

		return $this->db->get();
		
	}
	public function obtenerItemCategoria($params = [])
	{
		$sql = "
			SELECT
				idItemCategoria AS id
				, nombre AS value
			FROM compras.itemCategoria
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerSubCategoriaItem($params = [])
	{
		$sql = "
			SELECT
				idItemSubCategoria AS id
				, nombre AS value
			FROM compras.itemSubCategoria
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
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
			WHERE idProveedorEstado = 2
			order by 2
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionTAHistorico($params = [])
	{
		$sql = "
			SELECT
				ta.idItemTarifario
				, CONVERT(VARCHAR, tah.fecIni, 103) AS fecIni
				, ISNULL(CONVERT(VARCHAR, tah.fecFin, 103), 'En curso') AS fecFin
				, tah.costo
				, p.razonSocial AS proveedor
			FROM compras.itemTarifario ta
			JOIN compras.itemTarifarioHistorico tah ON ta.idItemTarifario = tah.idItemTarifario
			JOIN compras.proveedor p ON ta.idProveedor = p.idProveedor
			WHERE ta.idItem = {$params['idItem']}
			ORDER BY tah.idItemTarifarioHistorico DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionItemTarifario($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoItem']) ? ' AND ta.idItemTipo = ' . $params['tipoItem'] : '';
		$filtros .= !empty($params['marcaItem']) ? ' AND ma.idItemMarca = ' . $params['marcaItem'] : '';
		$filtros .= !empty($params['categoriaItem']) ? ' AND ca.idItemCategoria = ' . $params['categoriaItem'] : '';
		$filtros .= !empty($params['subCategoriaItem']) ? ' AND sca.idItemSubCategoria = ' . $params['subCategoriaItem'] : '';
		$filtros .= !empty($params['item']) ? " AND a.nombre LIKE '%" . $params['item'] . "%'" : "";
		$filtros .= !empty($params['proveedor']) ? ' AND p.idProveedor = ' . $params['proveedor'] : '';
		$filtros .= !empty($params['chMostrar']) ? ' AND tfa.flag_actual = ' . $params['chMostrar'] : '';
		$filtros .= !empty($params['precioMinimo']) ? ' AND tfa.costo >= ' . $params['precioMinimo'] : '';
		$filtros .= !empty($params['precioMaximo']) ? ' AND tfa.costo <= ' . $params['precioMaximo'] : '';
		$filtros .= !empty($params['idItemTarifario']) ? ' AND tfa.idItemTarifario = ' . $params['idItemTarifario'] : '';
		$filtros .= !empty($params['idItem']) ? ' AND a.idItem = ' . $params['idItem'] : '';
		$filtros .= !empty($params['idProveedor']) ? ' AND p.idProveedor = ' . $params['idProveedor'] : '';
		$filtros .= ($this->idTipoUsuario != '1') ? ' AND p.demo != 1 ' : '';

		$sql = "
			SELECT 
				tfa.idItemTarifario
				, ma.idItemMarca
				, ma.nombre AS itemMarca
				, ca.idItemCategoria
				, ca.nombre AS itemCategoria
				, sca.idItemSubCategoria
				, sca.nombre AS itemSubCategoria
				, ta.idItemTipo
				, ta.nombre AS itemTipo
				, a.idItem
				, a.nombre AS item
				, p.idProveedor
				, UPPER(p.razonSocial) AS proveedor
				, tfa.costo
				, tfa.flag_actual
				, tfa.estado
				, tfa.fechaVigencia
				, a.caracteristicas
			FROM compras.itemTarifario tfa
			JOIN compras.proveedor p ON tfa.idProveedor = p.idProveedor
			JOIN compras.item a ON tfa.idItem = a.idItem
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN compras.itemSubCategoria sca ON a.idItemSubCategoria = sca.idItemSubCategoria
			LEFT JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
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


	//Obtener proveedor no repetido - agregado

	public function obtenerProveedorNoRepetido($paramas = [])
	{

		$filtros = "";

		$filtros .= !empty($paramas['proveedor']) ? ' AND p.idProveedor = ' . $paramas['proveedor'] : '';


		$sql = "
			SELECT DISTINCT	
			p.idProveedor
			, p.razonSocial AS proveedor
			FROM compras.itemTarifario tfa
			JOIN compras.proveedor p ON tfa.idProveedor = p.idProveedor
			JOIN compras.item a ON tfa.idItem = a.idItem
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
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

	//Obtener flag no repetido - agregado

	public function obteneFlagNoRepetido()
	{


		$sql = "
		SELECT DISTINCT

				 tfa.flag_actual	
				
			FROM compras.itemTarifario tfa
			JOIN compras.proveedor p ON tfa.idProveedor = p.idProveedor
			JOIN compras.item a ON tfa.idItem = a.idItem
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
			WHERE 1 = 1
			
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}


	public function obtenerItems()
	{
		$sql = "
			SELECT
				a.idItem AS value
				, a.nombre AS label
			FROM compras.item a
			ORDER BY 2
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.item', 'id' => null];
		return $result;
	}



	public function validarExistenciaItemTarifario($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItemTarifario']) ? ' AND ta.idItemTarifario != ' . $params['idItemTarifario'] : '';

		$sql = "
			SELECT
				idItemTarifario
			FROM compras.itemTarifario ta
			WHERE
			ta.idItem = {$params['idItem']} AND ta.idProveedor = {$params['idProveedor']}
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

	public function validarExistenciaItemTarifarioMasivo($params = array())
	{
		$filtros = "";
		$nombre = "";


		$filtros .= !empty($params['idItem']) ? ' AND a.idItem != ' . $params['idItem'] : '';

		foreach ($params as $key => $value) {
		}


		$sql = "
			SELECT
				idItem
			FROM compras.itemTarifario ta
			WHERE
			ta.idItem = {$value['idItem']} AND ta.idProveedor = {$value['idProveedor']}
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

	public function validarItemTarifarioActual($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItemTarifario']) ? ' AND ta.idItemTarifario != ' . $params['idItemTarifario'] : '';

		$sql = "
			SELECT
				idItemTarifario
			FROM compras.itemTarifario ta
			WHERE
			ta.idItem = {$params['idItem']} AND flag_actual = 1 AND ta.idProveedor != {$params['idProveedor']}
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

	public function insertarItemTarifario($params = [])
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

	public function actualizarItemTarifario($params = [])
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


	public function obtenerItemsFotos($params = [])
	{
		$sql = "
			SELECT 
				it.idItemTarifario, ii.idTipoArchivo,ii.nombre_archivo,ii.extension,ii.nombre_inicial
			FROM compras.itemTarifario it
			JOIN compras.itemImagen ii ON ii.idItem=it.idItem
			WHERE 
				it.estado=1 AND ii.idTipoArchivo=2 AND ii.estado=1 AND it.idItemTarifario={$params['idItemTarifario']}
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.item', 'id' => null];
		return $result;
	}
}
