<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Comprobante extends My_Model{

    var $aSessTrack = [];

    public function __construct(){
        parent::__construct();
        $this->database = 'ImpactBussiness.compras.comprobante';
        $this->flag = 'compras.comprobante';
    }


    public function get($data){
        $sql = $this->db->get_where($this->database,array('idComprobante'=>$data['id']));
        return $sql->result_array();
    }


}
