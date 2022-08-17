<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tarifario/M_Item', 'model');
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
            'assets/custom/js/core/gestion',
            'assets/custom/js/Tarifario/item',
            
            
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Items';
        $config['data']['message'] = 'Lista de Items';
        $config['data']['tipoItem'] = $this->model->obtenerItemTipo()['query']->result_array();
        $config['data']['itemMarca'] = $this->model->obtenerItemMarca()['query']->result_array();
        $config['data']['itemCategoria'] = $this->model->obtenerItemCategoria()['query']->result_array();
        $config['data']['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();
        $config['data']['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();
        $config['view'] = 'modulos/Tarifario/item/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista ['dataTarifario'] = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();
        $Rproveedor = [];
        $item = [];
        $itemProveedor = [];

        foreach ($dataParaVista['dataTarifario'] as $key => $value) {
            $Rproveedor[$value['idProveedor']] = [
                'idProveedor' => $value['idProveedor'],
                'nproveedor' => $value['proveedor']

            ];

            $item[$value['idItem']] = $value;

            $itemProveedor[$value['idItem']][$value['idProveedor']] = $value; 
        }

        $dataParaVista ['dataProveedor'] = $Rproveedor;
        $dataParaVista ['dataItem'] = $item;
        $dataParaVista ['dataItemProveedor'] = $itemProveedor;
 
        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Tarifario/Item/reporte",  $dataParaVista, true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentItem']['datatable'] = 'tb-item';
        $result['data']['views']['idContentItem']['html'] = $html;
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

    public function getFormCargaMasivaTarifario()
	{
		$result = $this->result;
		$result['msg']['title'] = "Carga masiva de tarifario";

		$params=array();
		$params['idUsuario']=$this->session->userdata('idUsuario');

        $proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]] )->result_array();
        $proveedores = refactorizarDataHT(["data" => $proveedores, "value" => "razonSocial" ]);
        $item['item'] = $this->model->obtenerItems();
		
        $itemNombre = refactorizarDataHT(["data" => $item['item'], "value" => "label"]);

		//ARMANDO HANDSONTABLE
		$HT[0] = [
			'nombre' => 'Tarifario',
			'data' => [
                [
				'item' => null,
				'proveedor' => null,
				'costo' => null,
				'fecha' => null,
				'itemActual' => null,
                ]
			],
            'headers' => [
				'ITEM (*)',
				'PROVEEDOR (*)',
				'COSTO (*)',
				'FECHA (*)',
				'ESTE ITEM ES EL ACTUAL (*)',
				

            ],
			'columns' => [
				['data' => 'item', 'type' => 'myDropdown', 'placeholder' => 'item', 'width' => 200, 'source' => $itemNombre],
				['data' => 'proveedor', 'type' => 'myDropdown', 'placeholder' => 'proveedor', 'width' => 200, 'source' => $proveedores],
				['data' => 'costo', 'type' => 'numeric', 'placeholder' => 'costo', 'width' => 200],
				['data' => 'fecha', 'type' => 'myDate', 'placeholder' => 'fecha', 'width' => 200],
				['data' => 'itemActual', 'type' => 'checkbox', 'placeholder' => 'itemActual', 'width' => 200],

			],
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

    public function guardarCargaMasivaTarifario() {

        ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		set_time_limit(0);

        $this->db->trans_start();

        $result = $this->result;
        $result['msg']['title'] = "Carga masiva de tarifario";

        $post = json_decode($this->input->post('data'), true);

        
        $itemProveedores = [];
        $itemNombre = [];

        $proveedores = $this->model->getWhereJoinMultiple('compras.proveedor', [0 => ['idProveedorEstado' => 2]] )->result_array();
        $item['item'] = $this->model->obtenerItems();

        foreach ($proveedores as $key => $row) {
            $itemProveedores[$row['razonSocial']] = $row['idProveedor'];
        }

        foreach ($item['item'] as $key => $row) {
            $itemNombre[$row['label']] = $row['value'];
        }



        foreach ($post['HT'][0] as $tablaHT) {

            if(empty($tablaHT['item'] || $tablaHT['proveedor'] || $tablaHT['costo'] || $tablaHT['fecha'] || $tablaHT['itemActual'])) {
                $msg = createMessage(['type' => 2,'message' => 'Complete los campos obligatorios']);
                continue;
            }

            $proveedoresItem = !empty($itemProveedores[$tablaHT['proveedor']]) ? $itemProveedores[$tablaHT['proveedor']] : NULL;
            $nombreItem = !empty($itemNombre[$tablaHT['item']]) ? $itemNombre[$tablaHT['item']] : NULL;

            if(empty($proveedoresItem || $nombreItem )) {
                goto respuesta;
            }

            $dataTarifario['insert'][] = [
                'idItem' => $nombreItem,
                'idProveedor' => $proveedoresItem,
                'costo' => $tablaHT['costo'],
                'flag_actual' => $tablaHT['itemActual'],
                'fechaVigencia' => $tablaHT['fecha']
                
            ];

        }

        $insertarTarifario = $this->model->insertarMasivo('compras.itemTarifario', $dataTarifario['insert']);


        $dataTarifario ['dataTarifario'] = $this->model->obtenerInformacionItemTarifario($post)['query']->result_array();

        $tarifario = [];

        foreach ($dataTarifario ['dataTarifario'] as $key => $row) {
            $tarifario[$row['idProveedor']][$row['idItem']] = $row['idItemTarifario'];
        }

        foreach ($post['HT'][0] as $tablaHThistorico) {

            if(empty($tablaHThistorico['item'] || $tablaHThistorico['proveedor'] || $tablaHThistorico['costo'] || $tablaHThistorico['fecha'] || $tablaHThistorico['itemActual'])) {
                $msg = createMessage(['type' => 2,'message' => 'Complete los campos obligatorios']);
                continue;
            }

            $proveedoresItem = !empty($itemProveedores[$tablaHThistorico['proveedor']]) ? $itemProveedores[$tablaHThistorico['proveedor']] : NULL;
            $nombreItem = !empty($itemNombre[$tablaHThistorico['item']]) ? $itemNombre[$tablaHThistorico['item']] : NULL;
            $tarifarioId = !empty($tarifario[$proveedoresItem][$nombreItem]) ? $tarifario[$proveedoresItem][$nombreItem] : NULL;

            $dataTarifarioHistorico['insert'][] = [
                'idItemTarifario' => $tarifarioId,
                'fecIni' => getFechaActual(),
                'fecFin' => NULL,
                'costo' => $tablaHThistorico['costo']
            ];
        }

        $insertarTarifarioHistorico = $this->model->insertarMasivo('compras.itemTarifarioHistorico', $dataTarifarioHistorico['insert']);

        if (!$insertarTarifario || !$insertarTarifarioHistorico) {
            respuesta:
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
            $this->db->trans_commit();
        }

        echo json_encode($result);



    }



    //proveedor no repetido
    public function proveedorNoRepetido()
    {
        $result = $this->result;
        $post_1 = json_decode($this->input->post('data'), true);

        $dataParaVista1 = [];
        $dataParaVista1 = $this->model->obtenerProveedorNoRepetido($post_1)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Tarifario/Item/reporte", ['NoRproveedor' => $dataParaVista1], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentItem']['datatable'] = 'tb-item';
        $result['data']['views']['idContentItem']['html'] = $html;
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

    public function formularioRegistroItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $items =  $this->model->obtenerItems();
        foreach ($items as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioRegistro", $dataParaVista, true);
        $result['data']['items'] = $data['items'];

        echo json_encode($result);
    }

    public function formularioActualizacionItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['proveedor'] = $this->model->obtenerProveedor()['query']->result_array();

        $items =  $this->model->obtenerItems();
        foreach ($items as $key => $row) {
            $data['items'][1][$row['value']]['value'] = $row['value'];
            $data['items'][1][$row['value']]['label'] = $row['label'];
        }
        foreach ($data['items'] as $k => $r) {
            $data['items'][$k] = array_values($data['items'][$k]);
        }
        $data['items'][0] = array();
        $result['data']['existe'] = 0;

        $dataParaVista['informacionItem'] = $this->model->obtenerInformacionItemTarifario($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioActualizacion", $dataParaVista, true);
        $result['data']['items'] = $data['items'];

        echo json_encode($result);
    }

    public function formularioHistorialItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['datos'] = $this->model->obtenerInformacionTAHistorico($post)['query']->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Historial Tarifario de Item';
        $result['data']['html'] = $this->load->view("modulos/Tarifario/Item/formularioHistorial", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeItemActual = 0;

        $data['insert'] = [
            'idItem' => $post['idItem'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1,
            'fechaVigencia' => !empty($post['fechaVigencia']) ? $post['fechaVigencia'] : NULL
        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarItemTarifarioActual($data['insert']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['insert']['flag_actual'] = 0;
                $existeItemActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaItemTarifario($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemTarifario';

        $insert = $this->model->insertarItemTarifario($data);
        $data = [];

        $data['insert'] = [
            'idItemTarifario' => $insert['id'],
            'fecIni' => getFechaActual(),
            'fecFin' => NULL,
            'costo' => $post['costo'],
        ];

        $data['tabla'] = 'compras.itemTarifarioHistorico';

        $subInsert = $this->model->insertarItemTarifario($data);

        $data = [];

        if (!$insert['estado'] or !$subInsert['estado']) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }

        if ($existeItemActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idItemTarifario'] = $insert['id'];
            $result['data']['idItem'] = $post['idItem'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $existeItemActual = 0;

        $data['update'] = [
            'idItemTarifario' => $post['idItemTarifario'],

            'idItem' => $post['idItem'],
            'idProveedor' => $post['proveedor'],
            'costo' => $post['costo'],
            'flag_actual' => empty($post['actual']) ? 0 : 1,
            'fechaVigencia' => !empty($post['fechaVigencia']) ? $post['fechaVigencia'] : NULL

        ];

        if (!empty($post['actual'])) {
            $validacionActual = $this->model->validarItemTarifarioActual($data['update']);
            if (!empty($validacionActual['query']->row_array())) {
                $data['update']['flag_actual'] = 0;
                $existeItemActual = 1;
            }
        }

        $validacionExistencia = $this->model->validarExistenciaItemTarifario($data['update']);
        unset($data['update']['idItemTarifario']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $update = $this->model->actualizarItemTarifario($data);
        $data = [];
        $actualizacionHistoricos = true;

        if ($post['costoAnterior'] != $post['costo']) {
            $data['update'] = [
                'fecFin' => getFechaActual(-1),
            ];

            $data['tabla'] = 'compras.itemTarifarioHistorico';
            $data['where'] = [
                'idItemTarifario' => $post['idItemTarifario'],
                'fecFin' => NULL
            ];

            $subUpdate = $this->model->actualizarItemTarifario($data);
            $data = [];

            $data['insert'] = [
                'idItemTarifario' => $post['idItemTarifario'],
                'fecIni' => getFechaActual(),
                'fecFin' => NULL,
                'costo' => $post['costo'],
            ];

            $data['tabla'] = 'compras.itemTarifarioHistorico';

            $subInsert = $this->model->insertarItemTarifario($data);
            $data = [];

            if (!$subUpdate['estado'] && !$subInsert['estado']) {
                $actualizacionHistoricos = false;
            }
        }

        if (!$update['estado'] or !$actualizacionHistoricos) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('registroExitoso');
        }

        if ($existeItemActual == true && $result['result'] == 1) {
            $result['result'] = 2;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('alertaPersonalizada', ['message' => 'Ya existe un item que se encuentra como actual, ¿Deseas reemplazarlo?']);
            $result['data']['idItemTarifario'] = $post['idItemTarifario'];
            $result['data']['idItem'] = $post['idItem'];
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarActualItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];
        $data['update'] = [
            'flag_actual' => 0
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItem' => $post['idItem']
        ];

        $insert = $this->model->actualizarItemTarifario($data);
        $data = [];

        $data['update'] = [
            'flag_actual' => 1
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $insert = $this->model->actualizarItemTarifario($data);
        $data = [];

        if (!$insert['estado']) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroErroneo');
        } else {
            $result['result'] = 1;
            $result['msg']['title'] = 'Hecho!';
            $result['msg']['content'] = getMensajeGestion('exitosoPersonalizado', ['message' => 'Se actualizó el item actual']);
        }

        respuesta:
        echo json_encode($result);
    }

    public function actualizarEstadoItemTarifario()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.itemTarifario';
        $data['where'] = [
            'idItemTarifario' => $post['idItemTarifario']
        ];

        $update = $this->model->actualizarItemTarifario($data);
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
