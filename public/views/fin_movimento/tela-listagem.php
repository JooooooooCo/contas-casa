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
                <div class ="col s2 valign">
                    <a  href="#"
                        name="btn-grid-completa"
                        class="btn darken-1 white-text botao-icone teal darken-2 tooltipped"
                        data-position="bottom"
                        data-tooltip="Exibir tabela completa"
                        @click="alteraExibicaoColunasGrid()"
                        v-show="!sn_grid_completa"
                    ><i class="material-icons">tab</i></a>
                    <a  href="#"
                        name="btn-grid-reduzida"
                        class="btn darken-1 white-text botao-icone orange darken-3 tooltipped"
                        data-position="bottom"
                        data-tooltip="Exibir tabela reduzida"
                        @click="alteraExibicaoColunasGrid()"
                        v-show="sn_grid_completa"
                    ><i class="material-icons">tab_unselected</i></a>
                </div>
                <div class ="col s10 right-align valign">
                    <a  href="#"
                        name="btn-incluir"
                        class="btn darken-1 white-text botao-icone teal darken-2"
                        @click="incluirMovimento()"
                    ><i class="material-icons">add</i></a>
                    <a  href="#"
                        name="btn-editar"
                        class="btn darken-1 white-text botao-icone teal darken-2"
                        :disabled="!sn_uma_linha_selecionada"
                        @click="editarMovimento()"
                    ><i class="material-icons">edit</i></a>
                    <a  href="#"
                        name="btn-remover"
                        class="btn waves-effect waves-light red darken-3 botao-icone"
                        :disabled="!sn_uma_linha_selecionada"
                        @click="removerMovimento()"
                    ><i class="material-icons">delete</i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class ="col s12">
                <div
                    id="myGrid"
                    class="ag-theme-alpine-dark"
                    style="height: 70vh; width:100%;"
                    v-if="!sn_carregando"
                ></div>
            </div>
        </div>

        <div class='row'>
            <div class="col s12 right-align">
                <a href="<?php echo ROTA_SITE_VIEWS; ?>home/tela-selecao-centro-custo.php">Trocar o centro de custo</a>
            </div>
            <div class="col s12 right-align">
                <a href="<?php echo ROTA_SITE_ROOT; ?>index.php">Sair</a>
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
