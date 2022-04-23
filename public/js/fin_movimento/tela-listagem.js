new Vue({
    mixins: [mixin],
    el: '#tela-listagem',
    data: {
        sn_carregando: false,
        arrTipoMovimento: [],
        arrTipoPgto: [],
        arrTipoSituacaoPgto: [],
        arrTipoGrupoI: [],
        arrMovimentos: null,
        sn_tela_listagem: true,
        sn_alterar: false,
        nr_linhas_selecionadas: 0,
        sn_grid_completa: false,
        gridOptions: null,
        objMovimentoSelecionado: null
    },

    computed: {
        snPodeSelecionarTudo() {
            if (!this.gridOptions) return false;

            let nr_linhas = this.gridOptions.api.getDisplayedRowCount();
            return nr_linhas != this.nr_linhas_selecionadas;
        },
        snPodeLimparSelecao() {
            return this.snLinhasSelecionadas;
        },
        snUmaLinhaSelecionada() {
            return this.nr_linhas_selecionadas == 1;
        },
        snLinhasSelecionadas() {
            return this.nr_linhas_selecionadas >= 1;
        }
    },
    methods: {
        async carregarOpcoesSelects() {
            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-movimento.php')
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoMovimento = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-pgto.php')
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoPgto = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-situacao-pgto.php')
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoSituacaoPgto = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-um.php')
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoI = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });
        },

        async listarMovimento() {
            this.sn_carregando = true;

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar.php', {
                    // params: {
                    //     ID: 12345
                    // }
                })
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    this.arrMovimentos = response.data.retorno;

                    this.sn_carregando = false;
                })
                .catch(error => {
                  console.error(error);;
                });
        },

        inicializarGrid() {
            var filterParams = {
                comparator: function (filterLocalDateAtMidnight, cellValue) {
                    var dateAsString = cellValue;
                    if (dateAsString == null) return -1;
                    var dateParts = dateAsString.split('-');
                    var cellDate = new Date(
                    Number(dateParts[0]),
                    Number(dateParts[1]) - 1,
                    Number(dateParts[2])
                    );

                    if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
                    return 0;
                    }

                    if (cellDate < filterLocalDateAtMidnight) {
                    return -1;
                    }

                    if (cellDate > filterLocalDateAtMidnight) {
                    return 1;
                    }
                },
                browserDatePicker: true,
            };

            const columnDefs = [
                {
                    headerName: "Cód.",
                    field: "cd_movimento",
                    width: this.mixinGetLarguraPercJanela(9.10),
                    filter: 'agNumberColumnFilter'
                },
                { headerName: "Tipo", field: "ds_tipo_movimento", width: this.mixinGetLarguraPercJanela(7.8) },
                { headerName: "Modo Pgto", field: "ds_tipo_pgto", width: this.mixinGetLarguraPercJanela(7.8) },
                { headerName: "Situação", field: "ds_tipo_situacao_pgto", width: this.mixinGetLarguraPercJanela(7.8) },
                {
                    headerName: "Dt Compra",
                    field: "dt_compra",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams
                },
                {
                    headerName: "Dt Vcto",
                    field: "dt_vcto",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams,
                    cellStyle: {'font-weight': 'bold', 'background-color': '#006156'}
                },
                {
                    headerName: "Dt Pgto",
                    field: "dt_pgto",
                    width: this.mixinGetLarguraPercJanela(7.8) ,
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams
                },
                {
                    headerName: "Vl Original",
                    field: "vl_original",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.mixinMonetarioFormatadoAgGrid
                },
                {
                    headerName: "Vl Pago",
                    field: "vl_pago",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.mixinMonetarioFormatadoAgGrid
                },
                {
                    headerName: "Dif Pgto",
                    field: "vl_dif_pgto",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.mixinMonetarioFormatadoAgGrid
                },
                { headerName: "Parcela atual", field: "nr_parcela_atual", width: this.mixinGetLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Qtd parcelas", field: "nr_qtd_parcelas", width: this.mixinGetLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Grupo 1", field: "ds_tipo_grupo_i", width: this.mixinGetLarguraPercJanela(12.4) },
                { headerName: "Grupo 2", field: "ds_tipo_grupo_ii", width: this.mixinGetLarguraPercJanela(12.4) },
                { headerName: "Grupo 3", field: "ds_tipo_grupo_iii", width: this.mixinGetLarguraPercJanela(12.4) },
                { headerName: "Descrição Pessoal", field: "ds_movimento", width: this.mixinGetLarguraPercJanela(20) },
                { headerName: "Obs 1", field: "ds_obs_i", width: this.mixinGetLarguraPercJanela(26) },
                { headerName: "Obs 2", field: "ds_obs_ii", width: this.mixinGetLarguraPercJanela(26) },
                { headerName: "Média gastos", field: "ds_media_gastos", width: this.mixinGetLarguraPercJanela(12.4) },
                {
                    headerName: "Real ou Adm",
                    field: "sn_real",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    valueFormatter: (params) => {
                        return params.value == 1 ? 'REAL' : 'ADMINISTRATIVO';
                    }
                }
            ];

            this.gridOptions = {
                columnDefs: columnDefs,
                rowData: this.arrMovimentos,
                defaultColDef: {
                    editable: (params) => {
                        return false;
                        // return params.column.colId != 'id';
                    },
                    singleClickEdit: true,
                    sortable: true,
                    filter: true,
                    resizable: true,
                },
                rowSelection: 'multiple',
                suppressRowDeselection: true,
                suppressRowClickSelection: false,
                rowMultiSelectWithClick: true,
                enableRangeSelection: true,
                animateRows: true,
                isRowSelectable: () => {
                    return true
                }
            };

            // lookup the container we want the Grid to use
            const eGridDiv = document.querySelector('#myGrid');

            // create the grid passing in the div to use together with the columns & data we want to use
            new agGrid.Grid(eGridDiv, this.gridOptions);

            // Mostra e Oculta colunas
            this.exibicaoColunasGrid();

            // Vincula um evento ao selecionar uma linha da grid
            this.gridOptions.api.addEventListener("rowSelected", this.linhaSelecionada);
        },

        linhaSelecionada() {
            this.nr_linhas_selecionadas = this.gridOptions.api.getSelectedNodes().length;
        },

        incluirMovimento() {
            this.sn_alterar=false;
            this.objMovimentoSelecionado = null;
            this.sn_tela_listagem = false;
        },

        editarMovimento() {
            this.sn_alterar=true;

            this.objMovimentoSelecionado = this.gridOptions.api.getSelectedNodes()[0].data;

            if (!this.objMovimentoSelecionado) {
                alert('Erro na seleção da linha para edição!');
                return;
            }

            this.sn_tela_listagem = false;
        },

        async removerMovimento() {
            this.objMovimentoSelecionado = this.gridOptions.api.getSelectedNodes()[0].data;

            if (!this.objMovimentoSelecionado) {
                alert('Erro na seleção da linha para exclusão!');
                return;
            }

            let sn_excluir = confirm(
                'Deseja realmente excluir o movimento abaixo?\n'
                + '\n Tipo: ' + this.objMovimentoSelecionado.ds_tipo_movimento
                + '\n Vcto: ' + this.objMovimentoSelecionado.dt_vcto
                + '\n Valor: ' + this.objMovimentoSelecionado.vl_original
                + '\n Grupo: '+ this.objMovimentoSelecionado.ds_tipo_grupo_i
                + ' - '+ this.objMovimentoSelecionado.ds_tipo_grupo_ii
                + ' - '+ this.objMovimentoSelecionado.ds_tipo_grupo_iii
                + '\n Descrição: ' + this.objMovimentoSelecionado.ds_movimento
            );

            if (!sn_excluir) return;

            await axios
                .delete(ROTA_SITE_ACTIONS + 'fin_movimento/remover.php', {
                    params: {
                        cd_movimento: this.objMovimentoSelecionado.cd_movimento
                    }
                })
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        return;
                    }

                    alert('Sucesso');
                    this.filtrarGrid();
                })
                .catch(error => {
                  console.error(error);;
                });
        },

        alteraExibicaoColunasGrid() {
            this.sn_grid_completa = !this.sn_grid_completa;

            this.exibicaoColunasGrid();
        },

        selecionarTudo() {
            this.gridOptions.api.forEachNode(node => node.setSelected(true));
        },

        limparSelecao() {
            this.gridOptions.api.forEachNode(node => node.setSelected(false));
        },

        exibicaoColunasGrid() {
            this.gridOptions.columnApi.setColumnsVisible(
                [
                    'ds_tipo_movimento',
                    'dt_compra',
                    'dt_pgto',
                    'vl_pago',
                    'vl_dif_pgto',
                    'nr_parcela_atual',
                    'nr_qtd_parcelas',
                    'ds_obs_i',
                    'ds_obs_ii',
                    'ds_media_gastos',
                    'sn_real'
                ],
                this.sn_grid_completa
            );
        },

        async filtrarGrid() {
            await this.listarMovimento();

            this.inicializarGrid();
        },

        async voltouTelaListagem() {
            this.sn_tela_listagem = true;

            await this.filtrarGrid();
        },
    },
    async mounted () {
        await this.carregarOpcoesSelects();

        await this.filtrarGrid();

        this.mixinAtualizarMaterialize();
    }
})



