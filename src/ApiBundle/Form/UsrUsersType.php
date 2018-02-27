<?php

namespace ApiBundle\Form;

use ApiBundle\Enum\EnumNumPermissionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UsrUsersType
 * @package ApiBundle\Form
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class UsrUsersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('usrActif')
            ->add('usrLogin')
            ->add('usrNom')
            ->add('usrPrenom')
            ->add('usrAdresseMail')
            ->add('usrMailCycleDeVie')
            ->add('usrAuthorizations', CollectionType::class);

        $authorizationsTransformer = $this->createAuthorizationsTransformer();
        $builder->get('usrAuthorizations')->addViewTransformer($authorizationsTransformer);
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
            'data_class' => 'ApiBundle\Entity\UsrUsers',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ));
    }

    /**
     * Renvoie le transformer des permissions
     *
     * @return CallbackTransformer
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function createAuthorizationsTransformer()
    {
        return new CallbackTransformer(
            function ($original) {
                $transformed = [];
                foreach ($original as $habilitation => $permission) {
                    $binary = str_pad(decbin($permission), 3, '0', STR_PAD_LEFT);
                    if ('right' == substr($habilitation, 0, 5)) {
                        $transformed[$habilitation] = [
                            'R' => (bool)$binary[2],
                            'W' => (bool)$binary[1],
                            'D' => (bool)$binary[0],
                        ];
                    } elseif ('access' == substr($habilitation, 0, 6)) {
                        $transformed[$habilitation] = (bool)$permission;
                    }
                }

                return $transformed;
            },
            function ($transformed) {
                $original = [];
                foreach ($transformed as $habilitation => $permission) {
                    $decimal = 0;
                    if ('right' == substr($habilitation, 0, 5)) {
                        if (isset($permission['R']) && $permission['R']) {
                            $decimal += EnumNumPermissionType::READ;
                        }
                        if (isset($permission['W']) && $permission['W']) {
                            $decimal += EnumNumPermissionType::WRITE;
                        }
                        if (isset($permission['D']) && $permission['D']) {
                            $decimal += EnumNumPermissionType::DELETE;
                        }
                        $original[$habilitation] = $decimal;
                    } elseif ('access' == substr($habilitation, 0, 6)) {
                        $original[$habilitation] = (bool)$permission;
                    }
                }

                return $original;
            }
        );
    }
}
