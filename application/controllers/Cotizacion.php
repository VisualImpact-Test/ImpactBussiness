<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotizacion extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Cotizacion', 'model');
		$this->load->model('M_Item', 'model_item');
		$this->load->model('M_control', 'model_control');
		$this->load->model('M_proveedor', 'model_proveedor');
		$this->load->model('M_FormularioProveedor', 'model_formulario_proveedor');
		$this->load->model('M_login', 'model_login');
		header('Access-Control-Allow-Origin: *');
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
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/cotizacion',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['view'] = 'modulos/Cotizacion/index';

		$this->view($config);
	}

	public function test()
	{
		$config = array();
		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/select.dataTables.min',
			'assets/libs/photoswipe/photoswipe',
		);
		$config['js']['script'] = array(
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/cotizacion',
			'assets/custom/js/dataTables.select.min',
			'assets/libs/photoswipe/photoswipe.min',
			'assets/libs/photoswipe/photoswipe-ui-default.min',
		);
		$config['single'] = true;

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['view'] = 'modulos/Cotizacion/test';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];
        if (isset($_SESSION['item'])){
            $item = $_SESSION['item'];

            $post['id'] = $item;
            $datoDeseado =  $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
            unset($post['id']);
            $post['idDiferente'] = $item;
            $datoRestante =  $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
            $dataParaVista = array_merge($datoDeseado,$datoRestante);

        }else{
            $dataParaVista = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
        }

		// $post['estadoCotizacion'] = '1,2,3,4';

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Cotizacion/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentCotizacion']['datatable'] = 'tb-cotizacion';
		$result['data']['views']['idContentCotizacion']['html'] = $html;
		$result['data']['configTable'] =  [
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

	public function filtroCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['estadoCotizacion'] = ESTADO_COTIZACION_APROBADA;
		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerInformacionCotizacionFiltro($post)['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Cotizacion/reporteFiltro", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'Filtro Cotizacion';
		$result['data']['width'] = '80%';

		echo json_encode($result);
	}

	public function formularioRegistroCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();


		$itemServicio =  $this->model_item->obtenerItemServicio();
		foreach ($itemServicio as $key => $row) {
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
		}
		foreach ($data['itemServicio'] as $k => $r) {
			$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
		}
		$data['itemServicio'][0] = array();
		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioRegistro", $dataParaVista, true);
		$result['data']['itemServicio'] = $data['itemServicio'];

		echo json_encode($result);
	}

	public function formularioVisualizacionCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$data = $this->model->obtenerInformacionCotizacionDetalle($post)['query']->result_array();
		foreach ($data as $key => $row) {
			$oper = ($this->db->where('idCotizacion', $row['idCotizacion'])->get('compras.operDetalle'))->row_array();
			$dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
			$dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
			$dataParaVista['cabecera']['idOper'] = $oper['idOper'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['cabecera']['codCotizacion'] = $row['codCotizacion'];
			$dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
			$dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costo'] = $row['costo'];
			$dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
			$dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
			$dataParaVista['detalle'][$key]['fecha'] = !empty($row['fechaModificacion']) ? $row['fechaModificacion'] : $row['fechaCreacion'];
			$dataParaVista['detalle'][$key]['cotizacionDetalleEstado'] = $row['cotizacionDetalleEstado'];
		}
		$dataParaVista['cabecera']['idOC'] = ($this->db->where('estado', '1')->where('idCotizacionDetalle', $data[0]['idCotizacionDetalle'])->get('compras.ordenCompraDetalle'))->row_array()['idOrdenCompra'];

		$dataParaVista['estados'] = $this->model_control->get_estados_cotizacion()->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Visualizar Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioVisualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarCotizacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['tabla'] = 'compras.cotizacion';

		if ($post['tipoRegistro'] == ESTADO_ENVIADO_CLIENTE || $post['tipoRegistro'] == ESTADO_COTIZACION_APROBADA) {
			$insertCotizacionHistorico = [
				'idCotizacionEstado' => $post['tipoRegistro'],
				'idCotizacion' => $post['idCotizacion'],
				'idUsuarioReg' => $this->idUsuario,
				'estado' => true,
			];
			$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);

			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_OPERACIONES);

			$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toOperaciones = [];
			foreach ($usuariosOperaciones as $usuario) {
				$toOperaciones[] = $usuario['email'];
			}

			$this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => $toOperaciones]);
		}

		if ($post['tipoRegistro'] == ESTADO_ENVIADO_CLIENTE) {
			$data['update'] = [
				'idCotizacionEstado' => ESTADO_ENVIADO_CLIENTE,
				'total' => $post['totalForm'],
			];
			$data['where'] = [
				'idCotizacion' => $post['idCotizacion'],
			];

			$this->model->actualizarCotizacion($data);

			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'Se envió el detalle de la cotización al cliente correctamente']);

			$this->db->trans_complete();
			goto respuesta;
		}

		if ($post['tipoRegistro'] == ESTADO_COTIZACION_APROBADA) {

			if (!empty($post['codigo_oc']) || !empty($post['motivo']) || (isset($post['file-item[0]']) && !empty($post['file-item[0]']))) {

				$data['update'] = [
					'idCotizacionEstado' => ESTADO_COTIZACION_APROBADA,
					'codOrdenCompra' => !empty($post['codigo_oc']) ? $post['codigo_oc'] : NULL,
					'montoOrdenCompra' => !empty($post['monto_oc']) ? $post['monto_oc'] : NULL,
					'motivoAprobacion' => !empty($post['motivo']) ? $post['motivo'] : NULL,
				];
				$data['where'] = [
					'idCotizacion' => $post['idCotizacion'],
				];

				$this->model->actualizarCotizacion($data);

				if (isset($post['file-item[0]']) && !empty($post['file-item[0]'])) {
					$archivo = [
						'base64' => $post['file-item[0]'],
						'name' => $post['file-name[0]'],
						'type' => $post['file-type[0]'],
						'carpeta' => 'cotizacion',
						'nombreUnico' => 'COTI' . $post['idCotizacion'] . str_replace(':', '', $this->hora) . 'OC',
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);
					$insertArchivos[] = [
						'idCotizacion' => $post['idCotizacion'],
						'idTipoArchivo' => TIPO_ORDEN_COMPRA,
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => $tipoArchivo[1],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
					if (!empty($insertArchivos)) {
						$this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
					}
				}

				$result['result'] = 1;
				$result['msg']['title'] = 'Hecho!';
				$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'Se procesó la cotizacion correctamente']);
				$this->db->trans_complete();
			} else {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Debe completar al menos un campo para continuar']);
			}

			goto respuesta;
		}

		// SOLICITANTE MEJORADO
		$idSolicitante = NULL;
		if (!is_numeric($post['solicitante'])) {
			$whereSolicitante = [];
			$whereSolicitante[] = [
				'estado' => 1
			];
			$tablaSolicitantes = 'compras.solicitante';

			$solicitantes = $this->model->getWhereJoinMultiple($tablaSolicitantes, $whereSolicitante)->result_array();
			$dataSolicitante = [];
			foreach ($solicitantes as $solicitante) {
				$dataSolicitante[$solicitante['nombre']] = $solicitante['idSolicitante'];
			}
			if (empty($dataSolicitante[$post['solicitante']])) {
				$insertSolicitante = [
					'nombre' => $post['solicitante'],
					'fechaRegistro' => getActualDateTime(),
					'estado' => true,
				];
				$insertSolicitante = $this->model->insertar(['tabla' => $tablaSolicitantes, 'insert' => $insertSolicitante]);
				$idSolicitante = $insertSolicitante['id'];
			} else {
				$idSolicitante = $dataSolicitante[$post['solicitante']];
			}
		} else {
			$idSolicitante = $post['solicitante'];
		}
		// FIN: SOLICITANTE MEJORADO

		$data['insert'] = [
			'nombre' => $post['nombre'],
			'fechaEmision' => getActualDateTime(),
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			//'idCentroCosto' => trim(explode('-',$post['cuentaCentroCostoForm'])[1]),
			'idSolicitante' => $idSolicitante,
			'fechaDeadline' => !empty($post['deadline']) ? $post['deadline'] : NULL,
			'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
			'fee' => $post['feeForm'],
			'total' => $post['totalForm'],
			'total_fee' => $post['totalFormFee'],
			'total_fee_igv' => $post['totalFormFeeIgv'],
			'idPrioridad' => $post['prioridadForm'],
			'motivo' => $post['motivoForm'],
			'comentario' => $post['comentarioForm'],
			'diasValidez' => $post['diasValidez'],
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idUsuarioReg' => $this->idUsuario,
			'mostrarPrecio' => !empty($post['flagMostrarPrecio']) ? $post['flagMostrarPrecio'] : 0,
		];

		$validacionExistencia = $this->model->validarExistenciaCotizacion($data['insert']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'El título de cotizacion ya se encuentra registrado']);
			goto respuesta;
		}

		$data['anexos_arreglo'] = [];
		$data['anexos'] = [];

		if (!empty($post['anexo-file'])) {

			$data['anexos_arreglo'] = getDataRefactorizada([
				'base64' => $post['anexo-file'],
				'type' => $post['anexo-type'],
				'name' => $post['anexo-name'],

			]);

			foreach ($data['anexos_arreglo'] as $anexo) {
				$data['anexos'][] = [
					'base64' => $anexo['base64'],
					'type' => $anexo['type'],
					'name' => $anexo['name'],
					'carpeta' => 'cotizacion',
					'nombreUnico' => "ANX" . uniqid(),
				];
			}
		}

		$insert = $this->model->insertarCotizacion($data);
		$data['idCotizacion'] = $insert['id'];
		$insertAnexos = $this->model->insertarCotizacionAnexos($data);
		$data['update'] = [
			'codCotizacion' => generarCorrelativo($insert['id'], 6),
		];
		$data['where'] = [
			'idCotizacion' => $insert['id'],
		];
		$updateCotizacion = $this->model->actualizarCotizacion($data);
		$post['idCotizacion'] = $insert['id'];
		$data = [];

		//Insertar historico estado cotizacion
		$insertCotizacionHistorico = [
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idCotizacion' => $post['idCotizacion'],
			'idUsuarioReg' => $this->idUsuario,
			'estado' => true,
		];
		$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);

		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
		$post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
		$post['caracteristicasCompras'] = checkAndConvertToArray($post['caracteristicasCompras']);
		$post['caracteristicasProveedor'] = checkAndConvertToArray($post['caracteristicasProveedor']);
		$post['costoForm'] = checkAndConvertToArray($post['costoForm']);
		$post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
		$post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
		$post['gapForm'] = checkAndConvertToArray($post['gapForm']);
		$post['precioForm'] = checkAndConvertToArray($post['precioForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		$post['cotizacionInternaForm'] = checkAndConvertToArray($post['cotizacionInternaForm']);
		$post['flagCuenta'] = checkAndConvertToArray($post['flagCuenta']);
		$post['flagRedondearForm'] = checkAndConvertToArray($post['flagRedondearForm']);

		foreach ($post['nameItem'] as $k => $r) {
			$dataItem = [];
			$idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
			$nameItem = $post['nameItem'][$k];
			$itemsSinProveedor = [];
			if (empty($idItem)) { // si es nuevo verificamos y lo registramos
				$validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem, 'nombre' =>  $nameItem]);
				$item = $validacionExistencia['query']->row_array();

				if (empty($item)) {

					$dataItem['insert'] = [
						'nombre' => trim($nameItem),
						'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
						'idItemTipo' => $post['tipoItemForm'][$k],
					];

					$dataItem['tabla'] = 'compras.item';
					$idItem = $this->model_item->insertarItem($dataItem)['id'];
				}

				if (!empty($item)) {
					$idItem = $item['idItem'];
					$itemsSinProveedor[$idItem] = true;
				}
			}

			if ($post['cantidadForm'][$k] > LIMITE_COMPRAS) {
				$post['cotizacionInternaForm'][$k] = 1;
			}

			$data['insert'][] = [
				'idCotizacion' => $insert['id'],
				'idItem' => $idItem,
				'idItemTipo' => $post['tipoItemForm'][$k],
				'nombre' => trim($nameItem),
				'cantidad' => $post['cantidadForm'][$k],
				'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
				'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
				'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
				'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
				'idItemEstado' => !empty($itemsSinProveedor[$idItem]) ? 2  : $post['idEstadoItemForm'][$k],
				'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
				'idCotizacionDetalleEstado' => 1,
				'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
				'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
				'caracteristicasProveedor' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
				'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
				'cotizacionInterna' => !empty($post['cotizacionInternaForm'][$k]) ? $post['cotizacionInternaForm'][$k] : 0,
				'flagCuenta' => !empty($post['flagCuenta'][$k]) ? $post['flagCuenta'][$k] : 0,
				'flagRedondear' => !empty($post['flagRedondearForm'][$k]) ? $post['flagRedondearForm'][$k] : 0,
				'fechaCreacion' => getActualDateTime()
			];

			switch ($post['tipoItemForm'][$k]) {
				case COD_TRANSPORTE['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'nombre' => $post["nombreSubItemForm[$k]"],
						'costo' => $post["costoSubItemForm[$k]"],
						'subTotal' => $post["costoSubItemForm[$k]"],
					]);
					break;

				case COD_DISTRIBUCION['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'unidadMedida' => $post["unidadMedidaSubItem[$k]"],
						'tipoServicio' => $post["tipoServicioSubItem[$k]"],
						'costo' => $post["costoSubItem[$k]"],
						'cantidad' => $post["cantidadSubItemDistribucion[$k]"],
						'cantidadPdv' => $post["cantidadPdvSubItemDistribucion[$k]"],
						'idItemLogistica' => isset($post["itemLogisticaForm[$k]"]) ? $post["itemLogisticaForm[$k]"] : null,
						'idDistribucionTachado' => $post["chkTachado[$k]"],
						'requiereOrdenCompra' => empty($post["generarOCSubItem[$k]"]) ? 0 : 1,
						'idProveedorDistribucion' => isset($post["proveedorDistribucionSubItem[$k]"]) ? $post["proveedorDistribucionSubItem[$k]"] : null,
						'cantidadReal' => $post["cantidadRealSubItem[$k]"],
					]);
					break;

				case COD_TEXTILES['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'talla' => $post["tallaSubItem[$k]"],
						'tela' => $post["telaSubItem[$k]"],
						'color' => $post["colorSubItem[$k]"],
						'cantidad' => $post["cantidadTextil[$k]"],
						'genero' => $post["generoSubItem[$k]"]
					]);


					break;

				case COD_TARJETAS_VALES['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'monto' => $post["montoSubItem[$k]"],
					]);
					break;

				default:
					$data['subDetalle'][$k] = [];
					break;
			}

			foreach ($data['subDetalle'][$k] as $subItem) {

				// if (isset($subItem['genero']) === NULL or empty($subItem['genero'])) unset($subItem['genero']); 
				$data['insertSubItem'][$k][] = [
					'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : NULL,
					'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : NULL,
					'unidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : NULL,
					'tipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : NULL,
					'costo' => !empty($subItem['costo']) ? $subItem['costo'] : NULL,
					'talla' => !empty($subItem['talla']) ? $subItem['talla'] : NULL,
					'tela' => !empty($subItem['tela']) ? $subItem['tela'] : NULL,
					'color' => !empty($subItem['color']) ? $subItem['color'] : NULL,
					'genero' => isset($subItem['genero']) ? $subItem['genero'] : NULL,
					'monto' => !empty($subItem['monto']) ? $subItem['monto'] : NULL,
					'subTotal' => !empty($subItem['costo']) && !empty($subItem['cantidad']) ? ($subItem['costo'] * $subItem['cantidad']) : NULL,
					'costoDistribucion' => !empty($post['costoDistribucion']) ? $post['costoDistribucion'] : NULL, //$post
					'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
					'idItem' => !empty($subItem['idItemLogistica']) ? $subItem['idItemLogistica'] : NULL,
					'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
					'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
					'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
					'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,

				];
			}

			if (!empty($post["file-name[$k]"])) {
				$data['archivos_arreglo'][$k] = getDataRefactorizada([
					'base64' => $post["file-item[$k]"],
					'type' => $post["file-type[$k]"],
					'name' => $post["file-name[$k]"],
				]);
				foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
					$data['archivos'][$k][] = [
						'base64' => $archivo['base64'],
						'type' => $archivo['type'],
						'name' => $archivo['name'],
						'carpeta' => 'cotizacion',
						'nombreUnico' => uniqid(),
					];
				}
			}

			if (isset($post['imagenDeItem[' . $idItem . ']'])) {
				foreach (checkAndConvertToArray($post['imagenDeItem[' . $idItem . ']']) as $imagenes) {
					$itemImagen = $this->db->where('idItemImagen', $imagenes)->get('compras.itemImagen')->row_array();
					$data['archivosDeImagen'][$k][] = $this->db->where('idItemImagen', $imagenes)->get('compras.itemImagen')->row_array();
				}
			}
		}

		$data['tabla'] = 'compras.cotizacionDetalle';

		$insertDetalle = $this->model->insertarCotizacionDetalle($data);
		$data = [];

		if ($post['tipoRegistro'] == ESTADO_ENVIADO_COMPRAS) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}

			$estadoEmail = $this->enviarCorreo(['idCotizacion' => $insert['id'], 'to' => $toCompras]);
			$necesitaCotizacionIntera = false;
			foreach ($post['cotizacionInternaForm'] as $cotizacionInterna) {
				if ($cotizacionInterna == 1) {
					$necesitaCotizacionIntera = true;
					break;
				}
			}

			$estadoCotizacion = ($necesitaCotizacionIntera) ? ESTADO_ENVIADO_COMPRAS : ESTADO_CONFIRMADO_COMPRAS;
			$data['tabla'] = 'compras.cotizacion';
			$data['update'] = [
				'idCotizacionEstado' => $estadoCotizacion,
			];
			$data['where'] = [
				'idCotizacion' => $post['idCotizacion'],
			];

			$this->model->actualizarCotizacion($data);

			$insertCotizacionHistorico = [
				'idCotizacionEstado' => $estadoCotizacion,
				'idCotizacion' => $post['idCotizacion'],
				'idUsuarioReg' => $this->idUsuario,
				'estado' => true,
			];
			$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);
		}

		if (!$insert['estado'] || !$insertDetalle['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		}

		respuesta:

		echo json_encode($result);
	}

	public function insertarCotizacionDetalleSub($params)
	{
		$dataDetalle = $params['data'];
		$post = $params['post'];
		$idCotizacion = $dataDetalle['insert'][0]['idCotizacion'];

		$this->db->select('idCotizacion, idCotizacionDetalle, idItem');
		$this->db->where([
			'idCotizacion' => $idCotizacion
		]);
		$detalle = $this->db->get('compras.cotizacionDetalle')->result_array();


		return true;
	}

	public function actualizarEstadoCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$data['tabla'] = 'compras.cotizacion';
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion']
		];

		$update = $this->model->actualizarCotizacion($data);
		$data = [];

		if (!$update['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		respuesta:
		echo json_encode($result);
	}

	public function enviarCorreo($params = [])
	{
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'teamsystem@visualimpact.com.pe',
			'smtp_pass' => '#nVi=0sN0ti$',
			'mailtype' => 'html'
		);

		$this->load->library('email', $config);
		$this->email->clear(true);
		$this->email->set_newline("\r\n");

		$data = [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
		$this->email->to($params['to']);
		$this->email->cc($cc);

		$data = $this->model->obtenerInformacionCotizacionDetalle($params)['query']->result_array();



		foreach ($data as $key => $row) {
			$dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
			$dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costo'] = $row['costo'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
		}

		$dataParaVista['link'] = base_url() . index_page() . 'Cotizacion';

		$bcc = array(
			'eder.alata@visualimpact.com.pe',
			'luis.durand@visualimpact.com.pe'
		);
		$this->email->bcc($bcc);

		$this->email->subject('IMPACTBUSSINESS - NUEVA COTIZACION GENERADA');
		$html = $this->load->view("modulos/Cotizacion/correo/informacionProveedor", $dataParaVista, true);
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Cotizacion'], true);
		$this->email->message($correo);

		$estadoEmail = $this->email->send();

		if (!$estadoEmail) {

			$mensaje = $this->email->print_debugger();
		}

		return $estadoEmail;
	}

	public function generarCotizacionPDF() //DescargarCotizacion
	{
		$data = [];
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idCotizacion = $post['id'];

		if (!empty($idCotizacion)) {
			$data = [];
			$dataParaVista = [];
			$dataParaVista['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion /*, 'anexo' => true */])['query']->result_array();
			// $dataParaVista['imagenDeItem'] = $this->model->obtenerImagenesDeCotizacion(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
			$data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();
			$dataArchivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion])['query']->result_array();
			foreach ($data as $key => $row) {
				$dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
				$dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
				$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
				$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
				$dataParaVista['cabecera']['comentario'] = $row['comentario'];
				// $dataParaVista['cabecera']['tipoCotizacion'] = $row['tipoCotizacion'];
				$dataParaVista['cabecera']['fecha'] = $row['fechaCreacion'];
				$dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
				$dataParaVista['cabecera']['fee'] = $row['fee'];
				$dataParaVista['cabecera']['igv'] = $row['flagIgv'];
				$dataParaVista['cabecera']['total'] = $total = $row['total'];
				$dataParaVista['cabecera']['total_fee'] = $row['total_fee'];
				$dataParaVista['cabecera']['total_fee_igv'] = $row['total_fee_igv'];
				$dataParaVista['detalle'][$key]['idCotizacionDetalle'] = $row['idCotizacionDetalle'];
				$dataParaVista['detalle'][$key]['item'] = $row['item'];
				$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
				$dataParaVista['detalle'][$key]['costo'] = $row['costo'];
				$dataParaVista['detalle'][$key]['gap'] = $row['gap'];
				$dataParaVista['detalle'][$key]['precio'] = $row['precio'];
				$dataParaVista['detalle'][$key]['subtotal'] = $row['subtotal'];
				$dataParaVista['detalle'][$key]['caracteristicas'] = $row['caracteristicas'];
				$dataParaVista['detalle'][$key]['idItemTipo'] = $row['idItemTipo'];
				$dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
				$dataParaVista['detalle'][$key]['itemMarca'] = $row['itemMarca'];

				$dataParaVista['detalleSub'][$row['idCotizacionDetalle']] = $this->model->obtenerCotizacionDetalleSub(['idCotizacionDetalle' => $row['idCotizacionDetalle']])->result_array();
			}

			foreach ($dataArchivos as $archivo) {
				$dataParaVista['archivos'][$archivo['idCotizacionDetalle']][] = $archivo;
			}

			if (!empty($dataParaVista['cabecera']['fee'])) {

				$dataParaVista['cabecera']['fee_prc'] = $fee = ($total * ($dataParaVista['cabecera']['fee'] / 100));

				$totalFee = $dataParaVista['cabecera']['total_fee'] = ($total + $fee);
			}

			if (!empty($dataParaVista['cabecera']['total_fee_igv'])) {
				$total = $dataParaVista['cabecera']['total'];
				if (!empty($totalFee)) {
					$dataParaVista['cabecera']['igv_prc'] =  $igv =  ($totalFee * $dataParaVista['cabecera']['igv'] ? IGV : 0);
					$dataParaVista['cabecera']['total_fee_igv'] = $totalFee + $igv;
				} else if (empty($totalFee) && $dataParaVista['cabecera']['igv']) {
					$dataParaVista['cabecera']['igv_prc'] =  $igv =  ($total * $dataParaVista['cabecera']['igv'] ? IGV : 0);
					$dataParaVista['cabecera']['total_fee_igv'] = $total + $igv;
				}
			}

			if (empty($dataParaVista['cabecera']['total_fee_igv'])) {
				$dataParaVista['cabecera']['total_fee_igv'] = $totalFee;
			}

			$dataParaVista['cabecera']['mostrarPrecio'] = false;

			if (count($dataParaVista) == 0) exit();

			$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'FORMATO DE COTIZACIÓN'], true);
			$contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);
			$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/body", $dataParaVista, true);
			$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);

			require APPPATH . '/vendor/autoload.php';
			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'setAutoTopMargin' => 'stretch',
				'orientation' => 'L',
				'autoMarginPadding' => 0,
				'bleedMargin' => 0,
				'crossMarkMargin' => 0,
				'cropMarkMargin' => 0,
				'nonPrintMargin' => 0,
				'margBuffer' => 0,
				'collapseBlockMargins' => false,
			]);
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->SetHTMLHeader($contenido['header']);
			$mpdf->SetHTMLFooter($contenido['footer']);
			$mpdf->AddPage();
			$mpdf->WriteHTML($contenido['style']);
			$mpdf->WriteHTML($contenido['body']);

			header('Set-Cookie: fileDownload=true; path=/');
			header('Cache-Control: max-age=60, must-revalidate');
			$mpdf->Output('Cotizacion.pdf', 'D');
		}

		$this->aSessTrack[] = ['idAccion' => 9];
	}

    public function generarVistaPreviaCotizacionPDF() //VistaPrevia
    {
        $data = [];
        require_once('../mpdf/mpdf.php');
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        //$idCotizacion = $post['id'];

        if (!empty($post)) {
           // $dataParaVista = $post;
            $dataParaVista['cabecera']['idCotizacion'] = $post['idCotizacion'];
            $dataParaVista['cabecera']['cotizacion'] = $post['cotizacion'];
            $dataParaVista['cabecera']['cuenta'] = $post['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $post['cuentaCentroCosto'];
            $dataParaVista['cabecera']['comentario'] = $post['comentario'];
            // $dataParaVista['cabecera']['tipoCotizacion'] = $post['tipoCotizacion'];
            $dataParaVista['cabecera']['fecha'] = $post['fechaCreacion'];
            $dataParaVista['cabecera']['cotizacionEstado'] = $post['cotizacionEstado'];
            $dataParaVista['cabecera']['fee'] = $post['fee'];
            $dataParaVista['cabecera']['igv'] = $post['flagIgv'];
            $dataParaVista['cabecera']['total'] = $total = $post['total'];
            $dataParaVista['cabecera']['total_fee'] = $post['total_fee'];
            $dataParaVista['cabecera']['total_fee_igv'] = $post['total_fee_igv'];


            $contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'FORMATO DE COTIZACIÓN'], true);
            $contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);
            $contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/body", $dataParaVista, true);
            $contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);

            require APPPATH . '/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'setAutoTopMargin' => 'stretch',
                'orientation' => 'L',
                'autoMarginPadding' => 0,
                'bleedMargin' => 0,
                'crossMarkMargin' => 0,
                'cropMarkMargin' => 0,
                'nonPrintMargin' => 0,
                'margBuffer' => 0,
                'collapseBlockMargins' => false,
            ]);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetHTMLHeader($contenido['header']);
            $mpdf->SetHTMLFooter($contenido['footer']);
            $mpdf->AddPage();
            $mpdf->WriteHTML($contenido['style']);
            $mpdf->WriteHTML($contenido['body']);

            header('Set-Cookie: fileDownload=true; path=/');
            header('Cache-Control: max-age=60, must-revalidate');
            $mpdf->Output('Cotizacion.pdf', 'D');
        }

        $this->aSessTrack[] = ['idAccion' => 9];
    }

	public function guardarArchivo()
	{
		$ruta = '../documentosAdjuntos/ImpactBusiness/'; //Decalaramos una variable con la ruta en donde almacenaremos los archivos
		$mensage = 'Bien hecho'; //Declaramos una variable mensaje quue almacenara el resultado de las operaciones.
		foreach ($_FILES as $key) //Iteramos el arreglo de archivos
		{
			if ($key['error'] == UPLOAD_ERR_OK) //Si el archivo se paso correctamente Ccontinuamos
			{
				$NombreOriginal = $key['name']; //Obtenemos el nombre original del archivo
				$ext = pathinfo($NombreOriginal, PATHINFO_EXTENSION);
				$nombreUnico = uniqid() . '.' . $ext;
				$temporal = $key['tmp_name']; //Obtenemos la ruta Original del archivo
				$Destino = $ruta . $nombreUnico;	//Creamos una ruta de destino con la variable ruta y el nombre original del archivo

				move_uploaded_file($temporal, $Destino); //Movemos el archivo temporal a la ruta especificada

				$data = [
					'nombreOriginal' => $NombreOriginal,
					'nombreUnico' => $nombreUnico,
					'ext' => $ext
				];
			}

			if ($key['error'] == '') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
			{
				$mensage .= '-> Archivo <b>' . $NombreOriginal . '</b> Subido correctamente. <br>';
			}
			if ($key['error'] != '') //Si existio algún error retornamos un el error por cada archivo.
			{
				$mensage .= '-> No se pudo subir el archivo <b>' . $NombreOriginal . '</b> debido al siguiente Error: n' . $key['error'];
			}
		}
		if (!empty($data)) {
			echo json_encode($data);
		} else {
			echo $mensage; // Regresamos los mensajes generados al cliente
		}
	}

	public function guardarArchivoBD()
	{
		$this->db->trans_start();
		$result = $this->result;

		$post = json_decode($this->input->post('data'), true);

		$data['insert'] = [
			'idCotizacion' => $post['idCotizacion'],
			'idTipoArchivo' => 1, // Orden de COmpra
			'nombre_unico' => $post['nombreUnico'],
			'nombre_archivo' => $post['nombreOriginal'],
			'extension' => $post['ext'],
			'estado' => true,
			'idUsuarioReg' => $this->idUsuario,
		];
		$data['tabla'] = 'compras.cotizacionArchivos';
		$rs = $this->model->insertar($data);

		$data['tabla'] = 'compras.cotizacion';
		$data['update'] = [
			'idCotizacionEstado' => 6
		];
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion'],
		];

		$rs = $this->model->actualizarCotizacion($data);
		if (!$rs['estado']) {
			$result['result'] = 0;
			$result['data']['width'] = '40%';
			$result['data']['html'] = createMessage(['type' => 2, 'No se pudo guardar el archivo']);
		} else {
			$result['result'] = 1;
			$result['data']['html'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();
		echo json_encode($result);
	}

	public function formularioSolicitudCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $post['id']])['query']->result_array();

		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();

		$itemServicio =  $this->model_item->obtenerItemServicio();
		foreach ($itemServicio as $key => $row) {
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
			$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
		}
		foreach ($data['itemServicio'] as $k => $r) {
			$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
		}
		$data['itemServicio'][0] = array();
		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/frmSolicitudCotizacion", $dataParaVista, true);
		$result['data']['itemServicio'] = $data['itemServicio'];

		echo json_encode($result);
	}

	public function formularioAprobar()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Procesar Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/frmProcesarSinOc", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formFeatures()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioFeatures", $dataParaVista, true);

		echo json_encode($result);
	}

	public function viewItemDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['data'] = $this->model_item->obtenerInformacionItems(['idItem' => $post['codItem']])['query']->row_array();

		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Cotizacion';
		$result['data']['width'] = '50%';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/viewItemDetalle", $dataParaVista, true);

		echo json_encode($result);
	}

	public function viewRegistroCotizacion()
	{

		$config = array();
		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-action-button'
		);
		$config['js']['script'] = array(
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();

		$itemServicio =  $this->model_item->obtenerItemServicio();
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
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}

		$data['itemServicio'][0] = array();
		$config['data']['itemServicio'] = $data['itemServicio'];
		$config['single'] = true;
		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['gapEmpresas'] = $this->model->obtenerGapEmpresas()['query']->result_array();
		$config['data']['itemLogistica'] = $this->model_item->obtenerItemsLogistica();
		$config['data']['costoDistribucion'] = $this->model->obtenerCostoDistribucion()['query']->row_array();
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$config['data']['proveedorDistribucion'] = $this->model_proveedor->obtenerProveedorDistribucion()->result_array();
		$config['view'] = 'modulos/Cotizacion/viewFormularioRegistro';
		$this->view($config);
	}

	public function getImagenes()
	{
		$post = $this->input->post();
		$imagenes = $this->db->where(['idItem' => $post['idItem'], 'estado' => 1])->get('compras.itemImagen')->result_array();
		echo json_encode($imagenes);
	}
	public function viewSolicitudCotizacionInterna($idCotizacion = '')
	{
		if (empty($idCotizacion)) {
			redirect('Cotizacion', 'refresh');
		}

		$config = array();

		$this->load->library('Mobile_Detect');

		$detect = $this->mobile_detect;

		$config['data']['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['data']['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['data']['col_dropdown'] = 'three column' : '';

		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-action-button'
		);
		$config['js']['script'] = array(
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();


		$cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSubdis(
			[
				'idCotizacion' => $idCotizacion
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
		}

		foreach ($config['data']['cotizacionDetalle'] as $sub) {
			$config['data']['cotizacionDetalleArchivosDelProveedor'][$sub['idCotizacionDetalle']] = $this->model->getCotizacionProveedorArchivosSeleccionados(['idCotizacionDetalle' => $sub['idCotizacionDetalle']])->result_array();
		}

		foreach ($archivos as $archivo) {
			$config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
		}

		foreach ($cotizacionProveedores as $cotizacionProveedor) {
			$config['data']['cotizacionProveedor'][$cotizacionProveedor['idCotizacionDetalle']] = $cotizacionProveedor;
		}
		foreach ($cotizacionProveedoresVista as $cotizacionProveedorVista) {
			$config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
		}

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio =  $this->model_item->obtenerItemServicio();
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
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}
		$data['itemServicio'][0] = array();
		$config['data']['itemServicio'] = $data['itemServicio'];

		$config['single'] = true;

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$config['data']['costoDistribucion'] = $this->model->obtenerCostoDistribucion()['query']->row_array();
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$config['data']['proveedorDistribucion'] = $this->model_proveedor->obtenerProveedorDistribucion()->result_array();

		foreach ($config['data']['tachadoDistribucion'] as $tachado) {
			$config['data']['detalleTachado'][$tachado['idItem']][] = $tachado;
		}

		$config['data']['disabled'] = true;
		$config['data']['siguienteEstado'] = ESTADO_ENVIADO_CLIENTE;
		$config['data']['controller'] = 'Cotizacion';
		$config['view'] = 'modulos/SolicitudCotizacion/viewFormularioActualizarCotizacionCliente';

		$this->view($config);
	}

	public function frmGenerarOper()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$ids = implode(',', $post['ids']);
		$cotizaciones = $this->model->obtenerInformacionCotizacion(['id' => $ids])['query']->result_array();
		$cotizacionDetalle = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();
		$diasMax = ($this->model->obtenerMaxDiasEntrega(['idsCotizacion' => $ids])['query']->row_array())['diasEntrega'];
		$dataParaVista = [];
		$dataParaVista['totalOper'] = 0;
		foreach ($cotizaciones as $row) {
			$dataParaVista['cuenta'][$row['idCuenta']] = [
				'id' => $row['idCuenta'],
				'value' => $row['cuenta']
			];
			$dataParaVista['cuentaCentroCosto'][$row['idCuentaCentroCosto']] = [
				'id' => $row['idCuentaCentroCosto'],
				'value' => $row['cuentaCentroCosto']
			];

			$dataParaVista['totalOper'] += $row['total'];
		}

		foreach ($cotizacionDetalle as $rowDetalle) {
			$dataParaVista['detalle'][$rowDetalle['idCotizacion']][$rowDetalle['idCotizacionDetalle']] = $rowDetalle;
		}
		$dataParaVista['cotizaciones'] = $cotizaciones;
		$dataParaVista['usuarios'] = $this->model->obtenerUsuarios()->result_array();
		$dataParaVista['fechaEntrega'] = $this->model->calcularDiasHabiles(['dias' => $diasMax]);

		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['msg']['title'] = 'GENERAR OPER';

		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formRegistrarOper", $dataParaVista, true);

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function registrarOper()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);


		$insertOper = [
			'requerimiento' => !empty($post['requerimiento']) ? $post['requerimiento'] : NULL,
			'total' => !empty($post['totalOper']) ? $post['totalOper'] : NULL,
			'fechaRequerimiento' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'concepto' => !empty($post['concepto']) ? $post['concepto'] : NULL,
			'idUsuarioReceptor' => !empty($post['receptor']) ? $post['receptor'] : NULL,
			'idUsuarioReg' => $this->idUsuario,
			'observacion' => !empty($post['observaciones']) ? $post['observaciones'] : NULL,
		];

		$oper = $this->model->insertar(['tabla' => 'compras.oper', 'insert' => $insertOper]);
		$operUpdate = $this->model->actualizarCotizacion(['tabla' => 'compras.oper', 'update' => ['requerimiento' => "OP" . generarCorrelativo($oper['id'], 6)], 'where' => ['idOper' => $oper['id']]]);

		$post['idCotizacion'] = checkAndConvertToArray($post['idCotizacion']);

		$insertOperDetalle = [];
		$updateCotizacion = [];
		$insertHistoricoCotizacion = [];
		foreach ($post['idCotizacion'] as $idCotizacion) {
			$insertOperDetalle[] = [
				'idOper' => $oper['id'],
				'idCotizacion' => $idCotizacion,
			];

			$updateCotizacion[] = [
				'idCotizacion' => $idCotizacion,
				'idCotizacionEstado' => ESTADO_OPER_ENVIADO,
			];

			$insertHistoricoCotizacion[] = [
				'idCotizacionEstado' => ESTADO_OPER_ENVIADO,
				'idCotizacion' => $idCotizacion,
				'idUsuarioReg' => $this->idUsuario,
			];
		}

		$operDet = $this->model->insertarMasivo('compras.operDetalle', $insertOperDetalle);
		$updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion', $updateCotizacion, 'idCotizacion');
		$insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION, $insertHistoricoCotizacion);

		if (!$oper['estado'] || $operDet['estado']) {
			$result['result'] = 0;
			$result['data']['width'] = '40%';
			$result['data']['html'] = createMessage(['type' => 2, 'No se pudo generar el OPER']);
			goto respuesta;
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Generar Oper';
			$result['data']['html'] = getMensajeGestion('registroExitoso');
			$dataParaVista = [];
			$ids = implode(',', $post['idCotizacion']);
			$dataParaVista['detalle'] = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();

			$html = $this->load->view("modulos/Cotizacion/correoGeneracionOper", $dataParaVista, true);
			$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "SolicitudCotizacion/viewUpdateOper/{$oper['id']}"], true);

			$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => USER_COORDINADOR_OPERACIONES])['query']->result_array();
			$toOperaciones = [];
			foreach ($usuariosOperaciones as $usuario) {
				$toOperaciones[] = $usuario['email'];
			}

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => USER_COORDINADOR_COMPRAS])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}

			$config = [
				'to' => $toOperaciones,
				'cc' =>  $toCompras,
				'asunto' => 'Generación de Oper',
				'contenido' => $correo,
			];
			email($config);
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function finalizarCotizacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$post['idCotizacion'] = checkAndConvertToArray($post['idCotizacion']);

		$updateCotizacion = [];
		$insertHistoricoCotizacion = [];
		foreach ($post['idCotizacion'] as $idCotizacion) {

			$updateCotizacion[] = [
				'idCotizacion' => $idCotizacion,
				'idCotizacionEstado' => ESTADO_FINALIZADA,
			];

			$insertHistoricoCotizacion[] = [
				'idCotizacionEstado' => ESTADO_FINALIZADA,
				'idCotizacion' => $idCotizacion,
				'idUsuarioReg' => $this->idUsuario,
			];
		}

		$updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion', $updateCotizacion, 'idCotizacion');
		$insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION, $insertHistoricoCotizacion);

		$result['msg']['title'] = 'Finalizar Cotizacion';

		if (!$updateCotizacion || !$insertHistoricoCotizacion) {
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'No se pudo finalizar la cotización']);
		} else {
			$result['result'] = 1;
			$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'La cotización se finalizó correctamente']);
			$this->db->trans_complete();
		}

		echo json_encode($result);
	}

	public function descargarOper()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		$oper = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])['query']->result_array();
		$dataParaVista['dataOper'] = $oper[0];
		$ids = [];
		foreach ($oper as $v) {
			$ids[] = $v['idCotizacion'];
			$config['data']['oper'][$v['idOper']] = $v;
		}

		$idCotizacion = implode(",", $ids);
		$dataParaVista['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
		$dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();

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

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'REQUERIMIENTO DE BIENES O SERVICIOS', 'codigo' => 'SIG-LOG-FOR-001'], true);
		$contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);

		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/oper", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("OPER.pdf", \Mpdf\Output\Destination::DOWNLOAD);

		return true;
	}

	public function getOrdenesCompra()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		// $ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'],'idOrdenCompra' => $idOrdenCompra,'estado' => 1])['query']->result_array();
		$dataParaVista['data'] = $this->model->obtenerInformacionOrdenCompra()['query']->result_array();
		$ordenDetalle = $this->model->obtenerInformacionOrdenCompraCotizacion()['query']->result_array();

		foreach ($ordenDetalle as $row) {
			$dataParaVista['cotizaciones'][$row['idOrdenCompra']][] = $row['cotizacionCodNombre'];
		}

		foreach ($ordenDetalle as $key => $value) {
			$dataParaVista['codCotizacion'][] = $key['codCotizacion'];
		}

		$result['result'] = 1;
		$result['data']['width'] = '90%';
		$result['msg']['title'] = 'Ordenes de compra';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/tableOrdenCompra", $dataParaVista, true);

		echo json_encode($result);
	}

	public function descargarOrdenCompra()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);

		$ordenCompra = $this->model_formulario_proveedor->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();

		$dataParaVista['data'] = $ordenCompra[0];
		$dataParaVista['detalle'] = $ordenCompra;

		$dataParaVista['imagenesDeItem'] = [];
		if ($dataParaVista['data']['mostrar_imagenes'] == '1') {
			foreach ($dataParaVista['detalle'] as $key => $value) {
				$dataParaVista['imagenesDeItem'][$value['idItem']] = $this->db->where('idItem', $value['idItem'])->get('compras.itemImagen')->result_array();
			}
		}

		$ids = [];
		foreach ($ordenCompra as $v) {
			$cuenta = $this->model->obtenerCuentaDeLaCotizacionDetalle($v['idCotizacion']);
			$centroCosto = $this->model->obtenerCentroCostoDeLaCotizacionDetalle($v['idCotizacion']);

			$cuentas[$cuenta] = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $cuenta])->row_array()['nombre'];
			$centrosDeCosto[$centroCosto] = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $centroCosto])->row_array()['subcanal'];
			$ids[] = $v['idCotizacion'];
		}
		$dataParaVista['cuentas'] = implode(', ', $cuentas);
		$dataParaVista['centrosCosto'] = implode(', ', $centrosDeCosto);
		$idCotizacion = implode(",", $ids);
		// $dataParaVista['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
		// $dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => false])['query']->result_array();

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

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'ORDEN DE COMPRA DE BIENES Y SERVICIOS', 'codigo' => 'SIG-LOG-FOR-009'], true);
		$contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);

		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/orden_compra", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		$cod_oc = generarCorrelativo($dataParaVista['data']['idOrdenCompra'], 6);
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("OC{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}

	public function getFormSendToCliente()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista['data'] = $post;

		$result['result'] = 1;
		$result['data']['width'] = '75%';
		$result['msg']['title'] = 'Enviar Cotizacion al cliente';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formSendToCliente", $dataParaVista, true);

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function sendToCliente()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$data['tabla'] = 'compras.cotizacion';
		$data['update'] = [
			'idCotizacionEstado' => ESTADO_ENVIADO_CLIENTE,
		];
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion'],
		];
		$post['formRegistro']['anexosEliminados'] = !empty($post['anexosEliminados']) ? $post['anexosEliminados'] : [];
		$post['formRegistro']['archivoEliminado'] = !empty($post['archivosEliminados']) ? $post['archivosEliminados'] : [];

		$updateEstado = $this->model->actualizarCotizacion($data); //Update estado

		$update = $this->actualizarCotizacion($post['formRegistro']); //Update campos

		if ($update['result'] == 0) {
			$result['result'] = 0;
			$result['data']['width'] = '45%';
			$result['msg']['title'] = 'Enviar Cotizacion al cliente';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => "No se pudo enviar la cotización"]);
			goto respuesta;
		}

		$insertCotizacionHistorico = [
			'idCotizacionEstado' => ESTADO_ENVIADO_CLIENTE,
			'idCotizacion' => $post['idCotizacion'],
			'idUsuarioReg' => $this->idUsuario,
			'estado' => true,
		];
		$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);

		$message = 'Se actualizó la cotización';
		if ($post['flagEnviarCorreo'] == 1) {

			$this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => !empty($post['correos']) ? $post['correos'] : []]);
			$message = 'La cotización se envió al cliente';
		}

		$result['result'] = 1;
		$result['data']['width'] = '45%';
		$result['msg']['title'] = 'Enviar Cotizacion al cliente';
		$result['msg']['content'] = createMessage(['type' => 1, 'message' => $message]);

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}
	public function actualizaCotizacionData()
	{
		$post = json_decode($this->input->post('data'), true);
		echo json_encode($this->actualizarCotizacion($post));
	}
	public function actualizarCotizacion($post)
	{	

		$this->db->trans_start();
		$result = $this->result;

		$data['tabla'] = 'compras.cotizacion';

		$data = [];

		$data['update'] = [
			'nombre' => $post['nombre'],
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'fechaDeadline' => !empty($post['deadline']) ? $post['deadline'] : NULL,
			'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'flagIgv' => !empty($post['igv']) ? 1 : 0,
			'fee' => $post['feeForm'],
			'total' => $post['totalForm'],
			'total_fee' => $post['totalFormFee'],
			'total_fee_igv' => $post['totalFormFeeIgv'],
			'idPrioridad' => $post['prioridadForm'],
			'motivo' => !empty($post['motivoForm']) ? trim($post['motivoForm']) : '',
			'comentario' => $post['comentarioForm'],
			'diasValidez' => $post['diasValidez'],
			'mostrarPrecio' => !empty($post['flagMostrarPrecio']) ? $post['flagMostrarPrecio'] : 0,
		];
		
		if (isset($post['actualizarEstado'])) {
			if ($post['actualizarEstado'] == '2') {
				$data['update']['idCotizacionEstado'] = 2;
			}
		}

		if (isset($post['solicitante'])) {
			// Validar Existencia de Solicitante
			if (intval($post['solicitante'] == 0)) {
				$rpta = null;
			} else {
				$query = $this->db->get_where('compras.solicitante', ['idSolicitante' => $post['solicitante']]);
				$rpta = $query->row_array();
			}
			if (empty($rpta)) {
				$this->db->insert('compras.solicitante', [
					'nombre' => $post['solicitante'],
					'fechaRegistro' => getActualDateTime()
				]);
				$data['update']['idSolicitante'] = $this->db->insert_id();
			} else {
				$data['update']['idSolicitante'] = $post['solicitante'];
			}
		}

		if (isset($post['detalleEliminado'])) {
			$post['detalleEliminado'] = checkAndConvertToArray($post['detalleEliminado']);
			foreach ($post['detalleEliminado'] as $key => $value) {
				$this->db->update('compras.cotizacionDetalle',['estado' => 0], ['idCotizacionDetalle' => $value]);
			}
		}
		$validacionExistencia = $this->model->validarExistenciaCotizacion(['nombre' => $post['nombre'], 'idCotizacion' => $post['idCotizacion']]);
		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.cotizacion';
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion']
		];

		$update = $this->model->actualizarCotizacion($data);

		$data['anexos_arreglo'] = [];
		$data['anexos'] = [];


		if (!empty($post['anexo-file'])) {

			$data['anexos_arreglo'] = getDataRefactorizada([
				'base64' => $post['anexo-file'],
				'type' => $post['anexo-type'],
				'name' => $post['anexo-name'],
			]);

			foreach ($data['anexos_arreglo'] as $anexo) {
				if (empty($anexo['base64'])) continue;
				$data['anexos'][] = [
					'base64' => $anexo['base64'],
					'type' => $anexo['type'],
					'name' => $anexo['name'],
					'carpeta' => 'cotizacion',
					'nombreUnico' => "ANX" . uniqid(),
				];
			}
		}

		$data['idCotizacion'] = $post['idCotizacion'];
		$data['anexosEliminados'] = $post['anexosEliminados'];
		$insertAnexos = $this->model->insertarCotizacionAnexos($data);

		$data = [];

		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		$post['nameItemOriginal'] = checkAndConvertToArray($post['nameItemOriginal']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
		$post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
		$post['caracteristicasCompras'] = checkAndConvertToArray($post['caracteristicasCompras']);
		$post['caracteristicasProveedor'] = checkAndConvertToArray($post['caracteristicasProveedor']);
		$post['costoForm'] = checkAndConvertToArray($post['costoForm']);
		$post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
		$post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
		$post['gapForm'] = checkAndConvertToArray($post['gapForm']);
		$post['precioForm'] = checkAndConvertToArray($post['precioForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		if (isset($post['flagCuenta'])) $post['flagCuenta'] = checkAndConvertToArray($post['flagCuenta']);

		$post['flagRedondearForm'] = checkAndConvertToArray($post['flagRedondearForm']);

		foreach ($post['nameItem'] as $k => $r) {
			$idCot = $post['idCotizacionDetalle'][$k];
			if ($post['idCotizacionDetalle'][$k] != '0') {
				$data['update'][$k] = [
					'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
					'idCotizacion' => $post['idCotizacion'],
					'idItem' => (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL,
					'idItemTipo' => $post['tipoItemForm'][$k],
					'nombre' => $post['nameItem'][$k],
					'cantidad' => $post['cantidadForm'][$k],
					'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
					'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
					'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
					'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
					'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
					'idItemEstado' => $post['idEstadoItemForm'][$k],
					'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
					'idCotizacionDetalleEstado' => 2,
					'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
					'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
					'caracteristicasProveedor' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
					'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
					'flagCuenta' => !empty($post['flagCuenta'][$k]) ? $post['flagCuenta'][$k] : 0,
					'flagRedondear' => !empty($post['flagRedondearForm'][$k]) ? $post['flagRedondearForm'][$k] : 0,
				];

				if (!empty($post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"])) {
					switch ($post['tipoItemForm'][$k]) {
						case COD_SERVICIO['id']:
							$data['subDetalle'][$k] = getDataRefactorizada([
								'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
								'nombre' => $post["nombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
								'cantidad' => $post["cantidadSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							]);
							break;

						case COD_DISTRIBUCION['id']:
							$data['subDetalle'][$k] = getDataRefactorizada([
								'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
								'unidadMedida' => $post["unidadMedidaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'tipoServicio' => $post["tipoServicioSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'costo' => $post["costoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'cantidad' => $post["cantidadSubItemDistribucion[{$post['idCotizacionDetalle'][$k]}]"],
								'cantidadPdv' => $post["cantidadPdvSubItemDistribucion[{$post['idCotizacionDetalle'][$k]}]"],
								'idItem' => $post["itemLogisticaForm[{$post['idCotizacionDetalle'][$k]}]"],
								'idDistribucionTachado' => $post["chkTachado[{$post['idCotizacionDetalle'][$k]}]"],
								'requiereOrdenCompra' => empty($post["generarOCSubItem[{$post['idCotizacionDetalle'][$k]}]"]) ? 0 : 1,
								'idProveedorDistribucion' => isset($post["proveedorDistribucionSubItem[{$post['idCotizacionDetalle'][$k]}]"]) ? $post["proveedorDistribucionSubItem[{$post['idCotizacionDetalle'][$k]}]"] : null,
								'cantidadReal' => $post["cantidadRealSubItem[{$post['idCotizacionDetalle'][$k]}]"],
							]);
							break;

						case COD_TEXTILES['id']:
							$data['subDetalle'][$k] = getDataRefactorizada([
								'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
								'talla' => $post["tallaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'tela' => $post["telaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'color' => $post["colorSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								'cantidad' => $post["cantidadTextil[{$post['idCotizacionDetalle'][$k]}]"],
								'genero' => $post["generoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
								// 'costo' => $post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"],
								// 'subtotal' => $post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"],
							]);
							if (isset($post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"])) {
								$data['subDetalle'][$k]['costo'] = $post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"];
							}
							if (isset($post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"])) {
								$data['subDetalle'][$k]['subtotal'] = $post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"];
							}
							break;

						case COD_TARJETAS_VALES['id']:
							$data['subDetalle'][$k] = getDataRefactorizada([
								'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
								'monto' => $post["montoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
							]);
							break;

						case COD_TRANSPORTE['id']:
							$data['subDetalle'][$k] = getDataRefactorizada([
								'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
								'nombre' => $post["nombreSubItemForm[{$post['idCotizacionDetalle'][$k]}]"],
								'costo' => $post["costoSubItemForm[{$post['idCotizacionDetalle'][$k]}]"],
							]);
							break;
						default:
							$data['subDetalle'][$k] = [];
							break;
					}
				}

				// Cambiar de nombre en la tabla Item en caso se haga una modificacion en el mismo.
				if (!empty($post['idItemForm'][$k]) && $post['nameItem'][$k] != $post['nameItemOriginal'][$k] && !empty($post['nameItemOriginal'][$k])) {
					$this->db->update('compras.item', ['nombre' => $post['nameItem'][$k]], ['idItem' => $post['idItemForm'][$k]]);
				}
				// FIN
				if (!empty($post["file-name[$idCot]"])) {
					$data['archivos_arreglo'][$k] = getDataRefactorizada([
						'base64' => $post["file-item[$idCot]"],
						'type' => $post["file-type[$idCot]"],
						'name' => $post["file-name[$idCot]"],
					]);
					foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
						if (empty($archivo['base64'])) continue;
						$data['archivos'][$k][] = [
							'base64' => $archivo['base64'],
							'type' => $archivo['type'],
							'name' => $archivo['name'],
							'carpeta' => 'cotizacion',
							'nombreUnico' => uniqid(),
						];
					}
				}
			} else {
				$data['insert'][$k] = [
					'idCotizacion' => $post['idCotizacion'],
					'idItem' => (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL,
					'idItemTipo' => $post['tipoItemForm'][$k],
					'nombre' => $post['nameItem'][$k],
					'cantidad' => $post['cantidadForm'][$k],
					'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
					//'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
					'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
					'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
					'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
					'idItemEstado' => $post['idEstadoItemForm'][$k],
					'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
					'idCotizacionDetalleEstado' => 1,
					'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
					'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
					'caracteristicasProveedor' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
					'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
					'cotizacionInterna' => !empty($post['cotizacionInternaForm'][$k]) ? $post['cotizacionInternaForm'][$k] : 0,
					'flagCuenta' => !empty($post['flagCuenta'][$k]) ? $post['flagCuenta'][$k] : 0,
					'flagRedondear' => !empty($post['flagRedondearForm'][$k]) ? $post['flagRedondearForm'][$k] : 0,
					'fechaCreacion' => getActualDateTime(),
				];

				switch ($post['tipoItemForm'][$k]) {
					// case COD_SERVICIO['id']:
					// 	$subDetalleInsert[$k] = getDataRefactorizada([
					// 		'nombre' => $post["nombreSubItemServicio[$k]"],
					// 		'cantidad' => $post["cantidadSubItemServicio[$k]"],
					// 	]);
					// 	break;
					case COD_DISTRIBUCION['id']:
						$subDetalleInsert[$k] = getDataRefactorizada([
							'unidadMedida' => $post["unidadMedidaSubItem[$k]"],
							'tipoServicio' => $post["tipoServicioSubItem[$k]"],
							'costo' => $post["costoSubItem[$k]"],
							'cantidad' => $post["cantidadSubItemDistribucion[$k]"],	
							'cantidadPdv' => $post["cantidadPdvSubItemDistribucion[$k]"],
							'idItem' => $post["itemLogisticaForm[$k]"],
							'idDistribucionTachado' => $post["chkTachado[$k]"],
							'requiereOrdenCompra' => empty($post["generarOCSubItem[$k]"]) ? 0 : 1,
							'idProveedorDistribucion' => isset($post["proveedorDistribucionSubItem[$k]"]) ? $post["proveedorDistribucionSubItem[$k]"] : null,
							'cantidadReal' => $post["cantidadRealSubItem[$k]"],
						]);
						break;

					case COD_TEXTILES['id']:
						$subDetalleInsert[$k] = getDataRefactorizada([
							'talla' => $post["tallaSubItem[$k]"],
							'tela' => $post["telaSubItem[$k]"],
							'color' => $post["colorSubItem[$k]"],
							'cantidad' => $post["cantidadTextil[$k]"],
							'genero' => $post["generoSubItem[$k]"]
						]);
						break;

					case COD_TARJETAS_VALES['id']:
						$subDetalleInsert[$k] = getDataRefactorizada([
							'monto' => $post["montoSubItem[$k]"],
						]);
						break;

					case COD_TRANSPORTE['id']:
						$subDetalleInsert[$k] = getDataRefactorizada([
							'nombre' => $post["nombreSubItemForm[$k]"],
							'costo' => $post["costoSubItemForm[$k]"],
						]);
						break;
					default:
						$subDetalleInsert[$k] = [];
						break;
				}

				if (!empty($subDetalleInsert)) {
					foreach ($subDetalleInsert[$k] as $subItem) {
						$data['newInsertSubItem'][$k][] = [
							'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : NULL,
							'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : NULL,
							'unidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : NULL,
							'tipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : NULL,
							'costo' => !empty($subItem['costo']) ? $subItem['costo'] : NULL,
							'talla' => !empty($subItem['talla']) ? $subItem['talla'] : NULL,
							'tela' => !empty($subItem['tela']) ? $subItem['tela'] : NULL,
							'color' => !empty($subItem['color']) ? $subItem['color'] : NULL,
							'genero' => !empty($subItem['genero']) ? $subItem['genero'] : NULL,
							'monto' => !empty($subItem['monto']) ? $subItem['monto'] : NULL,
							'subTotal' => !empty($subItem['costo']) && !empty($subItem['cantidad']) ? ($subItem['costo'] * $subItem['cantidad']) : NULL,
							'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
							'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
							'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
							'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : NULL,
							'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
							'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
						];
					}
				}

				if (!empty($post["file-name[$k]"])) {
					$data['archivos_arreglo'][$k] = getDataRefactorizada([
						'base64' => $post["file-item[$k]"],
						'type' => $post["file-type[$k]"],
						'name' => $post["file-name[$k]"],
					]);
					foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
						if (empty($archivo['base64'])) continue;
						$data['archivos'][$k][] = [
							'base64' => $archivo['base64'],
							'type' => $archivo['type'],
							'name' => $archivo['name'],
							'carpeta' => 'cotizacion',
							'nombreUnico' => uniqid(),
						];
					}
				}
			}
		}
		$data['archivoEliminado'] = isset($post['archivosEliminados']) ? $post['archivosEliminados'] : null;

		$data['tabla'] = 'compras.cotizacionDetalle';
		$data['where'] = 'idCotizacionDetalle';

		$updateDetalle = $this->model->actualizarCotizacionDetalleArchivos($data);
		if (!empty($post['subItemEliminado'])) {
			foreach ($post['subItemEliminado'] as $key => $value) {
				$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalleSub' => $value]);
			}
		}
		$data = [];

		$estadoEmail = true;
		if (isset($post['actualizarEstado'])) {
			if ($post['actualizarEstado'] == '2') {
				// Para no enviar Correos en modo prueba.
				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);
				$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toCompras = [];
				foreach ($usuariosCompras as $usuario) {
					$toCompras[] = $usuario['email'];
				}

				$estadoEmail = $this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => $toCompras]);
			}
		}

		if (!$update['estado'] || !$updateDetalle['estado'] || !$estadoEmail) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();
		respuesta:
		return $result;
	}

	public function registrarSolicitudAutorizacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$insert = [
			'idTipoAutorizacion' => AUTH_CAMBIO_COSTO,
			'idCotizacion' => $post['idCotizacion'],
			'idCotizacionDetalle' => $post['idCotizacionDetalle'],
			'idAutorizacionEstado' => AUTH_ESTADO_PENDIENTE,
			'comentario' => !empty($post['comentario']) ? $post['comentario'] : NULL,
			'idUsuarioReg' => $this->idUsuario,
			'nuevoValor' => $post['nuevoCosto'],
			'nuevoGap' => $post['nuevoGap'],
			'estado' => true,
		];

		$rs = $this->model->insertar([
			'tabla' => 'compras.autorizacion',
			'insert' => $insert,
		]);

		if ($rs['estado']) {
			$result['result'] = 1;
			$result['data']['width'] = '45%';
			$result['msg']['title'] = 'Enviar solicitud de autorización';
			$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'Se registró la solicitud satisfactoriamente']);
		}
		if (!$rs['estado']) {
			$result['result'] = 0;
			$result['data']['width'] = '45%';
			$result['msg']['title'] = 'Enviar solicitud de autorización';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function viewFormularioActualizar($idCotizacion = '')
	{

		if (empty($idCotizacion)) {
			redirect('Cotizacion', 'refresh');
		}

		$config = array();

		$this->load->library('Mobile_Detect');

		$detect = $this->mobile_detect;

		$config['data']['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['data']['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['data']['col_dropdown'] = 'three column' : '';

		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-action-button'
		);
		$config['js']['script'] = array(
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['btnEnviar'] = false;
		$config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
		if ($config['data']['cotizacion']['idCotizacionEstado'] == '1') {
			$config['data']['btnEnviar'] = true;
		}
		$config['data']['costo'] = $this->model->obtenerCosto(['id' => $idCotizacion])['query']->row_array();
		$config['data']['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionTarifario'] = $this->model->obtenerCotizacionDetalleTarifario(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['proveedorDistribucion'] = $this->model_proveedor->obtenerProveedorDistribucion()->result_array();
		$config['data']['costoDistribucion'] = $this->model->obtenerCostoDistribucion()['query']->row_array();
		$config['data']['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		// $cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();

		$cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSubdis(
			[
				'idCotizacion' => $idCotizacion,
				// 'cotizacionInterna' => true
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
		}

		foreach ($archivos as $archivo) {
			$config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
		}

		foreach ($cotizacionProveedoresVista as $cotizacionProveedorVista) {
			$config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
		}

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio =  $this->model_item->obtenerItemServicio();
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
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cantidadImagenes'] = $row['cantidadImagenes'];
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}
		$data['itemServicio'][0] = array();
		$config['data']['itemServicio'] = $data['itemServicio'];
		$config['single'] = true;
		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
		$config['data']['disabled'] = true;
		$config['data']['siguienteEstado'] = ESTADO_ENVIADO_CLIENTE;
		$config['data']['controller'] = 'Cotizacion';

		$config['view'] = 'modulos/Cotizacion/viewFormularioActualizar';

		$this->view($config);
	}


	public function viewFormularioDuplicar($idCotizacion = '')
	{



		if (empty($idCotizacion)) {
			redirect('Cotizacion', 'refresh');
		}


		$config = array();
		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-action-button'
		);
		$config['js']['script'] = array(
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
		$config['data']['costo'] = $this->model->obtenerCosto(['id' => $idCotizacion])['query']->row_array();
		$config['data']['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionTarifario'] = $this->model->obtenerCotizacionDetalleTarifario(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		// $cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();

		$cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSubdis(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => true
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
		}

		foreach ($archivos as $archivo) {
			$config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
		}

		foreach ($cotizacionProveedoresVista as $cotizacionProveedorVista) {
			$config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
		}

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio =  $this->model_item->obtenerItemServicio();
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
		}
		foreach ($data['itemServicio'] as $k => $r) {
			$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
		}
		$data['itemServicio'][0] = array();
		$config['data']['itemServicio'] = $data['itemServicio'];

		$config['single'] = true;

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['nombre'] = '  [copia]';
		$config['data']['repetido'] = 1;
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
		$config['data']['disabled'] = true;
		$config['data']['siguienteEstado'] = ESTADO_ENVIADO_CLIENTE;
		$config['data']['controller'] = 'Cotizacion';
		$config['view'] = 'modulos/Cotizacion/viewFormularioDuplicar';

		$this->view($config);
	}

	public function actualizarCotizacion2()
	{

		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$dataDetalleSub = [];

		$whereSolicitante = [];
		$whereSolicitante[] = [
			'estado' => 1
		];
		$tablaSolicitantes = 'compras.solicitante';

		$solicitantes = $this->model->getWhereJoinMultiple($tablaSolicitantes, $whereSolicitante)->result_array();
		$dataSolicitante = [];
		foreach ($solicitantes as $solicitante) {
			$dataSolicitante[$solicitante['nombre']] = $solicitante['idSolicitante'];
		}

		$idSolicitante = NULL;
		if (empty($dataSolicitante[$post['solicitante']])) {
			$insertSolicitante = [
				'nombre' => $post['solicitante'],
				'fechaRegistro' => getActualDateTime(),
				'estado' => true,
			];
			$insertSolicitante = $this->model->actualizarCotizacion(['tabla' => $tablaSolicitantes, 'insert' => $insertSolicitante]);
			$idSolicitante = $insertSolicitante['id'];
		}

		if (!empty($dataSolicitante[$post['solicitante']])) {
			if (!is_numeric($post['solicitante'])) {
				$idSolicitante = $dataSolicitante[$post['solicitante']];
			}
			if (is_numeric($post['solicitante'])) {
				$idSolicitante = $post['solicitante'];
			}
		}

		$data['update'] = [
			'nombre' => $post['nombre'],
			'fechaEmision' => getActualDateTime(),
			'idCuenta' => $post['cuentaForm'],
			// 'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idSolicitante' => $idSolicitante,
			'fechaDeadline' => !empty($post['deadline']) ? $post['deadline'] : NULL,
			'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
			'fee' => $post['feeForm'],
			'total' => $post['totalForm'],
			'total_fee' => $post['totalFormFee'],
			'total_fee_igv' => $post['totalFormFeeIgv'],
			'idPrioridad' => $post['prioridadForm'],
			'motivo' => $post['motivoForm'],
			'comentario' => $post['comentarioForm'],
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idUsuarioReg' => $this->idUsuario
		];

		$data['tabla'] = 'compras.cotizacion';
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion']
		];

		$insert = $this->model->actualizarCotizacion($data);

		$data = [];


		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
		$post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
		$post['costoForm'] = checkAndConvertToArray($post['costoForm']);
		$post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
		$post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
		$post['gapForm'] = checkAndConvertToArray($post['gapForm']);
		$post['precioForm'] = checkAndConvertToArray($post['precioForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		$post['cotizacionInternaForm'] = checkAndConvertToArray($post['cotizacionInternaForm']);

		foreach ($post['nameItem'] as $k => $r) {
			$dataItem = [];
			$idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
			$nameItem = $post['nameItem'][$k];
			$itemsSinProveedor = [];

			$dataItem['update'] = [
				'nombre' => trim($nameItem),
				'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,

			];

			$dataItem['tabla'] = 'compras.item';
			$dataItem['where'] = [
				'idItem' => $post['idItemForm'][$k],
			];

			$idItem = $this->model_item->actualizarItem($dataItem);


			if (!empty($item)) {
				$idItem = $item['idItem'];
				$itemsSinProveedor[$idItem] = true;
			}

			$data['update'][] = [
				'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
				'idCotizacion' => $post['idCotizacion'],
				'idItem' => $post['idItemForm'][$k],
				'nombre' => trim($nameItem),
				'cantidad' => $post['cantidadForm'][$k],
				'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
				'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
				'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
				'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
				'idItemEstado' => !empty($post['idItemForm'][$k]) ? 2  : $post['idEstadoItemForm'][$k],
				'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
				'idCotizacionDetalleEstado' => 1,
				'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
				'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
				'cotizacionInterna' => !empty($post['cotizacionInternaForm'][$k]) ? $post['cotizacionInternaForm'][$k] : 0,
				'fechaModificacion' => getActualDateTime()
			];

			//

		}

		// $insertDetalleSub = $this->model->actualizarMasivo('compras.cotizacionDetalleSub', $data['update'], 'idCotizacionDetalleSub');

		$insertDetalle = $this->model->actualizarMasivo('compras.cotizacionDetalle', $data['update'], 'idCotizacionDetalle');
		$data = [];

		if (!$insert['estado'] || $insertDetalle['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}

	//filtroReporte
	public function filtroOper()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$dataParaVista['datos'] = $this->model->obtenerInformacionOperSolicitud($post)['query']->result_array();
		$operDetalle = $this->model->obtenerOperDetalleCotizacion()['query']->result_array();

		foreach ($operDetalle as $row) {
			$dataParaVista['cotizaciones'][$row['idOper']][] = $row['cotizacionCodNombre'];
		}


		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/SolicitudCotizacion/reporteFiltroSolicitud", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'Oper Registrados';
		$result['data']['width'] = '80%';

		echo json_encode($result);
	}

	public function duplicarCotizacion()
	{

		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['tabla'] = 'compras.cotizacion';

		$data['insert'] = [
			'nombre' => $post['nombre'],
			'fechaEmision' => getActualDateTime(),
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idSolicitante' => $post['solicitante'],
			'fechaDeadline' => !empty($post['deadline']) ? $post['deadline'] : NULL,
			'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
			'fee' => $post['feeForm'],
			'total' => $post['totalForm'],
			'total_fee' => $post['totalFormFee'],
			'total_fee_igv' => $post['totalFormFeeIgv'],
			'idPrioridad' => $post['prioridadForm'],
			'motivo' => $post['motivoForm'],
			'comentario' => $post['comentarioForm'],
			'diasValidez' => $post['diasValidez'],
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idUsuarioReg' => $this->idUsuario
		];

		$validacionExistencia = $this->model->validarExistenciaCotizacion($data['insert']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'El título de cotizacion ya se encuentra registrado']);
		}

		$insert = $this->model->insertarCotizacion($data);
		$data['idCotizacion'] = $insert['id'];

		$data['anexos_arreglo'] = [];
		$data['anexos'] = [];
		if (!empty($post['anexo-name'])) {

			$data['anexos_arreglo'] = getDataRefactorizada([
				'idCotizacionDetalleArchivo' => $post['idCotizacionDetalleArchivo'],
				'base64' => $post['anexo-file'],
				'type' => $post['anexo-type'],
				'name' => $post['anexo-name'],

			]);

			foreach ($data['anexos_arreglo'] as $anexo) {
				if (empty($anexo['idCotizacionDetalleArchivo'])) {

					$data['anexos'][] = [
						'base64' => $anexo['base64'],
						'type' => $anexo['type'],
						'name' => $anexo['name'],
						'carpeta' => 'cotizacion',
						'nombreUnico' => "ANX" . uniqid(),
					];
				} else {
					$data['anexoExistente'][] = $anexo['idCotizacionDetalleArchivo'];
				}
			}

			$insertAnexos = $this->model->insertarCotizacionAnexos($data);
		}

		$data = [
			'tabla' => 'compras.cotizacion',
			'update' => [
				'codCotizacion' => generarCorrelativo($insert['id'], 6)
			],
			'where' => [
				'idCotizacion' => $insert['id']
			]
		];
		$this->model->actualizarCotizacion($data);

		$post['idCotizacion'] = $insert['id'];
		$data = [];

		//Insertar historico estado cotizacion
		$insertCotizacionHistorico = [
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idCotizacion' => $post['idCotizacion'],
			'idUsuarioReg' => $this->idUsuario,
			'estado' => true,
		];

		$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);

		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
		$post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
		$post['costoForm'] = checkAndConvertToArray($post['costoForm']);
		$post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
		$post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
		//  $post['feeForm'] = checkAndConvertToArray($post['feeForm']);
		$post['gapForm'] = checkAndConvertToArray($post['gapForm']);
		$post['precioForm'] = checkAndConvertToArray($post['precioForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		$post['cotizacionInternaForm'] = checkAndConvertToArray($post['cotizacionInternaForm']);

		foreach ($post['nameItem'] as $k => $r) {
			$dataItem = [];
			$idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
			$nameItem = $r;
			$itemsSinProveedor = [];
			if (empty($idItem)) { // si es nuevo verificamos y lo registramos
				$validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem, 'nombre' =>  $nameItem]);
				$item = $validacionExistencia['query']->row_array();

				if (empty($item)) {
					$dataItem['insert'] = [
						'nombre' => trim($nameItem),
						'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
						'idItemTipo' => $post['tipoItemForm'][$k],
					];

					$dataItem['tabla'] = 'compras.item';
					$idItem = $this->model_item->insertarItem($dataItem)['id'];
				}

				if (!empty($item)) {
					$idItem = $item['idItem'];
					$itemsSinProveedor[$idItem] = true;
					$post['idEstadoItemForm'][$k] = 3; // Estado Item: En desuso.
				}
			}

			if ($post['cantidadForm'][$k] > LIMITE_COMPRAS) {
				$post['cotizacionInternaForm'][$k] = 1;
			}

			$data['insert'][] = [
				'idCotizacion' => $insert['id'],
				'idItem' => $idItem,
				'idItemTipo' => $post['tipoItemForm'][$k],
				'nombre' => trim($nameItem),
				'cantidad' => $post['cantidadForm'][$k],
				'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
				'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
				//  'fee' => !empty($post['feeForm'][$k]) ? $post['feeForm'][$k] : NULL,
				'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
				'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
				'idItemEstado' => !empty($itemsSinProveedor[$idItem]) ? 2  : $post['idEstadoItemForm'][$k],
				'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
				'idCotizacionDetalleEstado' => 1,
				'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
				'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
				'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
				'cotizacionInterna' => !empty($post['cotizacionInternaForm'][$k]) ? $post['cotizacionInternaForm'][$k] : 0,
				'fechaCreacion' => getActualDateTime()
			];

			switch ($post['tipoItemForm'][$k]) {
				case COD_SERVICIO['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'nombre' => $post["nombreSubItemServicio[$k]"],
						'cantidad' => $post["cantidadSubItemServicio[$k]"],
					]);
					break;

				case COD_DISTRIBUCION['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'unidadMedida' => $post["unidadMedidaSubItem[$k]"],
						'tipoServicio' => $post["tipoServicioSubItem[$k]"],
						'costo' => $post["costoSubItem[$k]"],
						'cantidad' => $post["cantidadSubItemDistribucion[$k]"],
					]);
					break;

				case COD_TEXTILES['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'talla' => $post["tallaSubItem[$k]"],
						'tela' => $post["telaSubItem[$k]"],
						'color' => $post["colorSubItem[$k]"],
						'cantidad' => $post["cantidadTextil[$k]"],
						'genero' => $post["generoSubItem[$k]"]
					]);
					break;

				case COD_TARJETAS_VALES['id']:
					$data['subDetalle'][$k] = getDataRefactorizada([
						'monto' => $post["montoSubItem[$k]"],
					]);
					break;

				default:
					$data['subDetalle'][$k] = [];
					break;
			}

			if (!empty($data['subDetalle'])) {
				foreach ($data['subDetalle'][$k] as $subItem) {
					$data['insertSubItem'][$k][] = [
						'nombre' => !empty($subItem['nombre']) ? $subItem['nombre'] : NULL,
						'cantidad' => !empty($subItem['cantidad']) ? $subItem['cantidad'] : NULL,
						'unidadMedida' => !empty($subItem['unidadMedida']) ? $subItem['unidadMedida'] : NULL,
						'tipoServicio' => !empty($subItem['tipoServicio']) ? $subItem['tipoServicio'] : NULL,
						'costo' => !empty($subItem['costo']) ? $subItem['costo'] : NULL,
						'talla' => !empty($subItem['talla']) ? $subItem['talla'] : NULL,
						'tela' => !empty($subItem['tela']) ? $subItem['tela'] : NULL,
						'color' => !empty($subItem['color']) ? $subItem['color'] : NULL,
						'genero' => !empty($subItem['genero']) ? $subItem['genero'] : NULL,
						'monto' => !empty($subItem['monto']) ? $subItem['monto'] : NULL,
						'subTotal' => !empty($subItem['costo']) && !empty($subItem['cantidad']) ? ($subItem['costo'] * $subItem['cantidad']) : NULL,
					];
				}
			}



			if (!empty($post["file-name[$k]"])) {

				if (!empty($post["idCotizacionDetalleArchivo2[$k]"])) {

					$data['archivos_arreglo'][$k] = getDataRefactorizada([

						'idCotizacionDetalleArchivo' => $post["idCotizacionDetalleArchivo2[$k]"],
						'base64' => $post["file-item[$k]"],
						'type' => $post["file-type[$k]"],
						'name' => $post["file-name[$k]"]

					]);
				}


				if (empty($post["idCotizacionDetalleArchivo2[$k]"])) {

					$data['archivos_arreglo'][$k] = getDataRefactorizada([

						'base64' => $post["file-item[$k]"],
						'type' => $post["file-type[$k]"],
						'name' => $post["file-name[$k]"]

					]);
				}

				// foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
				// 	if (empty($archivo['idCotizacionDetalleArchivo'])) {

				// 		$data['archivos'][$k][] = [
				// 			'base64' => $archivo['base64'],
				// 			'type' => $archivo['type'],
				// 			'name' => $archivo['name'],
				// 			'carpeta' => 'cotizacion',
				// 			'nombreUnico' => uniqid(),

				// 		];
				// 	}
				// }


				foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
					if (empty($archivo['idCotizacionDetalleArchivo'])) {

						$data['archivos'][$k][] = [
							'base64' => $archivo['base64'],
							'type' => $archivo['type'],
							'name' => $archivo['name'],
							'carpeta' => 'cotizacion',
							'nombreUnico' => uniqid(),

						];
					} else {

						$data['archivoExistente'][$k][] = $archivo['idCotizacionDetalleArchivo'];
						$data['idCotizacion'] = $insert['id'];
					}
				}
			}
		}




		$data['tabla'] = 'compras.cotizacionDetalle';

		$insertDetalle = $this->model->insertarCotizacionDetalle($data);
		$data = [];

		$estadoEmail = true;

		$estadoEmail = $this->enviarCorreo(['idCotizacion' => $insert['id'], 'to' => []]); // Si se desea enviar correo indicarlo aqui.
		//Verificamos si es necesario enviar a compras para cotizar con el proveedor

		$necesitaCotizacionIntera = false;
		foreach ($post['cotizacionInternaForm'] as $cotizacionInterna) {
			if ($cotizacionInterna == 1) {
				$necesitaCotizacionIntera = true;
				break;
			}
		}

		$estadoCotizacion = ($necesitaCotizacionIntera) ? ESTADO_ENVIADO_COMPRAS : ESTADO_CONFIRMADO_COMPRAS;
		$data['tabla'] = 'compras.cotizacion';
		$data['update'] = [
			'idCotizacionEstado' => $estadoCotizacion,
		];
		$data['where'] = [
			'idCotizacion' => $post['idCotizacion'],
		];

		$this->model->actualizarCotizacion($data);

		$insertCotizacionHistorico = [
			'idCotizacionEstado' => $estadoCotizacion,
			'idCotizacion' => $post['idCotizacion'],
			'idUsuarioReg' => $this->idUsuario,
			'estado' => true,
		];
		$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);





		if (!$insert['estado'] || !$insertDetalle || !$estadoEmail) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		}

		respuesta:
		echo json_encode($result);
	}
}
