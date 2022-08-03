<table border="1" style="width: 100%; float: left;">
    <tr>
        <td style="text-align: center;"><img src="<?=base_url()?>/public/assets/images/visualimpact/logo.png" style="width: 135px; height: 55px;"></td>
        <td style="text-align: center;"><b><?=!empty($title) ? $title : '' ?></b></td>
        <td style="text-align: left;">
            <?if(!empty($codigo)){?>
                <p>COD: <?=!empty($codigo)? $codigo : '-' ?></p>
            <?}?>
            <p>REVISIÓN: 00</p>
            <p>PÁGINA 1 de 1</p>
        </td>
    </tr>
</table>