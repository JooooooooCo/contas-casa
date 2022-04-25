var mixinAlert = {
    data: function () {
        return {
            mixinSnAlertCarregando: false,
        }
    },
    methods: {
        mixinAlertCarregando(sn_carregando) {
            this.mixinSnAlertCarregando = sn_carregando;

            if (sn_carregando) {
                Swal.fire({
                    imageUrl: ROTA_SITE_PUBLIC + 'images/loader.gif',
                    imageWidth: 140,
                    imageHeight: 145,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });

                return;
            }

            Swal.close();
        },

        async mixinAlertProsseguir(
            ds_texto_prosseguir = 'Deseja realmente prosseguir?',
            sn_botao_opcional = false,
            ds_texto_botao_opcional = 'Não',
            sn_botao_confirmar = true,
            ds_texto_botao_confirmar = 'OK',
            sn_botao_cancelar = true,
            ds_texto_botao_cancelar = 'Cancelar',
        ) {
            result = await Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                html: ds_texto_prosseguir,
                showConfirmButton: sn_botao_confirmar,
                confirmButtonText: ds_texto_botao_confirmar,
                showCancelButton: sn_botao_cancelar,
                cancelButtonText: ds_texto_botao_cancelar,
                showDenyButton: sn_botao_opcional,
                denyButtonText: ds_texto_botao_opcional,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });

            return result.value;
        },

        async mixinAlertErro(ds_texto_erro = 'Houve um erro durante a operação.') {
            this.mixinAlertCarregando(false);

            await Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: ds_texto_erro
            });
        },

        async mixinAlertSucesso(ds_texto_erro = 'Operação concluída com sucesso.') {
            this.mixinAlertCarregando(false);

            await Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                html: ds_texto_erro
            });
        },
    }
  }
