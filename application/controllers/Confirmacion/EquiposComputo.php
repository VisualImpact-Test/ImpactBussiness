<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EquiposComputo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Confirmacion/M_EquiposComputo', 'model');
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
            'assets/custom/js/Confirmacion/equiposComputo'
        );

        $config['data']['icon'] = 'fas fa-money-bill';
        $config['data']['title'] = 'Equipos Computo';
        $config['data']['message'] = 'Lista de Equipos Computo';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/Confirmacion/EquiposComputo/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionEquiposComputo($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Confirmacion/EquiposComputo/reporte", ['datos' => $dataParaVista], true);
        }
        $html = $this->load->view("modulos/Confirmacion/EquiposComputo/reporte", ['datos' => $dataParaVista], true);

        $result['result'] = 1;
        $result['data']['views']['idContentEquiposComputo']['datatable'] = 'tb-equiposComputo';
        $result['data']['views']['idContentEquiposComputo']['html'] = $html;
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

    public function formularioVisualizacionEquiposComputo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $post;

        // $data = $this->model->obtenerInformacionEquiposComputoDetalle($post)['query']->result_array();

        // foreach ($data as $key => $row) {
        //     $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
        //     $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
        //     $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
        //     $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
        //     $dataParaVista['cabecera']['codCotizacion'] = $row['codCotizacion'];
        //     $dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
        //     $dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
        //     $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
        //     $dataParaVista['detalle'][$key]['item'] = $row['item'];
        //     $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
        //     $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
        //     $dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
        //     $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        //     $dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
        //     $dataParaVista['detalle'][$key]['fechaCreacion'] = $row['fechaCreacion'];
        //     $dataParaVista['detalle'][$key]['cotizacionDetalleEstado'] = $row['cotizacionDetalleEstado'];
        // }

        $result['result'] = 1;
        $result['msg']['title'] = 'Visualizar Equipos Computo de la Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/Confirmacion/EquiposComputo/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }
}