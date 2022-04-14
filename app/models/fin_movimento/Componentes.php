<?php
namespace app\models\fin_movimento;

class Componentes {

    private $tipo_movimento, $grupo_dois;

    public function getTipoMovimento() {
        return $this->tipo_movimento;
    }

    public function setTipoMovimento($tipo_movimento) {
        $this->tipo_movimento = $tipo_movimento;
    }

    public function getGrupoDois() {
        return $this->grupo_dois;
    }

    public function setGrupoDois($grupo_dois) {
        $this->grupo_dois = $grupo_dois;
    }
}
