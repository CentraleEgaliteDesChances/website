<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class AjouterLyceenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lyceen', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Eleve',
            'query_builder' => function (EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('e')
                                        ->where('(SELECT COUNT(ge) FROM CECTutoratBundle:GroupeEleves ge WHERE ge.lyceen = e
                                                                                                                        AND  ge.anneeScolaire = :anneeScolaire) = 0 ')
                                        ->setParameter('anneeScolaire', AnneeScolaire::withDate());
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_lyceen_type';
    }
}
