<?php
require_once(__DIR__ . '/../core/core.php');

function UpdateSoldServicesDates(&$bean)
{
    global $current_user;

    $soaFactory = new SoaRestClient();
    $soaClient = $soaFactory->Get();

    $logger = new KLogger ("centra-logs", KLogger::DEBUG);

    $notes = new NotesClient();

    $email = $bean->email1;
    $id = $bean->id;
    $name = $bean->first_name;

    if ($bean->googlelocal_check_c == 1 && $bean->googlelocal_sale_date_c == "") {
        $bean->googlelocal_sale_date_c = date("m/d/Y");
        $bean->googlelocal_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->status = "client";

        $contracts = json_decode(file_get_contents(__DIR__ . "/echosign.json"), true);
        $contract_type = $bean->googlelocal_contract_type_c;

        if ($contract_type == "") {
            sugar_die("You must select a contract");
        }

        $contract_id = $contracts[$contract_type];

        $logger->LogInfo("The selected contract for: " . $bean->googlelocal_contract_type_c . " was: " . $contract_id);

        $result = $soaClient->get("/echosign/send", array(
            'email' => $email,
            'contractId' => $contract_id
        ));

        $logger->LogInfo("The echosign API returned: " . $result);

        if (strlen($result) == 14) {
            $bean->echosign_doc_id_c = $result;
            $logger->LogInfo("lead name:" . $name . " send echosign contract");

            $notes->AddNote($id, "Google local listing was sold by " . current_user());
        } else {
            $bean->googlelocal_info_req_sent_c = "";
            $logger->LogInfo("sending echosign for google listing to " . $name . "failed");
        }


    }

    if ($bean->mobileweb_check_c == 1 && $bean->mobileweb_sale_date_c == "" && $bean->googlelocal_check_c == 0) {
        $bean->mobileweb_sale_date_c = date("m/d/Y");
        $bean->mobileweb_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->status = "client";

        $contracts = json_decode(file_get_contents(__DIR__ . "/echosign.json"), true);
        $contract_type = $bean->mobileweb_contract_type_c;

        if ($contract_type == "") {
            sugar_die("You must select a contract");
        }

        $contract_id = $contracts[$contract_type];

        $logger->LogInfo("The selected contract for: " . $bean->mobileweb_contract_type_c . " was: " . $contract_id);

        $result = $soaClient->get("/echosign/send", array(
            'email' => $email,
            'contractId' => $contract_id
        ));

        $logger->LogInfo("The echosign API returned: " . $result);

        if (strlen($result) == 14) {
            $bean->echosign_doc_id_c = $result;
            $logger->LogInfo("lead name:" . $name . " was sent a mobile web contract");

            $notes->AddNote($id, "Mobile web was sold by " . current_user());
        } else {
            $logger->LogInfo("mobile web contract for " . $name . "failed :" . $result);
        }


    }

    if ($bean->merch_check_c == 1 && $bean->marchent_sale_date_c == "") {
        $bean->marchent_sale_date_c = date("m/d/Y");
        $bean->marchent_sale_rep_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->status = "client";

    }
}

?>