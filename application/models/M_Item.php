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

	public function obtenerTipoItem($params = [])
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

	public function obtenerMarcaItem($params = [])
	{
		$sql = "
			SELECT
				idItemMarca AS id
				, nombre AS value
			FROM compras.itemMarca
			WHERE estado = 1
			ORDER BY nombre
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCategoriaItem($params = [])
	{
		$sql = "
			SELECT
				idItemCategoria AS id
				, nombre AS value
			FROM compras.itemCategoria
			WHERE estado = 1
			order by nombre
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
			ORDER BY nombre
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerItemTextil($params = [])
	{
		$sql = "
			SELECT
				idItemtextil AS id
				, talla AS itemTalla
				, tela AS itemTela
				, color AS itemColor
			FROM compras.itemTextil
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerTarifario()
	{
		$this->db
			->select('it.*, i.nombre as item, p.razonSocial as proveedor, DATEDIFF (DAY, GETDATE(), it.fechaVigencia ) as diasRestantes')
			->from('compras.itemTarifario it')
			->join('compras.item i', 'i.idItem=it.idItem', 'LEFT')
			->join('compras.proveedor p', 'p.idProveedor=it.idProveedor', 'LEFT')
			->where('it.flag_actual', 1)
			->where('i.estado', 1)
			->order_by('i.nombre');

		return $this->db->get();
	}

	public function obtenerInformacionItems($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoItem']) ? ' AND a.idItemTipo = ' . $params['tipoItem'] : '';
		$filtros .= !empty($params['marcaItem']) ? ' AND a.idItemMarca = ' . $params['marcaItem'] : '';
		$filtros .= !empty($params['categoriaItem']) ? ' AND a.idItemCategoria = ' . $params['categoriaItem'] : '';
		$filtros .= !empty($params['subcategoriaItem']) ? ' AND a.idItemSubCategoria = ' . $params['subcategoriaItem'] : '';
		$filtros .= !empty($params['item']) ? " AND a.nombre LIKE '%" . $params['item'] . "%'" : "";
		$filtros .= !empty($params['idItem']) ? ' AND a.idItem = ' . $params['idItem'] : '';
		$filtros .= !empty($params['cuenta']) ? ' AND a.idCuenta = ' . $params['cuenta'] : '';
		if ($this->idUsuario != 1) {
			$filtros .= ' AND a.demo = 0';
		}

		$sql = "
			SELECT
				a.idItem
				, ta.idItemTipo
				, ta.nombre AS tipoItem
				, ma.idItemMarca
				, ma.nombre AS itemMarca
				, ca.idItemCategoria
				, ca.nombre AS itemCategoria
				, sca.idItemSubCategoria
				, sca.nombre AS itemSubCategoria
				, a.nombre AS item
				, id.talla
				, id.tela
				, id.color
				, id.monto
				, a.caracteristicas
				, a_l.idArticulo AS idItemLogistica
				, a_l.nombre AS equivalenteLogistica
				, a.estado
				, a.idUnidadMedida
				, a.idCuenta
				, a.flagPacking
				, a.flagParaPresupuesto
				, a.idTipoPresupuesto
				, a.idTipoPresupuestoDetalle
			FROM compras.item a
			JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN compras.itemSubCategoria sca ON a.idItemSubCategoria = sca.idItemSubCategoria
			LEFT JOIN compras.itemDetalle id ON a.idItem = id.idItem
			LEFT JOIN visualImpact.logistica.articulo a_l ON a.idItemLogistica = a_l.idArticulo
			WHERE 1 = 1
			{$filtros}
			ORDER BY a.estado desc, a.nombre
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
		$filtros .= !empty($params['logistica']) ? " AND ISNULL(a.idItemLogistica, 0 ) <> 0" : "";

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
			{$filtros}
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
		JOIN listTarifario it on it.idItem = i.idItem and it.ntarifario=1
		LEFT JOIN compras.proveedor pr ON it.idProveedor = pr.idProveedor
		LEFT JOIN listImagenes img ON img.idItem = i.idItem
		WHERE i.estado = 1
		order by 2
		";
		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.item', 'id' => null];
		return $result;
	}
	public function obtenerItemsLogistica()
	{
		$sql = "
			SELECT DISTINCT
				a.idArticulo AS value
				, ISNULL(a.codigo + ' - ','') + a.nombre AS label
				, ISNULL(a.peso,0) as pesoLogistica
				, um.idUnidadMedida AS idum
				, um.nombre AS um
			FROM visualimpact.logistica.articulo a
			LEFT JOIN visualimpact.logistica.articulo_det ad ON a.idArticulo = ad.idArticulo
			LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
			ORDER BY 2,1
		";

		$result = $this->db->query($sql)->result_array();
		return $result;
	}

	public function obtenerAllItemsLogistica()
	{
		$sql = "
			SELECT DISTINCT
				a.idArticulo AS id
				, a.nombre AS value
				, ISNULL(a.peso,0) as pesoLogistica
				, um.idUnidadMedida AS idum
				, um.nombre AS um
				, mc.idCuenta as cuenta
				, ISNULL(a.pesoCosto,0) as pesoCostoLogistica
			FROM visualimpact.logistica.articulo a
			LEFT JOIN visualimpact.logistica.articulo_det ad ON a.idArticulo = ad.idArticulo
			LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
			JOIN visualImpact.logistica.articulo_marca_cuenta mc ON mc.idMarca=a.idMarca
			ORDER BY 2,1
		";

		$result = $this->db->query($sql)->result_array();
		return $result;
	}

	public function obtenerUnidadMedida()
	{
		$sql = "
			SELECT
				idUnidadMedida id, nombre value
			FROM ImpactBussiness.compras.unidadMedida WHERE estado=1
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.articulo', 'id' => null];
		return $result;
	}

	public function validarExistenciaItem($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItem']) ? ' AND a.idItem != ' . $params['idItem'] : '';

		$sql = "
			SELECT
				idItem
			FROM compras.item a
			WHERE
			a.nombre = '{$params['nombre']}'
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

	public function validarExistenciaItemMasivo($params = array())
	{
		$filtros = "";
		$nombre = "";

		$filtros .= !empty($params['idItem']) ? ' AND a.idItem != ' . $params['idItem'] : '';

		foreach ($params as $key => $value) {
		}

		$sql = "
			SELECT
				idItem
			FROM compras.item a
			WHERE
			a.nombre = '{$value['nombre']}'
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

	//validar existencia itemImagen

	public function insertarItemImage($params = [])
	{
		$insertArchivos = [];

		if (!empty($params['archivos'])) {
			foreach ($params['archivos'] as $Grupo_archivo) {
				foreach ($Grupo_archivo as $archivo) {

					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);
					$insertArchivos[] = [
						'idItem' => $archivo['idItem'],
						'idTipoArchivo' => TIPO_IMAGEN,
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => $tipoArchivo[1],
						'estado' => true
					];
				}
			}
		}

		if (!empty($insertArchivos)) {
			$query = $this->db->insert_batch('compras.itemImagen', $insertArchivos);
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}
		// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];

		return $this->resultado;
	}

	public function obtenerItemImagenes($params = [])
	{
		return $this->db->where('estado', 1)->where('idItem', $params['idItem'])->get('compras.itemImagen');
	}

	public function insertarItem($params = [])
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

	public function actualizarItem($params = [])
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

	public function obtenerItemsCuenta($idCuenta, $idArticulo = null)
	{
		$filtro = '';
		if (!empty($idArticulo)) {
			$filtro = ' AND a.idArticulo=' . $idArticulo;
		}
		$sql = "
		SELECT DISTINCT
				a.idArticulo AS value
			, ISNULL(a.codigo + ' - ','') + a.nombre AS label
			, ISNULL(a.peso,0) as pesoLogistica
		FROM visualimpact.logistica.articulo a
		LEFT JOIN visualimpact.logistica.articulo_det ad ON a.idArticulo = ad.idArticulo
		LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
		JOIN visualImpact.logistica.articulo_marca_cuenta mc ON mc.idMarca=a.idMarca
		WHERE mc.idCuenta='" . $idCuenta . "' $filtro
		";
		return $query = $this->db->query($sql);
	}
	public function obtenerItemsCuenta2($idCuenta, $articulo = null, $nombre = true)
	{
		$selectOpc = $nombre ? 'a.nombre' : 'a.codigo';

		$filtro = '';
		if (!empty($articulo)) {
			$filtro = " AND $selectOpc='" . $articulo . "'";
		}
		// REVISANDO QUE LA BUSQUEDA SEA POR CODIGO O NOMBRE DEL ITEM PE1010110016-- ESE FALLA
		$sql = "
		SELECT DISTINCT
				a.idArticulo AS value
			, $selectOpc AS label
			, ISNULL(a.peso,0) as pesoLogistica
			, ISNULL(a.pesoCosto,0) as pesoCuenta
			, a.nombre
			, a.codigo
		FROM visualimpact.logistica.articulo a
		LEFT JOIN visualimpact.logistica.articulo_det ad ON a.idArticulo = ad.idArticulo
		LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
		JOIN visualImpact.logistica.articulo_marca_cuenta mc ON mc.idMarca=a.idMarca
		WHERE a.codigo is not null AND mc.idCuenta='" . $idCuenta . "' $filtro
		ORDER BY 2";
		return $query = $this->db->query($sql);
	}

	public function obtenerItemExcel()
	{
		$sql = "
			SELECT DISTINCT i.nombre AS item, it.nombre AS tipoItem, COALESCE(emp.nombre, '-') AS cuenta,
			iI.nombre_archivo, iI.extension
			FROM compras.item i
			INNER JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
			LEFT JOIN rrhh.dbo.Empresa emp ON emp.idEmpresa = i.idCuenta
			LEFT JOIN compras.itemImagen iI ON iI.idItem = i.idItem AND iI.estado = 1
			WHERE i.estado = 1;
		";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
}
