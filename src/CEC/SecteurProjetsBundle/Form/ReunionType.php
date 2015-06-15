<?php

namespace CEC\SecteurProjetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReunionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array(
			'label' => 'Nom de la réunion',
			'attr' => array('placeholder' => 'Nom de la réunion')
			))
            ->add('date', 'date', array(
			'widget' => 'choice',
			'label' => 'Date de la réunion'
			))
            ->add('heureDebut', 'time', array(
			'widget'=>'choice',
			'label' => 'Heure de début de la réunion'
			))
            ->add('heureFin', 'time', array(
			'widget' => 'choice',
			'label' => 'Heure estimée de fin de la réunion'
			))
            ->add('adresse', null, array(
			'label' => 'Lieu de la réunion'
			))
            ->add('description', null, array(
			'label' => 'Courte description de la réunion (255 caractères max)'
			))
			->add('projet', null, array(
			'data_class' => 'CEC\SecteurProjetsBundle\Entity\Projet',
			'expanded' => true,
			'label' => 'Projet concerné',
			'property' => 'nom',
			'required' => true
			))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurProjetsBundle\Entity\Reunion'
        ));
    }

    public function getName()
    {
        return 'Reunion';
    }
}
