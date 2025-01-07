<?php
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Movimento;
use app\classes\fin_movimento\MovimentoSql;
use app\models\nucleo\Sessao;

Sessao::validarUsuarioLogado();

$movimento = new Movimento();
$_POST = json_decode(file_get_contents("php://input"),true);
$movimento->setArrCdMovimento($_POST['arrCdMovimento']);

$movimentoSql = new MovimentoSql();
$movimentoSql->reconcile($movimento);
?>
