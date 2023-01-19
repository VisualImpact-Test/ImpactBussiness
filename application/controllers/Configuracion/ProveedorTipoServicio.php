<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorTipoServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Configuracion/M_ProveedorTipoServicio', 'model');
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
			'assets/libs/datatables/responsive.bootstrap4.min',
			'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/Configuracion/proveedorTipoServicio'
		);

		$config['data']['icon'] = 'fad fa-list';
		$config['data']['title'] = 'Tipos Servicios';
		$config['data']['message'] = 'Lista de Tipos Servicios';
		$config['view'] = 'modulos/Configuracion/ProveedorTipoServicio/index';

		$this->view($config);
	}

	public function reporteDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		// $dataParaVista = [];
		$dataParaVista = $this->model->obtenerInformacionProveedorTipoServicio($post)->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Configuracion/ProveedorTipoServicio/Detalle/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentProveedorTipoServicioDetalle']['datatable'] = 'tb-proveedorTipoServicio-detalle';
		$result['data']['views']['idContentProveedorTipoServicioDetalle']['html'] = $html;
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
	public function formularioRegistroProveedorTipoServicioDetalle()
	{
		$result = $this->result;

		$dataParaVista = []; // Por si se necesita enviar un parametro en adelante.

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Tipo Servicio para Proveedor';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/ProveedorTipoServicio/Detalle/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarProveedorTipoServicioDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataInsert = [
			'nombre' => $post['nombre'],
		];

		$validacionExistencia = $this->model->obtenerInformacionProveedorTipoServicio($dataInsert);

		if (!empty($validacionExistencia->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$insert = $this->model->guardarDatos('compras.proveedorTipoServicio', $dataInsert);

		if ($insert['estado']) {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarEstadoTipoServicioDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$datos = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$filtro = [
			'idProveedorTipoServicio' => $post['idProveedorTipoServicio']
		];

		$update = $this->model->actualizarDatos('compras.proveedorTipoServicio', $datos, $filtro);

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


	public function formularioActualizacionTipoServicioDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [
			'data' => $this->model->obtenerInformacionProveedorTipoServicio($post)->row_array()
		];

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Tipo Servicio para Proveedor';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/ProveedorTipoServicio/Detalle/formularioActualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarTipoServicioDetalle()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$datos = [
			'no_idProveedorTipoServicio' => $post['id'],
			'nombre' => $post['nombre'],
		];

		$validacionExistencia = $this->model->obtenerInformacionProveedorTipoServicio($datos);
		unset($datos['no_idProveedorTipoServicio']);

		if (!empty($validacionExistencia->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$filtro = [
			'idProveedorTipoServicio' => $post['id']
		];

		$update = $this->model->actualizarDatos('compras.proveedorTipoServicio', $datos, $filtro);

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
}
