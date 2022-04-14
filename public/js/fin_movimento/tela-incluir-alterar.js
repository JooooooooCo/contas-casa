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
                vl_original: null,
                vl_pago: null,
                vl_dif_pgto: null,
                nr_parcela_atual: null,
                nr_qtd_parcelas: null,
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
    },
    methods:{
        async carregarOpcoesGrupoII() {
            this.sn_carregando = true;
            this.arrTipoGrupoII = [];
            this.arrTipoGrupoIII = [];
            if (!this.objDados.cd_tipo_movimento) return;

            await axios.get(
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

        prepararDadosPost() {
            this.objDados.cd_tipo_grupo_i = this.objDados.objTipoGrupoI?.cd_opcao;
            this.objDados.cd_tipo_grupo_ii = this.objDados.objTipoGrupoII?.cd_opcao;
            this.objDados.cd_tipo_grupo_iii = this.objDados.objTipoGrupoIII?.cd_opcao;

            delete this.objDados.objTipoGrupoI;
            delete this.objDados.objTipoGrupoII;
            delete this.objDados.objTipoGrupoIII;
            delete this.objDados.ds_tipo_movimento;
            delete this.objDados.ds_tipo_pgto;
            delete this.objDados.ds_tipo_situacao_pgto;
            delete this.objDados.ds_tipo_grupo_i;
            delete this.objDados.ds_tipo_grupo_ii;
            delete this.objDados.ds_tipo_grupo_iii;
        },

        async salvarMovimento() {
            this.sn_carregando = true;
            this.prepararDadosPost();

            let objDadosPost = {
                'objDados': this.objDados
            }

            await axios
                .post(
                    // ROTA_SITE_ACTIONS + this.snAlterar ? 'fin_movimento/alterar.php' : 'fin_movimento/adicionar.php', // alterar falta criar rota
                    ROTA_SITE_ACTIONS + 'fin_movimento/adicionar.php', // alterar falta criar rota
                    objDadosPost
                )
                .then(function (response) {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.sn_carregando = false;

                    alert('Sucesso');
                    console.log(response);
                })
                .catch(function (error) {
                    console.error(error);
                });

            this.voltarTelaListagem();
        },

        async popularDadosAlterar() {
            this.objDados = {... this.objMovimentoSelecionado};

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
        },

        voltarTelaListagem() {
            this.resetarObjDados();
            this.$emit('voltar');
        },

        formataMonetario(ds_campo) {
            let ds_valor = this.objDados[ds_campo];
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
            this.objDados = this.getObjDadosPadrao;
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

        window.addEventListener("resize", this.checkIsMobile);

        this.checkIsMobile();

        this.atualizarMaterialize();
    },

    watch: {
    },

    template:"#tela-incluir-alterar",
});

