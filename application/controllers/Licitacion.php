<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Licitacion extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Licitacion', 'model');
		$this->load->model('M_Cotizacion', 'mCotizacion');
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
			'assets/custom/js/licitacion'
		);

		$config['data']['icon'] = 'fas fa-dollar-sign';
		$config['data']['title'] = 'Licitación';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/Licitacion/index';

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
		$data = $this->model->obtenerInformacionLicitacion()->result_array();

		foreach ($data as $value) {
			$dataParaVista['licitacion'][$value['idLicitacion']] = $value;
			$cargo = $this->model->getLicitacionCargo($value['idLicitacion'])->result_array(); //$this->db->where('idLicitacion', )->where('estado', 1)->get('compras.licitacionCargo')->result_array();
			$documento = $this->db->where('idLicitacion', $value['idLicitacion'])->where('estado', 1)->get('compras.licitacionDocumento')->result_array();

			if (!empty($cargo)) {
				$temp = [];
				foreach ($cargo as $cargoData) {
					$temp[] = $cargoData['cargo'];
				}
				$dataParaVista['licitacion'][$value['idLicitacion']]['cargo'] = implode(', ', $temp);
			} else {
				$dataParaVista['licitacion'][$value['idLicitacion']]['cargo'] = '';
			}

			if (!empty($documento)) {
				$temp = [];
				foreach ($documento as $documentoData) {
					$temp[] = $documentoData['documento'];
				}
				$dataParaVista['licitacion'][$value['idLicitacion']]['documento'] = implode(', ', $temp);
			} else {
				$dataParaVista['licitacion'][$value['idLicitacion']]['documento'] = '';
			}
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Licitacion/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentLicitacion']['datatable'] = 'tb-licitacion';
		$result['data']['views']['idContentLicitacion']['html'] = $html;
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

	public function formularioRegistroLicitacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->get('compras.tipoPresupuesto')->result_array();
		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['licitacionCargo'] = [];
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

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Licitacion';
		$result['data']['cargo'] = $dataParaVista['cargo'];
		$result['data']['html'] = $this->load->view("modulos/Licitacion/formularioRegistroLicitacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function addDocumento()
	{
		$post = $this->input->post();
		$dataParaVista['num'] = $post['id'];
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();

		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();

		echo $this->load->view('modulos/Licitacion/addDocumento', $dataParaVista, true);
	}

	public function registrarLicitacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

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

		$insertLicitacion = [
			'idCliente' => $idCliente,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false
		];

		$this->db->insert('compras.licitacion', $insertLicitacion);
		$idLicitacion = $this->db->insert_id();

		$insertLicitacionHistorico = $insertLicitacion;
		$insertLicitacionHistorico['idLicitacion'] = $idLicitacion;
		$insertLicitacionHistorico['idUsuario'] = $this->idUsuario;
		$insertLicitacionHistorico['fechaReg'] = getActualDateTime();
		unset($insertLicitacionHistorico['chkAprobado']);

		$this->db->insert('compras.licitacionHistorico', $insertLicitacionHistorico);

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];
		$post['cantidadCargo'] = isset($post['cantidadCargo']) ? checkAndConvertToArray($post['cantidadCargo']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idLicitacion' => $idLicitacion,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->insert_batch('compras.licitacionCargo', $insertCargo);

		$insertLicitacionDetalle = [];
		$insertLicitacionDetalleSub = [];
		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertLicitacionDetalle = [
					'idLicitacion' => $idLicitacion,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.licitacionDetalle', $insertLicitacionDetalle);
				$idLicitacionDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertLicitacionDetalleSub[] = [
							'idLicitacionDetalle' => $idLicitacionDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}
		$this->db->insert_batch('compras.licitacionDetalleSub', $insertLicitacionDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			$insertDocumento[] = [
				'idLicitacion' => $idLicitacion,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersona' => $post['persona'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->insert_batch('compras.licitacionDocumento', $insertDocumento);
		}

		$insertFechas = [];

		for ($i = 0; $i < intval($post['cantidadMeses']); $i++) {
			if (empty($post['fechaIni'])) {
				$fechaDescripcion = 'Mes ' . ($i + 1);
			} else {
				$fechaDescripcion = date('Y-m-d', strtotime("+$i months", strtotime($post['fechaIni'])));
			}

			$insertFechas[] = [
				'idLicitacion' => $idLicitacion,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
		}

		$this->db->insert_batch('compras.licitacionFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:

		echo json_encode($result);
	}

	public function formularioActualizacionLicitacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$idLicitacion = $post['idLicitacion'];
		$dataParaVista = [];
		$dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->get('compras.tipoPresupuesto')->result_array();
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->get('compras.tipoPresupuesto')->result_array();
		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;
		$dataParaVista['persona'] = $this->db->get('compras.personal')->result_array();;
		$dataParaVista['idLicitacion'] = $idLicitacion;
		$dataParaVista['cliente'] = $this->db->get('compras.cliente')->result_array();
		$dataParaVista['departamento'] = $this->model->obtenerDepartamento()->result_array();
		$dataParaVista['moneda'] = $this->db->where('estado', 1)->get('compras.moneda')->result_array();
		$dataParaVista['licitacion'] = $this->model->getLicitacion($idLicitacion);
		$dataParaVista['licitacionFecha'] = $this->db->where('estado', 1)->where('idLicitacion', $idLicitacion)->order_by('idLicitacionFecha')->get('compras.licitacionFecha')->result_array();
		$dataParaVista['licitacionDocumento'] = $this->db->where('estado', 1)->where('idLicitacion', $idLicitacion)->order_by('idLicitacionDocumento')->get('compras.licitacionDocumento')->result_array();
		$licitacionDetalle = $this->db->where('estado', 1)->where('idLicitacion', $idLicitacion)->get('compras.licitacionDetalle')->result_array();
		$cargo = $this->model->getLicitacionCargo($idLicitacion)->result_array();

		foreach ($licitacionDetalle as $k => $v) {
			$licitacionDetalleSub = $this->db->where('estado', 1)->where('idLicitacionDetalle', $v['idLicitacionDetalle'])->get('compras.licitacionDetalleSub')->result_array();
			foreach ($licitacionDetalleSub as $k1 => $v1) {
				$dataParaVista['licitacionDetalleSub'][$v['idTipoPresupuesto']][$v1['idTipoPresupuestoDetalle']] = $v1;
			}
		}
		foreach ($cargo as $value) {
			$dataParaVista['licitacionCargo'][$value['idCargo']] = $value;
		}

		$detalle = $this->model->getLicitacionDetalle($idLicitacion)->result_array();
		foreach ($detalle as $value) {
			$dataParaVista['licitacionDetalle'][$value['idTipoPresupuesto']] = $value;
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

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Licitacion';
		$result['data']['html'] = $this->load->view("modulos/Licitacion/formularioRegistroLicitacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function actualizarLicitacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

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

		$updateLicitacion = [
			'idCliente' => $idCliente,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false
		];

		$idLicitacion = $post['idLicitacion'];
		$this->db->update('compras.licitacion', $updateLicitacion, ['idLicitacion' => $idLicitacion]);
		$this->db->update('compras.licitacionHistorico', ['estado' => 0], ['idLicitacion' => $idLicitacion]);

		$insertLicitacionHistorico = $updateLicitacion;
		$insertLicitacionHistorico['idLicitacion'] = $idLicitacion;
		$insertLicitacionHistorico['idUsuario'] = $this->idUsuario;
		$insertLicitacionHistorico['fechaReg'] = getActualDateTime();
		unset($insertLicitacionHistorico['chkAprobado']);
		$this->db->insert('compras.licitacionHistorico', $insertLicitacionHistorico);

		$post['cargo'] = checkAndConvertToArray($post['cargo']);
		$post['cantidadCargo'] = checkAndConvertToArray($post['cantidadCargo']);
		$post['chkContadorTipo'] = checkAndConvertToArray($post['chkContadorTipo']);
		$post['nroDocumento'] = isset($post['nroDocumento']) ? checkAndConvertToArray($post['nroDocumento']) : [];
		$post['area'] = isset($post['area']) ? checkAndConvertToArray($post['area']) : [];
		$post['persona'] = isset($post['persona']) ? checkAndConvertToArray($post['persona']) : [];

		$insertCargo = [];
		foreach ($post['cargo'] as $k => $v) {
			$insertCargo[] = [
				'idLicitacion' => $idLicitacion,
				'idCargo' => $v,
				'cantidad' => $post['cantidadCargo'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		$this->db->update('compras.licitacionCargo', ['estado' => 0], ['idLicitacion' => $idLicitacion]);
		$this->db->insert_batch('compras.licitacionCargo', $insertCargo);

		$insertLicitacionDetalle = [];
		$this->db->update('compras.licitacionDetalle', ['estado' => 0], ['idLicitacion' => $idLicitacion]);

		$insertLicitacionDetalleSub = [];
		foreach ($post['chkContadorTipo'] as $k => $v) {
			if (isset($post["chkTipoPresupuesto[$v]"])) {
				$insertLicitacionDetalle = [
					'idLicitacion' => $idLicitacion,
					'idTipoPresupuesto' => $v,
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.licitacionDetalle', $insertLicitacionDetalle);
				$idLicitacionDetalle = $this->db->insert_id();
			}
			if (isset($post["chkContadorTipoDetalle[$v]"])) {
				foreach (checkAndConvertToArray($post["chkContadorTipoDetalle[$v]"]) as $key => $value) {
					if (isset($post["chkTipoPresupuestoDet[$v][$value]"])) {
						$insertLicitacionDetalleSub[] = [
							'idLicitacionDetalle' => $idLicitacionDetalle,
							'idTipoPresupuestoDetalle' => $value,
							'idUsuario' => $this->idUsuario,
							'fechaReg' => getActualDateTime()
						];
					}
				}
			}
		}
		$this->db->insert_batch('compras.licitacionDetalleSub', $insertLicitacionDetalleSub);

		$insertDocumento = [];
		foreach ($post['nroDocumento'] as $k => $v) {
			$insertDocumento[] = [
				'idLicitacion' => $idLicitacion,
				'documento' => $v,
				'idArea' => $post['area'][$k],
				'idPersona' => $post['persona'][$k],
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
		}
		if (!empty($insertDocumento)) {
			$this->db->update('compras.licitacionDocumento', ['estado' => 0], ['idLicitacion' => $idLicitacion]);
			$this->db->insert_batch('compras.licitacionDocumento', $insertDocumento);
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
				'idLicitacion' => $idLicitacion,
				'orden' => $i + 1,
				'fecha' => $fechaDescripcion,
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime(),
			];
			$orden++;
		}

		$this->db->update('compras.licitacionFecha', ['estado' => 0], ['idLicitacion' => $idLicitacion]);
		$this->db->insert_batch('compras.licitacionFecha', $insertFechas);

		// Enviar correo
		// Falta funcion xd

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:

		echo json_encode($result);
	}

	public function aprobarLicitacion()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$updateLicitacion = [
			'chkAprobado' => true,
			'fechaAprobado' => getActualDateTime(),
		];

		$this->db->update('compras.licitacion', $updateLicitacion, ['idLicitacion' => $post['idLicitacion']]);

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
		$idLicitacion = $post['idLicitacion'];

		$dataParaVista = [];
		$dataParaVista['licitacion'] = $this->model->getLicitacion($idLicitacion);
		$dataParaVista['licitacionFecha'] = $this->db->where('estado', 1)->where('idLicitacion', $idLicitacion)->order_by('idLicitacionFecha')->get('compras.licitacionFecha')->result_array();
		$dataParaVista['licitacionCargo'] = $this->model->getLicitacionCargo($idLicitacion)->result_array();
		$dataParaVista['licitacionDetalle'] = $this->model->getLicitacionDetalle($idLicitacion)->result_array();
		// $dataParaVista['cargo'] = 
		foreach ($this->model->getLicitacionDetalleSub($idLicitacion)->result_array() as $k => $v) {
			$dataParaVista['licitacionDetalleSub'][$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['sueldoMinimo'] = $this->db->where('fechaFin', NULL)->get('compras.sueldoMinimo')->row_array()['monto'];

		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
		$dataParaVista['tipoPresupuestoDetalle'] = $tipoPresupuestoDetalle;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Presupuesto';
		$result['data']['html'] = $this->load->view("modulos/Licitacion/formularioRegistroPresupuesto", $dataParaVista, true);
		$result['data']['fechas'] = $dataParaVista['licitacionFecha'];
		$result['data']['tipoPresupuestoDetalle'] = $dataParaVista['tipoPresupuestoDetalle'];
		$result['data']['cargo'] = $dataParaVista['licitacionCargo'];
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
		$result['data']['html'] = $this->load->view('modulos/Licitacion/tablaParaRegistro', $dataParaVista, true);
		$result['data']['htmlSueldo'] = $this->load->view('modulos/Licitacion/tablaSueldo', $dataParaVista, true);
		resultado:
		echo json_encode($result);
	}

	public function registrarPresupuesto()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$idLicitacion = $post['idLicitacion'];

		$post['idLicitacionFecha'] = checkAndConvertToArray($post['idLicitacionFecha']);
		$post['idLicitacionCargo'] = checkAndConvertToArray($post['idLicitacionCargo']);
		$post['idLicitacionDetalle'] = checkAndConvertToArray($post['idLicitacionDetalle']);

		// compras.presupuesto
		$insertPresupuesto = [
			'idLicitacion' => $idLicitacion,
			// 'observacion' => $post['observacion'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime()
		];
		$this->db->insert('compras.presupuesto', $insertPresupuesto);
		$idPresupuesto = $this->db->insert_id();

		// compras.presupuestoCargo
		$insertPresupuestoCargo = [];
		foreach ($post['idLicitacionFecha'] as $vf) {
			foreach ($post['idLicitacionCargo'] as $vc) {
				$insertPresupuestoCargo[] = [
					'idPresupuesto' => $idPresupuesto,
					'idLicitacionFecha' => $vf,
					'idLicitacionCargo' => $vc,
					'cantidad' => $post["cantidadCargo[$vc][$vf]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
			}
		}
		$this->db->insert_batch('compras.presupuestoCargo', $insertPresupuestoCargo);

		// compras.presupuestoDetalle
		foreach ($post['idLicitacionDetalle'] as $vd) {
			$insertPresupuestoDetalle = [
				'idPresupuesto' => $idPresupuesto,
				'idLicitacionDetalle' => $vd,
				'monto' => '0',
				'idUsuario' => $this->idUsuario,
				'fechaReg' => getActualDateTime()
			];
			$this->db->insert('compras.presupuestoDetalle', $insertPresupuestoDetalle);
			$idPresupuestoDetalle = $this->db->insert_id();

			// compras.presupuestoDetalleSueldo
			$insertPresupuestoDetalleSueldo = [];
			foreach ($post['idLicitacionCargo'] as $kc => $vc) {
				log_message('error', json_encode($post["idLicitacionDetalleSub[$vd]"]));
				foreach (checkAndConvertToArray($post["idLicitacionDetalleSub[$vd]"]) as $kds => $vds) {
					$insertPresupuestoDetalleSueldo[] = [
						'idPresupuestoDetalle' => $idPresupuestoDetalle,
						'idLicitacionDetalleSub' => $vds,
						'idLicitacionCargo' => $vc,
						'porCL' => $post["porCl[$vc][$vds]"],
						'monto' => $post["monto[$vc][$vds]"],
						'idUsuario' => $this->idUsuario,
						'fechaReg' => getActualDateTime()
					];
				}
			}
			$this->db->insert_batch('compras.presupuestoDetalleSueldo', $insertPresupuestoDetalleSueldo);

			// compras.presupuestoDetalleSub
			$insertPresupuestoDetalleSub = [];
			foreach (checkAndConvertToArray($post["idLicitacionDetalleSub[$vd]"]) as $kds => $vds) {
				$insertPresupuestoDetalleSub = [
					'idPresupuestoDetalle' => $idPresupuestoDetalle,
					'idLicitacionDetalleSub' => $vds,
					'split' => $post["splitDS[$vds]"],
					'precioUnitario' => $post["precioUnitarioDS[$vds]"],
					'cantidad' => $post["cantidadDS[$vds]"],
					'monto' => $post["montoDS[$vds]"],
					'idFrecuencia' => $post["idFrecuenciaDS[$vds]"],
					'idUsuario' => $this->idUsuario,
					'fechaReg' => getActualDateTime()
				];
				$this->db->insert('compras.presupuestoDetalleSub', $insertPresupuestoDetalleSub);
				$idPresupuestoDetalleSub = $this->db->insert_id();

				// compras.presupuestoDetalleSubCargo
				$insertPresupuestoDetalleSubCargo = [];
				foreach ($post['idLicitacionCargo'] as $vc) {
					$insertPresupuestoDetalleSubCargo[] = [
						'idPresupuestoDetalleSub' => $idPresupuestoDetalleSub,
						'idLicitacionCargo' => $vc,
						'checked' => isset($post["chkPD[$vds][$vc]"]) ? true : false,
						'idUsuario' => $this->idUsuario,
						'fechaReg' => getActualDateTime()
					];
				}
				$this->db->insert_batch('compras.presupuestoDetalleSubCargo', $insertPresupuestoDetalleSubCargo);
			}
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		$this->db->trans_complete();
		respuesta:

		echo json_encode($result);
	}
}
