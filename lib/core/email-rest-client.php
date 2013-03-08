<?php
require_once __DIR__ . '/pest.php';
require_once __DIR__ . '/config-manager.php';

class EmailRestClient
{
    private $client;

    function __construct()
    {
        $configManager = new ConfigManager();
        $config = $configManager->Get();

        $this->client = new Pest($config->CentraAppsApiBaseUrl);
    }

    function Get()
    {
        return $this->client;
    }
}

?>