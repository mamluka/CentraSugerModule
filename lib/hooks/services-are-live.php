<?php
require_once(__DIR__ . '/../core/core.php');

function ServicesAreLiveEmails(&$bean)
{
    $email = $bean->email1;

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    $soaFactory = new SoaRestClient();
    $soa = $soaFactory->Get();

    $notes = new NotesClient();

    $name = $bean->first_name;

    if ($bean->mobileweb_live_c == 1 && $bean->mobileweb_check_c == 1) {

        $result = $soa->get("/emails/mobile-web/mobile-web-live", array(
            "email" => $email
        ));

        if ($result == "OK") {
            $logger->LogInfo("lead name: " . $name . " was sent a mobile site is live");

            $notes->AddNote($bean->id, "Mobile web was set to live by " . current_user());
        } else {
            $logger->LogInfo("mobile site live email to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }
    }

    if ($bean->googlelocal_live_c == 1 && $bean->googlelocal_check_c == 1) {

        $result = $soa->get("/emails/google-local-listing/google-local-listing-live", array(
            "email" => $email
        ));

        if ($result == "OK") {
            $bean->googlelocal_live_assign_name_c = current_user();
            $bean->googlelocal_live_assign_date_c = crm_date();

            $logger->LogInfo("lead name: " . $name . " was sent a google local listing email");

            $notes->AddNote($bean->id, "Google local listing is set live by " . current_user());
        } else {
            $logger->LogInfo("google local listing email to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }
    }
}

?>
