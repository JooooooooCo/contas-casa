<template id="tela-incluir-alterar">
    <div>
        <div class="row">
            <div class ="col s12 m10 push-m1 valign-wrapper">
                <div class ="col s12 m6 l6">
                    <h5 class="light white-text" v-show="!snAlterar">Incluir movimento</h5>
                    <h5 class="light white-text" v-show="snAlterar">Alterar movimento</h5>
                </div>
                <div class ="col s12 m6 l6 right-align valign">
                    <a href="#" name="btn-voltar" class="btn waves-effect waves-light red darken-3 botao-icone" @click="voltarTelaListagem()">
                        <i class="material-icons">arrow_back</i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class ="col s12 m10 push-m1">
                <form>
                    <div class="row">
                        <div class="col s12">
                            <v-radio-button
                                v-model="objDados.cd_tipo_movimento"
                                :arr-obj-opcoes="arrTipoMovimento"
                                label="Tipo de movimento"
                                @change="carregarOpcoesGrupoII()"
                            ></v-radio-button>
                        </div>

                        <div class="col s12 m6">
                            <v-radio-button
                                v-model="objDados.cd_tipo_pgto"
                                :arr-obj-opcoes="arrTipoPgto"
                                label="Modo pagamento"
                            ></v-radio-button>
                        </div>

                        <div class="col s12 m6">
                            <v-radio-button
                                v-model="objDados.cd_tipo_situacao_pgto"
                                :arr-obj-opcoes="arrTipoSituacaoPgto"
                                label="Situação"
                            ></v-radio-button>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12 m4">
                            <input
                                type="text"
                                autocomplete="off"
                                name="dt_compra"
                                id="dt_compra"
                                v-model="objDados.dt_compra"
                                v-mask="'##/##/####'"
                                @click="mixinShowDatePicker('dt_compra')"
                                @keyup="mixinHideDatePicker('dt_compra')"
                                class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                            />
                            <label for="dt_compra">Data Compra</label>
                            <v-date-picker
                                class="inline-block h-full"
                                v-model="objDados.dt_compra"
                                v-if="objDatePickerExibir.dt_compra"
                                :model-config="modelConfigDatePicker"
                                color="teal"
                                is-dark
                                @input="mixinHideDatePicker('dt_compra')"
                            ></v-date-picker>
                        </div>

                        <div class="input-field col s12 m4">
                            <input
                                type="text"
                                autocomplete="off"
                                name="dt_vcto"
                                id="dt_vcto"
                                v-model="objDados.dt_vcto"
                                v-mask="'##/##/####'"
                                @click="mixinShowDatePicker('dt_vcto')"
                                @keyup="mixinHideDatePicker('dt_vcto')"
                                class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                            />
                            <label for="dt_vcto">Data Vencimento</label>
                            <v-date-picker
                                class="inline-block h-full"
                                v-model="objDados.dt_vcto"
                                v-if="objDatePickerExibir.dt_vcto"
                                :model-config="modelConfigDatePicker"
                                color="teal"
                                is-dark
                                @input="mixinHideDatePicker('dt_vcto')"
                            ></v-date-picker>
                        </div>

                        <div class="input-field col s12 m4">
                            <input
                                type="text"
                                autocomplete="off"
                                name="dt_pgto"
                                id="dt_pgto"
                                v-model="objDados.dt_pgto"
                                v-mask="'##/##/####'"
                                @click="mixinShowDatePicker('dt_pgto')"
                                @keyup="mixinHideDatePicker('dt_pgto')"
                                class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                            />
                            <label for="dt_pgto">Data Pagamento</label>
                            <v-date-picker
                                class="inline-block h-full"
                                v-model="objDados.dt_pgto"
                                v-if="objDatePickerExibir.dt_pgto"
                                :model-config="modelConfigDatePicker"
                                color="teal"
                                is-dark
                                @input="mixinHideDatePicker('dt_pgto')"
                            ></v-date-picker>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12 m4">
                            <input type="text" name="vl_original" id="vl_original" v-model="objDados.vl_original" @keyup="formataMonetario('vl_original')">
                            <label for="vl_original">Valor Original</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input type="text" name="vl_pago" id="vl_pago" v-model="objDados.vl_pago" @keyup="formataMonetario('vl_pago')">
                            <label for="vl_pago">Valor Pago</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input type="text" name="vl_dif_pgto" id="vl_dif_pgto" :value="getVlDifPgto" disabled>
                            <label for="vl_dif_pgto">Dif. Pagamento</label>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12 m6">
                            <input type="number" name="nr_parcela_atual" id="nr_parcela_atual" v-model="objDados.nr_parcela_atual">
                            <label for="nr_parcela_atual">Parcela atual</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="number" name="nr_qtd_parcelas" id="nr_qtd_parcelas" v-model="objDados.nr_qtd_parcelas">
                            <label for="nr_qtd_parcelas">Qtd Parcelas</label>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12 m4">
                            <v-select
                                :select-on-tab="true"
                                input-id="objTipoGrupoI"
                                :options="arrTipoGrupoI"
                                v-model="objDados.objTipoGrupoI"
                                label="ds_opcao"
                            ></v-select>
                            <label class="active">Grupo 1</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <v-select
                                :select-on-tab="true"
                                input-id="objTipoGrupoII"
                                :options="arrTipoGrupoII"
                                v-model="objDados.objTipoGrupoII"
                                label="ds_opcao"
                                @input="carregarOpcoesGrupoIII()"
                                :reset-on-options-change="true"
                            ></v-select>
                            <label class="active">Grupo 2</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <v-select
                                :select-on-tab="true"
                                input-id="TipoGrupoIII"
                                :options="arrTipoGrupoIII"
                                v-model="objDados.objTipoGrupoIII"
                                label="ds_opcao"
                                :reset-on-options-change="true"
                            ></v-select>
                            <label class="active">Grupo 3</label>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12">
                            <input type="text" name="ds_movimento" id="ds_movimento" v-model="objDados.ds_movimento">
                            <label for="ds_movimento">Descricao pessoal</label>
                        </div>
                    </div>
                    <div class="row mar-top-10">
                        <div class="input-field col s12 m4">
                            <input type="text" name="ds_obs_i" id="ds_obs_i" v-model="objDados.ds_obs_i">
                            <label for="ds_obs_i">Obs um</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input type="text" name="ds_obs_ii" id="ds_obs_ii" v-model="objDados.ds_obs_ii">
                            <label for="ds_obs_ii">Obs dois</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input type="text" name="ds_media_gastos" id="ds_media_gastos" v-model="objDados.ds_media_gastos">
                            <label for="ds_media_gastos">Media gastos</label>
                        </div>
                    </div>
                    <div class="row">
                        <div :class="isMobile ? 'col s12 mar-bottom-50' : 'col s12'">
                            <v-radio-button
                                v-model="objDados.sn_real"
                                :arr-obj-opcoes="arrOpcoesSnReal"
                                label="Movimento"
                            ></v-radio-button>
                        </div>
                    </div>

                    <input type="hidden" name="id" id="id">

                    <div class="fixed-action-btn" v-if="isMobile">
                        <div class="row">
                            <div class="input-field col s12">
                                <a class="btn-floating btn-large waves-effect waves-light red darken-3 mar-right-5" @click="voltarTelaListagem()"><i class="material-icons">close</i></a>
                                <a class="btn-floating btn-large waves-effect waves-light teal darken-2" @click="salvarMovimento()"><i class="material-icons">check</i></a>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="!isMobile">
                        <div class="input-field col s12">
                            <a class="btn waves-effect waves-light red darken-3 mar-right-5" @click="voltarTelaListagem()">Cancelar</a>
                            <a class="btn darken-1 white-text teal darken-2" @click="salvarMovimento()">Salvar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script src="<?php echo ROTA_SITE_PUBLIC; ?>js/fin_movimento/tela-incluir-alterar.js"></script>
