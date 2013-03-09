<?php
require_once(__DIR__ . '/../core/core.php');

function UpdateSoldServicesDates(&$bean)
{
    global $current_user;

    $apiFactory = new EmailRestClient();
    $emailClient = $apiFactory->Get();

    $soaFactory = new SoaRestClient();
    $soaClient = $soaFactory->Get();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);


    $email = $bean->email1;
    $id = $bean->id;
    $name = $bean->first_name;

    if ($bean->googlelocal_check_c == 1 && $bean->googlelocal_sale_date_c == "") {
        $bean->googlelocal_sale_date_c = date("m/d/Y");
        $bean->googlelocal_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;

        $contracts = json_decode(file_get_contents(__DIR__ . "/echosign.json"), true);
        $contract_id = $contracts[$bean->googlelocal_contract_type_c];

        $result = $soaClient->get("/echosign/send", array(
            'email' => $email,
            'contractId' => $contract_id
        ));

        if ($result->response != "ERROR") {
            $bean->echosign_doc_id_c = $result->response;
            $logger->LogInfo("lead name:" . $name . " send echosign contract");
        } else {
            $bean->googlelocal_info_req_sent_c = "";
            $logger->LogInfo("sending echosign for google listing to " . $name . "failed");
        }

        $bean->save();
    }

    if ($bean->mobileweb_check_c == 1 && $bean->mobileweb_sale_date_c == "") {
        $bean->mobileweb_sale_date_c = date("m/d/Y");
        $bean->mobileweb_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;

        $result = $emailClient->get("/email/mobile-site-client-information-request?email=" . $email . "&name=" . $name . "&customerId=" . $id);

        if ($result->response == "OK") {
            $bean->mobileweb_info_req_sent_c = date("m/d/Y");
            $logger->LogInfo("lead name:" . $name . " was sent a mobile web details request");
        } else {
            $bean->mobileweb_info_req_sent_c = "";
            $logger->LogInfo("request for mobile web details from " . $name . "failed :" . $result->response);
        }

        $bean->save();
    }

    if ($bean->merch_check_c == 1 && $bean->marchent_sale_date_c == "") {
        $bean->marchent_sale_date_c = date("m/d/Y");
        $bean->marchent_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->save();
    }
}

?>