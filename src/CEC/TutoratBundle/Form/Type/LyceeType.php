<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LyceeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('statut')
            ->add('telephone')
            ->add('ZEP')
            ->add('pivot')
            ->add('dateCreation')
            ->add('dateModification')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Lycee'
        ));
    }

    public function getName()
    {
        return 'cec_tutoratbundle_lyceetype';
    }
}
