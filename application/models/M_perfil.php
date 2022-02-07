<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_perfil extends MY_Model{

	var $lastId;
	var $lastQuery;

	public function __construct(){
		parent::__construct();
	}

	public function query($sql){
		$this->db->query($sql);
    }
    public function getDatosDeUsuario($idUsuario)
	{
		$sql = "
            SELECT td.breve, 
            u.numDocumento, 
            u.nombres, 
            u.apePaterno, 
            u.apeMaterno, 
            emp.email_corp,
            emp.telefono,
            emp.celular,
            emp.archFoto
        FROM trade.usuario u
            INNER JOIN trade.usuario_tipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
            INNER JOIN rrhh.dbo.empleado emp ON emp.numTipoDocuIdent = u.numDocumento
        WHERE u.idUsuario = $idUsuario;
		";
		return $this->db->query($sql);
	}
}