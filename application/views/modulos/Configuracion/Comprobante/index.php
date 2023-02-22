<div class="row mt-4" >
    <div class="col-lg-2 d-flex justify-content-center align-items-center">
        <h3 class="card-title mb-3">
            <i class="<?= $icon?>"></i> <?= $title?>
        </h3>
    </div>
    <div class="col-lg-10 d-flex">
        <div class="card w-100 mb-3 p-0">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item btnReporte" name="tipoReporte">
                        <a data-toggle="tab" href="#tab-content-0" class="active nav-link" data-id="0" data-value="1">Lista </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block">
    <a href="javascript:;" class="customizer-close"><i class="fal fa-times"></i></a>
    <a href="javascript:;" class="customizer-toggle box-shadow-3 bg-trade-visual-grad-left text-white">
        <i class="fal fa-cog fa-lg fa-spin"></i>
    </a>
    <div class="customizer-content p-2 ps-container ps-theme-dark" data-ps-id="aca1f25c-4ed9-a04b-d154-95a5d6494748">
        <form id="gestionElementos">
            <div class="card-header" style="margin-bottom: 14px;">
                <h4>CONFIGURACIÓN</h4>
            </div>
            <div>
                <input type="hidden" id="idTipoFormato" name="tipoFormato" value="1">
            </div>
            <div class="customizer-content-button">
                <button type="button" class="btn btn-outline-trade-visual border-0 btn-Consultar" title="Filtrar">
                    <i class="fa fa-search"></i> <span class="txt_filtro"></span>
                </button>
                <button type="button" class="btn btn-outline-trade-visual border-0 btn-New"  title="Agregar">
                    <i class="fa fa-plus"></i> <span class="txt_filtro"></span>
                </button>
                <!--button type="button" class="btn btn-outline-trade-visual border-0 btn-Excel" id="btn-Excel" title="Excel">
                    <i class="far fa-lg  fa-file-excel"></i> <span class="txt_filtro"></span>
                </button>
                <button type="button" class="btn btn-outline-trade-visual border-0 btn-HistorialExcel" id="btn-HistorialExcel" title="Historial de Excel">
                    <i class="fas fa-lg fa-list"></i> <span class="txt_filtro"></span>
                </button-->
            </div>
            <hr>
            <div class="card-body" style="margin-bottom: 14px;" id="bodyFilter">

            </div>
        </form>
    </div>
</div>

<div class="main-card mb-3 card ">
    <div class="card-body p-0">
        <div class="tab-content" id="content-auditoria">
            <div class="tab-pane fade show active content" id="content" role="tabpanel">
                <?= getMensajeGestion('noResultados') ?>
            </div>

        </div>
    </div>
</div>
