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

		
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Proveedor';
		$result['data']['html'] = $this->load->view("modulos/Finanzas/PagosGenerados/formularioRegistro", $dataParaVista, true);

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
	
}
