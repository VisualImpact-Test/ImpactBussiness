<div class="card-datatable p-0" style="height:250px;overflow-x:auto;">
	<table id="tablaEfectividadGtm" class="mb-0 table table-sm text-nowrap compact" style="width:100%;border-style:none;">
		<thead style="background-color:darkgray;color:white;">
			<tr>
				<th class="text-center" style="width:60%">NOMBRES</th>
				<th class="text-center" style="width:15%">DNI</th>
			</tr>
		</thead>
		<tbody>
			<?foreach($usuariosFalta AS $key => $row){?>
				<tr>
					<td class="text-left"><?=$row['nombres'].' '.$row['apePaterno'].' '.$row['apeMaterno']?></td>
					<td class="text-center"><?=$row['numDocumento']?></td>
				</tr>
			<?}?>
		</tbody>
	</table>
</div>