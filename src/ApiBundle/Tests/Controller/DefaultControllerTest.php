<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;

class DefaultControllerTest extends DocapostWebTestCase
{
    /**
     * Test que la signature / renvoi bien une erreur 404
     */
    public function testIndex()
    {
        $client = static::makeClient();
        $client->request('GET', '/', [], [], $this->mandatoryHeaders);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
