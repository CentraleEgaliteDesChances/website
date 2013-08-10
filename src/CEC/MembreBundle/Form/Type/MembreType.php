<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', null, array(
                'label' => 'Prénom',
            ))
            ->add('nom')
            ->add('email', null, array(
                'label' => 'Adresse email',
            ))
            ->add('telephone', null, array(
                'label' => 'Numéro de téléphone',
            ))
            ->add('promotion');
    }
    
    public function getName()
    {
        return 'Membre';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Membre',
        ));
    }
}

