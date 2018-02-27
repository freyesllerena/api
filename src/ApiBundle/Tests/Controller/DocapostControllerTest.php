<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Controller\DocapostController;

class DocapostControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testDocapostController()
    {
        $expectedResponse = '{"data":{"myAttribute":"myValue"}}';
        $controller = new DocapostController();

        $myObject = new \stdClass();
        $myObject->myAttribute = 'myValue';

        $response = $controller->export($myObject);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), $expectedResponse);
    }

    public function testEmptyData()
    {
        $expectedResponse = '';
        $controller = new DocapostController();

        $myData = '';

        $response = $controller->export($myData);

        $this->assertEquals($response->getStatusCode(), 204);
        $this->assertEquals($response->getContent(), $expectedResponse);
    }

    public function testStringData()
    {
        $controller = new DocapostController();

        $myData = 'myString';
        try {
            $controller->export($myData);
            $this->fail('Exception non vérifiée');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'unexpected character');
        }
    }

    public function testStringJSONData()
    {
        $expectedResponse = '{"data":{"myKey":"myValue"}}';
        $controller = new DocapostController();

        $myData = '{"myKey":"myValue"}';

        $response = $controller->export($myData);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), $expectedResponse);
    }

    public function testMessageJsonResponse()
    {
        $expectedResponse = preg_replace('/\s+/', '', '
            {
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "myCodeError",
                        "values": [],
                        "fieldName": "myFieldAttribute"
                    }, {
                        "code": "myCodeErrorWithRemplacements",
                        "values": {
                            "__myFirstRemplacement__": ["myValue1", "myValue2"],
                            "__mySecondRemplacement__": "myValue3"
                        },
                        "fieldName": ""
                    }]
                }
            }
        ');
        $controller = new DocapostController();

        $controller->addResponseMessage('myCodeError', '', 'myFieldAttribute');

        $translations['__myFirstRemplacement__'] = [
            'myValue1',
            'myValue2'
        ];
        $translations['__mySecondRemplacement__'] = 'myValue3';
        $controller->addResponseMessage('myCodeErrorWithRemplacements', $translations);
        $response = $controller->messageJsonResponse($controller::MSG_LEVEL_ERROR, 400);

        $this->assertEquals($response->getStatusCode(), 400);
        $this->assertEquals($response->getContent(), $expectedResponse);
    }

    public function testValidateWSParametersFalse()
    {
        $expectedResponse = preg_replace('/\s+/', '', '
            {
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__": "myId"},
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterIsEmpty",
                        "values": {"__parameter__": "myInteger"},
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsNotBoolean",
                        "values": {
                            "__parameter__": "myIsBoolean",
                            "__value__": "notABoolean"
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsIncorrect",
                        "values": {
                            "__parameter__": "myValueRegex",
                            "__value__": "notANumericValue"
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsNotAnArray",
                        "values": {
                            "__parameter__": "myArray3",
                            "__value__": "notAnArray"
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsNotAnInteger",
                        "values": {
                            "__parameter__": 0,
                            "__value__": "myValue"
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__": 1},
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsNotAString",
                        "values": {
                            "__parameter__": "myString",
                            "__value__": ["notAString"]
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsNotAnInteger",
                        "values": {
                            "__parameter__": "myInteger",
                            "__value__": "notAnInteger"
                        },
                        "fieldName": ""
                    }, {
                        "code": "errDocapostControllerParameterTypeIsIncorrect",
                        "values": {
                            "__parameter__": "myRegex",
                            "__value__": "123"
                        },
                        "fieldName": ""
                    }]
                }
            }
        ');
        $controller = new DocapostController();
        $checks = [
            'myId' => 'int',
            'myArray' => ['myInteger' => 'int', 'myIsBoolean' => 'bool'],
            'myArray2' => ['myValueRegex' => '[\d+]'],
            'myArray3' => ['int'],
            'myArray4' => ['int', 'bool'],
            'myString' => 'isString',
            'myInteger' => 'int',
            'myRegex' => '[A-Za-z]'
        ];
        $datas = [
            'myArray' => ['myInteger' => '', 'myIsBoolean' => 'notABoolean'],
            'myArray2' => ['myValueRegex' => 'notANumericValue'],
            'myArray3' => 'notAnArray',
            'myArray4' => ['myValue'],
            'myString' => ['notAString'],
            'myInteger' => 'notAnInteger',
            'myRegex' => '123'
        ];
        $this->assertEquals(false, $controller->validateWSParameters($datas, $checks));
        $response = $controller->messageJsonResponse($controller::MSG_LEVEL_ERROR, 400);
        $this->assertEquals($response->getStatusCode(), 400);
        $this->assertEquals($response->getContent(), $expectedResponse);
    }

    public function testValidateWSParametersTrue()
    {
        $controller = new DocapostController();
        $checks = [
            'myId' => null,
            'myArray' => ['myInteger' => 'int', 'myIsBoolean' => 'bool'],
            'myArray2' => ['int'],
            'myString' => '\w+',
        ];
        $datas = [
            'myId' => 5,
            'myArray' => ['myInteger' => 100, 'myIsBoolean' => true],
            'myArray2' => [20, 30, 40, 50],
            'myString' => 'isString'
        ];
        $this->assertEquals(true, $controller->validateWSParameters($datas, $checks));
    }
}
