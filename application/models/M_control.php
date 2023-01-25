<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_control extends MY_Model{

	var $resultado = [
		'query' => '',
		'estado' => false,
		'id' => null,
		'msg' => ''
	];

	public function __construct(){
		parent::__construct();
	}

	public function get_estados_cotizacion(){
		$sql = "
		SELECT
		ce.idCotizacionEstado id,
		ce.nombre,
		ce.descripcion,
		ce.descripcionPendiente
		FROM 
		ImpactBussiness.compras.cotizacionEstado ce
		WHERE 
		ce.estado = 1
		ORDER BY
		ce.orden
		";

		return $this->db->query($sql);
	}

	public function getUsuarios($params){

		$filtros = '';
		$filtros .= !empty($params['idUsuario']) ? " AND u.idUsuario IN({$params['idUsuario']})" : ""; 
		$filtros .= !empty($params['tipoUsuario']) ? " AND uh.idTipoUsuario IN({$params['tipoUsuario']})" : ""; 


		$sql = "
		DECLARE @hoy DATE = GETDATE();
		SELECT
		u.usuario,
		u.nombres + ' ' + u.apePaterno + ' ' + ISNULL(u.apeMaterno,'') nombreUsuario,
		u.email,
		ut.nombre tipoUsuario
		FROM 
		sistema.usuario u 
		JOIN sistema.usuarioHistorico uh ON u.idUsuario = uh.idUsuario
		JOIN sistema.usuarioTipo ut ON uh.idTipoUsuario = ut.idTipoUsuario
		WHERE 
		General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@hoy,@hoy) = 1
		{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}



		return $this->resultado;
	}

}
