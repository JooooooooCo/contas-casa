<?php
namespace app\models\nucleo;

require_once '../../../vendor/autoload.php';
require_once('../../../config.php');
use app\models\nucleo\Email;

class Log
{
    private $Email;

    public function __construct() {
        $this->Email = new Email();
    }

    public function gravarLog($conteudo) {
        $conteudo .= 'Data da operação: ' . date("Y-m-d H:i:s") . PHP_EOL;
        $conteudo .= '----------------------------------------------------------------' . PHP_EOL;

        $this->Email->enviarEmailLog($conteudo);
    }

    public function geraLogCamposInclusaoExclusao($novos) {
        $log = '';

        foreach ($novos as $key => $value) {
            $log.= $key.': '.$novos[$key].PHP_EOL;
        }

        return $log;
    }

    public function geraLogCamposAlteracao($antigos, $novos, $nivel = 1) {
        $log = '';

        if( $novos == null || $antigos == null   ) {
            return $log ;
        }

        foreach ($antigos as $key => $value) {
            if($novos[$key] instanceof stdClass){
                $novos[$key] = (array) $novos[$key];
            }

            if( $value != null && is_array($value) && $novos[$key] != null && !empty($novos[$key])){
                $log.= $this->geraLogCamposAlteracao($antigos[$key], $novos[$key], $nivel + 1 );
            } else if (array_key_exists ( $key, $novos ) && $antigos[$key] != $novos[$key]) {
                $log.= $key.': '.$antigos[$key] .' para '. $novos[$key].PHP_EOL;
            }
        }

        return $log;
    }
}
