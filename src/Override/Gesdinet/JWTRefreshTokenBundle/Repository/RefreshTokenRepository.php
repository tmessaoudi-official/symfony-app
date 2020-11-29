<?php

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Repository;

use App\Override\Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Doctrine\ORM\EntityRepository;
use DateTime;

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
            ->getResult();
    }
}
