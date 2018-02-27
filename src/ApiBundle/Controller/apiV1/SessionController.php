<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Exception\LogonException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use ApiBundle\Controller\DocapostController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SessionController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Session",
     *     description="Première connexion à l'API, ouverture de session",
     *     statusCodes={
     *          302="Returned on request success",
     *          200="Renvoi une erreur au format ADP"
     *     }
     * )
     * @Get("/logon")
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function logon(Request $request)
    {
// todo: Vérifier plutot sur le security-token si la session est initialisée
//        // L'utilisateur est déjà connecté avec cette session
//        if ($request->getSession()->isStarted()) {
//            return false;
//        }

        try {
            $this->checkMandatoryHeaders($request);

            $this->get('api.manager.session')->logonUser(
                $request->headers->get('userid'),
                $request->headers->get('codesource'),
                $request->headers->get('pac'),
                array(
                    'profil' => $request->headers->get('profil'),
                    'numinstance' => $request->headers->get('numinstance'),
                    'password' => $request->headers->get('password'),
                )
            );
        } catch (LogonException $exception) {
            return new Response($exception->getMessage(), 200, array(
                'BVEERREUR' => $exception->getCode(),
                'BVEMESSAGE' => $exception->getMessage(),
            ));
        }

        if ($request->headers->get('codesource') == 'ADP_AUTOMATE') {
            $response = new Response('success');
        } else {
            $response = new RedirectResponse($this->getParameter('bvrh_front_url'), 302);
            $url = $request->headers->get('bveurl') . $this->getParameter('bvrh_front_url');
            $response->headers->set('bveurl', $url);
            $response->headers->set('location', $url);
        }
        $response->headers->set('BVEERREUR', 0);
        $response->headers->set('BVEMESSAGE', 'OK');

        return $response;
    }

    /**
     * @ApiDoc(
     *     section="Session",
     *     description="Déconnexion de l'utilisateur",
     *     statusCodes={
     *          302="Returned on request success",
     *          200="Renvoi une erreur au format ADP"
     *     }
     * )
     * @Get("/logoff")
     * @param Request $request
     * @return DocapostJsonResponse
     */
    public function logoff(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate(1);

        $output = array(
            'redirect_url' => 'http://www.google.com',
        );
        return new DocapostJsonResponse($output);
    }

    /**
     * @ApiDoc(
     *     section="Session",
     *     description="Renvoit les droits utilisateurs pour la session en cours",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          403="Accès non autorisé"
     *     }
     * )
     * @Get("/session/authorizations")
     * @return DocapostJsonResponse
     */
    public function getAuthorizations()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->export(
            $user->getUsrAuthorizations()
        );
    }

    /**
     * Vérifie si les headers obligatoires sont dans la requête
     *
     * @param Request $request
     * @throws LogonException
     */
    protected function checkMandatoryHeaders(Request $request)
    {
        $required = array(
            'codesource' => array('codesource non géré', 1),
            'pac' => array('absence du code pac', 2),
            'userid' => array('Absence du user id', 7)
        );

        if ($request->headers->get('codesource') == 'ADP_PORTAIL') {
            $required += array(
                'logoffurl' => array('Absence de logoffurl', 5),
                'bveurl' => array('Absence de bveurl', 6),
            );
        }

        if ($request->headers->get('codesource') == 'ADP_ARC') {
            $required += array(
                'logoffurl' => array('Absence de logoffurl', 5),
                'bveurl' => array('Absence de bveurl', 6),
                'profil' => array('Absence de profil', 18),
                'companyid' => array('Absence de companyid', 12),
                'softappid' => array('Absence de softappid', 15),
                'lastname' => array('Absence de lastname', 15),
                'firstname' => array('Absence de firstname', 16),
                'email' => array('Absence de email', 17),
                'client-context' => array('Absence de client-context', 19)
            );
        }

        if ($request->headers->get('codesource') == 'ADP_AUTOMATE') {
            $required += array(
                'password' => array('Absence du password', 99),
            );
        }

        foreach ($required as $field => list($msgError, $codeError)) {
            if (!$request->headers->has($field)) {
                throw new LogonException($msgError, $codeError);
            }
        }
    }
}
