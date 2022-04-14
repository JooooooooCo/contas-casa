<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once('../../../rotas.php');

$sn_tela_login = ($_SERVER['PHP_SELF'] == ROTA_SITE_VIEWS . 'login/login.php');
$sn_tela_selecao_centro_custo = ($_SERVER['PHP_SELF'] == ROTA_SITE_VIEWS . 'home/tela-selecao-centro-custo.php');

if (!$sn_tela_login) {
  if (!isset($_SESSION['ds_login'])) {
    header('location:' . ROTA_SITE_ROOT . 'index.php');
  }

  if (!isset($_SESSION['cd_centro_custo']) && !$sn_tela_selecao_centro_custo) {
    header('location:' . ROTA_SITE_VIEWS . 'home/tela-selecao-centro-custo.php');
  }
}
?>

<!DOCTYPE html>
  <html>
    <head>
        <meta charset="utf-8">
        <title> Contas Casa </title>

        <!-- jquery -->
        <script type="text/javascript" src="<?php echo ROTA_SITE_PUBLIC; ?>plugins/jquery/jquery-2.1.4.js"></script>

        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!--Import materialize.css-->
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/materialize/materialize.min.css">

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- VueJs -->
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>plugins/vuejs/vue.js"></script>

        <!-- Vue-Select -->
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>plugins/vuejs/vue-select.js"></script>
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/vue-select/vue-select.css">
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/vue-select/vue-select-custom.css">

        <!-- Vue datepicker -->
        <script src='<?php echo ROTA_SITE_PUBLIC; ?>plugins/vuejs/v-calendar.umd.min.js'></script>
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/v-calendar/v-calendar-custom.css">

        <!-- Vue the mask -->
        <script src='<?php echo ROTA_SITE_PUBLIC; ?>plugins/vuejs/vue-the-mask.js'></script>

        <!-- Axios -->
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>plugins/axios/axios.min.js"></script>

        <!-- ag-grid -->
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>plugins/ag-grid/ag-grid-community.min.noStyle.js"></script>
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/ag-grid/ag-grid.css">
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/ag-grid/ag-theme-alpine-dark.css">

        <!-- Custom -->
        <link rel="stylesheet" href="<?php echo ROTA_SITE_PUBLIC; ?>css/custom.css">

        <!-- Passando dados do PHP, utilizados nos arquivos js -->
        <script>
          // Rotas do site (caminho projeto)
          const ROTA_SITE_ACTIONS = '<?php echo ROTA_SITE_ACTIONS; ?>';
          const ROTA_SITE_ROOT = '<?php echo ROTA_SITE_ROOT; ?>';
        </script>

        <!-- Componentes personalizados -->
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>js/nucleo/mixin-gerais.js"></script>
        <script src="<?php echo ROTA_SITE_PUBLIC; ?>js/nucleo/v-radio-button.js"></script>
    </head>

    <body style="<?php if ($sn_tela_login || $sn_tela_selecao_centro_custo) {echo 'max-height: 100vh;padding: 0;overflow: hidden;';} ?>">
