if( $.fn.dataTableExt ){
	$.extend( $.fn.dataTable.defaults, {
		'dom': 'Bfrtip',
		'lengthMenu': [
				[ 20, 50, 100, 1000 ],
				[ '20', '50', '100', '1000' ]
		],
		"scrollY": 450,
        "scrollX": true,
		'pagingType': 'full_numbers',
		'iDisplayLength': 20,
		'oClasses':{
			'sFilterInput':'form-control form-control-sm ml-1',
			'sLengthSelect':'form-control btn-sm mt-1',
		},
	
		'oLanguage': {
				'oAria': {
						'sSortAscending':  ': Activar para ordenar la columna de manera ascendente',
						'sSortDescending': ': Activar para ordenar la columna de manera descendente'
					},
				'oPaginate': {
						'sPrevious': '<i class="fas fa-angle-left"></i>',
						'sNext': '<i class="fas fa-angle-right"></i>',
						'sFirst': '<i class="fas fa-angle-double-left"></i>',
						'sLast': '<i class="fas fa-angle-double-right"></i>',
					  },
				'sEmptyTable': 'Ningún dato disponible en esta tabla',
				'sInfo': '_START_ al _END_ / _TOTAL_ registros',
				'sInfoEmpty': '0 al 0 / 0 registros',
				'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
				'sInfoPostFix': '',
				'sDecimal': '',
				'sThousands': ',',
				'sLengthMenu': ' _MENU_ filas',
				'sLoadingRecords': 'Cargando...',
				'sProcessing': 'Procesando...',
				'sSearch': '',
				'sSearchPlaceholder': 'Buscar en los resultados',
				'sUrl': '',
				'sZeroRecords': 'No se encontraron resultados',
				'buttons': {
						'excel': 'Excel',
						'pageLength': {
								_: '<i class="fal fa-th-list"></i> %d filas'
							},
						'selectAll': "Marcar Todo",
						'selectNone': "Desmarcar Todo"
					}
		},
		'buttons': [
			{
				'extend': 'pageLength',
				'titleAttr': 'Filas visibles'
				
			},
			{
				// 'extend': 'excel',
				'text': '<i class="fal fa-file-excel"></i> Excel',
				// 'exportOptions': {
				// 		'columns': ':visible :not(.excel-borrar)',
				// },
				'titleAttr': 'Exportar a excel',
				'className': 'btn-datatable-excel'
			},
			{
				'extend':'colvis',
				'text': '<i class="far fa-eye"></i>',
				'columns': ':not(.noVis)',
				'titleAttr': 'Ocultar/Mostrar columnas',
			
			},
			{
				'text': '<i class="fal fa-expand"></i>',
				'action': function( e, dt, node, config ){
					$(node).find('i').toggleClass('fa-expand fa-compress');
					Fn.fullScreen();
				},
				'titleAttr': 'Pantalla completa',

			},
		],
		
	
		'initComplete': function(){
			var this_ = this;
			setTimeout(function(){
				$(".navbar").removeAttr("style");
				(this_.DataTable().columns('.hideCol').visible(false)); //Ocultamos por defecto todas las cabeceras con la clase hideCol
				this_.DataTable().columns.adjust();
					
			}, 200);	

			// $(".buttons-excel").css('background-color','#28a745');
			// $(".buttons-excel").css('border','none');
		}
	});

	$.extend( true, $.fn.dataTable.Buttons.defaults, {
		'dom': {
			'button': {
				'className': 'btn btn-sm btn-outline-trade-visual '
			}
		}
	} );
	
	
}