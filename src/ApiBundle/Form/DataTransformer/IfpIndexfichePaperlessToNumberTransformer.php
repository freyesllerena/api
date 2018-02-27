<?php

namespace ApiBundle\Form\DataTransformer;

use ApiBundle\Entity\IfpIndexfichePaperless;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IfpIndexfichePaperlessToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $document
     *
     * @return string
     */
    public function transform($document)
    {
        if (null === $document) {
            return '';
        }

        return $document->getIfpId();
    }

    /**
     * Transforme un Id en objet IfpIndexfichePaperless
     *
     * @param mixed $documentNumber L'id d'un document
     *
     * @return IfpIndexfichePaperless|null
     * @throws TransformationFailedException
     */
    public function reverseTransform($documentNumber)
    {
        if (!$documentNumber) {
            return '';
        }

        $document = $this->entityManager->getRepository('ApiBundle:IfpIndexfichePaperless')->find($documentNumber);

        if (null === $document) {
            throw new TransformationFailedException();
        }

        return $document;
    }
}
