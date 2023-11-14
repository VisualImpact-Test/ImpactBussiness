<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorDocumento extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ProveedorDocumento', 'model');
		$this->load->model('M_Cotizacion', 'mCotizacion');
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
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/Finanzas/proveedorDocumento'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Proveedor Documentos';
		$config['data']['message'] = 'Lista';
		$config['data']['proveedor'] = $this->model->getProveedoresQueTienenOC()->result_array();
		$config['data']['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$config['view'] = 'modulos/Finanzas/ProveedorDocumento/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$datos = $this->model->obtenerRegistrosParaFinanzas($post)->result_array();
		foreach ($datos as $k => $v) {
			if (!isset($dataParaVista['datos'][$v['idOrdenCompra']])) {
				$dataParaVista['datos'][$v['idOrdenCompra']] = $v;
				$dataParaVista['datos'][$v['idOrdenCompra']]['monto'] = 0;

				$dataParaVista['datos'][$v['idOrdenCompra']]['adjuntosCargados'] = false;
				$buscarCargados = $this->db->get_where('compras.sustentoAdjunto', ['idProveedor' => $v['idProveedor'], 'idCotizacion' => $v['idCotizacion'], 'estado' => 1])->result_array();
				if (!empty($buscarCargados)) $dataParaVista['datos'][$v['idOrdenCompra']]['adjuntosCargados'] = true;
			}
			$dataParaVista['datos'][$v['idOrdenCompra']]['monto'] += $v['subtotal'];
		}
		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Finanzas/ProveedorDocumento/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idProveedorDocumento']['datatable'] = 'tb-proveedorDocumento';
		$result['data']['views']['idProveedorDocumento']['html'] = $html;
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
	public function descargarExcel()
	{
		$post = json_decode($this->input->post('data'), true);

		$datos = $this->model->obtenerRegistrosParaFinanzas($post)->result_array();
		$data = [];
		foreach ($datos as $k => $v) {
			if (!isset($data[$v['idOrdenCompra']])) {
				$data[$v['idOrdenCompra']] = $v;
				$data[$v['idOrdenCompra']]['monto'] = 0;

				$data[$v['idOrdenCompra']]['adjuntosCargados'] = false;
				$buscarCargados = $this->db->get_where('compras.sustentoAdjunto', ['idProveedor' => $v['idProveedor'], 'idCotizacion' => $v['idCotizacion'], 'estado' => 1])->result_array();
				if (!empty($buscarCargados)) $data[$v['idOrdenCompra']]['adjuntosCargados'] = true;
			}
			$data[$v['idOrdenCompra']]['monto'] += $v['subtotal'];
		}
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		// ini_set('memory_limit', '1024M');
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
				'size' => 13,
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

		$objPHPExcel->getActiveSheet()->getStyle('B1:S1')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B1', 'FECHA DE GENERACIÓN OC VISUAL')
			->setCellValue('C1', 'MES OC VISUAL')
			->setCellValue('D1', 'OPER')
			->setCellValue('E1', 'OC VISUAL')
			->setCellValue('F1', 'RUC')
			->setCellValue('G1', 'PROVEEDOR')
			->setCellValue('H1', 'CUENTA')
			->setCellValue('I1', 'CENTRO COSTO')
			->setCellValue('J1', 'DESCRIPCION TRACKING')
			->setCellValue('K1', 'DESCRIPCION COMPRAS')
			->setCellValue('L1', 'IMPORTE SIN IGV')
			->setCellValue('M1', 'IMPORTE INC. IGV')
			->setCellValue('N1', 'MONEDA')
			->setCellValue('O1', 'PO CLIENTE')
			->setCellValue('P1', 'GR')
			->setCellValue('Q1', 'ESTADO DE ATENCION')
			->setCellValue('R1', 'FECHA DE FACTURA')
			->setCellValue('S1', 'NUMERO DE FACTURA');

		$objPHPExcel->getActiveSheet()->getStyle("B1:S1")->applyFromArray($estilo_titulo)->getFont()->setBold(true);
		// $objPHPExcel->getActiveSheet()->getStyle("A8:A11")->getFont()->setBold(true);
		// $objPHPExcel->getActiveSheet()->getStyle("A8:A11")->applyFromArray($estilo_subtitulo);

		// $objPHPExcel->setActiveSheetIndex(0)
		// 	->setCellValue('A13', 'DESCRIPCION')
		// 	->setCellValue('B13', 'CANTIDAD')
		// 	->setCellValue('C13', 'PRECIO UNITARIO')
		// 	->setCellValue('D13', 'TOTAL')
		// 	->setCellValue('E13', 'TIEMPO');
		// $objPHPExcel->getActiveSheet()->getStyle("A13:E13")->applyFromArray($estilo_cabecera);
		$nIni = 2;
		foreach ($data as $k => $v) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $nIni, date_change_format($v['fechaRegOC']))
				->setCellValue('C' . $nIni, NOMBRE_MES[explode('-', $v['fechaRegOC'])[1]])
				->setCellValue('D' . $nIni, $v['oper'])
				->setCellValue('E' . $nIni, str_pad($v['idOrdenCompra'], 8, "0", STR_PAD_LEFT))
				->setCellValue('F' . $nIni, $v['rucProveedor'])
				->setCellValue('G' . $nIni, $v['razonSocial'])
				->setCellValue('H' . $nIni, $v['cuenta'])
				->setCellValue('I' . $nIni, $v['centroCosto'])
				->setCellValue('J' . $nIni, $v['desTracking'])
				->setCellValue('K' . $nIni, $v['cotizacion'])
				->setCellValue('L' . $nIni, $v['monto'])
				->setCellValue('M' . $nIni, $v['monto'] * (1 + ($v['igv'] / 100)))
				->setCellValue('N' . $nIni, $v['nombreMoneda'])
				->setCellValue('O' . $nIni, $v['poCliente'])
				->setCellValue('P' . $nIni, 'PENDIENTE')
				->setCellValue('Q' . $nIni, '')
				->setCellValue('R' . $nIni, '')
				->setCellValue('S' . $nIni, '');

			$objPHPExcel
				->getActiveSheet()
				->getStyle('L' . $nIni)
				->getNumberFormat()
				->setFormatCode('"S/"#,##0.00_-');
			$objPHPExcel
				->getActiveSheet()
				->getStyle('M' . $nIni)
				->getNumberFormat()
				->setFormatCode('"S/"#,##0.00_-');
			$nIni++;
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
}
