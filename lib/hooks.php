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

        $bean->save();
        $already_run = true;
    }
}

?>