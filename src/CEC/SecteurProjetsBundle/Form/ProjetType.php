<?php

namespace CEC\SecteurProjetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array(
			'label' => 'Nom du projet',
			'attr' => array('placeholder' => 'Nom du projet')
			))
            ->add('description', 'textarea', array(
			'label' => 'Description publique du projet',
			'attr' => array('rows' => '6', 'placeholder' => 'Description du projet')
			))
            ->add('description_courte', null, array(
			'label' => 'Description courte du projet (Max 255 caractères)',
			'attr' => array('placeholder' => 'Description courte')
			))
            ->add('dateDebut', 'datetime', array(
			'widget'=> 'choice',
			'label' => 'Date du départ du projet'
			))
            ->add('dateFin', 'datetime', array(
			'widget' => 'choice',
			'label' => 'Estimation de la date de retour'
			))
			->add('lieu', null, array(
			'label' => 'Lieu où se déroule le projet',
			'attr' => array('placeholder' => 'Lieu où se déroule le projet')
			))
			->add('contacts', null, array(
			'label' => 'Contacts concernant le projet',
			'required'=>false,
			))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurProjetsBundle\Entity\Projet',
        ));
    }

    public function getName()
    {
        return 'ProjetType';
    }
}
