<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfil extends MY_Controller
{
	var $return = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_perfil', 'model');
		$this->return = array('result' => 0, 'url' => '', 'data' => '', 'msg' => array('title' => '', 'content' => ''));
	}

	public function index()
	{
		$config['view'] = 'perfil/index';
		$config['js']['script'] = array('perfil');
		$config['data']['title'] = 'Mi Perfil';
		$config['data']['icon'] = 'fa fa-user';
		$config['data']['message'] = 'Aquí encontrará los datos de su perfil.';

        $id_usuario = $this->session->userdata('idUsuario');

        $rs = $this->model->getDatosDeUsuario($id_usuario);
        foreach($rs->result() as $row){
            $config['data']['foto'] = $row->archFoto;
            $config['data']['tipoDoc'] = $row->breve;
            $config['data']['documento'] = $row->numDocumento;
            $config['data']['nombres'] = $row->nombres;
            $config['data']['apellidos'] = $row->apePaterno." ".$row->apeMaterno;
            $config['data']['telefono'] = $row->telefono;
            $config['data']['celular'] = $row->celular;
            $config['data']['email'] = $row->email_corp;

        }
		$this->view($config);
	}

	public function getMisDatos()
	{
		$result = $this->result;


		$idUsuario = $this->idUsuario;
		$datosDeUsuario = $this->model->getDatosDeUsuario($idUsuario)->row_array();

		$dataParaVista['datosDeUsuario'] = $datosDeUsuario;
		$result['data']['html'] = $this->load->view("perfil/seccionMisDatos", $dataParaVista, true);
		$result['result'] = 1;

		echo json_encode($result);
	}

	public function getCambiarClave()
	{
		$result = $this->result;

		$dataParaVista = array();
		$result['data']['html'] = $this->load->view("perfil/seccionCambiarClave", $dataParaVista, true);
		$result['result'] = 1;

		echo json_encode($result);
	}
}
