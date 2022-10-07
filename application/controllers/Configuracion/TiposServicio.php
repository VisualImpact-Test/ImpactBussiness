<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TiposServicio extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_TiposServicio', 'model');
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
            'assets/custom/js/Configuracion/tiposServicio'
        );

        $config['data']['icon'] = 'fad fa-list';
        $config['data']['title'] = 'Tipos Servicios';
        $config['data']['message'] = 'Lista de Tipos Servicios';
        $config['view'] = 'modulos/Configuracion/TiposServicio/index';

        $this->view($config);
    }

    public function reporteDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionTiposServicio($post)->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Configuracion/TiposServicio/Detalle/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentTiposServicioDetalle']['datatable'] = 'tb-tiposServicio-detalle';
        $result['data']['views']['idContentTiposServicioDetalle']['html'] = $html;
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
    public function formularioRegistroTiposServicioDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $dataParaVista = [
          'tipoServicioUbigeo' => $this->model->obtenerTipoServicioUbigeo(['estado' => [1]])->result_array(),
          'unidadMedida' => $this->model->obtenerUnidadMedida(['estado' => [1]])->result_array()
        ];

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Tipos Servicio';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/TiposServicio/Detalle/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarTiposServicioDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $data = [];

        $data['insert'] = [
          'idTipoServicioUbigeo' => $post['tipoServicioUbigeo'],
          'idItemTipo' => 7,
          'idUnidadMedida' => $post['unidadMedida'],
          'nombre' => $post['nombre'].' '.$post['nombreExtra'],
        ];

        $validacionExistencia = $this->model->obtenerInformacionTiposServicio($data['insert']);

        if (!empty($validacionExistencia->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['insert']['costo'] = $post['costo'];

        $insert = $this->model->guardarDatos('compras.tipoServicio', $data['insert']);

        if ($insert['estado']) {
          $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        } else {
          $result['result'] = 0;
          $result['msg']['title'] = 'Alerta!';
          $result['msg']['content'] = getMensajeGestion('registroErroneo');
        }

        respuesta:
        echo json_encode($result);

    }
    public function actualizarEstadoTipoServicioDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $datos = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $filtro = [
            'idTipoServicio' => $post['idTipoServicio']
        ];

        $update = $this->model->actualizarDatos('compras.tipoServicio',$datos, $filtro);

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
    public function formularioActualizacionTipoServicioDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [
          'tipoServicioUbigeo' => $this->model->obtenerTipoServicioUbigeo(['estado' => [1]])->result_array(),
          'unidadMedida' => $this->model->obtenerUnidadMedida(['estado' => [1]])->result_array(),
          'informacion' => $this->model->obtenerInformacionTiposServicio($post)->row_array()
        ];

        // $dataParaVista['informacion'] = $this->model->obtenerInformacionTiposServicio($post)->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Tipo Servicio';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/TiposServicio/Detalle/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function actualizarTipoServicioDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $datos = [
          'NO_idTipoServicio' => $post['id'],
          'idTipoServicioUbigeo' => $post['tipoServicioUbigeo'],
          'idItemTipo' => 7,
          'idUnidadMedida' => $post['unidadMedida'],
          'nombre' => $post['nombre'],
        ];

        $validacionExistencia = $this->model->obtenerInformacionTiposServicio($datos);
        unset($datos['NO_idTipoServicio']);

        if (!empty($validacionExistencia->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $filtro = [
            'idTipoServicio' => $post['id']
        ];

        $update = $this->model->actualizarDatos('compras.tipoServicio', $datos, $filtro);

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
