<?php

namespace CEC\ActiviteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fichierOriginal', null, array(
                'label' => false,
                'help_inline' => 'La taille du document ne peut excéder 1 Mo, et les formats acceptés sont les formats Microsoft Word et Microsoft PowerPoint (.doc, .docx, .ppt, .pptx).',
            ))
            ->add('fichierPDF', null, array(
                    'label' => false,
                    'help_inline' => 'La taille du document ne peut excéder 1 Mo, et les formats acceptés sont les formats Adobe PDF (.pdf).',
            ))
            ->add('description', null, array(
                    'label' => 'Description des changements',
                    'help_inline' => 'Indiquez brièvement les modifications apportées par rapport à la version précédente. Ce champ ne peut excéder 255 caractères.',
                    'attr' => array('class' => 'input-block-level'),
            ));
    }
    
    public function getName()
    {
        return 'Document';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\ActiviteBundle\Entity\Document',
        ));
    }
}
