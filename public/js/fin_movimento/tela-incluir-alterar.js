Vue.component('v-select', VueSelect.VueSelect)

Vue.component('tela-incluir-alterar',{
    mixins: [mixinGerais, mixinAlert],
    name:"TelaIncluirAlterar",
    props:{
        snAlterar:{
            type: Boolean,
            default: false
        },
        objMovimentoSelecionado:{
            type: Object,
            default: null
        },
        arrTipoMovimento: {
            type: Array,
            default: []
        },
        arrTipoPgto: {
            type: Array,
            default: []
        },
        arrTipoSituacaoPgto: {
            type: Array,
            default: []
        },
        arrTipoGrupoI: {
            type: Array,
            default: []
        }
    },
    data:()=>({
        sn_auto_preenchimento: false,
        sn_preenchendo_dados_alterar: false,
        objDados: null,
        arrTipoGrupoII: [],
        arrTipoGrupoIII: [],
        objDatePickerExibir: {
            dt_compra: false,
            dt_vcto: false,
            dt_pgto: false,
        },
        modelConfigDatePicker: {
            type: 'string',
            mask: 'DD/MM/YYYY', // Uses 'iso' if missing
        },
        isMobile: false,
        arrOpcoesSnReal: []
    }),

    computed: {
        getDataAtualFormatada() {
            let dt_atual = new Date();
            let dia = dt_atual.getDate();
            dia = dia.toString().length == 1 ? '0' + dia : dia;
            let mes = dt_atual.getMonth() + 1;
            mes = mes.toString().length == 1 ? '0' + mes : mes;
            let ano = dt_atual.getFullYear();

            return dia + '/' + mes + '/' + ano;
        },
        getObjDadosPadrao() {
            return {
                cd_movimento: null,
                cd_tipo_movimento: '1',
                cd_tipo_pgto: -1,
                cd_tipo_situacao_pgto: null,
                dt_compra: this.objDados?.dt_compra ?? this.getDataAtualFormatada,
                dt_vcto: null,
                dt_pgto: null,
                vl_original: '0,00',
                nr_parcela_atual: '1',
                nr_qtd_parcelas: '1',
                objTipoGrupoI: null,
                objTipoGrupoII: null,
                objTipoGrupoIII: null,
                ds_movimento: null,
                ds_obs_i: null,
                ds_obs_ii: null,
                ds_media_gastos: null,
                sn_real: '1'
            }
        }
    },
    methods:{
        async carregarOpcoesGrupoII() {
            this.arrTipoGrupoII = [];
            this.arrTipoGrupoIII = [];
            if (!this.objDados.cd_tipo_movimento) return;

            await axios
                .get(
                    ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-dois.php',
                    {
                        params: {
                            cd_tipo_movimento: this.objDados.cd_tipo_movimento
                        }
                    }
                )
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoII = response.data.retorno;
                })
                .catch(error => {
                    console.error(error);;
                });
        },

        async carregarOpcoesGrupoIII() {
            this.arrTipoGrupoIII = [];
            if (!this.objDados.objTipoGrupoII?.cd_opcao) return;

            await axios
                .get(
                    ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-tres.php',
                    {
                        params: {
                            cd_tipo_grupo_ii: this.objDados.objTipoGrupoII?.cd_opcao
                        }
                    }
                )
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoIII = response.data.retorno;
                })
                .catch(error => {
                    console.error(error);;
                });
        },

        getDadosPostPreparados() {
            let objDados = {...this.objDados};
            objDados.cd_tipo_grupo_i = objDados.objTipoGrupoI?.cd_opcao;
            objDados.cd_tipo_grupo_ii = objDados.objTipoGrupoII?.cd_opcao;
            objDados.cd_tipo_grupo_iii = objDados.objTipoGrupoIII?.cd_opcao;

            delete objDados.objTipoGrupoI;
            delete objDados.objTipoGrupoII;
            delete objDados.objTipoGrupoIII;
            delete objDados.ds_tipo_movimento;
            delete objDados.ds_tipo_pgto;
            delete objDados.ds_tipo_situacao_pgto;
            delete objDados.ds_tipo_grupo_i;
            delete objDados.ds_tipo_grupo_ii;
            delete objDados.ds_tipo_grupo_iii;

            return objDados;
        },

        getValorParcela(vl_total, nr_qtd_parcelas) {
            let vl_parcela, vl_ultima_parcela;
            vl_total = vl_total.replace(',', '.');
            vl_total = parseFloat(vl_total);
            vl_parcela = vl_total / nr_qtd_parcelas;
            vl_parcela = vl_parcela.toFixed(2);
            vl_ultima_parcela = vl_total - (vl_parcela * (nr_qtd_parcelas - 1));
            vl_ultima_parcela = vl_ultima_parcela.toFixed(2);

            return [vl_parcela, vl_ultima_parcela];
        },

        gerarParcelasPost(objDados) {
            let arrParcelas = [];
            let arrValor = [];

            arrValor = this.getValorParcela(objDados.vl_original, objDados.nr_qtd_parcelas);
            let vl_original_parcela = arrValor[0], vl_original_ultima_parcela = arrValor[1];

            for (let nr_parcela = 1; nr_parcela <= objDados.nr_qtd_parcelas; nr_parcela++) {
                let objTemp = {...objDados};

                objTemp.nr_parcela_atual = nr_parcela;
                objTemp.vl_original = nr_parcela < objDados.nr_qtd_parcelas ? vl_original_parcela : vl_original_ultima_parcela;

                arrParcelas.push(objTemp);
            }

            return arrParcelas;
        },

        async salvarMovimento(sn_adicionar_outro = false) {
            let sn_prosseguir = await this.mixinAlertProsseguir();
            if (!sn_prosseguir) return;

            if (this.snAlterar) {
                this.alterarMovimento();
                return;
            }

            this.incluirMovimento(sn_adicionar_outro);
        },

        async incluirMovimento(sn_adicionar_outro = false) {
            let sn_gerar_parcelas = false;

            if (this.objDados.nr_qtd_parcelas > 1) {
                let vl_original = this.objDados.vl_original.replace(',', '.');
                vl_original = parseFloat(vl_original);
                let vl_parcela = vl_original / this.objDados.nr_qtd_parcelas;
                vl_parcela = vl_parcela.toFixed(2);
                vl_parcela = vl_parcela.toString().replace('.', ',');

                let ds_msg = 'Deseja gerar ' + this.objDados.nr_qtd_parcelas + ' parcelas, com valor original de R$ ' + vl_parcela + ' cada'
                    + '</br> OU, gerar somente 1 parcela, com valor original de R$ ' + this.objDados.vl_original;

                let ds_botao_1 = 'Gerar ' + this.objDados.nr_qtd_parcelas + ' parcelas';
                let ds_botao_2 = 'Gerar somente 1 parcela';

                sn_gerar_parcelas = await this.mixinAlertProsseguir(
                    ds_msg,
                    true,
                    ds_botao_2,
                    true,
                    ds_botao_1,
                    false
                );
            }

            this.mixinAlertCarregando(true);
            let objDados = this.getDadosPostPreparados();
            let arrParcelas = sn_gerar_parcelas ? this.gerarParcelasPost(objDados) : [objDados];

            let objDadosPost = {
                'objDados': arrParcelas
            }

            await axios
                .post(
                    ROTA_SITE_ACTIONS + 'fin_movimento/adicionar.php',
                    objDadosPost
                )
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    await this.mixinAlertSucesso();

                    if (sn_adicionar_outro) {
                        this.recarregarTelaIncluirAlterar();
                        return;
                    }

                    this.voltarTelaListagem();
                })
                .catch(error => {
                    console.error(error);
                });
        },

        async alterarMovimento() {
            this.mixinAlertCarregando(true);
            let objDados = this.getDadosPostPreparados();

            let objDadosPost = {
                'objDados': objDados
            }

            await axios
                .post(
                    ROTA_SITE_ACTIONS + 'fin_movimento/alterar.php?cd_movimento=' + this.objDados.cd_movimento,
                    objDadosPost
                )
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    await this.mixinAlertSucesso();
                    this.voltarTelaListagem();
                })
                .catch(error => {
                    console.error(error);
                });
        },

        async popularDadosAlterar() {
            this.sn_preenchendo_dados_alterar = true;

            this.objDados = {... this.objMovimentoSelecionado};

            this.objDados.vl_original = this.mixinMonetarioFormatado(this.objDados.vl_original);

            this.arrTipoGrupoI.forEach(objOpcao => {
                if (objOpcao.cd_opcao == this.objDados.cd_tipo_grupo_i){
                    this.objDados.objTipoGrupoI = objOpcao;
                }
            });

            await this.carregarOpcoesGrupoII();

            this.arrTipoGrupoII.forEach(objOpcao => {
                if (objOpcao.cd_opcao == this.objDados.cd_tipo_grupo_ii){
                    this.objDados.objTipoGrupoII = objOpcao;
                }
            });

            await this.carregarOpcoesGrupoIII();

            this.arrTipoGrupoIII.forEach(objOpcao => {
                if (objOpcao.cd_opcao == this.objDados.cd_tipo_grupo_iii){
                    this.objDados.objTipoGrupoIII = objOpcao;
                }
            });

            delete this.objDados.cd_tipo_grupo_i;
            delete this.objDados.cd_tipo_grupo_ii;
            delete this.objDados.cd_tipo_grupo_iii;

            this.sn_preenchendo_dados_alterar = false;
        },

        voltarTelaListagem() {
            this.resetarObjDados();
            this.$emit('voltar');
        },

        recarregarTelaIncluirAlterar() {
            this.resetarObjDados();
            document.documentElement.scrollTop = 0
        },

        executaFormulaValor(ds_campo) {
            let ds_valor = this.objDados[ds_campo] ?? '0';
            ds_valor = ds_valor.toString();

            if (ds_valor.search('=') < 0) return;

            ds_valor = ds_valor.slice(ds_valor.search('=') + 1);
            ds_valor = ds_valor.replaceAll(',', '.');
            ds_valor = ds_valor.replace(/[^-()\d/*+.]/g, ''); // remove caracteres não matematicos
            ds_valor = ds_valor.replace(/^0+(?=\d)/, ''); // remove zeros no inicio da string
            ds_valor = eval(ds_valor);
            this.objDados[ds_campo] = ds_valor ? ds_valor.toFixed(2) : 0;

            this.formataMonetario(ds_campo);
        },

        formataMonetario(ds_campo) {
            let ds_valor = this.objDados[ds_campo] ?? '0';
            ds_valor = ds_valor.toString();

            if (ds_valor.search('=') >= 0) return;

            ds_valor = ds_valor.replace(/\D/g,''); // remove todos os caracteres não númericos
            let nr_caracteres = ds_valor.length;

            if (nr_caracteres > 2) {
                let ds_valor_inteiro = parseInt(ds_valor.substr(0, nr_caracteres - 2), 10);
                let ds_valor_decimal = ds_valor.substr(nr_caracteres - 2, 2);

                this.objDados[ds_campo] = ds_valor_inteiro + ',' + ds_valor_decimal;
                return;
            }

            if (nr_caracteres == 2) {
                this.objDados[ds_campo] = '0,' + ds_valor;
                return;
            }

            if (nr_caracteres == 1) {
                this.objDados[ds_campo] = '0,0' + ds_valor;
                return;
            }

            this.objDados[ds_campo] = '0,00'
        },

        resetarObjDados() {
            this.objDados = {...this.getObjDadosPadrao};
        },

        carregarOpcoesRadio() {
            this.arrOpcoesSnReal = [
                {
                    ds_opcao: 'Real',
                    cd_opcao:1
                },
                {
                    ds_opcao: 'Administrativo',
                    cd_opcao:0
                }
            ];
        },

        autoPreenchimento() {
            if (!this.sn_auto_preenchimento) return;

            switch(this.objDados.cd_tipo_pgto) {
                case '1':
                    // Caso de pagamento com dinheiro
                    this.objDados.cd_tipo_situacao_pgto = 1; // Pago
                    this.objDados.dt_vcto = this.objDados.dt_compra;
                    this.objDados.dt_pgto = this.objDados.dt_compra;
                    break;
                case '2':
                    // Caso de pagamento com cartão de crédito
                    this.objDados.cd_tipo_situacao_pgto = 2; // Pendente
                    this.objDados.dt_pgto = null;
                    if (this.objDados.dt_compra) {
                        let arrDateParts = this.objDados.dt_compra.split('/');
                        let dt_vcto = new Date(
                            Number(arrDateParts[2]),
                            Number(arrDateParts[1]) - 1,
                            Number(arrDateParts[0])
                        );

                        let nr_dia_compra = dt_vcto.getDate();

                        if (nr_dia_compra >= 1 && nr_dia_compra <= 7) {
                            dt_vcto.setDate(15);
                        }

                        if (nr_dia_compra >= 8 && nr_dia_compra <= 10) {
                            this.objDados.dt_vcto = null;
                            return;
                        }

                        if (nr_dia_compra >= 11) {
                            dt_vcto.setDate(dt_vcto.getDate() + 30);
                            dt_vcto.setDate(15);
                        }

                        let dia_vcto = dt_vcto.getDate();
                        dia_vcto = dia_vcto.toString().length == 1 ? '0' + dia_vcto : dia_vcto;
                        let mes_vcto = dt_vcto.getMonth() + 1;
                        mes_vcto = mes_vcto.toString().length == 1 ? '0' + mes_vcto : mes_vcto;
                        let ano_vcto = dt_vcto.getFullYear();

                        this.objDados.dt_vcto = dia_vcto + '/' + mes_vcto + '/' + ano_vcto;
                    }
                    break;
            }

            this.mixinAtualizarMaterialize();
        },
    },

    created() {
        this.resetarObjDados();
    },

    async mounted() {
        this.carregarOpcoesRadio();

        if (this.snAlterar) {
            this.popularDadosAlterar();
        }

        window.addEventListener("resize", this.mixinCheckIsMobile);

        this.mixinCheckIsMobile();

        this.mixinAtualizarMaterialize();

        this.sn_auto_preenchimento = !this.snAlterar;
    },

    watch: {
        objDados: {
          deep: true,
          handler: function handler() {
            this.autoPreenchimento();
          }
        }
    },

    template:"#tela-incluir-alterar",
});

