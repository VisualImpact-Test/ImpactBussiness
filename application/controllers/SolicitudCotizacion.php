<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SolicitudCotizacion extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_cotizacion', 'model');
		$this->load->model('M_Item', 'model_item');
		$this->load->model('M_control', 'model_control');
		$this->load->model('M_proveedor', 'model_proveedor');
		$this->load->model('M_formularioProveedor', 'model_formulario_proveedor');
		$this->load->model('M_Autorizacion', 'model_autorizacion');
		$this->load->model('M_Oper', 'mOper');
	}

	public function index()
	{
		$config = array();
		$config['nav']['menu_active'] = '131';
		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday'
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
			'assets/custom/js/solicitudCotizacion',

		);

		$config['data']['icon'] = 'fas fa-money-check-edit-alt';
		$config['data']['title'] = 'Solicitudes de Cotizacion';
		$config['data']['message'] = 'Lista de Cotizacions Enviadas';
		$config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$config['view'] = 'modulos/SolicitudCotizacion/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		if (isset($_SESSION['item'])) {
			$item = $_SESSION['item'];

			$post['id'] = $item;
			$datoDeseado =  $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
			unset($post['id']);
			$post['idDiferente'] = $item;
			$datoRestante =  $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
			$dataParaVista = array_merge($datoDeseado, $datoRestante);
		} else {
			$dataParaVista = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/SolicitudCotizacion/reporte", ['datos' => $dataParaVista], true);
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

	public function formularioSolicitudCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $post['id'], 'idItemEstado' => 2])['query']->result_array();

		$dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores(['estadoProveedor' => 2])['query']->result_array();
		foreach ($proveedores as $k => $p) {
			$dataParaVista['proveedores'][$p['idProveedor']] = $p;
		}

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
		$result['msg']['title'] = 'Verificar Solicitud de Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioRegistro", $dataParaVista, true);
		$result['data']['itemServicio'] = $data['itemServicio'];

		echo json_encode($result);
	}

	public function CentroCosto()
	{
		$data = json_decode($this->input->post('data'));
		$grupo['data']['centro'] = $this->model->obtenerCuentaCentroCostoEdit($data->id)['query']->result_array();
		echo json_encode($grupo);
	}
	public function formCotizacionTransporteSincerado()
	{
		$result = $this->result;
		$idCotizacion = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$dataParaVista['cotizacion'] = $this->db->where('idCotizacion', $idCotizacion)->get('compras.cotizacion')->row_array();
		// Add Cuenta y CentroCosto
		$coti = $dataParaVista['cotizacion'];
		$dataParaVista['cotizacion']['cuenta'] = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $coti['idCuenta']])->row_array()['nombre'];
		$dataParaVista['cotizacion']['centroCosto'] = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $coti['idCentroCosto']])->row_array()['subcanal'];

		$dataParaVista['cotizacionDetalle'] = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacion' => $coti['idCotizacion'], 'idItemTipo' => COD_TRANSPORTE['id']])->result_array();

		foreach ($dataParaVista['cotizacionDetalle'] as $k => $v) {
			$dataParaVista['cotizacionDetalleSub'][$v['idCotizacionDetalle']] = $this->db->get_where('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $v['idCotizacionDetalle']])->result_array();
		}

		$depPro = $this->db->get('General.dbo.ubigeo')->result_array();
		foreach ($depPro as $k => $v) {
			$dataParaVista['depPro'][$v['cod_departamento']][$v['cod_provincia']] = $v;
		}

		$tipoServicioUbigeo = $this->db->get('compras.tipoServicioUbigeo')->result_array();
		foreach ($tipoServicioUbigeo as $k => $v) {
			$dataParaVista['tipoServicioUbigeo'][$v['idTipoServicioUbigeo']] = $v;
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Sincerado Cotización Transporte';
		$result['msg']['content'] = $this->load->view("modulos/SolicitudCotizacion/formularioRegistroSincerado", $dataParaVista, true);
		echo json_encode($result);
	}

	public function formularioSolicitudCotizacionfecha()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $post['id'], 'idItemEstado' => 2])['query']->result_array();

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
		$result['msg']['title'] = 'Verificar Solicitud de Cotizacion';
		$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioRegistrofecha", $dataParaVista, true);
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
			$dataParaVista['cabecera']['idOper'] = $oper['idOper'];
			$dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
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
		$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioVisualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioConsultaMultiple()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		// Si se consulta Cotización;
		if (1 <= intval($post['tipo']) && intval($post['tipo']) <= 5) {
			$dataCotizacion = $this->model->obternerCotizacionDetalle($post)->result_array();

			foreach ($dataCotizacion as $key => $row) {
				$dataCotizacion[$key]['fechaDeadline'] = date_change_format($row['fechaDeadline']);
				$dataCotizacion[$key]['fechaRequerida'] = date_change_format($row['fechaRequerida']);
				$dataCotizacion[$key]['item'] = $row['nombre'];
				$dataSub[$row['idCotizacionDetalle']] = $this->model->obtenerCotizacionDetalleSub(['idCotizacionDetalle' => $row['idCotizacionDetalle']])->result_array();
			};

			$dataParaVista['cotizacion'] = $dataCotizacion;
			$dataParaVista['cotizacionSub'] = $dataSub;
			$result['result'] = 1;
			$dataParaVista['incluirCosto'] = false;

			if (intval($post['tipo']) <= 2) {
				$result['msg']['title'] = 'Cotización Registrada';
			} else
			if (intval($post['tipo']) <= 5) {
				$dataParaVista['incluirCosto'] = true;
				$result['msg']['title'] = 'Cotización Valorizada';
			}

			$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/viewCotizacionRegistro", $dataParaVista, true);
			goto respuesta;
		}

		// Si se consulta Oper;
		if (6 <= intval($post['tipo']) && intval($post['tipo']) <= 7) {
			$idOper = $this->mOper->obtenerInformacionComprasOper($post)->row_array()['idOper'];
			$oper = $this->mOper->obtenerInformacionComprasOper(['idOper' => $idOper])->result_array();

			$dataParaVista['oper'] = $oper;
			$result['msg']['title'] = 'Oper Registrado';
			$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/viewOperRegistro", $dataParaVista, true);
		}
		respuesta:
		echo json_encode($result);
	}

	public function formularioVisualizacionCotizacionProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$data = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor($post)->result_array();
		$dataParaVista['cabecera'] = $this->model->obtenerInformacionCotizacion(['id' => $data[0]['idCotizacion']])['query']->row_array();
		// $dataimg = [];

		foreach ($data as $key => $row) {
			$dataParaVista['item'][$row['idItem']]['idItem'] = $row['idItem'];
			$dataParaVista['item'][$row['idItem']]['item'] = $row['item'];
			$dataParaVista['item'][$row['idItem']]['tipoItem'] = $row['tipoItem'];
			$dataParaVista['item'][$row['idItem']]['cantidad'] = $row['cantidad'];
			$dataParaVista['item'][$row['idItem']]['unidadMedida'] = $row['unidadMedida'];

			$dataParaVista['proveedor'][$row['idProveedor']]['proveedor'] = $row['proveedor'];

			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['costoUnitario'] = $row['costoUnitario'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['fechaValidez'] = $row['fechaValidez'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['fechaEntrega'] = $row['fechaEntrega'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['comentario'] = $row['comentario'];

			$dataParaVista['images'][$row['idItem']][$row['idProveedor']] = $this->model->obtenerArchivoCotizacionDetalleProveedors(['idCotizacionDetalleProveedorDetalle' => $row['idCotizacionDetalleProveedorDetalle']])->result_array();
		}

		$dataParaVista['estados'] = $this->model_control->get_estados_cotizacion()->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Cotización Item - Proveedor';
		$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioCotizacionProveedor", $dataParaVista, true);

		echo json_encode($result);
	}

	public function descargarExcel()
	{
		$post = $this->input->post();
		$idCotizacionDetalle = $post['idCotizacionDetalle'];
		$idProveedor = $post['idProveedor'];

		if (isset($post['idCotizacion'])) {
			$idCotizacion = $post['idCotizacion'];
		} else {
			$idCotizacion = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacionDetalle' => $idCotizacionDetalle])->row_array()['idCotizacion'];
		}

		$idCotizacionDetalleProveedor = $this->db->get_where('compras.cotizacionDetalleProveedor', ['idCotizacion' => $idCotizacion, 'idProveedor' => $idProveedor, 'estado' => '1'])->row_array()['idCotizacionDetalleProveedor'];
		$data = $this->db->get_where('compras.cotizacionDetalleProveedorDetalle', ['idCotizacionDetalleProveedor' => $idCotizacionDetalleProveedor])->result_array();
		$proveedor = $this->db->get_where('compras.proveedor', ['idProveedor' => $idProveedor])->row_array();
		$cotizacion = $this->db->get_where('compras.cotizacion', ['idCotizacion' => $idCotizacion])->row_array();

		$cuenta = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $cotizacion['idCuenta']])->row_array();
		$cc = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $cotizacion['idCentroCosto']])->row_array();

		$ids = [];
		$ids_two = [];
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$ids[] = $v['idCotizacionDetalleProveedorDetalle'];
				$ids_two[] = $v['idCotizacionDetalle'];
			}
		}
		$idsT = implode(',', $ids);
		$idsT_two = implode(',', $ids_two);
		$imgCDP = $this->db->select("extension, nombre_archivo, nombre_inicial, 'cotizacionProveedor/' as ruta")->where('idTipoArchivo', 2)->where("idCotizacionDetalleProveedorDetalle in ($idsT)")->get('compras.cotizacionDetalleProveedorDetalleArchivos')->result_array();
		$imgCoti = $this->db->select("extension, nombre_archivo, nombre_inicial, 'cotizacion/' as ruta")->where('idTipoArchivo', 2)->where("idCotizacionDetalle in ($idsT_two)")->get('compras.cotizacionDetalleArchivos')->result_array();
		$imgProveedor = array_merge($imgCDP, $imgCoti);
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		/** Include PHPExcel */
		require_once '../phpExcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();

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
				'font'  => array(
					'color' => array('rgb' => 'ffffff'),
					'size'  => 11,
					'name'  => 'Calibri'
				)
			);
		$estilo_titulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'size' => 16,
				'name'  => 'Calibri'
			]
		];
		$estilo_subtitulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'size' => 11,
				'name'  => 'Calibri'
			]
		];
		$estilo_data['left'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		$estilo_data['center'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		$estilo_data['right'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		/**FIN ESTILOS**/

		$gdImage = imagecreatefromjpeg(APPPATH . '../public/assets/images/visualimpact/logo_full.jpg');
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Sample image');
		$objDrawing->setDescription('TEST');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(50);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->mergeCells('B5:F5');
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B5', 'REQUERIMIENTO DE COTIZACION INTERNA')
			->setCellValue('A8', 'PROVEEDOR')
			->setCellValue('B8', $proveedor['razonSocial'])
			->setCellValue('A9', 'CUENTA')
			->setCellValue('B9', $cuenta['nombre'])
			->setCellValue('A10', 'CC')
			->setCellValue('B10', $cc['subcanal'])
			->setCellValue('A11', 'FECHA')
			->setCellValue('B11', getFechaActual());

		$objPHPExcel->getActiveSheet()->getStyle("B5")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray($estilo_titulo);

		$objPHPExcel->getActiveSheet()->getStyle("A8:A11")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A8:A11")->applyFromArray($estilo_subtitulo);

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A13', 'DESCRIPCION')
			->setCellValue('B13', 'CANTIDAD')
			->setCellValue('C13', 'PRECIO UNITARIO')
			->setCellValue('D13', 'TOTAL')
			->setCellValue('E13', 'TIEMPO');
		$objPHPExcel->getActiveSheet()->getStyle("A13:E13")->applyFromArray($estilo_cabecera);
		$nIni = 14;
		foreach ($data as $k => $v) {
			$itm = $this->db->get_where('compras.item', ['idItem' => $v['idItem']])->row_array();
			if (empty($v['cantidad']) || intval($v['cantidad']) == 0) $v['cantidad'] = 1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $nIni, $itm['nombre'] . ' - ' . $itm['caracteristicas'])
				->setCellValue('B' . $nIni, $v['cantidad'])
				->setCellValue('C' . $nIni, moneda(floatval($v['costo']) / floatval($v['cantidad'])))
				->setCellValue('D' . $nIni, moneda($v['costo']))
				->setCellValue('E' . $nIni, $v['diasValidez']);
			$nIni++;
		}
		$fin = $nIni - 1;
		$objPHPExcel->getActiveSheet()->getStyle("A14:A$fin")->applyFromArray($estilo_data['left']);
		$objPHPExcel->getActiveSheet()->getStyle("B14:B$fin")->applyFromArray($estilo_data['center']);
		$objPHPExcel->getActiveSheet()->getStyle("C14:C$fin")->applyFromArray($estilo_data['right']);
		$objPHPExcel->getActiveSheet()->getStyle("D14:D$fin")->applyFromArray($estilo_data['right']);
		$objPHPExcel->getActiveSheet()->getStyle("E14:E$fin")->applyFromArray($estilo_data['center']);

		$nIni++;
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, '** Precion no incluye IGV');
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, '** Precio valido por ' . $data[0]['diasValidez']);
		$nIni++;
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, 'IMAGENES ADJUNTAS');

		$nIni = $nIni + 2;
		foreach ($imgProveedor as $k => $v) {
			if ($v['extension'] == 'jpeg') {
				$gdImage = imagecreatefromjpeg(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
			}
			if ($v['extension'] == 'png') {
				$gdImage = imagecreatefrompng(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
			}

			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setName('Sample image');
			$objDrawing->setDescription('TEST');
			$objDrawing->setImageResource($gdImage);
			if ($v['extension'] == 'jpeg') {
				$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
			}
			if ($v['extension'] == 'png') {
				$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
			}
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setHeight(100);
			$objDrawing->setCoordinates('A' . $nIni);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			$nIni = $nIni + 6;
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function descargarExcel_provserv()
	{
		$post = $this->input->post();
		$idOrdenCompra = $post['id'];
		$idProveedor = $post['idProveedor'];
		$idCotizacion = $post['idCotizacion'];
		$flag = $post['flag'];


		$ids = [];
		$ids_two = [];

		if ($flag == 0) {
			$data = $this->db->select('*')
				->from('compras.cotizacionDetalle cd')
				->join(
					'compras.cotizacionDetalleProveedorDetalle cdpd',
					'cd.idCotizacionDetalle = cdpd.idCotizacionDetalle',
					'INNER'
				)
				->where(['cd.idProveedor' => $idProveedor])
				->where(['cd.idCotizacion' => $idCotizacion])
				->where(['cd.estado' => 1])
				->get()->result_array();

			$cotizacion = $this->db->get_where('compras.cotizacion', ['idCotizacion' => $idCotizacion])->row_array();

			$cuenta = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $cotizacion['idCuenta']])->row_array();
			$cc = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $cotizacion['idCentroCosto']])->row_array();

			if (!empty($data)) {
				foreach ($data as $k => $v) {
					$ids[] = $v['idCotizacionDetalleProveedorDetalle'];
					$ids_two[] = $v['idCotizacionDetalle'];
				}
			}


			$idsT = implode(',', $ids);
			$idsT_two = implode(',', $ids_two);
			$imgCDP = $this->db->select("extension, nombre_archivo, nombre_inicial, 'cotizacionProveedor/' as ruta")
				->where('idTipoArchivo', 2)->where("idCotizacionDetalleProveedorDetalle in ($idsT)")
				->get('compras.cotizacionDetalleProveedorDetalleArchivos')->result_array();
			$imgCoti = $this->db->select("extension, nombre_archivo, nombre_inicial, 'cotizacion/' as ruta")
				->where('idTipoArchivo', 2)->where("idCotizacionDetalle in ($idsT_two)")
				->get('compras.cotizacionDetalleArchivos')->result_array();
			$imgProveedor = array_merge($imgCDP, $imgCoti);
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			ini_set('memory_limit', '1024M');
			set_time_limit(0);
		} else {
			/*$data = $this->db->get_where(
				'orden.ordenCompraDetalle',
				['idOrdenCompra' => $idOrdenCompra]
			)->result_array();*/
			$data = $this->db
				->distinct()
				->select('
				cd.total, 
    cc.nombre AS cuentaCentroCosto,
    pr.razonSocial AS proveedor,
    cu.nombre AS cuenta,
    cd.idProveedor,
    cd.requerimiento AS codOrdenCompra,
    1 AS flagOcLibre, cp.idItem,
	cp.cantidad AS cantidad,
	cp.costoSubTotal AS costo,
	cp.costoUnitario')
				->from('orden.ordenCompra cd')
				->join('orden.ordenCompraDetalle cp', 'cd.idOrdenCompra = cp.idOrdenCompra', 'INNER')
				->join('compras.proveedor pr', 'pr.idProveedor = cd.idProveedor', 'INNER')
				->join('visualImpact.logistica.cuentaCentroCosto cc', 'cd.idCentroCosto = cc.idCuentaCentroCosto', 'LEFT')
				->join('visualImpact.logistica.cuenta cu', 'cd.idCuenta = cu.idCuenta', 'INNER')
				->where('cd.estado', '1')
				->where('cp.idOrdenCompra', $idOrdenCompra)
				->group_by('cd.total, 
				cc.nombre, 
				cu.nombre, 
				cd.idProveedor, 
				pr.razonSocial, 
				cd.requerimiento, 
				cp.idOrdenCompra, cp.idItem, cp.cantidad, cp.costoSubTotal, cp.costoUnitario');

			$result = $data->get();
			foreach ($result->result() as $row) {
				$cuenta = $row->cuenta;
				$cc = $row->cuentaCentroCosto;
			}

			$data = $result->result_array();
		}

		$proveedor = $this->db
			->get_where('compras.proveedor', ['idProveedor' => $idProveedor])->row_array();



		/** Include PHPExcel */
		require_once '../phpExcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();

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
				'font'  => array(
					'color' => array('rgb' => 'ffffff'),
					'size'  => 11,
					'name'  => 'Calibri'
				)
			);
		$estilo_titulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'size' => 16,
				'name'  => 'Calibri'
			]
		];
		$estilo_subtitulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'size' => 11,
				'name'  => 'Calibri'
			]
		];
		$estilo_data['left'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		$estilo_data['center'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		$estilo_data['right'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font'  => [
				'name'  => 'Calibri'
			]
		];
		/**FIN ESTILOS**/

		$gdImage = imagecreatefromjpeg(APPPATH . '../public/assets/images/visualimpact/logo_full.jpg');
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Sample image');
		$objDrawing->setDescription('TEST');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(50);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->mergeCells('B5:F5');


		if ($flag == 0) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B5', 'REQUERIMIENTO DE COTIZACION INTERNA')
				->setCellValue('A8', 'PROVEEDOR')
				->setCellValue('B8', $proveedor['razonSocial'])
				->setCellValue('A9', 'CUENTA')
				->setCellValue('B9', $cuenta['nombre'])
				->setCellValue('A10', 'CC')
				->setCellValue('B10', $cc['subcanal'])
				->setCellValue('A11', 'FECHA')
				->setCellValue('B11', getFechaActual());

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A13', 'DESCRIPCION')
				->setCellValue('B13', 'CANTIDAD')
				->setCellValue('C13', 'PRECIO UNITARIO')
				->setCellValue('D13', 'TOTAL')
				->setCellValue('E13', 'TIEMPO');
		} else {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B5', 'REQUERIMIENTO DE COTIZACION INTERNA')
				->setCellValue('A8', 'PROVEEDOR')
				->setCellValue('B8', $proveedor['razonSocial'])
				->setCellValue('A9', 'CUENTA')
				->setCellValue('B9', $cuenta)
				->setCellValue('A10', 'CC')
				->setCellValue('B10', $cc)
				->setCellValue('A11', 'FECHA')
				->setCellValue('B11', getFechaActual());

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A13', 'DESCRIPCION')
				->setCellValue('B13', 'CANTIDAD')
				->setCellValue('C13', 'PRECIO UNITARIO')
				->setCellValue('D13', 'TOTAL');
		}

		$objPHPExcel->getActiveSheet()->getStyle("B5")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray($estilo_titulo);

		$objPHPExcel->getActiveSheet()->getStyle("A8:A11")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A8:A11")->applyFromArray($estilo_subtitulo);


		$objPHPExcel->getActiveSheet()->getStyle("A13:E13")->applyFromArray($estilo_cabecera);
		$nIni = 14;
		foreach ($data as $k => $v) {
			$itm = $this->db->get_where('compras.item', ['idItem' => $v['idItem']])->row_array();
			if (empty($v['cantidad']) || intval($v['cantidad']) == 0) $v['cantidad'] = 1;

			$obj1;
			if ($flag == 0) {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $nIni, $itm['nombre'] . ' - ' . $itm['caracteristicas'])
					->setCellValue('B' . $nIni, $v['cantidad'])
					->setCellValue('C' . $nIni, moneda(floatval($v['costo']) / floatval($v['cantidad'])))
					->setCellValue('D' . $nIni, moneda($v['costo']))
					->setCellValue('E' . $nIni, $v['diasValidez']);

				$obj1 =	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, '** Precio valido por ' . $data[0]['diasValidez']);
				foreach ($imgProveedor as $k => $v) {
					if ($v['extension'] == 'jpeg') {
						$gdImage = imagecreatefromjpeg(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
					}
					if ($v['extension'] == 'png') {
						$gdImage = imagecreatefrompng(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
					}

					$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
					$objDrawing->setName('Sample image');
					$objDrawing->setDescription('TEST');
					$objDrawing->setImageResource($gdImage);
					if ($v['extension'] == 'jpeg') {
						$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
					}
					if ($v['extension'] == 'png') {
						$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
					}
					$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
					$objDrawing->setHeight(100);
					$objDrawing->setCoordinates('A' . $nIni);
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
					$nIni = $nIni + 6;
				}
			} else {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $nIni, $itm['nombre'] . ' - ' . $itm['caracteristicas'])
					->setCellValue('B' . $nIni, $v['cantidad'])
					->setCellValue('C' . $nIni, moneda(floatval($v['costo']) / floatval($v['cantidad'])))
					->setCellValue('D' . $nIni, moneda($v['costo']));
			}

			$nIni++;
		}
		$fin = $nIni - 1;
		$objPHPExcel->getActiveSheet()->getStyle("A14:A$fin")->applyFromArray($estilo_data['left']);
		$objPHPExcel->getActiveSheet()->getStyle("B14:B$fin")->applyFromArray($estilo_data['center']);
		$objPHPExcel->getActiveSheet()->getStyle("C14:C$fin")->applyFromArray($estilo_data['right']);
		$objPHPExcel->getActiveSheet()->getStyle("D14:D$fin")->applyFromArray($estilo_data['right']);
		$objPHPExcel->getActiveSheet()->getStyle("E14:E$fin")->applyFromArray($estilo_data['center']);

		$nIni++;
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, '** Precion no incluye IGV');
		$nIni++;
		$obj1;
		$nIni++;
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, 'IMAGENES ADJUNTAS');

		$nIni = $nIni + 2;


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function formularioVisualizacionCotizacionProveedorItems()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$data = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor($post)->result_array();
		$dataParaVista['cabecera'] = $this->model->obtenerInformacionCotizacion(['id' => $data[0]['idCotizacion']])['query']->row_array();
		// $dataimg = [];

		foreach ($data as $key => $row) {
			$dataParaVista['item'][$row['idItem']]['idItem'] = $row['idItem'];
			$dataParaVista['item'][$row['idItem']]['item'] = $row['item'];
			$dataParaVista['item'][$row['idItem']]['tipoItem'] = $row['tipoItem'];
			$dataParaVista['item'][$row['idItem']]['cantidad'] = $row['cantidad'];
			$dataParaVista['item'][$row['idItem']]['unidadMedida'] = $row['unidadMedida'];
			$dataParaVista['item'][$row['idItem']]['idItemTipo'] = $row['idItemTipo'];

			$dataParaVista['proveedor'][$row['idProveedor']]['proveedor'] = $row['proveedor'];

			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['costoUnitario'] = number_format($row['costoUnitario'], 2, '.', '');
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['costo'] = number_format($row['costo'], 2, '.', '');
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['fechaValidez'] = $row['fechaValidez'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['diasEntrega'] = $row['diasEntrega'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['comentario'] = $row['comentario'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['sucursal'] = $row['sucursal'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['razonSocial'] = $row['razonSocial'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['tipoElemento'] = $row['tipoElemento'];
			$dataParaVista['itemProveedor'][$row['idItem']][$row['idProveedor']]['marca'] = $row['marca'];

			$dataParaVista['images'][$row['idItem']][$row['idProveedor']] = $this->model->obtenerArchivoCotizacionDetalleProveedors(['idCotizacionDetalleProveedorDetalle' => $row['idCotizacionDetalleProveedorDetalle']])->result_array();
			$dataParaVista['subItems'][$row['idItem']][$row['idProveedor']] = $this->model_formulario_proveedor->obtenerInformacionCotizacionDetalleSub(['idCotizacionDetalleProveedorDetalle' => $row['idCotizacionDetalleProveedorDetalle']])->result_array();
		}

		$dataParaVista['estados'] = $this->model_control->get_estados_cotizacion()->result_array();
		$dataParaVista['idCotizacionDetalle'] = $post['idCotizacionDetalle'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Cotización Item - Proveedor';
		$result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioCotizacionProveedorItems", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarCotizacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data['tabla'] = 'compras.cotizacion';

		$data = [];

		$data['update'] = [
			'nombre' => $post['nombre'],
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
			'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
			'fee' => $post['feeForm'],
			'total' => $post['totalForm'],
			'comentario' => $post['comentarioForm'],
		];

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
		$post['flagCuenta'] = checkAndConvertToArray($post['flagCuenta']);
		$post['flagRedondearForm'] = checkAndConvertToArray($post['flagRedondearForm']);
		$post['diasEntregaItem'] = checkAndConvertToArray($post['diasEntregaItem']);
		$post['tituloCoti'] = checkAndConvertToArray($post['tituloCoti']);

		foreach ($post['nameItem'] as $k => $r) {
			$data['update'][] = [
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
				// 'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
				'idCotizacionDetalleEstado' => 2,
				'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
				'caracteristicasCompras' => !empty($post['caracteristicasCompras'][$k]) ? $post['caracteristicasCompras'][$k] : NULL,
				'caracteristicasProveedor' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
				'flagCuenta' => !empty($post['flagCuenta'][$k]) ? $post['flagCuenta'][$k] : 0,
				'flagRedondear' => !empty($post['flagRedondearForm'][$k]) ? $post['flagRedondearForm'][$k] : 0,
				'diasEntrega' => !empty($post['diasEntregaItem'][$k]) ? $post['diasEntregaItem'][$k] : NULL,
				'tituloParaOC' => !empty($post['tituloCoti'][$k]) ? $post['tituloCoti'][$k] : NULL,
				'enlaces' => !empty($post['linkForm'][$k]) ? $post['linkForm'][$k] : NULL,
			];

			// Cambiar de nombre en la tabla Item en caso se haga una modificacion en el mismo.
			if (!empty($post['idItemForm'][$k]) && $post['nameItem'][$k] != $post['nameItemOriginal'][$k]) {
				$this->db->update('compras.item', ['nombre' => $post['nameItem'][$k]], ['idItem' => $post['idItemForm'][$k]]);
			}
			// FIN

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

			if (!empty($post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"])) {
				switch ($post['tipoItemForm'][$k]) {
					case COD_SERVICIO['id']:
						$data['subDetalle'][$k] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
							'sucursal' => $post["sucursalSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'razonSocial' => $post["razonSocialSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'tipoElemento' => $post["tipoElementoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'marca' => $post["marcaSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'nombre' => $post["nombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'cantidad' => $post["cantidadSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'costo' => $post["costoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
							'subtotal' => $post["subtotalSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						]);
						break;

					case COD_DISTRIBUCION['id']:
						$data['subDetalle'][$k] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
							'unidadMedida' => $post["unidadMedidaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
							'tipoServicio' => $post["tipoServicioSubItem[{$post['idCotizacionDetalle'][$k]}]"],
							'costo' => $post["costoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
							'cantidad' => $post["cantidadSubItemDistribucion[{$post['idCotizacionDetalle'][$k]}]"],
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
							'costo' => $post["costoTextil[{$post['idCotizacionDetalle'][$k]}]"],
							'subtotal' => $post["subtotalTextil[{$post['idCotizacionDetalle'][$k]}]"],
						]);
						break;

					case COD_TARJETAS_VALES['id']:
						$data['subDetalle'][$k] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
							'monto' => $post["montoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
						]);
						break;

					default:
						$data['subDetalle'][$k] = [];
						break;
				}
			}

			if ($post['tipoItemForm'][$k] == COD_SERVICIO['id']) {
				if (!empty($post["newNombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"])) {
					$this->db->delete('compras.cotizacionDetalleSub', ['idCotizacionDetalle' => $post['idCotizacionDetalle'][$k]]);
					$data['insertSubItem'][$k] = getDataRefactorizada([
						'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
						'nombre' => $post["newNombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'cantidad' => $post["newCantidadSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'costo' => $post["newCostoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'subtotal' => $post["newSubtotalSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'sucursal' => $post["newSucursaleSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'razonSocial' => $post["newRazonSocialSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'tipoElemento' => $post["newTipoElementoSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
						'marca' => $post["newMarcaSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
					]);
				}
			}
		}
		$data['archivoEliminado'] = $post['archivoEliminado'];

		$data['tabla'] = 'compras.cotizacionDetalle';
		$data['where'] = 'idCotizacionDetalle';

		$updateDetalle = $this->model->actualizarCotizacionDetalleArchivos($data);
		$data = [];

		$estadoEmail = true;
		if ($post['tipoRegistro'] == 2) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : MAIL_COORDINADORA_COMPRAS);

			$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toOperaciones = [];
			foreach ($usuariosOperaciones as $usuario) {
				$toOperaciones[] = $usuario['email'];
			}

			$estadoEmail = $this->enviarCorreo(['idCotizacion' => $post['idCotizacion'], 'to' => $toOperaciones]);

			// $estadoEmail = $this->enviarCorreo(['idCotizacion' => $post['idCotizacion']]);
		}

		if (!$update['estado'] || !$updateDetalle['estado'] || !$estadoEmail) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');

			if ($post['tipoRegistro'] == ESTADO_CONFIRMADO_COMPRAS) {
				$data['tabla'] = 'compras.cotizacion';
				$data['update'] = [
					'idCotizacionEstado' => $post['tipoRegistro'],
				];
				$data['where'] = [
					'idCotizacion' => $post['idCotizacion'],
				];

				$this->model->actualizarCotizacion($data);

				$insertCotizacionHistorico = [
					'idCotizacionEstado' => ESTADO_CONFIRMADO_COMPRAS,
					'idCotizacion' => $post['idCotizacion'],
					'idUsuarioReg' => $this->idUsuario,
					'estado' => true,
				];
				$insertCotizacionHistorico = $this->model->insertar(['tabla' => TABLA_HISTORICO_ESTADO_COTIZACION, 'insert' => $insertCotizacionHistorico]);
			}
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function enviarCorreo($params = [])
	{
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			// 'smtp_host' => 'aspmx.l.google.com',
			// 'smtp_port' => '25',
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
	public function enviarSolicitudProveedor()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		foreach (checkAndConvertToArray($post['proveedorSolicitudForm']) as $idPro) {
			$datoExistente = $this->db->get_where('compras.cotizacionDetalleProveedor', ['idProveedor' => $idPro, 'idCotizacion' => $post['idCotizacion'], 'estado' => 1])->result_array();
			if (!empty($datoExistente)) {
				$result['result'] = 1;
				$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Ya se asignó previamente al proveedor con esta cotización.']);
				$result['msg']['title'] = 'Alerta';
				goto respuesta;
			}
		}

		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		foreach ($post['nameItem'] as $k => $r) {
			$idCotizacionDetalle_ = $post['idCotizacionDetalle'][$k];

			if (empty($post["checkItem[{$idCotizacionDetalle_}]"])) continue;
			$data['select'][] = $idCotizacionDetalle_;
		}

		// No deberia activarse esta alerta, debido a que existe una validación con las funciones del semantic
		if (empty($data['select'])) {
			$result['result'] = 1;
			$result['data']['html'] = createMessage(['type' => 2, 'message' => 'Debe seleccionar al menos un item']);
			$result['msg']['title'] = 'Alerta';
			goto respuesta;
		}

		$items = implode(",", $data['select']);
		$dataParaVista['detalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $post['idCotizacion'], 'cotizacionInterna' => true, 'idCotizacionDetalle' => $items])['query']->result_array();

		$data = [];
		$post['proveedorSolicitudForm'] = checkAndConvertToArray($post['proveedorSolicitudForm']);

		$cotizacionProveedores = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor(['idCotizacion' => $post['idCotizacion']])->result_array();
		$cotizacionProveedor = [];
		$cotizacionProveedorDetalle = [];
		foreach ($cotizacionProveedores as $p_cotizacion) {
			$cotizacionProveedor[$p_cotizacion['idProveedor']] = $p_cotizacion;
			$cotizacionProveedorDetalle[$p_cotizacion['idProveedor']][$p_cotizacion['idCotizacion']][$p_cotizacion['idItem']] = $p_cotizacion;
		}
		$rs['estado'] = true;
		$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => USER_COORDINADOR_COMPRAS])['query']->result_array();
		$ccCompras = [];
		foreach ($usuariosCompras as $usuario) {
			$ccCompras[] = $usuario['email'];
		}
		foreach ($post['proveedorSolicitudForm'] as $idProveedor) {
			if (empty($cotizacionProveedor[$idProveedor])) {
				$data['tabla'] = 'compras.cotizacionDetalleProveedor';
				$data['insert'] = [
					'idProveedor' => $idProveedor,
					'idCotizacion' => $post['idCotizacion'],
					'estado' => true,
				];
				$rs = $this->model->insertar($data);
				$idCotizacionDetalleProveedor = $rs['id'];
			}

			if (!empty($cotizacionProveedor[$idProveedor])) {
				$idCotizacionDetalleProveedor = $cotizacionProveedor[$idProveedor]['idCotizacionDetalleProveedor'];
			}

			$data = [];
			$asunto = 'SOLICITUD DE COTIZACIÓN';
			$titles = [];
			foreach ($dataParaVista['detalle'] as $k => $row) {
				if (!empty($row['tituloParaOc'])) {
					$titles[] = $row['tituloParaOc'];
				}
				if (!empty($titles)) {
					$asunto = 'COTIZACIÓN - ' . implode(', ', $titles);
				}
				$row_cotizacion = isset($cotizacionProveedorDetalle[$idProveedor][$post['idCotizacion']]) ? $cotizacionProveedorDetalle[$idProveedor][$post['idCotizacion']] : [];
				if (empty($row_cotizacion[$row['idItem']])) {
					$data['insert'][] = [
						'idCotizacionDetalleProveedor' => $idCotizacionDetalleProveedor,
						'idItem' => $row['idItem'],
						'costo' => $row['costo'],
						'flag_activo' => 1,
						'fechaCreacion' => getActualDateTime(),
						'idCotizacionDetalle' => $row['idCotizacionDetalle'],
						'estado' => 1,
						'cantidad' => $row['cantidad']
					];
				}
			}

			$rsDet = true;
			if (!empty($data['insert'])) {
				$rsDet = $this->model_formulario_proveedor->insertarMasivoDetalleProveedor(
					[
						'tabla' => 'compras.cotizacionDetalleProveedorDetalle',
						'insert' => $data['insert'],
						'post' => $post
					]
				);
			}

			if (!$rs['estado'] || !$rsDet) {
				$result['result'] = 1;
				$result['data']['html'] = createMessage(['type' => 2, 'message' => 'No se pudo enviar la solicitud']);
				$result['msg']['title'] = 'Alerta';

				goto respuesta;
			}

			$proveedor = $this->model_proveedor->obtenerInformacionProveedores(['idProveedor' => $idProveedor])['query']->row_array();

			$accesoDocumento = !empty($proveedor['nroDocumento']) ? base64_encode($proveedor['nroDocumento']) : '';
			$accesoEmail = !empty($proveedor['correoContacto']) ? base64_encode($proveedor['correoContacto']) : '';
			$fechaActual = base64_encode(date('Y-m-d'));
			$accesoCodProveedor = !empty($proveedor['idProveedor']) ? base64_encode($proveedor['idProveedor']) : '';

			$urlAcceso = "?doc={$accesoDocumento}&email={$accesoEmail}&date={$fechaActual}&cod={$accesoCodProveedor}";

			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toComprasProveedor = [];
			foreach ($usuariosCompras as $usuario) {
				$toComprasProveedor[] = $usuario['email'];
			}

			$contactosProveedor =  $this->db->get_where('compras.proveedorCorreo', ['idProveedor' => $proveedor['idProveedor'], 'estado' => 1])->result_array();

			foreach ($contactosProveedor as $contactoProveedor) {
				$toComprasProveedor[] = $contactoProveedor['correo'];
			}

			$html = $this->load->view("modulos/SolicitudCotizacion/correoProveedor", $dataParaVista, true);
			$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "FormularioProveedor/Cotizaciones/{$post['idCotizacion']}{$urlAcceso}"], true);
			$config = [
				'to' => !empty($proveedor['correoContacto']) ? $proveedor['correoContacto'] : $toComprasProveedor,
				'cc' => $toComprasProveedor,
				'asunto' => $asunto,
				'contenido' => $correo,
			];
			email($config);
		}

		$result['result'] = 1;
		$result['data']['html'] = createMessage(['type' => 1, 'message' => 'Solicitud enviada al proveedor']);
		$result['msg']['title'] = 'Solicitud Enviada';

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function cerrarCotizacionProveedor()
	{
		$post = $this->input->post();
		$this->db->update('compras.cotizacionDetalleProveedorDetalle', ['flag_activo' => 0], ['idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle']]);
		return 'x';
	}
	public function verCotizacionesProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		foreach ($post['nameItem'] as $k => $r) {
			$data['select'][] = $post['idCotizacionDetalle'][$k];
		}
		$detalles = implode(",", $data['select']);
		$dataParaVista['detalle'] = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor(['idCotizacionDetalle' => $detalles])->result_array();

		$html = $this->load->view("modulos/SolicitudCotizacion/viewCotizacionesProveedor", $dataParaVista, true);

		$result['result'] = 1;
		$result['msg']['title'] = 'Solicitudes';
		$result['data']['html'] = $html;

		echo json_encode($result);
	}

	public function viewSolicitudCotizacionInterna($idCotizacion = '')
	{
		if (empty($idCotizacion)) {
			redirect('SolicitudCotizacion', 'refresh');
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
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion([
			'idCotizacion' => $idCotizacion,
			'cotizacionInterna' => true,
			'noTipoItem' => COD_DISTRIBUCION['id'] . ',' . COD_PERSONAL['id']
		])['query']->result_array();

		$cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSub(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => true,
				'noTipoItem' => COD_DISTRIBUCION['id']
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
			$config['data']['cotizacionDetalleArchivosDelProveedor'][$sub['idCotizacionDetalle']] = $this->model->getCotizacionProveedorArchivosSeleccionados(['idCotizacionDetalle' => $sub['idCotizacionDetalle']])->result_array();
		}

		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => true])['query']->result_array();
		$cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => true])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => true])['query']->result_array();

		foreach ($archivos as $archivo) {
			$config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
		}
		foreach ($cotizacionProveedores as $cotizacionProveedor) {
			$config['data']['cotizacionProveedor'][$cotizacionProveedor['idCotizacionDetalle']] = $cotizacionProveedor;
			$config['data']['cotizacionProveedorRegistrados'][$cotizacionProveedor['idCotizacionDetalle']][] = $cotizacionProveedor['razonSocial'];
		}
		$cotizacionProveedorSubDetalle = [];
		foreach ($cotizacionProveedoresVista as $cotizacionProveedorVista) {
			$config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;

			$cotizacionProveedorSubDetalle[]  = $this->db->get_where('compras.cotizacionDetalleProveedorDetalleSub', ['idCotizacionDetalleProveedorDetalle' => $cotizacionProveedorVista['idCotizacionDetalleProveedorDetalle'], 'estado' => 1])->result_array();

			$config['data']['cotizacionProveedorArchivos'][$cotizacionProveedorVista['idCotizacionDetalleProveedorDetalle']] = $this->model->obtenerArchivoCotizacionDetalleProveedors(['idCotizacionDetalleProveedorDetalle' => $cotizacionProveedorVista['idCotizacionDetalleProveedorDetalle']])->result_array();
		}

		foreach ($cotizacionProveedorSubDetalle as $subProveedor) {
			foreach ($subProveedor as $sub) {
				$config['data']['cotizacionProveedorSub'][$sub['idCotizacionDetalleProveedorDetalle']][] = $sub;
			}
		}

		$config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
		$proveedores = $this->model_proveedor->obtenerInformacionProveedores([/*'estadoProveedor' => 2, */'order_by' => 'razonSocial ASC'])['query']->result_array();

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
		if (!empty($data['itemServicio'])) {
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
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$config['data']['tipoMoneda'] = $this->model->obtenertipoMoneda()['query']->result_array();
		$config['data']['tipoServicioCotizacion'] = $this->model->obtenerTipoServicioCotizacion()['query']->result_array();
		$config['data']['siguienteEstado'] = ESTADO_CONFIRMADO_COMPRAS;
		$config['data']['controller'] = 'SolicitudCotizacion';
		$config['data']['disabled'] = false;
		$config['view'] = 'modulos/SolicitudCotizacion/viewFormularioActualizarCotizacion';

		$this->view($config);
	}

	public function viewUpdateOper($idOper = '')
	{
		if (empty($idOper)) {
			redirect('SolicitudCotizacion', 'refresh');
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
		$oper = $this->model->obtenerInformacionOper(['idOper' => $idOper])['query']->result_array();
		$ids = [];
		foreach ($oper as $v) {
			$ids[] = $v['idCotizacion'];
			$config['data']['oper'][$v['idOper']] = $v;
		}

		$idCotizacion = implode(",", $ids);

		$config['data']['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
		//Obteniendo Solo los Items Nuevos para verificacion de los proveedores
		$config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => false,
				'noTipoItem' => COD_DISTRIBUCION['id'],
				'noOC' => true
			]
		)['query']->result_array();

		$cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSub(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => false,
				'noTipoItem' => COD_DISTRIBUCION['id']
			]
		)['query']->result_array();

		foreach ($cotizacionDetalleSub as $sub) {
			$config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
		}

		$autorizaciones = $this->model_autorizacion->getAutorizaciones(['idCotizacion' => $idCotizacion])['query']->result_array();

		foreach ($autorizaciones as $autorizacion) {
			$config['data']['autorizaciones'][$autorizacion['idCotizacionDetalle']] = $autorizacion;
		}

		$archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos([
			'idCotizacion' => $idCotizacion,
			'cotizacionInterna' => false,
			'noTipoItem' => COD_DISTRIBUCION
		])['query']->result_array();
		$cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion' => $idCotizacion, 'union' => true])['query']->result_array();
		$cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion' => $idCotizacion, 'union' => true])['query']->result_array();

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
		$config['data']['tachadoDistribucion'] = $this->model->getTachadoDistribucion()['query']->result_array();
		$config['data']['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
		$config['data']['siguienteEstado'] = ESTADO_OC_ENVIADA;
		$config['data']['controller'] = 'SolicitudCotizacion';
		$config['data']['disabled'] = false;
		$config['data']['idOper'] = $idOper;
		$config['view'] = 'modulos/SolicitudCotizacion/viewFormularioGenerarOrdenCompra';
		$this->view($config);
	}
	public function registrarSincerado()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['cotizacionDetalleSub'] = checkAndConvertToArray($post['cotizacionDetalleSub']);

		$dataInsert = [];
		foreach ($post['cotizacionDetalleSub'] as $v) {
			$dataInsert[] = [
				'idCotizacionDetalleSub' => $v,
				'costo' => $post["costo[$v]"],
				'dias' => $post["dias[$v]"],
				'cantidad' => $post["cantidad[$v]"],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($dataInsert)) {
			$this->db->insert_batch('compras.cotizacionDetalleSubSincerado', $dataInsert);
			$result['result'] = 1;
			$result['msg']['title'] = 'Registro Sincerado';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');

			$this->db->update('compras.cotizacion', ['idCotizacionEstado' => 4], ['idCotizacion' => $post['idCotizacion']]);
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Registro Sincerado';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function registrarOrdenCompra()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['idCotizacion'] = checkAndConvertToArray($post['idCotizacion']);

		$updateCotizacion = [];
		$insertHistoricoCotizacion = [];
		foreach ($post['idCotizacion'] as $idCotizacion) {
			$updateCotizacion[] = [
				'idCotizacion' => $idCotizacion,
				'idCotizacionEstado' => ESTADO_OC_ENVIADA,
			];

			$insertHistoricoCotizacion[] = [
				'idCotizacionEstado' => ESTADO_OC_ENVIADA,
				'idCotizacion' => $idCotizacion,
				'idUsuarioReg' => $this->idUsuario,
			];
		}
		$data = [];
		$data['oc'] = getDataRefactorizada([
			'idProveedor' => $post['idProveedor'],
			'metodoPago' => $post['metodoPago'],
			'moneda' => $post['idMoneda'],
			'igvOrden' => isset($post['igvOrden']) ? $post['igvOrden'] : false,
			'fechaEntrega' => $post['fechaEntrega'],
			'lugarEntrega' => $post['lugarEntrega'],
			'pocliente' => $post['pocliente'],
			'observacion' => $post['observacion'],
			'comentario' => $post['comentario'],
			'mostrar_observacion' => isset($post['mostrar_observacion']) ? 1 : 0,
			'mostrar_imagenes' => isset($post['mostrar_imagenes']) ? 1 : 0,
			'mostrar_imagenesCoti' => isset($post['mostrar_imagenesCoti']) ? 1 : 0,
			'idAlmacen' => $post['idAlmacen'],
			'idOper' => isset($post['idOper']) ? $post['idOper'] : null,
			'enlaces' => isset($post['enlaces']) ? $post['enlaces'] : null,
			'descripcionFinanzas' => $post['descripcionFinanzas'],
		]);

		$oper = $this->db->get_where("compras.oper", ['idOper' => $post['idOper']])->row_array();
		$correosProveedor = [];
		foreach ($data['oc'] as $row) {
			$insert_oc = [
				'idProveedor' => $row['idProveedor'],
				'estado' => true,
				'idUsuarioReg' => $this->idUsuario,
				'requerimiento' => $oper['requerimiento'],
				'idMetodoPago' => $row['metodoPago'],
				'concepto' => !empty($oper['concepto']) ? $oper['concepto'] : NULL,
				'idMoneda' => !empty($row['moneda']) ? $row['moneda'] : 1, //Moneda SOL por defecto
				'observacion' => !empty($row['observacion']) ? $row['observacion'] : NULL,
				'comentario' => !empty($row['comentario']) ? $row['comentario'] : NULL,
				'pocliente' => !empty($row['pocliente']) ? $row['pocliente'] : NULL,
				'igv' => $row['igvOrden'] ? '18' : null,
				'entrega' => !empty($row['lugarEntrega']) ? $row['lugarEntrega'] : NULL,
				'fechaEntrega' => !empty($row['fechaEntrega']) ? $row['fechaEntrega'] : NULL,
				'mostrar_observacion' => !empty($row['mostrar_observacion']) ? $row['mostrar_observacion'] : NULL,
				'mostrar_imagenes' => !empty($row['mostrar_imagenes']) ? $row['mostrar_imagenes'] : NULL,
				'mostrar_imagenesCoti' => !empty($row['mostrar_imagenesCoti']) ? $row['mostrar_imagenesCoti'] : NULL,
				'idAlmacen' => !empty($row['idAlmacen']) ? $row['idAlmacen'] : NULL,
				'enlaces' => !empty($row['enlaces']) ? $row['enlaces'] : NULL,
				'seriado' => 'OC' . $this->model->obtenerSeriado(OC_SERIADO),
				'descripcionFinanzas' => $row['descripcionFinanzas']
			];

			$rs_oc = $this->model->insertar(['tabla' => 'compras.ordenCompra', 'insert' => $insert_oc]);

			if (!empty($post["idCotizacionDetalle[{$row['idProveedor']}]"])) {
				$post["idCotizacionDetalle[{$row['idProveedor']}]"] = checkAndConvertToArray($post["idCotizacionDetalle[{$row['idProveedor']}]"]);

				foreach ($post["idCotizacionDetalle[{$row['idProveedor']}]"] as $rowdet) {
					$data['insert']['oc_detalle'][] = [
						'idOrdenCompra' => $rs_oc['id'],
						'idCotizacionDetalle' => $rowdet,
						'idUsuarioReg' => $this->idUsuario,
					];
				}
			}

			$correosProveedor[] = [
				'idProveedor' => $row['idProveedor'],
				'idOrdenCompra' => $rs_oc['id'],
			];
		}

		if (!empty($data['insert']['oc_detalle'])) {
			$rs_det = $this->model->insertarMasivo("compras.ordenCompraDetalle", $data['insert']['oc_detalle']);
		}

		$updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion', $updateCotizacion, 'idCotizacion');
		$insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION, $insertHistoricoCotizacion);

		if ($rs_det && $updateCotizacion && $insertHistoricoCotizacion) {
			$result['result'] = 1;
			$result['msg']['title'] = 'Generar OC';
			$result['data']['html'] = getMensajeGestion('registroExitoso');
			$dataParaVista = [];
			$ids = implode(',', $post['idCotizacion']);
			$dataParaVista['detalle'] = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();

			foreach ($correosProveedor as $correoProveedor) {
				$dataProveedor = $this->model_proveedor->obtenerInformacionProveedores(['idProveedor' => $correoProveedor['idProveedor']])['query']->row_array();

				$accesoDocumento = !empty($dataProveedor['nroDocumento']) ? base64_encode($dataProveedor['nroDocumento']) : '';
				$accesoEmail = !empty($dataProveedor['correoContacto']) ? base64_encode($dataProveedor['correoContacto']) : '';
				$fechaActual = base64_encode(date('Y-m-d'));
				$accesoCodProveedor = !empty($dataProveedor['idProveedor']) ? base64_encode($dataProveedor['idProveedor']) : '';

				$urlAcceso = "?doc={$accesoDocumento}&email={$accesoEmail}&date={$fechaActual}&cod={$accesoCodProveedor}";

				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

				$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toComprasProveedor = [];
				foreach ($usuariosCompras as $usuario) {
					$toComprasProveedor[] = $usuario['email'];
				}

				$contactosProveedor =  $this->db->get_where('compras.proveedorCorreo', ['idProveedor' => $correoProveedor['idProveedor'], 'estado' => 1])->result_array();

				foreach ($contactosProveedor as $contactoProveedor) {
					$toComprasProveedor[] = $contactoProveedor['correo'];
				}

				$dataParaVista['ocDet'] = $this->model_formulario_proveedor->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $rs_oc['id']])['query']->result_array();
				$html = $this->load->view("modulos/Cotizacion/correoGeneracionOC", $dataParaVista, true);
				$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "FormularioProveedor/viewOrdenCompra/{$correoProveedor['idOrdenCompra']}{$urlAcceso}"], true);

				$config = [
					'to' => !empty($dataProveedor['correoContacto']) ? $dataProveedor['correoContacto'] : $toComprasProveedor,
					'cc' => $toComprasProveedor,
					'asunto' => 'OC ' . $rs_oc['id'] . ' - ' . $post['asunto'],
					'contenido' => $correo,
				];
				email($config);
			}
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Generar OC';
			$result['data']['html'] = getMensajeGestion('registroErroneo');
		}

		$verificarCierre = $this->model->obtenerInformacionDetalleCotizacion(
			[
				'idCotizacion' => $idCotizacion,
				'cotizacionInterna' => false,
				'noTipoItem' => COD_DISTRIBUCION['id'],
				'noOC' => true
			]
		)['query']->result_array();
		if (!empty($verificarCierre)) {
			$this->db->update('compras.cotizacion', ['idCotizacionEstado' => ESTADO_OC_GENERADA], ['idCotizacion' => $idCotizacion]);
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function descargarOrdenCompraPdf() // Es la vista previa de como se veria la OC
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		// Data de Cabecera
		$dataOper = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])['query']->result_array();
		$dataProveedor = $this->model_proveedor->obtenerInformacionProveedores(['idProveedor' => $post['idProveedor']])['query']->row_array();
		$dataMoneda = $this->db->get_where('compras.moneda', ['idMoneda' => $post['idMoneda']])->row_array();
		$dataMetodoPago = $this->db->get_where('compras.metodoPago', ['idMetodoPago' => $post['metodoPago']])->row_array();
		$idUsuarioFirma = $this->db->get_where('sistema.usuario', ['idUsuario' => $this->idUsuario])->row_array()['idUsuarioFirma'];
		$dataFirma = $this->db->get_where('sistema.usuarioFirma', ['idUsuarioFirma' => $idUsuarioFirma])->row_array();

		$cotDet = [];
		foreach (checkAndConvertToArray($post["idCotizacionDetalle[{$post['idProveedor']}]"]) as $k => $v) {
			$xxd = checkAndConvertToArray($post["caracteristicasItem[{$post['idProveedor']}]"]);
			$this->db->update('compras.cotizacionDetalle', ['caracteristicasCompras' => $xxd[$k]], ['idCotizacionDetalle' => $v]);
			$cotDet[] = $v;
		}
		$cotizacionDet = implode(',', $cotDet);
		// Data para detalle
		$ids = implode(', ', checkAndConvertToArray($post['idCotizacion']));
		$detalleCotizacion = $this->model->obternerCotizacionDetalle(['idCotizacion' => $ids, 'idProveedor' => $post['idProveedor'], 'idCotizacionDetalle' => $cotizacionDet])->result_array();
		$dataSub = [];
		foreach ($detalleCotizacion as $key => $value) {
			$detalleCotizacion[$key]['cotizacionSubTotal'] = $value['subtotal'];
			$detalleCotizacion[$key]['subTotalOrdenCompra'] = $value['subtotal'];
		}

		$dataParaVista['data'] = [
			'requerimiento' => $dataOper[0]['requerimiento'],
			'pocliente' => $post['pocliente'],
			'razonSocial' => $dataProveedor['razonSocial'],
			'rucProveedor' => $dataProveedor['nroDocumento'],
			'nombreContacto' => $dataProveedor['nombreContacto'],
			'direccion' => $dataProveedor['direccion'],
			'numeroContacto' => $dataProveedor['numeroContacto'],
			'correoContacto' => $dataProveedor['correoContacto'],
			'fechaEntrega' => $post['fechaEntrega'],
			'simboloMoneda' => $dataMoneda['simbolo'],
			'entrega' => $post['lugarEntrega'],
			'observacion' => $post['observacion'],
			'monedaPlural' => $dataMoneda['nombreMoneda'],
			'comentario' => $post['comentario'],
			'metodoPago' => $dataMetodoPago['nombre'],
			'nombre_archivo' => $dataFirma['nombre_archivo'],
			'igv' => (isset($post['igvOrden']) ? '18' : '0'),
			'mostrar_observacion' => isset($post['mostrar_observacion']) ? '1' : '0',
			'mostrar_imagenes' => isset($post['mostrar_imagenes']) ? '1' : '0',
			'mostrar_imagenesCoti' => isset($post['mostrar_imagenesCoti']) ? '1' : '0',
			'concepto' => isset($post['concepto']) ? $post['concepto'] : Null,
		];

		$dataParaVista['imagenesDeItem'] = [];
		if ($dataParaVista['data']['mostrar_imagenes'] == '1') {
			foreach ($detalleCotizacion as $key => $value) {
				$dataParaVista['imagenesDeItem'][$value['idItem']] = $this->db->where('idItem', $value['idItem'])->get('compras.itemImagen')->result_array();
			}
		}
		if ($dataParaVista['data']['mostrar_imagenesCoti'] == '1') {
			foreach ($detalleCotizacion as $key => $value) {
				$dd = $this->model->getImagenCotiProv(['idCotizacionDetalle' => $value['idCotizacionDetalle'], 'idProveedor' => $post['idProveedor']])->result_array();
				foreach ($dd as $kl => $vl) {
					$dataParaVista['imagenesDeItem'][$value['idItem']][] = $vl;
				}
			}
		}

		foreach ($detalleCotizacion as $k => $v) {
			$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
		}

		$dataParaVista['detalle'] = $detalleCotizacion;

		var_dump($dataParaVista['detalle']);
		exit;
		// METER ESTAS 2 LINEAS EN UN FOR, en caso se pase varias cotizaciones.
		$cuenta = $this->model->obtenerCuentaDeLaCotizacionDetalle(checkAndConvertToArray($post['idCotizacion'])[0]);
		$centroCosto = $this->model->obtenerCentroCostoDeLaCotizacionDetalle(checkAndConvertToArray($post['idCotizacion'])[0]);

		$cuentas[$cuenta] = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $cuenta])->row_array()['nombre'];
		$centrosDeCosto[$centroCosto] = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $centroCosto])->row_array()['subcanal'];

		$dataParaVista['cuentas'] = implode(', ', $cuentas);
		$dataParaVista['centrosCosto'] = implode(', ', $centrosDeCosto);

		$cuenta = $this->model->obtenerCuentaDeLaCotizacionDetalle(checkAndConvertToArray($post['idCotizacion'])[0]);

		require APPPATH . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
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
		$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/orden_compra_vistaPrevia", $dataParaVista, true);
		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		$cod_oc = $dataParaVista['data']['seriado'];

		$mpdf->Output("OC{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}

	public function getOrdenesCompra()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		// $ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'],'idOrdenCompra' => $idOrdenCompra,'estado' => 1])['query']->result_array();
		$dataParaVista['data'] = $this->model->obtenerInformacionOrdenCompra()['query']->result_array();

		$result['result'] = 1;
		$result['data']['width'] = '90%';
		$result['msg']['title'] = 'Ordenes de compra';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/tableOrdenCompra", $dataParaVista, true);

		echo json_encode($result);
	}

	public function frmGenerarOper()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$ids = implode(',', $post['ids']);
		$cotizaciones = $this->model->obtenerInformacionCotizacion(['id' => $ids])['query']->result_array();
		$cotizacionDetalle = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();

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

		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['msg']['title'] = 'GENERAR OPER';
		$result['data']['html'] = $this->load->view("modulos/Cotizacion/formRegistrarOper", $dataParaVista, true);

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

	public function filtrarCotPro()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$cotiPro = $this->model->getCotizacionDelProveedor()->result_array();

		$comp = '';
		foreach ($cotiPro as $k => $v) {
			if ($comp != $v['idCotizacion'] . '_' . $v['idProveedor']) {
				$comp = $v['idCotizacion'] . '_' . $v['idProveedor'];

				$doc = null;
				$files = $this->db->where('idCotizacionDetalleProveedorDetalle', $v['idCotizacionDetalleProveedorDetalle'])->get('compras.cotizacionDetalleProveedorDetalleArchivos')->result_array();
				foreach ($files as $kf => $vf) {
					if ($vf['idTipoArchivo'] == TIPO_EXCEL || $vf['idTipoArchivo'] == TIPO_PDF) {
						$doc = RUTA_WASABI . 'cotizacionProveedor/' . $vf['nombre_archivo'];
						$v['iconFile'] = $vf['idTipoArchivo'] == TIPO_EXCEL ? 'excel' : 'pdf';
					}
				}
				$v['doc'] = $doc;
				$dataParaVista['datos'][] = $v;
			}
		}
		// $operDetalle = $this->model->obtenerOperDetalleCotizacion()['query']->result_array();

		// foreach ($operDetalle as $row) {
		// 	$dataParaVista['cotizaciones'][$row['idOper']][] = $row['cotizacionCodNombre'];
		// }
		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/SolicitudCotizacion/reporteFiltroCotizacionProveedor", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'Cotizaciones del Proveedor';
		$result['data']['width'] = '80%';

		echo json_encode($result);
	}

	public function formPreviewOrdenCompra()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['monedas'] = $this->model->obtenerMonedas()['query']->result_array();

		$proveedores = [];
		$proveedoresBD = $this->model_proveedor->obtenerInformacionProveedores()['query']->result_array();

		foreach ($proveedoresBD as $proveedor) {
			$proveedores['dataProveedor'][$proveedor['idProveedor']] = $proveedor;
			$proveedores['proveedorMetodoPago'][$proveedor['idProveedor']][$proveedor['idMetodoPago']] = $proveedor;
		}

		$dataParaVista['dataOper'] = $this->db->get_where('compras.oper', ['idOper' => $post['idOper']])->row_array();

		$dataParaVista['data'] = [
			'idOper' => $post['idOper'],
			'idCotizacion' => checkAndConvertToArray($post['idCotizacion']),
			'feeForm' => !empty($post['feeForm']) ? $post['feeForm'] : '',
			'totalForm' => !empty($post['totalForm']) ? $post['totalForm'] : '',
			'totalFormFeeIgv' => !empty($post['totalFormFeeIgv']) ? $post['totalFormFeeIgv'] : '',
			'totalFormFee' => !empty($post['totalFormFee']) ? $post['totalFormFee'] : '',
		];
		$dataParaVista['detalle'] = getDataRefactorizada([
			'idCotizacionDetalle' => $post['idCotizacionDetalle'],
			'nameItem' => $post['nameItem'],
			'idItemForm' => $post['idItemForm'],
			'idEstadoItemForm' => $post['idEstadoItemForm'],
			'idProveedorForm' => $post['idProveedorForm'],
			'cotizacionInternaForm' => $post['cotizacionInternaForm'],
			'tipoItemForm' => $post['tipoItemForm'],
			'caracteristicasItem' => $post['caracteristicasItem'],
			'cantidadForm' => $post['cantidadForm'],
			'costoForm' => $post['costoForm'],
			'gapForm' => $post['gapForm'],
			'precioForm' => $post['precioForm'],
			'subtotalForm' => empty($post['subtotalForm']) ? number_format($post['cantidadForm'] * $post['costoForm'], 2) : $post['subtotalForm'],
			'ocDelCliente' => $post['ocDelCliente'],
			// 'caracteristicasCompras' => $post['caracteristicasCompras'],
		]);

		$provCompare = [];
		$post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
		$titulos = [];
		foreach ($post['idCotizacionDetalle'] as $kc => $vc) {
			$tt = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacionDetalle' => $vc])->row_array()['tituloParaOC'];
			if (!empty($tt)) {
				$titulos[] = $tt;
			}
		}
		$titulo = '';
		if (!empty($titulos)) {
			$titulo = implode(', ', $titulos);
		}
		$enlaces = [];
		foreach ($post['idCotizacionDetalle'] as $kc => $vc) {
			$tt = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacionDetalle' => $vc])->row_array()['enlaces'];
			if (!empty($tt)) {
				$enlaces[] = $tt;
			}
		}
		$enlace = '';
		if (!empty($enlaces)) {
			$enlace = implode('\n ', $enlaces);
		}
		$dataParaVista['dataOper']['tituloAsunto'] = $titulo;
		$dataParaVista['dataOper']['enlaces'] = $enlace;
		foreach ($dataParaVista['detalle'] as $dd => $row) {
			$idCotizacionDetalle_ = $post['idCotizacionDetalle'][$dd];
			if (empty($post["checkItem[{$idCotizacionDetalle_}]"])) continue;
			$provCompare[$row['idProveedorForm']] = $row['idProveedorForm'];

			if (!empty($post["idCotizacionDetalleSub[{$row['idCotizacionDetalle']}]"])) {
				$k = $row['idCotizacionDetalle'];
				switch ($row['tipoItemForm']) {
					case COD_SERVICIO['id']:
						$dataParaVista['subDetalleOrden'][$k][$row['tipoItemForm']] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[$k]"],
							'nombre' => $post["nombreSubItemServicio[$k]"],
							'cantidad' => $post["cantidadSubItemServicio[$k]"],
							'sucursal' => $post["sucursalSubItemServicio[$k]"],
							'razonSocial' => $post["razonSocialSubItemServicio[$k]"],
							'tipoElemento' => $post["tipoElementoSubItemServicio[$k]"],
							'marca' => $post["marcaSubItemServicio[$k]"],
							'costo' => $post["precioUnitarioSubItemServicio[$k]"],
							'subtotal' => $post["subtotalSubItemServicio[$k]"],
						]);
						break;

					case COD_TEXTILES['id']:
						$dataParaVista['subDetalleOrden'][$k][$row['tipoItemForm']] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[$k]"],
							'talla' => $post["tallaSubItem[$k]"],
							'tela' => $post["telaSubItem[$k]"],
							'color' => $post["colorSubItem[$k]"],
							'cantidad' => $post["cantidadTextil[$k]"],
							'genero' => $post["generoTextil[$k]"]
						]);
						break;

					case COD_TARJETAS_VALES['id']:
						$dataParaVista['subDetalleOrden'][$k][$row['tipoItemForm']] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[$k]"],
							'monto' => $post["montoSubItem[$k]"],
						]);
						break;
					case COD_DISTRIBUCION['id']:
						$dataParaVista['subDetalleOrden'][$k][$row['tipoItemForm']] = getDataRefactorizada([
							'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[$k]"],
							'cantidad' => $post["cantidadSubItemDistribucion[$k]"],
							'costo' => $post["costoSubItem[$k]"],
							'idTipoServicio' => $post["tipoServicioSubItem[$k]"],
							'cantidadPdv' => $post["cantidadPDVSubItemDistribucion[$k]"],
							'unidadMedida' => $post["unidadMedidaNameSubItem[$k]"],
							'idUnidadMedida' => $post["unidadMedidaSubItem[$k]"],
						]);
						break;

					default:
						$data['subDetalleOrden'][$k][$row['tipoItemForm']] = [];
						break;
				}
			}

			$row['proveedor'] = $proveedores['dataProveedor'][$row['idProveedorForm']]['razonSocial'];
			$row['rucProveedor'] = $proveedores['dataProveedor'][$row['idProveedorForm']]['nroDocumento'];
			$row['proveedorMetodoPago'] = $proveedores['proveedorMetodoPago'][$row['idProveedorForm']];

			$dataParaVista['dataOrden'][$row['idProveedorForm']] = $row;

			// $row['subtotalForm'] = $row['costoForm'] * $row['cantidadForm']; //Debe tomarse el precio de compra para calcular el subtotal, porque es orden de compra
			$dataParaVista['dataOrdenDet'][$row['idProveedorForm']][] = $row;
		}

		if (count($provCompare) > 1) {
			$result['result'] = 2;
			$result['data']['html'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Se indicaron distintos proveedores en la selección']);
			$result['msg']['title'] = 'OC Vista previa';
			$result['data']['width'] = '30%';
			goto resultado;
		}
		$dataParaVista['almacenes'] = $this->db->where('estado', '1')->get('visualImpact.logistica.almacen')->result_array();
		$html = getMensajeGestion('noRegistros');

		if (!empty($dataParaVista)) {
			$dataParaVista['tipoServicios'] = $this->model->obtenertipoServicios()['query']->result_array();
			$html = $this->load->view("modulos/SolicitudCotizacion/viewOrdenCompraPre", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'OC Vista previa';
		$result['data']['width'] = '98%';

		resultado:
		echo json_encode($result);
	}

	public function frmPropuestasItem()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$cotizacionProveedorPropuesta =  $this->model->getPropuestasItem(
			[
				'idCotizacionDetalle' => $post['idCotizacionDetalle'],
			]
		)['query']->result_array();

		$propuestaArchivos = $this->model->getPropuestaItemArchivos(['idCotizacionDetalle' => $post['idCotizacionDetalle']])->result_array();

		foreach ($cotizacionProveedorPropuesta as $cpp) {
			$dataParaVista['cotizacionPropuesta'][$cpp['idPropuestaItem']] = $cpp;
		}

		foreach ($propuestaArchivos as $cpa) {
			$dataParaVista['cotizacionPropuestaArchivos'][$cpa['idPropuestaItem']][$cpa['idPropuestaItemArchivo']] = $cpa;
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/SolicitudCotizacion/frmSeleccionarPropuesta", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'PROPUESTAS ITEM';
		$result['data']['width'] = '90%';

		echo json_encode($result);
	}
}
