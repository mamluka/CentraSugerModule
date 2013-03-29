<?php
require_once(__DIR__ . '/../core/core.php');

function VerifiedLocalListingData(&$bean)
{
    $soaFactory = new SoaRestClient();
    $soa = $soaFactory->Get();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    $email = $bean->email1;
    $name = $bean->first_name;

    $notes = new NotesClient();

    if ($bean->googlelocal_verified_c == 1 && $bean->googlelocal_check_c == 1 && $bean->googlelocal_verified_date_c == "") {

        $result = $soa->get("/emails/google-local-listing/google-local-listing-heads-up", array(
            "email" => $email,
            "customerId" => $bean->id
        ));

        if ($result == "OK") {
            $bean->googlelocal_verified_date_c = crm_date();
            $logger->LogInfo("lead name:" . $name . " was sent a local listing info heads up email");

            $notes->AddNote($bean->id, "Google local listing details were verified");
        } else {
            $logger->LogInfo("local listing heads up send to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }
    }
}

?>