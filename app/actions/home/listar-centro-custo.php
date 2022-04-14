<?php
require_once '../../../vendor/autoload.php';
use app\classes\home\CentroCustoSql;

$CentroCustoSql = new CentroCustoSql();
$CentroCustoSql->readCentroCusto();

?>