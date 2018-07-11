<?php

namespace Bkstg\CoreBundle\Search\EventSubscriber;

use Bkstg\SearchBundle\Event\FilterCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FilterCollectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FilterCollectionEvent::NAME => [
                ['addProductionFilter', 0],
            ]
        ];
    }

    public function addProductionFilter(FilterCollectionEvent $event)
    {
        $now = new \DateTime();
        $qb = $event->getQueryBuilder();
        $query = $qb->query()->bool()
            ->addMust($qb->query()->term(['_type' => 'production']))
            ->addMust($qb->query()->term(['active' => true]))
            ->addMust($qb->query()->terms('id', $event->getGroupIds()))
            ->addMust($qb->query()->bool()
                ->addShould($qb->query()->range('expiry', ['gt' => $now->format('U') * 1000]))
                ->addShould($qb->query()->constant_score()->setParam('filter', ['missing' => ['field' => 'expiry']]))
            )
        ;
        $event->addFilter($query);
    }
}
