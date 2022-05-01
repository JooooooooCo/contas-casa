<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'fin_movimento/listagem.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div id="tela-listagem">
    <div class="row" v-show="sn_tela_listagem">
        <div class ="col s12 m10 push-m1">
            <div class="row">
                <div class ="col s12 valign-wrapper">
                    <div class ="col s12">
                        <h5 class="light white-text"> Lista de movimentos </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class ="col s12 valign-wrapper">
                    <div class ="col s6 valign">
                        <a  href="#"
                            name="btn-grid-selecionar-tudo"
                            class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            :data-tooltip="snPodeSelecionarTudo ? 'Selecionar tudo' : 'Limpar seleção'"
                            @click="snPodeSelecionarTudo ? selecionarTudo() : limparSelecao()"
                            :disabled="(!snPodeSelecionarTudo && !snPodeLimparSelecao) || !snPossuiRegistros"
                        >
                            <i class="material-icons" v-show="snPodeSelecionarTudo && !snPodeLimparSelecao">check_box_outline_blank</i>
                            <i class="material-icons" v-show="snPodeSelecionarTudo && snPodeLimparSelecao">indeterminate_check_box</i>
                            <i class="material-icons" v-show="!snPodeSelecionarTudo && snPodeLimparSelecao">check_box</i>
                            <i class="material-icons" v-show="!snPodeSelecionarTudo && !snPodeLimparSelecao">check_box_outline_blank</i>
                        </a>
                        <a  href="#"
                            name="btn-collapses-abertas"
                            class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            :data-tooltip="sn_collapses_abertas ? 'Abrir linhas' : 'Recolher linhas'"
                            @click="expandirRecolherCollapses()"
                            v-show="!sn_exibicao_grid"
                            :disabled="!snPossuiRegistros"
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
                            :disabled="!snPossuiRegistros"
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
                            :disabled="!snPossuiRegistros"
                        >
                            <i class="material-icons" v-show="sn_exibicao_grid">view_list</i>
                            <i class="material-icons" v-show="!sn_exibicao_grid">view_module</i>
                        </a>
                        <a  href="#"
                            name="btn-filtro"
                            class="btn white-text blue-grey darken-3 botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            data-tooltip="Filtros"
                            @click="sn_exibir_filtro = !sn_exibir_filtro"
                        >
                            <i class="material-icons">filter_list</i>
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

            <div class="row" v-show="sn_exibir_filtro">
                <div class='col s12'>
                    <div class="z-depth-1 card-padrao">
                        <div class="row">
                            <div class="col s12">
                                <div class="row mar-bottom-0">
                                    <div class="col s12 white-text mar-top-10">
                                        <b>FILTROS</b>
                                    </div>
                                </div>

                                <div class="row mar-bottom-0">
                                    <div class="col s12 m6">
                                        <v-radio-button
                                            v-model="objFiltros.cd_tipo_movimento"
                                            :arr-obj-opcoes="getOpcoesFiltroTipoMovimento"
                                            label="Tipo de movimento"
                                        ></v-radio-button>
                                    </div>

                                    <div class="col s12 m6">
                                        <v-radio-button
                                            v-model="objFiltros.cd_tipo_pgto"
                                            :arr-obj-opcoes="getOpcoesFiltroTipoPgto"
                                            label="Modo de pagamento"
                                        ></v-radio-button>
                                    </div>
                                </div>

                                <div class="row mar-bottom-0">
                                    <div class="col s12 m6">
                                        <v-radio-button
                                            v-model="objFiltros.cd_tipo_situacao_pgto"
                                            :arr-obj-opcoes="getOpcoesFiltroSituacao"
                                            label="Situação"
                                        ></v-radio-button>
                                    </div>
                                </div>

                                <div class="row mar-bottom-0">
                                    <div class="input-field col s12 m3 mar-top-30" @mouseleave="mixinHideDatePicker('dt_inicio')">
                                        <input
                                            type="text"
                                            autocomplete="off"
                                            name="dt_inicio"
                                            id="dt_inicio"
                                            v-model="objFiltros.dt_inicio"
                                            v-mask="'##/##/####'"
                                            @click="mixinShowDatePicker('dt_inicio')"
                                            @keyup="mixinHideDatePicker('dt_inicio')"
                                            class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                                        />
                                        <label for="dt_inicio">Vencimento início</label>
                                        <v-date-picker
                                            class="inline-block h-full"
                                            v-model="objFiltros.dt_inicio"
                                            v-if="objDatePickerExibir.dt_inicio"
                                            :model-config="modelConfigDatePicker"
                                            color="teal"
                                            is-dark
                                            @input="mixinHideDatePicker('dt_inicio')"
                                        ></v-date-picker>
                                    </div>

                                    <div class="input-field col s12 m3 mar-top-30" @mouseleave="mixinHideDatePicker('dt_fim')">
                                        <input
                                            type="text"
                                            autocomplete="off"
                                            name="dt_fim"
                                            id="dt_fim"
                                            v-model="objFiltros.dt_fim"
                                            v-mask="'##/##/####'"
                                            @click="mixinShowDatePicker('dt_fim')"
                                            @keyup="mixinHideDatePicker('dt_fim')"
                                            class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                                        />
                                        <label for="dt_fim">Vencimento fim</label>
                                        <v-date-picker
                                            class="inline-block h-full"
                                            v-model="objFiltros.dt_fim"
                                            v-if="objDatePickerExibir.dt_fim"
                                            :model-config="modelConfigDatePicker"
                                            color="teal"
                                            is-dark
                                            @input="mixinHideDatePicker('dt_fim')"
                                        ></v-date-picker>
                                    </div>

                                    <div :class="isMobile ? 'col s12 m6' : 'col s12 m6 mar-top-30'">
                                        <a class="btn white-text blue-grey darken-3 mar-right-5 mar-bottom-5" @click="alterarFiltroData(1)">Mês Anterior</a>
                                        <a class="btn white-text blue-grey darken-3 mar-right-5 mar-bottom-5" @click="alterarFiltroData(2)">Mês Atual</a>
                                        <a class="btn white-text blue-grey darken-3 mar-right-5 mar-bottom-5" @click="alterarFiltroData(3)">Mês Seguinte</a>
                                    </div>
                                </div>

                                <div class="row mar-top-10">
                                    <div class="input-field col s12 l6">
                                        <input type="text" name="ds_movimento_filtro" id="ds_movimento_filtro" v-model="objFiltros.ds_movimento">
                                        <label for="ds_movimento_filtro">Descrição pessoal</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <a class="btn darken-1 white-text teal darken-2 mar-right-5 mar-bottom-5" @click="filtrarGrid()">Filtrar</a>
                                        <a class="btn white-text blue-grey darken-3 mar-right-5 mar-bottom-5" @click="resetarObjFiltros()">Limpar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-show="!snPossuiRegistros">
                <div class ="col s12 white-text center">
                    <i class="material-icons large orange-text text-darken-3">error_outline</i>
                    <p>
                        Nenhum resultado encontrado.
                    </p>
                </div>
            </div>

            <div class="row" v-show="sn_exibicao_grid && snPossuiRegistros">
                <div class ="col s12">
                    <div
                        id="myGrid"
                        class="ag-theme-alpine-dark"
                        style="height: 78vh; width:100%;"
                        v-if="!mixinSnAlertCarregando"
                    ></div>
                </div>
            </div>

            <div class="row" v-show="!sn_exibicao_grid && snPossuiRegistros">
                <div class ="col s12 valign-wrapper">
                    <ul class="collapsible col s12">
                        <li v-for="(objMovimento, index) in arrMovimentos" :key="objMovimento.cd_movimento">
                            <div class="collapsible-header" :style="getCollapseHeaderStyle(index)" @click="clicouCollapse(objMovimento.cd_movimento)">
                                <div class="col s10">
                                    <i class="material-icons" :id="'checkbox-cd-movimento-' + objMovimento.cd_movimento">check_box_outline_blank</i>

                                    <span class="texto-uppercase">
                                        <b>{{objMovimento.dt_vcto}}</b> | R$ {{objMovimento.vl_original}}
                                        | {{objMovimento.nr_parcela_atual}} de {{objMovimento.nr_qtd_parcelas}}
                                        | {{objMovimento.ds_movimento.substring(0, 80)}} | Cód {{objMovimento.cd_movimento}}
                                    </span>
                                </div>

                                <div class="col s2 right-align" v-show="objMovimento.cd_tipo_movimento == 1">
                                    <i class="material-icons red-text" style="margin: 0px;">attach_money</i>
                                    <i class="material-icons red-text" style="margin: 0px;">arrow_downward</i>
                                </div>

                                <div class="col s2 right-align" v-show="objMovimento.cd_tipo_movimento == 2">
                                    <i class="material-icons green-text" style="margin: 0px;">attach_money</i>
                                    <i class="material-icons green-text" style="margin: 0px;">arrow_upward</i>
                                </div>
                            </div>
                            <div class="collapsible-body card-padrao">
                                <div class='row'>
                                    <div class="col s4">
                                        <b>Código:</b> {{objMovimento.cd_movimento}}
                                    </div>
                                    <div class="col s8">
                                        <b>Descrição:</b> {{objMovimento.ds_movimento}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Tipo:</b> {{objMovimento.ds_tipo_movimento}}
                                    </div>
                                    <div class="col s4">
                                            <b>Modo Pgto:</b> {{objMovimento.ds_tipo_pgto}}
                                    </div>
                                    <div class="col s4">
                                            &nbsp
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Situação:</b> {{objMovimento.ds_tipo_situacao_pgto}}
                                    </div>
                                    <div class="col s4">
                                            <b>Real / Adm:</b> {{objMovimento.sn_real ? 'Real' : 'Administrativo'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Parcela:</b> {{objMovimento.nr_parcela_atual}} de {{objMovimento.nr_qtd_parcelas}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Dt Vcto:</b> {{objMovimento.dt_vcto}}
                                    </div>
                                    <div class="col s4">
                                            <b>Dt Compra:</b> {{objMovimento.dt_compra}}
                                    </div>
                                    <div class="col s4">
                                            <b>Dt Pgto:</b> {{objMovimento.dt_pgto}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Vl Original:</b> {{objMovimento.vl_original}}
                                    </div>
                                    <div class="col s4">
                                            <b>Vl Pago:</b> {{objMovimento.vl_pago}}
                                    </div>
                                    <div class="col s4">
                                            <b>Dif Pgto:</b> {{objMovimento.vl_dif_pgto}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Grupo 1:</b> {{objMovimento.ds_tipo_grupo_i}}
                                    </div>
                                    <div class="col s4">
                                            <b>Grupo 2:</b> {{objMovimento.ds_tipo_grupo_ii}}
                                    </div>
                                    <div class="col s4">
                                            <b>Grupo 3:</b> {{objMovimento.ds_tipo_grupo_iii}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Obs 1:</b> {{objMovimento.ds_obs_i}}
                                    </div>
                                    <div class="col s4">
                                            <b>Obs 2:</b> {{objMovimento.ds_obs_ii}}
                                    </div>
                                    <div class="col s4">
                                            <b>Média Gastos:</b> {{objMovimento.ds_media_gastos}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div style="position: absolute;top: 5px;right: 15px;width: 100%" @mouseleave="sn_exibir_logout = false">
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
