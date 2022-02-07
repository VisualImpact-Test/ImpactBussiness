<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Control extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('M_control', 'm_control');
	}

	public function get_proyecto(){
		$result = $this->result;

			$input = json_decode($this->input->post('data'), true);
			$data = $this->m_control->get_proyecto($input);

			// $data = array();
			// foreach($query as $row){
				// $data[$row['id']]['nombre'] = $row['nombre'];
			// }

			$result['data'] = $data;

		echo json_encode($result);
	}

	public function get_combos(){
		$result = $this->result;

			$input = json_decode($this->input->post('data'), true);

			$data = array();
				if( isset($input['combos']['grupoCanal']) ){
					$data['grupoCanal'] = $this->m_control->get_grupoCanal($input);
				}

				if( isset($input['combos']['canal']) ){
					$data['canal'] = $this->m_control->get_canal($input);
				}

				if( isset($input['combos']['subCanal']) ){
					$data['subCanal'] = $this->m_control->get_subCanal($input);
				}

				if( isset($input['combos']['zona']) ){
					$data['zona'] = $this->m_control->get_zona($input);
				}

				if( isset($input['combos']['tipoCliente']) ){
					$data['tipoCliente'] = $this->m_control->get_tipoCliente($input);
				}

				// MAYORISTA
				if( isset($input['combos']['plaza']) ){
					$data['plaza'] = $this->m_control->get_plaza($input);
				}

				// TRADICIONAL
				if( isset($input['combos']['distribuidora']) ){
					$data['distribuidora'] = $this->m_control->get_distribuidora($input);
				}

				if( isset($input['combos']['distribuidoraSucursal']) ){
					$data['distribuidoraSucursal'] = $this->m_control->get_distribuidoraSucursal($input);
				}

				// MODERNO
				if( isset($input['combos']['cadena']) ){
					$data['cadena'] = $this->m_control->get_cadena($input);
				}

				if( isset($input['combos']['banner']) ){
					$data['banner'] = $this->m_control->get_banner($input);
				}

				// ENCARGADO
				if( isset($input['combos']['encargado']) ){
					$data['encargado'] = $this->m_control->get_encargado($input);
				}

				if( isset($input['combos']['colaborador']) ){
					$data['colaborador'] = $this->m_control->get_colaborador($input);
				}
				if( isset($input['combos']['usuario']) ){
					$data['usuario'] = $this->m_control->get_usuarios($input);
				}

				$result['data'] = $data;
				
				if(!empty($input['idGrupoCanal']))
				{
					$segmentacion = getSegmentacion(['grupoCanal_filtro' => $input['idGrupoCanal']]);
					$result['data']['grupoCanal'] = $segmentacion['grupoCanal'];
				}
		echo json_encode($result);
	}

	public function get_elemento_iniciativa_HT(){
		$result = $this->result;

		$input = json_decode($this->input->post('data'), true);
		$elementos = $this->m_control->get_elemento_iniciativa_HT($input);

		$elementosRefactorizado = [];
		foreach ($elementos as $row) {
			if (!in_array($row['nombre'], $elementosRefactorizado)) $elementosRefactorizado[] = $row['nombre'];
		}
		$elementos = !empty($elementosRefactorizado) ? $elementosRefactorizado : [' '];
		
		$result['data'] = $elementos;

		echo json_encode($result);
	}

	public function get_canal_HT(){
		$result = $this->result;

		$input = json_decode($this->input->post('data'), true);
		$canales = $this->m_control->get_canal_HT($input);

		$canalesRefactorizado = [];
		foreach ($canales as $row) {
			if (!in_array($row['nombre'], $canalesRefactorizado)) $canalesRefactorizado[] = $row['nombre'];
		}
		$canales = !empty($canalesRefactorizado) ? $canalesRefactorizado : [' '];
		
		$result['data'] = $canales;

		echo json_encode($result);
	}

	public function get_cliente_HT(){
		$result = $this->result;

		$input = json_decode($this->input->post('data'), true);
		$clientes = $this->m_control->get_cliente_HT($input);

		$clientesRefactorizado = [];
		foreach ($clientes as $row) {
			if (!in_array($row['idCliente'], $clientesRefactorizado)) $clientesRefactorizado[] = $row['idCliente'];
		}
		$clientes = !empty($clientesRefactorizado) ? $clientesRefactorizado : [' '];
		
		$result['data'] = $clientes;

		echo json_encode($result);
	}

	public function get_banner_HT(){
		$result = $this->result;

		$input = json_decode($this->input->post('data'), true);
		$banner = $this->m_control->get_banner_HT($input);

		$bannerRefactorizado = [];
		foreach ($banner as $row) {
			if (!in_array($row['nombre'], $bannerRefactorizado)) $bannerRefactorizado[] = $row['nombre'];
		}
		$banner = !empty($bannerRefactorizado) ? $bannerRefactorizado : [' '];
		$result['data'] = $banner;
		echo json_encode($result);
	}

	public function actualizarAnuncion(){
		$result = $this->result;
		$data=json_decode($this->input->post('data'),true);
		// $this->m_control->actualizarAnuncio(['idUsuario'=> $this->idUsuario]);
		$this->session->set_userdata('anuncioVisto', 1);
		echo json_encode($result);
	}
	
	public function getAnuncios(){
		$result = $this->result;
		$anuncioVisto = $this->session->userdata('anuncioVisto');

		$result['result'] = 0;
		$result['data']['html'] = $this->load->view('core/AvisoConfidencialidad',[],true);
		if($anuncioVisto == 0){
			$result['result'] = 1;
		}
		echo json_encode($result);
	}

	public function get_cuenta(){
		$result = $this->result;

			$result['data']['idCuenta'] = $this->sessIdCuenta;
			$result['data']['idProyecto'] = $this->sessIdProyecto;

			$result['data']['cuenta'] = $this->m_control->get_cuenta();
			
			if( !empty($this->sessIdCuenta) ){
				$result['data']['proyecto'] = $this->m_control->get_proyecto([ 'idCuenta' => $this->sessIdCuenta ]);
			}

		echo json_encode($result);
	}

	public function get_cuentaProyecto(){
		$result = $this->result;
			$input = json_decode($this->input->post('data'), true);

			$result['data']['idProyecto'] = $this->session->userdata('idProyecto');
			$result['data']['proyecto'] = $this->m_control->get_proyecto($input);

		echo json_encode($result);
	}

	public function guardarCambioCuenta(){
		$result = $this->result;
			$input = json_decode($this->input->post('data'), true);
			$query = $this->m_control->get_cuentaProyecto($input);

			$this->session->set_userdata($query);
			$result['result'] = 1;

		echo json_encode($result);
	}

	public function mostrarMaps(){
		//Datos Generales
		$data['lati_1']=$this->input->post('lati_1');
		$data['long_1']=$this->input->post('long_1');
		$data['lati_2']=$this->input->post('lati_2');
		$data['long_2']=$this->input->post('long_2');
		$data['modulo']=$this->input->post('modulo');
        //Cargamos Vista
		$this->load->view("modulos/control/maps", $data);
    }

	public function mostrarFoto(){
		//Datos Generales
		$data['foto']=$this->input->post('foto');
		$data['modulo']=$this->input->post('modulo');
        //Cargamos Vista
		$this->load->view("modulos/control/foto", $data);
    }
	public function json_usuarios(){
		$result = $this->result;
		$get = $this->input->get();
		$result['items']= $this->m_control->get_usuarios_multi($get);

		echo json_encode($result);
	}
	public function json_pdv(){
		$result = $this->result;
		$get = $this->input->get();
		$result['items']= $this->m_control->get_clientes_json($get)->result_array();

		echo json_encode($result);
	}


	public function guardar_peticion_actualizar_visitas(){
		$result = $this->result;
			$input = json_decode($this->input->post('data'), true);
			

			$params=array();
			$params['fechaIni']=$input['fecIni'];
			$params['fechaFin']=$input['fecFin'];
			$params['idProyecto'] = $this->sessIdProyecto;
			$params['idUsuario'] =$this->session->userdata('idUsuario');
			$params['idCuenta'] =$this->session->userdata('idCuenta');

			$this->m_control->registrar_peticion_actualizar_visitas($params);
			
			$idPeticion = $this->db->insert_id();

			$params['idPeticion'] =$idPeticion;

			$this->m_control->registrar_peticion_actualizar_visitasDet($params);

			$result['result'] = 1;

		echo json_encode($result);
	}

	public function get_peticion_actualizar_visitas()
	{
		$result = $this->result;

		$input=array();
		$input['idProyecto'] = $this->sessIdProyecto;
		$rs_peticion=$this->m_control->get_peticion_actualizar_visitas($input);
		$ultima_pet=null;
		$ultima_pet_det=null;

		if($rs_peticion!=null){
			if($rs_peticion[0]!=null){
				$ultima_pet=$rs_peticion[0];
			}
		}

		$idPeticion = $ultima_pet['idPeticion'];
		$peticionesDet = [];
		if(!empty($idPeticion)){
			$ultima_pet_det = $this->m_control->get_peticion_actualizar_visitas_det(['idPeticion' => $idPeticion]);

			foreach($ultima_pet_det as $v){
				$peticionesDet[] = [
					'idPeticionDet' => $v['idPeticionDet'],
					'notificado' => 1,
				];
			}

			if(!empty($peticionesDet)){
				$rs = $this->m_control->actualizarNotificacionesPeticion($peticionesDet);
			}
		}

		$result['result'] = 1;
		$result['data']['peticionActualizarVisita'] = $ultima_pet;
		$result['data']['peticionActualizarVisitaDet'] = $ultima_pet_det;
		echo json_encode($result);
	}

	public function bat_peticion_actualizar_visitas(){
		execInBackground( RUTA_BAT.'carga_masiva procesar_peticiones_actualizar_visitas',$_POST);
	}



	
}
?>