<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Recover extends CI_Model
{
        var $CI;

        public function __construct()
        {
                parent::__construct();

                $this->CI =& get_instance();
        }
        public function getUsuarioActivo($id,$pwd){

                $sql = "
SELECT
*
FROM sistema.usuario
WHERE idUsuario = '".$id."'
AND claveEncriptada = HASHBYTES('SHA1', '".$pwd."')
                ";

                $rs = $this->db->query($sql);

                $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'sistema.usuario' ];
                return $rs;
        }

        public function getInformacionDeToken($token)
        {
                $this->db->select('token, idUsuario, fecha, hora, estado');
                $this->db->from('master.tokenClaves');
                $this->db->where('token', $token);

                $informacionDeToken = $this->db->get();

                $query_email = $this->db->last_query();

                $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'master.tokenClaves' ];
                return $informacionDeToken;
        }

        public function cambiarClave($update)
        {
                $value = array($update['nuevaClave'], $update['nuevaClave'], $update['idUsuario']);
                $resultado = $this->db->query("UPDATE sistema.usuario SET clave = ?, claveEncriptada = HASHBYTES('SHA1', ?) WHERE idUsuario = ?", $value);

                $this->CI->aSessTrack[] = [ 'idAccion' => 7, 'tabla' => 'sistema.usuario', 'id' => $update['idUsuario'] ];
                return $resultado;
        }

        public function getIdUsuario($email)
        {
                $sql =
                "
                SELECT
                u.idUsuario
                , u.email
                FROM sistema.usuario u
                WHERE u.estado = 1 AND ( u.email = ?)
                ";

                $idUsuario = $this->db->query($sql, ['email' => $email]);

                $query = $this->db->last_query();

                $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'sistema.usuario', 'id' => null ];
                return $idUsuario;
        }

        public function guardarToken($idUsuario, $token)
        {
                $data = array(
                        'token' => $token,
                        'idUsuario' => $idUsuario,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'), 
                        'estado' => 1,
                );

                $this->db->insert('master.tokenClaves', $data);
                $this->CI->aSessTrack[] = [ 'idAccion' => 6, 'tabla' => 'master.tokenClaves', 'id' => $this->db->insert_id() ];
        }

        public function actualizarTokens($idUsuario)
        {

                $this->db->set('estado', 0);
                $this->db->where('idUsuario', $idUsuario);

                $this->CI->aSessTrack[] = [ 'idAccion' => 7, 'tabla' => 'master.tokenClaves', 'id' => $idUsuario ];
                $resultado = $this->db->update('master.tokenClaves');
        }

        public function actualizar_fecha_cambio( $data = [] ){
                $this->db->trans_begin();

                $update = array(
                        'intentos' => 0,
                        'ultimo_cambio_pwd' => getFechaActual(),
                        'flag_activo' => 1
                );

                $this->db->where('idUsuario', $data['idUsuario']);
                $result = $this->db->update('sistema.usuario', $update);

                if ($this->db->trans_status() === FALSE || !$result){
                        $this->db->trans_rollback();
                        return 0;
                }else{
                        $this->db->trans_commit();

                        $this->CI->aSessTrack[] = [ 'idAccion' => 7, 'tabla' => 'sistema.usuario', 'id' => $data['idUsuario'] ];
                        return 1;
                }
	}
}
