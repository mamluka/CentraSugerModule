<?php
class ConfigManager
{
    private $decoded_json;

    function __construct()
    {
        $config_file = __DIR__ . '/config.json';
        $json = file_get_contents($config_file);
        $this->decoded_json = json_decode($json);
    }

    function Get()
    {
        return $this->decoded_json;
    }
}

?>