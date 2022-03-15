<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CotizacionEfectiva extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_CotizacionEfectiva', 'model');
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
            'assets/custom/js/cotizacionEfectiva'
        );

        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Cotizacion Efectiva';
        $config['data']['message'] = 'Lista de Cotizacion Efectivas';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/CotizacionEfectiva/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        // $dataParaVista = $this->model->obtenerInformacionCotizacionEfectiva($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/CotizacionEfectiva/reporte", ['datos' => $dataParaVista], true);
        }
        $html = $this->load->view("modulos/CotizacionEfectiva/reporte", ['datos' => $dataParaVista], true);

        $result['result'] = 1;
        $result['data']['views']['idContentCotizacionEfectiva']['datatable'] = 'tb-cotizacionEfectiva';
        $result['data']['views']['idContentCotizacionEfectiva']['html'] = $html;
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

    public function formularioVisualizacionCotizacionEfectiva()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $result['result'] = 1;
        $result['msg']['title'] = 'Visualizar CotizacionEfectiva';
        $result['data']['html'] = $this->load->view("modulos/CotizacionEfectiva/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }
}