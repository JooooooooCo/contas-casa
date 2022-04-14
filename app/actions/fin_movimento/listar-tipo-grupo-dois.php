<?php
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Componentes;
use app\classes\fin_movimento\ComponentesSql;

$componentes = new Componentes();
// $_GET = json_decode(file_get_contents("php://input"),true);

if ($_GET['cd_tipo_movimento']) {
    $componentes->setTipoMovimento($_GET['cd_tipo_movimento']);
}

$ComponentesSql = new ComponentesSql();
$ComponentesSql->readTipoGrupoDois($componentes);
?>
