<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotizacion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Cotizacion', 'model');
        $this->load->model('M_Item', 'model_item');
        $this->load->model('M_control', 'model_control');
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
            'assets/custom/js/cotizacion'
        );

        $config['data']['icon'] = 'fas fa-money-check-edit-alt';
        $config['data']['title'] = 'Cotizacion';
        $config['data']['message'] = 'Lista de Cotizacions';
        $config['data']['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $config['data']['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $config['view'] = 'modulos/Cotizacion/index';

        $this->view($config);
    }

    public function reporte()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];
        $dataParaVista = $this->model->obtenerInformacionCotizacion($post)['query']->result_array();

        $html = getMensajeGestion('noRegistros');
        if (!empty($dataParaVista)) {
            $html = $this->load->view("modulos/Cotizacion/reporte", ['datos' => $dataParaVista], true);
        }

        $result['result'] = 1;
        $result['data']['views']['idContentCotizacion']['datatable'] = 'tb-cotizacion';
        $result['data']['views']['idContentCotizacion']['html'] = $html;
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

    public function formularioRegistroCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
        $dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();

        $itemServicio =  $this->model->obtenerItemServicio();
        foreach ($itemServicio as $key => $row) {
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['semaforoVigencia'] = $row['semaforoVigencia'];
        }
        foreach ($data['itemServicio'] as $k => $r) {
            $data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
        }
        $data['itemServicio'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['msg']['title'] = 'Registrar Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioRegistro", $dataParaVista, true);
        $result['data']['itemServicio'] = $data['itemServicio'];

        echo json_encode($result);
    }

    public function formularioVisualizacionCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $dataParaVista = [];

        $data = $this->model->obtenerInformacionCotizacionDetalle($post)['query']->result_array();
        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
            $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['cabecera']['codCotizacion'] = $row['codCotizacion'];
            $dataParaVista['cabecera']['cotizacionEstado'] = $row['cotizacionEstado'];
            $dataParaVista['cabecera']['fechaEmision'] = $row['fechaEmision'];
            $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['idItemEstado'] = $row['idItemEstado'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
            $dataParaVista['detalle'][$key]['proveedor'] = $row['proveedor'];
            $dataParaVista['detalle'][$key]['fecha'] = !empty($row['fechaModificacion']) ? $row['fechaModificacion'] : $row['fechaCreacion'];
            $dataParaVista['detalle'][$key]['cotizacionDetalleEstado'] = $row['cotizacionDetalleEstado'];
        }

        $dataParaVista['estados'] = $this->model_control->get_estados_cotizacion()->result_array();

        $result['result'] = 1;
        $result['msg']['title'] = 'Visualizar Cotizacion';
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/formularioVisualizacion", $dataParaVista, true);

        echo json_encode($result);
    }

    public function registrarCotizacion()
    {
        $this->db->trans_start();
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['insert'] = [
            'nombre' => $post['nombre'],
            'fechaEmision' => getActualDateTime(),
            'idCuenta' => $post['cuentaForm'],
            'idCentroCosto' => $post['cuentaCentroCostoForm'],
            'fechaRequerimiento' => $post['fechaRequerimiento'],
            'flagIgv' => !empty($post['igvForm']) ? 1 : 0,
            'total' => $post['totalForm'],
            'idPrioridad' => $post['prioridadForm'],
            'motivo' => $post['motivoForm'],
            'comentario' => $post['comentarioForm'],
            'idCotizacionEstado' => $post['tipoRegistro'],
            'idUsuarioReg' => $this->idUsuario
        ];

        $validacionExistencia = $this->model->validarExistenciaCotizacion($data['insert']);

        if (!empty($validacionExistencia['query']->row_array())) {
            $result['result'] = 0;
            $result['msg']['title'] = 'Alerta!';
            $result['msg']['content'] = getMensajeGestion('registroRepetido');
            goto respuesta;
        }

        $data['tabla'] = 'compras.cotizacion';

        $insert = $this->model->insertarCotizacion($data);
        $data = [];

        $post['nameItem'] = checkAndConvertToArray($post['nameItem']);
        $post['idItemForm'] = checkAndConvertToArray($post['idItemForm']);
        $post['tipoItemForm'] = checkAndConvertToArray($post['tipoItemForm']);
        $post['cantidadForm'] = checkAndConvertToArray($post['cantidadForm']);
        $post['idEstadoItemForm'] = checkAndConvertToArray($post['idEstadoItemForm']);
        $post['caracteristicasItem'] = checkAndConvertToArray($post['caracteristicasItem']);
        $post['costoForm'] = checkAndConvertToArray($post['costoForm']);
        $post['subtotalForm'] = checkAndConvertToArray($post['subtotalForm']);
        $post['idProveedorForm'] = checkAndConvertToArray($post['idProveedorForm']);

        foreach ($post['nameItem'] as $k => $r) {
            $dataItem = [];
            $idItem = (!empty($post['idItemForm'][$k])) ? $post['idItemForm'][$k] : NULL;
            $nameItem = $post['nameItem'][$k];
            if(empty($idItem)) { // si es nuevo verificamos y lo registramos 
                $validacionExistencia = $this->model_item->validarExistenciaItem(['idItem' => $idItem , 'nombre' =>  $nameItem]);

                if (!empty($validacionExistencia['query']->row_array())) {
                    $result['result'] = 0;
                    $result['msg']['title'] = 'Alerta!';
                    $result['msg']['content'] = createMessage(['type'=> 2 , 'message' => "El item {$nameItem} ya existe en el sistema"]);
                    goto respuesta;
                }

                $dataItem['insert'] = [
                    'nombre' => trim($nameItem),
                    'caracteristicas' => !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL,
                    'idItemTipo' => $post['tipoItemForm'][$k],
                ];

                $dataItem['tabla'] = 'compras.item';
                $idItem = $this->model_item->insertarItem($dataItem)['id'];
                
            }

            $data['insert'][] = [
                'idCotizacion' => $insert['id'],
                'idItem' => $idItem,
                'idItemTipo' => $post['tipoItemForm'][$k],
                'nombre' => trim($nameItem),
                'cantidad' => $post['cantidadForm'][$k],
                'costo' => !empty($post['costoForm'][$k]) ? $post['costoForm'][$k] : NULL,
                'subtotal' => !empty($post['subtotalForm'][$k]) ? $post['subtotalForm'][$k] : NULL,
                'idItemEstado' => $post['idEstadoItemForm'][$k],
                'idProveedor' => empty($post['idProveedorForm'][$k]) ? NULL : $post['idProveedorForm'][$k],
                'idCotizacionDetalleEstado' => 1,
                'caracteristicas'=> !empty($post['caracteristicasItem'][$k]) ? $post['caracteristicasItem'][$k] : NULL, 
                'fechaCreacion' => getActualDateTime()
            ];
        }

        $data['tabla'] = 'compras.cotizacionDetalle';

        $insertDetalle = $this->model->insertarCotizacionDetalle($data);
        $data = [];

        $estadoEmail = true;
        if($post['tipoRegistro'] == 2){
            $estadoEmail = $this->enviarCorreo($insert['id']);
        }

        if (!$insert['estado'] || !$insertDetalle['estado'] || !$estadoEmail) {
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

    public function actualizarEstadoCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);

        $data = [];

        $data['update'] = [
            'estado' => ($post['estado'] == 1) ? 0 : 1
        ];

        $data['tabla'] = 'compras.cotizacion';
        $data['where'] = [
            'idCotizacion' => $post['idCotizacion']
        ];

        $update = $this->model->actualizarCotizacion($data);
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

    public function enviarCorreo($idCotizacion)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'teamsystem@visualimpact.com.pe',
            'smtp_pass' => '#nVi=0sN0ti$',
            'mailtype' => 'html'
        );

        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->set_newline("\r\n");

        $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
        $this->email->to(['aaron.ccenta@visualimpact.com.pe', 'jean.alarcon@visualimpact.com.pe']);

        $data = [];
        $dataParaVista = [];
        $data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();

        foreach ($data as $key => $row) {
            $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
            $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
            $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
            $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
            $dataParaVista['detalle'][$key]['itemTipo'] = $row['itemTipo'];
            $dataParaVista['detalle'][$key]['item'] = $row['item'];
            $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
            $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
            $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
        }

        $dataParaVista['link'] = base_url() . index_page() . 'Cotizacion';

        // $bcc = array(
        //     'team.sistemas@visualimpact.com.pe',
        // );
        // $this->email->bcc($bcc);

        $this->email->subject('IMPACTBUSSINESS - NUEVA COTIZACION GENERADA');
        $html = $this->load->view("modulos/Cotizacion/correo/informacionProveedor", $dataParaVista, true);
        $correo = $this->load->view("modulos/Cotizacion/correo/formato", ['html' => $html, 'link' => base_url() . index_page() . 'Cotizacion'], true);
        $this->email->message($correo);

        $estadoEmail = $this->email->send();

        return $estadoEmail;
    }

    public function generarCotizacionPDF($idCotizacion = '')
    {
        require_once('../mpdf/mpdf.php');
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        if (!empty($idCotizacion)) {
            $data = [];
            $dataParaVista = [];
            $data = $this->model->obtenerInformacionCotizacionDetalle(['idCotizacion' => $idCotizacion])['query']->result_array();

            foreach ($data as $key => $row) {
                $dataParaVista['cabecera']['idCotizacion'] = $row['idCotizacion'];
                $dataParaVista['cabecera']['cotizacion'] = $row['cotizacion'];
                $dataParaVista['cabecera']['cuenta'] = $row['cuenta'];
                $dataParaVista['cabecera']['cuentaCentroCosto'] = $row['cuentaCentroCosto'];
                $dataParaVista['cabecera']['tipoCotizacion'] = $row['tipoCotizacion'];
                $dataParaVista['cabecera']['fecha'] = $row['fecha'];
                $dataParaVista['detalle'][$key]['item'] = $row['item'];
                $dataParaVista['detalle'][$key]['cantidad'] = $row['cantidad'];
                $dataParaVista['detalle'][$key]['costo'] = $row['costo'];
                $dataParaVista['detalle'][$key]['estadoItem'] = $row['estadoItem'];
            }
            if (count($dataParaVista) == 0) exit();

            $contenido['header'] = $this->load->view("modulos/Cotizacion/pdf/header", array(), true);
            $contenido['footer'] = $this->load->view("modulos/Cotizacion/pdf/footer", array(), true);
            $contenido['body'] = $this->load->view("modulos/Cotizacion/pdf/body", $dataParaVista, true);
            $contenido['style'] = '<style>table { border-collapse: collapse; }table.tb-detalle th, table.tb-detalle td { border: 1px solid #484848; padding:5px; }.square { margin-right: 15px; border: 1px solid #000; text-align: center; }body {font-size: 12px;}</style>';

            require APPPATH . '/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();

            $mpdf->SetHTMLHeader($contenido['header']);
            $mpdf->SetHTMLFooter($contenido['footer']);
            $mpdf->AddPage();
            $mpdf->WriteHTML($contenido['style']);
            $mpdf->WriteHTML($contenido['body']);

            header('Set-Cookie: fileDownload=true; path=/');
            header('Cache-Control: max-age=60, must-revalidate');
            $mpdf->Output('Cotizacion.pdf', 'D');
        }

        $this->aSessTrack[] = ['idAccion' => 9];
    }

    public function guardarArchivo(){
        $ruta = '../documentosAdjuntos/ImpactBusiness/'; //Decalaramos una variable con la ruta en donde almacenaremos los archivos
		$mensage = 'Bien hecho';//Declaramos una variable mensaje quue almacenara el resultado de las operaciones.
		foreach ($_FILES as $key) //Iteramos el arreglo de archivos
		{
			if($key['error'] == UPLOAD_ERR_OK )//Si el archivo se paso correctamente Ccontinuamos 
				{
					$NombreOriginal = $key['name'];//Obtenemos el nombre original del archivo
					$ext = pathinfo($NombreOriginal, PATHINFO_EXTENSION);
					$nombreUnico = uniqid().'.'.$ext;
					$temporal = $key['tmp_name']; //Obtenemos la ruta Original del archivo
					$Destino = $ruta.$nombreUnico;	//Creamos una ruta de destino con la variable ruta y el nombre original del archivo	
					
					move_uploaded_file($temporal, $Destino); //Movemos el archivo temporal a la ruta especificada	
					
					$data = [
						'nombreOriginal' => $NombreOriginal,
						'nombreUnico' => $nombreUnico,
						'ext'=>$ext
					];
				}
		
			if ($key['error']=='') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
				{
					$mensage .= '-> Archivo <b>'.$NombreOriginal.'</b> Subido correctamente. <br>';
				}
			if ($key['error']!='')//Si existio algún error retornamos un el error por cada archivo.
				{
					$mensage .= '-> No se pudo subir el archivo <b>'.$NombreOriginal.'</b> debido al siguiente Error: n'.$key['error']; 
				}
			
		}
		if(!empty($data)){
			echo json_encode($data);
		}else{
			echo $mensage;// Regresamos los mensajes generados al cliente
		}
    }

    public function guardarArchivoBD()
    {
        $this->db->trans_start();
		$result = $this->result;

		$post = json_decode($this->input->post('data'), true);

		$data['insert'] = [
			'idCotizacion' => $post['idCotizacion'],
            'idTipoArchivo' => 1, // Orden de COmpra
			'nombre_unico' => $post['nombreUnico'],
			'nombre_archivo' => $post['nombreOriginal'],
			'extension' => $post['ext'],
            'estado' => true,
            'idUsuarioReg' => $this->idUsuario,
		];
        $data['tabla'] = 'compras.cotizacionArchivos';
		$rs = $this->model->insertar($data);
		if(!$rs['estado']){
			$result['result'] = 0;
			$result['data']['width'] = '40%';
			$result['data']['html'] = createMessage(['type'=>2,'No se pudo guardar el archivo']);
		}else{
			$result['result'] = 1;
			$result['data']['html'] = getMensajeGestion('registroExitoso');
		}

		$this->db->trans_complete();
		echo json_encode($result);
    }

    public function formularioSolicitudCotizacion()
    {
        $result = $this->result;
        $post = json_decode($this->input->post('data'), true);
        
        $dataParaVista = [];
        $dataParaVista['cotizacion'] = $this->model->obtenerInformacionCotizacion($post)['query']->row_array();

        //Obteniendo Solo los Items Nuevos para verificacion de los proveedores
        $dataParaVista['cotizacionDetalle'] = $this->model->obtenerInformacionDetalleCotizacion(['idCotizacion'=> $post['id'],'idItemEstado' => 2])['query']->result_array();

        $dataParaVista['cuenta'] = $this->model->obtenerCuenta()['query']->result_array();
        $dataParaVista['cuentaCentroCosto'] = $this->model->obtenerCuentaCentroCosto()['query']->result_array();
        $dataParaVista['itemTipo'] = $this->model->obtenerItemTipo()['query']->result_array();
        $dataParaVista['prioridadCotizacion'] = $this->model->obtenerPrioridadCotizacion()['query']->result_array();

        $itemServicio =  $this->model->obtenerItemServicio();
        foreach ($itemServicio as $key => $row) {
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['value'] = $row['value'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['label'] = $row['label'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['costo'] = $row['costo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['tipo'] = $row['tipo'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['idProveedor'] = $row['idProveedor'];
            $data['itemServicio'][1][$row['tipo'] . '-' . $row['value']]['proveedor'] = $row['proveedor'];
        }
        foreach ($data['itemServicio'] as $k => $r) {
            $data['itemServicio'][$k] = array_values($data['itemServicio'][$k]);
        }
        $data['itemServicio'][0] = array();
        $result['data']['existe'] = 0;

        $result['result'] = 1;
        $result['data']['html'] = $this->load->view("modulos/Cotizacion/frmSolicitudCotizacion", $dataParaVista, true);
        $result['data']['itemServicio'] = $data['itemServicio'];

        echo json_encode($result);
    }
}
