<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SecteursMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('secteurs', null, array(
            'expanded'     => true,
            'label'        => 'Indiquez les secteurs dans lesquels vous vous impliquez :',
            'property'     => 'nom',
        ));
    }
    
    public function getName() {
        return 'SecteursMembre';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Membre',
        ));
    }
}
