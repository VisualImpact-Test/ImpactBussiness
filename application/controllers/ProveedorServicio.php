<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ProveedorServicio', 'model');
		$this->load->model('M_FormularioProveedor', 'model_FormularioProveedor');
		$this->load->model('M_Cotizacion', 'mCotizacion');
		// $this->load->model('M_Cotizacion', 'mCotizacion');
		// $this->load->model('M_control', 'model_control');
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
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/core/gestion',
			'assets/custom/js/proveedorServicio',
		);

		$config['data']['proveedor'] = $this->db->order_by('razonSocial')->get_where('compras.proveedor', ['idProveedorEstado' => '2'])->result_array();
		$config['data']['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$config['data']['cuentaCentroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		
		$config['data']['icon'] = 'icon chartline';
		$config['data']['title'] = 'Gestor de Servicio';
		$config['data']['message'] = 'Lista';
		$config['view'] = 'modulos/ProveedorServicio/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$where = [];
		if (!empty($post['proveedor'])) $where['idProveedor'] = $post['proveedor'];
		if (!empty($post['fecha'])) $where['fechaEmision'] = $post['fecha'];
		if (!empty($post['cuenta'])) $where['idCuenta'] = $post['cuenta'];
		if (!empty($post['centroCosto'])) $where['idCentroCosto'] = $post['centroCosto'];
		if (!empty($post['codPO'])) $where['codPo_'] = $post['codPO'];

		$dataParaVista = [];

		$data = $this->model->obtenerDatosReporte($where)->result_array();
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
				$data[$k]['adjuntoFechaEjecucion'] = $this->db->get_where('compras.cotizacionDetalleProveedorFechaEjecucion', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor']])->result_array();
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
			if (empty($v['fechaEntrega'])) {
				$data[$k]['status'] = 'Solicitado';
			} else {
				// Se busca cotizaciones del proveedor y se listan los ids
				$cotizacionDelProveedor = $this->db->get_where('compras.cotizacionDetalleProveedorDetalle', ['estado' => '1', 'idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor']])->result_array();
				$list_idCotDet = [];
				foreach ($cotizacionDelProveedor as $vcd) {
					$list_idCotDet[] = $vcd['idCotizacionDetalle'];
				}
				// De los ids listados se busca cuales fueron tomadas para la cotizacion
				$cotDet = $this->db->where_in('idCotizacionDetalle', $list_idCotDet)->get_where('compras.cotizacionDetalle', ['idProveedor' => $v['idProveedor']])->result_array();
				if (empty($cotDet)) {
					$data[$k]['status'] = 'Cotizado';
				} else {
					// En caso se encuentren en uso la cotizacion del proveedor se busca las ordenes de compras utilizadas en dicha cotizacion
					$ocd = $this->db->where_in('idCotizacionDetalle', $list_idCotDet)->get('compras.ordenCompraDetalle')->result_array();
					if (empty($ocd)) { // Si no hay ordenes se analiza que el tiempo de vigencia siga disponible.
						$fEn = new DateTime(date_change_format_bd($v['fechaEntrega']));
						$fAc = new DateTime('now');
						if ($fAc > $fEn) {
							$data[$k]['status'] = 'Vencido';
						} else {
							$data[$k]['status'] = 'Por confirmar';
						}
					} else {
						// En caso SI se encuentren ordenes de compra se listan los ids de las OC (usualmente es 1 pero se tiene en consideración para multiples).
						$list_idOrdComp = [];
						foreach ($ocd as $v1) {
							$list_idOrdComp[] = $v1['idOrdenCompra'];
						}
						$oc = $this->db->where_in('idOrdenCompra', $list_idOrdComp)->where('idProveedor', $v['idProveedor'])->get('compras.ordenCompra')->result_array();
						// TODO → El if parece que puede borrarse ya que no existe posibilidad que cumpla la condición
						if (empty($oc)) {
							$data[$k]['status'] = 'Por confirmar';
						} else {
							$data[$k]['status'] = 'Aprobado';

							// Se consulta los tipos de Item, considerando que solo se requiere Validación de Arte para SERVICIO (Mantenimiento), Textiles e Impresiones.
							// TODO falta generar tipo de item "Impresiones"
							$listDetalleCotProv = $this->db->get_where('compras.cotizacionDetalleProveedorDetalle', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor']])->result_array();
							foreach ($listDetalleCotProv as $vt) {
								$it = $this->db->get_where('compras.cotizacionDetalle', ['idCotizacionDetalle' => $vt['idCotizacionDetalle'], 'estado' => 1])->row_array()['idItemTipo'];
								if ($it == COD_SERVICIO['id'] || $it == COD_TEXTILES['id']) {
									$data[$k]['requiereValidacion'] = '1';
									$data[$k]['mostrarValidacion'] = '1';
									$data[$k]['solicitarFecha'] = '0';
								}
							}
							// Se consulta si tiene "Validación de Arte" cargado aprobados.
							$va = $this->db->group_start()->where('flagRevisado', 0)->or_where('flagAprobado', 1)->group_end()->where('idProveedor', $v['idProveedor'])->where('idCotizacion', $v['idCotizacion'])->where('estado', 1)->get('compras.validacionArte')->result_array();
							if (!empty($va)) {
								$data[$k]['mostrarValidacion'] = '0';
							}

							// Se compara el Total de Artes Cargados con el Total de Artes Aprobados.
							$w = ['idProveedor' => $v['idProveedor'], 'idCotizacion' => $v['idCotizacion'], 'estado' => 1];
							$artesCargados = $this->db->get_where('compras.validacionArte', $w)->result_array();
							$w['flagRevisado'] = 1;
							$w['flagAprobado'] = 1;
							$artesAprobados = $this->db->get_where('compras.validacionArte', $w)->result_array();

							if (!empty($artesAprobados)) {
								if (count($artesAprobados) == count($artesCargados)) {
									$data[$k]['solicitarFecha'] = '1';
									// $data[$k]['fechaInicio'] = $artesAprobados[0]['fechaInicio'];
									// $data[$k]['fechaFinal'] = $artesAprobados[0]['fechaFinal'];
									// $data[$k]['flagFechaRegistro'] = $artesAprobados[0]['flagFechaRegistro'];
								}
							}
							// Si se solicita fecha, validar si la información fue cargada o no.
							if ($data[$k]['solicitarFecha'] == '1') {
								$fechaEjecCargado = $this->db->get_where('compras.cotizacionDetalleProveedorFechaEjecucion', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor'], 'estado' => '1'])->result_array();
								if (!empty($fechaEjecCargado)) {
									$data[$k]['flagFechaRegistro'] = '1';
									$data[$k]['fechaInicio'] = $fechaEjecCargado[0]['fechaInicial'];
									$data[$k]['fechaFinal'] = $fechaEjecCargado[0]['fechaFinal'];
								}
							}
						}
					}
				}
			}

			$data[$k]['ocGen'] = $this->model_FormularioProveedor->getDistinctOC(['idCotizacion' => $v['idCotizacion'], 'idProveedor' => $v['idProveedor']])->result_array();

			$sustComp = $this->db->get_where('compras.cotizacionDetalleProveedorSustentoCompra', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor'], 'estado' => '1'])->result_array();
			$data[$k]['sustentoComp'][$v['idCotizacionDetalleProveedor']] = $sustComp;
			if (!empty($sustComp)) {
				$data[$k]['flagSustentoServicio'] = '1';
				foreach ($sustComp as $rSC) {
					if ($rSC['flagRevisado'] == '0' || $rSC['flagAprobado'] == '0') {
						$data[$k]['flagSustentoServicio'] = '0';
					}
				}
			}

			$va4 = $this->db->where('estado', '1')->where('idCotizacion', $v['idCotizacion'])->where('idProveedor', $v['idProveedor'])->get('compras.sustentoAdjunto')->result_array();
			foreach ($va4 as $v4) {
				$data[$k]['sustentoC'][$v4['idCotizacion']][$v4['idProveedor']] = $v4;
			}
			$accesoDocumento = !empty($proveedor['nroDocumento']) ? base64_encode($proveedor['nroDocumento']) : '';
			$accesoEmail = !empty($proveedor['correoContacto']) ? base64_encode($proveedor['correoContacto']) : '';
			$fechaActual = base64_encode(date('Y-m-d'));
			$accesoCodProveedor = !empty($proveedor['idProveedor']) ? base64_encode($proveedor['idProveedor']) : '';
			$data[$k]['link'] = "?doc={$accesoDocumento}&email={$accesoEmail}&date={$fechaActual}&cod={$accesoCodProveedor}";
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($data)) {
			$dataParaVista['data'] = $data;
			$html = $this->load->view("modulos/ProveedorServicio/reporte", $dataParaVista, true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentProveedorServicio']['datatable'] = 'tb-proveedorServicio';
		$result['data']['views']['idContentProveedorServicio']['html'] = $html;
		// $result['data']['configTable'] =  [
		// 	'columnDefs' =>
		// 	[
		// 		0 =>
		// 		[
		// 			"visible" => false,
		// 			"targets" => []
		// 		]
		// 	]
		// ];

		echo json_encode($result);
	}
	/*
	public function adjuntarArchivo($id)
	{
		$config['single'] = true;
		// AGREGAR VALIDACION PARA SOLO MOSTRAR LOS PENDIENTES.
		$config['js']['script'] = array('assets/custom/js/adjuntarDocumento');
		$config['data']['documento'] = $this->model->getDocumento($id)->row_array();
		$config['view'] = 'adjuntarDocumento';

		$this->view($config);
	}

	

	public function formularioRegistroOrdenServicio()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
			$tipoPresupuestoDetalle[$v['idTipoPresupuesto']][] = $v;
		}
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

		$insertOrdenServicio = [
			'idCliente' => $idCliente,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
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
		$dataParaVista['cargo'] = $this->db->get('compras.cargo')->result_array();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('orden, 1')->get('compras.tipoPresupuesto')->result_array();
		$dataParaVista['area'] = $this->db->get('compras.area')->result_array();
		//$dataParaVista['tipoPresupuesto'] = $this->db->get('compras.tipoPresupuesto')->result_array();
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

		$updateOrdenServicio = [
			'idCliente' => $idCliente,
			'idDepartamento' => $post['departamento'],
			'idProvincia' => $post['provincia'],
			'idDistrito' => !empty($post['distrito']) ? $post['distrito'] : NULL,
			'idMoneda' => $post['moneda'],
			'cantidadMeses' => $post['cantidadMeses'],
			'fechaIni' => !empty($post['fechaIni']) ? $post['fechaIni'] : NULL,
			'fechaFin' => !empty($post['fechaFin']) ? $post['fechaFin'] : NULL,
			'observacion' => $post['observacion'],
			'chkAprobado' => false,
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

		foreach ($this->db->get('compras.tipoPresupuestoDetalle')->result_array() as $k => $v) {
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
	*/
}
