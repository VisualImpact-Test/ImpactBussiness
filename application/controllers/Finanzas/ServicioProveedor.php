<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ServicioProveedor extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_ServicioProveedor', 'model');
        $this->load->model('M_PagosGenerados', 'mPagosGenerales');
        $this->load->model('M_OrdenServicio', 'mOrdenServicio');
        
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
        $config['data']['title'] = 'Proveedor de Servicio';
        $config['data']['message'] = 'Lista';
        $config['view'] = 'modulos/Finanzas/ServicioProveedor/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $dataParaVista = [];
        $dataParaVista['datos'] = $this->model->obtenerServicioProveedor()['query']->result_array();
        $dataParaVista['contacto'] = $this->model->obtenerContactoProveedor()['query']->result_array();

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
        $dataParaVista['ServicioProveedor'] = $this->model->ObtenerDatoServicioProveedor($post)['query']->result_array();
        $dataParaVista['Contactos'] = $this->model->ObtenerDatoServicioProveedorContacto($post)['query']->result_array();

        $dataParaVista['tipoDocumento'] = $this->model->ObtenerDatosTipoDocumento($post)['query']->result_array();
        
       
        $dataParaVista['departamento'] = $this->mOrdenServicio->obtenerDepartamento()->result_array();
                
        $dataParaVista['provincia'] = $this->mOrdenServicio->obtenerProvincia()->result_array();
        $provincia = changeKeyInArray($dataParaVista['provincia'], 'cod_departamento', 'cod_provincia' );
        $result['data']['provincia'] = $provincia;

        $dataParaVista['distrito'] = $this->mOrdenServicio->obtenerDistrito()->result_array();
        $distrito = changeKeyInArray($dataParaVista['distrito'], 'cod_departamento', 'cod_provincia' , 'cod_distrito' );
        $result['data']['distrito'] = $distrito;

	    // $provincia = [];
		// foreach ($this->model->obtenerProvincia()->result_array() as $v) {
		// 	$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		// }
		// $result['data']['provincia'] = $provincia;

		// $distrito = [];
		// foreach ($this->model->obtenerDistrito()->result_array() as $v) {
		// 	$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		// }


        $provincia = [];
		foreach ($this->mOrdenServicio->obtenerProvincia()->result_array() as $v) {
			$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		}
		$result['data']['provincia'] = $provincia;

		$distrito = [];
		foreach ($this->mOrdenServicio->obtenerDistrito()->result_array() as $v) {
			$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		}
        $result['data']['distrito'] = $distrito;


        $result['result'] = 1;
        $result['msg']['title'] = 'Editar Proveedor Servicio';
        $result['data']['html'] = $this->load->view("modulos/Finanzas/ServicioProveedor/formularioEditar", $dataParaVista, true);
        echo json_encode($result);
        

        // $dataParaVista['datos'] = $this->model->obtenerServicioProveedor($post)['query']->result_array();
        // $dataParaVista['ubigeo'] = $this->model->obtenerCiudadUbigeo($post)['query']->result_array();
        // $dataParaVista['estado'] = $this->model->obtenerEstado()['query']->result_array();
        // $ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

        // $dataParaVista['departamento'] = [];
        // $dataParaVista['provincia'] = [];
        // $dataParaVista['distrito'] = [];

        // foreach ($ciudad as $ciu) {
        //     $dataParaVista['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
        //     $dataParaVista['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
        //     $dataParaVista['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
        //     $dataParaVista['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
        // }

        // $result['result'] = 1;
        // $result['msg']['title'] = 'Actualizar Proveedor Servicio';
        // $result['data']['html'] = $this->load->view("modulos/Finanzas/ServicioProveedor/formularioActualizar", $dataParaVista, true);
        // echo json_encode($result);
    }




    public function formularioRegistroProveedorServicio()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
		$dataParaVista['tipoDocumento'] = $this->model->ObtenerDatosTipoDocumento($post)['query']->result_array();
      
       
        $dataParaVista['departamento'] = $this->mOrdenServicio->obtenerDepartamento()->result_array();
                
        $dataParaVista['provincia'] = $this->mOrdenServicio->obtenerProvincia()->result_array();
        $provincia = changeKeyInArray($dataParaVista['provincia'], 'cod_departamento', 'cod_provincia' );
        $result['data']['provincia'] = $provincia;

        $dataParaVista['distrito'] = $this->mOrdenServicio->obtenerDistrito()->result_array();
        $distrito = changeKeyInArray($dataParaVista['distrito'], 'cod_departamento', 'cod_provincia' , 'cod_distrito' );
        $result['data']['distrito'] = $distrito;




        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Proveedor Servicio';
        $result['data']['html'] = $this->load->view("modulos/Finanzas/ServicioProveedor/formularioRegistro", $dataParaVista, true);
        echo json_encode($result);
    }

    public function registrarProveedorServicio()
    {
    	$result = $this->result;
    	$post = json_decode($this->input->post('data'), true);
        $this->db->trans_begin();

        $insertData = [
            'idTipoDocumento' => $post['tipoComprobante'],
            'numDocumento' => $post['numDocumento'],
            'datosProveedor' => $post['datProveedor'],
            'departamento' => $post['departamento'],
            'provincia' => $post['provincia'],
            'distrito' => $post['distrito'],
            'direccion' => $post['direccion'],
            'idProveedorEstado' => 2,
            'estado' =>1
        ];

        $insertarDatos = $this->db->insert('finanzas.proveedorServicio', $insertData);
        $idProveedorServicio = $this->db->insert_id();

  

        if (isset($post['nomContactoinput'])) {
      
        $insertContacto = [
			'nomContacto' => checkAndConvertToArray($post['nomContactoinput']),
			'telContacto' => checkAndConvertToArray($post['telContactoinput']),
            'correoContacto' => checkAndConvertToArray($post['correoContactoimput']),
			'idProveedorServicio' =>  $idProveedorServicio
		];
		$data = [];
		foreach ($insertContacto['nomContacto'] as $key => $nomContacto) {
			$telContacto = $insertContacto['telContacto'][$key];
            $correoContacto = $insertContacto['correoContacto'][$key];
			$idProveedorServicio = $insertContacto['idProveedorServicio'];

			if (!empty($nomContacto)) {
				$data[] = [
					'nomContacto' => $nomContacto,
					'telContacto' => $telContacto,
                    'correoContacto' =>$correoContacto,
					'idProveedorServicio' => $idProveedorServicio,
                    'estado' => 1
				];
			}
		}


		if (!empty($data)) {
			$this->db->insert_batch('finanzas.proveedorServicioContacto', $data);
		}
        }
        if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $result['result'] = 2;
        $result['msg']['title'] = 'Error al Registrar';
        $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
        $this->db->trans_commit();
        $result['result'] = 1;
        $result['msg']['title'] = 'Pago Registrado';
        $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }
        echo json_encode($result);


        //  var_dump($post);
    	//$validar = $this->model->validarExistenciaProveedorServicio($post)['query']->result_array();

    	// if(!empty($validar)) {
    	// 	$result['result'] = 0;
    	// 	$result['msg']['title'] = 'Alerta!';
    	// 	$result['msg']['content'] = getMensajeGestion('registroRepetido');
    	// 	goto respuesta;
    	// }

    	// $elementosAValidar = [
    	// 	'numeroDocumento' => ['requerido', 'numerico'],
    	// 	'razonSocial' => ['requerido'],	
    	// 	'distrito' => ['requerido'],
    	// 	'direccion' => ['requerido'],
    	// 	'correoContacto' => ['email']
    	// ];

    	// $resultadoDeValidaciones = verificarValidacionesBasicas($elementosAValidar, $post);

    	// if (!verificarSeCumplenValidaciones($resultadoDeValidaciones)) {
        //     $result['result'] = 0;
    	// 	$result['msg']['title'] = 'Alerta!';
        //     $result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
        //     goto respuesta;
        // }

    	

    	



    //	$insertarDatos = $this->db->insert('finanzas.proveedorServicio', $insertData);

   
    	// respuesta:
    	// echo json_encode($result);
    }
    
    public function verificarNumDocumento()
    {
        $result = $this->result;
    	$post = json_decode($this->input->post('data'), true);
       // $this->db->trans_begin();
       // var_dump($post);
        $validacionExistencia = $this->model->validarExistenciaServicioProveedor($post);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}else{
            $result['result'] = 1;
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarServicioProveedor()
    {

        $result = $this->result;
    	$post = json_decode($this->input->post('data'), true);
        
        $this->db->trans_begin();
        $idProveedorServicio = $post['idProveedorServicio'];
        $update = [
            'idTipoDocumento' => $post['tipoComprobante'],
            'numDocumento' => $post['numDocumento'],
            'datosProveedor' => $post['datProveedor'],
            'departamento' => $post['departamento'],
            'provincia' => $post['provincia'],
            'distrito' => $post['distrito'],
            'direccion' => $post['direccion'],
            // 'idProveedorEstado' => 2,
            // 'estado' =>1
        ];

      
        $this->db->update('finanzas.proveedorServicio', $update, ['idProveedorServicio' => $idProveedorServicio]);
        if (!empty($post['nomContactoinput'])) {
        $insertContacto = [
			'nomContacto' => checkAndConvertToArray($post['nomContactoinput']),
			'telContacto' => checkAndConvertToArray($post['telContactoinput']),
            'correoContacto' => checkAndConvertToArray($post['correoContactoimput']),
			'idProveedorServicio' =>  $idProveedorServicio
		];
		$data = [];
		foreach ($insertContacto['nomContacto'] as $key => $nomContacto) {
			$telContacto = $insertContacto['telContacto'][$key];
            $correoContacto = $insertContacto['correoContacto'][$key];
			$idProveedorServicio = $insertContacto['idProveedorServicio'];

			if (!empty($nomContacto)) {
				$data[] = [
					'nomContacto' => $nomContacto,
					'telContacto' => $telContacto,
                    'correoContacto' =>$correoContacto,
					'idProveedorServicio' => $idProveedorServicio,
                    'estado' => 1
				];
			}
		}

        

		if (!empty($data)) {
			$this->db->insert_batch('finanzas.proveedorServicioContacto', $data);
		}
         }

         foreach ($post['archivoEliminado'] as $key => $row) {
            $this->db->update('finanzas.proveedorServicioContacto', ['estado' => 0],['idContacto' => $row]);
        }
       // echo $this->db->last_query();exit();

        if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $result['result'] = 2;
        $result['msg']['title'] = 'Error al Registrar';
        $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
        $this->db->trans_commit();
        $result['result'] = 1;
        $result['msg']['title'] = 'Pago Registrado';
        $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }
        echo json_encode($result);

    }
}
