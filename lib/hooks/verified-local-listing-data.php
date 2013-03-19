<?php
require_once(__DIR__ . '/../core/core.php');

function VerifiedLocalListingData(&$bean)
{
    $apiFactory = new EmailRestClient();
    $api = $apiFactory->Get();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    $email = $bean->email1;
    $name = $bean->first_name;

    $notes = new NotesClient();

    if ($bean->googlelocal_verified_c == 1 && $bean->googlelocal_check_c == 1) {

        $result = $api->get("/email/google-local-listing-heads-up?email=" . $email . '&customerId=' . $bean->id);

        if ($result == "OK") {
            $bean->googlelocal_verified_date_c = date("m/d/Y");
            $logger->LogInfo("lead name:" . $name . " was sent a local listing info heads up email");

            $notes->AddNote($bean->id,"Google local listing details was verified");
        } else {
            $logger->LogInfo("local listing heads up send to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }

    }
}

?>