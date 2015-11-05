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
use Symfony\Component\Translation\TranslatorInterface;

class UserReenterPasswordForm extends AbstractType
{
    const NAME = 'user_reset_form';

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => $this->translator->trans('The password fields must match.'),
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('save', 'submit')
        ;
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return self::NAME;
    }
}