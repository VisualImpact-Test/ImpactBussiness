<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categoria extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_Categoria', 'model');
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
            'assets/custom/js/configuracion/categoria'
        );

        $config['data']['icon'] = 'fas fa-books';
        $config['data']['title'] = 'Categorias';
        $config['data']['message'] = 'Lista de Categorias';
        $config['view'] = 'modulos/configuracion/Categoria/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionCategorias($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Configuracion/Categoria/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentCategoria']['datatable'] = 'tb-categoria';
        $result['data']['views']['idContentCategoria']['html'] = $html;
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

    public function formularioRegistroCategoria()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Categoria';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Categoria/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioActualizacionCategoria()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['informacionCategoria'] = $this->model->obtenerInformacionCategorias($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Categoria';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Categoria/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarCategoria()
    {

        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre']
        ];

        $validacionExistencia = $this->model->validarExistenciaCategoria($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemCategoria';

        $insert = $this->model->insertarCategoria($data);
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

        $this->db->trans_complete();

        respuesta:
        echo json_encode($result);
    }

    public function actualizarCategoria()
    {

        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idItemCategoria' => $post['idItemCategoria'],

            'nombre' => $post['nombre']
        ];

        $validacionExistencia = $this->model->validarExistenciaCategoria($data['update']);
        unset($data['update']['idItemCategoria']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemCategoria';
        $data['where'] = [
            'idItemCategoria' => $post['idItemCategoria']
        ];

        $insert = $this->model->actualizarCategoria($data);
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

        $this->db->trans_complete();

        respuesta:
        echo json_encode($result);
    }

    public function actualizarEstadoCategoria()
    {
        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.itemCategoria';
        $data['where'] = [
            'idItemCategoria' => $post['idCategoriaArticulo']
        ];

        $update = $this->model->actualizarCategoria($data);
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

        $this->db->trans_complete();

        respuesta:
        echo json_encode($result);
    }
}
