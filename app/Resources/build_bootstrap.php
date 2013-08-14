#!/usr/bin/env php
<?php

/*
 * This file is part of the Symfony Standard Edition.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 ** Ce fichier a été modifié pour tenir compte de l'impossibilité d'exécuter PHP 5.4 en CLI
 ** sur les serveurs mutualisés de 1and1.
 ** 
 ** Lors du déploiement du site de CEC, il faut utiliser
 **     php6 composer.phar install --no-script
 ** puis executer
 **     php6 app/Resources/build_bootstrap.php
 **
 ** A supprimer lors de la mise à jour de symfony 2.1 vers symfony 2.3.
 */

if (PHP_SAPI !== 'cli') {
    echo __FILE__.' should be invoked via the CLI version of PHP, not the '.PHP_SAPI.' SAPI'.PHP_EOL;
    exit(1);
}

$argv = $_SERVER['argv'];

// allow the base path to be passed as the first argument, or default
if (isset($argv[1])) {
    $appDir = $argv[1];
} else {
    if (!$appDir = realpath(__DIR__.'/../../../../../../../../app')) {
        exit('Looks like you don\'t have a standard layout.');
    }
}

require_once $appDir.'/autoload.php';

\Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::doBuildBootstrap($appDir);
