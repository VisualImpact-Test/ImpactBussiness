<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Tracking extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function obtenerInformacionTracking()
	{
		$sql = "
			with gr as (
				select sgr.*, s.idPresupuesto,c.nombre as concepto
				from compras.sinceradoGr sgr
				join compras.sincerado s ON s.idSincerado = sgr.idSincerado
				join compras.conceptoTracking c on c.idConceptoTracking = sgr.conceptoTracking
				where sgr.estado = 1
			), incentivo as (
				select sum(sdsd.montoOriginal) as montoOriginal, sum(sdsd.montoSincerado) as montoSincerado, s.idPresupuesto, s.idPresupuestoHistorico
				from compras.sinceradoDetalleSueldo_Det sdsd
				join compras.sinceradoDetalle sd on sd.idSinceradoDetalle = sdsd.idSinceradoDetalle
				join compras.sincerado s on sd.idSincerado = s.idSincerado
				where flagIncentivo = 1 and sdsd.estado = 1 and sd.estado = 1
				group by s.idPresupuesto, s.idPresupuestoHistorico
			), soporte as (
				select s.idPresupuesto, s.idPresupuestoHistorico, sum(sd.montoOriginal) as montoOrignial, sum(sd.montoSincerado) as montoSincerado
				from compras.sincerado_Det sd
				join compras.sincerado s on s.idSincerado = sd.idSincerado
				where sd.idTipoPresupuestoDetalle = 16 and sd.estado = 1
				group by s.idPresupuesto, s.idPresupuestoHistorico
			), monto as (
				select
				s.idPresupuesto, s.idPresupuestoHistorico,
				sum(sd.montoSincerado) as montoSincerado,
				sum(case when idTipoPresupuesto IN (1) then sd.montoSincerado - incentivo.montoSincerado else 0 end) 
				as planilla,
				sum(case when idTipoPresupuesto IN (1) then incentivo.montoSincerado else 0 end) as incentivo,
				sum(case when idTipoPresupuesto in(2) then soporte.montoSincerado else 0 end) as soporte,
				sum(case when idTipoPresupuesto not IN (1) then sd.montoSincerado 
				- case when idTipoPresupuesto in(2) then soporte.montoSincerado else 0 end
				else 0 end) as compras
				from compras.sincerado s
				join compras.sinceradoDetalle sd on s.idSincerado = sd.idSincerado
				left join incentivo on incentivo.idPresupuesto = s.idPresupuesto and incentivo.idPresupuestoHistorico = s.idPresupuestoHistorico
				left join soporte on soporte.idPresupuesto = s.idPresupuesto and soporte.idPresupuestoHistorico = s.idPresupuestoHistorico
				where sd.estado = 1
				group by s.idPresupuesto, s.idPresupuestoHistorico
			)
			select
				os.idOrdenServicio,
				gr.idSinceradoGr,
				e.abreviatura correlativa,
				os.fechaIni mes,
				e.razonSocial cliente,
				ec.canal,
				ec.subcanal,
				os.nombre descripcion,
				gr.usuario usuario, -- PENDIENTE, se debe indicar al llenar GR
				oc.fechaOC fechaOC,
				oc.codigoOc oc,
				'-----' fechaSustento, -- NO SE INDICA SUSTENTO
				gr.fecha fechaGR,
				gr.fechaReg fechaEnvioFinanzas,
				gr.descripcion gr,
				gr.presupuestoSincerado monto,
				'-----' status,
				gr.concepto as concepto,
				monto.planilla AS planillas,
				monto.incentivo as incentivo,
				monto.soporte as soporte,
				monto.compras as compras,
				s.totalFee1Sincerado + s.totalFee2Sincerado + s.totalFee3Sincerado as fee,
				s.totalSincerado as total,
				tda.fechaEstimadaEjecucion,
				tda.comentario
			from compras.ordenServicio os
			left join compras.presupuesto p ON p.idOrdenServicio = os.idOrdenServicio
			join rrhh.dbo.Empresa e ON e.idEmpresa = os.idCuenta
			join rrhh.dbo.empresa_Canal ec ON ec.idEmpresaCanal = os.idCentroCosto
			left join gr ON gr.idPresupuesto = p.idPresupuesto
			left join compras.ordenServicioDatosOc oc ON oc.idOrdenServicio = os.idOrdenServicio
			left join monto on monto.idPresupuesto = p.idPresupuesto
			left join compras.sincerado s ON s.idPresupuesto = p.idPresupuesto
			left join compras.trackingDatosAdicionales tda ON tda.idOrdenServicio = os.idOrdenServicio AND tda.idSinceradoGr = gr.idSinceradoGr
		";
		return $this->db->query($sql);
	}
}