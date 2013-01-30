<?php

namespace CEC\MembreBundle\Entity\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * NomUtilisateurInvalideException est utilisé lorsque le nom d'utilisateur fourni
 * ne resepcte pas les exigences d'un nom d'utilisateur.
 *
 * @author Jean-Baptiste Bayle <jean-baptiste.bayle@student.ecp.fr>
 */
class NomUtilisateurInvalideException extends AuthenticationException
{
}
