<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tarifario/M_Item', 'model');
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
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/core/gestion',
			'assets/custom/js/Tarifario/item',
		);

		$config['data']['icon'] = 'fas fa-shopping-cart';
		$config['data']['title'] = 'Items';
		$config['data']['message'] = 'Lista de Items';
		$config['data']['tipoItem'] = $this->model->obtenerItemTipo()['query']->result_array();
		$config['data']['itemMarca'] = $this->model->obtenerItemMarca()['query']->result_array();
		$config['data']['itemCategoria'] = $this->model->obtenerItemCategoria()['query']->result_array();
		$config['data']['subCategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$config['data']['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();
		$config['view'] = 'modulos/Tarifario/item/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['dataTarifario'] = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();
		$Rproveedor = [];
		$item = [];
		$itemProveedor = [];

		foreach ($dataParaVista['dataTarifario'] as $key => $value) {
			$Rproveedor[$value['idProveedor']] = [
				'idProveedor' => $value['idProveedor'],
				'nproveedor' => $value['proveedor']
			];

			$item[$value['idItem']] = $value;

			$itemProveedor[$value['idItem']][$value['idProveedor']] = $value;
		}

		$dataParaVista['dataProveedor'] = $Rproveedor;
		$dataParaVista['dataItem'] = $item;
		$dataParaVista['dataItemProveedor'] = $itemProveedor;

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Tarifario/Item/reporte",  $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentItem']['datatable'] = 'tb-item';
		$result['data']['views']['idContentItem']['html'] = $html;
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

	public function descargarTarifario() // Es la vista previa de como se veria la OC
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = $this->input->post();

		$dataParaVista['tarifario'] = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();
		// $dataProveedor = [];

		foreach ($dataParaVista['tarifario']  as $value) {
			$Rproveedor[$value['idProveedor']] = [
				'idProveedor' => $value['idProveedor'],
				'nproveedor' => $value['proveedor']
			];

			$item[$value['idItem']] = $value;
			$itemProveedor[$value['idItem']][$value['idProveedor']] = $value;
		}

		$dataParaVista['dataProveedor'] = $Rproveedor;
		$dataParaVista['dataItem'] = $item;
		$dataParaVista['dataItemProveedor'] = $itemProveedor;

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
			'orientation' => 'L',
		]);

		$contenido['header'] = $this->load->view("modulos/Tarifario/Item/pdf/header", ['title' => 'LISTADO DE ITEMs EN EL TARIFARIO' /*, 'codigo' => 'SIG-LOG-FOR-009' */], true);
		// $contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);

		$contenido['style'] = $this->load->view("modulos/Tarifario/Item/pdf/style", [], true);
		$contenido['body'] = $this->load->view("modulos/Tarifario/Item/pdf/reporte", $dataParaVista, true);
		$mpdf->SetHTMLHeader($contenido['header']);
		// $mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		// $cod_oc = generarCorrelativo($dataParaVista['data']['idOrdenCompra'], 6);

		$mpdf->Output("tarifario.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}

	public function descargarExcelDemo()
	{
		$post = $this->input->post();

		$dataTarifario = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();

		// $this->load->library('PHPExcel');

		// $objPHPExcel = $this->phpexcel;

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
			->setCellValue('B8', 'DEMO01')
			->setCellValue('A9', 'CUENTA')
			->setCellValue('B9', 'DEMO02')
			->setCellValue('A10', 'CC')
			->setCellValue('B10', 'DEMO03')
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
		// foreach ($data as $k => $v) {
		// 	$itm = $this->db->get_where('compras.item', ['idItem' => $v['idItem']])->row_array();
		// 	$objPHPExcel->setActiveSheetIndex(0)
		// 		->setCellValue('A' . $nIni, $itm['nombre'] . ' - ' . $itm['caracteristicas'])
		// 		->setCellValue('B' . $nIni, $v['cantidad'])
		// 		->setCellValue('C' . $nIni, moneda(floatval($v['costo']) / floatval($v['cantidad'])))
		// 		->setCellValue('D' . $nIni, moneda($v['costo']))
		// 		->setCellValue('E' . $nIni, $v['diasValidez']);
		// 	$nIni++;
		// }
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
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, '** Precio valido por ' . 'DEMO04');
		$nIni++;
		$nIni++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nIni, 'IMAGENES ADJUNTAS');

		$nIni = $nIni + 2;
		// foreach ($imgProveedor as $k => $v) {
		// 	if ($v['extension'] == 'jpeg') {
		// 		$gdImage = imagecreatefromjpeg(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
		// 	}
		// 	if ($v['extension'] == 'png') {
		// 		$gdImage = imagecreatefrompng(RUTA_WASABI . $v['ruta'] . $v['nombre_archivo']);
		// 	}

		// 	$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		// 	$objDrawing->setName('Sample image');
		// 	$objDrawing->setDescription('TEST');
		// 	$objDrawing->setImageResource($gdImage);
		// 	if ($v['extension'] == 'jpeg') {
		// 		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		// 	}
		// 	if ($v['extension'] == 'png') {
		// 		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		// 	}
		// 	$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		// 	$objDrawing->setHeight(100);
		// 	$objDrawing->setCoordinates('A' . $nIni);
		// 	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		// 	$nIni = $nIni + 6;
		// }

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function getFormCargaMasivaTarifario()
	{
		$result = $this->result;
		$result['msg']['title'] = "Carga masiva de tarifario";

		$params = array();
		$params['idUsuario'] = $this->session->userdata('idUsuario');

		$proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]], '*', [], 'razonSocial')->result_array();
		$proveedores = refactorizarDataHT(["data" => $proveedores, "value" => "razonSocial"]);
		$item['item'] = $this->model->obtenerItems();
		// $item['item'] = asort($item['item']);

		$itemNombre = refactorizarDataHT(["data" => $item['item'], "value" => "label"]);

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Tarifario',
			'data' => [
				[
					'item' => null,
					'proveedor' => null,
					'costo' => null,
					'fecha' => null,
					'itemActual' => null,
				]
			],
			'headers' => [
				'ITEM (*)',
				'PROVEEDOR (*)',
				'COSTO (*)',
				'FECHA (*)',
				'ESTE ITEM ES EL ACTUAL (*)',
			],
			'columns' => [
				['data' => 'item', 'type' => 'myDropdown', 'placeholder' => 'item', 'width' => 600, 'source' => $itemNombre],
				['data' => 'proveedor', 'type' => 'myDropdown', 'placeholder' => 'proveedor', 'width' => 200, 'source' => $proveedores],
				['data' => 'costo', 'type' => 'numeric', 'placeholder' => 'costo', 'width' => 200],
				['data' => 'fecha', 'type' => 'myDate', 'placeholder' => 'fecha', 'width' => 200],
				['data' => 'itemActual', 'type' => 'checkbox', 'placeholder' => 'itemActual', 'width' => 200],

			],
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		echo json_encode($result);
	}

	public function getFormActualizarMasivoTarifario()
	{
		$result = $this->result;
		$result['msg']['title'] = "Actualización masiva de tarifario";

		$params = array();
		$params['idUsuario'] = $this->session->userdata('idUsuario');

		$proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]], '*', [], 'razonSocial')->result_array();
		$proveedores = refactorizarDataHT(["data" => $proveedores, "value" => "razonSocial"]);
		$item['item'] = $this->model->obtenerItems();
		$itemNombre = refactorizarDataHT(["data" => $item['item'], "value" => "label"]);

		$data = $this->model->obtenerTarifarioItemProveedorParaActualizacionMasiva()->result_array();

		foreach ($data as $key => $value) {
			$data[$key]['fecha'] = date_change_format($value['fecha']);
			// $data[$key]['itemActual'] = ($value['itemActual'] == '1' ? true : false);
		}

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Tarifario',
			'data' => $data,
			'headers' => [
				'ITEM (*)',
				'PROVEEDOR (*)',
				'COSTO (*)',
				'FECHA (*)',
				// 'ESTE ITEM ES EL ACTUAL (*)',
			],
			'columns' => [
				['data' => 'item', 'type' => 'myDropdown', 'placeholder' => 'item', 'width' => 600, 'source' => $itemNombre],
				['data' => 'proveedor', 'type' => 'myDropdown', 'placeholder' => 'proveedor', 'width' => 200, 'source' => $proveedores],
				['data' => 'costo', 'type' => 'numeric', 'placeholder' => 'costo', 'width' => 200],
				['data' => 'fecha', 'type' => 'myDate', 'placeholder' => 'fecha', 'width' => 200],
				// ['data' => 'itemActual', 'type' => 'checkbox', 'placeholder' => 'itemActual', 'width' => 200],

			],
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		echo json_encode($result);
	}

	public function guardarCargaMasivaTarifario()
	{

		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);

		$this->db->trans_start();

		$result = $this->result;
		$result['msg']['title'] = "Carga masiva de tarifario";

		$post = json_decode($this->input->post('data'), true);

		$itemProveedores = [];
		$itemNombre = [];
		$conteoFlag = [];
		$conteoProv = [];

		$proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]])->result_array();
		$item['item'] = $this->model->obtenerItems();

		foreach ($proveedores as $key => $row) {
			$itemProveedores[$row['razonSocial']] = $row['idProveedor'];
		}

		foreach ($item['item'] as $key => $row) {
			$itemNombre[$row['label']] = $row['value'];
		}

		//Eliminar la fila en blanco
		array_pop($post['HT'][0]);

		foreach ($post['HT'][0] as $tablaHT) {

			if (empty($tablaHT['item']) || empty($tablaHT['proveedor']) || empty($tablaHT['costo']) || empty($tablaHT['fecha'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			$idProveedor = !empty($itemProveedores[$tablaHT['proveedor']]) ? $itemProveedores[$tablaHT['proveedor']] : NULL;
			$idItem = !empty($itemNombre[$tablaHT['item']]) ? $itemNombre[$tablaHT['item']] : NULL;

			if (empty($idProveedor || $idItem)) {
				goto respuesta;
			}

			// Comprobar que el itemTarifario no se encuentre registrado.
			$validarExistenciaTarifario = $this->model->obtenerInformacionItemTarifario(
				[
					'idItem' => $idItem,
					'idProveedor' => $idProveedor
				]
			)['query']->result_array();

			if (!empty($validarExistenciaTarifario)) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				// $result['msg']['content'] = getMensajeGestion('registroRepetido');
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'El item <b>' . $tablaHT['item'] . '</b> ya se encuentra registrado para el proveedor <b>' . $tablaHT['proveedor'] . '</b>']);
				goto respuesta;
			}

			$dataTarifario['insert'][] = [
				'idItem' => $idItem,
				'idProveedor' => $idProveedor,
				'costo' => $tablaHT['costo'],
				'flag_actual' => $tablaHT['itemActual'],
				'fechaVigencia' => $tablaHT['fecha']

			];

			// Validar que el flag y/o proveedor no se indique varias veces sobre el mismo item.
			if (!isset($conteoFlag[$idItem])) $conteoFlag[$idItem] = 0;
			if (!isset($conteoProv[$idItem][$idProveedor])) $conteoProv[$idItem][$idProveedor] = 0;

			if ($tablaHT['itemActual']) {
				$conteoFlag[$idItem]++;
				// Aprovechando la condicion se busca el "Item Actual" guardado en la BDs para quitarle el activo. pt.1
				$itemActual = $this->model->obtenerInformacionItemTarifario(
					[
						'idItem' => $idItem,
						'chMostrar' => '1'
					]
				)['query']->result_array();
			};
			$conteoProv[$idItem][$idProveedor]++;

			if ($conteoFlag[$idItem] > 1) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'La opción de <b>ACTUAL</b> debe ser indicado solo 1 vez como maximo por <b>ITEM</b>']);
				goto respuesta;
			}

			if ($conteoProv[$idItem][$idProveedor] > 1) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Se ha encontrado duplicidad de <b>ITEM - PROVEEDOR</b> entre los datos indicados']);
				goto respuesta;
			}
		}

		// Aprovechando la condicion se busca el "Item Actual" guardado en la BDs para quitarle el activo. pt.2
		if (isset($itemActual)) {
			foreach ($itemActual as $datos) {
				$this->db->update('compras.itemTarifario', ['flag_actual' => '0'], ['idItemTarifario' => $datos['idItemTarifario']]);
			}
		}

		// NECESITAMOS EL ID PARA EL HISTORICO, POR ESO SE EXCLUYE EL INSERTADO MASIVO
		// $insertarTarifario = $this->model->insertarMasivo('compras.itemTarifario', $dataTarifario['insert']);
		foreach ($dataTarifario['insert'] as $insert) {
			$this->db->insert('compras.itemTarifario', $insert);
			$idItemTarifario = $this->db->insert_id();

			$dataTarifarioHistorico['insert'][] = [
				'idItemTarifario' => $idItemTarifario,
				'fecIni' => getFechaActual(),
				'fecFin' => $insert['fechaVigencia'],
				'costo' => $insert['costo']
			];
		}

		$insertarTarifarioHistorico = $this->model->insertarMasivo('compras.itemTarifarioHistorico', $dataTarifarioHistorico['insert']);

		if (!$insertarTarifarioHistorico) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_commit();
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarCargaMasivaTarifario()
	{

		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);

		$this->db->trans_start();

		$result = $this->result;
		$result['msg']['title'] = "Actualización masiva de tarifario";

		$post = json_decode($this->input->post('data'), true);

		$itemProveedores = [];
		$itemNombre = [];
		$conteoFlag = [];
		$conteoProv = [];

		$proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]])->result_array();
		$item['item'] = $this->model->obtenerItems();

		foreach ($proveedores as $key => $row) {
			$itemProveedores[$row['razonSocial']] = $row['idProveedor'];
		}

		foreach ($item['item'] as $key => $row) {
			$itemNombre[$row['label']] = $row['value'];
		}

		//Eliminar la fila en blanco
		array_pop($post['HT'][0]);

		$updateData = []; // Datos que se van a actualizar en compras.itemTarifario
		$insertTarifarioHistorico = [];
		foreach ($post['HT'][0] as $tablaHT) {

			if (empty($tablaHT['item']) || empty($tablaHT['proveedor']) || empty($tablaHT['costo']) || empty($tablaHT['fecha'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			$idProveedor = !empty($itemProveedores[$tablaHT['proveedor']]) ? $itemProveedores[$tablaHT['proveedor']] : NULL;
			$idItem = !empty($itemNombre[$tablaHT['item']]) ? $itemNombre[$tablaHT['item']] : NULL;

			if (empty($idProveedor || $idItem)) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Error con Item o Proveedor']);
				goto respuesta;
			}

			$hoy = new DateTime();
			$fecha = new DateTime(date_change_format_bd($tablaHT['fecha']));

			if ($fecha >= $hoy) {
				$dataTarifarioItem = $this->model->obtenerInformacionItemTarifario(
					[
						'idItem' => $idItem,
						'idProveedor' => $idProveedor
					]
				)['query']->row_array();

				$updateData[] = [
					'idItemTarifario' => $dataTarifarioItem['idItemTarifario'],
					'idItem' => $idItem,
					'idProveedor' => $idProveedor,
					'costo' => $tablaHT['costo'],
					'fechaVigencia' => $tablaHT['fecha']
				];

				$insertTarifarioHistorico[] = [
					'idItemTarifario' => $dataTarifarioItem['idItemTarifario'],
					'fecIni' => getFechaActual(),
					'fecFin' => $tablaHT['fecha'],
					'costo' => $tablaHT['costo'],
				];
			}

			// Validar que el flag y/o proveedor no se indique varias veces sobre el mismo item.
			/**
			if (!isset($conteoFlag[$idItem])) $conteoFlag[$idItem] = 0;
			if (!isset($conteoProv[$idItem][$idProveedor])) $conteoProv[$idItem][$idProveedor] = 0;

			if ($tablaHT['itemActual']) {
				$conteoFlag[$idItem]++;
				// Aprovechando la condicion se busca el "Item Actual" guardado en la BDs para quitarle el activo. pt.1
				$itemActual = $this->model->obtenerInformacionItemTarifario(
					[
						'idItem' => $idItem,
						'chMostrar' => '1'
					]
				)['query']->result_array();
			};
			$conteoProv[$idItem][$idProveedor]++;

			if ($conteoFlag[$idItem] > 1) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'La opción de <b>ACTUAL</b> debe ser indicado solo 1 vez como maximo por <b>ITEM</b>']);
				goto respuesta;
			}

			if ($conteoProv[$idItem][$idProveedor] > 1) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Se ha encontrado duplicidad de <b>ITEM - PROVEEDOR</b> entre los datos indicados']);
				goto respuesta;
			}
			 */
		}

		// Aprovechando la condicion se busca el "Item Actual" guardado en la BDs para quitarle el activo. pt.2
		/**
		if (isset($itemActual)) {
			foreach ($itemActual as $datos) {
				$this->db->update('compras.itemTarifario', ['flag_actual' => '0'], ['idItemTarifario' => $datos['idItemTarifario']]);
			}
		}
		 */

		if (empty($updateData)) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'No se encontraron registros para actualizar.']);
			goto respuesta;
		}
		$update = $this->model->actualizarMasivo('compras.itemTarifario', $updateData, 'idItemTarifario');
		$insertarTarifarioHistorico = $this->model->insertarMasivo('compras.itemTarifarioHistorico', $insertTarifarioHistorico);

		if (!$insertarTarifarioHistorico || !$update) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_commit();
		}

		respuesta:
		echo json_encode($result);
	}

	//proveedor no repetido
	public function proveedorNoRepetido()
	{
		$result = $this->result;
		$post_1 = json_decode($this->input->post('data'), true);

		$dataParaVista1 = [];
		$dataParaVista1 = $this->model->obtenerProveedorNoRepetido($post_1)['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Tarifario/Item/reporte", ['NoRproveedor' => $dataParaVista1], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentItem']['datatable'] = 'tb-item';
		$result['data']['views']['idContentItem']['html'] = $html;
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

	public function formularioRegistroItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

		$items =  $this->model->obtenerItems();
		foreach ($items as $key => $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}

		$tarifario = $this->db->select('*, ISNULL(DATEDIFF(DAY,GETDATE(),fechaVigencia),999) as diasTranscurridos')->where(['estado' => 1])->get('compras.itemTarifario')->result_array();
		foreach ($tarifario as $key => $value) {
			$dataTarifario[$value['idItem']][$value['idProveedor']] = $value;
		}
		$data['items'][0] = array();
		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Tarifario de Item';
		$result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioRegistro", $dataParaVista, true);
		$result['data']['items'] = $data['items'];
		$result['data']['tarifario'] = $dataTarifario;

		echo json_encode($result);
	}

	public function formularioActualizacionItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$tarifario = $this->db->where(['idItemTarifario' => $post['idItemTarifario']])->get('compras.itemTarifario')->row_array();
		$idItem = $tarifario['idItem'];

		// $listTarifas = $this->db->select('itemTarifario.*, proveedor.razonSocial')->where(['idItem' => $idItem])->join('compras.proveedor', 'proveedor.idProveedor = itemTarifario.idProveedor')->get('compras.itemTarifario')->result_array();

		$dataParaVista = [];
		$dataParaVista['proveedor'] = $this->db
			->select('itemTarifario.*, proveedor.razonSocial')
			->join('compras.proveedor', 'proveedor.idProveedor = itemTarifario.idProveedor')
			->where(['idItem' => $idItem])
			->get('compras.itemTarifario')->result_array();

		$items =  $this->model->obtenerItems();
		foreach ($items as $key => $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}
		$data['items'][0] = array();
		$result['data']['existe'] = 0;

		$dataParaVista['informacionItem'] = $this->model->obtenerInformacionItemTarifario($post)['query']->row_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Tarifario de Item';
		$result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioActualizacion", $dataParaVista, true);
		$result['data']['items'] = $data['items'];

		echo json_encode($result);
	}

	public function formularioHistorialItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$tarifario = $this->db->where(['idItemTarifario' => $post['idItemTarifario']])->get('compras.itemTarifario')->row_array();
		$idItem = $tarifario['idItem'];

		$dataParaVista['datos'] = $this->model->obtenerInformacionTAHistorico(['idItem' => $idItem])['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Historial Tarifario de Item';
		$result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioHistorial", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$existeItemActual = 0;

		$data['insert'] = [
			'idItem' => $post['idItem'],
			'idProveedor' => $post['proveedor'],
			'costo' => $post['costo'],
			'flag_actual' => empty($post['actual']) ? 0 : 1,
			'fechaVigencia' => !empty($post['fechaVigencia']) ? $post['fechaVigencia'] : NULL
		];
		$fechaFin = $data['insert']['fechaVigencia'];

		if (!empty($post['actual'])) {
			$validacionActual = $this->model->validarItemTarifarioActual($data['insert']);
			if (!empty($validacionActual['query']->row_array())) {
				$data['insert']['flag_actual'] = 0;
				$existeItemActual = 1;
			}
		}

		$validacionExistencia = $this->model->validarExistenciaItemTarifario($data['insert'])['query']->row_array();

		if (!empty($validacionExistencia)) {
			$idItemTarifario = $validacionExistencia['idItemTarifario'];
			$dataHistorico = $this->db->where(['idItemTarifario' => $idItemTarifario])->order_by('idItemTarifarioHistorico desc')->get('compras.itemTarifarioHistorico')->row_array();

			$this->db->update(
				'compras.itemTarifario',
				[
					'costo' => $post['costo'],
					'flag_actual' => $data['insert']['flag_actual'],
					'fechaVigencia' => $data['insert']['fechaVigencia']
				],
				['idItemTarifario' => $idItemTarifario]
			);

			$nFF = empty($dataHistorico['fecFin']) ? getFechaActual(-1) : $dataHistorico['fecFin'];
			$this->db->update('compras.itemTarifarioHistorico', ['fecFin' => $nFF], ['idItemTarifarioHistorico' => $dataHistorico['idItemTarifarioHistorico']]);
			$insert['estado'] = true;
		} else {
			$data['tabla'] = 'compras.itemTarifario';
			$insert = $this->model->insertarItemTarifario($data);
			$idItemTarifario =  $insert['id'];
		}

		$data = [];
		$data['insert'] = [
			'idItemTarifario' => $idItemTarifario,
			'fecIni' => getFechaActual(),
			'fecFin' => $fechaFin,
			'costo' => $post['costo'],
		];

		$data['tabla'] = 'compras.itemTarifarioHistorico';

		$subInsert = $this->model->insertarItemTarifario($data);

		$data = [];

		if (!$insert['estado'] or !$subInsert['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		if ($existeItemActual == true && $result['result'] == 1) {
			$result['result'] = 2;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
			$result['data']['idItemTarifario'] = $idItemTarifario;
			$result['data']['idItem'] = $post['idItem'];
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$existeItemActual = 0;

		$data['update'] = [
			'idItemTarifario' => $post['idItemTarifario'],
			'idItem' => $post['idItem'],
			'idProveedor' => $post['proveedor'],
			'costo' => $post['costo'],
			'flag_actual' => empty($post['actual']) ? 0 : 1,
			'fechaVigencia' => !empty($post['fechaVigencia']) ? $post['fechaVigencia'] : NULL

		];
		$fechaVigencia = $data['update']['fechaVigencia'];

		if (!empty($post['actual'])) {
			$validacionActual = $this->model->validarItemTarifarioActual($data['update']);
			if (!empty($validacionActual['query']->row_array())) {
				$data['update']['flag_actual'] = 0;
				$existeItemActual = 1;
			}
		}

		$validacionExistencia = $this->model->validarExistenciaItemTarifario($data['update']);
		unset($data['update']['idItemTarifario']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.itemTarifario';
		$data['where'] = [
			'idItemTarifario' => $post['idItemTarifario']
		];

		$update = $this->model->actualizarItemTarifario($data);
		$data = [];
		$actualizacionHistoricos = true;

		if ($post['costoAnterior'] != $post['costo']) {
			$data['update'] = [
				'fecFin' => getFechaActual(-1),
			];

			$data['tabla'] = 'compras.itemTarifarioHistorico';
			$data['where'] = [
				'idItemTarifario' => $post['idItemTarifario'],
				'fecFin' => $fechaVigencia
			];

			$subUpdate = $this->model->actualizarItemTarifario($data);
			$data = [];

			$data['insert'] = [
				'idItemTarifario' => $post['idItemTarifario'],
				'fecIni' => getFechaActual(),
				'fecFin' => $fechaVigencia,
				'costo' => $post['costo'],
			];

			$data['tabla'] = 'compras.itemTarifarioHistorico';

			$subInsert = $this->model->insertarItemTarifario($data);
			$data = [];

			if (!$subUpdate['estado'] && !$subInsert['estado']) {
				$actualizacionHistoricos = false;
			}
		}

		if (!$update['estado'] or !$actualizacionHistoricos) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		if ($existeItemActual == true && $result['result'] == 1) {
			$result['result'] = 2;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
			$result['data']['idItemTarifario'] = $post['idItemTarifario'];
			$result['data']['idItem'] = $post['idItem'];
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarActualItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['update'] = [
			'flag_actual' => 0
		];

		$data['tabla'] = 'compras.itemTarifario';
		$data['where'] = [
			'idItem' => $post['idItem']
		];

		$insert = $this->model->actualizarItemTarifario($data);
		$data = [];

		$data['update'] = [
			'flag_actual' => 1
		];

		$data['tabla'] = 'compras.itemTarifario';
		$data['where'] = [
			'idItemTarifario' => $post['idItemTarifario']
		];

		$insert = $this->model->actualizarItemTarifario($data);
		$data = [];

		if (!$insert['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('exitosoPersonalizado', ['message' => 'Se actualizó el item actual']);
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarEstadoItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$data['tabla'] = 'compras.itemTarifario';
		$data['where'] = [
			'idItemTarifario' => $post['idItemTarifario']
		];

		$update = $this->model->actualizarItemTarifario($data);
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

	public function formularioFotosItemTarifario()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['idItemTarifario'] = $post['idItemTarifario'];

		$dataParaVista = [];
		$dataParaVista['itemFotos'] = $this->model->obtenerItemsFotos($data);

		$result['result'] = 1;
		$result['msg']['title'] = 'Fotos de Items';
		$result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioFotos", $dataParaVista, true);

		echo json_encode($result);
	}
}
