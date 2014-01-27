<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizzActuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('semaine', 'date', array(
                'label' => 'Semaine du quizz actu',
                'help_inline' => 'Date du premier jour de la semaine du quizz actu.',
            ))
            ->add('commentaire', null, array(
                'label' => 'Commentaire',
                'required' => false,
                'attr' => array('rows' => '6', 'placeholder' => 'Commentaire'),
                'help_inline' => 'Entrez ici un commentaire sur le quizz actu si besoin. Ne peut excéder 200 caractères.',
            ))
            ->add('fichierPDF', null, array(
                    'label' => false,
                    'help_inline' => 'La taille du document ne peut excéder 10 Mo, et les formats acceptés sont les formats Adobe PDF (.pdf).',
            ));
    }

    public function getName()
    {
        return 'QuizzActu';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\QuizzActu',
        ));
    }
}
