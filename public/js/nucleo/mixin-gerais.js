var mixinGerais = {
    methods: {
        mixinMonetarioFormatadoAgGrid(params) {
            return this.mixinMonetarioFormatado(params.value);
        },

        mixinMonetarioFormatado(valor) {
            if (valor == null) return;

            let str = valor.toString();
            return str.replace('.', ',');
        },

        mixinGetLarguraPercJanela(nr_percentual) {
            return (($(window).width()*0.98) * (nr_percentual / 100));
        },

        mixinAtualizarMaterialize() {
            $(document).ready(function(){
                $('.tooltipped').tooltip();
            });

            Vue.nextTick(() => {
                $('.tooltipped').tooltip();
                // $(`.dropdown-button-${this.id_tela}`).dropdown();
                $('.collapsible').collapsible();
                $('input').characterCounter();
                M.updateTextFields();
            });
        },

        mixinShowDatePicker(nm_campo) {
            this.objDatePickerExibir[nm_campo] = true;
            this.mixinAtualizarMaterialize();
        },

        mixinHideDatePicker(nm_campo) {
            this.objDatePickerExibir[nm_campo] = false;
            this.mixinAtualizarMaterialize();
        },

        mixinCheckIsMobile() {
            if( window.innerWidth <= 760 ) {
                this.isMobile = true;
                return;
            }

            this.isMobile = false;
        },

        mixinGetVlDifPgto(vl_original, vl_pago) {
            vl_original = vl_original ?? '0';
            vl_original = vl_original.replace(/\D/g,''); // remove todos os caracteres não númericos
            vl_original = parseFloat(vl_original);

            vl_pago = vl_pago ?? '0';
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
        },
    }
  }
