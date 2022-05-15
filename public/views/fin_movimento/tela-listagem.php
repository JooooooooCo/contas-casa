<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'fin_movimento/listagem.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div id="tela-listagem">
    <div class="fundo-carregamento-inicial" v-show="!sn_tela_carregada"></div>
    <div class="row" v-show="sn_tela_listagem">
        <div class ="col s12">
            <div class="row">
                <div class ="col s12 valign-wrapper">
                    <div class ="col s12">
                        <h5 class="light blue-grey-text text-darken-4"> Lista de movimentos </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class ="col s12 valign-wrapper">
                    <div class ="col s6 valign">
                        <a  href="#"
                            name="btn-grid-selecionar-tudo"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
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
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            :data-tooltip="sn_collapses_abertas ? 'Abrir linhas' : 'Recolher linhas'"
                            @click="expandirRecolherCollapses()"
                            v-show="!sn_exibicao_grid"
                            :disabled="!snPossuiRegistros"
                        >
                            <i class="material-icons">{{sn_collapses_abertas ? 'layers_clear' : 'layers'}}</i>
                        </a>
                        <a  href="#"
                            name="btn-grid-completa-reduzida"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            :data-tooltip="sn_grid_completa ? 'Exibir tabela reduzida' : 'Exibir tabela completa'"
                            @click="alteraExibicaoColunasGrid()"
                            v-show="sn_exibicao_grid"
                            :disabled="!snPossuiRegistros"
                        >
                            <i class="material-icons">{{sn_grid_completa ? 'tab_unselected' : 'tab'}}</i>
                        </a>
                        <a  href="#"
                            name="btn-grid-card"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            :data-tooltip="sn_exibicao_grid ? 'Lista' : 'Planilha'"
                            @click="alteraExibicaoGridCard()"
                            :disabled="!snPossuiRegistros"
                        >
                            <i class="material-icons">{{sn_exibicao_grid ? 'view_list' : 'view_module'}}</i>
                        </a>
                        <a  href="#"
                            name="btn-filtro"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            data-tooltip="Totalizadores"
                            @click="sn_exibir_totalizadores = !sn_exibir_totalizadores"
                        >
                            <i class="material-icons">{{sn_exibir_totalizadores ? 'visibility_off' : 'visibility'}}</i>
                        </a>
                        <a  href="#"
                            name="btn-filtro"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
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
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
                            data-position="bottom"
                            data-tooltip="Incluir"
                            @click="incluirMovimento()"
                        ><i class="material-icons">add</i></a>
                        <a  href="#"
                            name="btn-editar"
                            class="btn blue-grey-text text-darken-4 white botao-icone tooltipped mar-top-5"
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
                                    <div class="col s12 black-text mar-top-10">
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
                                            :disabled="objFiltros.sn_somente_adicionados_hoje"
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
                                            :disabled="objFiltros.sn_somente_adicionados_hoje"
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
                                            @input="mixinHideDatePicker('dt_fim')"
                                        ></v-date-picker>
                                    </div>

                                    <div :class="isMobile ? 'col s12 m6' : 'col s12 m6 mar-top-30'">
                                        <a class="btn blue-grey-text text-darken-4 white mar-right-5 mar-bottom-5"
                                            :disabled="objFiltros.sn_somente_adicionados_hoje"
                                            @click="alterarFiltroData(1)"
                                        >Mês Anterior</a>
                                        <a class="btn blue-grey-text text-darken-4 white mar-right-5 mar-bottom-5"
                                            :disabled="objFiltros.sn_somente_adicionados_hoje"
                                            @click="alterarFiltroData(2)"
                                        >Mês Atual</a>
                                        <a class="btn blue-grey-text text-darken-4 white mar-right-5 mar-bottom-5"
                                            :disabled="objFiltros.sn_somente_adicionados_hoje"
                                            @click="alterarFiltroData(3)"
                                        >Mês Seguinte</a>
                                    </div>
                                </div>

                                <div class="row mar-top-10">
                                    <div class="input-field col s12 m4">
                                        <v-select
                                            input-id="filtroTipoGrupoI"
                                            :options="arrTipoGrupoI"
                                            v-model="objFiltros.objTipoGrupoI"
                                            label="ds_opcao"
                                        ></v-select>
                                        <label class="active">Grupo 1</label>
                                    </div>
                                    <div class="input-field col s12 m4">
                                        <v-select
                                            input-id="filtroTipoGrupoII"
                                            :options="arrTipoGrupoII"
                                            v-model="objFiltros.objTipoGrupoII"
                                            label="ds_opcao"
                                            @input="carregarOpcoesGrupoIII()"
                                            :reset-on-options-change="true"
                                        ></v-select>
                                        <label class="active">Grupo 2</label>
                                    </div>
                                    <div class="input-field col s12 m4">
                                        <v-select
                                            input-id="TipoGrupoIII"
                                            :options="arrTipoGrupoIII"
                                            v-model="objFiltros.objTipoGrupoIII"
                                            label="ds_opcao"
                                            :reset-on-options-change="true"
                                            :disabled="!objFiltros.objTipoGrupoII"
                                        ></v-select>
                                        <label class="active">Grupo 3</label>
                                    </div>
                                </div>

                                <div class="row mar-top-10">
                                    <div class="input-field col s12 l6">
                                        <input type="text" name="ds_movimento_filtro" id="ds_movimento_filtro" v-model="objFiltros.ds_movimento">
                                        <label for="ds_movimento_filtro">Descrição pessoal</label>
                                    </div>

                                    <div class="col s12 l6 mar-top-30">
                                        <label>
                                            <input
                                                class="filled-in"
                                                type="checkbox"
                                                id="sn_somente_adicionados_hoje"
                                                name="sn_somente_adicionados_hoje"
                                                v-model="objFiltros.sn_somente_adicionados_hoje"
                                            />
                                            <span>Somente adicionados hoje</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <a class="btn darken-1 white-text teal darken-2 mar-right-5 mar-bottom-5" @click="filtrarGrid()">Filtrar</a>
                                        <a class="btn blue-grey-text text-darken-4 white mar-right-5 mar-bottom-5" @click="resetarObjFiltros()">Limpar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-show="!snPossuiRegistros">
                <div class ="col s12 blue-grey-text text-darken-2 center">
                    <img src="<?php echo ROTA_SITE_IMAGES; ?>warning.png" class="imagem-warning" alt="erro sem registros">
                    <br>
                    <b>Nenhum resultado encontrado</b>
                </div>
            </div>

            <div class="row mar-bottom-5" v-show="sn_exibir_totalizadores && snPossuiRegistros">
                <div class='col s6 l3'>
                    <div class="card-panel right-align teal">
                        <i class="material-icons medium white-text card-panel-icon">
                            {{objTotalizadores?.vl_saldo_anterior_pago > 0 ? 'trending_up' : 'trending_down'}}
                        </i>
                        <span class="white-text card-panel-header">
                            SALDO INICIAL
                        </span>
                        </br>
                        <span class="white-text card-panel-body">
                            R$ {{objTotalizadores?.vl_saldo_anterior_pago}}
                        </span>
                    </div>
                </div>
                <div class='col s6 l3'>
                    <div class="card-panel right-align orange darken-3">
                        <i class="material-icons medium white-text card-panel-icon">
                            {{objTotalizadores?.vl_saldo_final_previsto > 0 ? 'trending_up' : 'trending_down'}}
                        </i>
                        <span class="white-text card-panel-header">
                            SALDO FINAL
                        </span>
                        </br>
                        <span class="white-text card-panel-body">
                            R$ {{objTotalizadores?.vl_saldo_final_previsto}}
                        </span>
                    </div>
                </div>
                <div class='col s6 l3'>
                    <div class="card-panel right-align teal darken-3">
                        <i class="material-icons medium white-text card-panel-icon">
                            {{objTotalizadores?.vl_saldo_pago > 0 ? 'trending_up' : 'trending_down'}}
                        </i>
                        <span class="white-text card-panel-header">
                            SALDO ATUAL
                        </span>
                        </br>
                        <span class="white-text card-panel-body">
                            R$ {{objTotalizadores?.vl_saldo_pago}}
                        </span>
                    </div>
                </div>
                <div class='col s6 l3'>
                    <div class="card-panel card-panel-small right-align teal darken-2">
                        <i class="material-icons small white-text card-panel-icon-small">attach_money</i>
                        <span class="white-text card-panel-header-small">
                            RECEITA
                        </span>
                        <span class="white-text card-panel-body-small">
                            R$ {{objTotalizadores?.vl_receita_previsto}}
                        </span>
                    </div>
                    <div class="card-panel card-panel-small right-align teal darken-2">
                        <i class="material-icons small white-text card-panel-icon-small">money_off</i>
                        <span class="white-text card-panel-header-small">
                            DESPESA
                        </span>
                        <span class="white-text card-panel-body-small">
                            R$ {{objTotalizadores?.vl_despesa_previsto}}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row" v-show="sn_exibicao_grid && snPossuiRegistros">
                <div class ="col s12">
                    <div
                        id="myGrid"
                        class="ag-theme-material"
                        style="height: 75.5vh; width:100%;"
                        v-if="!mixinSnAlertCarregando"
                    ></div>
                </div>
            </div>

            <div class="row" v-show="!sn_exibicao_grid && snPossuiRegistros">
                <div class ="col s12 valign-wrapper">
                    <ul class="collapsible col s12 white" style="padding-bottom: 10px;">
                        <li v-for="(objMovimento, index) in arrMovimentos" :key="objMovimento.cd_movimento">
                            <div class="collapsible-header white" @click="clicouCollapse(objMovimento.cd_movimento)">
                                <div class="col s1 padding-0 valign-wrapper">
                                    <i class="material-icons blue-grey-text text-darken-4" :id="'checkbox-cd-movimento-' + objMovimento.cd_movimento">check_box_outline_blank</i>
                                </div>

                                <div class="col s9">
                                    <div class="col s12 padding-0">
                                        <div class="col s6 padding-0">
                                            <span class="texto-uppercase blue-grey-text text-darken-4">
                                                <b>{{objMovimento.dt_vcto ?? 'NÃO INFORMADO'}}</b>
                                            </span>
                                        </div>
                                        <div class="col s6 padding-0">
                                            <span class="texto-uppercase blue-grey-text text-darken-4">
                                                R$ {{objMovimento.vl_original ?? 'NÃO INFORMADO'}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col s12 padding-0">
                                        <div class="col s6 padding-0">
                                            <span class="texto-uppercase blue-grey-text text-darken-4">
                                                Cód {{objMovimento.cd_movimento ?? 'NÃO INFORMADO'}}
                                            </span>
                                        </div>
                                        <div class="col s6 padding-0">
                                            <span class="texto-uppercase blue-grey-text text-darken-4">
                                                {{objMovimento.nr_parcela_atual ?? 'NÃO INFORMADO'}} de {{objMovimento.nr_qtd_parcelas ?? 'NÃO INFORMADO'}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col s12 padding-0">
                                        <span class="texto-uppercase blue-grey-text text-darken-4">
                                            {{objMovimento.ds_movimento ? objMovimento.ds_movimento.substring(0, 80) : 'NÃO INFORMADO'}}
                                        </span>
                                    </div>
                                </div>

                                <div class="col s2 valign-wrapper-right">
                                    <i class="material-icons green-text" style="margin: 0px;" v-show="objMovimento.cd_tipo_situacao_pgto == 1">check_circle</i>
                                    <i class="material-icons orange-text" style="margin: 0px;" v-show="objMovimento.cd_tipo_situacao_pgto == 2">info</i>
                                    <i class="material-icons blue-text" style="margin: 0px;" v-show="objMovimento.cd_tipo_situacao_pgto == 3">pause_circle_filled</i>
                                    <i class="material-icons red-text" style="margin: 0px;" v-show="objMovimento.cd_tipo_movimento == 1">arrow_downward</i>
                                    <i class="material-icons green-text" style="margin: 0px;" v-show="objMovimento.cd_tipo_movimento == 2">arrow_upward</i>
                                </div>
                            </div>
                            <div class="collapsible-body card-padrao blue-grey-text text-darken-4">
                                <div class='row'>
                                    <div class="col s4">
                                        <b>Código:</b> {{objMovimento.cd_movimento ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s8">
                                        <b>Descrição:</b> {{objMovimento.ds_movimento ?? 'NÃO INFORMADO'}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Tipo:</b> {{objMovimento.ds_tipo_movimento ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Modo Pgto:</b> {{objMovimento.ds_tipo_pgto ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            &nbsp
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Situação:</b> {{objMovimento.ds_tipo_situacao_pgto ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Real / Adm:</b> {{objMovimento.sn_real ? 'REAL' : 'ADMINISTRATIVO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Parcela:</b> {{objMovimento.nr_parcela_atual ?? 'NÃO INFORMADO'}} de {{objMovimento.nr_qtd_parcelas ?? 'NÃO INFORMADO'}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Dt Vcto:</b> {{objMovimento.dt_vcto ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Dt Compra:</b> {{objMovimento.dt_compra ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Dt Pgto:</b> {{objMovimento.dt_pgto ?? 'NÃO INFORMADO'}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Vl Original:</b> {{objMovimento.vl_original ?? 'NÃO INFORMADO'}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Grupo 1:</b> {{objMovimento.ds_tipo_grupo_i ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Grupo 2:</b> {{objMovimento.ds_tipo_grupo_ii ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Grupo 3:</b> {{objMovimento.ds_tipo_grupo_iii ?? 'NÃO INFORMADO'}}
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col s4">
                                            <b>Obs 1:</b> {{objMovimento.ds_obs_i ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Obs 2:</b> {{objMovimento.ds_obs_ii ?? 'NÃO INFORMADO'}}
                                    </div>
                                    <div class="col s4">
                                            <b>Média Gastos:</b> {{objMovimento.ds_media_gastos ?? 'NÃO INFORMADO'}}
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
                        <i class="material-icons blue-grey-text text-darken-4" @mouseover="sn_exibir_logout = true">menu</i>

                        <div v-show="sn_exibir_logout" class="blue-grey darken-4" style="position: absolute; width: fit-content; right: 5px; padding: 10px; border-radius: 3px; z-index: 499;">
                            <a href="<?php echo ROTA_SITE_VIEWS; ?>home/tela-selecao-centro-custo.php" class="white-text">Trocar o centro de custo</a>
                            </br>
                            <a href="<?php echo ROTA_SITE_ROOT; ?>index.php" class="white-text">Sair</a>
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
