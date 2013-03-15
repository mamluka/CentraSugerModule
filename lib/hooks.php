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
        if (isset($_SESSION["already_run"])) {
            $_SESSION["already_run"] = false;
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

        $_SESSION["already_run"] = true;

        $bean->save();
    }
}

?>