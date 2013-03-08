<?php
require_once __DIR__ . '/pest.php';
require_once __DIR__ . '/config-manager.php';

class SoaRestClient
{
    private $client;

    function __construct()
    {
        $configManager = new ConfigManager();
        $config = $configManager->Get();

        $this->client = new Pest($config->SoaApiBaseUrl);
    }

    function Get()
    {
        return $this->client;
    }
}

?>