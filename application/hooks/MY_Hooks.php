<?php defined('BASEPATH') OR exit('No direct script access allowed');

	function sessionTracking(){
		$CI =& get_instance();

		$queryCI = "SELECT CASE WHEN (@@OPTIONS | 256) = @@OPTIONS THEN 1 ELSE 0 END AS qi";
		$queries = $CI->db->queries ? $CI->db->queries : [];

		//Retirando query CI;
		if( array_search($queryCI, $queries) !== false ){
			$key = array_search($queryCI, $queries);
			unset($queries[$key]);
		}

		$input = [
				'idUsuario' => !empty($CI->session->userdata('idUsuario')) ? $CI->session->userdata('idUsuario') : null,
				'uri' => $CI->uri->ruri_string(),
				'controlador' => $CI->router->class,
				'metodo' => $CI->router->method,
				'ipAddress' => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
				'sessionId' => !empty($CI->sessId) ? $CI->sessId : null,
				'browser' => !empty($CI->agent->browser()) ? $CI->agent->browser() : null,
				'browserVer' => !empty($CI->agent->version()) ? $CI->agent->version() : null,
				'browserSess' => !empty(session_id()) ? session_id() : null,
				'platform' => !empty($CI->agent->platform()) ? $CI->agent->platform() : null,
				'fecha' => date('d/m/Y'),
				'hora' => date('H:i:s')
			];
		if(isset( $CI->aSessTrack)){
			$aSessTrack = $CI->aSessTrack;
		}
		if( empty($aSessTrack) ){
			$input['idAccion'] = empty($queries) ? 4 : null;
			$CI->db->insert('web.sessionTracking', $input);
		}
		else{
			foreach($aSessTrack as $row){
				$input['idAccion'] = !empty($row['idAccion']) ? $row['idAccion'] : null;
				$input['tabla'] = !empty($row['tabla']) ? $row['tabla'] : null;
				$input['id'] = !empty($row['id']) ? $row['id'] : null;

				$CI->db->insert('web.sessionTracking', $input);
			}
		}

	}