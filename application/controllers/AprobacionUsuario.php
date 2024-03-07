<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AprobacionUsuario extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_AprobacionUsuario', 'model');
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
			'assets/libs/select2/4.0.13/js/select2',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/AprobacionUsuario',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['title'] = 'Usuarios Aprobados';
		$config['data']['icon'] = 'fa fa-home';

		$config['view'] = 'formularioRequerimientosInternos/aprobacionUsuario/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$dataParaVista = [];
		$dataParaVista['usuariosAprobados'] = $this->model->obtenerUsuarioAprobados()['query']->result_array();

		$html = getMensajeGestion('noResultados');
		if (!empty($dataParaVista['usuariosAprobados'])) {
			$html = $this->load->view("formularioRequerimientosInternos/aprobacionUsuario/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentAprobacionUsuario']['datatable'] = 'tb-requerimientos-aprobacionUsuario';
		$result['data']['views']['idContentAprobacionUsuario']['html'] = $html;
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
	public function formularioRegistroUsuarioAprobar()
	{
		$dataParaVista = [];
		
		$usuario = $this->model->obtenerInformacionUsuario()['query']->result_array();
		if(!empty($usuario)) {
			$dataParaVista['usuario'] = $usuario;
		} else {
			$dataParaVista['usuario'] = $this->db->select("idUsuario AS id, (nombres + ' ' + apeMaterno + ' ' + apePaterno) AS value")->get_where('sistema.usuario')->result_array();
		}

		$config['data']['title'] = 'Registrar Nuevo Usuario';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/aprobacionUsuario/formularioRegistro", $dataParaVista, true);

		echo json_encode($config);
	}
	public function obtenerTipoUsuario()
	{
		$data = json_decode($this->input->post('data'));
		$grupo['usuarioTipo'] = $this->db->select('ut.nombre')->from('sistema.usuario u')->join('sistema.usuarioHistorico uh', 'uh.idUsuario = u.idUsuario', 'INNER')->join('sistema.usuarioTipo ut', 'ut.idTipoUsuario = uh.idTipoUsuario', 'INNER')->where('u.idUsuario', $data)->get()->row_array();
	
		echo json_encode($grupo);
	}
	public function registrarUsuarioAprobar()
	{
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$data['tabla'] = 'compras.requerimientoInternoUsuarioAprobacion';

		$data['insert'] = [
			'idUsuario' => $post['usuario'],
		];

		$insert = $this->model->insertarUsuarioAprobar($data);
		if (!$insert['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		}

		respuesta:
		echo json_encode($result);
	}
}
