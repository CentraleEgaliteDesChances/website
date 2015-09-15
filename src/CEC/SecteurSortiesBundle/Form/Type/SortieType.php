<?php

namespace CEC\SecteurSortiesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', null, array(
                    'label' => 'Nom de la sortie',
                    'attr' => array('placeholder' => 'Nom de la sortie'),
                ))
                ->add('adresse', null, array(
                    'label' => 'Adresse du lieu de rendez-vous',
                    'attr' => array('placeholder' => 'Adresse du lieu de rendez-vous'),
                ))
                ->add('dateSortie', 'date', array(
                    'widget' => 'choice',
                    'view_timezone' => 'Europe/Paris',
                    'label' => 'Date de la sortie',
                    'attr' => array('placeholder' => 'Date de la sortie'),
                ))
                ->add('heureDebut', 'time', array(
                    'widget' => 'choice',
                    'label' => 'Heure de rendez-vous',
                    'attr' => array('placeholder' => 'Heure de rendez-vous'),
                ))
                ->add('heureFin', 'time', array(
                    'widget' => 'choice',
                    'label' => 'Estimation de l\'heure de fin',
                    'attr' => array('placeholder' => 'Estimation de l\'heure de fin'),
                ))
                ->add('places', null, array(
                    'label' => 'Nombre de places offertes (0 si pas de limite)',
                    'data' => 0
                ))
                ->add('description', 'textarea', array(
                    'label' => 'Description',
                    'attr' => array('rows' => '6', 'placeholder' => 'Description'),
                    'help_inline' => 'La description ne peut excéder 800 caractères.',
                ))
                ->add('nbTuteurs', 'integer', array(
                    'label' => 'Nombre de tuteurs accompagnateurs',
                    'attr' => array('placeholder' => 'Nombre de tuteurs qui ont accompagné les lycéens lors de la sortie'),
                ))
                ->add('commentaire', 'textarea', array(
                    'label' => 'Commentaire',
                    'attr' => array('placeholder' => 'Commentaire sur le déroulement de la sortie'),
                    'help_inline' => 'Le commentaire ne peut excéder 800 caractères.',
                ))
                ->add('prix', 'integer', array(
                    'label' => 'Coût total',
                    'attr' => array('placeholder' => 'Coût total de la sortie'),
                ));
    }

    public function getName()
    {
        return 'Sortie';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurSortiesBundle\Entity\Sortie',
            'nbLyceens' => NULL,
            'nbTuteurs' => NULL,
            'commentaire' => NULL,
            'prix' => NULL,
        ));
    }
}
