<div class="row">
	<div class="col-12">
		<div id="content-boxes" >
		</div>
		<div class="main-card mb-3 card">
			<div id="content-table" class="card-body">
				<table style="width: 100%; font-size: 9px" id="tb-list" class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>FECHA</th>
							<th>HORA</th>
							<th>USUARIO</th>
							<th>TIPO</th>
							<th>FECHA1</th>
							<th>HORA1</th>
							<th>USUARIO1</th>
							<th>TIPO1</th>
							<th>FECHA2</th>
							<th>HORA2</th>
							<th>USUARIO2</th>
							<th>TIPO2</th>
							<th>FECHA3</th>
							<th>HORA3</th>
							<th>USUARIO3</th>
							<th>TIPO3</th>
						</tr>
					</thead>
					<tbody>
						<?$i=1; foreach($table as $row){?>
							<tr >
								<td class="text-center" ><?=$i++?></td>
								<td class="text-center" ><?=date_change_format($row['fecha'])?></td>
								<td class="text-center" ><?=$row['hora']?></td>
								<td class="text-center" ><?=!empty($row['nombreUsuario'])? $row['nombreUsuario']: '-'?></td>
								<td class="text-center" ><?=!empty($row['idTipoAsistencia'])? $row['idTipoAsistencia'] : '-'?></td>
								<td class="text-center" ><?=date_change_format($row['fecha'])?>1</td>
								<td class="text-center" ><?=$row['hora']?>1</td>
								<td class="text-center" ><?=!empty($row['nombreUsuario'])? $row['nombreUsuario']: '-'?>1</td>
								<td class="text-center" ><?=!empty($row['idTipoAsistencia'])? $row['idTipoAsistencia'] : '-'?>1</td>
								<td class="text-center" ><?=date_change_format($row['fecha'])?>2</td>
								<td class="text-center" ><?=$row['hora']?>2</td>
								<td class="text-center" ><?=!empty($row['nombreUsuario'])? $row['nombreUsuario']: '-'?>2</td>
								<td class="text-center" ><?=!empty($row['idTipoAsistencia'])? $row['idTipoAsistencia'] : '-'?>2</td>
								<td class="text-center" ><?=date_change_format($row['fecha'])?>3</td>
								<td class="text-center" ><?=$row['hora']?>3</td>
								<td class="text-center" ><?=!empty($row['nombreUsuario'])? $row['nombreUsuario']: '-'?>3</td>
								<td class="text-center" ><?=!empty($row['idTipoAsistencia'])? $row['idTipoAsistencia'] : '-'?>3</td>
							</tr>
						<?}?>
					</tbody>
				</table>    
			</div>
		</div>
	</div>
</div>