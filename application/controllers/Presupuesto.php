<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presupuesto extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Presupuesto', 'model');
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
            'assets/custom/js/presupuesto'
        );

        $config['data']['icon'] = 'fas fa-money-bill';
        $config['data']['title'] = 'Presupuesto';
        $config['data']['message'] = 'Lista de Presupuestos';
        $config['data']['tipoPresupuesto'] = $this->model->obtenerTipoPresupuesto()['query']->result_array();
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/Presupuesto/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionPresupuesto($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Presupuesto/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentPresupuesto']['datatable'] = 'tb-presupuesto';
        $result['data']['views']['idContentPresupuesto']['html'] = $html;
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

    public function formularioRegistroPresupuesto()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoPresupuesto'] = $this->model->obtenerTipoPresupuesto()['query']->result_array();
        $dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();

        $articuloServicio =  $this->model->obtenerArticuloServicio();
        foreach ($articuloServicio as $key => $row) {
            $data['articuloServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['articuloServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['articuloServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['articuloServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
        }
        foreach ($data['articuloServicio'] as $k => $r) {
            $data['articuloServicio'][$k] = array_values($data['articuloServicio'][$k]);
        }
        $data['articuloServicio'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Presupuesto';
        $result['data']['html'] = $this->load->view("modulos/Presupuesto/formularioRegistro", $dataParaVista, true);
        $result['data']['articuloServicio'] = $data['articuloServicio'];

        echo json_encode($result);
    }

    public function formularioVisualizacionPresupuesto()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $data = $this->model->obtenerInformacionPresupuestoDetalle($post)['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idPresupuesto'] = $row['idPresupuesto'];
            $dataParaVista['cabecera']['presupuesto'] = $row['presupuesto'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['cabecera']['tipoPresupuesto'] = $row['tipoPresupuesto'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        }

        $result['result'] = 1;
        $result['msg']['title'] = 'Visualizar Presupuesto';
        $result['data']['html'] = $this->load->view("modulos/Presupuesto/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarPresupuesto()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'fecha' => getFechaActual(),
            'idTipoPresupuesto' => $post['tipo'],
            'idCuenta' => $post['cuentaForm'],
            'idCentroCosto' => $post['cuentaCentroCostoForm']
        ];

        $validacionExistencia = $this->model->validarExistenciaPresupuesto($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.presupuesto';

        $insert = $this->model->insertarPresupuesto($data);
        $data = [];

        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        $post['idTipoArticulo'] = checkAndConvertToArray($post['idTipoArticulo']);
        $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
        $post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
        $post['costoForm'] = checkAndConvertToArray($post['costoForm']);
        $post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);

        foreach ($post['nameItem'] as $k => $r) {
            $data['insert'][] = [
                'idPresupuesto' => $insert['id'],
                'idArticulo' => ($post['idTipoArticulo'][$k] == 1) ? $post['idItemForm'][$k] : NULL,
                'idServicio' => ($post['idTipoArticulo'][$k] == 2) ? $post['idItemForm'][$k] : NULL,
                'nombre' => $post['nameItem'][$k],
                'cantidad' => $post['cantidadForm'][$k],
                'costo' => $post['costoForm'][$k],
                'idEstadoItem' => $post['idEstadoItemForm'][$k]
            ];
        }

        $data['tabla'] = 'compras.presupuestoDetalle';

        $insertDetalle = $this->model->insertarPresupuestoDetalle($data);
        $data = [];

        $estadoEmail = $this->enviarCorreo($insert['id']);

        if (!$insert['estado'] || !$insertDetalle['estado'] || !$estadoEmail) {
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

    public function actualizarEstadoPresupuesto()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.presupuesto';
        $data['where'] = [
            'idPresupuesto' => $post['idPresupuesto']
        ];

        $update = $this->model->actualizarPresupuesto($data);
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

    public function enviarCorreo($idPresupuesto)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'teamsystem@visualimpact.com.pe',
            'smtp_pass' => 'v1su4l2010',
            'mailtype' => 'html'
        );

        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->set_newline("\r\n");

        $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
        $this->email->to('harry.pineda@visualimpact.com.pe');

        $data = [];
        $dataParaVista = [];
        $data = $this->model->obtenerInformacionPresupuestoDetalle(['idPresupuesto' => $idPresupuesto])['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idPresupuesto'] = $row['idPresupuesto'];
            $dataParaVista['cabecera']['presupuesto'] = $row['presupuesto'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['cabecera']['tipoPresupuesto'] = $row['tipoPresupuesto'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        }

        $dataParaVista['link'] = base_url() . index_page() . '/Presupuesto';

        // $bcc = array(
        //     'team.sistemas@visualimpact.com.pe',
        // );
        // $this->email->bcc($bcc);

        $this->email->subject('IMPACTBUSSINESS - NUEVO PRESUPUESTO GENERADO');
        $html = $this->load->view("modulos/Presupuesto/correo/informacionProveedor", $dataParaVista, true);
        $correo = $this->load->view("modulos/Presupuesto/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . '/Presupuesto'], true);
        $this->email->message($correo);

        $estadoEmail = $this->email->send();

        return $estadoEmail;
    }

    public function generarPresupuestoPDF($idPresupuesto = '')
    {
        require_once('../mpdf/mpdf.php');
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        if (!empty($idPresupuesto)) {
            $data = [];
            $dataParaVista = [];
            $data = $this->model->obtenerInformacionPresupuestoDetalle(['idPresupuesto' => $idPresupuesto])['query']->result_array();

            foreach ($data as $key => $row) {
                $dataParaVista['cabecera']['idPresupuesto'] = $row['idPresupuesto'];
                $dataParaVista['cabecera']['presupuesto'] = $row['presupuesto'];
                $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
                $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
                $dataParaVista['cabecera']['tipoPresupuesto'] = $row['tipoPresupuesto'];
                $dataParaVista['cabecera']['fecha'] = $row['fecha'];
                $dataParaVista['detalle'][$key]['item'] = $row['item'];
                $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
                $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
                $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
            }
            if (count($dataParaVista) == 0) exit();

            $contenido['header'] = $this->load->view("modulos/Presupuesto/pdf/header", array(), true);
            $contenido['footer'] = $this->load->view("modulos/Presupuesto/pdf/footer", array(), true);
            $contenido['body'] = $this->load->view("modulos/Presupuesto/pdf/body", $dataParaVista, true);
            $contenido['style'] = '<style>table { border-collapse: collapse; }table.tb-detalle th, table.tb-detalle td { border: 1px solid #484848; padding:5px; }.square { margin-right: 15px; border: 1px solid #000; text-align: center; }body {font-size: 12px;}</style>';

            require APPPATH . '/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();

            $mpdf->SetHTMLHeader($contenido['header']);
            $mpdf->SetHTMLFooter($contenido['footer']);
            $mpdf->AddPage();
            $mpdf->WriteHTML($contenido['style']);
            $mpdf->WriteHTML($contenido['body']);

            header('Set-Cookie: fileDownload=true; path=/');
            header('Cache-Control: max-age=60, must-revalidate');
            $mpdf->Output('Presupuesto.pdf', 'D');
        }

        $this->aSessTrack[] = ['idAccion' => 9];
    }
}
