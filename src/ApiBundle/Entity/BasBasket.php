<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelTypeFolderType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BasBasket
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\BasBasketRepository")
 */
class BasBasket extends BfoBaseFolder
{

    /**
     * Get folType
     *
     * @return string
     */
    public function getFolType()
    {
        return EnumLabelTypeFolderType::BASKET_TYPE;
    }
}
