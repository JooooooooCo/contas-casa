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

        async mixinAlertProsseguir(ds_texto_prosseguir = 'Deseja realmente prosseguir?', sn_botao_opcional = false, ds_texto_botao_opcional = '') {
            result = await Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                html: ds_texto_prosseguir,
                confirmButtonText: 'OK',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                showDenyButton: sn_botao_opcional,
                denyButtonText: ds_texto_botao_opcional,
            });

            return result.value;
        },

        async mixinAlertErro(ds_texto_erro = 'Houve um erro durante a operação.') {
            await Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: ds_texto_erro
            });
        },

        async mixinAlertSucesso(ds_texto_erro = 'Operação concluída com sucesso.') {
            await Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                html: ds_texto_erro
            });
        },
    }
  }
