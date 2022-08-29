<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autorizacion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Autorizacion', 'model');
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
            'assets/custom/js/Finanzas/autorizacion'
        );

        $config['data']['icon'] = 'fa fa-shield';
        $config['data']['title'] = 'Autorizaciones';
        $config['data']['message'] = 'Lista de Proveedores';
        // $config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
        // $config['data']['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
        // $config['data']['estado'] = $this->model->obtenerEstado()['query']->result_array();
        $config['view'] = 'modulos/Finanzas/autorizacion/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $data = $this->model->getAutorizaciones($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($data)) {
            $html = $this->load->view("modulos/Finanzas/Autorizacion/reporte", ['data' => $data], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentAutorizaciones']['datatable'] = 'tb-autorizacion';
        $result['data']['views']['idContentAutorizaciones']['html'] = $html;
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

    public function frmActualizarAutorizacion(){
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];
        $dataParaVista['data'] = $this->model->getAutorizaciones($post)['query']->row_array();
        $dataParaVista['anexos'] = $this->model->obtenerInformacionAutorizacionArchivos(['idAutorizacion'=> $post['id']])['query']->result_array();
        $html = $this->load->view("modulos/Finanzas/Autorizacion/frmActualizarAutorizacion", $dataParaVista, true);
        
        $result['result'] = 1;
        $result['flagUpdate'] = $dataParaVista['data']['idAutorizacionEstado'] == AUTH_ESTADO_PENDIENTE ? true : false;
        $result['data']['html'] = $html;
        $result['msg']['title'] = 'Autorización';
        echo json_encode($result);
    }

    public function actualizarAutorizacion()
    {   
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $estado = !empty($post['autorizacion']) ? AUTH_ESTADO_ACEPTADO : AUTH_ESTADO_RECHAZADO;

        $updateAutorizacion = [
            'idAutorizacionEstado' => $estado,
            'fechaModificacion' => getActualDateTime(),
        ];
        $whereAutorizacion = [
            'idAutorizacion' => $post['idAutorizacion'],
        ];
        $rs1 = $this->db->update("compras.autorizacion",$updateAutorizacion,$whereAutorizacion);

        $updateCotDetalle = [
            'costo' => $post['nuevoValor'],
            'gap' => $post['nuevoGap'],
            'costoAnterior' => $post['costoAnterior'],
        ];

        $whereCotDetalle = [
            'idCotizacionDetalle' => $post['idCotizacionDetalle'],
        ];

        $rs2 = $this->db->update("compras.cotizacionDetalle",$updateCotDetalle,$whereCotDetalle);

        $result['data']['title'] = 'Autorización';

        if($rs1 && $rs2){
            $result['result'] = 1;
            $result['msg']['content'] = getMensajeGestion('actualizacionExitosa');

            $data['anexos_arreglo'] = [];
            $data['anexos'] = [];

            $data['anexos_arreglo'] = getDataRefactorizada([
                'base64' => $post['anexo-file'],
                'type' => $post['anexo-type'],
                'name' => $post['anexo-name'],
                
            ]);

            foreach($data['anexos_arreglo'] as $anexo){
                $data['anexos'][] = [
                    'base64' => $anexo['base64'],
                    'type' => $anexo['type'],
                    'name' => $anexo['name'],
                    'carpeta'=> 'cotizacion',
                    'nombreUnico' => "AUTH".uniqid(),
                ];
            }
        
            $data['idAutorizacion'] = $post['idAutorizacion'];
            $insertAnexos = $this->model->insertarAutorizacionAnexos($data);

            $this->db->trans_complete();
        }else{
            $result['result'] = 0;
            $result['msg']['content'] = getMensajeGestion('actualizacionErronea');
        }

        
        
        echo json_encode($result);
    }
}
