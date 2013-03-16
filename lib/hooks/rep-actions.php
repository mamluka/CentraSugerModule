<?php
require_once(__DIR__ . '/../core/core.php');

function UpdateWhoAssignedDeadStatus(&$bean)
{
    global $current_user;
    $noteClient = new NotesClient();

    if ($bean->status == "Dead" && $bean->dead_status_assigner_c == "") {
        $bean->dead_status_assigner_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->dead_status_assigned_date_c = date('Y-m-d H:i:s');

    }
}

function ClientStatusChange(&$bean)
{
    global $current_user;
    $noteClient = new NotesClient();

    if ($bean->status == "client" && $bean->rep_client_status_changed_c == "") {
        $bean->rep_client_status_changed_c = $current_user->first_name . " " . $current_user->last_name;
        $bean->client_status_change_date_c = date("m/d/Y");

    }
}

function UpdateWhoCancelled(&$bean)
{
    if ($bean->status == "cancelled" && $bean->cancelling_rep_c == "") {
        global $current_user;
        $noteClient = new NotesClient();

        $user = new User();
        $user->retrieve($bean->assigned_user_id);

        $bean->original_rep_c = $user->first_name . " " . $user->last_name;
        $bean->cancelling_rep_c = $current_user->first_name . " " . $current_user->last_name;

        $noteClient->AddNote($bean->id, "Status changed to cancelled by David MZ");
    }
}

?>