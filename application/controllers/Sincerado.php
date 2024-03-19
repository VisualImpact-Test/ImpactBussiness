<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sincerado extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Sincerado', 'model');
		$this->load->model('M_Cotizacion', 'mCotizacion');
		$this->load->model('M_OrdenServicio', 'mOrdenServicio');
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
			'assets/custom/js/sincerado',
			'assets/custom/js/ordenServicio'
		);
		$config['data']['icon'] = 'icon project diagram';
		$config['data']['title'] = 'Sincerado';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Sincerado/index';
		$this->view($config);
	}
	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$dataParaVista = [];
		$data = $this->model->getSincerado()->result_array();
		$html = getMensajeGestion('noRegistros');
		if (!empty($data)) {
			foreach ($data as $value) {
				$dataParaVista['sincerado'][$value['idSincerado']] = $value;
			}
		}

		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Sincerado/reporte", $dataParaVista, true);
		}
		$result['result'] = 1;
		$result['data']['views']['idContentSincerado']['datatable'] = 'tb-sincerado';
		$result['data']['views']['idContentSincerado']['html'] = $html;
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
	public function formularioListaParaSincerar()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$datos = $this->model->obtenerInformacionDelPresupuestoValido()->result_array(); // db->get_where('compras.presupuestoValido', ['estado' => 1])->result_array();

		foreach ($datos as $k => $v) {
			$dataParaVista['datos'][$k] = $v;
			if ($v['chkUtilizarCliente']) {
				$dataParaVista['datos'][$k]['cuenta_cliente'] = $this->db->get_where('compras.cliente', ['idCliente' => $v['idCliente']])->row_array()['nombre'];
			} else {
				$cuenta = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $v['idCuenta']])->row_array()['nombre'];
				$centroCosto = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $v['idCentroCosto']])->row_array()['subcanal'];
				$dataParaVista['datos'][$k]['cuenta_cliente'] = $cuenta . ' / ' . $centroCosto;
			}
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Presupuestos Validados';
		$html = empty($datos) ? getMensajeGestion('noRegistros') : $this->load->view("modulos/Sincerado/formularioListaParaSincerado", $dataParaVista, true);
		$result['data']['html'] = $html;

		echo json_encode($result);
	}
	public function formularioCargarGr()
	{
		$result = $this->result;
		$post = $this->input->post();
		$dataParaVista = [];
		$dataParaVista['conceptoTracking'] = $this->db->get_where('compras.conceptoTracking', ['estado' => 1])->result_array();
		$dataParaVista['sincerado'] = $this->db->get_where('compras.sincerado', ['idSincerado' => $post['idSincerado']])->row_array();
		$idMoneda = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $dataParaVista['sincerado']['idOrdenServicio']])->row_array()['idMoneda'];
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda', ['idMoneda' => $idMoneda])->row_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Cargar GR';
		$html = $this->load->view("modulos/Sincerado/formularioCargarGr", $dataParaVista, true);
		$result['data']['html'] = $html;

		echo json_encode($result);
	}


	public function formularioAprobar()
	{
		$result = $this->result;
		$post = $this->input->post();

		$dataParaVista = [];
		$dataParaVista['sincerado'] = $this->db->get_where('compras.sincerado', ['idSincerado' => $post['idSincerado']])->row_array();

		$html = $this->load->view("modulos/Sincerado/formularioAprobar", $dataParaVista, true);
		$result['result'] = 1;
		$result['msg']['title'] = 'Aprobar Sincerado';
		$result['data']['html'] = $html;

		echo json_encode($result);
	}

	public function AprobarSincerado()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idSincerado = $post['idSincerado'];

		$updateSincerado = [
			'usuarioAprobar' => $this->idUsuario,
			'flagPendienteAprobar' => '0',
			'fechaAprobar' => getActualDateTime()
		];
		$this->db->update('compras.sincerado', $updateSincerado, ['idSincerado' => $idSincerado]);
		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function guardarGrSincerado()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['usuario'] = checkAndConvertToArray($post['usuario']);
		$post['conceptoTracking'] = checkAndConvertToArray($post['conceptoTracking']);
		$post['descripcion'] = checkAndConvertToArray($post['descripcion']);
		$post['fecha'] = checkAndConvertToArray($post['fecha']);
		$post['porcentaje'] = checkAndConvertToArray($post['porcentaje']);
		$post['monto'] = checkAndConvertToArray($post['monto']);
		$post['porcentajeSincerado'] = checkAndConvertToArray($post['porcentajeSincerado']);
		$post['presupuestoSincerado'] = checkAndConvertToArray($post['presupuestoSincerado']);
		$post['diferenciaSincerado'] = checkAndConvertToArray($post['diferenciaSincerado']);

		$insertData = [];
		foreach ($post['descripcion'] as $k => $v) {
			$insertData[] = [
				'idSincerado' => $post['idSincerado'],
				'usuario' => $post['usuario'][$k],
				'descripcion' => $v,
				'fecha' => $post['fecha'][$k],
				'fechaReg' => getActualDateTime(),
				'idUsuario' => $this->idUsuario,
				'porcentaje' => $post['porcentaje'][$k],
				'monto' => $post['monto'][$k],
				'porcentajeSincerado' => $post['porcentajeSincerado'][$k],
				'presupuestoSincerado' => $post['presupuestoSincerado'][$k],
				'diferenciaSincerado' => $post['diferenciaSincerado'][$k],
				'conceptoTracking' => $post['conceptoTracking'][$k],
			];
		}
		if (empty($insertData)) {
			$result = mensajeList('NoData', $result);
			goto respuesta;
		}

		$success = $this->db->insert_batch('compras.sinceradoGr', $insertData);
		if (!$success) {
			$result = mensajeList('registroErroneo', $result);
			goto respuesta;
		}

		$result = mensajeList('registroExitoso', $result);
		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}

	public function descargarExcelGr()
	{
		$post = $this->input->post();
		$data = $this->db->get_where('compras.sinceradoGr', ['idSincerado' => $post['idSincerado'], 'estado' => 1])->result_array();
		$sincerado = $this->db->get_where('compras.sincerado', ['idSincerado' => $post['idSincerado'], 'estado' => 1])->row_array();
		if (empty($data)) {
			echo json_encode(mensajeList('NoData'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
			exit();
		}

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
				'type' => PHPExcel_Style_Fill::FILL_NONE,
				// 'startcolor' => array('rgb' => 'FFFF00')
			],
			'borders' => [
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			],
			'font'  => [
				'size' => 13,
				'name'  => 'Calibri',
				'bold' => true,
			]
		];
		$estilo_bordado = [
			'borders' => [
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			],
			'font'  => [
				'size' => 13,
				'name'  => 'Calibri',
				// 'bold' => true,
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

		if (count($data) == 1 and $data[0]['porcentaje'] == '100' and $data[0]['porcentajeSincerado'] == '100') {
			$v = $data[0];
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B2', 'CODIGO GR')
				->setCellValue('B3', 'FECHA GR')
				->setCellValue('C2', $v['descripcion'])
				->setCellValue('C3', date_change_format($v['fecha']));
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getStyle("B2:B3")->applyFromArray($estilo_titulo)->getFont();
			$objPHPExcel->getActiveSheet()->getStyle("C2:C3")->applyFromArray($estilo_bordado)->getFont();
		} else {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E1', 'MONTO TOTAL')
				->setCellValue('F1', $sincerado['totalSincerado'])
				->setCellValue('A3', 'DESCRIPCIÓN')
				->setCellValue('B3', 'FECHA')
				->setCellValue('C3', 'PORCENTAJE')
				->setCellValue('D3', 'MONTO')
				->setCellValue('E3', '% SINCERADO')
				->setCellValue('F3', 'PRESUPUESTO SINCERADO')
				->setCellValue('G3', 'DIFERENCIA');
			$rIni = 3;
			foreach ($data as $v) {
				$rIni++;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $rIni, $v['descripcion'])
					->setCellValue('B' . $rIni, date_change_format($v['fecha']))
					->setCellValue('C' . $rIni, $v['porcentaje'])
					->setCellValue('D' . $rIni, $v['monto'])
					->setCellValue('E' . $rIni, $v['porcentajeSincerado'])
					->setCellValue('F' . $rIni, $v['presupuestoSincerado'])
					->setCellValue('G' . $rIni, $v['diferenciaSincerado']);
			}
			$objPHPExcel->getActiveSheet()->getStyle("A3:G3")->applyFromArray($estilo_titulo)->getFont();
			$objPHPExcel->getActiveSheet()->getStyle("E1")->applyFromArray($estilo_titulo)->getFont();
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		}

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	public function formularioFechasSincerado()
	{
		$result = $this->result;
		$post = $this->input->post();
		$dataParaVista = [];
		$dataParaVista['idPresupuestoValido'] = $post['idPresupuestoValido'];
		$dataParaVista['fechaSincerado'] = $this->mCotizacion->obtenerFechaSincerado(['idPresupuestoValido' => $post['idPresupuestoValido']])['query']->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Fechas Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formularioFechaSincerado", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioEditarSincerado()
	{
		$result = $this->result;
		$post = $this->input->post();

		$idSincerado = $post['idSincerado'];

		$dataParaVista = [];
		$dataParaVista['sincerado'] = $this->db->get_where('compras.sincerado', ['idSincerado' => $idSincerado])->row_array();
		$dataParaVista['sincerado_Det'] = changeKeyInArray($this->db->get_where('compras.sincerado_Det', ['idSincerado' => $idSincerado, 'estado' => 1])->result_array(), 'idTipoPresupuestoDetalle');
		$dataParaVista['fechaSincerado'] = $dataParaVista['sincerado']['fecha_seleccionada'];
		$dataParaVista['valorPorcentual'] =
			$this->db->select('osds.valorPorcentual')->from('compras.ordenServicioDetalleSub osds')->join('compras.ordenServicioDetalle osd', 'osd.idOrdenServicioDetalle = osds.idOrdenServicioDetalle')->where('osd.estado', 1)->where('osds.idTipoPresupuestoDetalle', COD_ASIGNACIONFAMILIAR)->where('osd.idOrdenServicio', $dataParaVista['sincerado']['idOrdenServicio'])->get()->row_array()['valorPorcentual'];
		$dataParaVista['idCuenta'] = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $dataParaVista['sincerado']['idOrdenServicio']])->row_array()['idCuenta'];
		$dataParaVista['cargos'] = $this->mCotizacion->getAll_Cargos(['soloCargosOcupados' => true, 'idCuenta' => $dataParaVista['idCuenta']])->result_array();
		$dataParaVista['empleados'] = $this->mOrdenServicio->getAll_RRHHEmpleados(['activo' => true])->result_array();
		$dataParaVista['tipoPresupuestoDetalleMovilidad'] = $this->db->get_where('compras.tipoPresupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalleAlmacen'] = $this->db->get_where('compras.tipoPresupuestoDetalleAlmacen', ['estado' => 1])->result_array();
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];

		$where = [];
		if (!empty($dataParaVista['idCuenta'])) {
			$where['idCuenta'] = $dataParaVista['idCuenta'];
		}
		// Para traer presupuestoDetalleAlmacen y presupuestoDetalleAlmacenRecursos
		$idSinDet_Almacen = $this->db->get_where('compras.sinceradoDetalle', ['idSincerado' => $idSincerado, 'idTipoPresupuesto' => COD_ALMACEN, 'estado' => 1])->row_array()['idSinceradoDetalle'];

		$arTPDA = $this->db->get_where('compras.sinceradoDetalleAlmacen', ['idSinceradoDetalle' => $idSinDet_Almacen])->result_array();
		foreach ($arTPDA as $v) {
			$dataParaVista['dataTPDA'][$v['idTipoPresupuestoDetalleAlmacen']] = $v;
		}

		$arTPDAR = $this->db->get_where('compras.sinceradoDetalleAlmacenRecursos', ['idSinceradoDetalle' => $idSinDet_Almacen])->result_array();
		foreach ($arTPDAR as $v) {
			$dataParaVista['dataTPDARecursos'][$v['idTipoPresupuestoDetalleAlmacen']][] = $v;
		}
		// Fin

		$items = $this->mOrdenServicio->getItemsCnPresupuesto($where)->result_array();
		foreach ($items as $item) {
			if (!isset($dataParaVista['item'][$item['idTipoPresupuestoDetalle']])) $dataParaVista['item'][$item['idTipoPresupuestoDetalle']] = [];
			$dataParaVista['items'][$item['idTipoPresupuestoDetalle']][] = $item;
		}
		$dataParaVista['itemPrecio'] = $this->mOrdenServicio->itemPrecios();

		$sinceradoCargo = $this->mOrdenServicio->getSinceradoCargo($idSincerado)->result_array();

		foreach ($sinceradoCargo as $k => $v) {
			$cargo[$v['idCargo']] = $v;
			$fecha[$v['fecha']] = $v;
			$dataParaVista['sinceradoCargo'][$v['fecha']][$v['idCargo']] = $v;
		}
		foreach ($fecha as $k => $v) {
			$dataParaVista['fechaDelPre'][] = $v;
		}
		foreach ($cargo as $k => $v) {
			$dataParaVista['cargoDelPre'][] = $v;
		}
		$dataParaVista['sinceradoDetalle'] = $this->mOrdenServicio->getSinceradoDetalle($idSincerado)->result_array();

		$sinceradoDetalleSueldoAdicional = [];
		$arrayIdSinceradoDetalle = [];
		foreach ($dataParaVista['sinceradoDetalle'] as $k => $v) {
			$arrayIdSinceradoDetalle[] = $v['idSinceradoDetalle'];
			$dataParaVista['sinceradoDetalleSub'][$v['idSinceradoDetalle']] = $this->mOrdenServicio->getSinceradoDetalleSub($v['idSinceradoDetalle'])->result_array();

			foreach ($dataParaVista['sinceradoDetalleSub'][$v['idSinceradoDetalle']] as $presDetSub) {
				foreach ($this->db->get_where('compras.sinceradoDetalleSubCargo', ['idSinceradoDetalleSub' => $presDetSub['idSinceradoDetalleSub']])->result_array() as $prDetSbCar) {
					$dataParaVista['sinceradoDetalleSubCargo'][$presDetSub['idSinceradoDetalleSub']][$prDetSbCar['idCargo']] = $prDetSbCar;
				}
				$dataParaVista['sinceradoDetalleSubElemento'][$presDetSub['idSinceradoDetalleSub']] = [];
				foreach ($this->db->get_where('compras.sinceradoDetalleSubElemento', ['idSinceradoDetalleSub' => $presDetSub['idSinceradoDetalleSub']])->result_array() as $prDetSbElm) {
					$dataParaVista['sinceradoDetalleSubElemento'][$presDetSub['idSinceradoDetalleSub']][] = $prDetSbElm;
				}
			}

			$sinceradoDetalleSueldo = $this->mOrdenServicio->getSinceradoDetalleSueldo($v['idSinceradoDetalle'])->result_array();
			foreach ($sinceradoDetalleSueldo as $pds) {
				$dataParaVista['sinceradoDetalleSueldo'][$pds['idSinceradoDetalle']][$pds['idTipoPresupuestoDetalle']][$pds['idCargo']] = $pds;
				$dataParaVista['idCargoRef'] = $pds['idCargo'];
			}

			if (!isset($sinceradoDetalleSueldoAdicional)) $sinceradoDetalleSueldoAdicional = [];
			if (!isset($sinceradoDetalleMovilidad)) $sinceradoDetalleMovilidad = [];
			if (!isset($sinceradoDetalleMovilidad_Det)) $sinceradoDetalleMovilidad_Det = [];

			if ($v['idTipoPresupuesto'] == COD_SUELDO) $sinceradoDetalleSueldoAdicional = $this->db->get_where('compras.sinceradoDetalleSueldoAdicional', ['idSinceradoDetalle' => $v['idSinceradoDetalle']])->result_array();
			if ($v['idTipoPresupuesto'] == COD_MOVILIDAD) $sinceradoDetalleMovilidad = $this->db->get_where('compras.sinceradoDetalleMovilidad', ['idSinceradoDetalle' => $v['idSinceradoDetalle']])->result_array();
		}

		$dataParaVista['sinceradoDetalleMovilidad_Det'] = changeKeyInArray($this->db->where_in('idSinceradoDetalle', $arrayIdSinceradoDetalle)->get('compras.sinceradoDetalleMovilidad_Det')->result_array(), 'flagViaje', 'flagAdicional');
		$dataParaVista['sinceradoDetalleSueldo_Det'] = changeKeyInArray($this->db->select('ISNULL(idCargo, 0) idCargo, flagIncentivo, montoOriginal, montoSincerado')->where_in('idSinceradoDetalle', $arrayIdSinceradoDetalle)->get('compras.sinceradoDetalleSueldo_Det')->result_array(), 'idCargo');

		$dataParaVista['sinceradoDetalleMovilidad'] = [];
		if (!empty($sinceradoDetalleMovilidad)) {
			foreach ($sinceradoDetalleMovilidad as $km => $vm) {
				$dataParaVista['sinceradoDetalleMovilidad'][$vm['idTipoPresupuestoDetalleMovilidad']] = $vm;
			}
		}
		$dataParaVista['sinceradoDetalleSueldoAdicional'] = $sinceradoDetalleSueldoAdicional;

		foreach ($this->db->select('tpd.*, it.costo, it.idProveedor')->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')->order_by('tpd.nombre')->get('compras.tipoPresupuestoDetalle tpd')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;

		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formularioEditarSincerado", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['fechaDelPre'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['cargoDelPre'];
		echo json_encode($result);
	}
	public function formularioRegistrarSincerado()
	{
		$result = $this->result;

		$post = json_decode($this->input->post('data'), true);

		$idPresupuestoValido = $post['idPresupuestoValido'];
		$ra = $this->db->get_where('compras.presupuestoValido', ['idPresupuestoValido' => $idPresupuestoValido])->row_array();
		$idPresupuesto = $ra['idPresupuesto'];
		$idPresupuestoHistorico = $ra['idPresupuestoHistorico'];

		$dataParaVista = [];
		$dataParaVista['idPresupuestoValido'] = $idPresupuestoValido;
		$dataParaVista['idPresupuestoHistorico'] = $idPresupuestoHistorico;
		$dataParaVista['fechaSincerado'] = $post['fechaSincerado'];
		$dataParaVista['presupuesto'] = $this->db->get_where('compras.presupuestoHistorico', ['idPresupuesto' => $idPresupuesto, 'idPresupuestoHistorico' => $idPresupuestoHistorico])->row_array();
		$dataParaVista['valorPorcentual'] = $this->db->select('osds.valorPorcentual')->from('compras.ordenServicioDetalleSub osds')->join('compras.ordenServicioDetalle osd', 'osd.idOrdenServicioDetalle = osds.idOrdenServicioDetalle')->where('osd.estado', 1)->where('osds.idTipoPresupuestoDetalle', COD_ASIGNACIONFAMILIAR)->where('osd.idOrdenServicio', $dataParaVista['presupuesto']['idOrdenServicio'])->get()->row_array()['valorPorcentual'];
		$dataParaVista['idCuenta'] = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $dataParaVista['presupuesto']['idOrdenServicio']])->row_array()['idCuenta'];
		$dataParaVista['cargos'] = $this->mCotizacion->getAll_Cargos(['soloCargosOcupados' => true, 'idCuenta' => $dataParaVista['idCuenta']])->result_array();
		$dataParaVista['empleados'] = $this->mOrdenServicio->getAll_RRHHEmpleados(['activo' => true])->result_array();
		$dataParaVista['tipoPresupuestoDetalleMovilidad'] = $this->db->get_where('compras.tipoPresupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalleAlmacen'] = $this->db->get_where('compras.tipoPresupuestoDetalleAlmacen', ['estado' => 1])->result_array();
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];

		$where = [];
		if (!empty($dataParaVista['idCuenta'])) {
			$where['idCuenta'] = $dataParaVista['idCuenta'];
		}
		// Para traer presupuestoDetalleAlmacen y presupuestoDetalleAlmacenRecursos
		$idPreDet_Almacen = $this->db->get_where('compras.presupuestoDetalle', ['idPresupuesto' => $idPresupuesto, 'idTipoPresupuesto' => COD_ALMACEN, 'idPresupuestoHistorico' => $idPresupuestoHistorico])->row_array()['idPresupuestoDetalle'];

		$arTPDA = $this->db->get_where('compras.presupuestoDetalleAlmacen', ['idPresupuestoDetalle' => $idPreDet_Almacen])->result_array();
		foreach ($arTPDA as $v) {
			$dataParaVista['dataTPDA'][$v['idTipoPresupuestoDetalleAlmacen']] = $v;
		}

		$arTPDAR = $this->db->get_where('compras.presupuestoDetalleAlmacenRecursos', ['idPresupuestoDetalle' => $idPreDet_Almacen])->result_array();
		foreach ($arTPDAR as $v) {
			$dataParaVista['dataTPDARecursos'][$v['idTipoPresupuestoDetalleAlmacen']][] = $v;
		}
		// Fin

		// $items = $this->db->where('idTipoPresupuestoDetalle is not null')->get_where('compras.item', $where)->result_array();
		$items = $this->mOrdenServicio->getItemsCnPresupuesto($where)->result_array();
		foreach ($items as $item) {
			if (!isset($dataParaVista['item'][$item['idTipoPresupuestoDetalle']])) $dataParaVista['item'][$item['idTipoPresupuestoDetalle']] = [];
			$dataParaVista['items'][$item['idTipoPresupuestoDetalle']][] = $item;
		}
		$dataParaVista['itemPrecio'] = $this->mOrdenServicio->itemPrecios();

		$presupuestoCargo = $this->mOrdenServicio->getPresupuestoCargo($idPresupuesto, $idPresupuestoHistorico)->result_array();
		foreach ($presupuestoCargo as $k => $v) {
			$cargo[$v['idCargo']] = $v;
			$fecha[$v['fecha']] = $v;
			$dataParaVista['presupuestoCargo'][$v['fecha']][$v['idCargo']] = $v;
		}
		foreach ($fecha as $k => $v) {
			$dataParaVista['fechaDelPre'][] = $v;
		}
		foreach ($cargo as $k => $v) {
			$dataParaVista['cargoDelPre'][] = $v;
		}
		$dataParaVista['presupuestoDetalle'] = $this->mOrdenServicio->getPresupuestoDetalle($idPresupuesto)->result_array();

		$presupuestoDetalleSueldoAdicional = [];
		foreach ($dataParaVista['presupuestoDetalle'] as $k => $v) {
			$dataParaVista['presupuestoDetalleSub'][$v['idPresupuestoDetalle']] = $this->mOrdenServicio->getPresupuestoDetalleSub($v['idPresupuestoDetalle'])->result_array();

			foreach ($dataParaVista['presupuestoDetalleSub'][$v['idPresupuestoDetalle']] as $presDetSub) {
				foreach ($this->db->get_where('compras.presupuestoDetalleSubCargo', ['idPresupuestoDetalleSub' => $presDetSub['idPresupuestoDetalleSub']])->result_array() as $prDetSbCar) {
					$dataParaVista['presupuestoDetalleSubCargo'][$presDetSub['idPresupuestoDetalleSub']][$prDetSbCar['idCargo']] = $prDetSbCar;
				}
				$dataParaVista['presupuestoDetalleSubElemento'][$presDetSub['idPresupuestoDetalleSub']] = [];
				foreach ($this->db->get_where('compras.presupuestoDetalleSubElemento', ['idPresupuestoDetalleSub' => $presDetSub['idPresupuestoDetalleSub']])->result_array() as $prDetSbElm) {
					$dataParaVista['presupuestoDetalleSubElemento'][$presDetSub['idPresupuestoDetalleSub']][] = $prDetSbElm;
				}
			}

			$presupuestoDetalleSueldo = $this->mOrdenServicio->getPresupuestoDetalleSueldo($v['idPresupuestoDetalle'])->result_array();
			foreach ($presupuestoDetalleSueldo as $pds) {
				$dataParaVista['presupuestoDetalleSueldo'][$pds['idPresupuestoDetalle']][$pds['idTipoPresupuestoDetalle']][$pds['idCargo']] = $pds;
				$dataParaVista['idCargoRef'] = $pds['idCargo'];
			}

			if ($v['idTipoPresupuesto'] == COD_SUELDO) $presupuestoDetalleSueldoAdicional = $this->db->get_where('compras.presupuestoDetalleSueldoAdicional', ['idPresupuestoDetalle' => $v['idPresupuestoDetalle']])->result_array();
			if ($v['idTipoPresupuesto'] == COD_MOVILIDAD) $presupuestoDetalleMovilidad = $this->db->get_where('compras.presupuestoDetalleMovilidad', ['idPresupuestoDetalle' => $v['idPresupuestoDetalle']])->result_array();
		}
		$dataParaVista['presupuestoDetalleMovilidad'] = [];
		if (!empty($presupuestoDetalleMovilidad)) {
			foreach ($presupuestoDetalleMovilidad as $km => $vm) {
				$dataParaVista['presupuestoDetalleMovilidad'][$vm['idTipoPresupuestoDetalleMovilidad']] = $vm;
			}
		}
		$dataParaVista['presupuestoDetalleSueldoAdicional'] = $presupuestoDetalleSueldoAdicional;

		foreach ($this->db->select('tpd.*, it.costo, it.idProveedor')->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')->order_by('tpd.nombre')->get('compras.tipoPresupuestoDetalle tpd')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formularioRegistroSincerado", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['fechaDelPre'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['cargoDelPre'];

		echo json_encode($result);
	}

	public function registrarSincerado()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idOrdenServicio = $post['idOrdenServicio'];
		$post['fechaList'] = checkAndConvertToArray($post['fechaList']);
		$post['cargoList'] = checkAndConvertToArray($post['cargoList']);
		$post['idTipoPresupuesto'] = checkAndConvertToArray($post['idTipoPresupuesto']);
		$post['tpdS'] = checkAndConvertToArray($post['tpdS']);
		$post['clS'] = checkAndConvertToArray($post['clS']);

		$post['sinc_montoOriginal'] = is_array($post['sinc_montoOriginal']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['sinc_montoOriginal']) : number_format(floatval(str_replace(',', '', $post['sinc_montoOriginal'])), 2, '.', '');
		$post['sinc_montoSincerado'] = is_array($post['sinc_montoSincerado']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['sinc_montoSincerado']) : number_format(floatval(str_replace(',', '', $post['sinc_montoSincerado'])), 2, '.', '');
		$post['sueldo_montoOriginal'] = is_array($post['sueldo_montoOriginal']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['sueldo_montoOriginal']) : number_format(floatval(str_replace(',', '', $post['sueldo_montoOriginal'])), 2, '.', '');
		$post['otros_montoOriginal'] = is_array($post['otros_montoOriginal']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['otros_montoOriginal']) : number_format(floatval(str_replace(',', '', $post['otros_montoOriginal'])), 2, '.', '');
		$post['otros_montoSincerado'] = is_array($post['otros_montoSincerado']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['otros_montoSincerado']) : number_format(floatval(str_replace(',', '', $post['otros_montoSincerado'])), 2, '.', '');
		$post['sueldo_montoSincerado'] = is_array($post['sueldo_montoSincerado']) ? array_map(function ($costo) {
			return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
		}, $post['sueldo_montoSincerado']) : number_format(floatval(str_replace(',', '', $post['sueldo_montoSincerado'])), 2, '.', '');
		$post['presupuestoSubTotal'] = number_format(floatval(str_replace(',', '', $post['presupuestoSubTotal'])), 2, '.', '');
		$post['head_sbtotalOriginal'] = number_format(floatval(str_replace(',', '', $post['head_sbtotalOriginal'])), 2, '.', '');
		$post['head_sbtotalSincerado'] = number_format(floatval(str_replace(',', '', $post['head_sbtotalSincerado'])), 2, '.', '');
		$post['presupuestoFee1'] = number_format(floatval(str_replace(',', '', $post['presupuestoFee1'])), 2, '.', '');
		$post['head_fee1Original'] = number_format(floatval(str_replace(',', '', $post['head_fee1Original'])), 2, '.', '');
		$post['head_fee1Sincerado'] = number_format(floatval(str_replace(',', '', $post['head_fee1Sincerado'])), 2, '.', '');
		$post['presupuestoFee2'] = number_format(floatval(str_replace(',', '', $post['presupuestoFee2'])), 2, '.', '');
		$post['head_fee2Original'] = number_format(floatval(str_replace(',', '', $post['head_fee2Original'])), 2, '.', '');
		$post['head_fee2Sincerado'] = number_format(floatval(str_replace(',', '', $post['head_fee2Sincerado'])), 2, '.', '');
		$post['presupuestoFee3'] = number_format(floatval(str_replace(',', '', $post['presupuestoFee3'])), 2, '.', '');
		$post['head_fee3Original'] = number_format(floatval(str_replace(',', '', $post['head_fee3Original'])), 2, '.', '');
		$post['head_fee3Sincerado'] = number_format(floatval(str_replace(',', '', $post['head_fee3Sincerado'])), 2, '.', '');
		$post['head_totalOriginal'] = number_format(floatval(str_replace(',', '', $post['head_totalOriginal'])), 2, '.', '');
		$post['head_totalSincerado'] = number_format(floatval(str_replace(',', '', $post['head_totalSincerado'])), 2, '.', '');
		if (!empty($post['montoSueldoAdicional'])) {
			$post['montoSueldoAdicional'] = number_format(floatval(str_replace(',', '', $post['montoSueldoAdicional'])), 2, '.', '');
		}
		if (!empty($post['movilidadSueldoAdicional'])) {
			$post['movilidadSueldoAdicional'] = number_format(floatval(str_replace(',', '', $post['movilidadSueldoAdicional'])), 2, '.', '');
		}


		if (!isset($post['idSincerado'])) { // NUEVO
			$insertSincerado = [
				'idPresupuesto' => $post['idPresupuesto'],
				'idPresupuestoHistorico' => $post['idPresupuestoHistorico'],
				'idOrdenServicio' => $idOrdenServicio,
				'fecha_seleccionada' => $post['fechaSincerado'],
				'sctr' => isset($post['pesupuestoSctr']) ? $post['pesupuestoSctr'] : NULL,
				'subtotalOriginal' => $post['head_sbtotalOriginal'],
				'subtotalSincerado' => $post['head_sbtotalSincerado'],
				'fee1' => $post['presupuestoFee1'],
				'totalFee1Original' => $post['head_fee1Original'],
				'totalFee1Sincerado' => $post['head_fee1Sincerado'],
				'fee2' => $post['presupuestoFee2'],
				'totalFee2Original' => $post['head_fee2Original'],
				'totalFee2Sincerado' => $post['head_fee2Sincerado'],
				'fee3' => $post['presupuestoFee3'],
				'totalFee3Original' => $post['head_fee3Original'],
				'totalFee3Sincerado' => $post['head_fee3Sincerado'],
				'totalOriginal' => $post['head_totalOriginal'],
				'totalSincerado' => $post['head_totalSincerado'],
				'observacion' => $post['observacion'],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			if (floatval($post['head_fee3Sincerado']) > floatval($post['head_fee3Original'])) {
				$insertSincerado['flagPendienteAprobar'] = 1;
			}
			$this->db->insert('compras.sincerado', $insertSincerado);
			$idSincerado = $this->db->insert_id();
		} else { // ACTUALIZAR
			$updateSincerado = [
				'idPresupuesto' => $post['idPresupuesto'],
				'idPresupuestoHistorico' => $post['idPresupuestoHistorico'],
				'idOrdenServicio' => $idOrdenServicio,
				'fecha_seleccionada' => $post['fechaSincerado'],
				'sctr' => isset($post['pesupuestoSctr']) ? $post['pesupuestoSctr'] : NULL,
				'subtotalOriginal' => $post['head_sbtotalOriginal'],
				'subtotalSincerado' => $post['head_sbtotalSincerado'],
				'fee1' => $post['presupuestoFee1'],
				'totalFee1Original' => $post['head_fee1Original'],
				'totalFee1Sincerado' => $post['head_fee1Sincerado'],
				'fee2' => $post['presupuestoFee2'],
				'totalFee2Original' => $post['head_fee2Original'],
				'totalFee2Sincerado' => $post['head_fee2Sincerado'],
				'fee3' => $post['presupuestoFee3'],
				'totalFee3Original' => $post['head_fee3Original'],
				'totalFee3Sincerado' => $post['head_fee3Sincerado'],
				'totalOriginal' => $post['head_totalOriginal'],
				'totalSincerado' => $post['head_totalSincerado'],
				'observacion' => $post['observacion'],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$idSincerado = $post['idSincerado'];
			$this->db->update('compras.sincerado', $updateSincerado, ['idSincerado' => $idSincerado]);
			$this->model->anularSinceradoDetalle($idSincerado);
		}

		// compras.sinceradoCargo
		$insertSinceradoCargo = [];
		foreach ($post['fechaList'] as $kf => $vf) {
			foreach ($post['cargoList'] as $vc) {
				$insertSinceradoCargo[] = [
					'idSincerado' => $idSincerado,
					'fecha' => date_change_format_bd($vf),
					'idCargo' => $vc,
					'cantidad' => $post["cantidadCargoFecha[$vc][$kf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.sinceradoCargo', $insertSinceradoCargo);

		// compras.sinceradoDetalle
		foreach ($post['idTipoPresupuesto'] as $vd) {
			$montoOriginal = 0;
			$montoSincerado = 0;
			$post['sinc_idTipoPresupuesto'] = checkAndConvertToArray($post['sinc_idTipoPresupuesto']);
			$post['sinc_montoOriginal'] = checkAndConvertToArray($post['sinc_montoOriginal']);
			$post['sinc_montoSincerado'] = checkAndConvertToArray($post['sinc_montoSincerado']);
			foreach ($post['sinc_idTipoPresupuesto'] as $k_ => $v_) {
				if ($vd == $v_) {
					$montoOriginal = $post['sinc_montoOriginal'][$k_];
					$montoSincerado = $post['sinc_montoSincerado'][$k_];
				}
			}
			$insertSinceradoDetalle = [
				'idSincerado' => $idSincerado,
				'idTipoPresupuesto' => $vd,
				'montoOriginal' => verificarEmpty($montoOriginal, 2),
				'montoSincerado' => verificarEmpty($montoSincerado, 2),
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.sinceradoDetalle', $insertSinceradoDetalle);
			$idSinceradoDetalle = $this->db->insert_id();

			// compras.sinceradoDetalleSueldo && compras.sinceradoDetalleSueldoAdicional
			if ($vd == COD_SUELDO) {
				// compras.sinceradoDetalleSueldo
				$insertSinceradoDetalleSueldo = [];
				foreach ($post['cargoList'] as $vc) {
					$post["monto[$vc]"] = is_array($post["monto[$vc]"]) ? array_map(function ($costo) {
						return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
					}, $post["monto[$vc]"]) : number_format(floatval(str_replace(',', '', $post["monto[$vc]"])), 2, '.', '');
					$post["monto[$vc]"] = checkAndConvertToArray($post["monto[$vc]"]);
					foreach ($post['tpdS'] as $kds => $vds) {
						$insertSinceradoDetalleSueldo[] = [
							'idSinceradoDetalle' => $idSinceradoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'idCargo' => $vc,
							'porCL' => $post["clS"][$kds],
							'monto' => $post["monto[$vc]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
				$this->db->insert_batch('compras.sinceradoDetalleSueldo', $insertSinceradoDetalleSueldo);

				// compras.sinceradoDetalleSueldo_Det
				$insertSinceradoDetalleSueldo_Det = [];
				$post['sueldo_idCargo'] = checkAndConvertToArray($post['sueldo_idCargo']);
				$post['sueldo_montoSincerado'] = checkAndConvertToArray($post['sueldo_montoSincerado']);
				$post['sueldo_montoOriginal'] = checkAndConvertToArray($post['sueldo_montoOriginal']);
				$post['sueldo_flagIncentivo'] = checkAndConvertToArray($post['sueldo_flagIncentivo']);
				foreach ($post['sueldo_idCargo'] as $k_ => $v_) {
					$insertSinceradoDetalleSueldo_Det[] = [
						'idSinceradoDetalle' => $idSinceradoDetalle,
						'idCargo' => verificarEmpty($v_, 4),
						'flagIncentivo' => $post['sueldo_flagIncentivo'][$k_],
						'montoOriginal' => $post['sueldo_montoOriginal'][$k_],
						'montoSincerado' => $post['sueldo_montoSincerado'][$k_],
						'idUsuario' => $this->idUsuario,
						'fechaReg' => getActualDateTime()
					];
				}
				$this->db->insert_batch('compras.sinceradoDetalleSueldo_Det', $insertSinceradoDetalleSueldo_Det);

				// compras.sinceradoDetalleSueldoAdicional
				$insertSinceradoDetalleSueldoAdicional = [];
				if (isset($post['cargoSueldoAdicional'])) {
					$post['cargoSueldoAdicional'] = checkAndConvertToArray($post['cargoSueldoAdicional']);
					$post['empleadoSueldoAdicional'] = checkAndConvertToArray($post['empleadoSueldoAdicional']);
					$post['montoSueldoAdicional'] = checkAndConvertToArray($post['montoSueldoAdicional']);
					$post['movilidadSueldoAdicional'] = checkAndConvertToArray($post['movilidadSueldoAdicional']);

					foreach ($post['cargoSueldoAdicional'] as $pdaK => $pda) {
						$insertSinceradoDetalleSueldoAdicional[] = [
							'idSinceradoDetalle' => $idSinceradoDetalle,
							'idCargo' => $pda,
							'idEmpleado' => $post['empleadoSueldoAdicional'][$pdaK],
							'monto' => $post['montoSueldoAdicional'][$pdaK],
							'montoMovilidad' => $post['movilidadSueldoAdicional'][$pdaK],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.sinceradoDetalleSueldoAdicional', $insertSinceradoDetalleSueldoAdicional);
				}
			} else if ($vd == COD_MOVILIDAD) {
				// compras.sinceradoDetalleMovilidad
				$insertSinceradoDetalleMovilidad = [];
				if (isset($post['movOrigen'])) {
					$post['movIdTPDM'] = checkAndConvertToArray($post['movIdTPDM']);
					$post['movOrigen'] = checkAndConvertToArray($post['movOrigen']);
					$post['movDestino'] = checkAndConvertToArray($post['movDestino']);
					$post['movFrecuenciaOpc'] = checkAndConvertToArray($post['movFrecuenciaOpc']);
					$post['movDias'] = checkAndConvertToArray($post['movDias']);
					$post['movPrecBus'] = checkAndConvertToArray($post['movPrecBus']);
					$post['movPrecHosp'] = checkAndConvertToArray($post['movPrecHosp']);
					$post['movPrecViaticos'] = checkAndConvertToArray($post['movPrecViaticos']);
					$post['movPrecMovInt'] = checkAndConvertToArray($post['movPrecMovInt']);
					$post['movPrecTaxi'] = checkAndConvertToArray($post['movPrecTaxi']);
					$post['movSubTotal'] = checkAndConvertToArray($post['movSubTotal']);
					$post['movFrecuenciaCnt'] = checkAndConvertToArray($post['movFrecuenciaCnt']);
					$post['movTotal'] = checkAndConvertToArray($post['movTotal']);

					foreach ($post['movOrigen'] as $kmov => $vmov) {
						$insertSinceradoDetalleMovilidad[] = [
							'idSinceradoDetalle' => $idSinceradoDetalle,
							'idTipoPresupuestoDetalleMovilidad' => $post['movIdTPDM'][$kmov],
							'origen' => $vmov,
							'destino' => $post['movDestino'][$kmov],
							'split' => $post['movFrecuenciaOpc'][$kmov],
							'dias' => $post['movDias'][$kmov],
							'precioBus' => $post['movPrecBus'][$kmov],
							'precioHospedaje' => $post['movPrecHosp'][$kmov],
							'precioViaticos' => $post['movPrecViaticos'][$kmov],
							'precioMovilidadInterna' => $post['movPrecMovInt'][$kmov],
							'precioTaxi' => $post['movPrecTaxi'][$kmov],
							'subtotal' => $post['movSubTotal'][$kmov],
							'frecuencia' => $post['movFrecuenciaCnt'][$kmov],
							'total' => $post['movTotal'][$kmov],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.sinceradoDetalleMovilidad', $insertSinceradoDetalleMovilidad);
				}

				// compras.sinceradoDetalleMovilidad_Det
				$insertSinceradoDetalleMovilidad_Det = [];
				$post['movilidad_viaje'] = checkAndConvertToArray($post['movilidad_viaje']);
				$post['movilidad_adicional'] = checkAndConvertToArray($post['movilidad_adicional']);
				$post['movilidad_montoOriginal'] = checkAndConvertToArray($post['movilidad_montoOriginal']);
				$post['movilidad_montoSincerado'] = checkAndConvertToArray($post['movilidad_montoSincerado']);
				foreach ($post['movilidad_montoOriginal'] as $k_ => $v_) {
					$insertSinceradoDetalleMovilidad_Det[] = [
						'idSinceradoDetalle' => $idSinceradoDetalle,
						'flagViaje' => $post['movilidad_viaje'][$k_],
						'flagAdicional' => $post['movilidad_adicional'][$k_],
						'montoOriginal' => $post['movilidad_montoOriginal'][$k_],
						'montoSincerado' => $post['movilidad_montoSincerado'][$k_],
						'idUsuario' => $this->idUsuario,
						'fechaReg' => getActualDateTime()
					];
				}
				$this->db->insert_batch('compras.sinceradoDetalleMovilidad_Det', $insertSinceradoDetalleMovilidad_Det);
			} else if ($vd == COD_ALMACEN) {
				// compras.sinceradoDetalleAlmacen
				$insertSinceradoDetalleAlmacen = [];
				if (isset($post['almFrecuenciaOpc'])) {
					$post['almIdTPDA'] = checkAndConvertToArray($post['almIdTPDA']);
					$post['almFrecuenciaOpc'] = checkAndConvertToArray($post['almFrecuenciaOpc']);
					$post['almMonto'] = checkAndConvertToArray($post['almMonto']);

					foreach ($post['almIdTPDA'] as $kalm => $valm) {
						$insertSinceradoDetalleAlmacen[] = [
							'idSinceradoDetalle' => $idSinceradoDetalle,
							'idTipoPresupuestoDetalleAlmacen' => $valm,
							'split' => $post['almFrecuenciaOpc'][$kalm],
							'monto' => $post['almMonto'][$kalm],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.sinceradoDetalleAlmacen', $insertSinceradoDetalleAlmacen);
				}

				// compras.sinceradoDetalleAlmacenRecursos
				if (isset($post['almIdTPDAR'])) {
					$post['almIdTPDAR'] = checkAndConvertToArray($post['almIdTPDAR']);
					$insertSinceradoDetalleAlmacenRecursos = [];
					foreach ($post['fechaList'] as $kf => $vf) {
						foreach ($post['almIdTPDAR'] as $vc) {
							$insertSinceradoDetalleAlmacenRecursos[] = [
								'idSinceradoDetalle' => $idSinceradoDetalle,
								'idTipoPresupuestoDetalleAlmacen' => $vc,
								'fecha' => $vf,
								'cantidad' => $post["almRecursos[$vc][$kf]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
					}
					$this->db->insert_batch('compras.sinceradoDetalleAlmacenRecursos', $insertSinceradoDetalleAlmacenRecursos);
				}
			} else { // compras.sinceradoDetalleSub
				$insertSinceradoDetalleSub = [];
				if (isset($post["tipoPresupuestoDetalleSub[$vd]"])) {
					$post["tipoPresupuestoDetalleSub[$vd]"] = checkAndConvertToArray($post["tipoPresupuestoDetalleSub[$vd]"]);

					foreach ($post["tipoPresupuestoDetalleSub[$vd]"] as $kds => $vds) {
						$post["precioUnitarioDS[$vd]"] = is_array($post["precioUnitarioDS[$vd]"]) ? array_map(function ($costo) {
							return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
						}, $post["precioUnitarioDS[$vd]"]) : number_format(floatval(str_replace(',', '', $post["precioUnitarioDS[$vd]"])), 2, '.', '');
						$post["montoDS[$vd]"] = is_array($post["montoDS[$vd]"]) ? array_map(function ($costo) {
							return number_format(floatval(str_replace(',', '', $costo)), 2, '.', '');
						}, $post["montoDS[$vd]"]) : number_format(floatval(str_replace(',', '', $post["montoDS[$vd]"])), 2, '.', '');
						$post["splitDS[$vd]"] = checkAndConvertToArray($post["splitDS[$vd]"]);
						$post["precioUnitarioDS[$vd]"] = checkAndConvertToArray($post["precioUnitarioDS[$vd]"]);
						$post["cantidadDS[$vd]"] = checkAndConvertToArray($post["cantidadDS[$vd]"]);
						$post["gapDS[$vd]"] = checkAndConvertToArray($post["gapDS[$vd]"]);
						$post["montoDS[$vd]"] = checkAndConvertToArray($post["montoDS[$vd]"]);
						$post["frecuenciaDS[$vd]"] = checkAndConvertToArray($post["frecuenciaDS[$vd]"]);

						if (is_numeric($vds)) {
							$idTipoPresupuestoDetalle = $vds;
						} else {
							$ii = [
								'idTipoPresupuesto' => $vd,
								'nombre' => $vds,
								'split' => $post["splitDS[$vd]"][$kds],
								'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
								'frecuencia' => $post["frecuenciaDS[$vd]"][$kds],
								'estado' => 1
							];
							$this->db->insert('compras.tipoPresupuestoDetalle', $ii);
							$idTipoPresupuestoDetalle = $this->db->insert_id();
						}

						$insertSinceradoDetalleSub = [
							'idSinceradoDetalle' => $idSinceradoDetalle,
							'idTipoPresupuestoDetalle' => $idTipoPresupuestoDetalle,
							'split' => $post["splitDS[$vd]"][$kds],
							'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
							'cantidad' => $post["cantidadDS[$vd]"][$kds],
							'monto' => $post["montoDS[$vd]"][$kds],
							'gap' => $post["gapDS[$vd]"][$kds],
							'idFrecuencia' => $post["frecuenciaDS[$vd]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
						$this->db->insert('compras.sinceradoDetalleSub', $insertSinceradoDetalleSub);
						$idSinceradoDetalleSub = $this->db->insert_id();

						// compras.sinceradoDetalleSubCargo
						$insertSinceradoDetalleSubCargo = [];
						foreach ($post['cargoList'] as $vc) {
							$insertSinceradoDetalleSubCargo[] = [
								'idSinceradoDetalleSub' => $idSinceradoDetalleSub,
								'idCargo' => $vc,
								'checked' => isset($post["chkDS[$vc][$vd][$kds]"]) ? true : false,
								'cantidad' => $post["subCantDS[$vc][$vd][$kds]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
						$this->db->insert_batch('compras.sinceradoDetalleSubCargo', $insertSinceradoDetalleSubCargo);

						// compras.sinceradoDetalleSubElemento
						$insertSinceradoDetalleSubElemento = [];
						if (isset($post["elementoPresupuesto[$vd][$kds]"])) {
							$post["elementoPresupuesto[$vd][$kds]"] = checkAndConvertToArray($post["elementoPresupuesto[$vd][$kds]"]);
							$post["cantidadElementos[$vd][$kds]"] = checkAndConvertToArray($post["cantidadElementos[$vd][$kds]"]);
							$post["montoElementos[$vd][$kds]"] = checkAndConvertToArray($post["montoElementos[$vd][$kds]"]);
							$post["subTotalElemento[$vd][$kds]"] = checkAndConvertToArray($post["subTotalElemento[$vd][$kds]"]);
							foreach ($post["elementoPresupuesto[$vd][$kds]"] as $elmK => $elmV) {
								$insertSinceradoDetalleSubElemento[] = [
									'idSinceradoDetalleSub' => $idSinceradoDetalleSub,
									'idItem' => $elmV,
									'cantidad' => $post["cantidadElementos[$vd][$kds]"][$elmK],
									'monto' => $post["montoElementos[$vd][$kds]"][$elmK],
									'subTotal' => $post["subTotalElemento[$vd][$kds]"][$elmK],
									'idUsuario' => $this->idUsuario,
									'fechaReg' => getActualDateTime()
								];
							}
						}
						if (!empty($insertSinceradoDetalleSubElemento)) $this->db->insert_batch('compras.sinceradoDetalleSubElemento', $insertSinceradoDetalleSubElemento);
					}
				}
			}
		}

		// compras.sincerado_Det
		$insertSincerado_Det = [];
		$post['otros_idTipoPresupuestoDetalle'] = checkAndConvertToArray($post['otros_idTipoPresupuestoDetalle']);
		$post['otros_flagSctr'] = checkAndConvertToArray($post['otros_flagSctr']);
		$post['otros_montoOriginal'] = checkAndConvertToArray($post['otros_montoOriginal']);
		$post['otros_montoSincerado'] = checkAndConvertToArray($post['otros_montoSincerado']);
		foreach ($post['otros_idTipoPresupuestoDetalle'] as $k_ => $v_) {
			$insertSincerado_Det[] = [
				'idSincerado' => $idSincerado,
				'idTipoPresupuestoDetalle' => $v_,
				'flagSctr' => $post['otros_flagSctr'][$k_],
				'montoOriginal' => $post['otros_montoOriginal'][$k_],
				'montoSincerado' => $post['otros_montoSincerado'][$k_],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->insert_batch('compras.sincerado_Det', $insertSincerado_Det);

		// $this->db->update('compras.ordenServicio', ['chkPresupuesto' => true, 'fechaPresupuesto' => getActualDateTime(), 'idOrdenServicioEstado' => '2'], ['idOrdenServicio' => $idOrdenServicio]);
		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function descargarExcel()
	{
		$post = json_decode($this->input->post('data'), true);


		$datosSincerado = $this->model->obtenerDatosSincerado($post)->result_array();
		$datosFechas = $this->model->obtenerOrdenServicioFechas($datosSincerado[0]['idSincerado'])->result_array();
		$datosSinceradoCargo = $this->model->obtenerSinceradoCargos($datosSincerado[0]['idSincerado'])->result_array();
		$datosCargoSueldo = $this->model->obtenerCargoSueldo($datosSincerado[0]['idSincerado'])->result_array();

		$datosPreHist = $this->model->obtenerPresupuestoHist($datosSincerado[0]['idSincerado'])->result_array();
		$datosfechaCargo = $this->model->obtenerFechaCargo($datosSincerado[0]['idSincerado'])->result_array();
		$datosTipoPresupuesto = $this->model->obtenerTipoPresupuesto($datosSincerado[0]['idSincerado'])->result_array();

		$datosDetalleSueldo = $this->model->obtenerDetalleSueldo($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalSueldo = $this->model->obtenerTotalSueldo($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraComunicacion = $this->model->obtenerCabeceraComunicacion($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleComunicacion = $this->model->obtenerDetalleComunicacion($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalComunicacion = $this->model->obtenerTotalComunicacion($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraGastosAdmin = $this->model->obtenerCabeceraGastosAdmin($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleGastosAdmin = $this->model->obtenerDetalleGastoAdmin($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalGastosAdmin = $this->model->obtenerTotalGastoAdmin($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraMateProte = $this->model->obtenerCabeceraMateProte($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleMateProte = $this->model->obtenerDetalleMateProte($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalMateProte = $this->model->obtenerTotalMateProte($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraMateOngo = $this->model->obtenerCabeceraMateOngo($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleMateOngo = $this->model->obtenerDetalleMateOngo($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalMateOngo = $this->model->obtenerTotalMateOngo($datosSincerado[0]['idSincerado'])->result_array();

		$datosDetalleMovilidad = $this->model->obtenerDetalleMovilidad($datosSincerado[0]['idSincerado'])->result_array();

		$datosDetalleAlmacen = $this->model->obtenerDetalleAlmacen($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraUniforme = $this->model->obtenerCabeceraUniforme($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleUniforme = $this->model->obtenerDetalleUniforme($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalUniforme = $this->model->obtenerTotalUniforme($datosSincerado[0]['idSincerado'])->result_array();

		$datosCaeceraMateOper = $this->model->obtenerCabeceraMateOper($datosSincerado[0]['idSincerado'])->result_array();
		$datosDetalleMateOper = $this->model->obtenerDetalleMateOper($datosSincerado[0]['idSincerado'])->result_array();
		$datosTotalMateOper = $this->model->obtenerTotalMateOper($datosSincerado[0]['idSincerado'])->result_array();


		$datosFeeTotal = $this->model->obtenerDetalleFeeTotal($datosSincerado[0]['idSincerado'])->result_array();

		//echo json_encode($datosCargoSueldo); exit;

		$data = [];

		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		// ini_set('memory_limit', '1024M');
		set_time_limit(0);

		/** Include PHPExcel */
		require_once '../phpExcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();

		/**ESTILOS**/
		$estilo_titulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				//'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'startcolor' => array('rgb' => 'FFC000')
			],
			'font'  => [
				'size' => 14,
				'name'  => 'Calibri',
				//'color' => array('rgb' => 'FFFFFF'),
				'bold' => true, // Agregar negrita
				'italic' => true // Agregar cursiva
			]
		];
		$estilo_subtitulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				//'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'startcolor' => array('rgb' => 'FFC000')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'color' => array('rgb' => 'C00000'),
				'bold' => true, // Agregar negrita
				'italic' => true // Agregar cursiva
			]
		];

		$estilo_sincerado = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => 'FFC000')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'color' => array('rgb' => 'FFFFFF'),
			]
		];
		$estilo_fecha = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => '002060')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'color' => array('rgb' => 'FFFFFF'),
			]
		];
		$estilo_cantidad = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				// 'type' => PHPExcel_Style_Fill::FILL_SOLID,
				// 'startcolor' => array('rgb' => '002060')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			]
		];
		$estilo_personal = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			]
		];
		$estilo_personal_titu = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => 'BDD7EE')
			],
			'font'  => [
				'size' => 12,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			]
		];
		$estilo_moneda = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				// 'type' => PHPExcel_Style_Fill::FILL_SOLID,
				// 'startcolor' => array('rgb' => '002060')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			],
			'numberformat' => [
				'code' => '[$S/ ]#,##0.00'
			]
		];
		$estilo_moneda_total = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => 'BDD7EE')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			],
			'numberformat' => [
				'code' => '[$S/ ]#,##0.00'
			]
		];
		$estilo_sub_total = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => 'FFD966')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			],
			'numberformat' => [
				'code' => '[$S/ ]#,##0.00'
			]
		];
		$estilo_sub_total_cab = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => 'FFD966')
			],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			]
			// 'numberformat' => [
			// 	'code' => '[$S/ ]#,##0.00'
			// ]
		];
		$estilo_fee = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			// 'fill' =>	[
			// 	'type' => PHPExcel_Style_Fill::FILL_SOLID,
			// 	'startcolor' => array('rgb' => 'FFD966')
			// ],
			'font'  => [
				'size' => 8,
				'name'  => 'Calibri',
				'bold' => true, // Agregar negrita
			]
			// 'numberformat' => [
			// 	'code' => '[$S/ ]#,##0.00'
			// ]
		];
		/**FIN ESTILOS**/
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$objPHPExcel->getActiveSheet()->getStyle('B1:S1')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'FORMATO DE PPTO')->getStyle('B1')->applyFromArray($estilo_titulo);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Costos sin IGV')->getStyle('B2')->applyFromArray($estilo_subtitulo);


		$gdImage = imagecreatefromjpeg(APPPATH . '../public/assets/images/visualimpact/logo_full.jpg');
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Sample image');
		$objDrawing->setDescription('TEST');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(50);
		$objDrawing->setCoordinates('C1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$col = "C";
		// columna de fechas
		foreach ($datosFechas as $k => $v) {
			$row = "4";
			$celda = $col . $row;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $v['fecha'])->getStyle($celda)->applyFromArray($estilo_fecha)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

			$row++;
			$celda = $col . $row;
			$cantPers = 0;
			foreach ($datosSinceradoCargo as $j => $i) {

				$cabecera = 'B' . $row;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $i['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);

				foreach ($datosfechaCargo as $d => $f) {
					if ($v['fecha'] == $f['fecha'] and $i['idCargo'] ==  $f['idCargo']) {
						$cantPers = $cantPers + $f['cantidad'];
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $f['cantidad'])->getStyle($celda)->applyFromArray($estilo_cantidad)->getFont()->setBold(true);
					}
				}

				$row++;
				$celda = $col . $row;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $cantPers)->getStyle($celda)->applyFromArray($estilo_cantidad)->getFont()->setBold(true);
			}
			$row++;
			$row++;
			$cantMontosTotalNormal = 0;
			foreach ($datosTipoPresupuesto as $m => $n) {
				if ($n['montoOriginal'] != 0) {
					$cabecera = 'B' . $row;
					$celda = $col . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $n['nombre']);

					if ($n['idTipoPresupuesto'] == 1) {
						foreach ($datosTotalSueldo as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 2) {
						foreach ($datosTotalComunicacion as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 3) {
						foreach ($datosTotalUniforme as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 4) {
						foreach ($datosTotalMateOper as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 5) {
						foreach ($datosTotalMateProte as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 6) {
						foreach ($datosTotalMateOngo as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 7) {
						foreach ($datosTotalGastosAdmin as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 8) {
						foreach ($datosDetalleMovilidad as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $ñ['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
							// $row++;
							// $celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 9) {
						foreach ($datosDetalleAlmacen as $t => $z) {
							if ($z['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotalNormal = $cantMontosTotalNormal + $z['montoOriginal'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $z['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
							// $row++;
							// $celda = $col . $row;
						}
					}
					$row++;
					$cabecera = 'B' . $row;
					$celda = $col . $row;
					if ($n['idTipoPresupuesto'] == 1) {
						foreach ($datosCargoSueldo as $j => $i) {
							$cabecera = 'B' . $row;
							if (!empty($i['nombre'])) {
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $i['nombre']);
							} else {
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'INCENTIVO');
							}

							foreach ($datosDetalleSueldo as $e => $r) {
								if ($i['idCargo'] == $r['idCargo'] and $r['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $r['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 2) {
						foreach ($datosCaeceraComunicacion as $t => $y) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $y['nombre']);
							foreach ($datosDetalleComunicacion as $z => $x) {
								if ($y['idTipoPresupuestoDetalle'] == $x['idTipoPresupuestoDetalle'] and $x['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $x['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 3) {
						foreach ($datosCaeceraUniforme as $ab => $cd) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $cd['nombre']);
							foreach ($datosDetalleUniforme as $ef => $gh) {
								if ($cd['idTipoPresupuestoDetalle'] == $gh['idTipoPresupuestoDetalle'] and $gh['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $gh['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 4) {
						foreach ($datosCaeceraMateOper as $xz => $yt) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $yt['nombre']);
							foreach ($datosDetalleMateOper as $ji => $mn) {
								if ($yt['idTipoPresupuestoDetalle'] == $mn['idTipoPresupuestoDetalle'] and $mn['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $mn['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 5) {
						foreach ($datosCaeceraMateProte as $q => $w) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $w['nombre']);
							foreach ($datosDetalleMateProte as $l => $a) {
								if ($w['idTipoPresupuestoDetalle'] == $a['idTipoPresupuestoDetalle'] and $a['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $a['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 6) {
						foreach ($datosCaeceraMateOngo as $e => $r) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $r['nombre']);
							foreach ($datosDetalleMateOngo as $u => $c) {
								if ($r['idTipoPresupuestoDetalle'] == $c['idTipoPresupuestoDetalle'] and $c['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $c['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 7) {
						foreach ($datosCaeceraGastosAdmin as $g => $h) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $h['nombre']);
							foreach ($datosDetalleGastosAdmin as $u => $o) {
								if ($h['idTipoPresupuestoDetalle'] == $o['idTipoPresupuestoDetalle'] and $o['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $o['montoOriginal'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
				}
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $cantMontosTotalNormal)->getStyle($celda)->applyFromArray($estilo_sub_total)->getFont()->setBold(true);
			$row++;
			$celda = $col . $row;

			foreach ($datosFeeTotal as $fe => $fee) {
				if ($fee['fecha_seleccionada'] == $v['fecha']) {

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee1Original'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee2Original'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee3Original'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalOriginal'])->getStyle($celda)->applyFromArray($estilo_sub_total)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
				}
			}

			//$col++;
			//aqui se termina la columna y se sube a la row 4
			$row = "4";
			$col++;
			$celda = $col . $row;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, "SINCERADO")->getStyle($celda)->applyFromArray($estilo_sincerado)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			$row++;
			$celda = $col . $row;
			$cantPers = 0;
			foreach ($datosSinceradoCargo as $j => $i) {
				foreach ($datosfechaCargo as $d => $f) {
					if ($v['fecha'] == $f['fecha'] and $i['idCargo'] ==  $f['idCargo']) {
						$cantPers = $cantPers + $f['cantidadSinc'];
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $f['cantidadSinc'])->getStyle($celda)->applyFromArray($estilo_cantidad)->getFont()->setBold(true);
					}
				}
				$row++;
				$celda = $col . $row;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $cantPers)->getStyle($celda)->applyFromArray($estilo_cantidad)->getFont()->setBold(true);
			}
			$row++;
			$row++;
			// aqui va el foreach
			$cantMontosTotal = 0;
			foreach ($datosTipoPresupuesto as $m => $n) {
				if ($n['montoOriginal'] != 0) {
					$cabecera = 'B' . $row;
					$celda = $col . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $n['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal_titu)->getFont()->setBold(true);

					if ($n['idTipoPresupuesto'] == 1) {
						foreach ($datosTotalSueldo as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 2) {
						foreach ($datosTotalComunicacion as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 3) {
						foreach ($datosTotalUniforme as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 4) {
						foreach ($datosTotalMateOper as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 5) {
						foreach ($datosTotalMateProte as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 6) {
						foreach ($datosTotalMateOngo as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 7) {
						foreach ($datosTotalGastosAdmin as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 8) {
						foreach ($datosDetalleMovilidad as $b => $ñ) {
							if ($ñ['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $ñ['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $ñ['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}
					if ($n['idTipoPresupuesto'] == 9) {
						foreach ($datosDetalleAlmacen as $t => $z) {
							if ($z['fecha_seleccionada'] == $v['fecha']) {
								$cantMontosTotal = $cantMontosTotal + $z['montoSincerado'];
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $z['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda_total)->getFont()->setBold(true);
							}
						}
					}

					$row++;
					$cabecera = 'B' . $row;
					$celda = $col . $row;
					if ($n['idTipoPresupuesto'] == 1) {
						foreach ($datosCargoSueldo as $j => $i) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $i['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleSueldo as $e => $r) {
								if ($i['idCargo'] == $r['idCargo'] and $r['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $r['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 2) {
						foreach ($datosCaeceraComunicacion as $t => $y) {
							//$cantComu = 0;
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $y['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleComunicacion as $z => $x) {
								if ($y['idTipoPresupuestoDetalle'] == $x['idTipoPresupuestoDetalle'] and $x['fecha_seleccionada'] == $v['fecha']) {
									//$cantComu = $cantComu + $x['montoSincerado'];
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $x['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}

							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 3) {
						foreach ($datosCaeceraUniforme as $ab => $cd) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $cd['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleUniforme as $ef => $gh) {
								if ($cd['idTipoPresupuestoDetalle'] == $gh['idTipoPresupuestoDetalle'] and $gh['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $gh['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 4) {
						foreach ($datosCaeceraMateOper as $xz => $yt) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $yt['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleMateOper as $ji => $mn) {
								if ($yt['idTipoPresupuestoDetalle'] == $mn['idTipoPresupuestoDetalle'] and $mn['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $mn['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 5) {
						foreach ($datosCaeceraMateProte as $q => $w) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $w['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleMateProte as $l => $a) {
								if ($w['idTipoPresupuestoDetalle'] == $a['idTipoPresupuestoDetalle'] and $a['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $a['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 6) {
						foreach ($datosCaeceraMateOngo as $e => $r) {
							$cabecera = 'B' . $row;
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $r['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							foreach ($datosDetalleMateOngo as $u => $c) {
								if ($r['idTipoPresupuestoDetalle'] == $c['idTipoPresupuestoDetalle'] and $c['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $c['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
					if ($n['idTipoPresupuesto'] == 7) {
						foreach ($datosCaeceraGastosAdmin as $g => $h) {
							$cabecera = 'B' . $row;
							if ($h['flagSctr'] == 1) {
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'SCTR')->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							} else {
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, $h['nombre'])->getStyle($cabecera)->applyFromArray($estilo_personal)->getFont()->setBold(true);
							}
							foreach ($datosDetalleGastosAdmin as $u => $o) {
								if ($h['idTipoPresupuestoDetalle'] == $o['idTipoPresupuestoDetalle'] and $o['fecha_seleccionada'] == $v['fecha']) {
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $o['montoSincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
								}
							}
							$row++;
							$celda = $col . $row;
						}
					}
				}
			}
			$cabecera = 'B' . $row;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'SUB TOTAL')->getStyle($cabecera)->applyFromArray($estilo_sub_total_cab)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $cantMontosTotal)->getStyle($celda)->applyFromArray($estilo_sub_total)->getFont()->setBold(true);
			$row++;
			$celda = $col . $row;

			foreach ($datosFeeTotal as $fe => $fee) {
				if ($fee['fecha_seleccionada'] == $v['fecha']) {
					$cabecera = 'B' . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'FEE ' . $fee['fee1'] . '%')->getStyle($cabecera)->applyFromArray($estilo_fee)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee1Sincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$cabecera = 'B' . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'FEE ' . $fee['fee2'] . '%')->getStyle($cabecera)->applyFromArray($estilo_fee)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee2Sincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$cabecera = 'B' . $row;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'FEE ' . $fee['fee3'] . '%')->getStyle($cabecera)->applyFromArray($estilo_fee)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalFee3Sincerado'])->getStyle($celda)->applyFromArray($estilo_moneda)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$cabecera = 'B' . $row;

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cabecera, 'TOTAL COTIZACION (sin IGV)')->getStyle($cabecera)->applyFromArray($estilo_sub_total_cab)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, $fee['totalSincerado'])->getStyle($celda)->applyFromArray($estilo_sub_total)->getFont()->setBold(true);
					$row++;
					$celda = $col . $row;
					$cabecera = 'B' . $row;
				}
			}




			$col++;
		}
		// $colUlt = $col . '1';
		// $col = "B";
		// $celda = $col . $row;
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, 'SUB TOTAL:')->getStyle($celda)->applyFromArray($estilo_sub_total_cab)->getFont()->setBold(true);
		// $row++;
		// $row++;
		// $row++;
		// $celda = $col . $row;
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, 'OBSERVACIONES:');
		// $row++;
		// $celda = $col . $row;
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue($celda, ' - FEE Proyecto 10% ');

		//$objPHPExcel->getActiveSheet()->getStyle("B1:".$colUlt)->applyFromArray($estilo_titulo)->getFont()->setBold(true);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
}
