<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AprobacionRequerimientoInterno extends MY_Controller
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
			'assets/custom/js/Administracion/AprobacionRequerimientoInterno',
			'assets/custom/js/dataTables.select.min'
		);

		$config['data']['title'] = 'Requerimientos Internos';
		$config['data']['icon'] = 'fa fa-home';

		$config['view'] = 'formularioRequerimientosInternos/administracion/index';

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
			$html = $this->load->view("formularioRequerimientosInternos/administracion/reporte", ['datos' => $dataParaVista], true);
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

		$config = array();
		$this->load->library('Mobile_Detect');
		$detect = $this->mobile_detect;
		$config['data']['col_dropdown'] = 'four column';
		$detect->isMobile() ? $config['data']['col_dropdown'] = '' : '';
		$detect->isTablet() ? $config['data']['col_dropdown'] = 'three column' : '';

		$dataParaVista = [];
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
		$dataParaVista['requerimientoInterno'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->row_array();
		$dataParaVista['requerimientoTarifario'] = $this->model->obtenerInformacionRequerimientoInternoDetalle($post)['query']->result_array();

		$dataParaVista['pdf'] = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => true])['query']->row_array();
		$archivos = $this->model->obtenerInformacionRequerimientoInternoArchivos(['idRequerimientoInterno' => $post['idRequerimientoInterno'], 'aprobacion' => false])['query']->result_array();

		foreach ($archivos as $archivo) {
			$dataParaVista['requerimientoInternoDetalleArchivos'][$archivo['idRequerimientoInternoDetalle']][] = $archivo;
		}
		$dataParaVista['proveedorARequerir'] = $this->model_proveedor->obtenerInformacionProveedores([/*'estadoProveedor' => 2, */'order_by' => 'razonSocial ASC'])['query']->result_array();
		$dataParaVista['proveedor'] = $this->model->obtenerInformacionProveedores(['estadoProveedor' => 3])['query']->result_array();
		$dataParaVista['itemServicio'] = $data['itemServicio'];
		$dataParaVista['usuarioAprobar'] = $this->model->obtenerUsuarioAprobar()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta(['idUsuario' => $this->idUsuario])['query']->result_array();
		$dataParaVista['tipoMoneda'] = $this->m_cotizacion->obtenertipoMoneda()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->m_cotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['prioridad'] = $this->m_cotizacion->obtenerPrioridadCotizacion()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->m_cotizacion->obtenerTipoServicioCotizacion()['query']->result_array();
		$config['data']['title'] = 'Registrar Nuevo Requerimiento';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/administracion/formularioVerificacion", $dataParaVista, true);


		echo json_encode($config);
	}
	public function aprobarRequerimientoInterno()
	{
		$result = $this->result;
		$json = json_decode($this->input->post('data'));
		$estadoAprobado = 2;
		$datos = [
			'idRequerimientoInternoEstado' => $estadoAprobado
		];
		$where = "idRequerimientoInterno = " . $json;
		$res = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		if ($res) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $json, 'to' => $this->idUsuario == '1' ? ['bill.salazar@visualimpact.com.pe'] : $toCompras]);

			$result['result'] = 1;
			$result['msg']['content'] = getMensajeGestion('aprobacionExitosaRI');
		} else {
			$result['result'] = 0;
			$result['msg']['content'] = getMensajeGestion('aprobacionErroneaRI');
		}

		echo json_encode($result);
	}
	public function rechazarRequerimientoInterno()
	{
		$result = $this->result;
		$json = json_decode($this->input->post('data'));
		$estadoRechazado = 3;
		$datos = [
			'idRequerimientoInternoEstado' => $estadoRechazado
		];
		$where = "idRequerimientoInterno = " . $json;
		$res = $this->model->actualizarSimple('compras.requerimientoInterno', $where, $datos);
		if ($res) {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$toCompras = [];
			foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $json, 'to' => $this->idUsuario == '1' ? ['bill.salazar@visualimpact.com.pe'] : $toCompras]);

			$result['result'] = 1;
			$result['msg']['content'] = getMensajeGestion('rechazoExitosoRI');
		} else {
			$result['result'] = 0;
			$result['msg']['content'] = getMensajeGestion('rechazoErroneoRI');
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
			$dataParaVista['cabecera']['requerimientoInternoDetalleEstado'] = $row['requerimientoInternoDetalleEstado'] == 'En Compras' ? 'aprobado y se encuentra en compras' : $row['requerimientoInternoDetalleEstado'];
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
		}

		$dataParaVista['link'] = base_url() . index_page() . 'requerimientoInterno';

		$email['asunto'] = 'IMPACTBUSSINESS - REQUERIMIENTO INTERNO '.strtoupper($dataParaVista['cabecera']['requerimientoInternoDetalleEstado']);

		$html = $this->load->view("formularioRequerimientosInternos/correo/administracion/estadoRequerimiento", $dataParaVista, true);
		$correo = $this->load->view("formularioRequerimientosInternos/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'SolicitanteInterno'], true);

		$email['contenido'] = $correo;
		$estadoEmail = email($email);

		return $estadoEmail;
	}
}
