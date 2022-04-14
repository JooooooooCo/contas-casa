<?php
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Movimento;
use app\classes\fin_movimento\MovimentoSql;

$movimento = new Movimento();
$_POST = json_decode(file_get_contents("php://input"),true);

if ($_POST['objDados']) {
    $movimento->setArrDados($_POST['objDados']);
}

$movimentoSql = new MovimentoSql();
$movimentoSql->create($movimento);
?>
