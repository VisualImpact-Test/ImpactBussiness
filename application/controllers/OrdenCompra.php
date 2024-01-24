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
		$this->load->model('M_control', 'model_control');
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
			$datos[$row['idOrdenCompra']] = [
				'idProveedor' => $row['idProveedor'],
				'requerimiento' => $row['requerimiento'],
				'concepto' => $row['concepto'],
				'proveedor' => $row['razonSocial'],
				'simboloMoneda' => $row['simboloMoneda'],
				'entrega' => $row['entrega'],
				'fechaEntrega' => date_change_format($row['fechaEntrega']),
				'poCliente' => $row['poCliente'],
				'total' => $row['total'],
				'IGVPorcentaje' => $row['IGVPorcentaje'],
				'totalIGV' => $row['totalIGV'],
				'observacion' => $row['observacion'],
				'estado' => $row['estado'],
				'monedaCambio' => $row['monedaCambio'],
				'seriado' => $row['seriado'],
				'moneda' => $row['monedaPlural']
			];
		}

		$gr = $this->db->get_where('orden.ordenCompraGr', ['estado' => 1])->result_array();
		foreach ($gr as $k => $v) {
			$datos[$v['idOrdenCompra']]['gr'][] = $v;
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($datos)) {
			$dataParaVista['datos'] = $datos;
			$html = $this->load->view("modulos/OrdenCompra/reporte", $dataParaVista, true);
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
		$result['msg']['title'] = 'Generar OC desde Oper libre';
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
		$dataParaVista['tipo'] = $this->mTipo->obtenerInformacionTiposArticulo()['query']->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['moneda'] = $this->mMoneda->obtenerMonedasActivas()->result_array();
		$dataParaVista['proveedor'] = $this->mProveedor->obtenerProveedoresActivos()->result_array();
		$dataParaVista['metodoPago'] = $this->mFormProveedor->obtenerMetodoPago1($idProveedor)['query']->result_array();
		$dataParaVista['almacenes'] = $this->db->where('estado', '1')->get('visualImpact.logistica.almacen')->result_array();

		$dataParaVista['oc'] = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $idOC])->result_array();
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCostoEdit($dataParaVista['oc'][0]['idCuenta'])['query']->result_array();

		foreach ($dataParaVista['oc'] as $key => $value) {
			$dataParaVista['ocSubItem'][$value['idOrdenCompraDetalle']] = $this->model->obtenerInformacionOrdenCompraSubItem(['idOrdenCompraDetalle' => $value['idOrdenCompraDetalle']])->result_array();
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Editar OC';
		$result['data']['html'] = $this->load->view("modulos/OrdenCompra/formularioEditar", $dataParaVista, true);

		foreach ($this->model->getItemTarifario()->result_array() as $v) {
			$itemTarifario[$v['idItem']][$v['idProveedor']] = $v;
		}
		$result['data']['item'] = $this->model_item->obtenerItemServicio();
		$result['data']['itemTarifario'] = $itemTarifario;

		echo json_encode($result);
	}
	public function getImagenesItem()
	{
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

	public function ImagenItem()
	{
		$data = json_decode($this->input->post('data'));
		$grupo['data']['imagen'] = $this->model->obtenerArchivo($data->id)['query']->result_array();
		echo json_encode($grupo);
	}

	public function CentroCosto()
	{
		$data = json_decode($this->input->post('data'));
		$grupo['data']['centro'] = $this->model_cotizacion->obtenerCuentaCentroCostoEdit($data->id)['query']->result_array();
		echo json_encode($grupo);
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
		$post['adjuntoItemCantidad'] = checkAndConvertToArray($post['adjuntoItemCantidad']);
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
			$post['subItem_genero'] = checkAndConvertToArray($post['subItem_genero']);
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
		$ocSeriado = $insertData['seriado'];

		$this->db->insert('orden.ordenCompra', $insertData);
		$idOC = $this->db->insert_id();
		$insertData = [];
		$insertDataSub = [];
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
				$this->db->insert('compras.item', $dataInserItem);
				$post['idItemForm'][$key] = $this->db->insert_id();
				$idItem[] = [
					'id' => $this->db->insert_id()
				];
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
				$insertDataSub[] = [
					'idOrdenCompraDetalle' => $idOCDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$ordenAdjunto] == '' ? NULL : $post['subItem_tipoServ'][$ordenAdjunto],
					'idItemLogistica' => $post['subItem_itemLog'][$ordenAdjunto] == '' ? NULL : $post['subItem_itemLog'][$ordenAdjunto],
					'idUnidadMedida' => $post['subItem_idUm'][$ordenAdjunto] == '' ? NULL : $post['subItem_idUm'][$ordenAdjunto],
					'nombre' => $post['subItem_nombre'][$ordenAdjunto] == '' ? NULL : $post['subItem_nombre'][$ordenAdjunto],
					'talla' => $post['subItem_talla'][$ordenAdjunto] == '' ? NULL : $post['subItem_talla'][$ordenAdjunto],
					'idGenero' => $post['subItem_genero'][$ordenAdjunto] == '' ? NULL : $post['subItem_genero'][$ordenAdjunto],
					'tela' => $post['subItem_tela'][$ordenAdjunto] == '' ? NULL : $post['subItem_tela'][$ordenAdjunto],
					'color' => $post['subItem_color'][$ordenAdjunto] == '' ? NULL : $post['subItem_color'][$ordenAdjunto],
					'cantidad' => $post['subItem_cantidad'][$ordenAdjunto] == '' ? NULL : $post['subItem_cantidad'][$ordenAdjunto],
					'cantidadPDV' => $post['subItem_cantidadPdv'][$ordenAdjunto] == '' ? NULL : $post['subItem_cantidadPdv'][$ordenAdjunto],
					'costo' => $post['subItem_costo'][$ordenAdjunto] == '' ? NULL : $post['subItem_costo'][$ordenAdjunto],
					'monto' => $post['subItem_monto'][$ordenAdjunto] == '' ? NULL : $post['subItem_monto'][$ordenAdjunto]
				];
				$ordenAdjunto++;
			}

			for ($i = 0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
				$insertDataSub[] = [
					'idOrdenCompraDetalle' => $idOCDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
					'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
					'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
					'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
					'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
					'idGenero' => $post['subItem_genero'][$orden] == '' ? NULL : $post['subItem_genero'][$orden],
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
		if (isset($post['adjuntoItemFile-item'])) {
			if (count($post['adjuntoItemFile-item']) > 1) {
				foreach ($idItem as $key1 => $value) {
					$archivo = [
						'base64' => $post['adjuntoItemFile-item'][$key1],
						'name' => $post['adjuntoItemFile-name'][$key1],
						'type' => $post['adjuntoItemFile-type'][$key1],
						'carpeta' => 'item',
						'nombreUnico' => uniqid()
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);

					$insertArchivos[] = [
						'idItem' => $value['id'],
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
					];
				}
			} else {
				$archivo = [
					'base64' => $post['adjuntoItemFile-item'],
					'name' => $post['adjuntoItemFile-name'],
					'type' => $post['adjuntoItemFile-type'],
					'carpeta' => 'item',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos[] = [
					'idItem' => $idItem[0]['id'],
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'estado' => true,
				];
			}
		}
		if (!empty($insertArchivos)) $this->db->insert_batch('compras.itemImagen', $insertArchivos);

		// Envio de Correo
		$detalleParaCorreo = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $idOC])->result_array();
		foreach ($detalleParaCorreo as $k => $v) {
			$dataParaVista['ocDet'][$k]['nombre'] = $v['item'];
			$dataParaVista['ocDet'][$k]['cantidad'] = $v['cantidad_item'];
		}

		$htmlCorreo = $this->load->view("modulos/Cotizacion/correoGeneracionOC", $dataParaVista, true);
		$correoProveedor = [];
		$correoDeProveedor[] = $this->db->get_where('compras.proveedor', ['idProveedor' => $post['proveedor']])->row_array()['correoContacto'];
		foreach ($this->db->get_where('compras.proveedorCorreo', ['idProveedor' => $post['proveedor'], 'estado' => 1])->result_array() as $k => $v) {
			$correoDeProveedor[] = $v['correo'];
		}

		$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);
		$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();

		$toCorreo = [];
		foreach ($usuariosCompras as $usuario) {
			$toCorreo[] = $usuario['email'];
		}

		$config = [
			'to' => $correoProveedor,
			'cc' => $this->idUsuario == '1' ? [] : $toCorreo,
			'asunto' => 'OC ' . $ocSeriado,
			'contenido' => $htmlCorreo,
		];
		email($config);
		// Fin: Envio de Correo

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		respuesta:
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
			$post['subItem_genero'] = checkAndConvertToArray($post['subItem_genero']);
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
				$idItem[] = [
					'id' => $this->db->insert_id()
				];
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
					'idGenero' => $post['subItem_genero'][$orden] == '' ? NULL : $post['subItem_genero'][$orden],
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

		if (isset($post['adjuntoItemFile-item'])) {
			if (count($post['adjuntoItemFile-item']) > 1) {
				foreach ($idItem as $key1 => $value) {
					$archivo = [
						'base64' => $post['adjuntoItemFile-item'][$key1],
						'name' => $post['adjuntoItemFile-name'][$key1],
						'type' => $post['adjuntoItemFile-type'][$key1],
						'carpeta' => 'item',
						'nombreUnico' => uniqid()
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);

					$insertArchivos[] = [
						'idItem' => $value['id'],
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
					];
				}
			} else {
				$archivo = [
					'base64' => $post['adjuntoItemFile-item'],
					'name' => $post['adjuntoItemFile-name'],
					'type' => $post['adjuntoItemFile-type'],
					'carpeta' => 'item',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos[] = [
					'idItem' => $idItem[0]['id'],
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'estado' => true,
				];
			}
		}

		if (!empty($insertArchivos)) $this->db->insert_batch('compras.itemImagen', $insertArchivos);

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.ordenCompraDetalleSub', $insertDataSub);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		respuesta:
		echo json_encode($result);
	}

	public function visualizarPdfOCLibre($oc = null)
	{
		$post['idOC'] = $oc;
		$this->descargarOCLibre($post, true);
	}

	public function descargarOCLibre($data = [], $visible = false)
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
		if (!empty($data)) {
			$post = $data;
		} else {
			$post = json_decode($this->input->post('data'), true);
		}
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
		if ($visible) {
			$mpdf->Output("{$cod_oc}.pdf", 'I');
		} else {
			$mpdf->Output("{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
		}
	}
	public function formularioRegistroGrOrdenCompraLibre()
	{
		$post = $this->input->post();
		$dataParaVista['idOrdenCompra'] = $post['idOrdenCompra'];

		$dataParaVista['dataCargada'] = $this->db->get_where('orden.ordenCompraGr', ['estado' => 1, 'idOrdenCompra' => $post['idOrdenCompra']])->result_array();
		$result = $this->result;
		$result['result'] = 1;
		$result['data']['dataCargada'] = !empty($dataParaVista['dataCargada']);
		$result['msg']['title'] = 'Hecho!';
		$result['data']['html'] = $this->load->view('modulos/OrdenCompra/formularioRegistroGrOCLibre', $dataParaVista, true);
		echo json_encode($result);
	}
	public function registrarGrOcLibre()
	{
		$result = $this->result;
		$post = $this->input->post('data');

		$post['numeroGr'] = checkAndConvertToArray($post['numeroGr']);
		$post['fechaGr'] = checkAndConvertToArray($post['fechaGr']);

		$insertData = [];
		foreach ($post['numeroGr'] as $k => $v) {
			$insertData[] = [
				'idOrdenCompra' => $post['idOrdenCompra'],
				'numeroGr' => $v,
				'fechaGr' => $post['fechaGr'][$k],
				'fechaReg' => getActualDateTime(),
				'idUsuarioReg' => $this->idUsuario
			];
		}

		if (!empty($insertData)) {
			$this->db->update('orden.ordenCompraGr', ['estado' => 0], ['idOrdenCompra' => $post['idOrdenCompra']]);
			$this->db->insert_batch('orden.ordenCompraGr', $insertData);
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'No se encontraron datos para insertar']);
			goto respuesta;
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		respuesta:
		echo json_encode($result);
	}
};
