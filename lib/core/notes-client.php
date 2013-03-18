<?php
require_once __DIR__ . '/soa-rest-client.php';
class NotesClient
{
    function AddNote($id, $message)
    {
        $soaFactory = new SoaRestClient();
        $client = $soaFactory->Get();

        $logger = new KLogger ("centra-logs", KLogger::DEBUG);

        $client->post('/crm/note', array(
            'id' => $id,
            'message' => $message
        ));

        $logger->LogInfo("Note " . $message . " sent");
    }
}

?>