<nav class="navbar navbar-expand-lg bg-light sticky-top p-0 shadow">
	<button class="btn btn-trade-visual ml-3" id="btn-toggle-menu">
		<i class="fas fa-lg fa-bars"></i>
	</button>
	<div>
		<a class="navbar-brand col-md-3" href="javascript:;" page="home">
			<div class="logo-src"></div>
		</a>
	</div>

	<!-- <button class="btn btn-trade-visual" id="btn-toggle-menu" data-show="false"><i class="fal fa-window-maximize"></i></button> -->

	<div class="navbar-collapse collapse show" id="navbarsExample09">
		<form id="frm-sidebarMenu" class="form-inline col-md-5 my-2 my-md-0">
			<div class="ui category search" style="width:100%">
				<div class="ui icon input" style="width:100%;">
					<input class="prompt" type="text" placeholder="Buscar en ImpactTrade..." style="width:100%;">
					<i class="search icon"></i>
				</div>
				<div class="results"></div>
			</div>
		</form>

		<ul class="navbar-nav center-items_mlr" style="display: table;">
			<li style="display: table-cell;">
				<div class="input-group" style="transform: translateY(-20%);">
					<span class="input-group-text text-capitalize border-0 pt-0 text-left" style="background-color: #f8f9fa; font-size:12px"> Cuenta: <?= $this->sessNomCuenta ?> <br> Proyecto: <?= $this->sessNomProyecto ?></span>
				</div>
				<input id="sessIdCuenta" type="hidden" value="<?=$this->sessIdCuenta?>">
				<input id="sessIdProyecto" type="hidden" value="<?=$this->sessIdProyecto?>">
			</li>
			<? $logoCuenta = $this->session->userdata('logoCuenta'); ?>
			<? if (!empty($logoCuenta)) { ?>
				<li style="display: table-cell;"><img src="assets/images/logos/<?= $logoCuenta ?>" class="logo-cuenta" /></li>
			<? } ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?
					if (strlen($header_foto) == 0) echo '<img class="user-profile" src="assets/images/avatars/' . rand(1, 4) . '.png" alt="" />';
					else echo '<img class="user-profile" src="https://www.visualimpact.com.pe/intranet/files/empleado/' . $header_foto . '" alt=""  />';
					?>
					<?
					if (strlen($header_usuario) == 0) echo 'USUARIO TI';
					else echo $header_usuario;
					?></a>
				<div class="dropdown-menu item-right" aria-labelledby="dropdown09">
					<a class="dropdown-item btn-anuncios" href="javascript:;" data-target="#modalAvisoConfidencialidad" data-toggle="modal"><i class="fa fa-bullhorn pull-left"></i> Anuncios</a>
					<a class="dropdown-item" href="<?= base_url() . "Perfil" ?>"><i class="nav-link-icon fa fa-cog"></i> Mi Perfil</a>
					<?
					if (
						isset($this->permisos['cuenta']) &&
						isset($this->permisos['proyecto']) && (count($this->permisos['cuenta']) > 1 ||
							empty($this->permisos['proyecto']) ||
							count($this->permisos['proyecto']) > 1)
					) {
					?>
						<a class="dropdown-item" href="javascript:;" id="a-cambiarcuenta"><i class="nav-link-icon fas fa-filter"></i> Cuenta / Proyecto</a>
					<? } ?>

					<?
					if ($this->session->userdata('idUsuario') == "1") {
					?>
						<a class="dropdown-item" href="javascript:;" id="a-actualizarVisitas"><i class="nav-link-icon fas fa-sync"></i> Actualizar Visitas</a>
					<?
					}
					?>

					<a class="dropdown-item" href="<?= base_url() . 'Recover' ?>"><i class="fa fa-unlock"></i> Cambiar Clave</a>
					<a class="dropdown-item" href="javascript:Fn.logOut('home/logout');"><i class="fa fa-sign-out"></i> Salir</a>
				</div>
				<button id="btn-anuncios" style="display:none;"></button>
			</li>
		</ul>
	</div>
</nav>