<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tarifario/M_Item', 'model');
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
            // 'assets/libs/datatables/responsive.bootstrap4.min',
            // 'assets/custom/js/core/datatables-defaults',
            'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/languages/all',
            'assets/libs/handsontable@7.4.2/dist/moment/moment',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/Tarifario/item'
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Items';
        $config['data']['message'] = 'Lista de Items';
        $config['data']['tipoItem'] = $this->model->obtenerItemTipo()['query']->result_array();
        $config['data']['itemMarca'] = $this->model->obtenerItemMarca()['query']->result_array();
        $config['data']['itemCategoria'] = $this->model->obtenerItemCategoria()['query']->result_array();
        $config['data']['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();
        $config['view'] = 'modulos/Tarifario/item/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Tarifario/Item/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentItem']['datatable'] = 'tb-item';
        $result['data']['views']['idContentItem']['html'] = $html;
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

    public function formularioRegistroItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $items =  $this->model->obtenerItems();
        foreach ($items as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioRegistro", $dataParaVista, true);
        $result['data']['items'] = $data['items'];

        echo json_encode($result);
    }

    public function formularioActualizacionItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $items =  $this->model->obtenerItems();
        foreach ($items as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $dataParaVista['informacionItem'] = $this->model->obtenerInformacionItemTarifario($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioActualizacion", $dataParaVista, true);
        $result['data']['items'] = $data['items'];

        echo json_encode($result);
    }

    public function formularioHistorialItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['datos'] = $this->model->obtenerInformacionTAHistorico($post)['query']->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Historial Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioHistorial", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeItemActual = 0;

        $data['insert'] = [
            'idItem' => $post['idItem'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1
        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarItemTarifarioActual($data['insert']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['insert']['flag_actual'] = 0;
                $existeItemActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaItemTarifario($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemTarifario';

        $insert = $this->model->insertarItemTarifario($data);
        $data = [];

        $data['insert'] = [
            'idItemTarifario' => $insert['id'],
            'fecIni' => getFechaActual(),
            'fecFin' => NULL,
            'costo' => $post['costo'],
        ];

        $data['tabla'] = 'compras.itemTarifarioHistorico';

        $subInsert = $this->model->insertarItemTarifario($data);

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

        if ($existeItemActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idItemTarifario'] = $insert['id'];
            $result['data']['idItem'] = $post['idItem'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeItemActual = 0;

        $data['update'] = [
            'idItemTarifario' => $post['idItemTarifario'],

            'idItem' => $post['idItem'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1
        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarItemTarifarioActual($data['update']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['update']['flag_actual'] = 0;
                $existeItemActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaItemTarifario($data['update']);
        unset($data['update']['idItemTarifario']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $update = $this->model->actualizarItemTarifario($data);
        $data = [];
        $actualizacionHistoricos = true;

        if ($post['costoAnterior'] != $post['costo']) {
            $data['update'] = [
                'fecFin' => getFechaActual(),
            ];

            $data['tabla'] = 'compras.itemTarifarioHistorico';
            $data['where'] = [
                'idItemTarifario' => $post['idItemTarifario'],
                'fecFin' => NULL
            ];

            $subUpdate = $this->model->actualizarItemTarifario($data);
            $data = [];

            $data['insert'] = [
                'idItemTarifario' => $post['idItemTarifario'],
                'fecIni' => getFechaActual(),
                'fecFin' => NULL,
                'costo' => $post['costo'],
            ];

            $data['tabla'] = 'compras.itemTarifarioHistorico';

            $subInsert = $this->model->insertarItemTarifario($data);
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

        if ($existeItemActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idItemTarifario'] = $post['idItemTarifario'];
            $result['data']['idItem'] = $post['idItem'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarActualItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $data['update'] = [
            'flag_actual' => 0
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItem' => $post['idItem']
        ];

        $insert = $this->model->actualizarItemTarifario($data);
        $data = [];

        $data['update'] = [
            'flag_actual' => 1
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $insert = $this->model->actualizarItemTarifario($data);
        $data = [];

        if (!$insert['estado']) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('exitosoPersonalizado', ['message' => 'Se actualizó el item actual']);
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarEstadoItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $update = $this->model->actualizarItemTarifario($data);
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
