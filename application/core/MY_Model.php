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

	public function getWhereJoinMultiple($tabla, $where, $select = '*', $join = [], $orden = ''){
		// Las abreviaturas de las tablas deben estar definidas en las variables.
		// Ejemplo $tabla = $join['tabla'] = 'ImpactTrade_bd.trade.tablaDeEjemplo tb';
		$this->db->select($select,false);
		$this->db->from($tabla);
		foreach ($join as $key => $value) {
			$this->db->join($value['tabla'],$value['on'],$value['tipo']);
		}
		foreach ($where as $key => $value) {
			if ($key==0){ $this->db->where($value); }
			else {
				$this->db->or_group_start()->where($value)->group_end();
			}
		}
		if($orden != ''){
			$this->db->order_by($orden);
		}
		return $this->db->get();
	}

	public function insertarMasivo($table, $input)
	{
		return $this->db->insert_batch($table, $input);
	}

}