<?php

namespace CEC\MembreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('mail')
            ->add('lycee')
            ->add('telephone')
            ->add('roles')
            ->add('motDePasse')
            ->add('dateCreation')
            ->add('dateModification')
            ->add('referent')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Professeur'
        ));
    }

    public function getName()
    {
        return 'cec_membrebundle_professeurtype';
    }
}
