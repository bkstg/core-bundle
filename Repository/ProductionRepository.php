<?php

namespace Bkstg\CoreBundle\Repository;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Entity\User;
use Bkstg\CoreBundle\User\UserInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductionRepository extends EntityRepository
{
    /**
     * Finds *all* open productions.
     *
     * @return Query The query finding open productions.
     */
    public function findAllOpenQuery(): Query
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            // Add conditions.
            ->andWhere($qb->expr()->eq('p.status', ':status'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('p.expiry'),
                $qb->expr()->gt('p.expiry', ':now')
            ))

            // Add parameters.
            ->setParameter('status', true)
            ->setParameter('now', new \DateTime())

            // Order by and get results.
            ->orderBy('p.name', 'ASC')
            ->getQuery();
    }

    /**
     * Finds *all* closed productions.
     *
     * @return Production[]
     */
    public function findAllClosedQuery()
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            // Add conditions.
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('p.status', ':status'),
                $qb->expr()->lte('p.expiry', ':now')
            ))

            // Add parameters.
            ->setParameter('status', false)
            ->setParameter('now', new \DateTime())

            // Order by and get results.
            ->orderBy('p.name', 'ASC')
            ->getQuery();
    }
}
