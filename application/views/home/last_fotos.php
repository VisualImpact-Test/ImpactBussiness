<h5 class="card-title">ULTIMAS FOTOS <!--<sup>Top Mode</sup>--></h5>
			<?
			?>
			<div class="row">
				<?
				$contador = 1;
				
				foreach($fotos as $data_foto){
					?>

					<?
					//INI
					$latiIni = $data_foto['lati_ini']; 
					$longIni = $data_foto['long_ini']; 
					$latitud = $data_foto['latitud']; 
					$longitud = $data_foto['longitud']; 
					$gpsIni = ( ( empty($latiIni) || $latiIni == 0 || empty($longIni) || $longIni == 0  ) )? '' : '<a href="javascript:;" class="lk-show-gps1 a-fa" data-latitud="'.$latiIni.'" data-longitud="'.$longIni.'" data-latitud-cliente="'.$latitud.'" data-longitud-cliente="'.$longitud.'" data-type="ini" data-perfil="'.$data_foto['tipoUsuario'].'"  data-usuario="'.$data_foto['nombreUsuario'].'" data-cliente="'.$data_foto['razonSocial'].'"  ><i class="fa fa-map-marker" ></i></a> '; 
					
					//FIN
					$latiFin = $data_foto['lati_fin']; 
					$longFin = $data_foto['long_fin']; 
					$latitud = $data_foto['latitud']; 
					$longitud = $data_foto['longitud']; 
					$gpsFin = ( ( empty($latiFin) || $latiFin == 0 || empty($longFin) || $longFin == 0  ) )? '' : '<a href="javascript:;" class="lk-show-gps1 a-fa" data-latitud="'.$latiFin.'" data-longitud="'.$longFin.'" data-latitud-cliente="'.$latitud.'" data-longitud-cliente="'.$longitud.'" data-type="fin" data-perfil="'.$data_foto['tipoUsuario'].'"  data-usuario="'.$data_foto['nombreUsuario'].'" data-cliente="'.$data_foto['razonSocial'].'"  ><i class="fa fa-map-marker" ></i></a> '; 
					

					?>
				
						<div class="col-lg-3 col-md-4 .col-sm-4">
							<div class="thumbnail" border="1px solid #000">
							
								<div class="popup-gallery">
									<a data-user="<?=$data_foto['nombreUsuario']?>" title="<?=$data_foto['razonSocial']?>" rel='prettyPhoto' href="<?=site_url().'ControlFoto/obtener_carpeta_foto/'.get_ruta_foto($data_foto['idModulo'],$data_foto['fotoUrl']).$data_foto['fotoUrl']?>" >
									<img id='latest-fotos' src="<?=site_url().'ControlFoto/obtener_carpeta_foto/'.get_ruta_foto($data_foto['idModulo'],$data_foto['fotoUrl']).$data_foto['fotoUrl']?>" alt="Lights" class="w-100" style="text-align: center; height: 150px; border-radius: 5px 5px 5px 5px;">
									</a>
								</div>
								
								<div style="margin: 0.5em; text-decoration: none">
									<h6 class="text-primary"><?=$data_foto['razonSocial']?></h6>
									<label style="font-size: 10px">Nombre usuario: <?=$data_foto['nombreUsuario']?></label><br>
									<label style="font-size: 10px">Tipo Foto: <?=$data_foto['nombreTipoFoto']?></label><br>
									<label style="font-size: 8px"><?=$data_foto['fecha'].' '.time_change_format($data_foto['hora'])?></label>
								<br>
								<div class="contenedor_hora"> 
									<td class="text-center"><?=!empty($data_foto['hora_ini'])?$gpsIni.$data_foto['hora_ini']:'-';?></td>
									- 
									<td class="text-center"><?=!empty($data_foto['hora_fin'])?$gpsFin.$data_foto['hora_fin']:'-';?></td>
								</div>
									
								
								</div>
							</div>
						<hr>

						</div>
						<? $contador++;
					}?>
			</div>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
  $('.image-link').magnificPopup({type:'image'});
  $('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>By: '+item.el.attr('data-user')+'</small>';
			}
		}
	});
});
</script>