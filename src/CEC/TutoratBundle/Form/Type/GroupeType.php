<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lycees', 'entity', array(
                'label' => 'Lycées associés (pour sélectionner plusieurs lycées, maintenez la touche Ctr enfoncée)',
                'class' => 'CEC\TutoratBundle\Entity\Lycee',
                'multiple' => true,
                'query_builder' => function (EntityRepository $entityRepository)
                {
                    return $entityRepository->createQueryBuilder('l')
                        ->where('l.pivot = 0')
                        ->andWhere('l.cordee IS NOT NULL')
                        ->orderBy('l.nom', 'ASC');
                },
                'empty_value' => false,
            ))
            ->add('niveau', 'choice', array(
                    'label' => 'Niveau du groupe',
                    'choices' => array('Secondes' => 'Secondes', 'Premières' => 'Premières', 'Terminales' => 'Terminales')
            ))
            ->add('typeDeTutorat', 'choice', array(
                'label' => 'Type de tutorat',
                'choices' => array(
                    'Tutorat Scientifique' => 'Tutorat Scientifique', 
                    'Tutorat Culturel' => 'Tutorat Culturel',
                    'Tutorat Culturel et Scientifique' => 'Tutorat Culturel et Scientifique',
                    ),
                'empty_value' => false,
            ))
            ->add('lieu', null, array(
                'attr' => array('placeholder' => 'A Centrale ou dans le lycée'),
            ))
            ->add('debut', null, array(
                'label' => 'Indiquez la date et l\'heure d\'une séance — seul le jour de la semaine sera pris en compte',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy à HH:mm',
                'attr' => array('placeholder' => 'JJ/MM/AAAA à HH:MM'),
            ))
            ->add('fin', 'datetime', array(
                'label' => 'Indiquez l\'heure de fin de séance',
                'widget' => 'single_text',
                'format' => 'HH:mm',
                'attr' => array('placeholder' => 'HH:MM'),
            ))
            ->add('rendezVous', null, array(
                'label' => 'Lieu de rendez-vous pour partir en séance',
                'attr' => array('placeholder' => 'Rendez-vous...'),
            ));
    }
    
    public function getName()
    {
        return 'groupe';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Groupe',
        ));
    }
}
