<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotizacion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Cotizacion', 'model');
        $this->load->model('M_Item', 'model_item');
        $this->load->model('M_control', 'model_control');
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
            'assets/custom/js/cotizacion'
        );

        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Cotizacion';
        $config['data']['message'] = 'Lista de Cotizacions';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/Cotizacion/index';

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
            $html = $this->load->view("modulos/Cotizacion/reporte", ['datos' => $dataParaVista], true);
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

    public function formularioRegistroCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

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
        $result['msg']['title'] = 'Registrar Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioRegistro", $dataParaVista, true);
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
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'fechaEmision' => getActualDateTime(),
            'idCuenta' => $post['cuentaForm'],
            'idCentroCosto' => $post['cuentaCentroCostoForm'],
            'idCotizacionEstado' => 2
        ];

        $validacionExistencia = $this->model->validarExistenciaCotizacion($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.cotizacion';

        $insert = $this->model->insertarCotizacion($data);
        $data = [];

        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
        $post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
        $post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
        $post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);

        foreach ($post['nameItem'] as $k => $r) {
            $data['insert'][] = [
                'idCotizacion' => $insert['id'],
                'idItem' => (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL,
                'idItemTipo' => $post['tipoItemForm'][$k],
                'nombre' => $post['nameItem'][$k],
                'cantidad' => $post['cantidadForm'][$k],
                'idItemEstado' => $post['idEstadoItemForm'][$k],
                'idProveedor' => empty($post['idProveedor'][$k]) ? NULL : $post['idProveedor'][$k],
                'idCotizacionDetalleEstado' => 1,
                'fechaCreacion' => getActualDateTime()
            ];
        }

        $data['tabla'] = 'compras.cotizacionDetalle';

        $insertDetalle = $this->model->insertarCotizacionDetalle($data);
        $data = [];

        $estadoEmail = $this->enviarCorreo($insert['id']);

        if (!$insert['estado'] || !$insertDetalle['estado'] || !$estadoEmail) {
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

    public function actualizarEstadoCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.cotizacion';
        $data['where'] = [
            'idCotizacion' => $post['idCotizacion']
        ];

        $update = $this->model->actualizarCotizacion($data);
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

    public function enviarCorreo($idCotizacion)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'teamsystem@visualimpact.com.pe',
            'smtp_pass' => '#nVi=0sN0ti$',
            'mailtype' => 'html'
        );

        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->set_newline("\r\n");

        $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
        $this->email->to('harry.pineda@visualimpact.com.pe');

        $data = [];
        $dataParaVista = [];
        $data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
            $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        }

        $dataParaVista['link'] = base_url() . index_page() . 'Cotizacion';

        // $bcc = array(
        //     'team.sistemas@visualimpact.com.pe',
        // );
        // $this->email->bcc($bcc);

        $this->email->subject('IMPACTBUSSINESS - NUEVA COTIZACION GENERADA');
        $html = $this->load->view("modulos/Cotizacion/correo/informacionProveedor", $dataParaVista, true);
        $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Cotizacion'], true);
        $this->email->message($correo);

        $estadoEmail = $this->email->send();

        return $estadoEmail;
    }

    public function generarCotizacionPDF($idCotizacion = '')
    {
        require_once('../mpdf/mpdf.php');
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        if (!empty($idCotizacion)) {
            $data = [];
            $dataParaVista = [];
            $data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();

            foreach ($data as $key => $row) {
                $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
                $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
                $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
                $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
                $dataParaVista['cabecera']['tipoCotizacion'] = $row['tipoCotizacion'];
                $dataParaVista['cabecera']['fecha'] = $row['fecha'];
                $dataParaVista['detalle'][$key]['item'] = $row['item'];
                $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
                $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
                $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
            }
            if (count($dataParaVista) == 0) exit();

            $contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", array(), true);
            $contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);
            $contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/body", $dataParaVista, true);
            $contenido['style'] = '<style>table { border-collapse: collapse; }table.tb-detalle th, table.tb-detalle td { border: 1px solid #484848; padding:5px; }.square { margin-right: 15px; border: 1px solid #000; text-align: center; }body {font-size: 12px;}</style>';

            require APPPATH . '/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();

            $mpdf->SetHTMLHeader($contenido['header']);
            $mpdf->SetHTMLFooter($contenido['footer']);
            $mpdf->AddPage();
            $mpdf->WriteHTML($contenido['style']);
            $mpdf->WriteHTML($contenido['body']);

            header('Set-Cookie: fileDownload=true; path=/');
            header('Cache-Control: max-age=60, must-revalidate');
            $mpdf->Output('Cotizacion.pdf', 'D');
        }

        $this->aSessTrack[] = ['idAccion' => 9];
    }

    // public function formularioRegistroItem()
    // {
    //     $result = $this->result;
    //     $post = json_decode($this->input->post('data'), true);

    //     $dataParaVista = [];

    //     $dataParaVista['tipoItem'] = $this->model_item->obtenerTipoItem()['query']->result_array();
    //     $dataParaVista['marcaItem'] = $this->model_item->obtenerMarcaItem()['query']->result_array();
    //     $dataParaVista['categoriaItem'] = $this->model_item->obtenerCategoriaItem()['query']->result_array();
    //     $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

    //     $dataParaVista['nombreItem'] = verificarEmpty($post['nombre'], 3);

    //     $itemsLogistica =  $this->model_item->obtenerItemsLogistica();
    //     foreach ($itemsLogistica as $key => $row) {
    //         $data['items'][1][$row['value']]['value'] = $row['value'];
    //         $data['items'][1][$row['value']]['label'] = $row['label'];
    //         $data['items'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
    //         $data['items'][1][$row['value']]['um'][$row['idum']] = $row['um'];
    //     }
    //     foreach ($data['items'] as $k => $r) {
    //         $data['items'][$k] = array_values($data['items'][$k]);
    //     }
    //     $data['items'][0] = array();
    //     $result['data']['existe'] = 0;

    //     $result['result'] = 1;
    //     $result['msg']['title'] = 'Registrar Item';
    //     $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioRegistroItem", $dataParaVista, true);
    //     $result['data']['itemsLogistica'] = $data['items'];

    //     echo json_encode($result);
    // }

    // public function registrarItem()
    // {
    //     $result = $this->result;
    //     $post = json_decode($this->input->post('data'), true);

    //     $data = [];
    //     $params = [];

    //     $data['insert'] = [
    //         'nombre' => $post['nombre'],
    //         'idTipoItem' => $post['tipo'],
    //         'idMarcaItem' => $post['marca'],
    //         'idCategoriaItem' => $post['categoria'],
    //         'idItemLogistica' => $post['idItemLogistica']
    //     ];

    //     $validacionExistencia = $this->model_item->validarExistenciaItem($data['insert']);

    //     if (!empty($validacionExistencia['query']->row_array())) {
    //         $result['result'] = 0;
    //         $result['msg']['title'] = 'Alerta!';
    //         $result['msg']['content'] = getMensajeGestion('registroRepetido');
    //         goto respuesta;
    //     }

    //     $data['tabla'] = 'compras.item';

    //     $insert = $this->model_item->insertarItem($data);
    //     $data = [];

    //     if (!$insert['estado']) {
    //         $result['result'] = 0;
    //         $result['msg']['title'] = 'Alerta!';
    //         $result['msg']['content'] = getMensajeGestion('registroErroneo');
    //     } else {
    //         $result['result'] = 1;
    //         $result['msg']['title'] = 'Hecho!';
    //         $result['msg']['content'] = getMensajeGestion('registroExitoso');
    //     }

    //     //INSERTAR TARIFARIO
    //     $params = [
    //         'idProveedor' => $post['proveedor'],
    //         'costo' => $post['costo'],
    //         'idItem' => $insert['id']
    //     ];

    //     $insertTarifarioItem = $this->registrarTarifarioItem($params);

    //     //ACTUALIZAR PRESUPUESTO
    //     $params = [
    //         'idCotizacion' => $post['idCotizacion'],
    //         'nombre' => $post['nombre'],
    //         'idItem' => $insert['id'],
    //         'idProveedor' => $post['proveedor'],
    //         'costo' => $post['costo']
    //     ];

    //     $updateCotizacion = $this->actualizarItemsCotizacion($params);

    //     respuesta:
    //     echo json_encode($result);
    // }

    // public function actualizarItemsCotizacion($params = [])
    // {
    //     $result = $this->result;

    //     $data = [];

    //     $data['update'] = [
    //         'idItem' => $params['idItem'],
    //         'idProveedor' => $params['idProveedor'],
    //         'costo' => $params['costo'],
    //         'idEstadoItem' => 1
    //     ];

    //     $data['tabla'] = 'compras.cotizacionDetalle';
    //     $data['where'] = [
    //         'idCotizacion' => $params['idCotizacion'],
    //         'nombre' => $params['nombre']
    //     ];

    //     $update = $this->model->actualizarCotizacion($data);
    //     $data = [];

    //     if (!$update['estado']) {
    //         $result['result'] = 0;
    //         $result['msg']['title'] = 'Alerta!';
    //         $result['msg']['content'] = getMensajeGestion('registroErroneo');
    //     } else {
    //         $result['result'] = 1;
    //         $result['msg']['title'] = 'Hecho!';
    //         $result['msg']['content'] = getMensajeGestion('registroExitoso');
    //     }

    //     return $result;
    // }

    // public function registrarTarifarioItem($params = [])
    // {
    //     $result = $this->result;

    //     $data = [];

    //     $data['insert'] = [
    //         'idItem' => $params['idItem'],
    //         'idProveedor' => $params['idProveedor'],
    //         'costo' => $params['costo'],
    //         'flag_actual' => 1
    //     ];

    //     $data['tabla'] = 'compras.tarifarioItem';

    //     $insert = $this->model->insertarTarifarioItem($data);
    //     $data = [];

    //     $data['insert'] = [
    //         'idTarifarioItem' => $insert['id'],
    //         'fecIni' => getFechaActual(),
    //         'fecFin' => NULL,
    //         'costo' => $params['costo'],
    //     ];

    //     $data['tabla'] = 'compras.tarifarioItemHistorico';

    //     $subInsert = $this->model->insertarTarifarioItem($data);

    //     $data = [];

    //     if (!$insert['estado'] or !$subInsert['estado']) {
    //         $result['result'] = 0;
    //         $result['msg']['title'] = 'Alerta!';
    //         $result['msg']['content'] = getMensajeGestion('registroErroneo');
    //     } else {
    //         $result['result'] = 1;
    //         $result['msg']['title'] = 'Hecho!';
    //         $result['msg']['content'] = getMensajeGestion('registroExitoso');
    //     }

    //     return $result;
    // }

    // public function formularioGenerarCotizacion()
    // {
    //     $result = $this->result;
    //     $post = json_decode($this->input->post('data'), true);

    //     $dataParaVista = [];

    //     $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

    //     $dataParaVista['items'] = $post['items'];

    //     $result['result'] = 1;
    //     $result['msg']['title'] = 'Generar Cotizacion';
    //     $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioGenerarCotizacion", $dataParaVista, true);

    //     echo json_encode($result);
    // }

    // public function registrarCotizacion()
    // {
    //     $result = $this->result;
    //     $post = json_decode($this->input->post('data'), true);

    //     $data = [];
    //     $params = [];

    //     $data['insert'] = [
    //         'nombre' => 'COTIZACION',
    //         'idProveedor' => $post['proveedorCotizacion'],
    //         'fecha' => getFechaActual(),
    //         'estado' => 1
    //     ];

    //     $data['tabla'] = 'compras.cotizacion';

    //     $insert = $this->model_item->insertarItem($data);
    //     $data = [];

    //     $post['itemCotizacion'] = checkAndConvertToArray($post['itemCotizacion']);
    //     $post['costoCotizacion'] = checkAndConvertToArray($post['costoCotizacion']);

    //     foreach ($post['itemCotizacion'] as $k => $r) {
    //         $idItem = $this->model->obtenerItem($post['itemCotizacion'][$k])['query']->row_array();
    //         $data['insert'][] = [
    //             'idCotizacion' => $insert['id'],
    //             'idItem' => $idItem['idItem'],
    //             'costo' => $post['costoCotizacion'][$k]
    //         ];
    //     }

    //     $data['tabla'] = 'compras.cotizacionDetalle';

    //     $insertDetalle = $this->model->insertarCotizacionDetalle($data);
    //     $data = [];

    //     if (!$insert['estado']) {
    //         $result['result'] = 0;
    //         $result['msg']['title'] = 'Alerta!';
    //         $result['msg']['content'] = getMensajeGestion('registroErroneo');
    //     } else {
    //         $result['result'] = 1;
    //         $result['msg']['title'] = 'Hecho!';
    //         $result['msg']['content'] = getMensajeGestion('registroExitoso');
    //     }

    //     respuesta:
    //     echo json_encode($result);
    // }
}
