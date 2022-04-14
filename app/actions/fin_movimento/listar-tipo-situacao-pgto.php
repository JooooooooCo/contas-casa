<?php
require_once '../../../vendor/autoload.php';
// use app\classes\fin_movimento\ComponentesSql;

$ComponentesSql = new \app\classes\fin_movimento\ComponentesSql();
$ComponentesSql->readTipoSituacaoPgto();
?>
