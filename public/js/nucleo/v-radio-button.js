Vue.component('v-radio-button', {
    model:{
        prop:"opcaoSelecionadaRadio",
        event:"change"
    },
    name:"VRadioButton",
    props:{
        label:{
            Type:String,
            default:""
        },
        arrObjOpcoes:{
            type:Array,
            default: ()=> {
                return []
            }
        },
        opcaoSelecionadaRadio:{
            default:null
        },
        snTodasOpcoesColoridas:{
            default:false
        }
    },

    data:()=>({
        opcaoSelecionada:null,
    }),

    methods:{
        defineRadio(opcao) {
            this.opcaoSelecionada = opcao;
            this.$emit('change',this.opcaoSelecionada);
        }
    },

    mounted() {
        if (this.opcaoSelecionadaRadio != null) {
            this.opcaoSelecionada = this.opcaoSelecionadaRadio;
        }

        if (this.arrObjOpcoes != null && this.arrObjOpcoes.length > 0 && this.opcaoSelecionadaRadio == null) {
            this.opcaoSelecionada = this.arrObjOpcoes[0].cd_opcao;
        }

        if (this.opcaoSelecionada != null) {
            this.defineRadio(this.opcaoSelecionada);
        }
    },

    watch:{
        opcaoSelecionadaRadio:function () {
            if (this.opcaoSelecionada == this.opcaoSelecionadaRadio) return;
            this.opcaoSelecionada = this.opcaoSelecionadaRadio;
            this.defineRadio(this.opcaoSelecionada);
        },
        opcaoSelecionada:function () {
            this.$emit('input',this.opcaoSelecionada);
        }
    },

    template: `
        <div class="v-radio-button">
            <p class="title-itens-radio-button" v-if="label !== ''">{{label}}</p>

            <div class="btn-group" role="group">
                <template v-for="(opcao,index) in arrObjOpcoes">
                    <a  :class="opcaoSelecionada == opcao.cd_opcao || snTodasOpcoesColoridas ?
                            'btn white-text teal darken-2 mar-right-5 mar-bottom-5' :
                            'btn blue-grey-text text-darken-4 white mar-right-5 mar-bottom-5'"
                        @click="defineRadio(opcao.cd_opcao)"
                    >{{opcao.ds_opcao}}</a>
                </template>
            </div>
        </div>
    `
  })