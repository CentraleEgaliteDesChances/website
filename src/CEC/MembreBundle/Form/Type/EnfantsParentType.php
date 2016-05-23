<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 23/05/16
 * Time: 17:10
 */

namespace CEC\MembreBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnfantsParentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('eleves', 'entity', array(
                'class' => 'CEC\MembreBundle\Entity\Eleve',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('eleve')
                        ->orderBy('eleve.nom')
                        ->addOrderBy('eleve.prenom');
                },
                'multiple' => true,
                'placeholder' => 'Choisir parmi les élèves inscrits',
                'label' => 'Voulez-vous modifier les élèves enregistrés comme vos enfants ?'
            ))
            ;
    }

    public function getName() {
        return 'EnfantsParent';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\ParentEleve',
        ));
    }
}