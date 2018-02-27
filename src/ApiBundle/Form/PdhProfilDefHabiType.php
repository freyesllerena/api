<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Manager\PopulationFilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Type de formulaire pour les filtres par population
 *
 * @package ApiBundle\Form
 */
class PdhProfilDefHabiType extends AbstractType
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var PopulationFilterManager
     */
    protected $popFilterManager;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * PdeProfilDefHabiType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->popFilterManager = $container->get('api.manager.population_filter');
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('pdhLibelle')
            ->add('pdhModeReference')
            ->add('pdhModeArchive')
            ->add('pdhHabilitations');

        $integerToBoolean = new CallbackTransformer('boolval', 'intval');

        $builder->get('pdhModeReference')->addViewTransformer($integerToBoolean);
        $builder->get('pdhModeArchive')->addViewTransformer($integerToBoolean);
        $builder->get('pdhHabilitations')->addViewTransformer(new CallbackTransformer(
            array($this, 'transformHabilitation'),
            array($this, 'reverseTransformHabilitation')
        ));
    }

    /**
     * @inheritdoc
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\PdhProfilDefHabi',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ));
    }

    /**
     * Transforme une habilitation BACK -> FRONT
     *
     * @param $original
     * @return array
     */
    public function transformHabilitation($original)
    {
        $items = array();
        foreach ($original as $typeHabilitation => $habilitation) {
            if (!$habilitation) {
                $habilitation = '<operator><type>and</type></operator>';
            }

            $item = $this->popFilterManager->unserialize($habilitation);
            $item['codeMetadata'] = ucfirst($typeHabilitation);
            $items[] = $item;

        }

        $transformed = array(
            'codeMetadata' => 'ROOT',
            'typeRegle' => null,
            'values' => array(),
            'operator' => null,
            'children' => $items,
        );

        return $this->transformHabilitationNode($transformed);
    }

    /**
     * Transforme une habilitation FRONT -> BACK
     *
     * @param $transformed
     * @return array
     */
    public function reverseTransformHabilitation($transformed)
    {
        $original = array();

        foreach ($transformed['children'] as $child) {
            foreach (PdhProfilDefHabi::getTypesHabilitations() as $typeHabilitation) {
                if (strtolower($child['codeMetadata']) == strtolower($typeHabilitation)) {
                    $child = $this->reverseTransformHabilitationNode($child);
                    $original[$typeHabilitation] = $this->popFilterManager->serialize($child);
                }
            }
        }

        foreach (PdhProfilDefHabi::getTypesHabilitations() as $typeHabilitation) {
            if (!isset($original[$typeHabilitation])) {
                throw new \InvalidArgumentException(sprintf(
                    "Arborescence invalide : le type d'habilitation '%s' n'a pas été trouvé",
                    $typeHabilitation
                ));
            }
        }

        return $original;
    }

    /**
     * Transformation noeud d'une habilitation BACK -> FRONT
     *
     * @param array $node
     * @return array
     */
    protected function transformHabilitationNode($node)
    {
        if (is_array($node) && array_key_exists('children', $node)) {
            $leaf = (count($node['children']) == 0);
            $node = array('leaf' => $leaf) + $node;

            if (!in_array(strtolower($node['codeMetadata']), array('collectif', 'individuel', 'root'))) {
                $node['codeMetadata'] = $this->transformField($node['codeMetadata']);

                $values = $this->getLabelsMetadata($node['codeMetadata'], $node['values']);

                $node['values'] = array_values($values);
            }

            foreach ($node['children'] as $n => $child) {
                $node['children'][$n] = $this->transformHabilitationNode($child);
            }
            return $node;
        }
    }

    /**
     * Transformation noeud d'une habilitation FRONT -> BACK
     *
     * @param $node
     * @return array
     */
    protected function reverseTransformHabilitationNode($node) {
        if (is_array($node) && array_key_exists('children', $node)) {
            unset($node['leaf']);

            if (!in_array(strtolower($node['codeMetadata']), array('collectif', 'individuel', 'root'))) {
                $node['codeMetadata'] = $this->reverseTransformField($node['codeMetadata']);
                $node['values'] = array_map(function($value) {
                    return $value['code'];
                }, $node['values']);
            }

            foreach ($node['children'] as $n => $child) {
                $node['children'][$n] = $this->reverseTransformHabilitationNode($child);
            }
            return $node;
        }
    }

    /**
     * Transformation nom de metadata BACK -> FRONT
     *
     * @param $original
     * @return mixed|string
     * @throws \Exception
     */
    protected function transformField($original)
    {
        $value = strtolower($original);
        $attempts = array($value);

        if (substr($value, 0, 3) == 'id_') {
            $attempts[] = substr($value, 3);
        }

        foreach ($attempts as $attempt) {
            $attempt = preg_replace_callback("/(?:^|_)([a-z])/", function ($matches) {
                return strtoupper($matches[1]);
            }, $attempt);
            $attempt = 'ifp'.ucfirst($attempt);
            $method = 'get'.ucfirst($attempt);
            if (method_exists('ApiBundle\Entity\IfpIndexfichePaperless', $method)) {
                return $attempt;
            }
        }
    }

    /**
     * Transformation nom de metadata FRONT -> BACK
     *
     * @param $transformed
     * @return string
     */
    protected function reverseTransformField($transformed)
    {
        $value = substr($transformed, 3);
        $value = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $value)), '_');
        if (substr($value, 0, 3) != 'id_') {
            $value = 'id_'.$value;
        }
        return strtoupper($value);
    }

    /**
     * Renvoit les labels d'un métadata
     *
     * @param $metadata
     * @param $codes
     * @return array
     */
    protected function getLabelsMetadata($metadata, $codes)
    {
        $results = array();
        foreach ($codes as $code) {
            $results[$code] = array(
                'code' => $code,
                'value' => $code,
            );
        }

        $datas  = (object) array(
            'referencialPac' => array($this->container->get('security.token_storage')->getToken()->getAttribute('pac')),
            'actualData' => true,
            'archivedData' => true,
            'start' => null,
            'limit' => null,
            'main' => (object) array(
                'code' =>  $metadata,
                'value' => '',
            ),
            'fields' => (object) array(
                $metadata => $codes,
            )
        );

        $managerAutocomplete = $this->container->get('api.manager.autocomplete');
        $sourceParams = $managerAutocomplete->getSourceParams($datas);
        $source = array_flip($sourceParams);
        $searchable = $managerAutocomplete->getSearchableFields($source);
        $items = $managerAutocomplete->autocompleteSearch($datas, $searchable);
        foreach ($items as $item) {
            if (isset($item['code'])) {
                $results[$item['code']] = $item;
            }
        }

        return $results;
    }
}