<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NouveauMembreBuroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('membre', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Membre',
            'query_builder' => function (\Doctrine\ORM\EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('m')
                    ->where('m.buro = FALSE')
                    ->orderBy('m.nom', 'ASC');
            },
            'empty_value' => false,
        ));
    }
    
    public function getName()
    {
        return 'NouveauMembreBuro';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Utility\NouveauMembreBuro',
        ));
    }
}
