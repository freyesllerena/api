<?php

namespace ApiBundle\Tests\Manager;

use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Exception\LogonException;
use ApiBundle\Manager\ClassificationPlanManagerInterface;
use ApiBundle\Manager\UserManager;
use ApiBundle\Repository\PdaProfilDefAppliRepositoryInterface;
use ApiBundle\Repository\UsrUsersRepositoryInterface;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserManagerTest extends BaseManagerTest
{

    /**
     * @var UserManager
     */
    protected $manager;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $container;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->doctrine = $this->createDoctrineMock(array(
            'ApiBundle:UsrUsers' => $this->getMock('ApiBundle\Repository\UsrUsersRepositoryInterface'),
            'ApiBundle:PdaProfilDefAppli' => $this->getMock('ApiBundle\Repository\PdaProfilDefAppliRepositoryInterface')
        ));

        $this->container = $this->createContainerMock(array(
            'doctrine.orm.entity_manager' => $this->doctrine->getManager(),
            'security.token_storage' => new TokenStorage(),
            'api.manager.classification_plan' => $this->getMock('ApiBundle\Manager\ClassificationPlanManagerInterface'),
            'api.repository.config' => new \ArrayObject(array(
                'type_abo_visu' => 2,
                'pac' => 'TSI504'
            )),
        ));

        $this->manager = new UserManager($this->container);
    }

    /**
     * Teste le plan de classification filtré lorsque l'utilisateur n'est pas authentifié
     */
    public function testGetFilteredClassificationPlanForCurrentUserWhenUserIsNotAuthenticated()
    {
        $this->assertEquals(array(), $this->manager->getFilteredClassificationPlanForCurrentUser('DES'));
    }

    /**
     * Teste le plan de classification filtré lorsque l'utilisateur est authentifié et existe dans en base de données
     */
    public function testGetFilteredClassificationPlanForCurrentUserWhenUserIsAuthenticatedAndExists()
    {
        $items = array(
            'complete' => [
                (object) [
                    'id' => 1,
                    'text' => 'Plan de classement complet',
                ]
            ],
            'filteredByApplication' => [
                (object) [
                    'id' => 1,
                    'text' => 'Plan de classement avec filtres applicatifs',
                ]
            ],
            'filteredByApplicationAndMembership' =>  [
                (object) [
                    'id' => 1,
                    'text' => 'Plan de classement avec filtres applicatifs et abonnements',
                ]
            ],
        );

        $user = (new UsrUsers())->setUsrLogin('johndoe');

        $this->getTokenStorage()->setToken(
            new UserToken($user, array(), '')
        );

        $this->getPdaRepository()->method('findByUser')
            ->will($this->returnValueMap(array(
                array($user, [ (new PdaProfilDefAppli())->setPdaRefBve('D501090|D501040') ] ),
            )));

        $this->getClassificationPlanManager()->method('buildCompleteClassificationPlan')
            ->will($this->returnValueMap(array(
                array('DES', $items['complete'])
            )));

        $this->getClassificationPlanManager()->method('filterClassificationPlanByApplication')
            ->will($this->returnValueMap(array(
                array($items['complete'], array('D501090', 'D501040'), $items['filteredByApplication'])
            )));

        $this->getClassificationPlanManager()->method('filterClassificationPlanByMembershipLevel')
            ->will($this->returnValueMap(array(
                array($items['filteredByApplication'], 2, $items['filteredByApplicationAndMembership'])
            )));

        $this->assertEquals(
            $items['filteredByApplicationAndMembership'],
            $this->manager->getFilteredClassificationPlanForCurrentUser('DES')
        );
    }

    /**
     * Teste le plan de classification filtré lorsque l'utilisateur est authentifié mais n'existe pas en base de données
     */
    public function testGetFilteredClassificationPlanForCurrentUserWhenUserIsAuthenticatedAndDoesNotExist()
    {

        $items = array(
            'complete' => [
                (object) [
                    'id' => 1,
                    'text' => 'Plan de classement complet',
                ]
            ],
            'filteredByMembership' => [
                (object) [
                    'id' => 1,
                    'text' => 'Plan de classement avec filtres applicatifs et abonnements',
                ]
            ],
        );

        $token = new UserToken('non_existing_user', array(), '');
        $this->getTokenStorage()->setToken($token);

        $this->getClassificationPlanManager()->method('buildCompleteClassificationPlan')
            ->will($this->returnValueMap(array(
                array('DES', $items['complete'])
            )));

        $this->getClassificationPlanManager()->method('filterClassificationPlanByMembershipLevel')
            ->will($this->returnValueMap(array(
                array($items['complete'], 2, $items['filteredByMembership'])
            )));

        $this->assertEquals(
            $items['filteredByMembership'],
            $this->manager->getFilteredClassificationPlanForCurrentUser('DES')
        );
    }

    /**
     * Teste logon avec des headers valides
     *
     * @dataProvider getLogonWithValidData
     *
     * @param $data
     * @throws \ApiBundle\Exception\LogonException
     */
    public function testAuthenticateWithValidData($data)
    {
        $this->getUsrRepository()->method('findOneByUsrLogin')
            ->will($this->returnValueMap(array(
                array('sbrion-msc', $this->createValidUser('sbrion-msc')),
            )));

        $this->manager->authenticate($data);

    }

    /**
     * @return array
     */
    public function getLogonWithValidData()
    {
        return array(
            array(['codesource' => 'ADP_PORTAIL', 'pac' => 'TSI504', 'logoffurl' => 'http://logoff',
                'bveurl' => 'http://bve', 'userid' => 'sbrion-msc']),
            array(['codesource' => 'ADP_ARC', 'pac' => 'TSI504', 'logoffurl' => 'http://logoff9',
                'bveurl' => 'http://bve8', 'userid' => 'sbrion-msc', 'profil' => 'expertia', 'companyid' => 2,
                'softappid' => 2, 'lastname' => 'mm', 'firstname' => 'm', 'email' => 'mm@doca.fr',
                'client-context' => 'mm']),
            array(['codesource' => 'ADP_ARC', 'pac' => 'TSI504', 'logoffurl' => 'http://logoff9',
                'bveurl' => 'http://bve8', 'userid' => 'anonymous', 'profil' => 'expertia', 'companyid' => 2,
                'softappid' => 2, 'lastname' => 'mm', 'firstname' => 'm', 'email' => 'mm@doca.fr',
                'client-context' => 'mm']),
        );
    }

    /**
     * Teste logon avec des headers invalides
     *
     * @dataProvider getLogonWithInvalidData
     *
     * @param $data
     * @param $codeError
     * @param $msgError
     * @throws \ApiBundle\Exception\LogonException
     */
    public function testAuthenticateWithInvalidData($data, $codeError, $msgError)
    {
        $this->getUsrRepository()->method('findOneByUsrLogin')
            ->will($this->returnValueMap(array(
                array('sbrion-msc', $this->createValidUser('sbrion-msc')),
                array('not-active', $this->createValidUser('not-active')->setUsrActif(0)),
                array('disabled-membership', $this->createValidUser('disabled-membership')->setUsrStatus('INACTIV')),
                array(
                    'not-started-membership',
                    $this->createValidUser('not-started-membership')->setUsrBeginningdate(new \DateTime('+1 month'))
                ),
                array(
                    'expired-membership',
                    $this->createValidUser('expired-membership')->setUsrEndingdate(new \DateTime('-1 month'))
                ),
            )));

        try {
            $this->manager->authenticate($data);
            $this->fail();
        } catch (LogonException $e) {
            $this->assertEquals($e->getCode(), $codeError);
            $this->assertEquals($e->getMessage(), $msgError);
        }
    }

    /**
     * @return array
     */
    public function getLogonWithInvalidData()
    {
        $headers = array(
            'codesource' => 'ADP_PORTAIL',
            'pac' => 'TSI504',
            'logoffurl' => 'http://logoff',
            'bveurl' => 'http://bve',
            'userid' => 'sbrion-msc'
        );

        $data = array(
            array([], 1, 'codesource non géré'),
            array(['codesource' => 'azeazeap'] + $headers, 11, 'codesource inconnu'),
            array(['pac' => null] + $headers, 2, 'absence du code pac'),
            array(['logoffurl' => null] + $headers, 5, 'Absence de logoffurl'),
            array(['bveurl' => null] + $headers, 6, 'Absence de bveurl'),
            array(['userid' => null] + $headers, 7, 'Absence du user id'),
            array(['pac' => '1234'] + $headers, 3, 'Code pac inconnu'),
            array(['userid' => 'anonymous'] + $headers, 8, 'User id inexistant pour ce pac'),
            array(['userid' => 'not-active'] + $headers, 9, 'User id non autorisé à accéder au BVE pour ce pac'),
            array(['userid' => 'disabled-membership'] + $headers, 41, "Vous n'avez pas accès à cette instance"),
            array(
                ['userid' => 'not-started-membership'] + $headers,
                42,
                "Vous n'avez pas encore accès à cette instance"
            ),
            array(['userid' => 'expired-membership'] + $headers, 43, "Vous n'avez plus accès à cette instance"),
        );

        $headers = array(
                'codesource' => 'ADP_ARC',
                'profil' => 'expertia',
                'companyid' => 2,
                'softappid' => 2,
                'lastname' => 'mm',
                'firstname' => 'm',
                'email' => 'mm@doca.fr',
                'client-context' => 'mm'
            ) + $headers;

        $data = array_merge(array(
            array(['profil' => null] + $headers, 18, 'Absence de profil'),
            array(['companyid' => null] + $headers, 12, 'Absence de companyid'),
            array(['softappid' => null] + $headers, 15, 'Absence de softappid'),
            array(['lastname' => null] + $headers, 15, 'Absence de lastname'),
            array(['firstname' => null] + $headers, 16, 'Absence de firstname'),
            array(['email' => null] + $headers, 17, 'Absence de email'),
            array(['client-context' => null] + $headers, 19, 'Absence de client-context'),
        ), $data);

        return $data;
    }

    protected function createValidUser($login)
    {
        $user = new UsrUsers();
        $user->setUsrLogin($login);
        $user->setUsrActif(true);
        $user->setUsrStatus('ACTIV');
        $user->setUsrBeginningdate(new \DateTime('-2 months'));
        $user->setUsrEndingdate(new \DateTime('+2 months'));
        return $user;
    }

    /**
     * @return EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntityManager()
    {
        return $this->doctrine->getManager();
    }

    /**
     * @return UsrUsersRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUsrRepository()
    {
        return $this->getEntityManager()->getRepository('ApiBundle:UsrUsers');
    }

    /**
     * @return PdaProfilDefAppliRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPdaRepository()
    {
        return $this->getEntityManager()->getRepository('ApiBundle:PdaProfilDefAppli');
    }

    /**
     * @return TokenStorageInterface
     */
    protected function getTokenStorage()
    {
        return $this->container->get('security.token_storage');
    }

    /**
     * @return ClassificationPlanManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClassificationPlanManager()
    {
        return $this->container->get('api.manager.classification_plan');
    }
}
