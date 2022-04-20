<?php
namespace app\classes\fin_movimento;
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Movimento;
use app\classes\nucleo\ExecutaSql;
use PDO;

class MovimentoSql
{
    private $cd_centro_custo;
    private $ExecutaSql;

    public function __construct() {
        $this->cd_centro_custo = $_SESSION['cd_centro_custo'];
        $this->ExecutaSql = new ExecutaSql();
    }

    public function create(Movimento $m) {
        $this->ExecutaSql->openTransaction();

        try {
            $arrDados = $this->preparaDados($m->getArrDados());

            $arrRetorno = $this->ExecutaSql
                ->setArrDados($arrDados)
                ->setDsTabela('fin_movimento')
                ->create();

            $this->ExecutaSql->closeTransaction();
        } catch(\Exception $e) {
            $this->ExecutaSql->abortTransaction();

            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function read() {
        try {
            $arrRetorno = $this->ExecutaSql->setDsSql("
                SELECT
                    fm.cd_movimento,
                    tm.cd_tipo_movimento,
                    tm.ds_tipo_movimento,
                    tp.cd_tipo_pgto,
                    tp.ds_tipo_pgto,
                    ts.cd_tipo_situacao_pgto,
                    ts.ds_tipo_situacao_pgto,
                    DATE_FORMAT(fm.dt_compra,'%d/%m/%Y') AS dt_compra,
                    DATE_FORMAT(fm.dt_vcto,'%d/%m/%Y') AS dt_vcto,
                    DATE_FORMAT(fm.dt_pgto,'%d/%m/%Y') AS dt_pgto,
                    fm.vl_original,
                    fm.vl_pago,
                    fm.vl_dif_pgto,
                    fm.nr_parcela_atual,
                    fm.nr_qtd_parcelas,
                    tg1.cd_tipo_grupo_i,
                    tg1.ds_tipo_grupo_i,
                    tg2.cd_tipo_grupo_ii,
                    tg2.ds_tipo_grupo_ii,
                    tg3.cd_tipo_grupo_iii,
                    tg3.ds_tipo_grupo_iii,
                    fm.ds_movimento,
                    fm.ds_obs_i,
                    fm.ds_obs_ii,
                    fm.ds_media_gastos,
                    fm.sn_real
                FROM
                    fin_movimento fm
                    LEFT JOIN tipo_movimento tm ON (tm.cd_tipo_movimento = fm.cd_tipo_movimento)
                    LEFT JOIN tipo_pgto tp ON (tp.cd_tipo_pgto = fm.cd_tipo_pgto)
                    LEFT JOIN tipo_situacao_pgto ts ON (ts.cd_tipo_situacao_pgto = fm.cd_tipo_situacao_pgto)
                    LEFT JOIN tipo_grupo_i tg1 ON (tg1.cd_tipo_grupo_i = fm.cd_tipo_grupo_i)
                    LEFT JOIN tipo_grupo_ii tg2 ON (tg2.cd_tipo_grupo_ii = fm.cd_tipo_grupo_ii)
                    LEFT JOIN tipo_grupo_iii tg3 ON (tg3.cd_tipo_grupo_iii = fm.cd_tipo_grupo_iii)
                WHERE
                    fm.cd_centro_custo = $this->cd_centro_custo
            ")->read();
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function update(Movimento $m) {
        $this->ExecutaSql->openTransaction();

        try {
            $arrDadosAntigos = $this->retornarDadosMovimento($m->getCdMovimento());
            $arrDados = $this->preparaDados($m->getArrDados());

            $arrDadosAlterados = $this->retornarDadosAlterados($arrDadosAntigos, $arrDados);

            if (count($arrDadosAlterados) <= 0) {
                throw new \Exception(
                    $this->translate('Não foi identificada nenhuma alteração')
                );
            }

            $ds_condicao = 'cd_movimento = ' . $m->getCdMovimento();

            $arrRetorno = $this->ExecutaSql
                ->setArrDados($arrDadosAlterados)
                ->setDsCondicao($ds_condicao)
                ->setDsTabela('fin_movimento')
                ->update();

            $this->ExecutaSql->closeTransaction();
        } catch(\Exception $e) {
            $this->ExecutaSql->abortTransaction();

            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    public function delete(Movimento $m) {
        $this->ExecutaSql->openTransaction();

        try {
            $ds_condicao = 'cd_movimento = ' . $m->getCdMovimento();

            $arrRetorno = $this->ExecutaSql
                ->setDsCondicao($ds_condicao)
                ->setDsTabela('fin_movimento')
                ->delete();

            $this->ExecutaSql->closeTransaction();
        } catch(\Exception $e) {
            $this->ExecutaSql->abortTransaction();

            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];
        }

        echo json_encode($arrRetorno);
    }

    private function preparaDados($arrDados) {
        $arrDados['cd_centro_custo'] = $this->cd_centro_custo;
        unset($arrDados['cd_movimento']);

        $arrDados['dt_compra'] = date('Y-m-d', strtotime($arrDados['dt_compra']));
        $arrDados['dt_vcto'] = date('Y-m-d', strtotime($arrDados['dt_vcto']));
        $arrDados['dt_pgto'] = date('Y-m-d', strtotime($arrDados['dt_pgto']));
        $arrDados['vl_original'] = str_replace(",", ".", $arrDados['vl_original']);
        $arrDados['vl_pago'] = str_replace(",", ".", $arrDados['vl_pago']);
        $arrDados['vl_dif_pgto'] = str_replace(",", ".", $arrDados['vl_dif_pgto']);

        return $arrDados;
    }

    private function retornarDadosMovimento($cd_movimento) {
        try {
            $arrRetorno = $this->ExecutaSql->setDsSql("
                SELECT
                    *
                FROM
                    fin_movimento
                WHERE
                    cd_movimento = $cd_movimento
            ")->read();
        } catch(\Exception $e) {
            $arrRetorno = [
                'sucesso' => false,
                'retorno' => $e->getMessage()
            ];

            echo json_encode($arrRetorno);
            die;
        }

        $arrRetorno = $arrRetorno['retorno'][0];
        unset($arrRetorno['cd_movimento']);
        unset($arrRetorno['dt_inclusao']);
        unset($arrRetorno['dt_alteracao']);

        return $arrRetorno;
    }

    private function retornarDadosAlterados(
        $arrDadosAntigos,
        $arrDadosNovos
    ) {
        $retorno = [];

        if ($arrDadosNovos == null || $arrDadosAntigos == null) {
        return $retorno ;
        }

        foreach ($arrDadosNovos as $key => $value) {
        if ($arrDadosNovos[$key] == $arrDadosAntigos[$key]) {
            unset($arrDadosNovos[$key]);
        }
        }

        if (count($arrDadosNovos) > 0) {
        $retorno = $arrDadosNovos;
        }

        return $retorno;
    }
}
