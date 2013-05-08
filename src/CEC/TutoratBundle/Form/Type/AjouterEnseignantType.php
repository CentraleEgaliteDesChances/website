<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AjouterEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enseignant', 'entity', array(
            'label' => false,
            'class' => 'CECTutoratBundle:Enseignant',
            'query_builder' => function (EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('e')
                    ->where('e.lycee IS NULL')
                    ->orderBy('e.nom', 'ASC');
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_enseignant';
    }
}
