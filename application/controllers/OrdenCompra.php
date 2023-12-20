<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrdenCompra extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_OrdenCompra', 'model');
		$this->load->model('M_Cotizacion', 'model_cotizacion');
		$this->load->model('M_Item', 'model_item');
		$this->load->model('M_Moneda', 'mMoneda');
		$this->load->model('M_Proveedor', 'mProveedor');
		$this->load->model('M_FormularioProveedor', 'mFormProveedor');
		$this->load->model('M_Oper', 'mOper');
		$this->load->model('Configuracion/M_Tipo', 'mTipo');
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
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/OrdenCompra',
			'assets/custom/js/dataTables.select.min'
		);
		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'OC';
		$config['data']['message'] = 'Lista de OCs';
		$config['view'] = 'modulos/OrdenCompra/index';
		$this->view($config);
	}

	public function reporteLibre()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$data = $this->model->obtenerOrdenCompraLista($post)->result_array();
		foreach ($data as $key => $row) {
			$dataParaVista[$row['idOrdenCompra']] = [
				'idProveedor' => $row['idProveedor'],
				'requerimiento' => $row['requerimiento'],
				'concepto' => $row['concepto'],
				'proveedor' => $row['razonSocial'],
				'simboloMoneda' => $row['simboloMoneda'],
				'entrega' => $row['entrega'],
				'fechaEntrega' => date_change_format($row['fechaEntrega']),
				'total' => $row['total'],
				'IGVPorcentaje' => $row['IGVPorcentaje'],
				'totalIGV' => $row['totalIGV'],
				'observacion' => $row['observacion'],
				'estado' => $row['estado'],
				'monedaCambio' => $row['monedaCambio'],
				'seriado' => $row['seriado']
			];
			$item[$row['idOrdenCompra']][$row['item']] = $row['item'];
		}

		foreach ($dataParaVista as $key => $row) {
			$dataParaVista[$key]['item'] = implode(', ', $item[$key]);
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/OrdenCompra/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentOCLibre']['datatable'] = 'tb-oc';
		$result['data']['views']['idContentOCLibre']['html'] = $html;
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

	public function formularioOperSinCotizarCarga()
	{
		$result = $this->result;
		$idOC = json_decode($this->input->post('data'), true);
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['cuenta'] = $this->model_cotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['item'] = $this->model_item->obtenerItemServicio();
		$dataParaVista['tipo'] = $this->mTipo->obtenerInformacionTiposArticulo()['query']->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['moneda'] = $this->mMoneda->obtenerMonedasActivas()->result_array();
		$dataParaVista['proveedor'] = $this->mProveedor->obtenerProveedoresActivos()->result_array();
		$dataParaVista['metodoPago'] = $this->mFormProveedor->obtenerMetodoPago()->result_array();

		//$dataParaVista['oc'] = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $idOC])->result_array();
		$dataParaVista['oc'] = $this->model->obtenerInformacionOperSinCot(['idOper' => $idOC])->result_array();
		//echo $this->db->last_query(); exit();
		foreach ($dataParaVista['oc'] as $key => $value) {
			$dataParaVista['ocSubItem'][$value['idOperDetalle']] = $this->model->obtenerInformacionOperSinCotSubItem(['idOperDetalle' => $value['idOperDetalle']])->result_array();
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Editar OC';
		//$result['data']['html'] = $this->load->view("modulos/OrdenCompra/formularioEditar", $dataParaVista, true);
		$result['data']['html'] = $this->load->view("modulos/OrdenCompra/Oper/formularioOperSinCotizar", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioEditarOCLibre()
	{
		$result = $this->result;
		$idOC = json_decode($this->input->post('id'), true);
		$idProveedor = json_decode($this->input->post('idproveedor'), true);

		$dataParaVista = [];
		$dataParaVista['cuenta'] = $this->model_cotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['item'] = $this->model_item->obtenerItemServicio();
		$dataParaVista['tipo'] = $this->mTipo->obtenerInformacionTiposArticulo()['query']->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['moneda'] = $this->mMoneda->obtenerMonedasActivas()->result_array();
		$dataParaVista['proveedor'] = $this->mProveedor->obtenerProveedoresActivos()->result_array();
		$dataParaVista['metodoPago'] = $this->mFormProveedor->obtenerMetodoPago1($idProveedor)['query']->result_array();
		$dataParaVista['almacenes'] = $this->db->where('estado', '1')->get('visualImpact.logistica.almacen')->result_array();

		$dataParaVista['oc'] = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $idOC])->result_array();
		foreach ($dataParaVista['oc'] as $key => $value) {
			$dataParaVista['ocSubItem'][$value['idOrdenCompraDetalle']] = $this->model->obtenerInformacionOrdenCompraSubItem(['idOrdenCompraDetalle' => $value['idOrdenCompraDetalle']])->result_array();
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Editar OC';
		$result['data']['html'] = $this->load->view("modulos/OrdenCompra/formularioEditar", $dataParaVista, true);

		foreach ($this->model->getItemTarifario()->result_array() as $v) {
			$itemTarifario[$v['idItem']][$v['idProveedor']] = $v;
		}
		$result['data']['itemTarifario'] = $itemTarifario;

		echo json_encode($result);
	}
	public function formularioRegistroOCLibre()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cuenta'] = $this->model_cotizacion->obtenerCuenta()['query']->result_array(); //
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['item'] = $this->model_item->obtenerItemServicio();
		$dataParaVista['tipo'] = $this->mTipo->obtenerInformacionTiposArticulo()['query']->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['moneda'] = $this->mMoneda->obtenerMonedasActivas()->result_array();
		$dataParaVista['proveedor'] = $this->mProveedor->obtenerProveedoresActivos()->result_array();
		$dataParaVista['almacenes'] = $this->db->where('estado', '1')->get('visualImpact.logistica.almacen')->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar OC';
		$result['data']['html'] = $this->load->view("modulos/OrdenCompra/formularioRegistro", $dataParaVista, true);

		foreach ($this->model->getItemTarifario()->result_array() as $v) {
			$itemTarifario[$v['idItem']][$v['idProveedor']] = $v;
		}
		$result['data']['itemTarifario'] = $itemTarifario;

		echo json_encode($result);
	}

	public function metodoPago()
	{
		$data = json_decode($this->input->post('data'));
		$grupo['data']['metodo'] = $this->mFormProveedor->obtenerMetodoPago(['idProveedor' => $data->id])->result_array();
		echo json_encode($grupo);
	}

	public function modalOperSinCotizar()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$data = $this->mOper->obtenerInformacionOper($post)->result_array();
		foreach ($data as $key => $row) {
			$dataParaVista[$row['idOper']] = [
				'idOper' => $row['idOper'],
				'concepto' => $row['concepto'],
				'requerimiento' => $row['requerimiento'],
				'fechaRequerimiento' => date_change_format($row['fechaRequerimiento']),
				'fechaEntrega' => date_change_format($row['fechaEntrega']),
				'total' => $row['total'],
				'feePorcentaje' => $row['feePorcentaje'],
				'totalFee' => $row['totalFee'],
				'IGVPorcentaje' => $row['IGVPorcentaje'],
				'totalFeeIGV' => $row['totalFeeIGV'],
				'observacion' => $row['observacion'],
				'estado' => $row['estado']
			];
			$item[$row['idOper']][$row['idItem']] = $row['idItem'];
		}

		foreach ($dataParaVista as $key => $row) {
			$dataParaVista[$key]['item'] = implode(', ', $item[$key]);
			// var_dump($dataParaVista[$key]['item']);
		}


		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/OrdenCompra/Oper/listaOperSinCotizar", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Seleccionar Oper';
		$result['data']['html'] = $html;


		echo json_encode($result);
	}
	public function registrarOCLibre()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['item'] = checkAndConvertToArray($post['item']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);
		$post['precio_real'] = checkAndConvertToArray($post['precio_real']);

		if (isset($post['subItem_monto'])) {
			$post['subItem_monto'] = checkAndConvertToArray($post['subItem_monto']);
			$post['subItem_tipoServ'] = checkAndConvertToArray($post['subItem_tipoServ']);
			$post['subItem_idUm'] = checkAndConvertToArray($post['subItem_idUm']);
			$post['subItem_itemLog'] = checkAndConvertToArray($post['subItem_itemLog']);
			$post['subItem_nombre'] = checkAndConvertToArray($post['subItem_nombre']);
			$post['subItem_talla'] = checkAndConvertToArray($post['subItem_talla']);
			$post['subItem_tela'] = checkAndConvertToArray($post['subItem_tela']);
			$post['subItem_color'] = checkAndConvertToArray($post['subItem_color']);
			$post['subItem_costo'] = checkAndConvertToArray($post['subItem_costo']);
			$post['subItem_cantidad'] = checkAndConvertToArray($post['subItem_cantidad']);
			$post['subItem_cantidadPdv'] = checkAndConvertToArray($post['subItem_cantidadPdv']);
		}
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
			//'totalIGV_real' => $post['totalIGV_real'],
		];
		$this->db->insert('orden.ordenCompra', $insertData);
		$idOC = $this->db->insert_id();
		$insertData = [];
		$insertDataSub = [];
		$orden = 0;
		foreach ($post['item'] as $key => $value) {
			// En caso: el item es nuevo
			$dataInserItem = [];
			if ($post['idItemForm'][$key] == '0') {
				$dataInserItem = [
					'nombre' => $post['item'][$key],
					'idItemTipo' => $post['tipo'][$key]
				];
				$this->db->insert('compras.item', $dataInserItem);
				$post['idItemForm'][$key] = $this->db->insert_id();
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

			for ($i = 0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
				$insertDataSub[] = [
					'idOrdenCompraDetalle' => $idOCDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
					'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
					'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
					'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
					'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
					'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
					'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
					'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
					'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
					'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
					'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
				];
				$orden++;
			}
		}

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.ordenCompraDetalleSub', $insertDataSub);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		echo json_encode($result);
	}



	public function editarOCLibre()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['item'] = checkAndConvertToArray($post['item']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);
		$post['precio_real'] = checkAndConvertToArray($post['precio_real']);

		if (isset($post['subItem_monto'])) {
			$post['subItem_monto'] = checkAndConvertToArray($post['subItem_monto']);
			$post['subItem_tipoServ'] = checkAndConvertToArray($post['subItem_tipoServ']);
			$post['subItem_idUm'] = checkAndConvertToArray($post['subItem_idUm']);
			$post['subItem_itemLog'] = checkAndConvertToArray($post['subItem_itemLog']);
			$post['subItem_nombre'] = checkAndConvertToArray($post['subItem_nombre']);
			$post['subItem_talla'] = checkAndConvertToArray($post['subItem_talla']);
			$post['subItem_tela'] = checkAndConvertToArray($post['subItem_tela']);
			$post['subItem_color'] = checkAndConvertToArray($post['subItem_color']);
			$post['subItem_costo'] = checkAndConvertToArray($post['subItem_costo']);
			$post['subItem_cantidad'] = checkAndConvertToArray($post['subItem_cantidad']);
			$post['subItem_cantidadPdv'] = checkAndConvertToArray($post['subItem_cantidadPdv']);
		}
		$mostrar_observacion = 0;
		if (isset($post['mostrar_observacion']) == 'on') {
			$mostrar_observacion = 1;
		}
		$updateData[0] = [
			'idOrdenCompra' => $post['idOc'],
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
			'mostrar_observacion' => $mostrar_observacion,
			'idAlmacen' => $post['idAlmacen'],
			'descripcionCompras' => $post['descripcionCompras']
		];
		$rpta = $this->model->actualizarMasivo('orden.ordenCompra', $updateData, 'idOrdenCompra');
		$idOC = $updateData[0]['idOrdenCompra'];
		$this->db->update('orden.ordenCompraDetalle', ['estado' => '0'], ['idOrdenCompra' => $idOC]);

		$insertData = [];
		$insertDataSub = [];
		$orden = 0;
		foreach ($post['item'] as $key => $value) {
			// En caso: el item es nuevo
			$dataInserItem = [];
			if ($post['idItemForm'][$key] == '0') {
				$dataInserItem = [
					'nombre' => $post['item'][$key],
					'idItemTipo' => $post['tipo'][$key]
				];
				$this->db->insert('compras.item', $dataInserItem);
				$post['idItemForm'][$key] = $this->db->insert_id();
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

			for ($i = 0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
				$insertDataSub[] = [
					'idOrdenCompraDetalle' => $idOCDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
					'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
					'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
					'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
					'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
					'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
					'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
					'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
					'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
					'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
					'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
				];
				$orden++;
			}
		}

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.ordenCompraDetalleSub', $insertDataSub);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		echo json_encode($result);
	}

	public function descargarOCLibre()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		$dataParaVista['detalle'] = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $post['idOC']])->result_array();
		$ids = [];
		$dataParaVista['data'] = $dataParaVista['detalle'][0];

		foreach ($dataParaVista['detalle'] as $k => $v) {
			$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->get_where('orden.ordenCompraDetalleSub', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle']])->result_array();
		}

		require APPPATH . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
			// 'orientation' => '',
			'autoMarginPadding' => 0,
			'bleedMargin' => 0,
			'crossMarkMargin' => 0,
			'cropMarkMargin' => 0,
			'nonPrintMargin' => 0,
			'margBuffer' => 0,
			'collapseBlockMargins' => false,
		]);

		$mpdf->curlAllowUnsafeSslRequests = true;
		$mpdf->showImageErrors = true;
		$mpdf->debug = true;

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'ORDEN DE COMPRA DE BIENES Y SERVICIOS', 'codigo' => 'SIG-LOG-FOR-001'], true);
		$contenido['footer'] = $this->load->view("modulos/OrdenCompra/pdf/footer", array(), true);

		// $contenido['style'] = $this->load->view("modulos/OrdenCompra/pdf/oper_style", [], true);
		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/OrdenCompra/pdf/orden_compra", $dataParaVista, true);
		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		$cod_oc = generarCorrelativo($dataParaVista['detalle'][0]['seriado'], 6) . "-"
			. $dataParaVista['detalle'][0]['concepto'];

		$mpdf->Output("{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}
};
