<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Servicio extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tarifario/M_Servicio', 'model');
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
            'assets/custom/js/Tarifario/servicio'
        );

        $config['data']['icon'] = 'fas fa-handshake';
        $config['data']['title'] = 'Servicios';
        $config['data']['message'] = 'Lista de Servicios';
        $config['data']['tipoServicio'] = $this->model->obtenerTipoServicio()['query']->result_array();
        $config['data']['razonSocProveedor'] = $this->model->obtenerRazonSocProveedor()['query'];
        $config['view'] = 'modulos/Tarifario/Servicio/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionTarifarioServicios($post)['query'];
   
        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Tarifario/Servicio/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentServicio']['datatable'] = 'tb-servicio';
        $result['data']['views']['idContentServicio']['html'] = $html;
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

    public function formularioHistorialTarifarioServicio()
    {
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['datos'] = $this->model->obtenerHistorialTarifarioServicio($post)['query'];

        $result['result'] = 1;
        $result['msg']['title'] = 'Historial Tarifario de Servicio';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Servicio/formularioHistorial", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioRegistroTarifarioServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

       $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerRazonSocProveedor()['query'];

        $servicios = $this->model->obtenerServicios();

        foreach ($servicios as $key => $row) {
            $data['servicios'][1][$row['value']]['value'] = $row['value'];
            $data['servicios'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['servicios'] as $k => $r) {
            $data['servicios'][$k] = array_values($data['servicios'][$k]);
        }
        $data['servicios'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Tarifario de Servicio';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Servicio/formularioRegistro", $dataParaVista, true);
        $result['data']['servicios'] = $data['servicios'];

        echo json_encode($result);
    }

    public function actualizarEstadoServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $update = $this->model->actualizarServicio($post);

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

    public function formularioActualizacionTarifarioServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerRazonSocProveedor()['query'];

        $servicios = $this->model->obtenerServicios();

        foreach ($servicios as $key => $row) {
            $data['servicios'][1][$row['value']]['value'] = $row['value'];
            $data['servicios'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['servicios'] as $k => $r) {
            $data['servicios'][$k] = array_values($data['servicios'][$k]);
        }
        $data['servicios'][0] = array();
        $result['data']['existe'] = 0;

        $post['row_array'] = true;
        $dataParaVista['informacionTarifarioServicio'] = $this->model->obtenerInformacionTarifarioServicios($post)['query'];

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Tarifario de Servicio';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Servicio/formularioActualizacion", $dataParaVista, true);
        $result['data']['servicios'] = $data['servicios'];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    public function registrarTarifarioServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeServicioActual = 0;

        $data['insert'] = [
            'idServicio' => $post['idServicio'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => isset($post['actual']) && $post['actual'] === 'on' ? 0 : 1
        ];

        if (!isset($post['actual'])) {
            $validacionActual = $this->model->validarTarifarioServicio($data['insert'], $validar = 'actual');

            if (!empty($validacionActual['query'])) {
                $data['insert']['flag_actual'] = 0;
                $existeServicioActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarTarifarioServicio($data['insert'], $validar = 'existe');

        if (!empty($validacionExistencia['query'])) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');

            echo json_encode($result);
            exit();
        }

        $insert = $this->model->insertarTarifarioServicio($data, $tabla = 'compras.tarifarioServicio');
        $data = [];

        $data['insert'] = [
            'idTarifarioServicio' => $insert['id'],
            'fecIni' => getFechaActual(),
            'fecFin' => NULL,
            'costo' => $post['costo'],
        ];

        $subInsert = $this->model->insertarTarifarioServicio($data, $tabla = 'compras.tarifarioServicioHistorico');

        $data = [];

        if (!$insert['estado'] or !$subInsert['estado']) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }

        if ($existeServicioActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un servicio que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idTarifarioServicio'] = $insert['id'];
            $result['data']['idServicio'] = $post['idServicio'];
        }

        echo json_encode($result);
    }

    public function actualizarTarifarioServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data=[];
        $existeServicioActual = 0;

        $data['update'] = [
            'idTarifarioServicio' => $post['idTarifarioServicio'],
            'idServicio' => $post['idServicio'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => isset($post['actual']) && $post['actual'] === 'on' ? 0 : 1
        ];

        if (!isset($post['actual'])) {
            $validacionActual = $this->model->validarTarifarioServicio($data['update'], $validar = 'actual');

            if (!empty($validacionActual['query'])) {
                $data['update']['flag_actual'] = 0;
                $existeServicioActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarTarifarioServicio($data['update'], $validar = 'existe');
        unset($data['update']['idTarifarioServicio']);

        if (!empty($validacionExistencia['query'])) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');

            echo json_encode($result);
            exit();
        }

        $data['where'] = [
            'idTarifarioServicio' => $post['idTarifarioServicio']
        ];

        $update = $this->model->actualizarTarifarioServicio($data, $table = 'compras.tarifarioServicio');

        $data = [];
        $actualizacionHistoricos = true;

        if ($post['costoAnterior'] != $post['costo']) {
            $data['update'] = [
                'fecFin' => getFechaActual(),
            ];

            $data['where'] = [
                'idTarifarioServicio' => $post['idTarifarioServicio'],
                'fecFin' => NULL
            ];

            $subUpdate = $this->model->actualizarTarifarioServicio($data, $table = 'compras.tarifarioServicioHistorico');
            $data = [];

            $data['insert'] = [
                'idTarifarioServicio' => $post['idTarifarioServicio'],
                'fecIni' => getFechaActual(),
                'fecFin' => NULL,
                'costo' => $post['costo'],
            ];

            $subInsert = $this->model->insertarTarifarioServicio($data, 'compras.tarifarioServicioHistorico');
            $data = [];

            if (!$subUpdate['estado'] && !$subInsert['estado']) {
                $actualizacionHistoricos = false;
            }
        }

        if (!$update['estado'] or !$actualizacionHistoricos) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }

        if ($existeServicioActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un servicio que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idTarifarioServicio'] = $post['idTarifarioServicio'];
            $result['data']['idServicio'] = $post['idServicio'];
        }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }
}
