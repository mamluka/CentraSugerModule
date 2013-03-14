<?php
require_once(__DIR__ . '/../core/core.php');

function HandleNonBillable(&$bean)
{

    $apiFactory = new EmailRestClient();
    $api = $apiFactory->Get();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "") {
        $logger->LogInfo("Saved a client: " . $bean->id . " as non billable without choosing a reason!");
        sugar_die("Non billable must have a reason");
        exit;
    }

    global $current_user;


    $email = $bean->email1;
    $id = $bean->id;
    $name = $bean->first_name;

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "invalid_url" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = date("m/d/Y");
        $bean->status = "pitstop";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;

        $result = $api->get("/email/invalid-url?email=" . $email . "&customerId=" . $id);

        if ($result == "OK") {
            $logger->LogInfo("lead name:" . $name . " was sent a invalid url request");
        } else {
            $logger->LogInfo("Invalid url request to " . $name . "failed :" . $result);
        }


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "not_business_owner" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = date("m/d/Y");
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->status = "Dead";

        $result = $api->get("/email/not-the-right-person?email=" . $email);

        if ($result == "OK") {
            $logger->LogInfo("lead name:" . $name . " was sent a not the right person email");
        } else {
            $logger->LogInfo("not the right person send to" . $name . "failed :" . $result);
        }


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "not_interested" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = date("m/d/Y");
        $bean->status = "Dead";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "invalid_email" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = date("m/d/Y");
        $bean->status = "pitstop";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;


    }
}

?>