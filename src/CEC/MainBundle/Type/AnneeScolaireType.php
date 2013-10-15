<?php

namespace CEC\MainBundle\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


/**
 * Type de donnée "Année Scolaire".
 * Utilise la classe AnneeScolaire pour n'enregistrer que l'année inférieure
 * dans la base de donnée (utile pour les comparaisons).
 */
class AnneeScolaireType extends Type
{
    const ANNEE_SCOLAIRE_TYPE = 'anneescolaire';
    
    public function getName() {
        return self::ANNEE_SCOLAIRE_TYPE;
    }
    
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        return $platform->getDoctrineTypeMapping('INT');
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) return null;
        return new AnneeScolaire($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : $value->getAnneeInferieure();
    }
}
