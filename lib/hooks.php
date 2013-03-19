<?php

require_once(__DIR__ . '/hooks/include-all-hooks.php');
require_once(__DIR__ . '/core/core.php');

class CentraHooks
{
    function BeforeSave(&$bean, $event, $arguments)
    {
        ValidateStatuses($bean);
    }

    function AfterSave(&$bean, $event, $arguments)
    {
        if (isset($_SESSION["already_run"])) {
            $_SESSION["already_run"] = false;
            return;
        }

        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $logger->LogInfo("==========Hooks started=================");

        SendMobilePreviewEmail($bean);
        UpdateWhoAssignedDeadStatus($bean);
        ClientStatusChange($bean);
        UpdateSoldServicesDates($bean);
        UpdateWhoCancelled($bean);
        HandleNonBillable($bean);
        ServicesAreLiveEmails($bean);
        VerifiedLocalListingData($bean);

        $_SESSION["already_run"] = true;

        $bean->save();

        $logger->LogInfo("==========Hooks ended=================");
    }
}

?>