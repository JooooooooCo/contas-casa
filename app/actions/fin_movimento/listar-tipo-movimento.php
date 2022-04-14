<?php
require_once '../../../vendor/autoload.php';
use app\classes\fin_movimento\ComponentesSql;
use app\models\nucleo\Sessao;

Sessao::validarUsuarioLogado();

$ComponentesSql = new ComponentesSql();
$ComponentesSql->readTipoMovimento();
?>
