<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorDocumento extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ProveedorDocumento', 'model');
		$this->load->model('M_Cotizacion', 'mCotizacion');
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
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/Finanzas/proveedorDocumento'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Reporte de Documentos';
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

		$datos1 = $this->model->obtenerRegistrosParaFinanzas($post)->result_array();
		$datos2 = $this->model->obtenerRegistrosParaFinanzasLibre($post)->result_array();

		$datos = array_merge($datos1, $datos2);
		$datos = ordenarArrayPorColumna($datos, 'ordenCompra', SORT_DESC);

		foreach ($datos as $k => $v) {
			if (!isset($dataParaVista['datos'][$v['ordenCompra']])) {
				$dataParaVista['datos'][$v['ordenCompra']] = $v;
				$dataParaVista['datos'][$v['ordenCompra']]['monto'] = 0;

				$dataParaVista['datos'][$v['ordenCompra']]['adjuntosCargados'] = false;
				$buscarCargados = $this->db->get_where('sustento.comprobante', ['idOrdenCompra' => $v['idOrdenCompra'], 'flagOcLibre' => $v['flagOcLibre'], 'estado' => 1])->result_array();
				if (!empty($buscarCargados)) $dataParaVista['datos'][$v['ordenCompra']]['adjuntosCargados'] = true;
			}
			$dataParaVista['datos'][$v['ordenCompra']]['monto'] += $v['subtotal'];
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
			],
		];

		echo json_encode($result);
	}
	public function descargarExcel()
	{
		$post = json_decode($this->input->post('data'), true);

		$datos1 = $this->model->obtenerRegistrosParaFinanzas($post)->result_array();
		$datos2 = $this->model->obtenerRegistrosParaFinanzasLibre($post)->result_array();

		$datos = array_merge($datos1, $datos2);
		$datos = ordenarArrayPorColumna($datos, 'ordenCompra', SORT_DESC);

		$data = [];
		foreach ($datos as $k => $v) {
			if (!isset($data[$v['ordenCompra']])) {
				$data[$v['ordenCompra']] = $v;
				$data[$v['ordenCompra']]['monto'] = 0;

				$data[$v['ordenCompra']]['adjuntosCargados'] = false;
				$buscarCargados = $this->db->get_where('compras.sustentoAdjunto', ['idProveedor' => $v['idProveedor'], 'idCotizacion' => $v['idCotizacion'], 'estado' => 1])->result_array();
				if (!empty($buscarCargados)) $data[$v['ordenCompra']]['adjuntosCargados'] = true;
			}
			$data[$v['ordenCompra']]['monto'] += $v['subtotal'];
		}
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
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
				'font' => array(
					'color' => array('rgb' => 'ffffff'),
					'size' => 11,
					'name' => 'Calibri'
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
			'font' => [
				'size' => 13,
				'name' => 'Calibri'
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
			'font' => [
				'size' => 11,
				'name' => 'Calibri'
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
			'font' => [
				'name' => 'Calibri'
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
			'font' => [
				'name' => 'Calibri'
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
			'font' => [
				'name' => 'Calibri'
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

		$nIni = 2;
		foreach ($data as $k => $v) {
			if ($v['numeroDocumento'] == null) {
				$numFactura = '-';
			} else {
				$numFactura = $v['numeroDocumento'];
			}
			if (empty($row['adjuntosCargados'])) {
				$estado = 'Enviado al proveedor';
			} else {
				$estado = 'Documentos enviados';
			}
			if ($v['fechaEmision'] == null) {
				$fecha_factura = '-';
			} else {
				$fecha_factura = $v['fechaEmision'];
			}
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $nIni, date_change_format($v['fechaRegOC']))
				->setCellValue('C' . $nIni, NOMBRE_MES[explode('-', $v['fechaRegOC'])[1]])
				->setCellValue('D' . $nIni, $v['oper'])
				->setCellValue('E' . $nIni, $v['ordenCompra'])
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
				->setCellValue('P' . $nIni, $v['numeroGR'])
				->setCellValue('Q' . $nIni, $estado)
				->setCellValue('R' . $nIni, $fecha_factura)
				->setCellValue('S' . $nIni, $numFactura);

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
	public function formularioSustentosCargados()
	{
		$result = $this->result;
		$post = $this->input->post();

		$dataParaVista = [
			'idOrdenCompra' => $post['idOrdenCompra'],
			'flagOcLibre' => $post['flagOcLibre'],
			'monto' => $post['monto'],
		];
		$dataParaVista['sustentosCargados'] = $this->db->get_where(
			'sustento.comprobante',
			[
				'idOrdenCompra' => $post['idOrdenCompra'],
				'flagOcLibre' => $post['flagOcLibre'],
				'flagRevisado' => 1,
				'estado' => 1
			]
		)->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Sustentos Cargados';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorDocumento/SustentosCargados", $dataParaVista, true);
		echo json_encode($result);
	}
	public function actualizarEstadoSustentoFinanza()
	{
		$result = $this->result;
		$post = $this->input->post();

		$dataParaVista = [];
		if (empty($post['observacionRechazoFinanza']) && empty($post['flagAprobadoFinanza'])) {
			$result['result'] = 2;
			$result['msg']['title'] = 'Ingresar Observación';
			$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorDocumento/formularioObservacionDeRechazo", $dataParaVista, true);
			goto respuesta;
		}

		$this->db->update('sustento.comprobante', ['observacionRechazoFinanza' => $post['observacionRechazoFinanza'], 'flagAprobadoFinanza' => $post['flagAprobadoFinanza']], ['idSustentoAdjunto' => $post['idSustentoAdjunto']]);
		
		if ($post['flagOcLibre'] == 1) {
			$validarAprobados = $this->db->get_where('sustento.comprobante', ['idOrdenCompra' => $post['ordenCompra'],'idProveedor' => $post['proveedor'],'flagoclibre' => $post['flagOcLibre'],'flagAprobadoFinanza' => 0])->result_array();
		} else {
			$validarAprobados = $this->db->get_where('sustento.comprobante', ['idOrdenCompra' => $post['ordenCompra'], 'idCotizacion' => $post['cotizacion'],'idProveedor' => $post['proveedor'],'flagoclibre' => $post['flagOcLibre'],'flagAprobadoFinanza' => 0])->result_array();
		}

		if (count($validarAprobados) < 1) {
			$pro = $this->db->where('idProveedor', $post['proveedor'])->get('compras.proveedor')->row_array();
			if ($post['flagOcLibre'] == 0) {
				$ordenCompra = $this->model->obtenerOCEmail(['idOrdenCompra' => $post['ordenCompra']])->row_array();
				$ordenCompra['monto'] = $post['monto'];
			} else {
				$ordenCompra = $this->model->obtenerOCLibreEmail(['idOrdenCompra' => $post['ordenCompra']])->row_array();
				$ordenCompra['monto'] = $post['monto'];
			}

			if ($this->idUsuario == '1') {
				$idTipoParaCorreo = USER_ADMIN;
				$usuariosCorreo = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toCorreo = [];
				foreach ($usuariosCorreo as $usuario) {
					$toCorreo[] = $usuario['email'];
				}
			} else {
				$toCorreo = [$pro['correoContacto']];
			}

			$cfg['to'] = ['bill.salazar@visualimpact.com.pe', 'eder.alata@visualimpact.com.pe', 'luis.durand@visualimpact.com.pe'];
			$cfg['asunto'] = 'CONFIRMACION DE RECEPCION DE FACTURAS: ' . $pro['razonSocial'];
			$cfg['contenido'] = $this->load->view("email/conformidadProveedores", ['data' => $ordenCompra], true);
			
			$this->sendEmail($cfg);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Completo';
		$result['data']['html'] = getMensajeGestion('registroExitoso');

		respuesta:
		echo json_encode($result);
	}
}
