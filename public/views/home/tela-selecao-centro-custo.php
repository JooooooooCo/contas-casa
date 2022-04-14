<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'home/selecao-centro-custo.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div id="tela-selecao-centro-custo" v-show="!sn_carregando">
    <div class="row" id="index_login">
        <div class="col s12" style="min-height: 100vh;">
            <div class="col s12 m6 l4" style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                ">
                <div class="z-depth-1 row">
                    <form id="form-selecao-centro-custo" class="col s12" method="POST" action="<?php echo ROTA_SITE_VIEWS; ?>fin_movimento/tela-listagem.php">
                        <div class='row mar-top-20 center-align'>
                            <i class="material-icons white-text large">account_balance_wallet</i>
                        </div>

                        <div class='row mar-top-10'>
                            <div class="col s12 center-align">
                                <v-radio-button
                                    v-model="cd_centro_custo"
                                    :arr-obj-opcoes="arrCentroCusto"
                                    label="Selecione o Centro de Custo"
                                    @input="enviarForm()"
                                ></v-radio-button>
                            </div>
                        </div>
                        <input type="hidden" id="cd_centro_custo" name="cd_centro_custo" :value="cd_centro_custo">

                        <div class='row mar-top-10'>
                            <div class="col s12 right-align">
                                <a href="<?php echo ROTA_SITE_ROOT; ?>index.php">Sair</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="<?php echo ROTA_SITE_PUBLIC; ?>js/home/tela-selecao-centro-custo.js"></script>

<?php
include_once ROTA_FOLDER_INCLUDES . 'footer.php';
?>
