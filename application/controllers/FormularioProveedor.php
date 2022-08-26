<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FormularioProveedor extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_FormularioProveedor', 'model');
		$this->load->model('M_cotizacion', 'm_cotizacion');
		$proveedor = $this->session->userdata('proveedor');

	}

	public function index()
	{
		$proveedor = $this->session->userdata('proveedor');
		if(!empty($proveedor)){
			redirect('FormularioProveedor/Cotizaciones','refresh');
			exit();
		}
		$config['css']['style'] = array('');
		$config['js']['script'] = array('assets/custom/js/FormularioProveedores');
		$config['view'] = 'formularioProveedores/login';
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		$config['data']['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
		$ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

		$config['data']['departamento'] = [];
		$config['data']['provincia'] = [];
		$config['data']['distrito'] = [];

		foreach ($ciudad as $ciu) {
			$config['data']['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
			$config['data']['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
			$config['data']['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
			$config['data']['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
		}

		$config['single'] = true;

		$this->view($config);
	}

	public function login(){
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$proveedor = $this->model->loginProveedor($post)->row_array();

		if(empty($proveedor)){
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type'=> 2 , 'message' => 'Datos inválidos']);
			goto respuesta;
		}

		if($proveedor['idProveedorEstado'] == 1){
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type'=> 2 , 'message' => 'Usuario por aprobar']);
			goto respuesta;
		}

		if($proveedor['idProveedorEstado'] != 2){
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type'=> 2 , 'message' => 'Datos inválidos']);
			goto respuesta;
		}


		$result['result'] = 1;
		$result['msg']['content'] = createMessage(['type'=> 1 , 'message' => "Bienvenido <b>{$proveedor['razonSocial']}</b>"]);
		$result['data']['url'] = base_url()."FormularioProveedor/cotizacionesLista";

		$this->session->set_userdata('proveedor',$proveedor);
		respuesta:
		echo json_encode($result);
	}

	public function signup()
	{

		$config['css']['style'] = array();
		$config['js']['script'] = array('assets/custom/js/FormularioProveedores');
		$config['view'] = 'formularioProveedores';
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		$config['data']['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
		$ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

		$config['data']['departamento'] = [];
		$config['data']['provincia'] = [];
		$config['data']['distrito'] = [];

		foreach ($ciudad as $ciu) {
			$config['data']['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
			$config['data']['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
			$config['data']['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
			$config['data']['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
		}

		$config['single'] = true;

		$this->view($config);
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
			'cod_ubigeo' => $post['distrito'],
			'direccion' => $post['direccion'],
			'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
			'idProveedorEstado' => 5,
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

		$estadoEmail = $this->enviarCorreo($insert['id']);

		$estadoEmail=true;

		if (!$insert['estado'] || !$second_insert['estado'] || !$estadoEmail || !$third_insert || !$fourth_insert) {
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
		$data = $this->model->obtenerInformacionProveedor(['idProveedor' => $idProveedor])['query']->result_array();

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
		$this->email->bcc($bcc);

		$this->email->subject('IMPACTBUSSINESS - NUEVA ENTRADA DE PROVEEDORES');
		// $html = $this->load->view("formularioProveedores/informacionProveedor", $dataParaVista, true);
		$html = $this->load->view("email/header", $dataParaVista, true);
		$correo = $this->load->view("formularioProveedores/formato", ['html' => $html, 'link' => base_url() . index_page() . '/proveedores'], true);
		$this->email->message($correo);

		$estadoEmail = $this->email->send();

		return $estadoEmail;
	}

	public function validar_captcha_v3($post)
	{
		define("RECAPTCHA_V3_SECRET_KEY", '6Le7INUaAAAAAEsBU33EfPneKHjz5OTSUHVRORdi');

		$token = $post->{'token'};
		$action = $post->{'action'};

		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_V3_SECRET_KEY . "&response={$token}");
		$response = json_decode($response);

		$arrResponse = (array) $response;
		// verificar la respuesta
		if ($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
			// Si entra aqui, es un humano, puedes procesar el formulario
			$msj =  1;
		} else {
			// Si entra aqui, es un robot....
			$msj =  0;
		}

		return $msj;
	}

	function validar_captcha_v2($user_response)
	{

		if (!empty($user_response)) {
			$secret = '6LdAotoaAAAAAONJyJaNn-SQS5wfVkQwfvyvQEe2';
			$ip = $_SERVER['REMOTE_ADDR'];

			$validation = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$user_response&remoteip=$ip");

			return json_decode($validation);
		} else {
			return (object)['success' => 0];
		}
	}
	public function cotizacionesLista()
	{
		$proveedor = $this->session->userdata('proveedor');
		if(empty($proveedor)){
			redirect('FormularioProveedor','refresh');
			exit();
		}

		$config['css']['style'] = array();
		$config['js']['script'] = array('assets/custom/js/FormularioProveedoresCotizacionesLista');

		$config['view'] = 'formularioProveedores/cotizacionesLista';
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['single'] = true;
		$this->view($config);
	}
	public function cotizacionesListaRefresh()
	{
		$proveedor = $this->session->userdata('proveedor');
		if(empty($proveedor)){
			redirect('FormularioProveedor','refresh');
			exit();
		}
		$result = $this->result;
		// $post = json_decode($this->input->post('data'), true);
		$post['idProveedor'] = $proveedor['idProveedor'];
		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerListaCotizaciones($post)->result_array();
		$html = $this->load->view("formularioProveedores/cotizacionesLista-table", ['datos' => $dataParaVista,'idProveedor' => $proveedor['idProveedor']], true);

    $result['result'] = 1;
    $result['data']['views']['content-tb-cotizaciones-proveedor']['datatable'] = 'tb-cotizaciones';
    $result['data']['views']['content-tb-cotizaciones-proveedor']['html'] = $html;
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
		// echo json_encode($this->model->obtenerListaCotizaciones($post)->result_array());

	}
	public function cotizaciones($idCotizacion = '0')
	{
		$proveedor = $this->session->userdata('proveedor');
		if(empty($proveedor)){
			redirect('FormularioProveedor','refresh');
			exit();
		}

		$config['css']['style'] = array();
		$config['js']['script'] = array('assets/custom/js/FormularioProveedoresCotizaciones');

		$config['view'] = 'formularioProveedores/cotizaciones';
		$config['data']['idCotizacion'] = $idCotizacion;
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		// $cotizacionProveedor = $this->db->get_where('compras.cotizacionDetalleProveedor',['idProveedor' => $proveedor['idProveedor'],'idCotizacion' => $idCotizacion,'estado' => 1])->row_array();
		$cotizacionProveedor = $this->model->obtenerCotizacionDetalleProveedor(['idProveedor' => $proveedor['idProveedor'],'idCotizacion' => $idCotizacion,'estado' => 1])['query']->row_array();
		$config['data']['cabecera'] = $this->m_cotizacion->obtenerInformacionCotizacion(['id' => $cotizacionProveedor['idCotizacion']])['query']->row_array();

		$config['single'] = true;
		if( empty($idCotizacion) || empty($cotizacionProveedor)){
			$config['view'] = 'formularioProveedores/validacionEmail';
		}
		$this->view($config);
	}

	public function cotizacionesRefresh()
  {

		$proveedor = $this->session->userdata('proveedor');
		if(empty($proveedor)){
			redirect('FormularioProveedor','refresh');
			exit();
		}

    $result = $this->result;
    $post = json_decode($this->input->post('data'), true);
		$post['idProveedor'] = $proveedor['idProveedor'];
    $dataParaVista = [];
    $dataParaVista = $this->model->obtenerInformacionCotizacionProveedor($post)->result_array();
		log_message('error', json_encode($dataParaVista));
		$dataParaVistaSub = [];
		foreach ($dataParaVista as $key => $value) {
			$dataParaVistaSub[$value['idCotizacionDetalle']] = $this->model->obtenerInformacionCotizacionDetalleSub(['idCotizacionDetalle' => $value['idCotizacionDetalle']])->result_array();
		}

		$archivos = $this->model->obtenerCotizacionDetalleProveedorDetalleArchivos($post)->result_array();

		$html = $this->load->view("formularioProveedores/cotizaciones-table", ['datos' => $dataParaVista, 'subdatos' => $dataParaVistaSub, 'idProveedor' => $proveedor['idProveedor'],'idCotizacion' => $post['idCotizacion'], 'archivos' => $archivos], true);
    $result['result'] = 1;
    // $result['data']['views']['content-tb-cotizaciones-proveedor']['datatable'] = 'tb-cotizaciones';
    $result['data']['views']['content-tb-cotizaciones-proveedor']['html'] = $html;
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
	public function obtenerFecha()
	{
		$post = $this->input->post();
		$post['fecha'] = getFechaActual($post['fecha']);
		echo json_encode($post);
	}
	public function actualizarCotizacionProveedor()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['idCotizacionDetalleProveedorDetalle'] = checkAndConvertToArray($post['idCotizacionDetalleProveedorDetalle']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$insertArchivos = [];

		$post['diasValidez'] = checkAndConvertToArray($post['diasValidez']);
		$post['fechaValidez'] = checkAndConvertToArray($post['fechaValidez']);
		$post['comentario'] = checkAndConvertToArray($post['comentario']);
		$post['fechaEntrega'] = checkAndConvertToArray($post['fechaEntrega']);

		foreach ($post['idCotizacionDetalleProveedorDetalle'] as $k => $r) {
			$subTotal = (!empty($post['costo'][$k])) ? $post['costo'][$k] : 0;
			$cantidad = (!empty($post['cantidad'][$k])) ? $post['cantidad'][$k] : 0;
      		$data['update'][] = [
				'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$k],
				// 'costo' => costoUnitario($cantidad,$subTotal),
				'costo' => $subTotal,
				'flag_activo' => 0,
				'diasValidez' => $post['diasValidez'][$k],
				'fechaValidez' => $post['fechaValidez'][$k],
				'comentario' => $post['comentario'][$k],
				'fechaEntrega' => $post['fechaEntrega'][$k]
      ];
			if(isset($post['file-type['.$r.']'])){
				if(is_array($post['file-type['.$r.']'])){
					foreach ($post['file-type['.$r.']'] as $key => $value) {
						$archivo = [
							'base64' => $post['file-item['.$r.']'][$key],
							'name' => $post['file-name['.$r.']'][$key],
							'type' => $post['file-type['.$r.']'][$key],
							'carpeta' => 'cotizacionProveedor',
							'nombreUnico' => 'COTIPRO'.$post['idCotizacionDetalleProveedorDetalle'][$k].str_replace(':', '', $this->hora).'_'.$key.'CP',
						];
						$archivoName = $this->saveFileWasabi($archivo);
						$tipoArchivo = explode('/',$archivo['type']);
						$insertArchivos[] = [
							'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$k],
							'idTipoArchivo' => TIPO_ORDEN_COMPRA,
							'nombre_inicial' => $archivo['name'],
							'nombre_archivo' => $archivoName,
							'nombre_unico' => $archivo['nombreUnico'],
							'extension' => $tipoArchivo[1],
							'estado' => true,
							'idUsuarioReg' => $this->idUsuario
						];
					}
				}else{
					$archivo = [
						'base64' => $post['file-item['.$r.']'],
						'name' => $post['file-name['.$r.']'],
						'type' => $post['file-type['.$r.']'],
						'carpeta' => 'cotizacionProveedor',
						'nombreUnico' => 'COTIPRO'.$post['idCotizacionDetalleProveedorDetalle'][$k].str_replace(':', '', $this->hora).'_0'.'CP',
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/',$archivo['type']);
					$insertArchivos[] = [
						'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$k],
						'idTipoArchivo' => TIPO_ORDEN_COMPRA,
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => $tipoArchivo[1],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}
    }
		if(!empty($insertArchivos)){
			$this->db->insert_batch('compras.cotizacionDetalleProveedorDetalleArchivos', $insertArchivos);
		}
    $data['tabla'] = 'compras.cotizacionDetalleProveedorDetalle';
    $data['where'] = 'idCotizacionDetalleProveedorDetalle';
    $updateDetalle = $this->m_cotizacion->actualizarCotizacionDetalle($data);


    if (!$updateDetalle['estado']) {
	    $result['result'] = 0;
	    $result['msg']['title'] = 'Alerta!';
	    $result['msg']['content'] = getMensajeGestion('registroErroneo');
    } else {
      $result['result'] = 1;
      $result['msg']['title'] = 'Hecho!';
      $result['msg']['content'] = getMensajeGestion('registroExitoso');

			// $proveedor = $this->session->userdata('proveedor');
			// $dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor',['idProveedor' => $proveedor['idProveedor']])->row_array();
			// $dataParaVista['cotizacion'] = $this->db->get_where('compras.cotizacion',['idCotizacion' => $post['idCotizacion']])->row_array();

			// $html = $this->load->view("formularioProveedores/correoProveedorPrecios", $dataParaVista, true);
      // $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . "FormularioProveedor/Cotizaciones/{$post['idCotizacion']}"], true);
      // $config = [
      //     'to' => 'aaron.ccenta@visualimpact.com.pe',
      //     'asunto' => 'Solicitud de Cotizacion',
      //     'contenido' => $correo,
      // ];
      // email($config);

			$insertCotizacionHistorico = [
        'idCotizacionEstado' => ESTADO_ENVIADO_COMPRAS,
				'idCotizacionInternaEstado' => INTERNA_PRECIO_RECIBIDO,
        'idCotizacion' => $post['idCotizacion'],
        'idUsuarioReg' => $this->idUsuario,
        'estado' => true,
      ];
      $insertCotizacionHistorico = $this->model->insertarProveedor(['tabla'=>'compras.cotizacionEstadoHistorico','insert'=>$insertCotizacionHistorico]);
    }

		$this->db->trans_complete();
    respuesta:
    echo json_encode($result);
	}

	public function logout(){
		$result = $this->result;
		$this->session->unset_userdata('proveedor');

		$result['msg']['title'] = 'Cerrar Sesion';
		$result['msg']['content'] = createMessage(['type'=>1,'message'=>'La sesion ha sido finalizada']);
		$result['data']['url'] = base_url()."FormularioProveedor";
		echo json_encode($result);

		redirect('FormularioProveedor','refresh');
	}

	public function viewOrdenCompra($idOrdenCompra = '0')
	{
		$proveedor = $this->session->userdata('proveedor');
		if(empty($proveedor)){
			redirect('FormularioProveedor','refresh');
			exit();
		}

		$config['css']['style'] = array(
            'assets/custom/css/floating-action-button'
        );
		$config['js']['script'] = array(
			'assets/custom/js/FormularioProveedoresCotizaciones',
			'assets/custom/js/FormularioProveedoresOC',

		);

		$config['view'] = 'formularioProveedores/ordenCompra';
		$config['data']['idOrdenCompra'] = $idOrdenCompra;
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'],'idOrdenCompra' => $idOrdenCompra,'estado' => 1])['query']->result_array();
		$config['data']['cabecera'] = $this->m_cotizacion->obtenerInformacionOrdenCompra(['id' => $idOrdenCompra])['query']->row_array();
		$config['data']['detalle'] = $ordenCompraProveedor;

		$config['single'] = true;
		if(empty($idOrdenCompra) || empty($ordenCompraProveedor)){
			$config['view'] = 'formularioProveedores/validacionEmail';
		}
		$this->view($config);
	}

	public function confirmarOrdenCompra()
	{
		$this->db->trans_start();
		$result = $this->result;
        $post = json_decode($this->input->post('data'), true);

		$post['idCotizacion'] = array_unique(checkAndConvertToArray($post['idCotizacion']));

        $updateCotizacion = [];
        $insertHistoricoCotizacion = [];
        foreach($post['idCotizacion'] as $idCotizacion){

            $updateCotizacion[] = [
                'idCotizacion' => $idCotizacion,
                'idCotizacionEstado' => ESTADO_OC_CONFIRMADA,
            ];

            $insertHistoricoCotizacion[] = [
                'idCotizacionEstado' => ESTADO_OC_CONFIRMADA,
                'idCotizacion' => $idCotizacion,
                'idUsuarioReg' => $this->idUsuario,
            ];
        }
		$updateOrdenCompra = $this->db->update("compras.ordenCompra",[
			'fechaEntrega' => $post['fechaEntrega']
		],[
			'idOrdenCompra' => $post['idOrdenCompra']
		]);

		$updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion',$updateCotizacion,'idCotizacion');
        $insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION,$insertHistoricoCotizacion);


		if($updateOrdenCompra && $updateCotizacion && $insertHistoricoCotizacion){
			$result['result'] = 1;
            $result['msg']['title'] = 'Confirmar OC';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');

			$this->db->trans_complete();
		}else{
			$result['result'] = 0;
            $result['msg']['title'] = 'Confirmar OC';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

        echo json_encode($result);
	}


}
