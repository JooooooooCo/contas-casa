<?php
namespace app\classes\home;
require_once '../../../vendor/autoload.php';
use app\classes\nucleo\ExecutaSql;
use PDO;

class CentroCustoSql
{
    private $cd_usuario;
    private $ExecutaSql;

    public function __construct() {
        $this->cd_usuario = $_SESSION['cd_usuario'];
        $this->ExecutaSql = new ExecutaSql();
    }

    public function readCentroCusto() {
        try {
            $arrRetorno = $this->ExecutaSql->setDsSql("
                SELECT
                    cc.cd_centro_custo as cd_opcao,
                    cc.ds_centro_custo as ds_opcao
                FROM
                    centro_custo cc
                    INNER JOIN centro_custo_usuario ccu on (ccu.cd_centro_custo = cc.cd_centro_custo)
                WHERE
                    ccu.cd_usuario = $this->cd_usuario
            ")->read();
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }
}
