<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FormularioProveedor extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_FormularioProveedor', 'model');
		$this->load->model('M_cotizacion', 'm_cotizacion');
		$this->load->model('M_proveedor', 'm_proveedor');
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
		$config['data']['metodoPago'] = $this->model->obtenerMetodoPago()->result_array();
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
		$config['data']['metodoPago'] = $this->model->obtenerMetodoPago()->result_array();
		$config['data']['tipoServicio'] = $this->m_proveedor->obtenerProveedorTipoServicio()->result_array();
		$config['data']['comprobante'] = $this->m_proveedor->obtenerComprobante()['query']->result_array();
		$config['data']['bancos'] = $this->db->get_where('dbo.banco')->result_array();
		$config['data']['tiposCuentaBanco'] = $this->db->get_where('dbo.tipoCuentaBanco')->result_array();
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
			'cod_ubigeo' => $post['distrito'],
			'direccion' => $post['direccion'],
			'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
			'idProveedorEstado' => 5,
			'nombreContacto' => $post['nombreContacto'],
			'correoContacto' => $post['correoContacto'],
			'numeroContacto' => $post['numeroContacto'],
			'cuenta' => verificarEmpty($post['cuentaPrincipal'], 4),
			'cci' => verificarEmpty($post['cuentaInterbancariaPrincipal'], 4),
			'idBanco' => verificarEmpty($post['banco'], 4),
			'idTipoCuentaBanco' => verificarEmpty($post['tipoCuenta'], 4),
			'chkDetraccion' => isset($post["chkDetraccion"]) ? 1 : 0,
			'cuentaDetraccion' => verificarEmpty($post['cuentaDetraccion'], 4),
		];

		$validacionExistencia = $this->model->validarExistenciaProveedor($data['insert']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.proveedor';

		// Inicio: Validando que no falte la captura de cuenta antes de guardar la información
		// → Captura Principal: Obligatorio
		if (
			!isset($post['cuentaPrincipalFile-item']) ||
			!isset($post['cuentaPrincipalFile-name']) ||
			!isset($post['cuentaPrincipalFile-type'])
		) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta']);
			goto respuesta;
		}
		// → Captura Detraccion: Obligatorio si marca el check
		if (
			isset($post["chkDetraccion"]) &&
			(
				!isset($post['cuentaDetraccionFile-item']) ||
				!isset($post['cuentaDetraccionFile-name']) ||
				!isset($post['cuentaDetraccionFile-type'])
			)
		) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta Detracción']);
			goto respuesta;
		}
		// Fin

		$post['cuentaPrincipalFile-item'] = checkAndConvertToArray($post['cuentaPrincipalFile-item']);
		$post['cuentaPrincipalFile-name'] = checkAndConvertToArray($post['cuentaPrincipalFile-name']);
		$post['cuentaPrincipalFile-type'] = checkAndConvertToArray($post['cuentaPrincipalFile-type']);

		$post['cuentaDetraccionFile-item'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-item']) ? $post['cuentaDetraccionFile-item'] : []
		);
		$post['cuentaDetraccionFile-name'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-name']) ? $post['cuentaDetraccionFile-name'] : []
		);
		$post['cuentaDetraccionFile-type'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-type']) ? $post['cuentaDetraccionFile-type'] : []
		);

		$insert = $this->model->insertarProveedor($data);
		$idProveedor = $insert['id'];
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
				'idProveedor' => $idProveedor,
				'cod_departamento' => verificarEmpty($value['regionCobertura'], 4),
				'cod_provincia' => verificarEmpty($value['provinciaCobertura'], 4),
				'cod_distrito' => verificarEmpty($value['distritoCobertura'], 4),
			];

			$zonasInsertadas[$idRegion][$idProvincia][$idDistrito] = 1;
		}

		$data['tabla'] = 'compras.zonaCobertura';

		$second_insert = $this->model->insertarProveedorCobertura($data);
		$data = [];

		foreach (checkAndConvertToArray($post['metodoPago']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $idProveedor,
				'idMetodoPago' => $value,

			];
		}

		$third_insert = $this->model->insertarMasivo("compras.proveedorMetodoPago", $data['insert']);
		$data = [];

		foreach (checkAndConvertToArray($post['rubro']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $idProveedor,
				'idRubro' => $value,
			];
		}

		$fourth_insert = $this->model->insertarMasivo("compras.proveedorRubro", $data['insert']);
		$data = [];

		foreach (checkAndConvertToArray($post['comprobante']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $idProveedor,
				'idComprobante' => $value,
			];
		}

		$fourth_insert = $this->model->insertarMasivo("compras.proveedorComprobante", $data['insert']);
		$data = [];

		// tipoServicio
		foreach (checkAndConvertToArray($post['tipoServicio']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $idProveedor,
				'idProveedorTipoServicio' => $value,
			];
		}

		$tipoServicio_insert = $this->model->insertarMasivo("compras.proveedorProveedorTipoServicio", $data['insert']);
		$data = [];

		$fifth_insert = true;
		if (isset($post['correoAdicional'])) {
			foreach (checkAndConvertToArray($post['correoAdicional']) as $key => $value) {
				$data['insert'][] = [
					'idProveedor' => $idProveedor,
					'correo' => $value,
				];
			}
			$fifth_insert = $this->model->insertarMasivo("compras.proveedorCorreo", $data['insert']);
		}
		$data = [];

		// INICIO: Para subir archivos del proveedor → Funciona con multiples archivos.
		$insertArchivos = [];
		// → Archivo cuenta principal
		if (!empty($post['cuentaPrincipalFile-item'])) {
			foreach ($post['cuentaPrincipalFile-item'] as $k => $v) {
				$archivo = [
					'base64' => $post['cuentaPrincipalFile-item'][$k],
					'name' => $post['cuentaPrincipalFile-name'][$k],
					'type' => $post['cuentaPrincipalFile-type'][$k],
					'carpeta' => 'proveedorAdjuntos',
					'nombreUnico' => 'Cuenta_' . $idProveedor . '_' . str_replace(':', '', $this->hora) . '_' . $k,
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);
				$insertArchivos[] = [
					'idProveedor' => $idProveedor,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'estado' => true,
					'idUsuarioReg' => $this->idUsuario,
					'flagPrincipal' => true,
				];
			}
		}
		// → Archivo cuenta detracción
		if (!empty($post['cuentaDetraccionFile-item'])) {
			foreach ($post['cuentaDetraccionFile-item'] as $k => $v) {
				$archivo = [
					'base64' => $post['cuentaDetraccionFile-item'][$k],
					'name' => $post['cuentaDetraccionFile-name'][$k],
					'type' => $post['cuentaDetraccionFile-type'][$k],
					'carpeta' => 'proveedorAdjuntos',
					'nombreUnico' => 'CuentaDetraccion_' . $idProveedor . '_' . str_replace(':', '', $this->hora) . '_' . $k,
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);
				$insertArchivos[] = [
					'idProveedor' => $idProveedor,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'estado' => true,
					'idUsuarioReg' => $this->idUsuario,
					'flagPrincipal' => false,
				];
			}
		}
		if (!empty($insertArchivos)) $this->db->insert_batch('compras.proveedorArchivo', $insertArchivos);
		// FIN: Para subir archivos del proveedor

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
			// 'smtp_host' => 'aspmx.l.google.com',
			// 'smtp_port' => '25',
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
		$this->email->bcc($bcc);

		$this->email->subject('IMPACTBUSSINESS - NUEVA ENTRADA DE PROVEEDORES');
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
			$idItemMarca = $post['idItemMarca'][$key];
			if (!empty($post['idItemMarca'][$key]) && !is_numeric($post['idItemMarca'][$key])) {
				$this->db->insert('compras.itemMarca', ['nombre' => $post['idItemMarca'][$key], 'estado' => 1]);
				$idItemMarca = $this->db->insert_id();
			}
			$insertData = [
				'idCotizacionDetalleProveedorDetalle' => $post['idCotizacionDetalleProveedorDetalle'][$key],
				'nombre' => $post['nombre'][$key],
				'idItemMarca' => !empty($post['idItemMarca'][$key]) ? $idItemMarca : NULL,
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
			$msj = 1;
		} else {
			// Si entra aqui, es un robot....
			$msj = 0;
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
		$config['js']['script'] = array(
			'assets/custom/js/FormularioProveedoresCotizacionesLista',
			'assets/libs/fileDownload/jquery.fileDownload'
		);

		$config['view'] = 'formularioProveedores/cotizacionesLista';
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		$config['single'] = true;
		$this->view($config);
	}

	public function cotizacionesListaRefresh()
	{
		// En caso no se encuentre proveedor logueado
		$proveedor = $this->session->userdata('proveedor');
		if (empty($proveedor)) {
			redirect('FormularioProveedor', 'refresh');
			exit();
		}

		$result = $this->result;
		$post['idProveedor'] = $proveedor['idProveedor'];
		$dataParaVista = [];

		$data1 = $this->model->obtenerListaCotizaciones($post)->result_array();
		$data2 = $this->model->obtenerListaCotizaciones2($post)->result_array();

		$data = array_merge($data1, $data2);
		$data = ordenarArrayPorColumna($data, 'fechaEmision', SORT_DESC);

		foreach ($data as $k => $v) {
			// Inicio: Para número de Oper
			$idOp = $this->db->get_where('compras.operDetalle', ['idCotizacion' => $v['idCotizacion'], 'estado' => '1'])->row_array()['idOper'];
			$data[$k]['operData'] = $this->db->get_where('compras.oper', ['idOper' => $idOp])->row_array();
			// Fin: Para número de Oper

			// Inicio: Para el titulo de la cotizacion
			$data[$k]['title'] = $v['nombre'];
			$st = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacion' => $v['idCotizacion']])->result_array();
			$title = [];
			$data[$k]['requiereGuia'] = 1;
			foreach ($st as $vt) {
				if (!empty($vt['tituloParaOC'])) $title[] = $vt['tituloParaOC'];
				if ($vt['idItemTipo'] == COD_SERVICIO['id'] || $vt['idItemTipo'] == COD_DISTRIBUCION['id']) $data[$k]['requiereGuia'] = 0;
				//$data[$k]['adjuntoFechaEjecucion'] = $this->db->get_where('compras.cotizacionDetalleProveedorFechaEjecucion', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor']])->result_array();
			}
			if (!empty($title)) {
				$data[$k]['title'] = 'COTIZACIÓN - ' . implode(', ', $title);
			}
			// Fin: Para el titulo de la cotizacion

			// Inicio: Para el estado del proveedor
			$data[$k]['mostrarValidacion'] = '2'; // No requiere Val Art
			$data[$k]['solicitarFecha'] = '1';
			$data[$k]['flagFechaRegistro'] = '0';
			$data[$k]['flagSustentoServicio'] = '0';
			$flagOcLibre = base64_encode($v['flagOcLibre']);

			if (!empty($v['idOrdenCompra'])) {
				$data[$k]['status'] = 'Aprobado';
				// Se consulta los tipos de Item, considerando que solo se requiere Validación de Arte para SERVICIO (Mantenimiento), Textiles e Impresiones.
				// TODO falta generar tipo de item "Impresiones"
				$listDetalleCotProv = $this->db->get_where('orden.ordenCompraDetalle', ['idOrdenCompra' => $v['idOrdenCompra']])->result_array();
				foreach ($listDetalleCotProv as $vt) {
					$it = $this->db->get_where('orden.ordenCompraDetalle', ['idOrdenCompra' => $vt['idOrdenCompra'], 'estado' => 1])->row_array()['idTipo'];

					if ($it == COD_SERVICIO['id'] || $it == COD_TEXTILES['id']) {
						$data[$k]['requiereValidacion'] = '1';
						$data[$k]['mostrarValidacion'] = '1';
						$data[$k]['solicitarFecha'] = '0';
					}
				}

				// Se consulta si tiene "Validación de Arte" cargado aprobados.
				$va = $this->db->group_start()->where('flagRevisado', 0)
					->or_where('flagAprobado', 1)->group_end()
					->where('idProveedor', $v['idProveedor'])
					->where('idOrdenCompra', $v['idOrdenCompra'])
					->where('flagOcLibre', $v['flagOcLibre'])
					->where('estado', 1)->get('sustento.validacionArte')->result_array();
				if (!empty($va)) {
					$data[$k]['mostrarValidacion'] = '0';
				}

				// Se compara el Total de Artes Cargados con el Total de Artes Aprobados.
				$w = ['idProveedor' => $v['idProveedor'], 'idOrdenCompra' => $v['idOrdenCompra'], 'flagOcLibre' => $v['flagOcLibre'], 'estado' => 1];
				$artesCargados = $this->db->get_where('sustento.validacionArte', $w)->result_array();
				$w['flagRevisado'] = 1;
				$w['flagAprobado'] = 1;
				$artesAprobados = $this->db->get_where('sustento.validacionArte', $w)->result_array();

				if (!empty($artesAprobados)) {
					if (count($artesAprobados) == count($artesCargados)) {
						$data[$k]['solicitarFecha'] = '1';
					}
				}

				// Si se solicita fecha, validar si la información fue cargada o no.
				if ($data[$k]['solicitarFecha'] == '1') {
					$fechaE = [
						'idOrdenCompra' => $v['idOrdenCompra'],
						'idProveedor' => $v['idProveedor'],
						'flagOcLibre' => $v['flagOcLibre'], 'estado' => '1'
					];

					if ($v['flagOcLibre'] == 0) {
						$fechaE['idCotizacion'] = $v['idCotizacion'];
					}

					$fechaEjecCargado = $this->db->get_where('sustento.fechaEjecucion', $fechaE)->result_array();

					if (!empty($fechaEjecCargado)) {
						$data[$k]['flagFechaRegistro'] = '1';
						$data[$k]['fechaInicio'] = $fechaEjecCargado[0]['fechaInicial'];
						$data[$k]['fechaFinal'] = $fechaEjecCargado[0]['fechaFinal'];
					}
				}
			} else {
				$data[$k]['status'] = 'Aprobado';
				$fechaE = [
					'idOrdenCompra' => $v['idOrdenCompra'],
					'idProveedor' => $v['idProveedor'],
					'flagOcLibre' => $v['flagOcLibre'], 'estado' => '1'
				];

				if ($v['flagOcLibre'] == 0) {
					$fechaE['idCotizacion'] = $v['idCotizacion'];
				}

				$fechaEjecCargado = $this->db->get_where('sustento.fechaEjecucion', $fechaE)->result_array();
				if (!empty($fechaEjecCargado)) {
					$data[$k]['flagFechaRegistro'] = '1';
					$data[$k]['fechaInicio'] = $fechaEjecCargado[0]['fechaInicial'];
					$data[$k]['fechaFinal'] = $fechaEjecCargado[0]['fechaFinal'];
				}
			}

			$data[$k]['ocGen'] = $v['seriado'];

			$sustComp = $this->db->get_where('sustento.sustentoAdjunto', [
				'idOrdenCompra' => $v['idOrdenCompra'],
				'idProveedor' => $v['idProveedor'],
				'flagoclibre' => $v['flagOcLibre'],
				'estado' => '1'
			])->result_array();
			$data[$k]['sustentoComp'] = $sustComp;

			if (!empty($sustComp)) {
				$data[$k]['flagSustentoServicio'] = '1';
				foreach ($sustComp as $rSC) {
					if ($rSC['flagRevisado'] == '0' || $rSC['flagAprobado'] == '0') {
						$data[$k]['flagSustentoServicio'] = '0';
					}
				}
			}

			$va4 = $this->db->where('estado', '1')
				->where('idOrdenCompra', $v['idOrdenCompra'])
				->where('idProveedor', $v['idProveedor'])
				->where('flagoclibre', $v['flagOcLibre'])
				->get('sustento.comprobante')->result_array();
			foreach ($va4 as $v4) {
				$data[$k]['sustentoC'][$v4['idOrdenCompra']][$v4['idProveedor']] = $v4;
			}
			$accesoDocumento = !empty($proveedor['nroDocumento']) ? base64_encode($proveedor['nroDocumento']) : '';
			$accesoEmail = !empty($proveedor['correoContacto']) ? base64_encode($proveedor['correoContacto']) : '';
			$fechaActual = base64_encode(date('Y-m-d'));
			$accesoCodProveedor = !empty($proveedor['idProveedor']) ? base64_encode($proveedor['idProveedor']) : '';
			$data[$k]['link'] = "?doc={$accesoDocumento}&email={$accesoEmail}
			&date={$fechaActual}&cod={$accesoCodProveedor}&flagOcLibre={$flagOcLibre}";
			$dataParaVista = $data;
		}

		$html = $this->load->view("formularioProveedores/cotizacionesLista-table", ['datos' => $dataParaVista, 'idProveedor' => $proveedor['idProveedor']], true);


		$result['result'] = 1;
		$result['data']['views']['content-tb-cotizaciones-proveedor']['datatable'] = 'tb-cotizaciones';
		$result['data']['views']['content-tb-cotizaciones-proveedor']['html'] = $html;
		$result['data']['configTable'] = [
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

	public function formularioValidacionArte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedor'] = $post['proveedor'];
		$dataParaVista['cotizacion'] = $post['cotizacion'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Arte';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioRegistroValidacionArte", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioListadoFechasCargados()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['mostrarOpcionesExt'] = false;
		if (isset($post['mostrarOpcionesExt'])) {
			$dataParaVista['mostrarOpcionesExt'] = true;
		}

		$dataParaVista['proveedor'] = $post['proveedor'];
		$dataParaVista['cotizacion'] = $post['cotizacion'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];

		$dataParaVista['artes'] = $this->db
			->where('idProveedor', $post['proveedor'])
			->where('idOrdenCompra', $post['ordencompra'])
			->where('flagoclibre', $post['flagoclibre'])
			->where('estado', 1)
			->get('sustento.fechaEjecucion')->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Fechas Cargadas';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioListadoFechas", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioListadoArtesCargados()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['mostrarOpcionesExt'] = false;
		if (isset($post['mostrarOpcionesExt'])) {
			$dataParaVista['mostrarOpcionesExt'] = true;
		}

		$dataParaVista['proveedor'] = $post['proveedor'];
		$dataParaVista['cotizacion'] = $post['cotizacion'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];
		$dataParaVista['artes'] = $this->db->where('idProveedor', $post['proveedor'])
			->where('idOrdenCompra', $post['ordencompra'])
			->where('flagoclibre', $post['flagoclibre'])
			->where('estado', 1)->get('sustento.validacionArte')->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Artes Cargados';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioListadoArtes", $dataParaVista, true);

		echo json_encode($result);
	}

	public function editarValidacionArteEstado()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$this->db->update('sustento.validacionArte', [
			'flagAprobado' => $post['estado'],
			'flagRevisado' => '1'
		], ['idValidacionArte' => $post['id']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function formularioListadoSustentoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['mostrarOpcionesExt'] = false;
		if (isset($post['mostrarOpcionesExt'])) {
			$dataParaVista['mostrarOpcionesExt'] = true;
		}

		$dataParaVista['idOrdenCompra'] = $post['id'];
		$dataParaVista['idCotizacion'] = $post['idcot'];
		$dataParaVista['idProveedor'] = $post['idpro'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];

		if (!empty($post['id']))
			$where = ['idOrdenCompra' => $post['id'], 'flagoclibre' => $post['flagoclibre'], 'estado' => '1'];
		else
			$where = ['idCotizacion' => $post['idcot'], 'flagoclibre' => $post['flagoclibre'], 'idProveedor' => $post['idpro'], 'estado' => '1'];

		$dataParaVista['sustentosCargados'] = $this->db
			->get_where(
				'sustento.sustentoAdjunto',
				$where
			)
			->result_array();
		// $dataParaVista['cotizacion'] = $post['cotizacion'];
		// $dataParaVista['artes'] = $this->db->where('idProveedor', $post['proveedor'])->where('idCotizacion', $post['cotizacion'])->where('estado', 1)->get('compras.validacionArte')->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Sustentos Cargados';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioListadoSustentoServicio", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioListadoSustentoComprobante()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['mostrarOpcionesExt'] = false;
		if (isset($post['mostrarOpcionesExt'])) {
			$dataParaVista['mostrarOpcionesExt'] = true;
		}

		$dataParaVista['idOrdenCompra'] = $post['id'];
		$dataParaVista['idCotizacion'] = $post['idcot'];
		$dataParaVista['idProveedor'] = $post['idpro'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];
		$dataParaVista['seriado'] = $post['seriado'];

		if (!empty($post['id']))
			$where = ['idCotizacionDetalleProveedor' => $post['id']];
		else
			$where = ['idCotizacion' => $post['idcot'], 'idProveedor' => $post['idpro']];
		//$dc = $this->db->get_where('compras.cotizacionDetalleProveedor', $where)->row_array();
		$dataParaVista['sustentosCargados'] = $this->db->order_by('idFormatoDocumento, 1')
			->get_where(
				'sustento.comprobante',
				[
					'idProveedor' => $post['idpro'], 'idOrdenCompra' => $post['id'],
					'flagOcLibre' => $post['flagoclibre'], 'estado' => '1'
				]
			)
			->result_array();


		if ($post['flagoclibre'] == 0) {
			$dataParaVista['ocGenerado'] = $this->model->getDistinctOC(
				['idCotizacion' => $post['idcot'], 'idProveedor' => $post['idpro']]
			)->result_array();
		} else {
			$dataParaVista['ocGenerado'] = $this->model->getDistinctOC2(
				['idOrdenCompra' => $post['id']]
			)->result_array();
		}


		$result['result'] = 1;
		$result['msg']['title'] = 'Sustentos Cargados';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioListadoSustentoComprobante", $dataParaVista, true);

		echo json_encode($result);
	}

	public function enviarCorreoValidacionDeArtes()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data']);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		$df = $this->db->where('idProveedor', $post['proveedor'])->where('idCotizacion', $post['cotizacion'])->where('estado', '1')->get('compras.validacionArte')->result_array();
		$pro = $this->db->where('idProveedor', $post['proveedor'])->get('compras.proveedor')->row_array();
		$cot = $this->db->where('idCotizacion', $post['cotizacion'])->get('compras.cotizacion')->row_array();
		if (!empty($df)) {
			$cfg['to'] = ['eder.alata@visualimpact.com.pe'];
			$cfg['asunto'] = 'IMPACT BUSSINESS - VALIDACIÓN DE ARCHIVOS';
			$cfg['contenido'] = $this->load->view("email/arteGenerado", ['data' => $df, 'proveedor' => $pro, 'cotizacion' => $cot], true);
			$this->sendEmail($cfg);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');
		echo json_encode($result);
	}
	public function formularioEditarArte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista['idValidacionArte'] = $post['id'];
		// $dataParaVista['artes'] = $this->db->where('idProveedor', $post['proveedor'])->where('idCotizacion', $post['cotizacion'])->where('estado', 1)->get('compras.validacionArte')->result_array();
		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Arte';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioEditarArte", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioEditarSustentoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista['idCotizacionDetalleProveedorSustentoCompra'] = $post['idcotdetprov'];
		$dataParaVista['idOrdenCompra'] = $post['id'];
		$dataParaVista['idCotizacion'] = $post['idcot'];
		$dataParaVista['idProveedor'] = $post['idpro'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];

		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Sustento';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioEditarSustentoServicio", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioEditarSustentoComprobante()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista['idSustentoAdjunto'] = $post['id'];
		$sa = $this->db->get_where('sustento.comprobante', ['idSustentoAdjunto' => $post['id']])->row_array();
		// $acept = '';


		switch ($sa['idFormatoDocumento']) {
			case '1':
				$acept = 'image/*, .pdf';
				break;
			case '2':
				$acept = '.pdf';
				break;
			case '3':
				$acept = '.xml, .zip';
				break;
			case '4':
				$acept = '.xlsx, .zip';
				break;
			default:
				$acept = '';
				break;
		}
		$sa['idSustentoAdjunto'] = $post['id'];

		$dataParaVista['idFormatoDocumento'] = $sa['idFormatoDocumento'];
		$dataParaVista['numeroDocumento'] = $sa['numeroDocumento'];
		$dataParaVista['fechaEmision'] = $sa['fechaEmision'];
		$dataParaVista['acept'] = $acept;
		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Sustento';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioEditarSustentoComprobante", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioFechaEjecucion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedor'] = $post['proveedor'];
		$dataParaVista['cotizacion'] = $post['cotizacion'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Fecha de Ejecución';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioRegistroFechaEjecucion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioFechaVencimiento()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['fechaRegistro'] = $post['fechaRegistro'];
		$dataParaVista['cantidadDias'] = $post['cantidadDias'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Fecha Vencimiento';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioFechaVencimiento", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioSustento()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedor'] = $post['proveedor'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flag'] = $post['flagoclibre'];
		$dataParaVista['requiereguia'] = $post['requiereguia'];
		$dataParaVista['cotizacion'] = $post['cotizacion'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sustento';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioRegistroSustento", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioSustentoServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['idCotizacionDetalleProveedor'] = $post['id'];
		$dataParaVista['idCotizacion'] = $post['idcot'];
		$dataParaVista['idProveedor'] = $post['idpro'];
		$dataParaVista['ordencompra'] = $post['ordencompra'];
		$dataParaVista['flagoclibre'] = $post['flagoclibre'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Sustento';
		$result['data']['html'] = $this->load->view("formularioProveedores/formularioRegistroSustentoServicio", $dataParaVista, true);

		echo json_encode($result);
	}
	public function confirmarArte()
	{
		$get = [];
		$get = $this->input->get();

		foreach ($get as $k => $g) {
			$get[$k] = base64_decode($g);
		}

		$this->db->update('sustento.validacionArte', ['flagRevisado' => 1, 'flagAprobado' => $get['ne']], ['idValidacionArte' => $get['det'], 'idProveedor' => $get['pro'], 'idCotizacion' => $get['cot']]);

		echo "<script type='text/javascript'>";
		echo "window.close();";
		echo "</script>";
	}
	public function registrarValidacionArte()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['proveedor'] = json_decode($post['data'], true)['proveedor'];
		$post['cotizacion'] = json_decode($post['data'], true)['cotizacion'];
		$post['ordencompra'] = json_decode($post['data'], true)['ordencompra'];
		$post['flagoclibre'] = json_decode($post['data'], true)['flagoclibre'];

		if ($post['flagoclibre'] == 1) {
			$post['ordencompra'] = json_decode($post['data'], true)['ordencompra'];
			$post['cotizacion'] = NULL;
		}

		//Actualización de 
		$dataUpdate = array(
			'estado' => '0',
		);

		$this->db->where('idOrdenCompra', $post['ordencompra']);
		$this->db->where('flagoclibre', $post['flagoclibre']);
		$this->db->update('sustento.validacionArte', $dataUpdate);


		// $post['enlaces'] = explode('\r\n', json_decode($post['data'], true)['enlaces']);
		// $post['enlaces'] = var_export(preg_split('~\R~', json_decode($post['data'], true)['enlaces']));
		$post['enlaces'] = explode(chr(10), json_decode($post['data'], true)['enlaces']);
		$ids_insert = [];
		if (!empty($post['enlaces'])) {
			// $ar = explode('\r\n', $post['enlaces']);
			foreach ($post['enlaces'] as $k => $v) {
				if (!empty($v)) {
					$insertArchivos = [
						'nombre_inicial' => 'Enlace',
						'nombre_archivo' => $v,
						'nombre_unico' => $v,
						'estado' => true,
						'idProveedor' => $post['proveedor'],
						'idCotizacion' => $post['cotizacion'],
						'idOrdenCompra' => $post['ordencompra'],
						'flagoclibre' => $post['flagoclibre'],
						'flagAdjunto' => 0
					];
					$this->db->insert('sustento.validacionArte', $insertArchivos);
					$ids_insert[] = $this->db->insert_id();
				}
			}
		}
		if (isset($post['base64Adjunto'])) {

			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'validacionArte',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'idProveedor' => $post['proveedor'],
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flagoclibre'],
					'flagAdjunto' => 1
				];
				$this->db->insert('sustento.validacionArte', $insertArchivos);
				$ids_insert[] = $this->db->insert_id();
			}

			if (!empty($ids_insert)) {
				$df = $this->db->where_in('idValidacionArte', $ids_insert)->get('compras.validacionArte')->result_array();
				$pro = $this->db->where('idProveedor', $post['proveedor'])->get('compras.proveedor')->row_array();
				$cot = $this->db->where('idCotizacion', $post['cotizacion'])->get('compras.cotizacion')->row_array();
				if (!empty($df)) {
					$cfg['to'] = ['eder.alata@visualimpact.com.pe'];
					$cfg['asunto'] = 'IMPACT BUSSINESS - VALIDACIÓN DE ARCHIVOS';
					$cfg['contenido'] = $this->load->view("email/arteGenerado", ['data' => $df, 'proveedor' => $pro, 'cotizacion' => $cot], true);
					$this->sendEmail($cfg);
				}
			}
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}

	public function editarValidacionArte()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data']);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		if ($post['opcion'] == '1') {
			if (empty($post['enlace'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}
			$set = [
				'idTipoArchivo' => null,
				'nombre_inicial' => 'Enlace',
				'nombre_archivo' => $post['enlace'],
				'nombre_unico' => $post['enlace'],
				'extension' => null,
				'flagRevisado' => 0,
				'flagAdjunto' => 0
			];
			$where = [
				'idValidacionArte'	 => $post['idValidacionArte']
			];
			$this->db->update('compras.validacionArte', $set, $where);
		} else {
			if (!isset($post['base64Adjunto'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'validacionArte',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$set = [];
				$set = [
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'flagRevisado' => 0,
					'flagAdjunto' => 1
				];
				$where = [
					'idValidacionArte'	 => $post['idValidacionArte']
				];
				$this->db->update('compras.validacionArte', $set, $where);
			}
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function editarSustentoComprobante()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data']);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		if (empty($post['base64Adjunto'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
			goto respuesta;
		}

		foreach ($post['base64Adjunto'] as $key => $row) {
			$carpeta = null;
			$fechaEmision = null;
			if ($post['idFormatoDocumento'] == 1) {
				$carpeta = "sustentoGuia";
				$fechaEmision = null;
			} else if ($post['idFormatoDocumento'] == 2) {
				$carpeta = "sustentoFactura";
				$fechaEmision = $post['fechaEmision'];
			} else if ($post['idFormatoDocumento'] == 3) {
				$carpeta = "sustentoXml";
				$fechaEmision = null;
			} else {
				$carpeta = "sustentoAdicional";
				$fechaEmision = null;
			}

			$archivo = [
				'base64' => $row,
				'name' => $post['nameAdjunto'][$key],
				'type' => $post['typeAdjunto'][$key],
				'carpeta' => $carpeta,
				'nombreUnico' => uniqid()
			];
			$archivoName = $this->saveFileWasabi($archivo);
			$tipoArchivo = explode('/', $archivo['type']);

			$sa = $this->db->get_where('sustento.comprobante', ['idSustentoAdjunto' => $post['idSustentoAdjunto'], 'estado' => '1'])->row_array();

			$nDocumento = null;
			if (empty($post['nDocumento'])) {
				$nDocumento = null;
			} else {
				$nDocumento = $post['nDocumento'];
			}

			$insert = [
				'idFormatoDocumento' => $sa['idFormatoDocumento'],
				'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
				'extension' => FILES_WASABI[$tipoArchivo[1]],
				'nombre_inicial' => $archivo['name'],
				'nombre_archivo' => $archivoName,
				'nombre_unico' => $archivo['nombreUnico'],
				'estado' => true,
				'idOrdenCompra' => $sa['idOrdenCompra'],
				'flagoclibre' => $sa['flagoclibre'],
				'idProveedor' => $sa['idProveedor'],
				'idCotizacion' => $sa['idCotizacion'],
				'flagIncidencia' => $sa['flagIncidencia'],
				'flagRevisado' => 0,
				'flagAprobado' => 0,
				'numeroDocumento' => $nDocumento,
				'fechaEmision' => $fechaEmision
			];
			$this->db->update('sustento.comprobante', ['estado' => 0], ['idSustentoAdjunto' => $post['idSustentoAdjunto']]);
			$this->db->insert('sustento.comprobante', $insert);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function editarSustentoServicio()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data']);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		$post['proveedor'] = $post['idProveedor'];
		$post['cotizacion'] = $post['idCotizacion'];
		$post['ordencompra'] = $post['ordencompra'];
		$post['flagoclibre'] = $post['flagoclibre'];

		if ($post['flagoclibre'] == 1) {
			$post['ordencompra'] = $post['ordencompra'];
			$post['cotizacion'] = NULL;
		}

		if ($post['opcion'] == '1') {
			if (empty($post['enlace'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}
			$sa = $this->db->get_where('sustento.sustentoAdjunto', ['idCotizacionDetalleProveedorSustentoCompra' => $post['idCotizacionDetalleProveedorSustentoCompra'], 'estado' => '1'])->row_array();
			$insert = [
				// 'idFormatoDocumento' => $sa['idFormatoDocumento'],
				'idTipoArchivo' => '7',
				'extension' => null,
				'nombre_inicial' => 'Enlace',
				'nombre_archivo' => $post['enlace'],
				'nombre_unico' => $post['enlace'],
				'flagRevisado' => 0,
				'flagAprobado' => 0,
				'estado' => true,
				'fechaReg' => getActualDateTime(),
				'idProveedor' => $post['proveedor'],
				'idCotizacion' => $post['cotizacion'],
				'idOrdenCompra' => $post['ordencompra'],
				'flagoclibre' => $post['flagoclibre'],
				// 'idCotizacion' => $sa['idCotizacion'],
				// 'flagIncidencia' => $sa['flagIncidencia'],
			];
			$this->db->update('sustento.sustentoAdjunto', ['estado' => 0], ['idCotizacionDetalleProveedorSustentoCompra' => $post['idCotizacionDetalleProveedorSustentoCompra']]);
			$this->db->insert('sustento.sustentoAdjunto', $insert);
		} else {
			if (empty($post['base64Adjunto'])) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'sustentoServicio',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$sa = $this->db->get_where('sustento.sustentoAdjunto', ['idCotizacionDetalleProveedorSustentoCompra' => $post['idCotizacionDetalleProveedorSustentoCompra'], 'estado' => '1'])->row_array();
				$insert = [
					// 'idFormatoDocumento' => $sa['idFormatoDocumento'],
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'flagRevisado' => 0,
					'flagAprobado' => 0,
					'estado' => true,
					'fechaReg' => getActualDateTime(),
					'idProveedor' => $post['proveedor'],
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flagoclibre'],
					// 'idCotizacion' => $sa['idCotizacion'],
					// 'flagIncidencia' => $sa['flagIncidencia'],
				];
				$this->db->update('sustento.sustentoAdjunto', ['estado' => 0], ['idCotizacionDetalleProveedorSustentoCompra' => $post['idCotizacionDetalleProveedorSustentoCompra']]);
				$this->db->insert('sustento.sustentoAdjunto', $insert);
			}
		}
		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function editarSustentoServicioEstado()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$this->db->update(
			'sustento.sustentoAdjunto',
			['flagAprobado' => $post['estado'], 'flagRevisado' => '1'],
			['idCotizacionDetalleProveedorSustentoCompra' => $post['id']]
		);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function editarSustentoComprobanteEstado()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$this->db->update(
			'sustento.comprobante',
			['flagAprobado' => $post['estado'], 'flagRevisado' => '1'],
			['idSustentoAdjunto' => $post['id']]
		);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function guardarFechaEjecucion()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data'], true);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		if ($post['flagoclibre'] == 1) {
			$post['ordencompra'] = $post['ordencompra'];
			$post['cotizacion'] = NULL;
		}

		// Inicio: Validaciones anti-errores :v
		if ((!empty($post['fechaIni']) || !empty($post['fechaFin'])) && (empty($post['fechaIni']) || empty($post['fechaFin']))) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Al seleccionar fecha de Ejecución es necesario indicar ambas fechas.']);
			goto respuesta;
		}

		if (strtotime($post['fechaIni']) > strtotime($post['fechaFin'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Error en las fechas seleccionadas.']);
			goto respuesta;
		}

		if (empty($post['fechaIni']) && empty($post['fechaFin']) && empty($post['base64Adjunto'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Debe completar el registro indicando información.']);
			goto respuesta;
		}
		// Fin: Validaciones anti-errores

		$ids_insert = [];
		$post['base64Adjunto'] = checkAndConvertToArray($post['base64Adjunto']);
		$post['nameAdjunto'] = checkAndConvertToArray($post['nameAdjunto']);
		$post['typeAdjunto'] = checkAndConvertToArray($post['typeAdjunto']);

		//$cdp = $this->db->get_where('compras.cotizacionDetalleProveedor', ['idProveedor' => $post['proveedor'], 'idCotizacion' => $post['cotizacion'], 'estado' => 1])->row_array()['idCotizacionDetalleProveedor'];

		if (!empty($post['base64Adjunto'])) {
			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'fechaEjecucion',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flagoclibre'],
					'idProveedor' => $post['proveedor'],
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'fechaInicial' => $post['fechaIni'],
					'fechaFinal' => $post['fechaFin'],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'estado' => true,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('sustento.fechaEjecucion', $insertArchivos);
				$ids_insert[] = $this->db->insert_id();
			}
		} else {
			$insertArchivos = [
				'idCotizacion' => $post['cotizacion'],
				'idOrdenCompra' => $post['ordencompra'],
				'flagoclibre' => $post['flagoclibre'],
				'idProveedor' => $post['proveedor'],
				'idTipoArchivo' => null,
				'nombre_inicial' => null,
				'nombre_archivo' => null,
				'nombre_unico' => null,
				'fechaInicial' => $post['fechaIni'],
				'fechaFinal' => $post['fechaFin'],
				'extension' => null,
				'estado' => true,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('sustento.fechaEjecucion', $insertArchivos);
			$ids_insert[] = $this->db->insert_id();
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		////////////////////////////////////
		if (!empty($ids_insert)) {
			$daC = $this->db->where_in('idCotizacionDetalleProveedorFechaEjecucion', $ids_insert)->get('sustento.fechaEjecucion')->result_array();
		} else {
			$daC = [];
		}

		if (!empty($daC)) {
			$cfg['to'] = ['eder.alata@visualimpact.com.pe'];
			$cfg['asunto'] = 'IMPACT BUSSINESS - Fecha de Ejecución';
			$cfg['contenido'] = $this->load->view("email/fechaEjecucion", ['data' => $daC, 'fechaInicial' => $post['fechaIni'], 'fechaFinal' => $post['fechaFin']], true);
			$this->sendEmail($cfg);
		}
		/////////////////////////////////////
		respuesta:
		echo json_encode($result);
	}
	public function guardarSustentoServicio()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		foreach (json_decode($post['data'], true) as $k => $v) {
			$post[$k] = $v;
		}
		if ($post['flagoclibre'] == 1) {
			$post['ordencompra'] = $post['ordencompra'];
			$post['cotizacion'] = NULL;
		}

		// $post['proveedor'] = json_decode($post['data'], true)['proveedor'];
		$post['enlaces'] = explode(chr(10), json_decode($post['data'], true)['enlaces']);
		$ids_insert = [];

		if (!empty($post['enlaces'])) {
			foreach ($post['enlaces'] as $k => $v) {
				if (!empty($v)) {
					$insertArchivos = [
						'idCotizacion' => $post['idCotizacion'],
						'idOrdenCompra' => $post['ordencompra'],
						'flagoclibre' => $post['flagoclibre'],
						'idProveedor' => $post['idProveedor'],
						'idTipoArchivo' => TIPO_ENLACE,
						'extension' => '',
						'nombre_inicial' => 'Enlace',
						'nombre_archivo' => $v,
						'nombre_unico' => $v,
						'flagRevisado' => false,
						'flagAprobado' => false,
						'estado' => true,
						'fechaReg' => getActualDateTime(),
					];
					$this->db->insert('sustento.sustentoAdjunto', $insertArchivos);
					$ids_insert[] = $this->db->insert_id();
				}
			}
		}

		if (!empty($post['base64Adjunto'])) {

			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'sustentoServicio',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idCotizacion' => $post['idCotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flagoclibre'],
					'idProveedor' => $post['idProveedor'],
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'flagRevisado' => false,
					'flagAprobado' => false,
					'estado' => true,
					'fechaReg' => getActualDateTime(),
				];
				$this->db->insert('sustento.sustentoAdjunto', $insertArchivos);
				$ids_insert[] = $this->db->insert_id();
			}
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function guardarSustento()
	{
		$this->db->trans_start();

		$result = $this->result;

		$fechaHoy = date_change_format_bd(getFechaActual());
		$hora = strtotime(time_change_format(getActualDateTime()));

		$horaLimiteMin = strtotime('09:00:00');
		$horaLimiteMax = strtotime('13:00:00');

		$r = $this->db->where('fecha', $fechaHoy)->get('General.dbo.tiempo')->row_array();

		// if ($r['idDia'] != 1 && $r['idDia'] != 2 && $r['idDia'] != 4) {
		// 	$result['result'] = 0;
		// 	$result['msg']['title'] = 'Alerta!';
		// 	$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Subir sustentos los días Martes y Jueves de 9:00 AM hasta las 12:00']);
		// 	goto respuesta;
		// }

		// if ($hora > $horaLimiteMax || $hora < $horaLimiteMin) {
		// 	$result['result'] = 0;
		// 	$result['msg']['title'] = 'Alerta!';
		// 	$result['msg']['content'] = createMessage(['type' => 2, 'message' => 'Subir sustentos los días Martes y Jueves de 9:00 AM hasta las 12:00']);
		// 	goto respuesta;
		// }

		$post = json_decode($this->input->post('data'), true);
		$post['data'] = json_decode($post['data'], true);
		foreach ($post['data'] as $k => $v) {
			$post[$k] = $v;
		}

		if ($post['flag'] == 1) {
			$post['ordencompra'] = $post['ordencompra'];
			$post['cotizacion'] = NULL;
		}

		$this->db->update('sustento.comprobante', ['estado' => 0], ['idProveedor' => $post['proveedor'], 'idOrdenCompra' => $post['ordencompra'], 'flagoclibre' => $post['flag']]);
		if (isset($post['base64Adjunto_g'])) {
			foreach ($post['base64Adjunto_g'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto_g'][$key],
					'type' => $post['typeAdjunto_g'][$key],
					'carpeta' => 'sustentoGuia',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idFormatoDocumento' => 1,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flag'],
					'idProveedor' => $post['proveedor'],
					'flagIncidencia' => $post['incidencia'],
					'flagRevisado' => 0,
					'flagAprobado' => 0,
					'numeroDocumento' => $post['nguia']
				];
				$this->db->insert('sustento.comprobante', $insertArchivos);
			}
		}

		if (isset($post['base64Adjunto_f'])) {
			foreach ($post['base64Adjunto_f'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto_f'][$key],
					'type' => $post['typeAdjunto_f'][$key],
					'carpeta' => 'sustentoFactura',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idFormatoDocumento' => 2,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flag'],
					'idProveedor' => $post['proveedor'],
					'flagoclibre' => $post['flag'],
					'idProveedor' => $post['proveedor'],
					'flagIncidencia' => $post['incidencia'],
					'flagRevisado' => 0,
					'flagAprobado' => 0,
					'numeroDocumento' => $post['nfactura'],
					'fechaEmision' => $post['fechaEmision']
				];
				$this->db->insert('sustento.comprobante', $insertArchivos);
			}
		}

		if (isset($post['base64Adjunto_x'])) {
			foreach ($post['base64Adjunto_x'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto_x'][$key],
					'type' => $post['typeAdjunto_x'][$key],
					'carpeta' => 'sustentoXml',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idFormatoDocumento' => 3,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flag'],
					'idProveedor' => $post['proveedor'],
					'flagIncidencia' => $post['incidencia'],
					'flagRevisado' => 0,
					'flagAprobado' => 0,
				];
				$this->db->insert('sustento.comprobante', $insertArchivos);
			}
		}

		if (isset($post['base64Adjunto_da'])) {
			foreach ($post['base64Adjunto_da'] as $key => $row) {
				$archivo = [
					'base64' => $row,
					'name' => $post['nameAdjunto_da'][$key],
					'type' => $post['typeAdjunto_da'][$key],
					'carpeta' => 'sustentoAdicional',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idFormatoDocumento' => 4,
					'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
					'extension' => FILES_WASABI[$tipoArchivo[1]],
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'estado' => true,
					'idCotizacion' => $post['cotizacion'],
					'idOrdenCompra' => $post['ordencompra'],
					'flagoclibre' => $post['flag'],
					'idProveedor' => $post['proveedor'],
					'flagIncidencia' => $post['incidencia'],
					'flagRevisado' => 0,
					'flagAprobado' => 0,
				];
				$this->db->insert('sustento.comprobante', $insertArchivos);
			}
		}

		$daC = $this->db->where('estado', 1)->where('idCotizacion', $post['cotizacion'])->where('idProveedor', $post['proveedor'])->get('compras.sustentoAdjunto')->result_array();
		$daD = $this->db->distinct()->select('idFormatoDocumento')->where('estado', 1)->where('idCotizacion', $post['cotizacion'])->where('idProveedor', $post['proveedor'])->get('compras.sustentoAdjunto')->result_array();
		$pro = $this->db->where('idProveedor', $post['proveedor'])->get('compras.proveedor')->row_array();
		$cot = $this->db->where('idCotizacion', $post['cotizacion'])->get('compras.cotizacion')->row_array();
		$ocG = $this->model->getDistinctOC(['idCotizacion' => $post['cotizacion'], 'idProveedor' => $post['proveedor']])->result_array();
		// foreach ($ocG as $k => $v) {
		// 	$ocG[$k]['url'] =
		// }
		if (!empty($daC)) {
			$cfg['to'] = ['eder.alata@visualimpact.com.pe'];
			$cfg['asunto'] = 'IMPACT BUSSINESS - Sustentos Cargados';
			$cfg['contenido'] = $this->load->view("email/sustentos", ['data' => $daC, 'proveedor' => $pro, 'cotizacion' => $cot, 'formatos' => $daD, 'ocG' => $ocG], true);
			$this->sendEmail($cfg);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
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
		$result['data']['configTable'] = [
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
					} else {
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

	public function viewOrdenCompra($idOrdenCompra = '0', $flagOcLibre = '0')
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
		$config['data']['flagOcLibre'] = $flagOcLibre;
		$config['data']['title'] = 'Formulario Proveedores';
		$config['data']['icon'] = 'fa fa-home';
		if ($flagOcLibre == 0) {
			$ordenCompraProveedor = $this->model->obtenerOrdenCompraDetalleProveedor(['idProveedor' => $proveedor['idProveedor'], 'idOrdenCompra' => $idOrdenCompra, 'estado' => 1])['query']->result_array();
			$dataCabecera = $this->m_cotizacion->obtenerInformacionOrdenCompra(['id' => $idOrdenCompra])['query']->row_array();
		} else {
			$ordenCompraProveedor = $this->model->obtenerOrdenCompraLibre(['idOrdenCompra' => $idOrdenCompra])->result_array();
			$dataCabecera = $this->model->obtenerOrdenCompraLibre(['idOrdenCompra' => $idOrdenCompra])->row_array();
		}

		$config['data']['cabecera'] = $dataCabecera;
		$config['data']['detalle'] = $ordenCompraProveedor;
		$config['data']['flagOcLibre'] = $flagOcLibre;

		foreach ($config['data']['detalle'] as $k => $v) {
			if ($flagOcLibre == 0)
				$config['data']['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			elseif ($flagOcLibre == 1)
				$config['data']['subDetalleItem'][$v['idItem']] = $this->db->select('*, idGenero as genero')->get_where('orden.ordenCompraDetalleSub', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle']])->result_array();
		}

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

		if (isset($post['flag'])) {
			if ($post['flag'] == 1) {
				$ordenCompra = $this->model->obtenerOrdenCompraDetalleProveedorOC(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();
			} else {
				$ordenCompra = $this->model->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();
			}
		} else {
			$ordenCompra = $this->model->obtenerOrdenCompraDetalleProveedor(['idOrdenCompra' => $post['id'], 'estado' => 1])['query']->result_array();
		}

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
			if ($post['flag'] == 0)
				$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->where('idCotizacionDetalle', $v['idCotizacionDetalle'])->get('compras.cotizacionDetalleSub')->result_array();
			elseif ($post['flag'] == 1)
				$dataParaVista['subDetalleItem'][$v['idItem']] = $this->db->select('*, idGenero as genero')->get_where('orden.ordenCompraDetalleSub', ['idOrdenCompraDetalle' => $v['idOrdenCompraDetalle']])->result_array();
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

		$cod_oc = $dataParaVista['data']['seriado'];
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("{$cod_oc}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
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
