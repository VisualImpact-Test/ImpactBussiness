<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorServicio extends MY_Controller
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

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Pagos';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Finanzas/ProveedorServicio/index';

		$this->view($config);
	}
	public function reporte()
	{
		$result = $this->result;
        $dataParaVista = [];
		$dataParaVista['proveedorServicio'] = $this->model->obtenerProveedorServicio()['query']->result_array();

		

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Finanzas/ProveedorServicio/reporte", $dataParaVista, true);
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
		$dataParaVista['proveedorServicio'] = $this->db->get_where('finanzas.proveedorServicio')->result_array();
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda', array('estado' => '1'))->result_array();
		$dataParaVista['frecuenciaPago'] = $this->db->get_where('finanzas.frecuenciaPagoProveedorServicioPago', array('estado' => '1'))->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Pago Proveedor Servicio';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorServicio/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioActualizacionProveedorServicioPago()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedorServicioPago'] = $this->model->obtenerProveedorServicio(['idProveedorServicioPago' => $post['idProveedorServicioPago']])['query']->result_array();
		
		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Pago Proveedor Servicio';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/ProveedorServicio/formularioActualizacion", $dataParaVista, true);

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
			'fechaInicio' => $post['deadlineInicio'],
			'fechaTermino' => verificarEmpty($post['deadlineTermino'], 4),
			'idMoneda' => $post['moneda'],
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

	public function actualizarEstadoProveedorServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['update'] = ['idProveedorEstado' => ($post['estado'] == 2) ? 3 : 2];

		$data['tabla'] = 'finanzas.proveedorServicio';
		$data['where'] = ['idProveedorServicio' => $post['idProveedorServicio']];

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
}
