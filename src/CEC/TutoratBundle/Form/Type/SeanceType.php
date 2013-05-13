<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', null, array(
                'label' => 'Indiquez la date de la séance',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('placeholder' => 'JJ/MM/AAAA'),
            ))
            ->add('debut', null, array(
                'label' => 'Horaires de la séance',
                'widget' => 'single_text',
                'attr' => array('placeholder' => 'HH:MM'),
                'widget_addon' => array(
                    'type' => 'prepend',
                    'text' => 'de'
                ),
            ))
            ->add('fin', null, array(
                'label' => false,
                'widget' => 'single_text',
                'attr' => array('placeholder' => 'HH:MM'),
                'widget_addon' => array(
                    'type' => 'prepend',
                    'text' => 'à'
                ),
            ))
            ->add('lieu', null, array(
                'label' => 'Lieu de la séance de tutorat',
                'attr' => array('placeholder' => 'A Centrale ou dans le lycée'),
            ))
            ->add('rendezVous', null, array(
                'label' => 'Lieu de rendez-vous pour partir en séance',
                'attr' => array('placeholder' => 'Rendez-vous...'),
            ));
    }
    
    public function getName()
    {
        return 'seance';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Seance',
        ));
    }
}
