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
        $this->load->model('M_formularioProveedor','model_formulario_proveedor');
        $this->load->model('M_Autorizacion','model_autorizacion');

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
            'assets/libs/fileDownload/jquery.fileDownload',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/solicitudCotizacion',
           
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
	
	public function formularioSolicitudCotizacionfecha()
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
        $result['data']['html'] = $this->load->view("modulos/SolicitudCotizacion/formularioRegistrofecha", $dataParaVista, true);
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
        $data['tabla'] = 'compras.cotizacion';
        
        $data = [];
        
        $data['update'] = [
            'nombre' => $post['nombre'],
            'idCuenta' => $post['cuentaForm'],
            'idCentroCosto' => $post['cuentaCentroCostoForm'],
            //'idCentroCosto' => trim(explode("-",$post['cuentaCentroCostoForm'])[1]),
            'fechaRequerida' => !empty($post['fechaRequerida']) ? $post['fechaRequerida'] : NULL,
            'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
            'fee' => $post['feeForm'],
            'total' => $post['totalForm'],
            // 'idPrioridad' => $post['prioridadForm'],
            // 'motivo' => $post['motivoForm'],
            'comentario' => $post['comentarioForm'],
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
        $post['caracteristicasProveedor'] = checkAndConvertToArray($post['caracteristicasProveedor']);
        $post['costoForm'] = checkAndConvertToArray($post['costoForm']);
        $post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
        $post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);
        $post['gapForm'] = checkAndConvertToArray($post['gapForm']);
        $post['precioForm'] = checkAndConvertToArray($post['precioForm']);
        $post['linkForm'] = checkAndConvertToArray($post['linkForm']);
        
        foreach ($post['nameItem'] as $k => $r) {
            $data['update'][] = [
                'idCotizacionDetalle' => $post['idCotizacionDetalle'][$k],
                'idCotizacion' => $post['idCotizacion'],
                'idItem' => (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL,
                'idItemTipo' => $post['tipoItemForm'][$k],
                'nombre' => $post['nameItem'][$k],
                'cantidad' => $post['cantidadForm'][$k],
                'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
                'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
                'gap' => !empty($post['gapForm'][$k]) ? $post['gapForm'][$k] : NULL,
                'precio' => !empty($post['precioForm'][$k]) ? $post['precioForm'][$k] : NULL,
                'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
                'idItemEstado' => $post['idEstadoItemForm'][$k],
                'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
                'idCotizacionDetalleEstado' => 2, 
                'caracteristicas'=> !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL, 
                'caracteristicasCompras'=> !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL, 
            ];

            if(!empty($post["file-name[$k]"])){
                $data['archivos_arreglo'][$k] = getDataRefactorizada([
                    'base64' => $post["file-item[$k]"],
                    'type' => $post["file-type[$k]"],
                    'name' => $post["file-name[$k]"],
                ]);
                foreach($data['archivos_arreglo'][$k] as $key => $archivo){
                    $data['archivos'][$k][] = [
                    'base64' => $archivo['base64'],
                    'type' => $archivo['type'],
                    'name' => $archivo['name'],
                    'carpeta'=> 'cotizacion',
                    'nombreUnico' => uniqid(),
                    ];
                }
            }

            if(!empty($post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"])){
                switch ($post['tipoItemForm'][$k]) {
                    case COD_SERVICIO['id']:
                        $data['subDetalle'][$k] = getDataRefactorizada([
                            'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
                            'nombre' => $post["nombreSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
                            'cantidad' => $post["cantidadSubItemServicio[{$post['idCotizacionDetalle'][$k]}]"],
                        ]);
                        break;
                    
                    case COD_DISTRIBUCION['id']:
                        $data['subDetalle'][$k] = getDataRefactorizada([
                            'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
                            'unidadMedida' => $post["unidadMedidaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'tipoServicio' => $post["tipoServicioSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'costo' => $post["costoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'cantidad' => $post["cantidadSubItemDistribucion[{$post['idCotizacionDetalle'][$k]}]"],
                        ]);
                        break;
                    
                    case COD_TEXTILES['id']:
                        $data['subDetalle'][$k] = getDataRefactorizada([
                            'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
                            'talla' => $post["tallaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'tela' => $post["telaSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'color' => $post["colorSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                            'cantidad' => $post["cantidadTextil[{$post['idCotizacionDetalle'][$k]}]"],
                        ]);
                        break;
    
                    case COD_TARJETAS_VALES['id']:
                        $data['subDetalle'][$k] = getDataRefactorizada([
                            'idCotizacionDetalleSub' => $post["idCotizacionDetalleSub[{$post['idCotizacionDetalle'][$k]}]"],
                            'monto' => $post["montoSubItem[{$post['idCotizacionDetalle'][$k]}]"],
                        ]);
                        break;
    
                    default:
                        $data['subDetalle'][$k] = [];
                        break;
                }
            }

        }
        $data['archivoEliminado'] = $post['archivoEliminado'];

        $data['tabla'] = 'compras.cotizacionDetalle';
        $data['where'] = 'idCotizacionDetalle';
        $updateDetalle = $this->model->actualizarCotizacionDetalleArchivos($data);
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

            if($post['tipoRegistro'] == ESTADO_CONFIRMADO_COMPRAS){
                $data['tabla'] = 'compras.cotizacion';
                $data['update'] = [
                    'idCotizacionEstado' => $post['tipoRegistro'],
                ];
                $data['where'] = [
                    'idCotizacion' => $post['idCotizacion'],
                ];
    
                $this->model->actualizarCotizacion($data);
    
                $insertCotizacionHistorico = [
                    'idCotizacionEstado' => ESTADO_CONFIRMADO_COMPRAS, 
                    'idCotizacion' => $post['idCotizacion'],
                    'idUsuarioReg' => $this->idUsuario,
                    'estado' => true,
                ];
                $insertCotizacionHistorico = $this->model->insertar(['tabla'=>TABLA_HISTORICO_ESTADO_COTIZACION,'insert'=>$insertCotizacionHistorico]);
            }
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

      
        $dataParaVista = [];

        $post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        foreach ($post['nameItem'] as $k => $r) {
            $idCotizacionDetalle_ = $post['idCotizacionDetalle'][$k];

            if(empty($post["checkItem[{$idCotizacionDetalle_}]"])) continue;
            $data['select'][] = $idCotizacionDetalle_;
        }

        if(empty($data['select'])){
            $result['result'] = 1;
            $result['data']['html'] = createMessage(['type'=>2,'message'=>'Debe seleccionar al menos un item']);
            $result['msg']['title'] = 'Alerta';
            goto respuesta;
        }

        $items = implode(",",$data['select']);
        $dataParaVista['detalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=>$post['idCotizacion'],'cotizacionInterna' => true, 'idCotizacionDetalle' => $items])['query']->result_array();

       
        $data = [];
        $post['proveedorSolicitudForm'] = checkAndConvertToArray($post['proveedorSolicitudForm']);

        $cotizacionProveedores = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor(['idCotizacion' => $post['idCotizacion']])->result_array();
        $cotizacionProveedor = [];
        $cotizacionProveedorDetalle = [];
        foreach($cotizacionProveedores as $p_cotizacion){
            $cotizacionProveedor[$p_cotizacion['idProveedor']] = $p_cotizacion;
            $cotizacionProveedorDetalle[$p_cotizacion['idProveedor']][$p_cotizacion['idCotizacion']][$p_cotizacion['idItem']] = $p_cotizacion;
        }
        $rs['estado'] = true;
        $usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => USER_COORDINADOR_COMPRAS])['query']->result_array();
        $ccCompras = [];
        foreach($usuariosCompras as $usuario){
            $ccCompras[] = $usuario['email'];
        }
        
        foreach($post['proveedorSolicitudForm'] as $idProveedor){
            if(empty($cotizacionProveedor[$idProveedor])){

                $data['tabla'] = 'compras.cotizacionDetalleProveedor';
                $data['insert'] = [
                    'idProveedor' => $idProveedor,
                    'idCotizacion' => $post['idCotizacion'],
                    'estado' => true,
                ];
                $rs = $this->model->insertar($data);
                $idCotizacionDetalleProveedor = $rs['id'];
            }

            if(!empty($cotizacionProveedor[$idProveedor])){
                $idCotizacionDetalleProveedor = $cotizacionProveedor[$idProveedor]['idCotizacionDetalleProveedor'];
            }

            $data = [];
            foreach($dataParaVista['detalle'] as $k => $row){
                $row_cotizacion = isset($cotizacionProveedorDetalle[$idProveedor][$post['idCotizacion']]) ? $cotizacionProveedorDetalle[$idProveedor][$post['idCotizacion']] : [] ;
                if(empty($row_cotizacion[$row['idItem']])){
                    $data['insert'][] = [
                        'idCotizacionDetalleProveedor' => $idCotizacionDetalleProveedor,
                        'idItem' => $row['idItem'],
                        'costo'=> $row['costo'],
                        'flag_activo' => 1,
                        'fechaCreacion' => getActualDateTime(),
                        'idCotizacionDetalle' => $row['idCotizacionDetalle'],
                        'estado' => 1,
                    ];


                }
            }

            $rsDet = true;
            if(!empty($data['insert'])){       
                $rsDet = $this->model_formulario_proveedor->insertarMasivoDetalleProveedor(['tabla' => 'compras.cotizacionDetalleProveedorDetalle','insert' => $data['insert'],'post' => $post]);
            }

            if(!$rs['estado'] || !$rsDet){
                $result['result'] = 1;
                $result['data']['html'] = createMessage(['type'=>2,'message'=>'No se pudo enviar la solicitud']);
                $result['msg']['title'] = 'Alerta';
    
                goto respuesta;
            }


            $proveedor = $this->model_proveedor->obtenerInformacionProveedores(['idProveedor' => $idProveedor])['query']->row_array();
            $html = $this->load->view("modulos/SolicitudCotizacion/correoProveedor", $dataParaVista, true);
            $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "FormularioProveedor/Cotizaciones/{$post['idCotizacion']}"], true);
            $config = [
                'to' => $proveedor['correoContacto'],
                'cc' => $ccCompras,
                'asunto' => 'Solicitud de cotizacion',
                'contenido' => $correo,
            ];
            email($config);
        }
        



        $result['result'] = 1;
        $result['data']['html'] = createMessage(['type'=>1,'message'=>'Solicitud enviada al proveedor']);
        $result['msg']['title'] = 'Solicitud Enviada';

        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

    public function verCotizacionesProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        
        $post['idCotizacionDetalle'] = checkAndConvertToArray($post['idCotizacionDetalle']);
        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        foreach ($post['nameItem'] as $k => $r) {
            $data['select'][] = $post['idCotizacionDetalle'][$k];
        }
        $detalles = implode(",",$data['select']);
        $dataParaVista['detalle'] = $this->model_formulario_proveedor->obtenerInformacionCotizacionProveedor(['idCotizacionDetalle' => $detalles])->result_array();

        $html = $this->load->view("modulos/SolicitudCotizacion/viewCotizacionesProveedor", $dataParaVista, true);

        $result['result'] = 1;
        $result['msg']['title'] = 'Solicitudes';
        $result['data']['html'] = $html;

        echo json_encode($result);
    }

    public function viewSolicitudCotizacionInterna($idCotizacion = '')
    {
        
        if(empty($idCotizacion)){
            redirect('SolicitudCotizacion','refresh');
        }
        
        $config = array();

        $this->load->library('Mobile_Detect');

		$detect = $this->mobile_detect;
        
        $config['data']['col_dropdown'] = 'four column';
        $detect->isMobile() ? $config['data']['col_dropdown'] = '' : '';
        $detect->isTablet() ? $config['data']['col_dropdown'] = 'three column' : '';
         
        $config['nav']['menu_active'] = '131';
        $config['css']['style'] = array(
            'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/css/floating-action-button'
        );
        $config['js']['script'] = array(
            // 'assets/libs/datatables/responsive.bootstrap4.min',
            // 'assets/custom/js/core/datatables-defaults',
            'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/languages/all',
            'assets/libs/handsontable@7.4.2/dist/moment/moment',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/viewAgregarCotizacion'
        );
        
        $config['data']['cotizacion'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->row_array();
        //Obteniendo Solo los Items Nuevos para verificacion de los proveedores
        $config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion([
            'idCotizacion'=> $idCotizacion,
            'cotizacionInterna' => true,
            'noTipoItem' => COD_DISTRIBUCION['id']
        ])['query']->result_array();
        $cotizacionDetalleSub =  $this->model->obtenerInformacionDetalleCotizacionSub(
            [
            'idCotizacion'=> $idCotizacion,
            'cotizacionInterna' => true,
            'noTipoItem' => COD_DISTRIBUCION['id']
            ]
        )['query']->result_array();

        foreach($cotizacionDetalleSub as $sub){
            $config['data']['cotizacionDetalleSub'][$sub['idCotizacionDetalle']][$sub['idItemTipo']][] = $sub;
        }

        $archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => true])['query']->result_array();
        $cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => true])['query']->result_array();
        $cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => true])['query']->result_array();

        foreach($archivos as $archivo){
            $config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
        }
        foreach($cotizacionProveedores as $cotizacionProveedor){
            $config['data']['cotizacionProveedor'][$cotizacionProveedor['idCotizacionDetalle']] = $cotizacionProveedor;
            $config['data']['cotizacionProveedorRegistrados'][$cotizacionProveedor['idCotizacionDetalle']][] = $cotizacionProveedor['razonSocial'];
        }
        foreach($cotizacionProveedoresVista as $cotizacionProveedorVista){
            $config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
        }

        $config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
        $config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
        $proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado'=>2])['query']->result_array();

        foreach($proveedores as $proveedor){
            $config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
        } 

        $itemServicio =  $this->model->obtenerItemServicio();
        foreach ($itemServicio as $key => $row) {
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cotizacionInterna'] = $row['cotizacionInterna'];
        }
        foreach ($data['itemServicio'] as $k => $r) {
            $data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
        }
        $data['itemServicio'][0] = array();
        $config['data']['itemServicio'] = $data['itemServicio'];

        $config['single'] = true;
        
        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Cotizacion';
        $config['data']['message'] = 'Lista de Cotizacions';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
        $config['data']['siguienteEstado'] = ESTADO_CONFIRMADO_COMPRAS;
        $config['data']['controller'] = 'SolicitudCotizacion';
        $config['data']['disabled'] = false;
        $config['view'] = 'modulos/SolicitudCotizacion/viewFormularioActualizarCotizacion';

        $this->view($config);
    }

    public function viewUpdateOper($idOper = '')
    {

        if(empty($idOper)){
            redirect('SolicitudCotizacion','refresh');
        }
        
        $config = array();

        $this->load->library('Mobile_Detect');

		$detect = $this->mobile_detect;
        
        $config['data']['col_dropdown'] = 'four column';
        $detect->isMobile() ? $config['data']['col_dropdown'] = '' : '';
        $detect->isTablet() ? $config['data']['col_dropdown'] = 'three column' : '';
         
        $config['nav']['menu_active'] = '131';
        $config['css']['style'] = array(
            'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/css/floating-action-button'
        );
        $config['js']['script'] = array(
            // 'assets/libs/datatables/responsive.bootstrap4.min',
            // 'assets/custom/js/core/datatables-defaults',
            'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/languages/all',
            'assets/libs/handsontable@7.4.2/dist/moment/moment',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/viewAgregarCotizacion'
        );
        $oper = $this->model->obtenerInformacionOper(['idOper' => $idOper])['query']->result_array();
        $ids = [];
        foreach($oper as $v){
            $ids[] = $v['idCotizacion'];
            $config['data']['oper'][$v['idOper']] = $v;
        }

        $idCotizacion = implode(",",$ids);

        $config['data']['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
        //Obteniendo Solo los Items Nuevos para verificacion de los proveedores
        $config['data']['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => false])['query']->result_array();
        $autorizaciones = $this->model_autorizacion->getAutorizaciones(['idCotizacion'=> $idCotizacion])['query']->result_array();

        foreach($autorizaciones as $autorizacion){
            $config['data']['autorizaciones'][$autorizacion['idCotizacionDetalle']] = $autorizacion;
        }

        
        $archivos = $this->model->obtenerInformacionDetalleCotizacionArchivos([
            'idCotizacion'=> $idCotizacion,
            'cotizacionInterna' => false,
            'noTipoItem' => COD_DISTRIBUCION
            ])['query']->result_array();
        $cotizacionProveedores = $this->model->obtenerInformacionDetalleCotizacionProveedores(['idCotizacion'=> $idCotizacion,'union'=>true])['query']->result_array();
        $cotizacionProveedoresVista = $this->model->obtenerInformacionDetalleCotizacionProveedoresParaVista(['idCotizacion'=> $idCotizacion,'union' => true])['query']->result_array();

        foreach($archivos as $archivo){
            $config['data']['cotizacionDetalleArchivos'][$archivo['idCotizacionDetalle']][] = $archivo;
        }
        foreach($cotizacionProveedores as $cotizacionProveedor){
            $config['data']['cotizacionProveedor'][$cotizacionProveedor['idCotizacionDetalle']] = $cotizacionProveedor;
        }
        foreach($cotizacionProveedoresVista as $cotizacionProveedorVista){
            $config['data']['cotizacionProveedorVista'][$cotizacionProveedorVista['idCotizacionDetalle']][] = $cotizacionProveedorVista;
            
        }

        $config['data']['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
        $config['data']['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();
        $proveedores = $this->model_proveedor->obtenerInformacionProveedores(['proveedorEstado'=>2])['query']->result_array();

        foreach($proveedores as $proveedor){
            $config['data']['proveedores'][$proveedor['idProveedor']] = $proveedor;
        } 

        $itemServicio =  $this->model->obtenerItemServicio();
        foreach ($itemServicio as $key => $row) {
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cotizacionInterna'] = $row['cotizacionInterna'];
        }
        foreach ($data['itemServicio'] as $k => $r) {
            $data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
        }
        $data['itemServicio'][0] = array();
        $config['data']['itemServicio'] = $data['itemServicio'];

        $config['single'] = true;
        
        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Cotizacion';
        $config['data']['message'] = 'Lista de Cotizacions';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['data']['solicitantes'] = $this->model->obtenerSolicitante()['query']->result_array();
        $config['data']['siguienteEstado'] = ESTADO_OC_ENVIADA;
        $config['data']['controller'] = 'SolicitudCotizacion';
        $config['data']['disabled'] = false;
        $config['data']['idOper'] = $idOper;
        $config['view'] = 'modulos/SolicitudCotizacion/viewFormularioGenerarOrdenCompra';

        $this->view($config);
    }

    public function registrarOrdenCompra()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $post['idCotizacion'] = checkAndConvertToArray($post['idCotizacion']);

        $updateCotizacion = [];
        $insertHistoricoCotizacion = [];
        foreach($post['idCotizacion'] as $idCotizacion){

            $updateCotizacion[] = [
                'idCotizacion' => $idCotizacion,
                'idCotizacionEstado' => ESTADO_OC_ENVIADA,
            ];
            
            $insertHistoricoCotizacion[] = [
                'idCotizacionEstado' => ESTADO_OC_ENVIADA,
                'idCotizacion' => $idCotizacion,
                'idUsuarioReg' => $this->idUsuario,
            ];
        }
        //Insert OC
        $data = [];
        $data['oc'] = getDataRefactorizada([
            'idProveedor' => $post["idProveedorForm"],
            'idCotizacionDetalle' => $post["idCotizacionDetalle"],
        ]);

        $oper = $this->db->get_where("compras.oper",['idOper' => $post['idOper']])->row_array();

        foreach ($data['oc'] as $row) {

            if(empty($row['idProveedor'])) continue;

            if(empty($data['proveedor'][$row['idProveedor']])){
                $insert_oc = [
                    'idProveedor' => $row['idProveedor'],
                    'estado' => true,
                    'idUsuarioReg' => $this->idUsuario,
                    'requerimiento' => $oper['requerimiento']
                ];
                $rs_oc = $this->model->insertar(['tabla'=>'compras.ordenCompra','insert'=>$insert_oc]);
            }

            $data['insert']['oc_detalle'][] = [
                'idOrdenCompra' => $rs_oc['id'],
                'idCotizacionDetalle' => $row['idCotizacionDetalle']
            ];
            
            $data['proveedor'][$row['idProveedor']] = $row['idProveedor'];
        }

        if(!empty($data['insert']['oc_detalle'])){
            $rs_det = $this->model->insertarMasivo("compras.ordenCompraDetalle",$data['insert']['oc_detalle']);
        }

        $updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion',$updateCotizacion,'idCotizacion');
        $insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION,$insertHistoricoCotizacion);

        if($rs_det && $updateCotizacion && $insertHistoricoCotizacion){
            $result['result'] = 1;
            $result['msg']['title'] = 'Generar OC';
            $result['data']['html'] = getMensajeGestion('registroExitoso');
            $dataParaVista = []; 
            $ids = implode(',',$post['idCotizacion']);
            $dataParaVista['detalle'] = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();

            $html = $this->load->view("modulos/Cotizacion/correoGeneracionOC", $dataParaVista, true);
            $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "FormularioProveedor/viewOrdenCompra/{$rs_oc['id']}"], true);
            $config = [
                'to' => 'aaron.ccenta@visualimpact.com.pe',
                'asunto' => 'Generación de OC',
                'contenido' => $correo,
            ];
            email($config);
        }else{
            $result['result'] = 0;
            $result['msg']['title'] = 'Generar OC';
            $result['data']['html'] = getMensajeGestion('registroErroneo');
        }

        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

    public function getOrdenesCompra()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        // $ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'],'idOrdenCompra' => $idOrdenCompra,'estado' => 1])['query']->result_array();
		$dataParaVista['data'] = $this->model->obtenerInformacionOrdenCompra()['query']->result_array();

        $result['result'] = 1;
        $result['data']['width'] = '90%';
        $result['msg']['title'] = 'Ordenes de compra';
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/tableOrdenCompra", $dataParaVista, true);

        echo json_encode($result);
    }

    public function frmGenerarOper()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $ids = implode(',' ,$post['ids']);
        $cotizaciones = $this->model->obtenerInformacionCotizacion(['id' => $ids])['query']->result_array();
        $cotizacionDetalle = $this->model->obtenerInformacionCotizacionDetalle(['idsCotizacion' => $ids])['query']->result_array();

        $dataParaVista = [];
        $dataParaVista['totalOper'] = 0;
        foreach($cotizaciones as $row){
            $dataParaVista['cuenta'][$row['idCuenta']] = [
                'id' => $row['idCuenta'],
                'value' => $row['cuenta'] 
            ];
            $dataParaVista['cuentaCentroCosto'][$row['idCuentaCentroCosto']] = [
                'id' => $row['idCuentaCentroCosto'],
                'value' => $row['cuentaCentroCosto'] 
            ];

            $dataParaVista['totalOper'] += $row['total']; 
        }

        foreach($cotizacionDetalle as $rowDetalle){
            $dataParaVista['detalle'][$rowDetalle['idCotizacion']][$rowDetalle['idCotizacionDetalle']] = $rowDetalle;
        }
        $dataParaVista['cotizaciones'] = $cotizaciones;
        $dataParaVista['usuarios'] = $this->model->obtenerUsuarios()->result_array();

        $result['result'] = 1;
        $result['data']['width'] = '95%';
        $result['msg']['title'] = 'GENERAR OPER';
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/formRegistrarOper", $dataParaVista, true);

        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

     //filtroReporte

     public function filtroOper()
     {
         $result = $this->result;
         $post = json_decode($this->input->post('data'), true);
         $dataParaVista = [];
         
         $dataParaVista = $this->model->obtenerInformacionOperSolicitud($post)['query']->result_array();
 
         $html = getMensajeGestion('noRegistros');
         if (!empty($dataParaVista)) {
             $html = $this->load->view("modulos/SolicitudCotizacion/reporteFiltroSolicitud", ['datos' => $dataParaVista], true);
         }
 
         $result['result'] = 1;
         $result ['data']['html'] = $html;
         $result['msg']['title'] = 'Oper Registrados';
         $result['data']['width'] = '80%';
         
         echo json_encode($result);
     }
     
     public function formPreviewOrdenCompra()
     {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        $dataParaVista = [];
        
        $dataParaVista = $this->model->obtenerInformacionOperSolicitud($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/SolicitudCotizacion/reporteFiltroSolicitud", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result ['data']['html'] = $html;
        $result['msg']['title'] = 'Oper Registrados';
        $result['data']['width'] = '80%';
        
        echo json_encode($result);
    }
}
