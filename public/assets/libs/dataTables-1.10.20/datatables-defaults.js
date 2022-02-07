if( $.fn.dataTableExt ){
	$.extend( $.fn.dataTable.defaults, {
		'dom': 'Bfrtip',
		'lengthMenu': [
				[ 15, 50, 100, 1000 ],
				[ '15 Filas', '50 Filas', '100 Filas', '1000 Filas' ],
			],
		'scrollX': true,
		'scrollY': '50vh',
		'pagingType': 'full_numbers',
		'iDisplayLength': 15,
		'oLanguage': {
				'oAria': {
						'sSortAscending':  ': Activar para ordenar la columna de manera ascendente',
						'sSortDescending': ': Activar para ordenar la columna de manera descendente'
					},
				'oPaginate': {
						'sFirst': '<<',
						'sLast': '>>',
						'sNext': '>',
						'sPrevious': '<'
					},
				'sEmptyTable': 'Ningún dato disponible en esta tabla',
				'sInfo': '_START_ al _END_ / _TOTAL_ registros',
				'sInfoEmpty': '0 al 0 / 0 registros',
				'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
				'sInfoPostFix': '',
				'sDecimal': '',
				'sThousands': ',',
				'sLengthMenu': 'Mostrar _MENU_ registros',
				'sLoadingRecords': 'Cargando...',
				'sProcessing': 'Procesando...',
				'sSearch': '',
				'sSearchPlaceholder': 'Buscar',
				'sUrl': '',
				'sZeroRecords': 'No se encontraron resultados',
				'buttons': {
						'excel': 'Excel',
						'pageLength': {
								_: 'Mostrando %d Filas'
							},
						'selectAll': "Seleccionar Todo",
						'selectNone': "Deseleccionar Todo"
					}
			},
		'buttons': [
				{
					'extend': 'excel',
					'className': 'btn-default',
					'exportOptions': {
							'columns': ':not(.excel-borrar)'
						}
				},
				{
					'extend': 'pageLength'
				}
			],
		'initComplete': function(){
				var this_ = this;

				setTimeout(function(){
						this_.DataTable().columns.adjust();
					},100);

			}
	});

	$.extend( true, $.fn.dataTable.Buttons.defaults, {
		'dom': {
			'button': {
					'className': 'btn btn-sm vi-button mx-0'
				}
		}
	} );
}