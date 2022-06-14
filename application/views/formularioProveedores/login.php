<style>
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

</style>
<div class="row child-divcenter">
    <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
</div>
<div class="loginBox">
    <form id="frmLoginProveedor" >
        <fieldset class="scheduler-border">
            <legend class="scheduler-border" style="color: white ;">Ingrese sus datos</legend>
            <div class="control-group child-divcenter row mt-2" style="width:100%;">
                <input id="uname" type="text" name="email" placeholder="Email" patron="requerido,email">
            </div>
            <div class="control-group child-divcenter row mt-2" style="width:100%;">
                <input id="pass" type="password" name="ruc" placeholder="Ruc" patron="requerido,ruc">
            </div>
            <button type="button" class="btn btn-outline-primary w-100 m-2 btnLoginProveedor">Login</button>
            <!-- <a href="#">Forget Password<br> </a> -->
            <div class="text-center">
                <a href="<?= base_url() ?>FormularioProveedor/signup" style="color: lightgrey;">Registrarse<br> </a>
            </div>
        </fieldset>
    </form>

</div>