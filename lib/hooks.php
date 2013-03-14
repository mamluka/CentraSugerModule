<?php

require_once(__DIR__ . '/hooks/include-all-hooks.php');

class CentraHooks
{
    function BeforeSave(&$bean, $event, $arguments)
    {
        ValidateStatuses($bean);
    }

    function AfterSave(&$bean, $event, $arguments)
    {
        global $already_run;

        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $logger->LogInfo("started the hooks");

        if ($already_run)
            return;

        SendMobilePreviewEmail($bean);
        UpdateWhoAssignedDeadStatus($bean);
        ClientStatusChange($bean);
        UpdateSoldServicesDates($bean);
        UpdateWhoCancelled($bean);
        HandleNonBillable($bean);
        ServicesAreLiveEmails($bean);
        VerifiedLocalListingData($bean);

        $logger->LogInfo("stopeed hooks");
        $bean->save();

        $logger->LogInfo("saved hooks");

        $already_run = true;
    }
}

?>