<?php
require_once __DIR__ . '/soa-rest-client.php';
class NotesClient
{
    function AddNote($id, $message)
    {
        $soaFactory = new SoaRestClient();
        $client = $soaFactory->Get();

        $client->post('/crm/note', array(
            'id' => $id,
            'message' => $message
        ));
    }
}

?>