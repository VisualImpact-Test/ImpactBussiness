<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Autorizacion extends MY_Model
{
	var $resultado = [
		'query' => '',
		'estado' => false,
		'id' => null,
		'msg' => ''
	];

	public function __construct()
	{
		parent::__construct();
	}


    public function getAutorizaciones($params = []){

		$filtros = "";
		$filtros .= !empty($params['id']) ? " AND a.idAutorizacion = {$params['id']}" : '';
		$filtros .= !empty($params['idCotizacion']) ? " AND a.idCotizacion IN({$params['idCotizacion']})" : '';
        $sql = "
			SELECT
				a.idAutorizacion,
                at.nombre tipoAutorizacion,
                ae.nombre estadoAutorizacion,
				a.idAutorizacionEstado,
				a.idCotizacionDetalle,
                a.nombre,
                c.codCotizacion,
                a.comentario,
                a.idUsuarioReg,
                ISNULL(u.nombres,'') + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'') usuario ,
                CONVERT(VARCHAR, a.fechaCreacion, 103) AS fechaCreacion,
                CONVERT(VARCHAR, a.fechaModificacion, 103) AS fechaModificacion,
				p.nroDocumento rucProveedor,
				p.razonSocial proveedor,
				cd.nombre item,
				cd.costo,
				cd.costoAnterior,
				a.nuevoValor,
				a.nuevoGap
			FROM compras.autorizacion a 
            JOIN compras.autorizacionEstado ae ON ae.idAutorizacionEstado = a.idAutorizacionEstado
            JOIN sistema.usuario u ON a.idUsuarioReg = u.idUsuario 
			JOIN compras.cotizacion c ON c.idCotizacion = a.idCotizacion 
			JOIN compras.cotizacionDetalle cd ON a.idCotizacionDetalle = cd.idCotizacionDetalle
			JOIN compras.proveedor p ON p.idProveedor = cd.idProveedor
			JOIN compras.autorizacionTipo at ON at.idTipoAutorizacion = a.idTipoAutorizacion
			WHERE a.estado = 1
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
    }
	public function insertarAutorizacionAnexos($data = []){
		$insert = true;

		foreach($data['anexos'] as $archivo){
			$archivoName = $this->saveFileWasabi($archivo);
			$tipoArchivo = explode('/',$archivo['type']);
			$insertArchivos[] = [
				'idAutorizacion' => $data['idAutorizacion'],
				'idTipoArchivo' => $tipoArchivo[0] == 'image' ? TIPO_IMAGEN : TIPO_PDF,
				'nombre_inicial' => $archivo['name'],
				'nombre_archivo' => $archivoName,
				'nombre_unico' => $archivo['nombreUnico'],
				'extension' => $tipoArchivo[1],
				'estado' => true,
				'idUsuarioReg' => $this->idUsuario,
				'flag_anexo' => true,
			];
		}

		if(!empty($insertArchivos)){
			$insert = $this->db->insert_batch('compras.cotizacionDetalleArchivos', $insertArchivos);
		}

		return $insert;
	}

	public function obtenerInformacionAutorizacionArchivos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idAutorizacion']) ? " AND a.idAutorizacion IN (" . $params['idAutorizacion'] . ")" : "";

		$sql = "
			SELECT
				a.idAutorizacion,
				cda.idCotizacionDetalleArchivo,
				cda.idTipoArchivo,
				cda.nombre_inicial,
				cda.nombre_archivo,
				cda.extension
			FROM
			compras.autorizacion a
			JOIN compras.cotizacionDetalleArchivos cda ON a.idAutorizacion = cda.idAutorizacion
			WHERE
			1 = 1
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
