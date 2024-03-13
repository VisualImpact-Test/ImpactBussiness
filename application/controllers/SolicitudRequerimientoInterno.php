<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SolicitudRequerimientoInterno extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_FormularioRequerimientoInterno', 'model');
		$this->load->model('M_Item', 'model_item');
		$this->load->model('M_Cotizacion', 'm_cotizacion');
		$this->load->model('M_control', 'model_control');
		$this->load->model('M_Proveedor', 'model_proveedor');
		$this->load->model('M_FormularioProveedor', 'mFormProveedor');
		$this->load->model('M_Moneda', 'mMoneda');
		$this->load->model('M_OrdenCompra', 'm_OrdenCompra');
	}

	public function index()
	{
		$config = array();
		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/select.dataTables.min'
		);
		$config['js']['script'] = array(
			'assets/libs/select2/4.0.13/js/select2',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/SolicitudRequerimientoInterno',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['title'] = 'Solicitud de Requerimientos Internos';
		$config['data']['icon'] = 'fa fa-home';

		$config['view'] = 'formularioRequerimientosInternos/SolicitudRequerimientoInterno/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$dataParaVista = [];
		$solicitanteInterno = $this->session->userdata('idUsuario');
		$dataParaVista['requerimientoInterno'] = $this->model->obtenerInformacionRequerimientoInterno($solicitanteInterno)['query']->result_array();

		$html = getMensajeGestion('noResultados');
		if (!empty($dataParaVista['requerimientoInterno'])) {
			$html = $this->load->view("formularioRequerimientosInternos/SolicitudRequerimientoInterno/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentRequerimientoInterno']['datatable'] = 'tb-requerimientos-solicitanteInterno';
		$result['data']['views']['idContentRequerimientoInterno']['html'] = $html;
		$result['data']['configTable'] = [
			'columnDefs' =>
			[
				0 =>
				[
					"visible" => false,
					"targets" => []
				]
			]
		];

		echo json_encode($result);
	}
	public function formularioAprobacionRequerimientoInterno()
	{
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$this->load->library('Mobile_Detect');
		$detect = $this->mobile_detect;
		$dataParaVista['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['col_dropdown'] = 'three column' : '';

		$itemServicio = $this->model->obtenerItemServicio();
		if (!empty($itemServicio)) {
			foreach ($itemServicio as $key => $row) {
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cotizacionInterna'] = $row['cotizacionInterna'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['flagCuenta'] = $row['flagCuenta'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['caracteristicas'] = $row['caracteristicas'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cantidadImagenes'] = $row['cantidadImagenes'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idUnidadMedida'] = $row['idUnidadMedida'];
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}

		$data['itemServicio'][0] = array();
		$dataParaVista['requerimientoTarifario'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		$dataParaVista['requerimientoInterno'] = $dataParaVista['requerimientoTarifario'][0];

		$dataParaVista['pdf'] = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => true])['query']->row_array();
		$archivos = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => false])['query']->result_array();

		foreach ($archivos as $archivo) {
			$dataParaVista['requerimientoInternoDetalleArchivos'][$archivo['idRequerimientoInternoDetalle']][] = $archivo;
		}

		$dataParaVista['itemServicio'] = $data['itemServicio'];
		$dataParaVista['proveedorSelect'] = $this->db->select('idProveedor AS id, razonSocial AS value')->get_where('compras.proveedor', 'idProveedorEstado = 1 OR idProveedorEstado = 2')->result_array();
		$dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedorEstado' => 2])->result_array();
		$dataParaVista['usuarioAprobar'] = $this->model->obtenerUsuarioAprobar()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta(['idUsuario' => $this->idUsuario])['query']->result_array();
		$dataParaVista['tipoMoneda'] = $this->m_cotizacion->obtenertipoMoneda()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->m_cotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['prioridad'] = $this->m_cotizacion->obtenerPrioridadCotizacion()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->m_cotizacion->obtenerTipoServicioCotizacion()['query']->result_array();

		foreach ($dataParaVista['requerimientoTarifario'] as $k => $v) {
			$listProveedores = $this->db->get_where('compras.solicitudCostoProveedor', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			$list = [];
			foreach ($listProveedores as $vp) {
				$list[] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
			}
			$dataParaVista['listProveedores'][$v['idItem']] = implode(', ', $list);
			$dataParaVista['listProveedoresCosto'][$v['idItem']] = $listProveedorCosto = $this->db->get_where('compras.itemTarifario', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			foreach ($listProveedorCosto as $kp => $vp) {
				$dataParaVista['listProveedoresCosto'][$v['idItem']][$kp]['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
				$config['data']['itemTarifario'][$v['idItem']][$vp['idProveedor']] = $vp['costo'];
			}
		}
		$config['data']['title'] = 'Aprobar Requerimiento';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioActualizacion", $dataParaVista, true);


		echo json_encode($config);
	}
	public function formularioVisualizacionRequerimientoInterno()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$data = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		foreach ($data as $key => $row) {
			$dataParaVista['cabecera']['idRequerimientoInterno'] = $row['idRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoInterno'] = $row['requerimientoInterno'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['cabecera']['codRequerimientoInterno'] = $row['codRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoIEstado'] = $row['requerimientoIEstado'];
			$dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
			$dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
			$dataParaVista['detalle'][$key]['fecha'] = !empty($row['fechaModificacion']) ? $row['fechaModificacion'] : $row['fechaCreacion'];
			$dataParaVista['detalle'][$key]['requerimientoInternoDetalleEstado'] = $row['requerimientoInternoDetalleEstado'];
		}

		if ($data[0]['requerimientoIEstado'] == 'Rechazado') {
			$dataParaVista['estados'] = $this->db->get_where('compras.requerimientoInternoEstado', ['nombre' => 'Rechazado'])->result_array();
		} else {
			$dataParaVista['estados'] = $this->db->get_where('compras.requerimientoInternoEstado', "nombre != 'Rechazado' AND nombre != 'Anulado'")->result_array();
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Visualizar Requerimiento Interno';
		$result['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioVisualizacion", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioRegistroOC()
	{
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$this->load->library('Mobile_Detect');
		$detect = $this->mobile_detect;
		$dataParaVista['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['col_dropdown'] = 'three column' : '';

		$itemServicio = $this->model->obtenerItemServicio(['idProveedor' => $post['proveedor']]);
		if (!empty($itemServicio)) {
			foreach ($itemServicio as $key => $row) {
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cotizacionInterna'] = $row['cotizacionInterna'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['flagCuenta'] = $row['flagCuenta'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['caracteristicas'] = $row['caracteristicas'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cantidadImagenes'] = $row['cantidadImagenes'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idUnidadMedida'] = $row['idUnidadMedida'];
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}

		$data['itemServicio'][0] = array();
		$dataParaVista['requerimientoTarifario'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		$dataParaVista['requerimientoInterno'] = $dataParaVista['requerimientoTarifario'][0];
		$dataParaVista['pdf'] = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => true])['query']->row_array();
		$archivos = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => false])['query']->result_array();

		foreach ($archivos as $archivo) {
			$dataParaVista['requerimientoInternoDetalleArchivos'][$archivo['idRequerimientoInternoDetalle']][] = $archivo;
			$dataParaVista['ocAdjunto'][$archivo['idRequerimientoInternoDetalle']][] = [
				'id' => $archivo['idRequerimientoInternoDetalleArchivo'],
				'nombre' => $archivo['nombre_archivo'],
				'idTipoArchivo' => $archivo['idTipoArchivo'],
				'origenBD' => 'compras.requerimientoInternoDetalleArchivos',
				'columnaBD' => 'idRequerimientoInternoDetalleArchivo',
				'carpeta' => 'requerimientoInterno/'
			];
		}
		$dataParaVista['proveedorFinal'] = $post['proveedor'];
		$dataParaVista['metodoPago'] = $this->mFormProveedor->obtenerMetodoPago(['idProveedor' => $post['proveedor']])->result_array();
		$dataParaVista['moneda'] = $this->mMoneda->obtenerMonedasActivas()->result_array();
		$dataParaVista['almacenes'] = $this->db->where('estado', '1')->get('visualImpact.logistica.almacen')->result_array();

		$dataParaVista['idRequerimientoInterno'] = $post['idRequerimientoInterno'];
		$dataParaVista['itemServicio'] = $data['itemServicio'];
		$dataParaVista['proveedorSelect'] = $this->db->select('idProveedor AS id, razonSocial AS value')->get_where('compras.proveedor', 'idProveedorEstado = 1 OR idProveedorEstado = 2')->result_array();
		$dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedorEstado' => 2])->result_array();
		$dataParaVista['usuarioAprobar'] = $this->model->obtenerUsuarioAprobar()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta(['idUsuario' => $this->idUsuario])['query']->result_array();
		$dataParaVista['tipoMoneda'] = $this->m_cotizacion->obtenertipoMoneda()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->m_cotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['prioridad'] = $this->m_cotizacion->obtenerPrioridadCotizacion()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->m_cotizacion->obtenerTipoServicioCotizacion()['query']->result_array();

		foreach ($dataParaVista['requerimientoTarifario'] as $k => $v) {
			$listProveedores = $this->db->get_where('compras.solicitudCostoProveedor', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			$list = [];
			foreach ($listProveedores as $vp) {
				$list[] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
			}
			$dataParaVista['listProveedores'][$v['idItem']] = implode(', ', $list);
			$dataParaVista['listProveedoresCosto'][$v['idItem']] = $listProveedorCosto = $this->db->get_where('compras.itemTarifario', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			foreach ($listProveedorCosto as $kp => $vp) {
				$dataParaVista['listProveedoresCosto'][$v['idItem']][$kp]['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
				$config['data']['itemTarifario'][$v['idItem']][$vp['idProveedor']] = $vp['costo'];
			}
		}
		$config['data']['title'] = 'Registrar OC';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/SolicitudRequerimientoInterno/formularioGenerarOC", $dataParaVista, true);


		echo json_encode($config);
	}
	public function formularioSeleccionProveedor()
	{
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['requerimientoInterno'] = $post;
		$dataParaVista['proveedor'] = $this->db->distinct()
			->select('ri.idRequerimientoInterno, p.idProveedor AS id, p.razonSocial AS value,
		ri.idCuenta, ri.idCentroCosto')
			->join('compras.requerimientoInternoDetalle rid', 'rid.idRequerimientoInterno = ri.idRequerimientoInterno')
			->join('compras.proveedor p', 'p.idProveedor = rid.idProveedor')
			->where('ri.idRequerimientoInterno', $post['idRequerimientoInterno'])
			->where('rid.flagProveedor', 0)
			->get('compras.requerimientoInterno ri')->result_array();
		$result['result'] = 1;
		$config['data']['title'] = 'Generar OC - Requerimiento Interno';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/SolicitudRequerimientoInterno/formularioProveedor", $dataParaVista, true);


		echo json_encode($config);
	}
	public function regitrarOC()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['item'] = checkAndConvertToArray($post['item']);
		$post['idRequerimientoInternoDetalle'] = checkAndConvertToArray($post['idRequerimientoInternoDetalle']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		//post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		//$post['adjuntoItemCantidad'] = checkAndConvertToArray($post['adjuntoItemCantidad']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);
		$post['precio_real'] = checkAndConvertToArray($post['precio_real']);

		$mostrar_observacion = 0;
		if (isset($post['mostrar_observacion']) == 'on') {
			$mostrar_observacion = 1;
		}

		$insertData = [
			'requerimiento' => $post['requerimiento'],
			'fechaEntrega' => $post['fechaEntrega'],
			'poCliente' => $post['poCliente'],
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idMoneda' => $post['moneda'],
			'idProveedor' => $post['proveedor'],
			'entrega' => $post['entrega'],
			'comentario' => $post['comentario'],
			'concepto' => $post['concepto'],
			'idMetodoPago' => $post['metodoPago'],
			'total' => $post['total_real'],
			'IGVPorcentaje' => intval($post['igvPorcentaje']) - 100,
			'totalIGV' => $post['totalIGV_real'],
			'idUsuarioReg' => $this->idUsuario,
			'observacion' => $post['observacion'],
			'idOper' => $post['idOper'],
			'seriado' => 'OC' . $this->model->obtenerSeriado(OC_SERIADO),
			'mostrar_observacion' => $mostrar_observacion,
			'idAlmacen' => $post['idAlmacen'],
			'descripcionCompras' => $post['descripcionCompras'],
			'flagRequerimientoInterno' => 1,
		];
		$ocSeriado = $insertData['seriado'];

		$this->db->insert('orden.ordenCompra', $insertData);
		$idOC = $this->db->insert_id();

		$insertData = [];
		$insertDataArchivos = [];
		$orden = 0;
		$ordenAdjunto = 0;
		$insertArchivos = [];
		foreach ($post['item'] as $key => $value) {
			// En caso: el item es nuevo
			$dataInserItem = [];
			if ($post['idItemForm'][$key] == '0') {
				$dataInserItem = [
					'nombre' => $post['item'][$key],
					'idItemTipo' => $post['tipo'][$key]
				];
				//aqui va la validacion
				$validacionItem = $this->model->getValidarItem($post['item'][$key])->result_array();
				if (empty($validacionItem)) {
					$this->db->insert('compras.item', $dataInserItem);
					$post['idItemForm'][$key] = $this->db->insert_id();
				} else {
					$post['idItemForm'][$key] = $validacionItem[0]['idItem'];
				}
			}

			// Fin: En Caso.
			$insertData = [
				'idOrdenCompra' => $idOC,
				'idItem' => $post['idItemForm'][$key],
				'idTipo' => $post['tipo'][$key],
				'costoUnitario' => $post['costo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costoSubTotal' => $post['costo'][$key] * $post['cantidad'][$key],
				'gap' => $post['gap'][$key],
				'costoSubTotalGap' => $post['precio_real'][$key]
			];
			$insert = $this->db->insert('orden.ordenCompraDetalle', $insertData);
			$idOCDet = $this->db->insert_id();
			for ($i = 0; $i < intval($post['adjuntoItemCantidad'][$key]); $i++) {
				$ii = [];
				$post['adjuntoItemFile-idOrigen'] = checkAndConvertToArray($post['adjuntoItemFile-idOrigen']);
				$post['adjuntoItemFile-name'] = checkAndConvertToArray($post['adjuntoItemFile-name']);
				$post['adjuntoItemFile-item'] = checkAndConvertToArray($post['adjuntoItemFile-item']);
				$post['adjuntoItemFile-type'] = checkAndConvertToArray($post['adjuntoItemFile-type']);
				if (!empty($post['adjuntoItemFile-idOrigen'][$ordenAdjunto])) { // Si la imagen viene del item
					$ii = $this->db->get_where($post['adjuntoItemFile-name'][$ordenAdjunto], [$post['adjuntoItemFile-type'][$ordenAdjunto] => $post['adjuntoItemFile-idOrigen'][$ordenAdjunto]])->row_array();
					$ii['nombre_archivo'] = "../requerimientoInterno/".$post['adjuntoItemFile-item'][$ordenAdjunto] . $ii['nombre_archivo'];
				} else { // Si la imagen es cargada en la OC
					$archivo = [
						'base64' => $post['adjuntoItemFile-item'][$ordenAdjunto],
						'name' => $post['adjuntoItemFile-name'][$ordenAdjunto],
						'type' => $post['adjuntoItemFile-type'][$ordenAdjunto],
						'carpeta' => 'ordenCompra',
						'nombreUnico' => uniqid()
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);

					$ii = [
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]]
					];
				}
				$insertDataArchivos[] = [
					'idOrdenCompra' => $idOC,
					'idOrdenCompraDetalle' => $idOCDet,
					'idTipoArchivo' => $ii['idTipoArchivo'],
					'nombre_inicial' => $ii['nombre_inicial'],
					'nombre_archivo' => $ii['nombre_archivo'],
					'nombre_unico' => $ii['nombre_unico'],
					'extension' => $ii['extension'],
					'idUsuario' => $this->idUsuario
				];
				$ordenAdjunto++;
			}
		}
		if (!empty($insertDataArchivos)) $this->db->insert_batch('orden.ordenCompraAdjunto', $insertDataArchivos);

		foreach ($post['idRequerimientoInternoDetalle'] as $idRequerimientoInternoDetalle) {
			$updateFlagProveedor[] = [
				'idRequerimientoInternoDetalle' => $idRequerimientoInternoDetalle,
				'flagProveedor' => 1,
			];
		}
		$updateRequerimientoInterno = $this->model->actualizarMasivo('compras.requerimientoInternoDetalle', $updateFlagProveedor, 'idRequerimientoInternoDetalle');

		$cds = $this->db->select('COUNT(*) AS cantidad_registros, SUM(CASE WHEN flagProveedor = 1 THEN 1 ELSE 0 END) AS flag1')
			->get_where(
				'compras.requerimientoInternoDetalle',
				['idRequerimientoInterno' => $post['idRequerimientoInterno']]
			)->result_array();

		if ($cds[0]['cantidad_registros'] == $cds[0]['flag1']) {
			$dataUpdateFlagOc['tabla'] = 'compras.requerimientoInterno';
			$dataUpdateFlagOc['update'] = [
				'idRequerimientoInternoEstado' => 6,
			];
			$dataUpdateFlagOc['where'] = [
				'idRequerimientoInterno' => $post['idRequerimientoInterno'],
			];
			$updateRequerimientoInterno = $this->model->actualizarRequerimientoInterno($dataUpdateFlagOc);
		}

		// Envio de Correo
		$this->enviarCorreoOC($idOC);
		// Fin: Envio de Correo

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		respuesta:
		echo json_encode($result);
	}
	public function actualizarAprobacionCompras()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$dataDetalle = [];

		foreach ($post['nameItem'] as $k => $r) {
			if (!empty($r)) {
				$dataDetalle['update'][] = [
					'idRequerimientoInternoDetalle' => $post['idRequerimientoInternoDetalle'][$k],
					'idProveedor' => empty($post['proveedorForm'][$k]) ? NULL : $post['proveedorForm'][$k],
					'costo' => !empty($post['costoProveedorTarifarioForm'][$k]) ? $post['costoProveedorTarifarioForm'][$k] : NULL,
				];
			}
		}

		//ACTUALIZAR DETALLE REQUERIMIENTO INTERNO
		if (!empty($dataDetalle)) {
			$updateDetalle = $this->model->actualizarMasivo('compras.requerimientoInternoDetalle', $dataDetalle['update'], 'idRequerimientoInternoDetalle');
			//ESTADO ACTUALIZADO
			$estadoAprobado = 4;
			$datos = [
				'idRequerimientoInternoEstado' => $estadoAprobado
			];
			$where = "idRequerimientoInterno = " . $post['idRequerimientoInterno'];
			$estadoActualizado = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		}

		if (!$updateDetalle['estado'] && $estadoActualizado) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'to' => $this->idUsuario == '1' ? ['bill.salazar@visualimpact.com.pe'] : $toCompras]);

			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		respuesta:
		echo json_encode($result);
	}
	public function anularRequerimientoInterno()
	{
		$result = $this->result;
		$json = json_decode($this->input->post('data'));
		$estadoAnulado = 5;
		$datos = [
			'estado' => 0,
			'idRequerimientoInternoEstado' => $estadoAnulado
		];
		$where = "idRequerimientoInterno = " . $json;
		$res = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		if ($res) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $json, 'to' => $this->idUsuario == '1' ? ['bill.salazar@visualimpact.com.pe'] : $toCompras]);

			$result['result'] = 1;
			$result['msg']['content'] = getMensajeGestion('anulacionExitosaRI');
		} else {
			$result['result'] = 0;
			$result['msg']['content'] = getMensajeGestion('anulacionErroneaRI');
		}

		echo json_encode($result);
	}
	public function enviarCorreo($params = [])
	{
		$email = [];

		$data = [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$email['to'] = $params['to'];
		$email['cc'] = $cc;

		$data = $this->model->obtenerInformacionRequerimientoInternoDetalle($params)['query']->result_array();

		foreach ($data as $key => $row) {
			$dataParaVista['cabecera']['idRequerimientoInterno'] = $row['idRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoInterno'] = $row['requerimientoInterno'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['cabecera']['requerimientoInternoDetalleEstado'] = $row['requerimientoInternoDetalleEstado'] ==  'Por Generar OC' ? 'aceptado en compras' : $row['requerimientoInternoDetalleEstado'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
		}

		$dataParaVista['link'] = base_url() . index_page() . 'requerimientoInterno';

		$email['asunto'] = 'IMPACTBUSSINESS - REQUERIMIENTO INTERNO ' . strtoupper($dataParaVista['cabecera']['requerimientoInternoDetalleEstado']);

		$html = $this->load->view("formularioRequerimientosInternos/correo/administracion/estadoRequerimiento", $dataParaVista, true);
		$correo = $this->load->view("formularioRequerimientosInternos/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'SolicitanteInterno'], true);

		$email['contenido'] = $correo;
		$estadoEmail = email($email);

		return $estadoEmail;
	}
	public function getImagenes()
	{
		$post = $this->input->post();
		$imagenes = $this->db->where(['idItem' => $post['idItem'], 'estado' => 1])->get('compras.itemImagen')->result_array();
		echo json_encode($imagenes);
	}
	public function obtenerProveedor()
	{
		$grupo['data']['proveedor'] = $this->model->obtenerInformacionProveedores(['estadoProveedor' => 3])['query']->result_array();
		echo json_encode($grupo);
	}
	public function obtenerPrecioProveedorTarifario()
	{
		$data = json_decode($this->input->post('data'));
		var_dump($data);
		exit;
		$grupo['data'] = $this->model->obtenerItemServicio(['idProveedor' => $data['idProveedor']])['query']->result_array();
		echo json_encode($grupo);
	}
	public function enviarSolicitudCostoProveedor()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = $this->input->post();

		$listItems = checkAndConvertToArray($post['idItemForm']);
		$listProveedores = checkAndConvertToArray($post['proveedorSolicitudForm']);

		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['costoReferencialForm'] = checkAndConvertToArray($post['costoReferencialForm']);

		// $post['cantidadForm'] = checkAndConvertToArray($post['checkItem']);

		foreach ($listItems as $ki => $item) {
			if (isset($post['checkItem'][$item])) {
				foreach ($listProveedores as $proveedor) {
					$this->db->update('compras.solicitudCostoProveedor', ['estado' => 0], [
						'idProveedor' => $proveedor,
						'idItem' => $item,
					]);
					$this->db->insert(
						'compras.solicitudCostoProveedor',
						[
							'idProveedor' => $proveedor,
							'idItem' => $item,
							'cantidad' => $post['cantidadForm'][$ki],
							'costoReferencial' => $post['costoReferencialForm'][$ki],
							'fechaCreacion' => getActualDateTime(),
						]
					);
				}
			}
		}

		$result['result'] = 1;
		$result['data']['html'] = createMessage(['type' => 1, 'message' => 'Solicitud enviada al proveedor']);
		$result['msg']['title'] = 'Solicitud Enviada';

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}
	function enviarCorreoOC($idOc)
	{
		$detalleParaCorreo = $this->m_OrdenCompra->obtenerOrdenCompraLista(['idOrdenCompra' => $idOc])->result_array();
		foreach ($detalleParaCorreo as $k => $v) {
			$dataParaVista['ocDet'][$k]['nombre'] = $v['item'];
			$dataParaVista['ocDet'][$k]['cantidad'] = $v['cantidad_item'];
		}

		$htmlCorreo = $this->load->view("modulos/Cotizacion/correoGeneracionOC", $dataParaVista, true);
		$oc = $this->db->get_where('orden.ordenCompra', ['idOrdenCompra' => $idOc])->row_array();
		$idProveedor = $oc['idProveedor'];
		$ocSeriado = $oc['seriado'];
		$correoProveedor = [];
		$correoDeProveedor[] = $this->db->get_where('compras.proveedor', ['idProveedor' => $idProveedor])->row_array()['correoContacto'];
		foreach ($this->db->get_where('compras.proveedorCorreo', ['idProveedor' => $idProveedor, 'estado' => 1])->result_array() as $k => $v) {
			$correoDeProveedor[] = $v['correo'];
		}

		$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);
		$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();

		$toCorreo = [];
		foreach ($usuariosCompras as $usuario) {
			$toCorreo[] = $usuario['email'];
		}

		$html = $htmlCorreo;
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html], true);

		$config = [
			'to' => $this->idUsuario == '1' ? ['bill.salazar@visualimpact.com.pe'] : $correoProveedor,
			'cc' => $toCorreo,
			'asunto' => $ocSeriado,
			'contenido' => $correo,
		];
		email($config);
	}
}
