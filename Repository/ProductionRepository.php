<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Repository;

use Bkstg\CoreBundle\Entity\Production;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;

class ProductionRepository extends EntityRepository
{
    /**
     * Finds *all* open productions.
     *
     * @return Query The query finding open productions.
     */
    public function findAllOpenQuery(): AbstractQuery
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            // Add conditions.
            ->andWhere($qb->expr()->eq('p.active', ':active'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('p.expiry'),
                $qb->expr()->gt('p.expiry', ':now')
            ))

            // Add parameters.
            ->setParameter('active', true)
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
                $qb->expr()->eq('p.active', ':active'),
                $qb->expr()->lte('p.expiry', ':now')
            ))

            // Add parameters.
            ->setParameter('active', false)
            ->setParameter('now', new \DateTime())

            // Order by and get results.
            ->orderBy('p.name', 'ASC')
            ->getQuery();
    }
}
