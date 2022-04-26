<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'fin_movimento/listagem.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div id="tela-listagem">
    <div v-show="sn_tela_listagem">
        <div class="row">
            <div class ="col s12 m10 push-m1 valign-wrapper">
                <div class ="col s12">
                    <h5 class="light white-text"> Lista de movimentos </h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class ="col s12 m10 push-m1 valign-wrapper">
                <div class ="col s6 valign">
                    <a  href="#"
                        name="btn-grid-selecionar-tudo"
                        class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                        data-position="bottom"
                        :data-tooltip="snPodeSelecionarTudo ? 'Selecionar tudo' : 'Limpar seleção'"
                        @click="snPodeSelecionarTudo ? selecionarTudo() : limparSelecao()"
                    >
                        <i class="material-icons" v-show="snPodeSelecionarTudo && !snPodeLimparSelecao">check_box_outline_blank</i>
                        <i class="material-icons" v-show="snPodeSelecionarTudo && snPodeLimparSelecao">indeterminate_check_box</i>
                        <i class="material-icons" v-show="!snPodeSelecionarTudo && snPodeLimparSelecao">check_box</i>
                    </a>
                    <a  href="#"
                        name="btn-collapses-abertas"
                        class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                        data-position="bottom"
                        :data-tooltip="sn_collapses_abertas ? 'Abrir linhas' : 'Recolher linhas'"
                        @click="expandirRecolherCollapses()"
                        v-show="!sn_exibicao_grid"
                    >
                        <i class="material-icons" v-show="sn_collapses_abertas">layers_clear</i>
                        <i class="material-icons" v-show="!sn_collapses_abertas">layers</i>
                    </a>
                    <a  href="#"
                        name="btn-grid-completa-reduzida"
                        class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                        data-position="bottom"
                        :data-tooltip="sn_grid_completa ? 'Exibir tabela reduzida' : 'Exibir tabela completa'"
                        @click="alteraExibicaoColunasGrid()"
                        v-show="sn_exibicao_grid"
                    >
                        <i class="material-icons" v-show="sn_grid_completa">tab_unselected</i>
                        <i class="material-icons" v-show="!sn_grid_completa">tab</i>
                    </a>
                    <a  href="#"
                        name="btn-grid-card"
                        class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                        data-position="bottom"
                        :data-tooltip="sn_exibicao_grid ? 'Lista' : 'Planilha'"
                        @click="alteraExibicaoGridCard()"
                    >
                        <i class="material-icons" v-show="sn_exibicao_grid">view_list</i>
                        <i class="material-icons" v-show="!sn_exibicao_grid">view_module</i>
                    </a>
                </div>
                <div class ="col s6 right-align valign">
                    <a  href="#"
                        name="btn-incluir"
                        class="btn darken-1 white-text botao-icone teal darken-2 tooltipped mar-top-5"
                        data-position="bottom"
                        data-tooltip="Incluir"
                        @click="incluirMovimento()"
                    ><i class="material-icons">add</i></a>
                    <a  href="#"
                        name="btn-editar"
                        class="btn darken-1 white-text botao-icone orange darken-3 tooltipped mar-top-5"
                        data-position="bottom"
                        data-tooltip="Editar"
                        :disabled="!snUmaLinhaSelecionada"
                        @click="editarMovimento()"
                    ><i class="material-icons">edit</i></a>
                    <a  href="#"
                        name="btn-remover"
                        class="btn waves-effect waves-light red darken-3 botao-icone tooltipped mar-top-5"
                        data-position="bottom"
                        data-tooltip="Remover"
                        :disabled="!snLinhasSelecionadas"
                        @click="removerMovimento()"
                    ><i class="material-icons">delete</i></a>
                </div>
            </div>
        </div>

        <div class="row" v-show="sn_exibicao_grid">
            <div class ="col s12">
                <div
                    id="myGrid"
                    class="ag-theme-alpine-dark"
                    style="height: 78vh; width:100%;"
                    v-if="!mixinSnAlertCarregando"
                ></div>
            </div>
        </div>

        <div style="position: absolute;top: 5px;right: 5px;width: 100%" @mouseleave="sn_exibir_logout = false">
            <div class='row'>
                <div class="col s12 right-align">
                    <i class="material-icons white-text" @mouseover="sn_exibir_logout = true">menu</i>

                    <div v-show="sn_exibir_logout" style="background-color: #2c3032; position: absolute; width: fit-content; right: 5px; padding: 10px; border-radius: 3px; z-index: 999;">
                        <a href="<?php echo ROTA_SITE_VIEWS; ?>home/tela-selecao-centro-custo.php">Trocar o centro de custo</a>
                        </br>
                        <a href="<?php echo ROTA_SITE_ROOT; ?>index.php">Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="!sn_tela_listagem">
        <tela-incluir-alterar
            :sn-alterar="sn_alterar"
            :obj-movimento-selecionado="objMovimentoSelecionado"
            :arr-tipo-movimento="arrTipoMovimento"
            :arr-tipo-pgto="arrTipoPgto"
            :arr-tipo-situacao-pgto="arrTipoSituacaoPgto"
            :arr-tipo-grupo-i="arrTipoGrupoI"
            @voltar="voltouTelaListagem()"
        ></tela-incluir-alterar>
    </div>
</div>

<script type="module" src="<?php echo ROTA_SITE_PUBLIC; ?>js/fin_movimento/tela-listagem.js"></script>

<?php
include_once ROTA_FOLDER_VIEWS . 'fin_movimento/tela-incluir-alterar.php';
include_once ROTA_FOLDER_INCLUDES . 'footer.php';
?>
