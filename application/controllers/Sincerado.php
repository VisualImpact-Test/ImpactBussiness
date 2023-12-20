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
				$cargo = $this->model->getSinceradoCargo($value['idSincerado'])->result_array();
				if (!empty($cargo)) {
					$temp = [];
					foreach ($cargo as $cargoData) {
						$temp[] = $cargoData['cargo'];
					}
					$dataParaVista['sincerado'][$value['idSincerado']]['cargo'] = implode(', ', $temp);
				} else {
					$dataParaVista['sincerado'][$value['idSincerado']]['cargo'] = '';
				}
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
		$dataParaVista['sincerado'] = $this->db->get_where('compras.sincerado', ['idSincerado' => $post['idSincerado']])->row_array();
		$idMoneda = $this->db->get_where('compras.ordenServicio', ['idOrdenServicio' => $dataParaVista['sincerado']['idOrdenServicio']])->row_array()['idMoneda'];
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda', ['idMoneda' => $idMoneda])->row_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Cargar GR';
		$html = $this->load->view("modulos/Sincerado/formularioCargarGr", $dataParaVista, true);
		$result['data']['html'] = $html;

		echo json_encode($result);
	}

	public function guardarGrSincerado()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

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
				'descripcion' => $v,
				'fecha' => $post['fecha'][$k],
				'porcentaje' => $post['porcentaje'][$k],
				'monto' => $post['monto'][$k],
				'porcentajeSincerado' => $post['porcentajeSincerado'][$k],
				'presupuestoSincerado' => $post['presupuestoSincerado'][$k],
				'diferenciaSincerado' => $post['diferenciaSincerado'][$k]
			];
		}
		if (empty($insertData)) {
			$result = mensajeList($result, 'NoData');
			goto respuesta;
		}
		
		$success = $this->db->insert_batch('compras.sinceradoGr', $insertData);
		if (!$success) {
			$result = mensajeList($result, 'registroErroneo');
			goto respuesta;
		}

		$result = mensajeList($result, 'registroExitoso');
		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
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
	// TODO → Borrar al terminar
	public function registrarSincerado_borrar()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$insertSincerado = [
			'nombre' => $post['titulo'],
			'fechaDeadline' => $post['deadline'],
			'fechaRequerida' => $post['fechaRequerida'],
			'diasValidez' => $post['validez'],
			'idSolicitante' => $post['solicitante'],
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idPrioridad' => $post['prioridadForm'],
			'motivo' => $post['motivoForm'],
			'idTipoServicioCotizacion' => $post['tipoServicio'],
			'comentario' => $post['comentarioForm']
		];
		$this->db->insert('compras.cotizacionGeneral', $insertSincerado);
		$idSincerado = $this->db->insert_id();
		$insertDetalle = [];
		foreach ($post['items'] as $key => $value) {
			$insertDetalleSincerado[] = [
				'idCotizacionGeneral' => $idSincerado,
				'descripcionTipoPresupuestoDetalle' => $value,
				'monto' => $post['monto'][$key]
			];
		}

		$this->db->insert_batch('compras.cotizacionGeneralDetalle', $insertDetalleSincerado);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
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

		// compras.sincerado
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
				break;
			}
			$insertSinceradoDetalle = [
				'idSincerado' => $idSincerado,
				'idTipoPresupuesto' => $vd,
				'montoOriginal' => $montoOriginal,
				'montoSincerado' => $montoSincerado,
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
}
