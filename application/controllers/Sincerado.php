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
		$data = []; // ← ← ← Aqui ingresa el get con la data que va en la tabla
		$html = getMensajeGestion('noRegistros');
		if (!empty($data)) {
			foreach ($data as $value) {
				$dataParaVista['sincerado'][$value['idSincerado']] = $value;
				$cargo = $this->model->getSinceradoCargo($value['idSincerado'])->result_array();
				$documento = $this->db->where('idSincerado', $value['idSincerado'])->where('estado', 1)->get('compras.sinceradoDocumento')->result_array();
				if (!empty($cargo)) {
					$temp = [];
					foreach ($cargo as $cargoData) {
						$temp[] = $cargoData['cargo'];
					}
					$dataParaVista['sincerado'][$value['idSincerado']]['cargo'] = implode(', ', $temp);
				} else {
					$dataParaVista['sincerado'][$value['idSincerado']]['cargo'] = '';
				}
				if (!empty($documento)) {
					$temp = [];
					foreach ($documento as $documentoData) {
						$temp[] = $documentoData['documento'];
					}
					$dataParaVista['sincerado'][$value['idSincerado']]['documento'] = implode(', ', $temp);
				} else {
					$dataParaVista['sincerado'][$value['idSincerado']]['documento'] = '';
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

	public function formularioFechasSincerado()
	{
		$result = $this->result;
		$post = $this->input->post();
		//var_dump($post);
		$dataParaVista = [];
		$dataParaVista['idPresupuestoValido'] = $post['idPresupuestoValido'];
		$dataParaVista['fechaSincerado'] = $this->mCotizacion->obtenerFechaSincerado(['idPresupuestoValido' => $post['idPresupuestoValido']])['query']->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Fechas Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formulariFechasSincerado", $dataParaVista, true);

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
}
