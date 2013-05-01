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
            'query_builder' => function (EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('l')
                    ->where('l.groupe IS NULL')
                    ->orderBy('l.nom', 'ASC');
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter-lyceen'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_lyceen_type';
    }
}
