<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sincerado extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Sincerado', 'model');
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
			'assets/custom/js/sincerado'
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
	
		$dataParaVista = [];

		$dataParaVista['datoSincerado'] = $this->mCotizacion->obtenerFechaSinceradoDetalle(['idPresupuestoValido' => $post['idPresupuestoValido'] , 'fechaSincerado' => $post['fechaSincerado']])['query']->result_array();
		//echo $this->db->last_query(); exit();
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['solicitantes'] = $this->db->get_where('compras.solicitante', ['estado' => 1])->result_array();
		$dataParaVista['cotizacionPrioridad'] = $this->db->get_where('compras.cotizacionPrioridad', ['estado' => 1])->result_array();
		$dataParaVista['tipoServicioCotizacion'] = $this->db->get_where('compras.tipoServicioCotizacion', ['estado' => 1])->result_array();
		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$datos = $this->model->obtenerInformacionDelPresupuestoValido(['idPresupuestoValido' => $post['idPresupuestoValido']])->result_array();
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
		// $dataParaVista['itemTipo'] = $this->mCotizacion->obtenerItemTipo()['query']->result_array();
		// $dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formularioRegistroSincerado", $dataParaVista, true);

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
				'idCotizacionGeneral' => $idSincerado ,
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
