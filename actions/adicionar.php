<?php

require_once '../vendor/autoload.php';

var_dump($_POST);

if (isset($_POST['btn-adicionar'])) {

    $movimento = new \App\Model\Movimento();
    $movimento->setDespesaReceita($_POST['tipo_movimento']);
    $movimento->setVlOriginal($_POST['vl_original']);
    // $movimento->setId($_POST['id']);

    $movimentoSql = new \App\Model\MovimentoSql();
    $movimentoSql->create($movimento);

}
?>