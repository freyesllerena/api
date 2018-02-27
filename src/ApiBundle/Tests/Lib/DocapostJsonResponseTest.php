<?php

namespace ApiBundle\Tests\Lib;

use ApiBundle\DocapostJsonResponse;

class DocapostJsonResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test l'instanciation du constructeur sans données
     */
    public function testConstructorWithoutData()
    {
        $myJsonResponse = new DocapostJsonResponse();
        $this->assertEquals(204, $myJsonResponse->getStatusCode());
    }

    /**
     * Test l'instanciation du constructeur avec un tableau de données
     */
    public function testConstructorWithDataArray()
    {
        $myData = array('myContent' => 'my content data');
        $myJsonResponse = new DocapostJsonResponse($myData);
        $this->assertEquals(200, $myJsonResponse->getStatusCode());
    }

    /**
     * Test l'instanciation du constructeur avec des données dans une chaine
     */
    public function testConstructorWithDataString()
    {
        $myData = 'my content data';
        try {
            new DocapostJsonResponse($myData);
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }

    }

    /**
     * Test le retour du setData
     */
    public function testSetData()
    {
        $myData = array('myAttribute' => 'myValue');
        $myExpectedResponse = '{"data":{"myAttribute":"myValue"}}';
        $myJsonResponse = new DocapostJsonResponse($myData, 200);

        $response = $myJsonResponse->getContent();
        $this->assertEquals($myExpectedResponse, $response);
    }

    /**
     * Test si une exception est retournée
     */
    public function testSetDataException()
    {
        $errorPass = false;
        // Test avec une chaine de caractères
        try {
            new DocapostJsonResponse('String is not permitted', 200);
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }

        // test avec un tableau contenant des caractères non reconnus
        try {
            $arrayData = array('myAttribute' => hex2bin("A0"));
            new DocapostJsonResponse($arrayData, 200);
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $errorPass = true;
        }

        if (!$errorPass) {
            $this->fail('Json error not detected !');
        }
    }
}
