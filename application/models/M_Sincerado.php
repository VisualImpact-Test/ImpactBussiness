<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Sincerado extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function obtenerInformacionDelPresupuestoValido($params = [])
	{
		$this->db
			->select('pv.*, os.nombre as ordenServicio, mon.nombreMoneda as moneda, os.fechaIni, os.chkUtilizarCliente, os.idCliente, os.idCuenta, os.idCentroCosto')
			->from('compras.presupuestoValido pv')
			->join('compras.presupuestoHistorico ph', 'ph.idPresupuesto = pv.idPresupuesto AND ph.idPresupuestoHistorico = pv.idPresupuestoHistorico')
			->join('compras.ordenServicio os', 'os.idOrdenServicio = ph.idOrdenServicio')
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda')
			->where('os.estado', 1)
			->order_by('pv.idPresupuestoValido desc');
		if (isset($params['idPresupuestoValido'])) $this->db->where('pv.idPresupuestoValido', $params['idPresupuestoValido']);

		return $this->db->get();
	}
	public function getSinceradoCargo($id, $idH = null)
	{
		$this->db
			->select('pc.*, c.nombre as cargo')
			->from('compras.sinceradoCargo pc')
			->join('rrhh.dbo.CargoTrabajo c', 'c.idCargoTrabajo = pc.idCargo', 'LEFT')
			->where('pc.idSincerado', $id);

		$this->db->where('pc.estado', 1);

		$query = $this->db->get();
		return $query;
	}
	public function getSincerado()
	{
		$this->db
			->select('
			s.idSincerado,
			s.idPresupuesto,
			s.idPresupuestoHistorico,
			s.idOrdenServicio,
			s.fecha_seleccionada,
			s.estado,
			os.nombre,
			os.idCuenta,
			os.idCentroCosto,
			os.chkUtilizarCliente,
			c.razonSocial as cuenta,
			cc.subcanal AS centroCosto,
			mon.nombreMoneda as moneda,
			s.flagPendienteAprobar,
			(SELECT SUM(porcentaje) FROM compras.sinceradoGR WHERE idSincerado = s.idSincerado and estado = 1) porcentaje')
			->from('compras.sincerado s')
			->join('compras.ordenServicio os', 'os.idOrdenServicio = s.idOrdenServicio')
			->join('compras.presupuestoHistorico p', 'p.idPresupuesto = s.idPresupuesto AND p.idPresupuestoHistorico = s.idPresupuestoHistorico')
			->join('rrhh.dbo.Empresa c', 'os.idCuenta = c.idEmpresa', 'LEFT')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = os.idCentroCosto', 'LEFT')
			->join('compras.cliente cl', 'cl.idCliente = os.idCliente', 'LEFT')
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda', 'LEFT');
		// ->where('pc.idSincerado', $id);

		$this->db->where('s.estado', 1);

		$query = $this->db->get();
		return $query;
	}

	public function obtenerDatosSincerado($id)
	{
		$this->db
			->select('
			s.idSincerado,
			s.idPresupuesto,
			s.idPresupuestoHistorico,
			s.idOrdenServicio,
			s.fecha_seleccionada,
			s.estado,
			os.nombre,
			os.idCuenta,
			os.idCentroCosto,
			os.chkUtilizarCliente,
			c.razonSocial as cuenta,
			cc.subcanal AS centroCosto,
			mon.nombreMoneda as moneda')
			->from('compras.sincerado s')
			->join('compras.ordenServicio os', 'os.idOrdenServicio = s.idOrdenServicio')
			->join('compras.presupuestoHistorico p', 'p.idPresupuesto = s.idPresupuesto AND p.idPresupuestoHistorico = s.idPresupuestoHistorico')
			->join('rrhh.dbo.Empresa c', 'os.idCuenta = c.idEmpresa', 'LEFT')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = os.idCentroCosto', 'LEFT')
			->join('compras.cliente cl', 'cl.idCliente = os.idCliente', 'LEFT')
			->join('compras.moneda mon', 'mon.idMoneda = os.idMoneda', 'LEFT')
			// ->where('pc.idSincerado', $id);
			->where('s.idSincerado', $id);

		$this->db->where('s.estado', 1);

		$query = $this->db->get();
		return $query;
	}
	public function obtenerOrdenServicioFechas($id)
	{
		$this->db
			->select('*')
			->from('compras.ordenServicioFecha osf')
			->join('compras.sincerado scr', 'scr.idOrdenServicio = osf.idOrdenServicio')
			->where('scr.idSincerado', $id);

		$this->db->where('osf.estado', 1);

		$query = $this->db->get();
		return $query;
	}
	public function obtenerSinceradoCargos($id)
	{
		$this->db
			->select('*')
			->from('compras.sinceradoCargo sc')
			->join('compras.sincerado s', 's.fecha_seleccionada = sc.fecha and sc.idSincerado = s.idSincerado ')
			->join('rrhh.dbo.cargoTrabajo c', 'c.idCargoTrabajo = sc.idCargo')
			->where('s.idSincerado', $id);

		$this->db->where('s.estado', 1);

		$query = $this->db->get();
		return $query;
	}

	public function obtenerPresupuestoHist($id)
	{
		$this->db
			->select('idCargo , fecha , pc.cantidad as cantidadfecha')
			->from('compras.presupuestoHistorico ph')
			->join('compras.sincerado s', 'ph.idPresupuesto = s.idPresupuesto and ph.idPresupuestoHistorico = s.idPresupuestoHistorico')
			->join('compras.presupuestoCargo pc', 'ph.idPresupuesto = pc.idPresupuesto and ph.idPresupuestoHistorico = pc.idPresupuestoHistorico')
			->where('s.idSincerado', $id);

		$this->db->where('s.estado', 1);

		$query = $this->db->get();
		return $query;
	}

	public function obtenerFechaCargo($id)
	{
		$sql = "
		WITH list_sincerado
		AS(
		select s.idPresupuesto, s.idPresupuestoHistorico, idCargo,fecha,cantidad 
		from compras.sinceradoCargo sc
		join compras.sincerado s on s.fecha_seleccionada = sc.fecha and sc.idSincerado = s.idSincerado
		JOIN rrhh.dbo.cargoTrabajo c on c.idCargoTrabajo = sc.idCargo
		
		),
		list_todo AS
		(
		select ph.idPresupuesto, ph.idPresupuestoHistorico, pc.idCargo, fecha, cantidad 
		from compras.presupuestoHistorico ph
		join compras.sincerado s on ph.idPresupuesto = s.idPresupuesto and ph.idPresupuestoHistorico = s.idPresupuestoHistorico 
		join compras.presupuestoCargo pc on ph.idPresupuesto = pc.idPresupuesto and ph.idPresupuestoHistorico = pc.idPresupuestoHistorico
		where s.idSincerado = $id
		)
		
		select distinct lt.*, ls.idCargo as CargoSinc , ls.fecha as fechaSinc , ls.cantidad as cantidadSinc from list_todo lt
		left join list_sincerado ls on lt.idPresupuesto =ls.idPresupuesto and lt.idPresupuestoHistorico =ls.idPresupuestoHistorico
		AND lt.idCargo = ls.idCargo AND lt.fecha = ls.fecha
		order by lt.fecha, lt.idCargo
		";
		return $this->db->query($sql);
	}

	public function obtenerTipoPresupuesto($id)
	{
		$sql = "
		select sd.* , ps.nombre from compras.sincerado s
		join compras.sinceradoDetalle sd on s.idSincerado =sd.idSincerado
		join compras.tipoPresupuesto ps on ps.idTipoPresupuesto=sd.idTipoPresupuesto
		where s.idSincerado = $id
		and s.estado = 1
		";
		return $this->db->query($sql);
	}
	// public function obtenerDetalleSueldo($id)
	// {
	// 	$sql = "
	// 		select ss.*, s.fecha_seleccionada 
	// 		from compras.sinceradoDetalleSueldo_Det ss
	// 		join compras.sinceradoDetalle sd ON sd.idSinceradoDetalle = ss.idSinceradoDetalle
	// 		join compras.sincerado s ON s.idSincerado = sd.idSincerado
	// 		where sd.idSincerado = $id
	// 	";
	// 	//echo $sql;
	// 	return $this->db->query($sql);
	// }

	public function obtenerDetalleSueldo($id)
	{
		$sql = "
		select ss.*, s.fecha_seleccionada 
		from compras.sinceradoDetalleSueldo_Det ss
		join compras.sinceradoDetalle sd ON sd.idSinceradoDetalle = ss.idSinceradoDetalle
		join compras.sincerado s ON s.idSincerado = sd.idSincerado
		join ( 
		select distinct idPresupuesto, idPresupuestoHistorico 
		from compras.sincerado 
		where idSincerado=$id
		)
		 as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCargoSueldo($id)
	{
		$sql = "
				select ss.*, s.fecha_seleccionada , c.nombre
				from compras.sinceradoDetalleSueldo_Det ss
				join compras.sinceradoDetalle sd ON sd.idSinceradoDetalle = ss.idSinceradoDetalle
				join compras.sincerado s ON s.idSincerado = sd.idSincerado
				left JOIN rrhh.dbo.cargoTrabajo c on c.idCargoTrabajo = ss.idCargo
				where sd.idSincerado = $id
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraComunicacion($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado=$id and tpd.idTipoPresupuesto = 2
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraGastosAdmin($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		right join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado= $id and (tpd.idTipoPresupuesto = 7 or ss.idTipoPresupuestoDetalle = 0 )
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraMateProte($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado=$id and tpd.idTipoPresupuesto = 5
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraMateOngo($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado=$id and tpd.idTipoPresupuesto = 6
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraUniforme($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado=$id and tpd.idTipoPresupuesto = 3
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerCabeceraMateOper($id)
	{
		$sql = "
		select ss.* ,tpd.nombre from compras.tipoPresupuestoDetalle as tpd
		join compras.sincerado_Det as ss on tpd.idTipoPresupuestoDetalle = ss.idTipoPresupuestoDetalle
		where idSincerado=$id and tpd.idTipoPresupuesto = 4
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleComunicacion($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 2
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleGastoAdmin($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		left join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 7 or ss.idTipoPresupuestoDetalle = 0
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalGastoAdmin($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 7
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalComunicacion($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 2
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalSueldo($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 1
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalMateProte($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 5
		";
		//echo $sql;
		return $this->db->query($sql);
	}
	public function obtenerTotalMateOngo($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 6
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalMateOper($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 4
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerTotalUniforme($id)
	{
		$sql = "
		select sdt.* , s.fecha_seleccionada from compras.sinceradoDetalle as sdt
		join compras.sincerado s on s.idSincerado = sdt.idSincerado 
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where idTipoPresupuesto = 3
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleMateProte($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 5
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleMateOngo($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 6
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleMovilidad($id)
	{
		$sql = "
		select sdmd.*,
		s.fecha_seleccionada 
		from compras.sinceradoDetalleMovilidad_Det as sdmd
		join compras.sinceradoDetalle as sdt on sdt.idSinceradoDetalle = sdmd.idSinceradoDetalle
		join compras.sincerado s ON s.idSincerado = sdt.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleAlmacen($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sinceradoDetalle ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		where ss.idTipoPresupuesto = 9		
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleUniforme($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 3	
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleMateOper($id)
	{
		$sql = "
		select ss.*,
		s.fecha_seleccionada 
		from compras.sincerado_Det ss
		join compras.sincerado s ON s.idSincerado = ss.idSincerado
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		join compras.tipoPresupuestoDetalle as tpd on ss.idTipoPresupuestoDetalle = tpd.idTipoPresupuestoDetalle
		where tpd.idTipoPresupuesto = 4
		";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function obtenerDetalleFeeTotal($id)
	{
		$sql = "
		select s.* from compras.sincerado as s
		join ( select distinct idPresupuesto, idPresupuestoHistorico from compras.sincerado where idSincerado=$id )
		as p ON p.idPresupuesto = s.idPresupuesto and p.idPresupuestoHistorico = s.idPresupuestoHistorico
		";
		//echo $sql;
		return $this->db->query($sql);
	}
	public function anularSinceradoDetalle($idSincerado)
	{
		$this->db->update('compras.sinceradoCargo', ['estado' => 0], ['idSincerado' => $idSincerado, 'estado' => 1]);
		$this->db->update('compras.sincerado_Det', ['estado' => 0], ['idSincerado' => $idSincerado, 'estado' => 1]);

		$idsSinceradoDetalle = obtenerDatosCabecera($this->db->get_where('compras.sinceradoDetalle', ['estado' => 1])->result_array(), 'idSinceradoDetalle');
		$this->db->update('compras.sinceradoDetalle', ['estado' => 0], ['idSincerado' => $idSincerado, 'estado' => 1]);

		if (!empty($idsSinceradoDetalle)) {
			foreach ($idsSinceradoDetalle as $id) {
				$this->db->update('compras.sinceradoDetalleAlmacen', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleAlmacenRecursos', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleMovilidad', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleMovilidad_Det', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleSueldo', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleSueldo_Det', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);
				$this->db->update('compras.sinceradoDetalleSueldoAdicional', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);

				$idsSinceradoDetalleSub = obtenerDatosCabecera($this->db->get_where('compras.sinceradoDetalleSub', ['estado' => 1])->result_array(), 'idSinceradoDetalleSub');
				$this->db->update('compras.sinceradoDetalleSub', ['estado' => 0], ['idSinceradoDetalle' => $id, 'estado' => 1]);

				if (!empty($idsSinceradoDetalleSub)) {
					foreach ($idsSinceradoDetalleSub as $idSub) {
						$this->db->update('compras.sinceradoDetalleSubCargo', ['estado' => 0], ['idSinceradoDetalleSub' => $idSub, 'estado' => 1]);
						$this->db->update('compras.sinceradoDetalleSubElemento', ['estado' => 0], ['idSinceradoDetalleSub' => $idSub, 'estado' => 1]);
					}
				}
			}
		}
		return true;
	}
}
