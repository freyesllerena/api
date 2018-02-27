<?php

namespace VisBundle\Tests\Service;

use GuzzleHttp\Psr7\Response;
use VisBundle\Service\VisClientService;

class VisClientServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Teste l'instanciation du service
     */
    public function testConstruct() {
        new VisClientService("http://127.0.0.1"); // Ne doit déclencher aucune erreur

        try {
            new VisClientService(new \stdClass());
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            // Une erreur doit se déclencher
        }
    }

    /**
     * Teste l'execution d'un script
     */
    public function testExecuteScript()
    {
        $expected = new Response();

        $guzzle = $this->getMock('GuzzleHttp\ClientInterface');
        $guzzle->method('request')
            ->will($this->returnValue($expected));

        $vis = new VisClientService($guzzle);
        $actual = $vis->executeScript('VIS_GET_FILE:....');

        $this->assertEquals($expected, $actual);
    }
}