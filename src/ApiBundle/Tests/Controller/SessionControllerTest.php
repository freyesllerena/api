<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\FrameworkBundle\Client;

class SessionControllerTest extends DocapostWebTestCase
{

    /**
     * @var Client instance
     */
    private $client;

    public function setUp()
    {
        $this->initTests(
            [
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadConConfigData'
            ]
        );

        $this->client = static::makeClient();
    }

    /**
     * Teste la connection et la déconnection d'un utilisateur
     */
    public function testLogonAndLogoff()
    {
        $headers = array(
            'HTTP_Accept'      => 'application/json;version=1.0',
            'HTTP_codesource'  => 'ADP_PORTAIL',
            'HTTP_pac'         => 'TSI504',
            'HTTP_logoffurl'   => 'http://acme.com/logoff',
            'HTTP_bveurl'      => 'http://acme.com/bveurl',
            'HTTP_userid'      => 'MyUsrLogin01',
            'HTTP_profil'      => 'expertMS',
            'HTTP_numinstance' => '000001',
        );

        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_session_logon'),
            [],
            [],
            $headers,
            ''
        );

        $response = $this->client->getResponse();

        $this->assertStatusCode(302, $this->client);
        $this->assertEquals($response->headers->get('bveerreur'), 0);
        $this->assertEquals($response->headers->get('bvemessage'), 'OK');

        /* @var TokenInterface $userToken */
        $userToken = $this->getUserToken();
        $this->assertEquals($userToken->getPac(), 'TSI504');
        $this->assertEquals($userToken->getNumInstance(), '000001');

