<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Search\EventSubscriber;

use Bkstg\SearchBundle\Event\FilterCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FilterCollectionSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FilterCollectionEvent::NAME => [
                ['addProductionFilter', 0],
            ],
        ];
    }

    /**
     * Add the production filter to the main query.
     *
     * @param FilterCollectionEvent $event The filter collection event.
     *
     * @return void
     */
    public function addProductionFilter(FilterCollectionEvent $event): void
    {
        $now = new \DateTime();
        $qb = $event->getQueryBuilder();
        $query = $qb->query()->bool()
            ->addMust($qb->query()->term(['_type' => 'production']))
            ->addMust($qb->query()->term(['active' => true]))
            ->addMust($qb->query()->terms('id', $event->getGroupIds()))
            ->addMust($qb->query()->bool()
                ->addShould($qb->query()->range('expiry', ['gt' => $now->format('U') * 1000]))
                ->addShould($qb->query()->constant_score()->setParam('filter', ['missing' => ['field' => 'expiry']])));
        $event->addFilter($query);
    }
}
