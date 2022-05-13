new Vue({
    mixins: [mixinGerais, mixinAlert],
    el: '#tela-listagem',
    data: {
        isMobile: false,
        sn_exibir_filtro: false,
        sn_exibir_totalizadores: true,
        sn_exibir_logout: false,
        arrTipoMovimento: [],
        arrTipoPgto: [],
        arrTipoSituacaoPgto: [],
        arrTipoGrupoI: [],
        arrTipoGrupoII: [],
        arrTipoGrupoIII: [],
        arrMovimentos: null,
        objTotalizadores: null,
        sn_tela_listagem: true,
        sn_alterar: false,
        nr_linhas_selecionadas: 0,
        sn_exibicao_grid: false,
        sn_collapses_abertas: false,
        sn_grid_completa: false,
        gridOptions: null,
        objMovimentoSelecionado: null,
        objFiltros: null,
        modelConfigDatePicker: {
            type: 'string',
            mask: 'DD/MM/YYYY',
        },
        objDatePickerExibir: {
            dt_inicio: false,
            dt_fim: false
        },
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
        },
        snPossuiRegistros() {
            return this.arrMovimentos?.length > 0 ? true : false;
        },
        getObjFiltrosPadrao() {
            return {
                cd_tipo_movimento: -1,
                cd_tipo_pgto: -1,
                cd_tipo_situacao_pgto: -1,
                dt_inicio: null,
                dt_fim: null,
                ds_movimento: null,
                sn_somente_adicionados_hoje: false,
                objTipoGrupoI: null,
                objTipoGrupoII: null,
                objTipoGrupoIII: null
            }
        },
        getOpcoesFiltroTipoMovimento() {
            let arrRetorno = Array.from(this.arrTipoMovimento);
            arrRetorno.push({
                cd_opcao: -1,
                ds_opcao: 'Todos'
            });
            return arrRetorno;
        },
        getOpcoesFiltroSituacao() {
            let arrRetorno = Array.from(this.arrTipoSituacaoPgto);
            arrRetorno.push({
                cd_opcao: -1,
                ds_opcao: 'Todos'
            });
            return arrRetorno;
        },
        getOpcoesFiltroTipoPgto() {
            let arrRetorno = Array.from(this.arrTipoPgto);
            arrRetorno.push({
                cd_opcao: -1,
                ds_opcao: 'Todos'
            });
            return arrRetorno;
        },
    },
    methods: {
        async carregarOpcoesSelects() {
            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-movimento.php')
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoMovimento = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-pgto.php')
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoPgto = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-situacao-pgto.php')
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoSituacaoPgto = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-um.php')
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrTipoGrupoI = response.data.retorno;
                })
                .catch(error => {
                  console.error(error);;
                });
        },
        async carregarOpcoesGrupoII() {
            this.arrTipoGrupoII = [];
            this.arrTipoGrupoIII = [];

            await axios
                .get(
                    ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-dois.php',
                    {
                        params: {
                            cd_tipo_movimento: this.objFiltros.cd_tipo_movimento
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

            if (!this.objFiltros.objTipoGrupoII?.cd_opcao) return;

            await axios
                .get(
                    ROTA_SITE_ACTIONS + 'fin_movimento/listar-tipo-grupo-tres.php',
                    {
                        params: {
                            cd_tipo_grupo_ii: this.objFiltros.objTipoGrupoII?.cd_opcao
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

        formatarDataPadraoBanco(ds_data) {
            if (!ds_data) return null;

            let arrDateParts = ds_data.split('/');
            return arrDateParts[2] + '-' + arrDateParts[1] + '-' + arrDateParts[0];
        },

        async listarMovimento() {
            this.mixinAlertCarregando(true);

            let objFiltros = {...this.objFiltros};
            objFiltros.dt_inicio = this.formatarDataPadraoBanco(objFiltros.dt_inicio);
            objFiltros.dt_fim = this.formatarDataPadraoBanco(objFiltros.dt_fim);
            objFiltros.cd_tipo_grupo_i = objFiltros.objTipoGrupoI?.cd_opcao;
            objFiltros.cd_tipo_grupo_ii = objFiltros.objTipoGrupoII?.cd_opcao;
            objFiltros.cd_tipo_grupo_iii = objFiltros.objTipoGrupoIII?.cd_opcao;

            delete objFiltros.objTipoGrupoI;
            delete objFiltros.objTipoGrupoII;
            delete objFiltros.objTipoGrupoIII;

            await axios
                .get(ROTA_SITE_ACTIONS + 'fin_movimento/listar.php', {
                    params: {
                        objFiltros: objFiltros
                    }
                })
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    this.arrMovimentos = response.data.retorno.arrMovimentos;
                    this.objTotalizadores = response.data.retorno.objTotalizadores;

                    this.mixinAlertCarregando(false);
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
                    width: this.mixinGetLarguraPercJanela(5),
                    filter: 'agNumberColumnFilter'
                },
                { headerName: "Tipo", field: "ds_tipo_movimento", width: this.mixinGetLarguraPercJanela(6) },
                { headerName: "Modo Pgto", field: "ds_tipo_pgto", width: this.mixinGetLarguraPercJanela(6) },
                { headerName: "Situação", field: "ds_tipo_situacao_pgto", width: this.mixinGetLarguraPercJanela(8) },
                {
                    headerName: "Dt Compra",
                    field: "dt_compra",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams
                },
                {
                    headerName: "Dt Vcto",
                    headerClass: 'ag-grid-header-special',
                    field: "dt_vcto",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agDateColumnFilter',
                    filterParams: filterParams,
                    cellStyle: (params) => {
                        if (params.data.cd_tipo_movimento == '1') {
                            return {backgroundColor: '#be333333'};
                        }

                        if (params.data.cd_tipo_movimento == '2') {
                            return {backgroundColor: '#28554d33'};
                        }

                        return null;
                    }
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
                    headerClass: 'ag-grid-header-special',
                    field: "vl_original",
                    width: this.mixinGetLarguraPercJanela(7.8),
                    filter: 'agNumberColumnFilter',
                    valueFormatter: this.mixinMonetarioFormatadoAgGrid,
                    type: 'rightAligned',
                    cellStyle: (params) => {
                        if (params.data.cd_tipo_movimento == '1') {
                            return {backgroundColor: '#be333333'};
                        }

                        if (params.data.cd_tipo_movimento == '2') {
                            return {backgroundColor: '#28554d33'};
                        }

                        return null;
                    }
                },
                { headerName: "Parcela atual", field: "nr_parcela_atual", width: this.mixinGetLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Qtd parcelas", field: "nr_qtd_parcelas", width: this.mixinGetLarguraPercJanela(7.8), filter: 'agNumberColumnFilter' },
                { headerName: "Grupo 1", field: "ds_tipo_grupo_i", width: this.mixinGetLarguraPercJanela(9) },
                { headerName: "Grupo 2", field: "ds_tipo_grupo_ii", width: this.mixinGetLarguraPercJanela(13) },
                { headerName: "Grupo 3", field: "ds_tipo_grupo_iii", width: this.mixinGetLarguraPercJanela(13) },
                { headerName: "Descrição Pessoal", field: "ds_movimento", width: this.mixinGetLarguraPercJanela(26) },
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

        async editarMovimento() {
            this.sn_alterar=true;

            this.objMovimentoSelecionado = this.gridOptions.api.getSelectedNodes()[0].data;

            if (!this.objMovimentoSelecionado) {
                await this.mixinAlertErro('Erro na seleção da linha para edição!');
                return;
            }

            this.sn_tela_listagem = false;
        },

        async removerMovimento() {
            this.arrMovimentosSelecionados = this.gridOptions.api.getSelectedNodes();

            if (!this.arrMovimentosSelecionados) {
                await this.mixinAlertErro('Erro na seleção da linha para exclusão!');
                return;
            }

            let ds_msg = 'Deseja realmente excluir os movimentos abaixo?';
            let arrCdMovimento = []

            this.arrMovimentosSelecionados.forEach(objMovimentoSelecionado => {
                objMovimentoSelecionado = objMovimentoSelecionado.data;

                ds_msg = ds_msg
                    + '</br></br><b> Código: ' + objMovimentoSelecionado.cd_movimento + '</b>'
                    + '</br> Tipo: ' + objMovimentoSelecionado.ds_tipo_movimento
                    + '</br> Vcto: ' + objMovimentoSelecionado.dt_vcto
                    + '</br> Valor: ' + objMovimentoSelecionado.vl_original
                    + '</br> Grupo: '+ objMovimentoSelecionado.ds_tipo_grupo_i
                    + ' - '+ objMovimentoSelecionado.ds_tipo_grupo_ii
                    + ' - '+ objMovimentoSelecionado.ds_tipo_grupo_iii
                    + '</br> Descrição: ' + objMovimentoSelecionado.ds_movimento;

                arrCdMovimento.push(objMovimentoSelecionado.cd_movimento);
            });

            let sn_prosseguir = await this.mixinAlertProsseguir(ds_msg);
            if (!sn_prosseguir) return;

            this.mixinAlertCarregando(true);

            await axios
                .delete(ROTA_SITE_ACTIONS + 'fin_movimento/remover.php', {
                    params: {
                        arrCdMovimento: arrCdMovimento
                    }
                })
                .then(async (response) => {
                    if (!response.data.sucesso) {
                        await this.mixinAlertErro(response.data.retorno);
                        return;
                    }

                    await this.mixinAlertSucesso();
                    this.filtrarGrid();
                })
                .catch(error => {
                  console.error(error);;
                });
        },

        alteraExibicaoGridCard() {
            this.sn_exibicao_grid = !this.sn_exibicao_grid;

            this.sn_exibir_totalizadores = !this.sn_exibicao_grid;
        },

        expandirRecolherCollapses() {
            var instance = M.Collapsible.getInstance($('.collapsible'));

            instance.open()
            if (this.sn_collapses_abertas) {
                instance.close();
            }

            this.sn_collapses_abertas = !this.sn_collapses_abertas;
        },

        clicouCollapse(cd_movimento) {
            this.gridOptions.api.forEachNode(node => {
                if (node.data.cd_movimento == cd_movimento) {
                    if ($('#checkbox-cd-movimento-' + node.data.cd_movimento)[0].innerHTML == "check_box") {
                        node.setSelected(false);
                        $('#checkbox-cd-movimento-' + node.data.cd_movimento)[0].innerHTML="check_box_outline_blank";
                        return;
                    }

                    node.setSelected(true);
                    $('#checkbox-cd-movimento-' + node.data.cd_movimento)[0].innerHTML="check_box";
                }
            });

            this.sn_collapses_abertas = !$('.collapsible > li.active').length > 0;
        },

        alteraExibicaoColunasGrid() {
            this.sn_grid_completa = !this.sn_grid_completa;

            this.exibicaoColunasGrid();
        },

        selecionarTudo() {
            this.gridOptions.api.forEachNode(node => {
                node.setSelected(true);
                $('#checkbox-cd-movimento-' + node.data.cd_movimento)[0].innerHTML="check_box";
            });
        },

        limparSelecao() {
            this.gridOptions.api.forEachNode(node => {
                node.setSelected(false);
                $('#checkbox-cd-movimento-' + node.data.cd_movimento)[0].innerHTML="check_box_outline_blank";
            });
        },

        exibicaoColunasGrid() {
            this.gridOptions.columnApi.setColumnsVisible(
                [
                    'ds_tipo_movimento',
                    'dt_compra',
                    'dt_pgto',
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

            this.sn_exibir_filtro = false;

            this.nr_linhas_selecionadas = 0;

            this.inicializarGrid();
        },

        async voltouTelaListagem() {
            this.sn_tela_listagem = true;

            await this.filtrarGrid();
        },

        resetarObjFiltros() {
            this.objFiltros = {...this.getObjFiltrosPadrao};
        },

        alterarFiltroData(cd_tipo_data) {
            if (cd_tipo_data < 1) return;

            let data = new Date();
            let ano = data.getFullYear();
            let mes = data.getMonth() + 1;

            switch (cd_tipo_data) {
                case 1:
                    if (mes == 1) {
                        mes = 12;
                        ano = ano - 1;
                    } else {
                        mes = mes - 1;
                    }
                    break;
                case 2:
                    break;
                case 3:
                    if (mes == 12) {
                        mes = 1;
                        ano = ano + 1;
                    } else {
                        mes = mes + 1;
                    }
                    break;
            }

            let ultimo_dia = new Date(ano, mes, 0).getDate();

            mes = mes.toString();
            mes = mes.length == 1 ? '0' + mes : mes;

            this.objFiltros.dt_inicio = '01/' + mes + '/' + ano;
            this.objFiltros.dt_fim = ultimo_dia + '/' + mes + '/' + ano;
            this.mixinAtualizarMaterialize();
        },
    },

    created() {
        this.resetarObjFiltros();
    },

    async mounted () {
        this.alterarFiltroData(2);

        await this.carregarOpcoesSelects();

        await this.carregarOpcoesGrupoII();

        await this.carregarOpcoesGrupoIII();

        await this.filtrarGrid();

        this.mixinAtualizarMaterialize();

        window.addEventListener("resize", this.mixinCheckIsMobile);

        this.mixinCheckIsMobile();
    }
})



