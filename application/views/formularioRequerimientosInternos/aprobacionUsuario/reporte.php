<style>
	#validez {
		cursor: pointer;
		/* Esto cambiará el cursor a una mano al pasar sobre el div */
	}
</style>
<div class="card-datatable">
	<table id="tb-requerimientos-aprobacionUsuario" class="ui celled table" width="100%">
		<thead>
			<tr class="td-left">
				<th>#</th>
				<th>NOMBRE</th>
				<th>APELLIDOS</th>
				<th>TIPO USUARIO</th>
				<th class="text-center">ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<? foreach ($datos['usuariosAprobados'] as $k => $row) : ?>
				<tr data-id="<?= $row['id'] ?>" class="td-left">
					<td><?= $ix; ?></td>
					<td><?= $row['nombres']; ?></td>
					<td><?= $row['apeMaterno']." ".$row['apePaterno']; ?></td>
					<td><?= $row['usuarioTipo']; ?></td>
					<td class="td-center"><?= $row['estado'] == 1 ? 
					'<a class="ui green large label">Activo</a>' : '<a class="ui red large label">Inactivo</a>' ?></td>
				</tr>
				<? $ix++; ?>
			<? endforeach ?>
		</tbody>
	</table>
</div>