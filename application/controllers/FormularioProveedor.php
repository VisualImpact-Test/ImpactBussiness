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

		if (!empty($proveedor)) {
			redirect('FormularioProveedor/CotizacionesLista', 'refresh');
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

	public function login()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$proveedor = $this->model->loginProveedor($post)->row_array();

		if (empty($proveedor)) {
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Datos inválidos']);
			goto respuesta;
		}

		if ($proveedor['idProveedorEstado'] == 1) {
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Usuario por aprobar']);
			goto respuesta;
		}

		if ($proveedor['idProveedorEstado'] != 2) {
			$result['result'] = 0;
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Datos inválidos']);
			goto respuesta;
		}


		$result['result'] = 1;
		$result['msg']['content'] = createMessage(['type' => 1, 'message' => "Bienvenido <b>{$proveedor['razonSocial']}</b>"]);
		$result['data']['url'] = base_url() . "FormularioProveedor/cotizacionesLista";

		$this->session->set_userdata('proveedor', $proveedor);
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

		foreach ($zonasCobertura as $key => $value) {

			$idRegion = 0;
			$idProvincia = 0;
			$idDistrito = 0;

			!empty($value['regionCobertura']) ? $idRegion = $value['regionCobertura'] : '';
			!empty($value['provinciaCobertura']) ? $idProvincia = $value['provinciaCobertura'] : '';
			!empty($value['distritoCobertura']) ? $idDistrito = $value['distritoCobertura'] : '';

			if (!empty($zonasInsertadas[$idRegion][$idProvincia][$idDistrito])) continue;

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

		if (isset($post['correoAdicional'])) {
			foreach (checkAndConvertToArray($post['correoAdicional']) as $key => $value) {
				$data['insert'][] = [
					'idProveedor' => $insert['id'],
					'correo' => $value,
				];
			}
		}
		$fifth_insert = $this->model->insertarMasivo("compras.proveedorCorreo", $data['insert']);

		// $estadoEmail = $this->enviarCorreo($insert['id']);

		// $estadoEmail=true;

		if (!$insert['estado'] || !$second_insert['estado'] /*|| !$estadoEmail*/ || !$third_insert || !$fourth_insert || !$fifth_insert) {
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
		$this->email->to('eder.alata@visualimpact.com.pe');

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
		$bcc = [];
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

	public function validarPropuestaExistencia()
	{
		$post = json_decode($this->input->post('data'), true);
		$data = $this->model->validarPropuestaExistencia(['idCotizacionDetalleProveedorDetalle' => $post['id']])->result_array();
		if (empty($data)) {
			$rpta['continuar'] = true;
		} else {
			$rpta['continuar'] = false;
			$rpta['data'] = $data;
		}
		echo json_encode($rpta);
	}

	public function viewRegistroContraoferta()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [
			'categoria' => $this->model->obtenerCategorias()->result_array(),
			'marca' => $this->model->obtenerMarcas()->result_array(),
			'motivo' => $this->model->obtenerMotivos()->result_array(),
			'id' => $post['id']
		];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Propuesta';
		$result['data']['html'] = $this->load->view("formularioProveedores/viewRegistroContraoferta", $dataParaVista, true);

		echo json_encode($result);
	}

	public function contraofertaRegistrado()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		// $idPropuestaItem = $post['idPropuestaItem'];

		$dataParaVista = [
			'categoria' => $this->model->obtenerCategorias()->result_array(),
			'marca' => $this->model->obtenerMarcas()->result_array(),
			'motivo' => $this->model->obtenerMotivos()->result_array(),
			'id' => $post['id']
		];
		$dataParaVista['propuestaItem'] = $this->model->validarPropuestaExistencia(['idCotizacionDetalleProveedorDetalle' => $post['id']])->result_array();
		if (!empty($dataParaVista['propuestaItem'])) {
			foreach ($dataParaVista['propuestaItem'] as $key => $value) {
				$dataParaVista['propuestaItemArchivo'][$value['idPropuestaItem']] = $this->model->getPropuestaArchivos(['idPropuestaItem' => $value['idPropuestaItem']])->result_array();
			};
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Consultar Propuesta';
		$result['data']['html'] = $this->load->view("formularioProveedores/viewRegistroContraoferta", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarPropuesta()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['idCotizacionDetalleProveedorDetalle'] = checkAndConvertToArray($post['idCotizacionDetalleProveedorDetalle']);
		$post['nombre'] = checkAndConvertToArray($post['nombre']);
		$post['idItemMarca'] = checkAndConvertToArray($post['marca']);
		$post['idItemCategoria'] = checkAndConvertToArray($post['categoria']);
		$post['idPropuestaMotivo'] = checkAndConvertToArray($post['motivo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['cantidadImagenes'] = checkAndConvertToArray($post['cantidadImagenes']);
		if (isset($post['f_base64'])) $post['f_base64'] = checkAndConvertToArray($post['f_base64']);
		if (isset($post['f_name'])) $post['f_name'] = checkAndConvertToArray($post['f_name']);
		if (isset($post['f_type'])) $post['f_type'] = checkAndConvertToArray($post['f_type']);

		$orden = 0;
		$insertArchivos = [];
		foreach ($post['nombre'] as $key => $value) {
			$insertData = [
				'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$key],
				'nombre' => $post['nombre'][$key],
				'idItemMarca' => !empty($post['idItemMarca'][$key]) ? $post['idItemMarca'][$key] : NULL,
				'idItemCategoria' => !empty($post['idItemCategoria'][$key]) ? $post['idItemCategoria'][$key] : NULL,
				'idPropuestaMotivo' => $post['idPropuestaMotivo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costo' => $post['costo'][$key]
			];
			$insert = $this->db->insert('compras.propuestaItem', $insertData);
			$id = $this->db->insert_id();
			/////////////////////
			for ($i = 0; $i < intval($post['cantidadImagenes'][$key]); $i++) {
				$archivo = [
					'base64' => $post['f_base64'][$orden],
					'name' => $post['f_name'][$orden],
					'type' => $post['f_type'][$orden],
					'carpeta' => 'itemPropuesta',
					'nombreUnico' => 'PROITM_' . $id . str_replace(':', '', $this->hora) . '_' . $i,
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos[] = [
					'idPropuestaItem' => $id,
					'idTipoArchivo' => $archivo['type'] == 'pdf' ? '5' : '2',
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => $tipoArchivo[1],
					'fechaReg' => getFechaActual(),
					'horaReg' => time_change_format(getActualDateTime()),
					// 'idUsuarioReg' => $this->idUsuario
				];
				$orden++;
			}
		}
		if (!empty($insertArchivos)) {
			$insert = $this->model->insertarMasivo('compras.propuestaItemArchivo', $insertArchivos);
		}


		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');


		echo json_encode($result);
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
		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
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
		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
			exit();
		}
		$result = $this->result;
		// $post = json_decode($this->input->post('data'), true);
		$post['idProveedor'] = $proveedor['idProveedor'];
		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerListaCotizaciones($post)->result_array();
		$html = $this->load->view("formularioProveedores/cotizacionesLista-table", ['datos' => $dataParaVista, 'idProveedor' => $proveedor['idProveedor']], true);

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
		$get = [];
		$get = $this->input->get();

		foreach ($get as $k => $g) {
			$get[$k] = base64_decode($g);
		}

		if (!empty($get['doc']) && !empty($get['email']) && !empty($get['cod'])) {
			$proveedor = $this->model->loginProveedor(['ruc' => $get['doc'], 'email' => $get['email'], 'idProveedor' => $get['cod']])->row_array();
			$this->session->set_userdata('proveedor', $proveedor);
		}

		$proveedor = $this->session->userdata('proveedor');
		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
			exit();
		}

		$config['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-labels',
		);
		$config['js']['script'] = array(
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/core/gestion',
			'assets/custom/js/FormularioProveedoresCotizaciones',
		);

		$config['view'] = 'formularioProveedores/cotizaciones';
		$config['data']['idCotizacion'] = $idCotizacion;
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		// $cotizacionProveedor = $this->db->get_where('compras.cotizacionDetalleProveedor',['idProveedor' => $proveedor['idProveedor'],'idCotizacion' => $idCotizacion,'estado' => 1])->row_array();
		$cotizacionProveedor = $this->model->obtenerCotizacionDetalleProveedor(['idProveedor' => $proveedor['idProveedor'], 'idCotizacion' => $idCotizacion, 'estado' => 1])['query']->row_array();
		$config['data']['cabecera'] = $this->m_cotizacion->obtenerInformacionCotizacion(['id' => $cotizacionProveedor['idCotizacion']])['query']->row_array();

		$config['single'] = true;
		if (empty($idCotizacion) || empty($cotizacionProveedor)) {
			$config['view'] = 'formularioProveedores/validacionEmail';
		}
		$this->view($config);
	}

	public function cotizacionesRefresh()
	{
		$proveedor = $this->session->userdata('proveedor');
		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
			exit();
		}

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['idProveedor'] = $proveedor['idProveedor'];
		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerInformacionCotizacionProveedor($post)->result_array();
		$dataParaVistaImg = [];
		$dataParaVistaSub = [];
		foreach ($dataParaVista as $key => $value) {
			$dataParaVistaImg[$value['idCotizacionDetalle']] = $this->model->obtenerNombreArchivo(['idCotizacionDetalle' => $value['idCotizacionDetalle']])->result_array();
			$dataParaVistaSub[$value['idCotizacionDetalleProveedorDetalle']] = $this->model->obtenerInformacionCotizacionDetalleSub(['idCotizacionDetalleProveedorDetalle' => $value['idCotizacionDetalleProveedorDetalle']])->result_array();
		}
		$archivos = $this->model->obtenerCotizacionDetalleProveedorDetalleArchivos($post)->result_array();
		$html = $this->load->view("formularioProveedores/cotizaciones-table", [
			'datos' => $dataParaVista,
			'subdatos' => $dataParaVistaSub,
			'idProveedor' => $proveedor['idProveedor'],
			'idCotizacion' => $post['idCotizacion'],
			'archivos' => $archivos,
			'cotizacionIMG' => $dataParaVistaImg
		], true);
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

	public function calcularFechaDiasHabiles()
	{
		$post = $this->input->post();
		if (isset($post['diasHabiles'])) {
			if ($post['diasHabiles'] == 'false') {
				$fecha = !empty($post['fecha']) ? date("Ymd", strtotime($post['fecha'])) : date('Ymd');
				$fechaNueva = strtotime($post['dias'] . ' day', strtotime($fecha));
				$result = date('Y-m-d', $fechaNueva);
				goto resultado;
			}
		}
		$result = $this->model->calcularDiasHabiles($post)['fecha'];
		resultado:
		echo $result;
	}
	public function contarDiasHabiles()
	{
		$post = $this->input->post();
		if (isset($post['diasHabiles'])) {
			if ($post['diasHabiles'] == 'false') {
				$fechaIni = !empty($post['fechaIni']) ? date("Ymd", strtotime($post['fechaIni'])) : date('Ymd');
				$fechaFin = !empty($post['fechaFin']) ? date("Ymd", strtotime($post['fechaFin'])) : date('Ymd');

				$dias = (strtotime($fechaFin) - strtotime($fechaIni)) / 86400;
				$result = round($dias, 0, PHP_ROUND_HALF_UP);
				goto resultado;
			}
		}
		$result = $this->model->contarDiasHabiles($post)['conteo'];
		resultado:
		echo $result;
	}

	public function actualizarCotizacionProveedor()
	{

		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['idCotizacionDetalleProveedorDetalle'] = checkAndConvertToArray($post['idCotizacionDetalleProveedorDetalle']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['costoUnitario'] = checkAndConvertToArray($post['costoUnitario']);
		$insertArchivos = [];

		$post['diasValidez'] = checkAndConvertToArray($post['diasValidez']);
		$post['fechaValidez'] = checkAndConvertToArray($post['fechaValidez']);
		$post['comentario'] = checkAndConvertToArray($post['comentario']);
		$post['diasEntrega'] = checkAndConvertToArray($post['diasEntrega']);
		$post['fechaEntrega'] = checkAndConvertToArray($post['fechaEntrega']);
		$post['idItem'] = checkAndConvertToArray($post['idItem']);

		foreach ($post['idCotizacionDetalleProveedorDetalle'] as $k => $r) {
			// Para actualizar el detalle
			$subTotal = (!empty($post['costo'][$k])) ? $post['costo'][$k] : 0;
			$cantidad = (!empty($post['cantidad'][$k])) ? $post['cantidad'][$k] : 0;
			$data['update'][] = [
				'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$k],
				'costo' => $subTotal,
				// 'flag_activo' => 1,
				'diasValidez' => $post['diasValidez'][$k],
				'fechaValidez' => $post['fechaValidez'][$k],
				'comentario' => $post['comentario'][$k],
				'diasEntrega' => $post['diasEntrega'][$k],
				'fechaEntrega' => $post['fechaEntrega'][$k],
			];


			// Para archivos
			if (isset($post['file-type[' . $r . ']'])) {
				$post['file-type[' . $r . ']'] = checkAndConvertToArray($post['file-type[' . $r . ']']);
				$post['file-item[' . $r . ']'] = checkAndConvertToArray($post['file-item[' . $r . ']']);
				$post['file-name[' . $r . ']'] = checkAndConvertToArray($post['file-name[' . $r . ']']);
				foreach ($post['file-type[' . $r . ']'] as $key => $value) {
					$archivo = [
						'base64' => $post['file-item[' . $r . ']'][$key],
						'name' => $post['file-name[' . $r . ']'][$key],
						'type' => $post['file-type[' . $r . ']'][$key],
						'carpeta' => 'cotizacionProveedor',
						'nombreUnico' => 'COTIPRO' . $post['idCotizacionDetalleProveedorDetalle'][$k] . str_replace(':', '', $this->hora) . '_' . $key . 'CP',
					];

					$archivoName = $this->saveFileWasabi($archivo);

					$tipoArchivo = explode('/', $archivo['type']);
					$insertArchivos[] = [
						'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$k],
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivo['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivo['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario
					];
				}
			}

			// Para actualizar el SubDetalle
			if (isset($post['idCDPD[' . $r . ']']) && $r != '0') {
				$post['idCDPD[' . $r . ']'] = checkAndConvertToArray($post['idCDPD[' . $r . ']']);
				$post['idCDPDS[' . $r . ']'] = checkAndConvertToArray($post['idCDPDS[' . $r . ']']);
				$post['costo[' . $r . ']'] = checkAndConvertToArray($post['costo[' . $r . ']']);
				$post['subtotal[' . $r . ']'] = checkAndConvertToArray($post['subtotal[' . $r . ']']);
				$post['descripcion[' . $r . ']'] = checkAndConvertToArray($post['descripcion[' . $r . ']']);
				$post['cantidad[' . $r . ']'] = checkAndConvertToArray($post['cantidad[' . $r . ']']);

				foreach ($post['idCDPDS[' . $r . ']'] as $key => $value) {
					$updateDetalleSub['update'][] = [
						'idCotizacionDetalleProveedorDetalleSub' => $value,
						'costo' => $post['costo[' . $r . ']'][$key],
						'subtotal' => $post['subtotal[' . $r . ']'][$key],
						'descripcion' => $post['descripcion[' . $r . ']'][$key],
						'cantidad' => $post['cantidad[' . $r . ']'][$key],
					];
				}

				$updateDetalleSub['tabla'] = 'compras.cotizacionDetalleProveedorDetalleSub';
				$updateDetalleSub['where'] = 'idCotizacionDetalleProveedorDetalleSub';

				$this->m_cotizacion->actualizarCotizacionDetalle($updateDetalleSub);
				$updateDetalleSub = [];
			}
		}

		if (!empty($insertArchivos)) {
			$this->db->insert_batch('compras.cotizacionDetalleProveedorDetalleArchivos', $insertArchivos);
		}

		$data['tabla'] = 'compras.cotizacionDetalleProveedorDetalle';
		$data['where'] = 'idCotizacionDetalleProveedorDetalle';
		$updateDetalle = $this->m_cotizacion->actualizarCotizacionDetalle($data);
		$data = [];

		if (isset($post['idCDPD[0]'])) { // Significa que hay nuevos registros para guardar en "cotizacionDetalleProveedorDetalleSub"
			$post['idCDPD[0]'] = checkAndConvertToArray($post['idCDPD[0]']);
			$post['idCDPDS[0]'] = checkAndConvertToArray($post['idCDPDS[0]']);
			$post['costo[0]'] = checkAndConvertToArray($post['costo[0]']);
			$post['subtotal[0]'] = checkAndConvertToArray($post['subtotal[0]']);
			$post['descripcion[0]'] = checkAndConvertToArray($post['descripcion[0]']);
			$post['cantidad[0]'] = checkAndConvertToArray($post['cantidad[0]']);
			$post['sucursal[0]'] = checkAndConvertToArray($post['sucursal[0]']);
			$post['razonSocial[0]'] = checkAndConvertToArray($post['razonSocial[0]']);
			$post['tipoElemento[0]'] = checkAndConvertToArray($post['tipoElemento[0]']);
			$post['marca[0]'] = checkAndConvertToArray($post['marca[0]']);

			foreach ($post['idCDPD[0]'] as $key => $value) {
				$insertSub[] = [
					'idCotizacionDetalleProveedorDetalle' => $value,
					'idCotizacionDetalleSub' => null,
					'costo' => $post['costo[0]'][$key],
					'subtotal' => $post['subtotal[0]'][$key],
					'descripcion' => $post['descripcion[0]'][$key],
					'cantidad' => $post['cantidad[0]'][$key],
					'sucursal' => $post['sucursal[0]'][$key],
					'razonSocial' => $post['razonSocial[0]'][$key],
					'tipoElemento' => $post['tipoElemento[0]'][$key],
					'marca' => $post['marca[0]'][$key]
				];
			}

			$this->model->insertarMasivo('compras.cotizacionDetalleProveedorDetalleSub', $insertSub);
		}

		if (!$updateDetalle['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');

			$insertCotizacionHistorico = [
				'idCotizacionEstado' => ESTADO_ENVIADO_COMPRAS,
				'idCotizacionInternaEstado' => INTERNA_PRECIO_RECIBIDO,
				'idCotizacion' => $post['idCotizacion'],
				'idUsuarioReg' => $this->idUsuario,
				'estado' => true,
			];
			$insertCotizacionHistorico = $this->model->insertarProveedor(['tabla' => 'compras.cotizacionEstadoHistorico', 'insert' => $insertCotizacionHistorico]);

			$insertItemTarifario = [];

			foreach ($post['idItem'] as $key => $value) {
				if (!empty($post['idItem'][$key])) {

					$datos[] = [
						'idItem' => $value,
						'idProveedor' => $post['idProveedor'],
						'estado' => '1'
					];
					$dataTarifario = $this->model->getWhereJoinMultiple('compras.itemTarifario', $datos)->row_array();
					
					$dataIT = [
						'idItem' => $value,
						'idProveedor' => $post['idProveedor'],
						'costo' => $post['costoUnitario'][$key],
						'fechaVigencia' => $post['fechaValidez'][$key],
						'estado' => '1',
						'flag_actual' => '0'
					];
					if (empty($dataTarifario)) { // Si aún no se registra el Item.
						if (!empty($this->db->get_where('compras.itemTarifario', ['idItem' => $value, 'flag_actual' => '1'])->row_array())) {
							$dataIT['flag_actual'] = '1';
						}
						$rpta = $this->db->insert('compras.itemTarifario', $dataIT);
						$idItemTarifario = $this->db->insert_id();
					}else{
						$idItemTarifario = $dataTarifario['idItemTarifario'];
						$this->db->update('compras.itemTarifario', $dataIT, ['idItemTarifario' => $idItemTarifario]);
					}

					$historicoInsert = [
						'idItemTarifario' => $idItemTarifario,
						'fecIni' => getFechaActual(),
						'fecFin' => $post['fechaValidez'][$key],
						'costo' => $post['costoUnitario'][$key]
					];
					$rpta = $this->db->insert('compras.itemTarifarioHistorico', $historicoInsert);
				}
			}
		}

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function logout()
	{
		$result = $this->result;
		$this->session->unset_userdata('proveedor');

		$result['msg']['title'] = 'Cerrar Sesion';
		$result['msg']['content'] = createMessage(['type' => 1, 'message' => 'La sesion ha sido finalizada']);
		$result['data']['url'] = base_url() . "FormularioProveedor";
		echo json_encode($result);

		redirect('FormularioProveedor', 'refresh');
	}

	public function viewOrdenCompra($idOrdenCompra = '0')
	{
		$get = [];
		$get = $this->input->get();

		foreach ($get as $k => $g) {
			$get[$k] = base64_decode($g);
		}

		$hoy = new DateTime(date('Y-m-d'));
		$fechaAcceso = new DateTime(!empty($get['date']) ? $get['date'] : date('1999-01-01'));
		$diasDiferencia = $fechaAcceso->diff($hoy)->days;

		if ($diasDiferencia > DIAS_MAX_ACCESO) {
			echo 'Ha excedido los dias de acceso disponibles';
			redirect('FormularioProveedor', 'refresh');
			exit();
		}

		if (!empty($get['doc']) && !empty($get['email']) && !empty($get['cod'])) {
			$proveedor = $this->model->loginProveedor(['ruc' => $get['doc'], 'email' => $get['email'], 'idProveedor' => $get['cod']])->row_array();
			$this->session->set_userdata('proveedor', $proveedor);
		}

		$proveedor = $this->session->userdata('proveedor');

		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
			exit();
		}

		$config['css']['style'] = array(
			'assets/custom/css/floating-action-button'
		);
		$config['js']['script'] = array(
			'assets/custom/js/FormularioProveedoresCotizaciones',
			'assets/custom/js/FormularioProveedoresOC',
			'assets/libs/fileDownload/jquery.fileDownload',

		);

		$config['view'] = 'formularioProveedores/ordenCompra';
		$config['data']['idOrdenCompra'] = $idOrdenCompra;
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'], 'idOrdenCompra' => $idOrdenCompra, 'estado' => 1])['query']->result_array();
		$config['data']['cabecera'] = $this->m_cotizacion->obtenerInformacionOrdenCompra(['id' => $idOrdenCompra])['query']->row_array();
		$config['data']['detalle'] = $ordenCompraProveedor;

		$config['data']['imagen'] = [];

		if (!empty($config['data']['cabecera']['mostrar_imagenes'])) {
			foreach ($ordenCompraProveedor as $k => $v) {
				// foreach ($this->db->where('idTipoArchivo', '2')->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleArchivos')->result_array() as $vq1) {
				// 	$vq1['carpeta'] = 'cotizacion/';
				// 	$config['data']['imagen'][$v['idCotizacionDetalle']][] = $vq1;
				// }
				foreach ($this->db->where('idItem', $v['idItem'])->get('compras.itemImagen')->result_array() as $vq1) {
					$vq1['carpeta'] = 'item/';
					$config['data']['imagen'][$v['idCotizacionDetalle']][] = $vq1;
				}
			}
		}
		if (!empty($config['data']['cabecera']['mostrar_imagenesCoti'])) {
			foreach ($ordenCompraProveedor as $k => $v) {
				$xxdd = $this->m_cotizacion->getImagenCotiProv(['idCotizacionDetalle' => $v['idCotizacionDetalle'], 'idProveedor' => $v['idProveedor']])->result_array();
				foreach ($xxdd as $vq1) {
					$vq1['carpeta'] = 'cotizacion/';
					$config['data']['imagen'][$v['idCotizacionDetalle']][] = $vq1;
				}
			}
		}

		$config['single'] = true;
		if (empty($idOrdenCompra) || empty($ordenCompraProveedor)) {
			$config['view'] = 'formularioProveedores/validacionEmail';
		}

		$this->view($config);
	}

	public function descargarOrdenCompra()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$post = json_decode($this->input->post('data'), true);

		$ordenCompra = $this->model->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();

		$dataParaVista['data'] = $ordenCompra[0];
		$dataParaVista['detalle'] = $ordenCompra;

		$cotDet = [];
		foreach ($ordenCompra as $k => $v) {
			$cotDet[] = $v['idCotizacionDetalle'];
		}
		$cotizacionDet = implode(',', $cotDet);

		$dataParaVista['imagenesDeItem'] = [];
		if ($dataParaVista['data']['mostrar_imagenes'] == '1') {
			foreach ($dataParaVista['detalle'] as $key => $value) {
				$dataParaVista['imagenesDeItem'][$value['idItem']] = $this->db->where('idItem', $value['idItem'])->get('compras.itemImagen')->result_array();
			}
		}
		
		if ($dataParaVista['data']['mostrar_imagenesCoti'] == '1') {
			foreach ($ordenCompra as $key => $value) {
				$dd = $this->m_cotizacion->getImagenCotiProv(['idCotizacionDetalle' => $value['idCotizacionDetalle'], 'idProveedor' => $value['idProveedor']])->result_array();
				foreach ($dd as $kl => $vl) {
					$dataParaVista['imagenesDeItem'][$value['idItem']][] = $vl;
				}
			}
		}

		foreach ($dataParaVista['detalle'] as $k => $v) {
			$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
		}

		$ids = [];
		foreach ($ordenCompra as $v) {
			$cuenta = $this->m_cotizacion->obtenerCuentaDeLaCotizacionDetalle($v['idCotizacion']);
			$centroCosto = $this->m_cotizacion->obtenerCentroCostoDeLaCotizacionDetalle($v['idCotizacion']);

			$cuentas[$cuenta] = $this->db->get_where('rrhh.dbo.Empresa', ['idEmpresa' => $cuenta])->row_array()['nombre'];
			$centrosDeCosto[$centroCosto] = $this->db->get_where('rrhh.dbo.empresa_Canal', ['idEmpresaCanal' => $centroCosto])->row_array()['subcanal'];
			$ids[] = $v['idCotizacion'];
		}
		$dataParaVista['cuentas'] = implode(', ', $cuentas);
		$dataParaVista['centrosCosto'] = implode(', ', $centrosDeCosto);
		$idCotizacion = implode(",", $ids);
		
		require APPPATH . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
			// 'orientation' => '',
			'autoMarginPadding' => 0,
			'bleedMargin' => 0,
			'crossMarkMargin' => 0,
			'cropMarkMargin' => 0,
			'nonPrintMargin' => 0,
			'margBuffer' => 0,
			'collapseBlockMargins' => false,
		]);

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'ORDEN DE COMPRA DE BIENES Y SERVICIOS', 'codigo' => 'SIG-LOG-FOR-009'], true);
		$contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);

		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/orden_compra", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');

		$cod_oc = generarCorrelativo($dataParaVista['data']['idOrdenCompra'], 6);
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("OC{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
	}

	public function confirmarOrdenCompra()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['idCotizacion'] = array_unique(checkAndConvertToArray($post['idCotizacion']));

		$updateCotizacion = [];
		$insertHistoricoCotizacion = [];
		foreach ($post['idCotizacion'] as $idCotizacion) {

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
		$updateOrdenCompra = $this->db->update("compras.ordenCompra", [
			'fechaEntrega' => $post['fechaEntrega']
		], [
			'idOrdenCompra' => $post['idOrdenCompra']
		]);

		$updateCotizacion = $this->model->actualizarMasivo('compras.cotizacion', $updateCotizacion, 'idCotizacion');
		$insertHistoricoCotizacion = $this->model->insertarMasivo(TABLA_HISTORICO_ESTADO_COTIZACION, $insertHistoricoCotizacion);


		if ($updateOrdenCompra && $updateCotizacion && $insertHistoricoCotizacion) {
			$result['result'] = 1;
			$result['msg']['title'] = 'Confirmar OC';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');

			$this->db->trans_complete();
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Confirmar OC';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		echo json_encode($result);
	}

	public function getFormCargaMasivaCotizacionProveedorDetalleSub()
	{
		$result = $this->result;
		$result['msg']['title'] = "Carga masiva de servicio";
		$idCotizacionDetalleProveedorDetalle = $this->input->post('data');

		$proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]], '*', [], 'razonSocial')->result_array();
		$proveedores = refactorizarDataHT(["data" => $proveedores, "value" => "razonSocial"]);

		// $item['item'] = []; // $this->model->obtenerItems();
		// $itemNombre = refactorizarDataHT(["data" => $item['item'], "value" => "label"]);

		$datos = $this->db->where('estado', '1')->where('idCotizacionDetalleProveedorDetalle', $idCotizacionDetalleProveedorDetalle)->get('compras.cotizacionDetalleProveedorDetalleSub')->result_array();
		$datosHt = [];
		foreach ($datos as $v) {
			$datosHt[] = [
				'sucursal' => $v['sucursal'],
				'razonSocial' => $v['razonSocial'],
				'tipoElemento' => $v['tipoElemento'],
				'marca' => $v['marca'],
				'descripcion' => $v['descripcion'],
				'cantidad' => $v['cantidad'],
				'precUnitario' => $v['costo'],
			];
		}
		$datosHt[] = [
			'sucursal' => null,
			'razonSocial' => null,
			'tipoElemento' => null,
			'marca' => null,
			'descripcion' => null,
			'cantidad' => null,
			'precUnitario' => null,
		];
		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Servicio Detalle',
			'data' => $datosHt /*[
				[
					'sucursal' => null,
					'razonSocial' => null,
					'tipoElemento' => null,
					'marca' => null,
					'descripcion' => null,
					'cantidad' => null,
					'precUnitario' => null,
				]
			]*/,
			'headers' => [
				'SUCURSAL (*)',
				'RAZON SOCIAL (*)',
				'TIPO ELEMENTO (*)',
				'MARCA (*)',
				'DESCRIPCION (*)',
				'CANTIDAD (*)',
				'PREC UNITARIO (*)',
			],
			'columns' => [
				['data' => 'sucursal', 'type' => 'text', 'placeholder' => 'Sucursal', 'width' => 200],
				['data' => 'razonSocial', 'type' => 'text', 'placeholder' => 'Razon Social', 'width' => 200],
				['data' => 'tipoElemento', 'type' => 'text', 'placeholder' => 'Tipo Elemento', 'width' => 200],
				['data' => 'marca', 'type' => 'text', 'placeholder' => 'Marca', 'width' => 200],
				['data' => 'descripcion', 'type' => 'text', 'placeholder' => 'Descripción', 'width' => 400],
				['data' => 'cantidad', 'type' => 'numeric', 'placeholder' => 'Cantidad', 'width' => 200],
				['data' => 'precUnitario', 'type' => 'numeric', 'placeholder' => 'Prec. Unitario', 'width' => 200],
			],
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;


		echo json_encode($result);
	}

	public function guardarCargaMasivaCotizacionProveedorDetalleSub()
	{

		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);

		$this->db->trans_start();

		$result = $this->result;
		$result['msg']['title'] = "Carga masiva de servicio";

		$post = json_decode($this->input->post('data'), true);

		//Eliminar la fila en blanco
		array_pop($post['HT'][0]);

		foreach ($post['HT'][0] as $tablaHT) {

			if (
				empty($tablaHT['sucursal']) ||
				empty($tablaHT['razonSocial']) ||
				empty($tablaHT['tipoElemento']) ||
				empty($tablaHT['marca']) ||
				empty($tablaHT['descripcion']) ||
				empty($tablaHT['cantidad']) ||
				empty($tablaHT['precUnitario'])
			) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			$dataServicio['insert'][] = [
				'idCotizacionDetalleProveedorDetalle' => $post['id'],
				'sucursal' => $tablaHT['sucursal'],
				'razonSocial' => $tablaHT['razonSocial'],
				'tipoElemento' => $tablaHT['tipoElemento'],
				'marca' => $tablaHT['marca'],
				'descripcion' => $tablaHT['descripcion'],
				'cantidad' => $tablaHT['cantidad'],
				'costo' => $tablaHT['precUnitario'],
				'subTotal' => floatval($tablaHT['cantidad']) * floatval($tablaHT['precUnitario'])
			];
		}
		if (empty($dataServicio['insert'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
			goto respuesta;
		}
		$this->db->update('compras.cotizacionDetalleProveedorDetalleSub', ['estado' => 0], ['idCotizacionDetalleProveedorDetalle' => $post['id']]);
		$insertarServicios = $this->model->insertarMasivo('compras.cotizacionDetalleProveedorDetalleSub', $dataServicio['insert']);

		$cdpds = $this->db->where('estado', 1)->where('idCotizacionDetalleProveedorDetalle', $post['id'])->get('compras.cotizacionDetalleProveedorDetalleSub')->result_array();
		$montoTotal = 0;
		foreach ($cdpds as $key => $value) {
			$montoTotal += floatval($value['subTotal']);
		};

		$update = $this->db->update('compras.cotizacionDetalleProveedorDetalle', ['costo' => $montoTotal], ['idCotizacionDetalleProveedorDetalle' => $post['id']]);

		if (!$insertarServicios) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_commit();
		}

		respuesta:
		echo json_encode($result);
	}
}
