<?php
// Definir as configurações do sistema e renomear este arquivo para "config.php"

// Define as configurações de conexão com o banco
define("BANCO", 'mysql:host=XXXXXX;port=XXXXXX;dbname=XXXXXX');
define("BANCO_USUARIO", 'XXXXXX');
define("BANCO_SENHA", 'XXXXXX');

// Define as configurações de email, para envio de notificações
define("EMAIL_LOG_ATIVO", false);
define("EMAIL_LOG_PORT", 587);
define("EMAIL_LOG_HOST", 'smtp.mail.yahoo.com');
define("EMAIL_LOG_USER", 'examplo@yahoo.com');
define("EMAIL_LOG_SENHA", 'senha');
define("EMAIL_LOG_PARA", 'examplo@gmail.com');
define("EMAIL_LOG_DE", 'examplo@yahoo.com');
?>
