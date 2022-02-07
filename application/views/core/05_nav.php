<?
	$menu_opciones = array();
	$m_active = isset($menu_active)? $menu_active : '';
	$menu = $this->session->userdata('menu');
	if(isset($menu)){
		foreach($menu as $row){
			$grupo_menu[$row['idGrupoMenu']] = $row;
			$sub_menu[$row['idGrupoMenu']][$row['idSubGrupoMenu']] = $row;
			if(empty($row['idSubGrupoMenu'])) $single_menu[$row['idGrupoMenu']][$row['idMenuOpcion']] = $row;
			else $menu_option[$row['idGrupoMenu']][$row['idSubGrupoMenu']][$row['idMenuOpcion']] = $row;
		}
	}
	$ix_menu=0;
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 bg-pg sidebar d-none d-md-block w-0">
	<div class="sidebar-sticky pt-3">
		<ul class="nav flex-column">
			<li class="nav-item">
				<a href="javascript:;" page="home" <?=($m_active == 'home')? ' class="nav-link  active a-href" ' : 'class="nav-link  a-href"';?>>
					<i class="fa fa-home" ></i>
					Inicio
				</a>
			</li>
		<?if(isset($grupo_menu)){
			foreach($grupo_menu as $ix_g => $row_g){?>
				<h3 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1    ">
					<span><?=$row_g['grupoMenu']?></span>
				</h3>
				
				<?if(isset($single_menu[$ix_g])){?>
					<?foreach($single_menu[$ix_g] as $ix_sm => $row_sm){?>
						<li class="nav-item">
							<a href="javascript:;" page="<?=$row_sm['page']?>" <?=($m_active == $ix_sm)? ' class="nav-link  active a-href" ' : 'class="nav-link  a-href"';?>>
								<i class="<?=$row_sm['cssIcono']?>"></i>
								<?=$row_sm['nombre']?>
								<?$menu_opciones[$ix_sm]['category'] = $row_g['grupoMenu'];?>
								<?$menu_opciones[$ix_sm]['title'] = $row_sm['nombre']; ?>
								<?$menu_opciones[$ix_sm]['url'] = base_url().$row_sm['page'];?>
							</a>
						</li>
					<?}?>
				<?}?>
				<?if(isset($menu_option[$ix_g])){?>
					<?foreach($sub_menu[$ix_g] as $ix_s => $row_s){?>
						<?
							$m_existe = isset($menu_option[$ix_g][$ix_s][$m_active])? true : false;
						?>
						<?if(!empty($row_s['subGrupoMenu'])) {?>
						<li class="nav-item" >
							<a class="nav-link nav-link-sub-item  <?=($m_existe)? '' : ' collapsed ' ?>" href="#submenu<?=$ix_menu?>" data-toggle="collapse" data-target="#submenu<?=$ix_menu?>">
								<?=$row_s['subGrupoMenu']?>
							</a>
							<div class="collapse <?=($m_existe)? ' show ' : '' ?>" id="submenu<?=$ix_menu?>" aria-expanded="false">
								<ul class="flex-column nav">
									<? if(!empty($ix_s)) { ?>
										<?foreach($menu_option[$ix_g][$ix_s] as $ix_m => $row_m){?>
											<li class="nav-item sub-item">
												<a href="javascript:;" page="<?=$row_m['page']?>"  <?=($m_active == $ix_m)? ' class="nav-link py-0  active a-href" ' : ' class="nav-link py-0   a-href"';?> >
													<i class="<?=$row_m['cssIcono']?>"></i>
													<?=$row_m['nombre']?>
													<?$menu_opciones[$ix_m]['category'] = $row_s['subGrupoMenu'];?>
													<?$menu_opciones[$ix_m]['title'] = $row_m['nombre'];?>
													<?$menu_opciones[$ix_m]['url'] = base_url().$row_m['page'];?>
												</a>
											</li>
										<?}?>
									<? } ?>
								</ul>
							</div>
							<?$ix_menu++?>
						</li>
						<?}?>
					<?}?>
				<?}?>
			<?}
		}?>	
		</ul>
	</div>
</nav>
<script>
    var menu_opciones = '<? echo json_encode($menu_opciones); ?>';
</script>
