<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('groupe', 'entity', array(
        'label' => 'Choisir son groupe de tutorat',
        'class' => 'CECTutoratBundle:Groupe',
        'empty_value' => false,
        'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'GroupeMembre';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    
    }
}
