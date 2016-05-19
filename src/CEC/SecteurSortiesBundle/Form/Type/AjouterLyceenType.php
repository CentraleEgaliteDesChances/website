<?php

namespace CEC\SecteurSortiesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class AjouterLyceenType extends AbstractType
{
    private $sortie;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('lyceen', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Eleve',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('eleve')
                    ->orderBy('eleve.nom')
                    ->addOrderBy('eleve.prenom');
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_lyceen_type';
    }

    public function __construct (\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
    {
        $this->sortie = $sortie;
    }
}
