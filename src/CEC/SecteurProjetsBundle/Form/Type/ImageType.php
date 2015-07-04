<?php

namespace CEC\SecteurProjetsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', null, array(
            'label' => 'Photo à uploader',
            'label_attr' =>array('class' => 'col-md-3 control-label'),
            'help_inline' => 'La taille de la photo ne peut pas excéder 10 Mo (formats JPEG ou PNG)',
            ))
			->add('alt', null, array(
			'label' => 'Nom de l\'image',
            'label_attr' =>array('class' => 'col-md-3 control-label'),
            'attr' => array('class' => 'form-control col-md-4')
			))
			->add('legende', null, array(
			'label'=>'Legende de l\'image',
            'label_attr' =>array('class' => 'col-md-3 control-label'),
            'attr' => array('class' => 'form-control col-md-4')
			))
			;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurProjetsBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'ImageType';
    }
}
