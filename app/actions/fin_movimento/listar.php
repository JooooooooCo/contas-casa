<?php
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Movimento;
use app\classes\fin_movimento\MovimentoSql;
use app\models\nucleo\Sessao;

Sessao::validarUsuarioLogado();

$movimento = new Movimento();
$movimento->setArrFiltros((array) json_decode($_GET['objFiltros']));

$movimentoSql = new MovimentoSql();
$movimentoSql->read($movimento);
?>
