<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ServicioProveedor extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_ServicioProveedor', 'model');
    }

    public function index()
    {
        $config = array();
        $config['nav']['menu_active'] = '131';
        $config['css']['style'] = array(
            'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/js/select.dataTables.min'
        );
        $config['js']['script'] = array(
            'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/languages/all',
            'assets/libs/handsontable@7.4.2/dist/moment/moment',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/libs/fileDownload/jquery.fileDownload',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/Finanzas/servicioProveedor'
        );

        $config['data']['icon'] = 'fas fa-handshake';
        $config['data']['title'] = 'Servicio';
        $config['data']['message'] = 'Lista';
        $config['view'] = 'modulos/Finanzas/ServicioProveedor/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $dataParaVista = [];
        $dataParaVista['datos'] = $this->model->obtenerServicioProveedor()['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Finanzas/ServicioProveedor/reporte", $dataParaVista, true);
        }



        $result['result'] = 1;
        $result['data']['views']['idServicioProveedor']['datatable'] = 'tb-servicioProveedor';
        $result['data']['views']['idServicioProveedor']['html'] = $html;
        $result['data']['configTable'] = [
            'columnDefs' =>
            [
                0 =>
                [
                    "visible" => false,
                    "targets" => []
                ]
            ],
        ];

        echo json_encode($result);
    }

    public function actualizarEstadoProveedorServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $data['update'] = ['idProveedorEstado' => ($post['estado'] == 2) ? 3 : 2];

        $data['tabla'] = 'finanzas.proveedorServicio';
        $data['where'] = ['idProveedorServicio' => $post['idProveedorServicio']];

        $update = $this->model->actualizarServicioProveedor($data);
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

    public function formularioActualizacionServicioProveedor()
    {

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['datos'] = $this->model->obtenerServicioProveedor($post)['query']->result_array();
        $dataParaVista['ubigeo'] = $this->model->obtenerCiudadUbigeo($post)['query']->result_array();
        $dataParaVista['estado'] = $this->model->obtenerEstado()['query']->result_array();
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
        $result['msg']['title'] = 'Actualizar Proveedor Servicio';
        $result['data']['html'] = $this->load->view("modulos/Finanzas/ServicioProveedor/formularioActualizar", $dataParaVista, true);
        echo json_encode($result);
    }


    public function actualizarServicioProveedor()
    {

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        if ($post['tipoDocumento'] === 'DNI') {

        $data['update'] = [
            'dni' => $post['numeroDocumento'],
            'carnet_extranjeria' => null,
            'ruc' => null,
            'razonSocial' => $post['razonSocial'],
            'cod_ubigeo' => $post['distrito'],
            'direccion' => $post['direccion'],
            'idProveedorEstado' => $post['idProveedorEstado'],
            'nombreContacto' => $post['nombreContacto'],
            'correoContacto' => $post['correoContacto'],
            'numeroContacto' => $post['numeroContacto'],
            'estado' => 2
        ];

        } elseif ($post['tipoDocumento'] === 'RUC') {

            $data['update'] = [
                'ruc' => $post['numeroDocumento'],
                'dni' => null,
                'carnet_extranjeria' => null,
                'razonSocial' => $post['razonSocial'],
                'cod_ubigeo' => $post['distrito'],
                'direccion' => $post['direccion'],
                'idProveedorEstado' => $post['idProveedorEstado'],
                'nombreContacto' => $post['nombreContacto'],
                'correoContacto' => $post['correoContacto'],
                'numeroContacto' => $post['numeroContacto'],
                'estado' => 2
            ];

        } elseif ($post['tipoDocumento'] === 'CE') {

            $data['update'] = [
                'carnet_extranjeria' => $post['numeroDocumento'],
                'ruc' => null,
                'dni' => null,
                'razonSocial' => $post['razonSocial'],
                'cod_ubigeo' => $post['distrito'],
                'direccion' => $post['direccion'],
                'idProveedorEstado' => $post['idProveedorEstado'],
                'nombreContacto' => $post['nombreContacto'],
                'correoContacto' => $post['correoContacto'],
                'numeroContacto' => $post['numeroContacto'],
                'estado' => 2
            ];

        }
        
        $data['tabla'] = 'finanzas.proveedorServicio';
        $data['where'] = ['idProveedorServicio' => $post['idProveedorServicio']];
        $insert = $this->model->actualizarServicioProveedor($data);

        if (!$insert) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
            goto respuesta;
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }


        respuesta:
        echo json_encode($result);
    }

    public function formularioRegistroProveedorServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['estado'] = $this->model->obtenerEstado()['query']->result_array();
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
        $result['msg']['title'] = 'Registrar Proveedor Servicio';
        $result['data']['html'] = $this->load->view("modulos/Finanzas/ServicioProveedor/formularioRegistro", $dataParaVista, true);
        echo json_encode($result);
    }

    public function registrarProveedorServicio()
    {
    	$result = $this->result;
    	$post = json_decode($this->input->post('data'), true);

    	$validar = $this->model->validarExistenciaProveedorServicio($post)['query']->result_array();

    	if(!empty($validar)) {
    		$result['result'] = 0;
    		$result['msg']['title'] = 'Alerta!';
    		$result['msg']['content'] = getMensajeGestion('registroRepetido');
    		goto respuesta;
    	}

    	$elementosAValidar = [
    		'numeroDocumento' => ['requerido', 'numerico'],
    		'razonSocial' => ['requerido'],	
    		'distrito' => ['requerido'],
    		'direccion' => ['requerido'],
    		'idProveedorEstado' => ['requerido'],
    		'correoContacto' => ['requerido', 'email'],
    		'nombreContacto' => ['requerido'],
    		'numeroContacto' => ['requerido', 'numerico']
    	];

    	$resultadoDeValidaciones = verificarValidacionesBasicas($elementosAValidar, $post);

    	if (!verificarSeCumplenValidaciones($resultadoDeValidaciones)) {
            $result['result'] = 0;
    		$result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
            goto respuesta;
        }

    	if ($post['tipoDocumento'] === 'DNI') {

    		$insertData = [
    			'dni' => $post['numeroDocumento'],
    			'razonSocial' => $post['razonSocial'],
    			'cod_ubigeo' => $post['distrito'],
    			'direccion' => $post['direccion'],
    			'idProveedorEstado' => $post['idProveedorEstado'],
    			'nombreContacto' => $post['nombreContacto'],
    			'correoContacto' => $post['correoContacto'],
    			'numeroContacto' => $post['numeroContacto'],
    			'estado' => 1
    		];

    	} elseif ($post['tipoDocumento'] === 'RUC') {

    		$insertData = [
    			'ruc' => $post['numeroDocumento'],
    			'razonSocial' => $post['razonSocial'],
    			'cod_ubigeo' => $post['distrito'],
    			'direccion' => $post['direccion'],
    			'idProveedorEstado' => $post['idProveedorEstado'],
    			'nombreContacto' => $post['nombreContacto'],
    			'correoContacto' => $post['correoContacto'],
    			'numeroContacto' => $post['numeroContacto'],
    			'estado' => 1
    		];

    	} elseif ($post['tipoDocumento'] === 'CE') {

    		$insertData = [
    			'carnet_extranjeria' => $post['numeroDocumento'],
    			'razonSocial' => $post['razonSocial'],
    			'cod_ubigeo' => $post['distrito'],
    			'direccion' => $post['direccion'],
    			'idProveedorEstado' => $post['idProveedorEstado'],
    			'nombreContacto' => $post['nombreContacto'],
    			'correoContacto' => $post['correoContacto'],
    			'numeroContacto' => $post['numeroContacto'],
    			'estado' => 1
    		];

    	}

    	$insertarDatos = $this->db->insert('finanzas.proveedorServicio', $insertData);

    	if ($insertarDatos) {
    		$result['result'] = 1;
    		$result['msg']['title'] = 'Hecho!';
    		$result['msg']['content'] = getMensajeGestion('registroExitoso');
    	} else {


    		$result['msg']['title'] = 'Ocurrio un error';
    		$result['msg']['content'] = getMensajeGestion('registroInvalido');
    	}

    	respuesta:
    	echo json_encode($result);
    }
}
