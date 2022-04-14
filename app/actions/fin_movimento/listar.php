<?php
require_once '../../../vendor/autoload.php';
use app\classes\fin_movimento\MovimentoSql;

$movimentoSql = new MovimentoSql();
$movimentoSql->read();
?>
