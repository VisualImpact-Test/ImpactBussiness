<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Articulo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Articulo', 'model');
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
            'assets/custom/js/articulo'
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Articulos';
        $config['data']['message'] = 'Lista de Articulos';
        $config['data']['tipoArticulo'] = $this->model->obtenerTipoArticulo()['query']->result_array();
        $config['data']['marcaArticulo'] = $this->model->obtenerMarcaArticulo()['query']->result_array();
        $config['data']['categoriaArticulo'] = $this->model->obtenerCategoriaArticulo()['query']->result_array();
        $config['view'] = 'modulos/articulo/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionArticulos($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Articulo/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentArticulo']['datatable'] = 'tb-articulo';
        $result['data']['views']['idContentArticulo']['html'] = $html;
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

    public function formularioRegistroArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoArticulo'] = $this->model->obtenerTipoArticulo()['query']->result_array();
        $dataParaVista['marcaArticulo'] = $this->model->obtenerMarcaArticulo()['query']->result_array();
        $dataParaVista['categoriaArticulo'] = $this->model->obtenerCategoriaArticulo()['query']->result_array();

        $articulosLogistica =  $this->model->obtenerArticulosLogistica();
        foreach ($articulosLogistica as $key => $row) {
            $data['articulos'][1][$row['value']]['value'] = $row['value'];
            $data['articulos'][1][$row['value']]['label'] = $row['label'];
            $data['articulos'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
            $data['articulos'][1][$row['value']]['um'][$row['idum']] = $row['um'];
        }
        foreach ($data['articulos'] as $k => $r) {
            $data['articulos'][$k] = array_values($data['articulos'][$k]);
        }
        $data['articulos'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Articulo';
        $result['data']['html'] = $this->load->view("modulos/Articulo/formularioRegistro", $dataParaVista, true);
        $result['data']['articulosLogistica'] = $data['articulos'];

        echo json_encode($result);
    }

    public function formularioActualizacionArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoArticulo'] = $this->model->obtenerTipoArticulo()['query']->result_array();
        $dataParaVista['marcaArticulo'] = $this->model->obtenerMarcaArticulo()['query']->result_array();
        $dataParaVista['categoriaArticulo'] = $this->model->obtenerCategoriaArticulo()['query']->result_array();

        $articulosLogistica =  $this->model->obtenerArticulosLogistica();
        foreach ($articulosLogistica as $key => $row) {
            $data['articulos'][1][$row['value']]['value'] = $row['value'];
            $data['articulos'][1][$row['value']]['label'] = $row['label'];
            $data['articulos'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
            $data['articulos'][1][$row['value']]['um'][$row['idum']] = $row['um'];
        }
        foreach ($data['articulos'] as $k => $r) {
            $data['articulos'][$k] = array_values($data['articulos'][$k]);
        }
        $data['articulos'][0] = array();
        $result['data']['existe'] = 0;

        $dataParaVista['informacionArticulo'] = $this->model->obtenerInformacionArticulos($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Articulo';
        $result['data']['html'] = $this->load->view("modulos/Articulo/formularioActualizacion", $dataParaVista, true);
        $result['data']['articulosLogistica'] = $data['articulos'];

        echo json_encode($result);
    }

    public function registrarArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'idTipoArticulo' => $post['tipo'],
            'idMarcaArticulo' => $post['marca'],
            'idCategoriaArticulo' => $post['categoria'],
            'idArticuloLogistica' => $post['idArticuloLogistica']
        ];

        $validacionExistencia = $this->model->validarExistenciaArticulo($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.articulo';

        $insert = $this->model->insertarArticulo($data);
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

    public function actualizarArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idArticulo' => $post['idArticulo'],

            'nombre' => $post['nombre'],
            'idTipoArticulo' => $post['tipo'],
            'idMarcaArticulo' => $post['marca'],
            'idCategoriaArticulo' => $post['categoria'],
            'idArticuloLogistica' => $post['idArticuloLogistica']
        ];

        $validacionExistencia = $this->model->validarExistenciaArticulo($data['update']);
        unset($data['update']['idArticulo']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.articulo';
        $data['where'] = [
            'idArticulo' => $post['idArticulo']
        ];

        $insert = $this->model->actualizarArticulo($data);
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

    public function actualizarEstadoArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.articulo';
        $data['where'] = [
            'idArticulo' => $post['idArticulo']
        ];

        $update = $this->model->actualizarArticulo($data);
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
