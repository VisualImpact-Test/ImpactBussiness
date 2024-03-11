<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $titulo;
	var $carpetaHtml;
	var $carpetaArchivos;
	var $html;
	var $version;

	var $namespace;
	var $result;
	var $idUsuario;
	var $idUsuarioHist;
	var $idTipoUsuario;
	var $flagGestorDeArchivos;

	var $foto;
	var $usuario;
	var $usuario_completo;
	var $demo;
	var $navbar_permiso;

	var $permisos = array();

	var $fotos_url = 'http://movil.visualimpact.com.pe/fotos/impactTrade_Android/';
	var $aWebUrl = [];

	var $carpeta;
	var $estrellas = [1 => 20, 2 => 40, 3 => 60, 4 => 80, 5 => 100]; //livestorecheck

	var $sessId = null;
	var $sessIdCuenta = '';
	var $sessIdProyecto = '';
	var $aSessTrack = [];

	var $bsModal = false;
	var $flag_ti;
	var $flag_externo;
	var $sessIdDistribuidoraSucursal;

	var $hora;

	var $flagactualizarListas;

	public function __construct()
	{
		parent::__construct();
		$this->version = '1.1.70';
		date_default_timezone_set("America/Lima");

		$_SESSION['idCuenta'] = $this->session->userdata('idCuenta');
		$this->hora = date('H:i:s');
		$this->sessId = $this->session->userdata('sessionId');
		$this->namespace = $this->router->fetch_class();
		$this->idUsuario = $this->session->userdata('idUsuario');
		$this->idUsuarioHist = $this->session->userdata('idUsuarioHist');
		$this->idTipoUsuario = $this->session->userdata('idTipoUsuario');
		$this->flag_ti = ($this->idTipoUsuario == 4) ? true : false;
		$this->idEmpleado = $this->session->userdata('idEmpleado');
		$this->sessIdCuenta = $this->session->userdata('idCuenta');
		$this->sessNomCuenta = $this->session->userdata('cuenta');
		$this->sessNomCuentaCorto = $this->session->userdata('abreviacionCuenta');
		$this->sessBDCuenta = $this->session->userdata('sessBDCuenta');
		$this->sessIdProyecto = $this->session->userdata('idProyecto');
		$this->sessNomProyecto = $this->session->userdata('proyecto');
		$this->permisos = $this->session->userdata('permisos');
		$this->usuario = $this->session->userdata('nombres');
		$this->usuario_completo = $this->session->userdata('apeNom');
		$this->demo = $this->session->userdata('demo');
		$this->foto = $this->session->userdata('foto');
		$this->navbar_permiso = $this->session->userdata('pages');
		$this->flagGestorDeArchivos = $this->session->userdata('flag_gestorDeArchivos');
		$this->flag_externo = $this->session->userdata('externo');
		$this->sessIdDistribuidoraSucursal = $this->session->userdata('idDistribuidoraSucursal');

		$this->flagactualizarListas = $this->session->userdata('flag_actualizarListas');

		$is_ajax = $this->input->is_ajax_request();




		if (!empty($this->idUsuario) && $this->namespace == 'login') redirect('home', 'refresh');
		else {
			if (empty($this->idUsuario) && $this->namespace != 'login' && $this->namespace != 'recover' && $this->namespace != 'FormularioProveedor') { // && $this->namespace != '' si no requiere login para ejecutar colocar aqui el controlador
				if ($this->session_expired() && $is_ajax) {
					// Envía una respuesta de sesión expirada
					echo json_encode(['session_expired' => true]);
					exit;
				} else redirect('login', 'refresh');
			}
		}


		$this->result = array(
			'status' => 0,
			'url' => '',
			'data' => array(),
			'msg' => array('title' => 'Alerta', 'content' => ''),
			'session' => true,
			'result' => 0,
			'errormemoria' => false
		);

		$aDirectorio = explode('\\', FCPATH);
		$aDirectorio = array_filter($aDirectorio);
		array_pop($aDirectorio);

		$directorio = implode('\\', $aDirectorio) . '\\';

		$this->carpeta = array(
			'raiz' => $directorio,
			'livestorecheck' => '_archivos\pg\livestorecheck\\'
		);

		$this->aWebUrl = [
			'fotos' => ['movil' => 'http://movil.visualimpact.com.pe/fotos/impactTrade_Android/']
		];
	}

	private function session_expired()
	{
		// Verifica si existe un elemento clave en la sesión, por ejemplo, 'idUsuario'
		if (!$this->session->userdata('idUsuario')) {
			// Si 'idUsuario' no existe, asume que la sesión ha expirado
			return true;
		}

		// Verifica el tiempo de la última actividad
		$last_activity = $this->session->userdata('last_activity');
		$current_time = time();
		$max_inactive_time = 20; // 20 segundos

		if (($current_time - $last_activity) > $max_inactive_time) {
			// Si han pasado más de 20 segundos desde la última actividad
			return true;
		}

		// Si ninguna de las condiciones anteriores se cumple, la sesión aún es válida
		return false;
	}


	public function expulsar()
	{
		$this->session->sess_destroy();
		header("Location: " . site_url());
	}

	public function logout()
	{
		$this->aSessTrack[] = ['idAccion' => 3];
		$this->session->sess_destroy();

		$result = array();
		$result['result'] = 1;
		$result['url'] = 'login/';
		$result['data'] = '';
		$result['msg']['title'] = '';
		$result['msg']['content'] = '';

		echo json_encode($result);
	}

	public function fn_404()
	{
		$config['css']['style'] = array();
		$config['single'] = true;
		$config['view'] = 'templates/404';
		$this->view($config);
	}

	public function get_dni($dni)
	{
		$result = $this->result;

		/*
			https://www.apisperu.com/ generación de token:
				webmaster@turno.macctec.com
				elmwNY+?Ck8U
		*/

		$site = 'http://dniruc.apisperu.com/api/v1/dni/' . $dni . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IndlYm1hc3RlckB0dXJuby5tYWNjdGVjLmNvbSJ9.ANgmBhcpvztFEZB9hMpr4Bk1nd-OVEmtgZX5T-Sky74';

		$data = @file_get_contents($site);
		if (!empty($data)) {
			$result['status'] = 1;
		} else {
			$result['data'] = 0;
			$config = array('type' => 2, 'message' => 'El servicio para verificar el número de DNI no esta disponible');
			$result['msg']['content'] = createMessage($config);
		}

		$result['data'] = json_decode($data);
		echo json_encode($result);
	}

	public function mysql_last_id()
	{
		return $this->db->query('SELECT LAST_INSERT_ID() id')->row()->id;
	}

	public function view($config = array())
	{

		if (!isset($config['header']['header_foto'])) $config['header']['header_foto'] = $this->foto;
		if (!isset($config['header']['header_usuario'])) $config['header']['header_usuario'] = $this->usuario;

		if (!isset($config['js']['script'])) $config['js']['script'] = array();
		if (!isset($config['css']['style'])) $config['css']['style'] = array();
		$this->load->view('core/01_head', $config['css']);

		$single = isset($config['single']) ? $config['single'] : false;
		$noTitle = isset($config['noTitle']) ? $config['noTitle'] : false;
		$data = isset($config['data']) ? $config['data'] : false;

		$this->load->view('core/02_body', array('single' => $single));

		if (!$single) {
			$this->load->view('core/03_header', $config['header']);
			$this->load->view('core/04_container', array());
			$this->load->view('core/05_nav', (isset($config['nav']) ? $config['nav'] : array()));
			$this->load->view('core/06_content', array());
			if (!$noTitle) $this->load->view('core/07_content_title', $data);
		}

		$view = isset($config['view']) ? $config['view'] : $this->namespace;
		$this->load->view($view, $data);

		if (!$single) {
			$this->load->view('core/08_content_end', array());
			$this->load->view('core/09_container_end');
		}
		//
		$this->load->view('core/11_container_end');
		$this->load->view('core/10_body_js', $config['js']);

		$this->load->view('core/12_body_end');
		$this->load->view('core/13_html_end');
	}

	public function setDefaultTCPDF($config)
	{

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		require APPPATH . '/vendor/autoload.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Estableciendo informacion de Pdf
		$pdf->SetCreator('ImpactTrade');
		$pdf->SetAuthor('Visual Impact S.A.C.');
		$pdf->SetTitle($config['title']);
		$pdf->SetSubject($config['subject']);

		// Estableciendo Header de Pdf
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
		$pdf->SetHeaderData($config['logo'], $config['logoWidth'], $config['title'], $config['headerDescription'], array(0, 0, 0), array(0, 0, 0));
		$pdf->setFooterData(array(0, 0, 0), array(0, 0, 0));

		// Estableciendo fuentes para header y footer
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// Estableciendo la fuente de monoespaciado por defecto
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// Estableciendo los margenes del Pdf
		$pdf->SetMargins($config['margenIzquierdo'], $config['margenSuperior'], $config['margenDerecho']);
		$pdf->SetHeaderMargin($config['margenHeader']);
		$pdf->SetFooterMargin($config['margenFooter']);

		// Estableciendo el autobreak
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// Estableciendo el factor de esacala en base a la unidad de medida que se puso en la creacion del objecto
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// Estableciendo strings que dependen del lenguaje (sobretodo para imprimir el mismo pdf en diferentes idiomas)
		if (@file_exists(dirname(__FILE__) . '/lang/spa.php')) {
			require_once(dirname(__FILE__) . '/lang/spa.php');
			$pdf->setLanguageArray($l);
		}

		// Se recomienda desactivar para mejora el rendimiento
		$pdf->setFontSubsetting(false);

		// Estableciendo la fuente principal del Pdf
		$pdf->SetFont('dejavusans', '', 14, '', true);

		// Estableciendo efecto de sombra para el texto
		$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

		return $pdf;
	}

	public function getDefaultPHPSpreadSheetForDownload($config)
	{
		header('Set-Cookie: fileDownload=true; path=/');
		header('Content-Disposition: attachment;filename="' . $config["nombreArchivo"] . '.xls"');
		header('Cache-Control: max-age=60, must-revalidate');

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		return  $spreadsheet;
	}

	public function getDefaultPHPSpreadSheetWriter($config)
	{
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($config["spreadsheet"], 'Xls');
		return $writer;
	}

	public function getDefaultMpdf($config)
	{
		if ($config["tipo"] == "descargaConFileDownload") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Cache-Control: max-age=60, must-revalidate');
		}

		// Parametros del construct por defecto
		// $config = [
		// 	'mode' => '',
		// 	'format' => 'A4',
		// 	'default_font_size' => 0,
		// 	'default_font' => '',
		// 	'margin_left' => 15,
		// 	'margin_right' => 15,
		// 	'margin_top' => 16,
		// 	'margin_bottom' => 16,
		// 	'margin_header' => 9,
		// 	'margin_footer' => 9,
		// 	'orientation' => 'P',
		// ];

		//Medidas de margenes estan en mm, no pixeles.
		$construct = [
			'margin_top' => !empty($config["margin_top"]) ? $config["margin_top"] : 30,
			'margin_bottom' => !empty($config["margin_top"]) ? $config["margin_top"] : 47,
			'margin_header' => !empty($config["margin_top"]) ? $config["margin_top"] : 10,
			'margin_footer' => !empty($config["margin_top"]) ? $config["margin_top"] : 10,
		];

		$mpdf = new \Mpdf\Mpdf($construct);

		//	Estableciendo header por defecto
		if (empty($config["header"])) {
			$dataParaHeaderPdf = [
				"logo" => $config["logo"],
				"nombreReporte" => $config["nombreReporte"],
			];
			$header = $this->load->view("pdf/header", $dataParaHeaderPdf, true);
			$mpdf->SetHTMLHeader($header);
		}

		// Estableciendo footer por defecto
		if (empty($config["footer"])) {
			$footer = $this->load->view("pdf/footer", [], true);
			$mpdf->setFooter($footer);
		}

		// Estableciendo styles por defecto, y sobreescribiendo clases css con uno styles personalizado
		// Se crea la primera hoja del PDF al usar el WriteHtml para establecer los estilos
		$styles = $this->load->view("pdf/styles", [], true);
		$mpdf->WriteHTML($styles);
		if (!empty($config["styles"])) $mpdf->WriteHTML($config["styles"]);

		return $mpdf;
	}

	// Usada para buscar los ids del grupo de coincidencias en una tabla.
	// Se especifica el nombre de la tabla, el id que se desea obtener
	// y los grupos de coicidencias en un array multidimensional.
	// se retornara un array que contendra en su primer elemento 'matched'
	// los indices de los grupos que se han encontrado con el indice en su interior,
	// y 'unmatched' con los indices de los grupos no encontrados y un null en su interior.
	protected function getIdsTablaHT($tabla = '', $coincidencias = [], $idColumn = '', $validacionesExtra, $test = false)
	{
		$keys = array_keys($coincidencias[0]);

		foreach ($coincidencias as $key => $row) {
			//si valor apuntado es null no considerar
			$val = true;
			foreach ($row as $row_index => $row_value) {
				if (empty($row_value)) $val = false;
			}
			if ($val) {
				reset($coincidencias);
				if ($key === key($coincidencias)) $this->db->group_start();
				else $this->db->or_group_start();
				$this->db->where($row);
				$this->db->group_end();
			}
		}
		$this->db->where($validacionesExtra);

		$data = $this->db->get($tabla)->result_array();


		$matched = [];
		$unmatched = [];

		if (count($data) != 0) {
			foreach ($coincidencias as $key => $coincidenciasRow) {
				foreach ($data as $dataRow) {
					$columnsData = array_intersect_key($dataRow,  array_flip($keys));
					if ($coincidenciasRow == $columnsData) {
						$matched[$key] = $dataRow[$idColumn];
						break;
					}
				}
				if (empty($matched[$key])) $unmatched[$key] = null;
			}
		} else {
			$unmatched = [];
			foreach ($coincidencias as $key => $value) {
				$unmatched[$key] = null;
			}
		}

		return ['matched' => $matched, 'unmatched' => $unmatched];
	}

	// Sirve para obtener los ids en base a los campos especificados, creado debido a la
	// carencia de ids en dropdowns de la librería HandsOnTable.
	public function getIdsCorrespondientes($params)
	{
		$tablaHT = $params['tablaHT'];
		$grupos = $params['grupos'];
		$tablasGrupos = [];

		foreach ($grupos as $key => $grupo) {
			$validacionesExtra = isset($grupo['extra']) ? $grupo['extra'] : array();

			$tablaHTColumnasFiltradas = colsFromArray($tablaHT, $grupo['columnas']);

			$tablasConColumnasIds = [];
			foreach ($grupo['columnas'] as $keyColumna => $columna) {
				$arrayDeUnaColumna = colsFromArray($tablaHTColumnasFiltradas, $columna);
				$arrayDeUnaColumnaConKeyReemplazado = replaceKey($arrayDeUnaColumna, $grupo['columnasReales'][$keyColumna], $columna);
				$tablasConColumnasIds[] = $arrayDeUnaColumnaConKeyReemplazado;
			}
			$tablaConColumnasReales = $tablasConColumnasIds[0];
			foreach ($tablasConColumnasIds as $key => $value) {
				$tablaConColumnasReales = array_replace_recursive($tablaConColumnasReales, $value);
			}

			$tablasConIds = $this->getIdsTablaHT($grupo['tabla'], $tablaConColumnasReales, $grupo['idTabla'], $validacionesExtra)['matched'];
			$str = $this->db->last_query();
			$tablaConIdsRefactorizada = [];
			foreach ($tablasConIds as $key => $value) {
				$tablaConIdsRefactorizada[$key] = [$grupo['idTabla'] => $value];
			}
			$tablasGrupos[] =  $tablaConIdsRefactorizada;
		}

		$tablaFinalDeIds = $tablasGrupos[0];
		foreach ($tablasGrupos as $key => $value) {
			$tablaFinalDeIds = array_replace_recursive($tablaFinalDeIds, $value);
		}

		$tablaFinal = [];
		foreach ($tablaHT as $key => $value) {
			$tablaFinal[$key] = !empty($tablaFinalDeIds[$key]) ? array_replace($value, $tablaFinalDeIds[$key]) : $value;
		}

		return $tablaFinal;
	}

	public function sendEmail($email = array())
	{
		$result = false;
		$defaults = array(
			'to' => '',
			'cc' => '',
			'asunto' => '',
			'contenido' => '',
			'adjunto' => ''
		);

		$email += $defaults;

		if (!empty($email['to']) && !empty($email['asunto'])) {

			$this->load->library('email');

			$config = array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				// 'smtp_host' => 'aspmx.l.google.com',
				// 'smtp_port' => '25',
				'smtp_user' => 'teamsystem@visualimpact.com.pe',
				'smtp_pass' => '#nVi=0sN0ti$',
				'mailtype' => 'html',
				'smtp_timeout' => 20,
				'charset' => 'utf-8',
				'newline' => "\r\n"
			);

			$this->email->initialize($config);
			$this->email->clear(true);

			$this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - Intranet');
			$this->email->to($email['to']);

			if (!empty($email['cc'])) {
				$this->email->cc($email['cc']);
			}

			$this->email->subject($email['asunto']);
			$this->email->message($email['contenido']);
			if (!empty($email['adjunto'])) {
				$this->email->attach($email['adjunto']);
			}

			if ($this->email->send()) {
				$result = true;
			}
		}

		return $result;
	}

	public function saveFileWasabi($config = [])
	{
		if (empty($config['base64'])) return "";

		$carpeta = $config['carpeta'];
		$nombreUnico = $config['nombreUnico'];

		$file =
			[
				'base64' => $config['base64'],
				'name' => $config['name'],
				'type' => $config['type'],
				'extension' => explode('/', $config['type'])[1],
			];

		// REVISION DEL TIPO DE ARCHIVO
		$tipoArchivo = explode('/', $config['type']);

		$extensionForName = '';
		if ($tipoArchivo[0] == 'image') {
			$extensionForName = $tipoArchivo[1];
		} else { // AGREGADO EN constants.php PARA HACER MAS FACIL LA BUSQUEDA
			$extensionForName = FILES_WASABI[$tipoArchivo[1]];
		}
		///// FIN: REVISION DEL TIPO DE ARCHIVO

		$this->load->library('s3');

		$s3Client = $this->s3;

		$s3Client::setAuth('BS9EM7XW1288NCZXLL6G', 'cIe5Mfe7ovcjsm3waEcmqGDun6Xu6d0ftAepy3AS');

		$s3Client->setEndpoint('s3.us-central-1.wasabisys.com');

		$s3Client->setRegion('us-central-1');
		// $file_url = '';
		$file_url = FCPATH . $nombreUnico . "_WASABI.{$extensionForName}";
		$base64 = str_replace("data:{$file['type']};base64,", '', $file['base64']);
		$base64 = str_replace(' ', '+', $base64);
		$content = base64_decode($base64);

		file_put_contents($file_url, $content);

		$response = S3::putObject(S3::inputFile($file_url, false), 'impact.business/' . $carpeta, $nombreUnico . "_WASABI.{$extensionForName}", S3::ACL_PUBLIC_READ);
		unlink($file_url);
		return $nombreUnico . "_WASABI.{$extensionForName}";
	}
}

