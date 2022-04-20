<?php
namespace app\classes\nucleo;
require_once '../../../vendor/autoload.php';
use app\models\nucleo\Conexao;
use PDO;

class ExecutaSql
{
    private $pdo;
    private $ds_sql;
    private $arrDados;
    private $ds_condicao;
    private $ds_tabela;

    public function __construct() {
        $this->pdo = Conexao::getConn();
    }

    public function openTransaction() {
        $this->pdo->beginTransaction();
    }

    public function closeTransaction() {
        $this->pdo->commit();
    }

    public function abortTransaction() {
        $this->pdo->rollBack();
    }

    public function setDsSql($ds_sql) {
        $this->ds_sql = $ds_sql;
        return $this;
    }

    public function setArrDados($arrDados) {
        $this->arrDados = (array) $arrDados;
        return $this;
    }

    public function setDsTabela($ds_tabela) {
        $this->ds_tabela = $ds_tabela;
        return $this;
    }

    public function setDsCondicao($ds_condicao) {
        $this->ds_condicao = $ds_condicao;
        return $this;
    }

    public function create() {
        $nr_qtd_campos = count($this->arrDados);
        $ds_campos = implode(",", array_keys($this->arrDados));
        $ds_valores = implode(",", array_values($this->arrDados));
        $arrValores = array_values($this->arrDados);

        $ds_place_holders_valores = substr(str_repeat("?,", $nr_qtd_campos), 0, -1);

        $sql = "INSERT INTO $this->ds_tabela ($ds_campos) VALUES ($ds_place_holders_valores)";

        $stmt = $this->pdo->prepare($sql);

        $sn_sucesso = $stmt->execute($arrValores);
        $id_inserido = $this->pdo->lastInsertId();

        return [
            'sucesso' => $sn_sucesso,
            'retorno' => $id_inserido
        ];
    }

    public function read() {
        // echo $this->ds_sql;
        $stmt = $this->pdo->query($this->ds_sql);
        $arrDados = $stmt->fetchAll();

        return [
            'sucesso' => true,
            'retorno' => $arrDados
        ];
    }

    public function update() {
        $ds_campos_place_holders = '';
        $arrChaves = array_keys($this->arrDados);

        foreach ($arrChaves as $key => $ds_chave) {
            $arrChaves[$key] = "$ds_chave=?";
        }

        $ds_campos_place_holders = implode(",", $arrChaves);
        $arrValores = array_values($this->arrDados);

        $sql = "UPDATE $this->ds_tabela SET $ds_campos_place_holders WHERE $this->ds_condicao";

        $stmt = $this->pdo->prepare($sql);

        $sn_sucesso = $stmt->execute($arrValores);

        return [
            'sucesso' => $sn_sucesso,
            'retorno' => ''
        ];
    }

    public function delete() {
        $sql = "DELETE FROM $this->ds_tabela WHERE $this->ds_condicao";

        $stmt = $this->pdo->prepare($sql);

        $sn_sucesso = $stmt->execute();

        return [
            'sucesso' => $sn_sucesso,
            'retorno' => ''
        ];
    }
}
