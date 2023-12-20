<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ProveedorServicio extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ProveedorServicio', 'model');
		$this->load->model('M_FormularioProveedor', 'mFormularioProveedor');
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

		$data1 = $this->model->obtenerDatosReporte($where)->result_array();
		$data2 = $this->model->obtenerDatosReporte1($where)->result_array();

		$data = array_merge($data1, $data2);
		$data = ordenarArrayPorColumna($data, 'fechaEmision', SORT_DESC);

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
				//$data[$k]['adjuntoFechaEjecucion'] = $this->db->get_where('compras.cotizacionDetalleProveedorFechaEjecucion', ['idCotizacionDetalleProveedor' => $v['idCotizacionDetalleProveedor']])->result_array();
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
			//var_dump(json_encode($v));
			//exit;
			if (!empty($v['idOrdenCompra'])) {
				$data[$k]['status'] = 'Aprobado';
				// Se consulta los tipos de Item, considerando que solo se requiere Validación de Arte para SERVICIO (Mantenimiento), Textiles e Impresiones.
				// TODO falta generar tipo de item "Impresiones"
				$listDetalleCotProv = $this->db->get_where('orden.ordenCompraDetalle', ['idOrdenCompra' => $v['idOrdenCompra']])->result_array();
				foreach ($listDetalleCotProv as $vt) {
					$it = $this->db->get_where('orden.ordenCompraDetalle', ['idOrdenCompra' => $vt['idOrdenCompra'], 'estado' => 1])->row_array()['idTipo'];

					if (
						$it == COD_SERVICIO['id'] || $it == COD_TEXTILES['id']
						|| $it == COD_ARTICULO['id']
					) {
						$data[$k]['requiereValidacion'] = '1';
						$data[$k]['mostrarValidacion'] = '1';
						$data[$k]['solicitarFecha'] = '0';
					}
				}

				// Se consulta si tiene "Validación de Arte" cargado aprobados.
				$va = $this->db->group_start()->where('flagRevisado', 0)
					->or_where('flagAprobado', 1)->group_end()
					->where('idProveedor', $v['idProveedor'])
					->where('idOrdenCompra', $v['idOrdenCompra'])
					->where('flagOcLibre', $v['flagOcLibre'])
					->where('estado', 1)->get('sustento.validacionArte')->result_array();
				if (!empty($va)) {
					$data[$k]['mostrarValidacion'] = '0';
				}

				// Se compara el Total de Artes Cargados con el Total de Artes Aprobados.
				$w = ['idProveedor' => $v['idProveedor'], 'idOrdenCompra' => $v['idOrdenCompra'], 'flagOcLibre' => $v['flagOcLibre'], 'estado' => 1];
				$artesCargados = $this->db->get_where('sustento.validacionArte', $w)->result_array();
				$w['flagRevisado'] = 1;
				$w['flagAprobado'] = 1;
				$artesAprobados = $this->db->get_where('sustento.validacionArte', $w)->result_array();

				if (!empty($artesAprobados)) {
					if (count($artesAprobados) == count($artesCargados)) {
						$data[$k]['solicitarFecha'] = '1';
					}
				}

				// Si se solicita fecha, validar si la información fue cargada o no.
				if ($data[$k]['solicitarFecha'] == '1') {
					$fechaE = ['idOrdenCompra' => $v['idOrdenCompra'],
					'idProveedor' => $v['idProveedor'],
					'flagOcLibre' => $v['flagOcLibre'], 'estado' => '1'];

					if($v['flagOcLibre'] == 0) {
						$fechaE['idCotizacion'] = $v['idCotizacion'];
					}

					$fechaEjecCargado = $this->db->get_where('sustento.fechaEjecucion', $fechaE)->result_array();
					
					if (!empty($fechaEjecCargado)) {
						$data[$k]['flagFechaRegistro'] = '1';
						$data[$k]['fechaInicio'] = $fechaEjecCargado[0]['fechaInicial'];
						$data[$k]['fechaFinal'] = $fechaEjecCargado[0]['fechaFinal'];
					}
				}
			} else {
				$data[$k]['status'] = 'Aprobado';
				$fechaEjecCargado = $this->db->get_where('sustento.fechaEjecucion', ['idOrdenCompra' => $v['idOrdenCompra'], 'estado' => '1'])->result_array();
				if (!empty($fechaEjecCargado)) {
					$data[$k]['flagFechaRegistro'] = '1';
					$data[$k]['fechaInicio'] = $fechaEjecCargado[0]['fechaInicial'];
					$data[$k]['fechaFinal'] = $fechaEjecCargado[0]['fechaFinal'];
				}
			}

			$data[$k]['ocGen'] = $v['seriado'];

			$sustComp = $this->db->get_where('sustento.sustentoAdjunto', [
				'idOrdenCompra' => $v['idOrdenCompra'], 
				'idProveedor' => $v['idProveedor'], 
				'flagoclibre' => $v['flagOcLibre'], 
				'estado' => '1'])->result_array();
			$data[$k]['sustentoComp'] = $sustComp;

			if (!empty($sustComp)) {
				$data[$k]['flagSustentoServicio'] = '1';
				foreach ($sustComp as $rSC) {
					if ($rSC['flagRevisado'] == '0' || $rSC['flagAprobado'] == '0') {
						$data[$k]['flagSustentoServicio'] = '0';
					}
				}
			}

			$va4 = $this->db->where('estado', '1')
				->where('idOrdenCompra', $v['idOrdenCompra'])
				->where('idProveedor', $v['idProveedor'])
				->where('flagoclibre', $v['flagOcLibre'])
				->get('sustento.comprobante')->result_array();
			foreach ($va4 as $v4) {
				$data[$k]['sustentoC'][$v4['idOrdenCompra']][$v4['idProveedor']] = $v4;
			}
			$accesoDocumento = !empty($proveedor['nroDocumento']) ? base64_encode($proveedor['nroDocumento']) : '';
			$accesoEmail = !empty($proveedor['correoContacto']) ? base64_encode($proveedor['correoContacto']) : '';
			$fechaActual = base64_encode(date('Y-m-d'));
			$accesoCodProveedor = !empty($proveedor['idProveedor']) ? base64_encode($proveedor['idProveedor']) : '';
			$data[$k]['link'] = "?doc={$accesoDocumento}&email={$accesoEmail}&date={$fechaActual}&cod={$accesoCodProveedor}";
		}




		$html = getMensajeGestion('noRegistros');

		if (!empty($data)) {
			$dataParaVista['data'] = [];
			foreach ($data as $k => $v) {
				$v['flagMostrarExcel'] = !empty($this->db->select('*')
					->from('compras.cotizacionDetalle cd')
					->join(
						'compras.cotizacionDetalleProveedorDetalle cdpd',
						'cd.idCotizacionDetalle = cdpd.idCotizacionDetalle',
						'INNER'
					)
					->where(['cd.idProveedor' => $v['idProveedor']])
					->where(['cd.idCotizacion' => $v['idCotizacion']])
					->where(['cd.estado' => 1])
					->get()->result_array());
				$dataParaVista['data'][] = $v;
			}
			$html = $this->load->view("modulos/ProveedorServicio/reporte", $dataParaVista, true);
		}



		$result['result'] = 1;
		$result['data']['views']['idContentProveedorServicio']['datatable'] = 'tb-proveedorServicio';
		$result['data']['views']['idContentProveedorServicio']['html'] = $html;
		echo json_encode($result);
	}
}
