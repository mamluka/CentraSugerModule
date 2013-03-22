<?php
require_once(__DIR__ . '/../core/core.php');

function SendMobilePreviewEmail(&$bean)
{
    $apiFactory = new EmailRestClient();
    $api = $apiFactory->Get();

    $notes = new NotesClient();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    if ($bean->mobile_preview_email_sent_c == "" && $bean->prev_url_c != "http://" && $bean->prev_url_c != "" && $bean->status == "New" && $bean->email1 != "") {
        $email = $bean->email1;
        $mobile_preview_url = $bean->prev_url_c;

        $result = $api->get("/email/mobile-site-preview", array(
            'email' => $email,
            'previewUrl' => $mobile_preview_url
        ));


        $logger->LogInfo("contact name: " . $bean->first_name . " " . $bean->last_name);
        $logger->LogInfo("email is: " . $email);
        $logger->LogInfo("preview url is: " . $mobile_preview_url);
        $logger->LogInfo("response was " . $result);

        if ($result == "OK") {
            $bean->mobile_preview_email_sent_c = crm_date();
            $bean->status = "Assigned";

            $notes->AddNote($bean->id, "Site preview was sent to " . $email);
            $logger->LogInfo("Preview url send successfully");
        } else {
            $bean->mobile_preview_email_sent_c = "";
            $logger->LogInfo("Preview url send failed");
        }


    }
}

?>