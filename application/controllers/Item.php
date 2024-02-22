<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Item', 'model');
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
			// 'assets/libs/datatables/responsive.bootstrap4.min',
			// 'assets/custom/js/core/datatables-defaults',
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/libs/fileDownload/jquery.fileDownload',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/core/gestion',
			'assets/custom/js/item'
		);

		$config['data']['icon'] = 'fas fa-shopping-cart';
		$config['data']['title'] = 'Items';
		$config['data']['message'] = 'Lista de Items';
		$config['data']['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$config['data']['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$config['data']['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$config['data']['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$config['data']['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();

		$config['view'] = 'modulos/item/index';

		$this->view($config);
	}

	public function reporte()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];
		$dataParaVista = $this->model->obtenerInformacionItems($post)['query']->result_array();

		$html = getMensajeGestion('noRegistros');
		if (!empty($dataParaVista)) {
			$html = $this->load->view("modulos/Item/reporte", ['datos' => $dataParaVista], true);
		}

		$result['result'] = 1;
		$result['data']['views']['idContentItem']['datatable'] = 'tb-item';
		$result['data']['views']['idContentItem']['html'] = $html;
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

	public function formularioRegistroItem()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();

		$itemsLogistica = $this->model->obtenerItemsLogistica();
		foreach ($itemsLogistica as $key => $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
			$data['items'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
			$data['items'][1][$row['value']]['um'][$row['idum']] = $row['um'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}
		$data['items'][0] = array();
		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Item';
		$result['data']['html'] = $this->load->view("modulos/Item/formularioRegistro", $dataParaVista, true);
		$result['data']['itemsLogistica'] = $data['items'];

		echo json_encode($result);
	}

	public function listItemLogistica()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$dataParaVista['datos'] = [];

		$html = $this->load->view("modulos/Item/listItemLogistica", $dataParaVista, true);

		$result['result'] = 1;
		$result['data']['html'] = $html;
		$result['msg']['title'] = 'Lista de Items solicitados a Logística';
		$result['data']['width'] = '75%';

		echo json_encode($result);
	}

	public function formularioRegistroItemLogistica()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();

		$itemsLogistica = $this->model->obtenerItemsLogistica();
		foreach ($itemsLogistica as $key => $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
			$data['items'][1][$row['value']]['idum'][$row['idum']] = $row['idum'];
			$data['items'][1][$row['value']]['um'][$row['idum']] = $row['um'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}
		$data['items'][0] = array();
		$result['data']['existe'] = 0;

		$result['result'] = 1;
		$result['msg']['title'] = 'Registrar Item Logistica';
		$result['data']['html'] = $this->load->view("modulos/Item/formularioRegistroItemLogistica", $dataParaVista, true);
		$result['data']['itemsLogistica'] = $data['items'];

		echo json_encode($result);
	}

	public function formularioActualizacionItem()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$dataParaVista = [];

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->model->obtenerUnidadMedida();
		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('nombre')->get_where('compras.tipoPresupuesto', ['informacionDeProducto' => 1, 'estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalle'] = $this->db->select('idTipoPresupuesto idDependiente, idTipoPresupuestoDetalle id, nombre value')->order_by('nombre')->get_where('compras.tipoPresupuestoDetalle', ['estado' => 1])->result_array();

		$itemCentroCosto = $this->db->where('idItem', $post['idItem'])->where('estado', 1)->get('compras.itemCentroCosto')->result_array();
		foreach ($itemCentroCosto as $kicc => $vicc) {
			$dataParaVista['itemCC'][$vicc['idCentroCosto']] = $vicc['idCentroCosto'];
		}
		$itemsLogistica = $this->model->obtenerItemsLogistica();

		foreach ($itemsLogistica as $key => $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
			$data['items'][1][$row['value']]['idum'] = $row['idum'];
			$data['items'][1][$row['value']]['um'] = $row['um'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}

		$data['items'][0] = array();
		$result['data']['existe'] = 0;

		$dataParaVista['informacionItem'] = $this->model->obtenerInformacionItems($post)['query']->row_array();
		$dataParaVista['imagenItem'] = $this->model->obtenerItemImagenes($post)->result_array();

		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Item';

		$result['data']['html'] = $this->load->view("modulos/Item/formularioActualizacion", $dataParaVista, true);

		$result['data']['itemsLogistica'] = $data['items'];
		echo json_encode($result);
	}

	public function viewRegistroItem()
	{
		$result = $this->result;

		$result['nav']['menu_active'] = '131';
		$result['css']['style'] = array(
			'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/css/floating-action-button'
		);
		$result['js']['script'] = array(
			'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
			'assets/libs/handsontable@7.4.2/dist/languages/all',
			'assets/libs/handsontable@7.4.2/dist/moment/moment',
			'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
			'assets/custom/js/core/HTCustom',
			'assets/custom/js/item',
			'assets/custom/js/viewAgregarItem'
		);

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$dataParaVista['unidadMedida'] = $this->model->obtenerUnidadMedida();
		$dataParaVista['informacionItem'] = $this->model->obtenerInformacionItems()['query']->row_array();
		$dataParaVista['cuenta'] = $this->mCotizacion->obtenerCuenta()['query']->result_array();
		$dataParaVista['cuentaCentroCosto'] = $this->mCotizacion->obtenerCuentaCentroCosto(['estadoCentroCosto' => true])['query']->result_array();

		$dataParaVista['tipoPresupuesto'] = $this->db->order_by('nombre')->get_where('compras.tipoPresupuesto', ['informacionDeProducto' => 1, 'estado' => 1])->result_array();
		$dataParaVista['tipoPresupuestoDetalle'] = $this->db->select('idTipoPresupuesto idDependiente, idTipoPresupuestoDetalle id, nombre value')->order_by('nombre')->get_where('compras.tipoPresupuestoDetalle', ['estado' => 1])->result_array();
		$itemsLogistica = $this->model->obtenerItemsLogistica();
		foreach ($itemsLogistica as $row) {
			$data['items'][1][$row['value']]['value'] = $row['value'];
			$data['items'][1][$row['value']]['label'] = $row['label'];
			$data['items'][1][$row['value']]['idum'] = $row['idum'];
			$data['items'][1][$row['value']]['um'] = $row['um'];
		}
		foreach ($data['items'] as $k => $r) {
			$data['items'][$k] = array_values($data['items'][$k]);
		}
		$data['items'][0] = array();
		$dataParaVista['itemsLogistica'] = $data['items'][1];

		$result['single'] = true;
		$result['result'] = 1;
		$result['msg']['title'] = 'Actualizar Item';

		$result['data'] = $dataParaVista;
		$result['view'] = 'modulos/Item/viewRegistroItem';
		$this->view($result);
	}

	public function registrarItem()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$post['nombre'] = checkAndConvertToArray($post['nombre']);
		$post['caracteristicas'] = checkAndConvertToArray($post['caracteristicas']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['idItemLogistica'] = checkAndConvertToArray($post['idItemLogistica']);
		$post['unidadMedida'] = checkAndConvertToArray($post['unidadMedida']);
		$post['cuenta'] = checkAndConvertToArray($post['cuenta']);
		$post['centroCosto'] = isset($post['centroCosto']) ? checkAndConvertToArray($post['centroCosto']) : [];
		$post['flagPacking'] = checkAndConvertToArray($post['flagPacking']);
		$post['flagParaPresupuesto'] = checkAndConvertToArray($post['flagParaPresupuesto']);
		$post['tipoPresupuesto'] = checkAndConvertToArray($post['tipoPresupuesto']);
		$post['tipoPresupuestoDetalle'] = checkAndConvertToArray($post['tipoPresupuestoDetalle']);

		$idMarca = NULL;
		if (!is_numeric($post['marca'])) {
			$whereMarca = [];
			$whereMarca[] = [
				'estado' => 1
			];
			$tablaMarcas = 'compras.itemMarca';

			$Marcas = $this->model->getWhereJoinMultiple($tablaMarcas, $whereMarca)->result_array();
			$dataMarca = [];
			foreach ($Marcas as $Marca) {
				$dataMarca[$Marca['nombre']] = $Marca['idItemMarca'];
			}
			if (empty($dataMarca[$post['marca']])) {
				$insertMarca = [
					'nombre' => $post['marca'],
					'estado' => true,
				];
				$insertMarca = $this->model->insertar(['tabla' => $tablaMarcas, 'insert' => $insertMarca]);
				$idMarca = $insertMarca['id'];
			} else {
				$idMarca = $dataMarca[$post['marca']];
			}
		} else {
			$idMarca = $post['marca'];
		}

		$idItemCategoria = NULL;
		if (!is_numeric($post['categoria'])) {
			$whereCategoria = [];
			$whereCategoria[] = [
				'estado' => 1
			];
			$tablaCategorias = 'compras.itemCategoria';

			$Categorias = $this->model->getWhereJoinMultiple($tablaCategorias, $whereCategoria)->result_array();
			$dataCategoria = [];
			foreach ($Categorias as $Categoria) {
				$dataCategoria[$Categoria['nombre']] = $Categoria['idItemCategoria'];
			}

			if (empty($dataCategoria[$post['categoria']])) {
				$insertCategoria = [
					'nombre' => $post['categoria'],
					'estado' => true,
				];
				$insertCategoria = $this->model->insertar(['tabla' => $tablaCategorias, 'insert' => $insertCategoria]);
				$idItemCategoria = $insertCategoria['id'];
			} else {
				$idItemCategoria = $dataCategoria[$post['categoria']];
			}
		} else {
			$idItemCategoria = $post['categoria'];
		}

		$idItemSubcategoria = NULL;
		if (!is_numeric($post['subcategoria'])) {
			$whereSubcategoria = [];
			$whereSubcategoria[] = [
				'estado' => 1
			];
			$tablaSubcategorias = 'compras.itemSubCategoria';

			$subcategorias = $this->model->getWhereJoinMultiple($tablaSubcategorias, $whereSubcategoria)->result_array();
			$dataSubcategoria = [];
			foreach ($subcategorias as $subcategoria) {
				$dataSubcategoria[$subcategoria['nombre']] = $subcategoria['idItemSubCategoria'];
			}
			if (empty($dataSubcategoria[$post['subcategoria']])) {
				$insertSubCategoria = [
					'nombre' => $post['subcategoria'],
					'estado' => true,
				];
				$insertSubCategoria = $this->model->insertar(['tabla' => $tablaSubcategorias, 'insert' => $insertSubCategoria]);
				$idItemSubcategoria = $insertSubCategoria['id'];
			} else {
				$idItemSubcategoria = $dataSubcategoria[$post['subcategoria']];
			}
		} else {
			$idItemSubcategoria = $post['subcategoria'];
		}

		$data = [];

		// foreach que agarra los indices, para poder guardar el array generado del post
		foreach ($post['nombre'] as $k => $r) {
			// En caso el tipoPresupuestoDetalle no se encuentre registrado.
			$idTipoPresupuestoDetalle = !empty($post['tipoPresupuestoDetalle'][$k]) ? $post['tipoPresupuestoDetalle'][$k] : NULL;
			if (!empty($post['tipoPresupuesto'][$k]) && empty($post['tipoPresupuestoDetalle'][$k])) {
				$insertTPD = [
					'idTipoPresupuesto' => $post['tipoPresupuesto'][$k],
					'nombre' => trim($r),
					'split' => '1',
					'precioUnitario' => '0',
					'frecuencia' => '1',
				];
				$this->db->insert('compras.tipoPresupuestoDetalle', $insertTPD);
				$idTipoPresupuestoDetalle = $this->db->insert_id();
			}

			$data['insert'] = [
				'nombre' => trim($r),
				'caracteristicas' => $post['caracteristicas'][$k],
				'idItemTipo' => $post['tipo'][$k],
				'idItemMarca' => $idMarca,
				'idItemCategoria' => $idItemCategoria,
				'idItemSubCategoria' => $idItemSubcategoria,
				'idItemLogistica' => $post['idItemLogistica'][$k],
				'idUnidadMedida' => $post['unidadMedida'][$k],
				'idCuenta' => $post['cuenta'][$k],
				'flagPacking' => $post['flagPacking'][$k],
				'flagParaPresupuesto' => $post['flagParaPresupuesto'][$k],
				'idTipoPresupuesto' => !empty($post['tipoPresupuesto'][$k]) ? $post['tipoPresupuesto'][$k] : NULL,
				'idTipoPresupuestoDetalle' => $idTipoPresupuestoDetalle
			];

			$validacionExistencia = $this->model->validarExistenciaItem($data['insert']);

			if (!empty($validacionExistencia['query']->row_array())) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = getMensajeGestion('registroRepetido');
				goto respuesta;
			}
			$data['tabla'] = 'compras.item';

			$insert = $this->model->insertarItem($data);

			// Imagen
			if (!empty($post["file-name[$k]"])) {
				$data['archivos_arreglo'][$k] = getDataRefactorizada([
					'base64' => $post["file-item[$k]"],
					'type' => $post["file-type[$k]"],
					'name' => $post["file-name[$k]"],
				]);
				foreach ($data['archivos_arreglo'][$k] as $key => $archivo) {
					$data['archivos'][$k][] = [
						'idItem' => $insert['id'],
						'base64' => $archivo['base64'],
						'type' => $archivo['type'],
						'name' => $archivo['name'],
						'carpeta' => 'item',
						'nombreUnico' => uniqid()
					];
				}
			}

			// Centro Costo
			$insertCC = [];
			if (!empty($post['centroCosto'])) {
				foreach ($post['centroCosto'] as $kcc => $vcc) {
					$insertCC[] = [
						'idItem' => $insert['id'],
						'idCuenta' => $post['cuenta'][$k],
						'idCentroCosto' => $vcc,
					];
				}
				if (!empty($insertCC)) {
					$this->db->insert_batch('compras.itemCentroCosto', $insertCC);
				}
			}
		}

		$insertDetalle = true;
		if (!empty($dataDetallle['insert'])) {
			$insertDetalle = $this->model->insertarMasivo('compras.itemDetalle', $dataDetallle['insert']);
		}

		$insertImagen['estado'] = true;

		if (!empty($data['archivos'])) {
			$insertImagen = $this->model->insertarItemImage($data);
		}
		//foreach
		if (!$insert['estado'] || !$insertImagen['estado'] || !$insertDetalle) {
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
	public function registrarItemLogistica()
	{
		$this->db->trans_start();
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		if (empty($post['nombre'])) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
			goto respuesta;
		}

		$art = $this->db->where('nombre', $post['nombre'])->get('visualImpact.logistica.articulo')->result_array();
		if (!empty($art)) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$art = $this->db->where('nombre', $post['nombre'])->get('compras.itemLogistica')->result_array();
		if (!empty($art)) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$this->db->insert('compras.itemLogistica', ['nombre' => $post['nombre']]);
		$id_insert = $this->db->insert_id();

		$this->db->trans_complete();

		$cfg['to'] = ['eder.alata@visualimpact.com.pe'];
		$cfg['asunto'] = 'IMPACT BUSSINESS - SOLICITUD DE PESO PARA LA COTIZACIÓN';
		$html = $this->load->view("email/indicarPeso", [], true);

		$cfg['contenido'] = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Item/DetallarPeso/' . $id_insert], true);
		$this->sendEmail($cfg);

		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');

		respuesta:
		echo json_encode($result);
	}

	public function DetallarPeso($id)
	{
		$config['single'] = true;
		// AGREGAR VALIDACION PARA SOLO MOSTRAR LOS PENDIENTES.
		$config['js']['script'] = array('assets/custom/js/registroPesos');
		$config['data']['item'] = $this->db->where('idItemLogistica', $id)->get('compras.itemLogistica')->row_array();

		$config['view'] = 'modulos/Item/cargarPeso';

		$this->view($config);
	}
	public function actualizarItem()
	{

		$this->db->trans_start();

		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		// En caso el tipoPresupuestoDetalle no se encuentre registrado.
		$idTipoPresupuestoDetalle = !empty($post['tipoPresupuestoDetalle']) ? $post['tipoPresupuestoDetalle'] : NULL;
		if (!empty($post['tipoPresupuesto']) && empty($post['tipoPresupuestoDetalle'])) {
			$insertTPD = [
				'idTipoPresupuesto' => $post['tipoPresupuesto'],
				'nombre' => trim($post['nombre']),
				'split' => '1',
				'precioUnitario' => '0',
				'frecuencia' => '1',
			];
			$this->db->insert('compras.tipoPresupuestoDetalle', $insertTPD);
			$idTipoPresupuestoDetalle = $this->db->insert_id();
		}

		$data['update'] = [
			'idItem' => $post['idItem'],
			'nombre' => trim($post['nombre']),
			'caracteristicas' => $post['caracteristicas'],
			'idItemTipo' => $post['tipo'],
			'idItemMarca' => $post['marca'],
			'idItemCategoria' => $post['categoria'],
			'idItemSubCategoria' => $post['subcategoria'],
			'idItemLogistica' => $post['idItemLogistica'],
			'idUnidadMedida' => $post['unidadMedida'],
			'idCuenta' => $post['cuenta'],
			'flagPacking' => $post['flagPacking'],
			'flagParaPresupuesto' => $post['flagParaPresupuesto'],
			'idTipoPresupuesto' => !empty($post['tipoPresupuesto']) ? $post['tipoPresupuesto'] : NULL,
			'idTipoPresupuestoDetalle' => $idTipoPresupuestoDetalle,
		];

		$post['centroCosto'] = isset($post['centroCosto']) ? checkAndConvertToArray($post['centroCosto']) : [];

		$validacionExistencia = $this->model->validarExistenciaItem($data['update']);
		unset($data['update']['idItem']);

		if (!empty($validacionExistencia['query']->row_array())) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroRepetido');
			goto respuesta;
		}

		$data['tabla'] = 'compras.item';
		$data['where'] = [
			'idItem' => $post['idItem']
		];

		$insert = $this->model->actualizarItem($data);
		// itemCentroCosto
		$this->db->update('compras.itemCentroCosto', ['estado' => 0], ['idItem' => $post['idItem']]);

		foreach ($post['centroCosto'] as $kc => $vc) {
			$d = $this->db->where('idItem', $post['idItem'])->where('idCentroCosto', $vc)->get('compras.itemCentroCosto')->row_array();
			if (!empty($d)) {
				$this->db->update('compras.itemCentroCosto', ['estado' => 1], ['idItemCentroCosto' => $d['idItemCentroCosto']]);
			} else {
				$this->db->insert('compras.itemCentroCosto', ['idItem' => $post['idItem'], 'idCuenta' => $post['cuenta'], 'idCentroCosto' => $vc]);
			}
		}
		// Fin: itemCentroCosto
		$data = [];

		if (isset($post['idImagenAnularItem'])) {
			foreach ($post['idImagenAnularItem'] as $key => $row) {
				$this->db->update('compras.itemImagen', ['estado' => 0], ['idItemImagen' => $row]);
			}
		}
		if (isset($post['base64Adjunto'])) {
			foreach ($post['base64Adjunto'] as $key => $row) {
				$archivo = [
					'idItem' => $post['idItem'],
					'base64' => $row,
					'name' => $post['nameAdjunto'][$key],
					'type' => $post['typeAdjunto'][$key],
					'carpeta' => 'item',
					'nombreUnico' => uniqid()
				];
				$archivoName = $this->saveFileWasabi($archivo);
				$tipoArchivo = explode('/', $archivo['type']);

				$insertArchivos = [];
				$insertArchivos = [
					'idItem' => $archivo['idItem'],
					'idTipoArchivo' => TIPO_IMAGEN,
					'nombre_inicial' => $archivo['name'],
					'nombre_archivo' => $archivoName,
					'nombre_unico' => $archivo['nombreUnico'],
					'extension' => $tipoArchivo[1],
					'estado' => true
				];
				$this->db->insert('compras.itemImagen', $insertArchivos);
			}
		}

		if (!$insert['estado']) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();

		respuesta:
		echo json_encode($result);
	}
	public function guardarPesoItemLogistica()
	{
		$post = json_decode($this->input->post('data'));
		if (empty($post->{'peso'})) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroConDatosInvalidos');
			goto respuesta;
		}

		$this->db->update('compras.itemLogistica', ['peso' => $post->{'peso'}], ['idItemLogistica' => $post->{'idItemLogistica'}]);

		$result['result'] = 1;
		$result['msg']['title'] = 'Ok';

		respuesta:
		echo json_encode($result);
	}
	public function descargarTarifarioPDF()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		// $post = json_decode($this->input->post('data'), true);
		// $oper = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])['query']->result_array();
		// $dataParaVista['dataOper'] = $oper[0];
		// $ids = [];
		// foreach ($oper as $v) {
		// 	$ids[] = $v['idCotizacion'];
		// 	$config['data']['oper'][$v['idOper']] = $v;
		// }

		// $idCotizacion = implode(",", $ids);
		// $dataParaVista['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
		// $dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion' => $idCotizacion, 'cotizacionInterna' => false])['query']->result_array();

		$dataParaVista['itemTarifario'] = $this->model->obtenerTarifario()->result_array();

		require APPPATH . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
			'orientation' => 'L',
			'autoMarginPadding' => 0,
			'bleedMargin' => 0,
			'crossMarkMargin' => 0,
			'cropMarkMargin' => 0,
			'nonPrintMargin' => 0,
			'margBuffer' => 0,
			'collapseBlockMargins' => false,
		]);

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'ITEM TARIFARIO'], true);
		$contenido['footer'] = ''; //$this->load->view("modulos/Cotizacion/pdf/footer", array(), true);

		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Item/itemTarifarioPdf", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("prueba.pdf", \Mpdf\Output\Destination::DOWNLOAD);

		return true;
	}
	public function descargarListaDeItem()
	{
		require_once('../mpdf/mpdf.php');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$dataParaVista['items'] = $this->db->where('estado', 1)->get('compras.item')->result_array();

		require APPPATH . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'setAutoTopMargin' => 'stretch',
			'orientation' => 'L',
			'autoMarginPadding' => 0,
			'bleedMargin' => 0,
			'crossMarkMargin' => 0,
			'cropMarkMargin' => 0,
			'nonPrintMargin' => 0,
			'margBuffer' => 0,
			'collapseBlockMargins' => false,
		]);

		$contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", ['title' => 'ITEM TARIFARIO'], true);

		$contenido['style'] = $this->load->view("modulos/Cotizacion/pdf/oper_style", [], true);
		$contenido['body'] = $this->load->view("modulos/Item/listaItemPdf", $dataParaVista, true);

		$mpdf->SetHTMLHeader($contenido['header']);
		$mpdf->SetHTMLFooter($contenido['footer']);
		$mpdf->AddPage();
		$mpdf->WriteHTML($contenido['style']);
		$mpdf->WriteHTML($contenido['body']);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		// $mpdf->Output('OPER.pdf', 'D');
		$mpdf->Output("prueba.pdf", \Mpdf\Output\Destination::DOWNLOAD);

		return true;
	}
	public function actualizarEstadoItem()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];

		$data['update'] = [
			'estado' => ($post['estado'] == 1) ? 0 : 1
		];

		$data['tabla'] = 'compras.item';
		$data['where'] = [
			'idItem' => $post['idItem']
		];

		$update = $this->model->actualizarItem($data);
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

	public function getFormCargaMasivaItemHT()
	{

		$result = $this->result;
		$result['msg']['title'] = 'Lista items';

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$dataParaVista['logisticaItem'] = $this->model->obtenerItemsLogistica();

		$tipoItem = refactorizarDataHT(["data" => $dataParaVista['tipoItem'], "value" => "value"]);
		$marcaItem = refactorizarDataHT(["data" => $dataParaVista['marcaItem'], "value" => "value"]);
		$categoriaItem = refactorizarDataHT(["data" => $dataParaVista['categoriaItem'], "value" => "value"]);
		$subcategoriaItem = refactorizarDataHT(["data" => $dataParaVista['subcategoriaItem'], "value" => "value"]);
		$logisticaItem = refactorizarDataHT(['data' => $dataParaVista['logisticaItem'], "value" => "label"]);

		$HT[0] = [
			'nombre' => 'Item',
			'data' => [
				[
					'tipo' => null,
					'marca' => null,
					'categoria' => null,
					'subcategoria' => null,
					'item' => null,
					'caracteristicas' => null,
					'logistica' => null,
					'talla' => null,
					'tela' => null,
					'color' => null,
					'monto' => null,
				]
			],
			'headers' => [
				'TIPO (*)',
				'MARCA (*)',
				'CATEGORIA (*)',
				'SUBCATEGORIA (*)',
				'ITEM (*)',
				'CARACTERISTICAS (*)',
				'EQUIVALENTE EN LOGISTICA',
				'TALLA',
				'TELA',
				'COLOR',
				'MONTO'
			],
			'columns' => [
				['data' => 'tipo', 'type' => 'myDropdown', 'placeholder' => 'tipo', 'width' => 200, 'source' => $tipoItem],
				['data' => 'marca', 'type' => 'myDropdown', 'placeholder' => 'marca', 'width' => 200, 'source' => $marcaItem],
				['data' => 'categoria', 'type' => 'myDropdown', 'placeholder' => 'categoria', 'width' => 200, 'source' => $categoriaItem],
				['data' => 'subcategoria', 'type' => 'myDropdown', 'placeholder' => 'subCategoria', 'width' => 200, 'source' => $subcategoriaItem],
				['data' => 'item', 'type' => 'text', 'placeholder' => 'item', 'width' => 200, 'source'],
				['data' => 'caracteristicas', 'type' => 'text', 'placeholder' => 'caracteristicas', 'width' => 200],
				['data' => 'logistica', 'type' => 'myDropdown', 'placeholder' => 'logistica', 'width' => 600, 'source' => $logisticaItem],
				['data' => 'talla', 'type' => 'text', 'placeholder' => 'talla', 'width' => 200, 'source'],
				['data' => 'tela', 'type' => 'text', 'placeholder' => 'tela', 'width' => 200, 'source'],
				['data' => 'color', 'type' => 'text', 'placeholder' => 'color', 'width' => 200, 'source'],
				['data' => 'monto', 'type' => 'text', 'placeholder' => 'monto', 'width' => 200, 'source'],

			],
			'hideColumns' => [0, 1, 2],
			'colWidths' => 200,
		];

		//MOSTRANDO VISTA
		$dataParaVista['hojas'] = [0 => $HT[0]['nombre']];
		$result['result'] = 1;
		$result['data']['width'] = '95%';
		$result['data']['html'] = $this->load->view("formCargaMasivaGeneral", $dataParaVista, true);
		$result['data']['ht'] = $HT;

		echo json_encode($result);
	}

	public function guardarListaItemHT()
	{

		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);

		$this->db->trans_start();
		$result = $this->result;
		$result['msg']['title'] = "Lista items";

		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$itemTipo = [];
		$itemMarca = [];
		$itemCategoria = [];
		$itemSubCategoria = [];
		$itemLogistica = [];

		$dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
		$dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
		$dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
		$dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
		$dataParaVista['logisticaItem'] = $this->model->obtenerItemsLogistica();

		foreach ($dataParaVista['tipoItem'] as $key => $row) {
			$itemTipo[$row['value']] = $row['id'];
		}

		foreach ($dataParaVista['marcaItem'] as $key => $row) {
			$itemMarca[$row['value']] = $row['id'];
		}

		foreach ($dataParaVista['categoriaItem'] as $key => $row) {
			$itemCategoria[$row['value']] = $row['id'];
		}

		foreach ($dataParaVista['subcategoriaItem'] as $key => $row) {
			$itemSubCategoria[$row['value']] = $row['id'];
		}

		foreach ($dataParaVista['logisticaItem'] as $key => $row) {
			$itemLogistica[$row['label']] = $row['value'];
		}

		array_pop($post['HT'][0]);

		foreach ($post['HT'][0] as $tablaHT) {

			if (
				empty($tablaHT['tipo'] || $tablaHT['marca'] || $tablaHT['categoria'] || $tablaHT['subcategoria'] || $tablaHT['item'] || $tablaHT['caracteristicas'] || $tablaHT['logistica'])
			) {
				$msg = createMessage(['type' => 2, 'message' => 'Complete los campos obligatorios']);
				goto respuesta;
			}

			$idTipo = !empty($itemTipo[$tablaHT['tipo']]) ? $itemTipo[$tablaHT['tipo']] : NULL;
			$idMarca = !empty($itemMarca[$tablaHT['marca']]) ? $itemMarca[$tablaHT['marca']] : NULL;
			$idCategoria = !empty($itemCategoria[$tablaHT['categoria']]) ? $itemCategoria[$tablaHT['categoria']] : NULL;
			$idSubCategoria = !empty($itemSubCategoria[$tablaHT['subcategoria']]) ? $itemSubCategoria[$tablaHT['subcategoria']] : NULL;
			$idLogistica = !empty($itemLogistica[$tablaHT['logistica']]) ? $itemLogistica[$tablaHT['logistica']] : NULL;

			if (empty($idTipo || $idMarca || $idCategoria || $idSubCategoria)) {
				goto respuesta;
			}
			//validacion de nombres
			//INSERT
			$data['insert'][] = [
				'idItemTipo' => $idTipo,
				'idItemMarca' => $idMarca,
				'idItemCategoria' => $idCategoria,
				'idItemSubCategoria' => $idSubCategoria,
				'nombre' => $tablaHT['item'],
				'caracteristicas' => $tablaHT['caracteristicas'],
				'idItemLogistica' => $idLogistica
			];

			$validacionExistencia = $this->model->validarExistenciaItemMasivo($data['insert']);

			if (!empty($validacionExistencia['query']->row_array())) {
				$result['result'] = 0;
				$result['msg']['title'] = 'Alerta!';
				$result['msg']['content'] = getMensajeGestion('registroRepetido');
				goto respuesta;
			}
		}

		$insertItem = $this->model->insertarMasivo('compras.item', $data['insert']);

		$dataItem['item'] = $this->model->obtenerInformacionItems($post)['query']->result_array();

		$items = [];

		foreach ($dataItem['item'] as $key => $row) {
			$items[$row['item']] = $row['idItem'];
		}

		foreach ($post['HT'][0] as $tablaHTDetalle) {

			$idItem = !empty($items[$tablaHTDetalle['item']]) ? $items[$tablaHTDetalle['item']] : NULL;

			if (empty($idItem)) {
				continue;
			}

			$dataDetalle['insert'][] = [
				'idItem' => $idItem,
				'fechaIni' => getActualDateTime(),
				'talla' => !empty($tablaHTDetalle['talla']) ? $tablaHTDetalle['talla'] : NULL,
				'tela' => !empty($tablaHTDetalle['tela']) ? $tablaHTDetalle['tela'] : NULL,
				'color' => !empty($tablaHTDetalle['color']) ? $tablaHTDetalle['color'] : NULL,
				'monto' => !empty($tablaHTDetalle['monto']) ? $tablaHTDetalle['monto'] : NULL
			];
		}

		$itemDetalle = $this->model->insertarMasivo('compras.itemDetalle', $dataDetalle['insert']);

		if (!$itemDetalle || !$insertItem) {
			$result['result'] = 0;
			$result['msg']['title'] = 'Alerta!';
			$result['msg']['content'] = getMensajeGestion('registroErroneo');
		} else {
			$result['result'] = 1;
			$result['msg']['title'] = 'Hecho!';
			$result['msg']['content'] = getMensajeGestion('registroExitoso');
			$this->db->trans_commit();
		}

		respuesta:
		echo json_encode($result);
	}

	public function descargar_formato_excel()
	{
		require_once '../PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$datos = $this->model->obtenerItemExcel()['query']->result_array();

		/**ESTILOS**/
		$estilo_cabecera =
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E60000')
				),
				'font' => array(
					'color' => array('rgb' => 'ffffff'),
					'size' => 11,
					'name' => 'Calibri'
				)
			);
		$estilo_titulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font' => [
				'size' => 13,
				'name' => 'Calibri'
			]
		];
		$estilo_subtitulo = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font' => [
				'size' => 11,
				'name' => 'Calibri'
			]
		];
		$estilo_data['left'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font' => [
				'name' => 'Calibri'
			]
		];
		$estilo_data['center'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font' => [
				'name' => 'Calibri'
			]
		];
		$estilo_data['right'] = [
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			],
			'fill' =>	[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			],
			'font' => [
				'name' => 'Calibri'
			]
		];
		/**FIN ESTILOS**/

		$objPHPExcel->getActiveSheet()->getStyle('B1:E1')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

		$objPHPExcel->getProperties()
			->setCreator("Visual Impact")
			->setLastModifiedBy("Visual Impact")
			->setTitle("FORMATO")
			->setSubject("FORMATO")
			->setDescription("Visual Impact")
			->setKeywords("usuarios phpexcel")
			->setCategory("FORMATO");

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B1', '')
			->setCellValue('C1', 'ITEM')
			->setCellValue('D1', 'TIPO ITEM')
			->setCellValue('E1', 'CUENTA');
		$nIni = 2;
		$objPHPExcel->getActiveSheet()->setTitle('FORMATO');

		$objPHPExcel->getActiveSheet()->getStyle("B1:E1")->applyFromArray($estilo_titulo)->getFont()->setBold(true);

		foreach ($datos as $k => $v) {
			$objPHPExcel->getActiveSheet()->getRowDimension($nIni)->setRowHeight(120);
			$url = RUTA_WASABI . 'item/' . $v['nombre_archivo'];

			if ($v['extension'] == 'jpeg') {
				$imageUrl = imagecreatefromjpeg($url);
			} else {
				$imageUrl = imagecreatefrompng($url);
			}

			if($v['nombre_archivo'] != null) {
				$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
				$objDrawing->setName('Sample image');
				$objDrawing->setDescription('TEST');
				$objDrawing->setImageResource($imageUrl);
				if ($v['extension'] == 'jpeg') {
					$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
				} else {
					$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
				}
				$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
				$objDrawing->setHeight(80);
				$objDrawing->setwidth(80);
				$objDrawing->setOffsetX(8);
				$objDrawing->setOffsetY(8);
				$objDrawing->setCoordinates('B' . $nIni);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			}

			$objPHPExcel->getActiveSheet()
				->setCellValue('C' . $nIni, $v['item'])
				->setCellValue('D' . $nIni, $v['tipoItem'])
				->setCellValue('E' . $nIni, $v['cuenta']);

			$nIni++;
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formato.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	public function formularioFotosItem()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);

		$data = [];
		$data['idItem'] = $post['idItem'];

		$dataParaVista = [];
		$dataParaVista['itemFotos'] = $this->model->obtenerItemImagenes($data)->result_array();;

		$result['result'] = 1;
		$result['msg']['title'] = 'Fotos de Items';
		$result['data']['html'] = $this->load->view("modulos/Item/formularioFotos", $dataParaVista, true);

		echo json_encode($result);
	}
}
