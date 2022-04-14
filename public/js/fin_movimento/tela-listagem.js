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
        sn_uma_linha_selecionada: false,
        sn_linhas_selecinadas: false,
        sn_grid_completa: false,
        gridOptions: null,
        objMovimentoSelecionado: null
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
                    headerCheckboxSelection: true,
                    checkboxSelection: true,
                    width: this.getLarguraPercJanela(9.10),
                    filter: 'agNumberColumnFilter'
                },
                { headerName: "Tipo", field: "ds_tipo_movimento", width: this.getLarguraPercJanela(7.8) },
                { headerName: "Modo Pgto", field: "ds_tipo_pgto", width: this.getLarguraPercJanela(7.8) },
                { headerName: "Situação", field: "ds_tipo_situacao_pgto", width: this.getLarguraPercJanela(7.8) },
                {
                    headerName: "Dt Compra",
                    field: "dt_compra",
                    width: this.getLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams
                },
                {
                    headerName: "Dt Vcto",
                    field: "dt_vcto",
                    width: this.getLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams,
                    cellStyle: {'font-weight': 'bold', 'background-color': '#006156'}
                },
                {
                    headerName: "Dt Pgto",
                    field: "dt_pgto",
                    width: this.getLarguraPercJanela(7.8) ,
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams
                },
                {
                    headerName: "Vl Original",
                    field: "vl_original",
                    width: this.getLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.monetarioFormatado
                },
                {
                    headerName: "Vl Pago",
                    field: "vl_pago",
                    width: this.getLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.monetarioFormatado
                },
                {
                    headerName: "Dif Pgto",
                    field: "vl_dif_pgto",
                    width: this.getLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.monetarioFormatado
                },
                { headerName: "Parcela atual", field: "nr_parcela_atual", width: this.getLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Qtd Parcelas", field: "nr_qtd_parcelas", width: this.getLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Grupo 1", field: "ds_tipo_grupo_i", width: this.getLarguraPercJanela(12.4) },
                { headerName: "Grupo 2", field: "ds_tipo_grupo_ii", width: this.getLarguraPercJanela(12.4) },
                { headerName: "Grupo 3", field: "ds_tipo_grupo_iii", width: this.getLarguraPercJanela(12.4) },
                { headerName: "Descrição Pessoal", field: "ds_movimento", width: this.getLarguraPercJanela(20) },
                { headerName: "Obs 1", field: "ds_obs_i", width: this.getLarguraPercJanela(26) },
                { headerName: "Obs 2", field: "ds_obs_ii", width: this.getLarguraPercJanela(26) },
                { headerName: "Media Gastos", field: "ds_media_gastos", width: this.getLarguraPercJanela(12.4) },
                {
                    headerName: "Real ou Adm",
                    field: "sn_real",
                    width: this.getLarguraPercJanela(7.8),
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

        removerMovimento() {

            this.objMovimentoSelecionado = this.gridOptions.api.getSelectedNodes()[0].data;

            if (!this.objMovimentoSelecionado) {
                alert('Erro na seleção da linha para exclusão!');
                return;
            }

            let sn_excluir = confirm(
                'Deseja relamente excluir o movimento abaixo?\n'
                + '\n Tipo: ' + this.objMovimentoSelecionado.tipo_movimento
                + '\n Vcto: ' + this.objMovimentoSelecionado.dt_vcto
                + '\n Valor: ' + this.objMovimentoSelecionado.vl_original
                + '\n Grupo: '+ this.objMovimentoSelecionado.grupo_um
                + ' - '+ this.objMovimentoSelecionado.grupo_dois
                + ' - '+ this.objMovimentoSelecionado.grupo_tres
                + '\n Descrição: ' + this.objMovimentoSelecionado.descricao_pessoal
            );

            if (!sn_excluir) {
                return;
            }

            // chamar api exclusão
        },

        alteraExibicaoColunasGrid() {
            this.sn_grid_completa = !this.sn_grid_completa;

            this.exibicaoColunasGrid();
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

        this.atualizarMaterialize();
    }
})


