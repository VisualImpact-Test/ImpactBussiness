<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SolicitudRequerimientoInterno extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_FormularioRequerimientoInterno', 'model');
		$this->load->model('M_Item', 'model_item');
		$this->load->model('M_Cotizacion', 'm_cotizacion');
		$this->load->model('M_control', 'model_control');
		$this->load->model('M_Proveedor', 'model_proveedor');
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
			'assets/custom/js/SolicitudRequerimientoInterno',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['title'] = 'Solicitud de Requerimientos Internos';
		$config['data']['icon'] = 'fa fa-home';

		$config['view'] = 'formularioRequerimientosInternos/SolicitudRequerimientoInterno/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$dataParaVista = [];
		$solicitanteInterno = $this->session->userdata('idUsuario');
		$dataParaVista['requerimientoInterno'] = $this->model->obtenerInformacionRequerimientoInterno($solicitanteInterno)['query']->result_array();

		$html = getMensajeGestion('noResultados');
		if (!empty($dataParaVista['requerimientoInterno'])) {
			$html = $this->load->view("formularioRequerimientosInternos/SolicitudRequerimientoInterno/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentRequerimientoInterno']['datatable'] = 'tb-requerimientos-solicitanteInterno';
		$result['data']['views']['idContentRequerimientoInterno']['html'] = $html;
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
	public function formularioActualizacionRequerimientoInterno()
	{
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$this->load->library('Mobile_Detect');
		$detect = $this->mobile_detect;
		$dataParaVista['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['col_dropdown'] = 'three column' : '';

		$itemServicio = $this->model->obtenerItemServicio();
		if (!empty($itemServicio)) {
			foreach ($itemServicio as $key => $row) {
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['diasVigencia'] = $row['diasVigencia'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cotizacionInterna'] = $row['cotizacionInterna'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['flagCuenta'] = $row['flagCuenta'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['caracteristicas'] = $row['caracteristicas'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['cantidadImagenes'] = $row['cantidadImagenes'];
				$data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idUnidadMedida'] = $row['idUnidadMedida'];
			}
			foreach ($data['itemServicio'] as $k => $r) {
				$data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
			}
		}

		$data['itemServicio'][0] = array();
		$dataParaVista['requerimientoTarifario'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		$dataParaVista['requerimientoInterno'] = $dataParaVista['requerimientoTarifario'][0];

		$dataParaVista['pdf'] = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => true])['query']->row_array();
		$archivos = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => false])['query']->result_array();

		foreach ($archivos as $archivo) {
			$dataParaVista['requerimientoInternoDetalleArchivos'][$archivo['idRequerimientoInternoDetalle']][] = $archivo;
		}

		$dataParaVista['itemServicio'] = $data['itemServicio'];
		$dataParaVista['proveedorSelect'] = $this->db->select('idProveedor AS id, razonSocial AS value')->get_where('compras.proveedor', 'idProveedorEstado = 1 OR idProveedorEstado = 2')->result_array();
		$dataParaVista['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedorEstado' => 2])->result_array();
		$dataParaVista['usuarioAprobar'] = $this->model->obtenerUsuarioAprobar()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta(['idUsuario' => $this->idUsuario])['query']->result_array();
		$dataParaVista['tipoMoneda'] = $this->m_cotizacion->obtenertipoMoneda()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->m_cotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['prioridad'] = $this->m_cotizacion->obtenerPrioridadCotizacion()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->m_cotizacion->obtenerTipoServicioCotizacion()['query']->result_array();

		foreach ($dataParaVista['requerimientoTarifario'] as $k => $v) {
			$listProveedores = $this->db->get_where('compras.solicitudCostoProveedor', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			$list = [];
			foreach ($listProveedores as $vp) {
				$list[] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
			}
			$dataParaVista['listProveedores'][$v['idItem']] = implode(', ', $list);
			$dataParaVista['listProveedoresCosto'][$v['idItem']] = $listProveedorCosto = $this->db->get_where('compras.itemTarifario', ['idItem' => $v['idItem'], 'estado' => 1])->result_array();
			foreach ($listProveedorCosto as $kp => $vp) {
				$dataParaVista['listProveedoresCosto'][$v['idItem']][$kp]['proveedor'] = $this->db->get_where('compras.proveedor', ['idProveedor' => $vp['idProveedor']])->row_array()['razonSocial'];
				$config['data']['itemTarifario'][$v['idItem']][$vp['idProveedor']] = $vp['costo'];
			}
		}
		$config['data']['title'] = 'Aprobar Requerimiento';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioActualizacion", $dataParaVista, true);


		echo json_encode($config);
	}
	public function formularioVisualizacionRequerimientoInterno()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista = [];

		$data = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		foreach ($data as $key => $row) {
			$dataParaVista['cabecera']['idRequerimientoInterno'] = $row['idRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoInterno'] = $row['requerimientoInterno'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['cabecera']['codRequerimientoInterno'] = $row['codRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoIEstado'] = $row['requerimientoIEstado'];
			$dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
			$dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
			$dataParaVista['detalle'][$key]['fecha'] = !empty($row['fechaModificacion']) ? $row['fechaModificacion'] : $row['fechaCreacion'];
			$dataParaVista['detalle'][$key]['requerimientoInternoDetalleEstado'] = $row['requerimientoInternoDetalleEstado'];
		}
		//$dataParaVista['cabecera']['idOC'] = ($this->db->where('estado', '1')->where('idCotizacionDetalle', $data[0]['idCotizacionDetalle'])->get('compras.ordenCompraDetalle'))->row_array()['idOrdenCompra'];

		$dataParaVista['estados'] = $this->db->get_where('compras.requerimientoInternoEstado')->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Visualizar Requerimiento Interno';
		$result['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioVisualizacion", $dataParaVista, true);

		echo json_encode($result);
	}
	public function formularioSeleccionProveedor()
	{
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista['proveedor'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();
		
		$result['result'] = 1;
		$config['data']['title'] = 'Generar OC - Requerimiento Interno';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/SolicitudRequerimientoInterno/formularioProveedor", $dataParaVista, true);


		echo json_encode($config);
	}
	public function actualizarAprobacionCompras()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$dataDetalle = [];

		foreach ($post['nameItem'] as $k => $r) {
			if (!empty($r)) {
				$dataDetalle['update'][] = [
					'idRequerimientoInternoDetalle' => $post['idRequerimientoInternoDetalle'][$k],
					'idProveedor' => empty($post['proveedorForm'][$k]) ? NULL : $post['proveedorForm'][$k],
					'costo' => !empty($post['costoProveedorTarifarioForm'][$k]) ? $post['costoProveedorTarifarioForm'][$k] : NULL,
				];
			}
		}

		//ACTUALIZAR DETALLE REQUERIMIENTO INTERNO
		if (!empty($dataDetalle)) {
			$updateDetalle = $this->model->actualizarMasivo('compras.requerimientoInternoDetalle', $dataDetalle['update'], 'idRequerimientoInternoDetalle');
			//ESTADO ACTUALIZADO
			$estadoAprobado = 4;
			$datos = [
				'idRequerimientoInternoEstado' => $estadoAprobado
			];
			$where = "idRequerimientoInterno = " . $post['idRequerimientoInterno'];
			$estadoActualizado = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		}

		if (!$updateDetalle['estado'] && $estadoActualizado) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			//$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$usuariosCompras = 'bill.salazar@visualimpact.com.pe';
			$toCompras = [];
			/*foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}*/
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'to' => $toCompras]);

			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		} else {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		}

		respuesta:
		echo json_encode($result);
	}
	public function anularRequerimientoInterno()
	{
		$result = $this->result;
		$json = json_decode($this->input->post('data'));
		$estadoAnulado = 5;
		$datos = [
			'estado' => 0,
			'idRequerimientoInternoEstado' => $estadoAnulado
		];
		$where = "idRequerimientoInterno = " . $json;
		$res = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		if ($res) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			//$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$usuariosCompras = 'bill.salazar@visualimpact.com.pe';
			$toCompras = [];
			/*foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}*/
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $json, 'to' => $toCompras]);

			$result['result'] = 1;
			$result['msg']['content'] = getMensajeGestion('anulacionExitosaRI');
		} else {
			$result['result'] = 0;
			$result['msg']['content'] = getMensajeGestion('anulacionErroneaRI');
		}

		echo json_encode($result);
	}
	public function enviarCorreo($params = [])
	{
		$email = [];

		$data = [];
		$dataParaVista = [];
		$cc = !empty($params['cc']) ? $params['cc'] : [];

		$email['to'] = $params['to'];
		$email['cc'] = $cc;

		$data = $this->model->obtenerInformacionRequerimientoInternoDetalle($params)['query']->result_array();

		foreach ($data as $key => $row) {
			$dataParaVista['cabecera']['idRequerimientoInterno'] = $row['idRequerimientoInterno'];
			$dataParaVista['cabecera']['requerimientoInterno'] = $row['requerimientoInterno'];
			$dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
			$dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
			$dataParaVista['cabecera']['requerimientoInternoDetalleEstado'] = $row['requerimientoInternoDetalleEstado'] ==  'Por Generar OC' ? 'aceptado en compras' : $row['requerimientoInternoDetalleEstado'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
		}

		$dataParaVista['link'] = base_url() . index_page() . 'requerimientoInterno';

		$email['asunto'] = 'IMPACTBUSSINESS - REQUERIMIENTO INTERNO ' . strtoupper($dataParaVista['cabecera']['requerimientoInternoDetalleEstado']);

		$html = $this->load->view("formularioRequerimientosInternos/correo/administracion/estadoRequerimiento", $dataParaVista, true);
		$correo = $this->load->view("formularioRequerimientosInternos/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'SolicitanteInterno'], true);

		$email['contenido'] = $correo;
		$estadoEmail = email($email);

		return $estadoEmail;
	}
	public function getImagenes()
	{
		$post = $this->input->post();
		$imagenes = $this->db->where(['idItem' => $post['idItem'], 'estado' => 1])->get('compras.itemImagen')->result_array();
		echo json_encode($imagenes);
	}
	public function obtenerProveedor()
	{
		$grupo['data']['proveedor'] = $this->model->obtenerInformacionProveedores(['estadoProveedor' => 3])['query']->result_array();
		echo json_encode($grupo);
	}
	public function obtenerPrecioProveedorTarifario()
	{
		$data = json_decode($this->input->post('data'));
		var_dump($data);
		exit;
		$grupo['data'] = $this->model->obtenerItemServicio(['idProveedor' => $data['idProveedor']])['query']->result_array();
		echo json_encode($grupo);
	}
	public function enviarSolicitudCostoProveedor()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = $this->input->post();

		$listItems = checkAndConvertToArray($post['idItemForm']);
		$listProveedores = checkAndConvertToArray($post['proveedorSolicitudForm']);

		$post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
		$post['costoReferencialForm'] = checkAndConvertToArray($post['costoReferencialForm']);

		// $post['cantidadForm'] = checkAndConvertToArray($post['checkItem']);

		foreach ($listItems as $ki => $item) {
			if (isset($post['checkItem'][$item])) {
				foreach ($listProveedores as $proveedor) {
					$this->db->update('compras.solicitudCostoProveedor', ['estado' => 0], [
						'idProveedor' => $proveedor,
						'idItem' => $item,
					]);
					$this->db->insert(
						'compras.solicitudCostoProveedor',
						[
							'idProveedor' => $proveedor,
							'idItem' => $item,
							'cantidad' => $post['cantidadForm'][$ki],
							'costoReferencial' => $post['costoReferencialForm'][$ki],
							'fechaCreacion' => getActualDateTime(),
						]
					);
				}
			}
		}

		$result['result'] = 1;
		$result['data']['html'] = createMessage(['type' => 1, 'message' => 'Solicitud enviada al proveedor']);
		$result['msg']['title'] = 'Solicitud Enviada';

		$this->db->trans_complete();
		respuesta:
		echo json_encode($result);
	}
}
