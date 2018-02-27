<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\BasBasket;

class BasBasketTest extends \PHPUnit_Framework_TestCase
{
    public function testBasBasketEntity()
    {
        $myObject = new BasBasket();

        // Test Getter
        $this->assertEquals('BAS', $myObject->getFolType());
    }
}
