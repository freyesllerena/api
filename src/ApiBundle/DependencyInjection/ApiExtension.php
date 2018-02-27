<?php

namespace ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ApiExtension extends Extension
{
    /**
     * @codeCoverageIgnore
     * @inheritDoc
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('metadata.yml');
        $loader->load('authorizations.yml');
        $loader->load('user_preferences.yml');
    }
}
