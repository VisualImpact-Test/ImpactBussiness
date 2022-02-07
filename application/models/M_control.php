<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_control extends MY_Model{

	public function __construct(){
		parent::__construct();
	}

	public function get_cuenta($input = array()){
		$column = "";

		!empty($input['idCuenta']) ? $column = ",'{$input['idCuenta']}' as idSelect ":'';

		$filtro = "";
			$filtro .= getPermisos('cuenta');

		$sql = "
			DECLARE @fecha date=getdate();
			SELECT DISTINCT c.idCuenta AS id
			,c.nombre {$column}
			,COUNT(p.idProyecto) OVER (PARTITION BY uh.idUsuario) proyectos
			FROM trade.usuario_historico uh 
			JOIN trade.proyecto p ON p.idProyecto = uh.idProyecto
			JOIN trade.cuenta c ON c.idCuenta = p.idCuenta
			WHERE uh.estado = 1 
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
			AND uh.idUsuario = ".$this->session->userdata('idUsuario');

		return $this->db->query($sql)->result_array();
	}

	public function get_cuentaProyecto($input = []){
		$sql = "
			DECLARE @fecha date=getdate();
			SELECT
				cu.idCuenta,
				cu.nombre AS cuenta,
				cu.urlCSS AS cssCuenta,
				cu.urlLogo AS logoCuenta,
				cu.baseDatos AS sessBDCuenta,
				py.idProyecto,
				py.nombre AS proyecto,
				ISNULL(cu.abreviacion,'CUENTA') abreviacionCuenta,
				uh.idUsuarioHist,
				uh.idTipoUsuario
				
			FROM trade.cuenta cu
			JOIN trade.proyecto py ON cu.idCuenta = py.idCuenta
			JOIN trade.usuario_historico uh ON py.idProyecto = uh.idProyecto
				AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
				AND uh.idAplicacion IN(2)
				AND uh.estado = 1
			WHERE cu.idCuenta = {$input['idCuenta']}
			AND py.idProyecto = {$input['idProyecto']}
			AND uh.idUsuario = ".$this->session->userdata('idUsuario');

		return $this->db->query($sql)->row_array();
	}

	public function get_proyecto($input = array()){

		$columna = "";

		!empty($input['idProyecto']) ? $columna = ",'{$input['idProyecto']}' as idSelect ":'';

		$filtro = "";
			// $filtro .= getPermisos('proyecto');

			if( !empty($input['idCuenta']) ){
				$filtro .= " AND py.idCuenta = {$input['idCuenta']}";
			}

		$sql = "
		DECLARE @fecha date=GETDATE();
		SELECT DISTINCT py.idProyecto AS id
		, py.nombre $columna 
		FROM trade.proyecto py 
		JOIN trade.usuario_historico uh ON py.idProyecto = uh.idProyecto
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
			AND uh.idAplicacion IN(2)
			AND uh.estado = 1
		WHERE py.estado = 1{$filtro} 
		AND uh.idUsuario = ".$this->session->userdata('idUsuario')."
		ORDER BY py.nombre";

		return $this->db->query($sql)->result_array();
	}

	public function get_zona($input = array()){
		$idProyecto = $this->sessIdProyecto;
		$filtro = "";
			$filtro .= getPermisos('zona', $idProyecto);

			if( !empty($input['idZona']) ){
				$filtro .= " AND z.idZona = {$input['idZona']}";
			}

			if(!empty($idProyecto)){
				$filtro .= " AND z.idProyecto = {$idProyecto}";
			}



		$sql = "SELECT DISTINCT z.idZona AS id, z.nombre FROM trade.zona z WHERE estado = 1{$filtro} ORDER BY z.nombre";
		
		return $this->db->query($sql)->result_array();
	}

	public function get_plaza($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('plaza', $idProyecto);

			if( !empty($input['idPlaza']) ){
				$filtro .= " AND pl.idPlaza = {$input['idPlaza']}";
			}

		$sql = "SELECT pl.idPlaza AS id, pl.nombre FROM trade.plaza pl WHERE pl.estado = 1 AND pl.flagMayorista = 1{$filtro} ORDER BY pl.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_grupoCanal($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('grupoCanal', $idProyecto);

			if( !empty($idProyecto) ) $filtro .= " AND pgc.idProyecto = ".$idProyecto;
			if( !empty($input['idGrupoCanal']) ) $filtro .= " AND gca.idGrupoCanal = ".$input['idGrupoCanal'];

		$sql = "
			SELECT
				gca.idGrupoCanal AS id,
				gca.nombre
			FROM trade.grupoCanal gca
			JOIN trade.proyectoGrupoCanal pgc ON  gca.idGrupoCanal = pgc.idGrupoCanal AND pgc.estado = 1
			WHERE gca.estado = 1{$filtro}
			ORDER BY gca.nombre
		";

		return $this->db->query($sql)->result_array();
	}

	public function get_canal($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('canal', $idProyecto);

			if( !empty($idProyecto) ) $filtro .= " AND pgc.idProyecto = ".$idProyecto;
			if( !empty($input['idGrupoCanal']) ) $filtro .= " AND ca.idGrupoCanal = ".$input['idGrupoCanal'];
			if( !empty($input['idCanal']) ) $filtro .= " AND ca.idCanal = ".$input['idCanal'];

		$sql = "
			SELECT ca.idCanal AS id, ca.nombre
			FROM trade.canal ca 
			JOIN trade.proyectoGrupocanal pgc ON pgc.idGrupoCanal = ca.idGrupoCanal
			WHERE ca.estado = 1 
			{$filtro} 
			ORDER BY ca.nombre
		";

		return $this->db->query($sql)->result_array();
	}

	public function get_subCanal($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('subCanal', $idProyecto);

			if( !empty($input['idCanal']) ){
				$filtro .= " AND sca.idCanal = {$input['idCanal']}";
			}
			if( !empty($input['idSubCanal']) ){
				$filtro .= " AND sca.idSubCanal = {$input['idSubCanal']}";
			}

		$sql = "SELECT sca.idSubCanal AS id, sca.nombre FROM trade.subCanal sca WHERE sca.estado = 1{$filtro} ORDER BY sca.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_distribuidora($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('distribuidora', $idProyecto);

			if( !empty($input['idDistribuidora']) ){
				$filtro .= " AND d.idDistribuidora = {$input['idDistribuidora']}";
			}

		$sql = "SELECT d.idDistribuidora AS id, d.nombre FROM trade.distribuidora d WHERE d.estado = 1{$filtro} ORDER BY d.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_distribuidoraSucursal($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('distribuidoraSucursal', $idProyecto);

			if( !empty($input['idDistribuidora']) ){
				$filtro .= " AND ds.idDistribuidora = {$input['idDistribuidora']}";
			}
			if( !empty($input['idDistribuidoraSucursal']) ){
				$filtro .= " AND ds.idDistribuidoraSucursal = {$input['idDistribuidoraSucursal']}";
			}

		$sql = "
			SELECT DISTINCT
				ds.idDistribuidoraSucursal AS id,
				ubi.provincia +' - '+ ubi.distrito AS nombre
			FROM trade.distribuidoraSucursal ds
			JOIN General.dbo.ubigeo ubi ON ds.cod_ubigeo = ubi.cod_ubigeo
			WHERE ds.estado = 1{$filtro}
			ORDER BY nombre;
		";
		return $this->db->query($sql)->result_array();
	}

	public function get_cadena($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
		$filtro .= getPermisos('cadena', $idProyecto);

		if( !empty($input['idCadena']) ){
			$filtro .= " AND cd.idCadena = {$input['idCadena']}";
		}

		$cliente_historico = getClienteHistoricoCuenta();			
		$sql = "SELECT DISTINCT
				cd.idCadena AS id, 
				cd.nombre 
				FROM trade.cadena cd 
				JOIN trade.banner b ON b.idCadena = cd.idCadena
				JOIN trade.segmentacionClienteModerno segmod ON segmod.idBanner = b.idBanner
				JOIN {$cliente_historico} ch ON segmod.idSegClienteModerno = ch.idSegClienteModerno
				WHERE cd.estado = 1{$filtro} 
				ORDER BY cd.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_banner($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('banner', $idProyecto);

			if( !empty($input['idCadena']) ){
				$filtro .= " AND bn.idCadena = {$input['idCadena']}";
			}
			if( !empty($input['idBanner']) ){
				$filtro .= " AND bn.idBanner = {$input['idBanner']}";
			}

		$sql = "SELECT bn.idBanner AS id, bn.nombre FROM trade.banner bn WHERE bn.estado = 1{$filtro} ORDER BY bn.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_encargado($input = array()){
		$result = array();

			if( empty($input['idProyecto']) ){
				goto responder;
			}

			$fecIni = "GETDATE()";
			$fecFin = "GETDATE()";

			if( !empty($input['fechas']) ){
				$fechas = explode('-', str_replace(' ', '', $input['fechas']));
				if( !empty($fechas[0]) ){
					$fecIni = $fechas[0];
				}
				if( !empty($fechas[1]) ){
					$fecFin = $fechas[1];
				}
			}

			$sql = "SELECT idEncargado FROM trade.encargado WHERE estado = 1 AND idUsuario = {$this->idUsuario}";
			$query = $this->db->query($sql)->result_array();

			$filtro = "";
			if( !empty($query) ){
				$filtro .= " AND u.idUsuario = {$this->idUsuario}";
			}
			else{
				$permisos = getPermisos(array('cuenta', 'proyecto', 'grupoCanal', 'canal', 'subCanal'), $input['idProyecto']);
				if( !empty($permisos) ){
					$filtro .= "\n\t\t\t\t";
					$filtro .= substr($permisos, 1, strlen($permisos));
				}

				$filtroGrupoCanal = array();

					// GRUPO CANAL - TRADICIONAL
					$tradicional = getPermisos(array('zona', 'distribuidoraSucursal'), $input['idProyecto']);
						$tradicional = substr($tradicional, 5, strlen($tradicional));
						$tradicional = str_replace(" AND ", " OR ", $tradicional);

						if( !empty($tradicional) ){
							$filtroGrupoCanal[] = "(gca.idGrupoCanal IN (1,4) AND ({$tradicional}))";
						}

					// GRUPO CANAL - MAYORISTA
					$mayorista = getPermisos(array('plaza'), $input['idProyecto']);
						$mayorista = substr($mayorista, 5, strlen($mayorista));
						$mayorista = str_replace(" AND ", " OR ", $mayorista);
					
						if( !empty($mayorista) ){
							$filtroGrupoCanal[] .= "(gca.idGrupoCanal IN (5) AND ({$mayorista}))";
						}

					// GRUPO CANAL - MODERNO
					$moderno = getPermisos(array('banner'), $input['idProyecto']);
						$moderno = substr($moderno, 5, strlen($moderno));
						$moderno = str_replace(" AND ", " OR ", $moderno);

						if( !empty($moderno) ){
							$filtroGrupoCanal[] .= "(gca.idGrupoCanal IN (2) AND ({$moderno}))";
						}

				if( !empty($filtroGrupoCanal) ){
					$filtro .= "\n\t\t\t\t";
					$filtro .= "AND (";
						$filtro .= "\n\t\t\t\t\t";
							$filtro .= implode(' OR '."\n\t\t\t\t\t", $filtroGrupoCanal);
					$filtro .= "\n\t\t\t\t";
					$filtro .= ")";
				}
			}

			$aWhere = array(
					'idGrupoCanal' => 'gca.idGrupoCanal',
					'idCanal' => 'ca.idCanal',
					'idSubCanal' => 'sca.idSubCanal',
					'idZona' => 'z.idZona',
					'idDistribuidora' => 'd.idDistribuidora',
					'idDistribuidoraSucursal' => 'ds.idDistribuidoraSucursal',
					'idPlaza' => 'pl.idPlaza',
					'idCadena' => 'cd.idCadena',
					'idBanner' => 'bn.idBanner'
				);

			$sWhere = "";
			foreach($aWhere as $k_w => $v_w){
				if( !empty($input[$k_w]) ) {
					$sWhere .= " AND {$v_w} = {$input[$k_w]}";
				}
			}

			if( !empty($sWhere) ){
				$filtro .= "\n\t\t\t\t";
				$filtro .= $sWhere;
			}

			$sql = "
				DECLARE
					@fecIni DATE = {$fecIni},
					@fecFin DATE = {$fecFin};

				SELECT DISTINCT
					e.idEncargado AS id,
					ISNULL(u.apePaterno + ' ', '') + ISNULL(u.apeMaterno + ' ', '') + ISNULL(u.nombres + ' ', '') AS nombre
				FROM trade.encargado e
				JOIN trade.usuario u ON e.idUsuario = u.idUsuario
				JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
				LEFT JOIN trade.usuario_historicoCanal uca ON uh.idUsuarioHist = uca.idUsuarioHist
				LEFT JOIN trade.usuario_historicoZona uz ON uh.idUsuarioHist = uz.idUsuarioHist
				LEFT JOIN trade.usuario_historicoPlaza upl ON uh.idUsuarioHist = upl.idUsuarioHist
				LEFT JOIN trade.usuario_historicoDistribuidoraSucursal uds ON uh.idUsuarioHist = uds.idUsuarioHist
				LEFT JOIN trade.usuario_historicoBanner ubn ON uh.idUsuarioHist = ubn.idUsuarioHist
				LEFT JOIN trade.proyecto py ON uh.idProyecto = py.idProyecto
				LEFT JOIN trade.cuenta cu ON py.idCuenta = cu.idCuenta
				LEFT JOIN trade.canal ca ON uca.idCanal = ca.idCanal
				LEFT JOIN trade.grupoCanal gca ON gca.idGrupoCanal = ca.idGrupoCanal
				LEFT JOIN trade.zona z ON uz.idZona = z.idZona
				LEFT JOIN trade.plaza pl ON upl.idPlaza = pl.idPlaza
				LEFT JOIN trade.distribuidoraSucursal ds ON uds.idDistribuidoraSucursal = ds.idDistribuidoraSucursal
				LEFT JOIN trade.distribuidora d ON ds.idDistribuidora = d.idDistribuidora
				LEFT JOIN trade.banner bn ON ubn.idBanner = bn.idBanner
				LEFT JOIN trade.cadena cd ON bn.idCadena = cd.idCadena
				WHERE fn.datesBetween(uh.fecIni, uh.fecFin, @fecIni, @fecFin) = 1
				AND e.estado = 1 AND u.estado = 1{$filtro}
			";

			$result = $this->db->query($sql)->result_array();

		responder:
		return $result;
	}

	public function get_colaborador($input = array()){
		$fecIni = "GETDATE()";
		$fecFin = "GETDATE()";

			if( !empty($input['fechas']) ){
				$fechas = explode('-', str_replace(' ', '', $input['fechas']));
				if( !empty($fechas[0]) ){
					$fecIni = $fechas[0];
				}
				if( !empty($fechas[1]) ){
					$fecFin = $fechas[1];
				}
			}

		$filtro = "";
			if( !empty($input['idEncargado']) ){
				$filtro .= " AND e.idEncargado = {$input['idEncargado']}";
			}

		$sql = "
			DECLARE
				@fecIni DATE = {$fecIni},
				@fecFin DATE = {$fecFin};

			SELECT DISTINCT
				u.idUsuario AS id,
				ISNULL(u.apePaterno + ' ', '') + ISNULL(u.apeMaterno + ' ', '') + ISNULL(u.nombres + ' ', '') AS nombre
			FROM trade.encargado e
			JOIN trade.encargado_usuario eu ON e.idEncargado = eu.idEncargado
			JOIN trade.usuario u ON eu.idUsuario = u.idUsuario
			JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
			WHERE fn.datesBetween(uh.fecIni, uh.fecFin, @fecIni, @fecFin) = 1
			AND e.estado = 1 AND u.estado = 1{$filtro}
		";
		return $this->db->query($sql)->result_array();
	}

	public function get_elemento_iniciativa_HT($input = array()){

		$filtro = "";
			if( !empty($input['iniciativa']) ){
				$filtro .= " AND ev.nombre LIKE '{$input['iniciativa']}'";
			}
		$sql = " 
		SELECT distinct mev.idElementoVis AS id, ele.nombre 
		FROM {$this->sessBDCuenta}.trade.iniciativaTradElemento mev 
		JOIN {$this->sessBDCuenta}.trade.iniciativaTrad ev ON ev.idIniciativa=mev.idIniciativa
		JOIN trade.elementoVisibilidadTrad ele ON ele.idElementoVis=mev.idElementoVis
		WHERE ev.estado = 1 {$filtro}
		 ORDER BY ele.nombre ";
		return $this->db->query($sql)->result_array();
	}

	public function get_canal_HT($input = array()){

		$filtro = "";
			if( !empty($input['grupoCanal']) ){
				$filtro .= " AND gc.nombre LIKE '{$input['grupoCanal']}'";
			}
		$sql = "SELECT ca.idCanal AS id, ca.nombre FROM trade.canal ca 
		JOIN trade.grupoCanal gc ON gc.idGrupoCanal=ca.idGrupoCanal
		WHERE ca.estado = 1 {$filtro} ORDER BY ca.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_cliente_HT($input = array()){
		$sql = "
			DECLARE @fecha date=getdate(), @idCanal INT ;
			select @idCanal=idCanal FROM trade.canal where nombre like '".$input['canal']."';

			select DISTINCT ch.idCliente from trade.cliente c 
			JOIN {$this->sessBDCuenta}.trade.cliente_historico ch ON ch.idCliente=c.idCliente
			and @fecha BETWEEN ch.fecIni and ISNULL(ch.fecFin,@fecha) AND ch.estado=1

			JOIN trade.segmentacionNegocio sn ON sn.idSegNegocio=ch.idSegNegocio
			left join trade.subCanal sc ON sc.idSubCanal=sn.idSubCanal
			WHERE 
				((sn.idSubCanal IS NULL AND sn.idCanal=@idCanal) OR (sn.idSubCanal IS NOT NULL AND sc.idCanal=@idCanal) )
			";
		return $this->db->query($sql)->result_array();
	}

	public function get_banner_HT($input = array()){
		$sql = "SELECT b.idBanner,b.nombre from trade.banner b
		JOIN trade.cadena c ON c.idCadena=b.idCadena
		where c.nombre like '".$input['cadena']."' ";
		return $this->db->query($sql)->result_array();
	}

	public function actualizarAnuncio($params =[]){
		return $this->db->update('trade.usuario',['flag_anuncio_visto'=>1],['idUsuario'=>$params['idUsuario']]);
	}

	public function get_tipoCliente($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
			$filtro .= getPermisos('cadena', $idProyecto);

			if( !empty($input['idCanal']) ){
				$filtro .= " AND ct.idCanal = {$input['idCanal']}";
			}
			if( !empty($input['idClienteTipo']) ){
				$filtro .= " AND ct.idClienteTipo = {$input['idClienteTipo']}";
			}

		$sql = "SELECT ct.idClienteTipo AS id, ct.nombre FROM trade.cliente_tipo ct WHERE ct.estado = 1{$filtro} ORDER BY ct.nombre";
		return $this->db->query($sql)->result_array();
	}
	public function get_tab_menu_opcion($input = [])
	{
		$idProyecto = $this->sessIdProyecto;

		$sql = "
		SELECT 
		*
		FROM
		trade.tabMenuOpcionProyecto
		WHERE 
		idProyecto = {$idProyecto}
		AND estado = 1
		AND idMenuOpcion = {$input['idMenuOpcion']}
		ORDER BY orden
		";
		
		return $this->db->query($sql);
	}
	public function get_tiposUsuario($input = array()){
		$idProyecto = $this->sessIdProyecto;
		$idCuenta = $this->sessIdCuenta;
		$filtro = "";

		if( !empty($idCuenta) ){
			$filtro .= " AND tuc.idCuenta = {$idCuenta}";
		}

		$sql = "SELECT 
				tu.idTipoUsuario AS id, 
				tu.nombre 
				FROM trade.usuario_tipo tu 
				JOIN trade.tipoUsuarioCuenta tuc ON tuc.idTipoUsuario = tu.idTipoUsuario
				WHERE tu.estado = 1{$filtro} 
				ORDER BY tu.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function get_usuarios($params = [])
	{
		
		$idCuenta = $this->sessIdCuenta;
		$idProyecto = $this->sessIdProyecto;
	
		$filtros = '';
		$demo = $this->demo;$filtro_demo = '';
		if(!$demo){
			$filtro_demo = " AND u.demo = 0";
		}
		!empty($idProyecto) ? $filtros .= " AND uh.idProyecto = {$idProyecto}" : "";
		!empty($params['idTipousuario']) ? $filtros .= " AND uh.idTipoUsuario = {$params['idTipousuario']}" : "";
		$sql = "
		DECLARE @fecha DATE = GETDATE();
		SELECT DISTINCT 
		u.idUsuario id
		,ISNULL((ISNULL(u.nombres,'') + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'')),' ')  nombre
		FROM trade.usuario u
		JOIN trade.usuario_historico uh ON uh.idUsuario = u.idUsuario 
			--AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecha,@fecha) = 1
		WHERE u.estado=1
		{$filtros}
		{$filtro_demo}
		ORDER BY u.idUsuario DESC";
		return $this->db->query($sql)->result_array();
	}
	public function get_usuarios_multi($params = [])
	{
		
		$idCuenta = $this->sessIdCuenta;
		$idProyecto = $this->sessIdProyecto;
	
		$filtros = '';
		$demo = $this->demo;$filtro_demo = '';
		if(!$demo){
			$filtro_demo = " AND u.demo = 0";
		}
		!empty($params['input']) ? $filtros .= " AND (u.nombres LIKE('%".$params['input']."%') 
												OR u.apePaterno LIKE('%".$params['input']."%') 
												OR u.apeMaterno LIKE('%".$params['input']."%')
												OR u.idUsuario LIKE('%".$params['input']."%')
												OR u.numDocumento LIKE('%".$params['input']."%')
												)": '' ;
		$sql = "
		SELECT DISTINCT 
		u.idUsuario id
		,CONVERT(varchar,u.idUsuario) + ' - ' + ISNULL((ISNULL(u.nombres,'') + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'')),' ') +  ' - ' +ISNULL(u.numDocumento,' ') text
		FROM trade.usuario u
		WHERE u.estado=1
		{$filtros}
		{$filtro_demo}
		ORDER BY u.idUsuario DESC";
		return $this->db->query($sql)->result_array();
	}

	public function getPermisosTradicional($input = array()){
		$idProyecto = $this->sessIdProyecto;
		$sessIdHistorico = $this->idUsuarioHist;

		$filtro = "";
			!empty($sessIdHistorico) ? $filtro.= " AND ds.idUsuarioHist = {$sessIdHistorico} " : '' ;
		$sql = "
			SELECT idDistribuidoraSucursal FROM trade.usuario_historicoDistribuidoraSucursal ds WHERE ds.estado = 1{$filtro}
		";
		return $this->db->query($sql)->result_array();
	}
	public function getPermisosMayorista($input = array()){
		$idProyecto = $this->sessIdProyecto;
		$sessIdHistorico = $this->idUsuarioHist;


		$filtro = "";
			!empty($sessIdHistorico) ? $filtro.= " AND hpz.idUsuarioHist = {$sessIdHistorico} " : '' ;
		$sql = "
		SELECT idPlaza FROM trade.usuario_historicoPlaza hpz WHERE hpz.estado = 1{$filtro}
		";
		return $this->db->query($sql)->result_array();
	}
	public function getPermisosModerno($input = array()){
		$idProyecto = $this->sessIdProyecto;
		$sessIdHistorico = $this->idUsuarioHist;

		$filtro = "";
			!empty($sessIdHistorico) ? $filtro.= " AND uhb.idUsuarioHist = {$sessIdHistorico} " : '' ;
		$sql = "
		SELECT idBanner FROM trade.usuario_historicoBanner uhb WHERE uhb.estado = 1{$filtro}
		";
		return $this->db->query($sql)->result_array();
	}
	public function get_frecuencia($input = array()){
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";

		$sql = "SELECT f.idFrecuencia AS id, f.nombre FROM trade.frecuencia f WHERE f.estado = 1{$filtro} ORDER BY f.nombre";
		return $this->db->query($sql)->result_array();
	}

	public function getColumnasAdicionales($params = []){
		$idCuenta = $this->sessIdCuenta;
		$idProyecto = $this->sessIdProyecto;

		$filtro = "";
		!empty($params['idGrupoCanal']) ? $filtro.= " AND cd.idGrupoCanal = {$params['idGrupoCanal']} " : '' ;

		$sql = "
		SELECT
			cd.idCampoDinamico
			, cd.idGrupoCanal
			, ca.columna
			, UPPER(fn.Split_On_Upper_Case(ca.columna)) AS header
		FROM trade.configuracion_campo_dinamico cd
		JOIN trade.aplicacion_modulo m ON cd.idModulo = m.idModulo
		JOIN trade.configuracion_campo_adicional ca ON cd.idCampoAdicional = ca.idCampoAdicional
		WHERE cd.estado = 1
		AND cd.idCuenta = {$idCuenta}
		AND cd.idProyecto = {$idProyecto}
		AND m.idModuloGrupo = {$params['idModulo']}
		{$filtro}
		";
		
		return $this->db->query($sql)->result_array();
	}

	public function getGrupoCanalDeVisita($params = []){
		$sql = "
		SELECT TOP 1
			c.idGrupoCanal
		FROM {$this->sessBDCuenta}.trade.data_visita v
		JOIN trade.canal c ON v.idCanal = c.idCanal
		WHERE v.idVisita  = {$params}
		";
		return $this->db->query($sql)->row_array();
	}

	public function get_clientes_json($params = [])
	{
		$idCuenta = $this->sessIdCuenta;
		$idProyecto = $this->sessIdProyecto;
	
		$filtros = '';
		$demo = $this->demo;$filtro_demo = '';

		!empty($params['input']) ? $filtros .= " AND (idCliente LIKE('%".$params['input']."%') 
												OR razonSocial LIKE('%".$params['input']."%') 
												OR nombreComercial LIKE('%".$params['input']."%') 
												)": '' ;
		$sql = "
		SELECT 
		idCliente id
		,CONVERT(VARCHAR,idCliente) + ' - '+razonSocial
		FROM
		trade.cliente
		WHERE estado = 1
		{$filtros}
		ORDER BY idCliente DESC";
		return $this->db->query($sql);
	}


	public function get_peticion_actualizar_visitas($input = array()){
		$sql = "
			DECLARE @fecha date=getdate();
			SELECT TOP 1  
				idUsuario,idProyecto,fechaIni,fechaFin,estado,porcentaje,
				idPeticion,
				CONVERT(varchar,hora,8) hora,
				CONVERT(varchar,fechaActualizacion,103) fechaActualizacion,
				CASE WHEN (porcentaje >= 100) THEN 1 ELSE 0 END actualizado
			FROM 
				{$this->sessBDCuenta}.trade.peticionActualizarVisitas
			WHERE 
			idProyecto={$input['idProyecto']}
			ORDER BY fechaActualizacion DESC,idPeticion DESC;";
		return $this->db->query($sql)->result_array();
	}


	public function get_peticiones_actualizar_visitas(){
		$sql = "
			DECLARE @fecha date=getdate();
			SELECT  idPeticion,idUsuario,idProyecto,fechaIni,fechaFin,estado,porcentaje,
				CONVERT(varchar,fechaActualizacion,103) fechaActualizacion,
				CONVERT(varchar,hora,8) hora,
				CASE WHEN (porcentaje IS NOT NULL) THEN 1 ELSE 0 END actualizado
			FROM 
				{$this->sessBDCuenta}.trade.peticionActualizarVisitas
			WHERE estado=1 and fechaActualizacion is null
			ORDER BY fechaActualizacion DESC,idPeticion DESC;;";
		return $this->db->query($sql)->result_array();
	}


	public function actualizar_visitas($post)
	{
		$sql = "
			WITH list_visitas AS (
				SELECT v.idVisita  
				FROM {$this->sessBDCuenta}.trade.data_ruta r 
				JOIN {$this->sessBDCuenta}.trade.data_visita v ON v.idRuta=r.idRuta
				WHERE r.idProyecto={$post['idProyecto']} and r.fecha='{$post['fecha']}'
			)
			UPDATE {$this->sessBDCuenta}.trade.data_visita
			SET estado=estado
			where idVisita IN (
				SELECT  idVisita FROM list_visitas
			);";
		return $this->db->query($sql)->result_array();
	}

	public function actualizar_peticion_estado($post)
	{

		$sql = "
			DECLARE @fecha date=GETDATE();
			UPDATE {$this->sessBDCuenta}.trade.peticionActualizarVisitas
				SET estado=0
			WHERE 
				idPeticion={$post['idPeticion']}
			;";
		return $this->db->query($sql);
	}

	public function actualizar_peticion($post)
	{

		$sql = "
			DECLARE @fecha date=GETDATE();
			UPDATE {$this->sessBDCuenta}.trade.peticionActualizarVisitas
				SET estado=0,fechaActualizacion=GETDATE(),hora=GETDATE(),porcentaje={$post['porcentaje']}
			WHERE 
				idPeticion={$post['idPeticion']}
			;";
		return $this->db->query($sql);
	}

	public function registrar_peticion_actualizar_visitas($post)
	{
		$insert = [
			'idProyecto' => trim($post['idProyecto']),
			'fechaIni' => trim($post['fechaIni']),
			'fechaFin' => trim($post['fechaFin']),
			'idUsuario' => trim($post['idUsuario']),
			'estado' => 1,
			'idCuenta' => trim($post['idCuenta']),
			'fechaActualizacion'=> getActualDateTime(),
			'porcentaje' =>0,
		];

		$insert = $this->db->insert("{$this->sessBDCuenta}.trade.peticionActualizarVisitas", $insert);
		return $insert;
	}

	public function registrar_peticion_actualizar_visitasDet($post)
	{
		
		$sql = "INSERT INTO {$this->sessBDCuenta}.trade.peticionActualizarVisitasDet (idPeticion,idRuta,estado,hora)
		SELECT '".$post['idPeticion']."',idRuta,1,null FROM {$this->sessBDCuenta}.trade.data_ruta WHERE fecha  BETWEEN '".trim($post['fechaIni'])."' AND '".trim($post['fechaFin'])."' AND idProyecto='".trim($post['idProyecto'])."'";

		$insert = $this->db->query($sql);
		return $insert;
	}


	public function obtenerUsuariosPermisosDistribuidoraSucursal($input)
	{
		$fecIni = $input['fecIni'];
		$fecFin = $input['fecFin'];

		$sql = "
		DECLARE @fecIni date='".$fecIni."',@fecFin date='".$fecFin."';
		SELECT DISTINCT
		u.idUsuario
		,ISNULL(u.nombres,'') + ' ' +ISNULL(u.apePaterno,'') + ' ' +ISNULL(u.apeMaterno,'') nombreUsuario
		,u.numDocumento
		,uhds.idDistribuidoraSucursal
		,ut.idTipoUsuario
		,ut.nombre tipoUsuario
		FROM trade.usuario u 
		JOIN trade.usuario_historico uh ON uh.idUsuario = u.idUsuario AND uh.idTipoUsuario IN(2,11,17) AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecIni,@fecFin)=1
		JOIN trade.usuario_tipo ut ON ut.idTipoUsuario = uh.idTipoUsuario
		JOIN trade.usuario_historicoDistribuidoraSucursal uhds ON uhds.idUsuarioHist = uh.idUsuarioHist AND uhds.estado = 1
		";
		return $this->db->query($sql)->result_array();
	}
	public function obtenerUsuariosPermisosPlaza($input)
	{
		$fecIni = $input['fecIni'];
		$fecFin = $input['fecFin'];

		$sql = "
		DECLARE @fecIni date='".$fecIni."',@fecFin date='".$fecFin."';
		SELECT DISTINCT
		u.idUsuario
		,ISNULL(u.nombres,'') + ' ' +ISNULL(u.apePaterno,'') + ' ' +ISNULL(u.apeMaterno,'') nombreUsuario
		,u.numDocumento
		,uhp.idPlaza
		,ut.idTipoUsuario
		,ut.nombre tipoUsuario
		FROM trade.usuario u 
		JOIN trade.usuario_historico uh ON uh.idUsuario = u.idUsuario AND uh.idTipoUsuario IN(2,11,17) AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecIni,@fecFin)=1
		JOIN trade.usuario_tipo ut ON ut.idTipoUsuario = uh.idTipoUsuario
		JOIN trade.usuario_historicoPlaza uhp ON uhp.idUsuarioHist = uh.idUsuarioHist
		";
		return $this->db->query($sql)->result_array();
	}
	public function obtenerUsuariosPermisosBanner($input)
	{
		$fecIni = $input['fecIni'];
		$fecFin = $input['fecFin'];

		$sql = "
		DECLARE @fecIni date='".$fecIni."',@fecFin date='".$fecFin."';
		SELECT DISTINCT
		u.idUsuario
		,ISNULL(u.nombres,'') + ' ' +ISNULL(u.apePaterno,'') + ' ' +ISNULL(u.apeMaterno,'') nombreUsuario
		,u.numDocumento
		,uhb.idBanner
		,ut.idTipoUsuario
		,ut.nombre tipoUsuario
		FROM trade.usuario u 
		JOIN trade.usuario_historico uh ON uh.idUsuario = u.idUsuario AND uh.idTipoUsuario IN(2,11,17) AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecIni,@fecFin)=1
		JOIN trade.usuario_tipo ut ON ut.idTipoUsuario = uh.idTipoUsuario
		JOIN trade.usuario_historicoBanner uhb ON uhb.idUsuarioHist = uh.idUsuarioHist
		";
		return $this->db->query($sql)->result_array();
	}

	public function getHideColProyecto($input = [])
	{
		$idCuenta = $this->sessIdCuenta;
		$idProyecto = $this->sessIdProyecto;
		$sql = "
			SELECT
			nombre
			FROM
			trade.hideColProyecto
			WHERE estado = 1
			AND idCuenta = {$idCuenta}
		UNION
			SELECT
			nombre
			FROM
			trade.hideColProyecto
			WHERE estado = 1
			AND idProyecto = {$idProyecto}
			;";
		
		$rs = $this->db->query($sql)->result_array();
		$hideCols = [];
		foreach ($rs as $k => $v) {
			$hideCols[$v['nombre']] = true;
		}

		return $hideCols;
	}

	public function get_peticion_actualizar_visitas_det($input = [])
	{
		$sql = "
		SELECT 
		pdet.idPeticionDet,
		r.idUsuario,
		r.idTipoUsuario,
		r.tipoUsuario, 
		r.fecha,
		u.nombres + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'') nombreUsuario
		FROM
		{$this->sessBDCuenta}.trade.peticionActualizarVisitasDet pdet
		JOIN {$this->sessBDCuenta}.trade.data_ruta r ON r.idRuta = pdet.idRuta
		JOIN trade.usuario u ON u.idUsuario = r.idUsuario
		WHERE 
		pdet.idPeticion = {$input['idPeticion']}
		AND pdet.notificado = 0
		AND hora IS NOT NULL
		AND (r.demo = 0 OR r.demo IS NULL)
		ORDER BY r.fecha, r.idUsuario
		";

		return $this->db->query($sql)->result_array();
	}
	
	public function actualizarNotificacionesPeticion($update){
		return $this->db->update_batch("{$this->sessBDCuenta}.trade.peticionActualizarVisitasDet",$update,'idPeticionDet');
	}
}
