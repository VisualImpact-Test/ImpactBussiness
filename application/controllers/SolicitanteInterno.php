<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SolicitanteInterno extends MY_Controller
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
			'assets/custom/js/RequerimientoInterno',
			'assets/custom/js/dataTables.select.min'
		);
		
		$config['data']['title'] = 'Formulario Requerimiento Interno';
		$config['data']['icon'] = 'fa fa-home';

		$config['view'] = 'formularioRequerimientosInternos/index';

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
			$html = $this->load->view("formularioRequerimientosInternos/reporte", ['datos' => $dataParaVista], true);
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
	public function formularioRegistroRequerimientoInterno()
	{
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
		$dataParaVista['itemServicio'] = $data['itemServicio'];
		$dataParaVista['proveedor'] = $this->model->obtenerInformacionProveedores(['estadoProveedor' => 3])['query']->result_array();
		$dataParaVista['usuarioAprobar'] = $this->model->obtenerUsuarioAprobar()['query']->result_array();
		$dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->db->get_where('compras.unidadMedida', ['estado' => '1'])->result_array();
		$dataParaVista['cuenta'] = $this->model->obtenerCuenta(['idUsuario' => $this->idUsuario])['query']->result_array();
		$dataParaVista['tipoMoneda'] = $this->m_cotizacion->obtenertipoMoneda()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->m_cotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['prioridad'] = $this->m_cotizacion->obtenerPrioridadCotizacion()['query']->result_array();
		$dataParaVista['tipoServicio'] = $this->m_cotizacion->obtenerTipoServicioCotizacion()['query']->result_array();
		$config['data']['title'] = 'Registrar Nuevo Requerimiento';
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioRegistro", $dataParaVista, true);


		echo json_encode($config);
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
		$config['data']['html'] = $this->load->view("formularioRequerimientosInternos/formularioActualizacion", $dataParaVista, true);


		echo json_encode($config);
	}
	public function registrarRequerimientoInterno()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$data = [];
		$dataDetalle = [];
		$data['tabla'] = 'compras.requerimientoInterno';

		$data['insert'] = [
			'nombre' => $post['nombre'],
			'codRequerimientoInterno' => NULL,
			'fechaEmision' => getActualDateTime(),
			'idUsuario' => $this->idUsuario,
			'idCuenta' => $post['cuentaForm'],
			'idCentroCosto' => $post['cuentaCentroCostoForm'],
			'idUsuarioAprobacion' => $post['aprobacionForm'],
			'idTipoMoneda' => $post['tipoMoneda'],
			'comentario' => $post['comentarioForm'],
			'idRequerimientoInternoEstado' => 1,
		];

		$data['finanzas_arreglo'] = [];
		$data['finanzas_pdf'] = [];

		$insert = $this->model->insertarRequerimientoInterno($data);
		$data['update'] = [
			'codRequerimientoInterno' => generarCorrelativo($insert['id'], 6),
		];
		$data['where'] = [
			'idRequerimientoInterno' => $insert['id'],
		];
		$updateCotizacion = $this->model->actualizarRequerimientoInterno($data);

		$post['nameItem'] = checkAndConvertToArray($post['nameItem']);
		$post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
		//$post['ProveedorForm'] = checkAndConvertToArray($post['ProveedorForm']);
		$post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
		$post['linkForm'] = checkAndConvertToArray($post['linkForm']);
		$post['costoReferencialForm'] = checkAndConvertToArray($post['costoReferencialForm']);

		foreach ($post['nameItem'] as $k => $r) {
			if (!empty($r)) {
				$dataItem = [];
				$idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
				$nameItem = $post['nameItem'][$k];
				$itemsSinProveedor = [];

				if (empty($idItem)) { // si es nuevo verificamos y lo registramos
					$validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem, 'nombre' => $nameItem]);
					$item = $validacionExistencia['query']->row_array();

					if (empty($item)) {
						$dataItem['insert'] = [
							'nombre' => trim($nameItem),
							'caracteristicas' => !empty($post['caracteristicasProveedor'][$k]) ? $post['caracteristicasProveedor'][$k] : NULL,
							'idItemTipo' => $post['tipoItemForm'][$k],
						];

						$dataItem['tabla'] = 'compras.item';
						$idItem = $this->model_item->insertarItem($dataItem)['id'];
					}

					if (!empty($item)) {
						$idItem = $item['idItem'];
						$itemsSinProveedor[$idItem] = true;
					}
				}

				$dataDetalle['insert'][] = [
					'idRequerimientoInterno' => $insert['id'],
					'idItem' => $idItem,
					'idItemTipo' => $post['tipoItemForm'][$k],
					'nombre' => trim($nameItem),
					'idItemEstado' => !empty($itemsSinProveedor[$idItem]) ? 2 : $post['idEstadoItemForm'][$k],
					'idProveedor' => empty($post['proveedorForm'][$k]) ? NULL : $post['proveedorForm'][$k],
					'cantidad' => $post['cantidadForm'][$k],
					'costoReferencial' => !empty($post['costoReferencialForm'][$k]) ? $post['costoReferencialForm'][$k] : NULL,
					'idCotizacionDetalleEstado' => 1,
					'fechaCreacion' => getActualDateTime(),
				];

				if (!empty($post["file-name[$k]"])) {
					$dataDetalle['archivos_arreglo'][$k] = getDataRefactorizada([
						'base64' => $post["file-item[$k]"],
						'type' => $post["file-type[$k]"],
						'name' => $post["file-name[$k]"],
					]);
					foreach ($dataDetalle['archivos_arreglo'][$k] as $key => $archivo) {
						$dataDetalle['archivos'][$k][] = [
							'base64' => $archivo['base64'],
							'type' => $archivo['type'],
							'name' => $archivo['name'],
							'carpeta' => 'requerimientoInterno',
							'nombreUnico' => uniqid(),
						];
					}
				}

				if (isset($post['imagenDeItem[' . $idItem . ']'])) {
					foreach (checkAndConvertToArray($post['imagenDeItem[' . $idItem . ']']) as $imagenes) {
						$itemImagen = $this->db->where('idItemImagen', $imagenes)->get('compras.itemImagen')->row_array();
						$dataDetalle['archivosDeImagen'][$k][] = $this->db->where('idItemImagen', $imagenes)->get('compras.itemImagen')->row_array();
					}
				}
			}
		}

		//INSERTAR DETALLE REQUERIMIENTO INTERNO
		if (!empty($dataDetalle)) {
			$dataDetalle['tabla'] = 'compras.requerimientoInternoDetalle';
			$insertDetalle = $this->model->insertarRequerimientoInternoDetalle($dataDetalle);
		}

		if (!$insert['estado'] || !$insertDetalle['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			// Para no enviar Correos en modo prueba.
			$idTipoParaCorreo = ($this->idUsuario == '1' ? USER_ADMIN : USER_COORDINADOR_COMPRAS);

			//$usuariosCompras = $this->model_control->getUsuarios(['tipoUsuario' => $idTipoParaCorreo])['query']->result_array();
			$usuariosCompras = 'bill.salazar@visualimpact.com.pe';
			$toCompras = [];
			/*foreach ($usuariosCompras as $usuario) {
				$toCompras[] = $usuario['email'];
			}*/
			$toCompras[] = $usuariosCompras;
			$this->enviarCorreo(['idRequerimientoInterno' => $insert['id'], 'to' => $toCompras]);

			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_complete();
		}

		respuesta:
		echo json_encode($result);
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
			$dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
			$dataParaVista['detalle'][$key]['item'] = $row['item'];
			$dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
			$dataParaVista['detalle'][$key]['costoReferencial'] = $row['costoReferencial'];
			$dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
		}

		$dataParaVista['link'] = base_url() . index_page() . 'requerimientoInterno';

		$email['asunto'] = 'IMPACTBUSSINESS - NUEVO REQUERIMIENTO INTERNO GENERADO';

		$html = $this->load->view("formularioRequerimientosInternos/correo/informacionCompras", $dataParaVista, true);
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
}
