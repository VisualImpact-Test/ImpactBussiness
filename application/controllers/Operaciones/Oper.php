<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Oper extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Oper', 'model');
    $this->load->model('M_Cotizacion', 'model_cotizacion');
    $this->load->model('M_Item', 'model_item');
    // $this->load->model('M_control', 'model_control');
    // $this->load->model('M_proveedor','model_proveedor');
    // $this->load->model('M_FormularioProveedor','model_formulario_proveedor');
    // $this->load->model('M_login','model_login');
    // header('Access-Control-Allow-Origin: *');
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
      // 'assets/libs/datatables/responsive.bootstrap4.min',
      // 'assets/custom/js/core/datatables-defaults',
      'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
      'assets/libs/handsontable@7.4.2/dist/languages/all',
      'assets/libs/handsontable@7.4.2/dist/moment/moment',
      'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
      'assets/libs/fileDownload/jquery.fileDownload',
      'assets/custom/js/core/HTCustom',
      'assets/custom/js/Operaciones/Oper',
      'assets/custom/js/dataTables.select.min'
    );
    $config['data']['icon'] = 'fas fa-money-check-edit-alt';
    $config['data']['title'] = 'OPERS';
    $config['data']['message'] = 'Lista de OPERs';
    // $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
    // $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
    $config['view'] = 'modulos/Operaciones/Oper/index';
    $this->view($config);
  }

  public function reporteSinCotizacion()
  {
    $result = $this->result;
    $post = json_decode($this->input->post('data'), true);

    $dataParaVista = [];

    $data = $this->model->obtenerInformacionOper($post)->result_array();
    foreach ($data as $key => $row) {
      $dataParaVista[$row['idOper']] = [
        'concepto' => $row['concepto'],
        'requerimiento' => $row['requerimiento'],
        'fechaRequerimiento' => date_change_format($row['fechaRequerimiento']),
        'fechaEntrega' => date_change_format($row['fechaEntrega']),
        'total' => $row['total'],
        'feePorcentaje' => $row['feePorcentaje'],
        'totalFee' => $row['totalFee'],
        'IGVPorcentaje' => $row['IGVPorcentaje'],
        'totalFeeIGV' => $row['totalFeeIGV'],
        'observacion' => $row['observacion'],
        'estado' => $row['estado']
      ];
      $item[$row['idOper']][$row['idItem']] = $row['idItem'];
    }

    foreach ($dataParaVista as $key => $row) {
      $dataParaVista[$key]['item'] = implode(', ', $item[$key]);
    }

    $html = getMensajeGestion('noRegistros');
    if (!empty($dataParaVista)) {
      $html = $this->load->view("modulos/Operaciones/Oper/reporte", ['datos' => $dataParaVista], true);
    }

    $result['result'] = 1;
    $result['data']['views']['idContentOPERSinCotizacion']['datatable'] = 'tb-oper';
    $result['data']['views']['idContentOPERSinCotizacion']['html'] = $html;
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
  
  public function formularioEditarOperSinCotizacion()
  {
    $result = $this->result;
    $idOper = json_decode($this->input->post('data'), true);
    $dataParaVista = [];
    // log_message('error', $this->input->post());
    $dataParaVista['cuenta'] = $this->model->obtenerCuenta()->result_array();
    $dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
    $dataParaVista['item'] = $this->model_cotizacion->obtenerItemServicio();
    $dataParaVista['tipo'] = $this->model->obtenerTipo()->result_array();
    $dataParaVista['itemLogistica'] = $this->model_cotizacion->obtenerItemServicio(['logistica' => true]);
    $dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();

    $dataParaVista['oper'] = $this->model->obtenerInformacionOper(['idOper' => $idOper])->result_array();
    foreach ($dataParaVista['oper'] as $key => $value) {
      $dataParaVista['operSubItem'][$value['idOperDetalle']] = $this->model->obtenerInformacionOperSubItem(['idOperDetalle' => $value['idOperDetalle']])->result_array();
    }
    $result['result'] = 1;
    $result['msg']['title'] = 'Editar Oper';
    $result['data']['html'] = $this->load->view("modulos/Operaciones/Oper/formularioEditar", $dataParaVista, true);

    echo json_encode($result);
  }
  public function formularioRegistroOperSinCotizacion()
  {

    $result = $this->result;
    $post = json_decode($this->input->post('data'), true);

    $dataParaVista = [];

    $dataParaVista['cuenta'] = $this->model->obtenerCuenta()->result_array();
    $dataParaVista['centroCosto'] = $this->model_cotizacion->obtenerCuentaCentroCosto()['query']->result_array();
    $dataParaVista['item'] = $this->model_cotizacion->obtenerItemServicio();
    $dataParaVista['tipo'] = $this->model->obtenerTipo()->result_array();
    $dataParaVista['itemLogistica'] = $this->model_cotizacion->obtenerItemServicio(['logistica' => true]);
    $dataParaVista['tipoServicios'] = $this->model_cotizacion->obtenertipoServicios()['query']->result_array();

    $result['result'] = 1;
    $result['msg']['title'] = 'Registrar Oper';
    $result['data']['html'] = $this->load->view("modulos/Operaciones/Oper/formularioRegistro", $dataParaVista, true);

    echo json_encode($result);
  }

  public function registrarOperSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
    // $post['subItem_monto'][$orden]
		$post['item'] = checkAndConvertToArray($post['item']);
    $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);

    if (isset($post['subItem_monto'])) {
      $post['subItem_monto'] = checkAndConvertToArray($post['subItem_monto']);
      $post['subItem_tipoServ'] = checkAndConvertToArray($post['subItem_tipoServ']);
      $post['subItem_idUm'] = checkAndConvertToArray($post['subItem_idUm']);
      $post['subItem_itemLog'] = checkAndConvertToArray($post['subItem_itemLog']);
      $post['subItem_nombre'] = checkAndConvertToArray($post['subItem_nombre']);
      $post['subItem_talla'] = checkAndConvertToArray($post['subItem_talla']);
      $post['subItem_tela'] = checkAndConvertToArray($post['subItem_tela']);
      $post['subItem_color'] = checkAndConvertToArray($post['subItem_color']);
      $post['subItem_costo'] = checkAndConvertToArray($post['subItem_costo']);
      $post['subItem_cantidad'] = checkAndConvertToArray($post['subItem_cantidad']);
      $post['subItem_cantidadPdv'] = checkAndConvertToArray($post['subItem_cantidadPdv']);
    }

    $insertData = [
      // 'requerimiento' => $post['requerimiento'],
      'fechaEntrega' => $post['fechaEntrega'],
      'fechaRequerimiento' => $post['fechaRequerimiento'],
      'concepto' => $post['concepto'],
      'idcuenta' => $post['cuentaForm'],
      'idCentroCosto' => $post['cuentaCentroCostoForm'],
      'idUsuarioReceptor' => $post['usuarioReceptor'],
      'total' => $post['total'],
      'feePorcentaje' => $post['feePorcentaje'],
      'totalFee' => $post['totalFee'],
      'IGVPorcentaje' => intval($post['igvPorcentaje']) - 100,
      'totalFeeIGV' => $post['totalFeeIGV'],
      'idUsuarioReg' => $this->idUsuario,
      'observacion' => $post['observacion'],
    ];
    $this->db->insert('orden.oper', $insertData);
    $idOper = $this->db->insert_id();
    $this->db->update('orden.oper', ['requerimiento' => 'OPL'.generarCorrelativo($idOper,5)], ['idOper' => $idOper]);
    $insertData = [];
    $insertDataSub = [];
    $orden = 0;
		foreach ( $post['item'] as $key => $value) {
      // En caso el item es nuevo
      $dataInserItem = [];
      if($post['idItemForm'][$key] == '0'){
        $dataInserItem = [
          'nombre' => $post['item'][$key],
          'idItemTipo' => $post['tipo'][$key]
        ];
        $this->db->insert('compras.item', $dataInserItem);
        $post['idItemForm'][$key] = $this->db->insert_id();
      }
      //
			$insertData = [
				'idOper' => $idOper,
				'idItem' => $post['idItemForm'][$key],
				'idTipo' => $post['tipo'][$key],
				'costoUnitario' => $post['costo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costoSubTotal' => number_format($post['costo'][$key] * $post['cantidad'][$key], 2, '.', ''),
				'gap' => $post['gap'][$key],
        'costoSubTotalGap' => $post['precio'][$key]
			];
			$insert = $this->db->insert('orden.operDetalle', $insertData);
			$idOperDet = $this->db->insert_id();
			/////////////////////
			for ($i=0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
        $insertDataSub[] = [
          'idOperDetalle' => $idOperDet,
          'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
          'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
          'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
          'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
          'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
          'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
          'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
          'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
          'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
          'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
          'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
        ];
				$orden++;
			}

		}

    if (!empty($insertDataSub)) {
      $insert = $this->model->insertarMasivo('orden.operDetalleSub', $insertDataSub);
    }


		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');


		echo json_encode($result);
	}

  public function editarOperSinCotizacion()
	{
		$result = $this->result;
		$post = json_decode($this->input->post('data'), true);
		$post['item'] = checkAndConvertToArray($post['item']);
    $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
		$post['tipo'] = checkAndConvertToArray($post['tipo']);
		$post['cantidad'] = checkAndConvertToArray($post['cantidad']);
		$post['cantidadSubItem'] = checkAndConvertToArray($post['cantidadSubItem']);
		$post['costo'] = checkAndConvertToArray($post['costo']);
		$post['gap'] = checkAndConvertToArray($post['gap']);
		$post['precio'] = checkAndConvertToArray($post['precio']);
    if (isset($post['subItem_monto'])) {
      $post['subItem_monto'] = checkAndConvertToArray($post['subItem_monto']);
      $post['subItem_tipoServ'] = checkAndConvertToArray($post['subItem_tipoServ']);
      $post['subItem_idUm'] = checkAndConvertToArray($post['subItem_idUm']);
      $post['subItem_itemLog'] = checkAndConvertToArray($post['subItem_itemLog']);
      $post['subItem_nombre'] = checkAndConvertToArray($post['subItem_nombre']);
      $post['subItem_talla'] = checkAndConvertToArray($post['subItem_talla']);
      $post['subItem_tela'] = checkAndConvertToArray($post['subItem_tela']);
      $post['subItem_color'] = checkAndConvertToArray($post['subItem_color']);
      $post['subItem_costo'] = checkAndConvertToArray($post['subItem_costo']);
      $post['subItem_cantidad'] = checkAndConvertToArray($post['subItem_cantidad']);
      $post['subItem_cantidadPdv'] = checkAndConvertToArray($post['subItem_cantidadPdv']);
    }

    $updateData[0] = [
      'idOper' => $post['idOper'],
      'fechaEntrega' => $post['fechaEntrega'],
      'fechaRequerimiento' => $post['fechaRequerimiento'],
      'concepto' => $post['concepto'],
      'idcuenta' => $post['cuentaForm'],
      'idCentroCosto' => $post['cuentaCentroCostoForm'],
      'idUsuarioReceptor' => $post['usuarioReceptor'],
      'total' => $post['total'],
      'feePorcentaje' => $post['feePorcentaje'],
      'totalFee' => $post['totalFee'],
      'IGVPorcentaje' => intval($post['igvPorcentaje']) - 100,
      'totalFeeIGV' => $post['totalFeeIGV'],
      'idUsuarioReg' => $this->idUsuario,
      'observacion' => $post['observacion'],
    ];
    $rpta = $this->model->actualizarMasivo('orden.oper', $updateData, 'idOper');
    $idOper = $updateData[0]['idOper'];
    $this->db->update('orden.operDetalle', ['estado' => '0'], ['idOper' => $idOper]);

    $insertData = [];
    $insertDataSub = [];
    $orden = 0;
		foreach ( $post['item'] as $key => $value) {
      // En caso el item es nuevo
      $dataInserItem = [];
      if($post['idItemForm'][$key] == '0'){
        $dataInserItem = [
          'nombre' => $post['item'][$key],
          'idItemTipo' => $post['tipo'][$key]
        ];
        $this->db->insert('compras.item', $dataInserItem);
        $post['idItemForm'][$key] = $this->db->insert_id();
      }
      //
			$insertData = [
				'idOper' => $idOper,
				'idItem' => $post['idItemForm'][$key],
				'idTipo' => $post['tipo'][$key],
				'costoUnitario' => $post['costo'][$key],
				'cantidad' => $post['cantidad'][$key],
				'costoSubTotal' => number_format($post['costo'][$key] * $post['cantidad'][$key], 2, '.', ''),
				'gap' => $post['gap'][$key],
        'costoSubTotalGap' => $post['precio'][$key]
			];
			$insert = $this->db->insert('orden.operDetalle', $insertData);
			$idOperDet = $this->db->insert_id();
			/////////////////////
			for ($i=0; $i < intval($post['cantidadSubItem'][$key]); $i++) {
        $insertDataSub[] = [
          'idOperDetalle' => $idOperDet,
          'idTipoServicio' => $post['subItem_tipoServ'][$orden] == '' ? NULL : $post['subItem_tipoServ'][$orden],
          'idUnidadMedida' => $post['subItem_idUm'][$orden] == '' ? NULL : $post['subItem_idUm'][$orden],
          'idItemLogistica' => $post['subItem_itemLog'][$orden] == '' ? NULL : $post['subItem_itemLog'][$orden],
          'nombre' => $post['subItem_nombre'][$orden] == '' ? NULL : $post['subItem_nombre'][$orden],
          'talla' => $post['subItem_talla'][$orden] == '' ? NULL : $post['subItem_talla'][$orden],
          'tela' => $post['subItem_tela'][$orden] == '' ? NULL : $post['subItem_tela'][$orden],
          'color' => $post['subItem_color'][$orden] == '' ? NULL : $post['subItem_color'][$orden],
          'costo' => $post['subItem_costo'][$orden] == '' ? NULL : $post['subItem_costo'][$orden],
          'cantidad' => $post['subItem_cantidad'][$orden] == '' ? NULL : $post['subItem_cantidad'][$orden],
          'cantidadPDV' => $post['subItem_cantidadPdv'][$orden] == '' ? NULL : $post['subItem_cantidadPdv'][$orden],
          'monto' => $post['subItem_monto'][$orden] == '' ? NULL : $post['subItem_monto'][$orden]
        ];
				$orden++;
			}

		}

    if (!empty($insertDataSub)) {
      $insert = $this->model->insertarMasivo('orden.operDetalleSub', $insertDataSub);
    }


		$result['result'] = 1;
		$result['msg']['title'] = 'Hecho!';
		$result['msg']['content'] = getMensajeGestion('registroExitoso');


		echo json_encode($result);
	}

  public function descargarOperSinCotizacion()
  {
    require_once('../mpdf/mpdf.php');
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    $post = json_decode($this->input->post('data'), true);
    $dataParaVista['dataOper'] = $this->model->obtenerInformacionOper(['idOper' => $post['idOper']])->result_array();
    $ids = [];
    foreach ($dataParaVista['dataOper'] as $key => $value) {
      $dataParaVista['dataOper'][$key]['fechaRequerimiento'] = date_change_format($value['fechaRequerimiento']);
      $dataParaVista['dataOper'][$key]['fechaEntrega'] = date_change_format($value['fechaEntrega']);
    }
    // foreach($dataParaVista['dataOper'] as $v){
    //   // $ids[] = $v['idCotizacion'];
    //   $config['data']['oper'][$v['idOper']] = $v;
    // }

    // $idCotizacion = implode(",",$ids);
    // $dataParaVista['cotizaciones'] = $this->model->obtenerInformacionCotizacion(['id' => $idCotizacion])['query']->result_array();
    // $dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=> $idCotizacion,'cotizacionInterna' => false])['query']->result_array();

    require APPPATH . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();

    $contenido['header'] = $this->load->view("modulos/Operaciones/Oper/pdf/header", ['title' => 'REQUERIMIENTO DE BIENES O SERVICIOS LIBRE','codigo'=>'SIG-LOG-FOR-001'], true);
    $contenido['footer'] = $this->load->view("modulos/Operaciones/Oper/pdf/footer", array(), true);

    $contenido['style'] = $this->load->view("modulos/Operaciones/Oper/pdf/oper_style",[],true);
    log_message('error', json_encode($dataParaVista));
    $contenido['body'] = $this->load->view("modulos/Operaciones/Oper/pdf/oper", $dataParaVista,true);

    $mpdf->SetHTMLHeader($contenido['header']);
    $mpdf->SetHTMLFooter($contenido['footer']);
    $mpdf->AddPage();
    $mpdf->WriteHTML($contenido['style']);
    $mpdf->WriteHTML($contenido['body']);

    header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
    $mpdf->Output("OPER.pdf", \Mpdf\Output\Destination::DOWNLOAD);
  }
};
