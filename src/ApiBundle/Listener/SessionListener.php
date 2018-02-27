<?php

namespace ApiBundle\Listener;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Security\UserToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SessionListener
{

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * SessionListener constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->session = $container->get('session');
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Vérifie si la session est expirée
        $sessionConfig = $this->container->getParameter('session.storage.options');
        if ($this->session->isStarted() &&
            time() - $this->session->getMetadataBag()->getLastUsed() > $sessionConfig['gc_maxlifetime']) {
            $response =  new Response('Expired Session', 401);
            $event->setResponse($response);
        }
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // Place la langue par défaut de l'utilisateur dans un cookie
        $token = $this->container->get('security.token_storage')->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            $usrLang = $token->getUser()->getUsrLangue();
            $usrLang = preg_match('/[a-z]{2}_[A-Z]{2}/', $usrLang)? $usrLang : $this->container->getParameter('locale');
        } else {
            $usrLang = $this->container->getParameter('locale');
        }
        $cookieLang = new Cookie('lang', $usrLang, 0, '/', null, false, false);
        $event->getResponse()->headers->setCookie($cookieLang);

        // Retourne le code pac et le num instance dans la réponse
        if ($token && $token instanceof UserToken) {
            $event->getResponse()->headers->set('pac', $token->getAttribute('pac'));
            $event->getResponse()->headers->set('numinstance', $token->getAttribute('numinstance'));
        }
    }

    /**
     * Gestion des exceptions
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof AuthenticationException) {
            $response = new DocapostJsonResponse([
                'msg' => [
                    'level' => DocapostController::MSG_LEVEL_ERROR,
                    'infos' => $exception->getMessage()
                ]
            ], 403);

            $event->setResponse($response);
        }
    }

    /**
     * Rafraichit le contexte utilisateur
     */
    public function refreshUserToken()
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if ($token && $token->getUser() instanceof UsrUsers) {
            $userRepository = $this->container->get('doctrine')->getManager()->getRepository('ApiBundle:UsrUsers');
            $user = $userRepository->findOneByUsrLogin($token->getUser()->getUsrLogin());
            $token->setUser($user);
        }
    }

    /**
     * Garde la session active
     */
    public function keepSessionAlive()
    {
        $this->container->get('session')->getMetadataBag()->stampNew(1800);
    }
}
