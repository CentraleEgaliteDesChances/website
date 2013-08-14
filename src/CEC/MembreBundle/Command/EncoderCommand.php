<?php

namespace CEC\MembreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CEC\MembreBundle\Entity\Membre;

class EncoderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('membre:encoder')
            ->setDescription('Encode un mot de passe')
            ->addArgument('mot_de_passe', InputArgument::REQUIRED, 'Entrez le mot de passe à encoder')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $motDePasse = $input->getArgument('mot_de_passe');
        $factory = $this->getContainer()->get('security.encoder_factory');
        $membre = new Membre();
        $encoder = $factory->getEncoder($membre);
        
        // Encodage de mot de passe
        $motDePasseEncode = $encoder->encodePassword($motDePasse, $membre->getSalt());

        $output->writeln(
            "<info>Mot de passe encodé :</info> " .
            $motDePasseEncode . "\r\n"
        );
    }
}
