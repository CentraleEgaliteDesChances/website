<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompteRenduType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('noteContenu', null, array(
                'label' => 'Contenu du sujet (Note /5)',
                'help_inline' => 'Le sujet possède-t-il un contenu pédagogique pertinent ?',
                'attr' => array('class' => 'note-input'),
            ))
            ->add('noteInteractivite', null, array(
                'label' => 'Interactivité (Note /5)',
                'help_inline' => 'Le sujet est-il suffisamment interactif et ludique ? ',
                'attr' => array('class' => 'note-input'),
            ))
            ->add('noteAtteinteObjectifs', null, array(
                'label' => 'Atteinte des objectifs (Note /5)',
                'help_inline' => 'Les objectifs pédagogiques annoncés ont-ils été atteints en fin de la séance ?',
                'attr' => array('class' => 'note-input'),
            ))
            ->add('dureeAdaptee', 'choice', array(
                'label' => 'La durée de l\'activité vous semble-t-elle adaptée ?',
                'choices' => array(
                     -1 => 'La durée est globalement sous-estimée', 
                     0  => 'La durée estimée est globalement correcte',
                     1  => 'La durée est globalement sur-estimée',
                ),
                'empty_value' => false,
                'attr' => array('class' => 'input-block-level'),
            ))
            ->add('commentaires', null, array(
                'label' => 'Autres commentaires à destination des secteurs Actis ou des tuteurs',
                'attr' => array('class' => 'input-block-level'),
            ));
    }
    
    public function getName()
    {
        return 'CompteRendu';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\CompteRendu',
        ));
    }
}
