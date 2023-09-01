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

			$data[$k]['ocGen'] = $this->mFormularioProveedor->getDistinctOC(['idCotizacion' => $v['idCotizacion'], 'idProveedor' => $v['idProveedor']])->result_array();

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
			if (!empty($post['estado'])) {
				$dataParaVista['data'] = [];
				foreach ($data as $k => $v) {
					if ($v['status'] == $post['estado']) {
						$dataParaVista['data'][] = $v;
					}
				}
			} else {
				$dataParaVista['data'] = $data;
			}
			$html = $this->load->view("modulos/ProveedorServicio/reporte", $dataParaVista, true);
		}



		$result['result'] = 1;
		$result['data']['views']['idContentProveedorServicio']['datatable'] = 'tb-proveedorServicio';
		$result['data']['views']['idContentProveedorServicio']['html'] = $html;
		echo json_encode($result);
	}
}
