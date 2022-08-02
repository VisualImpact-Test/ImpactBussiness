<!-- <style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        font-family: sans-serif;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        overflow: hidden
    }


    .loginBox {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 350px;
        min-height: 200px;
        background: #000000;
        /* border: solid 1px #000000; */
        border-radius: 10px;
        padding: 40px;
        box-sizing: border-box;

    }

    .user {
        margin: 0 auto;
        display: block;
        margin-bottom: 20px
    }

    h3 {
        margin: 0;
        padding: 0 0 20px;
        color: #ffffff;
        text-align: center
    }

    .loginBox input {
        width: 100%;
        margin-bottom: 20px
    }

    .loginBox input[type="text"],
    .loginBox input[type="password"] {
        border: none;
        border-bottom: 2px solid #262626;
        outline: none;
        height: 40px;
        color: #fff;
        background: transparent;
        font-size: 16px;
        padding-left: 20px;
        box-sizing: border-box
    }

    .loginBox input[type="text"]:hover,
    .loginBox input[type="password"]:hover {
        color: #42F3FA;
        border: 1px solid #42F3FA;
        box-shadow: 0 0 5px rgba(0, 255, 0, .3), 0 0 10px rgba(0, 255, 0, .2), 0 0 15px rgba(0, 255, 0, .1), 0 2px 0 black
    }

    .loginBox input[type="text"]:focus,
    .loginBox input[type="password"]:focus {
        border-bottom: 2px solid #42F3FA
    }

    .inputBox {
        position: relative
    }

    .inputBox span {
        position: absolute;
        top: 10px;
        color: #262626
    }

    .loginBox input[type="submit"] {
        border: none;
        outline: none;
        height: 40px;
        font-size: 16px;
        background: #31b170;
        color: #fff;
        border-radius: 20px;
        cursor: pointer
    }

    .loginBox a {
        color: #262626;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        text-align: center;
        display: block
    }

</style> -->
<div class="row child-divcenter">
    <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
</div>
<div class="loginBox">
    <form id="frmLoginProveedor" >
      <div class="row">
        <div class="main-card mb-3 card main-efectividad col-md-6 px-0 child-divcenter" style="align-items: center;">
  			<div class="card-header bg-trade-visual-grad-left text-white" style="width: 100%;">
  				<h5 class="card-title">
  					<i class="fas fa-user fa-lg"></i> Ingrese sus datos</h5>
  			</div>
  			<div class="card-body vista-efectividad" style="width: 100%;">
  				<div class="row">
  					<div class="col-md-12">
              <div class="input-group mb-3">
                <div class="input-group-prepend col-2 px-0">
                  <span class="input-group-text" style="width: 100%" align="center">Email</span>
                </div>
                <input id="uname" type="text" class="form-control" name="email" placeholder="usuario@dominio.com" patron="requerido,email">
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend col-2 px-0">
                  <span class="input-group-text" style="width: 100%">RUC</span>
                </div>
                <input id="pass" type="password" class="form-control" name="ruc" placeholder="###########" patron="requerido,ruc">
              </div>
  					</div>
  				</div>
          <div class="row justify-content-md-center">
            <div class="col col-lg-5">
              <button type="button" class="btn btn-trade-visual w-100 m-2 btnLoginProveedor">Ingresar</button>
              <a href="<?= base_url() ?>FormularioProveedor/signup" class="btn btn-outline-secondary w-100 m-2">Registrarse<br> </a>
              <!-- <div class="text-center">
              </div> -->
            </div>
          </div>
  			</div>
      </div>
		</div>
    </form>

</div>
