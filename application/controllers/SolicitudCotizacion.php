<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SolicitudCotizacion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_cotizacion', 'model');
        $this->load->model('M_Item', 'model_item');
        $this->load->model('M_control', 'model_control');
        $this->load->model('M_proveedor','model_proveedor');
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
            'assets/custom/js/solicitudCotizacion'
        );

        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Solicitudes de Cotizacion';
        $config['data']['message'] = 'Lista de Cotizacions Enviadas';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/SolicitudCotizacion/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $post['estadoCotizacion'] = 2;
        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/SolicitudCotizacion/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentCotizacion']['datatable'] = 'tb-cotizacion';
        $result['data']['views']['idContentCotizacion']['html'] = $html;
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

    public function formularioSolicitudCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        
        $dataParaVista = [];
        $dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

        //Obteniendo Solo los Items Nuevos para verificacion de los proveedores
        $dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=> $post['id'],'idItemEstado' => 2])['query']->result_array();

        $dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
        $dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
        $proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado'=>2])['query']->result_array();
        foreach($proveedores as $k => $p){
            $dataParaVista['proveedores'][$p['idProveedor']] = $p;
        }

        $itemServicio =  $this->model->obtenerItemServicio();
        foreach ($itemServicio as $key => $row) {
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
        }
        foreach ($data['itemServicio'] as $k => $r) {
            $data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
        }
        $data['itemServicio'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Verificar Solicitud de Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioRegistro", $dataParaVista, true);
        $result['data']['itemServicio'] = $data['itemServicio'];

        echo json_encode($result);
    }

    public function formularioVisualizacionCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $data = $this->model->obtenerInformacionCotizacionDetalle($post)['query']->result_array();
        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
            $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['cabecera']['codCotizacion'] = $row['codCotizacion'];
            $dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
            $dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
            $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
            $dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
            $dataParaVista['detalle'][$key]['fecha'] = !empty($row['fechaModificacion']) ? $row['fechaModificacion'] : $row['fechaCreacion'];
            $dataParaVista['detalle'][$key]['cotizacionDetalleEstado'] = $row['cotizacionDetalleEstado'];
        }

        $dataParaVista['estados'] = $this->model_control->get_estados_cotizacion()->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Visualizar Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }


    public function actualizarCotizacion()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        
        $data['update'] = [
            'nombre' => $post['nombre'],
            'idCuenta' => $post['cuentaForm'],
            'idCentroCosto' => $post['cuentaCentroCostoForm'],
            'fechaRequerimiento' => $post['fechaRequerimiento'],
            'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
            'gap' => $post['gapForm'],
            'fee' => $post['feeForm'],
            // 'total' => $post['totalForm'],
            // 'idPrioridad' => $post['prioridadForm'],
            // 'motivo' => $post['motivoForm'],
            // 'comentario' => $post['comentarioForm'],
            // 'idCotizacionEstado' => $post['tipoRegistro']
        ];
        

        $validacionExistencia = $this->model->validarExistenciaCotizacion(['nombre' => $post['nombre'], 'idCotizacion' => $post['idCotizacion']]);
        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.cotizacion';
        $data['where'] = [
            'idCotizacion' => $post['idCotizacion']
        ];
        $update = $this->model->actualizarCotizacion($data);
        $data = [];

        $post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
        $post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
        $post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
        $post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
        $post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
        $post['costoForm'] = checkAndConvertToArray($post['costoForm']);
        $post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
        $post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);

        foreach ($post['nameItem'] as $k => $r) {
            $data['update'][] = [
                'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
                'idItem' => (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL,
                'idItemTipo' => $post['tipoItemForm'][$k],
                'nombre' => $post['nameItem'][$k],
                'cantidad' => $post['cantidadForm'][$k],
                'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
                'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
                'idItemEstado' => $post['idEstadoItemForm'][$k],
                'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
                'idCotizacionDetalleEstado' => 2, 
                'caracteristicas'=> !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL, 
            ];
        }

        $data['tabla'] = 'compras.cotizacionDetalle';
        $data['where'] = 'idCotizacionDetalle';
        $updateDetalle = $this->model->actualizarCotizacionDetalle($data);
        $data = [];

        $estadoEmail = true;
        // if($post['tipoRegistro'] == 2){
        //     $estadoEmail = $this->enviarCorreo($insert['id']);
        // }

        if (!$update['estado'] || !$updateDetalle['estado'] || !$estadoEmail) {
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


    public function enviarSolicitudProveedor()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        if(empty($post['checkItem'])){
            $result['result'] = 1;
            $result['data']['html'] = createMessage(['type'=>2,'message'=>'Debe seleccionar al menos un item']);
            $result['msg']['title'] = 'Alerta';
            goto respuesta;
        }
        $dataParaVista = [];

        $post['checkItem'] = checkAndConvertToArray($post['checkItem']);
        foreach ($post['nameItem'] as $k => $r) {

            if(empty($post['checkItem'][$k])) continue;
            $data['select'][] = $post['idCotizacionDetalle'][$k];
        }
        $items = implode(",",$data['select']);
        $dataParaVista['detalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=>$post['idCotizacion'],'idItemEstado' => 2, 'idCotizacionDetalle' => $items])['query']->result_array();

        foreach($dataParaVista['detalle'] as $k => $row){
            $data['insertProveedor'] = [
                'idProveedor' => $post['proveedorForm']
            ];
        }

        $html = $this->load->view("modulos/SolicitudCotizacion/correoProveedor", $dataParaVista, true);
        $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'FormularioProveedor/Cotizaciones'], true);
        $config = [
            'to' => 'aaron.ccenta@visualimpact.com.pe',
            'asunto' => 'Solicitud de Cotizacion',
            'contenido' => $correo,
        ];
        email($config);


        $result['result'] = 1;
        $result['data']['html'] = createMessage(['type'=>1,'message'=>'Solicitud enviada al proveedor']);
        $result['msg']['title'] = 'Solicitud Enviada';

        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

    
}
