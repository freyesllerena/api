<?php

namespace ApiBundle\Tests\Manager;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Créé un simulacre d'un conteneur
     *
     * @param array $services Un tableau indexé des services du conteneur
     * @param array $services Un tableau indexé des paramètres du conteneur
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createContainerMock(array $services = array(), array $parameters = array())
    {
        foreach ($services as $name => $instance) {
            $services[$name] = array($name, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $instance);
        }

        foreach ($parameters as $name => $value) {
            $parameters[$name] = array($name, $value);
        }

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->method('get')
            ->will($this->returnValueMap($services));
        $container->method('getParameter')
            ->will($this->returnValueMap($parameters));

        return $container;
    }

    /**
     * Créé un simulacre de Doctrine
     *
     * @param array $repositories Un tableau indexé des repositories du service Doctrine
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RegistryInterface
     */
    protected function createDoctrineMock(array $repositories = array())
    {
        foreach ($repositories as $name => $instance) {
            $repositories[$name] = array($name, $instance);
        }

        $doctrine = $this->getMock('Symfony\Bridge\Doctrine\RegistryInterface');
        $entityManager = $this->getMock('Doctrine\ORM\EntityManagerInterface');

        $doctrine->method('getManager')
            ->will($this->returnValue($entityManager));

        $entityManager->method('getRepository')
            ->will($this->returnValueMap($repositories));

        return $doctrine;
    }
}
