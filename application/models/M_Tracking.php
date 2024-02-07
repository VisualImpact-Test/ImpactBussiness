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
				os.idOrdenServicio as id,
				gr.idSinceradoGr as idGr,
				e.abreviatura correlativa,
				CAST(os.fechaIni as date) mes,
				e.razonSocial cliente,
				ec.canal,
				ec.subcanal,
				os.nombre descripcion,
				gr.usuario usuario, -- PENDIENTE, se debe indicar al llenar GR
				oc.fechaOC fechaOC,
				oc.codigoOc oc,
				null fechaSustento, -- NO SE INDICA SUSTENTO
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
				tda.comentario,
				tda.flagCotizacion
			from compras.ordenServicio os
			left join compras.presupuesto p ON p.idOrdenServicio = os.idOrdenServicio
			join rrhh.dbo.Empresa e ON e.idEmpresa = os.idCuenta
			join rrhh.dbo.empresa_Canal ec ON ec.idEmpresaCanal = os.idCentroCosto
			left join gr ON gr.idPresupuesto = p.idPresupuesto
			left join compras.ordenServicioDatosOc oc ON oc.idOrdenServicio = os.idOrdenServicio
			left join monto on monto.idPresupuesto = p.idPresupuesto
			left join compras.sincerado s ON s.idPresupuesto = p.idPresupuesto
			left join compras.trackingDatosAdicionales tda ON tda.idOrdenServicio = os.idOrdenServicio AND tda.idSinceradoGr = gr.idSinceradoGr
			
			union all

			select 
				c.idCotizacion as id,
				cgr.idCotizacionGr AS idGr,
				emp.abreviatura AS correlativa,
				CAST(c.fechaEmision as date) AS mes,
				emp.nombre as cliente,
				ec.canal as canal,
				ec.subcanal as subcanal,
				c.nombre as descripcion,
				'---' as usuario,
				c.fechaClienteOC as fechaOC,
				c.codOrdenCompra as oc,
				null as fechaSustento,
				cgr.fechaGR as fechaGR,
				null as fechaEnvioFinanzas,
				cgr.numeroGR as gr,
				null as monto,
				'---' as status,
				'---' as concepto,
				0 as planillas,
				0 as incentivo,
				0 as soporte,
				c.total as compras,
				c.total_fee - c.total as fee,
				c.total_fee as total,
				tda.fechaEstimadaEjecucion,
				tda.comentario,
				tda.flagCotizacion
			from compras.cotizacion c
			join rrhh.dbo.Empresa emp ON emp.idEmpresa = c.idCuenta
			join rrhh.dbo.empresa_Canal ec ON ec.idEmpresaCanal = c.idCentroCosto
			left join compras.cotizacionGr cgr on cgr.idCotizacion = c.idCotizacion and cgr.estado = 1
			left join compras.trackingDatosAdicionales tda ON tda.idOrdenServicio = c.idCotizacion AND tda.idSinceradoGr = cgr.idCotizacionGr AND tda.flagCotizacion = 1
			order by mes desc
		";
		return $this->db->query($sql);
	}
}
