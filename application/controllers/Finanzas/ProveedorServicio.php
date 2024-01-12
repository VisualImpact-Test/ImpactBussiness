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
		$data = $this->model->obtenerProveedorServicio()['query']->result_array();

		foreach ($data as $key => $row) {
			$dataParaVista[] = [
				'idProveedorServicio' => $row['idProveedorServicio'],
				'ruc' => $row['ruc'],
				'razonSocial' => $row['razonSocial'],
				'direccion' => $row['direccion'],
				'nombreContacto' => $row['nombreContacto'],
				'numeroContacto' => $row['numeroContacto'],
				'correoContacto' => $row['correoContacto'],
				'estado' => $row['estado'],
				'idEstado' => $row['idProveedorEstado'],
				'estadoIcono' => $row['estadoIcono'],
				'estadoToggle' => $row['estadoToggle'],
				'departamento' => $row['departamento'],
				'provincia' => $row['provincia'],
				'distrito' => $row['distrito'],
				'monto' => $row['monto'],
				'diaPago' => $row['diaPago'],
				'frecuenciaPago' => $row['frecuenciaPago'],
				'fechaInicio' => $row['fechaInicio'],
				'fechaTermino' => $row['fechaTermino'],
				'descripcionServicio' => $row['descripcionServicio'],
				'simbolo' => $row['simbolo'],
				'distrito' => $row['distrito'],
			];
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Finanzas/ProveedorServicio/reporte", ['datos' => $dataParaVista], true);
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

	public function actualizarEstadoProveedorServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['update'] = ['idProveedorEstado' => ($post['estado'] == 2) ? 3 : 2];

		$data['tabla'] = 'finanzas.proveedorServicio';
		$data['where'] = ['idProveedorServicio' => $post['idProveedorServicio']];

		$update = $this->model->actualizarProveedor($data);
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
