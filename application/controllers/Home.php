<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Home', 'model');
	}

	public function index()
	{
		$estado = '';
		if (!empty($query)) $estado = $query[0]['estado'];

		$usuario = array();
		$usuario['idUsuario'] = $this->session->userdata('idUsuario');
		$usuario['usuario'] = $this->session->userdata('apeNom');
		$usuario['idTipoUsuario'] = $this->session->userdata('idTipoUsuario');
		$usuario['tipoUsuario'] = $this->session->userdata('tipoUsuario');

		$usuario['estado'] = $estado;
		$usuario['device'] = 'web';
		$config['data']['usuario'] = $usuario;

		$config['css']['style'] = [
			'assets/libs/datatables/dataTables.bootstrap4.min',
			'assets/libs/datatables/buttons.bootstrap4.min',
			'assets/libs/MagnificPopup/magnific-popup',
			'assets/custom/css/rutas'
		];

		$config['js']['script'] = [
			'assets/libs/FancyZoom/FancyZoom',
			'assets/libs/FancyZoom/FancyZoomHTML',
			'assets/libs/datatables/datatables',
			'assets/libs/datatables/responsive.bootstrap4.min',
			'assets/custom/js/core/datatables-defaults',
			'assets/libs/MagnificPopup/jquery.magnific-popup.min',
			'assets/custom/js/home'
		];

		$config['view'] = 'home';
		$config['nav']['menu_active'] = 'home';

		$config['data']['icon'] = 'fa fa-home';
		$config['data']['title'] = 'Inicio';
		$config['data']['message'] = 'Bienvenido al sistema, ' . $this->session->userdata('nombres') . ' ' . $this->session->userdata('ape_paterno');

		$post['fecha'] = date('Y-m-d');

		$this->view($config);
	}
}
