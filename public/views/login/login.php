<?php
require_once '../../../rotas.php';
include_once ROTA_FOLDER_CONTROLLERS . 'login/login.php';
include_once ROTA_FOLDER_INCLUDES . 'header.php';
?>

<div class="row" id="index_login">
    <div class="col s12" style="min-height: 100vh;">
        <div class="col s10 m6 l4" style="
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            ">
            <div class="z-depth-1 row card-padrao">
                <form class="col s12" method="POST" action="<?php echo ROTA_SITE_ACTIONS; ?>login/login.php">
                    <div class='row mar-top-20 center-align'>
                        <i class="material-icons teal-text text-darken-2 large">fingerprint</i>
                    </div>

                    <div class='row mar-top-10'>
                        <div class='input-field col s12'>
                            <input class='validate' type='text' name='ds_login' id='ds_login' v-model="ds_login" />
                            <label for='ds_login' class="active" style="margin-top: -5px;">Login</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='ds_senha' id='ds_senha' required="true" v-model="ds_senha" />
                            <label for='ds_senha' class="active" style="margin-top: -5px;">Senha</label>
                        </div>
                    </div>

                    <div class='row mar-bottom-10'>
                        <div class='col s12'>
                            <button type="submit" name='btn_login'
                                class='col s12 btn btn-large waves-effect teal darken-2'>
                                Login
                            </button>
                        </div>
                    </div>

                    <div class='row mar-bottom-10'>
                        <div class='col s12 center-align'>
                            <span><?php echo $ds_erro_acesso; ?></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once ROTA_FOLDER_INCLUDES . 'footer.php';
?>
