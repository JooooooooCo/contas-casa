var mixin = {
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
            //Inicializa tooltip
            $(document).ready(function(){
                $('.tooltipped').tooltip();
            });

            Vue.nextTick(() => {
                $('.tooltipped').tooltip();
                // $(`.dropdown-button-${this.id_tela}`).dropdown();
                // $('.collapsible').collapsible();
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
    }
  }
