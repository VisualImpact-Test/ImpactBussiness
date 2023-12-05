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
	public function formularioRegistroSincerado()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		// $dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		// $dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		// $dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		// $dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();

		$dataParaVista['datos'] = $this->db->get_where('compras.presupuestoValido', ['estado' => 1])->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sincerado';
		$result['data']['html'] = $this->load->view("modulos/Sincerado/formularioRegistroSincerado", $dataParaVista, true);

		echo json_encode($result);
	}
}
