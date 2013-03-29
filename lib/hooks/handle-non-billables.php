<?php
require_once(__DIR__ . '/../core/core.php');

function HandleNonBillable(&$bean)
{

    $soaFactory = new SoaRestClient();
    $soa = $soaFactory->Get();

    $notes = new NotesClient();

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
        $bean->not_billable_assign_date_c = crm_date();
        $bean->status = "pitstop";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;

        $result = $soa->get("/emails/non-billable/invalid-url", array(
            "email" => $email,
            "customerId" => $id
        ));

        if ($result == "OK") {
            $logger->LogInfo("lead name:" . $name . " was sent a invalid url request");

            $notes->AddNote($id, "Moved to pit stop because of invalid url, invalid url email was sent to " . $email);
        } else {
            $logger->LogInfo("Invalid url request to " . $name . "failed :" . $result);
        }


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "not_business_owner" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = crm_date();
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->status = "Dead";

        $result = $api->get("/emails/non-billable/not-the-right-person", array(
            "email" => $email
        ));

        if ($result == "OK") {
            $logger->LogInfo("lead name:" . $name . " was sent a not the right person email");

            $notes->AddNote($id, "Moved to dead because we contacted not business owner, email sent to " . $email);
        } else {
            $logger->LogInfo("not the right person send to" . $name . "failed :" . $result);
        }


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "not_interested" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = crm_date();
        $bean->status = "Dead";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;

        $notes->AddNote($id, "Moved to dead status because lead was not interested");


    }

    if ($bean->not_billable_c == 1 && $bean->non_billable_reason_c == "invalid_email" && $bean->not_billable_assigner_c == "") {
        $bean->not_billable_assign_date_c = crm_date();
        $bean->status = "pitstop";
        $bean->not_billable_assigner_c = $current_user->first_name . " " . $current_user->last_name;

        $notes->AddNote($id, "Moved to pit stop because has invalid email");

    }
}

?>