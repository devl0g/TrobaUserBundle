<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/12/14
 * Time: 4:28 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password', 'password')
            ->add('password_reenter', 'password', [
                'mapped' => false
            ])
            ->add('register', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SikIndustries\Bundles\TrobaUserBundle\Entity\User'
        ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'user_registration_form';
    }
}