<?php

namespace CEC\SecteurSortieBundle\Form\Type;

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
                ->add('description', null, array(
                    'label' => 'Description',
                    'attr' => array('rows' => '6', 'placeholder' => 'Description'),
                    'help_inline' => 'Entrez ici une brève description de la sortie. La description ne peut excéder 255 caractères.',
                ));
    }

    public function getName()
    {
        return 'Sortie';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurSortieBundle\Entity\Sortie',
        ));
    }
}
