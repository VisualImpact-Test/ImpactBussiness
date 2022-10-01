<div class="card-datatable">
    <table id="filtroOper" class="ui celled table" width="100%">
    <thead>
            <tr>
                <th class="td-center">#</th>
                <th></th>
               
                <th>N° DE RQ</th>
                <th>USUARIO REGISTRO</th>
                <th>FECHA REGISTRO</th>
                <th class="td-center">OBSERVACION</th>
                <th>COTIZACIONES</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($datos as $k => $row) : ?>
                <tr data-idoper="<?=$row['idOper']?>">
                    <td class="text-center"> <?= ($k + 1) ?></td>
                    <td class="text-center"> 
                    <a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOper"><i class="fa fa-lg fa-file-pdf" title="Descargar OPER"></i></a>
                    </td>
                    <td class="text-left"> <?= !empty($row['requerimiento']) ? $row['requerimiento'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($row['usuarioRegistro']) ? $row['usuarioRegistro'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($row['fechaReg']) ? $row['fechaReg'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($row['observacion']) ? $row['observacion'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($cotizaciones[$row['idOper']]) ? implode(',',$cotizaciones[$row['idOper']]) : '-' ?></td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    	$(document).ready(function() {
			$('#filtroOper').DataTable( );
		} );
</script>