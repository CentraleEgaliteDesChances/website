<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CEC\ActiviteBundle\Form\Type\ActiviteType;
use CEC\ActiviteBundle\Form\Type\PremierDocumentType;

class NouvelleActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activite', new ActiviteType(), array(
                'show_child_legend' => false,
                'label' => false,
            ))
            ->add('document', new PremierDocumentType());
    }
    
    public function getName()
    {
        return 'NouvelleActivite';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\NouvelleActivite',
        ));
    }
}
