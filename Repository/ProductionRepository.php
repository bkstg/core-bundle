<?php

namespace Bkstg\CoreBundle\Repository;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Entity\User;
use Bkstg\CoreBundle\User\UserInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * ProductionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductionRepository extends EntityRepository
{
    /**
     * Finds *all* open productions.
     *
     * These results are not filtered and, therefore, should only be available
     * to administrators.
     *
     * @return Production[]
     */
    public function findAllOpen()
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
            ->getQuery()
            ->getResult();
    }

    public function findAllOpenPublic()
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
            // Add conditions.
            ->andWhere($qb->expr()->eq('p.status', ':status'))
            ->andWhere($qb->expr()->eq('p.visibility', ':visibility'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('p.expiry'),
                $qb->expr()->gt('p.expiry', ':now')
            ))

            // Add parameters.
            ->setParameter('status', true)
            ->setParameter('visibility', Production::VISIBILITY_PUBLIC)
            ->setParameter('now', new \DateTime())

            // Order by and get results.
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds *all* closed productions.
     *
     * These results are not filtered and, therefore, should only be available
     * to administrators.
     *
     * @return Production[]
     */
    public function findAllClosed()
    {
        // Build criteria.
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('status', false))
            ->orWhere(Criteria::expr()->lte('expiry', new \DateTime()));
        return $this->matching($criteria);
    }
}
