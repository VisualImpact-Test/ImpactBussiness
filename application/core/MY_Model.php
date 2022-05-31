<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model{

	var $insertId;
	var $tablas;

	public function actualizarMasivo($table, $input, $where)
	{
		$update = $this->db->update_batch($table, $input, $where);
		return $update;
	}

	public function deleteMasivo($table, $columna, $ids)
	{
		$this->db->where_in($columna, $ids);
		$delete = $this->db->delete($table);
		return $delete;
	}

	public function verificarRepetido($tabla, $where)
	{
		$this->db->where($where);
		$incidencias = count($this->db->get($tabla)->result());

		if ($incidencias > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function validar_filas_unicas_HT($post){
		$listas = array();
		foreach($post as $index => $value){
			$listas[$index] = $value['idLista'];
		}

		if(count($listas) != count(array_unique($listas))){
			return false;
		}else{
			return true;
		}

	}

	public function insertarMasivo($table, $input)
	{
		return $this->db->insert_batch($table, $input);
	}

}