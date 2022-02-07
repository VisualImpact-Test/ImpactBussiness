		<script type="text/javascript" src="assets/libs/jquery/js/jquery-3.5.1.min.js"></script>
		<script type="text/javascript" src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
		<?if( $this->bsModal ){?><script>
			$.fn.bsModal = $.fn.modal.noConflict();
		</script><?}?>
		<!-- -->
		<script type="text/javascript" src="assets/libs/select2/4.0.13/js/select2.full.min.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/select2/4.0.13/js/i18n/es.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/select2/4.0.13/js/select2.default.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/jquery.table2excel.min.js"></script>
		
		<script type="text/javascript" src="assets/libs/moment/moment.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/daterangepicker/daterangepicker.js?v=<?=$this->version;?>"></script>
		<!-- <script type="text/javascript" src="assets/libs/semanticui/semantic.min.js?v=<?=$this->version;?>"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.js"></script>

		<!-- -->
		<script type="text/javascript" src="assets/custom/js/core/system.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/custom/js/core/functions.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/masonry/masonry.pkgd.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/slick/slick.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/notify/bootstrap-notify.js?v=<?=$this->version;?>"></script>
		<script type="text/javascript" src="assets/libs/customizer.min.js?v=<?=$this->version;?>"></script>
		
		<script src="assets/libs/datatables/datatables.min.js"></script>
		<script src="assets/libs/tableTools/TableTools.js"></script>
		<!-- <script src="assets/libs/dataTables-1.10.25/dataTables-1.10.25/js/jquery.dataTables.min.js"></script>
		<script src="assets/libs/dataTables-1.10.25/AutoFill-2.3.7/js/autoFill.bootstrap4.js"></script>
		<script src="assets/libs/dataTables-1.10.25/JSZip-2.5.0/jszip.js"></script>
		<script src="assets/libs/dataTables-1.10.25/FixedColumns-3.3.3/js/dataTables.fixedColumns.min.js"></script>
		<script src="assets/libs/dataTables-1.10.25/Buttons-1.7.1/js/dataTables.buttons.min.js"></script>
		<script src="assets/libs/dataTables-1.10.25/Buttons-1.7.1/js/buttons.colVis.min.js"></script>
		<script src="assets/libs/dataTables-1.10.25/Buttons-1.7.1/js/buttons.html5.min.js"></script> -->
		<!-- <script src="assets/libs/datatables-1.10.25/datatables.min.js"></script> -->
		
		<script src="https://nightly.datatables.net/js/dataTables.bootstrap4.min.js"></script>
		<script src="assets/libs/sheetJs/xlsx.full.min.js"></script>
		<script src="assets/libs/fileSaver/FileSaver.min.js"></script>
		<script src="assets/libs/fileSaver/FileSaver.min.js"></script>

		<script src="assets/libs/jquery-ui-1.12.1/js/jquery-ui-1.12.1.js"></script>
	
		<?foreach($script as $js){?>
			<script src="<?=$js?>.js?v=<?=$this->version;?>"></script>
		<?}?>
		<script>
			var $cambiarCuenta = false;
		<?if( $this->namespace == 'home' && (empty($this->sessIdCuenta) || empty($this->sessIdProyecto)) ){?>
				$cambiarCuenta = true;
		<?} elseif( $this->namespace != 'home' && (empty($this->sessIdCuenta) || empty($this->sessIdProyecto)) ){?>
			$('#a-cambiarcuenta').click();
		<?}?>
		</script>