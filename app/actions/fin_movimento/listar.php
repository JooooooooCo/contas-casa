<?php
require_once '../../../vendor/autoload.php';
use app\classes\fin_movimento\MovimentoSql;
use app\models\nucleo\Sessao;

Sessao::validarUsuarioLogado();

$movimentoSql = new MovimentoSql();
$movimentoSql->read();
?>