        $this->client->request('GET', $this->getUrl('api_apiv1_session_logoff'), [], [], $headers, '');
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals(
            '{"data":{"redirect_url":"http:\/\/www.google.com"}}',
            $this->client->getResponse()->getContent()
        );
        $this->assertNull($this->getUserToken());
    }

    /**
     * Teste le logon avec un utilisateur ARC qui n'existe pas
     */
    public function testLogonWithInexistingArcUser()
    {
        $headers = array(
            'HTTP_Accept'         => 'application/json;version=1.0',
            'HTTP_codesource'     => 'ADP_ARC',
            'HTTP_pac'            => 'TSI504',
            'HTTP_logoffurl'      => 'http://acme.com/logoff',
            'HTTP_bveurl'         => 'http://acme.com/bveurl',
            'HTTP_userid'         => 'InexistingArcUser',
            'HTTP_profil'         => 'expertms',
            'HTTP_numinstance'    => '000001',
            'HTTP_companyid'      => '11',
            'HTTP_softappid'      => '11',
            'HTTP_firstname'      => 'john',
            'HTTP_lastname'       => 'smith',
            'HTTP_email'          => 'john.smith@nowhere.com',
            'HTTP_client-context' => '....',
        );

        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_session_logon'),
            [],
            [],
            $headers,
            ''
        );

        $response = $this->client->getResponse();

        $this->assertStatusCode(302, $this->client);
        $this->assertEquals($response->headers->get('bveerreur'), 0);
        $this->assertEquals($response->headers->get('bvemessage'), 'OK');

        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_session_getauthorizations'),
            [],
            [],
            $headers,
            ''
        );

        $this->assertEquals(
            $this->tools->jsonMinify(
                '{
                    "data":{
                        "rightAnnotationDoc": 7,
                        "rightAnnotationDossier": 7,
                        "rightClasser": 7,
                        "rightCycleDeVie": 3,
                        "rightRechercheDoc": 7,
                        "rightRecycler": 7,
                        "rightUtilisateurs": 3,
                        "accessExportCel": true,
                        "accessImportUnitaire": true
                    }
                }'
            ),
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Teste le logon avec des headers invalides
     */
    public function testLogonWithInvalidHeaders()
    {
        $this->loadUsers();

        $headers = array(
            'HTTP_Accept'      => 'application/json;version=1.0',
            'HTTP_numinstance' => '000001',
        );

        $headers = array(
            'HTTP_codesource' => 'ADP_PORTAIL',
            'HTTP_pac' => 'TSI504',
            'HTTP_logoffurl' => 'http://logoff',
            'HTTP_bveurl' => 'http://bve',
            'HTTP_userid' => 'sbrion-msc'
        ) + $headers;

        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_pac', 2, 'absence du code pac');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_logoffurl', 5, 'Absence de logoffurl');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_bveurl', 6, 'Absence de bveurl');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_userid', 7, 'Absence du user id');
        $this->assertLogonReturnError(['HTTP_codesource' => 'azeazeap'] + $headers, 11, 'codesource inconnu');
        $this->assertLogonReturnError(['HTTP_pac' => '1234'] + $headers, 3, 'Code pac inconnu');
        $this->assertLogonReturnError(['HTTP_userid' => 'anonymous'] + $headers, 8, 'User id inexistant pour ce pac');
        $this->assertLogonReturnError(
            ['HTTP_userid' => 'not-active'] + $headers,
            9,
            'User id non autorisé à accéder au BVE pour ce pac'
        );
        $this->assertLogonReturnError(
            ['HTTP_userid' => 'disabled-membership'] + $headers,
            41,
            "Vous n'avez pas accès à cette instance"
        );
        $this->assertLogonReturnError(
            ['HTTP_userid' => 'not-started-membership'] + $headers,
            42,
            "Vous n'avez pas encore accès à cette instance"
        );
        $this->assertLogonReturnError(
            ['HTTP_userid' => 'expired-membership'] + $headers,
            43,
            "Vous n'avez plus accès à cette instance"
        );

        // Une connection avec un utilisateur automate par ADP_PORTAIL doit retourner une erreur
        $this->assertLogonReturnError([
                'HTTP_userid' => 'AutomateUser',
                'HTTP_password' => '5baa61e4c9b93f3f0682250b6cf8331b'
            ] + $headers,
            8,
            'User id inexistant pour ce pac'
        );

        $headers = array(
            'HTTP_codesource' => 'ADP_ARC',
            'HTTP_profil'     => 'expertia',
            'HTTP_companyid'  => 2,
            'HTTP_softappid'  => 2,
            'HTTP_lastname'   => 'mm',
            'HTTP_firstname'  => 'm',
            'HTTP_email'      => 'mm@doca.fr',
            'HTTP_client-context' => 'mm',
            'HTTP_userid' => 'anonymous',
        ) + $headers;

        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_profil', 18, 'Absence de profil');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_companyid', 12, 'Absence de companyid');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_softappid', 15, 'Absence de softappid');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_lastname', 15, 'Absence de lastname');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_firstname', 16, 'Absence de firstname');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_email', 17, 'Absence de email');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_client-context', 19, 'Absence de client-context');
        $this->assertLogonReturnError(['HTTP_profil' => 'azazaza'] + $headers, 10, 'Profil non géré');

        // Une connection avec un utilisateur automate par ADP_PORTAIL doit retourner une erreur
        $this->assertLogonReturnError([
                'HTTP_userid' => 'AutomateUser',
                'HTTP_password' => '5baa61e4c9b93f3f0682250b6cf8331b'
            ] + $headers,
            8,
            'User id inexistant pour ce pac'
        );
    }

    /**
     * Teste le logon avec un utilisateur automate
     */
    public function testLogonWithAutomateUser()
    {
        $headers = array(
            'HTTP_Accept'      => 'application/json;version=1.0',
            'HTTP_numinstance' => '000001',
            'HTTP_pac'         => 'TSI504',
            'HTTP_codesource'  => 'ADP_AUTOMATE',
            'HTTP_userid'      => 'AutomateUser',
            'HTTP_password'    => '5baa61e4c9b93f3f0682250b6cf8331b',
        );

        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_pac', 2, 'absence du code pac');
        $this->assertLogonWithMissingHeaderReturnError($headers, 'HTTP_password', 99, 'Absence du password');
        $this->assertLogonReturnError(['HTTP_pac' => '1234'] + $headers, 3, 'Code pac inconnu');
        $this->assertLogonReturnError(['HTTP_userid' => 'anonymous'] + $headers, 8, 'User id inexistant pour ce pac');
        $this->assertLogonReturnError(['HTTP_password' => '1234'] + $headers, 8, 'User id inexistant pour ce pac');

        $this->assertLogonSuccess($headers, 200);
    }

    /**
     * Renvoit le contexte utilisateur
     * @return null|TokenInterface
     */
    protected function getUserToken()
    {
        return $this->client->getContainer()->get('security.token_storage')->getToken();
    }

    /**
     * Vérifie que le logon renvoit une erreur avec les headers spécifiés
     *
     * @param $headers
     * @param $codeError
     * @param $msgError
     */
    protected function assertLogonReturnError($headers, $codeError, $msgError)
    {
        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_session_logon'),
            [],
            [],
            $headers,
            ''
        );

        $response = $this->client->getResponse();

        if ($response->getStatusCode() == 500) {
            print $response->getContent();
        }

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals(
            array($response->headers->get('bveerreur'), $response->headers->get('bvemessage')),
            array($codeError, $msgError)
        );
    }

    /**
     * Vérifie que le logon renvoit une erreur avec un header manquant
     *
     * @param $headers
     * @param $missingHeader
     * @param $codeError
     * @param $msgError
     */
    protected function assertLogonWithMissingHeaderReturnError($headers, $missingHeader, $codeError, $msgError)
    {
        unset($headers[$missingHeader]);
        $this->assertLogonReturnError($headers, $codeError, $msgError);
    }

    /**
     * Vérifie que le logon avec les headers renseignés en paramètre est un succés
     *
     * @param array $headers
     * @param int $expectedCode
     */
    protected function assertLogonSuccess(array $headers, $expectedCode = 302)
    {
        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_session_logon'),
            [],
            [],
            $headers,
            ''
        );

        $response = $this->client->getResponse();

        $this->assertStatusCode($expectedCode, $this->client);
        $this->assertEquals($response->headers->get('bveerreur'), 0);
        $this->assertEquals($response->headers->get('bvemessage'), 'OK');
    }

    /**
     * Charge des utilisateurs dans doctrine
     */
    protected function loadUsers()
    {
        $abstract = new UsrUsers();
        $abstract->setUsrActif(true)
            ->setUsrStatus('ACTIV')
            ->setUsrBeginningdate(new \DateTime('-2 months'))
            ->setUsrEndingdate(new \DateTime('+2 months'))
            ->setUsrRaison('???')
            ->setUsrConfidentialite('???')
            ->setUsrAdresseMail('???')
            ->setUsrCommentaires('???')
            ->setUsrNom('???')
            ->setUsrPrenom('???')
            ->setUsrAdressePost('???')
            ->setUsrTel('???')
            ->setUsrRsoc('mlkmlk')
            ->setUsrAdresseMailCciAuto('mkmlk')
            ->setUsrTypeHabilitation('mlkmlk')
            ->setUsrOldPasswordList('???');

        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $user1 = clone ($abstract);
        $user1->setUsrLogin('sbrion-msc');
        $entityManager->persist($user1);

        $user2 = clone ($abstract);
        $user2->setUsrLogin('not-active')
            ->setUsrActif(0);
        $entityManager->persist($user2);

        $user3 = clone ($abstract);
        $user3->setUsrLogin('disabled-membership')
            ->setUsrStatus('INACTIV');
        $entityManager->persist($user3);

        $user4 = clone ($abstract);
        $user4->setUsrLogin('not-started-membership')
            ->setUsrBeginningdate(new \DateTime('+1 month'));
        $entityManager->persist($user4);

        $user5 = clone ($abstract);
        $user5->setUsrLogin('expired-membership')
            ->setUsrEndingDate(new \DateTime('-1 month'));
        $entityManager->persist($user5);

        $entityManager->flush();
    }
}
