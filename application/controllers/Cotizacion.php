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
		$this->database = 'ImpactBussiness.compras.cotizacion';
		$this->databaseAuditoria = 'ImpactBussiness.compras.cotizacionEstadoHistorico';
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
			'assets/custom/js/cotizacion',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['data']['usuario'] = $this->db->get_where('sistema.usuario')->result_array();
		$config['data']['estado'] = $this->db->get_where('compras.cotizacionEstado')->result_array();
		foreach ($config['data']['usuario'] as $k => $v) {
			$config['data']['usuario'][$k]['user_t'] = $v['nombres'] . ' ' . $v['apePaterno'] . ' ' . $v['apeMaterno'];
		}
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

	public function formularioIndicarGR()
	{
		$result = $this->result;
		$post = $this->input->post();

		$dataParaVista['idCotizacion'] = $post['idCotizacion'];
		$data['datosCot'] = $this->model->obtenerInformacionCotizacionGR_ORDENCOMPRA($dataParaVista['idCotizacion'])->result_array();

		$data['datosCotGR'] = $this->model->obtenerCotizacionGR($dataParaVista['idCotizacion'])->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Indicar Datos';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioIndicarGR", $data, true);

		echo json_encode($result);
	}

	public function registrarGR()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idCotizacion = $post['idCotizacion'];
		$datos = [
			'codOrdenCompra' => $post['codigo_oc'],
			'montoOrdenCompra' => $post['monto_oc'],
			'fechaClienteOC' => $post['fechaClienteOC'],
			'motivoAprobacion' => $post['motivo']
		];
		$this->db->update('compras.cotizacion', $datos, ['idCotizacion' => $idCotizacion]);

		$insertGR = [
			'numeroGR' => checkAndConvertToArray($post['numeroGR']),
			'fechaGR' => checkAndConvertToArray($post['fechaGR']),
			'idCotizacion' => $post['idCotizacion']
		];
		$data = [];
		foreach ($insertGR['numeroGR'] as $key => $numeroGR) {
			$fechaGR = $insertGR['fechaGR'][$key];
			$idCotizacion = $insertGR['idCotizacion'];

			if (!empty($numeroGR)) {
				$data[] = [
					'numeroGR' => $numeroGR,
					'fechaGR' => $fechaGR,
					'idCotizacion' => $idCotizacion
				];
			}
		}
		if (!empty($data)) {
			$this->db->insert_batch('compras.cotizacionGr', $data);
		}
		//$idSincerado = $this->db->insert_id();
		$result['result'] = 1;
		echo json_encode($result);
	}
	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		if (isset($_SESSION['item'])) {
			$item = $_SESSION['item'];

			$post['id'] = $item;
			$datoDeseado = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
			unset($post['id']);
			$post['idDiferente'] = $item;
			$datoRestante = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
			$dataParaVista = array_merge($datoDeseado, $datoRestante);
		} else {
			$dataParaVista = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
		}
		//echo $this->db->last_query();exit();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Cotizacion/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentCotizacion']['datatable'] = 'tb-cotizacion';
		$result['data']['views']['idContentCotizacion']['html'] = $html;
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
		$jsonData = json_decode($this->input->post('jsonData'), true);

		$dataParaVista = [];

		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();

		$itemServicio = $this->model_item->obtenerItemServicio();
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
		$result['data']['feeCuenta'] = $this->model->obtenerFeeCuenta($jsonData)['query']->result_array();

		echo json_encode($result);
	}

	public function formularioCompletarDatos()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

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
			$dataParaVista['cabecera']['fechaSustento'] = $row['fechaSustento'];
			$dataParaVista['cabecera']['fechaEnvioFinanzas'] = $row['fechaEnvioFinanzas'];
			$dataParaVista['cabecera']['aprovador'] = $row['aprovador'];
			$dataParaVista['cabecera']['montoSincerado'] = $row['montoSincerado'];
		}
		$dataParaVista['linea'] = $this->model->obtenerDetalleLinea($post)['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Visualizar Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioCompletarDatos", $dataParaVista, true);

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
			if ($post['tipoRegistro'] == ESTADO_ENVIADO_CLIENTE) {
				$this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => $toOperaciones]);
			}
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
					'fechaClienteOC' => !empty($post['fechaClienteOC']) ? $post['fechaClienteOC'] : NULL,
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
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						// 'idTipoArchivo' => TIPO_ORDEN_COMPRA,
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						// 'extension' => $tipoArchivo[1],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
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

				$this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => $toOperaciones]);
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
			'feePersonal' => isset($post['feeFormPersonal']) ? $post['feeFormPersonal'] : 0,
			'total' => $post['totalForm'],
			'total_fee' => $post['totalFormFee'],
			'total_fee_igv' => $post['totalFormFeeIgv'],
			'idPrioridad' => verificarEmpty($post['prioridadForm'], 4),
			'motivo' => $post['motivoForm'],
			'idOrdenServicio' => $post['ordenServicioSelect'],
			'comentario' => $post['comentarioForm'],
			'diasValidez' => $post['diasValidez'],
			'idCotizacionEstado' => ESTADO_REGISTRADO,
			'idUsuarioReg' => $this->idUsuario,
			'idTipoServicioCotizacion' => $post['tipoServicioCotizacion'],
			'mostrarPrecio' => !empty($post['flagMostrarPrecio']) ? $post['flagMostrarPrecio'] : 0,
			'idTipoMoneda' => $post['tipoMoneda'],
			'feeTarjetaVales' => $post['feeForm3'],

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
		$idCotizacion = $data['idCotizacion'];
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
		// $post['tituloCoti'] = checkAndConvertToArray($post['tituloCoti']);
		$post['unidadMedida'] = checkAndConvertToArray($post['unidadMedida']);
		$post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
		$post['caracteristicasCompras'] = checkAndConvertToArray($post['caracteristicasCompras']);
		$post['caracteristicasProveedor'] = checkAndConvertToArray($post['caracteristicasProveedor']);
		$post['costoForm'] = checkAndConvertToArray($post['costoForm']);
		$post['fee1'] = checkAndConvertToArray($post['fee1']);
		$post['fee2'] = checkAndConvertToArray($post['fee2']);
		$post['fee1Item'] = checkAndConvertToArray($post['fee1Item']);
		$post['fee2Item'] = checkAndConvertToArray($post['fee2Item']);
		$post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
		$post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
		$post['gapForm'] = checkAndConvertToArray($post['gapForm']);
		$post['precioForm'] = checkAndConvertToArray($post['precioForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		$post['cotizacionInternaForm'] = checkAndConvertToArray($post['cotizacionInternaForm']);
		$post['flagCuenta'] = checkAndConvertToArray($post['flagCuenta']);
		$post['flagRedondearForm'] = checkAndConvertToArray($post['flagRedondearForm']);
		$post['itemTextoPdf'] = checkAndConvertToArray($post['itemTextoPdf']);
		$post['cantidadPDV'] = checkAndConvertToArray($post['cantidadPDV']);
		$post['flagPackingSolicitado'] = checkAndConvertToArray($post['flagPackingSolicitado']);
		$post['costoPacking'] = checkAndConvertToArray($post['costoPacking']);
		$post['flagMovilidadSolicitado'] = checkAndConvertToArray($post['flagMovilidadSolicitado']);
		$post['costoMovilidad'] = checkAndConvertToArray($post['costoMovilidad']);
		$post['flagPersonalSolicitado'] = checkAndConvertToArray($post['flagPersonalSolicitado']);
		$post['costoPersonal'] = checkAndConvertToArray($post['costoPersonal']);
		$post['flagMostrarDetalle'] = checkAndConvertToArray($post['flagMostrarDetalle']);
		$post['flagOtrosPuntos'] = checkAndConvertToArray($post['flagOtrosPuntos']);
		$post['sueldo_personal'] = checkAndConvertToArray($post['sueldo_personal']);
		$post['asignacion_familiar_personal'] = checkAndConvertToArray($post['asignacion_familiar_personal']);
		$post['movilidad_personal'] = checkAndConvertToArray($post['movilidad_personal']);
		$post['refrigerio_personal'] = checkAndConvertToArray($post['refrigerio_personal']);
		$post['essalud_personal'] = checkAndConvertToArray($post['essalud_personal']);
		$post['cts_personal'] = checkAndConvertToArray($post['cts_personal']);
		$post['vacaciones_personal'] = checkAndConvertToArray($post['vacaciones_personal']);
		$post['gratificacion_personal'] = checkAndConvertToArray($post['gratificacion_personal']);
		$post['seguro_vida_personal'] = checkAndConvertToArray($post['seguro_vida_personal']);
		$post['total_adicionales'] = checkAndConvertToArray($post['total_adicionales']);
		$post['tipoTarjVales'] = checkAndConvertToArray($post['tipoTarjVales']);

		$post['verDetallePdf'] = checkAndConvertToArray($post['verDetallePdf']);
		if (isset($post['cargo_personal'])) $post['cargo_personal'] = checkAndConvertToArray($post['cargo_personal']);

		$post['cantidad_personal'] = checkAndConvertToArray($post['cantidad_personal']);
		$post['incentivo_personal'] = checkAndConvertToArray($post['incentivo_personal']);
		$post['mes_inicio_personal'] = checkAndConvertToArray($post['mes_inicio_personal']);
		$post['mes_fin_personal'] = checkAndConvertToArray($post['mes_fin_personal']);

		$n = 0; // Cantidad de items en la tabla de distribución.
		$cantRutViaj = 0; // Cantidad de items en la tabla de Rutas Viajeras.

		if (isset($post['idTipoServicio'])) $post['idTipoServicio'] = checkAndConvertToArray($post['idTipoServicio']);
		if (isset($post['item'])) $post['item'] = checkAndConvertToArray($post['item']);
		if (isset($post['cantidad'])) $post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		if (isset($post['costoTSCuenta'])) $post['costoTSCuenta'] = checkAndConvertToArray($post['costoTSCuenta']);
		if (isset($post['totalCuenta'])) $post['totalCuenta'] = checkAndConvertToArray($post['totalCuenta']);
		if (isset($post['idItem'])) $post['idItem'] = checkAndConvertToArray($post['idItem']);
		if (isset($post['pesoCuenta'])) $post['pesoCuenta'] = checkAndConvertToArray($post['pesoCuenta']);
		if (isset($post['idZona'])) $post['idZona'] = checkAndConvertToArray($post['idZona']);
		if (isset($post['reembarque'])) $post['reembarque'] = checkAndConvertToArray($post['reembarque']);
		if (isset($post['dias'])) $post['dias'] = checkAndConvertToArray($post['dias']);
		if (isset($post['gap'])) $post['gap'] = checkAndConvertToArray($post['gap']);
		if (isset($post['pesoVisual'])) $post['pesoVisual'] = checkAndConvertToArray($post['pesoVisual']);
		if (isset($post['costoTSVisual'])) $post['costoTSVisual'] = checkAndConvertToArray($post['costoTSVisual']);

		$enviarCorreoPacking = false;
		$tienePersonal = false;
		foreach ($post['nameItem'] as $k => $r) {
			if (!empty($r)) {
				$dataItem = [];
				$idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
				$nameItem = $post['nameItem'][$k];
				$itemsSinProveedor = [];
				// UM
				if (!is_numeric($post['unidadMedida'][$k])) {
					$getUm = $this->db->where('nombre', $post['unidadMedida'][$k])->get('compras.unidadMedida')->row_array();
					if (empty($getUm)) {
						$this->db->insert('compras.unidadMedida', ['nombre' => $post['unidadMedida'][$k]]);
						$post['unidadMedida'][$k] = $this->db->insert_id();
					} else {
						$post['unidadMedida'][$k] = $getUm['idUnidadMedida'];
					}
				}
				// Fin: UM

				if (empty($idItem)) { // si es nuevo verificamos y lo registramos
					$validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem, 'nombre' => $nameItem]);
					$item = $validacionExistencia['query']->row_array();

					if (empty($item)) {
						$dataItem['insert'] = [
							'nombre' => trim($nameItem),
							'caracteristicas' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
							'idItemTipo' => $post['tipoItemForm'][$k],
							'idUnidadMedida' => $post['unidadMedida'][$k],
						];

						$dataItem['tabla'] = 'compras.item';
						$idItem = $this->model_item->insertarItem($dataItem)['id'];
					}

					if (!empty($item)) {
						$idItem = $item['idItem'];
						$itemsSinProveedor[$idItem] = true;
					}
				}

				// if ($post['cantidadForm'][$k] > LIMITE_COMPRAS) {
				// 	$post['cotizacionInternaForm'][$k] = 1;
				// }
				if ($post['tipoItemForm'][$k] == COD_TRANSPORTE['id']) {
					$post['cotizacionInternaForm'][$k] = 0;
				}
				if ($post['tipoItemForm'][$k] == COD_DISTRIBUCION['id']) {
					$post['cotizacionInternaForm'][$k] = 0;
				}

				$data['insert'][] = [
					'idCotizacion' => $insert['id'],
					'idItem' => $idItem,
					'idItemTipo' => $post['tipoItemForm'][$k],
					'nombre' => trim($nameItem),
					'cantidad' => $post['cantidadForm'][$k],
					'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
					'fee1Por' => !empty($post['fee1'][$k]) ? $post['fee1'][$k] : NULL,
					'fee2Por' => !empty($post['fee2'][$k]) ? $post['fee2'][$k] : NULL,
					'fee1Monto' => !empty($post['fee1Item'][$k]) ? $post['fee1Item'][$k] : NULL,
					'fee2Monto' => !empty($post['fee2Item'][$k]) ? $post['fee2Item'][$k] : NULL,
					'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
					'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
					'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
					'idItemEstado' => !empty($itemsSinProveedor[$idItem]) ? 2 : $post['idEstadoItemForm'][$k],
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
					'flagAlternativo' => !empty($post['itemTextoPdf'][$k]) ? '1' : '0',
					'nombreAlternativo' => !empty($post['itemTextoPdf'][$k]) ? $post['itemTextoPdf'][$k] : NULL,
					'tituloParaOC' => !empty($post['tituloCoti'][$k]) ? $post['tituloCoti'][$k] : NULL,
					'cantPDV' => !empty($post['cantidadPDV'][$k]) ? $post['cantidadPDV'][$k] : NULL,
					'flagDetallePDV' => !empty($post['flagDetallePDV'][$k]) ? $post['flagDetallePDV'][$k] : NULL,
					// 'tipoServicio' => !empty($post['tipoServicio'][$k]) ? $post['tipoServicio'][$k] : NULL,
					'requiereOrdenCompra' => !empty($post['flagGenerarOC'][$k]) ? $post['flagGenerarOC'][$k] : 0,
					'tsCosto' => !empty($post['tsCosto'][$k]) ? $post['tsCosto'][$k] : NULL,
					// 'costoPacking' => !empty($post['costoPacking'][$k]) ? $post['costoPacking'][$k] : NULL,
					'flagPackingSolicitado' => !empty($post['flagPackingSolicitado'][$k]) ? $post['flagPackingSolicitado'][$k] : 0,
					'costoPacking' => !empty($post['costoPacking'][$k]) ? $post['costoPacking'][$k] : 0,
					'flagMovilidadSolicitado' => !empty($post['flagMovilidadSolicitado'][$k]) ? $post['flagMovilidadSolicitado'][$k] : 0,
					'costoMovilidad' => !empty($post['costoMovilidad'][$k]) ? $post['costoMovilidad'][$k] : 0,
					'flagPersonalSolicitado' => !empty($post['flagPersonalSolicitado'][$k]) ? $post['flagPersonalSolicitado'][$k] : 0,
					'costoPersonal' => !empty($post['costoPersonal'][$k]) ? $post['costoPersonal'][$k] : 0,
					'flagMostrarDetalle' => !empty($post['flagMostrarDetalle'][$k]) ? $post['flagMostrarDetalle'][$k] : 0,
					'sueldo' => !empty($post['sueldo_personal'][$k]) ? $post['sueldo_personal'][$k] : 0,
					'asignacionFamiliar' => !empty($post['asignacion_familiar_personal'][$k]) ? $post['asignacion_familiar_personal'][$k] : 0,
					'movilidad' => !empty($post['movilidad_personal'][$k]) ? $post['movilidad_personal'][$k] : 0,
					'refrigerio' => !empty($post['refrigerio_personal'][$k]) ? $post['refrigerio_personal'][$k] : 0,
					'incentivos' => !empty($post['incentivo_personal'][$k]) ? $post['incentivo_personal'][$k] : 0,
					'essalud' => !empty($post['essalud_personal'][$k]) ? $post['essalud_personal'][$k] : 0,
					'cts' => !empty($post['cts_personal'][$k]) ? $post['cts_personal'][$k] : 0,
					'vacaciones' => !empty($post['vacaciones_personal'][$k]) ? $post['vacaciones_personal'][$k] : 0,
					'gratificacion' => !empty($post['gratificacion_personal'][$k]) ? $post['gratificacion_personal'][$k] : 0,
					'segurovidaley' => !empty($post['seguro_vida_personal'][$k]) ? $post['seguro_vida_personal'][$k] : 0,
					'adicionales' => !empty($post['total_adicionales'][$k]) ? $post['total_adicionales'][$k] : 0,
					'idCargo' => !empty($post['cargo_personal'][$k]) ? $post['cargo_personal'][$k] : 0,
					'cantidad_personal' => !empty($post['cantidad_personal'][$k]) ? $post['cantidad_personal'][$k] : 0,
					'mesInicio' => !empty($post['mes_inicio_personal'][$k]) ? $post['mes_inicio_personal'][$k] : 0,
					'mesFin' => !empty($post['mesFin'][$k]) ? $post['mesFin'][$k] : 0,
					'idTipo_TarjetasVales' => !empty($post['tipoTarjVales'][$k]) ? $post['tipoTarjVales'][$k] : NULL,
					'flagDetalleTarjetasVales' => !empty($post['verDetallePdf'][$k]) ? $post['verDetallePdf'][$k] : NULL,
				];

				if ($post['flagPackingSolicitado'][$k] == '1') {
					$enviarCorreoPacking = true;
				}
				switch ($post['tipoItemForm'][$k]) {
					case COD_TRANSPORTE['id']:
						$data['subDetalle'][$k] = getDataRefactorizada([
							'cod_departamento' => $post["departamentoTransporte[$k]"],
							'cod_provincia' => $post["provinciaTransporte[$k]"],
							'cod_distrito' => $post["distritoTransporte[$k]"],
							'idTipoServicioUbigeo' => $post["tipoTransporte[$k]"],
							'costo' => $post["costoClienteTransporte[$k]"],
							'costoVisual' => $post["costoVisualTransporte[$k]"],
							'porcentajeParaCosto' => $post["porcAdicionalTransporte[$k]"],
							'dias' => $post["diasTransporte[$k]"],
							'cantidad' => $post["cantidadTransporte[$k]"],
							'tipo_movil' => $post["tipoMovilTransporte[$k]"],
						]);
						break;
					case COD_RUTAS_VIAJERAS['id']:
						if (!isset($post['cantidadItemsRutasViajeras'])) {
							$result['result'] = 0;
							$result['msg']['title'] = 'Alerta!';
							$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Debe indicar items con su cantidad y costo en la cotización de distribución']);
							goto respuesta;
						}
						$post['cantidadItemsRutasViajeras'] = checkAndConvertToArray($post['cantidadItemsRutasViajeras']);

						$nro = isset($post['cantidadItemsRutasViajeras'][$k]) ? $post['cantidadItemsRutasViajeras'][$k] : 0;
						$cantidad = intval($nro);
						$data['subDetalle'][$k] = [];
						for ($it = 0; $it < $cantidad; $it++) {
							$name = 'Ruta Viajera de ' . checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj];
							$name .= ' a ';
							$destinos = explode('-', checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]);
							if (count($destinos) == 1) $name .= $destinos[0];
							else {
								foreach ($destinos as $kr => $ruta) {
									if ($kr != 0) $name .= ' / ' . $destinos[$kr - 1] . ' a ';
									$name .= $destinos[$kr];
								}
							}
							$data['subDetalle'][$k][] = [
								'nombre' => $name,
								'origen' => strval(checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj]),
								'destino' => strval(checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]),
								'responsable' => strval(checkAndConvertToArray($post['subDetRutViajResponsable'])[$cantRutViaj]),
								'cargo' => strval(checkAndConvertToArray($post['subDetRutViajCargo'])[$cantRutViaj]),
								'dni' => strval(checkAndConvertToArray($post['subDetRutViajDni'])[$cantRutViaj]),
								'razonSocial' => strval(checkAndConvertToArray($post['subDetRutViajRazonSocial'])[$cantRutViaj]), //
								'frecuencia' => strval(checkAndConvertToArray($post['subDetRutViajFrecuencia'])[$cantRutViaj]), //
								'dias' => strval(checkAndConvertToArray($post['subDetRutViajDias'])[$cantRutViaj]), //
								'costoAereo' => strval(checkAndConvertToArray($post['subDetRutViajCostoAereo'])[$cantRutViaj]),
								'costoTransporte' => strval(checkAndConvertToArray($post['subDetRutViajCostoTransporte'])[$cantRutViaj]),
								'costoMovilidadInterna' => strval(checkAndConvertToArray($post['subDetRutViajCostoMovilidadInterna'])[$cantRutViaj]),
								'costoViaticos' => strval(checkAndConvertToArray($post['subDetRutViajCostoViaticos'])[$cantRutViaj]),
								'costoAlojamiento' => strval(checkAndConvertToArray($post['subDetRutViajCostoAlojamiento'])[$cantRutViaj]),
								'cantidadViajes' => strval(checkAndConvertToArray($post['subDetRutViajCantidadViajes'])[$cantRutViaj]),
								'costoVisual' => strval(checkAndConvertToArray($post['subDetRutViajCostoVisual'])[$cantRutViaj]), //
								'gap' => strval(checkAndConvertToArray($post['subDetRutViajGap'])[$cantRutViaj]), //
								'costo' => strval(checkAndConvertToArray($post['subDetRutViajCosto'])[$cantRutViaj]), //
								'cantidadReal' => strval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]), //
								'cantidad' => strval(round(floatval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]) / 12, 2)), //
								'tipo_movil' => strval(checkAndConvertToArray($post['subDetRutViajTipoMovil'])[$cantRutViaj]),
								'subtotal' => strval(checkAndConvertToArray($post['subDetRutViajSubTotal'])[$cantRutViaj]),
							];
							$cantRutViaj++;
						}
						break;
					case COD_DISTRIBUCION['id']:
						if (!isset($post['cantidadDatosTabla'])) {
							$result['result'] = 0;
							$result['msg']['title'] = 'Alerta!';
							$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Debe indicar items con su cantidad y costo en la cotización de distribución']);
							goto respuesta;
						}
						$post['cantidadDatosTabla'] = checkAndConvertToArray($post['cantidadDatosTabla']);

						$nro = isset($post['cantidadDatosTabla'][$k]) ? $post['cantidadDatosTabla'][$k] : 0;
						$cantidad = intval($nro);
						$data['subDetalle'][$k] = [];
						$post['idItem'] = checkAndConvertToArray($post['idItem']);
						for ($it = 0; $it < $cantidad; $it++) {
							$data['subDetalle'][$k][] = [
								'tipoServicio' => strval($post['idTipoServicio'][$n]), // idTipoServicio ... el modal esta con ese KEY por eso le pongo asi.
								'nombre' => strval($post['item'][$n]),
								'cantidad' => strval($post['cantidad'][$n]),
								'costo' => strval($post['costoTSCuenta'][$n]),
								'subtotal' => strval($post['totalCuenta'][$n]),
								'idItemLogistica' => strval($post['idItem'][$n]), // idItem ... el modal esta con ese KEY por eso le pongo asi.
								'peso' => strval($post['pesoCuenta'][$n]),
								'idZona' => strval($post['idZona'][$n]),
								'reembarque' => strval($post['reembarque'][$n]),
								'dias' => strval($post['dias'][$n]),
								'gap' => strval($post['gap'][$n]),
								'pesoVisual' => strval($post['pesoVisual'][$n]),
								'costoVisual' => strval($post['costoTSVisual'][$n]),
								'flagItemInterno' => 0, // FALTA LA OPCION DE AGREGAR ITEM DE COMPRAS
								'flagOtrosPuntos' => !empty($post['flagOtrosPuntos'][$k]) ? $post['flagOtrosPuntos'][$k] : 0
							];
							$n++;
						}
						break;
					case COD_PERSONAL['id']:
						$tienePersonal = true;
						$data['subDetalle'][$k] = getDataRefactorizada([
							'idConcepto' => $post["idCampoPersonal[$k]"],
							'flagConcepto' => $post["incluirCampoPersonal[$k]"],
							'cantidad' => $post["cantidadCampoPersonal[$k]"],
							'frecuencia' => $post["frecuenciaCampoPersonal[$k]"],
							'costo' => $post["costoCampoPersonal[$k]"],
							'subtotal' => $post["costoTotalCampoPersonal[$k]"]
						]);
						break;
						// Revisar que el sueldo se guarde....
					case COD_TEXTILES['id']:
						$search_textil = [];
						foreach (checkAndConvertToArray($post["cantidadTextil[$k]"]) as $ixT => $cantT) {
							if (empty($cantT)) {
								$result['result'] = 0;
								$result['msg']['title'] = 'Alerta!';
								$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar la cantidad de textiles para el item <b>' . $r . '</b>']);
								goto respuesta;
							}
							$txt_talla = checkAndConvertToArray($post["tallaSubItem[$k]"])[$ixT];
							$txt_tela = checkAndConvertToArray($post["telaSubItem[$k]"])[$ixT];
							$txt_color = checkAndConvertToArray($post["colorSubItem[$k]"])[$ixT];
							$txt_genero = checkAndConvertToArray($post["generoSubItem[$k]"])[$ixT];

							if (empty($txt_talla) && empty($txt_tela) && empty($txt_color) && empty($txt_genero)) {
								$result['result'] = 0;
								$result['msg']['title'] = 'Alerta!';
								$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'El sub-detalle de <b>' . $r . '</b> solo tiene indicado el dato de cantidad, por favor registre el item como <b>Articulo</b>']);
								goto respuesta;
							}

							if (empty($txt_talla)) $txt_talla = '_data_';
							if (empty($txt_tela)) $txt_tela = '_data_';
							if (empty($txt_color)) $txt_color = '_data_';
							if (empty($txt_genero)) $txt_genero = '_data_';

							if (!isset($search_textil[$txt_talla][$txt_tela][$txt_color][$txt_genero])) $search_textil[$txt_talla][$txt_tela][$txt_color][$txt_genero] = 1;
							else {
								$result['result'] = 0;
								$result['msg']['title'] = 'Alerta!';
								$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Se repite el sub-detalle para el item <b>' . json_encode($r) . '</b>, debe asignar una diferencia con los datos de talla, tela, color, genero']);
								goto respuesta;
							}
						}

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
							'nombre' => $post["descripcionSubItemTarjVal[$k]"],
							'cantidad' => $post["cantidadSubItemTarjVal[$k]"],
							'costo' => $post["montoSubItemTarjVal[$k]"],
							'subtotal' => floatval($post["cantidadSubItemTarjVal[$k]"]) * floatval($post["montoSubItemTarjVal[$k]"])
						]);
						break;
					case COD_SERVICIO_GENERAL['id']:
						$data['subDetalle'][$k] = [];
						if (!empty($post["descripcionSubItemServicioGeneral[$k]"])) {
							foreach ($post["descripcionSubItemServicioGeneral[$k]"] as $ksg => $vsg) {
								$data['subDetalle'][$k][] = [
									'nombre' => $vsg,
									'cantidad' => $post["cantidadSubItemServicioGeneral[$k]"][$ksg],
									'costo' => $post["montoSubItemServicioGeneral[$k]"][$ksg],
									'subtotal' => floatval($post["cantidadSubItemServicioGeneral[$k]"][$ksg]) * $post["montoSubItemServicioGeneral[$k]"][$ksg]
								];
							}
						}
						break;
					case COD_CONCURSO['id']:
						$data['subDetalle'][$k] = [];
						$post["descripcionSubItemConcurso[$k]"] = checkAndConvertToArray($post["descripcionSubItemConcurso[$k]"]);
						$post["cantidadSubItemConcurso[$k]"] = checkAndConvertToArray($post["cantidadSubItemConcurso[$k]"]);
						$post["montoSubItemConcurso[$k]"] = checkAndConvertToArray($post["montoSubItemConcurso[$k]"]);
						$post["porcentajeSubItemConcurso[$k]"] = checkAndConvertToArray($post["porcentajeSubItemConcurso[$k]"]);
						foreach ($post["descripcionSubItemConcurso[$k]"] as $kti => $vti) {
							$data['subDetalle'][$k][] = [
								'nombre' => $post["descripcionSubItemConcurso[$k]"][$kti],
								'cantidad' => $post["cantidadSubItemConcurso[$k]"][$kti],
								'costo' => $post["montoSubItemConcurso[$k]"][$kti],
								'porcentajeParaCosto' => $post["porcentajeSubItemConcurso[$k]"][$kti],
								'subtotal' => floatval($post["cantidadSubItemConcurso[$k]"][$kti]) * floatval($post["montoSubItemConcurso[$k]"][$kti] * (1 + (floatval(verificarEmpty($post["porcentajeSubItemConcurso[$k]"][$kti], 2)) / 100)))
							];
						}
						// $data['subDetalle'][$k] = getDataRefactorizada([]);
						break;

					case COD_PAGOS_FARMACIAS['id']:
						$data['subDetalle'][$k] = [];
						$post["descripcionSubItemPagosFarmacias[$k]"] = checkAndConvertToArray($post["descripcionSubItemPagosFarmacias[$k]"]);
						$post["cantidadSubItemPagosFarmacias[$k]"] = checkAndConvertToArray($post["cantidadSubItemPagosFarmacias[$k]"]);
						$post["montoSubItemPagosFarmacias[$k]"] = checkAndConvertToArray($post["montoSubItemPagosFarmacias[$k]"]);
						foreach ($post["descripcionSubItemPagosFarmacias[$k]"] as $kti => $vti) {
							$data['subDetalle'][$k][] = [
								'nombre' => $post["descripcionSubItemPagosFarmacias[$k]"][$kti],
								'cantidad' => $post["cantidadSubItemPagosFarmacias[$k]"][$kti],
								'costo' => $post["montoSubItemPagosFarmacias[$k]"][$kti],
								'subtotal' => floatval($post["cantidadSubItemPagosFarmacias[$k]"][$kti]) * floatval($post["montoSubItemPagosFarmacias[$k]"][$kti])
							];
						}
						break;
					default:
						$data['subDetalle'][$k] = [];
						break;
				}

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
						'genero' => isset($subItem['genero']) ? $subItem['genero'] : NULL,
						'monto' => !empty($subItem['monto']) ? $subItem['monto'] : NULL,
						// 'subTotal' => !empty($subItem['costo']) && !empty($subItem['cantidad']) ? ($subItem['costo'] * $subItem['cantidad']) : NULL,
						'costoDistribucion' => !empty($post['costoDistribucion']) ? $post['costoDistribucion'] : NULL, //$post
						'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
						'idItem' => !empty($subItem['idItemLogistica']) ? $subItem['idItemLogistica'] : NULL,
						'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
						'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
						'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
						'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : 0,
						'peso' => !empty($subItem['peso']) ? $subItem['peso'] : 0,
						'idZona' => !empty($subItem['idZona']) ? $subItem['idZona'] : NULL,
						'reembarque' => !empty($subItem['reembarque']) ? $subItem['reembarque'] : NULL,
						'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
						'gap' => !empty($subItem['gap']) ? $subItem['gap'] : NULL,
						'pesoVisual' => !empty($subItem['pesoVisual']) ? $subItem['pesoVisual'] : NULL,
						'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
						'porcentajeParaCosto' => !empty($subItem['porcentajeParaCosto']) ? $subItem['porcentajeParaCosto'] : NULL,
						'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : 0,
						'flagOtrosPuntos' => !empty($subItem['flagOtrosPuntos']) ? $subItem['flagOtrosPuntos'] : 0,
						'cod_departamento' => !empty($subItem['cod_departamento']) ? $subItem['cod_departamento'] : 0,
						'cod_provincia' => !empty($subItem['cod_provincia']) ? $subItem['cod_provincia'] : 0,
						'cod_distrito' => !empty($subItem['cod_distrito']) ? $subItem['cod_distrito'] : 0,
						'idTipoServicioUbigeo' => !empty($subItem['idTipoServicioUbigeo']) ? $subItem['idTipoServicioUbigeo'] : 0,
						'idConcepto' => !empty($subItem['idConcepto']) ? $subItem['idConcepto'] : NULL,
						'flagConcepto' => !empty($subItem['flagConcepto']) ? $subItem['flagConcepto'] : NULL,
						'frecuencia' => !empty($subItem['frecuencia']) ? $subItem['frecuencia'] : NULL,
						'subtotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : NULL,
						'tipo_movil' => !empty($subItem['tipo_movil']) ? $subItem['tipo_movil'] : NULL,
						// * De rutas Viajeras
						'razonSocial' => !empty($subItem['razonSocial']) ? $subItem['razonSocial'] : NULL,
						'origen' => !empty($subItem['origen']) ? $subItem['origen'] : NULL,
						'destino' => !empty($subItem['destino']) ? $subItem['destino'] : NULL,
						'responsable' => !empty($subItem['responsable']) ? $subItem['responsable'] : NULL,
						'cargo' => !empty($subItem['cargo']) ? $subItem['cargo'] : NULL,
						'dni' => !empty($subItem['dni']) ? $subItem['dni'] : NULL,
						'costoAereo' => !empty($subItem['costoAereo']) ? $subItem['costoAereo'] : NULL,
						'costoTransporte' => !empty($subItem['costoTransporte']) ? $subItem['costoTransporte'] : NULL,
						'costoMovilidadInterna' => !empty($subItem['costoMovilidadInterna']) ? $subItem['costoMovilidadInterna'] : NULL,
						'costoViaticos' => !empty($subItem['costoViaticos']) ? $subItem['costoViaticos'] : NULL,
						'costoAlojamiento' => !empty($subItem['costoAlojamiento']) ? $subItem['costoAlojamiento'] : NULL,
						'cantidadViajes' => !empty($subItem['cantidadViajes']) ? $subItem['cantidadViajes'] : NULL,
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

				// $post['cantidadDeUbigeos'] = checkAndConvertToArray($post['cantidadDeUbigeos']);
				$post['departamentoPDV'] = checkAndConvertToArray($post['departamentoPDV']);
				$post['provinciaPDV'] = checkAndConvertToArray($post['provinciaPDV']);
				$post['distritoPDV'] = checkAndConvertToArray($post['distritoPDV']);
				$post['paradasPDV'] = checkAndConvertToArray($post['paradasPDV']);
			}
		}
		if (!empty($data)) {
			$data['tabla'] = 'compras.cotizacionDetalle';
			$insertDetalle = $this->model->insertarCotizacionDetalle($data);
		}
		$data = [];

		if ($enviarCorreoPacking) {
			$this->enviarCorreoPacking(['idCotizacion' => $idCotizacion, 'to' => ['eder.alata@visualimpact.com.pe']]);
		}

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
			foreach (checkAndConvertToArray($post['cotizacionInternaForm']) as $cotizacionInterna) {
				if ($cotizacionInterna == '1') {
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

		if (!$insert['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');

			if ($tienePersonal) {
				$dpv['otros'] = $this->db->where('idItemTipo !=', COD_PERSONAL['id'])->get_where('compras.cotizacionDetalle', ['idCotizacion' => $insert['id']])->result_array();

				if (!empty($dpv['otros'])) {
					$result['result'] = 2;
					$result['data']['idCotizacion'] = $insert['id'];

					$dpv['personal'] = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacion' => $insert['id'], 'idItemTipo' => COD_PERSONAL['id']])->result_array();
					$result['msg']['content'] = $this->load->view('modulos/Cotizacion/formularioParaPersonal', $dpv, true);
				}
			}
			$this->db->trans_complete();
		}

		respuesta:
		echo json_encode($result);
	}
	public function actualizarCotizacion_personal()
	{
		$result = $this->result;
		$this->db->trans_start();
		$post = json_decode($this->input->post('data'), true);

		$post['idCotizacionDetallePersonal'] = checkAndConvertToArray($post['idCotizacionDetallePersonal']);
		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);

		$dataParaActualizar = [];
		foreach ($post['idCotizacionDetallePersonal'] as $k => $v) {
			$dataParaActualizar[] = [
				'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
				'idCotizacionDetallePersonal' => $v
			];
		}
		if (!empty($dataParaActualizar)) $this->db->update_batch('compras.cotizacionDetalle', $dataParaActualizar, 'idCotizacionDetalle');

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		$this->db->trans_complete();
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
	public function enviarCorreoPacking($params)
	{
		$sendMail = [];

		/*
		$config = array(
			'protocol' => 'smtp',
			// 'smtp_host' => 'ssl://smtp.googlemail.com',
			// 'smtp_port' => 465,
			'smtp_host' => 'aspmx.l.google.com',
			'smtp_port' => '25',
			'smtp_user' => 'teamsystem@visualimpact.com.pe',
			'smtp_pass' => '#nVi=0sN0ti$',
			'mailtype' => 'html'
		);

		$this->load->library('email', $config);
		$this->email->clear(true);
		$this->email->set_newline("\r\n");
		*/
		$data = !empty($params['data']) ? $params['data'] : [];
		$dataParaVista = [];
		// $cc = !empty($params['cc']) ? $params['cc'] : [];
		$sendMail['cc'] = !empty($params['cc']) ? $params['cc'] : [];

		// $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
		// $this->email->to($params['to']);
		$sendMail['to'] = $params['to'];
		// $this->email->cc($cc);

		$dataParaVista['link'] = base_url() . index_page() . 'Cotizacion/RegistrarPesos/' . $params['idCotizacion'];

		$bcc = array(
			'eder.alata@visualimpact.com.pe',
		);
		// $this->email->bcc($bcc);
		$sendMail['bcc'] = $bcc;

		// $this->email->subject('IMPACTBUSSINESS - INDICAR PESOS EN COTIZACION');
		$sendMail['asunto'] = 'IMPACTBUSSINESS - INDICAR PESOS EN COTIZACION';
		$html = $dataParaVista['link'];
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => $dataParaVista['link']], true);
		// $this->email->message($correo);
		$sendMail['contenido'] = $correo;

		// $estadoEmail = $this->email->send();
		$estadoEmail = email($sendMail);
		if (!$estadoEmail) {

			$mensaje = $estadoEmail;
		}

		return $estadoEmail;
	}
	public function enviarCorreo($params = [])
	{
		$email = [];

		$data = [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$email['to'] = $params['to'];
		$email['cc'] = $cc;

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

		$email['asunto'] = 'IMPACTBUSSINESS - NUEVA COTIZACION GENERADA';

		$html = $this->load->view("modulos/Cotizacion/correo/informacionProveedor", $dataParaVista, true);
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Cotizacion'], true);

		$email['contenido'] = $correo;

		$estadoEmail = email($email);

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
			$dataParaVista['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'idTipoArchivo' => TIPO_IMAGEN /*, 'anexo' => true */])['query']->result_array();
			// $dataParaVista['imagenDeItem'] = $this->model->obtenerImagenesDeCotizacion(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
			$data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();
			$dataArchivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion])['query']->result_array();
			$zonas = $this->db->where('estado', 1)->get('General.dbo.ubigeo')->result_array();
			foreach ($zonas as $k => $v) {
				$dataParaVista['zonas'][$v['cod_departamento']][$v['cod_provincia']] = $v;
			}
			$dataParaVista['detalleDistribucion'] = [];
			$dataParaVista['detalleSubT'] = [];
			if (!empty($data)) $dataParaVista['cabecera']['incluyeTransporte'] = false;
			if (!empty($data)) $dataParaVista['cabecera']['incluyeTarjVales'] = false;
			foreach ($data as $key => $row) {
				$dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
				$dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
				$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
				$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
				$dataParaVista['cabecera']['comentario'] = $row['comentario'];
				$dataParaVista['cabecera']['fecha'] = $row['fechaRequerida'];
				$dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
				$dataParaVista['cabecera']['fee'] = $row['fee'];
				$dataParaVista['cabecera']['feePersonal'] = $row['feePersonal'];
				$dataParaVista['cabecera']['igv'] = $row['flagIgv'];
				$dataParaVista['cabecera']['feeTarjetaVales'] = $row['feeTarjetaVales'];
				$dataParaVista['cabecera']['total'] = $total = $row['total'];
				$dataParaVista['cabecera']['total_fee'] = $row['total_fee'];
				$dataParaVista['cabecera']['total_fee_igv'] = $row['total_fee_igv'];
				$dataParaVista['cabecera']['solicitante'] = $row['solicitante'];
				$dataParaVista['cabecera']['codCotizacion'] = $row['codCotizacion'];
				$dataParaVista['cabecera']['mostrarPrecio'] = $row['mostrarPrecio'];
				$dataParaVista['cabecera']['fechaValido'] = $row['fechaValido'];
				// Para cabeceras del PDF
				if (!$dataParaVista['cabecera']['incluyeTransporte'])
					$dataParaVista['cabecera']['incluyeTransporte'] = ($row['idItemTipo'] == COD_TRANSPORTE['id']);
				if (!$dataParaVista['cabecera']['incluyePersonal'])
					$dataParaVista['cabecera']['incluyePersonal'] = ($row['idItemTipo'] == COD_PERSONAL['id']);
				if (!$dataParaVista['cabecera']['incluyeServicio'])
					$dataParaVista['cabecera']['incluyeServicio'] = ($row['idItemTipo'] == COD_SERVICIO['id']);
				if (!$dataParaVista['cabecera']['incluyeTarjVales'])
					$dataParaVista['cabecera']['incluyeTarjVales'] = ($row['idItemTipo'] == COD_TARJETAS_VALES['id']);
				$dataParaVista['detalle'][$key]['idCotizacionDetalle'] = $row['idCotizacionDetalle'];
				$dataParaVista['detalle'][$key]['idCotizacionDetallePersonal'] = $row['idCotizacionDetallePersonal'];
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
				$dataParaVista['detalle'][$key]['flagAlternativo'] = $row['flagAlternativo'];
				$dataParaVista['detalle'][$key]['nombreAlternativo'] = $row['nombreAlternativo'];
				$dataParaVista['detalle'][$key]['flagRedondear'] = $row['flagRedondear'];
				$dataParaVista['detalle'][$key]['costoPacking'] = $row['costoPacking'];
				$dataParaVista['detalle'][$key]['flagMostrarDetalle'] = $row['flagMostrarDetalle'];
				$dataParaVista['detalle'][$key]['adicionales'] = $row['adicionales'];
				$dataParaVista['detalle'][$key]['asignacionFamiliar'] = $row['asignacionFamiliar'];
				$dataParaVista['detalle'][$key]['sueldo'] = $row['sueldo'];
				$dataParaVista['detalle'][$key]['cts'] = $row['cts'];
				$dataParaVista['detalle'][$key]['movilidad'] = $row['movilidad'];
				$dataParaVista['detalle'][$key]['essalud'] = $row['essalud'];
				$dataParaVista['detalle'][$key]['vacaciones'] = $row['vacaciones'];
				$dataParaVista['detalle'][$key]['gratificacion'] = $row['gratificacion'];
				$dataParaVista['detalle'][$key]['cargo'] = $row['cargo'];
				$dataParaVista['detalle'][$key]['idCargo'] = $row['idCargo'];
				$dataParaVista['detalle'][$key]['refrigerio'] = $row['refrigerio'];
				$dataParaVista['detalle'][$key]['incentivos'] = $row['incentivos'];
				$dataParaVista['detalle'][$key]['segurovidaley'] = $row['segurovidaley'];
				$dataParaVista['detalle'][$key]['subtotalPersonal'] = $row['subtotalPersonal'];
				$dataParaVista['detalle'][$key]['totalPersonal'] = $row['totalPersonal'];
				$dataParaVista['detalle'][$key]['totalCargasSociales'] = $row['totalCargasSociales'];
				$dataParaVista['detalle'][$key]['totalSueldo'] = $row['totalSueldo'];
				$dataParaVista['detalle'][$key]['totalIncentivo'] = $row['totalIncentivo'];
				$dataParaVista['detalle'][$key]['mesInicio'] = $row['mesInicio'];
				$dataParaVista['detalle'][$key]['mesFin'] = $row['mesFin'];
				$dataParaVista['detalle'][$key]['cantidad_personal'] = $row['cantidad_personal'];
				$dataParaVista['detalle'][$key]['fee1Por'] = $row['fee1Por'];
				$dataParaVista['detalle'][$key]['fee2Por'] = $row['fee2Por'];
				$dataParaVista['detalle'][$key]['fee1Monto'] = $row['fee1Monto'];
				$dataParaVista['detalle'][$key]['fee2Monto'] = $row['fee2Monto'];
				$dataParaVista['detalle'][$key]['montoFeeTarjValCon'] = $row['montoFeeTarjValCon'];
				$dataParaVista['detalle'][$key]['montoFee'] = $row['montoFee'];
				$dataParaVista['detalle'][$key]['flagDetalleTarjetasVales'] = $row['flagDetalleTarjetasVales'];

				if ($row['idItemTipo'] != COD_DISTRIBUCION['id']) {
					$dataParaVista['detalleSub'][$row['idCotizacionDetalle']] = $this->model->obtenerCotizacionDetalleSub(['idCotizacionDetalle' => $row['idCotizacionDetalle']])->result_array();
					if ($row['idItemTipo'] == COD_TRANSPORTE['id']) {
						$dataParaVista['detalleSubT'][$row['idCotizacionDetalle']] = $this->db->where('idCotizacionDetalle', $row['idCotizacionDetalle'])->order_by('cod_departamento, cod_provincia, 1')->get('compras.cotizacionDetalleSub')->result_array();
					}
				} else {
					$cds = $this->db->order_by('idZona, idItem')->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $row['idCotizacionDetalle']])->result_array();
					foreach ($cds as $kz => $vz) {
						$zona = $this->model->getZonas(['otroAlmacen' => $vz['flagOtrosPuntos'], 'idZona' => $vz['idZona']])->row_array();
						$vz['zonaNombre'] = $zona['nombre'];
						$vz['tipoServicioNombre'] = $this->db->where('idTipoServicio', $vz['idTipoServicio'])->get('compras.tipoServicio')->row_array()['nombre'];
						$dataParaVista['detalleDistribucionZonas'][$row['idCotizacionDetalle']][$vz['idZona']][] = $vz;
					}
					foreach ($cds as $kz => $vz) {
						$vz['itemNombre'] = $this->db->where('idArticulo', $vz['idItem'])->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
						$dataParaVista['detalleDistribucionItems'][$row['idCotizacionDetalle']][$vz['idItem']][] = $vz;
					}
				}
			}

			foreach ($dataArchivos as $archivo) {
				$dataParaVista['archivos'][$archivo['idCotizacionDetalle']][] = $archivo;
			}

			// if (!empty($dataParaVista['cabecera']['fee'])) {
			// 	$dataParaVista['cabecera']['fee_prc'] = $fee = ($total * ($dataParaVista['cabecera']['fee'] / 100));
			// 	$totalFee = $dataParaVista['cabecera']['total_fee'] = ($total + $fee);
			// }

			if (!empty($dataParaVista['cabecera']['total_fee_igv'])) {
				$total = $dataParaVista['cabecera']['total'];
				if (!empty($totalFee)) {
					$dataParaVista['cabecera']['igv_prc'] = $igv = ($totalFee * $dataParaVista['cabecera']['igv'] ? IGV : 0);
					$dataParaVista['cabecera']['total_fee_igv'] = $totalFee + $igv;
				} else if (empty($totalFee) && $dataParaVista['cabecera']['igv']) {
					$dataParaVista['cabecera']['igv_prc'] = $igv = ($total * $dataParaVista['cabecera']['igv'] ? IGV : 0);
					$dataParaVista['cabecera']['total_fee_igv'] = $total + $igv;
				}
			}

			if (empty($dataParaVista['cabecera']['total_fee_igv'])) {
				$dataParaVista['cabecera']['total_fee_igv'] = $totalFee;
			}

			if (count($dataParaVista) == 0) exit();

			$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'FORMATO DE COTIZACIÓN', 'codigo' => 'COD: SIG-OPE-FOR-003'], true);
			$contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", ['solicitante' => $dataParaVista['cabecera']['solicitante']], true);
			$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/body", $dataParaVista, true);
			$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);

			if ($dataParaVista['cabecera']['incluyePersonal']) {
				$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/bodyPersonal", $dataParaVista, true);
			}
			require APPPATH . '/vendor/autoload.php';
			$orientation = '';
			if ($dataParaVista['cabecera']['incluyeServicio']) {
				$orientation = 'L';
			}
			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'setAutoTopMargin' => 'stretch',
				'orientation' => $orientation,
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
			$title = $dataParaVista['cabecera']['codCotizacion'] . ' ' . $dataParaVista['cabecera']['cotizacion'];
			$mpdf->Output("$title.pdf", 'D');
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
			'idTipoArchivo' => 1, // Orden de Compra
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

		$itemServicio = $this->model_item->obtenerItemServicio();
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
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/core/gestion',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['periodo'] = $this->model->obtenerPeriodo()->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$config['data']['tipoServicioCotizacion'] = $this->model->obtenerTipoServicioCotizacion()['query']->result_array();

		$itemServicio = $this->model_item->obtenerItemServicio();
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
		$config['data']['itemServicio'] = $data['itemServicio'];
		$config['single'] = true;
		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
		$config['data']['ordenServicio'] = $this->model->obtenerOrdenServicio()['query']->result_array();
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['tipoMoneda'] = $this->model->obtenertipoMoneda()['query']->result_array();
		$config['data']['departamento'] = $this->db->distinct()->select('tzt.cod_departamento, u.departamento')->from('compras.tarifarioZonaTransporte tzt')->join('General.dbo.ubigeo u', 'u.cod_departamento = tzt.cod_departamento', 'INNER')->where('tzt.estado', 1)->order_by('u.departamento')->get()->result_array();
		$config['data']['gapEmpresas'] = $this->model->obtenerGapEmpresas()['query']->result_array();
		$config['data']['itemLogistica'] = $this->model_item->obtenerAllItemsLogistica();
		$config['data']['costoDistribucion'] = $this->model->obtenerCostoDistribucion()['query']->row_array();
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$config['data']['proveedorDistribucion'] = $this->model_proveedor->obtenerProveedorDistribucion()->result_array();
		$config['data']['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$config['data']['listProveedor'] = $this->db->order_by('razonSocial')->get_where('compras.proveedor', ['idProveedorEstado' => 2, 'flagTarjetasVales' => 1])->result_array();
		$area = $this->db->get_where('rrhh.dbo.area', ['idEmpresa' => 2])->result_array();
		$areas = [];
		foreach ($area as $k => $v) {
			$areas[] = $v['idArea'];
		}
		$config['data']['cargoPersonal'] = $this->db->distinct()->select('nombre')->where_in('idArea', $areas)->order_by('nombre')->get('rrhh.dbo.cargoTrabajo')->result_array(); //Solo P&G
		$config['view'] = 'modulos/Cotizacion/viewFormularioRegistro';
		$this->view($config);
	}

	public function getAllProvincias()
	{
		$data = $this->db->distinct()
			->select('tzt.cod_departamento, tzt.cod_provincia as value, u.provincia as name')
			->from('compras.tarifarioZonaTransporte tzt')
			->join('General.dbo.ubigeo u', 'u.cod_provincia = tzt.cod_provincia AND u.cod_departamento = tzt.cod_departamento', 'INNER')
			->where('tzt.estado', 1)
			->order_by('u.provincia')->get()->result_array();
		$provincias = [];
		foreach ($data as $k => $v) {
			$provincias[$v['cod_departamento']][] = $v;
		}
		echo json_encode($provincias);
	}
	public function getAllDistritos()
	{
		$data = $this->db->distinct()
			->select('tzt.cod_departamento, tzt.cod_provincia, tzt.cod_distrito as value, u.provincia as name')
			->from('compras.tarifarioZonaTransporte tzt')
			->join('General.dbo.ubigeo u', 'u.cod_provincia = tzt.cod_provincia AND u.cod_departamento = tzt.cod_departamento AND u.cod_distrito = tzt.cod_distrito', 'INNER')
			->where('tzt.estado', 1)
			->order_by('u.provincia')->get()->result_array();

		$distritos = [];
		foreach ($data as $k => $v) {
			$distritos[$v['cod_departamento']][$v['cod_provincia']][] = $v;
		}
		echo json_encode($distritos);
	}
	public function getAllTiposDeTransporte()
	{
		$data = $this->db->distinct()
			->select('cod_departamento, cod_provincia, cod_distrito, tz.idTipoServicioUbigeo as value, ts.nombreAlternativo as name')
			->join('compras.tipoServicioUbigeo ts', 'ts.idTipoServicioUbigeo = tz.idTipoServicioUbigeo')
			->where('tz.estado', 1)->get('compras.tarifarioZonaTransporte tz')->result_array();

		$tarifarioZona = [];
		foreach ($data as $k => $v) {
			$tarifarioZona[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']][] = $v;
		}
		echo json_encode($tarifarioZona);
	}
	public function getAllCostoPorTipoDeTransporte()
	{
		$data = $this->db->distinct()->select('cod_departamento, cod_provincia, tz.idTipoServicioUbigeo, costoVisual, tipo_movil, costoCliente, idTarifarioZonaTransporte as value, ts.nombreAlternativo as name')
			->join('compras.tipoServicioUbigeo ts', 'ts.idTipoServicioUbigeo = tz.idTipoServicioUbigeo')->where('tz.estado', 1)->get('compras.tarifarioZonaTransporte tz')->result_array();

		$tarifarioZona = [];
		foreach ($data as $k => $v) {
			$tarifarioZona[$v['cod_departamento']][$v['cod_provincia']][$v['idTipoServicioUbigeo']][] = $v;
		}
		echo json_encode($tarifarioZona);
	}
	public function procesarTablaDatosDistribucion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'));

		$ht = $post->{'HT'}[0];
		array_pop($ht);

		$idCuenta = $post->{'cuenta'};
		$item = $post->{'item'};
		$pesoReal = $post->{'pesoReal'};
		$peso = $post->{'peso'};

		$almacen = $post->{'almacen'};

		$zonas = $this->model->getZonas(['otroAlmacen' => $almacen])->result_array();
		$zonas = refactorizarDataHT(["data" => $zonas, "value" => "nombre"]);

		$tipoServicio = $this->db->select("nombre as label")->get('compras.tipoServicio')->result_array();
		$tipoServicio = refactorizarDataHT(["data" => $tipoServicio, "value" => "label"]);

		$header = [];
		$column = [];
		$datosHt = [];
		$itOb = [];
		// DATOS
		if (!empty($ht)) {
			foreach ($ht as $k => $v) {
				$datosHt[$k]['zona'] = $v->{'zona'};
				$datosHt[$k]['dias'] = $this->model->getZonas(['otroAlmacen' => $almacen, 'nombre' => $v->{'zona'}])->row_array()['dias'];
				foreach ($item as $ki => $vi) {
					$datosHt[$k]['item' . $ki] = $v->{'item' . $ki};
					$itOb[$ki] = $this->db->where('idArticulo', $vi)->get('VisualImpact.logistica.articulo')->row_array();
				}

				$datosHt[$k]['gap'] = strval($v->{'gap'});

				$ts = $this->db->where('nombre', $v->{'tipoServicio'})->get('compras.tipoServicio')->row_array();

				$datosHt[$k]['tipoServicio'] = $v->{'tipoServicio'};
				$datosHt[$k]['reembarque'] = $v->{'reembarque'};
				$datosHt[$k]['costoTSVisual'] = $ts['costoVisual'];

				$pesoTotal = 0;
				foreach ($item as $ki => $vi) {
					$datosHt[$k]['pesoTotalVisual' . $ki] = strval(floatval($v->{'item' . $ki}) * floatval(round($itOb[$ki]['peso'], 4)));
					$pesoTotal += floatval($v->{'item' . $ki}) * floatval(round($itOb[$ki]['peso'], 4));
				}
				$datosHt[$k]['pesoTotalVisual'] = strval($pesoTotal);
				$datosHt[$k]['pesoGapVisual'] = strval((floatval($v->{'gap'}) + 100) * $pesoTotal / 100);
				$datosHt[$k]['totalFinalVisual'] = strval((floatval($datosHt[$k]['costoTSVisual']) * floatval($datosHt[$k]['pesoGapVisual'])) + floatval($datosHt[$k]['reembarque']));

				$datosHt[$k]['costoTSCuenta'] = $ts['costo'];

				$pesoTotal = 0;
				foreach ($item as $ki => $vi) {
					$datosHt[$k]['pesoTotalCuenta' . $ki] = strval(floatval($v->{'item' . $ki}) * floatval(round($itOb[$ki]['pesoCosto'], 4)));
					$pesoTotal += floatval($v->{'item' . $ki}) * floatval(round($itOb[$ki]['pesoCosto'], 4));
				}
				$datosHt[$k]['pesoTotalCuenta'] = strval($pesoTotal);
				$datosHt[$k]['pesoGapCuenta'] = strval((floatval($v->{'gap'}) + 100) * $pesoTotal / 100);
				$datosHt[$k]['totalFinalCuenta'] = strval((floatval($datosHt[$k]['costoTSCuenta']) * floatval($datosHt[$k]['pesoGapCuenta'])) + floatval($datosHt[$k]['reembarque']));
			}
		} else {
			$datosHt[0]['zona'] = null;
			$datosHt[0]['dias'] = null;
			foreach ($item as $ki => $vi) {
				if (!is_numeric($vi)) {
					$result['result'] = 0;
					$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Indicar Item Logistica']);
					goto Respuesta;
				}
				$datosHt[0]['item' . $ki] = null;
			}
			$datosHt[0]['gap'] = null;
			$datosHt[0]['tipoServicio'] = null;
			$datosHt[0]['costoTSVisual'] = null;
			foreach ($item as $ki => $vi) {
				$datosHt[0]['pesoTotalVisual' . $ki] = null;
			}
			$datosHt[0]['pesoTotalVisual'] = null;
			$datosHt[0]['pesoGapVisual'] = null;
			$datosHt[0]['totalFinalVisual'] = null;
			$datosHt[0]['costoTSCuenta'] = null;
			foreach ($item as $ki => $vi) {
				$datosHt[0]['pesoTotalCuenta' . $ki] = null;
			}
			$datosHt[0]['pesoTotalCuenta'] = null;
			$datosHt[0]['pesoGapCuenta'] = null;
			$datosHt[0]['totalFinalCuenta'] = null;
		}

		// HEADER & COLUMN & DATOS
		$header[] = 'ZONA *';
		$column[] = ['data' => 'zona', 'type' => 'myDropdown', 'placeholder' => 'Zona', 'width' => 200, 'source' => $zonas];

		$header[] = 'DIAS';
		$column[] = ['data' => 'dias', 'type' => 'numeric', 'placeholder' => 'Días', 'width' => 100, 'readOnly' => true];

		foreach ($item as $k => $v) {
			if (!is_numeric($v)) {
				$result['result'] = 0;
				$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Indicar Item Logistica']);
				goto Respuesta;
			}
			$itemNombre[$k] = $this->db->where('idArticulo', $v)->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
			$header[] = $itemNombre[$k] . ' *';
			$column[] = ['data' => 'item' . $k, 'type' => 'numeric', 'placeholder' => 'Cantidad', 'width' => 300];
		}

		$header[] = 'GAP *';
		$column[] = ['data' => 'gap', 'type' => 'numeric', 'placeholder' => 'GAP', 'width' => 100];

		$header[] = 'TIPO SERVICIO *';
		$column[] = ['data' => 'tipoServicio', 'type' => 'myDropdown', 'placeholder' => 'Tipo Servicio', 'width' => 300, 'source' => $tipoServicio];

		$header[] = 'REEMBARQUE *';
		$column[] = ['data' => 'reembarque', 'type' => 'numeric', 'placeholder' => 'REEMBARQUE', 'width' => 150];

		$header[] = 'COSTO TS VISUAL';
		$column[] = ['data' => 'costoTSVisual', 'type' => 'numeric', 'placeholder' => 'Costo TS Visual', 'width' => 200, 'readOnly' => true];

		foreach ($itemNombre as $k => $v) {
			$header[] = 'PESO VISUAL ' . $v . ' (' . $pesoReal[$k] . ')';
			$column[] = ['data' => 'pesoTotalVisual' . $k, 'type' => 'numeric', 'placeholder' => 'Peso', 'width' => 300, 'readOnly' => true];
		}
		$header[] = 'PESO TOTAL FINAL VISUAL';
		$column[] = ['data' => 'pesoTotalVisual', 'type' => 'numeric', 'placeholder' => 'Peso Total', 'width' => 200, 'readOnly' => true];

		$header[] = 'PESO GAP VISUAL';
		$column[] = ['data' => 'pesoGapVisual', 'type' => 'numeric', 'placeholder' => 'Peso Gap', 'width' => 150, 'readOnly' => true];

		$header[] = 'COSTO TOTAL VISUAL';
		$column[] = ['data' => 'totalFinalVisual', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 200, 'readOnly' => true];

		$header[] = 'COSTO TS CUENTA';
		$column[] = ['data' => 'costoTSCuenta', 'type' => 'numeric', 'placeholder' => 'Costo TS Cuenta', 'width' => 200, 'readOnly' => true];

		foreach ($itemNombre as $k => $v) {
			$header[] = 'PESO CUENTA ' . $v . ' (' . $peso[$k] . ')';
			$column[] = ['data' => 'pesoTotalCuenta' . $k, 'type' => 'numeric', 'placeholder' => 'Peso', 'width' => 300, 'readOnly' => true];
		}
		$header[] = 'PESO TOTAL FINAL CUENTA';
		$column[] = ['data' => 'pesoTotalCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Total', 'width' => 200, 'readOnly' => true];

		$header[] = 'PESO GAP CUENTA';
		$column[] = ['data' => 'pesoGapCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Gap', 'width' => 150, 'readOnly' => true];

		$header[] = 'COSTO TOTAL CUENTA';
		$column[] = ['data' => 'totalFinalCuenta', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 200, 'readOnly' => true];
		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Detalle Distribución',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Carga masiva detalle distribución";

		Respuesta:
		echo json_encode($result);
	}

	public function RegistrarPesos($id)
	{
		$config['single'] = true;
		// AGREGAR VALIDACION PARA SOLO MOSTRAR LOS PENDIENTES.
		$config['js']['script'] = array('assets/custom/js/registroPesos');
		$config['data']['cotizacion'] = $this->db->where('idCotizacion', $id)->get('compras.cotizacion')->row_array();
		$config['data']['cotizacionDetalle'] = $this->db->where('idCotizacion', $id)->get('compras.cotizacionDetalle')->result_array();

		$config['data']['zona'] = [];

		foreach ($config['data']['cotizacionDetalle'] as $v) {
			if ($v['flagPackingSolicitado'] == '1') {
				$cds = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $v['idCotizacionDetalle']])->result_array();

				foreach ($cds as $f) {
					$zona = $this->model->getZonas(['otroAlmacen' => $f['flagOtrosPuntos'], 'idZona' => $f['idZona']])->row_array();
					$config['data']['zona'][$zona['idAlmacen']] = $zona['nombre'];
				}
			}
			// $config['data']['zona'][]
			// $zz = $this->model->getZonas(['otroAlmacen' => $vz['flagOtrosPuntos'], 'idZona' => $vz['idZona']])->result_array();

		}

		$config['data']['itemPacking'] = $this->db->where('flagPacking', 1)->get('compras.item')->result_array();
		// foreach ($zz as $k => $v) {
		// 	$config['data']['zona'][$v['idAlmacen']] = $v['nombre'];
		// }
		foreach ($config['data']['cotizacionDetalle'] as $k => $v) {
			$config['data']['cotizacionDetalleSub'][$v['idCotizacionDetalle']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
		}
		$config['view'] = 'cargarPesoPacking';

		$this->view($config);
	}

	public function guardarPesoPacking()
	{
		$post = json_decode($this->input->post('data'));

		$post->{'idCotizacionDetalle'} = checkAndConvertToArray($post->{'idCotizacionDetalle'});
		$dataInsert = [];
		foreach ($post->{'idCotizacionDetalle'} as $key => $value) {
			$this->db->update('compras.cotizacionDetalleCostoPacking', ['estado' => 0], ['idCotizacionDetalle' => $value]);
			$this->db->update('compras.cotizacionDetalle', ['costoPacking' => $post->{"costoTotal[{$value}]"}], ['idCotizacionDetalle' => $value]);
			$post->{"item[{$value}]"} = checkAndConvertToArray($post->{"item[{$value}]"});
			$post->{"costo[{$value}]"} = checkAndConvertToArray($post->{"costo[{$value}]"});
			$post->{"cantidad[{$value}]"} = checkAndConvertToArray($post->{"cantidad[{$value}]"});
			foreach ($post->{"item[{$value}]"} as $k => $v) {
				$dataInsert[] = [
					'idCotizacionDetalle' => $value,
					'idItem' => $v,
					'costo' => $post->{"costo[{$value}]"}[$k],
					'cantidad' => $post->{"cantidad[{$value}]"}[$k]
				];
			}
		}
		$this->db->insert_batch('compras.cotizacionDetalleCostoPacking', $dataInsert);

		$result['result'] = 1;
		$result['msg']['title'] = 'Ok';
		echo json_encode($result);
	}
	public function procesarTablaDatosDistribucion_Pesos()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'));

		$ht = $post->{'HT'}[0];
		array_pop($ht);

		$idCuenta = $post->{'cuenta'};

		$itemLogistica = $this->model_item->obtenerItemsCuenta2($idCuenta)->result_array();
		$itemLogistica = refactorizarDataHT(["data" => $itemLogistica, "value" => "label"]);

		$itemLogisticaCod = $this->model_item->obtenerItemsCuenta2($idCuenta, null, false)->result_array();
		$itemLogisticaCod = refactorizarDataHT(["data" => $itemLogisticaCod, "value" => "label"]);

		$header = [];
		$column = [];
		$datosHt = [];
		$itOb = [];
		// DATOS
		if (!empty($ht)) {
			foreach ($ht as $k => $v) {
				if (empty($v->{'itemLogistica'}) && empty($v->{'codigoItemLogistica'})) {
					$result['result'] = 0;
					$result['msg']['title'] = 'Item Logistica sin indicar';
					$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Indicar Item Logistica']);
					goto Respuesta;
				}

				$buscarPorNombre = true;
				$fil = $v->{'itemLogistica'};
				if (!empty($v->{'codigoItemLogistica'})) {
					$buscarPorNombre = false;
					$fil = $v->{'codigoItemLogistica'};
				}

				$itm = $this->model_item->obtenerItemsCuenta2($idCuenta, $fil, $buscarPorNombre)->row_array();

				$datosHt[$k]['itemLogistica'] = $itm['nombre'];
				$datosHt[$k]['codigoItemLogistica'] = $itm['codigo'];
				$datosHt[$k]['pesoCuenta'] = round($itm['pesoCuenta'], 4);
				$datosHt[$k]['pesoVisual'] = round($itm['pesoLogistica'], 4);
			}
		} else {
			$datosHt[0]['codigoItemLogistica'] = null;
			$datosHt[0]['itemLogistica'] = null;
			$datosHt[0]['pesoCuenta'] = null;
			$datosHt[0]['pesoVisual'] = null;
		}

		// HEADER & COLUMN & DATOS
		$header[] = 'COD ITEM LOGISTICA*';
		$column[] = ['data' => 'codigoItemLogistica', 'type' => 'myDropdown', 'placeholder' => 'Item', 'width' => 150, 'source' => $itemLogisticaCod];
		// $datosHt[$nro]['codigoItemLogistica'] = null;

		$header[] = 'ITEM LOGISTICA*';
		$column[] = ['data' => 'itemLogistica', 'type' => 'myDropdown', 'placeholder' => 'Zona', 'width' => 700, 'source' => $itemLogistica];
		// $datosHt[$nro]['zona'] = null;

		$header[] = 'PESO CUENTA';
		$column[] = ['data' => 'pesoCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Cuenta', 'width' => 200, 'readOnly' => true];
		// $datosHt[$nro]['pesoCuenta'] = null;

		$header[] = 'PESO VISUAL';
		$column[] = ['data' => 'pesoVisual', 'type' => 'numeric', 'placeholder' => 'Peso Visual', 'width' => 200, 'readOnly' => true];
		// $datosHt[$nro]['pesoVisual'] = null;
		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Detalle Distribución',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Carga masiva detalle distribución";

		Respuesta:
		echo json_encode($result);
	}
	public function procesarTablaDatosRutasViajeras()
	{
		$result = $this->result;
		$post = $this->input->post();
		$ht = $post['HT'][0];
		array_pop($ht);

		$dataParaVista['presupuesto'] = empty($post['presupuesto']) ? 0 : $post['presupuesto'];
		$origen = $this->db->select('min(idTipoPresupuestoDetalleMovilidad) as idTipoPresupuestoDetalleMovilidad, origen')
			->group_by('origen')->get_where('compras.presupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$destino = $origen = refactorizarDataHT(["data" => $origen, "value" => "origen"]);
		$frecuencia = ['UNICA', 'MENSUAL', 'BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL'];

		$header = [];
		$column = [];
		$datosHt = [];

		// DATOS
		if (!empty($ht)) {
			foreach ($ht as $k => $v) {
				if (empty($v['origen']) && empty($v['destino'])) {
					$result['result'] = 0;
					$result['msg']['title'] = 'Origen y/o Destino sin indicar';
					$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Llenar los datos de Origen y Destino para obtener los costos.']);
					goto Respuesta;
				}

				$tpdm = $this->db->get_where(
					'compras.presupuestoDetalleMovilidad',
					[
						'origen' => $v['origen'], 'destino' => $v['destino'], 'estado' => 1
					]
				)->row_array();

				$datosHt[$k] = $v;
				if (empty($v['gap'])) $datosHt[$k]['gap'] = 0;
				if ($post['buscarCosto'] == 'true') { // * Esta asi porque desde el js lo pasa como string
					if (empty($tpdm)) {
						$datosHt[$k]['costoAereo'] =
							$datosHt[$k]['costoTransporte'] =
							$datosHt[$k]['costoMovilidadInterna'] =
							$datosHt[$k]['costoViaticos'] =
							$datosHt[$k]['costoAlojamiento'] = 0;
					} else {
						$datosHt[$k]['costoAereo'] = floatval(verificarEmpty($tpdm['precioAereo'], 2));
						$datosHt[$k]['costoTransporte'] = floatval(verificarEmpty($tpdm['precioBus'], 2));
						$datosHt[$k]['costoMovilidadInterna'] = floatval(verificarEmpty($tpdm['precioMovilidadInterna'], 2) / verificarEmpty($tpdm['dias'], 2) * floatval($v['dias']));
						$datosHt[$k]['costoViaticos'] = floatval(verificarEmpty($tpdm['precioViaticos'], 2) / verificarEmpty($tpdm['dias'], 2) * floatval($v['dias']));
						$datosHt[$k]['costoAlojamiento'] = floatval((verificarEmpty($tpdm['precioHospedaje'], 2)) / verificarEmpty($tpdm['dias'], 2) * floatval($v['dias']));
					}
				} else {
					if (floatval($datosHt[$k]['costoAereo']) < verificarEmpty($tpdm['precioAereo'], 2)) {
						$result['result'] = 0;
						$result['msg']['title'] = 'Costo menor al Presupuesto';
						$result['data']['html'] = createMessage(['type' => 2, 'message' => 'EL COSTO AÉREO ES MENOR A LO INDICADO EN EL PRESUPUESTO']);
						goto Respuesta;
					} else if (floatval($datosHt[$k]['costoTransporte']) < verificarEmpty($tpdm['precioBus'], 2)) {
						$result['result'] = 0;
						$result['msg']['title'] = 'Costo menor al Presupuesto';
						$result['data']['html'] = createMessage(['type' => 2, 'message' => 'EL COSTO DE TRANSPORTE ES MENOR A LO INDICADO EN EL PRESUPUESTO']);
						goto Respuesta;
					} else if (floatval($datosHt[$k]['costoMovilidadInterna']) < verificarEmpty($tpdm['precioMovilidadInterna'], 2)) {
						$result['result'] = 0;
						$result['msg']['title'] = 'Costo menor al Presupuesto';
						$result['data']['html'] = createMessage(['type' => 2, 'message' => 'EL COSTO DE MOVILIDAD INTERNA ES MENOR A LO INDICADO EN EL PRESUPUESTO']);
						goto Respuesta;
					} else if (floatval($datosHt[$k]['costoViaticos']) < verificarEmpty($tpdm['precioViaticos'], 2)) {
						$result['result'] = 0;
						$result['msg']['title'] = 'Costo menor al Presupuesto';
						$result['data']['html'] = createMessage(['type' => 2, 'message' => 'EL COSTO DE VIÁTICOS ES MENOR A LO INDICADO EN EL PRESUPUESTO']);
						goto Respuesta;
					} else if (floatval($datosHt[$k]['costoAlojamiento']) < verificarEmpty($tpdm['precioHospedaje'], 2)) {
						$result['result'] = 0;
						$result['msg']['title'] = 'Costo menor al Presupuesto';
						$result['data']['html'] = createMessage(['type' => 2, 'message' => 'EL COSTO DE ALOJAMIENTO ES MENOR A LO INDICADO EN EL PRESUPUESTO']);
						goto Respuesta;
					}
				}
				$datosHt[$k]['subtotal'] =
					floatval($datosHt[$k]['costoAereo']) +
					floatval($datosHt[$k]['costoTransporte']) +
					floatval($datosHt[$k]['costoMovilidadInterna']) +
					floatval($datosHt[$k]['costoViaticos']) +
					floatval($datosHt[$k]['costoAlojamiento']);

				$datosHt[$k]['totalMensual'] = round(floatval($datosHt[$k]['subtotal']) * floatval($v['cantidadViajes']), 4);
				$datosHt[$k]['costoVisual'] = round($datosHt[$k]['totalMensual'] + floatval($datosHt[$k]['costoAereo']), 4);
				$datosHt[$k]['total'] = round($datosHt[$k]['costoVisual'] * numeroPorcentaje($v['gap']), 4);
				// ? Revisar si se puede poner estas variables en constants.php o en una tabla en la BDs
				switch ($v['frecuencia']) {
					case 'QUINCENAL':
						$datosHt[$k]['frecuenciaAnual'] = 24;
						break;
					case 'MENSUAL':
						$datosHt[$k]['frecuenciaAnual'] = 12;
						break;
					case 'BIMESTRAL':
						$datosHt[$k]['frecuenciaAnual'] = 6;
						break;
					case 'TRIMESTRAL':
						$datosHt[$k]['frecuenciaAnual'] = 4;
						break;
					case 'SEMESTRAL':
						$datosHt[$k]['frecuenciaAnual'] = 2;
						break;
					case 'ANUAL':
						$datosHt[$k]['frecuenciaAnual'] = 1;
						break;
					case 'UNICA':
						$datosHt[$k]['frecuenciaAnual'] = 1;
						break;
					default:
						$datosHt[$k]['frecuenciaAnual'] = 0;
						break;
				}
				$datosHt[$k]['cuenta'] = round($datosHt[$k]['total'] * $datosHt[$k]['frecuenciaAnual'] / 12, 4);
			}
		} else {
			$datosHt[0]['responsable'] =
				$datosHt[0]['origen'] =
				$datosHt[0]['destino'] =
				$datosHt[0]['cargo'] =
				$datosHt[0]['dni'] =
				$datosHt[0]['razonSocial'] =
				$datosHt[0]['frecuencia'] =
				$datosHt[0]['dias'] =
				$datosHt[0]['costoAereo'] =
				$datosHt[0]['costoTransporte'] =
				$datosHt[0]['costoMovilidadInterna'] =
				$datosHt[0]['costoViaticos'] =
				$datosHt[0]['costoAlojamiento'] =
				$datosHt[0]['subtotal'] =
				$datosHt[0]['cantidadViajes'] =
				$datosHt[0]['totalMensual'] =
				$datosHt[0]['costoVisual'] =
				$datosHt[0]['gap'] =
				$datosHt[0]['total'] =
				$datosHt[0]['frecuenciaAnual'] =
				$datosHt[0]['cuenta'] =
				$datosHt[0]['tipo_movil'] = NULL;
		}


		// HEADER & COLUMN & DATOS
		$header[] = 'RESPONSABLE';
		$column[] = ['data' => 'responsable', 'type' => 'text', 'placeholder' => 'Responsable', 'width' => 250, 'source' => $origen];

		$header[] = 'ORIGEN';
		$column[] = ['data' => 'origen', 'type' => 'autocomplete', 'placeholder' => 'Origen', 'width' => 250, 'source' => $origen];

		$header[] = 'DESTINO';
		$column[] = ['data' => 'destino', 'type' => 'autocomplete', 'placeholder' => 'Destino', 'width' => 250, 'source' => $destino];

		$header[] = 'CARGO';
		$column[] = ['data' => 'cargo', 'type' => 'text', 'placeholder' => 'Cargo', 'width' => 200];

		$header[] = 'DNI';
		$column[] = ['data' => 'dni', 'type' => 'text', 'placeholder' => 'DNI', 'width' => 200];

		$header[] = 'APELLIDOS Y NOMBRES';
		$column[] = ['data' => 'razonSocial', 'type' => 'text', 'placeholder' => 'Apellidos y Nombres', 'width' => 400];

		$header[] = 'FRECUENCIA';
		$column[] = ['data' => 'frecuencia', 'type' => 'myDropdown', 'placeholder' => 'Frecuencia', 'width' => 250, 'source' => $frecuencia];

		$header[] = 'DIAS';
		$column[] = ['data' => 'dias', 'type' => 'numeric', 'placeholder' => 'Días', 'width' => 250];

		$header[] = 'AEREO';
		$column[] = ['data' => 'costoAereo', 'type' => 'numeric', 'placeholder' => 'Aereo', 'width' => 250];

		$header[] = 'TRANSPORTE';
		$column[] = ['data' => 'costoTransporte', 'type' => 'numeric', 'placeholder' => 'Transporte', 'width' => 250];

		$header[] = 'MOVILIDAD INTERNA';
		$column[] = ['data' => 'costoMovilidadInterna', 'type' => 'numeric', 'placeholder' => 'Movilidad Interna', 'width' => 250];

		$header[] = 'VIATICOS';
		$column[] = ['data' => 'costoViaticos', 'type' => 'numeric', 'placeholder' => 'Viaticos', 'width' => 250];

		$header[] = 'ALOJAMIENTO';
		$column[] = ['data' => 'costoAlojamiento', 'type' => 'numeric', 'placeholder' => 'Alojamiento', 'width' => 250];

		$header[] = 'SUBTOTAL';
		$column[] = ['data' => 'subtotal', 'type' => 'numeric', 'placeholder' => 'SubTotal', 'width' => 250, 'readOnly' => true];

		$header[] = '# VIAJES';
		$column[] = ['data' => 'cantidadViajes', 'type' => 'numeric', 'placeholder' => 'Viajes', 'width' => 250];

		$header[] = 'TOTAL MENSUAL';
		$column[] = ['data' => 'totalMensual', 'type' => 'numeric', 'placeholder' => 'Total Mensual', 'width' => 250, 'readOnly' => true];

		$header[] = 'TOTAL VI + TR. AEREO';
		$column[] = ['data' => 'costoVisual', 'type' => 'numeric', 'placeholder' => 'Total VI + Tr. Aereo', 'width' => 250, 'readOnly' => true];

		$header[] = 'GAP %';
		$column[] = ['data' => 'gap', 'type' => 'numeric', 'placeholder' => 'Gap', 'width' => 250];

		$header[] = 'TOTAL';
		$column[] = ['data' => 'total', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 250, 'readOnly' => true];

		$header[] = 'FRECUENCIA ANUAL';
		$column[] = ['data' => 'frecuenciaAnual', 'type' => 'numeric', 'placeholder' => 'Frecuencia Anual', 'width' => 250, 'readOnly' => true];

		$header[] = 'CUENTA';
		$column[] = ['data' => 'cuenta', 'type' => 'numeric', 'placeholder' => 'Cuenta', 'width' => 250, 'readOnly' => true];

		$header[] = 'TRASLADO';
		$column[] = ['data' => 'tipo_movil', 'type' => 'text', 'placeholder' => 'Traslado', 'width' => 250];
		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Detalle Distribución',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Carga masiva detalle rutas viajeras";

		Respuesta:
		echo json_encode($result);
	}
	public function getSubDetalleDistribucionMasivo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'));

		$idCuenta = $post->{'data'};
		$item = $post->{'item'};
		$pesoReal = $post->{'pesoReal'};
		$peso = $post->{'peso'};
		$almacen = $post->{'almacen'};
		$dataPrevia = json_decode(json_encode(json_decode($post->{'dataPrevia'})));

		if (empty($item)) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Sin Datos';
			$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Debe indicar Items previamente para esta operación']);
			goto Respuesta;
		}

		$zonas = $this->model->getZonas(['otroAlmacen' => $almacen])->result_array();
		$zonas = refactorizarDataHT(["data" => $zonas, "value" => "nombre"]);

		$tipoServicio = $this->db->select("nombre as label")->get('compras.tipoServicio')->result_array();
		$tipoServicio = refactorizarDataHT(["data" => $tipoServicio, "value" => "label"]);

		$header = [];
		$column = [];

		$datosHt = $dataPrevia;
		$nro = count($datosHt);
		// HEADER & COLUMN & DATOS
		$header[] = 'ZONA *';
		$column[] = ['data' => 'zona', 'type' => 'myDropdown', 'placeholder' => 'Zona', 'width' => 200, 'source' => $zonas];
		$datosHt[$nro]['zona'] = null;

		$header[] = 'DIAS';
		$column[] = ['data' => 'dias', 'type' => 'numeric', 'placeholder' => 'Días', 'width' => 100, 'readOnly' => true];
		$datosHt[$nro]['dias'] = null;

		foreach ($item as $k => $v) {
			if (!is_numeric($v)) {
				$result['result'] = 0;
				$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Indicar Item Logistica']);
				goto Respuesta;
			}
			$itemNombre[$k] = $this->db->where('idArticulo', $v)->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
			$header[] = $itemNombre[$k] . ' *';
			$column[] = ['data' => 'item' . $k, 'type' => 'numeric', 'placeholder' => 'Cantidad', 'width' => 300];
			$datosHt[$nro]['item' . $k] = null;
		}

		$header[] = 'GAP *';
		$column[] = ['data' => 'gap', 'type' => 'numeric', 'placeholder' => 'GAP', 'width' => 100];
		$datosHt[$nro]['gap'] = null;

		$header[] = 'TIPO SERVICIO *';
		$column[] = ['data' => 'tipoServicio', 'type' => 'myDropdown', 'placeholder' => 'Tipo Servicio', 'width' => 300, 'source' => $tipoServicio];
		$datosHt[$nro]['tipoServicio'] = null;

		$header[] = 'REEMBARQUE *';
		$column[] = ['data' => 'reembarque', 'type' => 'numeric', 'placeholder' => 'REEMBARQUE', 'width' => 150];
		$datosHt[$nro]['reembarque'] = null;

		$header[] = 'COSTO TS VISUAL';
		$column[] = ['data' => 'costoTSVisual', 'type' => 'numeric', 'placeholder' => 'Costo TS Visual', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['costoTSVisual'] = null;

		foreach ($itemNombre as $k => $v) {
			$header[] = 'PESO VISUAL ' . $v . ' (' . $pesoReal[$k] . ')';
			$column[] = ['data' => 'pesoTotalVisual' . $k, 'type' => 'numeric', 'placeholder' => 'Peso', 'width' => 300, 'readOnly' => true];
			$datosHt[$nro]['pesoTotalVisual' . $k] = null;
		}
		$header[] = 'PESO TOTAL FINAL VISUAL';
		$column[] = ['data' => 'pesoTotalVisual', 'type' => 'numeric', 'placeholder' => 'Peso Total', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['pesoTotalVisual'] = null;

		$header[] = 'PESO GAP VISUAL';
		$column[] = ['data' => 'pesoGapVisual', 'type' => 'numeric', 'placeholder' => 'Peso Gap', 'width' => 150, 'readOnly' => true];
		$datosHt[$nro]['pesoGapVisual'] = null;

		$header[] = 'COSTO TOTAL VISUAL';
		$column[] = ['data' => 'totalFinalVisual', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['totalFinalVisual'] = null;

		$header[] = 'COSTO TS CUENTA';
		$column[] = ['data' => 'costoTSCuenta', 'type' => 'numeric', 'placeholder' => 'Costo TS Cuenta', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['costoTSCuenta'] = null;

		foreach ($itemNombre as $k => $v) {
			$header[] = 'PESO CUENTA ' . $v . ' (' . $peso[$k] . ')';;
			$column[] = ['data' => 'pesoTotalCuenta' . $k, 'type' => 'numeric', 'placeholder' => 'Peso', 'width' => 300, 'readOnly' => true];
			$datosHt[$nro]['pesoTotalCuenta' . $k] = null;
		}
		$header[] = 'PESO TOTAL FINAL CUENTA';
		$column[] = ['data' => 'pesoTotalCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Total', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['pesoTotalCuenta'] = null;

		$header[] = 'PESO GAP CUENTA';
		$column[] = ['data' => 'pesoGapCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Gap', 'width' => 150, 'readOnly' => true];
		$datosHt[$nro]['pesoGapCuenta'] = null;

		$header[] = 'COSTO TOTAL CUENTA';
		$column[] = ['data' => 'totalFinalCuenta', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['totalFinalCuenta'] = null;

		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Detalle Distribución',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Carga masiva detalle distribución";

		Respuesta:
		echo json_encode($result);
	}

	public function getSubDetalleDistribucionMasivo_Items()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'));

		$idCuenta = $post->{'data'};

		$dataPrevia = json_decode(json_encode(json_decode($post->{'dataPrevia'})));

		$itemLogistica = $this->model_item->obtenerItemsCuenta2($idCuenta)->result_array();
		$itemLogistica = refactorizarDataHT(["data" => $itemLogistica, "value" => "label"]);

		$itemLogisticaCod = $this->model_item->obtenerItemsCuenta2($idCuenta, null, false)->result_array();
		$itemLogisticaCod = refactorizarDataHT(["data" => $itemLogisticaCod, "value" => "label"]);

		$header = [];
		$column = [];
		$datosHt = $dataPrevia;
		$nro = count($datosHt);

		// HEADER & COLUMN & DATOS
		$header[] = 'COD ITEM LOGISTICA*';
		$column[] = ['data' => 'codigoItemLogistica', 'type' => 'myDropdown', 'placeholder' => 'Item', 'width' => 150, 'source' => $itemLogisticaCod];
		$datosHt[$nro]['codigoItemLogistica'] = null;

		$header[] = 'ITEM LOGISTICA*';
		$column[] = ['data' => 'itemLogistica', 'type' => 'myDropdown', 'placeholder' => 'Item', 'width' => 700, 'source' => $itemLogistica];
		$datosHt[$nro]['itemLogistica'] = null;

		$header[] = 'PESO CUENTA';
		$column[] = ['data' => 'pesoCuenta', 'type' => 'numeric', 'placeholder' => 'Peso Cuenta', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['pesoCuenta'] = null;

		$header[] = 'PESO VISUAL';
		$column[] = ['data' => 'pesoVisual', 'type' => 'numeric', 'placeholder' => 'Peso Visual', 'width' => 200, 'readOnly' => true];
		$datosHt[$nro]['pesoVisual'] = null;
		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Items - Detalle Distribución',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Carga masiva items en detalle distribución";
		Respuesta:
		echo json_encode($result);
	}

	public function getSubDetalleRutasViajeras()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'));

		$dataPrevia = json_decode(json_encode(json_decode($post->{'dataPrevia'})));

		$header = [];
		$column = [];
		$datosHt = $dataPrevia;
		$nro = count($datosHt);

		$dataParaVista['presupuesto'] = empty($post->{'presupuesto'}) ? 0 : json_decode(json_encode(json_decode($post->{'presupuesto'})));
		$origen = $this->db->select('min(idTipoPresupuestoDetalleMovilidad) as idTipoPresupuestoDetalleMovilidad, origen')
			->group_by('origen')->get_where('compras.presupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$destino = $origen = refactorizarDataHT(["data" => $origen, "value" => "origen"]);
		$frecuencia = ['UNICA', 'MENSUAL', 'BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL'];
		// HEADER & COLUMN & DATOS
		$header[] = 'RESPONSABLE';
		$column[] = ['data' => 'responsable', 'type' => 'text', 'placeholder' => 'Responsable', 'width' => 250, 'source' => $origen];
		$datosHt[$nro]['responsable'] = null;

		$header[] = 'ORIGEN';
		$column[] = ['data' => 'origen', 'type' => 'autocomplete', 'placeholder' => 'Origen', 'width' => 250, 'source' => $origen];
		$datosHt[$nro]['origen'] = null;

		$header[] = 'DESTINO';
		$column[] = ['data' => 'destino', 'type' => 'autocomplete', 'placeholder' => 'Destino', 'width' => 250, 'source' => $destino];
		$datosHt[$nro]['destino'] = null;

		$header[] = 'CARGO';
		$column[] = ['data' => 'cargo', 'type' => 'text', 'placeholder' => 'Cargo', 'width' => 200 /*, 'readOnly' => true*/];
		$datosHt[$nro]['cargo'] = null;

		$header[] = 'DNI';
		$column[] = ['data' => 'dni', 'type' => 'text', 'placeholder' => 'DNI', 'width' => 200 /*, 'readOnly' => true*/];
		$datosHt[$nro]['dni'] = null;

		$header[] = 'APELLIDOS Y NOMBRES';
		$column[] = ['data' => 'razonSocial', 'type' => 'text', 'placeholder' => 'Apellidos y Nombres', 'width' => 400 /*, 'readOnly' => true*/];
		$datosHt[$nro]['razonSocial'] = null;

		$header[] = 'FRECUENCIA';
		$column[] = ['data' => 'frecuencia', 'type' => 'myDropdown', 'placeholder' => 'Frecuencia', 'width' => 250, 'source' => $frecuencia];
		$datosHt[$nro]['frecuencia'] = null;

		$header[] = 'DIAS';
		$column[] = ['data' => 'dias', 'type' => 'numeric', 'placeholder' => 'Días', 'width' => 250];
		$datosHt[$nro]['dias'] = null;

		$header[] = 'AEREO';
		$column[] = ['data' => 'costoAereo', 'type' => 'numeric', 'placeholder' => 'Aereo', 'width' => 250];
		$datosHt[$nro]['costoAereo'] = null;

		$header[] = 'TRANSPORTE';
		$column[] = ['data' => 'costoTransporte', 'type' => 'numeric', 'placeholder' => 'Transporte', 'width' => 250];
		$datosHt[$nro]['costoTransporte'] = null;

		$header[] = 'MOVILIDAD INTERNA';
		$column[] = ['data' => 'costoMovilidadInterna', 'type' => 'numeric', 'placeholder' => 'Movilidad Interna', 'width' => 250];
		$datosHt[$nro]['costoMovilidadInterna'] = null;

		$header[] = 'VIATICOS';
		$column[] = ['data' => 'costoViaticos', 'type' => 'numeric', 'placeholder' => 'Viaticos', 'width' => 250];
		$datosHt[$nro]['costoViaticos'] = null;

		$header[] = 'ALOJAMIENTO';
		$column[] = ['data' => 'costoAlojamiento', 'type' => 'numeric', 'placeholder' => 'Alojamiento', 'width' => 250];
		$datosHt[$nro]['costoAlojamiento'] = null;

		$header[] = 'SUBTOTAL';
		$column[] = ['data' => 'subtotal', 'type' => 'numeric', 'placeholder' => 'SubTotal', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['subtotal'] = null;

		$header[] = '# VIAJES';
		$column[] = ['data' => 'cantidadViajes', 'type' => 'numeric', 'placeholder' => 'Viajes', 'width' => 250];
		$datosHt[$nro]['cantidadViajes'] = null;

		$header[] = 'TOTAL MENSUAL';
		$column[] = ['data' => 'totalMensual', 'type' => 'numeric', 'placeholder' => 'Total Mensual', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['totalMensual'] = null;

		$header[] = 'TOTAL VI + TR. AEREO';
		$column[] = ['data' => 'costoVisual', 'type' => 'numeric', 'placeholder' => 'Total VI + Tr. Aereo', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['costoVisual'] = null;

		$header[] = 'GAP %';
		$column[] = ['data' => 'gap', 'type' => 'numeric', 'placeholder' => 'Gap', 'width' => 250];
		$datosHt[$nro]['gap'] = null;

		$header[] = 'TOTAL';
		$column[] = ['data' => 'total', 'type' => 'numeric', 'placeholder' => 'Total', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['total'] = null;

		$header[] = 'FRECUENCIA ANUAL';
		$column[] = ['data' => 'frecuenciaAnual', 'type' => 'numeric', 'placeholder' => 'Frecuencia Anual', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['frecuenciaAnual'] = null;

		$header[] = 'CUENTA';
		$column[] = ['data' => 'cuenta', 'type' => 'numeric', 'placeholder' => 'Cuenta', 'width' => 250, 'readOnly' => true];
		$datosHt[$nro]['cuenta'] = null;

		$header[] = 'TRASLADO';
		$column[] = ['data' => 'tipo_movil', 'type' => 'text', 'placeholder' => 'Traslado', 'width' => 250];
		$datosHt[$nro]['tipo_movil'] = null;
		// FIN: HEADER & COLUMN

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Detalle de Rutas Viajeras',
			'data' => $datosHt,
			'headers' => $header,
			'columns' => $column,
			// 'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		$result['msg']['title'] = "Detalle de Rutas Viajerass";
		Respuesta:
		echo json_encode($result);
	}

	function generarTablaDatosDistribucion()
	{
		$post = json_decode($this->input->post('data'), true);
		$ht = $post['HT'][0];
		array_pop($ht);

		$item = $post['item'];
		$pesoReal = $post['pesoReal'];
		$peso = $post['peso'];
		$almacen = $post['almacen'];

		$arrayDatos = [];
		$n = 0;
		foreach ($item as $ki => $vi) {
			foreach ($ht as $k => $v) {
				$reembarque = floatval($v['reembarque']) / count($item);
				if (empty($v['zona'])) {
					$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Zona']);
				}
				// if (empty($v['item' . $ki])) {
				// 	$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Cantidad']);
				// }
				if (empty($v['tipoServicio'])) {
					$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Tipo de Servicio']);
				}
				if ($v['pesoTotalVisual' . $ki] == null) {
					$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Debe procesar la información para cargar los pesos']);
				}

				if (empty($v['zona']) /*|| empty($v['item' . $ki])*/ || empty($v['tipoServicio']) || $v['pesoTotalVisual' . $ki] == null) {
					$result['result'] = 0;
					goto Respuesta;
				}
				$ts = $this->db->where('nombre', $v['tipoServicio'])->get('compras.tipoServicio')->row_array();
				$zona = $this->model->getZonas(['otroAlmacen' => $almacen, 'nombre' => $v['zona']])->row_array();
				$arrayDatos[$n]['idZona'] = $zona['idAlmacen'];
				$arrayDatos[$n]['zona'] = $v['zona'];
				$arrayDatos[$n]['dias'] = $v['dias'];
				$arrayDatos[$n]['idItem'] = $vi;
				$arrayDatos[$n]['item'] = $this->db->where('idArticulo', $vi)->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
				$arrayDatos[$n]['cantidad'] = $v['item' . $ki];
				$arrayDatos[$n]['gap'] = $v['gap'];
				$arrayDatos[$n]['reembarque'] = $reembarque;
				$arrayDatos[$n]['idTipoServicio'] = $this->db->where('nombre', $v['tipoServicio'])->get('compras.tipoServicio')->row_array()['idTipoServicio'];
				$arrayDatos[$n]['tipoServicio'] = $v['tipoServicio'];
				$arrayDatos[$n]['pesoVisual'] = $pesoReal[$ki];
				$arrayDatos[$n]['pesoTotalVisual'] = $v['pesoTotalVisual' . $ki];
				$arrayDatos[$n]['pesoGapVisual'] = floatval($v['pesoTotalVisual' . $ki]) * (100 + floatval($v['gap'])) / 100;
				$arrayDatos[$n]['costoTSVisual'] = $ts['costoVisual'];
				$arrayDatos[$n]['totalVisual'] = floatval($ts['costoVisual']) * floatval($arrayDatos[$n]['pesoGapVisual']) + $reembarque;
				$arrayDatos[$n]['pesoCuenta'] = $peso[$ki];
				$arrayDatos[$n]['pesoTotalCuenta'] = $v['pesoTotalCuenta' . $ki];
				$arrayDatos[$n]['pesoGapCuenta'] = floatval($v['pesoTotalCuenta' . $ki]) * (100 + floatval($v['gap'])) / 100;
				$arrayDatos[$n]['costoTSCuenta'] = $ts['costo'];
				$arrayDatos[$n]['totalCuenta'] = floatval($ts['costo']) * floatval($arrayDatos[$n]['pesoGapCuenta']) + $reembarque;
				$n++;
			}
		}
		$data = [
			'cabecera' => [
				'idZona' => 'IDZONA',
				'zona' => 'ZONA',
				'dias' => 'DIAS',
				'idItem' => 'ITEM',
				'item' => 'ITEM',
				'cantidad' => 'CANTIDAD',
				'gap' => 'GAP',
				'reembarque' => 'REEMBARQUE',
				'idTipoServicio' => 'ID TS',
				'tipoServicio' => 'TIPO SERVICIO',
				'pesoVisual' => 'PESO VISUAL',
				'pesoTotalVisual' => 'PESO TOTAL VISUAL',
				'pesoGapVisual' => 'PESO GAP VISUAL',
				'costoTSVisual' => 'COSTO TS VISUAL',
				'totalVisual' => 'COSTO TOTAL VISUAL',
				'pesoCuenta' => 'PESO CUENTA',
				'pesoTotalCuenta' => 'PESO TOTAL CUENTA',
				'pesoGapCuenta' => 'PESO GAP CUENTA',
				'costoTSCuenta' => 'COSTO TS CUENTA',
				'totalCuenta' => 'COSTO TOTAL CUENTA',
			],
			'datos' => $arrayDatos,
			'classP' => 'tb_data_'
		];
		$result = $this->result;
		$result['result'] = 1;
		$result['msg']['content'] = htmlTableValueArray($data);

		$result['msg']['cantidadPdv'] = count($ht);
		Respuesta:
		echo json_encode($result);
	}

	function generarDatosPesosItem()
	{
		$post = json_decode($this->input->post('data'), true);
		$ht = $post['HT'][0];
		array_pop($ht);

		$cuenta = $post['cuenta'];
		// $pesoReal = $post['pesoReal'];
		// $peso = $post['peso'];

		$arrayDatos = [];
		$n = 0;

		foreach ($ht as $k => $v) {

			if (empty($v['itemLogistica'])) {
				$result['result'] = 0;
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Item Logistica']);
				goto Respuesta;
			}
			$item = $this->model_item->obtenerItemsCuenta2($cuenta, $v['itemLogistica'])->row_array();
			$arrayDatos[$n]['idArticulo'] = $item['value'];
			$arrayDatos[$n]['nombre'] = $item['label'];
			$arrayDatos[$n]['pesoVisual'] = round($item['pesoLogistica'], 4);
			$arrayDatos[$n]['pesoCuenta'] = round($item['pesoCuenta'], 4);
			$n++;
		}

		$result = $this->result;
		$result['result'] = 1;
		$result['msg']['content'] = $arrayDatos;
		Respuesta:
		echo json_encode($result);
	}
	function generarDatosTablaRutasViajeras()
	{
		$post = $this->input->post();
		$ht = $post['HT'][0];
		array_pop($ht);

		$arrayDatos = [];
		$n = 0;

		foreach ($ht as $k => $v) {
			if (empty($v['origen']) || empty($v['destino'])) {
				$result['result'] = 0;
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Origen & Destino']);
				goto Respuesta;
			}
			if (empty($v['dias'])) {
				$result['result'] = 0;
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Indicar Cantidad de días']);
				goto Respuesta;
			}
			if (empty($v['cuenta'])) {
				$result['result'] = 0;
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Es necesario que la cuenta tenga un monto']);
				goto Respuesta;
			}
			$idTipoPresupuestoDetalleMovilidad = null;
			$tpdm = $this->db->get_where(
				'compras.presupuestoDetalleMovilidad',
				[
					'origen' => $v['origen'],
					'destino' => $v['destino'],
					'estado' => 1,
				]
			)->row_array();


			if (!empty($tpdm)) $idTipoPresupuestoDetalleMovilidad = $tpdm['idTipoPresupuestoDetalleMovilidad'];
			else {
				// * Buscar si hay un id con estado 0;
				$tpdm = $this->db->get_where(
					'compras.presupuestoDetalleMovilidad',
					[
						'origen' => $v['origen'],
						'destino' => $v['destino']
					]
				)->row_array();

				$datosInsertOrUpdate = [
					'estado' => 1,
					'precioBus' => floatval(verificarEmpty($v['costoTransporte'], 2)) / floatval($v['dias']),
					'precioHospedaje' => floatval(verificarEmpty($v['costoAlojamiento'], 2)) / floatval($v['dias']),
					'precioViaticos' => floatval(verificarEmpty($v['costoViaticos'], 2)) / floatval($v['dias']),
					'precioMovilidadInterna' => floatval(verificarEmpty($v['costoMovilidadInterna'], 2)) / floatval($v['dias']),
					'precioAereo' => floatval(verificarEmpty($v['costoAereo'], 2)) / floatval($v['dias']),
				];

				if (!empty($tpdm)) { // * Si encontramos 1 se activa y actualizan los costos.
					$idTipoPresupuestoDetalleMovilidad = $tpdm['idTipoPresupuestoDetalleMovilidad'];
					$this->db->update(
						'compras.presupuestoDetalleMovilidad',
						$datosInsertOrUpdate,
						['idTipoPresupuestoDetalleMovilidad' => $tpdm['idTipoPresupuestoDetalleMovilidad']]
					);
				} else {
					$datosInsertOrUpdate['origen'] = $v['origen'];
					$datosInsertOrUpdate['destino'] = $v['destino'];
					$datosInsertOrUpdate['idUsuario'] = $this->idUsuario;
					$datosInsertOrUpdate['fechaReg'] = getActualDateTime();
					$this->db->insert('compras.presupuestoDetalleMovilidad', $datosInsertOrUpdate);
					$idTipoPresupuestoDetalleMovilidad = $this->db->insert_id();
				}
			}


			// $item = $this->model_item->obtenerItemsCuenta2($cuenta, $v['itemLogistica'])->row_array();
			$arrayDatos[$n] = $v;
			$arrayDatos[$n]['idFrecuencia'] = $this->db->get_where('dbo.frecuencia', ['nombre' => $v['frecuencia']])->row_array()['idFrecuencia'];
			$arrayDatos[$n]['idTPDM'] = $idTipoPresupuestoDetalleMovilidad;
			$n++;
		}

		$result = $this->result;
		$result['result'] = 1;
		$result['msg']['content'] = $arrayDatos;
		Respuesta:
		echo json_encode($result);
	}
	public function getProvincia()
	{
		$post = $this->input->post();

		$provincia = $this->db->distinct()->select('tzt.cod_provincia, u.provincia')
			->from('compras.tarifarioZonaTransporte tzt')
			->join(
				'General.dbo.ubigeo u',
				'u.cod_provincia = tzt.cod_provincia AND tzt.cod_departamento = u.cod_departamento',
				'INNER'
			)
			->where('tzt.cod_departamento', $post['cod_dep'])
			->where('tzt.estado', 1)
			->order_by('u.provincia')->get()->result_array();

		echo htmlSelectOptionArray2(['title' => 'Seleccione', 'id' => 'cod_provincia', 'value' => 'provincia', 'query' => $provincia, 'class' => 'text-titlecase']);
	}
	public function getDistrito()
	{
		$post = $this->input->post();

		$distrito = $this->db->distinct()->select('tzt.cod_distrito, u.distrito')
			->from('compras.tarifarioZonaTransporte tzt')
			->join(
				'General.dbo.ubigeo u',
				'u.cod_distrito = tzt.cod_distrito AND tzt.cod_departamento = u.cod_departamento AND tzt.cod_provincia = u.cod_provincia',
				'INNER'
			)
			->where('tzt.cod_departamento', $post['cod_dep'])
			->where('tzt.cod_provincia', $post['cod_pro'])
			->where('tzt.estado', 1)
			->order_by('u.distrito')->get()->result_array();
		echo htmlSelectOptionArray2(['title' => 'Seleccione', 'id' => 'cod_distrito', 'value' => 'distrito', 'query' => $distrito, 'class' => 'text-titlecase']);
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
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
		//echo $this->db->last_query();exit();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();

		// * Para rutas Viajeras
		$listIdCotizacionDetalle = refactorizarDataHT(
			[
				"data" => $this->db->get_where('compras.cotizacionDetalle', ['idCotizacion' => $idCotizacion, 'idItemTipo' => COD_RUTAS_VIAJERAS['id']])->result_array(),
				"value" => "idCotizacionDetalle"
			]
		);
		if (!empty($listIdCotizacionDetalle)) {
			foreach ($listIdCotizacionDetalle as $idCotizacionDetalle) {
				$this->db->select('*')
					->select('frecuencia as idFrecuencia')
					->select('(SELECT nombre FROM dbo.frecuencia WHERE idFrecuencia = frecuencia) as frecuencia', false)
					->select('(
						isnull(costoAereo, 0) +
						isnull(costoTransporte, 0) +
						isnull(costoMovilidadInterna, 0) +
						isnull(costoViaticos, 0) +
						isnull(costoAlojamiento, 0)
					) * cantidadViajes as totalMensual')
					->select('costoVisual * (100 + isnull(gap, 0)) / 100 as total')
					->select('(SELECT frecuenciaAnual FROM dbo.frecuencia WHERE idFrecuencia = frecuencia) as frecuenciaAnual', false)
					->select('subtotal as cuenta');
				$rpta = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $idCotizacionDetalle])->result_array();
				$config['data']['dataRutasViajeras'][$idCotizacionDetalle] = $rpta;
			}
		}
		// * Fin: Para Rutas Viajeras
		$cotizacionDetalleSub = $this->model->obtenerInformacionDetalleCotizacionSubdis(
			[
				'idCotizacion' => $idCotizacion
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$sub['provincia'] = $this->db->distinct()->select('provincia')->get_where('General.dbo.ubigeo', ['cod_departamento' => $sub['cod_departamento'], 'cod_provincia' => $sub['cod_provincia']])->row_array()['provincia'];
			$sub['distrito'] = $this->db->distinct()->select('distrito')->get_where('General.dbo.ubigeo', ['cod_departamento' => $sub['cod_departamento'], 'cod_provincia' => $sub['cod_provincia'], 'cod_distrito' => $sub['cod_distrito']])->row_array()['distrito'];
			$sub['tipoServicioUbigeo'] = $this->db->get_where('compras.tipoServicioUbigeo', ['idTipoServicioUbigeo' => $sub['idTipoServicioUbigeo']])->row_array()['nombreAlternativo'];
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
		}

		foreach ($config['data']['cotizacionDetalle'] as $k => $v) {
			$config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] = $this->db->distinct()->select('idItem, isnull(peso, 0) as pesoCuenta, isnull(pesoVisual, 0) as pesoVisual, flagItemInterno')->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			$config['data']['cotizacionDetalleSubZonas'][$v['idCotizacionDetalle']] = $this->db->distinct()->select('idZona, flagOtrosPuntos, isnull(dias, 0) as dias, gap, costo, idTipoServicio, costoVisual')->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();

			$i = 0;
			foreach ($config['data']['cotizacionDetalleSubZonas'][$v['idCotizacionDetalle']] as $kz => $vz) {
				$psTV = 0;
				$psTC = 0;

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['zona'] = $this->model->getZonas(['otroAlmacen' => $vz['flagOtrosPuntos'], 'idZona' => $vz['idZona']])->row_array()['nombre'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['dias'] = $vz['dias'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['gap'] = $vz['gap'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['tipoServicio'] = $this->db->where('idTipoServicio', $vz['idTipoServicio'])->get('compras.tipoServicio')->row_array()['nombre'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['costoTSVisual'] = strval(round($vz['costoVisual'], 2));
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['costoTSCuenta'] = strval(round($vz['costo'], 2));

				foreach ($config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] as $ki => $vi) {
					$cantItm = $this->db->where('idItem', $vi['idItem'])->where('idZona', $vz['idZona'])->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->row_array()['cantidad'];

					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['item' . $ki] = $cantItm;
					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalVisual' . $ki] = strval(round(floatval($vi['pesoVisual']) * floatval($cantItm), 4));
					$psTV += (floatval($vi['pesoVisual']) * floatval($cantItm));

					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalCuenta' . $ki] = strval(round(floatval($vi['pesoCuenta']) * floatval($cantItm), 4));
					$psTC += (floatval($vi['pesoCuenta']) * floatval($cantItm));
				}

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalVisual'] = strval(round($psTV, 4));
				$pgV = $config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoGapVisual'] = strval(floatval($psTV) * (100 + floatval($vz['gap'])) / 100);
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['totalFinalVisual'] = strval(floatval($pgV) * floatval($vz['costoVisual']));

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalCuenta'] = strval(round($psTC, 4));
				$pgC = $config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoGapCuenta'] = strval(floatval($psTC) * (100 + floatval($vz['gap'])) / 100);
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['totalFinalCuenta'] = strval(floatval($pgC) * floatval($vz['costo']));
				$i++;
			}

			foreach ($config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] as $ki => $vi) {
				$config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']][$ki]['itemLogistica'] = $this->db->where('idArticulo', $vi['idItem'])->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
			}
		}

		foreach ($config['data']['cotizacionDetalle'] as $sub) {
			$config['data']['cotizacionDetalleArchivosDelProveedor'][$sub['idCotizacionDetalle']] = $this->model->getCotizacionProveedorArchivosSeleccionados(['idCotizacionDetalle' => $sub['idCotizacionDetalle']])->result_array();

			$config['data']['cotizacionDetallePDV'][$sub['idCotizacionDetalle']] = $this->model->obtenerDetallePDV($sub['idCotizacionDetalle']); //$this->db->where('idCotizacionDetalle', $sub['idCotizacionDetalle'])->get('compras.cotizacionDetalleUbigeo')->result_array();
			$dcds = $this->db->where('idCotizacionDetalle', $sub['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			foreach ($dcds as $kCds => $vCds) {
				$dcds[$kCds]['zona'] = $this->model->getZonas(['otroAlmacen' => $vCds['flagOtrosPuntos'], 'idZona' => $vCds['idZona']])->row_array()['nombre'];
				$dcds[$kCds]['item'] = $vCds['nombre'];
				$dcds[$kCds]['tipoServicio'] = $this->db->where('idTipoServicio', $vCds['idTipoServicio'])->get('compras.tipoServicio')->row_array()['nombre'];
				$dcds[$kCds]['pesoTotalVisual'] = floatval($vCds['pesoVisual']) * floatval($vCds['cantidad']);
				$dcds[$kCds]['pesoGapVisual'] = floatval($dcds[$kCds]['pesoTotalVisual']) * (100 + floatval($vCds['gap'])) / 100;
				$dcds[$kCds]['costoTSVisual'] = $vCds['costoVisual'];
				$dcds[$kCds]['totalVisual'] = floatval($dcds[$kCds]['pesoGapVisual']) * floatval($vCds['costoVisual']);
				$dcds[$kCds]['pesoCuenta'] = $vCds['peso'];
				$dcds[$kCds]['pesoTotalCuenta'] = floatval($vCds['peso']) * floatval($vCds['cantidad']);
				$dcds[$kCds]['pesoGapCuenta'] = floatval($dcds[$kCds]['pesoTotalCuenta']) * (100 + floatval($vCds['gap'])) / 100;
				$dcds[$kCds]['costoTSCuenta'] = $vCds['costo'];
				$dcds[$kCds]['totalCuenta'] = floatval($dcds[$kCds]['pesoGapCuenta']) * floatval($vCds['costo']);
			}
			$config['data']['tablaGen'][$sub['idCotizacionDetalle']] = '';
			if ($sub['idItemTipo'] == COD_DISTRIBUCION['id']) {
				$data = [
					'cabecera' => [
						'idZona' => 'IDZONA',
						'zona' => 'ZONA',
						'dias' => 'DIAS',
						'idItem' => 'ITEM',
						'item' => 'ITEM',
						'cantidad' => 'CANTIDAD',
						'gap' => 'GAP',
						'idTipoServicio' => 'ID TS',
						'tipoServicio' => 'TIPO SERVICIO',
						'pesoVisual' => 'PESO VISUAL',
						'pesoTotalVisual' => 'PESO TOTAL VISUAL',
						'pesoGapVisual' => 'PESO GAP VISUAL',
						'costoTSVisual' => 'COSTO TS VISUAL',
						'totalVisual' => 'COSTO TOTAL VISUAL',
						'pesoCuenta' => 'PESO CUENTA',
						'pesoTotalCuenta' => 'PESO TOTAL CUENTA',
						'pesoGapCuenta' => 'PESO GAP CUENTA',
						'costoTSCuenta' => 'COSTO TS CUENTA',
						'totalCuenta' => 'COSTO TOTAL CUENTA',
					],
					'datos' => $dcds,
					'classP' => 'tb_data_'
				];
				$config['data']['tablaGen'][$sub['idCotizacionDetalle']] = htmlTableValueArray($data);
			}
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
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['estadoProveedor' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio = $this->model_item->obtenerItemServicio();
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
		$config['data']['departamento'] = $this->db->distinct()->select('cod_departamento, departamento')->where('estado', 1)->order_by('departamento')->get('General.dbo.ubigeo')->result_array();
		$config['data']['tipoMoneda'] = $this->model->obtenertipoMoneda()['query']->result_array();
		$config['data']['tipoServicioCotizacion'] = $this->model->obtenerTipoServicioCotizacion()['query']->result_array();
		$config['data']['listProveedor'] = $this->db->order_by('razonSocial')->get_where('compras.proveedor', ['idProveedorEstado' => 2, 'flagTarjetasVales' => 1])->result_array();
		$config['data']['ordenServicio'] = $this->model->obtenerOrdenServicio()['query']->result_array();

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
		$cotizacionDetalle = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids, 'notIdItemTipo' => COD_DISTRIBUCION['id']])['query']->result_array();
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
			if ($rowDetalle['idItemTipo'] == COD_TRANSPORTE['id']) {
				$cds_ = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $rowDetalle['idCotizacionDetalle']])->result_array();
				$dataParaVista['detalle'][$rowDetalle['idCotizacion']][$rowDetalle['idCotizacionDetalle']]['costo'] = 0;
				foreach ($cds_ as $k => $v) {
					$cdss = $this->db->get_where('compras.cotizacionDetalleSubSincerado', ['idCotizacionDetalleSub' => $v['idCotizacionDetalleSub']])->row_array();
					$dataParaVista['detalle'][$rowDetalle['idCotizacion']][$rowDetalle['idCotizacionDetalle']]['costo'] += (floatval($cdss['costo']) * floatval($cdss['cantidad']) * floatval($cdss['dias']));
				}
			}
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
		$operUpdate = $this->model->actualizarCotizacion(
			[
				'tabla' => 'compras.oper',
				'update' => [
					'requerimiento' => "OP" . $this->model->obtenerSeriado(OPER_SERIADO)
				],
				'where' => ['idOper' => $oper['id']]
			]
		);

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
				'cc' => $toCompras,
				'asunto' => 'Generación de Oper',
				'contenido' => $correo,
			];
			email($config);
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function registrarOperTemp()
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
			'estado' => 0
		];

		$oper = $this->model->insertar(['tabla' => 'compras.oper', 'insert' => $insertOper]);
		$operUpdate = $this->model->actualizarCotizacion([
			'tabla' => 'compras.oper',
			'update' =>	[
				'requerimiento' => "TEMPORAL"
			],
			'where' => ['idOper' => $oper['id']]
		]);

		$post['idCotizacion'] = checkAndConvertToArray($post['idCotizacion']);

		$insertOperDetalle = [];
		$updateCotizacion = [];
		// $insertHistoricoCotizacion = [];
		foreach ($post['idCotizacion'] as $idCotizacion) {
			$insertOperDetalle[] = [
				'idOper' => $oper['id'],
				'idCotizacion' => $idCotizacion,
				'estado' => 0
			];

			// $updateCotizacion[] = [
			// 	'idCotizacion' => $idCotizacion,
			// 	'idCotizacionEstado' => ESTADO_OPER_ENVIADO,
			// ];

			// $insertHistoricoCotizacion[] = [
			// 	'idCotizacionEstado' => ESTADO_OPER_ENVIADO,
			// 	'idCotizacion' => $idCotizacion,
			// 	'idUsuarioReg' => $this->idUsuario,
			// ];
		}

		$operDet = $this->model->insertarMasivo('compras.operDetalle', $insertOperDetalle);
		// $updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion', $updateCotizacion, 'idCotizacion');
		// $insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION, $insertHistoricoCotizacion);

		if (!$oper['estado'] || $operDet['estado']) {
			$result['result'] = 0;
			$result['data']['width'] = '40%';
			$result['data']['html'] = createMessage(['type' => 2, 'No se pudo generar el OPER']);
			goto respuesta;
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Generar Oper';
			$result['data']['idOper'] = $oper['id'];
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

	public function descargarOperDirecto($oper = null)
	{
		$this->descargarOper($oper, true);
	}
	public function descargarOper($t = null, $visible = false)
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		if (!empty($t)) {
			$post['idOper'] = $t;
		}
		$oper = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])['query']->result_array();

		$dataParaVista['dataOper'] = $oper[0];
		$ids = [];
		foreach ($oper as $v) {
			$ids[] = $v['idCotizacion'];
			$config['data']['oper'][$v['idOper']] = $v;
		}

		$idCotizacion = implode(",", $ids);
		$dataParaVista['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
		$dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => false,
				'noTipoItem' => COD_DISTRIBUCION['id'] . ',' . COD_PERSONAL['id']
			]
		)['query']->result_array();

		foreach ($dataParaVista['cotizacionDetalle'] as $k => $v) {
			$dataParaVista['cotizacionDetalleSub'][$v['idCotizacionDetalle']] = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $v['idCotizacionDetalle']])->result_array();

			foreach ($dataParaVista['cotizacionDetalleSub'][$v['idCotizacionDetalle']] as $kd => $vd) {
				$dataParaVista['detalleSubTalla'][$v['idCotizacionDetalle']][$vd['talla']][$vd['genero']] = $vd;
			}

			if ($v['idItemTipo'] == COD_TRANSPORTE['id']) {
				$cds_ = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $v['idCotizacionDetalle']])->result_array();
				$dataParaVista['cotizacionDetalle'][$k]['costo'] = 0;
				$cantTotalT = 0;
				foreach ($cds_ as $vds) {
					$cdss = $this->db->get_where('compras.cotizacionDetalleSubSincerado', ['idCotizacionDetalleSub' => $vds['idCotizacionDetalleSub']])->row_array();
					$dataParaVista['cotizacionDetalle'][$k]['costo'] += (floatval($cdss['costo']) * floatval($cdss['cantidad']) * floatval($cdss['dias']));
					$cantTotalT += floatval($cdss['cantidad']);
				}
				$dataParaVista['cotizacionDetalle'][$k]['subtotalSinGap'] = $dataParaVista['cotizacionDetalle'][$k]['costo'];
				$dataParaVista['cotizacionDetalle'][$k]['cantidad'] = $cantTotalT;
			}
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
		$titlePdf = $oper[0]['requerimiento'] . ' - ' . $oper[0]['concepto'];
		if ($visible) {
			$mpdf->Output('322', 'I');
		} else {
			// $mpdf->Output("OC{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
			$mpdf->Output("$titlePdf.pdf", \Mpdf\Output\Destination::DOWNLOAD);
		}

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

	public function descargarOCDirecto($oc = null)
	{
		$this->descargarOrdenCompra($oc, true);
	}

	public function descargarOCDirectoProvServ($oc = null)
	{
		$this->descargarOrdenCompraProveedorServicio($oc, false);
	}

	public function descargarOrdenCompra($t = null, $visible = false)
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		if (!empty($t)) {
			$post['id'] = $t;
		}

		$ordenCompra = $this->model_formulario_proveedor->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();

		$dataParaVista['data'] = $ordenCompra[0];
		$dataParaVista['detalle'] = $ordenCompra;

		$cotDet = [];
		foreach ($ordenCompra as $k => $v) {
			$cotDet[] = $v['idCotizacionDetalle'];
		}
		$cotizacionDet = implode(',', $cotDet);

		$dataParaVista['imagenesDeItem'] = [];
		if ($dataParaVista['data']['mostrar_imagenes'] == '1') {
			foreach ($dataParaVista['detalle'] as $key => $value) {
				$dataParaVista['imagenesDeItem'][$value['idItem']] = $this->db->where('idItem', $value['idItem'])->get('compras.itemImagen')->result_array();
			}
		}

		if ($dataParaVista['data']['mostrar_imagenesCoti'] == '1') {
			foreach ($ordenCompra as $key => $value) {
				$dd = $this->model->getImagenCotiProv(['idCotizacionDetalle' => $value['idCotizacionDetalle'], 'idProveedor' => $value['idProveedor']])->result_array();
				foreach ($dd as $kl => $vl) {
					$dataParaVista['imagenesDeItem'][$value['idItem']][] = $vl;
				}
			}
		}

		foreach ($dataParaVista['detalle'] as $k => $v) {
			$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
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
		$mpdf->curlAllowUnsafeSslRequests = true;
		$mpdf->showImageErrors = true;
		$mpdf->debug = true;
		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		$cod_oc = generarCorrelativo($dataParaVista['data']['seriado'], 6) . "-" . $dataParaVista['data']['concepto'];
		if ($visible) {
			$mpdf->Output("OC{$cod_oc}.pdf", 'I');
		} else {
			$mpdf->Output("OC{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
		}
	}

	public function descargarOrdenCompraProveedorServicio($t = null, $visible = false)
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		$flag = $this->input->get('flag');
		if (!empty($t)) {
			$post['id'] = $t;
		}

		if ($flag == 0) {
			$ordenCompra = $this->model_formulario_proveedor->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();
		} else {
			$ordenCompra = $this->model_formulario_proveedor->obtenerOrdenCompraDetalleProveedorOC(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();
		}

		$dataParaVista['data'] = $ordenCompra[0];
		$dataParaVista['detalle'] = $ordenCompra;

		$cotDet = [];
		foreach ($ordenCompra as $k => $v) {
			$cotDet[] = $v['idCotizacionDetalle'];
		}
		$cotizacionDet = implode(',', $cotDet);

		$dataParaVista['imagenesDeItem'] = [];
		if ($dataParaVista['data']['mostrar_imagenes'] == '1') {
			foreach ($dataParaVista['detalle'] as $key => $value) {
				$dataParaVista['imagenesDeItem'][$value['idItem']] = $this->db->where('idItem', $value['idItem'])->get('compras.itemImagen')->result_array();
			}
		}

		if ($dataParaVista['data']['mostrar_imagenesCoti'] == '1') {
			foreach ($ordenCompra as $key => $value) {
				$dd = $this->model->getImagenCotiProv(['idCotizacionDetalle' => $value['idCotizacionDetalle'], 'idProveedor' => $value['idProveedor']])->result_array();
				foreach ($dd as $kl => $vl) {
					$dataParaVista['imagenesDeItem'][$value['idItem']][] = $vl;
				}
			}
		}

		foreach ($dataParaVista['detalle'] as $k => $v) {
			if ($flag == 0)
				$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			elseif ($flag == 1)
				$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->select('*, idGenero as genero')->get_where('orden.ordenCompraDetalleSub', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle']])->result_array();
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
		$mpdf->curlAllowUnsafeSslRequests = true;
		$mpdf->showImageErrors = true;
		$mpdf->debug = true;
		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		$cod_oc = generarCorrelativo($dataParaVista['data']['seriado'], 6) . "-" . $dataParaVista['data']['concepto'];
		if ($visible) {
			$mpdf->Output("{$cod_oc}.pdf", 'I');
		} else {
			$mpdf->Output("{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
		}
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
			'fechaEnvioCliente' => getActualDateTime(),
			'usurioEnvioCliente' => $this->idUsuario,
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
		$itemsTipoDistribucion = 0;
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
			'idPrioridad' => verificarEmpty($post['prioridadForm'], 4),
			'motivo' => !empty($post['motivoForm']) ? trim($post['motivoForm']) : '',
			'comentario' => $post['comentarioForm'],
			'diasValidez' => $post['diasValidez'],
			'mostrarPrecio' => !empty($post['flagMostrarPrecio']) ? $post['flagMostrarPrecio'] : 0,
			'idTipoServicioCotizacion' => $post['tipoServicioCotizacion'],
			'idTipoMoneda' => $post['tipoMoneda'],
			'feeTarjetaVales' => $post['feeForm3'],
		];

		if (isset($post['actualizarEstado'])) {
			if ($post['actualizarEstado'] == '2') {
				// Por defecto en estado confirmado
				$data['update']['idCotizacionEstado'] = 3;
				// Se detecta alguno pendiente de cotizar se envia a compras
				$post['cotizacionInternaForm'] = checkAndConvertToArray($post['cotizacionInternaForm']);
				foreach ($post['cotizacionInternaForm'] as $k => $v) {
					if ($v == '1') $data['update']['idCotizacionEstado'] = 2;
				}
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
				$this->db->update('compras.cotizacionDetalle', ['estado' => 0], ['idCotizacionDetalle' => $value]);
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
		if (isset($post['nameItem'])) {
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
			$post['flagPackingSolicitado'] = checkAndConvertToArray($post['flagPackingSolicitado']);
			$post['costoPacking'] = checkAndConvertToArray($post['costoPacking']);
			$post['flagMovilidadSolicitado'] = checkAndConvertToArray($post['flagMovilidadSolicitado']);
			$post['costoMovilidad'] = checkAndConvertToArray($post['costoMovilidad']);
			$post['flagPersonalSolicitado'] = checkAndConvertToArray($post['flagPersonalSolicitado']);
			$post['costoPersonal'] = checkAndConvertToArray($post['costoPersonal']);
			$post['flagMostrarDetalle'] = checkAndConvertToArray($post['flagMostrarDetalle']);
			$post['cantidadPDV'] = checkAndConvertToArray($post['cantidadPDV']);
			$post['flagGenerarOC'] = checkAndConvertToArray($post['flagGenerarOC']);
			$post['tipoTarjVales'] = checkAndConvertToArray($post['tipoTarjVales']);
			$post['verDetallePdf'] = isset($post['verDetallePdf']) ? checkAndConvertToArray($post['verDetallePdf']) : [];
			if (isset($post['flagCuenta'])) $post['flagCuenta'] = checkAndConvertToArray($post['flagCuenta']);

			$post['flagRedondearForm'] = checkAndConvertToArray($post['flagRedondearForm']);
			$post['itemTextoPdf'] = checkAndConvertToArray($post['itemTextoPdf']);
			$n = 0; // Cantidad de items en la tabla de distribución.
			$cantRutViaj = 0; // Cantidad de items en la tabla de Rutas Viajeras.
			foreach ($post['nameItem'] as $k => $r) {
				if (!empty($r)) {
					$idCot = $post['idCotizacionDetalle'][$k];
					if ($post['idCotizacionDetalle'][$k] != '0') {
						$idItem = $post['idItemForm'][$k];
						if (empty($idItem)) $idItem = $this->model->generarBuscarItem($r, $post['tipoItemForm'][$k]);
						$data['update'][$k] = [
							'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
							'idCotizacion' => $post['idCotizacion'],
							'idItem' => $idItem,
							'idItemTipo' => $post['tipoItemForm'][$k],
							'nombre' => $post['nameItem'][$k],
							'cantidad' => $post['cantidadForm'][$k],
							'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
							'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
							'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : 0,
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
							'flagAlternativo' => !empty($post['itemTextoPdf'][$k]) ? '1' : '0',
							'nombreAlternativo' => !empty($post['itemTextoPdf'][$k]) ? $post['itemTextoPdf'][$k] : NULL,
							'flagPackingSolicitado' => !empty($post['flagPackingSolicitado'][$k]) ? $post['flagPackingSolicitado'][$k] : 0,
							'costoPacking' => !empty($post['costoPacking'][$k]) ? $post['costoPacking'][$k] : 0,
							'flagMovilidadSolicitado' => !empty($post['flagMovilidadSolicitado'][$k]) ? $post['flagMovilidadSolicitado'][$k] : 0,
							'costoMovilidad' => !empty($post['costoMovilidad'][$k]) ? $post['costoMovilidad'][$k] : 0,
							'flagPersonalSolicitado' => !empty($post['flagPersonalSolicitado'][$k]) ? $post['flagPersonalSolicitado'][$k] : 0,
							'costoPersonal' => !empty($post['costoPersonal'][$k]) ? $post['costoPersonal'][$k] : 0,
							'flagMostrarDetalle' => !empty($post['flagMostrarDetalle'][$k]) ? $post['flagMostrarDetalle'][$k] : 0,
							'cantPdv' => !empty($post['cantidadPDV'][$k]) ? $post['cantidadPDV'][$k] : 0,
							'requiereOrdenCompra' => !empty($post['flagGenerarOC'][$k]) ? $post['flagGenerarOC'][$k] : 0,
							'idTipo_TarjetasVales' => !empty($post['tipoTarjVales'][$k]) ? $post['tipoTarjVales'][$k] : null,
							'flagDetalleTarjetasVales' => !empty($post['verDetallePdf'][$k]) ? $post['verDetallePdf'][$k] : 0,
						];

						if (
							!empty($post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"]) ||
							$post['tipoItemForm'][$k] == COD_RUTAS_VIAJERAS['id']
						) {
							switch ($post['tipoItemForm'][$k]) {
								case COD_SERVICIO['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'nombre' => $post["nombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["costoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'subtotal' => $post["subtotalSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'sucursal' => $post["sucursalSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'tipoElemento' => $post["tipoElementoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'marca' => $post["marcaSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
										'razonSocial' => $post["razonSocialSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
									]);
									break;
								case COD_RUTAS_VIAJERAS['id']:
									$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $post['idCotizacionDetalle'][$k]]);
									$post['cantidadItemsRutasViajeras'] = checkAndConvertToArray($post['cantidadItemsRutasViajeras']);
									$cantidad = intval($post['cantidadItemsRutasViajeras'][$cantRutViaj]);
									$data['subDetalle'][$k] = [];

									for ($it = 0; $it < $cantidad; $it++) {
										$name = 'Ruta Viajera de ' . checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj];
										$name .= ' a ';
										$destinos = explode('-', checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]);
										if (count($destinos) == 1) $name .= $destinos[0];
										else {
											foreach ($destinos as $kr => $ruta) {
												if ($kr != 0) $name .= ' / ' . $destinos[$kr - 1] . ' a ';
												$name .= $destinos[$kr];
											}
										}
										$data['subDetalle'][$k][] = [
											'nombre' => $name,
											'origen' => strval(checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj]),
											'destino' => strval(checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]),
											'responsable' => strval(checkAndConvertToArray($post['subDetRutViajResponsable'])[$cantRutViaj]),
											'cargo' => strval(checkAndConvertToArray($post['subDetRutViajCargo'])[$cantRutViaj]),
											'dni' => strval(checkAndConvertToArray($post['subDetRutViajDni'])[$cantRutViaj]),
											'razonSocial' => strval(checkAndConvertToArray($post['subDetRutViajRazonSocial'])[$cantRutViaj]), //
											'frecuencia' => strval(checkAndConvertToArray($post['subDetRutViajFrecuencia'])[$cantRutViaj]), //
											'dias' => strval(checkAndConvertToArray($post['subDetRutViajDias'])[$cantRutViaj]), //
											'costoAereo' => strval(checkAndConvertToArray($post['subDetRutViajCostoAereo'])[$cantRutViaj]),
											'costoTransporte' => strval(checkAndConvertToArray($post['subDetRutViajCostoTransporte'])[$cantRutViaj]),
											'costoMovilidadInterna' => strval(checkAndConvertToArray($post['subDetRutViajCostoMovilidadInterna'])[$cantRutViaj]),
											'costoViaticos' => strval(checkAndConvertToArray($post['subDetRutViajCostoViaticos'])[$cantRutViaj]),
											'costoAlojamiento' => strval(checkAndConvertToArray($post['subDetRutViajCostoAlojamiento'])[$cantRutViaj]),
											'cantidadViajes' => strval(checkAndConvertToArray($post['subDetRutViajCantidadViajes'])[$cantRutViaj]),
											'costoVisual' => strval(checkAndConvertToArray($post['subDetRutViajCostoVisual'])[$cantRutViaj]), //
											'gap' => strval(checkAndConvertToArray($post['subDetRutViajGap'])[$cantRutViaj]), //
											'costo' => strval(checkAndConvertToArray($post['subDetRutViajCosto'])[$cantRutViaj]), //
											'cantidadReal' => strval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]), //
											'cantidad' => strval(round(floatval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]) / 12, 2)), //
											'tipo_movil' => strval(checkAndConvertToArray($post['subDetRutViajTipoMovil'])[$cantRutViaj]),
											'subtotal' => strval(checkAndConvertToArray($post['subDetRutViajSubTotal'])[$cantRutViaj]),
										];
										$cantRutViaj++;
									}
									break;
								case COD_DISTRIBUCION['id']:
									$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $post['idCotizacionDetalle'][$k]]);
									$post['cantidadDatosTabla'] = checkAndConvertToArray($post['cantidadDatosTabla']);
									$cantidad = intval($post['cantidadDatosTabla'][$n]);
									$data['subDetalle'][$k] = [];

									for ($it = 0; $it < $cantidad; $it++) {
										$data['subDetalle'][$k][] = [
											'tipoServicio' => strval($post['idTipoServicio'][$n]), // idTipoServicio ... el modal esta con ese KEY por eso le pongo asi.
											'nombre' => strval(checkAndConvertToArray($post['item'])[$n]),
											'cantidad' => strval(checkAndConvertToArray($post['cantidad'])[$n]),
											'costo' 	=> strval(checkAndConvertToArray($post['costoTSCuenta'])[$n]),
											'subtotal' => strval(checkAndConvertToArray($post['totalCuenta'])[$n]),
											'idItem' => strval(checkAndConvertToArray($post['idItem'])[$n]),
											'peso' => strval(checkAndConvertToArray($post['pesoCuenta'])[$n]),
											'idZona' => strval(checkAndConvertToArray($post['idZona'])[$n]),
											'dias' => strval(checkAndConvertToArray($post['dias'])[$n]),
											'gap' => strval(checkAndConvertToArray($post['gap'])[$n]),
											'pesoVisual' => strval(checkAndConvertToArray($post['pesoVisual'])[$n]),
											'costoVisual' => strval(checkAndConvertToArray($post['costoTSVisual'])[$n]),
											'flagItemInterno' => 0, // FALTA LA OPCION DE AGREGAR ITEM DE COMPRAS
											'flagOtrosPuntos' => !empty(checkAndConvertToArray($post['flagOtrosPuntos'])[$k]) ? checkAndConvertToArray($post['flagOtrosPuntos'])[$k] : 0
										];
										$n++;
									}
									break;

								case COD_TEXTILES['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'talla' => $post["tallaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
										'tela' => $post["telaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
										'color' => $post["colorSubItem[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadTextil[{$post['idCotizacionDetalle'][$k]}]"],
										'genero' => $post["generoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => !empty($post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"]) ? $post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"] : NULL,
										'subtotal' => !empty($post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"]) ? $post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"] : NULL,
									]);
									break;

								case COD_TARJETAS_VALES['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'nombre' => $post["descripcionSubItemTarjVal[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadSubItemTarjVal[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["montoSubItemTarjVal[{$post['idCotizacionDetalle'][$k]}]"],
										'subtotal' => floatval($post["cantidadSubItemTarjVal[{$post['idCotizacionDetalle'][$k]}]"]) * floatval($post["montoSubItemTarjVal[{$post['idCotizacionDetalle'][$k]}]"]),
									]);
									break;
								case COD_CONCURSO['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'nombre' => $post["descripcionSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["montoSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"],
										'porcentajeParaCosto' => $post["porcentajeSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"],
										'subtotal' => floatval($post["cantidadSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"]) * floatval($post["montoSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"]) * (1 + (floatval($post["porcentajeSubItemConcurso[{$post['idCotizacionDetalle'][$k]}]"]) / 100)),
									]);
									break;
								case COD_PAGOS_FARMACIAS['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'nombre' => $post["descripcionSubItemPagosFarmacias[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadSubItemPagosFarmacias[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["montoSubItemPagosFarmacias[{$post['idCotizacionDetalle'][$k]}]"],
										'subtotal' => floatval($post["cantidadSubItemPagosFarmacias[{$post['idCotizacionDetalle'][$k]}]"]) * floatval($post["montoSubItemPagosFarmacias[{$post['idCotizacionDetalle'][$k]}]"]),
									]);
									break;
								case COD_SERVICIO_GENERAL['id']:
									// * Si se omite "idCotizacionDetalleSub" se inserta la nueva informacion por eso se hace un delete → no hay columna estado
									$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $post['idCotizacionDetalle'][$k]]);
									$data['subDetalle'][$k] = getDataRefactorizada([
										// 'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'nombre' => $post["descripcionSubItemServicioGeneral[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadSubItemServicioGeneral[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["montoSubItemServicioGeneral[{$post['idCotizacionDetalle'][$k]}]"],
										'subtotal' => floatval($post["cantidadSubItemServicioGeneral[{$post['idCotizacionDetalle'][$k]}]"]) * floatval($post["montoSubItemServicioGeneral[{$post['idCotizacionDetalle'][$k]}]"]),
									]);
									break;
								case COD_TRANSPORTE['id']:
									$data['subDetalle'][$k] = getDataRefactorizada([
										'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
										'cantidad' => $post["cantidadTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'costo' => $post["costoClienteTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'costoVisual' => $post["costoVisualTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'tipo_movil' => $post["tipoMovilTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'porcentajeParaCosto' => $post["porcAdicionalTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'costoDistribucion' => null,
										'dias' => $post["diasTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'cod_departamento' => $post["departamentoTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'cod_provincia' => $post["provinciaTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'cod_distrito' => $post["distritoTransporte[{$post['idCotizacionDetalle'][$k]}]"],
										'idTipoServicioUbigeo' => $post["tipoTransporte[{$post['idCotizacionDetalle'][$k]}]"],
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
							'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
							'idItemEstado' => $post['idEstadoItemForm'][$k],
							'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
							'cantidad' => $post['cantidadForm'][$k],
							'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
							'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
							'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
							'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
							'idCotizacionDetalleEstado' => 1,
							'fechaCreacion' => getActualDateTime(),
							'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
							'cotizacionInterna' => !empty($post['cotizacionInternaForm'][$k]) ? $post['cotizacionInternaForm'][$k] : 0,
							'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
							'flagCuenta' => !empty($post['flagCuenta'][$k]) ? $post['flagCuenta'][$k] : 0,
							'flagRedondear' => !empty($post['flagRedondearForm'][$k]) ? $post['flagRedondearForm'][$k] : 0,
							'flagAlternativo' => !empty($post['itemTextoPdf'][$k]) ? '1' : '0',
							'nombreAlternativo' => !empty($post['itemTextoPdf'][$k]) ? $post['itemTextoPdf'][$k] : NULL,
							'caracteristicasProveedor' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
							'requiereOrdenCompra' => !empty($post['flagGenerarOC'][$k]) ? $post['flagGenerarOC'][$k] : 0,
							'flagPackingSolicitado' => !empty($post['flagPackingSolicitado'][$k]) ? $post['flagPackingSolicitado'][$k] : 0,
							'costoPacking' => !empty($post['costoPacking'][$k]) ? $post['costoPacking'][$k] : 0,
							'flagMovilidadSolicitado' => !empty($post['flagMovilidadSolicitado'][$k]) ? $post['flagMovilidadSolicitado'][$k] : 0,
							'costoMovilidad' => !empty($post['costoMovilidad'][$k]) ? $post['costoMovilidad'][$k] : 0,
							'flagPersonalSolicitado' => !empty($post['flagPersonalSolicitado'][$k]) ? $post['flagPersonalSolicitado'][$k] : 0,
							'costoPersonal' => !empty($post['costoPersonal'][$k]) ? $post['costoPersonal'][$k] : 0,
							'flagMostrarDetalle' => !empty($post['flagMostrarDetalle'][$k]) ? $post['flagMostrarDetalle'][$k] : 0,
							'cantPdv' => !empty($post['cantidadPDV'][$k]) ? $post['cantidadPDV'][$k] : 0,

						];

						switch ($post['tipoItemForm'][$k]) {
							case COD_RUTAS_VIAJERAS['id']:
								$post['cantidadItemsRutasViajeras'] = checkAndConvertToArray($post['cantidadItemsRutasViajeras']);
								$cantidad = intval($post['cantidadItemsRutasViajeras'][$cantRutViaj]);
								$data['subDetalle'][$k] = [];

								for ($it = 0; $it < $cantidad; $it++) {
									$name = 'Ruta Viajera de ' . checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj];
									$name .= ' a ';
									$destinos = explode('-', checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]);
									if (count($destinos) == 1) $name .= $destinos[0];
									else {
										foreach ($destinos as $kr => $ruta) {
											if ($kr != 0) $name .= ' / ' . $destinos[$kr - 1] . ' a ';
											$name .= $destinos[$kr];
										}
									}
									$data['subDetalle'][$k][] = [
										'nombre' => $name,
										'origen' => strval(checkAndConvertToArray($post['subDetRutViajOrigen'])[$cantRutViaj]),
										'destino' => strval(checkAndConvertToArray($post['subDetRutViajDestino'])[$cantRutViaj]),
										'responsable' => strval(checkAndConvertToArray($post['subDetRutViajResponsable'])[$cantRutViaj]),
										'cargo' => strval(checkAndConvertToArray($post['subDetRutViajCargo'])[$cantRutViaj]),
										'dni' => strval(checkAndConvertToArray($post['subDetRutViajDni'])[$cantRutViaj]),
										'razonSocial' => strval(checkAndConvertToArray($post['subDetRutViajRazonSocial'])[$cantRutViaj]), //
										'frecuencia' => strval(checkAndConvertToArray($post['subDetRutViajFrecuencia'])[$cantRutViaj]), //
										'dias' => strval(checkAndConvertToArray($post['subDetRutViajDias'])[$cantRutViaj]), //
										'costoAereo' => strval(checkAndConvertToArray($post['subDetRutViajCostoAereo'])[$cantRutViaj]),
										'costoTransporte' => strval(checkAndConvertToArray($post['subDetRutViajCostoTransporte'])[$cantRutViaj]),
										'costoMovilidadInterna' => strval(checkAndConvertToArray($post['subDetRutViajCostoMovilidadInterna'])[$cantRutViaj]),
										'costoViaticos' => strval(checkAndConvertToArray($post['subDetRutViajCostoViaticos'])[$cantRutViaj]),
										'costoAlojamiento' => strval(checkAndConvertToArray($post['subDetRutViajCostoAlojamiento'])[$cantRutViaj]),
										'cantidadViajes' => strval(checkAndConvertToArray($post['subDetRutViajCantidadViajes'])[$cantRutViaj]),
										'costoVisual' => strval(checkAndConvertToArray($post['subDetRutViajCostoVisual'])[$cantRutViaj]), //
										'gap' => strval(checkAndConvertToArray($post['subDetRutViajGap'])[$cantRutViaj]), //
										'costo' => strval(checkAndConvertToArray($post['subDetRutViajCosto'])[$cantRutViaj]), //
										'cantidadReal' => strval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]), //
										'cantidad' => strval(round(floatval(checkAndConvertToArray($post['subDetRutViajCantidadReal'])[$cantRutViaj]) / 12, 2)), //
										'tipo_movil' => strval(checkAndConvertToArray($post['subDetRutViajTipoMovil'])[$cantRutViaj]),
										'subtotal' => strval(checkAndConvertToArray($post['subDetRutViajSubTotal'])[$cantRutViaj]),
									];
									$cantRutViaj++;
								}
								break;
							case COD_DISTRIBUCION['id']:
								$post['cantidadDatosTabla'] = checkAndConvertToArray($post['cantidadDatosTabla']);
								$cantidad = intval($post['cantidadDatosTabla'][$itemsTipoDistribucion++]);
								$subDetalleInsert[$k] = [];

								for ($it = 0; $it < $cantidad; $it++) {
									$subDetalleInsert[$k][] = [
										'tipoServicio' => strval($post['idTipoServicio'][$n]),
										'nombre' => strval(checkAndConvertToArray($post['item'])[$n]),
										'cantidad' => strval(checkAndConvertToArray($post['cantidad'])[$n]),
										'costo' 	=> strval(checkAndConvertToArray($post['costoTSCuenta'])[$n]),
										'subtotal' => strval(checkAndConvertToArray($post['totalCuenta'])[$n]),
										'idItem' => strval(checkAndConvertToArray($post['idItem'])[$n]),
										'peso' => strval(checkAndConvertToArray($post['pesoCuenta'])[$n]),
										'idZona' => strval(checkAndConvertToArray($post['idZona'])[$n]),
										'dias' => strval(checkAndConvertToArray($post['dias'])[$n]),
										'gap' => strval(checkAndConvertToArray($post['gap'])[$n]),
										'pesoVisual' => strval(checkAndConvertToArray($post['pesoVisual'])[$n]),
										'costoVisual' => strval(checkAndConvertToArray($post['costoTSVisual'])[$n]),
										'flagItemInterno' => 0,
										'flagOtrosPuntos' => !empty(checkAndConvertToArray($post['flagOtrosPuntos'])[$k]) ? checkAndConvertToArray($post['flagOtrosPuntos'])[$k] : 0
									];
									$n++;
								}
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
									'nombre' => $post["descripcionSubItemTarjVal[$k]"],
									'cantidad' => $post["cantidadSubItemTarjVal[$k]"],
									'costo' => $post["montoSubItemTarjVal[$k]"],
									'subtotal' => floatval($post["cantidadSubItemTarjVal[$k]"]) * floatval($post["montoSubItemTarjVal[$k]"])
								]);
								break;

							case COD_TRANSPORTE['id']:
								$subDetalleInsert[$k] = getDataRefactorizada([
									'cantidad' => $post["cantidadTransporte[$k]"],
									'costo' => $post["costoClienteTransporte[$k]"],
									'costoVisual' => $post["costoVisualTransporte[$k]"],
									'tipo_movil' => $post["tipoMovilTransporte[$k]"],
									'porcentajeParaCosto' => $post["porcAdicionalTransporte[$k]"],
									'costoDistribucion' => null,
									'cod_departamento' => $post["departamentoTransporte[$k]"],
									'cod_provincia' => $post["provinciaTransporte[$k]"],
									'cod_distrito' => $post["distritoTransporte[$k]"],
									'dias' => $post["diasTransporte[$k]"],
									'idTipoServicioUbigeo' => $post["tipoTransporte[$k]"],
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
									'subTotal' => !empty($subItem['subtotal']) ? $subItem['subtotal'] : NULL,
									'cantidadPdv' => !empty($subItem['cantidadPdv']) ? $subItem['cantidadPdv'] : NULL,
									'idItem' => !empty($subItem['idItem']) ? $subItem['idItem'] : NULL,
									'reembarque' => !empty($subItem['reembarque']) ? $subItem['reembarque'] : NULL,
									'idDistribucionTachado' => !empty($subItem['idDistribucionTachado']) ? $subItem['idDistribucionTachado'] : NULL,
									'requiereOrdenCompra' => !empty($subItem['requiereOrdenCompra']) ? $subItem['requiereOrdenCompra'] : NULL,
									'idProveedorDistribucion' => !empty($subItem['idProveedorDistribucion']) ? $subItem['idProveedorDistribucion'] : NULL,
									'cantidadReal' => !empty($subItem['cantidadReal']) ? $subItem['cantidadReal'] : NULL,
									'peso' => !empty($subItem['peso']) ? $subItem['peso'] : NULL,
									'idZona' => !empty($subItem['idZona']) ? $subItem['idZona'] : NULL,
									'dias' => !empty($subItem['dias']) ? $subItem['dias'] : NULL,
									'gap' => !empty($subItem['gap']) ? $subItem['gap'] : NULL,
									'pesoVisual' => !empty($subItem['pesoVisual']) ? $subItem['pesoVisual'] : NULL,
									'costoVisual' => !empty($subItem['costoVisual']) ? $subItem['costoVisual'] : NULL,
									'tipo_movil' => !empty($subItem['tipo_movil']) ? $subItem['tipo_movil'] : NULL,
									'porcentajeParaCosto' => !empty($subItem['porcentajeParaCosto']) ? $subItem['porcentajeParaCosto'] : NULL,
									'flagItemInterno' => !empty($subItem['flagItemInterno']) ? $subItem['flagItemInterno'] : NULL,
									'flagOtrosPuntos' => !empty($subItem['flagOtrosPuntos']) ? $subItem['flagOtrosPuntos'] : NULL,
									'cod_departamento' => !empty($subItem['cod_departamento']) ? $subItem['cod_departamento'] : NULL,
									'cod_provincia' => !empty($subItem['cod_provincia']) ? $subItem['cod_provincia'] : NULL,
									'cod_distrito' => !empty($subItem['cod_distrito']) ? $subItem['cod_distrito'] : NULL,
									'idTipoServicioUbigeo' => !empty($subItem['idTipoServicioUbigeo']) ? $subItem['idTipoServicioUbigeo'] : NULL,
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
			}
			if (!empty($data)) {
				$data['archivoEliminado'] = isset($post['archivosEliminados']) ? $post['archivosEliminados'] : null;
				$data['tabla'] = 'compras.cotizacionDetalle';
				$data['where'] = 'idCotizacionDetalle';
				$updateDetalle = $this->model->actualizarCotizacionDetalleArchivos($data);
				if (!empty($post['subItemEliminado'])) {
					foreach ($post['subItemEliminado'] as $key => $value) {
						$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalleSub' => $value]);
					}
				}
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

		if (!$update['estado'] || !$estadoEmail) {
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
			'assets/custom/js/core/gestion',
			'assets/custom/js/viewAgregarCotizacion'
		);

		$config['data']['btnEnviar'] = false;
		$config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
		//echo $this->db->last_query(); exit();
		if ($config['data']['cotizacion']['idCotizacionEstado'] == '1') {
			$config['data']['btnEnviar'] = true;
		}
		$config['data']['costo'] = $this->model->obtenerCosto(['id' => $idCotizacion])['query']->row_array();
		$config['data']['anexos'] = $this->model->obtenerInformacionCotizacionArchivos(['idCotizacion' => $idCotizacion, 'anexo' => true])['query']->result_array();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionTarifario'] = $this->model->obtenerCotizacionDetalleTarifario(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		//echo $this->db->last_query(); exit();
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['proveedorDistribucion'] = $this->model_proveedor->obtenerProveedorDistribucion()->result_array();
		$config['data']['costoDistribucion'] = $this->model->obtenerCostoDistribucion()['query']->row_array();
		$config['data']['ordenServicio'] = $this->model->obtenerOrdenServicio()['query']->result_array();
		$config['data']['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();
		$config['data']['departamento'] = $this->db->distinct()->select('tzt.cod_departamento, u.departamento')->from('compras.tarifarioZonaTransporte tzt')->join('General.dbo.ubigeo u', 'u.cod_departamento = tzt.cod_departamento', 'INNER')->where('tzt.estado', 1)->order_by('u.departamento')->get()->result_array();
		$config['data']['listProveedor'] = $this->db->order_by('razonSocial')->get_where('compras.proveedor', ['idProveedorEstado' => 2, 'flagTarjetasVales' => 1])->result_array();

		$cotizacionDetalleSub = $this->model->obtenerInformacionDetalleCotizacionSubdis(
			[
				'idCotizacion' => $idCotizacion,
			]
		)['query']->result_array();

		// * Para rutas Viajeras
		$listIdCotizacionDetalle = refactorizarDataHT(
			[
				"data" => $this->db->get_where('compras.cotizacionDetalle', ['idCotizacion' => $idCotizacion, 'idItemTipo' => COD_RUTAS_VIAJERAS['id']])->result_array(),
				"value" => "idCotizacionDetalle"
			]
		);
		if (!empty($listIdCotizacionDetalle)) {
			foreach ($listIdCotizacionDetalle as $idCotizacionDetalle) {
				$this->db->select('*')
					->select('frecuencia as idFrecuencia')
					->select('(SELECT nombre FROM dbo.frecuencia WHERE idFrecuencia = frecuencia) as frecuencia', false)
					->select('(
						isnull(costoAereo, 0) +
						isnull(costoTransporte, 0) +
						isnull(costoMovilidadInterna, 0) +
						isnull(costoViaticos, 0) +
						isnull(costoAlojamiento, 0)
					) * cantidadViajes as totalMensual')
					->select('costoVisual * (100 + isnull(gap, 0)) / 100 as total')
					->select('(SELECT frecuenciaAnual FROM dbo.frecuencia WHERE idFrecuencia = frecuencia) as frecuenciaAnual', false)
					->select('subtotal as cuenta');
				$rpta = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $idCotizacionDetalle])->result_array();
				$config['data']['dataRutasViajeras'][$idCotizacionDetalle] = $rpta;
			}
		}
		// * Fin: Para Rutas Viajeras

		foreach ($config['data']['cotizacionTarifario'] as $k => $v) {
			$config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] = $this->db->distinct()->select('idItem, isnull(peso, 0) as pesoCuenta, isnull(pesoVisual, 0) as pesoVisual, flagItemInterno')->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			$config['data']['cotizacionDetalleSubZonas'][$v['idCotizacionDetalle']] = $this->db->distinct()->select('idZona, flagOtrosPuntos, isnull(dias, 0) as dias, gap, costo, idTipoServicio, costoVisual, tipo_movil, reembarque')->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			$i = 0;
			foreach ($config['data']['cotizacionDetalleSubZonas'][$v['idCotizacionDetalle']] as $kz => $vz) {
				$psTV = 0;
				$psTC = 0;

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['zona'] = $this->model->getZonas(['otroAlmacen' => $vz['flagOtrosPuntos'], 'idZona' => $vz['idZona']])->row_array()['nombre'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['dias'] = $vz['dias'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['gap'] = $vz['gap'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['reembarque'] = $vz['reembarque'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['tipoServicio'] = $this->db->where('idTipoServicio', $vz['idTipoServicio'])->get('compras.tipoServicio')->row_array()['nombre'];
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['costoTSVisual'] = strval(round($vz['costoVisual'], 2));
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['costoTSCuenta'] = strval(round($vz['costo'], 2));

				foreach ($config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] as $ki => $vi) {
					$cantItm = $this->db->where('idItem', $vi['idItem'])->where('idZona', $vz['idZona'])->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->row_array()['cantidad'];

					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['item' . $ki] = $cantItm;
					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalVisual' . $ki] = strval(round(floatval($vi['pesoVisual']) * floatval($cantItm), 4));
					$psTV += (floatval($vi['pesoVisual']) * floatval($cantItm));

					$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalCuenta' . $ki] = strval(round(floatval($vi['pesoCuenta']) * floatval($cantItm), 4));
					$psTC += (floatval($vi['pesoCuenta']) * floatval($cantItm));
				}

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalVisual'] = strval(round($psTV, 4));
				$pgV = $config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoGapVisual'] = strval(floatval($psTV) * (100 + floatval($vz['gap'])) / 100);
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['totalFinalVisual'] = strval(floatval($pgV) * floatval($vz['costoVisual']));

				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoTotalCuenta'] = strval(round($psTC, 4));
				$pgC = $config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['pesoGapCuenta'] = strval(floatval($psTC) * (100 + floatval($vz['gap'])) / 100);
				$config['data']['cotizacionDetalleSubMix'][$v['idCotizacionDetalle']][$i]['totalFinalCuenta'] = strval(floatval($pgC) * floatval($vz['costo']));
				$i++;
			}

			foreach ($config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']] as $ki => $vi) {
				$config['data']['cotizacionDetalleSubItems'][$v['idCotizacionDetalle']][$ki]['itemLogistica'] = $this->db->where('idArticulo', $vi['idItem'])->get('VisualImpact.logistica.articulo')->row_array()['nombre'];
			}
		}

		foreach ($cotizacionDetalleSub as $sub) {
			$sub['provincia'] = '';
			if (!empty($sub['cod_provincia'])) {
				$sub['provincia'] = $this->db->get_where('General.dbo.ubigeo', ['cod_departamento' => $sub['cod_departamento'], 'cod_provincia' => $sub['cod_provincia']])->row_array()['provincia'];
			}
			$sub['distrito'] = '';
			if (!empty($sub['cod_distrito'])) {
				$sub['distrito'] = $this->db->get_where('General.dbo.ubigeo', ['cod_departamento' => $sub['cod_departamento'], 'cod_provincia' => $sub['cod_provincia'], 'cod_distrito' => $sub['cod_distrito']])->row_array()['distrito'];
			}
			$sub['tipoServicioUbigeo'] = '';
			if (!empty($sub['idTipoServicioUbigeo'])) {
				$sub['tipoServicioUbigeo'] = $this->db->get_where('compras.tipoServicioUbigeo', ['idTipoServicioUbigeo' => $sub['idTipoServicioUbigeo']])->row_array()['nombreAlternativo'];
			}
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
			$dcds = [];
			if ($sub['idItemTipo'] == COD_DISTRIBUCION['id']) {
				$dcds = $this->db->where('idCotizacionDetalle', $sub['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			}
			foreach ($dcds as $kCds => $vCds) {
				$reembarque = floatval($vCds['reembarque']);
				$dcds[$kCds]['reembarque'] = $reembarque;
				$dcds[$kCds]['zona'] = $this->model->getZonas(['otroAlmacen' => $vCds['flagOtrosPuntos'], 'idZona' => $vCds['idZona']])->row_array()['nombre'];
				$dcds[$kCds]['item'] = $vCds['nombre'];
				$dcds[$kCds]['tipoServicio'] = $this->db->where('idTipoServicio', $vCds['idTipoServicio'])->get('compras.tipoServicio')->row_array()['nombre'];
				$dcds[$kCds]['pesoTotalVisual'] = floatval($vCds['pesoVisual']) * floatval($vCds['cantidad']);
				$dcds[$kCds]['pesoGapVisual'] = floatval($dcds[$kCds]['pesoTotalVisual']) * (100 + floatval($vCds['gap'])) / 100;
				$dcds[$kCds]['costoTSVisual'] = $vCds['costoVisual'];
				$dcds[$kCds]['totalVisual'] = floatval($dcds[$kCds]['pesoGapVisual']) * floatval($vCds['costoVisual']) + $reembarque;
				$dcds[$kCds]['pesoCuenta'] = $vCds['peso'];
				$dcds[$kCds]['pesoTotalCuenta'] = floatval($vCds['peso']) * floatval($vCds['cantidad']);
				$dcds[$kCds]['pesoGapCuenta'] = floatval($dcds[$kCds]['pesoTotalCuenta']) * (100 + floatval($vCds['gap'])) / 100;
				$dcds[$kCds]['costoTSCuenta'] = $vCds['costo'];
				$dcds[$kCds]['totalCuenta'] = floatval($dcds[$kCds]['pesoGapCuenta']) * floatval($vCds['costo']) + $reembarque;
			}
			$config['data']['tablaGen'][$sub['idCotizacionDetalle']] = '';
			if ($sub['idItemTipo'] == COD_DISTRIBUCION['id']) {
				$data = [
					'cabecera' => [
						'idZona' => '',
						'zona' => 'ZONA',
						'dias' => 'DIAS',
						'idItem' => '',
						'item' => 'ITEM',
						'cantidad' => 'CANTIDAD',
						'gap' => 'GAP',
						'reembarque' => 'REEMBARQUE',
						'idTipoServicio' => '',
						'tipoServicio' => 'TIPO SERVICIO',
						'pesoVisual' => 'PESO VISUAL',
						'pesoTotalVisual' => 'PESO TOTAL VISUAL',
						'pesoGapVisual' => 'PESO GAP VISUAL',
						'costoTSVisual' => 'COSTO TS VISUAL',
						'totalVisual' => 'COSTO TOTAL VISUAL',
						'pesoCuenta' => 'PESO CUENTA',
						'pesoTotalCuenta' => 'PESO TOTAL CUENTA',
						'pesoGapCuenta' => 'PESO GAP CUENTA',
						'costoTSCuenta' => 'COSTO TS CUENTA',
						'totalCuenta' => 'COSTO TOTAL CUENTA',
					],
					'datos' => $dcds,
					'classP' => 'tb_data_'
				];
				$config['data']['tablaGen'][$sub['idCotizacionDetalle']] = htmlTableValueArray($data);
				$config['data']['totalTablaGen'][$sub['idCotizacionDetalle']] = sumarArrayPorCabecera($dcds, 'totalCuenta');
			}
		}
		foreach ($archivos as $archivo) {
			$config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
		}

		foreach ($cotizacionProveedoresVista as $cotizacionProveedorVista) {
			$config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
		}
		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['estadoProveedor' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio = $this->model_item->obtenerItemServicio();
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
		$config['data']['tipoMoneda'] = $this->model->obtenertipoMoneda()['query']->result_array();
		$config['data']['tipoServicioCotizacion'] = $this->model->obtenerTipoServicioCotizacion()['query']->result_array();

		$config['data']['ordenServicio'] = $this->model->obtenerOrdenServicio()['query']->result_array();
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

		$cotizacionDetalleSub = $this->model->obtenerInformacionDetalleCotizacionSubdis(
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
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['estadoProveedor' => 2])['query']->result_array();

		foreach ($proveedores as $proveedor) {
			$config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
		}

		$itemServicio = $this->model_item->obtenerItemServicio();
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
		$config['data']['nombre'] = ' [copia]';
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
				'idItemEstado' => !empty($post['idItemForm'][$k]) ? 2 : $post['idEstadoItemForm'][$k],
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
		// $post['feeForm'] = checkAndConvertToArray($post['feeForm']);
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
				$validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem, 'nombre' => $nameItem]);
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
				// 'fee' => !empty($post['feeForm'][$k]) ? $post['feeForm'][$k] : NULL,
				'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
				'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
				'idItemEstado' => !empty($itemsSinProveedor[$idItem]) ? 2 : $post['idEstadoItemForm'][$k],
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

	public function anularCotizacion()
	{
		$result = $this->result;
		$json = json_decode($this->input->post('data'));
		$estadoAnulado = 12;
		$datos = [
			'estado' => 0,
			'idCotizacionEstado' => $estadoAnulado
		];
		$where = "idCotizacion = " . $json;
		$res = $this->model->actualizarSimple($this->database, $where, $datos);
		if ($res) {
			$dataAuditoria = [
				'idCotizacionEstado' => $estadoAnulado,
				'idCotizacion' => $json,
				'fechaReg' => date('Y-m-d'),
				'horaReg' => date('H:i:s'),
				'idUsuarioReg' => $this->idUsuario,
				'estado' => 1,
			];
			$this->model->insert($this->databaseAuditoria, $dataAuditoria);
			$result['result'] = 1;
			$result['msg']['content'] = getMensajeGestion('anulacionExitosa');
		} else {
			$result['result'] = 0;
			$result['msg']['content'] = getMensajeGestion('anulacionErronea');
		}

		echo json_encode($result);
	}

	public function anulacionInfo()
	{
		$result = $this->result;
		$idCotizacion = json_decode($this->input->post('data'));
		$item = $this->model->infoHistorialCotizacionDescende($idCotizacion);
		if (!empty($item)) {
			$dataParaVista['data'] = [
				'nombreCotizacion' => $item[0]['nombreCotizacion'],
				'codigoCotizacion' => $item[0]['codigoCotizacion'],
				'fechaCreacion' => $item[0]['fechaCreacion'],
				'nombreEstado' => $item[0]['nombreEstado'],
				'nombreUsuario' => $item[0]['nombreUsuario'],
				'apellidoUsuario' => $item[0]['apellidoUsuario'],
				'fechaRegistro' => $item[0]['fechaRegistro'],
				'horaRegistro' => $item[0]['horaRegistro'],
			];

			$html = $this->load->view("modulos/Cotizacion/viewAnulacionInfo", $dataParaVista, true);
			$result['result'] = 1;
			$result['msg']['content'] = $html;
			$result['msg']['title'] = 'Información de anulación';
		} else {
			$result['msg']['title'] = 'Información de anulación';
			$result['result'] = 1;
			$result['msg']['content'] = 'No se ha podido encontrar información sobre esta anulación';
		}

		echo json_encode($result);
	}

	public function obtenerItemsLogistica()
	{
		$idCuenta = $this->input->post('cuenta');
		$idCentroCosto = $this->input->post('centroCosto');

		$data = $this->model_item->obtenerItemsCuenta($idCuenta)->result_array();
		$html = '';
		foreach ($data as $row) {
			$html .= '<option value="' . $row['value'] . '" data-option ="' . $row['pesoLogistica'] . '">' . $row['label'] . '</option>';
		}
		echo $html;
	}

	public function obtenerPesoLogistica()
	{
		$idCuenta = $this->input->post('cuenta');
		$idArticulo = $this->input->post('idArticulo');
		$data = $this->model_item->obtenerItemsCuenta($idCuenta, $idArticulo)->result_array();
		$html = '';
		foreach ($data as $row) {
			$html = $row['pesoLogistica'];
		}
		echo $html;
	}

	public function cargos()
	{

		$data = json_decode($this->input->post('data'), true);
		$idCuenta = $data['idCuenta'];
		$idCentro = $data['idCentro'];
		$result = $this->result;

		$data = $this->model->obtener_cargos($idCentro)->result_array();
		$html = '<select class="ui clearable dropdown simpleDropdown cargo_personal" id="cargo_personal" name="cargo_personal" >';
		$html .= '<option value="0">Seleccione</option>';
		foreach ($data as $row) {
			$html .= '<option value="' . $row['idCargoTrabajo'] . '">' . $row['nombre'] . '</option>';
		}
		$html .= "</select>";

		$result['result'] = 1;
		$result['data'] = $html;

		echo json_encode($result);
	}

	public function obtener_sueldos()
	{
		$data = json_decode($this->input->post('data'), true);

		$idCuenta = $data['idCuenta'];
		$idCentro = $data['idCentro'];
		$idCargo = $data['idCargo'];
		$result = $this->result;

		$data = $this->model->obtener_sueldos($idCuenta, $idCentro, $idCargo)->result_array();
		$total = count($data);
		$sueldo = 0;
		$movilidad = 0;
		$refrigerio = 0;
		$incentivo = 0;
		$tipo_cargo_sueldo = 0;
		$asignacionFamiliar = 102.5;
		if ($total == 1) {
			foreach ($data as $row) {
				$tipo_cargo_sueldo = 0;
				$sueldo = $row['sueldo'];
				$movilidad = $row['movilidad'];
				$refrigerio = $row['refrigerio'];
				$incentivo = $row['comisionFija'];
				$asignacionFamiliar = $row['asignacionFamiliar'];
			}
		}

		$result['result'] = 1;
		$result['tipo_cargo_sueldo'] = $tipo_cargo_sueldo;
		$result['sueldo'] = $sueldo;
		$result['refrigerio'] = $refrigerio;
		$result['movilidad'] = $movilidad;
		$result['incentivo'] = $incentivo;
		$result['asignacionFamiliar'] = $asignacionFamiliar;
		echo json_encode($result);
	}

	public function obtener_conceptos_adicionales()
	{

		$data = json_decode($this->input->post('data'), true);
		$id = $data['id'];
		$cantidad = $data['cantidad'];
		$posicion = $data['posicion'];
		// Emplear la posicion para modificar los name del subdetalle;
		$adicionales = $this->model->obtener_conceptos_adicionales($id, $cantidad)->result_array();
		$html = "";
		$total_adicional = 0;
		$html .= "<table style='width:100%;'>";
		$html .= "
				<tr>
					<th style='width:210px;'></th>
					<th></th>
					<th><div style='padding: 0px 0px 0px 20px;'>Cantidad</div></th>
					<th><div style='padding: 0px 0px 0px 20px;'>Frecuencia</div></th>
					<th><div style='padding: 0px 0px 0px 20px;'>Costo X Persona</div></th>
					<th><div style='padding: 0px 0px 0px 20px;'>Costo Total</div></th>
				</tr>
			";
		foreach ($adicionales as $row) {
			$html .= "<tr>";
			$html .= '<td><div style="padding:15px;">' . $row['nombre'] . '<input type="hidden" name="idCampoPersonal[' . $posicion . ']" value="' . $row['idConcepto'] . '"></div></td>';
			$html .= '<td><div style="padding:15px;"><select class="consideracionPersonal" name="incluirCampoPersonal[' . $posicion . ']"><option value="1">SI</option><option value="2">NO</option></select></div></td>';
			$html .= '<td><div style="padding:15px;"><input class="cntPersonal keyUpChange onlyNumbers" onchange="Cotizacion.multiplicarCantidadCostoPersonal(this);" name="cantidadCampoPersonal[' . $posicion . ']" value="' . $cantidad . '"></div></td>';
			$html .= '<td>
							<div style="padding:15px;">
								<select name="frecuenciaCampoPersonal[' . $posicion . ']">
									<option value="1">Mensual</option>
									<option value="2">Bimestral</option>
									<option value="3">Trimestral</option>
									<option value="4">Semestral</option>
									<option value="5">Anual</option>
								</select>
							</div>
						</td>';
			$html .= '<td><div style="padding:15px;"><input class="cstPersonal keyUpChange onlyNumbers" onchange="Cotizacion.multiplicarCantidadCostoPersonal(this);" name="costoCampoPersonal[' . $posicion . ']" value="' . $row['costo'] . '"></div></td>';
			$html .= '<td><div style="padding:15px;"><input class="sbtPersonal" name="costoTotalCampoPersonal[' . $posicion . ']" value="' . $row['total'] . '" readonly></div></td>';
			$html .= "</tr>";
			$total_adicional = $row['total_final'];
		}
		$html .= "</table>";

		$result['data'] = $html;
		$result['total_adicional'] = $total_adicional;
		echo json_encode($result);
	}

	function formularioItemsPersonal()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idCotizacion = $post['idCotizacion'];
		$dataParaVista['data'] = $this->model->obtenerDetalleItemPersonal($idCotizacion)->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Visualizar Items Personal';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/verItemsPersonal", $dataParaVista, true);

		echo json_encode($result);
	}

	function formularioRequerimientoPersonal()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idCotizacionDetalle = $post['idCotizacionDetalle'];
		$dataParaVista = array();
		$dataParaVista['idCotizacionDetalle'] = $idCotizacionDetalle;
		$result['result'] = 1;
		$result['msg']['title'] = 'Generar Requerimiento';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/generarRequerimientoPersonal", $dataParaVista, true);

		echo json_encode($result);
	}

	function guardarCompletarDatos()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$result['result'] = 0;
		$result['msg']['title'] = 'Información actualizada';
		$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'Se actualizo correctamente']);

		if ($this->db->update('compras.cotizacion', ['fechaSustento' => $post['fechaSustento'], 'fechaEnvioFinanzas' => $post['fechaEnvioFinanzas'], 'aprovador' => $post['aprovador'], 'montoSincerado' => $post['montoSincerado']], ['idCotizacion' => $post['idCotizacion']]))
			$result['result'] = 1;

		$insertLinea = [];
		if (is_array($post['lineaNum'])) {
			foreach ($post['lineaNum'] as $item) {
				$insertLinea[] = [
					'idCotizacion' => $post['idCotizacion'],
					'cantidad' => $item
				];
			}
		} else {
			$insertLinea[] = [
				'idCotizacion' => $post['idCotizacion'],
				'cantidad' => $post['lineaNum']
			];
		}

		$this->model->insertarMasivo('compras.cotizacionLinea', $insertLinea);
		echo json_encode($result);
	}

	public function formularioActualizarValidez()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista['idCotizacion'] = $post['idCotizacion'];
		$dataParaVista['diasValidez'] = $this->model->obtenerCotizacion($post)['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Validez';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/viewFormularioActualizarValidez", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarValidez()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data['update'] = [

			'diasValidez' => $post['diasValidez']

		];

		$data['tabla'] = 'compras.cotizacion';
		$data['where'] = ['idCotizacion' => $post['idCotizacion']];
		$insert = $this->model->actualizarCotizacion($data);

		if (!$insert) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
			goto respuesta;
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		respuesta:
		echo json_encode($result);
	}

	public function duplicarCotizacionGeneral()
	{
		$result = $this->result;
		$post = $this->input->post('idCotizacion');
		$this->db->trans_begin();

		$cotizacion = $this->model->datosCotizacion($post)['query']->result_array();
		$cotizacionDetalle = $this->model->obtenerCotizacionDetallada($post)['query']->result_array();


		$insertCotizacion = [
			'nombre' => $cotizacion[0]['nombre'] . " - COPIA",
			'fechaDeadline' => $cotizacion[0]['fechaDeadline'],
			'fechaRequerida' => $cotizacion[0]['fechaRequerida'],
			'fechaTermino' => $cotizacion[0]['fechaTermino'],
			'idCuenta' => $cotizacion[0]['idCuenta'],
			'idCentroCosto' => $cotizacion[0]['idCentroCosto'],
			'idCotizacionEstado' => 1,
			'idPrioridad' => $cotizacion[0]['idPrioridad'],
			'idSolicitante' => $cotizacion[0]['idSolicitante'],
			'motivo' => $cotizacion[0]['motivo'],
			'comentario' => $cotizacion[0]['comentario'],
			'flagIgv' => $cotizacion[0]['flagIgv'],
			'fee' => $cotizacion[0]['fee'],
			'total' => $cotizacion[0]['total'],
			'fechaEmision' => getActualDateTime(),
			'idUsuarioReg' => $this->idUsuario,
			'estado' => 1,
			'codOrdenCompra' => $cotizacion[0]['codOrdenCompra'],
			'motivoAprobacion' => $cotizacion[0]['motivoAprobacion'],
			'total_fee' => $cotizacion[0]['total_fee'],
			'total_fee_igv' => $cotizacion[0]['total_fee_igv'],
			'diasValidez' => $cotizacion[0]['diasValidez'],
			'mostrarPrecio' => $cotizacion[0]['mostrarPrecio'],
			'montoOrdenCompra' => $cotizacion[0]['montoOrdenCompra'],
			'feePersonal' => $cotizacion[0]['feePersonal'],
			'numeroGR' => $cotizacion[0]['numeroGR'],
			'fechaClienteOC' => $cotizacion[0]['fechaClienteOC'],
			'idOrdenServicio' => $cotizacion[0]['idOrdenServicio'],
			'fechaGR' => $cotizacion[0]['fechaGR'],
			'demo' => $cotizacion[0]['demo'],
			'fechaSustento' => $cotizacion[0]['fechaSustento'],
			'fechaEnvioFinanzas' => $cotizacion[0]['fechaEnvioFinanzas'],
			'aprovador' => $cotizacion[0]['aprovador'],
			'montoSincerado' => $cotizacion[0]['montoSincerado'],
			'idTipoServicioCotizacion' => $cotizacion[0]['idTipoServicioCotizacion'],
			'fechaEnvioCliente' => null,
			'usurioEnvioCliente' => null,
			'idTipoMoneda' => $cotizacion[0]['idTipoMoneda']
		];
		$this->db->insert('compras.cotizacion', $insertCotizacion);
		$idNewCotizacion = $this->db->insert_id();
		$update = ['codCotizacion' => formularCodCotizacion($idNewCotizacion)];
		$this->db->update('compras.cotizacion', $update, ['idCotizacion' => $idNewCotizacion]);

		foreach ($cotizacionDetalle as $k => $v) {
			$insertarCotizacionDetalle = [
				'idCotizacion' => $idNewCotizacion,
				'idItem' => $v['idItem'],
				'idItemTipo' => $v['idItemTipo'],
				'nombre' => $v['nombre'],
				'caracteristicas' => $v['caracteristicas'],
				'idItemEstado' => $v['idItemEstado'],
				'idProveedor' => $v['idProveedor'],
				'cantidad' => $v['cantidad'],
				'costo' => $v['costo'],
				'subtotal' => $v['subtotal'],
				'gap' => $v['gap'],
				'precio' => $v['precio'],
				'idCotizacionDetalleEstado' => $v['idCotizacionDetalleEstado'],
				//'fechaCreacion' => getDateTime($v['fechaCreacion']),
				'fechaCreacion' => getActualDateTime(),
				'fechaModificacion' => null,
				'enlaces' => $v['enlaces'],
				'cotizacionInterna' => $v['cotizacionInterna'],
				'caracteristicasCompras' => $v['caracteristicasCompras'],
				'idUnidadMedida' => $v['idUnidadMedida'],
				'costoAnterior' => $v['costoAnterior'],
				'flagCuenta' => $v['flagCuenta'],
				'flagRedondear' => $v['flagRedondear'],
				'fechaEntrega' => $v['fechaEntrega'],
				'diasEntrega' => $v['diasEntrega'],
				'caracteristicasProveedor' => $v['caracteristicasProveedor'],
				'estado' => 1,
				'flagAlternativo' => $v['flagAlternativo'],
				'nombreAlternativo' => $v['nombreAlternativo'],
				'tituloParaOC' => $v['tituloParaOC'],
				'cantPdv' => $v['cantPdv'],
				'flagDetallePDV' => $v['flagDetallePDV'],
				'tipoServicio' => $v['tipoServicio'],
				'requiereOrdenCompra' => $v['requiereOrdenCompra'],
				'tsCosto' => $v['tsCosto'],
				'costoPacking' => $v['costoPacking'],
				'flagPackingSolicitado' => $v['flagPackingSolicitado'],
				'flagMostrarDetalle' => $v['flagMostrarDetalle'],
				'sueldo' => $v['sueldo'],
				'asignacionFamiliar' => $v['asignacionFamiliar'],
				'movilidad' => $v['movilidad'],
				'incentivos' => $v['incentivos'],
				'essalud' => $v['essalud'],
				'cts' => $v['cts'],
				'vacaciones' => $v['vacaciones'],
				'gratificacion' => $v['gratificacion'],
				'segurovidaley' => $v['segurovidaley'],
				'adicionales' => $v['adicionales'],
				'refrigerio' => $v['refrigerio'],
				'idCargo' => $v['idCargo'],
				'cantidad_personal' => $v['cantidad_personal'],
				'mesInicio' => $v['mesInicio'],
				'mesFin' => $v['mesFin'],
				'frecuenciaCobro' => $v['frecuenciaCobro'],
				'costoMovilidad' => $v['costoMovilidad'],
				'flagMovilidadSolicitado' => $v['flagMovilidadSolicitado'],
				'costoPersonal' => $v['costoPersonal'],
				'flagPersonalSolicitado' => $v['flagPersonalSolicitado'],
				'fee1Por' => $v['fee1Por'],
				'fee2Por' => $v['fee2Por'],
				'fee1Monto' => $v['fee1Monto'],
				'fee2Monto' => $v['fee2Monto'],
				'idCotizacionDetallePersonal' => $v['idCotizacionDetallePersonal'],
			];

			$this->db->insert('compras.cotizacionDetalle', $insertarCotizacionDetalle);
			$idNewCotizacionDetalle = $this->db->insert_id();

			$cotizacionDetalleSub = $this->model->obtenerDuplicadoCotizacionDetalleSub($v['idCotizacionDetalle'])['query']->result_array();

			//echo $this->db->last_query();exit();
			foreach ($cotizacionDetalleSub as $j => $i) {
				$insertarCotizacionDetallesub = [
					'idCotizacionDetalle' => $idNewCotizacionDetalle,
					'idTipoServicio' => $i['idTipoServicio'],
					'idUnidadMedida' => $i['idUnidadMedida'],
					'nombre' => $i['nombre'],
					'talla' => $i['talla'],
					'tela' => $i['tela'],
					'color' => $i['color'],
					'cantidad' => $i['cantidad'],
					'costo' => $i['costo'],
					'subtotal' => $i['subtotal'],
					'fechaCreacion' => getActualDateTime(),
					'fechaModificacion' => null,
					'monto' => $i['monto'],
					'costoDistribucion' => $i['costoDistribucion'],
					'cantidadPdv' => $i['cantidadPdv'],
					'idItem' => $i['idItem'],
					'idDistribucionTachado' => $i['idDistribucionTachado'],
					'genero' => $i['genero'],
					'idProveedorDistribucion' => $i['idProveedorDistribucion'],
					'cantidadReal' => $i['cantidadReal'],
					'requiereOrdenCompra' => $i['requiereOrdenCompra'],
					'sucursal' => $i['sucursal'],
					'razonSocial' => $i['razonSocial'],
					'tipoElemento' => $i['tipoElemento'],
					'marca' => $i['marca'],
					'peso' => $i['peso'],
					'flagItemInterno' => $i['flagItemInterno'],
					'idZona' => $i['idZona'],
					'dias' => $i['dias'],
					'gap' => $i['gap'],
					'pesoVisual' => $i['pesoVisual'],
					'costoVisual' => $i['costoVisual'],
					'flagOtrosPuntos' => $i['flagOtrosPuntos'],
					'cod_departamento' => $i['cod_departamento'],
					'cod_provincia' => $i['cod_provincia'],
					'idTipoServicioUbigeo' => $i['idTipoServicioUbigeo'],
					'cod_distrito' => $i['cod_distrito'],
					'reembarque' => $i['reembarque'],
					'porcentajeParaCosto' => $i['porcentajeParaCosto'],
					'idConcepto' => $i['idConcepto'],
					'flagConcepto' => $i['flagConcepto'],
					'frecuencia' => $i['frecuencia'],
				];
				$this->db->insert('compras.cotizacionDetalleSub', $insertarCotizacionDetallesub);
				$idNewCotizacionDetalleSub = $this->db->insert_id();
			}
			$cotizacionDetalleArchivos = $this->model->obtenerCotizacionDetalleArchivos($post, $v['idCotizacionDetalle'])['query']->result_array();

			foreach ($cotizacionDetalleArchivos as $r => $p) {
				$insertarCotizacionDetalleArchivos = [
					'idCotizacion' => $idNewCotizacion,
					'idCotizacionDetalle' => $idNewCotizacionDetalle,
					'idTipoArchivo' => $p['idTipoArchivo'],
					'nombre_inicial' => $p['nombre_inicial'],
					'nombre_archivo' => $p['nombre_archivo'],
					'nombre_unico' => $p['nombre_unico'],
					'extension' => $p['extension'],
					'estado' => 1,
					'fechaReg' => getSoloFecha(),
					'horaReg' => getSoloHora(),
					'fechaModificacion' => null,
					'idUsuarioReg' => $this->idUsuario,
					'flag_anexo' => $p['flag_anexo'],
					'idAutorizacion' => $p['idAutorizacion'],
				];
				$this->db->insert('compras.cotizacionDetalleArchivos', $insertarCotizacionDetalleArchivos);
			}
		}



		$insertarEstadoHistorico = [
			'idCotizacionEstado' => 1,
			'idCotizacionInternaEstado' => null,
			'idCotizacion' => $idNewCotizacion,
			'observacion' => null,
			'fechaReg' => getSoloFecha(),
			'horaReg' => getSoloHora(),
			'idUsuarioReg' => $this->idUsuario,
			'estado' => 1,
		];
		$this->db->insert('compras.cotizacionEstadoHistorico', $insertarEstadoHistorico);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 2;
			$result['msg']['title'] = 'Error al Duplicar';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'Duplicado Exitoso';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}
		echo json_encode($result);
	}



	public function formularioOperLogCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista['cabOperLog'] = $this->model->datosOperLog($post)['query']->result_array();
		$idCuenta = $dataParaVista['cabOperLog'][0]['idCuenta'];
		$dcds = $this->db->select('cd.idCotizacionDetalle, cdt.idItem , cdt.nombre, cdt.cantidad, cdt.idZona, cdt.flagOtrosPuntos')->from('compras.cotizacionDetalle cd')->join('compras.cotizacionDetalleSub cdt', 'cdt.idCotizacionDetalle = cd.idCotizacionDetalle', 'INNER')->where('cd.idCotizacion', $post['idCotizacion'])->where('cd.idItemTipo', 7)->where('cd.estado', 1)->get()->result_array();
		//echo $this->db->last_query(); exit();
		$agrupadoPorId = [];
		foreach ($dcds as $kCds => $vCds) {
			$idCotizacionDetalle = $vCds['idCotizacionDetalle'];
			if (!isset($agrupadoPorId[$idCotizacionDetalle])) {
				$agrupadoPorId[$idCotizacionDetalle] = array();
			}
			$vCds['zona'] = $this->model->getZonas(['otroAlmacen' => $vCds['flagOtrosPuntos'], 'idZona' => $vCds['idZona']])->row_array()['nombre'];
			$agrupadoPorId[$idCotizacionDetalle][] = $vCds;
		}
		$dataParaVista['distribucion'] = $agrupadoPorId;
		$dataParaVista['CuentaUsuario'] = $this->model->datosCuentaUsuario($idCuenta)['query']->result_array();
		$dataParaVista['Almacen'] = $this->model->datosAlmacenOrigen($idCuenta)['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Generar OperLog';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioOperLog", $dataParaVista, true);

		echo json_encode($result);
	}


	public function generarOperLogCotizacion()
	{
		$result = $this->result;
		$post = $this->input->post('data');
		$this->db->trans_begin();
		$cabOperLog = $this->model->datosOperLog($post)['query']->result_array();

		// $archivoCotizacion = [
		// 	'base64' => $post['cotizacionFile-item'],
		// 	'name' => $post['cotizacionFile-name'],
		// 	'type' => $post['cotizacionFile-type'],
		// 	'carpeta' => 'operlog/cotizaciones',
		// 	'nombreUnico' => uniqid()
		// ];
		// $archivoNameCotizacion = $this->saveFileWasabi($archivoCotizacion);
		//$tipoArchivoCotizacion = explode('/', $archivo['type']);

		// $archivoOrdenCompra = [
		// 	'base64' => $post['ordenCompraFile-item'],
		// 	'name' => $post['ordenCompraFile-name'],
		// 	'type' => $post['ordenCompraFile-type'],
		// 	'carpeta' => 'operlog/ordenes',
		// 	'nombreUnico' => uniqid()
		// ];
		// $archivoNameOrdenCompra = $this->saveFileWasabi($archivoOrdenCompra);

		$idOperCod = array();

		foreach ($cabOperLog as $f => $d) {
			$insertarCabOperLog = [
				'idCuenta' => $d['idCuenta'],
				'idCuentaCentroCosto' => $d['idCentroCosto'],
				'idCuentaUsuario' => $post['CuentaUsuario'],
				'codCotizacion' => $d['codCotizacion'],
				'nombreCotizacion' => $d['nombreCotizacion'],
				'ordenCompra' => null,
				'dirigido' => $d['nombre'],
				'fotografico' => $d['fotografia'],
				'guia' => $d['guia'],
				'otros' => $d['otros'],
				'ordenCompra' => $d['codOrdenCompra'],
				// 'archivoCotizacion' => $archivoNameCotizacion, 
				// 'archivoOrden' => $archivoNameOrdenCompra, 
				'idEstado' => $d['idEstado'],
				'estado' => $d['estado'],
				'fecReg' => getSoloFecha(),
				'horReg' => getSoloHora(),
				'idUsuario' => $this->idUsuario,
				'updateCC' => null,
				'fechaUltimaModificacion' => null,
				'flagImpactBussiness' => 1
				// flagImpactBussiness
			];
			$this->db->insert('VisualImpact.logistica.operLog', $insertarCabOperLog);
			$idOperlog = $this->db->insert_id();
			$idUsuarioLog = $this->model->usuarioLogistica($this->idUsuario)['query']->result_array();
			$requerimiento = [
				'fecha' => getSoloFecha(),
				'idUsuario' => $idUsuarioLog[0]['idUsuarioLogistica'],
				'idOperLog' => $idOperlog,
				'idAlmacen' => '1',
				'idCuenta' => $d['idCuenta'],
				'idCentroCosto' => $d['idCentroCosto'],
				'idCuentaUsuario' => $post['CuentaUsuario'],
				'idUsuarioReg' => $idUsuarioLog[0]['idUsuarioLogistica'],
				'idEstado' => 1,
				'fechaReg' => getSoloFecha(),
				'horaReg' => getSoloHora(),
			];
			$this->db->insert('VisualImpact.logistica.requerimiento', $requerimiento);
			$idRequerimiento = $this->db->insert_id();
			$CantArticulo = $this->model->datosOperLogCantArticulo($d['idCotizacionDetalle'])['query']->result_array();
			//var_dump($CantArticulo); exit();
			foreach ($CantArticulo as $z => $x) {
				$requerimientoDet = [
					'idRequerimiento' => $idRequerimiento,
					'idArticulo' => $x['idItem'],
					'idUnidadMedida' => $x['idUnidadMedida'],
					'cantidad' => $x['cantidad'],
					'idArticuloEstado' => 1,
					'equivalente' => $x['cantidad'],
					'idUsuarioReg' => $idUsuarioLog[0]['idUsuarioLogistica'],
					'fechaReg' => getSoloFecha(),
					'horaReg' => getSoloHora(),
				];
				$this->db->insert('VisualImpact.logistica.requerimiento_det', $requerimientoDet);
				}
			
			array_push($idOperCod, $idOperlog);

			$cabOperLogDetalle = $this->model->datosOperLogDetalle($d['idCotizacionDetalle'])['query']->result_array();
			// echo $this->db->last_query();exit();
			foreach ($cabOperLogDetalle as $p => $q) {
				$cabOperLogDetalleSub = $this->model->datosOperLogDetalleSub($q['idCotizacionDetalle'])['query']->result_array();

				foreach ($cabOperLogDetalleSub as $l => $m) {
					$InsertOperLogDetalle = [
						'idOperlog' => $idOperlog,
						'idAlmacenOrigen' => $post['AlmacenOrigen'],
						'idAlmacenDestino' => $m['idZona'],
						'idTipoDestino' => ($m['flagOtrosPuntos'] == 0) ? 1 : 2,
						'idTipoTransporte' => $m['idTipoTransporte'],
						'recojo' => '',
						'idGuia' => null,
						'fechaEstimada' => null,
						'fechaEntrega' => null,
						'estado' => 1,
						'idTipoOrigen' => 1,
						//'idTipoDestino' => 2,					
					];
					$this->db->insert('VisualImpact.logistica.operLogDetalle', $InsertOperLogDetalle);
					$idOperlogDetalle = $this->db->insert_id();
					$ArtOper = $this->model->datosOperLogDetalleArticulo($m['idZona'], $q['idCotizacionDetalle'])['query']->result_array();
					foreach ($ArtOper as $w => $r) {
						$InsertOperLogArt = [
							'idOperlogDetalle' => $idOperlogDetalle,
							'idArticulo' => $r['idItem'],
							'cantidad' => $r['cantidad'],
							'estado' => 1,
						];
						$this->db->insert('VisualImpact.logistica.operLogDetalleArticulo', $InsertOperLogArt);
						$idOperArt = $this->db->insert_id();
					}
				}
			}

			$this->db->update('compras.cotizacionDetalle', ['idOperLog' => $idOperlog], ['idCotizacionDetalle' => $d['idCotizacionDetalle']]);
		}

		$this->db->update('compras.cotizacion', ['flagOperlog' => 1], ['idCotizacion' => $post['idCotizacion']]);
		
		$codConcat = "";
		if (count($idOperCod) === 1) {
			$codConcat = "Log-" . $idOperCod[0];
		} else {
			foreach ($idOperCod as $y => $t) {
				$codConcat .= "Log-" . $t . ", ";
			}
			$codConcat = rtrim($codConcat, ", ");
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 2;
			$result['msg']['title'] = 'Error al Crear OPERLOG';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'OPERLOG creado ';
			$result['msg']['content'] = '<div class="alert alert-success m-3 noResultado" role="alert">	<i class="fas fa-check"></i> El registro se realizó correctamente <b>' . $codConcat . '</b>. </div>';
		}
		echo json_encode($result);
	}

	public function descargar_oper_excel()
	{
		require_once '../PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		//	$datos = $this->model->obtenerItemExcel()['query']->result_array();
		//$post = $this->input->post('data');
		$post = json_decode($this->input->post('data'), true);
		//echo $post['id']; exit();


		/**ESTILOS**/
		$estilo_cabecera =
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E60000')
				),
				'font' => array(
					'color' => array('rgb' => 'ffffff'),
					'size' => 11,
					'name' => 'Calibri'
				)
			);
		$estilo_titulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => ['rgb' => '00A985']
			],
			'font'  => [
				'size' => 12,
				'name'  => 'Calibri',
				'color' => array('rgb' => 'FFFFFF'),
			],
			'borders' => [
				'allborders' => [
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => ['rgb' => '000000'] // Color negro en RGB
				]
			]
		];
		$estilo_detalle = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

			'font' => [
				'size' => 11,
				'name' => 'Calibri'
			],
			'borders' => [
				'allborders' => [
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => ['rgb' => '000000'] // Color negro en RGB
				]
			]
		];

		/**FIN ESTILOS**/

		$objPHPExcel->getActiveSheet()->getStyle('B1:E1')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

		$objPHPExcel->getProperties()
			->setCreator("Visual Impact")
			->setLastModifiedBy("Visual Impact")
			->setTitle("FORMATO")
			->setSubject("FORMATO")
			->setDescription("Visual Impact")
			->setKeywords("usuarios phpexcel")
			->setCategory("FORMATO");

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A2', 'RECOJO')
			->setCellValue('B2', 'TIPO ENVIO')
			// ->setCellValue('C2', 'TIPO ORIGEN')
			// ->setCellValue('D2', 'COD ORIGEN')
			// ->setCellValue('E2', 'ORIGEN')
			->setCellValue('C2', 'TIPO DESTINO')
			->setCellValue('D2', 'COD DESTINO')
			->setCellValue('E2', 'DESTINO')
			->getStyle("A1:E2")->applyFromArray($estilo_titulo)->getFont()->setBold(true);;

		$col = "F";
		$row = "1";
		$celdaFinal = "";
		$celda = $col . $row;
		$cabOperLog = $this->model->datosOperLog($post)['query']->result_array();
		foreach ($cabOperLog as $k => $d) {
			$celda = $col . $row;
			$cabOperLogDetalleSub = $this->model->cabOperLogDetalleSub($d['idCotizacionDetalle'])['query']->result_array();
			foreach ($cabOperLogDetalleSub as $l => $m) {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($celda, $m['idItem'])->getStyle($celda)->applyFromArray($estilo_titulo)->getFont()->setBold(true);
				$row++;
				$celda = $col . $row;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($celda, $m['nombre'])->getStyle($celda)->applyFromArray($estilo_titulo)->getFont()->setBold(true);
				$row++;
				$celda = $col . $row;
				$row2 = $row;
				$col2 =  "A";
				$zonasOper = $this->model->zonaOperLogDetalleSub($d['idCotizacionDetalle'])['query']->result_array();
				//echo $this->db->last_query(); exit();
				foreach ($zonasOper as $z => $a) {
					//$tituDet = $col2 . $row2;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A" . $row2, "")
						->setCellValue("B" . $row2, $a['idTipoTransporte'])
						->setCellValue("C" . $row2, $a['flagOtrosPuntos'] == 1 ? 2 : 1)
						->setCellValue("D" . $row2, $a['idZona'])
						->setCellValue("E" . $row2, $a['nombreLocal']);

					$row2++;
					$detOper = $this->model->detalleOperLogDetalleSub($d['idCotizacionDetalle'], $m['idItem'], $a['idZona'])['query']->result_array();
					//	echo $this->db->last_query(); exit();
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($celda, $detOper[0]['cantidad']);
					$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
					$celdaFinal = $celda;
					$row++;
					$celda = $col . $row;
				}
				$col++;
				$row = "1";
				$celda = $col . $row;
			}
		}

		$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:" . $celdaFinal)->applyFromArray($estilo_detalle)->getFont()->setBold(true);

		$nIni = 2;
		$objPHPExcel->getActiveSheet()->setTitle('FORMATO');


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}