class MY_Login extends CI_Controller
{

	var $version;
	var $result;

	var $foto;
	var $usuario;

	var $sessId = null;
	var $aSessTrack = [];

	var $bsModal = false;

	var $sessIdCuenta = '';
	var $sessIdProyecto = '';
	public function __construct()
	{
		parent::__construct();

		$this->usuario = $this->session->userdata('nombres');
		$this->foto = $this->session->userdata('foto');
		$this->sessIdCuenta = $this->session->userdata('idCuenta');
		$this->sessNomCuenta = $this->session->userdata('cuenta');
		$this->sessIdProyecto = $this->session->userdata('idProyecto');
		$this->sessNomProyecto = $this->session->userdata('proyecto');
		$this->load->model('MY_Model', 'model');

		$this->result = array(
			'status' => 0,
			'url' => '',
			'data' => array(),
			'msg' => array('title' => 'Alerta', 'content' => ''),
			'session' => true,
			'result' => 0,
		);
	}

	public function view($config = array())
	{

		if (!isset($config['header']['header_foto'])) $config['header']['header_foto'] = $this->foto;
		if (!isset($config['header']['header_usuario'])) $config['header']['header_usuario'] = $this->usuario;

		if (!isset($config['js']['script'])) $config['js']['script'] = array();
		if (!isset($config['css']['style'])) $config['css']['style'] = array();
		$this->load->view('core/01_head', $config['css']);

		$single = isset($config['single']) ? $config['single'] : false;
		$data = isset($config['data']) ? $config['data'] : false;

		$data['title'] = "Restablecer Clave";
		$data['message'] = "Inserte su nueva clave";
		$data['icon'] = "fas fa-key";

		$this->load->view('core/02_body', array('single' => $single));

		if (!$single) {
			$this->load->view('core/03_header', $config['header']);
			$this->load->view('core/04_container', array());
			$this->load->view('core/05_nav', (isset($config['nav']) ? $config['nav'] : array()));
			$this->load->view('core/06_content', array());
			$this->load->view('core/07_content_title', $data);
		}

		$view = isset($config['view']) ? $config['view'] : $this->namespace;
		$this->load->view($view, $data);
		if (!$single) {
			$this->load->view('core/08_content_end', array());
			$this->load->view('core/09_container_end');
		}

		$this->load->view('core/11_container_end');
		$this->load->view('core/10_body_js', $config['js']);

		$this->load->view('core/12_body_end');
		$this->load->view('core/13_html_end');
	}

	public function sendJS($data)
	{
		$inputbox = stripslashes($data);
		parse_str($inputbox, $array);
		return $array;
	}

	public function resetIntentosFallidos($idUsuario)
	{
		$this->db->set('intentosFallidos', 0);
		$this->db->where('idUsuario', $idUsuario);
		$this->db->update('trade.usuario');
	}
}
