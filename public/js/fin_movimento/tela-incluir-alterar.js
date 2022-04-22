Vue.component('v-select', VueSelect.VueSelect)

Vue.component('tela-incluir-alterar',{
    mixins: [mixin],
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
        sn_carregando: false,
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
        getObjDadosPadrao() {
            return {
                cd_movimento: null,
                cd_tipo_movimento: '1',
                cd_tipo_pgto: null,
                cd_tipo_situacao_pgto: null,
                dt_compra: null,
                dt_vcto: null,
                dt_pgto: null,
                vl_original: '0,00',
                vl_pago: '0,00',
                vl_dif_pgto: '0,00',
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
        },

        getVlDifPgto() {
            let vl_original = this.objDados.vl_original ?? '0';
            vl_original = vl_original.replace(/\D/g,''); // remove todos os caracteres não númericos
            vl_original = parseFloat(vl_original);

            let vl_pago = this.objDados.vl_pago ?? '0';
            vl_pago = vl_pago.replace(/\D/g,''); // remove todos os caracteres não númericos
            vl_pago = parseFloat(vl_pago);

            let vl_dif_pgto = vl_original - vl_pago;
            let ds_valor_dif_pgto = vl_dif_pgto.toString();
            let nr_caracteres = ds_valor_dif_pgto.length;

            if (nr_caracteres > 2) {
                let ds_valor_dif_pgto_inteiro = parseInt(ds_valor_dif_pgto.substr(0, nr_caracteres - 2), 10);
                let ds_valor_dif_pgto_decimal = ds_valor_dif_pgto.substr(nr_caracteres - 2, 2);

                return ds_valor_dif_pgto_inteiro + ',' + ds_valor_dif_pgto_decimal;
            }

            if (nr_caracteres == 2) {
                return '0,' + ds_valor_dif_pgto;
            }

            if (nr_caracteres == 1) {
                return '0,0' + ds_valor_dif_pgto;
            }

            return '0,00';
        }
    },
    methods:{
        async carregarOpcoesGrupoII() {
            this.sn_carregando = true;
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
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoII = response.data.retorno;

                    this.sn_carregando = false;
                })
                .catch(error => {
                    console.error(error);;
                });
        },

        async carregarOpcoesGrupoIII() {
            this.sn_carregando = true;
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
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoIII = response.data.retorno;

                    this.sn_carregando = false;
                })
                .catch(error => {
                console.error(error);;
                });
        },

        getDadosPostPreparados() {
            let objDados = {...this.objDados};
            objDados.vl_dif_pgto = this.getVlDifPgto;
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

        async salvarMovimento(sn_adicionar_outro = false) {
            let sn_prosseguir = confirm(
                'Deseja realmente prosseguir?'
            );

            if (!sn_prosseguir) return;

            this.sn_carregando = true;
            let objDados = this.getDadosPostPreparados();

            let objDadosPost = {
                'objDados': objDados
            }

            if (this.snAlterar) {
                await axios
                    .put(
                        ROTA_SITE_ACTIONS + 'fin_movimento/alterar.php?cd_movimento=' + this.objDados.cd_movimento,
                        objDadosPost
                    )
                    .then(response => {
                        if (!response.data.sucesso) {
                            alert(response.data.retorno);
                            return;
                        }

                        this.sn_carregando = false;

                        alert('Sucesso');

                        if (sn_adicionar_outro) {
                            this.recarregarTelaIncluirAlterar();
                            return;
                        }

                        this.voltarTelaListagem();
                    })
                    .catch(error => {
                        console.error(error);
                    });

                return;
            }

            await axios
                .post(
                    ROTA_SITE_ACTIONS + 'fin_movimento/adicionar.php',
                    objDadosPost
                )
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.sn_carregando = false;

                    alert('Sucesso');

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

        async popularDadosAlterar() {
            this.sn_preenchendo_dados_alterar = true;

            this.objDados = {... this.objMovimentoSelecionado};

            this.objDados.vl_original = this.mixinMonetarioFormatado(this.objDados.vl_original);
            this.objDados.vl_pago = this.mixinMonetarioFormatado(this.objDados.vl_pago);
            this.objDados.vl_dif_pgto = this.mixinMonetarioFormatado(this.objDados.vl_dif_pgto);

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

        formataMonetario(ds_campo) {
            let ds_valor = this.objDados[ds_campo] ?? '0';
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
    },

    watch: {
    },

    template:"#tela-incluir-alterar",
});

