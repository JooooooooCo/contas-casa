<?php
namespace app\models\nucleo;
use PDO;

class Sessao
{
    public static function validarUsuarioLogado()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['ds_login'])) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => 'Favor efetuar o login'
            ];

            echo json_encode($arrRetorno);
            die;
        }
    }
}
