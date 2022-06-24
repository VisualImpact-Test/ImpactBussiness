<?
$wireframe = '../public/assets/images/wireframe/image.png';
?>
    <h4 class="ui top header">
    <?=$data['item']?>
    </h4>
<div class="ui form">
    <div class="fields">
        <div class="eight wide field">
            <div class="ui sub header">Tipo</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Tipo" value="<?=!empty($data['tipoItem']) ? $data['tipoItem'] : ''?>">
        </div>
        <div class="eight wide field">
            <div class="ui sub header">Equivalente Logística</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Equivalente" value="<?=!empty($data['equivalenteLogistica']) ? $data['equivalenteLogistica'] : ''?>" readonly>
        </div>
    </div>
    <div class="fields">
        <div class="eight wide field">
            <div class="ui sub header">Categoria</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Categoria" value="<?=!empty($data['itemCategoria']) ? $data['itemCategoria'] : ''?>" readonly>
        </div>
        <div class="eight wide field">
            <div class="ui sub header">Marca</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Marca" value="<?=!empty($data['itemMarca']) ? $data['itemMarca'] : ''?>" readonly>
        </div>
    </div>
    <div class="fields">
        <div class="sixteen wide field">
            <div class="ui sub header">Unidad de Medida</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Unidad de medida" value="<?=!empty($data['unidadMedida']) ? $data['unidadMedida'] : ''?>" readonly>
        </div>
    </div>
    <?if(COD_TEXTILES == $data['idItemTipo']){?>
    <div class="fields">
        <div class="five wide field">
            <div class="ui sub header">Talla</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Talla" value="<?=!empty($data['talla']) ? $data['talla'] : ''?>" readonly>
        </div>
        <div class="five wide field">
            <div class="ui sub header">Tela</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Tela" value="<?=!empty($data['tela']) ? $data['tela'] : ''?>" readonly>
        </div>
        <div class="five wide field">
            <div class="ui sub header">Color</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Color" value="<?=!empty($data['color']) ? $data['color'] : ''?>" readonly>
        </div>
    </div>
    <?}?>
    <?if(COD_TARJETAS_VALES == $data['idItemTipo']){?>
    <div class="fields">
        <div class="sixteen wide field">
            <div class="ui sub header">Monto S/</div>
            <input id="nombre" name="nombre" patron="requerido" placeholder="Monto" value="<?=!empty($data['monto']) ? $data['monto'] : ''?>" readonly>
        </div>
    </div>
    <?}?>

</div>
<div class="ui segment">
    <div class="ui two column very relaxed grid">
        <div class="column">
            <div class="ui tiny images">
                <img class="ui image" src="<?= $wireframe ?>">
                <img class="ui image" src="<?= $wireframe ?>">
                <img class="ui image" src="<?= $wireframe ?>">
                <img class="ui image" src="<?= $wireframe ?>">
                <img class="ui image" src="<?= $wireframe ?>">
            </div>
        </div>
        <div class="column">
            <div class="ui relaxed divided list">
                <div class="item">
                    <i class="large file pdf middle aligned icon"></i>
                    <div class="content">
                        <a class="header">Semantic-Org/Semantic-UI</a>
                        <div class="description">Updated 10 mins ago</div>
                    </div>
                </div>
                <div class="item">
                    <i class="large file pdf middle aligned icon"></i>
                    <div class="content">
                        <a class="header">Semantic-Org/Semantic-UI-Docs</a>
                        <div class="description">Updated 22 mins ago</div>
                    </div>
                </div>
                <div class="item">
                    <i class="large file pdf middle aligned icon"></i>
                    <div class="content">
                        <a class="header">Semantic-Org/Semantic-UI-Docs</a>
                        <div class="description">Updated 22 mins ago</div>
                    </div>
                </div>
                <div class="item">
                    <i class="large linkify middle aligned icon"></i>
                    <div class="content">
                        <a class="header">Semantic-Org/Semantic-UI-Meteor</a>
                        <div class="description">Updated 34 mins ago</div>
                    </div>
                </div>
                <div class="item">
                    <i class="large linkify middle aligned icon"></i>
                    <div class="content">
                        <a class="header">Semantic-Org/Semantic-UI-Meteor</a>
                        <div class="description">Updated 34 mins ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui vertical divider">
    </div>
</div>