<?php
session_start();

if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['cd_centro_custo'] > 0) {
    $_SESSION['cd_centro_custo'] = $_POST['cd_centro_custo'];
}
?>
