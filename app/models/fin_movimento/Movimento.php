<?php
namespace app\models\fin_movimento;

class Movimento {
    private $arrDados;
    private $cd_movimento;
    private $arrCdMovimento;
    private $arrFiltros;

    public function getArrDados() {
        return $this->arrDados;
    }

    public function getCdMovimento() {
        return $this->cd_movimento;
    }

    public function getArrCdMovimento() {
        return $this->arrCdMovimento;
    }

    public function getArrFiltros() {
        return $this->arrFiltros;
    }

    public function setArrDados($arrDados) {
        $this->arrDados = (array) $arrDados;
    }

    public function setCdMovimento($cd_movimento) {
        $this->cd_movimento = $cd_movimento;
    }

    public function setArrCdMovimento($arrCdMovimento) {
        $this->arrCdMovimento = $arrCdMovimento;
    }

    public function setArrFiltros($arrFiltros) {
        $this->arrFiltros = $arrFiltros;
    }
}
