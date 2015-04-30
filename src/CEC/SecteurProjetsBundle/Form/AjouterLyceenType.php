<?php

namespace CEC\SecteurProjetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;

use Doctrine\ORM\EntityRepository;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class AjouterLyceenType extends AbstractType
{
    private $projet;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projet = $this->projet;

        $builder->add('lyceen', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Eleve',
            'query_builder' => function (EntityRepository $entityRepository) use($projet)
            {
                return $entityRepository->createQueryBuilder('e')
                                        ->where(':projet NOT IN (SELECT pe FROM CECSecteurProjetsBundle:ProjetEleve pe WHERE pe.lyceen = e
                                                                                                               AND  pe.anneeScolaire = :anneeScolaire)')
                                            ->setParameter('anneeScolaire', AnneeScolaire::withDate())
                                            ->setParameter('projet', $projet);
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_lyceen_type';
    }

    public function __construct(\CEC\SecteurProjetsBundle\Entity\Projet $projet)
    {
        $this->projet = $projet;
    }


}


