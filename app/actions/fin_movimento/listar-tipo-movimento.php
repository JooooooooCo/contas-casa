<?php
require_once '../../../vendor/autoload.php';
use app\classes\fin_movimento\ComponentesSql;

$ComponentesSql = new ComponentesSql();
$ComponentesSql->readTipoMovimento();
?>
