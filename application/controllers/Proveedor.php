<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proveedor extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Proveedor', 'model');
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
            'assets/custom/js/proveedor'
        );

        $config['data']['icon'] = 'fas fa-cart-plus';
        $config['data']['title'] = 'Proveedores';
        $config['data']['message'] = 'Lista de Proveedores';
        $config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
        $config['data']['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
        $config['data']['estado'] = $this->model->obtenerEstado()['query']->result_array();
        $config['view'] = 'modulos/proveedor/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $dataParaVista = [];
        $departamentosCobertura = [];
        $provinciasCobertura = [];
        $distritosCobertura = [];
        $data = $this->model->obtenerInformacionProveedores($post)['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista[$row['idProveedor']] = [
                'razonSocial' => $row['razonSocial'],
                'nroDocumento' => $row['nroDocumento'],
                'rubro' => $row['rubro'],
                'metodoPago' => $row['metodoPago'],
                'departamento' => $row['departamento'],
                'provincia' => $row['provincia'],
                'distrito' => $row['distrito'],
                'direccion' => $row['direccion'],
                'nombreContacto' => $row['nombreContacto'],
                'correoContacto' => $row['correoContacto'],
                'numeroContacto' => $row['numeroContacto'],
                'informacionAdicional' => $row['informacionAdicional'],
                'idEstado' => $row['idEstado'],
                'estado' => $row['estado'],
                'estadoIcono' => $row['estadoIcono'],
                'estadoToggle' => $row['estadotoggle'],
            ];
            $departamentosCobertura[$row['idProveedor']][$row['zc_departamento']] = $row['zc_departamento'];
            $provinciasCobertura[$row['idProveedor']][$row['zc_provincia']] = $row['zc_provincia'];
            $distritosCobertura[$row['idProveedor']][$row['zc_distrito']] = $row['zc_distrito'];
        }

        foreach ($dataParaVista as $key => $row) {
            $dataParaVista[$key]['departamentosCobertura'] = implode(', ', $departamentosCobertura[$key]);
            $dataParaVista[$key]['provinciasCobertura'] = implode(', ', $provinciasCobertura[$key]);
            $dataParaVista[$key]['distritosCobertura'] = implode(', ', $distritosCobertura[$key]);
        }

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Proveedor/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentProveedor']['datatable'] = 'tb-proveedor';
        $result['data']['views']['idContentProveedor']['html'] = $html;
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

    public function formularioRegistroProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['rubro'] = $this->model->obtenerRubro()['query']->result_array();
        $dataParaVista['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
        $ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

        $dataParaVista['departamento'] = [];
        $dataParaVista['provincia'] = [];
        $dataParaVista['distrito'] = [];

        foreach ($ciudad as $ciu) {
            $dataParaVista['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
            $dataParaVista['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
            $dataParaVista['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
            $dataParaVista['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
        }

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Proveedor';
        $result['data']['html'] = $this->load->view("modulos/Proveedor/formularioRegistro", $dataParaVista, true);

        echo json_encode($result);
    }

    public function formularioActualizacionProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $departamentosCobertura = [];
        $provinciasCobertura = [];
        $distritosCobertura = [];
        $data = $this->model->obtenerInformacionProveedores($post)['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista = [
                'idProveedor' => $row['idProveedor'],
                'razonSocial' => $row['razonSocial'],
                'nroDocumento' => $row['nroDocumento'],
                'idRubro' => $row['idRubro'],
                'rubro' => $row['rubro'],
                'idMetodoPago' => $row['idMetodoPago'],
                'metodoPago' => $row['metodoPago'],
                'cod_departamento' => $row['cod_departamento'],
                'departamento' => $row['departamento'],
                'cod_provincia' => $row['cod_provincia'],
                'provincia' => $row['provincia'],
                'cod_ubigeo' => $row['cod_ubigeo'],
                'distrito' => $row['distrito'],
                'direccion' => $row['direccion'],
                'nombreContacto' => $row['nombreContacto'],
                'correoContacto' => $row['correoContacto'],
                'numeroContacto' => $row['numeroContacto'],
                'informacionAdicional' => $row['informacionAdicional'],
                'estado' => $row['estado'],
                'estadoIcono' => $row['estadoIcono'],
                'estadoToggle' => $row['estadotoggle'],
            ];
            $departamentosCobertura[$row['zc_departamento']] = $row['zc_departamento'];
            $provinciasCobertura[$row['zc_provincia']] = $row['zc_provincia'];
            $distritosCobertura[$row['zc_distrito']] = $row['zc_distrito'];
        }

        $dataParaVista['departamentosCobertura'] = $departamentosCobertura;
        $dataParaVista['provinciasCobertura'] = $provinciasCobertura;
        $dataParaVista['distritosCobertura'] = $distritosCobertura;

        $dataParaVista['listadoDepartamentos'] = [];
        $dataParaVista['listadoProvincias'] = [];
        $dataParaVista['listadoDistritos'] = [];
        $dataParaVista['listadoDistritosUbigeo'] = [];

        $ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

        foreach ($ciudad as $ciu) {
            $dataParaVista['listadoDepartamentos'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
            $dataParaVista['listadoProvincias'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
            $dataParaVista['listadoDistritos'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
            $dataParaVista['listadoDistritosUbigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
        }

        $dataParaVista['listadoRubros'] = $this->model->obtenerRubro()['query']->result_array();
        $dataParaVista['listadoMetodosPago'] = $this->model->obtenerMetodoPago()['query']->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Proveedor';
        $dataParaVista['disabled'] = false;

        if($post['formularioValidar']){
            $result['msg']['title'] = 'Validar Proveedor';
            $dataParaVista['disabled'] = true;
        }
        $result['data']['html'] = $this->load->view("modulos/Proveedor/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'razonSocial' => $post['razonSocial'],
            'idTipoDocumento' => 3,
            'nroDocumento' => $post['ruc'],
            'idRubro' => $post['rubro'],
            'idMetodoPago' => $post['metodoPago'],
            'cod_ubigeo' => $post['distrito'],
            'direccion' => $post['direccion'],
            'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
            'idEstado' => 1,
            'nombreContacto' => $post['nombreContacto'],
            'correoContacto' => $post['correoContacto'],
            'numeroContacto' => $post['numeroContacto']
        ];

        $validacionExistencia = $this->model->validarExistenciaProveedor($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.proveedor';

        $insert = $this->model->insertarProveedor($data);
        $data = [];

        $post['regionCobertura'] = checkAndConvertToArray($post['regionCobertura']);
        $post['provinciaCobertura'] = empty($post['provinciaCobertura']) ? '' : checkAndConvertToArray($post['provinciaCobertura']);
        $post['distritoCobertura'] = empty($post['distritoCobertura']) ? '' : checkAndConvertToArray($post['distritoCobertura']);

        if (!empty($post['distritoCobertura'])) {
            foreach ($post['distritoCobertura'] as $key => $value) {
                $data['insert'][] = [
                    'idProveedor' => $insert['id'],
                    'cod_departamento' => explode('-', $value)[0],
                    'cod_provincia' => explode('-', $value)[1],
                    'cod_distrito' => explode('-', $value)[2]
                ];
            }
        } else if (!empty($post['provinciaCobertura'])) {
            foreach ($post['provinciaCobertura'] as $key => $value) {
                $data['insert'][] = [
                    'idProveedor' => $insert['id'],
                    'cod_departamento' => explode('-', $value)[0],
                    'cod_provincia' => explode('-', $value)[1],
                    'cod_distrito' => NULL
                ];
            }
        } else if (!empty($post['regionCobertura'])) {
            foreach ($post['regionCobertura'] as $key => $value) {
                $data['insert'][] = [
                    'idProveedor' => $insert['id'],
                    'cod_departamento' => $value,
                    'cod_provincia' => NULL,
                    'cod_distrito' => NULL
                ];
            }
        }

        $data['tabla'] = 'compras.zonaCobertura';

        $second_insert = $this->model->insertarProveedorCobertura($data);
        $data = [];

        if (!$insert['estado'] || !$second_insert['estado']) {
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

    public function actualizarProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idProveedor' => $post['idProveedor'],

            'razonSocial' => $post['razonSocial'],
            'nroDocumento' => $post['ruc'],
            'idRubro' => $post['rubro'],
            'idMetodoPago' => $post['metodoPago'],
            'cod_ubigeo' => $post['distrito'],
            'direccion' => $post['direccion'],
            'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
            'nombreContacto' => $post['nombreContacto'],
            'correoContacto' => $post['correoContacto'],
            'numeroContacto' => $post['numeroContacto']
        ];

        $validacionExistencia = $this->model->validarExistenciaProveedor($data['update']);
        unset($data['update']['idProveedor']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.proveedor';
        $data['where'] = [
            'idProveedor' => $post['idProveedor']
        ];

        $insert = $this->model->actualizarProveedor($data);
        $data = [];

        $post['regionCobertura'] = checkAndConvertToArray($post['regionCobertura']);
        $post['provinciaCobertura'] = empty($post['provinciaCobertura']) ? '' : checkAndConvertToArray($post['provinciaCobertura']);
        $post['distritoCobertura'] = empty($post['distritoCobertura']) ? '' : checkAndConvertToArray($post['distritoCobertura']);

        if (!empty($post['distritoCobertura'])) {
            foreach ($post['distritoCobertura'] as $key => $value) {
                $data['update'][] = [
                    'idProveedor' => $post['idProveedor'],
                    'cod_departamento' => explode('-', $value)[0],
                    'cod_provincia' => explode('-', $value)[1],
                    'cod_distrito' => explode('-', $value)[2]
                ];
            }
        } else if (!empty($post['provinciaCobertura'])) {
            foreach ($post['provinciaCobertura'] as $key => $value) {
                $data['update'][] = [
                    'idProveedor' => $post['idProveedor'],
                    'cod_departamento' => explode('-', $value)[0],
                    'cod_provincia' => explode('-', $value)[1],
                    'cod_distrito' => NULL
                ];
            }
        } else if (!empty($post['regionCobertura'])) {
            foreach ($post['regionCobertura'] as $key => $value) {
                $data['update'][] = [
                    'idProveedor' => $post['idProveedor'],
                    'cod_departamento' => $value,
                    'cod_provincia' => NULL,
                    'cod_distrito' => NULL
                ];
            }
        }

        $data['tabla'] = 'compras.zonaCobertura';
        $data['where'] = [
            'idProveedor' => $post['idProveedor']
        ];

        $second_insert = $this->model->insertarProveedorCobertura($data);
        $data = [];

        if (!$insert['estado'] || !$second_insert['estado']) {
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

    public function validarProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idEstado' => 2
        ];

        $data['tabla'] = 'compras.proveedor';
        $data['where'] = [
            'idProveedor' => $post['idProveedor']
        ];

        $update = $this->model->actualizarProveedor($data);
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

    public function actualizarEstadoProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idEstado' => ($post['estado'] == 2) ? 3 : 2
        ];

        $data['tabla'] = 'compras.proveedor';
        $data['where'] = [
            'idProveedor' => $post['idProveedor']
        ];

        $update = $this->model->actualizarProveedor($data);
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
