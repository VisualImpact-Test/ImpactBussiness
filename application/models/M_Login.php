<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Login extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function encontrarUsuario($input)
	{
		$filtros = "";
		$value = array($input['usuario'], $input['clave']);
		$sql = "
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = ?, @clave VARCHAR(250) = ?;
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.archFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
			FROM
				sistema.usuario u
				JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
					AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
				LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.usuario = @usuario
				AND u.claveEncriptada = HASHBYTES('SHA1', @clave)
				AND u.estado = 1
				AND u.flag_activo = 1
				$filtros
			;
		";
		return $this->db->query($sql, $value);
	}

	public function encontrarMenu($input)
	{
		$value = array($input['idUsuario']);
		$sql = "
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
				sistema.usuarioMenu amo
				JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
				JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
				LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1	
			WHERE
				amo.idUsuario = ?
				AND amo.estado = 1
			ORDER BY gm.orden,mo.nombre
			";
		return $this->db->query($sql, $value);
	}

	public function navbar_permiso($idUsuario)
	{
		$value = array($idUsuario);
		$sql = "
			select
			umo.idUsuarioMenuOpcion, umo.idUsuario, umo.idMenuOpcion, mo.page
			from sistema.usuarioMenu umo
			join sistema.menu mo on mo.idMenuOpcion = umo.idMenuOpcion
			where umo.estado='1' and umo.idUsuario=?
			";
		return $this->db->query($sql, $value);
	}

	public function verificar_usuario($data = [])
	{
		$sql = "
			DECLARE @fecha DATE = GETDATE();
			SELECT
				u.idUsuario
				, u.idTipoDocumento
				, u.numDocumento
				, u.usuario
				, u.clave
				, u.nombres
				, u.apePaterno
				, u.apeMaterno
				, u.estado
				, u.externo
				, u.idEmpleado
				, u.fechaCreacion
				, u.fechaModificacion
				, u.intentos
				, u.ultimo_cambio_pwd
				, u.flag_activo
				, u.claveEncriptada
				, e.email
				, e.email_corp
			FROM sistema.usuario u
			JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
			LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent
			WHERE u.estado = 1 AND u.usuario = '" . $data['usuario'] . "'
		";

		return $this->db->query($sql)->row_array();
	}

	public function actualizar_intentos($data = [])
	{
		$this->db->trans_begin();

		$update = array(
			'intentos' => isset($data['intentos']) ? $data['intentos'] + 1 : '0',
		);

		if (isset($data['intentos']) and $data['intentos'] + 1 >= 3) {
			$update['flag_activo'] = 0;
		}

		$this->db->where('idUsuario', $data['idUsuario']);
		$result = $this->db->update('sistema.usuario', $update);

		if ($this->db->trans_status() === FALSE || !$result) {
			$this->db->trans_rollback();
			return 0;
		} else {
			$this->db->trans_commit();
			return 1;
		}
	}

	public function insertar_auditoria($data = [])
	{
		$this->db->trans_begin();

		$result = $this->db->insert('sistema.usuario_auditoria_ingreso', $data);

		if ($this->db->trans_status() === FALSE || !$result) {
			$this->db->trans_rollback();
			return 0;
		} else {
			$this->db->trans_commit();
			return 1;
		}
	}

	public function registrar_intentos($data = [])
	{
		$CI = &get_instance();

		$usuario = $this->db->get_where('sistema.usuario', ['usuario' => $data['usuario']])->row_array();

		$input = [
			'idUsuario' => !empty($usuario['idUsuario']) ? $usuario['idUsuario'] : NULL,
			'ipAddress' => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL,
			'browser' => !empty($CI->agent->browser()) ? $CI->agent->browser() : NULL,
			'browserVer' => !empty($CI->agent->version()) ? $CI->agent->version() : NULL,
			'platform' => !empty($CI->agent->platform()) ? $CI->agent->platform() : NULL,
			'nro_intento' => !empty($usuario)  ? ($usuario['intentos'] + 1) : NULL,
		];

		return $this->db->insert('web.sessionFailedAttemps', $input);
	}

	public function encontrarUsuarioLogeado($input)
	{
		$filtros = "";
		$value = array($input['usuario']);
		$sql = "
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = ?;
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.archFoto foto
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
			FROM
				sistema.usuario u
				JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario 
					AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
				LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.idUsuario = @usuario
				AND u.estado = 1
				AND u.demo = 0
				$filtros
			;
		";
		return $this->db->query($sql, $value);
	}
}
