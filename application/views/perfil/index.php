<div class="row mt-4">
  <div class="col-lg-2 d-flex justify-content-center align-items-center">
    <h3 class="card-title mb-3">
      <i class="<?= $icon ?>"></i>
      <?= $title ?>
    </h3>
  </div>
  <div class="col-lg-10 d-flex">
    <div class="card w-100 mb-3 p-0">
      <div class="card-body p-0">
        <ul class="nav nav-tabs nav-justified">
          <li class="nav-item btnReporte" id="" name="" url="">
            <a data-toggle="tab" href="#contentRecover" class="active nav-link " data-value="1">&nbsp;</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row" id="contentPrecios">
  <div class="col-md-12">
    <div class="mb-3 card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="container bootstrap snippet">
              <div class="row">
                <div class="col-sm-3">
                  <div class="text-center">
                    <? if (isset($foto)) {
                      $src = 'https://www.visualimpact.com.pe/intranet/files/empleado/' . $foto;
                    } else {
                      $src = 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png';
                    } ?>
                    <img src="<?= $src ?>" class="avatar img-circle img-thumbnail" alt="avatar" id="foto_perfil" style="width: 170px;">
                  </div>
                </div>
                <div class="col-sm-9">
                  <div class="tab-content">
                    <div class="tab-pane active" id="home">
                      <hr>
                      <form class="form" action="##" method="post" id="registrationForm">
                        <div class="form-group">
                          <div class="row">
                            <div class="col">
                              <label for="first_name">
                                <h4>Nombres</h4>
                              </label>
                              <input readonly="readonly" type="text" value="<?= $nombres ?>" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any.">
                            </div>
                            <div class="col">
                              <label for="last_name">
                                <h4>Apellidos</h4>
                              </label>
                              <input readonly="readonly" type="text" value="<?= $apellidos ?>" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <label for="last_name">
                                <h4><?= $tipoDoc ?> </h4>
                              </label>
                              <input readonly="readonly" type="text" value="<?= hideNumDocumento($documento) ?>" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                            </div>
                            <div class="col">
                              <label for="mobile">
                                <h4>Número móvil</h4>
                              </label>
                              <input readonly="readonly" type="text" value="<?= $celular ?>" class="form-control" name="mobile" id="mobile" placeholder="mobile number" title="enter your mobile number if any.">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <label for="mobile">
                                <h4>Teléfono</h4>
                              </label>
                              <input readonly="readonly" type="text" value="<?= $telefono ?>" class="form-control" name="mobile" id="mobile" placeholder="mobile number" title="enter your mobile number if any.">
                            </div>
                            <div class="col">
                              <label for="email">
                                <h4>Email</h4>
                              </label>
                              <input readonly="readonly" type="email" value="<?= $email ?>" class="form-control" name="email" id="email" placeholder="you@visualimpact.com.pe" title="enter your email.">
                            </div>
                          </div>
                        </div>
                      </form>
                      <hr>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  // $('#foto_perfil').elevateZoom();
</script>