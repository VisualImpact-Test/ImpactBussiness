<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ProveedorDocumento', 'model');
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
			'assets/custom/js/Finanzas/proveedorServicio'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Pagos';
		$config['data']['message'] = 'Lista';
		// $config['data']['proveedor'] = $this->model->getProveedoresQueTienenOC()->result_array();
		// $config['data']['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$config['view'] = 'modulos/Finanzas/ProveedorServicio/index';

		$this->view($config);
	}
	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];
        $html = $this->load->view("modulos/Finanzas/ProveedorServicio/reporte", $dataParaVista, true);
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

	
}
