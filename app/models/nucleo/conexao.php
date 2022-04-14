<?php
namespace app\models\nucleo;
use PDO;

class Conexao
{
    private static $instance;

    public static function getConn()
    {
        try {
            $cfg = array (
                'DB' => 'mysql:host=localhost;port=3308;dbname=contas_casa',
                'DB_USER' => 'root',
                'DB_PASS' => ''
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
