<?php
namespace app\classes\login;
require_once '../../../vendor/autoload.php';
require_once('../../../rotas.php');
use app\classes\nucleo\ExecutaSql;
use PDO;

class LoginSql
{
    private $ds_login;
    private $ds_senha;
    private $ExecutaSql;

    public function __construct() {
        session_start();
        $this->ExecutaSql = new ExecutaSql();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->ds_login = $_POST['ds_login'];
            $this->ds_senha = md5($_POST['ds_senha']);
        }
    }

    public function logar() {
        try {
            if ($this->ds_login && $this->ds_senha) {
                $arrRetorno = $this->ExecutaSql->setDsSql("
                    SELECT
                        cd_usuario
                    FROM
                        usuario
                    WHERE
                        ds_login='$this->ds_login'
                        AND ds_senha='$this->ds_senha'
                ")->read();

                if (!$arrRetorno['sucesso']) {
                    echo $arrRetorno['retorno'];
                    die;
                }

                if (count($arrRetorno['retorno']) > 0) {
                    $_SESSION['ds_login'] = $this->ds_login;
                    $_SESSION['cd_usuario'] = $arrRetorno['retorno'][0]['cd_usuario'];

                    header('location:' . ROTA_SITE_VIEWS . 'home/tela-selecao-centro-custo.php');
                    return;
                }
            }

            $_POST['ds_erro_acesso'] = 'Usuário ou senha inválidos';
            header('location:' . ROTA_SITE_VIEWS . 'login/login.php?ds_erro_acesso=Usuário ou senha inválidos');
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
}
