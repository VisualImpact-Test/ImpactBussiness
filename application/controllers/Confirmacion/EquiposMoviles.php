<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EquiposMoviles extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Confirmacion/M_EquiposMoviles', 'model');
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
            'assets/custom/js/Confirmacion/equiposMoviles'
        );

        $config['data']['icon'] = 'fas fa-money-bill';
        $config['data']['title'] = 'Equipos Moviles';
        $config['data']['message'] = 'Lista de Equipos Moviles';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/Confirmacion/EquiposMoviles/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        // $dataParaVista = $this->model->obtenerInformacionEquiposMoviles($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Confirmacion/EquiposMoviles/reporte", ['datos' => $dataParaVista], true);
        }
        $html = $this->load->view("modulos/Confirmacion/EquiposMoviles/reporte", ['datos' => $dataParaVista], true);

        $result['result'] = 1;
        $result['data']['views']['idContentEquiposMoviles']['datatable'] = 'tb-equiposMoviles';
        $result['data']['views']['idContentEquiposMoviles']['html'] = $html;
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

    public function formularioVisualizacionEquiposMoviles()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $post;

        // $data = $this->model->obtenerInformacionEquiposMovilesDetalle($post)['query']->result_array();

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
        $result['msg']['title'] = 'Visualizar Equipos Moviles de la Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/Confirmacion/EquiposMoviles/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function enviarCorreo()
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'teamsystem@visualimpact.com.pe',
            'smtp_pass' => '#nVi=0sN0ti$',
            'mailtype' => 'html'
        );

        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->set_newline("\r\n");

        $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
        $this->email->to('harry.pineda@visualimpact.com.pe');

        $data = [];
        $dataParaVista = [];
        // $data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();

        // foreach ($data as $key => $row) {
        //     $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
        //     $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
        //     $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
        //     $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
        //     $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
        //     $dataParaVista['detalle'][$key]['item'] = $row['item'];
        //     $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
        //     $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
        //     $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        // }

        $dataParaVista['link'] = base_url() . index_page() . 'Cotizacion';

        // $bcc = array(
        //     'team.sistemas@visualimpact.com.pe',
        // );
        // $this->email->bcc($bcc);

        //$bcc = array('luis.durand@visualimpact.com.pe');
		$this->email->bcc($bcc);
        
        $this->email->subject('IMPACTBUSSINESS - CONFIRMACION DE ITEMS DE COTIZACION');
        $html = $this->load->view("modulos/CotizacionEfectiva/correo/informacionProveedor", $dataParaVista, true);
        $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Cotizacion'], true);
        $this->email->message($correo);

        $estadoEmail = $this->email->send();

        $result = $this->result;

        echo json_encode($result);
    }
}
