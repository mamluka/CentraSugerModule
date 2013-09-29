<?php
require_once(__DIR__ . '/core/core.php');
// prevent people from accessing this file directly
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not a valid entry point.');
}
class Notes
{
    function AddNote($event, $arguments)
    {
        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $configManager = new ConfigManager();
        $config = $configManager->Get();

        if ($_REQUEST['action'] != 'EditView') {
            if (isset($_SESSION["save_mode"]) && $_SESSION["save_mode"] == true) {
                echo '<input type="hidden" id="inputMode" value="save"/>';
            }

        }

        if (($_REQUEST['action'] == 'EditView' && isset($_REQUEST['record'])) || $_REQUEST['action'] == 'DetailView') {
            echo '<script type="text/javascript" src="custom/include/centra/js/jquery.msgbox.js"></script>';
            echo '<script type="text/javascript" src="custom/include/centra/js/centra.js?' . rand(555511, 99879) . '"></script>';

            echo '<link rel="stylesheet" type="text/css" href="custom/include/centra/css/centra.css?' . rand(555511, 99879) . '">';
            echo '<link rel="stylesheet" type="text/css" href="custom/include/centra/css/msgbox-light.css">';

            echo '<script type="text/javascript" src="custom/include/centra/js/postmessage.js"></script>';

            $record = $_REQUEST['record'];
            echo '<div id="crmTools" data-base-url="' . $config->SoaBaseUrl . '" data-record="' . $record . '"></div>';
        }

        $_SESSION["save_mode"] = false;

        session_commit();
    }
}