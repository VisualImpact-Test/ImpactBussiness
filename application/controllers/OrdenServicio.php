<?php

defined('BASEPATH') or exit('No direct script access allowed');

class OrdenServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_OrdenServicio', 'model');
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
			'assets/custom/js/ordenServicio'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Orden de Servicio';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/OrdenServicio/index';

		$this->view($config);
	}

	public function adjuntarArchivo($id)
	{
		$config['single'] = true;
		// AGREGAR VALIDACION PARA SOLO MOSTRAR LOS PENDIENTES.
		$config['js']['script'] = array('assets/custom/js/adjuntarDocumento');
		$config['data']['documento'] = $this->model->getDocumento($id)->row_array();
		$config['view'] = 'adjuntarDocumento';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$dataParaVista = [];
		$departamentosCobertura = [];
		$provinciasCobertura = [];
		$distritosCobertura = [];
		$data = $this->model->obtenerInformacionOrdenServicio()->result_array();

		foreach ($data as $value) {
			$dataParaVista['ordenServicio'][$value['idOrdenServicio']] = $value;
			$cargo = $this->model->getOrdenServicioCargo($value['idOrdenServicio'])->result_array();
			$documento = $this->db->where('idOrdenServicio', $value['idOrdenServicio'])->where('estado', 1)->get('compras.ordenServicioDocumento')->result_array();

			if (!empty($cargo)) {
				$temp = [];
				foreach ($cargo as $cargoData) {
					$temp[] = $cargoData['cargo'];
				}
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['cargo'] = implode(', ', $temp);
			} else {
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['cargo'] = '';
			}

			if (!empty($documento)) {
				$temp = [];
				foreach ($documento as $documentoData) {
					$temp[] = $documentoData['documento'];
				}
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['documento'] = implode(', ', $temp);
			} else {
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['documento'] = '';
			}
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/OrdenServicio/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentOrdenServicio']['datatable'] = 'tb-ordenServicio';
		$result['data']['views']['idContentOrdenServicio']['html'] = $html;
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

	public function generarPdf($id)
	{
		$data = [];
		require_once('../mpdf/mpdf.php');
		// ini_set('memory_limit', '1024M');
		// set_time_limit(0);

		// $post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$dataParaVista['presupuesto'] = $pr = $this->db->get_where('compras.presupuesto', ['idPresupuesto' => $id])->row_array();
		$dataParaVista['ordenServicio'] = $oS = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $pr['idOrdenServicio']])->row_array();

		$idOSD_AF_Porcentaje = $this->db->get_where('compras.ordenServicioDetalle', ['estado' => 1, 'idOrdenServicio' => $pr['idOrdenServicio'], 'idTipoPresupuesto' => COD_SUELDO])->row_array()['idOrdenServicioDetalle'];
		$AsignacionFamiliarPorcentaje = $this->db->get_where('compras.ordenServicioDetalleSub', ['idOrdenServicioDetalle' => $idOSD_AF_Porcentaje, 'estado' => 1, 'idTipoPresupuestoDetalle' => COD_ASIGNACIONFAMILIAR])->row_array()['valorPorcentual'];

		$fechas = $this->db->get_where('compras.ordenServicioFecha', ['idOrdenServicio' => $pr['idOrdenServicio'], 'estado' => 1])->result_array();
		$dataParaVista['fechas'] = $fechas = changeKeyInArray($fechas, 'fecha');

		$cargos = $this->mCotizacion->getAll_Cargos()->result_array();
		$dataParaVista['cargos'] = changeKeyInArray($cargos, 'idCargoTrabajo');

		$cargos = $this->db->get_where('compras.ordenServicioCargo', ['idOrdenServicio' => $pr['idOrdenServicio'], 'estado' => 1])->result_array();
		$dataParaVista['cargosOS'] = changeKeyInArray($cargos, 'idCargo');

		$presupuestoCargoFecha = $this->db->get_where('compras.presupuestoCargo', ['idPresupuesto' => $id, 'estado' => 1])->result_array();
		$dataParaVista['cantidadPorCargoFecha'] = $presupuestoCargoFecha = changeKeyInArray($presupuestoCargoFecha, 'idCargo', 'fecha');

		log_message('error', $this->db->last_query());
		$tipoPresupuesto = $this->db->get_where('compras.tipoPresupuesto', ['estado' => 1])->result_array();
		$dataParaVista['tiposPresupuesto'] = changeKeyInArray($tipoPresupuesto, 'idTipoPresupuesto');

		$tipoPresupuestoDetalle = $this->db->get_where('compras.tipoPresupuestoDetalle', ['estado' => 1])->result_array();
		$dataParaVista['tiposPresupuestoDetalle'] = $tipoPresupuestoDetalle = changeKeyInArray($tipoPresupuestoDetalle, 'idTipoPresupuestoDetalle');

		$presupuestoDet = $this->db->get_where('compras.presupuestoDetalle', ['idPresupuesto' => $id, 'estado' => 1])->result_array();
		$dataParaVista['presupuestoDetalle'] = $pd = changeKeyInArray($presupuestoDet, 'idPresupuestoDetalle');
		$whereIdPreDet = obtenerDatosCabecera($presupuestoDet, 'idPresupuestoDetalle');

		$presupuestoDetSub = $this->db->where_in('idPresupuestoDetalle', $whereIdPreDet)->get_where('compras.presupuestoDetalleSub', ['estado' => 1])->result_array();
		$dataParaVista['presupuestoDetalleSub'] = $presupuestoDetSub = changeKeyInArray($presupuestoDetSub, 'idPresupuestoDetalle', 'idTipoPresupuestoDetalle');

		$presupuestoDetSueldo = $this->db->where_in('idPresupuestoDetalle', $whereIdPreDet)->get_where('compras.presupuestoDetalleSueldo', ['estado' => 1])->result_array();
		$dataParaVista['presupuestoDetalleSueldo'] = $presupuestoDetSueldo = changeKeyInArray($presupuestoDetSueldo, 'idPresupuestoDetalle', 'idCargo', 'idTipoPresupuestoDetalle');

		// log_message('error', $this->db->last_query());
		// log_message('error', json_encode($presupuestoDetSueldo));

		$presDetMovilidad = $this->db->where_in('idPresupuestoDetalle', $whereIdPreDet)->where('dias >', 0)->get_where('compras.presupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$presDetAlmacen = $this->db->where_in('idPresupuestoDetalle', $whereIdPreDet)->where('monto >', 0)->get_where('compras.presupuestoDetalleAlmacen', ['estado' => 1])->result_array();

		$calculoCargoFechaServicio = [];
		$sueldoTotalPorCargo = [];
		$incentivoTotalPorCargo = [];
		$porcentajeTotalPorCargo = [];

		foreach ($presupuestoDetSub as $k1 => $v1) { // $k1 = idPresupuestoDetalle
			if ($pd[$k1]['idTipoPresupuesto'] == COD_GASTOSADMINISTRATIVOS) $idPreDe_GastAdmi = $k1;
			foreach ($v1 as $k2 => $v2) { // $k2 = idTipoPresupuestoDetalle
				$presupuestoDetalleSubCargo = $this->db->get_where('compras.presupuestoDetalleSubCargo', ['idPresupuestoDetalleSub' => $v2['idPresupuestoDetalleSub'], 'checked' => 1])->result_array();
				$presupuestoDetalleSubCargo = changeKeyInArray($presupuestoDetalleSubCargo, 'idCargo');

				$valorMax = [];
				$acumulado = [];
				$keyCode = '';
				$nroMes = 0;
				foreach ($fechas as $kf => $vf) {
					if (!isset($calculoCargoFechaServicio[$k1][$k2][$kf])) $calculoCargoFechaServicio[$k1][$k2][$kf] = 0;
					$nroMes++;

					foreach ($presupuestoDetalleSubCargo as $kc => $vc) {
						if (!isset($acumulado[$kc])) $acumulado[$kc] = 0;
						if (!isset($valorMax[$kc])) $valorMax[$kc] = 0;

						$montoTot = floatval($v2['monto']);
						if ($v2['idFrecuencia'] == '1') { // MENSUAL
							$calculoCargoFechaServicio[$k1][$k2][$kf] += floatval($v2['precioUnitario']) * floatval($v2['split']) * floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']);
						} elseif ($v2['idFrecuencia'] == '2') { // BIMENSUAL
							if ($nroMes == 1 || ($nroMes - 1) % 2 == 0) $keyCode = $kf;
							if (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) > $valorMax[$kc])
								$valorMax[$kc] = floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']);

							if ($valorMax[$kc] > floatval($presupuestoDetalleSubCargo[$kc]['cantidad']))
								$valorMax[$kc] = floatval($presupuestoDetalleSubCargo[$kc]['cantidad']);

							$calculoCargoFechaServicio[$k1][$k2][$keyCode] = $valorMax[$kc] * floatval($v2['precioUnitario']) * floatval($v2['split']);
						} elseif ($v2['idFrecuencia'] == '3') { // SEMESTRAL
							if ($nroMes == 1 || ($nroMes - 1) % 6 == 0) $keyCode = $kf;
							if (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) > $valorMax[$kc])
								$valorMax[$kc] = floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']);

							if ($valorMax[$kc] > floatval($presupuestoDetalleSubCargo[$kc]['cantidad']))
								$valorMax[$kc] = floatval($presupuestoDetalleSubCargo[$kc]['cantidad']);

							$calculoCargoFechaServicio[$k1][$k2][$keyCode] = $valorMax[$kc] * floatval($v2['precioUnitario']) * floatval($v2['split']);
						} elseif ($v2['idFrecuencia'] == '4') { // ANUAL
							if ($nroMes == 1 || ($nroMes - 1) % 12 == 0) $keyCode = $kf;
							if (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) > $valorMax[$kc])
								$valorMax[$kc] = floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']);

							if ($valorMax[$kc] > floatval($presupuestoDetalleSubCargo[$kc]['cantidad']))
								$valorMax[$kc] = floatval($presupuestoDetalleSubCargo[$kc]['cantidad']);

							$calculoCargoFechaServicio[$k1][$k2][$keyCode] = $valorMax[$kc] * floatval($v2['precioUnitario']) * floatval($v2['split']);
						} elseif ($v2['idFrecuencia'] == '5') { // UNICO
							if (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) > $valorMax[$kc]) $valorMax[$kc] = floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']);
							if ($acumulado[$kc] > $valorMax[$kc]) $acumulado[$kc] = $valorMax[$kc];

							$calculoCargoFechaServicio[$k1][$k2][$kf] += floatval($v2['precioUnitario']) * floatval($v2['split']) * (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) - $acumulado[$kc]);

							$acumulado[$kc] += (floatval($presupuestoCargoFecha[$kc][$kf]['cantidad']) - $acumulado[$kc]); // No borrar.
						} elseif ($v2['idFrecuencia'] == '6') { // FRACCIONADO
							$calculoCargoFechaServicio[$k1][$k2][$kf] = $montoTot / count($fechas);
						} else {
							$calculoCargoFechaServicio[$k1][$k2][$kf] = $v2['idFrecuencia'];
						}
					}
				}
			}
		}

		if (!empty($presDetAlmacen)) {
			foreach ($presDetAlmacen as $k => $v) {
				$nroMes = 0;
				foreach ($fechas as $kf => $vf) {
					$nroMes++;

					if (!isset($calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['almacen'][$kf])) $calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['almacen'][$kf] = 0;
					if ($v['split'] == 1) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['almacen'][$kf] += $v['monto'] * 1.348;
					} else if ($v['split'] == 2 && ($nroMes % 2 == 0 || count($fechas) == $nroMes)) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['almacen'][$kf] += $v['monto'] * 1.348;
					} else if ($v['split'] == 3 && ($nroMes % 3 == 0 || count($fechas) == $nroMes)) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['almacen'][$kf] += $v['monto'] * 1.348;
					}
				}
			}
		}

		if (!empty($presDetMovilidad)) {
			$movilidadAdicionalTotal = $this->db->select_sum('montoMovilidad')->where_in('idPresupuestoDetalle', $whereIdPreDet)->get('compras.presupuestoDetalleSueldoAdicional')->row_array()['montoMovilidad'];
			foreach ($presDetMovilidad as $k => $v) {
				$nroMes = 0;
				foreach ($fechas as $kf => $vf) {
					$nroMes++;

					if (!isset($calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['viajes'][$kf])) $calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['viajes'][$kf] = 0;
					if ($v['split'] == 1) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['viajes'][$kf] += $v['total'];
					} else if ($v['split'] == 2 && ($nroMes % 2 == 0 || count($fechas) == $nroMes)) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['viajes'][$kf] += $v['total'];
					} else if ($v['split'] == 3 && ($nroMes % 3 == 0 || count($fechas) == $nroMes)) {
						$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['viajes'][$kf] += $v['total'];
					}

					$calculoCargoFechaServicio[$v['idPresupuestoDetalle']]['movAdicional'][$kf] = $movilidadAdicionalTotal;
				}
			}
		}

		foreach ($presupuestoDetSueldo as $k1 => $v1) { // $k1 = idPresupuestoDetalle	
			foreach ($v1 as $k2 => $v2) { // $k2 = idCargo
				foreach ($fechas as $kf => $vf) {
					$sueldoTotalPorCargo[$k1][$k2] = 0;
					$sueldoAdTotalPorCargo[$k1][$k2] = 0;
					$incentivoTotalPorCargo[$k1][$k2] = 0;
					$porcentajeTotalPorCargo[$k1][$k2] = 0;
					$montoParaSCTR[$k2] = 0;

					foreach ($v2 as $k3 => $v3) { // $k3 = idTipoPresupuestoDetalle
						if ($tipoPresupuestoDetalle[$k3]['tipo'] == '1') $sueldoTotalPorCargo[$k1][$k2] += $v3['monto'];
						if ($tipoPresupuestoDetalle[$k3]['tipo'] == '2') $sueldoAdTotalPorCargo[$k1][$k2] += $v3['monto'];
						if ($tipoPresupuestoDetalle[$k3]['tipo'] == '3') $incentivoTotalPorCargo[$k1][$k2] += $v3['monto'];
						if ($tipoPresupuestoDetalle[$k3]['tipo'] == '4') $porcentajeTotalPorCargo[$k1][$k2] += $v3['porCL'];
						if ($tipoPresupuestoDetalle[$k3]['tipo'] == '1' || $tipoPresupuestoDetalle[$k3]['tipo'] == '2' || $tipoPresupuestoDetalle[$k3]['tipo'] == '3') {
							if ($k3 == COD_ASIGNACIONFAMILIAR) $montoParaSCTR[$k2] += ($v3['monto'] * 100 / $AsignacionFamiliarPorcentaje);
							else $montoParaSCTR[$k2] += $v3['monto'];
						}
						if (!isset($calculoCargoFechaServicio[$k1][$k2][$kf])) $calculoCargoFechaServicio[$k1][$k2][$kf] = 0;
						$calculoCargoFechaServicio[$k1][$k2][$kf] = ($sueldoTotalPorCargo[$k1][$k2] * (100 + $porcentajeTotalPorCargo[$k1][$k2]) / 100 + $sueldoAdTotalPorCargo[$k1][$k2]) * floatval($presupuestoCargoFecha[$k2][$kf]['cantidad']);
					}

					if (!isset($calculoCargoFechaServicio[$k1]['incentivo'][$kf])) $calculoCargoFechaServicio[$k1]['incentivo'][$kf] = 0;
					$calculoCargoFechaServicio[$k1]['incentivo'][$kf] += ($incentivoTotalPorCargo[$k1][$k2] * (100 + $porcentajeTotalPorCargo[$k1][$k2]) / 100) * floatval($presupuestoCargoFecha[$k2][$kf]['cantidad']);
					if ($pr['sctr'] > 0) {
						$calculoCargoFechaServicio[$idPreDe_GastAdmi]['sctr'][$kf] += ($montoParaSCTR[$k2] * $pr['sctr'] / 100) * floatval($presupuestoCargoFecha[$k2][$kf]['cantidad']);
					}
				}
			}
		}

		$sueldoAdicionalTotal = $this->db->select_sum('monto')->where_in('idPresupuestoDetalle', $whereIdPreDet)->get('compras.presupuestoDetalleSueldoAdicional')->row_array()['monto'];
		$sueldoAdicionalTotal = floatval($sueldoAdicionalTotal);

		if (!empty($calculoCargoFechaServicio[$k1]['incentivo'])) {
			foreach ($calculoCargoFechaServicio[$k1]['incentivo'] as $k => $v) {
				$calculoCargoFechaServicio[$k1]['incentivo'][$k] += $sueldoAdicionalTotal;
			}
		}
		$dataParaVista['calculoCargoFechaServicio'] = $calculoCargoFechaServicio;

		$totalCargoFechaServicio = [];
		foreach ($calculoCargoFechaServicio as $k1 => $v1) { // $k1 = idPresupuestoDetalle
			foreach ($v1 as $k2 => $v2) { // $k2 = idTipoPresupuestoDetalle
				foreach ($v2 as $k3 => $v3) { // $k3 = fecha
					if (!isset($totalCargoFechaServicio[$k1][$k3])) $totalCargoFechaServicio[$k1][$k3] = 0;
					$totalCargoFechaServicio[$k1][$k3] += $v3;

					if (!isset($totalCargoFechaServicio['acumuladoPorFecha'][$k3])) $totalCargoFechaServicio['acumuladoPorFecha'][$k3] = 0;
					$totalCargoFechaServicio['acumuladoPorFecha'][$k3] += $v3;

					if (!isset($totalCargoFechaServicio['acumuladoTotal'])) $totalCargoFechaServicio['acumuladoTotal'] = 0;
					$totalCargoFechaServicio['acumuladoTotal'] += $v3;

					if (!isset($totalCargoFechaServicio['totalFinal'][$k1])) $totalCargoFechaServicio['totalFinal'][$k1] = 0;
					$totalCargoFechaServicio['totalFinal'][$k1] += $v3;

					if (!isset($totalCargoFechaServicio['totalServicio'][$k2])) $totalCargoFechaServicio['totalServicio'][$k2] = 0;
					$totalCargoFechaServicio['totalServicio'][$k2] += $v3;
				}
			}
		}
		$dataParaVista['totalCargoFechaServicio'] = $totalCargoFechaServicio;


		$contenido['style'] = $this->load->view("modulos/OrdenServicio/pdf/oper_style", [], true);
		$contenido['header'] = $this->load->view("modulos/OrdenServicio/pdf/header", ['title' => $oS['nombre'] /*, 'codigo' => 'COD: SIG-OPE-FOR-???' */], true);
		$contenido['body'] = $this->load->view("modulos/OrdenServicio/pdf/body", $dataParaVista, true);
		$contenido['footer'] = $this->load->view("modulos/OrdenServicio/pdf/footer", ['solicitante' => ''], true);

		// foreach ($contenido as $v) echo $v; // Esta linea es para verlo desde HTML sin estar descargando a cada rato xd

		
		require APPPATH . '/vendor/autoload.php';
		// $orientation = '';
		// if ($dataParaVista['detalle'][0]['idItemTipo'] == COD_SERVICIO['id']) {
		// 	$orientation = 'L';
		// }
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
			'orientation' => 'L', // $orientation
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
		$title = 'Titulo de pruebita';
		$mpdf->Output("$title.pdf", 'D');

		// $this->aSessTrack[] = ['idAccion' => 9];
		
	}

	public function formularioRegistroOrdenServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		$tipoPresupuestoDetalle = [];
		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}

		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();

		$dataParaVista['cargo'] = $this->mCotizacion->getAll_Cargos()->result_array();

		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['ordenServicioCargo'] = [];
		$provincia = [];
		foreach ($this->model->obtenerProvincia()->result_array() as $k => $v) {
			$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		}
		$result['data']['provincia'] = $provincia;

		$distrito = [];
		foreach ($this->model->obtenerDistrito()->result_array() as $k => $v) {
			$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		}
		$result['data']['distrito'] = $distrito;

		$dataParaVista['ordenServicio'] = [];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar OrdenServicio';
		$result['data']['cargo'] = $dataParaVista['cargo'];
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroOrdenServicio", $dataParaVista, true);

		echo json_encode($result);
	}

	public function addDocumento()
	{
		$post = $this->input->post();
		$dataParaVista['documentosCargados'] = $post['documentoGenerado'];

		$dataParaVista['documento'] = $this->db->where('extension is not null')->get('compras.documento')->result_array();
		$dataParaVista['num'] = $post['id'];
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();
		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();

		echo $this->load->view('modulos/OrdenServicio/addDocumento', $dataParaVista, true);
	}

	public function registrarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idCliente = null;
		$idCuenta = null;
		$idCentroCosto = null;
		if ($post['chkUtilizarCliente']) {
			if (!is_numeric($post['clienteForm'])) {
				$insertCliente = [
					'nombre' => $post['clienteForm'],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.cliente', $insertCliente);
				$idCliente = $this->db->insert_id();
			} else {
				$idCliente = $post['clienteForm'];
			}
		} else {
			$idCuenta = $post['cuentaForm'];
			$idCentroCosto = $post['centroCostoForm'];
		}

		$insertOrdenServicio = [
			'idCliente' => $idCliente,
			'idCuenta' => $idCuenta,
			'idCentroCosto' => $idCentroCosto,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'nombre' => $post['nombre'],
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
			'chkUtilizarCliente' => $post['chkUtilizarCliente'],
			'chkPresupuesto' => false,
		];

		$this->db->insert('compras.ordenServicio', $insertOrdenServicio);
		$idOrdenServicio = $this->db->insert_id();

		$insertOrdenServicioHistorico = $insertOrdenServicio;
		$insertOrdenServicioHistorico['idOrdenServicio'] = $idOrdenServicio;
		$insertOrdenServicioHistorico['idUsuario'] = $this->idUsuario;
		$insertOrdenServicioHistorico['fechaReg'] = getActualDateTime();
		unset($insertOrdenServicioHistorico['chkAprobado']);
		unset($insertOrdenServicioHistorico['chkPresupuesto']);

		$this->db->insert('compras.ordenServicioHistorico', $insertOrdenServicioHistorico);

		if (!isset($post['cargo'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Registro Erroneo!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe indicar al menos un cargo']);
			goto respuesta;
		}

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['idDocumento'] = isset($post['idDocumento']) ? checkAndConvertToArray($post['idDocumento']) : [];
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];
		$post['cantidadCargo'] = isset($post['cantidadCargo']) ? checkAndConvertToArray($post['cantidadCargo']) : [];
		$post['sueldoCargo'] = isset($post['sueldoCargo']) ? checkAndConvertToArray($post['sueldoCargo']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'sueldo' => $post['sueldoCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->insert_batch('compras.ordenServicioCargo', $insertCargo);

		$insertOrdenServicioDetalle = [];
		$insertOrdenServicioDetalleSub = [];

		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertOrdenServicioDetalle = [
					'idOrdenServicio' => $idOrdenServicio,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.ordenServicioDetalle', $insertOrdenServicioDetalle);
				$idOrdenServicioDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertOrdenServicioDetalleSub[] = [
							'idOrdenServicioDetalle' => $idOrdenServicioDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'valorPorcentual' => ($value == COD_ASIGNACIONFAMILIAR) ? $post['asignacionFamiliar'] : NULL,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}

		$this->db->insert_batch('compras.ordenServicioDetalleSub', $insertOrdenServicioDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			if ($post['idDocumento'][$k] == '0') {
				$documento = [
					'nombre' => $v,
					'idArea' => $post['area'][$k],
					'idPersonal' => verificarEmpty($post['persona'][$k], 4),
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.documento', $documento);
				$idDocumento = $this->db->insert_id();
				// CORREO
				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : MAIL_COORDINADORA_COMPRAS);
				$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toOperaciones = [];
				foreach ($usuariosOperaciones as $usuario) {
					$toOperaciones[] = $usuario['email'];
				}
				$estadoEmail = $this->enviarCorreo(['data' => ['idDocumento' => $idDocumento], 'to' => $toOperaciones, 'cc' => ['luis.durand@visualimpact.com.pe']]);
				//
			} else {
				$idDocumento = $post['idDocumento'][$k];
			}

			$insertDocumento[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idDocumento' => $idDocumento,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersonal' => $post['persona'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->insert_batch('compras.ordenServicioDocumento', $insertDocumento);
		}

		$insertFechas = [];

		for ($i = 0; $i < intval($post['cantidadMeses']); $i++) {
			if (empty($post['fechaIni'])) {
				$fechaDescripcion = 'Mes ' . ($i + 1);
			} else {
				$fechaDescripcion = date('Y-m-d', strtotime("+$i months", strtotime($post['fechaIni'])));
			}

			$insertFechas[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
		}

		$this->db->insert_batch('compras.ordenServicioFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioActualizacionOrdenServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idOrdenServicio = $post['idOrdenServicio'];
		$dataParaVista = [];
		$dataParaVista['cargo'] = $this->mCotizacion->getAll_Cargos()->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();

		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();

		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();;
		$dataParaVista['idOrdenServicio'] = $idOrdenServicio;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['ordenServicio'] = $this->model->getOrdenServicio($idOrdenServicio);
		$dataParaVista['ordenServicioFecha'] = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->order_by('idOrdenServicioFecha')->get('compras.ordenServicioFecha')->result_array();
		$dataParaVista['ordenServicioDocumento'] = $this->model->obtenerDocumento($idOrdenServicio)->result_array();
		$ordenServicioDetalle = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->get('compras.ordenServicioDetalle')->result_array();
		$cargo = $this->model->getOrdenServicioCargo($idOrdenServicio)->result_array();

		foreach ($ordenServicioDetalle as $k => $v) {
			$ordenServicioDetalleSub = $this->db->where('estado', 1)->where('idOrdenServicioDetalle', $v['idOrdenServicioDetalle'])->get('compras.ordenServicioDetalleSub')->result_array();
			foreach ($ordenServicioDetalleSub as $k1 => $v1) {
				$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][$v1['idTipoPresupuestoDetalle']] = $v1;
			}
		}
		foreach ($cargo as $value) {
			$dataParaVista['ordenServicioCargo'][$value['idCargo']] = $value;
		}

		$detalle = $this->model->getOrdenServicioDetalle($idOrdenServicio)->result_array();
		foreach ($detalle as $value) {
			$dataParaVista['ordenServicioDetalle'][$value['idTipoPresupuesto']] = $value;
		}

		$provincia = [];
		foreach ($this->model->obtenerProvincia()->result_array() as $v) {
			$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		}
		$result['data']['provincia'] = $provincia;

		$distrito = [];
		foreach ($this->model->obtenerDistrito()->result_array() as $v) {
			$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		}
		$result['data']['distrito'] = $distrito;
		$result['data']['cargo'] = $dataParaVista['cargo'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar OrdenServicio';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroOrdenServicio", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idCliente = null;
		$idCuenta = null;
		$idCentroCosto = null;
		if ($post['chkUtilizarCliente']) {
			if (!is_numeric($post['clienteForm'])) {
				$insertCliente = [
					'nombre' => $post['clienteForm'],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.cliente', $insertCliente);
				$idCliente = $this->db->insert_id();
			} else {
				$idCliente = $post['clienteForm'];
			}
		} else {
			$idCuenta = $post['cuentaForm'];
			$idCentroCosto = $post['centroCostoForm'];
		}

		$updateOrdenServicio = [
			'idCliente' => $idCliente,
			'idCuenta' => $idCuenta,
			'idCentroCosto' => $idCentroCosto,
			'nombre' => $post['nombre'],
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
			'chkUtilizarCliente' => $post['chkUtilizarCliente'],
			'chkPresupuesto' => false
		];

		$idOrdenServicio = $post['idOrdenServicio'];
		$this->db->update('compras.ordenServicio', $updateOrdenServicio, ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->update('compras.ordenServicioHistorico', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);

		$insertOrdenServicioHistorico = $updateOrdenServicio;
		$insertOrdenServicioHistorico['idOrdenServicio'] = $idOrdenServicio;
		$insertOrdenServicioHistorico['idUsuario'] = $this->idUsuario;
		$insertOrdenServicioHistorico['fechaReg'] = getActualDateTime();
		unset($insertOrdenServicioHistorico['chkAprobado']);
		unset($insertOrdenServicioHistorico['chkPresupuesto']);
		$this->db->insert('compras.ordenServicioHistorico', $insertOrdenServicioHistorico);

		if (!isset($post['cargo'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Registro Erroneo!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe indicar al menos un cargo']);
			goto respuesta;
		}

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['cantidadCargo'] = checkAndConvertToArray($post['cantidadCargo']);
		$post['sueldoCargo'] = checkAndConvertToArray($post['sueldoCargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['idDocumento'] = isset($post['idDocumento']) ? checkAndConvertToArray($post['idDocumento']) : [];
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'sueldo' => $post['sueldoCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->update('compras.ordenServicioCargo', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->insert_batch('compras.ordenServicioCargo', $insertCargo);

		$insertOrdenServicioDetalle = [];
		$this->db->update('compras.ordenServicioDetalle', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);

		$insertOrdenServicioDetalleSub = [];
		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertOrdenServicioDetalle = [
					'idOrdenServicio' => $idOrdenServicio,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.ordenServicioDetalle', $insertOrdenServicioDetalle);
				$idOrdenServicioDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertOrdenServicioDetalleSub[] = [
							'idOrdenServicioDetalle' => $idOrdenServicioDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'valorPorcentual' => ($value == COD_ASIGNACIONFAMILIAR) ? $post['asignacionFamiliar'] : NULL,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}
		$this->db->insert_batch('compras.ordenServicioDetalleSub', $insertOrdenServicioDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			if ($post['idDocumento'][$k] == '0') {
				$documento = [
					'nombre' => $v,
					'idArea' => $post['area'][$k],
					'idPersonal' => verificarEmpty($post['persona'][$k], 4),
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.documento', $documento);
				$idDocumento = $this->db->insert_id();
				////////////////////////////////
				//
				// ENVIAR CORREO DE SOLICITUD.
				//
				// Para no enviar Correos en modo prueba.
				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : MAIL_COORDINADORA_COMPRAS);
				$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toOperaciones = [];
				foreach ($usuariosOperaciones as $usuario) {
					$toOperaciones[] = $usuario['email'];
				}
				$estadoEmail = $this->enviarCorreo(['data' => ['idDocumento' => $idDocumento], 'to' => $toOperaciones, 'cc' => ['luis.durand@visualimpact.com.pe']]);
				//
				////////////////////////////////
			} else {
				$idDocumento = $post['idDocumento'][$k];
			}

			$insertDocumento[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idDocumento' => $idDocumento,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersonal' => verificarEmpty($post['persona'][$k], 4),
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->update('compras.ordenServicioDocumento', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
			$this->db->insert_batch('compras.ordenServicioDocumento', $insertDocumento);
		}

		$insertFechas = [];
		$orden = 1;
		for ($i = 0; $i < intval($post['cantidadMeses']); $i++) {
			if (empty($post['fechaIni'])) {
				$fechaDescripcion = 'Mes ' . ($i + 1);
			} else {
				$fechaDescripcion = date('Y-m-d', strtotime("+$i months", strtotime($post['fechaIni'])));
			}

			$insertFechas[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
			$orden++;
		}

		$this->db->update('compras.ordenServicioFecha', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->insert_batch('compras.ordenServicioFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

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
			'smtp_user' => 'teamsystem@visualimpact.com.pe',
			'smtp_pass' => '#nVi=0sN0ti$',
			'mailtype' => 'html'
		);

		$this->load->library('email', $config);
		$this->email->clear(true);
		$this->email->set_newline("\r\n");

		$data = !empty($params['data']) ? $params['data'] : [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
		$this->email->to($params['to']);
		$this->email->cc($cc);

		$dataParaVista['link'] = base_url() . index_page() . 'OrdenServicio/adjuntarArchivo/' . $data['idDocumento'];

		$bcc = array(
			'eder.alata@visualimpact.com.pe',
			'luis.durand@visualimpact.com.pe'
		);
		$this->email->bcc($bcc);

		$this->email->subject('IMPACTBUSSINESS - DOCUMENTO SOLICITADO');
		$html = $dataParaVista['link'];
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => $dataParaVista['link']], true);
		$this->email->message($correo);

		$estadoEmail = $this->email->send();

		if (!$estadoEmail) {

			$mensaje = $this->email->print_debugger();
		}

		return $estadoEmail;
	}

	public function aprobarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$updateOrdenServicio = [
			'chkAprobado' => true,
			'fechaAprobado' => getActualDateTime(),
		];

		$this->db->update('compras.ordenServicio', $updateOrdenServicio, ['idOrdenServicio' => $post['idOrdenServicio']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioRegistroPresupuesto()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idOrdenServicio = $post['idOrdenServicio'];

		$dataParaVista = [];
		$dataParaVista['ordenServicio'] = $this->model->getOrdenServicio($idOrdenServicio);
		$dataParaVista['ordenServicioFecha'] = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->order_by('idOrdenServicioFecha')->get('compras.ordenServicioFecha')->result_array();
		$dataParaVista['ordenServicioCargo'] = $this->model->getOrdenServicioCargo($idOrdenServicio)->result_array();
		$dataParaVista['ordenServicioDetalle'] = $this->model->getOrdenServicioDetalle($idOrdenServicio)->result_array();
		foreach ($this->model->getOrdenServicioDetalleSub($idOrdenServicio)->result_array() as $k => $v) {
			$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][] = $v;
		}
		foreach ($this->db->where('idTipoPresupuesto', 1)->where('tipo', 4)->get('compras.tipoPresupuestoDetalle')->result_array() as $v) {
			$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];
		foreach ($this->db->select('tpd.*, it.costo, it.idProveedor')->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')->get('compras.tipoPresupuestoDetalle tpd')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['tipoPresupuestoDetalleMovilidad'] = $this->db->get_where('compras.tipoPresupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalleAlmacen'] = $this->db->get_where('compras.tipoPresupuestoDetalleAlmacen', ['estado' => 1])->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Presupuesto';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroPresupuesto", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['ordenServicioFecha'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['ordenServicioCargo'];
		echo json_encode($result);
	}

	public function registrarPresupuesto()
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

		// compras.presupuesto
		$insertPresupuesto = [
			'idOrdenServicio' => $idOrdenServicio,
			'sctr' => isset($post['pesupuestoSctr']) ? $post['pesupuestoSctr'] : NULL,
			'subtotal' => $post['presupuestoSubTotal'],
			'fee1' => $post['presupuestoFee1'],
			'totalFee1' => $post['presupuestoTotalFee1'],
			'fee2' => $post['presupuestoFee2'],
			'totalFee2' => $post['presupuestoTotalFee2'],
			'fee3' => $post['presupuestoFee3'],
			'totalFee3' => $post['presupuestoTotalFee3'],
			'total' => $post['presupuestoTotal'],
			'observacion' => $post['observacion'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime()
		];
		$this->db->insert('compras.presupuesto', $insertPresupuesto);
		$idPresupuesto = $this->db->insert_id();

		// compras.presupuestoCargo
		$insertPresupuestoCargo = [];
		foreach ($post['fechaList'] as $kf => $vf) {
			foreach ($post['cargoList'] as $vc) {
				$insertPresupuestoCargo[] = [
					'idPresupuesto' => $idPresupuesto,
					'fecha' => date_change_format_bd($vf),
					'idCargo' => $vc,
					'cantidad' => $post["cantidadCargoFecha[$vc][$kf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.presupuestoCargo', $insertPresupuestoCargo);

		// compras.presupuestoDetalle
		foreach ($post['idTipoPresupuesto'] as $vd) {
			$insertPresupuestoDetalle = [
				'idPresupuesto' => $idPresupuesto,
				'idTipoPresupuesto' => $vd,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.presupuestoDetalle', $insertPresupuestoDetalle);
			$idPresupuestoDetalle = $this->db->insert_id();

			// compras.presupuestoDetalleSueldoAdicional && compras.presupuestoDetalleSueldo
			if ($vd == COD_SUELDO) {
				// compras.presupuestoDetalleSueldo
				$insertPresupuestoDetalleSueldo = [];
				foreach ($post['cargoList'] as $vc) {
					$post["monto[$vc]"] = checkAndConvertToArray($post["monto[$vc]"]);
					foreach ($post['tpdS'] as $kds => $vds) {
						$insertPresupuestoDetalleSueldo[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'idCargo' => $vc,
							'porCL' => $post["clS"][$kds],
							'monto' => $post["monto[$vc]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
				$this->db->insert_batch('compras.presupuestoDetalleSueldo', $insertPresupuestoDetalleSueldo);

				// compras.presupuestoDetalleSueldoAdicional
				$insertPresupuestoDetalleSueldoAdicional = [];
				if (isset($post['cargoSueldoAdicional'])) {
					$post['cargoSueldoAdicional'] = checkAndConvertToArray($post['cargoSueldoAdicional']);
					$post['empleadoSueldoAdicional'] = checkAndConvertToArray($post['empleadoSueldoAdicional']);
					$post['montoSueldoAdicional'] = checkAndConvertToArray($post['montoSueldoAdicional']);
					$post['movilidadSueldoAdicional'] = checkAndConvertToArray($post['movilidadSueldoAdicional']);

					foreach ($post['cargoSueldoAdicional'] as $pdaK => $pda) {
						$insertPresupuestoDetalleSueldoAdicional[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idCargo' => $pda,
							'idEmpleado' => $post['empleadoSueldoAdicional'][$pdaK],
							'monto' => $post['montoSueldoAdicional'][$pdaK],
							'montoMovilidad' => $post['movilidadSueldoAdicional'][$pdaK],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.presupuestoDetalleSueldoAdicional', $insertPresupuestoDetalleSueldoAdicional);
				}
			} else if ($vd == COD_MOVILIDAD) {
				// compras.presupuestoDetalleMovilidad
				$insertPresupuestoDetalleMovilidad = [];
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
						$insertPresupuestoDetalleMovilidad[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
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
					$this->db->insert_batch('compras.presupuestoDetalleMovilidad', $insertPresupuestoDetalleMovilidad);
				}
			} else if ($vd == COD_ALMACEN) {
				// compras.presupuestoDetalleAlmacen
				$insertPresupuestoDetalleAlmacen = [];
				if (isset($post['almFrecuenciaOpc'])) {
					$post['almIdTPDA'] = checkAndConvertToArray($post['almIdTPDA']);
					$post['almFrecuenciaOpc'] = checkAndConvertToArray($post['almFrecuenciaOpc']);
					$post['almMonto'] = checkAndConvertToArray($post['almMonto']);

					foreach ($post['almIdTPDA'] as $kalm => $valm) {
						$insertPresupuestoDetalleAlmacen[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalleAlmacen' => $valm,
							'split' => $post['almFrecuenciaOpc'][$kalm],
							'monto' => $post['almMonto'][$kalm],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.presupuestoDetalleAlmacen', $insertPresupuestoDetalleAlmacen);
				}

				// compras.presupuestoDetalleAlmacenRecursos
				if (isset($post['almIdTPDAR'])) {
					$post['almIdTPDAR'] = checkAndConvertToArray($post['almIdTPDAR']);
					$insertPresupuestoDetalleAlmacenRecursos = [];
					foreach ($post['fechaList'] as $kf => $vf) {
						foreach ($post['almIdTPDAR'] as $vc) {
							$insertPresupuestoDetalleAlmacenRecursos[] = [
								'idPresupuestoDetalle' => $idPresupuestoDetalle,
								'idTipoPresupuestoDetalleAlmacen' => $vc,
								'fecha' => $vf,
								'cantidad' => $post["almRecursos[$vc][$kf]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
					}
					$this->db->insert_batch('compras.presupuestoDetalleAlmacenRecursos', $insertPresupuestoDetalleAlmacenRecursos);
				}
			} else { // compras.presupuestoDetalleSub
				$insertPresupuestoDetalleSub = [];
				if (isset($post["tipoPresupuestoDetalleSub[$vd]"])) {
					$post["tipoPresupuestoDetalleSub[$vd]"] = checkAndConvertToArray($post["tipoPresupuestoDetalleSub[$vd]"]);

					foreach ($post["tipoPresupuestoDetalleSub[$vd]"] as $kds => $vds) {
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

						$insertPresupuestoDetalleSub = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $idTipoPresupuestoDetalle,
							'split' => $post["splitDS[$vd]"][$kds],
							'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
							'cantidad' => $post["cantidadDS[$vd]"][$kds],
							'gap' => $post["gapDS[$vd]"][$kds],
							'monto' => $post["montoDS[$vd]"][$kds],
							'idFrecuencia' => $post["frecuenciaDS[$vd]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
						$this->db->insert('compras.presupuestoDetalleSub', $insertPresupuestoDetalleSub);
						$idPresupuestoDetalleSub = $this->db->insert_id();

						// compras.presupuestoDetalleSubCargo
						$insertPresupuestoDetalleSubCargo = [];
						foreach ($post['cargoList'] as $vc) {
							$insertPresupuestoDetalleSubCargo[] = [
								'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
								'idCargo' => $vc,
								'checked' => isset($post["chkDS[$vc][$vd][$kds]"]) ? true : false,
								'cantidad' => $post["subCantDS[$vc][$vd][$kds]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
						$this->db->insert_batch('compras.presupuestoDetalleSubCargo', $insertPresupuestoDetalleSubCargo);

						// compras.presupuestoDetalleSubElemento
						$insertPresupuestoDetalleSubElemento = [];
						if (isset($post["elementoPresupuesto[$vd][$kds]"])) {
							$post["elementoPresupuesto[$vd][$kds]"] = checkAndConvertToArray($post["elementoPresupuesto[$vd][$kds]"]);
							$post["cantidadElementos[$vd][$kds]"] = checkAndConvertToArray($post["cantidadElementos[$vd][$kds]"]);
							$post["montoElementos[$vd][$kds]"] = checkAndConvertToArray($post["montoElementos[$vd][$kds]"]);
							$post["subTotalElemento[$vd][$kds]"] = checkAndConvertToArray($post["subTotalElemento[$vd][$kds]"]);
							foreach ($post["elementoPresupuesto[$vd][$kds]"] as $elmK => $elmV) {
								$insertPresupuestoDetalleSubElemento[] = [
									'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
									'idItem' => $elmV,
									'cantidad' => $post["cantidadElementos[$vd][$kds]"][$elmK],
									'monto' => $post["montoElementos[$vd][$kds]"][$elmK],
									'subTotal' => $post["subTotalElemento[$vd][$kds]"][$elmK],
									'idUsuario' => $this->idUsuario,
									'fechaReg' => getActualDateTime()
								];
							}
						}
						if (!empty($insertPresupuestoDetalleSubElemento)) $this->db->insert_batch('compras.presupuestoDetalleSubElemento', $insertPresupuestoDetalleSubElemento);
					}
				}
			}
		}

		$this->db->update('compras.ordenServicio', ['chkPresupuesto' => true, 'fechaPresupuesto' => getActualDateTime()], ['idOrdenServicio' => $idOrdenServicio]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioEditarPresupuesto()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idPresupuesto = $post['idPresupuesto'];

		$dataParaVista = [];
		$dataParaVista['presupuesto'] = $this->db->get_where('compras.presupuesto', ['idPresupuesto' => $idPresupuesto])->row_array();
		$dataParaVista['valorPorcentual'] = $this->db->select('osds.valorPorcentual')->from('compras.ordenServicioDetalleSub osds')->join('compras.ordenServicioDetalle osd', 'osd.idOrdenServicioDetalle = osds.idOrdenServicioDetalle')->where('osd.estado', 1)->where('osds.idTipoPresupuestoDetalle', COD_ASIGNACIONFAMILIAR)->where('osd.idOrdenServicio', $dataParaVista['presupuesto']['idOrdenServicio'])->get()->row_array()['valorPorcentual'];
		$dataParaVista['idCuenta'] = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $dataParaVista['presupuesto']['idOrdenServicio']])->row_array()['idCuenta'];
		$dataParaVista['cargos'] = $this->mCotizacion->getAll_Cargos(['soloCargosOcupados' => true, 'idCuenta' => $dataParaVista['idCuenta']])->result_array();
		$dataParaVista['empleados'] = $this->model->getAll_RRHHEmpleados(['activo' => true])->result_array();
		$dataParaVista['tipoPresupuestoDetalleMovilidad'] = $this->db->get_where('compras.tipoPresupuestoDetalleMovilidad', ['estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalleAlmacen'] = $this->db->get_where('compras.tipoPresupuestoDetalleAlmacen', ['estado' => 1])->result_array();
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];

		$where = [];
		if (!empty($dataParaVista['idCuenta'])) {
			$where['idCuenta'] = $dataParaVista['idCuenta'];
		}
		// Para traer presupuestoDetalleAlmacen y presupuestoDetalleAlmacenRecursos
		$idPreDet_Almacen = $this->db->get_where('compras.presupuestoDetalle', ['idPresupuesto' => $idPresupuesto, 'idTipoPresupuesto' => COD_ALMACEN, 'estado' => 1])->row_array()['idPresupuestoDetalle'];

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
		$items = $this->model->getItemsCnPresupuesto($where)->result_array();
		foreach ($items as $item) {
			if (!isset($dataParaVista['item'][$item['idTipoPresupuestoDetalle']])) $dataParaVista['item'][$item['idTipoPresupuestoDetalle']] = [];
			$dataParaVista['items'][$item['idTipoPresupuestoDetalle']][] = $item;
		}
		$dataParaVista['itemPrecio'] = $this->model->itemPrecios();

		$presupuestoCargo = $this->model->getPresupuestoCargo($idPresupuesto)->result_array();
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
		$dataParaVista['presupuestoDetalle'] = $this->model->getPresupuestoDetalle($idPresupuesto)->result_array();

		$presupuestoDetalleSueldoAdicional = [];
		foreach ($dataParaVista['presupuestoDetalle'] as $k => $v) {
			$dataParaVista['presupuestoDetalleSub'][$v['idPresupuestoDetalle']] = $this->model->getPresupuestoDetalleSub($v['idPresupuestoDetalle'])->result_array();

			foreach ($dataParaVista['presupuestoDetalleSub'][$v['idPresupuestoDetalle']] as $presDetSub) {
				foreach ($this->db->get_where('compras.presupuestoDetalleSubCargo', ['idPresupuestoDetalleSub' => $presDetSub['idPresupuestoDetalleSub']])->result_array() as $prDetSbCar) {
					$dataParaVista['presupuestoDetalleSubCargo'][$presDetSub['idPresupuestoDetalleSub']][$prDetSbCar['idCargo']] = $prDetSbCar;
				}
				$dataParaVista['presupuestoDetalleSubElemento'][$presDetSub['idPresupuestoDetalleSub']] = [];
				foreach ($this->db->get_where('compras.presupuestoDetalleSubElemento', ['idPresupuestoDetalleSub' => $presDetSub['idPresupuestoDetalleSub']])->result_array() as $prDetSbElm) {
					$dataParaVista['presupuestoDetalleSubElemento'][$presDetSub['idPresupuestoDetalleSub']][] = $prDetSbElm;
				}
			}

			$presupuestoDetalleSueldo = $this->model->getPresupuestoDetalleSueldo($v['idPresupuestoDetalle'])->result_array();
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
		$result['msg']['title'] = 'Editar Presupuesto';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioEditarPresupuesto", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['fechaDelPre'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['cargoDelPre'];
		echo json_encode($result);
	}

	public function editarPresupuesto()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idOrdenServicio = $post['idOrdenServicio'];
		$idPresupuesto = $post['idPresupuesto'];

		$post['fechaList'] = checkAndConvertToArray($post['fechaList']);
		$post['cargoList'] = checkAndConvertToArray($post['cargoList']);
		$post['idTipoPresupuesto'] = checkAndConvertToArray($post['idTipoPresupuesto']);
		$post['tpdS'] = checkAndConvertToArray($post['tpdS']);
		$post['clS'] = checkAndConvertToArray($post['clS']);

		$this->model->anularPresupuesto($idPresupuesto);

		// compras.presupuesto
		$updatePresupuesto = [
			'idOrdenServicio' => $idOrdenServicio,
			'sctr' => isset($post['pesupuestoSctr']) ? $post['pesupuestoSctr'] : NULL,
			'subtotal' => $post['presupuestoSubTotal'],
			'fee1' => $post['presupuestoFee1'],
			'totalFee1' => $post['presupuestoTotalFee1'],
			'fee2' => $post['presupuestoFee2'],
			'totalFee2' => $post['presupuestoTotalFee2'],
			'fee3' => $post['presupuestoFee3'],
			'totalFee3' => $post['presupuestoTotalFee3'],
			'total' => $post['presupuestoTotal'],
			'observacion' => $post['observacion'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime()
		];
		$updatePresupuesto['estado'] = 1;
		$this->db->update('compras.presupuesto', $updatePresupuesto, ['idPresupuesto' => $idPresupuesto]);

		$updatePresupuesto['idPresupuesto'] = $idPresupuesto;

		$this->db->insert('compras.presupuestoHistorico', $updatePresupuesto);

		// compras.presupuestoCargo
		$insertPresupuestoCargo = [];
		foreach ($post['fechaList'] as $kf => $vf) {
			foreach ($post['cargoList'] as $vc) {
				$insertPresupuestoCargo[] = [
					'idPresupuesto' => $idPresupuesto,
					'fecha' => date_change_format_bd($vf),
					'idCargo' => $vc,
					'cantidad' => $post["cantidadCargoFecha[$vc][$kf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.presupuestoCargo', $insertPresupuestoCargo);

		// compras.presupuestoDetalle
		foreach ($post['idTipoPresupuesto'] as $vd) {
			$insertPresupuestoDetalle = [
				'idPresupuesto' => $idPresupuesto,
				'idTipoPresupuesto' => $vd,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.presupuestoDetalle', $insertPresupuestoDetalle);
			$idPresupuestoDetalle = $this->db->insert_id();

			// compras.presupuestoDetalleSueldo && compras.presupuestoDetalleSueldoAdicional
			if ($vd == COD_SUELDO) {
				// compras.presupuestoDetalleSueldo
				$insertPresupuestoDetalleSueldo = [];
				foreach ($post['cargoList'] as $vc) {
					$post["monto[$vc]"] = checkAndConvertToArray($post["monto[$vc]"]);
					foreach ($post['tpdS'] as $kds => $vds) {
						$insertPresupuestoDetalleSueldo[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'idCargo' => $vc,
							'porCL' => $post["clS"][$kds],
							'monto' => $post["monto[$vc]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
				$this->db->insert_batch('compras.presupuestoDetalleSueldo', $insertPresupuestoDetalleSueldo);

				// compras.presupuestoDetalleSueldoAdicional
				$insertPresupuestoDetalleSueldoAdicional = [];
				if (isset($post['cargoSueldoAdicional'])) {
					$post['cargoSueldoAdicional'] = checkAndConvertToArray($post['cargoSueldoAdicional']);
					$post['empleadoSueldoAdicional'] = checkAndConvertToArray($post['empleadoSueldoAdicional']);
					$post['montoSueldoAdicional'] = checkAndConvertToArray($post['montoSueldoAdicional']);
					$post['movilidadSueldoAdicional'] = checkAndConvertToArray($post['movilidadSueldoAdicional']);

					foreach ($post['cargoSueldoAdicional'] as $pdaK => $pda) {
						$insertPresupuestoDetalleSueldoAdicional[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idCargo' => $pda,
							'idEmpleado' => $post['empleadoSueldoAdicional'][$pdaK],
							'monto' => $post['montoSueldoAdicional'][$pdaK],
							'montoMovilidad' => $post['movilidadSueldoAdicional'][$pdaK],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.presupuestoDetalleSueldoAdicional', $insertPresupuestoDetalleSueldoAdicional);
				}
			} else if ($vd == COD_MOVILIDAD) {
				// compras.presupuestoDetalleMovilidad
				$insertPresupuestoDetalleMovilidad = [];
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
						$insertPresupuestoDetalleMovilidad[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
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
					$this->db->insert_batch('compras.presupuestoDetalleMovilidad', $insertPresupuestoDetalleMovilidad);
				}
			} else if ($vd == COD_ALMACEN) {
				// compras.presupuestoDetalleAlmacen
				$insertPresupuestoDetalleAlmacen = [];
				if (isset($post['almFrecuenciaOpc'])) {
					$post['almIdTPDA'] = checkAndConvertToArray($post['almIdTPDA']);
					$post['almFrecuenciaOpc'] = checkAndConvertToArray($post['almFrecuenciaOpc']);
					$post['almMonto'] = checkAndConvertToArray($post['almMonto']);

					foreach ($post['almIdTPDA'] as $kalm => $valm) {
						$insertPresupuestoDetalleAlmacen[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalleAlmacen' => $valm,
							'split' => $post['almFrecuenciaOpc'][$kalm],
							'monto' => $post['almMonto'][$kalm],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
					$this->db->insert_batch('compras.presupuestoDetalleAlmacen', $insertPresupuestoDetalleAlmacen);
				}

				// compras.presupuestoDetalleAlmacenRecursos
				if (isset($post['almIdTPDAR'])) {
					$post['almIdTPDAR'] = checkAndConvertToArray($post['almIdTPDAR']);
					$insertPresupuestoDetalleAlmacenRecursos = [];
					foreach ($post['fechaList'] as $kf => $vf) {
						foreach ($post['almIdTPDAR'] as $vc) {
							$insertPresupuestoDetalleAlmacenRecursos[] = [
								'idPresupuestoDetalle' => $idPresupuestoDetalle,
								'idTipoPresupuestoDetalleAlmacen' => $vc,
								'fecha' => $vf,
								'cantidad' => $post["almRecursos[$vc][$kf]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
					}
					$this->db->insert_batch('compras.presupuestoDetalleAlmacenRecursos', $insertPresupuestoDetalleAlmacenRecursos);
				}
			} else { // compras.presupuestoDetalleSub
				$insertPresupuestoDetalleSub = [];
				if (isset($post["tipoPresupuestoDetalleSub[$vd]"])) {
					$post["tipoPresupuestoDetalleSub[$vd]"] = checkAndConvertToArray($post["tipoPresupuestoDetalleSub[$vd]"]);
					foreach ($post["tipoPresupuestoDetalleSub[$vd]"] as $kds => $vds) {
						$post["splitDS[$vd]"] = checkAndConvertToArray($post["splitDS[$vd]"]);
						$post["precioUnitarioDS[$vd]"] = checkAndConvertToArray($post["precioUnitarioDS[$vd]"]);
						$post["cantidadDS[$vd]"] = checkAndConvertToArray($post["cantidadDS[$vd]"]);
						$post["gapDS[$vd]"] = checkAndConvertToArray($post["gapDS[$vd]"]);
						$post["montoDS[$vd]"] = checkAndConvertToArray($post["montoDS[$vd]"]);
						$post["frecuenciaDS[$vd]"] = checkAndConvertToArray($post["frecuenciaDS[$vd]"]);

						$insertPresupuestoDetalleSub = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'split' => $post["splitDS[$vd]"][$kds],
							'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
							'cantidad' => $post["cantidadDS[$vd]"][$kds],
							'gap' => $post["gapDS[$vd]"][$kds],
							'monto' => $post["montoDS[$vd]"][$kds],
							'idFrecuencia' => $post["frecuenciaDS[$vd]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
						$this->db->insert('compras.presupuestoDetalleSub', $insertPresupuestoDetalleSub);
						$idPresupuestoDetalleSub = $this->db->insert_id();

						// compras.presupuestoDetalleSubCargo
						$insertPresupuestoDetalleSubCargo = [];
						foreach ($post['cargoList'] as $vc) {
							$insertPresupuestoDetalleSubCargo[] = [
								'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
								'idCargo' => $vc,
								'checked' => isset($post["chkDS[$vc][$vd][$kds]"]) ? true : false,
								'cantidad' => $post["subCantDS[$vc][$vd][$kds]"],
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
						$this->db->insert_batch('compras.presupuestoDetalleSubCargo', $insertPresupuestoDetalleSubCargo);

						// compras.presupuestoDetalleSubElemento
						$insertPresupuestoDetalleSubElemento = [];
						if (isset($post["elementoPresupuesto[$vd][$kds]"])) {
							$post["elementoPresupuesto[$vd][$kds]"] = checkAndConvertToArray($post["elementoPresupuesto[$vd][$kds]"]);
							$post["cantidadElementos[$vd][$kds]"] = checkAndConvertToArray($post["cantidadElementos[$vd][$kds]"]);
							$post["montoElementos[$vd][$kds]"] = checkAndConvertToArray($post["montoElementos[$vd][$kds]"]);
							$post["subTotalElemento[$vd][$kds]"] = checkAndConvertToArray($post["subTotalElemento[$vd][$kds]"]);
							foreach ($post["elementoPresupuesto[$vd][$kds]"] as $elmK => $elmV) {
								$insertPresupuestoDetalleSubElemento[] = [
									'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
									'idItem' => $elmV,
									'cantidad' => $post["cantidadElementos[$vd][$kds]"][$elmK],
									'monto' => $post["montoElementos[$vd][$kds]"][$elmK],
									'subTotal' => $post["subTotalElemento[$vd][$kds]"][$elmK],
									'idUsuario' => $this->idUsuario,
									'fechaReg' => getActualDateTime()
								];
							}
						}
						if (!empty($insertPresupuestoDetalleSubElemento)) $this->db->insert_batch('compras.presupuestoDetalleSubElemento', $insertPresupuestoDetalleSubElemento);
					}
				}
			}
		}

		$this->db->update('compras.ordenServicio', ['chkPresupuesto' => true, 'fechaPresupuesto' => getActualDateTime()], ['idOrdenServicio' => $idOrdenServicio]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function guardarDocumento()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$archivo = [
			'base64' => $post['file-item'],
			'name' => $post['file-name'],
			'type' => $post['file-type'],
			'carpeta' => 'documentos',
			'nombreUnico' => 'file_' . str_pad($post['idDocumento'], 6, "0", STR_PAD_LEFT) . '_' . str_replace(':', '', $this->hora),
		];

		$archivoName = $this->saveFileWasabi($archivo);

		$tipoArchivo = explode('/', $archivo['type']);
		$updateDocumento = [
			'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
			'extension' => FILES_WASABI[$tipoArchivo[1]],
			'nombre_inicial' => $archivo['name'],
			'nombre_archivo' => $archivoName,
			'nombre_unico' => $archivo['nombreUnico'],
			'fechaCarga' => getActualDateTime()
		];
		$this->db->update('compras.documento', $updateDocumento, ['idDocumento' => $post['idDocumento']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function generarRowParaPresupuesto_1()
	{

		$post = $this->input->post();

		$dataParaVista['tipoPresupuestoDetalle'] = $this->db->order_by('nombre')->get_where('compras.tipoPresupuestoDetalle', ['idTipoPresupuesto' => $post['detalle']])->result_array();
		$dataParaVista['cargos'] = $post['cargos'];

		$dataParaVista['idTipoPresupuesto'] = $post['detalle'];
		$dataParaVista['numeroDeFila'] = $post['contador'];

		$dataParaVista['totalCargo'] = 0;
		foreach ($post['cargos'] as $cargo) {
			$dataParaVista['totalCargo'] += intval($cargo['cantidad']);
		}

		echo $this->load->view('modulos/OrdenServicio/Elements/rowParaPresupuesto_1', $dataParaVista, true);
	}

	public function generarRowParaPresupuesto_2()
	{

		$post = $this->input->post();

		$dataParaVista['idTipoPresupuesto'] = $post['detalle'];
		$dataParaVista['numeroDeFila'] = $post['contador'];
		$dataParaVista['fechas'] = $post['fechas'];

		echo $this->load->view('modulos/OrdenServicio/Elements/rowParaPresupuesto_2', $dataParaVista, true);
	}

	public function generarRowParaPresupuesto_3()
	{
		$post = $this->input->post();

		$where = ['idTipoPresupuestoDetalle' => $post['idTipoPresupuestoDetalle']];
		if (!empty($post['idCuenta'])) {
			$where['idCuenta'] = $post['idCuenta'];
		}

		// $dataParaVista['items'] = $this->db->get_where('compras.item', $where)->result_array();
		$dataParaVista['items'] = $this->model->getItemsCnPresupuesto($where)->result_array();
		$dataParaVista['itemPrecio'] = $this->model->itemPrecios();
		$dataParaVista['idTipoPresupuesto'] = $post['idTipoPresupuesto'];
		$dataParaVista['nroFila'] = $post['nroFila'];

		$rpta = '';
		if (!empty($dataParaVista['items'])) {
			$rpta = $this->load->view('modulos/OrdenServicio/Elements/rowParaPresupuesto_3', $dataParaVista, true);
		}
		echo $rpta;
	}
	public function generarRowAdicionalSueldo()
	{
		$post = $this->input->post();

		$where = ['soloCargosOcupados' => true];
		if (!empty($post['idCuenta'])) $where['idCuenta'] = $post['idCuenta'];

		$dataParaVista['cargos'] = $this->mCotizacion->getAll_Cargos($where)->result_array();

		$dataParaVista['empleados'] = $this->model->getAll_RRHHEmpleados(['activo' => true])->result_array();

		echo $this->load->view('modulos/OrdenServicio/Elements/rowAdicionalSueldo', $dataParaVista, true);
	}
}
