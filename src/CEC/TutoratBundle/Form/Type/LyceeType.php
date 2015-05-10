<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LyceeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lycee = $this->lycee;

        $builder
            ->add('nom')
            ->add('pivot', 'choice', array(
                'label'     => false,
                'expanded'  => true,
                'choices'   => array('0' => 'Lycée Source', '1' => 'Lycée Pivot'),
            ))
            ->add('cordee', null, array(
                'label'       => 'Cordée',
                'empty_value' => 'Aucune cordée',
            ))
            ->add('adresse', null, array(
                'label' => 'Adresse postale',
            ))
            ->add('codePostal', null, array(
                'label' => 'Code postal',
            ))
            ->add('ville')
            ->add('statut', 'choice', array(
                'label'     => false,
                'expanded'  => true,
                'choices'   => array('Établissement Public' => 'Établissement Public', 
                                     'Établissement Privé'  => 'Établissement Privé'),
            ))
            ->add('telephone', null, array(
                'label' => 'Numéro de téléphone',
            ))
            ->add('zep', 'checkbox', array(
                'label'  => false,
                'required' => false,
                'help_inline'    => 'Ce lycée est situé dans une ZEP',
            ))
            ->add('vpLycees', null, array(
                'label'       => 'VP Lycées',
                'empty_value' => 'Aucun VP Lycée',
                'empty_data' => null,
            ))
            ->add('referents','entity', array(
                'label' => 'Référents',
                'empty_value'=>'Aucun professeur référent',
                'empty_data' => null,
                'multiple' => true,
                'required'=>false,
                'class' => 'CECMembreBundle:Professeur',
                'query_builder' => function (EntityRepository $entityRepository) use($lycee)
                {
                    return $entityRepository->createQueryBuilder('p')
                                            ->where('p.lycee = :lycee')
                                                ->setParameter('lycee', $lycee)
                                            ->orderBy('p.nom', 'ASC');
                },
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Lycee'
        ));
    }

    public function getName()
    {
        return 'lycee';
    }

    public function __construct (\CEC\TutoratBundle\Entity\Lycee $lycee)
    {
        $this->lycee =$lycee;
    }
}
