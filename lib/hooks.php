<?php

require_once(__DIR__ . '/hooks/include-all-hooks.php');

class CentraHooks
{
    function BeforeSave(&$bean, $event, $arguments)
    {
        if (array_key_exists($_SESSION, "already_run"))
            return;

        ValidateStatuses($bean);

        $_SESSION["already_run"] = true;
    }

    function AfterSave(&$bean, $event, $arguments)
    {
        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $logger->LogInfo("started the hooks");

        if (isset($_SESSION["already_run"])) {
            $logger->LogInfo("exited");
            return;
        }


        SendMobilePreviewEmail($bean);
        UpdateWhoAssignedDeadStatus($bean);
        ClientStatusChange($bean);
        UpdateSoldServicesDates($bean);
        UpdateWhoCancelled($bean);
        HandleNonBillable($bean);
        ServicesAreLiveEmails($bean);
        VerifiedLocalListingData($bean);

        $logger->LogInfo("stopeed hooks");
        $_SESSION["already_run"] = true;

        $bean->save();

        $logger->LogInfo("saved hooks");


    }
}

?>