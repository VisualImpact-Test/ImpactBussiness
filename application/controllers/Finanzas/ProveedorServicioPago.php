<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorServicioPago extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Finanzas', 'model');
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
			'assets/custom/js/Finanzas/proveedorServicio'
		);

		$config['data']['proveedorServicio'] = $this->db->get_where('finanzas.proveedorServicio')->result_array();
		$config['data']['estado'] = $this->db->get_where('compras.proveedorEstado', "idProveedorEstado = 2 OR idProveedorEstado = 3")->result_array();
		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Provisionar Pagos';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Finanzas/ProveedorServicioPago/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$dataParaVista = [];
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista['proveedorServicio'] = $this->model->obtenerProveedorServicio(['idProveedorServicio' => $post['idProveedorServicio'], 'estado' => $post['estadoProveedorServicioPago']])['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Finanzas/ProveedorServicioPago/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idProveedorServicio']['datatable'] = 'tb-proveedorServicio';
		$result['data']['views']['idProveedorServicio']['html'] = $html;
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

	public function formularioRegistroProveedorServicioPago()
	{
		$result = $this->result;

		$dataParaVista = [];
		$dataParaVista['proveedorServicio'] = $this->db->get_where('finanzas.proveedorServicio', array('idProveedorEstado' => '2'))->result_array();
		
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda', array('estado' => '1'))->result_array();
		$dataParaVista['frecuenciaPago'] = $this->db->get_where('finanzas.frecuenciaPagoProveedorServicioPago', array('estado' => '1'))->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Pago Proveedor Servicio';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorServicioPago/formularioRegistroPago", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioActualizacionProveedorServicioPago()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedorServicioPago'] = $this->model->obtenerProveedorServicio(['idProveedorServicioPago' => $post['idProveedorServicioPago']])['query']->result_array();
		$dataParaVista['proveedorServicio'] = $this->model->seleccionarProveedorServicio(['idProveedorServicio' => $dataParaVista['proveedorServicioPago'][0]['idProveedorServicio']])['query']->result_array();
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda', array('estado' => '1'))->result_array();
		$dataParaVista['frecuenciaPago'] = $this->db->get_where('finanzas.frecuenciaPagoProveedorServicioPago', array('estado' => '1'))->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Pago Proveedor Servicio';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorServicioPago/formularioActualizacionPago", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarProveedorServicioPago()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		
		$data = [];
		$data['insert'] = [
			'idProveedorServicio' => $post['proveedorServicio'],
			'flagFijo' => isset($post["chkMontoFijo"]) ? 1 : 0,
			'monto' => isset($post["chkMontoFijo"]) ? $post['monto'] : null,
			'diaPago' => $post['diaPago'],
			'frecuenciaPago' => $post['frecuenciaPago'],
			'fechaInicio' => $post['fechaInicio'],
			'fechaTermino' => verificarEmpty($post['fechaTermino'], 4),
			'idMoneda' => $post['moneda'],
			'estado' => 2,
			'descripcionServicio' => verificarEmpty($post['informacionAdicional'], 4)
		];

		$data['tabla'] = 'finanzas.proveedorServicioPago';
		$insert = $this->model->insertarProveedorServicioPago($data);

		if (!$insert) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
			goto respuesta;
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function actualizarProveedorServicioPago()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['update'] = [
			'idProveedorServicio' => $post['proveedorServicio'],
			'flagFijo' => isset($post["chkMontoFijo"]) ? 1 : 0,
			'monto' => isset($post["chkMontoFijo"]) ? $post['monto'] : null,
			'diaPago' => $post['diaPago'],
			'frecuenciaPago' => $post['frecuenciaPago'],
			'fechaInicio' => $post['fechaInicio'],
			'fechaTermino' => verificarEmpty($post['fechaTermino'], 4),
			'idMoneda' => $post['moneda'],
			'descripcionServicio' => verificarEmpty($post['informacionAdicional'], 4)
		];

		$data['tabla'] = 'finanzas.proveedorServicioPago';
		$data['where'] = ['idProveedorServicioPago' => $post['idProveedorServicioPago']];
		$insert = $this->model->actualizarProveedorServicioPago($data);

		if (!$insert) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
			goto respuesta;
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function actualizarEstadoProveedorServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['update'] = ['estado' => ($post['estado'] == 2) ? 3 : 2];

		$data['tabla'] = 'finanzas.proveedorServicioPago';
		$data['where'] = ['idProveedorServicioPago' => $post['idProveedorServicioPago']];

		$update = $this->model->actualizarProveedorServicioPago($data);
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

	public function formularioRegistroProveedorServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['estado'] = $this->model->obtenerEstado()['query']->result_array();
		$ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

		$dataParaVista['departamento'] = [];
		$dataParaVista['provincia'] = [];
		$dataParaVista['distrito'] = [];

		foreach ($ciudad as $ciu) {
			$dataParaVista['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
			$dataParaVista['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
			$dataParaVista['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
			$dataParaVista['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Proveedor Servicio';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorServicioPago/formularioRegistro", $dataParaVista, true);
		echo json_encode($result);
	}

	// public function registrarProveedorServicio()
	// {
	// 	$result = $this->result;
	// 	$post = json_decode($this->input->post('data'), true);

	// 	$validar = $this->model->validarExistenciaProveedorServicio($post)['query']->result_array();

	// 	if (!empty($validar)) {
	// 		$result['result'] = 0;
	// 		$result['msg']['title'] = 'Alerta!';
	// 		$result['msg']['content'] = getMensajeGestion('registroRepetido');
	// 		goto respuesta;
	// 	}

	// 	$elementosAValidar = [
	// 		'numeroDocumento' => ['requerido', 'numerico'],
	// 		'razonSocial' => ['requerido'],
	// 		'distrito' => ['requerido'],
	// 		'direccion' => ['requerido'],
	// 		'idProveedorEstado' => ['requerido'],
	// 		'correoContacto' => ['requerido', 'email'],
	// 		'nombreContacto' => ['requerido'],
	// 		'numeroContacto' => ['requerido', 'numerico']
	// 	];

	// 	$resultadoDeValidaciones = verificarValidacionesBasicas($elementosAValidar, $post);

	// 	if (!verificarSeCumplenValidaciones($resultadoDeValidaciones)) {
	// 		$result['result'] = 0;
	// 		$result['msg']['title'] = 'Alerta!';
	// 		$result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
	// 		goto respuesta;
	// 	}

	// 	if ($post['tipoDocumento'] === 'DNI') {

	// 		$insertData = [
	// 			'dni' => $post['numeroDocumento'],
	// 			'razonSocial' => $post['razonSocial'],
	// 			'cod_ubigeo' => $post['distrito'],
	// 			'direccion' => $post['direccion'],
	// 			'idProveedorEstado' => $post['idProveedorEstado'],
	// 			'nombreContacto' => $post['nombreContacto'],
	// 			'correoContacto' => $post['correoContacto'],
	// 			'numeroContacto' => $post['numeroContacto'],
	// 			'estado' => 1
	// 		];
	// 	} elseif ($post['tipoDocumento'] === 'RUC') {

	// 		$insertData = [
	// 			'ruc' => $post['numeroDocumento'],
	// 			'razonSocial' => $post['razonSocial'],
	// 			'cod_ubigeo' => $post['distrito'],
	// 			'direccion' => $post['direccion'],
	// 			'idProveedorEstado' => $post['idProveedorEstado'],
	// 			'nombreContacto' => $post['nombreContacto'],
	// 			'correoContacto' => $post['correoContacto'],
	// 			'numeroContacto' => $post['numeroContacto'],
	// 			'estado' => 1
	// 		];
	// 	} elseif ($post['tipoDocumento'] === 'CE') {

	// 		$insertData = [
	// 			'carnet_extranjeria' => $post['numeroDocumento'],
	// 			'razonSocial' => $post['razonSocial'],
	// 			'cod_ubigeo' => $post['distrito'],
	// 			'direccion' => $post['direccion'],
	// 			'idProveedorEstado' => $post['idProveedorEstado'],
	// 			'nombreContacto' => $post['nombreContacto'],
	// 			'correoContacto' => $post['correoContacto'],
	// 			'numeroContacto' => $post['numeroContacto'],
	// 			'estado' => 1
	// 		];
	// 	}

	// 	$insertarDatos = $this->db->insert('finanzas.proveedorServicio', $insertData);

	// 	if ($insertarDatos) {
	// 		$result['result'] = 1;
	// 		$result['msg']['title'] = 'Hecho!';
	// 		$result['msg']['content'] = getMensajeGestion('registroExitoso');
	// 	} else {


	// 		$result['msg']['title'] = 'Ocurrio un error';
	// 		$result['msg']['content'] = getMensajeGestion('registroInvalido');
	// 	}

	// 	respuesta:
	// 	echo json_encode($result);
	// }
}
