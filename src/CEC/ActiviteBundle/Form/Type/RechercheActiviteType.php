<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RechercheActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', null, array(
                'label' => false,
                'attr' => array('placeholder' => "Titre de l'activité", "autofocus" => "")
            ))
            ->add('type', 'choice', array(
                'label' => false,
                'choices' => array(
                    'Activité Culturelle' => 'Activité Culturelle', 
                    'Activité Scientifique' => 'Activité Scientifique',
                    'Expérience Scientifique' => 'Expérience Scientifique',
                    'Autre' => 'Autre',
                ),
                'empty_value' => "Toutes les types",
                'required' => false,
            ))
            ->add('filtrerActivitesRealisees', null, array(
                'label' => "Masquer les activités déjà utilisées",
            ));
    }
    
    public function getName()
    {
        return 'RechercheActivite';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Utility\RechercheActivite',
        ));
    }
}
