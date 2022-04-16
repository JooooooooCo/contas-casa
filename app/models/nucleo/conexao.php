<?php
namespace app\models\nucleo;
require_once('../../../config.php');
use PDO;

class Conexao
{
    private static $instance;

    public static function getConn()
    {
        try {
            $cfg = array (
                'DB' => BANCO,
                'DB_USER' => BANCO_USUARIO,
                'DB_PASS' => BANCO_SENHA
            );

            if(!isset(self::$instance)) {
                self::$instance = new PDO(
                    $cfg['DB'], $cfg['DB_USER'], $cfg['DB_PASS'],
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                );
            }

            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return self::$instance;
        } catch(\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }
}
