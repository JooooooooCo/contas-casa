<?php
require_once '../../../vendor/autoload.php';
use app\classes\login\LoginSql;

$loginSql = new LoginSql();
$loginSql->logar();
?>
