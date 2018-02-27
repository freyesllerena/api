<?php

namespace ApiBundle\Tests\Listener;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Listener\SessionListener;
use ApiBundle\Security\UserToken;
use ApiBundle\Tests\Manager\BaseManagerTest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SessionListenerTestextends extends BaseManagerTest
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var SessionListener
     */
    protected $listener;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->doctrine = $this->createDoctrineMock(array(
            'ApiBundle:UsrUsers' => $this->getMock('ApiBundle\Repository\UsrUsersRepositoryInterface'),
        ));

        $this->container = $this->createContainerMock(array(
            'doctrine' => $this->doctrine,
            'security.token_storage' => new TokenStorage(),
        ), array(
            'locale' => 'fr_FR'
        ));

        $this->listener = new SessionListener($this->container);
    }

    /**
     * Gestion de la réponse
     */
    public function testOnKernelResponse()
    {
        $userToken = new UserToken('johndoe', array(
            'pac' => 'TSI504',
            'numinstance' => '000001',
        ));
        $this->container->get('security.token_storage')->setToken($userToken);

        // Teste la langue du cookie lorsque l'utilisateur n'est pas dans la base
        $request = new Request();
        $response = new Response();
        $event = $this->createKernelResponseEvent($request, $response);
        $this->listener->onKernelResponse($event);
        $cookies = $this->getCookiesResponse($response);
        $this->assertEquals('fr_FR', $cookies['lang']->getValue());

        // Teste la langue du cookie lorsque la langue de l'utilisateur n'est pas au format iso
        $user = new UsrUsers();
        $user->setUsrLogin('johndoe');
        $user->setUsrLangue('American');
        $userToken->setUser($user);
        $this->listener->onKernelResponse($event);
        $cookies = $this->getCookiesResponse($response);
        $this->assertEquals('fr_FR', $cookies['lang']->getValue());

        // Teste la langue du cookie lorsque la langue de l'utilisateur est au format iso
        $user->setUsrLangue('us_EN');
        $this->listener->onKernelResponse($event);
        $cookies = $this->getCookiesResponse($response);
        $this->assertEquals('us_EN', $cookies['lang']->getValue());

        // Teste si le code pac et num instance sont retournés dans la réponse
        $this->listener->onKernelResponse($event);
        $this->assertEquals('TSI504', $response->headers->get('pac'));
        $this->assertEquals('000001', $response->headers->get('numinstance'));

    }

    /**
     * Gestion des exceptions
     */
    public function testOnKernelException()
    {
        // Test sur une erreur quelconque
        $request = new Request();
        $exception = new \Exception("Une erreur est survenue");
        $event = $this->createKernelExceptionEvent($request, $exception);
        $this->listener->onKernelException($event);
        $this->assertNull($event->getResponse());

        // Test sur une erreur d'authentification
        $exception = new AuthenticationException("Erreur d'authentification");
        $event = $this->createKernelExceptionEvent($request, $exception);
        $this->listener->onKernelException($event);
        $this->assertEquals($event->getResponse()->getStatusCode(), 403);
    }

    /**
     * Réactualisation de l'user token
     */
    public function testRefreshUserToken()
    {
        $detachedUser = new UsrUsers();
        $detachedUser->setUsrLogin('johndoe');

        $userToken = new UserToken($detachedUser);
        $this->container->get('security.token_storage')->setToken($userToken);

        $doctrineUser = new UsrUsers();
        $doctrineUser->setUsrLogin('johndoe');
        $this->doctrine->getManager()->getRepository('ApiBundle:UsrUsers')
            ->method('findOneByUsrLogin')
            ->with($this->equalTo('johndoe'))
            ->will($this->returnValue($doctrineUser));

        $this->listener->refreshUserToken();
        $this->assertSame($userToken->getUser(), $doctrineUser);
    }

    /**
     * Créé un évenement kernel.response
     *
     * @param Request $request
     * @param Response $response
     * @return FilterResponseEvent
     */
    protected function createKernelResponseEvent(Request $request, Response $response)
    {
        $kernel = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');
        return new FilterResponseEvent(
            $kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );
    }

    /**
     * Crée un évenement kernel.exception
     *
     * @param Request $request
     * @param \Exception $exception
     * @return GetResponseForExceptionEvent
     */
    protected function createKernelExceptionEvent(Request $request, \Exception $exception)
    {
        $kernel = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');
        return new GetResponseForExceptionEvent(
            $kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $exception
        );
    }

    /**
     * Renvoit les cookies d'une réponse
     *
     * @param Response $response
     * @return array
     */
    protected function getCookiesResponse(Response $response)
    {
        $results = array();
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            $results[$cookie->getName()] = $cookie;
        }
        return $results;
    }
}

