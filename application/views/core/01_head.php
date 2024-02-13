<!doctype html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="es">
	<title>:.:.: Visual Impact - ImpactBussiness :.:.:</title>
	<meta name="description" content="coming soom.">
	<meta name="msapplication-tap-highlight" content="no">
	<base href="<?= base_url() . 'public/'; ?>" site_url="<?= site_url(); ?>">
	<link href="assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="icon" href="assets/images/icono_2.jpg">
	<link href="assets/custom/css/sidebar-right.css?v=<?= $this->version; ?>" rel="stylesheet">
	<link href="assets/custom/css/main.css?v=<?= $this->version; ?>" rel="stylesheet">
	<script>
		function initMap() {}
	</script>
	<link href="assets/custom/css/circle-progress.css?v=<?=$this->version;?>" rel="stylesheet">
	<link href="assets/libs/font-awesome/5.15.3/css/all.min.css?v=<?= $this->version; ?>" rel="stylesheet">
	<link href="assets/libs/daterangepicker/daterangepicker.css?v=<?= $this->version; ?>" rel="stylesheet">
	<link href="assets/libs/select2/4.0.13/css/select2.min.css?v=<?= $this->version; ?>" rel="stylesheet">
	<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.css"> -->
	<!-- <link href="assets/libs/datatables/datatables.bootstrap4.min.css" rel="stylesheet"> -->
	<link href="assets/libs/semantic_2.8.8/Fomantic-UI-CSS-master/semantic.min.css" rel="stylesheet">
	<link href="assets/libs/semantic_2.8.8/dataTables.semanticui.min.css" rel="stylesheet">
	<link href="assets/libs/jquery-ui-1.12.1/css/jquery-ui-1.12.1.css" rel="stylesheet">

	<? foreach ($style as $css) { ?>
		<link href="<?= $css ?>.css?v=<?= $this->version; ?>" rel="stylesheet">
	<? } ?>
	<!--CSS PERSONALIZADO POR CUENTA -->
	<? $cssCuenta = $this->session->userdata('cssCuenta'); ?>
	<? if (!empty($cssCuenta)) { ?>
		<link href="assets/custom/css/<?= $cssCuenta ?>?v=<?= $this->version; ?>" rel="stylesheet">
	<? } ?>
</head>