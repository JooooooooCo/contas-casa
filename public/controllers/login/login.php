<?php
session_start();
session_destroy();

$ds_erro_acesso = '';
if (isset($_GET['ds_erro_acesso'])) {
    $ds_erro_acesso = $_GET['ds_erro_acesso'];
}
?>
