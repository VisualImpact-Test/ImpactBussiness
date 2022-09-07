<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Configuracion/M_Usuario', 'model');
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
            'assets/custom/js/Configuracion/usuario'
        );

        $config['data']['icon'] = 'fad fa-user';
        $config['data']['title'] = 'Usuarios';
        $config['data']['message'] = 'Lista de Usuarios';
        $config['view'] = 'modulos/Configuracion/Usuario/index';

        $this->view($config);
    }

    public function reporteDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $post['demo'] = ($this->session->userdata('demo')) ? ['1','0'] : ['0'];
        $dataParaVista = $this->model->obtenerInformacionUsuarios($post)->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Configuracion/Usuario/Detalle/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentUsuarioDetalle']['datatable'] = 'tb-usuario-detalle';
        $result['data']['views']['idContentUsuarioDetalle']['html'] = $html;
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

    public function formularioUsuarioFirmaRegistroDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];

        $dataParaVista['informacionUsuario'] = $this->model->obtenerInformacionUsuarios($post)->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Firma'; //'Actualizar Usuario';
        $result['data']['html'] = $this->load->view("modulos/Configuracion/Usuario/Detalle/formularioActualizacion", $dataParaVista, true);
        echo json_encode($result);
    }

    public function registrarFirmaDetalle()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $archivo = [
          'base64' => $post['file-item'],
          'name' => $post['file-name'],
          'type' => $post['file-type'],
          'carpeta' => 'usuarioFirma',
          'nombreUnico' => 'usuarioFirma_'.$post['idUsuario'].'_'.str_replace(':', '', $this->hora)
        ];
        $archivoName = $this->saveFileWasabi($archivo);

        $tipoArchivo = explode('/',$archivo['type']);
        $insertUsuarioFirma = [
          'nombre_inicial' => $archivo['name'],
          'nombre_archivo' => $archivoName,
          'nombre_unico' => $archivo['nombreUnico'],
          'extension' => $tipoArchivo[1],
          'fechaReg' => getFechaActual(),
          'horaReg' => time_change_format(getActualDateTime())
        ];
        $id = $this->model->guardarDatos('sistema.usuarioFirma', $insertUsuarioFirma);

        $this->model->actualizarDatos('sistema.usuario',[ 'idUsuarioFirma' => $id ],['idUsuario' => $post['idUsuario']]);

        $historico = $this->model->getUsuarioFirmaHistorico($post['idUsuario'])->row_array();
        if (!empty($historico)) {
          $updateUFH = $this->model->actualizarDatos('sistema.usuarioFirmaHistorico',['fecFin' => getFechaActual(-1)],['idUsuarioFirmaHistorico' => $historico['idUsuarioFirmaHistorico']]);
        }

        $insertUsuarioFirmaHistorico = [
          'idUsuario' => $post['idUsuario'],
          'idUsuarioFirma' => $id,
          'fecIni' => getFechaActual()
        ];
        $idUFH = $this->model->guardarDatos('sistema.usuarioFirmaHistorico',$insertUsuarioFirmaHistorico);

        $result['result'] = 1;
        $result['msg']['title'] = 'Hecho!';
        $result['msg']['content'] = getMensajeGestion('registroExitoso');
        echo json_encode($result);
    }

}
