<?php

namespace ApiBundle\Tests\Listener;

use ApiBundle\Listener\DynamicDatabaseConnectionListener;
use ApiBundle\Tests\Manager\BaseManagerTest;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DynamicDatabaseConnectionListenerTest extends BaseManagerTest
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var DynamicDatabaseConnectionListener
     */
    protected $listener;

    public function setUp()
    {

        $request = new Request();
        $request->headers->set('numinstance', '000001');

        $this->container = $this->createContainerMock(array(
            'request' => $request,
            'kernel' => $this->getMock('Symfony\Component\HttpKernel\KernelInterface'),
            'memcache.default' => $this->getMock('\Lsw\MemcacheBundle\Cache\MemcacheInterface'),
            'security.token_storage' => new TokenStorage()
        ));

        $this->connection = new Connection(
            array(
                'driver' => 'pdo_sqlite',
                'path' => ':memory:',
                'memory' => true,
            ),
            $this->getMock('Doctrine\DBAL\Driver'),
            null,
            null
        );

        $this->listener = new DynamicDatabaseConnectionListener(
            $this->container,
            $this->connection
        );
    }

    public function testIkpCaller()
    {
        $this->container->get('memcache.default')
            ->expects($this->atLeast(1))
            ->method('get')
            ->with($this->equalTo('API::000001::ikp'))
            ->will($this->returnValue(json_encode(array(
                'mysql_server' => 'mysql',
                'mysql_database' => '0.0.0.0',
                'mysql_login' => 'johndoe',
                'mysql_password' => '1234'
            ))));

        $this->listener->onKernelRequest();


    }
}
