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
            'assets/custom/js/servicio'
        );

        $config['data']['icon'] = 'fas fa-handshake';
        $config['data']['title'] = 'Servicios';
        $config['data']['message'] = 'Lista de Servicios';
        $config['data']['tipoServicio'] = $this->model->obtenerTipoServicio()['query']->result_array();
        $config['view'] = 'modulos/servicio/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionServicios($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Servicio/reporte", ['datos' => $dataParaVista], true);
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

    public function formularioRegistroServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoServicio'] = $this->model->obtenerTipoServicio()['query']->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Servicio';
        $result['data']['html'] = $this->load->view("modulos/Servicio/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioActualizacionServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoServicio'] = $this->model->obtenerTipoServicio()['query']->result_array();

        $dataParaVista['informacionServicio'] = $this->model->obtenerInformacionServicios($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Servicio';
        $result['data']['html'] = $this->load->view("modulos/Servicio/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'idTipoServicio' => $post['tipo']
        ];

        $validacionExistencia = $this->model->validarExistenciaServicio($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.servicio';

        $insert = $this->model->insertarServicio($data);
        $data = [];

        if (!$insert['estado']) {
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

    public function actualizarServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idServicio' => $post['idServicio'],

            'nombre' => $post['nombre'],
            'idTipoServicio' => $post['tipo']
        ];

        $validacionExistencia = $this->model->validarExistenciaServicio($data['update']);
        unset($data['update']['idServicio']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.servicio';
        $data['where'] = [
            'idServicio' => $post['idServicio']
        ];

        $insert = $this->model->actualizarServicio($data);
        $data = [];

        if (!$insert['estado']) {
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

    public function actualizarEstadoServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.servicio';
        $data['where'] = [
            'idServicio' => $post['idServicio']
        ];

        $update = $this->model->actualizarServicio($data);
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
