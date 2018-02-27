<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

class ParametersValidation extends Controller
{
    // Objet non décodé
    const ERR_OBJECT_CONTENT_INCORRECT = 'errDocapostControllerObjectContentIncorrect';
    // Erreur interne
    const ERR_INTERNAL_ERROR = 'errDocapostControllerInternalError';
    // Le paramètre est manquant
    const ERR_PARAMETER_IS_MISSING = 'errDocapostControllerParameterIsMissing';
    // Le contenu est vide
    const ERR_PARAMETER_IS_EMPTY = 'errDocapostControllerParameterIsEmpty';
    // Le type de paramètre transmis est incorrect
    const ERR_PARAMETER_TYPE_IS_INCORRECT = 'errDocapostControllerParameterTypeIsIncorrect';
    // Le contenu transmis n'est pas booléen
    const ERR_PARAMETER_TYPE_IS_NOT_BOOLEAN = 'errDocapostControllerParameterTypeIsNotBoolean';
    // Le contenu n'est pas un entier
    const ERR_PARAMETER_TYPE_IS_NOT_AN_INTEGER = 'errDocapostControllerParameterTypeIsNotAnInteger';
    // Le contenu n'est pas une chaîne de caractères
    const ERR_PARAMETER_TYPE_IS_NOT_A_STRING = 'errDocapostControllerParameterTypeIsNotAString';
    // Le contenu n'est pas un tableau
    const ERR_PARAMETER_TYPE_IS_NOT_AN_ARRAY = 'errDocapostControllerParameterTypeIsNotAnArray';
    // Le contenu du fichier est incorrect
    const ERR_FILE_CONTENT_INCORRECT = 'errDocapostControllerFileContentIncorrect';
    // L'extension du fichier est incorrecte
    const ERR_FILE_EXTENSION_INCORRECT = 'errDocapostControllerFileExtensionIncorrect';

    const MSG_CODE_PARAMETER = '__parameter__';

    const MSG_CODE_VALUE = '__value__';

    const MSG_CODE_LABEL = '__label__';

    const ERR_FIELDS_NOT_NULLABLE = 'errDocapostControllerFieldsNotNullable';

    /**
     * @var array   Contient les éventuelles erreurs des paramètres transmis
     */
    private $errors = [];

    /**
     * @var bool    Renvoie TRUE si un message est setté
     */
    private $hasMessage = false;

    /**
     * @var array   Regroupe les messages renvoyés à l'utilisateur
     */
    protected $messageInfos = array();

    /**
     * Valide les paramètres (leurs type et contenu)
     *
     * @param array $checks Un tableau de vérifications
     * @param array $datas Les paramètres à valider
     *
     * @return bool
     */
    public function validateWSParameters(array $datas, array $checks = [])
    {
        foreach ($checks as $name => $check) {
            if (!array_key_exists($name, $datas)) {
                // Paramètre manquant
                $this->errors[] = [self::ERR_PARAMETER_IS_MISSING, [$name]];
            } elseif (null === $check) {
                // Pas de validation
                continue;
            } elseif (is_array($check)) {
                // Vérifie un tableau
                $this->checkIsArrayParameters($check, $datas[$name], $name);
            } elseif (!is_bool($datas[$name]) && 0 !== $datas[$name] && false == $datas[$name]) {
                // Contenu du paramètre vide
                $this->errors[] = [self::ERR_PARAMETER_IS_EMPTY, [$name]];
            } else {
                // Vérifie un booléen, un entier ou une chaîne de caractères
                $this->checkTypeParameter($check, $datas[$name], $name);
            }
        }

        if ($this->hasAnError()) {
            return false;
        }
        return true;
    }

    /**
     * Valide le type de paramètre
     *
     * @param bool|string|int $check Le type de paramètre attendu
     * @param bool|string|int $value Le contenu du paramètre
     * @param string $name Le nom du paramètre
     *
     * @return bool
     */
    private function checkTypeParameter($check, $value, $name)
    {
        if ($check == 'bool') {
            $error = $this->checkIsBooleanParameter($value, $name);
        } elseif ($check == 'int') {
            $error = $this->checkIsIntegerParameter($value, $name);
        } else {
            $error = $this->checkIsStringParameter($check, $value, $name);
        }
        return $error;
    }

    /**
     * Valide le contenu du tableau
     *
     * @param array $checks La structure attendue
     * @param array $values Un tableau de valeurs à valider
     * @param string $name Le nom du paramètre
     */
    private function checkIsArrayParameters($checks, $values, $name)
    {
        if (!is_array($values)) {
            $this->errors[] = [self::ERR_PARAMETER_TYPE_IS_NOT_AN_ARRAY, [$name, $values]];
        } else {
            $nbCheck = count($checks);
            if (array_key_exists(0, $checks) && 1 == $nbCheck) {
                if (null !== $checks[0]) {
                    // Vérifie chaque valeur du tableau numérique
                    foreach ($values as $valueParameter) {
                        if ($this->checkTypeParameter($checks[0], $valueParameter, $name)) {
                            break;
                        }
                    }
                }
            } elseif ($nbCheck) {
                // Vérifie chaque clef et valeur du tableau associatif
                $this->validateWSParameters($values, $checks);
            }
        }
    }

