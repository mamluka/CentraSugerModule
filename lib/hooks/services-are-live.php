<?php
require_once(__DIR__ . '/../core/core.php');

function ServicesAreLiveEmails(&$bean)
{
    $email = $bean->email1;

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    $apiFactory = new EmailRestClient();
    $api = $apiFactory->Get();

    $name = $bean->first_name;

    if ($bean->mobileweb_live_c == 1 && $bean->mobileweb_check_c == 1) {

        $result = $api->get("/email/mobile-site-live?email=" . $email);

        if ($result == "OK") {
            $logger->LogInfo("lead name: " . $name . " was sent a mobile site is live");
        } else {
            $logger->LogInfo("mobile site live email to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }

        $bean->save();
    }

    if ($bean->googlelocal_live_c == 1 && $bean->googlelocal_check_c == 1) {

        $result = $api->get("/email/google-local-listing-live?email=" . $email);

        if ($result == "OK") {
            $logger->LogInfo("lead name: " . $name . " was sent a google local listing email");
        } else {
            $logger->LogInfo("google local listing email to: " . $name . "failed :" . $result);
            sugar_die("There is a problem with the CRM business flow, please contact david.mazvovsky@gmail.com asap");
        }

        $bean->save();
    }
}

?>
