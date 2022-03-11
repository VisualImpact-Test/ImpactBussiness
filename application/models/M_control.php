<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_control extends MY_Model{

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


}
