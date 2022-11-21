<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SubCategoria extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_SubCategoria', 'model');
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
            'assets/custom/js/configuracion/subCategoria'
        );

        $config['data']['icon'] = 'fas fa-books';
        $config['data']['title'] = 'SubCategorias';
        $config['data']['message'] = 'Lista de SubCategorias';
        $config['view'] = 'modulos/configuracion/SubCategoria/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionSubCategoria($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Configuracion/SubCategoria/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentSubCategoria']['datatable'] = 'tb-SubCategoria';
        $result['data']['views']['idContentSubCategoria']['html'] = $html;
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

    public function formularioRegistroSubCategoria()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar SubCategoria';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/SubCategoria/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioActualizacionSubCategoria()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['informacionSubCategoria'] = $this->model->obtenerInformacionSubCategoria($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar SubCategoria';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/SubCategoria/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarSubCategoria()
    {

        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre']
        ];

        $validacionExistencia = $this->model->validarExistenciaSubCategoria($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemSubCategoria';

        $insert = $this->model->insertarSubCategoria($data);
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

    public function actualizarSubCategoria()
    {

        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idItemSubCategoria' => $post['idItemSubCategoria'],

            'nombre' => $post['nombre']
        ];

        $validacionExistencia = $this->model->validarExistenciaSubCategoria($data['update']);
        unset($data['update']['idItemSubCategoria']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemSubCategoria';
        $data['where'] = [
            'idItemSubCategoria' => $post['idItemSubCategoria']
        ];

        $insert = $this->model->actualizarSubCategoria($data);
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

    public function actualizarEstadoSubCategoria()
    {
        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.itemSubCategoria';
        $data['where'] = [
            'idItemSubCategoria' => $post['idItemSubCategoria']
        ];

        $update = $this->model->actualizarSubCategoria($data);
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
