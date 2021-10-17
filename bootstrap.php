<?php
// bootstrap.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use DI\Container;

require_once __DIR__ . '/vendor/autoload.php';

$container = new Container();
$container->set("settings", require __DIR__ . '/settings.php');

$container->set(EntityManager::class, function (Container $container): EntityManager {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container->get('settings')['doctrine']['metadata_dirs'],
        $container->get('settings')['doctrine']['dev_mode']
    );

    return EntityManager::create(
        $container->get('settings')['doctrine']['connection'],
        $config
    );
});

return $container;