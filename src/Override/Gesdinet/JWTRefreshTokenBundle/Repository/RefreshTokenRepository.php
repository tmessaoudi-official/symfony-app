<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Repository;

use App\Override\Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use DateTime;
use Doctrine\ORM\EntityRepository;

class RefreshTokenRepository extends EntityRepository
{
    /**
     * @param null $datetime
     *
     * @return RefreshToken[]
     */
    public function findInvalid($datetime = null)
    {
        $datetime = (null === $datetime) ? new DateTime() : $datetime;

        return $this->createQueryBuilder('u')
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
            ->getQuery()
            ->getResult()
        ;
    }
}
