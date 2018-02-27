<?php

namespace ApiBundle\Listener;

use ApiBundle\Entity\FolFolder;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;

class GenericEntityListener
{
    /**
     * @var Container
     */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FolFolder) {
            // Mise à jour de la date d'accès au dossier
            if ($folderUser = $this->container->get('api.manager.folder')->getFolderUserLink(
                $entity,
                $this->container->get('security.token_storage')->getToken()->getUser()->getUsrLogin()
            )
            ) {
                $folderUser->setFusDateAcces(new \DateTime());
                $entityManager = $args->getEntityManager();
                $entityManager->persist($folderUser);
                $entityManager->flush();
            }
        }
    }
}
