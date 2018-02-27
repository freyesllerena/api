<?php

namespace InteruploadBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class InteruploadExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        // configuration sur config_dev.yml et sur config_test.yml
        $container->setParameter('interupload.url_wsdl', $config['url_wsdl']);
        $container->setParameter('interupload.iup_url_ticket', $config['iup_url_ticket']);
        $container->setParameter('interupload.base_path_upload', $config['base_path_upload']);
        $container->setParameter('interupload.debug', $config['debug']);
        // configutation sur config.yml
        $container->setParameter('interupload.encoding', $config['encoding']);
        $container->setParameter('interupload.cache_wsdl', $config['cache_wsdl']);
        $container->setParameter('interupload.bundle_name_entities', $config['bundle_name_entities']);
        $container->setParameter('interupload.table_ticket_name', $config['table_ticket_name']);
        $container->setParameter('interupload.table_configuration_name', $config['table_configuration_name']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}