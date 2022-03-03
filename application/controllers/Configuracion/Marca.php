<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marca extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_Marca', 'model');
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
            'assets/custom/js/Configuracion/marca'
        );

        $config['data']['icon'] = 'fas fa-copyright';
        $config['data']['title'] = 'Marcas';
        $config['data']['message'] = 'Lista de Marcas';
        $config['view'] = 'modulos/Configuracion/Marca/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionMarcas($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Configuracion/Marca/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentMarca']['datatable'] = 'tb-marca';
        $result['data']['views']['idContentMarca']['html'] = $html;
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

    public function formularioRegistroMarca()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Marca';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Marca/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioActualizacionMarca()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['informacionMarca'] = $this->model->obtenerInformacionMarcas($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Marca';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Marca/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarMarca()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre']
        ];

        $validacionExistencia = $this->model->validarExistenciaMarca($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.marcaArticulo';

        $insert = $this->model->insertarMarca($data);
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

    public function actualizarMarca()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idMarcaArticulo' => $post['idMarcaArticulo'],

            'nombre' => $post['nombre'],
        ];

        $validacionExistencia = $this->model->validarExistenciaMarca($data['update']);
        unset($data['update']['idMarcaArticulo']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.marcaArticulo';
        $data['where'] = [
            'idMarcaArticulo' => $post['idMarcaArticulo']
        ];

        $insert = $this->model->actualizarMarca($data);
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

    public function actualizarEstadoMarca()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.marcaArticulo';
        $data['where'] = [
            'idMarcaArticulo' => $post['idMarcaArticulo']
        ];

        $update = $this->model->actualizarMarca($data);
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
