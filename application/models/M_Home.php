<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Home extends MY_Model{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function query_cotizacion($input){
		$sql = "
			SELECT 
				idCotizacion
				, codCotizacion
				, fechaEmision
				, fechaTermino
				, c.nombre titulo
				, cc.nombre centro_costo
				, c.total
				, DATEDIFF(dd, fechaEmision, ISNULL(fechaTermino, GETDATE())) dias
				, ce.idCotizacionEstado
				, c.comentario
				, (SELECT COUNT(1) FROM compras.cotizacionDetalle cd where cd.idCotizacion = c.idCotizacion) total_item
				, replace(ce.icono,'large','x-small') icono
			FROM 
				compras.cotizacion c
				JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
				JOIN visualImpact.logistica.cuentaCentroCosto cc ON cc.idCuenta = c.idCuenta AND cc.idCuentaCentroCosto = c.idCentroCosto
			WHERE
				idCotizacion = ".$input['idCotizacion'];
		return $this->db->query($sql);
	}
	
	public function query_cotizacion_detalle($input){
		$sql = "
			SELECT 
				cd.idItem
				, cd.nombre
				, cd.cantidad
				, cd.costo
				, cd.subtotal
				, (SELECT p.razonSocial FROM compras.proveedor p WHERE p.idProveedor = cd.idProveedor) proveedor
				, it.idItemTipo
				, DATEADD(DAY,FLOOR(RAND()*(10-1)+1),cd.fechaCreacion) fecha
				, it.nombre tipo
			FROM 
				compras.cotizacionDetalle cd
				
				JOIN compras.itemTipo it ON it.idItemTipo = cd.idItemTipo
				
			WHERE
				idCotizacion = ".$input['idCotizacion'];
		return $this->db->query($sql);
	}
	
	public function query_estados_cotizacion(){
		$sql = "
			SELECT 
				idCotizacionEstado, nombre
			FROM
				compras.cotizacionEstado ce
			ORDER BY ce.orden
		";
		return $this->db->query($sql);
	}
	
	public function query_estados_cotizacion_proceso(){
		$sql = "
			SELECT 
				idCotizacionEstado, nombre
			FROM
				compras.cotizacionEstado ce
			WHERE 
				ce.idCotizacionEstado <> 7
				
			ORDER BY ce.orden
		";
		return $this->db->query($sql);
	}
	
	public function query_cotizaciones_proceso(){
		$sql = "
			SELECT 
				ce.idCotizacionEstado, COUNT(c.idCotizacion) cantidad
			FROM 
				compras.cotizacion c
				JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
			WHERE
				ce.idCotizacionEstado <> 7
			GROUP BY ce.idCotizacionEstado
		";
		return $this->db->query($sql);
	}
	
	public function query_cotizaciones(){
		$sql = "
			DECLARE @fecIni DATE =  DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0);
			DECLARE @fecFin DATE = GETDATE();
			SELECT 
				idCotizacion
				, codCotizacion
				, fechaEmision
				, fechaTermino
				, CASE WHEN (fechaEmision < @fecIni) THEN 1 ELSE 0 END pasado
				, c.nombre titulo
				, cc.nombre centro_costo
				, c.total
				, DATEDIFF(dd, fechaEmision, ISNULL(fechaTermino, GETDATE())) dias
				, ce.idCotizacionEstado idEstado
				, replace(ce.icono,'large','x-small') icono
			FROM compras.cotizacion c
			JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
			JOIN visualImpact.logistica.cuentaCentroCosto cc ON cc.idCuenta = c.idCuenta AND cc.idCuentaCentroCosto = c.idCentroCosto
			WHERE
				fechaEmision BETWEEN @fecIni AND @fecFin
				OR fechaTermino IS NULL
				OR fechaTermino BETWEEN @fecIni AND @fecFin
		";
		return $this->db->query($sql);
	}
	
	/*
	public function find_data(){
		$sql = "
			SELECT TOP 1000 * FROM ImpactTrade_bd.trade.data_asistencia
		";
		return $this->db->query($sql);
	}

	public function get_latest_fotos(){

        $filtros ='';
        $idCanal =$this->session->userdata('idCanal');
        $idCuenta =$this->session->userdata('idCuenta');
        $idProyecto =$this->session->userdata('idProyecto');

        if(!empty($idCanal)){$filtros.= " AND v.idCanal = {$idCanal} ";}
        if(!empty($idCuenta)){$filtros.= " AND r.idCuenta = {$idCuenta} ";}
        if(!empty($idProyecto)){$filtros.= " AND r.idProyecto = {$idProyecto} ";}


        $sessIdTipoUsuario = $this->idTipoUsuario;
		$sessDemo = $this->demo;
        if( $sessIdTipoUsuario != 4 ){
            if( empty($sessDemo) ) $filtros .=  " AND (r.demo = 0 OR r.demo IS NULL)";
            else $filtros .=  " AND (r.demo = 0 OR r.idUsuario = {$this->idUsuario})";
        }
		$sql = "
        DECLARE @fecha DATE = GETDATE();
        WITH lista_fotos AS (
            SELECT 
                v.idVisita
                , vf.idVisitaFoto
                , vf.hora
                , ROW_NUMBER() OVER ( PARTITION BY v.idVisita ORDER BY ISNULL(vf.hora, v.horaFin) DESC ) fila
            FROM 
                {$this->sessBDCuenta}.trade.data_visita v
                JOIN {$this->sessBDCuenta}.trade.data_ruta r ON v.idRuta = r.idRuta
                JOIN {$this->sessBDCuenta}.trade.data_visitaFotos vf ON v.idVisita  = vf.idVisita
            WHERE 
                r.fecha = @fecha
                AND r.estado = 1 AND v.estado = 1 
        ) 
        
        SELECT TOP 20
            v.idVisita
            , UPPER(v.razonSocial) razonSocial
            , UPPER(ISNULL(vmf.nombreTipoFoto, am.nombre)) nombreTipoFoto
            , vf.fotoUrl
            , ISNULL(vf.hora, v.horaFin) hora
            , CONVERT(VARCHAR,r.fecha,103) fecha
            , r.nombreUsuario
            , r.tipoUsuario
            , vf.idModulo
            , ISNULL(v.latIni,0) lati_ini
            , ISNULL(v.lonIni,0) long_ini
            , ISNULL(v.latFin,0) lati_fin
            , ISNULL(v.lonFin,0) long_fin
            , ISNULL(c.latitud,0) latitud
            , ISNULL(c.longitud,0) longitud
            , CONVERT(VARCHAR(8), v.horaIni) hora_ini
            , CONVERT(VARCHAR(8), v.horaFin) hora_fin
        FROM {$this->sessBDCuenta}.trade.data_visita v
            JOIN {$this->sessBDCuenta}.trade.data_ruta r ON v.idRuta = r.idRuta
            JOIN {$this->sessBDCuenta}.trade.data_visitaFotos vf ON v.idVisita = vf.idVisita
            LEFT JOIN {$this->sessBDCuenta}.trade.data_visitaModuloFotos vmf ON vmf.idVisita = v.idVisita AND vf.idVisitaFoto = vmf.idVisitaFoto
            LEFT JOIN trade.aplicacion_modulo am ON am.idModulo = vf.idModulo
            JOIN trade.cliente c ON c.idCliente = v.idCliente

            --
            JOIN lista_fotos lf on lf.idVisita = v.idVisita AND vf.idVisitaFoto = lf.idVisitaFoto AND lf.fila = 1
        WHERE (r.fecha) = @fecha
        $filtros
        ORDER BY hora DESC
		";
		
		return $this->db->query($sql);

    }
    public function get_efectividad($params = []){

        $filtros ='';
        $idCanal =$this->session->userdata('idCanal');
        $idCuenta =$this->session->userdata('idCuenta');
        $idProyecto =$this->session->userdata('idProyecto');

        if(!empty($params['grupoCanal'])){
            $segmentacion = getSegmentacion(['grupoCanal_filtro' => $params['grupoCanal']]);

            if($segmentacion['tipoSegmentacion'] == "tradicional") 
            {
                $filtros .= !empty($params['distribuidora_filtro']) ? ' AND d.idDistribuidora='.$params['distribuidora_filtro'] : '';
                $filtros .= !empty($params['distribuidoraSucursal_filtro']) ? ' AND ds.idDistribuidoraSucursal='.$params['distribuidoraSucursal_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "mayorista") 
            {
                $filtros .= !empty($params['plaza_filtro']) ? ' AND sct.idPlaza='.$params['plaza_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "moderno") 
            {
                $filtros .= !empty($params['cadena_filtro']) ? ' AND cad.idCadena='.$params['cadena_filtro'] : '';
                $filtros .= !empty($params['banner_filtro']) ? ' AND ba.idBanner='.$params['banner_filtro'] : '';
            }
        }else{
            $segmentacion['columnas_bd'] = '';
            $segmentacion['join'] = '';
        }
        if(!empty($idCuenta)){$filtros.= " AND r.idCuenta = {$idCuenta} ";}
        if(!empty($idProyecto)){$filtros.= " AND r.idProyecto = {$idProyecto} ";}

        if(!empty($params['grupoCanal'])){$filtros.= " AND gc.idGrupoCanal = {$params['grupoCanal']} ";}
        if(!empty($params['canal'])){$filtros.= " AND c.idCanal = {$params['canal']} ";}

        $sessIdTipoUsuario = $this->idTipoUsuario;
		$sessDemo = $this->demo;
        if( $sessIdTipoUsuario != 4 ){
            if( empty($sessDemo) ) $filtros .=  " AND (r.demo = 0 OR r.demo IS NULL)";
            else $filtros .=  " AND (r.demo = 0 OR r.idUsuario = {$this->idUsuario})";
        }

        $sql = "
        DECLARE 
        @hoy DATE = '{$params['fecha']}',
        @inicio_mes DATE = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE());

    WITH lista_visitas AS(
    SELECT DISTINCT
         v.idVisita
        , CASE WHEN (r.fecha = @hoy) THEN 1 ELSE 0 END hoy
        , r.fecha
        , CASE WHEN (v.horaIni IS NOT NULL AND v.horaFin IS NOT NULL AND v.numFotos >= 1 AND ISNULL(estadoIncidencia,0) <> 1 ) THEN
                3
            ELSE
                CASE WHEN (v.horaIni IS NOT NULL ) THEN
                        1
            ELSE
                CASE WHEN (estadoIncidencia IS NOT NULL OR estadoIncidencia = 1) THEN
                        2
                    ELSE
                        0
                    END
                END
            END condicion
    FROM 
        {$this->sessBDCuenta}.trade.data_ruta r WITH(NOLOCK)
        JOIN {$this->sessBDCuenta}.trade.data_visita v WITH(NOLOCK) ON r.idRuta = v.idRuta
        JOIN ".getClienteHistoricoCuenta()." ch ON ch.idCliente = v.idCliente
            AND r.fecha BETWEEN ch.fecIni AND ISNULL(ch.fecFin,r.fecha)
            AND ch.idProyecto = r.idProyecto
        JOIN trade.canal c WITH(NOLOCK) ON c.idCanal = v.idCanal
        JOIN trade.grupoCanal gc WITH(NOLOCK) ON gc.idGrupoCanal = c.idGrupoCanal
        {$segmentacion['join']} 
    WHERE 
        r.fecha BETWEEN @inicio_mes AND @hoy
        AND r.estado = 1
        AND v.estado = 1
        {$filtros}
    ), lista_final AS (SELECT DISTINCT
        COUNT(idVisita) OVER () totalProg
        , COUNT(CASE WHEN condicion = 3 THEN 1 END) OVER () totalEfectiva
        , COUNT(CASE WHEN condicion = 2 THEN 1 END) OVER () totalIncidencia
        , COUNT(CASE WHEN condicion = 1 THEN 1 END) OVER () totalProcesos
        , COUNT(CASE WHEN condicion = 0 THEN 1 END) OVER () totalNoVisitados
        , fecha
        , CASE WHEN (fecha = @hoy) THEN 1 ELSE 0 END hoy
        , COUNT(idVisita) OVER (PARTITION BY fecha) totalProgHoy
        , COUNT(CASE WHEN condicion = 3 THEN 1 END) OVER (PARTITION BY fecha) totalEfectivaHoy
        , COUNT(CASE WHEN condicion = 2 THEN 1 END) OVER (PARTITION BY fecha) totalIncidenciaHoy
        , COUNT(CASE WHEN condicion = 1 THEN 1 END) OVER (PARTITION BY fecha) totalProcesoHoy
        , COUNT(CASE WHEN condicion = 0 THEN 1 END) OVER (PARTITION BY fecha) totalNoVisitadosHoy
    FROM lista_visitas
    )
    SELECT * FROM lista_final WHERE hoy = 1
     
        ";

        return $this->db->query($sql);
    }

	public function get_cobertura($params = []){
        $filtros ='';
        $idCanal =$this->session->userdata('idCanal');
        $idCuenta =$this->session->userdata('idCuenta');
        $idProyecto =$this->session->userdata('idProyecto');

        // $grupoCanal = $this->db->get_where('trade.canal',['idCanal'=>$idCanal])->row_array()['idGrupoCanal'];

        if(!empty($params['grupoCanal'])){
            $segmentacion = getSegmentacion(['grupoCanal_filtro' => $params['grupoCanal']]);

            if($segmentacion['tipoSegmentacion'] == "tradicional") 
            {
                $filtros .= !empty($params['distribuidora_filtro']) ? ' AND d.idDistribuidora='.$params['distribuidora_filtro'] : '';
                $filtros .= !empty($params['distribuidoraSucursal_filtro']) ? ' AND ds.idDistribuidoraSucursal='.$params['distribuidoraSucursal_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "mayorista") 
            {
                $filtros .= !empty($params['plaza_filtro']) ? ' AND sct.idPlaza='.$params['plaza_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "moderno") 
            {
                $filtros .= !empty($params['cadena_filtro']) ? ' AND cad.idCadena='.$params['cadena_filtro'] : '';
                $filtros .= !empty($params['banner_filtro']) ? ' AND ba.idBanner='.$params['banner_filtro'] : '';
            }

        }else{
            $segmentacion['columnas_bd'] = '';
            $segmentacion['join'] = '';
        }
        if(!empty($idCuenta)){$filtros.= " AND r.idCuenta = {$idCuenta} ";}
        if(!empty($idProyecto)){$filtros.= " AND r.idProyecto = {$idProyecto} ";}

        if(!empty($params['grupoCanal'])){$filtros.= " AND gc.idGrupoCanal = {$params['grupoCanal']} ";}
        if(!empty($params['canal'])){$filtros.= " AND c.idCanal = {$params['canal']} ";}

        $sessIdTipoUsuario = $this->idTipoUsuario;
		$sessDemo = $this->demo;
        if( $sessIdTipoUsuario != 4 ){
            if( empty($sessDemo) ) $filtros .=  " AND (r.demo = 0 OR r.demo IS NULL)";
            else $filtros .=  " AND (r.demo = 0 OR r.idUsuario = {$this->idUsuario})";
        }


        $sql = "
        DECLARE 
        @hoy DATE = '{$params['fecha']}',
        @inicio_mes DATE = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE());

        WITH lista_visitas AS(
        SELECT DISTINCT
            v.idVisita
            , v.idCliente
            , CASE WHEN (r.fecha = @hoy) THEN 1 ELSE 0 END hoy
            , r.fecha
            , CASE WHEN (v.horaIni IS NOT NULL AND v.horaFin IS NOT NULL AND v.numFotos >= 1 AND ISNULL(estadoIncidencia,0) <> 1  ) THEN
                    3
                ELSE
                    CASE WHEN (v.horaIni IS NOT NULL ) THEN
                            1
                    ELSE
                        CASE WHEN (estadoIncidencia IS NOT NULL OR estadoIncidencia = 1) THEN
                            2
                        ELSE
                            0
                        END
                    END
                END condicion
        FROM 
            {$this->sessBDCuenta}.trade.data_ruta r WITH(NOLOCK)
            JOIN {$this->sessBDCuenta}.trade.data_visita v WITH(NOLOCK) ON r.idRuta = v.idRuta
            JOIN ".getClienteHistoricoCuenta()." ch WITH(NOLOCK) ON ch.idCliente = v.idCliente
                AND r.fecha BETWEEN ch.fecIni AND ISNULL(ch.fecFin,r.fecha)
                AND ch.idProyecto = r.idProyecto
            JOIN trade.canal c WITH(NOLOCK) ON c.idCanal = v.idCanal
            JOIN trade.grupoCanal gc WITH(NOLOCK) ON gc.idGrupoCanal = c.idGrupoCanal
           {$segmentacion['join']} 
        WHERE 
            r.fecha BETWEEN @inicio_mes AND @hoy
            AND r.estado = 1
            AND v.estado = 1
           
            $filtros
        ), lista_cliente AS (
        SELECT
            idCliente
            , SUM(CASE WHEN condicion = 3 OR condicion = 2 THEN 1 ELSE 0 END) OVER (PARTITION BY idCliente) cobertura
            --
            , fecha
            , hoy
            , condicion
            , ROW_NUMBER() OVER (PARTITION BY idCliente ORDER BY fecha DESC) fila
        FROM lista_visitas
        )
        SELECT
            COUNT(CASE WHEN fila = 1 THEN 1 END) totalCartera
            , COUNT(CASE WHEN fila = 1 AND cobertura > 0 THEN 1 END) totalCobertura
            --
            , COUNT(CASE WHEN hoy = 1 AND fila = 1 THEN 1 END) carteraHoy
            , COUNT(CASE WHEN hoy = 1 AND fila = 1 AND (condicion = 3 OR condicion = 2) THEN 1 END) coberturaHoy
        FROM
            lista_cliente
         ";
            
        return $this->db->query($sql);

    }

    public function get_asistencia($params = [])
    {
        $filtros ='';

        if (!empty($params['idProyecto'])) {
            $filtros .= " AND py.idProyecto = {$params['idProyecto']} ";
        }
        if (!empty($params['idCuenta'])) {
            $filtros .= " AND cu.idCuenta = {$params['idCuenta']} ";
        }

        if(!empty($params['grupoCanal'])){$filtros.= " AND gc.idGrupoCanal = {$params['grupoCanal']} ";}
        if(!empty($params['canal'])){$filtros.= " AND ca.idCanal = {$params['canal']} ";}

        $sql = "
			DECLARE @fecha DATE=GETDATE(), @fecIni DATE='{$params['fecha']}', @fecFin DATE='{$params['fecha']}';
			WITH lista_visita_inicio AS (
			SELECT DISTINCT
					r.fecha
					, r.idUsuario
					, v.horaIni
					, c.latitud
					, c.longitud
					, row_number() OVER (PARTITION BY r.idUsuario, r.fecha ORDER BY v.horaIni ASC ) row
					
				FROM 
					{$this->sessBDCuenta}.trade.data_ruta r WITH(NOLOCK)
					JOIN {$this->sessBDCuenta}.trade.data_visita v WITH(NOLOCK) ON r.idRuta = v.idRuta 
					JOIN trade.cliente c WITH(NOLOCK) ON c.idCliente = v.idCliente
				WHERE
					r.fecha BETWEEN @fecIni AND @fecFin
					AND (r.demo = 0 OR r.demo IS NULL)
					AND v.horaIni IS NOT NULL
                    AND r.estado = 1
                    AND v.estado = 1
		),lista_visita_final AS (
			SELECT DISTINCT
				r.fecha
				, r.idUsuario
				, v.horaFin
				, c.latitud
				, c.longitud
				, row_number() OVER (PARTITION BY r.idUsuario, r.fecha ORDER BY v.horaFin DESC ) row
			FROM 
				{$this->sessBDCuenta}.trade.data_ruta r WITH(NOLOCK)
				JOIN {$this->sessBDCuenta}.trade.data_visita v WITH(NOLOCK) ON r.idRuta = v.idRuta 
				JOIN trade.cliente c WITH(NOLOCK) ON c.idCliente = v.idCliente
			WHERE
				r.fecha BETWEEN @fecIni AND @fecFin
				AND (r.demo = 0 OR r.demo IS NULL) 
                AND v.horaFin IS NOT NULL
                AND r.estado = 1
                AND v.estado = 1
				
		),lista_horario AS (
			SELECT 
				h.horaIngreso
				, h.horaSalida
				, h.horaProg
				, dh.idDia
				, dh.idCargoTrabajo
				, dh.idEmpleado
				, t.fecha
				, t.idFeriado
			FROM 
				rrhh.asistencia.horarioAdmin h 
				JOIN rrhh.asistencia.detalleHorario dh ON dh.idHorarioAdmin = h.idHorarioAdmin
				JOIN general.dbo.tiempo t ON t.idDia = dh.idDia AND t.fecha BETWEEN @fecIni AND @fecFin
			WHERE
				h.estado = 1
				AND (
					dh.fecIni <= ISNULL( dh.fecFin, @fecFin)
					AND (
						dh.fecIni BETWEEN @fecIni AND @fecFin 
						OR
						ISNULL( dh.fecFin, @fecFin ) BETWEEN @fecIni AND @fecFin 
						OR
						@fecIni BETWEEN dh.fecIni AND ISNULL( dh.fecFin, @fecFin ) 
						OR
						@fecFin BETWEEN dh.fecIni AND ISNULL( dh.fecFin, @fecFin )
					)
				) 
		) 
		, lista_horario_empleado AS (
			SELECT DISTINCT
				dh.idEmpleado
			FROM 
				rrhh.asistencia.horarioAdmin h 
				JOIN rrhh.asistencia.detalleHorario dh ON dh.idHorarioAdmin = h.idHorarioAdmin
			WHERE
				h.estado = 1
				AND (
					dh.fecIni <= ISNULL( dh.fecFin, @fecFin)
					AND (
						dh.fecIni BETWEEN @fecIni AND @fecFin 
						OR
						ISNULL( dh.fecFin, @fecFin ) BETWEEN @fecIni AND @fecFin 
						OR
						@fecIni BETWEEN dh.fecIni AND ISNULL( dh.fecFin, @fecFin ) 
						OR
						@fecFin BETWEEN dh.fecIni AND ISNULL( dh.fecFin, @fecFin )
					)
				) 
				AND dh.idEmpleado IS NOT NULL
		)	
		SELECT DISTINCT
			u.idUsuario
			, isnull(u.idEmpleado,u.numDocumento) idEmpleado
			, tu.idTipoUsuario
			, tu.nombre tipoUsuario
			, CONVERT(varchar(10),t.fecha,103) fecha
			, CONVERT(VARCHAR(8),t.fecha,112) fecha_id
			, cu.idCuenta
			, cu.nombre cuenta
			, py.idProyecto
			, py.nombre proyecto
			, gc.idGrupoCanal
			, gc.nombre grupoCanal
			, ca.idCanal
			, ca.nombre canal
			, t.feriado
			, toc.nombre ocurrencia
			, vd.idEmpleado vacaciones
			, u.estado
			, CONVERT(VARCHAR(8),vi.horaIni) horaIniVisita
			, CONVERT(VARCHAR(8),vf.horaFin) horaFinVisita
			, CASE WHEN (lh_e.idEmpleado IS NOT NULL ) THEN lh_1.horaIngreso ELSE lh_2.horaIngreso END horarioIng
			, CASE WHEN (lh_e.idEmpleado IS NOT NULL ) THEN lh_1.horaSalida ELSE lh_2.horaSalida END horarioSal
		FROM trade.usuario u WITH(NOLOCK)
			JOIN trade.usuario_historico uh WITH(NOLOCK) ON uh.idUsuario = u.idUsuario
			AND (
				uh.fecIni <= ISNULL( uh.fecFin, @fecFin)
				AND (
					uh.fecIni BETWEEN @fecIni AND @fecFin 
					OR
					ISNULL( uh.fecFin, @fecFin ) BETWEEN @fecIni AND @fecFin 
					OR
					@fecIni BETWEEN uh.fecIni AND ISNULL( uh.fecFin, @fecFin ) 
					OR
					@fecFin BETWEEN uh.fecIni AND ISNULL( uh.fecFin, @fecFin )
				)
			)
			LEFT JOIN trade.usuario_historicoCanal uhd WITH(NOLOCK) ON uhd.idUsuarioHist = uh.idUsuarioHist
			LEFT JOIN trade.canal ca WITH(NOLOCK) ON ca.idCanal = uhd.idCanal
			LEFT JOIN trade.grupoCanal gc WITH(NOLOCK) ON gc.idGrupoCanal = ca.idGrupoCanal
			LEFT JOIN trade.proyecto py WITH(NOLOCK) ON py.idProyecto = uh.idProyecto
			LEFT JOIN trade.cuenta cu WITH(NOLOCK) ON cu.idCuenta = py.idCuenta

			LEFT JOIN trade.usuario_tipo tu WITH(NOLOCK) ON uh.idTipoUsuario = tu.idTipoUsuario
			LEFT JOIN rrhh.dbo.empleado e WITH(NOLOCK) ON e.idEmpleado = u.idEmpleado AND e.flag IN ('activo')
			LEFT JOIN rrhh.dbo.CargoTrabajo ct WITH(NOLOCK) ON ct.idCargoTrabajo = e.idCargoTrabajo

			LEFT JOIN General.dbo.tiempo t WITH(NOLOCK) ON t.fecha BETWEEN @fecIni AND @fecFin

			LEFT JOIN lista_horario lh_1 WITH(NOLOCK) ON lh_1.idEmpleado = e.idEmpleado AND lh_1.fecha = t.fecha
			LEFT JOIN lista_horario lh_2 WITH(NOLOCK) ON lh_2.idEmpleado IS NULL AND lh_2.idCargoTrabajo = ct.idCargoTrabajo
			LEFT JOIN lista_horario_empleado lh_e WITH(NOLOCK) ON lh_e.idEmpleado = e.idEmpleado  

			LEFT JOIN rrhh.asistencia.asistencia at WITH(NOLOCK) ON e.idEmpleado=at.idEmpleado and at.fechaIngreso=t.fecha

			LEFT JOIN rrhh.dbo.Ocurrencias o WITH(NOLOCK) ON o.idEmpleado = e.idEmpleado
				AND t.fecha BETWEEN o.fecInicio AND ISNULL( o.fecTermino, t.fecha)
			LEFT JOIN rrhh.dbo.TipoOcurrencia toc WITH(NOLOCK) ON toc.idTipoOcurrencia = o.idTipoOcurrencia
			LEFT JOIN rrhh.dbo.vacacionesDetalle vd WITH(NOLOCK) ON  vd.idEmpleado = e.idEmpleado AND vd.estado = 1
				AND t.fecha BETWEEN vd.fecSalida AND ISNULL(vd.fecRetorno, t.fecha)
			LEFT JOIN lista_visita_inicio vi ON vi.fecha = t.fecha AND u.idUsuario = vi.idUsuario AND vi.row = 1
			LEFT JOIN lista_visita_final vf ON vf.fecha = t.fecha AND u.idUsuario = vf.idUsuario AND vf.row = 1
		WHERE
			uh.idAplicacion IN (1, 4, 8)
			{$filtros} AND u.demo = 0
            --AND tu.idTipoUsuario = 1
			
		ORDER BY cuenta, proyecto, grupoCanal, canal, fecha ASC
        ";

        $query = $this->db->query($sql);
		$result = array();
		if ( $query ) {
			$result = $query->result_array();
		}

        return $result;
    }

    public function obtener_asistencias($params = []){
		$sql = "
        SELECT
            a.idUsuario
            , CONVERT(VARCHAR(8),a.fecha,112) fecha_id
            , a.idTipoAsistencia
            , tia.nombre tipoAsistencia
            , oc.idOcurrencia
            , oc.nombre ocurrencia
        FROM 
            {$this->sessBDCuenta}.trade.data_asistencia a WITH(NOLOCK)
            JOIN master.tipoAsistencia tia WITH(NOLOCK) ON a.idTipoAsistencia=tia.idTipoAsistencia
            LEFT JOIN master.ocurrencias oc WITH(NOLOCK) ON oc.idOcurrencia=a.idOcurrencia AND oc.estado=1 AND oc.flagAsistencia=1
        WHERE
            a.fecha = '{$params['fecha']}'
        ORDER BY idUsuario DESC
        ";

		$query = $this->db->query($sql);

		$result = array();
		if ( $query ) {
			$result = $query->result_array();
		}

		return $result;
	}

    public function get_cantidadGtm($params = []){
        $filtros ='';

        if (!empty($params['idProyecto'])) {
            $filtros .= " AND py.idProyecto = {$params['idProyecto']} ";
        }
        if (!empty($params['idCuenta'])) {
            $filtros .= " AND cu.idCuenta = {$params['idCuenta']} ";
        }
        if(!empty($params['grupoCanal'])){$filtros.= " AND gc.idGrupoCanal = {$params['grupoCanal']} ";}
        if(!empty($params['canal'])){$filtros.= " AND ca.idCanal = {$params['canal']} ";}

		$sql = "
        DECLARE @fecha DATE = '{$params['fecha']}';
        SELECT
        COUNT(u.idUsuario) AS cantidadGtm
        FROM trade.usuario u
        JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
        AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
        LEFT JOIN trade.usuario_historicoCanal uhd ON uhd.idUsuarioHist = uh.idUsuarioHist
        LEFT JOIN trade.canal ca ON ca.idCanal = uhd.idCanal
        LEFT JOIN trade.grupoCanal gc ON gc.idGrupoCanal = ca.idGrupoCanal
        LEFT JOIN trade.proyecto py ON py.idProyecto = uh.idProyecto
        LEFT JOIN trade.cuenta cu ON cu.idCuenta = py.idCuenta
        WHERE uh.idTipoUsuario IN(1,18) AND u.demo = 0 AND u.estado = 1 AND uh.idAplicacion IN (1, 4, 8)
        {$filtros}
        ";

		$query = $this->db->query($sql);

		$result = array();
		if ( $query ) {
			$result = $query->row_array();
		}

		return $result;
	}

    public function get_efectividadPorGtm($params = []){
        $filtros ='';

        if(!empty($params['grupoCanal'])){
            $segmentacion = getSegmentacion(['grupoCanal_filtro' => $params['grupoCanal']]);

            if($segmentacion['tipoSegmentacion'] == "tradicional") 
            {
                $filtros .= !empty($params['distribuidora_filtro']) ? ' AND d.idDistribuidora='.$params['distribuidora_filtro'] : '';
                $filtros .= !empty($params['distribuidoraSucursal_filtro']) ? ' AND ds.idDistribuidoraSucursal='.$params['distribuidoraSucursal_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "mayorista") 
            {
                $filtros .= !empty($params['plaza_filtro']) ? ' AND sct.idPlaza='.$params['plaza_filtro'] : '';
            }

            if($segmentacion['tipoSegmentacion'] == "moderno") 
            {
                $filtros .= !empty($params['cadena_filtro']) ? ' AND cad.idCadena='.$params['cadena_filtro'] : '';
                $filtros .= !empty($params['banner_filtro']) ? ' AND ba.idBanner='.$params['banner_filtro'] : '';
            }

        }else{
            $segmentacion['columnas_bd'] = '';
            $segmentacion['join'] = '';
        }

        if (!empty($params['idProyecto'])) {
            $filtros .= " AND r.idProyecto = {$params['idProyecto']} ";
        }
        if (!empty($params['idCuenta'])) {
            $filtros .= " AND r.idCuenta = {$params['idCuenta']} ";
        }

        if(!empty($params['grupoCanal'])){$filtros.= " AND gc.idGrupoCanal = {$params['grupoCanal']} ";}
        if(!empty($params['canal'])){$filtros.= " AND c.idCanal IN ({$params['canal']}) ";}

        $sql = "
        DECLARE @fechaHoy DATE = '{$params['fecha']}';
        WITH lista_rutas AS (
            SELECT
                r.idRuta
                , v.idVisita
                , r.idUsuario
                , r.idTipoUsuario
                , v.horaIni
                , v.horaFin
                , v.idListIpp
                , v.ipp
                , v.idListProductos
                , v.productos
                , v.moduloFotos
                , v.estadoIncidencia
                , v.numFotos
            FROM {$this->sessBDCuenta}.trade.data_ruta r WITH(NOLOCK)
            JOIN {$this->sessBDCuenta}.trade.data_visita v WITH(NOLOCK) ON r.idRuta = v.idRuta
            JOIN ".getClienteHistoricoCuenta()." ch WITH(NOLOCK) ON ch.idCliente = v.idCliente
                AND r.fecha BETWEEN ch.fecIni AND ISNULL(ch.fecFin,r.fecha)
                AND ch.idProyecto = r.idProyecto
            JOIN trade.canal c ON c.idCanal = v.idCanal
            JOIN trade.grupoCanal gc ON gc.idGrupoCanal = c.idGrupoCanal
            {$segmentacion['join']}
            WHERE r.fecha = @fechaHoy 
            AND (r.demo = 0 OR r.demo IS NULL)
            AND r.estado = 1
            AND v.estado = 1
            {$filtros}
            AND r.idTipoUsuario IN(1,18)
        ), lista_programados AS (
            SELECT
                lr.idUsuario
                , COUNT(lr.idVisita) AS programados
            FROM lista_rutas lr
            GROUP BY lr.idUsuario
        ), lista_efectivos AS (
            SELECT
                lr.idUsuario
                , COUNT(lr.idVisita) AS efectivos
            FROM lista_rutas lr
            WHERE horaIni IS NOT NULL 
            AND horaFin IS NOT NULL 
            AND numFotos >= 1 
            AND ISNULL(estadoIncidencia,0) <> 1
            GROUP BY lr.idUsuario
        ), lista_modulos AS (
            SELECT
                lr.idUsuario
                , COUNT(lr.idListIpp) AS ippProgramados
                , COUNT(lr.ipp) AS ippEfectuados
                , COUNT(lr.idListProductos) AS productosProgramados
                , COUNT(lr.productos) AS productosEfectuados
                , COUNT(lr.idVisita) AS fotosProgramados
                , COUNT(lr.moduloFotos) AS fotosEfectuados
            FROM lista_rutas lr
            GROUP BY lr.idUsuario
        )
        SELECT
            lp.idUsuario
            , u.nombres+' '+u.apePaterno+' '+u.apeMaterno AS usuario
            , lp.programados
            , ISNULL(le.efectivos, 0) AS efectivos
            , lm.*
        FROM lista_programados lp
        LEFT JOIN lista_efectivos le ON lp.idUsuario = le.idUsuario
        LEFT JOIN lista_modulos lm ON lp.idUsuario = lm.idUsuario
        JOIN trade.usuario u ON lp.idUSuario = u.idUsuario
        ORDER BY efectivos ASC, programados DESC
        ";

		$query = $this->db->query($sql);

		$result = array();
		if ( $query ) {
			$result = $query->result_array();
		}

		return $result;
	}

    public function get_usuariosFaltas($params = []){
        $filtros ='';

        if (!empty($params['idProyecto'])) {
            $filtros .= " AND r.idProyecto = {$params['idProyecto']} ";
        }
        if (!empty($params['idCuenta'])) {
            $filtros .= " AND r.idCuenta = {$params['idCuenta']} ";
        }

        $sql = "
        SELECT
        *
        FROM trade.usuario
        WHERE idUsuario IN ({$params['usuariosFaltas']})
        ";

		$query = $this->db->query($sql);

		$result = array();
		if ( $query ) {
			$result = $query->result_array();
		}

		return $result;
	}
	*/
}