<?php
namespace app\classes\fin_movimento;
require_once '../../../vendor/autoload.php';
use app\models\fin_movimento\Movimento;
use app\classes\nucleo\ExecutaSql;
use app\models\nucleo\Log;
use PDO;

class MovimentoSql
{
    private $cd_centro_custo;
    private $ExecutaSql;
    private $Log;

    public function __construct() {
        $this->cd_centro_custo = $_SESSION['cd_centro_custo'];
        $this->ExecutaSql = new ExecutaSql();
        $this->Log = new Log();
    }

    public function create(Movimento $m) {
        $this->ExecutaSql->openTransaction();

        try {
            $arrDados = $m->getArrDados();
            $this->validarCamposObrigatorios($arrDados);
            $arrDados = $this->preparaDados($arrDados);

            $arrRetorno = $this->ExecutaSql
                ->setArrDados($arrDados)
                ->setDsTabela('fin_movimento')
                ->create();

            $cd_movimento = $arrRetorno['retorno'];

            // Gera log da operação
            $ds_log = "Inserido cd_movimento: $cd_movimento. Campos: ";
            $ds_log .= $this->Log->geraLogCamposInclusaoExclusao($arrDados);
            $this->Log->gravarLog($ds_log);

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
            $arrDados = $m->getArrDados();
            $this->validarCamposObrigatorios($arrDados);
            $arrDados = $this->preparaDados($arrDados);

            $cd_movimento = $m->getCdMovimento();
            $arrDadosAntigos = $this->retornarDadosMovimento($cd_movimento);

            $arrDadosAlterados = $this->retornarDadosAlterados($arrDadosAntigos, $arrDados);

            if (count($arrDadosAlterados) <= 0) {
                throw new \Exception('Não foi identificada nenhuma alteração');
            }

            $ds_condicao = "cd_movimento = $cd_movimento";

            $arrRetorno = $this->ExecutaSql
                ->setArrDados($arrDadosAlterados)
                ->setDsCondicao($ds_condicao)
                ->setDsTabela('fin_movimento')
                ->update();

            // Gera log da operação
            $ds_log = "Alterado cd_movimento: $cd_movimento. Campos: ";
            $ds_log .= $this->Log->geraLogCamposAlteracao($arrDadosAntigos, $arrDados);
            $this->Log->gravarLog($ds_log);

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
            $cd_movimento = $m->getCdMovimento();
            $arrDadosMovimento = $this->retornarDadosMovimento($cd_movimento);
            $ds_condicao = "cd_movimento = $cd_movimento";

            $arrRetorno = $this->ExecutaSql
                ->setDsCondicao($ds_condicao)
                ->setDsTabela('fin_movimento')
                ->delete();

            // Gera log da operação
            $ds_log = "Removido cd_movimento: $cd_movimento. Campos: ";
            $ds_log .= $this->Log->geraLogCamposInclusaoExclusao($arrDadosMovimento);
            $this->Log->gravarLog($ds_log);

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

        $dt_compra = \DateTime::createFromFormat('d/m/Y H:i:s', $arrDados['dt_compra'] . ' 00:00:00');
        $arrDados['dt_compra'] = $dt_compra ? $dt_compra->format('Y-m-d H:i:s') : null;

        $dt_vcto = \DateTime::createFromFormat('d/m/Y H:i:s', $arrDados['dt_vcto'] . ' 00:00:00');
        $arrDados['dt_vcto'] = $dt_vcto ? $dt_vcto->format('Y-m-d H:i:s') : null;

        $dt_pgto = \DateTime::createFromFormat('d/m/Y H:i:s', $arrDados['dt_pgto'] . ' 00:00:00');
        $arrDados['dt_pgto'] = $dt_pgto ? $dt_pgto->format('Y-m-d H:i:s') : null;

        $arrDados['vl_original'] = str_replace(",", ".", $arrDados['vl_original']);
        $arrDados['vl_pago'] = str_replace(",", ".", $arrDados['vl_pago']);
        $arrDados['vl_dif_pgto'] = str_replace(",", ".", $arrDados['vl_dif_pgto']);

        return $arrDados;
    }

    private function retornarDadosMovimento($cd_movimento) {
        try {
            $arrRetorno = $this->ExecutaSql->setDsSql("
                SELECT
                    cd_movimento,
                    cd_tipo_movimento,
                    cd_tipo_pgto,
                    cd_tipo_situacao_pgto,
                    DATE_FORMAT(dt_compra, '%Y-%m-%d %H:%i:%s') as dt_compra,
                    DATE_FORMAT(dt_vcto, '%Y-%m-%d %H:%i:%s') as dt_vcto,
                    DATE_FORMAT(dt_pgto, '%Y-%m-%d %H:%i:%s') as dt_pgto,
                    vl_original,
                    vl_pago,
                    vl_dif_pgto,
                    nr_parcela_atual,
                    nr_qtd_parcelas,
                    cd_tipo_grupo_i,
                    cd_tipo_grupo_ii,
                    cd_tipo_grupo_iii,
                    ds_movimento,
                    ds_obs_i,
                    ds_obs_ii,
                    ds_media_gastos,
                    sn_real,
                    dt_inclusao,
                    dt_alteracao,
                    cd_centro_custo
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

    private function validarCamposObrigatorios($arrDados)
    {
        if (count($arrDados) <= 0) {
            throw new \Exception('Dados não informados.');
        }

        $arrCamposInvalidos = [];
        $arrCamposObrigatorios = [
            'cd_tipo_movimento' => 'Tipo de movimento',
            'cd_tipo_pgto' => 'Modo de pagamento',
            'cd_tipo_situacao_pgto' => 'Situação',
            'dt_compra' => 'Data compra',
            'dt_vcto' => 'Data vencimento',
            'vl_original' => 'Valor original',
            'vl_dif_pgto' => 'Diferença pagamento',
            'nr_parcela_atual' => 'Parcela atual',
            'nr_qtd_parcelas' => 'Quantidade parcelas',
            'cd_tipo_grupo_i' => 'Grupo 1',
            'cd_tipo_grupo_ii' => 'Grupo 2',
            'cd_tipo_grupo_iii' => 'Grupo 3',
            'ds_movimento' => 'Descrição pessoal',
            'sn_real' => 'Movimento'
        ];

        foreach ($arrCamposObrigatorios as $ds_campo_banco => $ds_campo_tela) {
            if (!isset($arrDados[$ds_campo_banco]) || !$arrDados[$ds_campo_banco]) {
                array_push($arrCamposInvalidos, $ds_campo_tela);
            }
        }

        if (count($arrCamposInvalidos) > 0) {
            $ds_campos_invalidos = implode(", ", $arrCamposInvalidos);

            throw new \Exception("Favor informar os campos: $ds_campos_invalidos.");
        }

        if ($arrDados['vl_original'] == '0,00') {
            throw new \Exception('O campo Valor original deve ser maior que 0,00');
        }
    }
}
