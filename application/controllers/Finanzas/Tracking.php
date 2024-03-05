<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Tracking', 'model');
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
			'assets/custom/js/Finanzas/tracking',
			'https://unpkg.com/xlsx/dist/xlsx.full.min.js'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Tracking';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Finanzas/Tracking/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$data = $this->model->obtenerInformacionTracking()->result_array();
		$html = getMensajeGestion('noRegistros');
		if (!empty($data)) {
			$dataParaVista['tracking'] = $data;
			$html = $this->load->view("modulos/Finanzas/Tracking/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentTracking']['datatable'] = 'tb-tracking';
		$result['data']['views']['idContentTracking']['html'] = $html;
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
	public function formularioTrackingDatosAdicionales()
	{
		$result = $this->result;
		$post = $this->input->post();

		$dataParaVista['datosAdicionales'] = $this->db->get_where('compras.trackingDatosAdicionales', ['idOrdenServicio' => $post['idOrdenServicio'], 'idSinceradoGr' => $post['idSinceradoGr'], 'estado' => 1])->result_array();
		$dataParaVista['idOrdenServicio'] = $post['idOrdenServicio'];
		$dataParaVista['idSinceradoGr'] = $post['idSinceradoGr'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Datos Adicionales';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/Tracking/formularioRegistroDatosAdicionales", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formulariotrackingFechaSustento()
	{
		$result = $this->result;
		$post = $this->input->post();

		//$dataParaVista['datosAdicionales'] = $this->db->get_where('compras.trackingDatosAdicionales', ['idOrdenServicio' => $post['idOrdenServicio'], 'idSinceradoGr' => $post['idSinceradoGr'], 'estado' => 1])->result_array();
		$dataParaVista['idOrdenServicio'] = $post['idOrdenServicio'];
		$dataParaVista['idSinceradoGr'] = $post['idSinceradoGr'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sustento';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/Tracking/formularioRegistroFechaSustento", $dataParaVista, true);

		echo json_encode($result);
	}


	public function registrarTrackingDatosAdicionales()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = $this->input->post();

		$data = [
			'idOrdenServicio' => verificarEmpty($post['idOrdenServicio'], 4),
			'idSinceradoGr' => verificarEmpty($post['idSinceradoGr'], 4),
			'fechaEstimadaEjecucion' => $post['fechaEstimada'],
			'comentario' => $post['comentario'],
			'idUsuario' => $this->idUsuario
		];

		if (!isset($post['idTrackingDatosAdicionales'])) {
			$this->db->insert('compras.trackingDatosAdicionales', $data);
		} else {
			$this->db->update('compras.trackingDatosAdicionales', $data, ['idTrackingDatosAdicionales' => $post['idTrackingDatosAdicionales']]);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function registrarTrackingFechaSustento()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = $this->input->post();
		if ($post['estadoSustento'] == 0) {
			$data = ['estadoSustento' => $post['estadoSustento'],'usuarioEliminar' => $this->idUsuario];
		}elseif ($post['estadoSustento'] == 1) {
			$data = ['fechaSustento' => $post['fechaSustento']];
		}
		$this->db->update('compras.cotizacion', $data, ['idCotizacion' => $post['idCotizacion']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}
	
}
