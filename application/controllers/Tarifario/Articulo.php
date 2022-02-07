<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Articulo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tarifario/M_Articulo', 'model');
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
            'assets/custom/js/Tarifario/articulo'
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Articulos';
        $config['data']['message'] = 'Lista de Articulos';
        $config['data']['tipoArticulo'] = $this->model->obtenerTipoArticulo()['query']->result_array();
        $config['data']['marcaArticulo'] = $this->model->obtenerMarcaArticulo()['query']->result_array();
        $config['data']['categoriaArticulo'] = $this->model->obtenerCategoriaArticulo()['query']->result_array();
        $config['data']['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();
        $config['view'] = 'modulos/Tarifario/articulo/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionTarifarioArticulos($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Tarifario/Articulo/reporte", ['datos' => $dataParaVista], true);
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

    public function formularioRegistroTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $articulos =  $this->model->obtenerArticulos();
        foreach ($articulos as $key => $row) {
            $data['articulos'][1][$row['value']]['value'] = $row['value'];
            $data['articulos'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['articulos'] as $k => $r) {
            $data['articulos'][$k] = array_values($data['articulos'][$k]);
        }
        $data['articulos'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Tarifario de Articulo';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Articulo/formularioRegistro", $dataParaVista, true);
        $result['data']['articulos'] = $data['articulos'];

        echo json_encode($result);
    }

    public function formularioActualizacionTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $articulos =  $this->model->obtenerArticulos();
        foreach ($articulos as $key => $row) {
            $data['articulos'][1][$row['value']]['value'] = $row['value'];
            $data['articulos'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['articulos'] as $k => $r) {
            $data['articulos'][$k] = array_values($data['articulos'][$k]);
        }
        $data['articulos'][0] = array();
        $result['data']['existe'] = 0;

        $dataParaVista['informacionArticulo'] = $this->model->obtenerInformacionTarifarioArticulos($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Tarifario de Articulo';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Articulo/formularioActualizacion", $dataParaVista, true);
        $result['data']['articulos'] = $data['articulos'];

        echo json_encode($result);
    }

    public function formularioHistorialTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['datos'] = $this->model->obtenerInformacionTAHistorico($post)['query']->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Historial Tarifario de Articulo';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Articulo/formularioHistorial", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeArticuloActual = 0;

        $data['insert'] = [
            'idArticulo' => $post['idArticulo'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1
        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarTarifarioArticuloActual($data['insert']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['insert']['flag_actual'] = 0;
                $existeArticuloActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaTarifarioArticulo($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.tarifarioArticulo';

        $insert = $this->model->insertarTarifarioArticulo($data);
        $data = [];

        $data['insert'] = [
            'idTarifarioArticulo' => $insert['id'],
            'fecIni' => getFechaActual(),
            'fecFin' => NULL,
            'costo' => $post['costo'],
        ];

        $data['tabla'] = 'compras.tarifarioArticuloHistorico';

        $subInsert = $this->model->insertarTarifarioArticulo($data);

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

        if ($existeArticuloActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un articulo que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idTarifarioArticulo'] = $insert['id'];
            $result['data']['idArticulo'] = $post['idArticulo'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeArticuloActual = 0;

        $data['update'] = [
            'idTarifarioArticulo' => $post['idTarifarioArticulo'],

            'idArticulo' => $post['idArticulo'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1
        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarTarifarioArticuloActual($data['update']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['update']['flag_actual'] = 0;
                $existeArticuloActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaTarifarioArticulo($data['update']);
        unset($data['update']['idTarifarioArticulo']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.tarifarioArticulo';
        $data['where'] = [
            'idTarifarioArticulo' => $post['idTarifarioArticulo']
        ];

        $update = $this->model->actualizarTarifarioArticulo($data);
        $data = [];
        $actualizacionHistoricos = true;

        if ($post['costoAnterior'] != $post['costo']) {
            $data['update'] = [
                'fecFin' => getFechaActual(),
            ];

            $data['tabla'] = 'compras.tarifarioArticuloHistorico';
            $data['where'] = [
                'idTarifarioArticulo' => $post['idTarifarioArticulo'],
                'fecFin' => NULL
            ];

            $subUpdate = $this->model->actualizarTarifarioArticulo($data);
            $data = [];

            $data['insert'] = [
                'idTarifarioArticulo' => $post['idTarifarioArticulo'],
                'fecIni' => getFechaActual(),
                'fecFin' => NULL,
                'costo' => $post['costo'],
            ];

            $data['tabla'] = 'compras.tarifarioArticuloHistorico';

            $subInsert = $this->model->insertarTarifarioArticulo($data);
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

        if ($existeArticuloActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un articulo que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idTarifarioArticulo'] = $post['idTarifarioArticulo'];
            $result['data']['idArticulo'] = $post['idArticulo'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarActualTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $data['update'] = [
            'flag_actual' => 0
        ];

        $data['tabla'] = 'compras.tarifarioArticulo';
        $data['where'] = [
            'idArticulo' => $post['idArticulo']
        ];

        $insert = $this->model->actualizarTarifarioArticulo($data);
        $data = [];

        $data['update'] = [
            'flag_actual' => 1
        ];

        $data['tabla'] = 'compras.tarifarioArticulo';
        $data['where'] = [
            'idTarifarioArticulo' => $post['idTarifarioArticulo']
        ];

        $insert = $this->model->actualizarTarifarioArticulo($data);
        $data = [];

        if (!$insert['estado']) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('exitosoPersonalizado', ['message' => 'Se actualizó el articulo actual']);
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarEstadoTarifarioArticulo()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.tarifarioArticulo';
        $data['where'] = [
            'idTarifarioArticulo' => $post['idTarifarioArticulo']
        ];

        $update = $this->model->actualizarTarifarioArticulo($data);
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
