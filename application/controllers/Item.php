<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Item', 'model');
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
            'assets/custom/js/item'
        );

        $config['data']['icon'] = 'fas fa-shopping-cart';
        $config['data']['title'] = 'Items';
        $config['data']['message'] = 'Lista de Items';
        $config['data']['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $config['data']['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $config['data']['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
        $config['data']['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();


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

    public function formularioRegistroItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
        $dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();


        $itemsLogistica =  $this->model->obtenerItemsLogistica();
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

    public function formularioActualizacionItem()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $dataParaVista['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $dataParaVista['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
        $dataParaVista['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();


        $itemsLogistica =  $this->model->obtenerItemsLogistica();
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

        $dataParaVista['informacionItem'] = $this->model->obtenerInformacionItems($post)['query']->row_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Item';
        $result['data']['html'] = $this->load->view("modulos/Item/formularioActualizacion", $dataParaVista, true);
        $result['data']['itemsLogistica'] = $data['items'];

        echo json_encode($result);
    }

    public function viewRegistroItem()
    {

        //formularioview
        $dataParaVista = [];
        $dataParaVista['nav']['menu_active'] = '131';
        $dataParaVista['css']['style'] = array(
            'assets/libs/handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/css/floating-action-button'
        );
        $dataParaVista['js']['script'] = array(
            // 'assets/libs/datatables/responsive.bootstrap4.min',
            // 'assets/custom/js/core/datatables-defaults',
            'assets/libs//handsontable@7.4.2/dist/handsontable.full.min',
            'assets/libs/handsontable@7.4.2/dist/languages/all',
            'assets/libs/handsontable@7.4.2/dist/moment/moment',
            'assets/libs/handsontable@7.4.2/dist/pikaday/pikaday',
            'assets/custom/js/core/HTCustom',
            'assets/custom/js/item',
            'assets/custom/js/viewAgregarItem'

            //'assets/custom/js/viewAgregarCotizacion'
        );


        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);


        $dataParaVista['data']['tipoItem'] = $this->model->obtenerTipoItem()['query']->result_array();
        $dataParaVista['data']['marcaItem'] = $this->model->obtenerMarcaItem()['query']->result_array();
        $dataParaVista['data']['categoriaItem'] = $this->model->obtenerCategoriaItem()['query']->result_array();
        $dataParaVista['data']['subcategoriaItem'] = $this->model->obtenerSubCategoriaItem()['query']->result_array();


        $itemsLogistica =  $this->model->obtenerItemsLogistica();
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

        $dataParaVista['data']['informacionItem'] = $this->model->obtenerInformacionItems($post)['query']->row_array();

        $dataParaVista['single'] = true;
        $result['result'] = 1;
        $result['msg']['title'] = 'Actualizar Item';

        $dataParaVista['data']['itemsLogistica'] = $data['items'][1];
        $result['data']['html'] = $this->load->view("modulos/Item/viewRegistroItem", $dataParaVista, true);
        // $result['data']['itemsLogistica'] = $data['items'];
        $dataParaVista['view'] = 'modulos/Item/viewRegistroItem';

        $this->view($dataParaVista);
    }


    public function registrarItem()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $post['nombre'] = checkAndConvertToArray($post['nombre']);
        $post['caracteristicas'] = checkAndConvertToArray($post['caracteristicas']);
        $post['tipo'] = checkAndConvertToArray($post['tipo']);
        $post['marca'] = checkAndConvertToArray($post['marca']);
        $post['categoria'] = checkAndConvertToArray($post['categoria']);
        $post['subcategoria'] = checkAndConvertToArray($post['subcategoria']);
        $post['idItemLogistica'] = checkAndConvertToArray($post['idItemLogistica']);

        $data = [];

        // foreach que agarra los indices, para poder guardar el arrary generado del post
        foreach ($post['nombre'] as $k => $r) {

            $nombre = $post['nombre'][$k];

            $data['insert'] = [
                'nombre' => trim($nombre),
                'caracteristicas' => $post['caracteristicas'][$k], // el de la dereacha es el valor que recibe del post
                'idItemTipo' => $post['tipo'][$k],
                'idItemMarca' => $post['marca'][$k],
                'idItemCategoria' => $post['categoria'][$k],
                'idItemSubCategoria' => $post['subcategoria'][$k],
                //El de la izquierda es el nombre de la columna en la bd
                'idItemLogistica' => $post['idItemLogistica'][$k]
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

            $dataDetallle['insert'][] = [
                'idItem' => $insert['id'],
                'fechaIni' => getActualDateTime(),
                'talla' => !empty($post['talla'][$k]) ? $post['talla'][$k] : NULL,
                'tela' => !empty($post['tela'][$k]) ? $post['tela'][$k] : NULL,
                'color' => !empty($post['color'][$k]) ? $post['color'][$k] : NULL,
                'monto' => !empty($post['monto'][$k]) ? $post['monto'][$k] : NULL
            ];

            //imagen
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
        }

        $insertDetalle = $this->model->insertarMasivo('compras.itemDetalle', $dataDetallle['insert']);

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

    public function actualizarItem()
    {

        $this->db->trans_start();

        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'idItem' => $post['idItem'],
            'nombre' => $post['nombre'],
            'caracteristicas' => $post['caracteristicas'],
            'idItemTipo' => $post['tipo'],
            'idItemMarca' => $post['marca'],
            'idItemCategoria' => $post['categoria'],
            'idItemSubCategoria' => $post['subcategoria'],
            'idItemtextil' => $post['textil'],
            'idItemLogistica' => $post['idItemLogistica']
        ];

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
        $data = [];

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
        $subcategoriaItem =  refactorizarDataHT(["data" => $dataParaVista['subcategoriaItem'], "value" => "value"]);
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
                'TIPO (*)', 'MARCA (*)', 'CATEGORIA (*)', 'SUBCATEGORIA (*)', 'ITEM (*)', 'CARACTERISTICAS (*)', 'EQUIVALENTE EN LOGISTICA', 'TALLA', 'TELA', 'COLOR', 'MONTO'
            ],
            'columns' => [
                ['data' => 'tipo', 'type' => 'myDropdown', 'placeholder' => 'tipo', 'width' => 200, 'source' => $tipoItem],
                ['data' => 'marca', 'type' => 'myDropdown', 'placeholder' => 'marca', 'width' => 200, 'source' => $marcaItem],
                ['data' => 'categoria', 'type' => 'myDropdown', 'placeholder' => 'categoria', 'width' => 200, 'source' => $categoriaItem],
                ['data' => 'subcategoria', 'type' => 'myDropdown', 'placeholder' => 'subCategoria', 'width' => 200, 'source' => $subcategoriaItem],
                ['data' => 'item', 'type' => 'text', 'placeholder' => 'item', 'width' => 200, 'source'],
                ['data' => 'caracteristicas', 'type' => 'text', 'placeholder' => 'caracteristicas', 'width' => 200],
                ['data' => 'logistica', 'type' => 'text', 'placeholder' => 'logistica', 'width' => 200, 'source' => $logisticaItem],
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
}
