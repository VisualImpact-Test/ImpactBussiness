<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Oper extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Oper', 'model');
		$this->load->model('M_control', 'model_control');
		$this->load->model('M_Cotizacion', 'model_cotizacion');
		$this->load->model('M_Item', 'model_item');
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
			'assets/custom/js/Operaciones/Oper',
			'assets/custom/js/dataTables.select.min'
		);
		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'OPERS';
		$config['data']['message'] = 'Lista de OPERs';
		$config['view'] = 'modulos/Operaciones/Oper/index';
		$this->view($config);
	}

	public function reporteSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$data = $this->model->obtenerInformacionOper($post)->result_array();
		foreach ($data as $key => $row) {
			$dataParaVista[$row['idOper']] = [
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
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Operaciones/Oper/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentOPERSinCotizacion']['datatable'] = 'tb-oper';
		$result['data']['views']['idContentOPERSinCotizacion']['html'] = $html;
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

	public function formularioEditarOperSinCotizacion()
	{
		$result = $this->result;
		$idOper = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()->result_array();
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['item'] = $this->model_item->obtenerItemServicio();
		$dataParaVista['tipo'] = $this->model->obtenerTipo()->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedorEstado' => 2])->result_array();
		$dataParaVista['oper'] = $this->model->obtenerInformacionOper(['idOper' => $idOper])->result_array();

		foreach ($dataParaVista['oper'] as $key => $value) {
			$dataParaVista['operSubItem'][$value['idOperDetalle']] = $this->model->obtenerInformacionOperSubItem(['idOperDetalle' => $value['idOperDetalle']])->result_array();
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Oper';
		$result['data']['html'] = $this->load->view("modulos/Operaciones/Oper/formularioEditar", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioRegistroOperSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()->result_array();
		$dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['item'] = $this->model_item->obtenerItemServicio();
		$dataParaVista['tipo'] = $this->model->obtenerTipo()->result_array();
		$dataParaVista['itemLogistica'] = $this->model_item->obtenerItemServicio(['logistica' => true]);
		$dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();
		$dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedorEstado' => 2])->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Oper';
		$result['data']['html'] = $this->load->view("modulos/Operaciones/Oper/formularioRegistro", $dataParaVista, true);
		$result['data']['item'] = $dataParaVista['item'];
		echo json_encode($result);
	}

	public function registrarOperSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['costo'] = is_array($post['costo']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['costo']) : number_format(floatval(str_replace(',', '', $post['costo'])), 2, '.', '');
		$post['costoTarifario'] = is_array($post['costoTarifario']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['costoTarifario']) : number_format(floatval(str_replace(',', '', $post['costoTarifario'])), 2, '.', '');
		$post['costoGap'] = is_array($post['costoGap']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['costoGap']) : number_format(floatval(str_replace(',', '', $post['costoGap'])), 2, '.', '');
		$post['precio'] = is_array($post['precio']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['precio']) : number_format(floatval(str_replace(',', '', $post['precio'])), 2, '.', '');
		$post['totalFeeIGV'] = number_format(floatval(str_replace(',', '', $post['totalFeeIGV'])), 2, '.', '');
		$post['total'] = number_format(floatval(str_replace(',', '', $post['total'])), 2, '.', '');
		$post['totalFee'] = number_format(floatval(str_replace(',', '', $post['totalFee'])), 2, '.', '');
		
		$post['item'] = checkAndConvertToArray($post['item']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['idProveedor'] = checkAndConvertToArray($post['idProveedor']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['costoTarifario'] = checkAndConvertToArray($post['costoTarifario']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['costoGap'] = checkAndConvertToArray($post['costoGap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);

		if (isset($post['subItem_monto'])) {
			$post['subItem_costo'] = is_array($post['subItem_costo']) ? array_map(function ($subItem_monto) { return $subItem_monto !== "" ? number_format(floatval(str_replace(',', '', $subItem_monto)), 2, '.', '') : ""; }, $post['subItem_costo']) : ($post['subItem_costo'] !== "" ? number_format(floatval(str_replace(',', '', $post['subItem_costo'])), 2, '.', '') : "");
			$post['subItem_monto'] = checkAndConvertToArray($post['subItem_monto']);
			$post['subItem_tipoServ'] = checkAndConvertToArray($post['subItem_tipoServ']);
			$post['subItem_idUm'] = checkAndConvertToArray($post['subItem_idUm']);
			$post['subItem_itemLog'] = checkAndConvertToArray($post['subItem_itemLog']);
			$post['subItem_nombre'] = checkAndConvertToArray($post['subItem_nombre']);
			$post['subItem_talla'] = checkAndConvertToArray($post['subItem_talla']);
			$post['subItem_tela'] = checkAndConvertToArray($post['subItem_tela']);
			$post['subItem_genero'] = checkAndConvertToArray($post['subItem_genero']);
			$post['subItem_color'] = checkAndConvertToArray($post['subItem_color']);
			$post['subItem_costo'] = checkAndConvertToArray($post['subItem_costo']);
			$post['subItem_cantidad'] = checkAndConvertToArray($post['subItem_cantidad']);
			$post['subItem_cantidadPdv'] = checkAndConvertToArray($post['subItem_cantidadPdv']);
		}

		$insertData = [
			'fechaEntrega' => $post['fechaEntrega'],
			'fechaRequerimiento' => $post['fechaRequerimiento'],
			'concepto' => $post['concepto'],
			'numeroOC' => $post['numeroPO'],
			'idcuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idUsuarioReceptor' => $post['usuarioReceptor'],
			'total' => $post['total'],
			'feePorcentaje' => $post['feePorcentaje'],
			'totalFee' => $post['totalFee'],
			'IGVPorcentaje' => intval($post['igvPorcentaje']) - 100,
			'totalFeeIGV' => $post['totalFeeIGV'],
			'idUsuarioReg' => $this->idUsuario,
			'observacion' => $post['observacion'],
			'valor' => $post['valor']
		];

		$this->db->insert('orden.oper', $insertData);
		$idOper = $this->db->insert_id();
		$this->db->update(
			'orden.oper',
			[
				'requerimiento' => 'OPER' . $this->model->obtenerSeriado(OPER_SERIADO)
			],
			['idOper' => $idOper]
		);
		$insertData = [];
		$insertDataSub = [];
		$orden = 0;
		foreach ($post['item'] as $key => $value) {
			// En caso el item es nuevo
			$dataInserItem = [];
			if ($post['idItemForm'][$key] == '0') {
				$dataInserItem = [
					'nombre' => $post['item'][$key],
					'idItemTipo' => $post['tipo'][$key]
				];

				$this->db->insert('compras.item', $dataInserItem);
				$post['idItemForm'][$key] = $this->db->insert_id();
			}

			$insertData = [
				'idOper' => $idOper,
				'idItem' => $post['idItemForm'][$key],
				'idProveedor' => verificarEmpty($post['idProveedor'][$key], 4),
				'idTipo' => $post['tipo'][$key],
				'costoUnitario' => $post['costo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costoSubTotal' => number_format($post['costo'][$key] * $post['cantidad'][$key], 2, '.', ''),
				'gap' => $post['gap'][$key],
				'costoTarifario' => $post['costoTarifario'][$key],
				'costoGap' => $post['costoGap'][$key],
				'costoSubTotalGap' => $post['precio'][$key]
			];
			
			$insert = $this->db->insert('orden.operDetalle', $insertData);
			$idOperDet = $this->db->insert_id();
			/////////////////////
			for ($i = 0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
				$insertDataSub[] = [
					'idOperDetalle' => $idOperDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
					'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
					'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
					'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
					'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
					'genero' => $post['subItem_genero'][$orden] == '' ? NULL : $post['subItem_genero'][$orden],
					'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
					'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
					'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
					'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
					'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
					'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
				];
				$orden++;
			}
		}

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.operDetalleSub', $insertDataSub);
		}

		$dataParaVista = [];
		$dataParaVista['detalle'] = $this->model->obtenerInformacionDetalleOper(['idoper' => $idOper, 'estado' => 1])['query']->result_array();

		$html = $this->load->view("modulos/Cotizacion/correoGeneracionOper", $dataParaVista, true);
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html], true);

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

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		echo json_encode($result);
	}

	public function editarOperSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['costo'] = is_array($post['costo']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['costo']) : number_format(floatval(str_replace(',', '', $post['costo'])), 2, '.', '');
		$post['precio'] = is_array($post['precio']) ? array_map(function ($costo) { return number_format(floatval(str_replace(',', '', $costo)), 2, '.', ''); }, $post['precio']) : number_format(floatval(str_replace(',', '', $post['precio'])), 2, '.', '');
		$post['totalFeeIGV'] = number_format(floatval(str_replace(',', '', $post['totalFeeIGV'])), 2, '.', '');
		$post['total'] = number_format(floatval(str_replace(',', '', $post['total'])), 2, '.', '');
		$post['totalFee'] = number_format(floatval(str_replace(',', '', $post['totalFee'])), 2, '.', '');
		
		$post['item'] = checkAndConvertToArray($post['item']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);
		if (isset($post['subItem_monto'])) {
			$post['subItem_costo'] = is_array($post['subItem_costo']) ? array_map(function ($subItem_monto) { return $subItem_monto !== "" ? number_format(floatval(str_replace(',', '', $subItem_monto)), 2, '.', '') : ""; }, $post['subItem_costo']) : ($post['subItem_costo'] !== "" ? number_format(floatval(str_replace(',', '', $post['subItem_costo'])), 2, '.', '') : "");
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

		$updateData[0] = [
			'idOper' => $post['idOper'],
			'fechaEntrega' => $post['fechaEntrega'],
			'fechaRequerimiento' => $post['fechaRequerimiento'],
			'concepto' => $post['concepto'],
			'numeroOC' => $post['numeroPO'],
			'idcuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idUsuarioReceptor' => $post['usuarioReceptor'],
			'total' => $post['total'],
			'feePorcentaje' => $post['feePorcentaje'],
			'totalFee' => $post['totalFee'],
			'IGVPorcentaje' => intval($post['igvPorcentaje']) - 100,
			'totalFeeIGV' => $post['totalFeeIGV'],
			'idUsuarioReg' => $this->idUsuario,
			'observacion' => $post['observacion'],
			'valor' => $post['valor']
		];
		$rpta = $this->model->actualizarMasivo('orden.oper', $updateData, 'idOper');
		$idOper = $updateData[0]['idOper'];
		$this->db->update('orden.operDetalle', ['estado' => '0'], ['idOper' => $idOper]);

		$insertData = [];
		$insertDataSub = [];
		$orden = 0;
		foreach ($post['item'] as $key => $value) {
			// En caso el item es nuevo
			$dataInserItem = [];
			if ($post['idItemForm'][$key] == '0') {
				$dataInserItem = [
					'nombre' => $post['item'][$key],
					'idItemTipo' => $post['tipo'][$key]
				];
				$this->db->insert('compras.item', $dataInserItem);
				$post['idItemForm'][$key] = $this->db->insert_id();
			}
			//
			$insertData = [
				'idOper' => $idOper,
				'idItem' => $post['idItemForm'][$key],
				'idTipo' => $post['tipo'][$key],
				'costoUnitario' => $post['costo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costoSubTotal' => number_format($post['costo'][$key] * $post['cantidad'][$key], 2, '.', ''),
				'gap' => $post['gap'][$key],
				'costoSubTotalGap' => $post['precio'][$key]
			];
			$insert = $this->db->insert('orden.operDetalle', $insertData);
			$idOperDet = $this->db->insert_id();
			/////////////////////
			for ($i = 0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
				$insertDataSub[] = [
					'idOperDetalle' => $idOperDet,
					'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
					'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
					'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
					'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
					'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
					'genero' => $post['subItem_genero'][$orden] == '' ? NULL : $post['subItem_genero'][$orden],
					'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
					'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
					'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
					'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
					'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
					'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
				];
				$orden++;
			}
		}

		if (!empty($insertDataSub)) {
			$insert = $this->model->insertarMasivo('orden.operDetalleSub', $insertDataSub);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		echo json_encode($result);
	}

	public function descargarOperSinCotizacion()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);
		$oper = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])->result_array();
		$dataParaVista['dataOper'] = $oper[0];
		$ids = [];
		foreach ($oper as $v) {
			$ids[] = $v['idOper'];
			$config['data']['oper'][$v['idOper']] = $v;
			$idt = $v['idOper'];
		}
		$idOper = implode(",", $ids);
		$dataParaVista['oper'] = $this->model->obtenerInformacionOperPdf(['id' => $idt])['query']->result_array();
		$dataParaVista['operDetalle'] = $this->model->obtenerInformacionDetalleOper(['idoper' => $idt, 'cotizacionInterna' => false])['query']->result_array();

		foreach ($dataParaVista['operDetalle'] as $k => $v) {
			$dataParaVista['operDetalleSub'][$v['idOperDetalle']] = $this->db->get_where('orden.operDetalleSub', ['idOperDetalle' => $v['idOperDetalle']])->result_array();
			foreach ($dataParaVista['operDetalleSub'][$v['idOperDetalle']] as $kd => $vd) {
				$dataParaVista['detalleSubTalla'][$v['idOperDetalle']][$vd['talla']][$vd['genero']] = $vd;
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

		$contenido['header'] = $this->load->view("modulos/Operaciones/Oper/pdf2/header", ['title' => 'REQUERIMIENTO DE BIENES O SERVICIOS LIBRE', 'codigo' => 'SIG-LOG-FOR-001'], true);
		$contenido['footer'] = $this->load->view("modulos/Operaciones/Oper/pdf2/footer", array(), true);
		$contenido['style'] = $this->load->view("modulos/Operaciones/Oper/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Operaciones/Oper/pdf2/oper", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		$title = $oper[0]['requerimiento'];
		$mpdf->Output("$title.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}

	public function generarRowParaOper()
	{
		$post = $this->input->post();

		$where = ['soloCargosOcupados' => true];
		if (!empty($post['idCuenta'])) $where['idCuenta'] = $post['idCuenta'];
		if ($post['tipo'] != 0) {
			$dataParaVista['tipo'] = $post['tipo'];
		} else {
			$dataParaVista['tipo'] = 0;
		}
		$dataParaVista['usuario'] = $this->db->get_where('sistema.usuario')->result_array();

		echo $this->load->view('modulos/Operaciones/Oper/elements/rowAdicional', $dataParaVista, true);
	}
};
