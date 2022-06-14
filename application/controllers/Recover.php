<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recover extends MY_Login
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Recover', 'm_recover');
        date_default_timezone_set("America/Lima");
    }

    public function index()
    {
        $config['view'] = 'recover';
        $config['js']['script'] = array('assets/custom/js/recover');

        $config['css']['style'] = array();
        $config['single'] = false;
        $config['login'] = true;
        $this->view($config);
    }

    public function reestablecerClaveNoNav()
    {
        $config['view'] = 'recover_seguridad';
        $config['js']['script'] = array('assets/custom/js/recover');
        $config['css']['style'] = array();
        $config['single'] = true;

        $config['data'] = [];
        $this->view($config);
    }

    public function cambiarClaveUsuario(){
		$data=json_decode($this->input->post('data'));
        $this->result;
        
        if ($data->nuevaClave == '' or $data->nuevaClave2 == '') {
            $result['msg']['content'] = "Tiene que llenar todos los campos";
            $result['msg']['title'] = "Advertencia";
            goto respuesta;
        }

        if (!checkPassword($data->nuevaClave)) {
            $config_ = array( 'type' => 2, 'message' => "La clave debe contener mayúsculas, minúsculas, números y debe ser de al menos 8 carácteres de longitud" );
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        if ($data->nuevaClave != $data->nuevaClave2) {
            $config_ = array( 'type' => 2, 'message' => "La claves no coinciden" );
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        $id_usuario = $this->session->userdata('idUsuario');
        $pwd = $data->claveActual;
        $rs= $this->m_recover->getUsuarioActivo($id_usuario,$pwd)->result();
        
        if(count($rs)<=0){
            /*La clave es incorrecta*/
            $config_ = array( 'type' => 2, 'message' => "Su clave actual no coincide");
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content'] = createMessage($config_);
           
        }else{
            $arr_data = array(
                'nuevaClave' => $data->nuevaClave,
                'idUsuario' => $id_usuario
            );
           $rs =  $this->m_recover->cambiarClave($arr_data);
           if($rs){
            $config_ = array( 'type' => 1, 'message' => "Se actualizó la clave con éxito");
            $result['msg']['title'] = "Confirmacion";
            $result['msg']['content'] = createMessage($config_);
           }else{
            $config_ = array( 'type' => 0, 'message' => "Hubo un error al actualizar");
            $result['msg']['title'] = "Error";
            $result['msg']['content'] = createMessage($config_);
           }
        }

        respuesta:
		echo json_encode($result);
    }

    public function cambiarClaveUsuarioSeguridad(){
		$data = json_decode($this->input->post('data'));
        $this->result;
        
        if ($data->nuevaClave == '' or $data->nuevaClave2 == '') {
            $result['msg']['content'] = "Tiene que llenar todos los campos";
            $result['msg']['title'] = "Advertencia";
            goto respuesta;
        }

        if ($data->claveActual ==  $data->nuevaClave) {
            $config_ = array( 'type' => 2, 'message' => "La nueva clave no puede ser igual a la clave anterior" );
            $result['msg']['content'] = createMessage($config_);
            $result['msg']['title'] = "Advertencia";
            goto respuesta;
        }

        if (!checkPassword($data->nuevaClave)) {
            $config_ = array( 'type' => 2, 'message' => "La clave debe contener mayúsculas, minúsculas, números y debe ser de al menos 8 carácteres de longitud" );
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content'] = createMessage($config_);
            goto respuesta;
        }

        if ($data->nuevaClave != $data->nuevaClave2) {
            $config_ = array( 'type' => 2, 'message' => "La claves no coinciden" );
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content'] = createMessage($config_);
            goto respuesta;
        }

        $id_usuario = $this->session->userdata('idUsuario');
        $pwd = $data->claveActual;
        $rs= $this->m_recover->getUsuarioActivo($id_usuario,$pwd)->result();

        if(count($rs)<=0){
            /*La clave es incorrecta*/
            $config_ = array( 'type' => 2, 'message' => "Su clave actual no coincide");
            $result['msg']['title'] = "Advertencia";
            $result['msg']['content'] = createMessage($config_);
           
        }else{
            $arr_data = array(
                'nuevaClave' => $data->nuevaClave,
                'idUsuario' => $id_usuario
            );
           $rs =  $this->m_recover->cambiarClave($arr_data);
           if($rs){
            $config_ = array( 'type' => 1, 'message' => "Se actualizó la clave con éxito, por favor vuelva a ingresar para actualizar los cambios.");
            $this->m_recover->actualizar_fecha_cambio($arr_data);
            $result['result'] = 1;
            $result['msg']['title'] = "Confirmacion";
            $result['msg']['content'] = createMessage($config_);
           }else{
            $config_ = array( 'type' => 0, 'message' => "Hubo un error al actualizar");
            $result['msg']['title'] = "Error";
            $result['msg']['content'] = createMessage($config_);
           }
        }

        respuesta:
		echo json_encode($result);
    }

    public function frm_email_changepwd(){
		$data=json_decode($this->input->post('data'));
		$array = array();
		$result = $this->result;
		$result['status'] = 0;
		$result['result'] = 1;
		$result['data']['content'] = $this->load->view('changepwd/frm_email',$array,true);
		$result['msg']['title'] = "Ingrese su Correo";

		echo json_encode($result);
    }
    
    function mail()
    {
        $data = json_decode($this->input->post('data'));

        $email = $data->email;
        $token = $this->generarToken();

        $result = array();
        $result['result'] = 0;
        $result['url'] = 'login/';
        $result['msg']['title'] = "Recuperar contraseña";

        $arrayUsuario = $this->m_recover->getIdUsuario($email)->row_array();

        if(empty($arrayUsuario)){
            //Error
            $result['result'] = 1;
            $config_ = array( 'type' => 2, 'message' => "No se encontró ningun usuario asociado a este correo electrónico" );
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        $idUsuario = $arrayUsuario['idUsuario'];

        $estadoEmail = $this->enviarCorreo($email, $token);

        if (!$estadoEmail) {
            //Error
            $result['result'] = 1;
            $config_ = array( 'type' => 1, 'message' => "Se ha enviado un mensaje con las instrucciones de recuperación de contraseña al correo especificado" );
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        } else {
            //Correcto
            $this->m_recover->actualizarTokens($idUsuario);
            $this->m_recover->guardarToken($idUsuario, $token);

            $result['result'] = 1;
            $config_ = array( 'type' => 1, 'message' => "Se ha enviado un mensaje con las instrucciones de recuperación de contraseña al correo especificado" );
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        respuesta:
        echo json_encode($result);
    }

    public function reestablecerClave($token)
    {
        $informacionDeToken = $this->m_recover->getInformacionDeToken($token)->row_array();

        $horaTokenSolicitado = $informacionDeToken['fecha'] . ' ' . $informacionDeToken['hora'];
        $tiempoActual = date('Y-m-d H:i:s');

        $excedioTiempoLimite = $this->validarTiempoLimite($horaTokenSolicitado, $tiempoActual);

        if (!$excedioTiempoLimite || $informacionDeToken['estado'] == 0) {
            //Cambiar el redirect por algo mas rapido
            redirect('login', 'refresh');
            exit();
        }

        $config['view'] = 'changepwd/cambiarClave';
        $config['js']['script'] = array('assets/custom/js/recover');
        $config['css']['style'] = array();
        $config['single'] = true;

        $config['data'] = array('title' => 'Usuarios', 'informacionDeToken' => $informacionDeToken);
        $this->view($config);
    }

    public function validarTiempoLimite($tiempoInicio, $tiempoFinal)
    {
        $tiempoInicio = new \DateTime($tiempoInicio);
        $tiempoFinal   = new \DateTime($tiempoFinal);

        $diferencia = (array) $tiempoFinal->diff($tiempoInicio);

        if ($diferencia['y'] > 0 || $diferencia['m'] > 0 || $diferencia['d'] > 0 || $diferencia['h'] > 0) {
            return false;
        }

        if ($diferencia['i'] > 5) {
            return false;
        } else {
            return true;
        }
    }

    public function cambiarClave()
    {
        $data = json_decode($this->input->post('data'));

        $nuevaClave = $data->nuevaClave;
        $nuevaClave2 = $data->nuevaClave2;
        $idUsuario = base64_decode($data->idUsuario);

        $result['result'] = 0;
        $result['url'] = 'login/';
        $result['msg']['title'] = "Recuperar contraseña";

        if ($nuevaClave == '' or $nuevaClave2 == '') {
            $result['msg']['content'] = "Tiene que llenar todos los campos";
            goto respuesta;
        }

        if (!checkPassword($nuevaClave)) {
            $config_ = array( 'type' => 2, 'message' => "La clave debe contener mayúsculas, minúsculas, números y debe ser de al menos 8 carácteres de longitud" );
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        if ($nuevaClave != $nuevaClave2) {
            $config_ = array( 'type' => 2, 'message' => "La claves no coinciden" );
            $result['msg']['content']=createMessage($config_);
            goto respuesta;
        }

        $update = array();
        $update['nuevaClave'] = $nuevaClave;
        $update['idUsuario'] = $idUsuario;

        $resultado = $this->m_recover->cambiarClave($update);

        if (!$resultado) {
            $result['msg']['content'] = "Ha ocurrido un error, intentelo nuevamente";
            goto respuesta;
        } else {
            $this->m_recover->actualizarTokens($idUsuario);
            $this->m_recover->actualizar_fecha_cambio($update);
            $result['result'] = 1;
            $result['msg']['content'] = "La contraseña se actualizó exitosamente.";
            goto respuesta;
        }

        respuesta:
        echo json_encode($result);
    }

    public function generarToken()
    {
        $token = substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", mt_rand(0, 200), 1) . substr(md5(time()), 1);
        return $token;
    }

    public function enviarCorreo($email, $token)
    {
        //correo saliente
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'teamsystem@visualimpact.com.pe',
            'smtp_pass' => '#nVi=0sN0ti$',
            'mailtype' => 'html'
        );

        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->set_newline("\r\n");
//destinatario
        $this->email->from('team.sistemas@visualimpact.com.pe', 'Visual Impact - IMPACTBUSSINESS');
        $this->email->to($email);

        // $bcc = array(
        //     'team.sistemas@visualimpact.com.pe',
        // );
        // $this->email->bcc($bcc);

        $this->email->subject('IMPACTTRADE - Recuperación de contraseña');
        $html = $this->load->view("changepwd/recuperarClaveCorreo", [ 'link' => base_url().index_page().'/recover/reestablecerClave/'.$token ], true);
        $correo = $this->load->view("changepwd/formato", [ 'html' => $html, 'link' => base_url().index_page().'/recover/reestablecerClave/'.$token ], true);
        $this->email->message($correo);

        $estadoEmail = $this->email->send();

        return $estadoEmail;
    }
}
