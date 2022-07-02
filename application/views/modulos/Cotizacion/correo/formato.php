<style>
    @import url('https://fonts.googleapis.com/css?family=Lato:100&display=swap');

    body,
    html {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        font-family: 'Lato', sans-serif;
    }

    .btn:link,
    .btn:visited {
        text-transform: uppercase;
        text-decoration: none;
        padding: 15px 40px;
        display: inline-block;
        border-radius: 100px;
        transition: all .2s;
        position: relative;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn:active {
        transform: translateY(-1px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-white {
        background-color: #c90101;
        color: #fff;
    }

    .btn::after {
        content: "";
        display: inline-block;
        height: 100%;
        width: 100%;
        border-radius: 100px;
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
        transition: all .4s;
    }

    .btn-white::after {
        background-color: #c90101;
    }

    .btn:hover::after {
        transform: scaleX(1.4) scaleY(1.6);
        opacity: 0;
    }

    .btn-animated {
        animation: moveInBottom 5s ease-out;
        animation-fill-mode: backwards;
    }
</style>

<body style="width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  overflow: hidden;
  font-family: 'Lato', sans-serif;">
    <div style="background-color: #fbfbfb; padding: 20px; border: 3px #2586DA solid;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);transition: 0.3s;border-radius:10px;">
        <?= (!empty($html) ? $html : ''); ?>
        <?if(!empty($link)){?>
            <p>En caso de que el enlace no funcione, copie y pegue el siguiente link en su navegador: <?= $link ?></p>
        <?}?>
		<p style="margin-top:10px;width: 270px;"><strong>Correo Contacto: teamsystem@visualimpact.com.pe</strong></p>
        <?= getMensajeGestion('email_envio') ?>
    </div>
</body>