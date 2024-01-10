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
		$this->db->select('mp.idMetodoPago AS id, mp.nombre AS value');
		$this->db->from('compras.metodoPago mp');
		$this->db->join('compras.proveedorMetodoPago provMP', 'provMP.idMetodoPago = mp.idMetodoPago');
		$this->db->join('compras.proveedor p', 'p.idProveedor = provMP.idProveedor');
		$this->db->where('mp.estado = 1');

		if (isset($params['idProveedor'])) $this->db->where('provMP.idProveedor', $params['idProveedor']);
		return $this->db->get();
	}

	public function obtenerMetodoPago1($id)
	{
		$sql = "
		SELECT
			mp.idMetodoPago AS id
			, mp.nombre AS value
		FROM compras.metodoPago mp
		JOIN compras.proveedorMetodoPago provMP ON provMP.idMetodoPago = mp.idMetodoPago
		JOIN compras.proveedor p ON p.idProveedor = provMP.idProveedor
		WHERE mp.estado = 1 AND provMP.idProveedor =" . $id;

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function validarPropuestaExistencia($params = [])
	{
		$this->db
			->select('pi.*')
			->from('compras.propuestaItem pi')
			->where('pi.idCotizacionDetalleProveedorDetalle', $params['idCotizacionDetalleProveedorDetalle'])
			->where('pi.estado', '1');
		return $this->db->get();
	}

	public function getPropuestaArchivos($params = [])
	{
		$this->db
			->select('pia.*')
			->from('compras.propuestaItemArchivo pia')
			->where('pia.idPropuestaItem', $params['idPropuestaItem'])
			->where('pia.estado', '1');
		return $this->db->get();
	}
	public function obtenerCategorias($params = [])
	{
		$this->db
			->select('ic.*')
			->from('compras.itemCategoria ic')
			->where('ic.estado', '1')
			->order_by('nombre');
		return $this->db->get();
	}

	public function obtenerMarcas($params = [])
	{
		$this->db
			->select('im.*')
			->from('compras.itemMarca im')
			->where('im.estado', '1')
			->order_by('nombre');
		return $this->db->get();
	}

	function getDistinctOC($params = [])
	{
		$this->db->distinct()
			->select('ocd.idOrdenCompra')
			->from('compras.ordenCompraDetalle ocd')
			->join('compras.cotizacionDetalle cd', 'ocd.idCotizacionDetalle = cd.idCotizacionDetalle')
			->join('compras.ordenCompra oc', 'oc.idOrdenCompra = ocd.idOrdenCompra');

		if (isset($params['idCotizacion'])) {
			$this->db->where('cd.idCotizacion', $params['idCotizacion']);
		}
		if (isset($params['idProveedor'])) {
			$this->db->where('oc.idProveedor', $params['idProveedor']);
		}
		return $this->db->get();
	}

	function getDistinctOC2($params = [])
	{
		$this->db->distinct()
			->select('ocd.idOrdenCompra')
			->from('compras.ordenCompraDetalle ocd')
			->join('compras.ordenCompra oc', 'oc.idOrdenCompra = ocd.idOrdenCompra');

		if (isset($params['idOrdenCompra'])) {
			$this->db->where('oc.idOrdenCompra', $params['idOrdenCompra']);
		}

		return $this->db->get();
	}

	public function obtenerMotivos($params = [])
	{
		$this->db
			->select('pm.*')
			->from('compras.propuestaMotivo pm')
			->where('pm.estado', '1');
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
			JOIN compras.proveedorRubro pr ON pr.idproveedor = p.idProveedor
			JOIN compras.rubro r ON pr.idRubro = r.idRubro
			JOIN compras.proveedorMetodoPago at ON at.idproveedor = p.idProveedor
			JOIN compras.metodoPago mp ON at.idMetodoPago = mp.idMetodoPago
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

	public function loginProveedor($params = [])
	{
		$filtros = '';
		$filtros .= !empty($params['idProveedor']) ? " AND idProveedor = {$params['idProveedor']}" : "";

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
			{$filtros}
		";
		return $this->db->query($sql);
	}
	public function obtenerCotizacionDetalleProveedorDetalleArchivos($params = [])
	{
		$this->db->select('cdpda.*')
			->from('compras.cotizacionDetalleProveedorDetalleArchivos cdpda')
			->join('compras.cotizacionDetalleProveedorDetalle cdpd', 'cdpd.idCotizacionDetalleProveedorDetalle=cdpda.idCotizacionDetalleProveedorDetalle', 'left')
			->join('compras.cotizacionDetalleProveedor cdp', 'cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor', 'left')
			->where('cdpda.estado', '1');
		$this->db->where('cdp.idProveedor', $params['idProveedor']);
		$this->db->where('cdp.idCotizacion', $params['idCotizacion']);
		return $this->db->get();
	}
	public function obtenerInformacionCotizacionDetalleSub($params = [])
	{
		$this->db
			->select('cdpds.*, cds.nombre, cds.talla, cds.tela, cds.color, cds.cantidad as cantidadItem, cds.genero')
			->from('compras.cotizacionDetalleProveedorDetalleSub cdpds')
			->join('compras.cotizacionDetalleSub cds', 'cds.idCotizacionDetalleSub = cdpds.idCotizacionDetalleSub', 'left')
			->where('cdpds.estado', 1);
		isset($params['idCotizacionDetalleProveedorDetalle']) ? $this->db->where('cdpds.idCotizacionDetalleProveedorDetalle', $params['idCotizacionDetalleProveedorDetalle']) : '';
		return $this->db->get();
	}
	public function obtenerNombreArchivo(array $param = [])
	{
		$this->db
			->select('*')
			->from('compras.cotizacionDetalleArchivos')
			->where('idCotizacionDetalle', $param['idCotizacionDetalle']);
		return $this->db->get();
	}
	public function obtenerInformacionCotizacionProveedor($params = [])
	{
		$filtros = "WHERE 1 = 1";
		$filtros .= !empty($params['idProveedor']) ? "AND cdp.idProveedor = {$params['idProveedor']}" : '';
		$filtros .= !empty($params['idCotizacion']) ? "AND cdp.idCotizacion = {$params['idCotizacion']}" : '';
		$filtros .= !empty($params['flag_activo']) ? "AND cdpd.flag_activo = 1" : '';
		$filtros .= !empty($params['idCotizacionDetalle']) ? "AND cdpd.idCotizacionDetalle IN( {$params['idCotizacionDetalle']} )" : '';

		$sql = "
		SELECT
			cdp.idCotizacionDetalleProveedor,
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			ei.idItemEstado,
			ei.nombre AS estadoItem,
			cdpd.costo,
			cd.cantidad,
			cdp.idProveedor,
			cdp.idCotizacion,
			cd.idCotizacionDetalle,
			p.razonSocial proveedor,
			um.nombre unidadMedida,
			cdpd.costo/cd.cantidad as costoUnitario,
			cdpd.comentario,
			cdpd.diasValidez,
			CONVERT(VARCHAR, cdpd.fechaValidez, 103) AS fechaValidez,
			cdpd.diasEntrega,
			cdpd.fechaEntrega,
			cde.nombre AS cotizacionDetalleEstado,
			CONVERT( VARCHAR, cd.fechaCreacion, 103)  AS fechaCreacion,
			cd.caracteristicasProveedor,
			cdpd.flag_activo,
			cdpd.sucursal,
			cdpd.razonSocial,
			cdpd.tipoElemento,
			cdpd.marca,
			it.idItemTipo,
			cd.enlaces
		FROM
		compras.cotizacionDetalleProveedor cdp
		JOIN compras.proveedor p ON p.idProveedor = cdp.idProveedor
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdpd.idCotizacionDetalle
		JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
		LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cd.idUnidadMedida
		LEFT JOIN compras.item i ON i.idItem = cdpd.idItem AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = cd.idItemTipo
		JOIN compras.itemEstado ei ON cd.idItemEstado = ei.idItemEstado
		$filtros
		AND cdp.estado = 1
		";
		return $this->db->query($sql);
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
			(p.razonSocial LIKE '%{$params['razonSocial']}%'
			OR p.nroDocumento LIKE '%{$params['nroDocumento']}%')
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

	public function obtenerListaCotizaciones($data = [])
	{
		$this->db
			->distinct()
			->select("
		CONVERT(VARCHAR, MIN(cd.fechaEntrega), 103) AS fechaEntrega,
		ocd.idOrdenCompra,
		o.seriado,
		CONVERT(VARCHAR, c.fechaEmision, 103) AS fechaEmision,
		c.nombre, 
		c.motivo, 
		c.total,
		c.idCotizacion,
		cc.nombre AS cuentaCentroCosto,
		c.motivoAprobacion,
		pr.razonSocial AS proveedor,
		cu.nombre AS cuenta, 
		pr.idProveedor,
		c.codOrdenCompra, 
		0 AS flagOcLibre,
		o.estadoval,
		MAX(cdp.idCotizacionDetalleProveedor) AS idCotizacionDetalleProveedor
	")
			->from('compras.ordenCompraDetalle ocd')
			->join('compras.ordenCompra o', 'o.idOrdenCompra = ocd.idOrdenCompra', 'INNER')
			->join('compras.proveedor pr', 'pr.idProveedor = o.idProveedor', 'INNER')
			->join('compras.cotizacionDetalle cd', 'ocd.idCotizacionDetalle = cd.idCotizacionDetalle', 'INNER')
			->join('compras.cotizacion c', 'c.idCotizacion = cd.idCotizacion', 'INNER')
			->join('compras.cotizacionDetalleProveedor cdp', 'c.idCotizacion = cdp.idCotizacion', 'LEFT')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'c.idCentroCosto = cc.idCuentaCentroCosto', 'INNER')
			->join('visualImpact.logistica.cuenta cu', 'c.idCuenta = cu.idCuenta', 'INNER')
			->where('ocd.estado', '1')
			->group_by("
		ocd.idOrdenCompra,
		o.seriado,
		CONVERT(VARCHAR, c.fechaEmision, 103),
		c.nombre, 
		c.motivo, 
		c.total,
		c.idCotizacion,
		cc.nombre,
		c.motivoAprobacion,
		pr.razonSocial,
		cu.nombre, 
		pr.idProveedor,
		c.codOrdenCompra,
		o.estadoval
	")
			->order_by('ocd.idOrdenCompra DESC');

		if ($this->idUsuario != 1) $this->db->where('pr.demo', 0);
		// isset($params['idProveedor']) ? $this->db->where('cd.idProveedor', $params['idProveedor']) : '';

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE) =', $data['fechaEmision']);
		if (isset($data['idCuenta'])) $this->db->where('c.idCuenta', $data['idCuenta']);
		if (isset($data['idCentroCosto'])) $this->db->where('c.idCentroCosto', $data['idCentroCosto']);
		if (isset($data['codPo_'])) $this->db->like('c.codOrdenCompra', $data['codPo_']);


		return $this->db->get();
	}

	public function obtenerListaCotizaciones2($data = [])
	{
		$this->db
			->distinct()
			->select("
				CONVERT(VARCHAR, MIN(cd.fechaEntrega), 103) AS fechaEntrega,
				cp.idOrdenCompra,
				cd.seriado,
				CONVERT(VARCHAR, cd.fechaReg, 103) AS fechaEmision,
				(NULL) AS nombre,
				(NULL) AS motivo,
				cd.total,
				(NULL) AS idCotizacion,
				cc.nombre AS cuentaCentroCosto,
				(NULL) AS motivoAprobacion,
				pr.razonSocial AS proveedor,
				cu.nombre AS cuenta,
				cd.idProveedor,
				cd.requerimiento AS codOrdenCompra,
				1 AS flagOcLibre,
				cd.estadoval,
				(NULL) AS idCotizacionDetalleProveedor")
			->from('orden.ordenCompra cd')
			->join('orden.ordenCompraDetalle cp', 'cd.idOrdenCompra = cp.idOrdenCompra', 'INNER')
			->join('compras.proveedor pr', 'pr.idProveedor = cd.idProveedor', 'INNER')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'cd.idCentroCosto = cc.idCuentaCentroCosto', 'LEFT')
			->join('visualImpact.logistica.cuenta cu', 'cd.idCuenta = cu.idCuenta', 'INNER')
			->where('cd.estado', '1')
			->group_by("
			cd.seriado,
        CONVERT(VARCHAR, cd.fechaReg, 103),
        cd.total,
        cc.nombre,
        cu.nombre,
        cd.idProveedor,
        pr.razonSocial,
        cd.requerimiento,
        cp.idOrdenCompra,
		cd.estadoval")
			->order_by('cp.idOrdenCompra', 'DESC');


		if ($this->idUsuario != 1) $this->db->where('pr.demo', 0);
		// isset($params['idProveedor']) ? $this->db->where('cd.idProveedor', $params['idProveedor']) : '';

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE) =', $data['fechaEmision']);
		if (isset($data['idCuenta'])) $this->db->where('c.idCuenta', $data['idCuenta']);
		if (isset($data['idCentroCosto'])) $this->db->where('c.idCentroCosto', $data['idCentroCosto']);
		if (isset($data['codPo_'])) $this->db->like('c.codOrdenCompra', $data['codPo_']);


		return $this->db->get();
	}

	public function obtenerCotizacionDetalleProveedor($params)
	{

		$filtros = "WHERE 1 = 1";
		$filtros .= !empty($params['idProveedor']) ? ' AND cp.idProveedor = ' . $params['idProveedor'] : '';
		$filtros .= !empty($params['estado']) ? ' AND cp.estado = ' . $params['estado'] : '';
		$filtros .= !empty($params['idCotizacion']) ? ' AND cp.idCotizacion = ' . $params['idCotizacion'] : '';

		$sql = "
			SELECT
			*
			FROM
			compras.cotizacionDetalleProveedor cp
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
	public function obtenerOrdenCompraDetalleProveedor($params)
	{

		$filtros = "WHERE o.estado = 1";
		$filtros .= !empty($params['idProveedor']) ? ' AND o.idProveedor = ' . $params['idProveedor'] : '';
		$filtros .= !empty($params['idOrdenCompra']) ? ' AND o.idOrdenCompra = ' . $params['idOrdenCompra'] : '';

		$sql = "
			SELECT
				o.idOrdenCompra,
				o.idProveedor,
				--SUM(cp.costo * cp.cantidad) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
				SUM((cp.costo / md.valor) * cp.cantidad) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
				p.razonSocial,
				p.nroDocumento rucProveedor,
				p.nombreContacto,
				p.direccion,
				p.correoContacto,
				p.numeroContacto,
				CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega,
				CONVERT(VARCHAR, o.fechaReg, 103) AS fechaRegistro,
				cp.idCotizacion,
				o.requerimiento,
				o.concepto,
				o.observacion,
				o.entrega,
				o.idMetodoPago,
				o.idMoneda,
				o.pocliente,
				o.igv,
				o.comentario,
				o.mostrar_imagenes,
				o.mostrar_imagenesCoti,
				o.mostrar_observacion,
				m.nombre moneda,
				md.valor valorMoneda,
				mp.nombre metodoPago,
				uf.nombre_archivo,
				cp.idCotizacionDetalle,
				cp.cantidad,
				cp.nombre,
				cp.costo,
				m.nombreMoneda monedaPlural,
				m.simbolo simboloMoneda,
				--(cp.costo * cp.cantidad) subtotal,
				((cp.costo / md.valor) * cp.cantidad) subtotal,
				cp.idItemTipo,
				cp.subtotal as cotizacionSubTotal,
				cp.idItem,
				i.caracteristicas as caracteristicaItem,
				cp.caracteristicasCompras,
				o.seriado
			FROM
			compras.ordenCompra o
			JOIN compras.ordenCompraDetalle od ON od.idOrdenCompra = o.idOrdenCompra
				AND od.estado = 1
			JOIN compras.cotizacionDetalle cp ON od.idCotizacionDetalle = cp.idCotizacionDetalle
			JOIN compras.proveedor p ON p.idProveedor = o.idProveedor
			JOIN compras.moneda m ON m.idMoneda = o.idMoneda
			JOIN compras.monedaDet md ON md.idMoneda = m.idMoneda
				AND General.dbo.fn_fechaVigente(md.fecIni,md.fecFin,o.fechaReg,o.fechaReg)=1
			JOIN compras.metodoPago mp ON mp.idMetodoPago = o.idMetodoPago
			LEFT JOIN compras.item i ON i.idItem = cp.idItem
			LEFT JOIN sistema.usuario us ON us.idUsuario=o.idUsuarioReg
			LEFT JOIN sistema.usuarioFirma uf ON uf.idUsuarioFirma=us.idUsuarioFirma

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

	public function obtenerOrdenCompraLibre($params)
	{
		$this->db
			->select('
				ocd.*, 
				oc.*,
				/* NECESARIO PARA EL REPORTE */
				oc.total as subTotalOrdenCompra,
				oc.IGVPorcentaje as igv,
				ocd.idTipo as idItemTipo,
				null as caracteristicasCompras,
				costoUnitario as costo,
				costoSubTotal as subtotal,
				/* FIN */
				i.nombre,
				pr.razonSocial,
				pr.nroDocumento rucProveedor,	
				pr.nroDocumento ruc,
				pr.nombreContacto,
				pr.direccion,
				pr.correoContacto,
				pr.numeroContacto,
				mo.simbolo as simboloMoneda,
				mo.nombreMoneda as monedaPlural
				', false)
			->from('orden.ordenCompraDetalle ocd')
			->join('orden.ordenCompra oc', 'oc.idOrdenCompra = ocd.idOrdenCompra')
			->join('compras.proveedor pr', 'pr.idProveedor = oc.idProveedor')
			->join('compras.item i', 'i.idItem = ocd.idItem')
			->join('compras.moneda mo', 'mo.idMoneda = oc.idMoneda')
			->where('oc.estado', 1)->where('ocd.estado', 1);
		if (isset($params['idOrdenCompra'])) $this->db->where('ocd.idOrdenCompra', $params['idOrdenCompra']);
		return $this->db->get();
	}

	public function obtenerOrdenCompraDetalleProveedorOC($params)
	{

		$filtros = "WHERE o.estado = 1";
		$filtros .= !empty($params['idProveedor']) ? ' AND o.idProveedor = ' . $params['idProveedor'] : '';
		$filtros .= !empty($params['idOrdenCompra']) ? ' AND o.idOrdenCompra = ' . $params['idOrdenCompra'] : '';

		$sql = "
		SELECT
				o.idOrdenCompra,
				od.idOrdenCompraDetalle,
				o.idProveedor,
				--SUM(cp.costo * cp.cantidad) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
				o.total AS subTotalOrdenCompra,
				p.razonSocial,
				p.nroDocumento rucProveedor,
				p.nombreContacto,
				p.direccion,
				p.correoContacto,
				p.numeroContacto,
				CONVERT(VARCHAR, o.fechaEntrega, 103) AS fechaEntrega,
				CONVERT(VARCHAR, o.fechaReg, 103) AS fechaRegistro,
				0 AS idCotizacion,
				o.requerimiento,
				o.concepto,
				o.observacion,
				o.entrega,
				o.idMetodoPago,
				o.idMoneda,
				o.pocliente,
				o.IGVPorcentaje,
				o.comentario,
				o.mostrar_observacion,
				m.nombre moneda,
				md.valor valorMoneda,
				mp.nombre metodoPago,
				uf.nombre_archivo,
				od.cantidad,
				i.nombre,
				od.costoUnitario as costo,
				m.nombreMoneda monedaPlural,
				m.simbolo simboloMoneda,
				--(cp.costo * cp.cantidad) subtotal,
				od.costoSubTotal as subtotal,
				i.idItemTipo,
				od.costoSubTotal as cotizacionSubTotal,
				od.idItem,
				i.caracteristicas as caracteristicaItem,
				--i.caracteristicasCompras,
				o.seriado
			FROM
			orden.ordenCompra o
			LEFT JOIN orden.ordenCompraDetalle od ON od.idOrdenCompra = o.idOrdenCompra
				AND od.estado = 1
			--LEFT JOIN compras.cotizacionDetalle cp ON o.idProveedor = cp.idProveedor
			LEFT JOIN compras.proveedor p ON p.idProveedor = o.idProveedor
			LEFT JOIN compras.moneda m ON m.idMoneda = o.idMoneda
			LEFT JOIN compras.monedaDet md ON md.idMoneda = m.idMoneda
				AND General.dbo.fn_fechaVigente(md.fecIni,md.fecFin,o.fechaReg,o.fechaReg)=1
			LEFT JOIN compras.metodoPago mp ON mp.idMetodoPago = o.idMetodoPago
			LEFT JOIN compras.item i ON i.idItem = od.idItem
			LEFT JOIN sistema.usuario us ON us.idUsuario=o.idUsuarioReg
			LEFT JOIN sistema.usuarioFirma uf ON uf.idUsuarioFirma=us.idUsuarioFirma
			
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

	public function insertarMasivoDetalleProveedor($params)
	{

		$post = !empty($params['post']) ? $params['post'] : [];
		foreach ($params['insert'] as $row) {
			$query = $this->db->insert($params['tabla'], $row);
			$idCotizacionDetalleProveedorDetalle = $this->db->insert_id();

			if (!empty($post["idCotizacionDetalleSub[{$row['idCotizacionDetalle']}]"])) {
				$cotizacionesSub = checkAndConvertToArray($post["idCotizacionDetalleSub[{$row['idCotizacionDetalle']}]"]);

				foreach ($cotizacionesSub as $idSub) {
					$params['insertSub'][] = [
						'idCotizacionDetalleProveedorDetalle' => $idCotizacionDetalleProveedorDetalle,
						'idCotizacionDetalleSub' => $idSub,
						'costo' => NULL,
						'subTotal' => NULL,
						'fechaCreacion' => getActualDateTime(),
						'estado' => true,
					];
				}
			}
		}

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			if (!empty($params['insertSub'])) {
				$this->db->insert_batch("compras.cotizacionDetalleProveedorDetalleSub", $params['insertSub']);
			}
		}

		return $this->resultado;
	}
}
