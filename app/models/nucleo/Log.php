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
        error_reporting(E_ERROR);
        $conteudo .= 'Data da operacao: ' . date("Y-m-d H:i:s") . PHP_EOL;
        $conteudo .= '----------------------------------------------------------------' . PHP_EOL;

        $arquivos = ['../../../log.log', '../../../log_temporario.log'];

        foreach ($arquivos as $arquivo) {
            if (!file_exists($arquivo)) {
                touch($arquivo);
            }
            file_put_contents($arquivo, $conteudo, FILE_APPEND);
        }
    }

    public function enviarEmailLog() {
        error_reporting(E_ERROR);
        $ds_arquivo_log = "../../../log_temporario.log";

        if (!file_exists($ds_arquivo_log)) {
            throw new \Exception('Erro ao abrir o arquivo de log temporário');
        }

        $objArquivoLog = fopen($ds_arquivo_log, "r");
        $nr_tamanho_arquivo = filesize($ds_arquivo_log);
        $ds_conteudo_log = fread($objArquivoLog, $nr_tamanho_arquivo);
        fclose($objArquivoLog);

        $ds_conteudo_log = "<pre>$ds_conteudo_log</pre>";

        $this->Email->enviarEmailLog($ds_conteudo_log);
        unlink($ds_arquivo_log);
    }

    public function geraLogCamposInclusaoExclusao($novos) {
        $log = '';

        foreach ($novos as $key => $value) {
            $log .= $key . ': ' . $novos[$key] . PHP_EOL;
        }

        return $log;
    }

    public function geraLogCamposAlteracao($antigos, $novos, $nivel = 1) {
        $log = '';

        if ($novos == null || $antigos == null) {
            return $log;
        }

        foreach ($antigos as $key => $value) {
            if ($novos[$key] instanceof \stdClass) {
                $novos[$key] = (array) $novos[$key];
            }

            if ($value != null && is_array($value) && $novos[$key] != null && !empty($novos[$key])) {
                $log .= $this->geraLogCamposAlteracao($antigos[$key], $novos[$key], $nivel + 1);
            } else if (array_key_exists($key, $novos) && $antigos[$key] != $novos[$key]) {
                $log .= $key . ': ' . $antigos[$key] . ' para ' . $novos[$key] . PHP_EOL;
            }
        }

        return $log;
    }
}