    /**
     * Valide que le contenu du paramètre est une chaîne de caractères
     *
     * @param string $check Une expression régulière
     * @param string $value La valeur
     * @param string $name Le nom du paramètre
     *
     * @return bool
     */
    private function checkIsStringParameter($check, $value, $name)
    {
        $error = false;
        if (!is_string($value)) {
            $this->errors[] = [self::ERR_PARAMETER_TYPE_IS_NOT_A_STRING, [$name, $value]];
            $error = true;
        } elseif (!preg_match("/$check/", $value)) {
            $this->errors[] = [self::ERR_PARAMETER_TYPE_IS_INCORRECT, [$name, $value]];
            $error = true;
        }
        return $error;
    }

    /**
     * Valide que le contenu est un entier
     *
     * @param int $value La valeur
     * @param int $name Le nom du paramètre
     *
     * @return bool
     */
    private function checkIsIntegerParameter($value, $name)
    {
        $error = false;
        if (!is_integer($value)) {
            $this->errors[] = [self::ERR_PARAMETER_TYPE_IS_NOT_AN_INTEGER, [$name, $value]];
            $error = true;
        }
        return $error;
    }

    /**
     * Valide que le contenu est un booléen
     *
     * @param bool $value La valeur
     * @param string $name Le nom du paramètre
     *
     * @return bool
     */
    private function checkIsBooleanParameter($value, $name)
    {
        $error = false;
        if (!is_bool($value)) {
            $this->errors[] = [self::ERR_PARAMETER_TYPE_IS_NOT_BOOLEAN, [$name, $value]];
            $error = true;
        }
        return $error;
    }

    /**
     * Retourne TRUE s'il y a une ou plusieurs erreur(s) dans le tableau d'erreurs et les convertit en messages
     *
     * @return bool
     */
    private function hasAnError()
    {
        if (!empty($this->errors)) {
            $this->translateErrorsArrayIntoResponseMessages();
            return true;
        }
        return false;
    }

    /**
     * Crée les messages d'erreurs à partir des erreurs du tableau d'erreurs
     */
    private function translateErrorsArrayIntoResponseMessages()
    {
        foreach ($this->errors as $error) {
            $messageArgs = [];
            if (!empty($error[1])) {
                $messageArgs[self::MSG_CODE_PARAMETER] = $error[1][0];
                if (isset($error[1][1])) {
                    $messageArgs[self::MSG_CODE_VALUE] = $error[1][1];
                }
            }
            $this->addResponseMessage($error[0], $messageArgs);
        }
        $this->errors = [];
    }

    /**
     * Parcours le formulaire et ajoute un ou plusieurs messages s'il y a une ou plusieurs erreur(s)
     *
     * @param Form $form
     *
     * @return bool
     */
    public function translateErrorsFormIntoResponseMessages(Form $form)
    {
        foreach ($form as $fieldName => $formField) {
            // On récupère une ou plusieurs erreur(s) pour chaque champ
            foreach ($formField->getErrors(true) as $error) {
                $messageParameters = [];
                foreach ($error->getMessageParameters() as $parameter => $value) {
                    $messageParameters[preg_replace('/^{{ (.+) }}$/', '__$1__', $parameter)] =
                        ($value == 'null') ? '' : $value;
                }
                $this->addResponseMessage($error->getMessage(), $messageParameters, $fieldName);
            }
        }
        return $this->hasResponseMessage();
    }

    /**
     * Ajoute un message qui pourra être envoyé par messageJsonResponse
     *
     * @param string $code Le code issu du dictionnaire
     * @param array $values Un tableau clef -> valeur. Chaque valeur peut être un tableau.
     *                      Dans ce cas, les valeurs seront rassemblées dans une chaîne côté Front
     * @param null $fieldName L'Id du champ sur lequel porte le message
     *                        (l'attribut "name" dans un formulaire par exemple)
     *
     * @return bool
     */
    public function addResponseMessage($code, $values = array(), $fieldName = null)
    {
        if (!is_array($values)) {
            $values = [];
        }
        if ($fieldName === null) {
            $fieldName = '';
        }
        // Ajout du code, des valeurs et éventuellement de l'Id du champ dans le formulaire
        $this->messageInfos[] = [
            'code' => $code,
            'values' => $values,
            'fieldName' => $fieldName
        ];
        $this->hasMessage = true;
        return true;
    }

    /**
     * Renvoie TRUE si un message est setté à partir de addResponseMessage
     *
     * @return bool
     */
    public function hasResponseMessage()
    {
        return $this->hasMessage;
    }
}
