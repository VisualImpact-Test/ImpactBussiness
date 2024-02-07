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
		$cant = [];

		foreach ($data as $key => $row) {
			$encontrado = !empty($this->db->distinct()->select('idOrdenCompra')->get_where('sustento.comprobante', ['idOrdenCompra' => $row['idOrdenCompra'], 'estado' => 1])->result_array());
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
				'moneda' => $row['monedaPlural'],
				'dataIdOC' => $encontrado ? 1 : 0,
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
			$adjuntos = $this->db->get_where('orden.ordenCompraAdjunto', ['idOrdenCompraDetalle' => $value['idOrdenCompraDetalle'], 'estado' => 1])->result_array();
			if (!isset($dataParaVista['ocAdjunto'][$value['idOrdenCompraDetalle']])) $dataParaVista['ocAdjunto'][$value['idOrdenCompraDetalle']] = [];
			foreach ($adjuntos as $adj) {
				$dataParaVista['ocAdjunto'][$value['idOrdenCompraDetalle']][] = [
					'id' => $adj['idOrdenCompraAdjunto'],
					'nombre' => $adj['nombre_archivo'],
					'idTipoArchivo' => $adj['idTipoArchivo'],
					'origenBD' => 'orden.ordenCompraAdjunto',
					'columnaBD' => 'idOrdenCompraAdjunto',
					'carpeta' => 'ordenCompra/'
				];
			}
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
		$post = $this->input->post();
		$imagenes = $this->db->where(['idItem' => $post['idItem'], 'estado' => 1, 'idTipoArchivo' => TIPO_IMAGEN])->get('compras.itemImagen')->row_array();
		echo json_encode($imagenes);
	}
	public function formularioRegistroOCLibre()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cuenta'] = $this->model_cotizacion->obtenerCuenta()['query']->result_array(); //
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$result['data']['item'] = $this->model_item->obtenerItemServicio();
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
		$insertDataArchivos = [];
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
				//aqui va la validacion
				$validacionItem = $this->model->getValidarItem($post['item'][$key])->result_array();
				//echo $this->db->last_query();exit();
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
					$ii['nombre_archivo'] = $post['adjuntoItemFile-item'][$ordenAdjunto] . $ii['nombre_archivo'];
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
		if (!empty($insertDataSub)) $insert = $this->model->insertarMasivo('orden.ordenCompraDetalleSub', $insertDataSub);
		if (!empty($insertDataArchivos)) $this->db->insert_batch('orden.ordenCompraAdjunto', $insertDataArchivos);
		// Envio de Correo
		$this->enviarCorreo($idOC);
		// Fin: Envio de Correo

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		respuesta:
		echo json_encode($result);
	}

	function enviarCorreo($idOc)
	{
		$detalleParaCorreo = $this->model->obtenerOrdenCompraLista(['idOrdenCompra' => $idOc])->result_array();
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
			'to' => $this->idUsuario == '1' ? ['eder.alata@visualimpact.com.pe'] : $correoProveedor,
			'cc' => $toCorreo,
			'asunto' => $ocSeriado,
			'contenido' => $correo,
		];
		email($config);
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

		if (isset($post['adjuntoItemCantidad'])) $post['adjuntoItemCantidad'] = checkAndConvertToArray($post['adjuntoItemCantidad']);
		if (isset($post['adjuntoItemFile-idOrigen'])) $post['adjuntoItemFile-idOrigen'] = checkAndConvertToArray($post['adjuntoItemFile-idOrigen']);
		if (isset($post['adjuntoItemFile-type'])) $post['adjuntoItemFile-type'] = checkAndConvertToArray($post['adjuntoItemFile-type']);
		if (isset($post['adjuntoItemFile-name'])) $post['adjuntoItemFile-name'] = checkAndConvertToArray($post['adjuntoItemFile-name']);
		if (isset($post['adjuntoItemFile-item'])) $post['adjuntoItemFile-item'] = checkAndConvertToArray($post['adjuntoItemFile-item']);

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
		$ordenAdjunto = 0;
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
			for ($i = 0; $i < intval($post['adjuntoItemCantidad'][$key]); $i++) {
				$ii = [];
				if (!empty($post['adjuntoItemFile-idOrigen'][$ordenAdjunto])) { // Si la imagen viene del item
					$where = [];
					$where[$post['adjuntoItemFile-type'][$ordenAdjunto]] = $post['adjuntoItemFile-idOrigen'][$ordenAdjunto];
					$ii = $this->db->get_where($post['adjuntoItemFile-name'][$ordenAdjunto], $where)->row_array();
					if (substr($ii['nombre_archivo'], 0, 2) != '..') $ii['nombre_archivo'] = $post['adjuntoItemFile-item'][$ordenAdjunto] . $ii['nombre_archivo'];
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

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.ordenCompraDetalleSub', $insertDataSub);
		}
		if (!empty($insertDataArchivos)) $this->db->insert_batch('orden.ordenCompraAdjunto', $insertDataArchivos);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->enviarCorreo($idOC);
		respuesta:
		echo json_encode($result);
	}

	public function visualizarPdfOCDescargar($oc = null)
	{
		$post['idOC'] = $oc;
		$this->descargarOCLibre($post, false);
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
			$dataParaVista['subDetalleItem'][$v['idOrdenCompraDetalle']] = $this->db->get_where('orden.ordenCompraDetalleSub', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle']])->result_array();
			$dataParaVista['adjunto'][$v['idOrdenCompraDetalle']] = $this->db->get_where('orden.ordenCompraAdjunto', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle'], 'estado' => 1])->row_array();
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
