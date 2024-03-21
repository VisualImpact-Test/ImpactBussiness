<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proveedor extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Proveedor', 'model');
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
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/Finanzas/proveedor'
		);

		$config['data']['icon'] = 'fas fa-cart-plus';
		$config['data']['title'] = 'Seleccion de Proveedores';
		$config['data']['message'] = 'Lista de Proveedores';
		$config['data']['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		$config['data']['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
		$config['data']['estado'] = $this->model->obtenerEstado()['query']->result_array();
		$config['view'] = 'modulos/Finanzas/proveedor/index';

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

		$post['proveedorEstado'] = 1;
		$data = $this->model->obtenerInformacionProveedores($post)['query']->result_array();

		foreach ($data as $key => $row) {
			$dataParaVista[$row['idProveedor']] = [
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
				'idEstado' => $row['idProveedorEstado'],
				'estado' => $row['estado'],
				'estadoIcono' => $row['estadoIcono'],
				'estadoToggle' => $row['estadotoggle'],
				'chkDetraccion' => $row['chkDetraccion'],
				'cuentas_bancos' => nl2br($row['cuentas_bancos'])
			];
			$departamentosCobertura[$row['idProveedor']][$row['zc_departamento']] = $row['zc_departamento'];
			$provinciasCobertura[$row['idProveedor']][$row['zc_provincia']] = $row['zc_provincia'];
			$distritosCobertura[$row['idProveedor']][$row['zc_distrito']] = $row['zc_distrito'];
			$metodosPago[$row['idProveedor']][$row['metodoPago']] = $row['metodoPago'];
			$rubros[$row['idProveedor']][$row['rubro']] = $row['rubro'];
		}

		foreach ($dataParaVista as $key => $row) {
			$dataParaVista[$key]['departamentosCobertura'] = implode(', ', $departamentosCobertura[$key]);
			$dataParaVista[$key]['provinciasCobertura'] = implode(', ', $provinciasCobertura[$key]);
			$dataParaVista[$key]['distritosCobertura'] = implode(', ', $distritosCobertura[$key]);
			$dataParaVista[$key]['rubros'] = implode(', ', $rubros[$key]);
			$dataParaVista[$key]['metodosPago'] = implode(', ', $metodosPago[$key]);
		}

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Finanzas/Proveedor/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentProveedor']['datatable'] = 'tb-proveedor';
		$result['data']['views']['idContentProveedor']['html'] = $html;
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

	public function formularioRegistroProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['rubro'] = $this->model->obtenerRubro()['query']->result_array();
		$dataParaVista['metodoPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->model->obtenerProveedorTipoServicio()->result_array();
		$dataParaVista['comprobante'] = $this->model->obtenerComprobante()['query']->result_array();
		$dataParaVista['bancos'] = $this->db->get_where('dbo.banco')->result_array();
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda')->result_array();
		$dataParaVista['tiposCuentaBanco'] = $this->db->get_where('dbo.tipoCuentaBanco')->result_array();
		$ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

		$dataParaVista['departamento'] = [];
		$dataParaVista['provincia'] = [];
		$dataParaVista['distrito'] = [];

		foreach ($ciudad as $ciu) {
			$dataParaVista['departamento'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
			$dataParaVista['provincia'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
			$dataParaVista['distrito'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
			$dataParaVista['distrito_ubigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
		}

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Proveedor';
		$result['data']['html'] = $this->load->view("modulos/Proveedor/formularioRegistro", $dataParaVista, true);

		echo json_encode($result);
	}

	public function formularioActualizacionProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVisitaMetodoPago = [];
		$dataParaVistaRubro = [];
		$departamentosCobertura = [];
		$provinciasCobertura = [];
		$distritosCobertura = [];
		$data = $this->model->obtenerInformacionProveedores($post)['query']->result_array();

		foreach ($data as $key => $row) {
			$dataParaVista = [
				'idProveedor' => $row['idProveedor'],
				'razonSocial' => $row['razonSocial'],
				'nroDocumento' => $row['nroDocumento'],
				'idRubro' => $row['idRubro'],
				'rubro' => $row['rubro'],
				'cod_departamento' => $row['cod_departamento'],
				'departamento' => $row['departamento'],
				'cod_provincia' => $row['cod_provincia'],
				'provincia' => $row['provincia'],
				'cod_ubigeo' => $row['cod_ubigeo'],
				'distrito' => $row['distrito'],
				'direccion' => $row['direccion'],
				'nombreContacto' => $row['nombreContacto'],
				'correoContacto' => $row['correoContacto'],
				'numeroContacto' => $row['numeroContacto'],
				'informacionAdicional' => $row['informacionAdicional'],
				'estado' => $row['estado'],
				'estadoIcono' => $row['estadoIcono'],
				'estadoToggle' => $row['estadotoggle'],
				'costo' => $row['costo'],
				'idProveedorTipoServicio' => $row['idProveedorTipoServicio'],
				'idInformacionBancariaProveedor' => $row['idInformacionBancariaProveedor'],
				'tipoServicio' => $row['tipoServicio'],
				'cuentaPrincipal' => empty($row['cuenta']) ? NULL : $row['cuenta'],
				'cci' => empty($row['cci']) ? NULL : $row['cci'],
				'cuentaDetraccion' => empty($row['cuentaDetraccion']) ? NULL : $row['cuentaDetraccion'],
				'idComprobante' => $row['idComprobante'],
				'comprobante' => $row['comprobante'],
				'idBanco' => $row['idBanco'],
				'idTipoCuentaBanco' => $row['idTipoCuentaBanco'],
				'chkDetraccion' => $row['chkDetraccion'], 'adjuntoDetraccion' => $this->db->get_where('compras.proveedorArchivo', ['estado' => 1, 'idProveedor' => $row['idProveedor'], 'flagPrincipal' => 0])->row_array()
			];

			if (!empty($row['zc_departamento'])) $departamentosCobertura[trim($row['zc_departamento'])] = $row['zc_departamento'];
			if (!empty($row['zc_provincia'])) $provinciasCobertura[trim($row['zc_cod_departamento']) . '-' . trim($row['zc_cod_provincia'])] = $row['zc_provincia'];
			if (!empty($row['zc_distrito'])) $distritosCobertura[trim($row['zc_cod_departamento']) . '-' . trim($row['zc_cod_provincia']) . '-' . trim($row['zc_cod_distrito'])] = $row['zc_distrito'];
			if (!empty($row['idMetodoPago'])) $dataParaVisitaMetodoPago[trim($row['idMetodoPago'])] = $row['metodoPago'];
			if (!empty($row['idRubro'])) $dataParaVistaRubro[trim($row['idRubro'])] = $row['rubro'];
			if (!empty($row['idRubro'])) $dataParaVistaComprobante[trim($row['idComprobante'])] = $row['comprobante'];
			if (!empty($row['idProveedorTipoServicio'])) $dataParaVistaTipoServicio[trim($row['idProveedorTipoServicio'])] = $row['tipoServicio'];
		}

		$dataParaVista['departamentosCobertura'] = $departamentosCobertura;
		$dataParaVista['provinciasCobertura'] = $provinciasCobertura;
		$dataParaVista['distritosCobertura'] = $distritosCobertura;
		$dataParaVista['infoBancaria'] = $this->model->obtenerInformacionBancaria($post['idProveedor'])['query']->result_array();
		$dataParaVista['adjuntoPrincipal'] = $this->model->obtenerArchivo($post['idProveedor'])['query']->result_array();
		$dataParaVista['listadoDepartamentos'] = [];
		$dataParaVista['listadoProvincias'] = [];
		$dataParaVista['listadoDistritos'] = [];
		$dataParaVista['listadoDistritosUbigeo'] = [];
		$dataParaVista['proveedorMetodoPago'] = $dataParaVisitaMetodoPago;
		$dataParaVista['proveedorRubro'] = $dataParaVistaRubro;
		$dataParaVista['proveedorComprobante'] = $dataParaVistaComprobante;
		$dataParaVista['moneda'] = $this->db->get_where('compras.moneda')->result_array();
		$dataParaVista['bancos'] = $this->db->get_where('dbo.banco')->result_array();
		$dataParaVista['tiposCuentaBanco'] = $this->db->get_where('dbo.tipoCuentaBanco')->result_array();
		if (!empty($row['idProveedorTipoServicio'])) $dataParaVista['proveedorTipoServicio'] = $dataParaVistaTipoServicio;
		$dataParaVista['listTipoServicio'] = $this->model->obtenerProveedorTipoServicio()->result_array();

		$ciudad = $this->model->obtenerCiudadUbigeo()['query']->result();

		foreach ($ciudad as $ciu) {
			$dataParaVista['listadoDepartamentos'][trim($ciu->cod_departamento)]['nombre'] = textopropio($ciu->departamento);
			$dataParaVista['listadoProvincias'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)]['nombre'] = textopropio($ciu->provincia);
			$dataParaVista['listadoDistritos'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_distrito)]['nombre'] = textopropio($ciu->distrito);
			$dataParaVista['listadoDistritosUbigeo'][trim($ciu->cod_departamento)][trim($ciu->cod_provincia)][trim($ciu->cod_ubigeo)]['nombre'] = textopropio($ciu->distrito);
		}

		$dataParaVista['listadoRubros'] = $this->model->obtenerRubro()['query']->result_array();
		$dataParaVista['listadoComprobante'] = $this->model->obtenerComprobante()['query']->result_array();
		$dataParaVista['listadoMetodosPago'] = $this->model->obtenerMetodoPago()['query']->result_array();
		$dataParaVista['zonasProveedor'] = $this->model->obtenerZonaCoberturaProveedor(['idProveedor' => $post['idProveedor']])['query']->result_array();
		$dataParaVista['correosAdicionales'] = $this->model->obtenerCorreosAdicionales(['idProveedor' => $post['idProveedor'], 'estado' => '1'])->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Proveedor';
		$dataParaVista['disabled'] = false;

		if ($post['formularioValidar']) {
			$result['msg']['title'] = 'Validar Proveedor';
			$dataParaVista['disabled'] = true;
		}
		$result['data']['bancos'] = $dataParaVista['bancos'];
		$result['data']['tiposCuentaBanco'] = $dataParaVista['tiposCuentaBanco'];
		$result['data']['html'] = $this->load->view("modulos/Finanzas/Proveedor/formularioActualizacion", $dataParaVista, true);

		echo json_encode($result);
	}

	public function registrarProveedor()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['costo'] = monedaFormat($post['costo']);

		$data = [];
		$data['insert'] = [
			'razonSocial' => $post['razonSocial'],
			'idTipoDocumento' => 3,
			'nroDocumento' => $post['ruc'],
			'cod_ubigeo' => $post['distrito'],
			'direccion' => $post['direccion'],
			'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
			'idProveedorEstado' => 1,
			'nombreContacto' => $post['nombreContacto'],
			'correoContacto' => $post['correoContacto'],
			'numeroContacto' => $post['numeroContacto'],
			'costo' => $post['costo'],
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

		$informacionBancaria = [
			'cuenta' => $post['cuentaPrincipal'],
			'cci' => $post['cuentaInterbancariaPrincipal'],
			'idMoneda' => $post['moneda'],
			'idBanco' => $post['banco'],
			'idTipoCuentaBanco' => $post['tipoCuenta']
		];
		$informacionBancaria = getDataRefactorizada($informacionBancaria);
		// Inicio: Validando que no falte la captura de cuenta antes de guardar la información
		// → Captura Principal: Obligatorio
		if (!isset($post['cuentaPrincipalFile-item']) ||
			(count($post['cuentaPrincipalFile-item']) != count($informacionBancaria))
			
		) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta']);
			goto respuesta;
		}
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

		$insertArchivos = [];
		foreach ($informacionBancaria as $key => $value) {
			$dataInfoBanc['insert'] = [
				'idProveedor' => $idProveedor,
				'cuenta' => !empty($value['cuenta']) ? $value['cuenta'] : NULL,
				'cci' => !empty($value['cci']) ? $value['cci'] : NULL,
				'idMoneda' => !empty($value['idMoneda']) ? $value['idMoneda'] : NULL,
				'idBanco' => !empty($value['idBanco']) ? $value['idBanco'] : NULL,
				'idTipoCuentaBanco' => !empty($value['idTipoCuentaBanco']) ? $value['idTipoCuentaBanco'] : NULL,
				'estado' => 1,
			];

			$dataInfoBanc['tabla'] = 'compras.informacionBancariaProveedor';

			$th_insert = $this->model->insertarInformacionBancaria($dataInfoBanc);
			$idInfoBanc = $th_insert['id'];

			// → Archivo cuenta principal
			if (!empty($post['cuentaPrincipalFile-item'])) {
				$archivo = [
					'base64' => $post['cuentaPrincipalFile-item'][$key],
					'name' => $post['cuentaPrincipalFile-name'][$key],
					'type' => $post['cuentaPrincipalFile-type'][$key],
					'carpeta' => 'proveedorAdjuntos',
					'nombreUnico' => 'Cuenta_' . $idProveedor . '_' . str_replace(':', '', $this->hora) . '_' . $key,
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);
				$insertArchivos[] = [
					'idInformacionBancariaProveedor' => $idInfoBanc,
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
				'cod_departamento' => !empty($value['regionCobertura']) ? $value['regionCobertura'] : NULL,
				'cod_provincia' => !empty($value['provinciaCobertura']) ? $value['provinciaCobertura'] : NULL,
				'cod_distrito' => !empty($value['distritoCobertura']) ? $value['distritoCobertura'] : NULL
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
		
		if (!$insert['estado'] || !$second_insert['estado'] || !$third_insert || !$fourth_insert || !$fifth_insert || !$tipoServicio_insert) {
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

	public function actualizarProveedor()
	{
		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['costo'] = monedaFormat($post['costo']);

		$data = [];

		$data['update'] = [
			'idProveedor' => $post['idProveedor'],
			'razonSocial' => $post['razonSocial'],
			'nroDocumento' => $post['ruc'],
			'cod_ubigeo' => $post['distrito'],
			'direccion' => $post['direccion'],
			'informacionAdicional' => verificarEmpty($post['informacionAdicional'], 4),
			'nombreContacto' => $post['nombreContacto'],
			'correoContacto' => $post['correoContacto'],
			'numeroContacto' => $post['numeroContacto'],
			'costo' => $post['costo'],
			'chkDetraccion' => isset($post["chkDetraccion"]) ? 1 : 0,
			'cuentaDetraccion' => verificarEmpty($post['cuentaDetraccion'], 4),
		];

		$validacionExistencia = $this->model->validarExistenciaProveedor($data['update']);
		unset($data['update']['idProveedor']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.proveedor';
		$data['where'] = [
			'idProveedor' => $post['idProveedor']
		];

		$informacionBancaria = [
			'idInformacionBancariaProveedor' => $post['idProveedorInfoBancaria'],
			'cuenta' => $post['cuentaPrincipal'],
			'cci' => $post['cuentaInterbancariaPrincipal'],
			'idMoneda' => $post['moneda'],
			'idBanco' => $post['banco'],
			'idTipoCuentaBanco' => $post['tipoCuenta']
		];

		$informacionBancaria = getDataRefactorizada($informacionBancaria);

		foreach ($post['idProveedorArchivoEliminadoP'] as $key => $value) {
			// Inicio: Validando que no falte la captura de cuenta antes de guardar la información
			// → Captura Principal: Obligatorio
			$buscarAdjunto = $this->db->get_where('compras.proveedorArchivo', ['idProveedorArchivo' => $value, 'estado' => 1, 'flagPrincipal' => 1])->result_array();

			$post['idProveedorInfoBancaria'] = checkAndConvertToArray($post['idProveedorInfoBancaria']);
			foreach ($buscarAdjunto as $key => $idProveedorInfo) {
				if (!empty($post['idProveedorInfoBancaria']) && !empty($post['idProveedorArchivoEliminadoP'])) {
					if (isset($post['cuentaPrincipalFile-item'])) {
						if(empty($post['cuentaPrincipalFile-item'])) {
							$result['result'] = 0;
							$result['msg']['title'] = 'Alerta!';
							$result['msg']['content'] = getMensajeGestion(
								'alertaPersonalizada',
								['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta']
							);
							goto respuesta;
						}
					} else if (isset($post['cuentaPrincipal[' . 
					$idProveedorInfo['idInformacionBancariaProveedor'] . ']File-item'])) {
						if (empty($post['cuentaPrincipal[' . 
						$idProveedorInfo['idInformacionBancariaProveedor'] . ']File-item'])) {
							$result['result'] = 0;
							$result['msg']['title'] = 'Alerta!';
							$result['msg']['content'] = getMensajeGestion(
								'alertaPersonalizada',
								['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta']
							);
							goto respuesta;
						}
					} else {
							$result['result'] = 0;
							$result['msg']['title'] = 'Alerta!';
							$result['msg']['content'] = getMensajeGestion(
								'alertaPersonalizada',
								['message' => 'Debe adjuntar archivo con la captura del N° de Cuenta']
							);
							goto respuesta;
					}
				}
			}
		}

		// → Captura Detraccion: Obligatorio si marca el check
		$buscarAdjunto = $this->db->get_where('compras.proveedorArchivo', ['idProveedor' => $post['idProveedor'], 'estado' => 1, 'flagPrincipal' => 0])->result_array();
		if (
			isset($post["chkDetraccion"]) &&
			(!empty($post['idProveedorArchivoEliminadoD']) || empty($buscarAdjunto)) &&
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

		foreach ($informacionBancaria as $key => $value) {
			$post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item'] = checkAndConvertToArray(
				isset($post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item']) ? $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item'] : []
			);
			$post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-name'] = checkAndConvertToArray(
				isset($post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-name']) ? $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-name'] : []
			);
			$post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-type'] = checkAndConvertToArray(
				isset($post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-type']) ? $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-type'] : []
			);
		}
		$post['cuentaDetraccionFile-item'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-item']) ? $post['cuentaDetraccionFile-item'] : []
		);
		$post['cuentaDetraccionFile-name'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-name']) ? $post['cuentaDetraccionFile-name'] : []
		);
		$post['cuentaDetraccionFile-type'] = checkAndConvertToArray(
			isset($post['cuentaDetraccionFile-type']) ? $post['cuentaDetraccionFile-type'] : []
		);

		$insert = $this->model->actualizarProveedor($data);

		if (
			!empty($post['cuentaPrincipal']) && !empty($post['cuentaInterbancariaPrincipal']) &&
			!empty($post['banco']) && !empty($post['tipoCuenta'])
		) {
			$idInfoBanc = null;
			$archivoRFOT = [];
			$dataID = [];

			foreach ($informacionBancaria as $key => $value) {
				if ($value['idInformacionBancariaProveedor'] == '') {
					$dataInfoBanc['insert'] = [
						'idProveedor' => $post['idProveedor'],
						'cuenta' => !empty($value['cuenta']) ? $value['cuenta'] : NULL,
						'cci' => !empty($value['cci']) ? $value['cci'] : NULL,
						'idBanco' => !empty($value['idBanco']) ? $value['idBanco'] : NULL,
						'idMoneda' => !empty($value['idMoneda']) ? $value['idMoneda'] : NULL,
						'idTipoCuentaBanco' => !empty($value['idTipoCuentaBanco']) ? $value['idTipoCuentaBanco'] : NULL,
						'estado' => 1,
					];

					$dataInfoBanc['tabla'] = 'compras.informacionBancariaProveedor';


					$th_insert = $this->model->insertarInformacionBancaria($dataInfoBanc);
					$idInfoBanc = $th_insert['id'];
					$dataID[] = [
						'id' => $idInfoBanc
					];

					$dataID = getDataRefactorizada($dataID);
				}

				$dataInfBancaria[] = [
					'idInformacionBancariaProveedor' => $value['idInformacionBancariaProveedor'],
					'idProveedor' => $post['idProveedor'],
					'cuenta' => !empty($value['cuenta']) ? $value['cuenta'] : NULL,
					'cci' => !empty($value['cci']) ? $value['cci'] : NULL,
					'idBanco' => !empty($value['idBanco']) ? $value['idBanco'] : NULL,
					'idMoneda' => !empty($value['idMoneda']) ? $value['idMoneda'] : NULL,
					'idTipoCuentaBanco' => !empty($value['idTipoCuentaBanco']) ? $value['idTipoCuentaBanco'] : NULL,
					'estado' => 1,
				];
			}
			$th_update = $this->model->actualizarMasivo('compras.informacionBancariaProveedor', $dataInfBancaria, 'idInformacionBancariaProveedor');

			if (!empty($post['cuentaPrincipalFile-item'])) {
				if (is_array($post['cuentaPrincipalFile-item'])) {
					foreach ($post['cuentaPrincipalFile-item'] as $kw => $v) {
						$archivoRFOT[] = [
							'base64' => $post['cuentaPrincipalFile-item'][$kw],
							'name' => $post['cuentaPrincipalFile-name'][$kw],
							'type' => $post['cuentaPrincipalFile-type'][$kw]
						];
					}
				} else {
					$archivoRFOT[] = [
						'base64' => $post['cuentaPrincipalFile-item'],
						'name' => $post['cuentaPrincipalFile-name'],
						'type' => $post['cuentaPrincipalFile-type']
					];
				}

				$archivoRFOT = getDataRefactorizada($archivoRFOT);

				foreach ($archivoRFOT as $kw => $v) {
					$archivoR = [
						'base64' => $v['base64'],
						'name' => $v['name'],
						'type' => $v['type'],
						'carpeta' => 'proveedorAdjuntos',
						'nombreUnico' => 'Cuenta_' . $post['idProveedor'] . '_' . str_replace(':', '', $this->hora) . '_' . $kw,
					];
					$archivoName = $this->saveFileWasabi($archivoR);
					$tipoArchivo = explode('/', $archivoR['type']);


					$insertArchivosR[] = [
						'idInformacionBancariaProveedor' => $dataID[$kw]['id'],
						'idProveedor' => $post['idProveedor'],
						'idTipoArchivo' => FILES_TIPO_WASABI[$tipoArchivo[1]],
						'nombre_inicial' => $archivoR['name'],
						'nombre_archivo' => $archivoName,
						'nombre_unico' => $archivoR['nombreUnico'],
						'extension' => FILES_WASABI[$tipoArchivo[1]],
						'estado' => true,
						'idUsuarioReg' => $this->idUsuario,
						'flagPrincipal' => true,
					];
				}
			}

			if (!empty($insertArchivosR)) $this->db->insert_batch('compras.proveedorArchivo', $insertArchivosR);
		}

		if (!empty($post['idProveedorArchivoEliminadoP'])) {
			foreach ($post['idProveedorArchivoEliminadoP'] as $idProveedorArchivo_update) {
				$this->db->update('compras.proveedorArchivo', ['estado' => 0], ['idProveedorArchivo' => $idProveedorArchivo_update]);
			}
		}
		if (!empty($post['idProveedorArchivoEliminadoD'])) {
			foreach ($post['idProveedorArchivoEliminadoD'] as $idProveedorArchivo_update) {
				$this->db->update('compras.proveedorArchivo', ['estado' => 0], ['idProveedorArchivo' => $idProveedorArchivo_update]);
			}
		}

		// INICIO: Para subir archivos del proveedor → Funciona con multiples archivos.
		$insertArchivos = [];
		// → Archivo cuenta principal
		foreach ($informacionBancaria as $key => $value) {
			if (!empty($post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item'])) {
				foreach ($post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item'] as $k => $v) {
					$archivo = [
						'base64' => $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-item'][$k],
						'name' => $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-name'][$k],
						'type' => $post['cuentaPrincipal[' . $value['idInformacionBancariaProveedor'] . ']File-type'][$k],
						'carpeta' => 'proveedorAdjuntos',
						'nombreUnico' => 'Cuenta_' . $post['idProveedor'] . '_' . str_replace(':', '', $this->hora) . '_' . $key,
					];
					$archivoName = $this->saveFileWasabi($archivo);
					$tipoArchivo = explode('/', $archivo['type']);

					$insertArchivosE[] = [
						'idInformacionBancariaProveedor' => $value['idInformacionBancariaProveedor'],
						'idProveedor' => $post['idProveedor'],
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
		}
		if (!empty($insertArchivosE)) $this->db->insert_batch('compras.proveedorArchivo', $insertArchivosE);

		// → Archivo cuenta detracción
		if (!empty($post['cuentaDetraccionFile-item'])) {
			foreach ($post['cuentaDetraccionFile-item'] as $k => $v) {
				$archivo = [
					'base64' => $post['cuentaDetraccionFile-item'][$k],
					'name' => $post['cuentaDetraccionFile-name'][$k],
					'type' => $post['cuentaDetraccionFile-type'][$k],
					'carpeta' => 'proveedorAdjuntos',
					'nombreUnico' => 'CuentaDetraccion_' . $post['idProveedor'] . '_' . str_replace(':', '', $this->hora) . '_' . $k,
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);
				$insertArchivos[] = [
					'idProveedor' => $post['idProveedor'],
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

		$data = [];
		$data['tabla'] = 'compras.zonaCobertura';
		$data['where'] = [
			'idProveedor' => $post['idProveedor']
		];
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

			$data['update'][] = [
				'idProveedor' => $post['idProveedor'],
				'cod_departamento' => !empty($value['regionCobertura']) ? $value['regionCobertura'] : NULL,
				'cod_provincia' => !empty($value['provinciaCobertura']) ? $value['provinciaCobertura'] : NULL,
				'cod_distrito' => !empty($value['distritoCobertura']) ? $value['distritoCobertura'] : NULL
			];

			$zonasInsertadas[$idRegion][$idProvincia][$idDistrito] = 1;
		}

		$second_insert = $this->model->insertarProveedorCobertura($data);
		$data = [];
		foreach (checkAndConvertToArray($post['metodoPago']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $post['idProveedor'],
				'idMetodoPago' => $value,

			];
		}

		$data['where'] = ['idProveedor' => $post['idProveedor']];
		$this->model->BorrarProveedorMetodoPago(['tabla' => "compras.proveedorMetodoPago", 'where' => $data['where']]);

		$third_insert = $this->model->insertarMasivo("compras.proveedorMetodoPago", $data['insert']);

		$data = [];
		foreach (checkAndConvertToArray($post['rubro']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $post['idProveedor'],
				'idRubro' => $value,
			];
		}

		$data['where'] = ['idProveedor' => $post['idProveedor']];

		// Seria bueno cambiar el nombre de la funcion, pero lo evite desconociendo si hay otra consulta que haga uso de esta funcion
		$this->model->BorrarProveedorMetodoPago(['tabla' => "compras.proveedorRubro", 'where' => $data['where']]);

		$fourth_insert = $this->model->insertarMasivo("compras.proveedorRubro", $data['insert']);

		$data = [];

		foreach (checkAndConvertToArray($post['tipoServicio']) as $key => $value) {
			$data['update'][] = [
				'idProveedor' => $post['idProveedor'],
				'idProveedorTipoServicio' => $value,

			];
		}

		$tipoServicio_insert = $this->model->proveedorProveedorTipoServicioActualizarSinDuplicar($data['update']);

		$data = [];
		foreach (checkAndConvertToArray($post['comprobante']) as $key => $value) {
			$data['insert'][] = [
				'idProveedor' => $post['idProveedor'],
				'idComprobante' => $value,
			];
		}

		$data['where'] = ['idProveedor' => $post['idProveedor']];

		// Seria bueno cambiar el nombre de la funcion, pero lo evite desconociendo si hay otra consulta que haga uso de esta funcion
		$this->model->BorrarProveedorMetodoPago(['tabla' => "compras.proveedorComprobante", 'where' => $data['where']]);

		$fourth_insert = $this->model->insertarMasivo("compras.proveedorComprobante", $data['insert']);

		$data = [];

		
		if (!$insert['estado'] || !$second_insert['estado'] || !$third_insert || !$fourth_insert) {
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

	public function validarProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'idProveedorEstado' => $post['idProveedorEstado']
		];

		$data['tabla'] = 'compras.proveedor';
		$data['where'] = [
			'idProveedor' => $post['idProveedor']
		];

		$update = $this->model->actualizarProveedor($data);
		$data = [];

		$data['tabla'] = 'compras.proveedorEstadoHistorico';
		$data['insert'] = [
			'idProveedor' => $post['idProveedor'],
			'estado' => $post['idProveedorEstado'],
			'idUsuario' => $this->idUsuario,
			'fechaReg' => getActualDateTime(),
			'informacion' => $post['informacionEstado'],
			'datosValidos' => $post['datosValidos'],
			'contribuyenteValido' => $post['contribuyenteValido']
		];
		$insert = $this->model->insertarProveedor($data);
		$data = [];
		$dataParaVista = [];
		$infoProveedor = $this->model->obtenerInformacionProveedores(['idProveedor' => $post['idProveedor']])['query']->result_array();
		foreach ($infoProveedor as $key => $row) {
			$dataParaVista = [
				'direccion' => $row['direccion'],
				'nombreContacto' => $row['nombreContacto'],
				'correoContacto' => $row['correoContacto'],
				'numeroContacto' => $row['numeroContacto'],
				'motivo' => $post['informacionEstado']
			];
		}

		$html = $this->load->view($post['idProveedorEstado'] == 2 ? 'email/aprobacion' : 'email/rechazo', $dataParaVista, true);
		$correo = $this->load->view("formularioProveedores/formato", ['html' => $html, 'link' => base_url() . index_page() . '/proveedores'], true);

		$to = $this->idUsuario == '1' ? MAIL_DESARROLLO : MAIL_COORDINADORA_COMPRAS;
		$data = [
			'to' => $to,
			'asunto' => 'IMPACTBUSSINESS - ' . ($post['idProveedorEstado'] == 2 ? 'APROBACION' : 'RECHAZO') . ' DE PROVEEDOR',
			'contenido' => $correo
		];

		$rptaCorreo = email($data);
		if (!$update['estado'] || !$insert['estado'] || !$rptaCorreo) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		respuesta:
		echo json_encode($result);
	}

	public function actualizarEstadoProveedor()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'idProveedorEstado' => ($post['estado'] == 2) ? 3 : 2
		];

		$data['tabla'] = 'compras.proveedor';
		$data['where'] = [
			'idProveedor' => $post['idProveedor']
		];

		$update = $this->model->actualizarProveedor($data);
		$data = [];

		if (!$update['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		respuesta:
		echo json_encode($result);
	}
}

