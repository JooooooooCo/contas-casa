<?php
// Define as rotas do site (caminho projeto)
define("ROTA_SITE_ROOT", '/contas-casa/');
define("ROTA_SITE_APP", ROTA_SITE_ROOT . 'app/');
define("ROTA_SITE_PUBLIC", ROTA_SITE_ROOT . 'public/');
define("ROTA_SITE_ACTIONS", ROTA_SITE_APP . 'actions/');
define("ROTA_SITE_VIEWS", ROTA_SITE_PUBLIC . 'views/');
define("ROTA_SITE_CONTROLLERS", ROTA_SITE_PUBLIC . 'controllers/');

// Define as rotas de pasta (caminho absoluto)
define("ROTA_FOLDER_ROOT", str_replace("\\", "/", realpath(dirname(__FILE__))) . "/");
define("ROTA_FOLDER_APP", ROTA_FOLDER_ROOT . 'app/');
define("ROTA_FOLDER_PUBLIC", ROTA_FOLDER_ROOT . 'public/');
define("ROTA_FOLDER_VENDOR", ROTA_FOLDER_ROOT . 'vendor/');
define("ROTA_FOLDER_INCLUDES", ROTA_FOLDER_PUBLIC . 'includes/');
define("ROTA_FOLDER_CONTROLLERS", ROTA_FOLDER_PUBLIC . 'controllers/');
define("ROTA_FOLDER_VIEWS", ROTA_FOLDER_PUBLIC . 'views/');
?>
