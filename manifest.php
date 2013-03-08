<?php
global $sugar_config;

$manifest = array(
    'acceptable_sugar_versions' => array(
        'regex_matches' => array(
            0 => '6\.*',
        ),
    ),
    'acceptable_sugar_flavors' => array(
        0 => 'CE',
        1 => 'PRO',
        2 => 'ENT',
    ),
    'name' => 'Centra 1.0',
    'description' => 'Leads -> Centra integration',
    'is_uninstallable' => true,
    'author' => 'Centra',
    'published_date' => 'March 15, 2013',
    'version' => '1.0',
    'type' => 'module',
);

$installdefs = array(
    'id' => 'CG_Centra',
    'mkdir' => array(
        array('path' => 'custom/modules/Centra'),
    ),
    'copy' => array(
        array(
            'from' => '<basepath>/lib',
            'to' => 'custom/modules/Centra',
        ),
    ),
    'logic_hooks' => array(
        array(
            'module' => 'Leads',
            'hook' => 'before_save',
            'order' => 93,
            'description' => 'Centra hooks',
            'file' => 'custom/modules/Centra/hooks.php',
            'class' => 'CentraHooks',
            'function' => 'BeforeSave',
        ),
        array(
            'module' => 'Leads',
            'hook' => 'after_save',
            'order' => 93,
            'description' => 'Centra hooks',
            'file' => 'custom/modules/Centra/hooks.php',
            'class' => 'CentraHooks',
            'function' => 'AfterSave',
        ),
    ),
);

?>