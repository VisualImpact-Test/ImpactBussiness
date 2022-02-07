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
		$usuario['idCanal'] = $this->session->userdata('idCanal');
		$usuario['idGrupoCanal'] = $this->session->userdata('idGrupoCanal');
		$usuario['idBanner'] = $this->session->userdata('idBanner');
		$usuario['idCadena'] = $this->session->userdata('idCadena');

		$usuario['idCuenta'] = null;
		$usuario['idProyecto'] = null;
		$usuario['idGrupo'] = null;

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

		$config['data']['idCuenta'] = $post['idCuenta'] = $this->sessIdCuenta;
		$config['data']['idProyecto'] = $post['idProyecto'] = $this->sessIdProyecto;
		$post['fecha'] = date('Y-m-d');

		$config['data']['cantidadGtm'] = $this->model->get_cantidadGtm($post)['cantidadGtm'];

		$this->view($config);
	}

	public function get_fotos()
	{
		$result = $this->result;
		$array = array();

		$fotos = $this->model->get_latest_fotos()->result_array();
		$array['fotos'] = $fotos;

		if (count($fotos)) {
			$result['data']['html'] = $this->load->view("home/last_fotos", $array, true);
		} else {
			$result['data']['html'] = "<i class='fa fa-info-circle'></i> No hay fotos registradas el día de hoy.";
			// $result['data']['html'] = date('m');
		}

		echo json_encode($result);
	}

	public function get_asistencia()
	{
		$this->aSessTrack[] = ['idAccion' => 5, 'tabla' => "{$this->sessBDCuenta}.trade.data_ruta"];

		$post = json_decode($this->input->post('data'), true);
		$result = $this->result;
		$array = [];

		$post['idCuenta'] = $this->sessIdCuenta;
		$post['idProyecto'] = $this->sessIdProyecto;

		$data_asistencia = $this->model->obtener_asistencias($post);
		$data_asistencia_usuarios = $this->model->get_asistencia($post);

		foreach ($data_asistencia as $ka => $row) {
			$array['asistencias'][$row['fecha_id']]['usuarios'][$row['idUsuario']]['asistencias'][$row['idTipoAsistencia']]['tipoAsistencia'] = $row['tipoAsistencia'];
		}

		foreach ($data_asistencia_usuarios as $kr => $row) {
			$array['listaUsuarios'][$row['fecha']]['fecha'] = $row['fecha'];
			$array['listaUsuarios'][$row['fecha']]['fecha_id'] = $row['fecha_id'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['idEmpleado'] = $row['idEmpleado'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['idUsuario'] = $row['idUsuario'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['tipoUsuario'] = $row['tipoUsuario'];

			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['feriado'] = $row['feriado'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['vacaciones'] = $row['vacaciones'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['ocurrencia'] = $row['ocurrencia'];

			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['horarioIng'] = $row['horarioIng'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['horarioSal'] = $row['horarioSal'];

			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['horaIniVisita'] = $row['horaIniVisita'];
			$array['listaUsuarios'][$row['fecha']]['usuarios'][$row['idEmpleado']]['horaFinVisita'] = $row['horaFinVisita'];
		}

		$completos = 0;
		$incompletos = 0;
		$faltas = 0;
		$ocurrencias = 0;
		$vacaciones = 0;
		$feriados = 0;
		$noLaborables = 0;

		$usuariosFaltas = [];

		if (!empty($array['listaUsuarios'])) {
			foreach ($array['listaUsuarios'] as $klu => $fechas) {
				foreach ($fechas['usuarios'] as $kfu => $usuario) {
					$status = 0;

					if (!empty($usuario['feriado'])) {
						$feriados++;
						$status++;
					} else {
						if (!empty($usuario['vacaciones'])) {
							$vacaciones++;
							$status++;
						} else {
							if (!empty($usuario['ocurrencia'])) {
								$ocurrencias++;
								$status++;
							}
						}
					}

					$status_ing = isset($array['asistencias'][$fechas['fecha_id']]['usuarios'][$usuario['idUsuario']]['asistencias'][1]) ? 1 : 0;
					$status_sal = isset($array['asistencias'][$fechas['fecha_id']]['usuarios'][$usuario['idUsuario']]['asistencias'][3]) ? 1 : 0;

					if (empty($status)) {
						if (!empty($status_ing) && !empty($status_sal)) {
							$completos++;
							$status++;
						} elseif (!empty($status_ing) && empty($status_sal)) {
							$completos++;
							$status++;
						} else {
							if (!empty($usuario['horarioIng']) && !empty($usuario['horarioSal'])) {
								$usuariosFaltas[] = $usuario;

								$faltas++;
								$status++;
							} else {
								$noLaborables++;
								$status++;
							};
						}
					}

					if (empty($status)) {
						$faltas++;
						$status++;
					}
				}
			}
		}

		$porcentajeCompletos = 0;
		$porcentajeFaltas = 0;

		$porcentajeTotal = $completos + $faltas;
		if (!empty($porcentajeTotal)) {
			$porcentajeCompletos = number_format(($completos / $porcentajeTotal) * 100, 2);
			$porcentajeFaltas = number_format(($faltas / $porcentajeTotal) * 100, 2);
		}

		$html = '
		<div class="text-center"><h1 class="d-inline mt-0" style="color: #28A745;font-size: 3rem;">' . $porcentajeCompletos . ' % Correcto</h1></div>
		<hr>
		<div class="text-center" style="margin-bottom: 15px;"><h1 class="d-inline mt-0 verFaltas" style="color: #DC3545;font-size: 3rem;cursor: pointer;">' . $porcentajeFaltas . ' % Faltas</h1></div>
		<table class="table table-hover" style="font-size:19px;">
			<tr>
				<td class="text-center"><i class="fas fa-hand-holding-medical fa-lg fa-2x" style="color:#6063d7;"></i></td>
				<td>Ocurrencia</td>
				<td class="text-center"><strong>' . $ocurrencias . '</strong></td>
			</tr>
			<tr>
				<td class="text-center"><i class="fas fa-plane-departure fa-lg fa-2x" style="color:#95cbe7;"></i></td>
				<td>Vacaciones</td>
				<td class="text-center"><strong>' . $vacaciones . '</strong></td>
			</tr>
			<tr>
				<td class="text-center"><i class="far fa-handshake fa-lg fa-2x" style="color:#222222;"></i></td>
				<td>No laborable</td>
				<td class="text-center"><strong>' . $noLaborables . '</strong></td>
			</tr>
		</table>
		';

		$asistenciaArreglado = [$porcentajeCompletos, $porcentajeFaltas];

		$result['data']['usuariosFalta'] = $usuariosFaltas;
		$result['data']['asistencia'] = $asistenciaArreglado;
		$result['data']['html'] = $html;

		echo json_encode($result);
	}

	public function get_cantidadGtm()
	{
		$post = json_decode($this->input->post('data'), true);
		$result = $this->result;

		$post['idCuenta'] = $this->sessIdCuenta;
		$post['idProyecto'] = $this->sessIdProyecto;

		$data_cantidadGtm = $this->model->get_cantidadGtm($post);

		$result['data']['cantidadGtm'] = [$data_cantidadGtm['cantidadGtm']];

		echo json_encode($result);
	}
}
