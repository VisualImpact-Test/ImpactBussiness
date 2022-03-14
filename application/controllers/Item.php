<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Item', 'model');
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
            'assets/custom/js/item'
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Items';
        $config['data']['message'] = 'Lista de Items';
        $config['data']['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $config['data']['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $config['data']['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
        $config['view'] = 'modulos/item/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionItems($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Item/reporte", ['datos' => $dataParaVista], true);
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

    public function formularioRegistroItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();

        $itemsLogistica =  $this->model->obtenerItemsLogistica();
        foreach ($itemsLogistica as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
            $data['items'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
            $data['items'][1][$row['value']]['um'][$row['idum']] = $row['um'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Item';
        $result['data']['html'] = $this->load->view("modulos/Item/formularioRegistro", $dataParaVista, true);
        $result['data']['itemsLogistica'] = $data['items'];

        echo json_encode($result);
    }

    public function formularioActualizacionItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();

        $itemsLogistica =  $this->model->obtenerItemsLogistica();
        foreach ($itemsLogistica as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
            $data['items'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
            $data['items'][1][$row['value']]['um'][$row['idum']] = $row['um'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $dataParaVista['informacionItem'] = $this->model->obtenerInformacionItems($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Item';
        $result['data']['html'] = $this->load->view("modulos/Item/formularioActualizacion", $dataParaVista, true);
        $result['data']['itemsLogistica'] = $data['items'];

        echo json_encode($result);
    }

    public function registrarItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'caracteristicas' => $post['caracteristicas'],
            'idItemTipo' => $post['tipo'],
            'idItemMarca' => $post['marca'],
            'idItemCategoria' => $post['categoria'],
            'idItemLogistica' => $post['idItemLogistica']
        ];

        $validacionExistencia = $this->model->validarExistenciaItem($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.item';

        $insert = $this->model->insertarItem($data);
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

    public function actualizarItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idItem' => $post['idItem'],

            'nombre' => $post['nombre'],
            'caracteristicas' => $post['caracteristicas'],
            'idItemTipo' => $post['tipo'],
            'idItemMarca' => $post['marca'],
            'idItemCategoria' => $post['categoria'],
            'idItemLogistica' => $post['idItemLogistica']
        ];

        $validacionExistencia = $this->model->validarExistenciaItem($data['update']);
        unset($data['update']['idItem']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.item';
        $data['where'] = [
            'idItem' => $post['idItem']
        ];

        $insert = $this->model->actualizarItem($data);
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

    public function actualizarEstadoItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.item';
        $data['where'] = [
            'idItem' => $post['idItem']
        ];

        $update = $this->model->actualizarItem($data);
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
