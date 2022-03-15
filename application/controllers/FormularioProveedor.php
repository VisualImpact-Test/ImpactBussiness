<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FormularioProveedor extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_FormularioProveedor', 'model');
	}

	public function index()
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
			'idProveedorEstado' => 1,
			'nombreContacto' => $post['nombreContacto'],
			'correoContacto' => $post['correoContacto'],
			'numeroContacto' => $post['numeroContacto']
		];

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

		$estadoEmail = $this->enviarCorreo($insert['id']);

		if (!$insert['estado'] || !$second_insert['estado'] || !$estadoEmail) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

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
		$this->email->to('aaron.ccenta@visualimpact.com.pe');

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
}
