<?php
require_once '../../../vendor/autoload.php';
use app\classes\home\CentroCustoSql;
use app\models\nucleo\Sessao;

Sessao::validarUsuarioLogado();

$CentroCustoSql = new CentroCustoSql();
$CentroCustoSql->readCentroCusto();

?>