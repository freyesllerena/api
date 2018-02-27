<?php

namespace ApiBundle\Controller;

use ApiBundle\DocapostJsonResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DocapostController extends ParametersValidation
{
    const MSG_LEVEL_INFO = 1;

    const MSG_LEVEL_WARN = 2;

    const MSG_LEVEL_ERROR = 3;

    /**
     * Encode en JSON la réponse fournie
     *
     * @param $objects
     *
     * @return DocapostJsonResponse
     */
    public function export($objects)
    {
        if (!$objects) {
            return new DocapostJsonResponse();
        }
        // Convertion du champ text date en champ objet date
        if ($this->isItemsKey($objects)) {
            $objects = $this->replaceDateInObject($objects);
        }

        return new DocapostJsonResponse($objects);
    }

    /**
     * Extrait les valeurs d'un formulaire
     *
     * @param $form
     *
     * @return array
     */
    protected function extractFormValues(FormInterface $form)
    {
        $values = array();
        $view = $form->createView();
        foreach ($view as $name => $field) {
            /* @var $field FormView */
            $values[$name] = $field->vars['value'];
        }

        return $values;
    }

    /**
     * Décode une chaîne encodée en base64 et retourne un tableau
     *
     * @return mixed
     */
    public function getRequestParameters()
    {
        $encodedQuery = $this->container->get('request')->query->get('q');
        // Décodage de la chaîne
        $query = base64_decode($encodedQuery);
        parse_str($query, $parameters);

        return $parameters;
    }

    /**
     * Récupère les paramètres et peut éventuellement les contrôler et les valider
     *
     * @param bool $assoc Conversion en tableau associatif ?
     * @param bool $validation Doit-on vérifier les paramètres ?
     * @param array $checks Un tableau de vérifications
     *
     * @return mixed
     */
    public function getContentParameters($assoc = false, $validation = false, array $checks = [])
    {
        // Décodage des paramètres
        if (null === ($datas = @json_decode($this->container->get('request')->getContent(), $assoc))) {
            $this->addResponseMessage(self::ERR_OBJECT_CONTENT_INCORRECT);
        } elseif ($validation && count($checks)) {
            // Validation des paramètres
            $this->validateWSParameters(
                !$assoc ? @json_decode($this->container->get('request')->getContent(), true) : $datas,
                $checks
            );
        }
        return $datas;
    }

    /**
     * Formate et renvoie une réponse personnalisable
     *
     * @param int $level Le niveau de l'erreur (1 -> Info, 2 -> Warning, 3 -> Erreur)
     * @param int $messageCode Le code HTTP de retour
     *
     * @return DocapostJsonResponse
     */
    public function messageJsonResponse($level = self::MSG_LEVEL_INFO, $messageCode = 400)
    {
        return new DocapostJsonResponse([
            'msg' => [
                'level' => $level,
                'infos' => $this->messageInfos
            ]
        ], $messageCode);
    }

    /**
     * Convertit les objets en tableaux
     *
     * @param object|array $datasToNormalize Un objet ou tableau d'objets à normaliser
     * @param null|array $ignoredAttributes Liste des attributs de l'objet à ignorer
     *
     * @return array|bool|float|int|null|object|string
     */
    public function convertIntoArray($datasToNormalize, $ignoredAttributes = null)
    {
        $normalizer = new ObjectNormalizer();
        if (is_array($ignoredAttributes)) {
            $normalizer->setIgnoredAttributes($ignoredAttributes);
        }
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));

        return $serializer->normalize($datasToNormalize);
    }

    /**
     * Vérification si items existe dans l'object
     * @param $data
     * @return bool
     */
    private function isItemsKey($data)
    {
        if (is_array($data) && !is_null($data)) {
            if (array_key_exists('items', $data)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * On efface le champ texte date et on remplace par le champ object date
     * @param $data
     * @return array
     */
    private function replaceDateInObject($data)
    {
        $list = $data['items'];
        $datesForConvertion = $this->getParameter('dates_objects');
        if (!is_array($list)  || is_null($list)) {
            return null;
        }

        foreach ($list as $keyList => $valueList) {
            $listDates = [];
            foreach ($datesForConvertion as $nameDate) {
                if (!array_key_exists($nameDate, $valueList)) {
                    continue;
                }
                if ($valueList[$nameDate] === '' || $valueList[$nameDate] === null) {
                    $listDates[$nameDate] = null;
                    continue;
                }
                switch ($nameDate) {
                    case 'ifpIdPeriodePaie':
                        $year = substr($valueList[$nameDate], 0, 4);
                        $month = substr($valueList[$nameDate], 4, 2);
                        $newDate = new \DateTime($year.'-'.$month.'-01');
                        break;
                    case 'ifpIdPeriodeExerciceSociale':
                        $year = substr($valueList[$nameDate], 0, 4);
                        $newDate = new \DateTime($year.'-01-01');
                        break;
                    default:
                        $newDate = $valueList[$nameDate];
                }
                $list[$keyList][$nameDate] = $newDate;
            }
        }

        $data["items"] = $list;
        return $data;
    }
}
