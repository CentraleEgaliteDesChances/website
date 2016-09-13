<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DossierInscriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('professionPere','text',array(
                'required' =>false,
                'label' => 'Profession du père'
            ))
            ->add('professionMere','text',array(
                'required' =>false,
                'label' => 'Profession de la mère'
            ))
            ->add('telephoneParent',null,array(
                'required' =>false,
                'label' => 'Téléphone où joindre un des parents'
            ))
            ->add('mailParent', null, array(
                'label' => 'Email où joindre un des parents'
            ))
            ->add('statutParents','choice', array(
                'choices' => array(
                    'Mariés' => 'Mariés',
                    'Divorcés' => 'Divorcés',
                    'Concubinage' => 'Concubinage',
                    'Famille Monoparentale' => 'Famille Monoparentale'
                ),
                'label' => 'Statut des parents'
            ))
            ->add('nombrePersonnesACharge',null, array(
                'label' => 'Nombre de personnes à charge'
            ))
            ->add('nombreEnfants',null,array(
                'label' => 'Nombre d\'enfants'
            ))
            ->add('enfants','textarea', array(
                'required' =>false,
                'empty_data'=> 'Pas d\'enfants à charge',
                'label' => 'Prénom, âge, lieu et niveau d\'études de chaque enfant (Un enfant par ligne)'
            ))
            ->add('bourses',null,array(
                'required' =>false,
                'empty_data'=> 'Pas de bourses',
                'label' => 'Es-tu titulaire d\'une bourse ? Si oui, lesquelles ?'
            ))
            ->add('raisonInscriptionCecParticipeAuProgramme','checkbox',array(
                'label' => 'J\'ai déjà participé au programme',
                'required' =>false
            ))
            ->add('nombreAnneeChezCec','choice',array(
                'label' => 'Cette année sera ta combien-tième année à CEC ?',
                'choices' => array(
                    '1ère année' => '1ère année',
                    '2ème année' => '2ème année',
                    '3ème année' => '3ème année'
                )
            ))
            ->add('raisonInscriptionCecEncourageParProche','checkbox',array(
                'label' => 'J\'ai été encouragé par un proche',
                'required' =>false
            ))
            ->add('procheQuiAEncouragePourCec',null,array(
                'required' =>false,
                'empty_data'=> 'Case non cochée',
                'label' => 'Si tu as été encouragé par un proche, lequel ?'
            ))
            ->add('raisonInscriptionCecCuriosite','checkbox', array(
                'label' => 'La curiosité',
                'required' =>false
            ))
            ->add('raisonInscriptionCecProgrammeEducatif','checkbox', array(
                'label' => 'Le programme éducatif',
                'required' =>false
            ))
            ->add('raisonInscriptionCecSortiesProjets','checkbox',array(
                'label' => 'Les sorties et les projets',
                'required' =>false
            ))
            ->add('raisonInscriptionCecLycee','checkbox', array(
                'label' => 'Mon lycée',
                'required' =>false
            ))
            ->add('opinionPresentationCec',null,array(
                'required' =>false,
                'label' => 'Considères-tu que les présentations de CEC ( au lycée, aux parents, etc.) étaient suffisantes et assez claires ? Si non, pourquoi ?'
            ))
            ->add('participationForumOrientation',null,array(
                'required' =>false,
                'label' => 'As-tu déjà participé à un forum d’orientation ? Si oui, lesquels ?',
                'empty_data' => "Non"
            ))
            ->add('connaissanceFiliereApresBac',null,array(
                'required' =>false,
                'label' => 'Connais-tu les différentes filières possibles après le BAC (IUT,BTS,...) ?',
                'empty_data' => "Non"
            ))
            ->add('ideeOrientationPostBac',null,array(
                'required' =>false,
                'label' => false

            ))
            ->add('ideeMetier',null,array(
                'required' =>false,
                'label' => false
            ))
            ->add('aisanceOral','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('aisanceSystemeScolaire','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('capaciteObtentionEtudesSouhaitees','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('encouragementParEntourageEtudeSup','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('attachementActualites','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('interetScience','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('interetCulture','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('integrationGroupe','choice',array(
                'choices' => array(
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'label' => false
            ))
            ->add('activitesExtrascolaires',null, array(
                'label' => 'Quels sont tes principaux loisirs et tes activités extra-scolaires ?',
                'required' => false
            ))
            ->add('pratiqueMusee','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                )
            ))
            ->add('pratiqueTheatre','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                )
            ))
            ->add('pratiqueCinema','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  an' => 'Moins d\'1 fois /  an',
                    '1-2 fois / an' => '1-2 fois / an',
                    'Assez souvent' => 'Assez souvent'
                )
            ))
            ->add('pratiqueJournalTelevise','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  semaine' => 'Moins d\'1 fois /  semaine',
                    '1-2 fois / semaine' => '1-2 fois / semaine',
                    'Tous les jours' => 'Tous les jours'
                )
            ))
            ->add('pratiqueJournaux','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'Moins d\'1 fois /  semaine' => 'Moins d\'1 fois /  semaine',
                    '1-2 fois / semaine' => '1-2 fois / semaine',
                    'Tous les jours' => 'Tous les jours'
                )
            ))
            ->add('pratiqueLecture','choice',array(
                'choices' => array(
                    'Jamais' => 'Jamais',
                    'De temps en temps' => 'De temps en temps',
                    'Souvent' => 'Souvent',
                    'Tous les jours' => 'Tous les jours'
                )
            ))

            ->add('coursParticulier',null,array(
                'required' =>false,
                'label' => 'Prends-tu des cours particuliers ? Si oui, en quoi ?',
                'empty_data' => "Non"
            ))

            ->add('langueVivante',null, array(
               'label' => 'Quelles sont tes langues vivantes au lycée ?',
                'required' => false
            ))
            ->add('correspondantEtranger','textarea',array(
                'required' =>false,
                'label' => 'As tu déjà eu un correspondant étranger ?',
                'empty_data'=> 'Non',
            ))
            ->add('voyagesRealises','textarea',array(
                'required' =>false,
                'empty_data'=> 'Non rempli',
                'label' => 'As-tu déjà visité d\'autres pays ? combien ? lesquels ? dans quels cadres ? (familles colonies, école, stages linguistiques...) ? avec quelle régularité voyages-tu ?'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\DossierInscription'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cec_membrebundle_dossierinscription';
    }
}
