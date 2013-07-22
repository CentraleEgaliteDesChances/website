<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', null, array(
                'label' => 'Titre de l\'activité',
                'attr' => array('placeholder' => 'Titre de l\'activité'),
            ))
            ->add('type', 'choice', array(
                'label' => 'Type d\'activité',
                'choices' => array(
                    'Activité Culturelle' => 'Activité Culturelle', 
                    'Activité Scientifique' => 'Activité Scientifique',
                    'Expérience Scientifique' => 'Expérience Scientifique',
                    'Autre' => 'Autre',
                ),
                'empty_value' => false,
            ))
            ->add('description', null, array(
                'label' => 'Description',
                'attr' => array('rows' => '6', 'placeholder' => 'Description'),
                'help_inline' => 'Entrez ici une brève description de l\'activité pour permettre aux tuteurs de saisir rapidement le déroulement de celle-ci. La description ne peut excéder 255 caractères.',
            ))
            ->add('duree', null, array(
                'label' => 'Durée estimée',
                'attr' => array('placeholder' => 'Durée estimée'),
            ));
    }
    
    public function getName()
    {
        return 'Activite';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\Activite',
        ));
    }
}
