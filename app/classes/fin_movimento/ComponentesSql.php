<?php
namespace app\classes\fin_movimento;
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Componentes;
use app\classes\nucleo\ExecutaSql;
use PDO;

class ComponentesSql
{
    private $cd_centro_custo;
    private $ExecutaSql;

    public function __construct() {
        session_start();
        $this->cd_centro_custo = $_SESSION['cd_centro_custo'];
        $this->ExecutaSql = new ExecutaSql();
    }

    public function readTipoMovimento() {
        try {
            $arrRetorno = $this->read('tipo_movimento', 'cd_tipo_movimento', 'ds_tipo_movimento');
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function readTipoPgto() {
        try {
            $arrRetorno = $this->read('tipo_pgto', 'cd_tipo_pgto', 'ds_tipo_pgto');
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function readTipoSituacaoPgto() {
        try {
            $arrRetorno = $this->read('tipo_situacao_pgto', 'cd_tipo_situacao_pgto', 'ds_tipo_situacao_pgto');
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function readTipoGrupoUm() {
        try {
            $arrRetorno = $this->read('tipo_grupo_i', 'cd_tipo_grupo_i', 'ds_tipo_grupo_i');
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function readTipoGrupoDois(Componentes $c) {
        try {
            $cd_tipo_movimento = $c->getTipoMovimento();

            $arrRetorno = $this->read(
                "tipo_grupo_ii",
                "cd_tipo_grupo_ii",
                "ds_tipo_grupo_ii",
                "cd_tipo_movimento = $cd_tipo_movimento"
            );
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function readTipoGrupoTres(Componentes $c) {
        try {
            $cd_tipo_grupo_ii = $c->getGrupoDois();

            $arrRetorno = $this->read(
                "tipo_grupo_iii",
                "cd_tipo_grupo_iii",
                "ds_tipo_grupo_iii",
                "cd_tipo_grupo_ii = $cd_tipo_grupo_ii"
            );
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    private function read(
        $tabela,
        $campo_cd_opcao,
        $campo_ds_opcao,
        $condicao_where = '1 = 1'
    ) {
        try {
            return $this->ExecutaSql->setDsSql("
                SELECT
                    $campo_cd_opcao as cd_opcao,
                    $campo_ds_opcao as ds_opcao
                FROM
                    $tabela
                WHERE
                    $condicao_where AND
                    cd_centro_custo = $this->cd_centro_custo
            ")->read();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
