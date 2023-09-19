<?php

defined('BASEPATH') or exit('No direct script access allowed');

class OrdenServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_OrdenServicio', 'model');
		$this->load->model('M_Cotizacion', 'mCotizacion');
		$this->load->model('M_control', 'model_control');
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
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/ordenServicio'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Orden de Servicio';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/OrdenServicio/index';

		$this->view($config);
	}

	public function adjuntarArchivo($id)
	{
		$config['single'] = true;
		// AGREGAR VALIDACION PARA SOLO MOSTRAR LOS PENDIENTES.
		$config['js']['script'] = array('assets/custom/js/adjuntarDocumento');
		$config['data']['documento'] = $this->model->getDocumento($id)->row_array();
		$config['view'] = 'adjuntarDocumento';

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
		$data = $this->model->obtenerInformacionOrdenServicio()->result_array();

		foreach ($data as $value) {
			$dataParaVista['ordenServicio'][$value['idOrdenServicio']] = $value;
			$cargo = $this->model->getOrdenServicioCargo($value['idOrdenServicio'])->result_array();
			$documento = $this->db->where('idOrdenServicio', $value['idOrdenServicio'])->where('estado', 1)->get('compras.ordenServicioDocumento')->result_array();

			if (!empty($cargo)) {
				$temp = [];
				foreach ($cargo as $cargoData) {
					$temp[] = $cargoData['cargo'];
				}
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['cargo'] = implode(', ', $temp);
			} else {
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['cargo'] = '';
			}

			if (!empty($documento)) {
				$temp = [];
				foreach ($documento as $documentoData) {
					$temp[] = $documentoData['documento'];
				}
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['documento'] = implode(', ', $temp);
			} else {
				$dataParaVista['ordenServicio'][$value['idOrdenServicio']]['documento'] = '';
			}
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/OrdenServicio/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentOrdenServicio']['datatable'] = 'tb-ordenServicio';
		$result['data']['views']['idContentOrdenServicio']['html'] = $html;
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

	public function formularioRegistroOrdenServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		// $dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		$tipoPresupuestoDetalle = [];
		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}

		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();

		$dataParaVista['cargo'] = $this->mCotizacion->getAll_Cargos()->result_array();

		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['ordenServicioCargo'] = [];
		$provincia = [];
		foreach ($this->model->obtenerProvincia()->result_array() as $k => $v) {
			$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		}
		$result['data']['provincia'] = $provincia;

		$distrito = [];
		foreach ($this->model->obtenerDistrito()->result_array() as $k => $v) {
			$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		}
		$result['data']['distrito'] = $distrito;

		$dataParaVista['ordenServicio'] = [];

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar OrdenServicio';
		$result['data']['cargo'] = $dataParaVista['cargo'];
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroOrdenServicio", $dataParaVista, true);

		echo json_encode($result);
	}

	public function addDocumento()
	{
		$post = $this->input->post();
		$dataParaVista['documentosCargados'] = $post['documentoGenerado'];

		$dataParaVista['documento'] = $this->db->where('extension is not null')->get('compras.documento')->result_array();
		$dataParaVista['num'] = $post['id'];
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();
		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();

		echo $this->load->view('modulos/OrdenServicio/addDocumento', $dataParaVista, true);
	}

	public function registrarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		// $post = $this->input->post('data');
		$idCliente = null;
		$idCuenta = null;
		$idCentroCosto = null;
		if ($post['chkUtilizarCliente']) {
			if (!is_numeric($post['clienteForm'])) {
				$insertCliente = [
					'nombre' => $post['clienteForm'],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.cliente', $insertCliente);
				$idCliente = $this->db->insert_id();
			} else {
				$idCliente = $post['clienteForm'];
			}
		} else {
			$idCuenta = $post['cuentaForm'];
			$idCentroCosto = $post['centroCostoForm'];
		}

		$insertOrdenServicio = [
			'idCliente' => $idCliente,
			'idCuenta' => $idCuenta,
			'idCentroCosto' => $idCentroCosto,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'nombre' => $post['nombre'],
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
			'chkUtilizarCliente' => $post['chkUtilizarCliente'],
			'chkPresupuesto' => false,
		];

		$this->db->insert('compras.ordenServicio', $insertOrdenServicio);
		$idOrdenServicio = $this->db->insert_id();

		$insertOrdenServicioHistorico = $insertOrdenServicio;
		$insertOrdenServicioHistorico['idOrdenServicio'] = $idOrdenServicio;
		$insertOrdenServicioHistorico['idUsuario'] = $this->idUsuario;
		$insertOrdenServicioHistorico['fechaReg'] = getActualDateTime();
		unset($insertOrdenServicioHistorico['chkAprobado']);
		unset($insertOrdenServicioHistorico['chkPresupuesto']);

		$this->db->insert('compras.ordenServicioHistorico', $insertOrdenServicioHistorico);

		if (!isset($post['cargo'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Registro Erroneo!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe indicar al menos un cargo']);
			goto respuesta;
		}

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['idDocumento'] = isset($post['idDocumento']) ? checkAndConvertToArray($post['idDocumento']) : [];
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];
		$post['cantidadCargo'] = isset($post['cantidadCargo']) ? checkAndConvertToArray($post['cantidadCargo']) : [];
		$post['sueldoCargo'] = isset($post['sueldoCargo']) ? checkAndConvertToArray($post['sueldoCargo']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'sueldo' => $post['sueldoCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->insert_batch('compras.ordenServicioCargo', $insertCargo);

		$insertOrdenServicioDetalle = [];
		$insertOrdenServicioDetalleSub = [];

		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertOrdenServicioDetalle = [
					'idOrdenServicio' => $idOrdenServicio,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.ordenServicioDetalle', $insertOrdenServicioDetalle);
				$idOrdenServicioDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertOrdenServicioDetalleSub[] = [
							'idOrdenServicioDetalle' => $idOrdenServicioDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'valorPorcentual' => ($value == COD_ASIGNACIONFAMILIAR) ? $post['asignacionFamiliar'] : NULL,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}

		$this->db->insert_batch('compras.ordenServicioDetalleSub', $insertOrdenServicioDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			if ($post['idDocumento'][$k] == '0') {
				$documento = [
					'nombre' => $v,
					'idArea' => $post['area'][$k],
					'idPersonal' => verificarEmpty($post['persona'][$k], 4),
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.documento', $documento);
				$idDocumento = $this->db->insert_id();
				// CORREO
				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : MAIL_COORDINADORA_COMPRAS);
				$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toOperaciones = [];
				foreach ($usuariosOperaciones as $usuario) {
					$toOperaciones[] = $usuario['email'];
				}
				$estadoEmail = $this->enviarCorreo(['data' => ['idDocumento' => $idDocumento], 'to' => $toOperaciones, 'cc' => ['luis.durand@visualimpact.com.pe']]);
				//
			} else {
				$idDocumento = $post['idDocumento'][$k];
			}

			$insertDocumento[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idDocumento' => $idDocumento,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersonal' => $post['persona'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->insert_batch('compras.ordenServicioDocumento', $insertDocumento);
		}

		$insertFechas = [];

		for ($i = 0; $i < intval($post['cantidadMeses']); $i++) {
			if (empty($post['fechaIni'])) {
				$fechaDescripcion = 'Mes ' . ($i + 1);
			} else {
				$fechaDescripcion = date('Y-m-d', strtotime("+$i months", strtotime($post['fechaIni'])));
			}

			$insertFechas[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
		}

		$this->db->insert_batch('compras.ordenServicioFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioActualizacionOrdenServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idOrdenServicio = $post['idOrdenServicio'];
		$dataParaVista = [];
		// $dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['cargo'] = $this->mCotizacion->getAll_Cargos()->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();

		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['centroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();

		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();;
		$dataParaVista['idOrdenServicio'] = $idOrdenServicio;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['ordenServicio'] = $this->model->getOrdenServicio($idOrdenServicio);
		$dataParaVista['ordenServicioFecha'] = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->order_by('idOrdenServicioFecha')->get('compras.ordenServicioFecha')->result_array();
		// $dataParaVista['ordenServicioDocumento'] = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->order_by('idOrdenServicioDocumento')->get('compras.ordenServicioDocumento')->result_array();
		$dataParaVista['ordenServicioDocumento'] = $this->model->obtenerDocumento($idOrdenServicio)->result_array();
		$ordenServicioDetalle = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->get('compras.ordenServicioDetalle')->result_array();
		$cargo = $this->model->getOrdenServicioCargo($idOrdenServicio)->result_array();

		foreach ($ordenServicioDetalle as $k => $v) {
			$ordenServicioDetalleSub = $this->db->where('estado', 1)->where('idOrdenServicioDetalle', $v['idOrdenServicioDetalle'])->get('compras.ordenServicioDetalleSub')->result_array();
			foreach ($ordenServicioDetalleSub as $k1 => $v1) {
				$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][$v1['idTipoPresupuestoDetalle']] = $v1;
			}
		}
		foreach ($cargo as $value) {
			$dataParaVista['ordenServicioCargo'][$value['idCargo']] = $value;
		}

		$detalle = $this->model->getOrdenServicioDetalle($idOrdenServicio)->result_array();
		foreach ($detalle as $value) {
			$dataParaVista['ordenServicioDetalle'][$value['idTipoPresupuesto']] = $value;
		}

		$provincia = [];
		foreach ($this->model->obtenerProvincia()->result_array() as $v) {
			$provincia[$v['cod_departamento']][$v['cod_provincia']] = $v;
		}
		$result['data']['provincia'] = $provincia;

		$distrito = [];
		foreach ($this->model->obtenerDistrito()->result_array() as $v) {
			$distrito[$v['cod_departamento']][$v['cod_provincia']][$v['cod_distrito']] = $v;
		}
		$result['data']['distrito'] = $distrito;
		$result['data']['cargo'] = $dataParaVista['cargo'];
		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar OrdenServicio';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroOrdenServicio", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idCliente = null;
		$idCuenta = null;
		$idCentroCosto = null;
		if ($post['chkUtilizarCliente']) {
			if (!is_numeric($post['clienteForm'])) {
				$insertCliente = [
					'nombre' => $post['clienteForm'],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.cliente', $insertCliente);
				$idCliente = $this->db->insert_id();
			} else {
				$idCliente = $post['clienteForm'];
			}
		} else {
			$idCuenta = $post['cuentaForm'];
			$idCentroCosto = $post['centroCostoForm'];
		}

		$updateOrdenServicio = [
			'idCliente' => $idCliente,
			'idCuenta' => $idCuenta,
			'idCentroCosto' => $idCentroCosto,
			'nombre' => $post['nombre'],
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
			'chkUtilizarCliente' => $post['chkUtilizarCliente'],
			'chkPresupuesto' => false
		];

		$idOrdenServicio = $post['idOrdenServicio'];
		$this->db->update('compras.ordenServicio', $updateOrdenServicio, ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->update('compras.ordenServicioHistorico', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);

		$insertOrdenServicioHistorico = $updateOrdenServicio;
		$insertOrdenServicioHistorico['idOrdenServicio'] = $idOrdenServicio;
		$insertOrdenServicioHistorico['idUsuario'] = $this->idUsuario;
		$insertOrdenServicioHistorico['fechaReg'] = getActualDateTime();
		unset($insertOrdenServicioHistorico['chkAprobado']);
		unset($insertOrdenServicioHistorico['chkPresupuesto']);
		$this->db->insert('compras.ordenServicioHistorico', $insertOrdenServicioHistorico);

		if (!isset($post['cargo'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Registro Erroneo!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe indicar al menos un cargo']);
			goto respuesta;
		}

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['cantidadCargo'] = checkAndConvertToArray($post['cantidadCargo']);
		$post['sueldoCargo'] = checkAndConvertToArray($post['sueldoCargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['idDocumento'] = isset($post['idDocumento']) ? checkAndConvertToArray($post['idDocumento']) : [];
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'sueldo' => $post['sueldoCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->update('compras.ordenServicioCargo', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->insert_batch('compras.ordenServicioCargo', $insertCargo);

		$insertOrdenServicioDetalle = [];
		$this->db->update('compras.ordenServicioDetalle', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);

		$insertOrdenServicioDetalleSub = [];
		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertOrdenServicioDetalle = [
					'idOrdenServicio' => $idOrdenServicio,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.ordenServicioDetalle', $insertOrdenServicioDetalle);
				$idOrdenServicioDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertOrdenServicioDetalleSub[] = [
							'idOrdenServicioDetalle' => $idOrdenServicioDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'valorPorcentual' => ($value == COD_ASIGNACIONFAMILIAR) ? $post['asignacionFamiliar'] : NULL,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}
		$this->db->insert_batch('compras.ordenServicioDetalleSub', $insertOrdenServicioDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			if ($post['idDocumento'][$k] == '0') {
				$documento = [
					'nombre' => $v,
					'idArea' => $post['area'][$k],
					'idPersonal' => verificarEmpty($post['persona'][$k], 4),
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.documento', $documento);
				$idDocumento = $this->db->insert_id();
				////////////////////////////////
				//
				// ENVIAR CORREO DE SOLICITUD.
				//
				// Para no enviar Correos en modo prueba.
				$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : MAIL_COORDINADORA_COMPRAS);
				$usuariosOperaciones = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
				$toOperaciones = [];
				foreach ($usuariosOperaciones as $usuario) {
					$toOperaciones[] = $usuario['email'];
				}
				$estadoEmail = $this->enviarCorreo(['data' => ['idDocumento' => $idDocumento], 'to' => $toOperaciones, 'cc' => ['luis.durand@visualimpact.com.pe']]);
				//
				////////////////////////////////
			} else {
				$idDocumento = $post['idDocumento'][$k];
			}

			$insertDocumento[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'idDocumento' => $idDocumento,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersonal' => verificarEmpty($post['persona'][$k], 4),
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->update('compras.ordenServicioDocumento', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
			$this->db->insert_batch('compras.ordenServicioDocumento', $insertDocumento);
		}

		$insertFechas = [];
		$orden = 1;
		for ($i = 0; $i < intval($post['cantidadMeses']); $i++) {
			if (empty($post['fechaIni'])) {
				$fechaDescripcion = 'Mes ' . ($i + 1);
			} else {
				$fechaDescripcion = date('Y-m-d', strtotime("+$i months", strtotime($post['fechaIni'])));
			}

			$insertFechas[] = [
				'idOrdenServicio' => $idOrdenServicio,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
			$orden++;
		}

		$this->db->update('compras.ordenServicioFecha', ['estado' => 0], ['idOrdenServicio' => $idOrdenServicio]);
		$this->db->insert_batch('compras.ordenServicioFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function enviarCorreo($params = [])
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

		$data = !empty($params['data']) ? $params['data'] : [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
		$this->email->to($params['to']);
		$this->email->cc($cc);

		$dataParaVista['link'] = base_url() . index_page() . 'OrdenServicio/adjuntarArchivo/' . $data['idDocumento'];

		$bcc = array(
			'eder.alata@visualimpact.com.pe',
			'luis.durand@visualimpact.com.pe'
		);
		$this->email->bcc($bcc);

		$this->email->subject('IMPACTBUSSINESS - DOCUMENTO SOLICITADO');
		$html = $dataParaVista['link'];
		$correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => $dataParaVista['link']], true);
		$this->email->message($correo);

		$estadoEmail = $this->email->send();

		if (!$estadoEmail) {

			$mensaje = $this->email->print_debugger();
		}

		return $estadoEmail;
	}

	public function aprobarOrdenServicio()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$updateOrdenServicio = [
			'chkAprobado' => true,
			'fechaAprobado' => getActualDateTime(),
		];

		$this->db->update('compras.ordenServicio', $updateOrdenServicio, ['idOrdenServicio' => $post['idOrdenServicio']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioRegistroPresupuesto()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idOrdenServicio = $post['idOrdenServicio'];

		$dataParaVista = [];
		$dataParaVista['ordenServicio'] = $this->model->getOrdenServicio($idOrdenServicio);
		$dataParaVista['ordenServicioFecha'] = $this->db->where('estado', 1)->where('idOrdenServicio', $idOrdenServicio)->order_by('idOrdenServicioFecha')->get('compras.ordenServicioFecha')->result_array();
		$dataParaVista['ordenServicioCargo'] = $this->model->getOrdenServicioCargo($idOrdenServicio)->result_array();
		$dataParaVista['ordenServicioDetalle'] = $this->model->getOrdenServicioDetalle($idOrdenServicio)->result_array();
		foreach ($this->model->getOrdenServicioDetalleSub($idOrdenServicio)->result_array() as $k => $v) {
			$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][] = $v;
		}
		foreach ($this->db->where('idTipoPresupuesto', 1)->where('tipo', 4)->get('compras.tipoPresupuestoDetalle')->result_array() as $v) {
			$dataParaVista['ordenServicioDetalleSub'][$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];
		foreach ($this->db->select('tpd.*, it.costo, it.idProveedor')->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')->get('compras.tipoPresupuestoDetalle tpd')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Presupuesto';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioRegistroPresupuesto", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['ordenServicioFecha'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['ordenServicioCargo'];
		echo json_encode($result);
	}

	public function formTablaParaLlenado()
	{
		$result = $this->result;
		$post = $this->input->post();

		if (empty($post['nroFecha'])) {
			$result['data']['html'] = 'No hay cantidad de Fechas';
			goto resultado;
		}
		if (empty($post['nroFecha'])) {
			$result['data']['html'] = 'No hay cantidad de Personas';
			goto resultado;
		}
		$result['result'] = 1;
		$result['msg']['title'] = '';
		$persona = [
			0 => [
				'id' => 1, 'nombre' => 'Persona A'
			],
			1 => [
				'id' => 2, 'nombre' => 'Persona B'
			],
			2 => [
				'id' => 3, 'nombre' => 'Persona C'
			]
		];
		$personaList = [];
		foreach ($persona as $k => $v) {
			$personaList[$v['id']] = $v;
		}
		$dataParaVista = $post;
		$dataParaVista['persona'] = $persona;
		$result['data']['persona'] = $personaList;
		$result['data']['html'] = $this->load->view('modulos/OrdenServicio/tablaParaRegistro', $dataParaVista, true);
		$result['data']['htmlSueldo'] = $this->load->view('modulos/OrdenServicio/tablaSueldo', $dataParaVista, true);
		resultado:
		echo json_encode($result);
	}

	public function registrarPresupuesto()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idOrdenServicio = $post['idOrdenServicio'];

		$post['fechaList'] = checkAndConvertToArray($post['fechaList']);
		$post['cargoList'] = checkAndConvertToArray($post['cargoList']);
		$post['idTipoPresupuesto'] = checkAndConvertToArray($post['idTipoPresupuesto']);
		$post['tpdS'] = checkAndConvertToArray($post['tpdS']);
		$post['clS'] = checkAndConvertToArray($post['clS']);

		// compras.presupuesto
		$insertPresupuesto = [
			'idOrdenServicio' => $idOrdenServicio,
			'observacion' => $post['observacion'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime()
		];
		$this->db->insert('compras.presupuesto', $insertPresupuesto);
		$idPresupuesto = $this->db->insert_id();

		// compras.presupuestoCargo
		$insertPresupuestoCargo = [];
		foreach ($post['fechaList'] as $kf => $vf) {
			foreach ($post['cargoList'] as $vc) {
				$insertPresupuestoCargo[] = [
					'idPresupuesto' => $idPresupuesto,
					'fecha' => $vf,
					'idCargo' => $vc,
					'cantidad' => $post["cantidadCargoFecha[$vc][$kf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.presupuestoCargo', $insertPresupuestoCargo);

		// compras.presupuestoDetalle
		foreach ($post['idTipoPresupuesto'] as $vd) {
			$insertPresupuestoDetalle = [
				'idPresupuesto' => $idPresupuesto,
				'idTipoPresupuesto' => $vd,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.presupuestoDetalle', $insertPresupuestoDetalle);
			$idPresupuestoDetalle = $this->db->insert_id();

			// compras.presupuestoDetalleSueldo
			if ($vd == COD_SUELDO) {
				$insertPresupuestoDetalleSueldo = [];
				foreach ($post['cargoList'] as $vc) {
					$post["monto[$vc]"] = checkAndConvertToArray($post["monto[$vc]"]);
					foreach ($post['tpdS'] as $kds => $vds) {
						$insertPresupuestoDetalleSueldo[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'idCargo' => $vc,
							'porCL' => $post["clS"][$kds],
							'monto' => $post["monto[$vc]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
				$this->db->insert_batch('compras.presupuestoDetalleSueldo', $insertPresupuestoDetalleSueldo);
			}

			// compras.presupuestoDetalleSub
			if ($vd != COD_SUELDO) {
				$insertPresupuestoDetalleSub = [];
				if (isset($post["tipoPresupuestoDetalleSub[$vd]"])) {
					$post["tipoPresupuestoDetalleSub[$vd]"] = checkAndConvertToArray($post["tipoPresupuestoDetalleSub[$vd]"]);
					foreach ($post["tipoPresupuestoDetalleSub[$vd]"] as $kds => $vds) {
						$post["splitDS[$vd]"] = checkAndConvertToArray($post["splitDS[$vd]"]);
						$post["precioUnitarioDS[$vd]"] = checkAndConvertToArray($post["precioUnitarioDS[$vd]"]);
						$post["cantidadDS[$vd]"] = checkAndConvertToArray($post["cantidadDS[$vd]"]);
						$post["gapDS[$vd]"] = checkAndConvertToArray($post["gapDS[$vd]"]);
						$post["montoDS[$vd]"] = checkAndConvertToArray($post["montoDS[$vd]"]);
						$post["frecuenciaDS[$vd]"] = checkAndConvertToArray($post["frecuenciaDS[$vd]"]);

						if (is_numeric($vds)) {
							$idTipoPresupuestoDetalle = $vds;
						} else {
							$ii = [
								'idTipoPresupuesto' => $vd,
								'nombre' => $vds,
								'split' => $post["splitDS[$vd]"][$kds],
								'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
								'frecuencia' => $post["frecuenciaDS[$vd]"][$kds],
								'estado' => 1
							];
							$this->db->insert('compras.tipoPresupuestoDetalle', $ii);
							$idTipoPresupuestoDetalle = $this->db->insert_id();
						}

						$insertPresupuestoDetalleSub = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $idTipoPresupuestoDetalle,
							'split' => $post["splitDS[$vd]"][$kds],
							'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
							'cantidad' => $post["cantidadDS[$vd]"][$kds],
							'gap' => $post["gapDS[$vd]"][$kds],
							'monto' => $post["montoDS[$vd]"][$kds],
							'idFrecuencia' => $post["frecuenciaDS[$vd]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
						$this->db->insert('compras.presupuestoDetalleSub', $insertPresupuestoDetalleSub);
						$idPresupuestoDetalleSub = $this->db->insert_id();

						// compras.presupuestoDetalleSubCargo
						$insertPresupuestoDetalleSubCargo = [];
						foreach ($post['cargoList'] as $vc) {
							$insertPresupuestoDetalleSubCargo[] = [
								'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
								'idCargo' => $vc,
								'checked' => isset($post["chkDS[$vc][$vd][$kds]"]) ? true : false,
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
						$this->db->insert_batch('compras.presupuestoDetalleSubCargo', $insertPresupuestoDetalleSubCargo);
					}
				}
			}
		}

		$this->db->update('compras.ordenServicio', ['chkPresupuesto' => true, 'fechaPresupuesto' => getActualDateTime()], ['idOrdenServicio' => $idOrdenServicio]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function formularioEditarPresupuesto()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idPresupuesto = $post['idPresupuesto'];

		$dataParaVista = [];

		$dataParaVista['presupuesto'] = $this->db->get('compras.presupuesto')->row_array();

		$presupuestoCargo = $this->model->getPresupuestoCargo($idPresupuesto)->result_array();
		foreach ($presupuestoCargo as $k => $v) {
			$cargo[$v['idCargo']] = $v;
			$fecha[$v['fecha']] = $v;
			$dataParaVista['presupuestoCargo'][$v['fecha']][$v['idCargo']] = $v;
		}
		foreach ($fecha as $k => $v) {
			$dataParaVista['fechaDelPre'][] = $v;
		}
		foreach ($cargo as $k => $v) {
			$dataParaVista['cargoDelPre'][] = $v;
		}
		$dataParaVista['presupuestoDetalle'] = $this->model->getPresupuestoDetalle($idPresupuesto)->result_array();

		foreach ($dataParaVista['presupuestoDetalle'] as $k => $v) {
			$dataParaVista['presupuestoDetalleSub'][$v['idPresupuestoDetalle']] = $this->model->getPresupuestoDetalleSub($v['idPresupuestoDetalle'])->result_array();

			$presupuestoDetalleSueldo = $this->model->getPresupuestoDetalleSueldo($v['idPresupuestoDetalle'])->result_array();
			foreach ($presupuestoDetalleSueldo as $k => $v) {
				$dataParaVista['presupuestoDetalleSueldo'][$v['idPresupuestoDetalle']][$v['idTipoPresupuestoDetalle']][$v['idCargo']] = $v;
				$dataParaVista['idCargoRef'] = $v['idCargo'];
			}
		}

		foreach ($this->db->select('tpd.*, it.costo, it.idProveedor')->join('compras.itemTarifario it', 'it.idItem = tpd.idItem AND it.flag_actual = 1', 'LEFT')->get('compras.tipoPresupuestoDetalle tpd')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;

		$result['result'] = 1;
		$result['msg']['title'] = 'Editar Presupuesto';
		$result['data']['html'] = $this->load->view("modulos/OrdenServicio/formularioEditarPresupuesto", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['fechaDelPre'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['cargoDelPre'];
		echo json_encode($result);
	}

	public function editarPresupuesto()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$idOrdenServicio = $post['idOrdenServicio'];
		$idPresupuesto = $post['idPresupuesto'];

		$post['fechaList'] = checkAndConvertToArray($post['fechaList']);
		$post['cargoList'] = checkAndConvertToArray($post['cargoList']);
		$post['idTipoPresupuesto'] = checkAndConvertToArray($post['idTipoPresupuesto']);
		$post['tpdS'] = checkAndConvertToArray($post['tpdS']);
		$post['clS'] = checkAndConvertToArray($post['clS']);

		$this->model->anularPresupuesto($idPresupuesto);

		// compras.presupuesto
		$updatePresupuesto = [
			'idOrdenServicio' => $idOrdenServicio,
			'observacion' => $post['observacion'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime()
		];
		$this->db->update('compras.presupuesto', $updatePresupuesto, ['idPresupuesto' => $idPresupuesto]);

		$updatePresupuesto['idPresupuesto'] = $idPresupuesto;
		$updatePresupuesto['estado'] = 1;
		$this->db->insert('compras.presupuestoHistorico', $updatePresupuesto);

		// compras.presupuestoCargo
		$insertPresupuestoCargo = [];
		foreach ($post['fechaList'] as $kf => $vf) {
			foreach ($post['cargoList'] as $vc) {
				$insertPresupuestoCargo[] = [
					'idPresupuesto' => $idPresupuesto,
					'fecha' => $vf,
					'idCargo' => $vc,
					'cantidad' => $post["cantidadCargoFecha[$vc][$kf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.presupuestoCargo', $insertPresupuestoCargo);

		// compras.presupuestoDetalle
		foreach ($post['idTipoPresupuesto'] as $vd) {
			$insertPresupuestoDetalle = [
				'idPresupuesto' => $idPresupuesto,
				'idTipoPresupuesto' => $vd,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.presupuestoDetalle', $insertPresupuestoDetalle);
			$idPresupuestoDetalle = $this->db->insert_id();

			// compras.presupuestoDetalleSueldo
			if ($vd == COD_SUELDO) {
				$insertPresupuestoDetalleSueldo = [];
				foreach ($post['cargoList'] as $vc) {
					$post["monto[$vc]"] = checkAndConvertToArray($post["monto[$vc]"]);
					foreach ($post['tpdS'] as $kds => $vds) {
						$insertPresupuestoDetalleSueldo[] = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'idCargo' => $vc,
							'porCL' => $post["clS"][$kds],
							'monto' => $post["monto[$vc]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
				$this->db->insert_batch('compras.presupuestoDetalleSueldo', $insertPresupuestoDetalleSueldo);
			}

			// compras.presupuestoDetalleSub
			if ($vd != COD_SUELDO) {
				$insertPresupuestoDetalleSub = [];
				if (isset($post["tipoPresupuestoDetalleSub[$vd]"])) {
					$post["tipoPresupuestoDetalleSub[$vd]"] = checkAndConvertToArray($post["tipoPresupuestoDetalleSub[$vd]"]);
					foreach ($post["tipoPresupuestoDetalleSub[$vd]"] as $kds => $vds) {
						$post["splitDS[$vd]"] = checkAndConvertToArray($post["splitDS[$vd]"]);
						$post["precioUnitarioDS[$vd]"] = checkAndConvertToArray($post["precioUnitarioDS[$vd]"]);
						$post["cantidadDS[$vd]"] = checkAndConvertToArray($post["cantidadDS[$vd]"]);
						$post["gapDS[$vd]"] = checkAndConvertToArray($post["gapDS[$vd]"]);
						$post["montoDS[$vd]"] = checkAndConvertToArray($post["montoDS[$vd]"]);
						$post["frecuenciaDS[$vd]"] = checkAndConvertToArray($post["frecuenciaDS[$vd]"]);

						$insertPresupuestoDetalleSub = [
							'idPresupuestoDetalle' => $idPresupuestoDetalle,
							'idTipoPresupuestoDetalle' => $vds,
							'split' => $post["splitDS[$vd]"][$kds],
							'precioUnitario' => $post["precioUnitarioDS[$vd]"][$kds],
							'cantidad' => $post["cantidadDS[$vd]"][$kds],
							'gap' => $post["gapDS[$vd]"][$kds],
							'monto' => $post["montoDS[$vd]"][$kds],
							'idFrecuencia' => $post["frecuenciaDS[$vd]"][$kds],
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
						$this->db->insert('compras.presupuestoDetalleSub', $insertPresupuestoDetalleSub);
						$idPresupuestoDetalleSub = $this->db->insert_id();

						// compras.presupuestoDetalleSubCargo
						$insertPresupuestoDetalleSubCargo = [];
						foreach ($post['cargoList'] as $vc) {
							$insertPresupuestoDetalleSubCargo[] = [
								'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
								'idCargo' => $vc,
								'checked' => isset($post["chkDS[$vc][$vd][$kds]"]) ? true : false,
								'idUsuario' => $this->idUsuario,
								'fechaReg' => getActualDateTime()
							];
						}
						$this->db->insert_batch('compras.presupuestoDetalleSubCargo', $insertPresupuestoDetalleSubCargo);
					}
				}
			}
		}

		$this->db->update('compras.ordenServicio', ['chkPresupuesto' => true, 'fechaPresupuesto' => getActualDateTime()], ['idOrdenServicio' => $idOrdenServicio]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}

	public function guardarDocumento()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$archivo = [
			'base64' => $post['file-item'],
			'name' => $post['file-name'],
			'type' => $post['file-type'],
			'carpeta' => 'documentos',
			'nombreUnico' => 'file_' . str_pad($post['idDocumento'], 6, "0", STR_PAD_LEFT) . '_' . str_replace(':', '', $this->hora),
		];

		$archivoName = $this->saveFileWasabi($archivo);

		$tipoArchivo = explode('/', $archivo['type']);
		$updateDocumento = [
			'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
			'extension' => FILES_WASABI[$tipoArchivo[1]],
			'nombre_inicial' => $archivo['name'],
			'nombre_archivo' => $archivoName,
			'nombre_unico' => $archivo['nombreUnico'],
			'fechaCarga' => getActualDateTime()
		];
		$this->db->update('compras.documento', $updateDocumento, ['idDocumento' => $post['idDocumento']]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}
}
