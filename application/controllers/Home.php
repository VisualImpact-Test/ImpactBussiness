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

		$estado = '';//
		if (!empty($query)) $estado = $query[0]['estado'];

		$usuario = array();
        $key = Encriptar::codificar($this->session->userdata('idTipoUsuario'));
        $config['data']['key'] = $key;
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
			'assets/custom/css/rutas',
			'assets/custom/css/home'
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
		$config['data']['title'] = 'Home';
		//procesar informacion
		$query_1 = $this->model->query_estados_cotizacion_proceso()->result_array();
		$query_2 = $this->model->query_cotizaciones_proceso()->result_array();
		$query_3 = $this->model->query_cotizaciones()->result_array();
		$array = array();
		$total_estados = 0;
		foreach ($query_1 as $row) {
			$ix = $row['idCotizacionEstado'];
			$array[$ix]['id'] = $ix;
			$array[$ix]['nombre'] = $row['nombre'];
		}
		//
		foreach ($query_2 as $row) {
			$ix = $row['idCotizacionEstado'];
			$array[$ix]['cantidad'] = $row['cantidad'];
			$total_estados =  $total_estados + $row['cantidad'];
		}
		$config['data']['arr_estados'] = $array;
		$config['data']['arr_total_estados'] = $total_estados;
		//
		$total_coti_pasado = 0;
		$total_coti_actual = 0;
		$total_coti_actual_efec = 0;
		$total_monto_coti_efectiva = 0;
		foreach ($query_3 as $row) {
			if ($row['pasado'] == 1) {
				$total_coti_pasado = $total_coti_pasado + 1;
			} else {
				$total_coti_actual = $total_coti_actual + 1;
				if ($row['idEstado'] >= 5) {
					$total_coti_actual_efec = $total_coti_actual_efec + 1;
					$total_monto_coti_efectiva = $total_monto_coti_efectiva + $row['total'];
				}
			}
		}
		$config['data']['arr_cotizaciones'] = $query_3;
		$config['data']['arr_cotizaciones_total_pasado'] = $total_coti_pasado;
		$config['data']['arr_cotizaciones_total_actual'] = $total_coti_actual;
		$config['data']['arr_cotizaciones_total_actual_efec'] = $total_coti_actual_efec;
		$config['data']['arr_cotizaciones_total_monto_actual_efec'] = $total_monto_coti_efectiva;
		//
		setlocale(LC_TIME, "es_ES");
		$config['data']['message'] = ''; //'Bienvenido al sistema, ' . $this->session->userdata('nombres') . ' ' . $this->session->userdata('ape_paterno');

		$post['fecha'] = date('Y-m-d');

		$this->view($config);
	}

	public function get_cotizacion()
	{
		$input = json_decode($this->input->post('data'), true);
		$result = [];
		$data = [];
		$result['result'] = 0;
		$result['url'] = '';
		$result['msg']['title'] = 'Alerta';
		$result['msg']['content'] = '';
		//
		$query_1 = $this->model->query_cotizacion($input)->row_array();
		$query_2 = $this->model->query_cotizacion_detalle($input)->result_array();
		$query_3 = $this->model->query_estados_cotizacion()->result_array();
		//modificar
		if (!empty($query_1)) {
			$result['result'] = 1;
			$data['cotizacion'] = $query_1;
			$data['detalle'] = $query_2;
			$data['estados'] = $query_3;
			//
			foreach ($query_2 as $row) {
				$data['areas'][$row['idItemTipo']]['id'] = $row['idItemTipo'];
				$data['areas'][$row['idItemTipo']]['nombre'] = $row['tipo'];
				$data['areas'][$row['idItemTipo']]['completado'] = rand(0, 1);
				$data['areas'][$row['idItemTipo']]['fecha'] =  $row['fecha'];
				$data['areas'][$row['idItemTipo']]['usuario'] = 'Usuario, Demo TI';
			}
			$result['data']['html'] = $this->load->view("home/cotizacion", $data, true);
		} else {
			$result['msg']['title'] = 'Home';
			$result['msg']['content'] = '<p class="p-info"><i class="fa fa-info-circle"></i> No se ha generado ningun resultado.</p>';
		}
		echo json_encode($result);
	}
}
