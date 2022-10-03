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
            // 'assets/libs/datatables/responsive.bootstrap4.min',
            // 'assets/custom/js/core/datatables-defaults',
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
                'idEstado' => $row['idProveedorEstado'],
                'estado' => $row['estado'],
                'estadoIcono' => $row['estadoIcono'],
                'estadoToggle' => $row['estadotoggle'],
            ];
            $departamentosCobertura[$row['idProveedor']][$row['zc_departamento']] = $row['zc_departamento'];
            $provinciasCobertura[$row['idProveedor']][$row['zc_provincia']] = $row['zc_provincia'];
            $distritosCobertura[$row['idProveedor']][$row['zc_distrito']] = $row['zc_distrito'];
            $metodosPago[$row['idProveedor']][$row['metodoPago']] = $row['metodoPago'];
            $rubros[$row['idProveedor']][$row['rubro']] = $row['rubro'];
        }

        foreach ($dataParaVista as $key => $row) {
            $dataParaVista[$key]['departamentosCobertura'] = implode(', ', $departamentosCobertura[$key]);
            $dataParaVista[$key]['provinciasCobertura'] = implode(', ', $provinciasCobertura[$key]);
            $dataParaVista[$key]['distritosCobertura'] = implode(', ', $distritosCobertura[$key]);
            $dataParaVista[$key]['rubros'] = implode(', ', $rubros[$key]);
            $dataParaVista[$key]['metodosPago'] = implode(', ', $metodosPago[$key]);
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
        $dataParaVisitaMetodoPago = [];
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
                //'idMetodoPago' => $row['idMetodoPago'],
               // 'metodoPago' => $row['metodoPago'],
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
                'costo' => $row['costo'] || '0'
            ];

            if (!empty($row['zc_departamento'])) $departamentosCobertura[trim($row['zc_departamento'])] = $row['zc_departamento'];
            if (!empty($row['zc_provincia'])) $provinciasCobertura[trim($row['zc_cod_departamento']).'-'.trim($row['zc_cod_provincia'])] = $row['zc_provincia'];
            if (!empty($row['zc_distrito'])) $distritosCobertura[trim($row['zc_cod_departamento']).'-'.trim($row['zc_cod_provincia']).'-'.trim($row['zc_cod_distrito'])] = $row['zc_distrito'];
            if (!empty($row['idMetodoPago'])) $dataParaVisitaMetodoPago[trim($row['idMetodoPago'])] = $row['metodoPago'];
            if (!empty($row['idRubro'])) $dataParaVistaRubro[trim($row['idRubro'])] = $row['rubro'];
        }

        $dataParaVista['departamentosCobertura'] = $departamentosCobertura;
        $dataParaVista['provinciasCobertura'] = $provinciasCobertura;
        $dataParaVista['distritosCobertura'] = $distritosCobertura;

        $dataParaVista['listadoDepartamentos'] = [];
        $dataParaVista['listadoProvincias'] = [];
        $dataParaVista['listadoDistritos'] = [];
        $dataParaVista['listadoDistritosUbigeo'] = [];
        $dataParaVista['proveedorMetodoPago'] =  $dataParaVisitaMetodoPago;
        $dataParaVista['proveedorRubro'] =  $dataParaVistaRubro;


        $ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

        foreach ($ciudad as $ciu) {

            $dataParaVista['listadoDepartamentos'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
            $dataParaVista['listadoProvincias'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
            $dataParaVista['listadoDistritos'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
            $dataParaVista['listadoDistritosUbigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);

        }

        $dataParaVista['listadoRubros'] = $this->model->obtenerRubro()['query']->result_array();
        $dataParaVista['listadoMetodosPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
        $dataParaVista['zonasProveedor'] = $this->model->obtenerZonaCoberturaProveedor(['idProveedor' => $post['idProveedor']])['query']->result_array();
        $dataParaVista['correosAdicionales'] = $this->model->obtenerCorreosAdicionales(['idProveedor' => $post['idProveedor'], 'estado' => '1'])->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Proveedor';
        $dataParaVista['disabled'] = false;
        $info = $this->model->obtenerUltimaRespuestaEstado($post['idProveedor']);
        if ($info->num_rows() > 0) {
          $dataParaVista['informacionRespuesta'] = $info->row(0)->informacion;
        }
        if($post['formularioValidar']){
            $result['msg']['title'] = 'Validar Proveedor';
            $dataParaVista['disabled'] = true;
        }
        $result['data']['html'] = $this->load->view("modulos/Proveedor/formularioActualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarProveedor()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'razonSocial' => $post['razonSocial'],
            'idTipoDocumento' => 3,
            'nroDocumento' => $post['ruc'],
            // 'idRubro' => $post['rubro'],
            // 'idMetodoPago' => $post['metodoPago'],
            'cod_ubigeo' => $post['distrito'],
            'direccion' => $post['direccion'],
            'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
            'idProveedorEstado' => 1,
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

        $zonasCobertura = [
            'regionCobertura' => $post['regionCobertura'],
            'provinciaCobertura' => $post['provinciaCobertura'],
            'distritoCobertura' => $post['distritoCobertura'],
        ];

        $zonasCobertura = getDataRefactorizada($zonasCobertura);
        $zonasInsertadas = [];
        foreach ( $zonasCobertura as $key => $value) {

            $idRegion = 0;
            $idProvincia = 0;
            $idDistrito = 0;

            !empty($value['regionCobertura']) ? $idRegion = $value['regionCobertura'] : '';
            !empty($value['provinciaCobertura']) ? $idProvincia = $value['provinciaCobertura'] : '';
            !empty($value['distritoCobertura']) ? $idDistrito = $value['distritoCobertura'] : '';

            if(!empty($zonasInsertadas[$idRegion][$idProvincia][$idDistrito])) continue;

            $data['insert'][] = [
                'idProveedor' => $insert['id'],
                'cod_departamento' => !empty($value['regionCobertura']) ? $value['regionCobertura'] : NULL,
                'cod_provincia' => !empty($value['provinciaCobertura']) ? $value['provinciaCobertura'] : NULL,
                'cod_distrito' => !empty($value['distritoCobertura']) ? $value['distritoCobertura'] : NULL
            ];

            $zonasInsertadas[$idRegion][$idProvincia][$idDistrito] = 1;
        }

        $data['tabla'] = 'compras.zonaCobertura';

        $second_insert = $this->model->insertarProveedorCobertura($data);
        $data = [];

        foreach (checkAndConvertToArray($post['metodoPago']) as $key => $value) {
            $data['insert'][] = [
                'idProveedor' => $insert['id'],
                'idMetodoPago' => $value,

            ];
        }

        $third_insert = $this->model->insertarMasivo("compras.proveedorMetodoPago", $data['insert']);
        $data = [];

        foreach (checkAndConvertToArray($post['rubro']) as $key => $value) {
            $data['insert'][] = [
                'idProveedor' => $insert['id'],
                'idRubro' => $value,
            ];
        }

        $fourth_insert = $this->model->insertarMasivo("compras.proveedorRubro", $data['insert']);
        $data = [];

        $fifth_insert = true;
        if (isset($post['correoAdicional'])) {
          foreach (checkAndConvertToArray($post['correoAdicional']) as $key => $value) {
            $data['insert'][] = [
              'idProveedor' => $insert['id'],
              'correo' => $value,
            ];
          }
          $fifth_insert = $this->model->insertarMasivo("compras.proveedorCorreo", $data['insert']);
        }


        if (!$insert['estado'] || !$second_insert['estado'] || !$third_insert || !$fourth_insert || !$fifth_insert) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');

            goto respuesta;
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }

        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

    public function actualizarProveedor()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $enviarCorreo = false;
        $rptaCorreo = true;
        $data['update'] = [
            'idProveedor' => $post['idProveedor'],
            'razonSocial' => $post['razonSocial'],
            'nroDocumento' => $post['ruc'],
            // 'idRubro' => $post['rubro'],
            /*'idMetodoPago' => $post['metodoPago'],*/
            'cod_ubigeo' => $post['distrito'],
            'direccion' => $post['direccion'],
            'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
            'nombreContacto' => $post['nombreContacto'],
            'correoContacto' => $post['correoContacto'],
            'numeroContacto' => $post['numeroContacto'],
            'costo' => $post['costo']
        ];
        if (isset($post['idProveedorEstado'])) {
          $data['update']['idProveedorEstado'] = $post['idProveedorEstado'];
          $enviarCorreo = true;
          $rptaCorreo = false;
        }

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


        $data['tabla'] = 'compras.zonaCobertura';
        $data['where'] = [
            'idProveedor' => $post['idProveedor']
        ];


        $zonasCobertura = [
            'regionCobertura' => $post['regionCobertura'],
            'provinciaCobertura' => $post['provinciaCobertura'],
            'distritoCobertura' => $post['distritoCobertura'],
        ];

        $zonasCobertura = getDataRefactorizada($zonasCobertura);
        $zonasInsertadas = [];
        foreach ( $zonasCobertura as $key => $value) {

            $idRegion = 0;
            $idProvincia = 0;
            $idDistrito = 0;

            !empty($value['regionCobertura']) ? $idRegion = $value['regionCobertura'] : '';
            !empty($value['provinciaCobertura']) ? $idProvincia = $value['provinciaCobertura'] : '';
            !empty($value['distritoCobertura']) ? $idDistrito = $value['distritoCobertura'] : '';

            if(!empty($zonasInsertadas[$idRegion][$idProvincia][$idDistrito])) continue;

            $data['update'][] = [
                'idProveedor' => $post['idProveedor'],
                'cod_departamento' => !empty($value['regionCobertura']) ? $value['regionCobertura'] : NULL,
                'cod_provincia' => !empty($value['provinciaCobertura']) ? $value['provinciaCobertura'] : NULL,
                'cod_distrito' => !empty($value['distritoCobertura']) ? $value['distritoCobertura'] : NULL
            ];

            $zonasInsertadas[$idRegion][$idProvincia][$idDistrito] = 1;
        }

        $second_insert = $this->model->insertarProveedorCobertura($data);
        $data = [];


        foreach (checkAndConvertToArray($post['metodoPago']) as $key => $value) {
            $data['insert'][] = [
                'idProveedor' => $post['idProveedor'],
                'idMetodoPago' => $value,

            ];
        }

        $data['where'] = ['idProveedor' => $post['idProveedor']];


        $this->model->BorrarProveedorMetodoPago(['tabla' => "compras.proveedorMetodoPago", 'where' => $data['where']]);

        $third_insert = $this->model->insertarMasivo("compras.proveedorMetodoPago", $data['insert']);

        $data = [];


        foreach (checkAndConvertToArray($post['rubro']) as $key => $value) {
            $data['insert'][] = [
                'idProveedor' => $post['idProveedor'],
                'idRubro' => $value,

            ];
        }

        $data['where'] = ['idProveedor' => $post['idProveedor']];

        // Seria bueno cambiar el nombre de la funcion, pero lo evite desconociendo si hay otra consulta que haga uso de esta funcion
        $this->model->BorrarProveedorMetodoPago(['tabla' => "compras.proveedorRubro", 'where' => $data['where']]);

        $fourth_insert = $this->model->insertarMasivo("compras.proveedorRubro", $data['insert']);

        if($enviarCorreo){
          $data = [];

          $data['tabla'] = 'compras.proveedorEstadoHistorico';
          $data['insert'] = [
              'idProveedor' => $post['idProveedor'],
              'estado' => $post['idProveedorEstado'],
              'idUsuario' => $this->idUsuario,
              'fechaReg' => getActualDateTime(),
              'informacion' => 'Solicitud de validación'
          ];

          $fifth_insert = $this->model->insertarProveedor($data);

          $estadoEmail = $this->enviarCorreo($post['idProveedor']);
          if($fifth_insert && $estadoEmail){
            $rptaCorreo = true;
          }
        }

        $this->db->update('compras.proveedorCorreo', ['estado' => 0], ['idProveedor' => $post['idProveedor']]);

        $data = [];
        $sixth_insert = true;
        if (isset($post['correoAdicional'])) {
          foreach (checkAndConvertToArray($post['correoAdicional']) as $key => $value) {
            $data['insert'][] = [
              'idProveedor' => $post['idProveedor'],
              'correo' => $value,
            ];
          }
          $sixth_insert = $this->model->insertarMasivo("compras.proveedorCorreo", $data['insert']);
        }


        if (!$insert['estado'] || !$second_insert['estado'] || !$third_insert || !$fourth_insert || !$rptaCorreo || !$sixth_insert) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
            goto respuesta;
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }
        $this->db->trans_complete();
        respuesta:
        echo json_encode($result);
    }

    public function validarProveedor()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idProveedorEstado' => 1,

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
            'idProveedorEstado' => ($post['estado'] == 2) ? 3 : 2
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

    public function enviarCorreo($idProveedor)
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
  		$this->email->to('jean.alarcon@visualimpact.com.pe');

  		$data = [];
  		$dataParaVista = [];
  		$departamentosCobertura = [];
  		$provinciasCobertura = [];
  		$distritosCobertura = [];
  		$data = $this->model->obtenerInformacionProveedores(['idProveedor' => $idProveedor])['query']->result_array();

  		foreach ($data as $key => $row) {
  			$dataParaVista = [
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
  			];
  			$departamentosCobertura[$row['zc_departamento']] = $row['zc_departamento'];
  			$provinciasCobertura[$row['zc_provincia']] = $row['zc_provincia'];
  			$distritosCobertura[$row['zc_distrito']] = $row['zc_distrito'];
  		}

  		$dataParaVista['departamentosCobertura'] = implode(', ', $departamentosCobertura);
  		$dataParaVista['provinciasCobertura'] = implode(', ', $provinciasCobertura);
  		$dataParaVista['distritosCobertura'] = implode(', ', $distritosCobertura);

  		$dataParaVista['link'] = base_url() . index_page() . 'proveedor';

  		// $bcc = array(
  		//     'team.sistemas@visualimpact.com.pe',
  		// );
  		// $this->email->bcc($bcc);
  		//$bcc = array('luis.durand@visualimpact.com.pe');
  		// $this->email->bcc($bcc);

  		// $this->email->subject('IMPACTBUSSINESS - ACTUALIZACION ENTRADA DE PROVEEDORES');
  		// $html = $this->load->view("formularioProveedores/informacionProveedor", $dataParaVista, true);
  		$html = $this->load->view("email/header", $dataParaVista, true);
  		$correo = $this->load->view("formularioProveedores/formato", ['html' => $html, 'link' => base_url() . index_page() . '/proveedores'], true);
  		$this->email->message($correo);

  		$estadoEmail = $this->email->send();

  		return $estadoEmail;
  	}
}
