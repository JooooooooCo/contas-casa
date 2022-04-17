<?php
namespace app\models\fin_movimento;

class Movimento {
    private $arrDados;
    private $cd_movimento;

    public function getArrDados() {
        return $this->arrDados;
    }

    public function getCdMovimento() {
        return $this->cd_movimento;
    }

    public function setArrDados($arrDados) {
        $this->arrDados = (array) $arrDados;
    }

    public function setCdMovimento($cd_movimento) {
        $this->cd_movimento = $cd_movimento;
    }
}
