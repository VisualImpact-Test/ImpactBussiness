<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tipo extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Configuracion/M_Tipo', 'model');
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
			'assets/libs/select2/4.0.13/js/select2',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/Configuracion/tipo',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['icon'] = 'fad fa-ball-pile';
		$config['data']['title'] = 'Tipos';
		$config['data']['message'] = 'Lista de Tipos';
		$config['view'] = 'modulos/Configuracion/Tipo/index';

		$this->view($config);
	}

	//ARTICULO

	public function reporteArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$post['allTipoArticulo'] = true;
		$dataParaVista = $this->model->obtenerInformacionTiposArticulo($post)['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Configuracion/Tipo/Articulo/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentTipoArticulo']['datatable'] = 'tb-tipo-articulo';
		$result['data']['views']['idContentTipoArticulo']['html'] = $html;
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

	public function formularioRegistroTipoArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Tipo';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/Tipo/Articulo/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioActualizacionTipoArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['informacionTipo'] = $this->model->obtenerInformacionTiposArticulo($post)['query']->row_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Tipo';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/Tipo/Articulo/formularioActualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarTipoArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['insert'] = [
			'nombre' => $post['nombre']
		];

		$validacionExistencia = $this->model->validarExistenciaTipoArticulo($data['insert']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.itemTipo';

		$insert = $this->model->insertarTipoArticulo($data);
		$data = [];

		if (!$insert['estado']) {
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

	public function actualizarTipoArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'idItemTipo' => $post['idItemTipo'],

			'nombre' => $post['nombre']
		];

		$validacionExistencia = $this->model->validarExistenciaTipoArticulo($data['update']);
		unset($data['update']['idItemTipo']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.itemTipo';
		$data['where'] = [
			'idItemTipo' => $post['idItemTipo']
		];

		$insert = $this->model->actualizarTipoArticulo($data);
		$data = [];

		if (!$insert['estado']) {
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

	public function actualizarEstadoTipoArticulo()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$data['tabla'] = 'compras.itemTipo';
		$data['where'] = [
			'idItemTipo' => $post['idTipo']
		];

		$update = $this->model->actualizarTipoArticulo($data);
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


	/*
	//SERVICIO
	public function reporteServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerInformacionTiposServicio($post)['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Configuracion/Tipo/Servicio/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentTipoServicio']['datatable'] = 'tb-tipo-servicio';
		$result['data']['views']['idContentTipoServicio']['html'] = $html;
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

	public function formularioRegistroTipoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Tipo';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/Tipo/Servicio/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioActualizacionTipoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['informacionTipo'] = $this->model->obtenerInformacionTiposServicio($post)['query']->row_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Tipo';
		$result['data']['html'] = $this->load->view("modulos/Configuracion/Tipo/Servicio/formularioActualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarTipoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['insert'] = [
			'nombre' => $post['nombre']
		];

		$validacionExistencia = $this->model->validarExistenciaTipoServicio($data['insert']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.tipoServicio';

		$insert = $this->model->insertarTipoServicio($data);
		$data = [];

		if (!$insert['estado']) {
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

	public function actualizarTipoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'idTipoServicio' => $post['idTipo'],

			'nombre' => $post['nombre']
		];

		$validacionExistencia = $this->model->validarExistenciaTipoServicio($data['update']);
		unset($data['update']['idTipoServicio']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.tipoServicio';
		$data['where'] = [
			'idTipoServicio' => $post['idTipo']
		];

		$insert = $this->model->actualizarTipoServicio($data);
		$data = [];

		if (!$insert['estado']) {
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

	public function actualizarEstadoTipoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$data['tabla'] = 'compras.tipoServicio';
		$data['where'] = [
			'idTipoServicio' => $post['idTipo']
		];

		$update = $this->model->actualizarTipoServicio($data);
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
	*/
}
