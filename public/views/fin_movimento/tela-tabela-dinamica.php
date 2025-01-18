<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'fin_movimento/tabela-dinamica.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div id="tela-tabela-dinamica">
    <div class="row">
        <div class ="col s12">
            <div id="pivot-container" v-show="!snCarregando"></div>
        </div>
    </div>
</div>

<script type="module" src="<?php echo ROTA_SITE_PUBLIC; ?>js/fin_movimento/tela-tabela-dinamica.js"></script>

<?php
include_once ROTA_FOLDER_VIEWS . 'fin_movimento/tela-tabela-dinamica.php';
include_once ROTA_FOLDER_INCLUDES . 'footer.php';
?>
