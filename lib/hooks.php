<?php

require_once(__DIR__ . '/hooks/include-all-hooks.php');
require_once(__DIR__ . '/core/core.php');

class CentraHooks
{
    function BeforeSave(&$bean, $event, $arguments)
    {
        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $logger->LogInfo("==========Before saving hooks started=================");

        if (isset($_SESSION["already_run"]) && $_SESSION["already_run"] == true) {
            $logger->LogInfo("==========Before saving Hooks exited=================");
            return;
        }

        ValidateStatuses($bean);
        ChangeStatusToFollowUpIfAssginedAndSaved($bean);

        $logger->LogInfo("==========Before saving Hooks ended=================");
    }

    function AfterSave(&$bean, $event, $arguments)
    {
        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        if (isset($_SESSION["already_run"]) && $_SESSION["already_run"] == true) {
            $logger->LogInfo("==========After saving Hooks exited=================");
            $_SESSION["already_run"] = false;
            $_SESSION["save_mode"] = true;
            return;
        }

        $logger->LogInfo("==========After saving hooks started=================");

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

        $logger->LogInfo("==========After saving Hooks ended=================");
    }
}

?>