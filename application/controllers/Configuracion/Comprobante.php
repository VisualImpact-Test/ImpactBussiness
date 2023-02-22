<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comprobante extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_Comprobante', 'm_comprobante');

        $this->titulo = [
            'index' => 'Comprobante',
            'registrar' => 'Registrar comprobante',
            'editar' => 'Editar comprobante',
            'masivo' => 'Guardar masivo comprobante',
            'excel' => 'Importar excel',
            'historial' => 'Historial de importación masiva'
        ];
        $this->maxSizeRowExcel = 1001;
        $this->listaColumnas = ['NOMBRE','ESTADO'];
        $this->columnas = count($this->listaColumnas);
        $this->database = 'ImpactBussiness.compras.comprobante';
        $this->tableMasivo = 'DataMart_PG.dbo.usuarios_guardado_masivo';
        $this->flag = 'compras.comprobante';
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
            'assets/custom/js/configuracion/gestionConfiguracion',
            'assets/custom/js/configuracion/comprobante'
        );

        $config['data']['icon'] = 'fas fa-books';
        $config['view'] = 'modulos/configuracion/Categoria/index';
        $config['data']['title'] = $this->titulo['index'];
        $config['nav']['menu_active'] = '86';
        $config['view'] = 'modulos/Configuracion/Comprobante/index';

        $this->view($config);
    }

    public function getLista(){
        ini_set('precision', 5);
        $result = $this->result;
        $data = $this->m_comprobante->getDatos($this->database,'idComprobante');

        $result['result'] = 1;
        if (count($data) < 1) {
            $result['data']['html'] = getMensajeGestion('noRegistros');
        } else {
            $dataParaVista['data'] = $data;
            $result['data']['html'] = $this->load->view("modulos/Configuracion/Comprobante/tabla", $dataParaVista, true);
        }
        echo  json_encode($result);
    }
    public function getFormNew()
    {
        ini_set('precision', 5);
        $result = $this->result;
        $result['msg']['title'] = $this->titulo['editar'];
        $post = json_decode($this->input->post('data'), true);

        $result['result'] = 1;
        $result['data']['width'] = '45%';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Comprobante/nuevoFormulario", '',true);

        echo json_encode($result);
    }
    public function store(){
        ini_set('precision', 5);
        $result = $this->result;
        $json = json_decode($this->input->post('data'));
        $data = [
            'nombre' => $json->nombre
        ];
        $elementosAValidar = [
            'nombre' => ['requerido']
        ];
        $validaciones = verificarValidacionesBasicas($elementosAValidar, $data);
        $result['data']['validaciones'] = $validaciones;

        if (!verificarSeCumplenValidaciones($validaciones)) {
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
            goto responder;
        }

        $response = $this->m_comprobante->insert($this->database,$data);
        if (!$response) {
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }
        responder:
        echo json_encode($result);
    }

    public function getFormUpdate()
    {
        ini_set('precision', 5);
        $result = $this->result;
        $result['msg']['title'] = $this->titulo['editar'];
        $post = json_decode($this->input->post('data'), true);
        $dataParaVista['data'] = $this->m_comprobante->get($post)[0];
        $result['result'] = 1;
        $result['data']['width'] = '45%';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Comprobante/formUpdate", $dataParaVista, true);

        //$this->aSessTrack = $this->m_encuestas->aSessTrack;
        echo json_encode($result);
    }

    public function update(){
        ini_set('precision', 5);
        $result = $this->result;
        $json = json_decode($this->input->post('data'));
        $data = [
            'nombre' => $json->nombre,
        ];
        $elementosAValidar = [
            'nombre' => ['requerido'],
        ];
        $validaciones = verificarValidacionesBasicas($elementosAValidar, $data);
        $result['data']['validaciones'] = $validaciones;

        if (!verificarSeCumplenValidaciones($validaciones)) {
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
            goto responder;
        }

        $where = "idComprobante = ".$json->idElemento."";
        $response = $this->m_comprobante->actualizarSimple($this->database,$where,$data);
        if (!$response) {
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('actualizacionErronea');
        } else {
            $result['result'] = 1;
            $result['msg']['content'] = getMensajeGestion('actualizacionExitosa');
        }
        responder:
        echo json_encode($result);
    }

    public function cambiarEstado(){
        $result = $this->result;
        $json = json_decode($this->input->post('data'));
        $data = [
            'estado' => $json->estado,
        ];
        $where = "idComprobante = ".$json->id."";
        $response = $this->m_comprobante->actualizarSimple($this->database,$where,$data);
        if (!$response) {
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('cambioEstadoErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['content'] = getMensajeGestion('cambioEstadoExitoso');
        }
        echo json_encode($result);
    }



}
