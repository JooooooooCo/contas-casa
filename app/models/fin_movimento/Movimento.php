<?php
namespace app\models\fin_movimento;

class Movimento {
    private $arrDados;

    public function getArrDados() {
        return $this->arrDados;
    }

    public function setArrDados($arrDados) {
        $this->arrDados = (array) $arrDados;
    }
}
