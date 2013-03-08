<?php
require_once(__DIR__ . '/../core/core.php');

function ValidateStatuses(&$bean)
{
    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    if ($bean->status == "client" && $bean->mobileweb_check_c == 0 && $bean->googlelocal_check_c == 0 && $bean->merch_check_c == 0) {
        $logger->LogInfo("Saved a client: " . $bean->id . " status without a service!");
        sugar_die("You can't change a lead to client status without setting the centra service he purchased first, it is located in the Sold Services tab");
        exit;
    }

    if ($bean->status == "cancelled" && ($bean->mobileweb_check_c == 1 || $bean->googlelocal_check_c == 1 || $bean->merch_check_c == 1)) {
        $logger->LogInfo("Saved a cancelled: " . $bean->id . " status with services active");
        sugar_die("You can't change a lead to cancelled status when you have service checked, the services are located in the Sold Services tab");
        exit;
    }
}

?>