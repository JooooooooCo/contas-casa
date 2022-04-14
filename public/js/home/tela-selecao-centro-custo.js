new Vue({
    el: '#tela-selecao-centro-custo',
    data: {
        sn_carregando: false,
        arrCentroCusto: [],
        cd_centro_custo: null
    },
    methods: {
        async carregarCentroCusto() {
            this.sn_carregando = true;
            this.arrCentroCusto = [];

            await axios
                .get(
                    ROTA_SITE_ACTIONS + 'home/listar-centro-custo.php'
                )
                .then(response => {
                    if (!response.data.sucesso) {
                        alert(response.data.retorno);
                        window.location.replace(ROTA_SITE_ROOT);
                        return;
                    }

                    if (response.data.retorno.length == 1) {
                        this.cd_centro_custo = response.data.retorno[0]['id'];
                        this.enviarForm();
                    }

                    this.arrCentroCusto = response.data.retorno;

                    this.sn_carregando = false;
                })
                .catch(error => {
                    console.error(error);;
                });
        },

        enviarForm() {
            console.log('enviarForm');
            $('#form-selecao-centro-custo').submit();
        }
    },
    async mounted () {
        this.carregarCentroCusto();
    }
})
