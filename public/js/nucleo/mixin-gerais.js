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

        mixinGetDataFormatada(param_ano, param_mes, param_dia) {
            let objData = new Date(param_ano, param_mes, param_dia);
            let dia = objData.getDate();
            dia = dia.toString().length == 1 ? '0' + dia : dia;
            let mes = objData.getMonth() + 1;
            mes = mes.toString().length == 1 ? '0' + mes : mes;
            let ano = objData.getFullYear();

            return `${dia}/${mes}/${ano}`;
        }
    }
  }
