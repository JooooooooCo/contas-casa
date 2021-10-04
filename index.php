<?php


require_once 'vendor/autoload.php';

include_once 'includes/header.php';


// $movimento = new \App\Model\Movimento();
// $movimento->setDespesaReceita('T');
// $movimento->setVlOriginal(255);
// $movimento->setId(9);

// $movimentoSql = new \App\Model\MovimentoSql();
// $movimentoSql->create($movimento);
?>

<div class="row">
    <div class ="col s12 m10 push-m1">
        <h3 class="light"> Adicionar novo movimento </h3>
        <form action="actions/adicionar.php" method="POST">
            <div class="row">
                <div class="input-field col s12 m1">
                    <input type="text" name="tipo_movimento" id="tipo_movimento">
                    <label for="tipo_movimento">Despesa/Receita</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="tipo_pgto" id="tipo_pgto">
                    <label for="tipo_pgto">Dinheiro/Crédito</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="tipo_situacao_pgto" id="tipo_situacao_pgto">
                    <label for="tipo_situacao_pgto">Pago/Pendente</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="dt_vcto" id="dt_vcto">
                    <label for="dt_vcto">Data Vencimento</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="dt_pgto" id="dt_pgto">
                    <label for="dt_pgto">Data Pagamento</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="vl_original" id="vl_original">
                    <label for="vl_original">Valor Original</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="vl_pago" id="vl_pago">
                    <label for="vl_pago">Valor Pago</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="dif_pgto" id="dif_pgto">
                    <label for="dif_pgto">Dif. Pagamento</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="parcela_atual" id="parcela_atual">
                    <label for="parcela_atual">Parcela atual</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="qtd_parcelas" id="qtd_parcelas">
                    <label for="qtd_parcelas">Qtd Parcelas</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="grupo_um" id="grupo_um">
                    <label for="grupo_um">Grupo um</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="grupo_dois" id="grupo_dois">
                    <label for="grupo_dois">Grupo dois</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="grupo_tres" id="grupo_tres">
                    <label for="grupo_tres">Grupo tres</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="descricao_pessoal" id="descricao_pessoal">
                    <label for="descricao_pessoal">Descricao pessoal</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="obs_um" id="obs_um">
                    <label for="obs_um">Obs um</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="obs_dois" id="obs_dois">
                    <label for="obs_dois">Obs dois</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="media_gastos" id="media_gastos">
                    <label for="media_gastos">Media gastos</label>
                </div>
                <div class="input-field col s12 m1">
                    <input type="text" name="sn_real" id="sn_real">
                    <label for="sn_real">Real/adm </label>
                </div>
            </div>

            <input type="hidden" name="id" id="id">

            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" name="btn-adicionar" class="btn"> Adicionar </button>
                </div>
            </div>
        </form>
    </div>
</div>



<?php
include_once 'includes/footer.php';
?>
