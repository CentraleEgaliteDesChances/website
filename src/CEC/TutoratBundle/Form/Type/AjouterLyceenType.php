<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AjouterLyceenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lyceen', 'entity', array(
            'label' => false,
            'class' => 'CECTutoratBundle:Lyceen',
            'empty_value' => false,
        ))
            ->add('groupe', 'hidden');
    }
    
    public function getName()
    {
        return 'ajouter_lyceen';
    }
}
