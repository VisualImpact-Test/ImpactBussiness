<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PagosGenerados extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_PagosGenerados', 'model');
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
			'assets/custom/js/Finanzas/pagosGenerados'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Pagos Generados';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Finanzas/PagosGenerados/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];
        $dataParaVista['pagosGenerados'] = $this->model->ObtenerDatosPagosGenerados($post)['query']->result_array();
		// echo $this->db->last_query();exit();
        $html = $this->load->view("modulos/Finanzas/PagosGenerados/reporte", $dataParaVista, true);
        $result['result'] = 1;
		$result['data']['views']['idPagosGenerados']['datatable'] = 'tb-pagosGenerados';
		$result['data']['views']['idPagosGenerados']['html'] = $html;
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
	
	public function formularioRegistrarPago()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['pagosGenerados'] = $this->model->ObtenerDatosPagosGenerados($post)['query']->result_array();
		//echo $this->db->last_query();exit();
		$dataParaVista['tipoComprobante'] = $this->model->ObtenerDatosTipoComprobante($post)['query']->result_array();
		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['facturas'] = $this->model->ObtenerDatosFacturas($post)['query']->result_array();
		
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Pagos';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/PagosGenerados/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioRegistrarFactura()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['pagosGenerados'] = $this->model->ObtenerDatosPagosGenerados($post)['query']->result_array();
		//echo $this->db->last_query();exit();
		$dataParaVista['tipoComprobante'] = $this->model->ObtenerDatosTipoComprobante($post)['query']->result_array();
		$dataParaVista['facturas'] = $this->model->ObtenerDatosFacturas($post)['query']->result_array();
		$dataParaVista['moneda'] = $this->model->obtenertipoMoneda($post)['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Facturas';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/PagosGenerados/formularioRegistroFactura", $dataParaVista, true);

		echo json_encode($result);
	}
	
	public function addNewFactura()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['tipoComprobante'] = $this->model->ObtenerDatosTipoComprobante($post)['query']->result_array();
		$dataParaVista['moneda'] = $this->model->obtenertipoMoneda($post)['query']->result_array();
	
		$result['result'] = 1;
		$result['data']['html'] = $this->load->view("modulos/Finanzas/PagosGenerados/addNewFacturaForm", $dataParaVista, true);

		echo json_encode($result);
	}

	
	
	public function formRegistrarFactura()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$this->db->trans_begin();
		$post['fechaEmision'] = checkAndConvertToArray($post['fechaEmision']);
		$post['fechaRecepcion'] = checkAndConvertToArray($post['fechaRecepcion']);
		$post['fechaVencimiento'] = checkAndConvertToArray($post['fechaVencimiento']);
		$post['tipoComprobante'] = checkAndConvertToArray($post['tipoComprobante']);
		$post['numeroComprobante'] = checkAndConvertToArray($post['numeroComprobante']);
		$post['monto'] = checkAndConvertToArray($post['monto']);
		$post['moneda'] = checkAndConvertToArray($post['moneda']);
		$post['cuentaPrincipalFile-item'] = checkAndConvertToArray($post['cuentaPrincipalFile-item']);
		$post['cuentaPrincipalFile-name'] = checkAndConvertToArray($post['cuentaPrincipalFile-name']);
		$post['cuentaPrincipalFile-type'] = checkAndConvertToArray($post['cuentaPrincipalFile-type']);
	
		foreach ($post['monto'] as $k => $v) {
			$archivo = [
				'base64' => $post['cuentaPrincipalFile-item'][$k],
				'name' => $post['cuentaPrincipalFile-name'][$k],
				'type' => $post['cuentaPrincipalFile-type'][$k],
				'carpeta' => 'FinanzasComprobantes',
				'nombreUnico' => uniqid()
			];
			$archivoName = $this->saveFileWasabi($archivo);
			$tipoArchivo = explode('/', $archivo['type']);

			$insertFactura[] = [
				'idProveedorServicioGenerado' => $post['idProveedorServicioGenerado'],
				'fechaEmision' => $post['fechaEmision'][$k],
				'fechaRecepcion' => $post['fechaRecepcion'][$k],
				'fechaVencimiento' => $post['fechaVencimiento'][$k],
				'tipoComprobante' => $post['tipoComprobante'][$k],
				'numeroComprobante' => $post['numeroComprobante'][$k],
				'monto' => $post['monto'][$k],
				'idMoneda' => $post['moneda'][$k],
				'nombre_inicial' => $archivo['name'],
				'nombre_archivo' => $archivoName,
				'extension' => FILES_WASABI[$tipoArchivo[1]],
				'usuarioRegistro' => $this->idUsuario,
			];
			//echo var_dump($insertFactura); exit;
			
		}
		$this->db->insert_batch('finanzas.proveedorServicioPagoComprobante', $insertFactura);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 2;
			$result['msg']['title'] = 'Error al Registrar';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'Pago Registrado';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}
		echo json_encode($result);
	}
	public function registrarPagoGenerado()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$this->db->trans_begin();
		$flagdetraccion = ($post['porcentajeDetraccion'] > 0) ? '1' : '0' ;
		$update = [
			'fechaPagoComprobante' => $post['fechaPagoComprobante'],
			'idComprobante' => $post['tipoComprobante'],
			'numeroComprobante' => $post['numeroComprobante'],
			'monto' => $post['monto'],
			'porcentajeDetraccion' => $post['porcentajeDetraccion'],
			'montoDetraccion' => $post['montoDetraccion'],
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['centroCostoForm'],
			'flagDetraccion' => $flagdetraccion ,
			'idEstadoPago' => '2',
		];
		$this->db->update('finanzas.proveedorServicioPagoGenerado', $update, ['idProveedorServicioGenerado' => $post['idProveedorServicioGenerado']]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 2;
			$result['msg']['title'] = 'Error al Registrar';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'Pago Registrado';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}
		echo json_encode($result);
	}

	public function formularioRegistrarNotaCredito()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];
		$dataParaVista['pagosGenerados'] = $this->model->ObtenerDatosPagosGenerados($post)['query']->result_array();
		//echo $this->db->last_query();exit();
		$dataParaVista['tipoComprobante'] = $this->model->ObtenerDatosTipoComprobante($post)['query']->result_array();
		$dataParaVista['facturas'] = $this->model->ObtenerDatosFacturas($post)['query']->result_array();
		$dataParaVista['tipoNota'] = $this->model->ObtenerDatosTipoNota($post)['query']->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Notas Credito';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/PagosGenerados/formularioRegistroNotaCredito", $dataParaVista, true);

		echo json_encode($result);
	}

	public function guardarNotaCredito()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$this->db->trans_begin();

		$archivo = [
			'base64' => $post['item'],
			'name' => $post['name'],
			'type' => $post['type'],
			'carpeta' => 'FinanzasComprobantes',
			'nombreUnico' => uniqid()
		];
		$archivoName = $this->saveFileWasabi($archivo);
		$tipoArchivo = explode('/', $archivo['type']);

		$insertFactura[] = [
			'idServicioPagoComprobante' => $post['idServicioPagoComprobante'],
			'montoComprobante' => '0',
			'idTipoNota' => $post['tipoNota'],
			'montoNota' => $post['monto'],
			'fechaRecepcion' => $post['fechaRecepcion'],
			'fechaEmision' => $post['fechaEmision'],
			'numNota' => $post['numNota'],
			'nombre_inicial' => $archivo['name'],
			'nombre_archivo' => $archivoName,
			'extension' => FILES_WASABI[$tipoArchivo[1]],
			'usuarioRegistro' => $this->idUsuario,
		];

		$this->db->insert_batch('finanzas.proveedorServicioPagoNotaCredito', $insertFactura);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 0;
			$result['msg']['title'] = 'Error al Registrar';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'Pago Registrado';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}
		echo json_encode($result);
	
	}

	public function RegistrarPagoNew()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$this->db->trans_begin();
		$archivo = [
			'base64' => $post['item'],
			'name' => $post['name'],
			'type' => $post['type'],
			'carpeta' => 'FinanzasComprobantes',
			'nombreUnico' => uniqid()
		];
		$archivoName = $this->saveFileWasabi($archivo);
		$tipoArchivo = explode('/', $archivo['type']);
		$insertFactura[] = [
			'idServicioPagoComprobante' => $post['idServicioPagoComprobante'],
			'fechaPagoComprobante' => $post['fechaPagoComprobante'],
			'idTipoComprobante' => $post['tipoComprobante'],
			'numeroComprobante' => $post['numeroComprobante'],
			'montoPagado' => $post['monto'],
			'idCentroCosto' => $post['centro'],
			'idCuenta' => $post['cuenta'],
			
			'porcentajeDetraccion' => $post['porcentajeDetraccion'],
			'montoDetraccion' => $post['montoDetraccion'],
			'idEstadoPago' =>'2',
			'nombre_inicial' => $archivo['name'],
			'nombre_archivo' => $archivoName,
			'extension' => FILES_WASABI[$tipoArchivo[1]],
			'usuarioRegistro' => $this->idUsuario,
		];

		$this->db->insert_batch('finanzas.proveedorServicioPagoEfectuados', $insertFactura);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['result'] = 2;
			$result['msg']['title'] = 'Error al Registrar';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$this->db->trans_commit();
			$result['result'] = 1;
			$result['msg']['title'] = 'Pago Registrado';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}
		echo json_encode($result);


	}
}
