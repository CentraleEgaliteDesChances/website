<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class AjouterTuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tuteur', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Membre',
            'query_builder' => function (EntityRepository $entityRepository)
            {
                return $entityRepository->createQueryBuilder('t')
                                        ->where('(SELECT COUNT(gt) FROM CECTutoratBundle:GroupeTuteurs gt WHERE gt.tuteur = t
                                                                                                                        AND  gt.anneeScolaire = :anneeScolaire) = 0 ')
                                        ->setParameter('anneeScolaire', AnneeScolaire::withDate());
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_tuteur_type';
    }
}
