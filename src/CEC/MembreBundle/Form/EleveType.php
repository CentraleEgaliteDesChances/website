<?php

namespace CEC\MembreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('mail')
            ->add('lycee')
            ->add('classe')
            ->add('datenaiss')
            ->add('roles')
            ->add('motDePasse')
            ->add('dateCreation')
            ->add('dateModification')
            ->add('delegue')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Eleve'
        ));
    }

    public function getName()
    {
        return 'cec_membrebundle_elevetype';
    }
}
